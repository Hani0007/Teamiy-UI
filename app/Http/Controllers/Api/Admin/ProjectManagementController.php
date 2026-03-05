<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\FetchMembersRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectDetailsResource;
use App\Http\Resources\ProjectManagementResource;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Repositories\AttachmentRepository;

class ProjectManagementController extends Controller
{
    public function __construct(protected AttachmentRepository $attachmentRepo)
    {}

    public function index(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $query = Project::query()
                ->where('branch_id', $request->branch_id)
                ->when($request->filled('department_ids'), function ($q) use ($request) {
                    $q->where(function ($subQuery) use ($request) {
                        foreach ($request->department_ids as $departmentId) {
                            $subQuery->orWhereJsonContains('department_ids', (string) $departmentId);
                        }
                    });
                })
                ->withoutGlobalScope('branch')
                ->with([
                    'branch:id,name',
                    'projectLeaders.user:id,name',
                    'assignedMembers.user:id,name',
                    'projectAttachments'
                ]);

            $projects = $query->orderByDesc('id')->paginate(Project::RECORDS_PER_PAGE);

            return response()->json([
                'message' => __('success'),
                'data' => ProjectManagementResource::collection($projects),
            ], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(StoreProjectRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();
            $validatedData['created_by'] = $user->id;

            DB::beginTransaction();

            if ($request->filled('project_id')) {
                $project = Project::withoutGlobalScope('branch')->find($request->project_id);

                if (!$project) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $project->update($validatedData);

                if(isset($validatedData['attachments'])){
                    $project->projectAttachments()->delete();
                }

                if(isset($validatedData['attachments'])){
                    $projectFiles = $this->attachmentRepo->prepareAttachmentData($validatedData['attachments']);
                    $this->attachmentRepo->saveProjectAttachment($project, $projectFiles);
                }

                $message = __('updated_success');
            }
            else {
                $project = Project::create($validatedData);

                if(isset($validatedData['attachments'])){
                    $projectFiles = $this->attachmentRepo->prepareAttachmentData($validatedData['attachments']);
                    $this->attachmentRepo->saveProjectAttachment($project, $projectFiles);
                }

                $message = __('created_success');
            }

            if (isset($validatedData['project_leader'])) {
                DB::table('project_team_leaders')
                    ->where('project_id', $project->id)
                    ->delete();

                $insertData = collect($validatedData['project_leader'])
                    ->map(fn($leaderId) => [
                        'project_id' => $project->id,
                        'leader_id'  => $leaderId
                    ])->toArray();

                DB::table('project_team_leaders')->insert($insertData);
            }

            if (isset($validatedData['assigned_member'])) {
                $project->assignedMembers()->delete();

                foreach ($validatedData['assigned_member'] as $memberId) {
                    $project->assignedMembers()->create([
                        'member_id' => $memberId,
                    ]);
                }
            }

            if (!empty($validatedData['assigned_member'])) {
                $employeeNames = User::whereIn('id', $validatedData['assigned_member'])
                    ->where('is_active', 1)
                    ->pluck('username')
                    ->toArray();

                SMPushHelper::sendPushNotification(
                    __('project_assign') . $validatedData['name'],
                    '',
                    $validatedData['description'] ?? '',
                    __('project_assigned'),
                    $employeeNames,
                    ''
                );
            }

            DB::commit();

            $project->load(['assignedMembers.user', 'projectLeaders.user', 'branch', 'creator', 'projectAttachments']);

            return response()->json([
                'message' => $message,
                'data'    => new ProjectManagementResource($project)
            ], 200);

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function detail($id)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $project = Project::withoutGlobalScope('branch')->with('tasks', 'assignedMembers.user:id,name')->find($id);

            if (isset($project)) {
                return response()->json([
                    'message' => __('success'),
                    'data'    => new ProjectDetailsResource($project)
                ], 200);
            }

            return response()->json([
                'message' => __('record_not_found'),
                'data'    => []
            ], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function delete($id)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = Project::withoutGlobalScope('branch')->where('id', $id)->first();

            if($rec)
            {
                $rec->delete();

                return response()->json([
                    'message' => __('record_deleted'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function fetchMembers(FetchMembersRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();

            $branchId = $validatedData['branch_id'];
            $departmentIds = $validatedData['department_ids'];

            $employees = User::select('id', 'name', 'branch_id', 'department_id')->where('branch_id', $branchId)
                ->whereIn('department_id', $departmentIds)
                ->where('is_active', 1)
                ->get();

            return response()->json([
                'message' => __('success'),
                'data' => $employees,
            ], 200);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = Project::withoutGlobalScope('branch')->where('id', $request->id)->first();

            if($rec)
            {
                $rec->update(['status' => $request->status]);

                return response()->json([
                    'message' => __('updated_success'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function projects()
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            if ($user->hasRole('super-admin')) {
                $company = $user->company()->first();
            } else {
                $company = Company::where('admin_id', $user->parent_id)->first();
            }

            // if (!$company) {
            //     return response()->json(['message' => __('company_not_found')], 404);
            // }

            $branchIds = Branch::where('company_id', $company->id)->pluck('id');
            $projects = Project::withoutGlobalScope('branch')->whereIn('branch_id', $branchIds)
            ->with([
                'branch:id,name',
                'projectLeaders.user:id,name',
                'assignedMembers.user:id,name',
            ])
            ->get();

            return response()->json([
                'message' => __('success'),
                'data' => ProjectManagementResource::collection($projects),
            ], 200);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}

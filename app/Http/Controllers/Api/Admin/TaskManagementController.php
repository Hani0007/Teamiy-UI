<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskFilterRequest;
use App\Http\Requests\TaskManagementApiRequest;
use App\Http\Resources\ProjectAssignedMembersResource;
use App\Http\Resources\TaskListingResource;
use App\Http\Resources\TaskManagementResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\AttachmentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskManagementController extends Controller
{
    public function __construct(protected AttachmentRepository $attachmentRepo)
    {}

    public function projectAssignedMembers($id)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $project = Project::withoutGlobalScope('branch')->with('assignedMembers.user')->findOrFail($id);

            if(isset($project))
            {
                return response()->json([
                    'message' => __('success'),
                    'data'    => ProjectAssignedMembersResource::collection($project->assignedMembers)
                ], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(TaskManagementApiRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $validatedData = $request->validated();
            $validatedData['created_by'] = $user->id;
            // DOCUMENT UPLOAD
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $filename = 'task_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path('uploads/documents/tasks');

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $file->move($path, $filename);

                $validatedData['document'] = $filename;
            }

            DB::beginTransaction();

            if ($request->filled('task_id')) {
                $task = Task::find($request->task_id);

                if (!$task) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $task->update($validatedData);

                if(isset($validatedData['attachments'])){
                    $task->taskAttachments()->delete();
                }

                if (isset($validatedData['attachments'])) {
                    $taskAttachmentValidatedData = $this->attachmentRepo->prepareAttachmentData($validatedData['attachments']);
                    $this->attachmentRepo->saveTaskAttachment($task, $taskAttachmentValidatedData);
                }

                $message = __('updated_success');
            } else {
                $task = Task::create($validatedData);

                if (isset($validatedData['attachments'])) {
                    $taskAttachmentValidatedData = $this->attachmentRepo->prepareAttachmentData($validatedData['attachments']);
                    $this->attachmentRepo->saveTaskAttachment($task, $taskAttachmentValidatedData);
                }

                $message = __('created_success');
            }

            if (isset($validatedData['assigned_member'])) {
                $task->assignedMembers()->delete();

                foreach ($validatedData['assigned_member'] as $memberId) {
                    $task->assignedMembers()->create([
                        'member_id' => $memberId,
                    ]);
                }
            }

            $employeeNames = User::whereIn('id', $validatedData['assigned_member'])->where('is_active', 1)->pluck('username')->toArray();

            SMPushHelper::sendPushNotification(__('task_assing'). $validatedData['name'], '', $validatedData['description'] ?? '', __('task_assigned'), $employeeNames, '');

            DB::commit();
            $task->load(['assignedMembers.user', 'creator', 'taskAttachments']);

            return response()->json([
                'message' => $message,
                'data'    => new TaskManagementResource($task)
            ], 200);

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }

    }

    public function fetch(TaskFilterRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $filters = $request->validated();

            $query = Task::query()
                ->when($filters['project_id'] ?? null, fn($q, $branch) => $q->where('project_id', $branch))
                ->when($filters['priority'] ?? null, fn($q, $priority) => $q->where('priority', $priority))
                ->when($filters['status'] ?? null, fn($q, $status) => $q->where('status', $status))
                ->withoutGlobalScope('branch')
                ->with([
                    'assignedMembers.user:id,name',
                    'creator:id,name',
                    'taskAttachments'
                ]);

            $tasks = $query->orderByDesc('id')->get();

            if ($tasks->isNotEmpty()) {
                return response()->json([
                    'message' => __('success'),
                    'data'    => TaskListingResource::collection($tasks),
                ], 200);
            }

            return response()->json([
                'message' => __('record_not_found'),
                'data'    => []
            ], 404);

        } catch (Exception $ex) {
            DB::rollBack();
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

            $rules = [
                'id'     => 'required|integer|exists:tasks,id',
                'status' => 'required|string|in:pending,in_progress,completed,blocker',
            ];

            if ($request->status === 'blocker') {
                $rules['reason'] = 'required|string|max:500';
            }

            $messages = [
                'id.required'     => __('task_id_required'),
                'id.integer'      => __('task_id_must_be_number'),
                'id.exists'       => __('task_not_found'),
                'status.required' => __('task_status_required'),
                'status.in'       => __('task_status_invalid'),
                'reason.required' => __('reason_required_for_blocker'),
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'message' => __('validation_failed'),
                ], 422);
            }

            $rec = Task::find($request->id);

            if ($rec) {
                if ($request->status === 'blocker') {
                    $rec->update([
                        'status' => $request->status,
                        'reason' => $request->reason,
                    ]);
                } else {
                    $rec->update(['status' => $request->status]);
                }

                return response()->json([
                    'message' => __('updated_success'),
                ], 200);
            }

            return response()->json(['message' => __('record_not_found')], 404);

        } catch (Exception $ex) {
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

            $rec = Task::where('id', $id)->first();

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

    public function projectWiseAnalysis(Request $request)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $projectId = $request->input('project_id');
            $employeeId = $request->input('employee_id');

            $tasks = Task::where('project_id', $projectId)
                ->whereHas('assignedMembers', function ($q) use ($employeeId) {
                    $q->where('member_id', $employeeId)
                    ->where('assignable_type', 'task');
                })
                ->get();

            if($tasks->isEmpty())
            {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            $counts = [
                'to_do'       => $tasks->where('status', 'not_started')->count(),
                'in_progress' => $tasks->where('status', 'in_progress')->count(),
                'completed'   => $tasks->where('status', 'completed')->count(),
                'cancelled'   => $tasks->where('status', 'cancelled')->count(),
                'total'       => $tasks->count(),
            ];

            return response()->json([
                'message' => __('success'),
                'data'    => [
                    'analysis report'   => $counts,
                    'tasks'             => $tasks
                ]
            ], 200);

        } catch(Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function analysisReport($id)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $endDate = now();
            $startDate = now()->subDays(30);

            $tasks = Task::whereBetween('start_date', [$startDate, $endDate])
                ->whereHas('assignedMembers', function ($q) use ($id) {
                    $q->where('member_id', $id)
                    ->where('assignable_type', 'task');
                })
                ->get();

            if ($tasks->isEmpty()) {
                return response()->json(['message' => __('record_not_found')], 404);
            }

            $counts = [
                'to_do'       => $tasks->where('status', 'not_started')->count(),
                'in_progress' => $tasks->where('status', 'in_progress')->count(),
                'completed'   => $tasks->where('status', 'completed')->count(),
                'cancelled'   => $tasks->where('status', 'cancelled')->count(),
                'total'       => $tasks->count(),
            ];

            $counts['overdue'] = $tasks->filter(function ($task) {
                return $task->end_date && Carbon::parse($task->end_date)->isPast()
                    && $task->status !== 'completed';
            })->count();

            return response()->json([
                // 'message'    => 'success',
                'message'    => __('success'),
                'data'     => $counts
            ]);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }
}

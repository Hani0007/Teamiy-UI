<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiTeamMeetingRequest;
use App\Http\Resources\TeamMeetingResource;
use App\Models\Company;
use App\Models\TeamMeeting;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamMeetingController extends Controller
{
    public function index(Request $request)
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

            $teamMeetings = TeamMeeting::with('company', 'admin', 'branch', 'teamMeetingParticipator.participator')
                                ->where('company_id', $company->id)
                                ->where('branch_id', $request->branch_id)
                                ->get();

            return response()->json([
                // 'message' => 'success',
                'message' => __('success'),
                'data'    => TeamMeetingResource::collection($teamMeetings),
            ]);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(ApiTeamMeetingRequest $request)
    {
        $user = auth()->guard('admin-api')->user();

        try {
            if (!$user) {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $company = $user->hasRole('super-admin')
                ? $user->company()->first()
                : Company::where('admin_id', $user->parent_id)->first();

            $validatedData = $request->validated();
            $validatedData['created_by'] = $user->id;
            $validatedData['company_id'] = $company->id;

            DB::beginTransaction();

            if ($request->filled('team_meeting_id')) {
                $teamMeeting = TeamMeeting::find($request->team_meeting_id);

                if (!$teamMeeting) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                if ($request->hasFile('image')) {
                    if ($teamMeeting->image && file_exists(public_path($teamMeeting->image))) {
                        @unlink(public_path($teamMeeting->image));
                    }

                    $image = $request->file('image');
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/team-meetings'), $imageName);

                    $validatedData['image'] = 'uploads/team-meetings/' . $imageName;
                }

                $teamMeeting->update($validatedData);
                $message = __('updated_success');
            } else {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/team-meetings'), $imageName);

                    $validatedData['image'] = 'uploads/team-meetings/' . $imageName;
                }

                $teamMeeting = TeamMeeting::create($validatedData);
                $message = __('created_success');
            }

            if ($request->filled('meeting_participants') && is_array($request->meeting_participants)) {
                $participantData = collect($request->meeting_participants)->map(function ($participantId) {
                    return ['meeting_participator_id' => $participantId];
                })->toArray();

                $teamMeeting->teamMeetingParticipator()->delete();
                $teamMeeting->teamMeetingParticipator()->createMany($participantData);

                $users = User::whereIn('id', $validatedData['meeting_participants'])->where('is_active', 1)->get();

                $names = $users->pluck('username')->toArray();
                SMPushHelper::sendPushNotification($validatedData['title'], '', $validatedData['description'] ?? '', 'Team Meeting', $names, '');
            }

            DB::commit();

            $teamMeeting->load(['company', 'admin', 'branch', 'teamMeetingParticipator']);

            return response()->json([
                'message' => $message,
                'data'    => new TeamMeetingResource($teamMeeting),
            ]);

        } catch (Exception $ex) {
            DB::rollBack();
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

            $rec = TeamMeeting::where('id', $id)->first();

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
}

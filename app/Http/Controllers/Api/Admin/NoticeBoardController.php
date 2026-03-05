<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiNoticeBoard;
use App\Http\Resources\NoticeBoardResource;
use App\Models\Company;
use App\Models\Notice;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticeBoardController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {}

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

            // if (!$company) {
            //     return response()->json(['message' => __('company_not_found')], 404);
            // }

            $noticeBoards = Notice::with('company', 'admin', 'branch', 'noticeReceiversDetail')
                                ->where('company_id', $company->id)
                                ->where('branch_id', $request->branch_id)
                                ->get();

            return response()->json([
                // 'message' => 'success',
                'message' => __('success'),
                'data'    => NoticeBoardResource::collection($noticeBoards),
            ]);

        } catch (Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    public function store(ApiNoticeBoard $request)
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

            $validatedData = $request->validated();
            $validatedData['created_by'] = $user->id;
            $validatedData['company_id'] = $company->id;

             DB::beginTransaction();

            if ($request->filled('noticeboard_id')) {
                $noticeBoard = Notice::find($request->noticeboard_id);

                if (!$noticeBoard) {
                    return response()->json(['message' => __('record_not_found')], 404);
                }

                $noticeBoard->update($validatedData);
                $message = __('updated_success');
            } else {
                $noticeBoard = Notice::create($validatedData);
                $message = __('created_success');
            }

            if ($request->filled('notice_receivers') && is_array($request->notice_receivers)) {
                $receiverData = collect($request->notice_receivers)->map(function ($receiverId) {
                    return ['notice_receiver_id' => $receiverId];
                })->toArray();

                $noticeBoard->noticeReceiversDetail()->delete();
                $noticeBoard->noticeReceiversDetail()->createMany($receiverData);

                $users = User::whereIn('id', $validatedData['notice_receivers'])->where('is_active', 1)->get();

                $names = $users->pluck('username')->toArray();
                //$this->sendNotification($taskId, $remainingEmployeeIds, $changeMessage);
                SMPushHelper::sendPushNotification($validatedData['title'], '', $validatedData['description'] ?? '', 'Notice', $names, '');
            }

            DB::commit();
            $noticeBoard->load(['company', 'admin', 'branch', 'noticeReceiversDetail']);

            return response()->json([
                'message' => $message,
                'data'    => new NoticeBoardResource($noticeBoard),
            ]);

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 400);
        }
    }

    // private function sendNotification($taskId, $userIds, $message){
    //     $notificationData['title'] = __('message.task_notification');
    //     $notificationData['type'] = 'task';
    //     $notificationData['user_id'] = $userIds;
    //     $notificationData['description'] = $message;
    //     $notificationData['notification_for_id'] = $taskId;
    //     $notification = $this->notificationService->store($notificationData);
    //     if($notification){
    //         SMPushHelper::sendNoticeNotification($notification->title,
    //             $notification->description,
    //             $notificationData['user_id'],
    //             $taskId);
    //     }
    // }

    // private function sendNoticeNotification($title, $description, $userIds)
    // {
    //     SMPushHelper::sendNoticeNotification($title, $description, $userIds);
    // }

    public function delete($id)
    {
        $user = auth()->guard('admin-api')->user();

        try{
            if(!$user)
            {
                return response()->json(['message' => __('unauthorized_access')], 401);
            }

            $rec = Notice::where('id', $id)->first();

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

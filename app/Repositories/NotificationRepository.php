<?php

namespace App\Repositories;

use App\Helpers\AppHelper;
use App\Models\Company;
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Support\Carbon;

class NotificationRepository
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    const SEEN = 1;
    const UNSEEN = 0;

    public function getAllCompanyNotifications($filterParameters,$select=['*'],$with=[])
    {
        // $branchId = null;
        // $authUserId = null;
        // if(auth()->user()){
        //     $branchId = auth()->user()->branch_id;
        //     $authUserId = auth()->user()->id;
        // }

        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            $company = $user->company()->first();
        } else {
            $company = Company::where('admin_id', $user->parent_id)->first();
        }

        return Notification::select($select)->with($with)
            ->when(isset($filterParameters['type']), function($query) use ($filterParameters){
                $query->where('type',$filterParameters['type']);
            })
            // ->when(isset($branchId) && $authUserId != 1, function($query) use ($branchId){
            //     $query->whereHas('createdBy',function($query) use ($branchId){
            //         $query->where('branch_id', $branchId);
            //     });
            // })
            ->where('company_id', $company->id)
            ->orderBy('notification_publish_date','Desc')
            ->paginate( getRecordPerPage());
    }

    public function getAllCompanyRecentActiveNotification($perPage,$select=['*'])
    {
        $userId  = getAuthUserCode();

        $notifications = Notification::with([
                'notifiedUsers' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->where(function ($query) use ($userId) {

                $query->where(function ($q) use ($userId) {
                        $q->where('notification_for_id', $userId)
                        ->where('is_active', self::ACTIVE)
                        ->whereNotNull('notification_publish_date');
                    })
                    ->orWhereHas('notifiedUsers', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
            })
            ->where('notification_publish_date', '>=', Carbon::now()->subDays(30))
            ->orderBy('notification_publish_date', 'desc')
            ->paginate($perPage);

        return $notifications;
    }

    public function findNotificationDetailById($id,$select=['*'],$with=[])
    {
        return Notification::select($select)
            ->with($with)
            ->where('id',$id)
            ->first();
    }

    public function getNotificationForNavBar($select)
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            $company = $user->company()->first();
        } else {
            $company = Company::where('admin_id', $user->parent_id)->first();
        }

        return Notification::select($select)
            ->where('company_id',AppHelper::getAuthUserCompanyId() )
            ->where('is_active',self::ACTIVE)
            ->where('company_id', $company->id)
            ->latest()
            ->take(5)
            ->get();
    }

    public function store($validatedData)
    {
        return Notification::create($validatedData)->fresh();
    }

    public function update($notificationDetail,$validatedData)
    {
        return $notificationDetail->update($validatedData);
    }

    public function delete($notificationDetail)
    {
        return $notificationDetail->delete();
    }

    public function toggleStatus($id)
    {
        $notificationDetail = $this->findNotificationDetailById($id);
        return $notificationDetail->update([
            'is_active' => !$notificationDetail->is_active,
        ]);
    }

    public function notifyUser($notificationDetail,$usersArray)
    {
        return $notificationDetail->notifiedUsers()->createMany($usersArray);
    }

    public function findUserNotificationDetailById($id,$select)
    {
        return UserNotification::select($select)
            ->where('id',$id)
            ->first();
    }

    public function changeUserNotificationToSeen($userNotificationDetail)
    {
        return $userNotificationDetail->update([
            'is_seen' => self::SEEN
        ]);
    }

}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class DeleteAdminAccount extends Controller
{
    public function index()
    {
        return view('admin.account-delete-form');
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'identifier' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->identifier)->first();

        if ($admin) {
            $deleteUrl = URL::temporarySignedRoute(
                'admin.account.delete',
                now()->addHours(48),
                ['email' => $admin->email]
            );

            Mail::raw("Click the link below to delete your account (valid for 48 hours):\n\n{$deleteUrl}", function ($message) use ($admin) {
                $message->to($admin->email)
                        ->subject('Account Deletion Link');
            });

            return redirect()->back()->with('success', 'Account delete link has been sent successfully on your email address.');
        }

        return response()->json(['message' => __('admin_not_found')], 404);
    }

    // public function deleteAccount(Request $request)
    // {
    //     if (! $request->hasValidSignature()) {
    //         return response()->view('errors.link_expired', [], 401);
    //     }

    //     $email = $request->query('email');

    //     $apiUrl = url('/delete_admin');



    //     return response()->view('errors.link_expired', [], 401);
    // }
}

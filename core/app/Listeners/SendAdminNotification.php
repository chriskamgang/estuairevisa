<?php

namespace App\Listeners;

use App\Models\Admin;
use App\Notifications\AdminNotification;

class SendAdminNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $admin = Admin::first();

        if ($admin) {
            $admin->notify(new AdminNotification([
                'url' => route('admin.user'),
                'type' => 'others',
                'message' => $event->user->username . " has registered"

            ]));
        }
    }
}

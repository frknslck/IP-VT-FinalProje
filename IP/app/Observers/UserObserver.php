<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Role;
use App\Models\Notification;
use App\Models\Coupon;

class UserObserver
{
    
    public function created(User $user): void
    {
        $defaultRole = Role::where('name', 'Customer')->first();

        if ($defaultRole) {
            $user->roles()->attach($defaultRole->id);
        }

        Notification::create([
            'from' => 'System',
            'title' => 'Welcome!',
            'message' => 'Welcome to our website, '.$user->name.'! We hope you find exactly what you\'re looking for. But before you go, we\'ve got a little surprise waiting for you. Don\'t be shy and head over to your Coupons page to check it out, then start exploring our products! 😄 Have a fantastic day!'
        ])->users()->attach($user->id);

        $welcomeCoupon = Coupon::where('code', 'WELCOME5')->first();

        if($welcomeCoupon){
            $welcomeCoupon->users()->attach($user->id);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

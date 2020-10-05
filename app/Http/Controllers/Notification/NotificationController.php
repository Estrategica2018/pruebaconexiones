<?php

namespace App\Http\Controllers\Notification;


use App\Mail\SendPlanExpirationNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    //
    public function plan_expiration(Request $request){

        try {
            Mail::to('cristianjojoa01@gmail.com')->send(
                new SendPlanExpirationNotification());
        } catch (\Exception $ex) {

            return $ex;
        }

        return 1;
    }
}

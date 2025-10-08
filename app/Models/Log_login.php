<?php

namespace App\Models;

use Dflydev\DotAccessData\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Log_login extends Model
{
    protected $guarded = ['id'];

    public static function logUserAttempt($eventName, $eventTime, $email, $status, $statusDesc = null, $extraInfo = null)
    {
      
        try {
            $userAgent = Utility::getUserAgent();
            $clientIp = Utility::getClientIPAddress();
            $systemInfo = Utility::systemInfo();
            $browser = Utility::browser();

            return static::create([
                'user_id' => is_object(Auth::guard(config('app.guards.web'))->user()) ? Auth::guard(config('app.guards.web'))->user()->id : null,
                'email' => $email,
                'event_name' => $eventName,
                'event_time' => $eventTime,
                'status' => $status,
                'status_description' => $statusDesc,
                'ip_address' => $clientIp,
                'device' => $systemInfo['device'],
                'os' => $systemInfo['os'],
                'browser' => $browser,
                'extra_info' => $userAgent,
            ]);
        } catch (\Exception $e) {
            Log::info('logUserAttempt failed : ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class Utility extends Model
{
    static public function generateRandomPassword() {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%*_";
        $password = substr( str_shuffle( $chars ), 0, 8 );      
        return $password;  
    }

    static public function getClientIPAddress($request = null)
    {

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $clientIp = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $clientIp = $forward;
        } else {
            $clientIp = $remote;
        }

        return $clientIp;
    }

    static public function getUserAgent($request = null)
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        return $userAgent;
    }

    static function systemInfo()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osPlatform    = "Unknown OS Platform";
        $osArray       = array(
            '/windows phone 8/i'    =>  'Windows Phone 8',
            '/windows phone os 7/i' =>  'Windows Phone 7',
            '/windows nt 11.0/i'     =>  'Windows 11',
            '/windows nt 10.0/i'     =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );
        $found = false;
        $device = '';
        foreach ($osArray as $regex => $value) {
            if ($found)
                break;
            else if (preg_match($regex, $userAgent)) {
                $osPlatform    =   $value;
                $device = !preg_match('/(windows|mac|linux|ubuntu)/i', $osPlatform)
                    ? 'MOBILE' : (preg_match('/phone/i', $osPlatform) ? 'MOBILE' : 'SYSTEM');
            }
        }
        $device = !$device ? 'SYSTEM' : $device;
        return array('os' => $osPlatform, 'device' => $device);
    }

    static function browser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $browser        =   "Unknown Browser";

        $browserArray  = array(
            '/msie/i'       =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/edge/i'    =>  'Edge',
            '/edg/i'    =>  'Edge',
            '/opera/i'      =>  'Opera',
            '/opr/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/mobile/i'     =>  'Handheld Browser'
        );

        $found = false;
        foreach ($browserArray as $regex => $value) {
            if ($found)
                break;
            else if (preg_match($regex, $userAgent))
            // else if (preg_match($regex, $userAgent, $result)) 
            {
                $browser    =   $value;
            }
        }
        return $browser;
    }

    static public function encrypt($value)
    {
        try {
            $encrypted = Crypt::encryptString($value);

            return $encrypted;
        } catch (EncryptException $e) {
            // SystemLog::create('Utility-encrypt', Carbon::now(), 'error: ' . $e->getMessage());
            Log::info('Utility-encrypt error : ' . $e->getMessage());
        }
    }

    static public function decrypt($value)
    {
        try {
            $decrypted = Crypt::decryptString($value);

            return $decrypted;
        } catch (DecryptException $e) {
            // SystemLog::create('Utility-decrypt', Carbon::now(), 'error: ' . $e->getMessage());
            Log::info('Utility-decrypt error : ' . $e->getMessage());
        }
    }
}

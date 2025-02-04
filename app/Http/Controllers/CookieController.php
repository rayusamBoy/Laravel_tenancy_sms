<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    // Set cookie - default minutes 1440 = 24hours = 1 day
    public static function set($name, $value, $minutes = 1440, $path = null, $domain = null, $secure = false, $httpOnly = true, $raw = false, $sameSite = null)
    {
        return Cookie::make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }

    public static function setForever($name, $value, $path = null, $domain = null, $secure = false, $httpOnly = true, $raw = false, $sameSite = null)
    {
        return Cookie::forever($name, $value, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }

    public static function get($name)
    {
        return Cookie::get($name);
    }

    public static function has($name)
    {
        return Cookie::has($name);
    }

    public static function delete($name)
    {
        return Cookie::forget($name);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Cookie;

class CookieController extends Controller
{
    // Set cookie - default minutes 1440 = 24hours = 1 day
    public function set($name, $value, $minutes = 1440)
    {
        return Cookie::queue(Cookie::make($name, $value, $minutes));
    }

    public function setEncrypted($name, $value, $minutes = 1440)
    {
        return Cookie::queue(Cookie::encrypt($name, $value, $minutes));
    }

    public function setForever($name, $value)
    {
        return Cookie::queue(Cookie::forever($name, $value));
    }

    public function getDecrypted($name)
    {
        return Cookie::decrypt($name);
    }

    public function get($name)
    {
        return Cookie::get($name);
    }

    public function has($name)
    {
        return Cookie::has($name);
    }

    public function delete($name)
    {
        return Cookie::queue(Cookie::forget($name));
    }
}

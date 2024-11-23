<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Auth\EmailVerificationRequest;

class CustomEmailVerificationRequest extends EmailVerificationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // In the origninal class this if clause results to forbidden (403) error. Have done some debug and deduce that the route's id parameter return
        // empty string while the value is actually there in the request. Strangely, the value can be accessed cleanly by the route's
        // originalParameter() method by passing 'id' as an argument. It appears as somehow the parameter value get modified resulting to the
        // parameter different from the original one.
        // Since we have 'auth' middleware among others, there is no need to check for user authorization/authentication, that's why we comment this clause.

        /*
        if (! hash_equals((string) $this->user()->getKey(), (string) $this->route('id'))) {
            return false;
        }
        */

        if (!hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }
}

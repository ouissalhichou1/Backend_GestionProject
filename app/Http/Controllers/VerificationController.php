<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/verify';

    public function verify(Request $request)
{
    $user = User::findOrFail($request->route('id'));

    if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
        throw new AuthorizationException;
    }

    if ($user->hasVerifiedEmail()) {
        return view('verify', ['message' => 'Email already verified.']);
    }

    $user->markEmailAsVerified();
    $user->forceFill(['email_verified_at' => Carbon::now()])->save();

    return view('verify', ['message' => 'Email successfully verified.']);
}

    

}

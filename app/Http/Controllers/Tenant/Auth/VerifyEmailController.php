<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            if (tenancy()->initialized) {
                return redirect()->intended(route('tenant.dashboard', absolute: false).'?verified=1');
            } else {
                return redirect()->intended(route('central.dashboard', absolute: false).'?verified=1');
            }
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        if (tenancy()->initialized) {
            return redirect()->intended(route('tenant.dashboard', absolute: false).'?verified=1');
        } else {
            return redirect()->intended(route('central.dashboard', absolute: false).'?verified=1');
        }
    }
}

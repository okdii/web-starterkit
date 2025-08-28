<?php

namespace App\Http\Controllers\Central\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        if($request->user()->hasVerifiedEmail()) {
            if (tenancy()->initialized) {
                return redirect()->intended(route('tenant.dashboard', absolute: false));
            } else {
                return redirect()->intended(route('central.dashboard', absolute: false));
            }
        }
        else {
            return Inertia::render('Central/Auth/VerifyEmail', ['status' => session('status')]);
        }
    }
}

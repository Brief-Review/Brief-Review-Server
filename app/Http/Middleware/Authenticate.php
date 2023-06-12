<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if ($this->shouldVerifyEmail($request)) {
            $this->handleEmailVerification($request);
        }

        return $next($request);
    }

    /**
     * Determine if the request should be used for email verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldVerifyEmail($request)
    {
        // Check if the request is for email verification
        if ($request->route() && $request->route()->getName() === 'verification.verify') {
            // Check if the user is authenticated and the user's email has been verified
            if ($request->user() && $request->user()->hasVerifiedEmail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle the email verification process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function handleEmailVerification($request)
    {
        // Fulfill the email verification request
        $request->fulfill();
        
        // You can perform additional actions here if needed
    }
}


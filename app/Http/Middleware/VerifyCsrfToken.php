<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    // Prevent users seeing a 419 Page Expired when logging out
    public function handle($request, Closure $next)
    {
        if ($request->route()->named('logut')) {
            if (!Auth::check() || Auth::guard()->viaRemember()) {
                if (Auth::guard('fasilitator')->check()) {
                    $this->except[] = route('fasilitator.logout');
                } else {
                    $this->except[] = route('nonasn.logout');
                }
            }
        }

        return parent::handle($request, $next);
    }
}

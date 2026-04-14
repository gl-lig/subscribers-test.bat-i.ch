<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminFullAccess
{
    public function handle(Request $request, Closure $next)
    {
        $admin = auth()->guard('admin')->user();

        if ($admin && $admin->isApiUser()) {
            return redirect()->route('admin.logs.api')
                ->with('error', 'Accès restreint à la section API.');
        }

        return $next($request);
    }
}

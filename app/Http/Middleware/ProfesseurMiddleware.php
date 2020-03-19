<?php

namespace App\Http\Middleware;

use App\Professeur;
use Closure;

class ProfesseurMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $professeur = Professeur::query()->find($request->session()->get('p_id'));
        if ($request->session()->get('p_username') === null) {
            return response()->view('profauth.login');
        }
        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next,string $role): Response
    {
      $user = $request->user();

    //   check user authentication
      if(!$user){
        return response()->json([
            'message' => 'Unauthenticated.',
        ], 401);
      }

    //   check role permissions

      if($user->role !== $role){
        return response()->json([
            'message' => 'Forbidden. You do not have permission to access this resource.',
        ], 403);
      }

    //   request continues.

        return $next($request);
    }
}
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

        if($request->is('api/*') || $request->expectsJson()){
          return response()->json([
              'message' => 'Unauthenticated.',
          ], 401);
        }
        
        return redirect()->route('login');
      }

    //   check role permissions

      if($user->role !== $role){
        
        if($request->is('api/*') || $request->expectsJson()){
          return response()->json([
              'message' => 'Forbidden. You do not have permission to access this resource.',
          ], 403);
        }
        
        abort(403, 'You do not have permission to access this page.');
      }

    //   request continues.

        return $next($request);
    }
}
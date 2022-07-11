<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Authenticate
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
        $token = $request->bearerToken();
        
        if(!isset($token)){
			return response()->json(['statusCode' => 10001,
									 'status' => 'error',
									 'message' => 'Login Attempt Failed! Token not found.'
									], 401);
		}
	
        try{
			$userToken = UserToken::where('token', $token)->where('valid_until', '>', Carbon::now()->format('Y-m-d H:i:s'))->first();
			
			if(!$userToken){
				return response()->json(['statusCode' => 10002,
										 'status' => 'error',
										 'message' => 'Login Attempt Failed! User not found.'
										], 401);
			}
		}
		catch(ModelNotFoundException $e){
			return response()->json(['statusCode' => 10000,
									 'status' => 'error',
									 'message' => 'Unexpected server errors!'
									], 500);
		}
		
        return $next($request);
    }
}

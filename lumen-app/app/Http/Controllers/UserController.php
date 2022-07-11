<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function authenticate(Request $request)
	{
		$user = User::where('name', '=', $request->get('name'))->first();

		if(!$user){
			return response()->json(['statusCode' => 10101,
									 'status' => 'error',
									 'message' => 'Login Attempt Failed! User not found.'
									], 401);
		}
		
		if(empty($user)){
			return response()->json(['statusCode' => 10102,
									 'status' => 'error',
									 'message' => 'Login Attempt Failed! User not found.'
									], 401);
		}
		
		if (Hash::check($request->get('password'), $user->password)) {
			$today = Carbon::now();
			$token = Hash::make(env('APP_KEY').$today->toDateTimeString());
			
			$userToken = UserToken::create([
											   'user_id' => $user->id,
											   'token' => $token,
											   'valid_until' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
										   ]);
			
			return response()->json(['statusCode' => 200,
									 'status' => 'success',
									 'message' => 'Login succeed.',
									 'token' => $token
									],  200);
		}
		
		return response()->json(['statusCode' => 10102,
								 'status' => 'error',
								 'message' => 'Login Attempt Failed! Wrong Password.'
								], 401);
	}
}

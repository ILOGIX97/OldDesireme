<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request){

    	$request->validate([
    		'firstname' => 'required|string',
            'lastname' => 'required|string',
    		'email' => 'required|string|email|unique:users',
    		'password' => 'required|string',
            'displayname' => 'required',
            'phone' => 'required',
            'profile_photo' => 'required',
            'cover_photo' => 'required',
            'terms_condition' => 'required'
    	]);
       
    	$user = new User([
    		'firstname' => $request->firstname,
    		'lastname' => $request->lastname,
            'email' => $request->email,
    		'password' => bcrypt($request->password),
            'displayname' => $request->displayname,
            'phone' => $request->phone,
            'profile_photo' => $request->profile_photo,
            'cover_photo' => $request->cover_photo,
            'category' => $request->category,
            'terms_condition' => $request->terms_condition,
            'years_old' => $request->years_old,
            'tow_factor' => $request->tow_factor
    	]);

    	if($user->save()){
    		return response()->json(['message' => 'Successfully created user!'],201);
    	}else{
    		return response()->json(['error' => 'Provide proper details'],201);
    	}
    }

    public function login(Request $request)
    {
    	$request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        
        $tokenResult = $user->createToken('Personal Access Token');
        
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);

    }

    public function logout(Request $request){
    	$request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request){
        return response()->json($request->user());
    }
}

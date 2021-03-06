<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
    	// print_r("from register");
     //    print_r($request->post());
    	$request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        
        $credentials = request(['email', 'password']);
        
        if(!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
            

        $user = $request->user();
        $user_data = $user->toArray();
        print_r("user data");
        print_r($user_data);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        // print_r($token);die;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        $session_array = array(
            'user_email'=> $credentials['email'],
            'user_id'=>$user_data['id'],
            'access_token'=>$tokenResult->accessToken,
            'token_type'=>'Bearer',
            'expires_at'=>Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        );

        \session()->push('auth_session', $session_array);
        // $session_key =  \Session::get('auth_session');

        $return_array['error']=false;
        $return_array['access_token']=$tokenResult->accessToken;
        
        return($return_array);
    }
}

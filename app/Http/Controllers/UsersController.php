<?php

namespace App\Http\Controllers;

use App\Post;
use App\Models\Users;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    public function emailRegistration(Request $request)
    {
		$validator = Validator::make($request->all(), [
			'email' => 'required',
			'password' => 'required',
			'full_name' => 'required'
		]);

		if ($validator->fails()) {
			return $this->ValidatorFailedResponse();
		}
		
		$full_name = $request->full_name;
		$separated_name = explode(" ", $full_name);

		$user = new Users();
		$user->auth_type = "email";
		$user->first_name = $separated_name[0];
		$user->last_name = $separated_name[1] ?? null;
		$user->email = $request->email;
		$user->password = $request->password;

		// TODO : checking email register status
		if ($this->isEmailRegistered($request->email)) {
			return $this->MessageResponse(false, 'Email already registered');
		}
		
		try {
			if (!$user->save()) {
				return $this->CommonResponse(false, "Registration");
			}

			return $this->CommonResponse(true, "Registration", $user);
		} catch (\Throwable $th) {
			return $this->ExceptionResponse($th);
		}
	}

	public function checkemail(Request $request) {
		$validator = Validator::make($request->all(), [
			'email' => 'required'
        ]);
        
        if ($validator->fails()) {
			return $this->ValidatorFailedResponse();
		}
		
		// TODO : checking email register status
		if ($this->isEmailRegistered($request->email)) {
			// INFO : send message that email registered
			return $this->MessageResponse(false, "true");
		}
		return $this->MessageResponse(false, "false");
	}

	private function isEmailRegistered($email) {
		$userRegistered = Users::where(['email' => $email])->first();
		if ($userRegistered) {
			return true;
		}
		return false;
	}
	
	public function emailLogin(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
		]);
		
		if ($validator->fails()) {
			return $this->ValidatorFailedResponse();
		}

		$userRegistered = Users::where([
			'email' => $request->email,
			'password' => $request->password
		])->first();
		if (!$userRegistered) {
			return $this->MessageResponse(false, "Invalid username or password");
		}
		
		return $this->CommonResponse(true, "Login", [
			'detail' => $userRegistered,
			'token' => $this->jwt($userRegistered)
		]);
	}

	private function jwt(Users $user)
    {
        $payload = [
            'iss'   => '7class_app',
            'sub'   => $user,
            'iat'   => time(),
            'exp'   => time() + (24 * 60 * 60 * 7)
        ];
        return JWT::encode($payload, env('JWT_KEY'));
    }
}
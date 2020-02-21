<?php

namespace App\Http\Controllers;

use App\Post;
use App\Models\Users;
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
			return response()->json([
				'success' => false,
				'message' => 'Invalid request parameter',
				'data' => $validator->errors()
			], 401);
		}
		
		$full_name = $request->full_name;
		$separated_name = explode(" ", $full_name);

		$user = new Users();
		$user->auth_type = "email";
		$user->first_name = $separated_name[0];
		$user->last_name = $separated_name[1] ?? null;
		$user->email = $request->email;
		$user->password = $request->password;

		$userRegistered = Users::where(['email' => $request->email])->first();
		if ($userRegistered) {
			return response()->json([
				'success' => false,
				'data' => null,
				'message' => 'Email already registered'
			], 200);
		}
		
		try {
			if ($user->save()) {
				return response()->json([
					'success' => true,
					'data' => [
						'detail' => $user
					],
					'message' => 'Registration successful'
				], 200);
			} else {
				return response()->json([
					'success' => false,
					'data' => null,
					'message' => 'Registration failed'
				], 200);
			}
		} catch (\Throwable $th) {
			return response()->json([
				'success' => false,
				'data' => null,
				'message' => $th
			], 400);
		}
    }
}
<?php

namespace App\Http\Controllers;

use App\Post;
use App\Models\Classes;
use App\Models\ClassMembers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
			'name' => 'required'
        ]);
        
        if ($validator->fails()) {
			return $this->ValidatorFailedResponse();
        }

        $class = new Classes();
        $class->name = $request->name;
        $class->code = $this->generateClassCode();
        $class->description = $request->description ?? null;
        $class->created_by = $request->user->id;

        try {
			if (!$class->save()) {
				return $this->CommonResponse(false, "Create class");	
			}

			// TODO : joining to created class
			$joinClass = $this->joinClass($request->user->id, $class->id);
			if (!$joinClass) {
				return $this->CommonResponse(false, "Create class");	
			}

			return $this->MessageResponse(true, "Create class success", $class);
		} catch (\Throwable $th) {
			return $this->ExceptionResponse($th);
		}
	}
	
	public function myclasses(Request $request) {
		$result = Classes::whereHas('members', function($query) use($request) {
			$query->where('id_user', $request->user->id);
		})->get();
		return $this->MessageResponse(true, 'Joined class list fetched', $result);
	}

	public function classdetail(Request $request, $id_class) {
		$result = Classes::with('member')->find($id_class);
		return $this->MessageResponse(true, 'Class detail fetched', $result);
	}

	public function enroll(Request $request) {
		$validator = Validator::make($request->all(), [
			'class_code' => 'required'
        ]);
        
        if ($validator->fails()) {
			return $this->ValidatorFailedResponse();
		}

		// TODO : check is class exist
		$classExist = Classes::where([
			'code' => $request->class_code
		])->first();
		if (!$classExist) {
			return $this->MessageResponse(false, "Class not found");
		}

		// TODO : checking is user enrolled
		$isJoined = ClassMembers::where([
			'id_user' => $request->user->id,
			'id_class' => $classExist->id
		])->first();
		if ($isJoined) {
			return $this->MessageResponse(false, "Already joined");
		}

		// TODO : joining class
		$joinClass = $this->joinClass($request->user->id, $classExist->id);
		if ($joinClass) {
			return $this->CommonResponse(true, "Join class");
		} else {
			return $this->CommonResponse(false, "Join class");
		}
	}

	private function joinClass($id_user, $id_class)
	{
		$class = new ClassMembers();
        $class->id_user = $id_user;
		$class->id_class = $id_class;
		
		if ($class->save()) {
			return true;
		} else {
			return false;
		}
	}

	private function generateClassCode()
	{
		// TODO : generate unique class code
		$uniqueClassCodeWannaBe = $this->generateRandomCode();
		$codeAvailable = Classes::where([
			'code' => $uniqueClassCodeWannaBe
		])->first();
		do {
			$unieqClassCode = $this->generateRandomCode();
		} while($codeAvailable);

		return $uniqueClassCodeWannaBe;
	}

	private function generateRandomCode()
	{
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 5; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
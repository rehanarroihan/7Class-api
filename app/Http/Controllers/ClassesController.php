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
        $class->code = $this->generateRandomCode();
        $class->description = $request->description ?? null;
        $class->created_by = $request->user->id;

        try {
			if ($class->save()) {
				$joinClass = $this->joinClass($request->user->id, $class->id);
				if ($joinClass) {
					return $this->CommonResponse(true, "Create class");
				} else {
					return $this->CommonResponse(false, "Create class");
				}
			} else {
				return $this->CommonResponse(false, "Create class");
			}
		} catch (\Throwable $th) {
			return $this->ExceptionResponse($th);
		}
	}
	
	public function myclasses(Request $request) {
		$result = Classes::where('created_by', $request->user->id)->get();
		return $this->MessageResponse(true, 'Data fetched', $result);
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
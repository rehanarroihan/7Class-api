<?php

namespace App\Http\Controllers;

use App\Post;
use App\Models\Classes;
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
			return response()->json([
				'success' => false,
				'message' => 'Invalid request parameter',
				'data' => $validator->errors()
			], 400);
        }

        $class = new Classes();
        $class->name = $request->name;
        $class->description = $request->description ?? null;
        $class->created_by = $request->user->id;

        try {
			if ($class->save()) {
				return response()->json([
					'success' => true,
					'data' => [
						'detail' => $class
					],
					'message' => 'Class created'
				], 200);
			} else {
				return response()->json([
					'success' => false,
					'data' => null,
					'message' => 'Create class failed'
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
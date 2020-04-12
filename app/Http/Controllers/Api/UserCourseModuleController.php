<?php

namespace App\Http\Controllers\Api;

use App\UserCourseModule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class UserCourseModuleController extends Controller
{
    public function saveState(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_course_id' => 'required|string',
            'module_id' => 'required|string',
            'completed' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $selectParamater = [
                'user_course_id' => $request->get('user_course_id'),
                'module_id' => $request->get('module_id'),
            ];
            
            $isAlreadySaved = UserCourseModule::where($selectParamater)->count();
            $savingParameter = $selectParamater;
            $savingParameter['completed'] = $request->get('completed');

            if ($isAlreadySaved) {
                if ($request->get('completed') !== null) {
                    UserCourseModule::where($selectParamater)->update($savingParameter);
                }
            } else {
                UserCourseModule::create($savingParameter);
            }

            return response()->json([
                'error'   => false,
                'message' => 'Successfully saving a user course module',
                'data'    => $savingParameter
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a user course module',
                'data'    => []
            ], 500);
        }
    }
}

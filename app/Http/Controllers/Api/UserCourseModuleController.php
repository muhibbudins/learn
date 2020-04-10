<?php

namespace App\Http\Controllers\Api;

use App\UserCourseModule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class UserCourseModuleController extends Controller
{
    public function read(Request $request) {
        $entity = $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity || $includes) {
                $userCourseModules = UserCourseModule::find($entity ?? explode(',', $includes));
            }
    
            else if ($trashed) {
                $userCourseModules = UserCourseModule::onlyTrashed()->paginate(30);
            }
    
            else {
                $userCourseModules = UserCourseModule::paginate(30);
            }
    
            return response()->json($userCourseModules, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a user course module',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_course_id' => 'required|string',
            'module_id' => 'required|string',
            'completed' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $userCourseModule = UserCourseModule::create([
                'user_course_id' => $request->get('user_course_id'),
                'module_id' => $request->get('module_id'),
                'completed' => $request->get('completed')
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a user course module',
                'data'    => $userCourseModule
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a user course module',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'user_course_id' => 'string',
            'module_id' => 'string',
            'completed' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $userCourseModuleTrashed = UserCourseModule::onlyTrashed()->where('id', $entity)->count();

            if ($userCourseModuleTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'User course module already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $userCourseModuleData = UserCourseModule::find($entity);

            if ($request->get('user_course_id')) {
                $userCourseModuleData->user_course_id = $request->get('user_course_id');
            }
            if ($request->get('module_id')) {
                $userCourseModuleData->module_id = $request->get('module_id');
            }
            if ($request->get('completed')) {
                $userCourseModuleData->completed = $request->get('completed');
            }

            $userCourseModuleData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a user course module',
                'data'    => $userCourseModuleData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a user course module',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $userCourseModuleData = UserCourseModule::find($entity);

            if (!$userCourseModuleData) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not defined',
                    'data'    => []
                ], 400);
            }
            
            $userCourseModuleData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'User course module already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a user course module',
                'data'    => []
            ], 500);
        }
    }
}

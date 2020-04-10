<?php

namespace App\Http\Controllers\Api;

use App\Module;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    public function read(Request $request) {
        $entity = $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity || $includes) {
                $module = Module::find($entity ?? explode(',', $includes));
            }
    
            else if ($trashed) {
                $module = Module::onlyTrashed()->paginate(30);
            }
    
            else {
                $module = Module::paginate(30);
            }
    
            return response()->json($module, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a module',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'course_id' => 'string',
            'module_lesson_id' => 'string',
            'module_quiz_id' => 'string',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        // try {
            $module = Module::create([
                'course_id' => $request->get('course_id'),
                'module_lesson_id' => $request->get('module_lesson_id'),
                'module_quiz_id' => $request->get('module_quiz_id'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a module',
                'data'    => $module
            ], 201);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'error'   => true,
        //         'message' => 'Something went wrong when creating a module',
        //         'data'    => []
        //     ], 500);
        // }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'course_id' => 'string',
            'module_lesson_id' => 'string',
            'module_quiz_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'status' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $module = Module::onlyTrashed()->where('id', $entity)->count();

            if ($module > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Course already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $module = Module::find($entity);

            if ($request->get('course_id')) {
                $module->course_id = $request->get('course_id');
            }
            if ($request->get('module_lesson_id')) {
                $module->module_lesson_id = $request->get('module_lesson_id');
            }
            if ($request->get('module_quiz_id')) {
                $module->quiz_id = $request->get('module_quiz_id');
            }
            if ($request->get('title')) {
                $module->title = $request->get('title');
            }
            if ($request->get('description')) {
                $module->description = $request->get('description');
            }
            if ($request->get('status')) {
                $module->status = $request->get('status');
            }

            $module->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a module',
                'data'    => $module
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a module',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $module = Module::find($entity);

            if (!$module) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not defined',
                    'data'    => []
                ], 400);
            }
            
            $module->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Course already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a module',
                'data'    => []
            ], 500);
        }
    }
}

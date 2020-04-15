<?php

namespace App\Http\Controllers\Api;

use App\ModuleLesson;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class ModuleLessonController extends Controller
{
    public function room(Request $request, $userCourse, $entity) {
        $entity = $entity ?? $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity || $includes) {
                $moduleLessons = ModuleLesson::find($entity ?? explode(',', $includes));
            }
    
            else if ($trashed) {
                $moduleLessons = ModuleLesson::onlyTrashed()->paginate(30);
            }
    
            else {
                $moduleLessons = ModuleLesson::paginate(30);
            }
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully reading a module of class',
                'data'    => $moduleLessons
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a module lesson',
                'data'    => []
            ], 500);
        }
    }

    public function read(Request $request, $entity = null) {
        $entity = $entity ?? $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity || $includes) {
                $moduleLessons = ModuleLesson::find($entity ?? explode(',', $includes));
            }
    
            else if ($trashed) {
                $moduleLessons = ModuleLesson::onlyTrashed()->paginate(30);
            }
    
            else {
                $moduleLessons = ModuleLesson::paginate(30);
            }
    
            return response()->json($moduleLessons, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a module lesson',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'content' => 'required|string',
            'status' => 'integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $moduleLesson = ModuleLesson::create([
                'module_id' => $request->get('module_id'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'content' => $request->get('content'),
                'status' => $request->get('status') ?? 1,
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a module lesson',
                'data'    => $moduleLesson
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a module lesson',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'module_id' => 'string',
            'title' => 'string',
            'description' => 'string',
            'content' => 'string',
            'status' => 'integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $isExisted = ModuleLesson::find($entity)->count();

            if (!$isExisted) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
    
            $moduleLessonTrashed = ModuleLesson::onlyTrashed()->where('id', $entity)->count();

            if ($moduleLessonTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Course data already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $moduleLessonData = ModuleLesson::find($entity);

            if ($request->get('module_id')) {
                $moduleLessonData->module_id = $request->get('module_id');
            }
            if ($request->get('title')) {
                $moduleLessonData->title = $request->get('title');
            }
            if ($request->get('description')) {
                $moduleLessonData->description = $request->get('description');
            }
            if ($request->get('content')) {
                $moduleLessonData->content = $request->get('content');
            }
            if ($request->get('status')) {
                $moduleLessonData->status = $request->get('status');
            }

            $moduleLessonData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a module lesson',
                'data'    => $moduleLessonData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a module lesson',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $moduleLessonData = ModuleLesson::find($entity);

            if (!$moduleLessonData) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
            
            $moduleLessonData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Module lesson data already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a module lesson',
                'data'    => []
            ], 500);
        }
    }
}

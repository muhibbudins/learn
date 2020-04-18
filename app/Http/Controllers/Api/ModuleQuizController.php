<?php

namespace App\Http\Controllers\Api;

use App\ModuleQuiz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class ModuleQuizController extends Controller
{
    public function room(Request $request, $userCourseModule, $entity) {
        try {
            $moduleQuiz = ModuleQuiz::find($entity);
            $moduleQuiz['questions'] = $moduleQuiz->questions;

            foreach ($moduleQuiz['questions'] as $question) {
                $question['choices'] = $question->choices;
            }
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully reading a quiz of class',
                'data'    => $moduleQuiz
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a module quiz',
                'data'    => []
            ], 500);
        }
    }

    public function read(Request $request, $entity = null) {
        $entity = $entity ?? $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity) {
                $moduleQuiz = ModuleQuiz::find($entity);
                $moduleQuiz['questions'] = $moduleQuiz->questions;

                foreach ($moduleQuiz['questions'] as $question) {
                    $questionAnswer = 0;
                    foreach ($question->choices as $choice) {
                        if ($choice->answer) {
                            $questionAnswer = $choice->id;
                        }
                    }
                    $question['answer'] = $questionAnswer;
                }
            }
            else if ($trashed) {
                $moduleQuiz = ModuleQuiz::onlyTrashed()->paginate(10);
                $moduleQuiz->withPath('/master/module/quiz');
            }
            else {
                $moduleQuiz = ModuleQuiz::paginate(10);
                $moduleQuiz->withPath('/master/module/quiz');
            }
    
            return response()->json($moduleQuiz, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a module quiz',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'string',
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
            $moduleQuiz = ModuleQuiz::create([
                'module_id' => $request->get('module_id'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'status' => $request->get('status') ?? 1,
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a module quiz',
                'data'    => $moduleQuiz
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a module quiz',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'module_id' => 'integer',
            'title' => 'string',
            'description' => 'string',
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
            $isExisted = ModuleQuiz::find($entity)->count();

            if (!$isExisted) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
    
            $moduleQuizTrashed = ModuleQuiz::onlyTrashed()->where('id', $entity)->count();

            if ($moduleQuizTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Course data already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $moduleQuizData = ModuleQuiz::find($entity);

            if ($request->get('module_id')) {
                $moduleQuizData->module_id = $request->get('module_id');
            }
            if ($request->get('title')) {
                $moduleQuizData->title = $request->get('title');
            }
            if ($request->get('description')) {
                $moduleQuizData->description = $request->get('description');
            }
            if ($request->get('status')) {
                $moduleQuizData->status = $request->get('status');
            }

            $moduleQuizData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a modul quiz',
                'data'    => $moduleQuizData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a modul quiz',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $moduleQuiz = ModuleQuiz::find($entity);

            if (!$moduleQuiz) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
            
            $moduleQuiz->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Modul quiz data already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a module quiz',
                'data'    => []
            ], 500);
        }
    }
}

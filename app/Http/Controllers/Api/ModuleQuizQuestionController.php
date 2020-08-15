<?php

namespace App\Http\Controllers\Api;

use App\ModuleQuizQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ModuleQuizQuestionController extends Controller
{
    public function read(Request $request) {
        $entity = $entity ?? $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity) {
                $moduleQuizQuestion = ModuleQuizQuestion::find($entity);
                $moduleQuizQuestion['questions'] = $moduleQuizQuestion->questions;

                foreach ($moduleQuizQuestion['questions'] as $question) {
                    $question['choices'] = $question->choices;
                }
            }
            else if ($trashed) {
                $moduleQuizQuestion = ModuleQuizQuestion::onlyTrashed()->paginate(10);
                $moduleQuizQuestion->withPath('/master/module/quiz/question');
            }
            else {
                $moduleQuizQuestion = ModuleQuizQuestion::paginate(10);
                $moduleQuizQuestion->withPath('/master/module/quiz/question');
            }
    
            return response()->json($moduleQuizQuestion, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a module quiz question',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'module_quiz_id' => 'required|integer',
            'title' => 'required|string',
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
            $moduleQuizQuestion = ModuleQuizQuestion::create([
                'module_quiz_id' => $request->get('module_quiz_id'),
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'status' => $request->get('status') ?? 1,
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a module quiz question',
                'data'    => $moduleQuizQuestion
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a module quiz question',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'module_quiz_id' => 'integer',
            'title' => 'string',
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
            $isExisted = ModuleQuizQuestion::find($entity)->count();

            if (!$isExisted) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
    
            $moduleQuestionTrashed = ModuleQuizQuestion::onlyTrashed()->where('id', $entity)->count();

            if ($moduleQuestionTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Course data already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $moduleQuestionData = ModuleQuizQuestion::find($entity);

            if ($request->get('module_quiz_id')) {
                $moduleQuestionData->module_quiz_id = $request->get('module_quiz_id');
            }
            if ($request->get('title')) {
                $moduleQuestionData->title = $request->get('title');
            }
            if ($request->get('description')) {
                $moduleQuestionData->description = $request->get('description');
            }
            if ($request->get('status')) {
                $moduleQuestionData->status = $request->get('status');
            }

            $moduleQuestionData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a module question',
                'data'    => $moduleQuestionData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a module question',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $moduleQuizQuestion = ModuleQuizQuestion::find($entity);

            if (!$moduleQuizQuestion) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
            
            $moduleQuizQuestion->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Quiz question data already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a module quiz question',
                'data'    => []
            ], 500);
        }
    }
}

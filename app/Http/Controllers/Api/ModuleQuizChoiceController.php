<?php

namespace App\Http\Controllers\Api;

use App\ModuleQuizChoices;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class ModuleQuizChoiceController extends Controller
{
    public function read(Request $request) {
        $entity = $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity || $includes) {
                $moduleQuizChoices = ModuleQuizChoices::find($entity ?? explode(',', $includes));
            }
    
            else if ($trashed) {
                $moduleQuizChoices = ModuleQuizChoices::onlyTrashed()->paginate(30);
            }
    
            else {
                $moduleQuizChoices = ModuleQuizChoices::paginate(30);
            }
    
            return response()->json($moduleQuizChoices, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a module quiz choices',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'module_quiz_question_id' => 'required|string',
            'content' => 'required|string',
            'answer' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $moduleQuizChoices = ModuleQuizChoices::create([
                'module_quiz_question_id' => $request->get('module_quiz_question_id'),
                'content' => $request->get('content'),
                'answer' => $request->get('answer'),
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a module quiz choices',
                'data'    => $moduleQuizChoices
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a module quiz choices',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'module_quiz_question_id' => 'string',
            'content' => 'string',
            'answer' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $isExisted = ModuleQuizChoices::find($entity)->count();

            if (!$isExisted) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
    
            $moduleQuizChoiceTrashed = ModuleQuizChoices::onlyTrashed()->where('id', $entity)->count();

            if ($moduleQuizChoiceTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Course data already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $moduleQuizChoiceData = ModuleQuizChoices::find($entity);

            if ($request->get('module_quiz_question_id')) {
                $moduleQuizChoiceData->module_quiz_question_id = $request->get('module_quiz_question_id');
            }
            if ($request->get('content')) {
                $moduleQuizChoiceData->content = $request->get('content');
            }
            if ($request->get('answer') !== null) {
                $moduleQuizChoiceData->answer = $request->get('answer');
            }

            $moduleQuizChoiceData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a quiz choice',
                'data'    => $moduleQuizChoiceData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a quiz choice',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $moduleQuizChoicesData = ModuleQuizChoices::find($entity);

            if (!$moduleQuizChoicesData) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
            
            $moduleQuizChoicesData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Module quiz choices data already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a module quiz choices',
                'data'    => []
            ], 500);
        }
    }
}

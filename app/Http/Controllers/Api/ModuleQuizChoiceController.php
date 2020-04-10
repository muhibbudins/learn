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
            'module_quiz_id' => 'required|string',
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
                'module_quiz_id' => $request->get('module_quiz_id'),
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
            'module_quiz_id' => 'string',
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
            $moduleQuizChoicesTrashed = ModuleQuizChoices::onlyTrashed()->where('id', $entity)->count();

            if ($moduleQuizChoicesTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Module quiz choices already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $moduleQuizChoicesData = ModuleQuizChoices::find($entity);

            if ($request->get('module_quiz_id')) {
                $moduleQuizChoicesData->module_quiz_id = $request->get('module_quiz_id');
            }
            if ($request->get('content')) {
                $moduleQuizChoicesData->content = $request->get('content');
            }
            if ($request->get('answer')) {
                $moduleQuizChoicesData->answer = $request->get('answer');
            }

            $moduleQuizChoicesData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a module quiz choices',
                'data'    => $moduleQuizChoicesData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a module quiz choices',
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
                    'message' => 'Entity data is not defined',
                    'data'    => []
                ], 400);
            }
            
            $moduleQuizChoicesData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Module quiz choices already deleted',
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

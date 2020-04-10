<?php

namespace App\Http\Controllers\Api;

use App\ModuleQuiz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class ModuleQuizController extends Controller
{
    public function read(Request $request) {
        $entity = $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity) {
                $moduleQuiz = ModuleQuiz::find($entity);
                $moduleQuiz['choices'] = $moduleQuiz->choices;
            }
            else if ($trashed) {
                $moduleQuiz = ModuleQuiz::onlyTrashed()->paginate(30);
            }
            else {
                $moduleQuiz = [];
                $quizData = ModuleQuiz::all();

                foreach ($quizData as $quiz) {
                    $quiz['choices'] = $quiz->choices;
                    $moduleQuiz[] = $quiz;
                }
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
            'title' => 'required|string',
            'content' => 'required|string'
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
                'title' => $request->get('title'),
                'content' => $request->get('content'),
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
            'title' => 'string',
            'content' => 'string',
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
            $moduleQuiz = ModuleQuiz::onlyTrashed()->where('id', $entity)->count();

            if ($moduleQuiz > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Modul quiz already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $moduleQuiz = ModuleQuiz::find($entity);

            if ($request->get('title')) {
                $moduleQuiz->title = $request->get('title');
            }
            if ($request->get('content')) {
                $moduleQuiz->content = $request->get('content');
            }
            if ($request->get('status')) {
                $moduleQuiz->status = $request->get('status');
            }

            $moduleQuiz->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a module quiz',
                'data'    => $moduleQuiz
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a module quiz',
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
                    'message' => 'Entity data is not defined',
                    'data'    => []
                ], 400);
            }
            
            $moduleQuiz->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Modul quiz already deleted',
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

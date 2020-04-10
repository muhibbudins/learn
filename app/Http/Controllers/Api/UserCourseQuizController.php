<?php

namespace App\Http\Controllers\Api;

use App\UserCourseQuiz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class UserCourseQuizController extends Controller
{
    public function read(Request $request) {
        $entity = $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity || $includes) {
                $userCourseQuizzes = UserCourseQuiz::find($entity ?? explode(',', $includes));
            }
    
            else if ($trashed) {
                $userCourseQuizzes = UserCourseQuiz::onlyTrashed()->paginate(30);
            }
    
            else {
                $userCourseQuizzes = UserCourseQuiz::paginate(30);
            }
    
            return response()->json($userCourseQuizzes, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a user course quiz',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_course_id' => 'required|string',
            'module_quiz_id' => 'required|string',
            'module_quiz_choice_id' => 'string',
            'essay' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $userCourseQuiz = UserCourseQuiz::create([
                'user_course_id' => $request->get('user_course_id'),
                'module_quiz_id' => $request->get('module_quiz_id'),
                'module_quiz_choice_id' => $request->get('module_quiz_choice_id'),
                'essay' => $request->get('essay')
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a user course quiz',
                'data'    => $userCourseQuiz
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a user course quiz',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'user_course_id' => 'string',
            'module_quiz_id' => 'string',
            'module_quiz_choice_id' => 'string',
            'essay' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $userCourseQuizTrashed = UserCourseQuiz::onlyTrashed()->where('id', $entity)->count();

            if ($userCourseQuizTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'User course quiz already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $userCourseQuizData = UserCourseQuiz::find($entity);

            if ($request->get('user_course_id')) {
                $userCourseQuizData->user_course_id = $request->get('user_course_id');
            }
            if ($request->get('module_quiz_id')) {
                $userCourseQuizData->module_quiz_id = $request->get('module_quiz_id');
            }
            if ($request->get('module_quiz_choice_id')) {
                $userCourseQuizData->module_quiz_choice_id = $request->get('module_quiz_choice_id');
            }
            if ($request->get('essay')) {
                $userCourseQuizData->essay = $request->get('essay');
            }

            $userCourseQuizData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a user course quiz',
                'data'    => $userCourseQuizData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a user course quiz',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $userCourseQuizData = UserCourseQuiz::find($entity);

            if (!$userCourseQuizData) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not defined',
                    'data'    => []
                ], 400);
            }
            
            $userCourseQuizData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'User course quiz already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a user course quiz',
                'data'    => []
            ], 500);
        }
    }
}

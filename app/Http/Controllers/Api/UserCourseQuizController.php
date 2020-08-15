<?php

namespace App\Http\Controllers\Api;

use App\UserCourseQuiz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserCourseQuizController extends Controller
{
    public function saveState(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_course_id' => 'required|string',
            'module_quiz_question_id' => 'required|string',
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
            $selectParamater = [
                'user_course_id' => $request->get('user_course_id'),
                'module_quiz_question_id' => $request->get('module_quiz_question_id'),
            ];
            
            $isAlreadySaved = UserCourseQuiz::where($selectParamater)->count();
            $savingParameter = $selectParamater;

            $savingParameter['module_quiz_choice_id'] = $request->get('module_quiz_choice_id');
            $savingParameter['essay'] = $request->get('essay');

            if ($isAlreadySaved) {
                if ($request->get('module_quiz_choice_id') || $request->get('essay')) {
                    UserCourseQuiz::where($selectParamater)->update($savingParameter);
                }
            } else {
                UserCourseQuiz::create($savingParameter);
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

<?php

namespace App\Http\Controllers\Api;

use App\UserCourse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserCourseController extends Controller
{
    public function room(Request $request, $userCourse, $course) {
        try {
            $courseStatus = [];
            $userCourse = UserCourse::find($userCourse);

            if (!$userCourse) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Can\'t find the relation of courses',
                    'data'    => []
                ], 400);
            }

            $courseStatus['id'] = $userCourse->id;
            $courseStatus['course_id'] = $userCourse->course_id;
            $courseStatus['updated_at'] = $userCourse->updated_at;
            $courseStatus['completed'] = false;
            $courseStatus['total_lesson'] = 0;
            $courseStatus['total_quiz'] = 0;
            $courseStatus['completed_lesson'] = 0;
            $courseStatus['completed_quiz'] = 0;

            $userCourse['course'] = $userCourse->course;

            if (isset($userCourse['course'])) {
                foreach($userCourse['course']->modules as $modules) {
                    if (isset($modules->lessons)) {
                        $courseStatus['total_lesson'] += count($modules->lessons);

                        foreach ($modules->lessons as $lessons) {
                            $courseStatus['modules'][$modules->id]['lessons'][$lessons->id] = [
                                'id' => $lessons->id,
                                'completed' => false,
                                'last_lesson' => false,
                            ];
                        }
                    }

                    if (isset($modules->quizzes)) {
                        $courseStatus['total_quiz'] += count($modules->quizzes);
                        foreach ($modules->quizzes as $quizzes) {
                            $quizzes['questions'] = $quizzes->questions;
                            
                            if (isset($quizzes->questions)) {
                                $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['completed'] = false;
                                $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['last_quiz'] = false;
                                $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['total_question'] = count($quizzes->questions);
                                $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['total_correct'] = 0;
                                $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['total_wrong'] = 0;
                                $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['score'] = 0;
                                $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['score_number'] = 0;
                                $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['questions'] = [];

                                foreach ($quizzes->questions as $questions) {
                                    $questionAnswer = 0;
                                    
                                    foreach ($questions->choices as $choice) {
                                        if ($choice->answer) {
                                            $questionAnswer = $choice->id;
                                        }
                                    }

                                    $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id]['questions'][$questions->id] = [
                                        'answer' => false,
                                        'correct_answer' => $questionAnswer
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            $completedLessons = [];
            $completedQuizzes = [];

            foreach ($userCourse->modules as $modules) {
                if ($modules->completed) {
                    array_push($completedLessons, [
                        'lesson' => $modules->module_lesson_id,
                        'updated_at' => $modules->updated_at,
                    ]);
                }
            }

            foreach ($userCourse->quizzes as $quizzes) {
                if ($quizzes->module_quiz_choice_id) {
                    array_push($completedQuizzes, [
                        'question' => $quizzes->module_quiz_question_id,
                        'answer' => $quizzes->module_quiz_choice_id,
                        'updated_at' => $quizzes->updated_at,
                    ]);
                }
            }

            $countLastLesson = 0;
            $countLastQuiz = 0;
            foreach ($courseStatus['modules'] as $moduleId => $modules) {
                if (isset($modules['lessons'])) {
                    foreach ($modules['lessons'] as $lessonIndex => $lesson) {
                        $countLastLesson++;

                        foreach($completedLessons as $completedLesson) {
                            if ($lesson['id'] == $completedLesson['lesson']) {
                                $courseStatus['completed_lesson'] += 1;
                                $courseStatus['modules'][$moduleId]['lessons'][$lesson['id']]['completed'] = true;
                                $courseStatus['modules'][$moduleId]['lessons'][$lesson['id']]['updated_at'] = $completedLesson['updated_at'];
                            }
                        }
                        if ($courseStatus['total_lesson'] === $countLastLesson) {
                            $courseStatus['modules'][$moduleId]['lessons'][$lesson['id']]['last_lesson'] = true;
                        }
                    }
                } else {
                    $courseStatus['modules'][$moduleId]['lessons'] = [];
                }
                
                if (isset($modules['quizzes'])) {                    
                    foreach ($modules['quizzes'] as $quizId => $quiz) {
                        $countCompleted = 0;
                        $countLastQuiz++;

                        foreach ($quiz['questions'] as $questionId => $question) {
                            foreach($completedQuizzes as $completedQuiz) {
                                if ($questionId == $completedQuiz['question']) {
                                    $countCompleted++;

                                    $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['questions'][$questionId]['answer'] = true;
                                    $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['questions'][$questionId]['answer_choice'] = $completedQuiz['answer'];
                                    $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['questions'][$questionId]['updated_at'] = $completedQuiz['updated_at'];

                                    if ($question['correct_answer'] === $completedQuiz['answer']) {
                                        $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['total_correct'] += 1;
                                        $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['questions'][$questionId]['correct'] = true;
                                    } else {
                                        $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['total_wrong'] += 1;
                                    }
                                }
                            }

                            if ($countCompleted === $quiz['total_question']) {
                                $correctScore = $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['total_correct'] / $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['total_question'];

                                $courseStatus['completed_quiz'] += 1;
                                $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['completed'] = true;
                                $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['score'] = round($correctScore, 2);
                                $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['score_number'] = round($correctScore * 100, 2);
                            }

                            if ($courseStatus['total_quiz'] === $countLastQuiz) {
                                $courseStatus['modules'][$moduleId]['quizzes'][$quizId]['last_quiz'] = true;
                            }

                            unset($courseStatus['modules'][$moduleId]['quizzes'][$quizId]['questions'][$questionId]['correct_answer']);
                        }
                    }
                } else {
                    $courseStatus['modules'][$moduleId]['quizzes'] = [];
                }
            }

            if (
                ($courseStatus['total_lesson'] === $courseStatus['completed_lesson']) &&
                ($courseStatus['total_quiz'] === $courseStatus['completed_quiz'])
            ) {
                $courseStatus['completed'] = true;
            }

            return response()->json([
                'error'   => false,
                'message' => 'Successfully reading a class room',
                'data'    => $courseStatus
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a class room',
                'data'    => []
            ], 500);
        }
    }

    public function reportFollower(Request $request) {
        try {
            $followerCount = UserCourse::groupBy('course_id')->selectRaw('count(id) as followers, course_id')->get();

            foreach($followerCount as $course) {
                $course['course'] = $course->course;
                $course['course_title'] = $course['course']['title'];
                unset($course['course']);
            }

            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a report for accessed',
                'data'    => $followerCount
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reporting accessed',
                'data'    => []
            ], 500);
        }
    }

    public function leave(Request $request, $course) {
        try {
            $courseParameter = [
                'user_id' => Auth::user()->id,
                'course_id' => $course
            ];

            $isExisted = UserCourse::where($courseParameter)->count();

            if (!$isExisted) {
                return response()->json([
                    'error'   => true,
                    'message' => 'You\'re already leaving the course',
                    'data'    => []
                ], 400);
            }

            UserCourse::where($courseParameter)->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Successfully leaving the couse',
                'data'    => [
                    'entity' => $course
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a user course',
                'data'    => []
            ], 500);
        }
    }

    public function read(Request $request) {
        $entity = $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            // Force get only user data when access role not admin
            if (Auth::user()->role !== 'admin') {
                $userCourseData = UserCourse::where('user_id', Auth::user()->id)->get();

                foreach ($userCourseData as $userCourse) {
                    $userCourse->course;
                }

                $userCourses = [
                    'error'   => false,
                    'message' => 'Successfully reading a user course',
                    'data'    => $userCourseData
                ];
            } else {
                if ($entity) {
                    $userCourses = UserCourse::find($entity);
                    $userCourses['user'] = $userCourses->user;
                    $userCourses['course'] = $userCourses->course;

                    $userCourses = [
                        'error'   => false,
                        'message' => 'Successfully reading a user course',
                        'data'    => $userCourses
                    ];
                }
                else if ($includes) {
                    $userCourses = [];
                    $userCourseData = UserCourse::find(explode(',', $includes));
    
                    foreach ($userCourseData as $userCourse) {
                        $userCourse['user'] = $userCourse->user;
                        $userCourse['course'] = $userCourse->course;
                        $userCourses[] = $userCourse;
                    }

                    $userCourses = [
                        'error'   => false,
                        'message' => 'Successfully reading a user course',
                        'data'    => $userCourses
                    ];
                }
                else if ($trashed) {
                    $userCourses = UserCourse::onlyTrashed()->paginate(30);
                }
                else {
                    $userCourses = UserCourse::paginate(30);
                }
            }
    
            return response()->json($userCourses, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a user course',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'course_id' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $isExisted = UserCourse::withTrashed()->where([
                'user_id' => $request->get('user_id'),
                'course_id' => $request->get('course_id')
            ])->count();

            if ($isExisted) {
                $responseMessage = Auth::user()->role !== 'admin' ? 'You\'re' : 'This user' ;

                return response()->json([
                    'error'   => true,
                    'message' => $responseMessage . ' already joined the course',
                    'data'    => []
                ], 400);
            }

            $userCourse = UserCourse::create([
                'user_id' => $request->get('user_id'),
                'course_id' => $request->get('course_id')
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a user course',
                'data'    => $userCourse
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a user course',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'course_id' => 'string',
            'user_id' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $isExisted = UserCourse::find($entity)->count();

            if (!$isExisted) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
    
            $userCourseTrashed = UserCourse::onlyTrashed()->where('id', $entity)->count();

            if ($userCourseTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'User course data already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $isExisted = UserCourse::withTrashed()->where([
                'user_id' => $request->get('user_id'),
                'course_id' => $request->get('course_id')
            ])->count();

            if ($isExisted) {
                $responseMessage = Auth::user()->role !== 'admin' ? 'You\'re' : 'This user' ;

                return response()->json([
                    'error'   => true,
                    'message' => $responseMessage . ' already joined the course',
                    'data'    => []
                ], 400);
            }

            $userCourseData = UserCourse::find($entity);

            if ($request->get('course_id')) {
                $userCourseData->course_id = $request->get('course_id');
            }
            if ($request->get('user_id')) {
                $userCourseData->user_id = $request->get('user_id');
            }

            $userCourseData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a user course',
                'data'    => $userCourseData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a user course',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $userCourseData = UserCourse::find($entity);

            if (!$userCourseData) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
            
            $userCourseData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'User course data already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a user course',
                'data'    => []
            ], 500);
        }
    }
}

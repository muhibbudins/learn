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

            $userCourse['course'] = $userCourse->course;

            if ($userCourse['course']) {
                foreach($userCourse['course']->modules as $modules) {
                    if ($modules->lessons) {
                        foreach ($modules->lessons as $lessons) {
                            $courseStatus['modules'][$modules->id]['lessons'][] = [
                                'id' => $lessons->id,
                                'completed' => false
                            ];
                        }
                    }

                    if ($modules->quizzes) {
                        foreach ($modules->quizzes as $quizzes) {
                            $quizzes['questions'] = $quizzes->questions;

                            $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id] = [];

                            if ($quizzes->questions) {
                                foreach ($quizzes->questions as $questions) {
                                    $questionAnswer = 0;
                                    
                                    foreach ($questions->choices as $choice) {
                                        if ($choice->answer) {
                                            $questionAnswer = $choice->id;
                                        }
                                    }

                                    $courseStatus['modules'][$modules->id]['quizzes'][$quizzes->id][] = [
                                        'id' => $questions->id,
                                        'completed' => false,
                                        'correct' => false,
                                        'valid' => $questionAnswer
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            $userCourseModules = $userCourse->modules;
            $userCourseQuizzes = $userCourse->quizzes;

            $completedLessons = [];
            $completedQuizzes = [];

            foreach ($userCourseModules as $modules) {
                if ($modules->completed) {
                    array_push($completedLessons, [
                        'lesson' => $modules->module_lesson_id,
                        'updated_at' => $modules->updated_at,
                    ]);
                }
            }

            foreach ($userCourseQuizzes as $quizzes) {
                if ($quizzes->module_quiz_choice_id) {
                    array_push($completedQuizzes, [
                        'question' => $quizzes->module_quiz_question_id,
                        'choice' => $quizzes->module_quiz_choice_id,
                        'updated_at' => $quizzes->updated_at,
                    ]);
                }
            }

            foreach ($courseStatus['modules'] as $moduleId => $modules) {
                foreach ($modules['lessons'] as $lessonIndex => $lesson) {
                    foreach($completedLessons as $completedLesson) {
                        if ($lesson['id'] == $completedLesson['lesson']) {
                            $courseStatus['modules'][$moduleId]['lessons'][$lessonIndex]['completed'] = true;
                            $courseStatus['modules'][$moduleId]['lessons'][$lessonIndex]['updated_at'] = $completedLesson['updated_at'];
                        }
                    }
                }
                
                foreach ($modules['quizzes'] as $quizzesIndex => $quizzes) {
                    foreach($quizzes as $questionIndex => $question) {
                        foreach($completedQuizzes as $completedQuiz) {
                            if ($question['id'] == $completedQuiz['question']) {
                                $courseStatus['modules'][$moduleId]['quizzes'][$quizzesIndex][$questionIndex]['completed'] = true;
    
                                if ($question['valid'] === $completedQuiz['choice']) {
                                    $courseStatus['modules'][$moduleId]['quizzes'][$quizzesIndex][$questionIndex]['correct'] = true;
                                    $courseStatus['modules'][$moduleId]['quizzes'][$quizzesIndex][$questionIndex]['updated_at'] = $completedQuiz['updated_at'];
                                    unset($courseStatus['modules'][$moduleId]['quizzes'][$quizzesIndex][$questionIndex]['valid']);
                                }
                            }
                        }
                    }
                }
            }

            return response()->json($courseStatus, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a module quiz',
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

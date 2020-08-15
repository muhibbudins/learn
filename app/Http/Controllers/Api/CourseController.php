<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\UserCourse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function generalList(Request $request) {
        try {
            $courses = Course::where('status', 1)->paginate(10);
            $courses->withPath('/master/courses');

            return response()->json($courses, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a course',
                'data'    => []
            ], 500);
        }
    }

    public function generalDetail(Request $request, $entity) {
        try {
            if (!$entity) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity ID is not defined',
                    'data'    => []
                ], 400);
            }

            $course = Course::find($entity);

            if (!$course) {
                return response()->json([
                    'error'   => true,
                    'message' => 'This course is not found',
                    'data'    => []
                ], 400);
            }
            
            if ($course) {
                $course['modules'] = $course->modules;
            }
    
            if ($course['modules']) {
                foreach ($course['modules'] as $module) {
                    $module['lessons'] = $module->lessons;
                    $module['quizzes'] = $module->quizzes;
                }
            }

            if (Auth::check()) {
                $isAlreadyJoined = UserCourse::withTrashed()->where([
                    'user_id' => Auth::user()->id,
                    'course_id' => $entity
                ])->get();

                if ($isAlreadyJoined) {
                    $course['already_joined'] = true;
                } else {
                    $course['already_joined'] = false;
                }
            }

            if ($course) {
                return response()->json([
                    'error'   => false,
                    'message' => 'Successfully reading a course detail',
                    'data'    => $course,
                ]);
            } else {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity ID doesn\'t exists on database',
                    'data'    => []
                ], 400);
            }
    
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a course',
                'data'    => []
            ], 500);
        }
    }

    public function room(Request $request, $userCourse, $entity) {
        try {
            if (!$entity) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity ID is not defined',
                    'data'    => []
                ], 400);
            }

            $isUserCanAccess = UserCourse::where([
                'user_id' => Auth::user()->id,
                'course_id' => $entity
            ])->count();

            if (!$isUserCanAccess) {
                return response()->json([
                    'error'   => true,
                    'message' => 'You can\'t access this course',
                    'data'    => []
                ], 403);
            }

            $course = Course::find($entity);
            
            if ($course) {
                $course['modules'] = $course->modules;
            }
    
            if ($course['modules']) {
                foreach ($course['modules'] as $module) {
                    $module['lessons'] = $module->lessons;
                    $module['quizzes'] = $module->quizzes;
                }
            }

            if ($course) {
                return response()->json([
                    'error'   => false,
                    'message' => 'Successfully reading a course detail',
                    'data'    => $course,
                ]);
            } else {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity ID doesn\'t exists on database',
                    'data'    => []
                ], 400);
            }
    
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a course',
                'data'    => []
            ], 500);
        }
    }

    public function reportTotal(Request $request) {
        try {
            $courseData = [
                'total' => Course::where('status', 1)->count(),
                'taken' => UserCourse::groupBy('course_id')->count(),
            ];

            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a report for total',
                'data'    => $courseData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reporting total',
                'data'    => []
            ], 500);
        }
    }

    public function read(Request $request) {
        $entity = $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');
        $keyword = $request->get('keyword');

        try {
            if ($entity || $includes) {
                $coursesModules = [];
                $coursesLessons = [];
                $coursesQuizzes = [];

                $courses = Course::find($entity ?? explode(',', $includes));
                $hasUser = UserCourse::where([
                    'course_id' => $entity
                ])->count();

                $courses['has_user'] = $hasUser;
                foreach ($courses->modules as $module) {
                    $coursesModules[] = [
                        'value' => $module['id'],
                        'text' => $module['title'],
                    ];

                    $coursesLessons[$module['id']] = [];
                    foreach ($module->lessons as $lesson) {
                        $coursesLessons[$module['id']][] = [
                            'value' => $lesson['id'],
                            'text' => $lesson['title'],
                        ];
                    }
                    $coursesQuizzes[$module['id']] = [];
                    foreach ($module->quizzes as $quiz) {
                        $coursesQuizzes[$module['id']][] = [
                            'value' => $quiz['id'],
                            'text' => $quiz['title'],
                        ];
                    }
                }

                unset($courses['modules']);

                $courses['modules'] = $coursesModules;
                $courses['lessons'] = $coursesLessons;
                $courses['quizzes'] = $coursesQuizzes;
            }
    
            else if ($trashed) {
                $courses = Course::where('status', '>', -1)->onlyTrashed()->paginate(10);
                $courses->withPath('/master/courses');
            }
    
            else {
                $courses = Course::where('status', '>', -1)->where('title', 'like', '%'. $keyword .'%')->paginate(10);
                $courses->withPath('/master/courses');
            }
    
            return response()->json($courses, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a courses',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'content' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $course = Course::create([
                'title' => $request->get('title'), // Max 200 char
                'description' => $request->get('description'), // Max 200 char
                'content' => $request->get('content') ?? '',
                'status' => 0,
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a course',
                'data'    => $course
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a course',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'description' => 'string',
            'content' => 'string',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $isExisted = Course::find($entity)->count();

            if (!$isExisted) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
    
            $courseTrashed = Course::onlyTrashed()->where('id', $entity)->count();

            if ($courseTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Course data already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $courseData = Course::find($entity);

            if ($request->get('title')) {
                $courseData->title = $request->get('title');
            }
            if ($request->get('description')) {
                $courseData->description = $request->get('description');
            }
            if ($request->get('content')) {
                $courseData->content = $request->get('content');
            }
            if ($request->get('status') !== null) {
                $courseData->status = (int) $request->get('status');
            }

            $courseData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a course',
                'data'    => $courseData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a course',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $courseData = Course::find($entity);

            if (!$courseData) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
            
            $courseData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Course data already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a course',
                'data'    => []
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\UserCourse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserCourseController extends Controller
{
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
                $userCourses = [];
                $userCourseData = UserCourse::where('user_id', Auth::user()->id)->get();

                foreach ($userCourseData as $userCourse) {
                    $userCourses[] = $userCourse->course;
                }

                $userCourses = [
                    'error'   => false,
                    'message' => 'Successfully reading a user course',
                    'data'    => $userCourses
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

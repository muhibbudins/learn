<?php

namespace App\Http\Controllers\Api;

use App\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function read(Request $request) {
        $entity = $request->get('entity');
        $includes = $request->get('includes');
        $trashed = $request->get('trashed');

        try {
            if ($entity) {
                $courses = Course::find($entity);
                $courses['modules'] = $courses->modules;
                $courses['users'] = $courses->users;
            }
            else if ($includes) {
                $courses = [];
                $courseData = Course::find(explode(',', $includes));

                foreach ($courseData as $course) {
                    $course['modules'] = $course->modules;
                    $courses[] = $course;
                }
            }
            else if ($trashed) {
                $courses = Course::onlyTrashed()->paginate(30);
            }
    
            else {
                $courses = Course::paginate(30);
            }
    
            return response()->json($courses, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a course',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
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
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'content' => $request->get('content'),
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
            'title' => 'string|max:200',
            'description' => 'string|max:255',
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
            $courseTrashed = Course::onlyTrashed()->where('id', $entity)->count();

            if ($courseTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Course already deleted',
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
                    'message' => 'Entity data is not defined',
                    'data'    => []
                ], 400);
            }
            
            $courseData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'Course already deleted',
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

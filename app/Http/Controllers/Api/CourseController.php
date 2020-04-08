<?php

namespace App\Http\Controllers\Api;

use App\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function all(Request $request) {
        $id = $request->get('id');
        $in = $request->get('in');
        $trashed = $request->get('trashed');

        if ($id || $in) {
            $courses = Course::find($id ?? explode(',', $in));
        }

        else if ($trashed) {
            $courses = Course::onlyTrashed()->paginate(30);
        }

        else {
            $courses = Course::paginate(30);
        }

        return response()->json($courses);
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $course = Course::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'content' => $request->get('content'),
        ]);

        return response()->json($course, 201);
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:200',
            'description' => 'string|max:255',
            'content' => 'string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $courseTrashed = Course::onlyTrashed()->where('id', $entity)->count();

        if ($courseTrashed > 0) {
            return response()->json([
                'message' => 'Course already deleted',
                'entity' => $entity
            ], 400);
        }

        Course::where('id', $entity)->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'content' => $request->get('content'),
        ]);

        $course = Course::find($entity);

        return response()->json($course, 200);
    }

    public function delete(Request $request, $entity) {
        if(!$entity){
            return response()->json([
                'message' => 'Entity data is not defined'
            ], 400);
        }

        Course::where('id', $entity)->delete();

        return response()->json([
            'message' => 'Course already deleted',
            'entity' => $entity
        ], 200);
    }
}

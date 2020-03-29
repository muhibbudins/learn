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

        return response()->json([
            'trashed' => $trashed,
            'courses' => $courses
        ]);
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
    
    public function update(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'title' => 'string|max:200',
            'description' => 'string|max:255',
            'content' => 'string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $courseTrashed = Course::onlyTrashed()->where('id', $request->get('id'))->count();

        if ($courseTrashed > 0) {
            return response()->json([
                'message' => 'Course already deleted',
                'entity' => $request->get('id')
            ], 400);
        }

        Course::where('id', $request->get('id'))->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'content' => $request->get('content'),
        ]);

        $course = Course::find($request->get('id'));

        return response()->json($course, 200);
    }

    public function delete(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        Course::where('id', $request->get('id'))->delete();

        return response()->json([
            'message' => 'Course already deleted',
            'entity' => $request->get('id')
        ], 200);
    }
}

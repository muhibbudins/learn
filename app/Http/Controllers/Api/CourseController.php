<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Course;

class CourseController extends Controller
{
    public function all() {
        return response()->json([
            'courses' => Course::all()
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $userData = User::find(Auth::user()->id);

        return response()->json([
            'error'   => false,
            'message' => 'Successfully reading a user data',
            'data'    => $userData,
        ]);
    }

    public function meWithDetail(Request $request)
    {
        $userData = User::find(Auth::user()->id);
        $userData['courses'] = $userData->courses;

        foreach ($userData['courses'] as $userCourse) {
            $userCourse['course'] = $userCourse->course;
        }

        return response()->json([
            'error'   => false,
            'message' => 'Successfully reading a user data',
            'data'    => $userData,
        ]);
    }
    
    public function meUpdate(Request $request, $entity) {
        if ($entity != Auth::user()->id) {
            return response()->json([
                'error' => true,
                'messages' => 'You can\'t change other user data',
                'data' => [],
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'password' => 'min:3|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $userTrashed = User::onlyTrashed()->where('id', $entity)->count();

            if ($userTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'User data already deleted',
                    'data'    => []
                ], 400);
            }
    
            $userData = User::find($entity);

            if ($request->get('name')) {
                $userData->name = $request->get('name');
            }
            if ($request->get('password')) {
                if (!Hash::check($request->get('password_old'), $userData->password)) {
                    return response()->json([
                        'error'   => true,
                        'message' => 'Old password is not correct',
                        'data'    => []
                    ], 400);
                }

                $userData->password = Hash::make($request->get('password'));
            }
            if ($request->get('firstname')) {
                $userData->firstname = $request->get('firstname');
            }
            if ($request->get('lastname')) {
                $userData->lastname = $request->get('lastname');
            }
            if ($request->get('address')) {
                $userData->address = $request->get('address');
            }

            $userData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a user',
                'data'    => $userData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a user',
                'data'    => []
            ], 500);
        }
    }

    public function reportTotal(Request $request) {
        try {
            $totalUser = User::count();

            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a report for total student',
                'data'    => $totalUser
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reporting total student',
                'data'    => []
            ], 500);
        }
    }

    public function reportCount(Request $request) {
        try {
            $allReport = [];
            $allReportTemp = [];
            $allReportRoles = [];
            // Disable this query cause mysql version on server is Ver 14.14 Distrib 5.7.30
            // $allUsers = User::where([
            //     ['created_at', '>', 'DATE_SUB(NOW(), INTERVAL 1 YEAR)'],
            //     ['role', '=', 'student']
            // ])->selectRaw('DATE(created_at) as date, role')->get();
            $allUsers = User::selectRaw('DATE(created_at) as date, role')->where('role', '=', 'student')->get();

            foreach ($allUsers as $user) {
                $timestamp = strtotime($user->date);

                $allReportRoles[$user->role] = true;

                if (isset($allReportTemp[$timestamp]) && count($allReportTemp[$timestamp]) > 0) {
                    if (isset($allReportTemp[$timestamp][$user->role])) {
                        $allReportTemp[$timestamp][$user->role]++;
                    }
                }
                if (!isset($allReportTemp[$timestamp])){
                    $allReportTemp[$timestamp] = [];
                }
                if (!isset($allReportTemp[$timestamp][$user->role])){
                    $allReportTemp[$timestamp][$user->role] = 1;
                }
            }

            foreach ($allReportRoles as $role => $value) {
                $normalized = [];

                foreach ($allReportTemp as $time => $data) {
                    $dataValue = $data[$role] ?? 0;

                    $normalized[] = [
                        $time * 1000,
                        $dataValue
                    ];
                }

                $allReport[] = [
                    'name' => ucfirst($role),
                    'data' => $normalized
                ];
            }

            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a report for student',
                'data'    => $allReport
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reporting student',
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
            // Force get only user data when access role not admin
            if (Auth::user()->role !== 'admin') {
                return response()->json(Auth::user(), 200);
            }

            if ($entity || $includes) {
                $users = User::find($entity ?? explode(',', $includes));
            }
    
            else if ($trashed) {
                $users = User::onlyTrashed()->paginate(10);
                $users->withPath('/master/user');
            }
    
            else {
                $users = User::where('name', 'like', '%'. $keyword .'%')->paginate(10);
                $users->withPath('/master/user');
            }
    
            return response()->json($users, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when reading a user',
                'data'    => []
            ], 500);
        }
    }

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed',
            'role' => 'string',
            'status' => 'integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => $request->get('role') ?? 'student',
                'status' => $request->get('status') ?? 1,
            ]);
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully creating a user',
                'data'    => $user
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when creating a user',
                'data'    => []
            ], 500);
        }
    }
    
    public function update(Request $request, $entity) {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email',
            'password' => 'min:3|confirmed',
            'role' => 'string',
            'status' => 'integer',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => true,
                'messages' => $validator->errors(),
                'data' => $request->all(),
            ], 400);
        }

        try {
            $isExisted = User::find($entity)->count();

            if (!$isExisted) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
    
            $userTrashed = User::onlyTrashed()->where('id', $entity)->count();

            if ($userTrashed > 0) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Course data already deleted',
                    'data'    => [
                        'entity' => $entity
                    ]
                ], 400);
            }
    
            $userData = User::find($entity);

            if ($request->get('name')) {
                $userData->name = $request->get('name');
            }
            if ($request->get('email')) {
                $userData->email = $request->get('email');
            }
            if ($request->get('password')) {
                $userData->password = Hash::make($request->get('password'));
            }
            if ($request->get('role')) {
                $userData->role = $request->get('role');
            }
            if ($request->get('firstname')) {
                $userData->firstname = $request->get('firstname');
            }
            if ($request->get('lastname')) {
                $userData->lastname = $request->get('lastname');
            }
            if ($request->get('address')) {
                $userData->address = $request->get('address');
            }
            if ($request->get('status')) {
                $userData->status = $request->get('status');
            }

            $userData->save();
    
            return response()->json([
                'error'   => false,
                'message' => 'Successfully updating a user',
                'data'    => $userData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when updating a user',
                'data'    => []
            ], 500);
        }
    }

    public function delete(Request $request, $entity) {
        try {
            $userData = User::find($entity);

            if (!$userData) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Entity data is not found',
                    'data'    => []
                ], 400);
            }
            
            $userData->delete();

            return response()->json([
                'error'   => false,
                'message' => 'User data already deleted',
                'data'    => [
                    'entity' => $entity
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong when deleting a user',
                'data'    => []
            ], 500);
        }
    }
}

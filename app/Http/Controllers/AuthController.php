<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:3|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => $validator->errors(),
                'data'    => []
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role' => 'student',
            ]);

            return response()->json([
                'error'   => false,
                'message' => 'Your account already created',
                'data'    => [
                    'name'  => $request->get('name'),
                    'email' => $request->get('email'),
                    'role' => 'student',
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong at our server',
                'data'    => []
            ], 500);
        }
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if ($token = $this->guard()->attempt($credentials)) {
                return response()
                    ->json([
                        'error'   => false,
                        'message' => 'Your login access already granted',
                        'data'    => [
                            'token'   => $token
                        ]
                    ], 200)
                    ->header('Authorization', $token)
                ;
            }
    
            return response()->json([
                'error'   => true,
                'message' => 'Your credentials is invalid, please check you e-mail or password',
                'data'    => []
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'error'   => true,
                'message' => 'Something went wrong at our server',
                'data'    => []
            ], 500);
        }
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'error' => false,
            'message' => 'Successfully logging out from system'
        ], 200);
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json([
                    'error'   => false,
                    'message' => 'Your token already refreshed',
                    'data'    => [
                        'token'   => $token
                    ]
                ], 200)
                ->header('Authorization', $token)
            ;
        }

        return response()->json([
            'error'   => true,
            'message' => 'Your credentials is invalid, failed to refresh token',
            'data'    => []
        ], 401);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private function guard()
    {
        return Auth::guard();
    }
}
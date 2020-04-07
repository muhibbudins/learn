<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:3|confirmed',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        return response()->json(['status' => 'success'], 200);
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $token)
            ;
        }

        return response()->json(['error' => 'login_error'], 401);
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
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
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
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token)
            ;
        }

        return response()->json(['error' => 'refresh_token_error'], 401);
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
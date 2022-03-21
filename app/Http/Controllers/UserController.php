<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['update', 'profile', 'updateUser', 'index']);
    }

    public function index()
    {
        $user = Auth::user();
        if (isset($user)) {
            if ($user->IsAdmin()) {
                return User::all();
            } else {
                return response()->json([
                    'message' => "No permission1",
                ], 422);
            }
        } return response()->json([
        'message' => "No permission2",
    ], 422);
    }

    public function register(RegisterRequest $request)
    {

        return User::create([
            'password' => Hash::make($request->password)
        ] +$request->all());
    }

    public function profile(Request $request){
        $user = Auth::user();
        return response($user, 202);
    }

    public function update(Request $request){
        $user = Auth::user();
        if (isset($user)) {
            $user->update($request->all());
            return response($user, 202);
        }
            return response()->json([
                'message' => "No permission",
            ], 422);

    }

    public function updateUser(Request $request){
        $user = Auth::user();

        if (isset($user)) {
            if ($request->password) {
                $user->update([
                        'password' => Hash::make($request->password)
                    ] + $request->all());
                return response($user, 202);
            }
            $user->update($request->all());
            return response($user, 202);
        }
        return response()->json([
            'message' => "No permission",
        ], 422);
    }

    public function login(LoginRequest $request)
    {
        if ($user = User::where('email', $request->email)->first()) {
            if($user && Hash::check($request->password, $user->password)) {
                $user->generateToken();

                return [
                    'token' => $user->api_token,
                    'name' => $user->name,
                    'completed' => $user->completed,
                    'IsTeacher' => $user->IsAdmin,
                    'score' =>  $user->score,
                    'created_at' => $user->created_at,
                ];
            } else {
                return response()->json([
                    'message' => "The given data was invalid.",
                    'errors' => [
                        'login' => ['Не верный логин или пароль']
                    ]
                ], 422);
            }
        } else {
            return response()->json([
                'message' => "The given data was invalid.",
                'errors' => [
                    'login' => ['Не верный логин или пароль']
                ]
            ], 422);
        }
    }
}

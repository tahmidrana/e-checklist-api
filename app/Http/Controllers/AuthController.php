<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function signup(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'userid' => 'required|string|unique:users',
            'password' => 'required',
        ]);

        try {

            $user = new User();
            $user->name = $request->input('name');
            $user->userid = $request->input('userid');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->save();

            return response()->json(['status'=> 'success', 'message' => 'User registered successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['status'=> 'error', 'message' => 'User Registration Failed!'], 409);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'userid' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['userid', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['status'=> 'error', 'message' => 'Wrong userid or password'], 401);
        }

        return $this->respondWithToken($token);
    }
}

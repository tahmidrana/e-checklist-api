<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile()
    {
        return response()->json(['status'=> 'success', 'user' => Auth::user()], 200);
    }
}

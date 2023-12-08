<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Str;
#use App\Models\User;
#use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
#use Image;
#use Illuminate\Support\Facades\Storage;
use App\Repositories\UserRepository;



class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $user)
    {
        $this->userRepository=$userRepository;
    }

    public function register(Request $request)
    {
        return $this->userRepository->register($request);
    }

    public function login (Request $request)
    {
        return $this->userRepository->login($request);
    }

    public function userInfo()
    {
        $user=Auth::user();
        return response()->json(['user'=>$user],200);
    }

    public function logout()
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'message'=>'Successfully logged out'
        ]);
    }
}
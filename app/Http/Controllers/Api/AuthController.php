<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' =>  ['required', 'email', 'max:255', 'unique:users,email'],
            'password' =>  ['required', 'string', 'max:25', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $request['password']=Hash::make($request['password']);
        $user = User::create($request->toArray());
         
         $accessToken = $user->createToken('auth_token')->plainTextToken;

         return response()->json([
            'status' => true,
            'user' => $user,
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'message' => 'User registration was successful'
                ], 201);
    }

        public function login(Request $request)
        {
        if (!Auth::attempt($request->only('email', 'password'))) {
             return response()->json([
                    'status' => false,
                    'message' => 'Invalid login details'
                         ], 401);
            }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                'status' => true,
                'access_token' => $token,
                'token_type' => 'Bearer',
        ], 200);
        }
}

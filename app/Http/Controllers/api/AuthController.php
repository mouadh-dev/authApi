<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    /**
     * create User
     * @param Request $request
     *@return User
     */
    public function createUser(Request $request){
        $data =$request->validate(
        [
            'name' =>'required',
            'email' => 'required',
            'password' => 'required'
        ]);
  $data['password']= bcrypt($request->password);
  $user=User::create($data);

  return response()->json([
    'message'=>"user created successfully",
    'data'=> new UserResource($user)
  ]);

    }

     /**
     * login User
     * @param Request $request
     *@return User
     */
    public function loginUser(Request $request){
        $data =$request->validate(
        [
            'email' => 'required',
            'password' => 'required'
        ]);

if(!Auth::attempt($request->only(['email','password']))){
    return response()->json([
        'message' =>"Email and password does not match with our record"
    ],401);
}
$user=User::where('email', $request->email)->first();
  return response()->json([
    'message'=>"user logged in successfully",
    'token'=> $user->createToken("API TOKEN")->plainTextToken
  ],Response::HTTP_ACCEPTED);

    }

    /**
     * logout User
     * @param Request $request
     *@return User
     */
    public function logoutUser(Request $request){
        $user = $request->user();
        $user->tokens()->delete();

      return response()->noContent();
    }
}

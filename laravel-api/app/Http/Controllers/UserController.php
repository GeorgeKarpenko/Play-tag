<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserShowRequest;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

  public function login(UserShowRequest $request)
  {
    $login = $request->only([
        'email', 'password'
    ]);

    if(!\Auth::attempt($login)){
      return response()->json([
        'errors' => ['email'=> ["Неверно введёт Email или пароль"], 'password'=> ["Неверно введёт Email или пароль"]],
        'message' => "The given data was invalid."],422); 
    }
    $user = \Auth::user();
    $user->accessToken = \Auth::user()->createToken('authToken')->accessToken;
    return response()->json($user); 
  }
  
  /**
   * Validate and create a newly registered user.
   *
   * @param  array  $input
   * @return \App\Models\User
   */
  public function create(UserCreateRequest $request)
  {
    $data = $request->only([
        'name', 'email', 'password', 'password_confirmation'
    ]);
    $data['password'] = Hash::make($data['password']);
    $user = new User;

    return response()->json($user->create($data)); 
  }
}

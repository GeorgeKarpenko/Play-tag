<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserShowRequest;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function show(UserShowRequest $request)
  {
    $data = $request->only([
        'email', 'password'
    ]);

    $user = User::where([
      ['email', '=', $data['email']],
    ])->first();
    if (!$user || !Hash::check($data['password'], $user->password)) {
      return response()->json([
        'errors' => ['email'=> ["Неверно введёт Email или пароль"], 'password'=> ["Неверно введёт Email или пароль"]],
        'message' => "The given data was invalid."],422); 
    }
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

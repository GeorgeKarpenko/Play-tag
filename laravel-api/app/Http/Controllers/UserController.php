<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function show(Request $request)
  {
    $data = $request->only([
        'email', 'password'
    ]);
    Validator::make($data, [
      'email' => ['required', 'string', 'email', 'max:255'],
      'password' => ['required', 'string'],
    ])->validate();

    $user = User::where([
      ['email', '=', $data['email']],
    ])->first();
    if (! Hash::check($data['password'], $user->password)) {
      $user = null;
    }
    if(!$user){
      return response()->json([
        'errors' => ['email'=> ["Неверно введёт Email или пароль"], 'password'=> ["Неверно введёт Email или пароль"]],
        'message' => "The given data was invalid."],422); 
    }
    if (! Hash::check($request['password'], $user->password)){
      return response()->json(['error'=> 'Ошибка']); 
    }
    return response()->json($user); 
  }
  
  /**
   * Validate and create a newly registered user.
   *
   * @param  array  $input
   * @return \App\Models\User
   */
  public function create(Request $request)
  {
    $data = $request->only([
        'name', 'email', 'password', 'password_confirmation'
    ]);
    Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ])->validate();
    $data['password'] = Hash::make($data['password']);
    $user = new User;

    return response()->json($user->create($data)); 
  }
}

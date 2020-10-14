<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  // public function __construct()
  // {
  //   $this->middleware('auth');
  // }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function show(Request $request)
  {
    $user = User::where([
      ['name', '=', $request['name']],
      ['password', '=', $request['password']]
    })->firstOrFail();
    return response()->json($user); 
  }
}

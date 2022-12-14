<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function me() {
    return Auth::user();
  }

  public function check() {

    $output = [
      'isLoggedIn' => Auth::check()
    ];

    return response()->json($output);
  }

}

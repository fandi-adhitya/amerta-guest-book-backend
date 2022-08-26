<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
  
  public function auth(Request $request) {
    
    $this->validate($request, [
      'username' => 'required',
      'password' => 'required'
    ]);
    
    $username = $request->input('username');
    $password = $request->input('password');

    $user = User::where('username', $username)->first();

    if(!$user) {
      $output = [
        'message' => "User tidak ditemukan"
      ];
      return response()->json($output, 422);
    }

    $isPasswordValid = Hash::check($password, $user->password);

    if(!$isPasswordValid){
      $output = [
        'message' => "Username & password salah!"
      ];
      return response()->json($output, 422);
    }
    
    
    $generateToken = bin2hex(random_bytes(40));

    $user->update([
      'token' => $generateToken
    ]);

    return response()->json($user);
  }

  public function check() {
    $output = [
      'isLoggedIn' => Auth::check()
    ];

    return response()->json($output);
  }
}
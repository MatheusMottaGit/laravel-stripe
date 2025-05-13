<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  public function register(RegisterRequest $request)
  {
    $validated = $request->validated();

    $user = User::create([
      'name' => $validated['name'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
    ]);

    // when I have a UI I can create a token and return it

    return response()->json([
      'message' => 'User registered successfully',
      'user' => $user,
    ], 201);
  }

  public function login(LoginRequest $request)
  {
    $validated = $request->validated();
    $user = User::where('email', $validated['email'])->firstOrFail();

    $userPassword = Hash::check($validated['password'], $user->password);

    if(!$userPassword) {
      return response()->json([
        'message' => 'Password does not match.'
      ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
      'message' => 'Login successful',
      'user' => $user,
      'token' => $token
    ]);
  }

  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return response()->json([
      'message' => 'Successfully logged out'
    ]);
  }
}
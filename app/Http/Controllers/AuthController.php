<?php
# app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;
use App\Http\Requests\AuthenticateRequest;
use App\Http\Controllers\Controller;
use JWTAuth;
use JWTFactory;
use Hash;
use Validator;

class AuthController extends Controller
{
    public function authenticate(AuthenticateRequest $request) {
      // Get only email and password from request
      $credentials = $request->only('email', 'password');

      $validator = Validator::make($credentials, [
        'password' => 'required',
        'email' => 'required'
      ]);

      if($validator->fails()) {
        return response()->json([
            'message'   => 'Invalid credentials',
            'errors'        => $validator->errors()->all()
        ], 422);
      }

      // Get user by email
      $company = Company::where('email', $credentials['email'])->first();

      // Validate Company
      if(!$company) {
        return response()->json([
          'error' => 'Invalid credentials'
        ], 401);
      }

      // Validate Password
      if (!Hash::check($credentials['password'], $company->password)) {
          return response()->json([
            'error' => 'Invalid credentials'
          ], 401);
      }

      // Generate Token
      $token = JWTAuth::fromUser($company);

      // Get expiration time
      $objectToken = JWTAuth::setToken($token);
      $expiration = JWTAuth::decode($objectToken->getToken())->get('exp');

      $expiration_formated = date('d/m/Y H:i:s', $expiration);

      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => $expiration_formated
      ]);
    }
}
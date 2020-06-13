<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Profile;
use App\User;
use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'registerEmployer']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'redirect' => '/',
            'message' => 'Registration Successful!',
            'token' => $this->respondWithToken($token),
            'user' => auth()->user()
        ], 201);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:40'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'user_type' => ['required', 'string']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function register(request $request)
    {
        $this->validator($request->all())->validate();

        // $credentials = request(['email', 'password']);

        $user = user::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => hash::make($request['password']),
            'user_type' => $request['user_type']
        ]);

        profile::create([
            'user_id' => $user['id'],
        ]);

        $token = auth()->login($user);

        return response()->json([
            'redirect' => '/',
            'message' => 'registration successful!',
            'token' => $this->respondwithtoken($token),
            'user' => $user
        ], 201);
    }

    public function registerEmployer(request $request)
    {
        $this->validator($request->all())->validate();

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => hash::make($request['password']),
            'user_type' => $request['user_type']
        ]);

        $company = Company::create([
            'name' => $request['cname'],
            'user_id' => $user['id'],
            'slug' => Str::slug($request['cname']),
            'address' => '',
            'phone' => '',
            'website' => '',
            'logo' => '',
            'slogan' => '',
            'description' => '',
            'cover_photo' => '',
        ]);

        $token = auth()->login($user);

        return response()->json([
            'redirect' => '/',
            'message' => 'registration successful!',
            'token' => $this->respondwithtoken($token),
            'user' => $user,
            'company' => $company
        ], 201);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
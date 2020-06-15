<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Profile;
use App\User;
use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'registerEmployer', 'refresh']]);
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
     * Get a JWT via Passport and user via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $response = Http::post(Config::get('services.passport.login_endpoint'), [
                'grant_type' => 'password',
                'client_id' => Config::get('services.passport.client_id'),
                'client_secret' => Config::get('services.passport.client_secret'),
                'username' => $request->username,
                'password' => $request->password
            ]);

            $body = json_decode($response->getBody()->getContents());

            $userResponse = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$body->access_token,
            ])->get('http://job-board.test/api/user');

            $user = json_decode($userResponse->getBody()->getContents());

            return response()->json([
                'redirect' => '/',
                'message' => 'Login Successful!',
                'body' => $body,
                'user' => $user
            ], 201);

        } catch (RequestException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect.', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(request $request)
    {
        $this->validator($request->all())->validate();

        $user = user::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => hash::make($request['password']),
            'user_type' => $request['user_type']
        ]);

        profile::create([
            'user_id' => $user['id'],
        ]);

        return response()->json([
            'message' => 'User created successful!',
        ], 201);
    }

    /**
     * Create a new user as an employer and create Company instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User - \App\Company - Token
     */

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
        auth()->user()->tokens()->each(function($token, $key) {
            $token->delete();
        });
        return response()->json(['message' => 'Logout success'], 200); 
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $response = Http::post(Config::get('services.passport.login_endpoint'), [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => Config::get('services.passport.client_id'),
            'client_secret' => Config::get('services.passport.client_secret'),
            'scope' => '',
        ]);

        return json_decode((string) $response->getBody(), true);
    }

}
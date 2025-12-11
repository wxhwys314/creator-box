<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:fan,creator'],
        ];

        // Add creator_id validation if role is creator
        if (isset($data['role']) && $data['role'] === 'creator') {
            $rules['creator_id'] = [
                'required', 
                'string', 
                'max:255', 
                'unique:users',
                'regex:/^[a-z0-9-]+$/' // Only lowercase letters, numbers, and hyphens
            ];
        }

        return Validator::make($data, $rules, [
            'creator_id.regex' => 'Creator ID can only contain lowercase letters, numbers, and hyphens.',
            'creator_id.unique' => 'This Creator ID is already taken. Please choose another one.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ];

        // Add creator_id only if role is creator
        if ($data['role'] === 'creator') {
            $userData['creator_id'] = $data['creator_id'];
        }

        return User::create($userData);
    }
}
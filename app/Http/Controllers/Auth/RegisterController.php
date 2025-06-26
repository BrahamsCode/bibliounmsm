<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    protected $redirectTo = '/home';

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
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'student_code' => $this->generateUniqueStudentCode(),
            'password' => Hash::make($data['password']),
            'role' => 'student', // Asignar rol por defecto
        ]);
    }

    /**
     * Generate a unique student code
     *
     * @return string
     */
    private function generateUniqueStudentCode(): string
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $attempts++;

            $year = now()->format('Y');
            $timestamp = substr(now()->timestamp, -4);
            $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $studentCode = "LIB-{$year}-{$timestamp}{$randomNumber}";

            //  si ya existe
            $exists = User::where('student_code', $studentCode)->exists();

            if (!$exists) {
                return $studentCode;
            }
        } while ($attempts < $maxAttempts);

        $uuid = Str::uuid()->toString();
        $shortUuid = substr(str_replace('-', '', $uuid), 0, 8);

        return "LIB-{$year}-{$shortUuid}";
    }
}

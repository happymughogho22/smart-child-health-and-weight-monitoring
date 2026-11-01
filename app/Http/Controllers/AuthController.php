<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTRATION PAGE
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER USER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE REGISTRATION DATA
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'position' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE USER ACCOUNT
        |--------------------------------------------------------------------------
        |
        | New accounts require administrator approval.
        |
        | Default account state:
        | status = pending
        | role   = null
        |
        |--------------------------------------------------------------------------
        */

        try {
            User::create([
                'name' => $validated['name'],

                'email' => $validated['email'],

                'position' => $validated['position'],

                'role' => null,

                'status' => 'pending',

                'password' => Hash::make(
                    $validated['password']
                ),
            ]);

        } catch (QueryException $exception) {

            /*
            |--------------------------------------------------------------------------
            | HANDLE A POSSIBLE DUPLICATE EMAIL RACE
            |--------------------------------------------------------------------------
            |
            | The unique validation rule normally catches duplicate emails.
            | This also handles a duplicate discovered by the database
            | during account creation.
            |--------------------------------------------------------------------------
            */

            if (
                str_contains(
                    strtolower($exception->getMessage()),
                    'unique'
                ) ||
                str_contains(
                    strtolower($exception->getMessage()),
                    'duplicate'
                )
            ) {
                throw ValidationException::withMessages([
                    'email' => 'An account with this email address already exists.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | RE-THROW OTHER DATABASE ERRORS
            |--------------------------------------------------------------------------
            */

            throw $exception;
        }


        /*
        |--------------------------------------------------------------------------
        | REGISTRATION SUCCESS
        |--------------------------------------------------------------------------
        |
        | The user is not logged in automatically.
        | The administrator must approve the account first.
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'registration_success',
                'Your account has been created successfully. Please wait for administrator approval before logging in.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE LOGIN DATA
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | ATTEMPT AUTHENTICATION
        |--------------------------------------------------------------------------
        */

        if (
            !Auth::attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {
            return back()
                ->withErrors([
                    'email' => 'The email or password is incorrect.',
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | GET AUTHENTICATED USER
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | CHECK ACCOUNT APPROVAL STATUS
        |--------------------------------------------------------------------------
        */

        if ($user->status !== 'approved') {

            Auth::logout();

            $message = match ($user->status) {
                'pending' =>
                    'Your account is awaiting administrator approval.',

                'rejected' =>
                    'Your registration has been rejected. Please contact the system administrator.',

                'suspended' =>
                    'Your account has been suspended. Please contact the system administrator.',

                default =>
                    'Your account is not currently authorized to access the system.',
            };

            return back()
                ->withErrors([
                    'email' => $message,
                ])
                ->onlyInput('email');
        }


        /*
        |--------------------------------------------------------------------------
        | REGENERATE SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT APPROVED USER
        |--------------------------------------------------------------------------
        */

        return redirect()->intended('/dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | LOG OUT CURRENT USER
        |--------------------------------------------------------------------------
        */

        Auth::logout();


        /*
        |--------------------------------------------------------------------------
        | INVALIDATE CURRENT SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();


        /*
        |--------------------------------------------------------------------------
        | REGENERATE CSRF TOKEN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO LOGIN PAGE
        |--------------------------------------------------------------------------
        */

        return redirect()->route('login');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdministrator extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:create-administrator';

    /**
     * The console command description.
     */
    protected $description = 'Create the initial system administrator account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Create System Administrator Account');
        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | GET ADMINISTRATOR NAME
        |--------------------------------------------------------------------------
        */

        $name = $this->ask('Administrator name');


        /*
        |--------------------------------------------------------------------------
        | GET ADMINISTRATOR EMAIL
        |--------------------------------------------------------------------------
        */

        $email = $this->ask('Administrator email');


        /*
        |--------------------------------------------------------------------------
        | CHECK IF EMAIL ALREADY EXISTS
        |--------------------------------------------------------------------------
        */

        if (User::where('email', $email)->exists()) {

            $this->error(
                'A user with this email address already exists.'
            );

            return Command::FAILURE;
        }


        /*
        |--------------------------------------------------------------------------
        | GET PASSWORD
        |--------------------------------------------------------------------------
        */

        $password = $this->secret(
            'Administrator password'
        );


        /*
        |--------------------------------------------------------------------------
        | CONFIRM PASSWORD
        |--------------------------------------------------------------------------
        */

        $passwordConfirmation = $this->secret(
            'Confirm administrator password'
        );


        /*
        |--------------------------------------------------------------------------
        | CHECK PASSWORDS
        |--------------------------------------------------------------------------
        */

        if ($password !== $passwordConfirmation) {

            $this->error(
                'The passwords do not match.'
            );

            return Command::FAILURE;
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            'name' => $name,

            'email' => $email,

            'position' => 'System Administrator',

            'password' => Hash::make($password),

        ]);


        /*
        |--------------------------------------------------------------------------
        | ASSIGN ADMINISTRATOR PRIVILEGES
        |--------------------------------------------------------------------------
        |
        | role and status are intentionally NOT in User::$fillable.
        | We assign them explicitly here so public registration
        | cannot assign administrator privileges.
        |
        |--------------------------------------------------------------------------
        */

        $user->role = 'administrator';

        $user->status = 'approved';

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | SUCCESS MESSAGE
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info(
            'Administrator account created successfully.'
        );

        $this->newLine();

        $this->line(
            'Name: ' . $user->name
        );

        $this->line(
            'Email: ' . $user->email
        );

        $this->line(
            'Role: ' . $user->role
        );

        $this->line(
            'Status: ' . $user->status
        );

        $this->newLine();

        return Command::SUCCESS;
    }
}
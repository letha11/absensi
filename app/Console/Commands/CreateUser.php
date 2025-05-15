<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {--admin : Create an admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isAdmin = $this->option('admin');
        
        if ($isAdmin) {
            $this->info('Creating a new admin user...');
            $name = $this->ask('What is the admin name?');
            $email = $this->ask('What is the admin email?');
            $password = $this->secret('What is the admin password?');
            
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->save();
            
            $this->info('Admin user created successfully!');
        } else {
            $this->info('Please use --admin option to create an admin user or use karyawan:create command to create a karyawan user.');
        }
    }
} 
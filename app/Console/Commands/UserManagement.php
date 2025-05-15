<?php

namespace App\Console\Commands;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:manage 
                            {--create-admin : Create a new admin user}
                            {--create-karyawan : Create a new karyawan user}
                            {--list-admins : List all admin users}
                            {--list-karyawan : List all karyawan users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage users in the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('create-admin')) {
            $this->createAdmin();
        } elseif ($this->option('create-karyawan')) {
            $this->createKaryawan();
        } elseif ($this->option('list-admins')) {
            $this->listAdmins();
        } elseif ($this->option('list-karyawan')) {
            $this->listKaryawan();
        } else {
            $this->info('Please specify an option:');
            $this->line('  --create-admin      Create a new admin user');
            $this->line('  --create-karyawan   Create a new karyawan user');
            $this->line('  --list-admins       List all admin users');
            $this->line('  --list-karyawan     List all karyawan users');
        }
    }

    /**
     * Create a new admin user
     */
    private function createAdmin()
    {
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
    }

    /**
     * Create a new karyawan user
     */
    private function createKaryawan()
    {
        $this->info('Creating a new karyawan user...');
        $nik = $this->ask('What is the NIK?');
        $nama_lengkap = $this->ask('What is the full name?');
        $jabatan = $this->ask('What is the position/jabatan?');
        $password = $this->secret('What is the password?');
        
        $karyawan = new Karyawan();
        $karyawan->nik = $nik;
        $karyawan->nama_lengkap = $nama_lengkap;
        $karyawan->jabatan = $jabatan;
        $karyawan->password = Hash::make($password);
        $karyawan->save();
        
        $this->info('Karyawan created successfully!');
    }

    /**
     * List all admin users
     */
    private function listAdmins()
    {
        $users = User::select('id', 'name', 'email', 'created_at')->get();
        
        if ($users->isEmpty()) {
            $this->info('No admin users found.');
            return;
        }
        
        $this->table(
            ['ID', 'Name', 'Email', 'Created At'],
            $users->toArray()
        );
    }

    /**
     * List all karyawan users
     */
    private function listKaryawan()
    {
        $karyawan = Karyawan::select('nik', 'nama_lengkap', 'jabatan', 'created_at')->get();
        
        if ($karyawan->isEmpty()) {
            $this->info('No karyawan users found.');
            return;
        }
        
        $this->table(
            ['NIK', 'Name', 'Position', 'Created At'],
            $karyawan->toArray()
        );
    }
} 
<?php

namespace App\Console\Commands;

use App\Models\Karyawan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateKaryawan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'karyawan:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new karyawan user for the application';

    /**
     * Execute the console command.
     */
    public function handle()
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
} 
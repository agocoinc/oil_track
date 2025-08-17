<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompanyUserSeeder extends Seeder
{
    public function run()
    {
        User::truncate();
        Company::truncate();

        $companies = [
            [
                'aname' => 'شركة النفط الأولى',
                'lname' => 'OilCo One',
            ],
        ];

        foreach ($companies as $companyData) {
            $company = Company::create($companyData);

            $users = [
                [
                    'name' => 'أحمد علي',
                    'email' => 'ahmed@example.com',
                    'password' => Hash::make('password123'),
                    'company_id' => $company->id,
                ],
                [
                    'name' => 'عائشة محمد',
                    'email' => 'aisha@example.com',
                    'password' => Hash::make('password123'),
                    'company_id' => $company->id,
                ],
            ];

            foreach ($users as $userData) {
                User::create($userData);
            }
        }
    }
}

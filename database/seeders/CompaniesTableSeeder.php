<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CompaniesTableSeeder extends Seeder
{
    public function run()
    {
        $companies = [
            ['cname' => 'Company A', 'cphone_number' => '1234567890', 'cemail' => 'companya@example.com'],
            ['cname' => 'Company B', 'cphone_number' => '2345678901', 'cemail' => 'companyb@example.com'],
            ['cname' => 'Company C', 'cphone_number' => '3456789012', 'cemail' => 'companyc@example.com'],
            ['cname' => 'Company D', 'cphone_number' => '4567890123', 'cemail' => 'companyd@example.com'],
            ['cname' => 'Company E', 'cphone_number' => '5678901234', 'cemail' => 'companye@example.com'],
            ['cname' => 'Company F', 'cphone_number' => '6789012345', 'cemail' => 'companyf@example.com'],
            ['cname' => 'Company G', 'cphone_number' => '7890123456', 'cemail' => 'companyg@example.com'],
            ['cname' => 'Company H', 'cphone_number' => '8901234567', 'cemail' => 'companyh@example.com'],
            ['cname' => 'Company I', 'cphone_number' => '9012345678', 'cemail' => 'companyi@example.com'],
            ['cname' => 'Company J', 'cphone_number' => '1023456789', 'cemail' => 'companyj@example.com'],
            ['cname' => 'Company K', 'cphone_number' => '1123456780', 'cemail' => 'companyk@example.com'],
            ['cname' => 'Company L', 'cphone_number' => '1223456781', 'cemail' => 'companyl@example.com'],
            ['cname' => 'Company M', 'cphone_number' => '1323456782', 'cemail' => 'companym@example.com'],
            ['cname' => 'Company N', 'cphone_number' => '1423456783', 'cemail' => 'companyn@example.com'],
            ['cname' => 'Company O', 'cphone_number' => '1523456784', 'cemail' => 'companyo@example.com'],
            ['cname' => 'Company P', 'cphone_number' => '1623456785', 'cemail' => 'companyp@example.com'],
            ['cname' => 'Company Q', 'cphone_number' => '1723456786', 'cemail' => 'companyq@example.com'],
            ['cname' => 'Company R', 'cphone_number' => '1823456787', 'cemail' => 'companyr@example.com'],
            ['cname' => 'Company S', 'cphone_number' => '1923456788', 'cemail' => 'companys@example.com'],
            ['cname' => 'Company T', 'cphone_number' => '2023456789', 'cemail' => 'companyt@example.com'],
            ['cname' => 'Company U', 'cphone_number' => NULL, 'cemail' => 'companyu@example.com'],
            ['cname' => 'Company V', 'cphone_number' => NULL, 'cemail' => 'companyv@example.com'],
            ['cname' => 'Company W', 'cphone_number' => '3023456780', 'cemail' => 'companyw@example.com'],
            ['cname' => 'Company X', 'cphone_number' => '3123456781', 'cemail' => 'companyx@example.com'],
            ['cname' => 'Company Y', 'cphone_number' => '3223456782', 'cemail' => 'companyy@example.com'],
            ['cname' => 'Company Z', 'cphone_number' => '3323456783', 'cemail' => 'companyz@example.com'],
            ['cname' => 'Alpha Inc.', 'cphone_number' => '3423456784', 'cemail' => 'alpha@example.com'],
            ['cname' => 'Beta LLC', 'cphone_number' => '3523456785', 'cemail' => 'beta@example.com'],
            ['cname' => 'Gamma Corp.', 'cphone_number' => '3623456786', 'cemail' => 'gamma@example.com'],
            ['cname' => 'Delta Ltd.', 'cphone_number' => '3723456787', 'cemail' => 'delta@example.com'],
        ];

        foreach ($companies as &$company) {
            $company['created_at'] = Carbon::now();
            $company['updated_at'] = Carbon::now();
        }

        DB::table('company')->insert($companies);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class CompanyUsersTableSeeder extends Seeder
{
    public function run()
    {
        $managers = [
            ['cid' => 1, 'name' => 'Alice Johnson', 'phone_number' => '1012345670', 'email' => 'alice.johnson@example.com', 'address' => '123 Elm Street'],
            ['cid' => 2, 'name' => 'Bob Smith', 'phone_number' => '1112345671', 'email' => 'bob.smith@example.com', 'address' => '456 Oak Avenue'],
            ['cid' => 3, 'name' => 'Charlie Brown', 'phone_number' => '1212345672', 'email' => 'charlie.brown@example.com', 'address' => '789 Pine Lane'],
            ['cid' => 4, 'name' => 'Diana Prince', 'phone_number' => '1312345673', 'email' => 'diana.prince@example.com', 'address' => '101 Maple Road'],
            ['cid' => 5, 'name' => 'Ethan Hunt', 'phone_number' => '1412345674', 'email' => 'ethan.hunt@example.com', 'address' => '202 Birch Court'],
            ['cid' => 6, 'name' => 'Fiona Carter', 'phone_number' => '1512345675', 'email' => 'fiona.carter@example.com', 'address' => '303 Cedar Blvd'],
            ['cid' => 7, 'name' => 'George Baker', 'phone_number' => '1612345676', 'email' => 'george.baker@example.com', 'address' => '404 Spruce Drive'],
            ['cid' => 8, 'name' => 'Hannah Scott', 'phone_number' => '1712345677', 'email' => 'hannah.scott@example.com', 'address' => '505 Ash Street'],
            ['cid' => 9, 'name' => 'Ian Walker', 'phone_number' => '1812345678', 'email' => 'ian.walker@example.com', 'address' => '606 Willow Avenue'],
            ['cid' => 10, 'name' => 'Jane Miller', 'phone_number' => '1912345679', 'email' => 'jane.miller@example.com', 'address' => '707 Poplar Lane'],
            ['cid' => 11, 'name' => 'Kyle White', 'phone_number' => '2012345680', 'email' => 'kyle.white@example.com', 'address' => '808 Chestnut Road'],
            ['cid' => 12, 'name' => 'Laura Green', 'phone_number' => '2112345681', 'email' => 'laura.green@example.com', 'address' => '909 Redwood Court'],
            ['cid' => 13, 'name' => 'Mike Adams', 'phone_number' => '2212345682', 'email' => 'mike.adams@example.com', 'address' => '100 Sycamore Blvd'],
            ['cid' => 14, 'name' => 'Nina Carter', 'phone_number' => '2312345683', 'email' => 'nina.carter@example.com', 'address' => '200 Elm Avenue'],
            ['cid' => 15, 'name' => 'Oscar Ramirez', 'phone_number' => '2412345684', 'email' => 'oscar.ramirez@example.com', 'address' => '300 Maple Lane'],
            ['cid' => 16, 'name' => 'Paulina Gomez', 'phone_number' => '2512345685', 'email' => 'paulina.gomez@example.com', 'address' => '400 Birch Road'],
            ['cid' => 17, 'name' => 'Quinn Rivera', 'phone_number' => '2612345686', 'email' => 'quinn.rivera@example.com', 'address' => '500 Cedar Court'],
            ['cid' => 18, 'name' => 'Rachel Morgan', 'phone_number' => '2712345687', 'email' => 'rachel.morgan@example.com', 'address' => '600 Spruce Blvd'],
            ['cid' => 19, 'name' => 'Sam Lee', 'phone_number' => '2812345688', 'email' => 'sam.lee@example.com', 'address' => '700 Ash Drive'],
            ['cid' => 20, 'name' => 'Tina Brown', 'phone_number' => '2912345689', 'email' => 'tina.brown@example.com', 'address' => '800 Willow Street'],
            ['cid' => 21, 'name' => 'Ursula Kelly', 'phone_number' => '3012345690', 'email' => 'ursula.kelly@example.com', 'address' => '900 Poplar Avenue'],
            ['cid' => 22, 'name' => 'Victor Young', 'phone_number' => '3112345691', 'email' => 'victor.young@example.com', 'address' => '100 Chestnut Lane'],
            ['cid' => 23, 'name' => 'Wendy Hall', 'phone_number' => '3212345692', 'email' => 'wendy.hall@example.com', 'address' => '200 Redwood Road'],
            ['cid' => 24, 'name' => 'Xander Cole', 'phone_number' => '3312345693', 'email' => 'xander.cole@example.com', 'address' => '300 Sycamore Court'],
            ['cid' => 25, 'name' => 'Yvonne Martinez', 'phone_number' => '3412345694', 'email' => 'yvonne.martinez@example.com', 'address' => '400 Elm Blvd'],
            ['cid' => 26, 'name' => 'Zachary Harris', 'phone_number' => '3512345695', 'email' => 'zachary.harris@example.com', 'address' => '500 Maple Drive'],
            ['cid' => 27, 'name' => 'Anna Stone', 'phone_number' => '3612345696', 'email' => 'anna.stone@example.com', 'address' => '600 Birch Street'],
            ['cid' => 28, 'name' => 'Brian Woods', 'phone_number' => '3712345697', 'email' => 'brian.woods@example.com', 'address' => '700 Cedar Avenue'],
            ['cid' => 29, 'name' => 'Cindy Foster', 'phone_number' => '3812345698', 'email' => 'cindy.foster@example.com', 'address' => '800 Spruce Lane'],
            ['cid' => 30, 'name' => 'David Evans', 'phone_number' => '3912345699', 'email' => 'david.evans@example.com', 'address' => '900 Ash Road'],
        ];

        foreach ($managers as &$manager) {
            $manager['is_manager'] = true;
            $manager['password'] = Hash::make('password'); // 'password' default password
            $manager['remember_token'] = Str::random(10);
            $manager['created_at'] = Carbon::now();
            $manager['updated_at'] = Carbon::now();
        }

        DB::table('company_users')->insert($managers);
    }
}

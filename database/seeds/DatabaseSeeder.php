<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('main_user')->insert([
            'user_id' => 1001,
            'user_nickname' => "tri",
            'user_group_id' => 1,
            'user_phone' => '0334675666',
            'user_password' => bcrypt(123456),
            'user_country_code' => "tri",
            'user_email' => "tri@gmail.com",
            'user_status' => 1,
        ]);
    }
}

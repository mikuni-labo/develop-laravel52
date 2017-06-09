<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        
        DB::table('users')->truncate();
        
        DB::table('users')->insert([
            [
                'id'           => '1',
                'role'         => 'ADMINISTRATOR',
                'last_name'        => 'システム',
                'first_name'        => '管理者',
                'company'      => '株式会社テスト',
                'department'     => 'テスト部署',
                'email'        => 'admin@user.jp',
                'password'     => Hash::make('p1p1p1p1'),
                'status'       => '1',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
                'confirmed_at' => Carbon::now(),
            ],
            [
                'id'           => '2',
                'role'         => 'PROVIDER',
                'last_name'        => '配信確認',
                'first_name'        => '担当者',
                'company'      => '株式会社テスト',
                'department'     => 'テスト部署',
                'email'        => 'provider@user.jp',
                'password'     => Hash::make('p1p1p1p1'),
                'status'       => '1',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
                'confirmed_at' => Carbon::now(),
            ],
            [
                'id'           => '3',
                'role'         => 'OPERATOR',
                'last_name'        => '運用',
                'first_name'        => '担当者',
                'company'      => '株式会社テスト',
                'department'     => 'テスト部署',
                'email'        => 'operator@user.jp',
                'password'     => Hash::make('p1p1p1p1'),
                'status'       => '1',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
                'confirmed_at' => Carbon::now(),
            ],
            [
                'id'           => '4',
                'role'         => 'READER',
                'last_name'        => '閲覧',
                'first_name'        => '専用者',
                'company'      => '株式会社テスト',
                'department'     => 'テスト部署',
                'email'        => 'reader@user.jp',
                'password'     => Hash::make('p1p1p1p1'),
                'status'       => '1',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
                'confirmed_at' => Carbon::now(),
            ],
        ]);
        /*
        // テストデータ生成
        for ($i=0; $i<50000; $i++)
        {
            DB::table('users')->insert([
                    'role'         => array_rand(\Config::get('Fixed.role')),
                    'last_name'        => $faker->lastName,
                    'first_name'        => $faker->firstName,
                    'email'        => str_random(8) .'@user.jp',
                    'password'     => Hash::make('p1p1p1p1'),
                    'status'       => '1',
                    'created_at'   => Carbon::now(),
                    'updated_at'   => Carbon::now(),
                    'confirmed_at' => Carbon::now(),
            ]);
        }
        */
    }
}

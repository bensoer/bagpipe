<?php

class UserSeeder
    extends DatabaseSeeder
{
    public function run()
    {
        $users = [
            [
                //"username" => "bert.townshend",
                "password" => Hash::make("password"),
                "email"    => "ikben@shaw.ca"
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
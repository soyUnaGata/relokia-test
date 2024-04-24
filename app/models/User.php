<?php

namespace Models;

class User
{
    public $id;
    public $name;
    public $email;

    public static function createFromArray($data): User
    {
        $user = new User();

        $user->id = $data['id'];
        $user->name = $data['name'];
        $user->email = $data['email'];

        return $user;
    }
}
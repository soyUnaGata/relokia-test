<?php

namespace Models;

class Organization
{
    public $id;
    public $name;

    public static function createFromArray($data): Organization
    {
        $organization = new Organization();

        $organization->id = $data['id'];
        $organization->name = $data['name'];

        return $organization;
    }
}
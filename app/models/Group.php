<?php

namespace Models;

class Group
{
    public $id;
    public $name;

    public static function createFromArray($data): Group
    {
        $group = new Group();

        $group->id = $data['id'];
        $group->name = $data['name'];

        return $group;
    }
}
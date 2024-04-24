<?php

namespace Models;

class Comment
{
    public $id;
    public $body;

    public static function create($data) : Comment
    {
        $comment = new Comment();
        $comment->id = $data['id'];
        $comment->body = $data['body'];
        return $comment;
    }
}
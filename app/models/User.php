<?php

namespace Warkim\models;

use Warkim\helpers\Model;


class User extends Model
{
    protected $table = "users";

    public static function all()
    {
        return self::get_all((new self)->table);
    }

    public static function get($id)
    {
        $table = (new self)->table;

        return self::query("SELECT * FROM $table WHERE id = $id");
    }

    public static function getData()
    {
        return self::prepare("SELECT * FROM users WHERE id = ?", [1]);
    }
}

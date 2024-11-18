<?php

namespace Warkim\models;

use Warkim\core\Model;
use InvalidArgumentException;


class User extends Model
{
    protected $table        = "users";
    protected $primaryKey   = "id";
    protected $columns      = ["nama", "umur"];


    public static function getPrepare()
    {
        return self::prepare("SELECT * FROM users WHERE id = ?", [1]);
    }
}

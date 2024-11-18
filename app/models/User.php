<?php

namespace Warkim\models;

use Warkim\core\Model;


class User extends Model
{
    protected $table        = "users";
    protected $primaryKey   = "id";
    protected $columns      = ["nama", "umur"];
}

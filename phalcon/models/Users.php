<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $id;

    public $username;

    public $password;


    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users
     */

    public static function findFirst($parameters = null) {
        return parent::findFirst($parameters);
    }
}
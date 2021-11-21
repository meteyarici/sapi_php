<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Token extends Model
{
    public $id;

    public $token;

    public $user;

    public $expire;


    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Token
     */

    public static function findFirst($parameters = null) {
        return parent::findFirst($parameters);
    }
}
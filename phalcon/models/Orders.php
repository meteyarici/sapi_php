<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Orders extends Model
{

   public $id;

   public $orderCode;

   public $productid;

   public $quantity;

   public $address;

   public $shippingDate;

   public $owner;

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders
     */
    public static function findFirst($parameters = null) {
        return parent::findFirst($parameters);
    }
}
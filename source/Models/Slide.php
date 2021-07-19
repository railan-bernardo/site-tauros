<?php


namespace Source\Models;


use Source\Core\Model;

class Slide extends Model
{
    /**
     * Slide constructor.
     */
    public function __construct()
    {
        parent::__construct("slide", ["id"],["title"]);
    }



    /**
     * @return bool
     */
    public function save():bool
    {

        return parent::save();
    }
}
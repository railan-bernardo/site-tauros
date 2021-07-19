<?php


namespace Source\Models;

use Source\Core\Model;
class Gallerys extends Model
{
    /**
     * Slide constructor.
     */
    public function __construct()
    {
        parent::__construct("gallery_service", ["id"],["idservice"]);
    }



    /**
     * @return bool
     */
    public function save():bool
    {

        return parent::save();
    }
}
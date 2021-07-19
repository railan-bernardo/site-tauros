<?php


namespace Source\Models;

use Source\Core\Model;
class AbountGallery extends Model
{
    /**
     * Slide constructor.
     */
    public function __construct()
    {
        parent::__construct("about_gallery", ["id"],["id_about"]);
    }



    /**
     * @return bool
     */
    public function save():bool
    {

        return parent::save();
    }
}
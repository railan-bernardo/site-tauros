<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class Service
 * @package Source\Models
 */
class SitePost extends Model
{
    /**
     * Post constructor.
     */
    public function __construct()
    {
        parent::__construct("site", ["id"], ["title", "description",  "phone", "msg", "phone_wp", "email", "facebook", "instagram", "copyright", "meta_tag"]);
    }



    /**
     * @return bool
     */
    public function save(): bool
    {

        return parent::save();
    }
}
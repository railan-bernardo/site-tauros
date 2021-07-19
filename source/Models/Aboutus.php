<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class Service
 * @package Source\Models
 */
class Aboutus extends Model
{
    /**
     * Post constructor.
     */
    public function __construct()
    {
        parent::__construct("about", ["id"], ["title", "content"]);
    }


    /**
     * @return bool
     */
    public function save(): bool
    {
        return parent::save();
    }
}
<?php

namespace Source\Models;

use Source\Core\Model;

class Banner extends Model{

    public function __construct()
    {
        parent::__construct("banner", ["id"], ["name"]);
    }


    public function save(): bool
    {
        return parent::save();
    }

}

?>
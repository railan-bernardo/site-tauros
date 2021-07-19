<?php

namespace Source\Models;

use Source\Core\Model;

class Supplier extends Model{

  public function __construct(){
  	parent::__construct("supplier", ["id"], ["title"]);
  }

  public function save(): bool
  {
  	return parent::save();
  }

}

 ?>
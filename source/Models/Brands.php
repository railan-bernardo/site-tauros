<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class Service
 * @package Source\Models
 */
class Brands extends Model
{
    /**
     * Post constructor.
     */
    public function __construct()
    {
        parent::__construct("brands", ["id"], ["brand_name", "uri"]);
    }

    /**
     * @param string $uri
     * @param string $columns
     * @return null|Brands
     */
    public function findByUri(string $uri, string $columns = "*"): ?Brands
    {
        $find = $this->find("uri = :uri", "uri={$uri}", $columns);
        return $find->fetch();
    }

    /**
     * @return bool
     */
    public function save(): bool
    {

        return parent::save();
    }
}
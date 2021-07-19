<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class Category
 * @package Source\Models
 */
class ServiceCategory extends Model
{
    /**
     * Category constructor.
     */
    public function __construct()
    {
        parent::__construct("servicecategory", ["id"], ["title", "description"]);
    }

    /**
     * @param string $uri
     * @param string $columns
     * @return null|ServiceCategory
     */
    public function findByUri(string $uri, string $columns = "*"): ?ServiceCategory
    {
        $find = $this->find("uri = :uri", "uri={$uri}", $columns);
        return $find->fetch();
    }

    /**
     * @return Post
     */
    public function posts(): Service
    {
        return (new Service())->find("category = :id", "id={$this->id}");
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $checkUri = (new ServiceCategory())->find("uri = :uri AND id != :id", "uri={$this->uri}&id={$this->id}");

        if ($checkUri->count()) {
            $this->uri = "{$this->uri}-{$this->lastId()}";
        }

        return parent::save();
    }
}
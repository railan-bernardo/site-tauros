<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class Subcategory
 * @package Source\Models
 */
class Subcategory extends Model
{
    /**
     * Subcategory constructor.
     */
    public function __construct()
    {
        parent::__construct("subcategory", ["id"], ["idcategory","title", "description"]);
    }

    /**
     * @param null|string $terms
     * @param null|string $params
     * @param string $columns
     * @return mixed|Model
     */
    public function findPost(?string $terms = null, ?string $params = null, string $columns = "*")
    {
       

        return parent::find($terms, $params, $columns);
    }

    /**
     * @param string $uri
     * @param string $columns
     * @return null|Subcategory
     */
    public function findByUri(string $uri, string $columns = "*"): ?Subcategory
    {
        $find = $this->find("uri = :uri", "uri={$uri}", $columns);
        return $find->fetch();
    }

    /**
     * @return Service
     */
    public function posts(): Service
    {
        return (new Service())->find("subcategory = :id", "id={$this->id}");
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $checkUri = (new Subcategory())->find("uri = :uri AND id != :id", "uri={$this->uri}&id={$this->id}");

        if ($checkUri->count()) {
            $this->uri = "{$this->uri}-{$this->lastId()}";
        }

        return parent::save();
    }
}
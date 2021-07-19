<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Class Service
 * @package Source\Models
 */
class Service extends Model
{
    /**
     * Post constructor.
     */
    public function __construct()
    {
        parent::__construct("services", ["id"], ["title", "uri", "subtitle", "content", "code", "brand"]);
    }

    /**
     * @param null|string $terms
     * @param null|string $params
     * @param string $columns
     * @return mixed|Model
     */
    public function findPost(?string $terms = null, ?string $params = null, string $columns = "*")
    {
        $terms = "status = :status AND post_at <= NOW()" . ($terms ? " AND {$terms}" : "");
        $params = "status=post" . ($params ? "&{$params}" : "");

        return parent::find($terms, $params, $columns);
    }

    /**
     * @param string $uri
     * @param string $columns
     * @return null|Service
     */
    public function findByUri(string $uri, string $columns = "*"): ?Service
    {
        $find = $this->find("uri = :uri", "uri={$uri}", $columns);
        return $find->fetch();
    }


    /**
     * @return null|ServiceCategory
     */
    public function category(): ?ServiceCategory
    {
        if ($this->category) {
            return (new ServiceCategory())->findById($this->category);
        }
        return null;
    }

    /**
     * @return null|Subcategory
     */
    public function subcategory(): ?Subcategory
    {
        if ($this->subcategory) {
            return (new Subcategory())->findById($this->subcategory);
        }
        return null;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        $checkUri = (new Service())->find("uri = :uri AND id != :id", "uri={$this->uri}&id={$this->id}");

        if ($checkUri->count()) {
            $this->uri = "{$this->uri}-{$this->lastId()}";
        }

        return parent::save();
    }
}
<?php

namespace Lyncas\Models;

abstract class Model
{
    protected $primaryKey = 'id';
    protected $attributes = [];

    public function __construct()
    {
        $this->primaryKey = 'id';
        $this->attributes = [];
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function __get($attribute)
    {
        if (!isset($this->attributes[$attribute])) {
            return null;
        }

        return $this->attributes[$attribute];
    }

    public function __set($attribute, $value)
    {
        if ($attribute == $this->primaryKey || in_array($attribute, $this->fillable)) {
            $this->attributes[$attribute] = $value;
        }
    }
}

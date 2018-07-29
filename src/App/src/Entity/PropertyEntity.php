<?php

namespace App\Entity;

class PropertyEntity
{

    private $id;
    private $area;

    public function exchangeArray(array $array)
    {
        $this->id    = $array['id'];
        $this->area  = $array['area'];
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
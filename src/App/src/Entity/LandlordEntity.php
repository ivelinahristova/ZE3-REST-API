<?php

namespace App\Entity;

class LandlordEntity
{

    private $id;
    private $name;
    private $phone;
    private $personalId;

    public function exchangeArray(array $array)
    {
        $this->id    = $array['id'];
        $this->name  = $array['name'];
        $this->phone  = $array['phone'];
        $this->personalId  = $array['personal_id'];
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
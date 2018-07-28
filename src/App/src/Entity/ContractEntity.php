<?php

namespace App\Entity;

class ContractEntity
{

    private $number;
    private $type;
    private $startDate;
    private $endDate;
    private $price;
    private $rent;

    public function exchangeArray(array $array)
    {

        $this->number    = $array['number'];
        $this->type  = $array['type'];
        $this->startDate = $array['start_date'];
        $this->endDate = $array['end_date'];
        $this->price = $array['price'];
        $this->rent = $array['rent'];
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}
<?php

namespace App\Model;
use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
class LandlordModelFactory
{
    public function __invoke(ContainerInterface $container)
    {

        $adapter = $container->get(AdapterInterface::class);

        return new LandlordModel($adapter);
    }
}

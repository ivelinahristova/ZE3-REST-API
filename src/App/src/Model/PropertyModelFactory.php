<?php

namespace App\Model;
use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
class PropertyModelFactory
{
    public function __invoke(ContainerInterface $container)
    {

        $adapter = $container->get(AdapterInterface::class);

        return new PropertyModel($adapter);
    }
}

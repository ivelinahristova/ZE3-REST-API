<?php

namespace App\Action\Properties;

use App\Model\PropertyModel;
use Psr\Container\ContainerInterface;
use App\Model\ContractModel;

class GetPropertyFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(PropertyModel::class);

        return new GetPropertyAction($model);
    }
}

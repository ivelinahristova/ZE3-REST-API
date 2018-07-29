<?php

namespace App\Action\Properties;

use App\Model\PropertyModel;
use Psr\Container\ContainerInterface;

class DeletePropertyFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(PropertyModel::class);

        return new DeletePropertyAction($model);
    }
}

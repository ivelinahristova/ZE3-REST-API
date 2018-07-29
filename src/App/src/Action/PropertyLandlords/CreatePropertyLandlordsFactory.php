<?php

namespace App\Action\PropertyLandlords;

use App\Model\PropertyModel;
use Psr\Container\ContainerInterface;

class CreatePropertyLandlordsFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(PropertyModel::class);

        return new CreatePropertyLandlordsAction($model);
    }
}

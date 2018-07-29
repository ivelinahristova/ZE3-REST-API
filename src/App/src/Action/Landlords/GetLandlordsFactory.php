<?php

namespace App\Action\Landlords;

use App\Model\LandlordModel;
use Psr\Container\ContainerInterface;

class GetLandlordsFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(LandlordModel::class);

        return new GetLandlordsAction($model);
    }
}

<?php

namespace App\Action\Landlords;

use App\Model\LandlordModel;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;

class CreateLandlordFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(LandlordModel::class);
        $helper = $container->get(UrlHelper::class);

        return new CreateLandlordAction($model, $helper);
    }
}

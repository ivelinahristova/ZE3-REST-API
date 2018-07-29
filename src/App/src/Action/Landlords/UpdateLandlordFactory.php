<?php

namespace App\Action\Landlords;

use App\Model\LandlordModel;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;

class UpdateLandlordFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(LandlordModel::class);
        $urlHelper = $container->get(UrlHelper::class);

        return new UpdateLandlordAction($model, $urlHelper);
    }
}

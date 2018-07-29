<?php

namespace App\Action\Properties;

use App\Model\PropertyModel;
use Psr\Container\ContainerInterface;
use App\Model\ContractModel;
use Zend\Expressive\Helper\UrlHelper;

class CreatePropertyFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(PropertyModel::class);
        $helper = $container->get(UrlHelper::class);

        return new CreatePropertyAction($model, $helper);
    }
}

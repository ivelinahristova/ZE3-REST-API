<?php

namespace App\Action\Properties;

use App\Model\PropertyModel;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;

class UpdatePropertyFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(PropertyModel::class);
        $urlHelper = $container->get(UrlHelper::class);

        return new UpdatePropertyAction($model, $urlHelper);
    }
}

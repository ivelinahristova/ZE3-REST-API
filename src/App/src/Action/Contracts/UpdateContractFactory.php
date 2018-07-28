<?php

namespace App\Action\Contracts;

use Psr\Container\ContainerInterface;
use App\Model\ContractModel;
use Zend\Expressive\Helper\UrlHelper;

class UpdateContractFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(ContractModel::class);
        $urlHelper = $container->get(UrlHelper::class);

        return new UpdateContractAction($model, $urlHelper);
    }
}

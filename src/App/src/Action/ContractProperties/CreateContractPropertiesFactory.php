<?php

namespace App\Action\ContractProperties;

use Psr\Container\ContainerInterface;
use App\Model\ContractModel;

class CreateContractPropertiesFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(ContractModel::class);

        return new CreateContractPropertiesAction($model);
    }
}

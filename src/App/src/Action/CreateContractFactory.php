<?php

namespace App\Action;

use Psr\Container\ContainerInterface;
use App\Model\ContractModel;

class CreateContractFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $model = $container->get(ContractModel::class);

        return new CreateContractAction($model);
    }
}

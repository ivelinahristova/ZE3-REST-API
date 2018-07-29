<?php

namespace App\Action\Contracts;

use App\Entity\ContractEntity;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Model\ContractModel;
use Zend\Diactoros\Response\EmptyResponse;

class GetContractsAction implements ServerMiddlewareInterface
{
    private $model;

    public function __construct(
        ContractModel $model
    ) {
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $params = $request->getQueryParams();
        $type = isset($params['type']) ? $params['type'] : false;

        if($type && array_key_exists($type, ContractEntity::TYPES)) {
            $contracts = $this->model->getContractsByType($type);
        } else {
            $contracts = $this->model->getContracts();
        }

        return new JsonResponse($contracts);
    }
}

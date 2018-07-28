<?php

namespace App\Action;

use App\Entity\ContractEntity;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Model\ContractModel;
use Zend\Diactoros\Response\EmptyResponse;
use Fig\Http\Message\StatusCodeInterface;
use Zend\Diactoros\Response\TextResponse;

class GetContractAction implements ServerMiddlewareInterface
{
    private $model;

    public function __construct(
        ContractModel $model
    ) {
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $number = $request->getAttribute('number');
        $contract = null;
        try {
            $contract = $this->model->getContract($number);
        } catch (\DomainException $exception) {
            return new TextResponse($exception->getMessage(), StatusCodeInterface::STATUS_NOT_FOUND);
        } catch (\Exception $exception) {
            return new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        if($contract instanceof ContractEntity) {
            return new JsonResponse($contract->getArrayCopy(),StatusCodeInterface::STATUS_FOUND);
        }

        return new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
    }
}

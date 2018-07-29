<?php

namespace App\Action\Properties;

use App\Entity\ContractEntity;
use App\Entity\PropertyEntity;
use App\Model\PropertyModel;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Model\ContractModel;
use Zend\Diactoros\Response\EmptyResponse;
use Fig\Http\Message\StatusCodeInterface;
use Zend\Diactoros\Response\TextResponse;

class GetPropertyAction implements ServerMiddlewareInterface
{
    private $model;

    public function __construct(
        PropertyModel $model
    ) {
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $id = $request->getAttribute('id');
        $property = null;
        try {
            $property = $this->model->getProperty($id);
        } catch (\DomainException $exception) {
            return new TextResponse($exception->getMessage(), StatusCodeInterface::STATUS_NOT_FOUND);
        } catch (\Exception $exception) {
            return new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        if($property instanceof PropertyEntity) {
            return new JsonResponse($property->getArrayCopy(),StatusCodeInterface::STATUS_FOUND);
        }

        return new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
    }
}

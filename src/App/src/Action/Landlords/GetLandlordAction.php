<?php

namespace App\Action\Landlords;

use App\Entity\ContractEntity;
use App\Entity\LandlordEntity;
use App\Entity\PropertyEntity;
use App\Model\LandlordModel;
use App\Model\PropertyModel;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Model\ContractModel;
use Zend\Diactoros\Response\EmptyResponse;
use Fig\Http\Message\StatusCodeInterface;
use Zend\Diactoros\Response\TextResponse;

class GetLandlordAction implements ServerMiddlewareInterface
{
    private $model;

    public function __construct(
        LandlordModel $model
    ) {
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {

        $id = $request->getAttribute('id');
        $landlord = null;
        try {
            $landlord = $this->model->getLandlord($id);
        } catch (\DomainException $exception) {
            return new TextResponse($exception->getMessage(), StatusCodeInterface::STATUS_NOT_FOUND);
        } catch (\Exception $exception) {
            return new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        if($landlord instanceof LandlordEntity) {
            return new JsonResponse($landlord->getArrayCopy(),StatusCodeInterface::STATUS_FOUND);
        }

        return new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
    }
}

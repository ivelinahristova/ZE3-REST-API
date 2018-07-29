<?php

namespace App\Action\Landlords;

use App\Model\LandlordModel;
use App\Model\PropertyModel;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class GetLandlordsAction implements ServerMiddlewareInterface
{
    private $model;

    public function __construct(
        LandlordModel $model
    ) {
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $properties = $this->model->getLandlords();

        return new JsonResponse($properties);
    }
}

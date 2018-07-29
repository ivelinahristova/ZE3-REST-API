<?php

namespace App\Action\Contracts;

use App\Model\PropertyModel;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class GetPropertiesAction implements ServerMiddlewareInterface
{
    private $model;

    public function __construct(
        PropertyModel $model
    ) {
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $number = $request->getAttribute('number');
        $contracts = $this->model->getPropertiesByContract($number);

        return new JsonResponse($contracts);
    }
}

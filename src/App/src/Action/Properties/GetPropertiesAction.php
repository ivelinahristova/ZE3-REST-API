<?php

namespace App\Action\Properties;

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
        $properties = $this->model->getProperties();

        return new JsonResponse($properties);
    }
}

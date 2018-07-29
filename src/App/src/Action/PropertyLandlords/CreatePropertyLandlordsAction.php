<?php

namespace App\Action\PropertyLandlords;

use App\Model\PropertyModel;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Model\ContractModel;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\TextResponse;
use Fig\Http\Message\StatusCodeInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;

class CreatePropertyLandlordsAction implements ServerMiddlewareInterface
{
    private $model;

    public function __construct(
        PropertyModel $model
    ) {
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $params = $request->getParsedBody();

        try {
            $this->model->AddLandlord($params);
            $response = new EmptyResponse(StatusCodeInterface::STATUS_CREATED);
        } catch (\InvalidArgumentException $exception) {
            $response = new TextResponse($exception->getMessage(), StatusCodeInterface::STATUS_BAD_REQUEST);
        } catch (InvalidQueryException $exception) {
            var_dump($exception->getMessage()); exit;
            $response = new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}

<?php

namespace App\Action\Landlords;

use App\Model\LandlordModel;
use App\Model\PropertyModel;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\TextResponse;
use Fig\Http\Message\StatusCodeInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;

class DeleteLandlordAction implements ServerMiddlewareInterface
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

        try {
            $this->model->DeleteLandlord($id);
            $response = new EmptyResponse(StatusCodeInterface::STATUS_NO_CONTENT);

        } catch (\InvalidArgumentException $exception) {
            $response = new TextResponse($exception->getMessage(), StatusCodeInterface::STATUS_BAD_REQUEST);
        } catch (InvalidQueryException $exception) {
            $response = new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}

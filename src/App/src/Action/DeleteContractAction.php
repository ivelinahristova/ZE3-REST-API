<?php

namespace App\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Model\ContractModel;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\TextResponse;
use Fig\Http\Message\StatusCodeInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;

class DeleteContractAction implements ServerMiddlewareInterface
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

        try {
            $contractId = $this->model->DeleteContract($number);
            $response = new JsonResponse(['number' => $contractId], StatusCodeInterface::STATUS_OK);

        } catch (\InvalidArgumentException $exception) {
            $response = new TextResponse($exception->getMessage(), StatusCodeInterface::STATUS_BAD_REQUEST);
        } catch (InvalidQueryException $exception) {
            $response = new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}

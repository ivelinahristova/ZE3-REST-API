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
use Zend\Expressive\Helper\UrlHelper;

class UpdateContractAction implements ServerMiddlewareInterface
{
    private $model;
    private $helper;

    public function __construct(
        ContractModel $model,
        UrlHelper $helper
    ) {
        $this->model = $model;
        $this->helper = $helper;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $params = $request->getParsedBody();
        $number = $request->getAttribute('number');

        try {
            $contractId = $this->model->UpdateContract($params, $number);
            if(is_null($contractId)) {
                $response = new TextResponse('Nothing to update', StatusCodeInterface::STATUS_BAD_REQUEST);
            } else {
                $uri = $this->helper->generate('contracts.get', ['number' => $number]);
                $response = new EmptyResponse(StatusCodeInterface::STATUS_OK);
                $response->withHeader('Location', $uri);
            }
        } catch (\InvalidArgumentException $exception) {
            $response = new TextResponse($exception->getMessage(), StatusCodeInterface::STATUS_BAD_REQUEST);
        } catch (InvalidQueryException $exception) {
            $response = new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}

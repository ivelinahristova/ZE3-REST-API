<?php

namespace App\Action\Properties;

use App\Model\PropertyModel;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\TextResponse;
use Fig\Http\Message\StatusCodeInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Expressive\Helper\UrlHelper;

class CreatePropertyAction implements ServerMiddlewareInterface
{
    private $model;
    private $helper;

    public function __construct(
        PropertyModel $model,
        UrlHelper $helper
    ) {
        $this->model = $model;
        $this->helper = $helper;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $params = $request->getParsedBody();

        try {
            $id = $this->model->createProperty($params);
            $uri = $this->helper->generate('properties.get', ['id' => $id]);
            $response = new EmptyResponse(StatusCodeInterface::STATUS_OK);
            $response->withHeader('Location', $uri);
        } catch (\InvalidArgumentException $exception) {
            $response = new TextResponse($exception->getMessage(), StatusCodeInterface::STATUS_BAD_REQUEST);
        } catch (InvalidQueryException $exception) {
            $response = new EmptyResponse(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}

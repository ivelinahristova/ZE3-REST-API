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
use Zend\Expressive\Helper\UrlHelper;

class CreateLandlordAction implements ServerMiddlewareInterface
{
    private $model;
    private $helper;

    public function __construct(
        LandlordModel $model,
        UrlHelper $helper
    ) {
        $this->model = $model;
        $this->helper = $helper;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $params = $request->getParsedBody();

        try {
            $id = $this->model->createLandlord($params);
            $uri = $this->helper->generate('landlords.get', ['id' => $id]);
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

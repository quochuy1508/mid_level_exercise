<?php

namespace Magenest\CustomRouter\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @param ActionFactory $actionFactory
     */
    public function __construct(
        ActionFactory $actionFactory
    ) {
        $this->actionFactory = $actionFactory;
    }

    /**
     * Match corresponding URL Rewrite and modify request.
     *
     * @param RequestInterface|HttpRequest $request
     * @return ActionInterface|null
     * @throws NoSuchEntityException
     */
    public function match(RequestInterface $request)
    {
        try {
            $pathInfo = $request->getPathInfo();
            if ($pathInfo) {
                $patternOnlyPriceNumber = '/\d+(\.\d{1,2})?-\d+(\.\d{1,2})?/';
                $patternPrice = '/-price-\d+(\.\d{1,2})?-\d+(\.\d{1,2})?/';
                if (
                    preg_match($patternOnlyPriceNumber, $pathInfo, $matchOnlyPrice) &&
                    preg_match($patternPrice, $pathInfo, $matchAllParam)
                ) {
                    $filterPrice = $matchOnlyPrice[0];
                    $pathNotParams = preg_replace($patternPrice, '', $pathInfo);
                    $request->setPathInfo('' . $pathNotParams)->setParam('price', $filterPrice);
                    return $this->actionFactory->create(
                        Forward::class
                    );
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } catch (\Exception $exception) {
            return null;
        }
    }
}

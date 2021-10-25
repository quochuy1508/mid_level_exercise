<?php

namespace Magenest\Logger\Controller\Index;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Index
 */
class Index implements ActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var Json
     */
    private $json;

    public function __construct(
        LoggerInterface $logger,
        RequestInterface $request,
        RemoteAddress $remoteAddress,
        Json $json,
        ResultFactory $resultFactory
    ) {
        $this->logger = $logger;
        $this->request = $request;
        $this->remoteAddress = $remoteAddress;
        $this->json = $json;
        $this->resultFactory = $resultFactory;
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        $this->logger->info('IP = ' . $this->remoteAddress->getRemoteAddress() . ' - ' . $this->json->serialize($this->request->getParams()));
        return $resultRedirect->setPath('/');
    }
}

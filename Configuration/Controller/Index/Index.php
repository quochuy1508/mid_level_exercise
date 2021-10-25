<?php

namespace Magenest\Configuration\Controller\Index;

use Magento\Framework\View\Result\PageFactory;

class Index implements \Magento\Framework\App\ActionInterface
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return PageFactory
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Configuration Front View'));
        return $resultPage;
    }
}

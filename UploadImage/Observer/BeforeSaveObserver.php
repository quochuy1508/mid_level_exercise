<?php

namespace Magenest\UploadImage\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\Event\ObserverInterface;
use Magenest\UploadImage\Model\ImageUploader;
use Magento\Customer\Model\ResourceModel\CustomerFactory;

class BeforeSaveObserver implements ObserverInterface
{
    protected $_customerRepositoryInterface;

    /**
     * @var ImageUploader
     */
    private $imageUploader;
    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * @var Customer
     */
    private $customer;

    public function __construct(
        ImageUploader $imageUploader,
        CustomerRepositoryInterface $customerRepositoryInterface,
        CustomerFactory $customerFactory,
        Customer $customer
    ) {
        $this->imageUploader = $imageUploader;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->customerFactory = $customerFactory;
        $this->customer = $customer;
    }

    /**
     * Address before save event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $request = $observer->getEvent()->getRequest();
        $customer = $observer->getEvent()->getCustomer();

        $data = $request->getPostValue();

        if (isset($data['customer_image']['image'][0]['name']) && isset($data['customer_image']['image'][0]['tmp_name'])) {
            $data['image'] = $data['customer_image']['image'][0]['name'];
            $this->imageUploader->moveFileFromTmp($data['image']);
        } elseif (isset($data['customer_image']['image'][0]['name']) && !isset($data['customer_image']['image'][0]['tmp_name'])) {
            $data['image'] = $data['customer_image']['image'][0]['name'];
        } else {
            $data['image'] = '';
        }

        $customer->setCustomAttribute('image_customer', $data['image']);
    }
}

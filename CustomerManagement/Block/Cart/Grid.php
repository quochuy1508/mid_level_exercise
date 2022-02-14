<?php

namespace Magenest\CustomerManagement\Block\Cart;

use Magenest\CustomerManagement\Model\ResourceModel\SavedCart\Collection;
use Magenest\CustomerManagement\Model\ResourceModel\SavedCart\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Address\ToOrderAddress;
use Magento\Sales\Model\Order\Config;
use Magento\Theme\Block\Html\Pager;
use Magento\Sales\Model\Order\Address\Renderer;

/**
 * Sales order history block
 *
 * @api
 * @since 100.0.2
 */
class Grid extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Magenest_CustomerManagement::cart/grid.phtml';

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var Config
     */
    protected $_orderConfig;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $orders;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var Renderer
     */
    private $addressRenderer;

    /**
     * @var ToOrderAddress
     */
    private $quoteToOrderAddressConverter;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param Session $customerSession
     * @param Config $orderConfig
     * @param ResourceConnection $resourceConnection
     * @param Renderer $addressRenderer
     * @param CartRepositoryInterface $quoteRepository
     * @param ToOrderAddress $quoteToOrderAddressConverter
     * @param array $data
     */
    public function __construct(
        Context            $context,
        CollectionFactory  $collectionFactory,
        Session            $customerSession,
        Config             $orderConfig,
        ResourceConnection $resourceConnection,
        Renderer $addressRenderer,
        CartRepositoryInterface $quoteRepository,
        ToOrderAddress $quoteToOrderAddressConverter,
        array              $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->resourceConnection = $resourceConnection;
        $this->addressRenderer = $addressRenderer;
        $this->quoteRepository = $quoteRepository;
        $this->quoteToOrderAddressConverter = $quoteToOrderAddressConverter;
        parent::__construct($context, $data);
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getViewUrl($order)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $order->getId()]);
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getSendEmailUrl($entityId)
    {
        return $this->getUrl('customermanagement/cart/sendEmail', ['id' => $entityId]);
    }

    /**
     * Get reorder URL
     *
     * @param string $quoteId
     * @return string
     */
    public function getRestoreCartUrl($quoteId)
    {
        return $this->getUrl('customermanagement/cart/restore', ['quote_id' => $quoteId]);
    }

    /**
     * Get delete URL
     *
     * @return string
     */
    public function getDeleteUrl($quoteId)
    {
        return $this->getUrl('customermanagement/cart/delete', ['quote_id' => $quoteId]);
    }

    /**
     * Get message for no orders.
     *
     * @return Phrase
     * @since 102.1.0
     */
    public function getEmptyOrdersMessage()
    {
        return __('You have no saved cart.');
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Saved Card'));
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getSavedCarts()) {
            $pager = $this->getLayout()->createBlock(
                Pager::class,
                'saved.cart.grid.pager'
            )->setCollection(
                $this->getSavedCarts()
            );
            $this->setChild('pager', $pager);
            $this->getSavedCarts()->load();
        }
        return $this;
    }

    /**
     * Get customer orders
     *
     * @return bool|Collection
     */
    public function getSavedCarts()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create()
                ->addFieldToSelect(
                    '*'
                )->addFieldToFilter(
                    'customer_id',
                    ['eq' => $customerId]
                )->setOrder(
                    'entity_id',
                    'desc'
                );
        }
        return $this->orders;
    }

    /**
     * Provide order collection factory
     *
     * @return CollectionFactory
     */
    private function getOrderCollectionFactory()
    {
        if ($this->collectionFactory === null) {
            $this->collectionFactory = ObjectManager::getInstance()->get(CollectionFactory::class);
        }
        return $this->collectionFactory;
    }

    /**
     * @param $quoteId
     * @return array
     */
    public function getInformationByQuoteId($quoteId)
    {
        $result = null;
        try {
            $quote = $this->quoteRepository->get($quoteId);
            $result['address'] = $this->getFormattedAddress($quote->getBillingAddress());
            $result['items'] = '';
            $result['qty'] = $quote->getItemsQty();
            $result['coupon_code'] = $quote->getCouponCode() ?? null;
            $result['shipping_method'] = $quote->getShippingAddress()->getShippingMethod() ?? null;
            $result['payment_method'] = $quote->getPayment()->getMethod() ?? null;
            foreach ($quote->getItems() as $item) {
                $result['items'] .= $item->getName() . '<br>';
            }
        } catch (\Exception $exception) {

        }

        return $result;
    }

    /**
     * @param $address
     * @return string|null
     * @throws \Exception
     */
    public function getFormattedAddress($address) {

        if ($address instanceof \Magento\Quote\Model\Quote\Address) {
            $address = $this->quoteToOrderAddressConverter->convert($address);
        }

        if (!$address instanceof \Magento\Sales\Model\Order\Address) {
            throw new \Exception(__('Expected instance of \Magento\Sales\Model\Order\Address, got ' . get_class($address)));
        }

        return $this->addressRenderer->format($address, 'html');
    }
}

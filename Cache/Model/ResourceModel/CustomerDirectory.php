<?php

namespace Magenest\Cache\Model\ResourceModel;

use Magenest\Cache\Helper\CacheHelper;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Serialize\Serializer\Json;

class CustomerDirectory extends AbstractDb
{
    /**
     * @var CacheInterface
     */
    protected $_cache;

    /**
     * @var CacheHelper
     */
    protected $cacheHelper;

    /**
     * @var Json
     * @since 101.0.0
     */
    protected $serializer;

    public function __construct(
        Context        $context,
        CacheInterface $cache,
        CacheHelper $cacheHelper,
        Json $serializer
    ) {
        $this->_cache = $cache;
        $this->cacheHelper = $cacheHelper;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('customer_directory', 'entity_id');
    }

    /**
     * Load an object
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        $cacheKey = $this->cacheHelper->getCacheKeyCustomerDirectoryEntity($value);
        if (
            $this->cacheHelper->isCacheEnabled() &&
            $data = $this->_cache->load($cacheKey)
        ) {
            $object->beforeLoad($value, $field);
            $object->setData($this->serializer->unserialize($data));
            $this->unserializeFields($object);
            $this->_afterLoad($object);
            $object->afterLoad();
            $object->setOrigData();
            $object->setHasDataChanges(false);
            return $this;
        } else {
            $result = parent::load($object, $value, $field);
            $this->_cache->save(
                $this->serializer->serialize($object->getData()),
                $cacheKey,
                [
                    Config::TYPE_IDENTIFIER
                ]
            );
            return $result;
        }
    }

    /**
     * Perform actions before entity save
     *
     * @param \Magento\Framework\DataObject $object
     * @return void
     * @since 100.1.0
     */
    public function beforeSave(\Magento\Framework\DataObject $object)
    {
        parent::beforeSave($object);
        $this->_cache->clean([$this->cacheHelper->getCacheKeyCustomerDirectoryEntity($object->getEntityId())]);
    }


    /**
     * Perform actions after entity save
     *
     * @param \Magento\Framework\DataObject $object
     * @return void
     * @since 100.1.0
     */
    public function afterSave(\Magento\Framework\DataObject $object)
    {
        parent::afterSave($object);
        $this->_cache->save(
            $this->serializer->serialize($object->getData()),
            $this->cacheHelper->getCacheKeyCustomerDirectoryEntity($object->getEntityId()),
            [
                Config::TYPE_IDENTIFIER
            ]
        );
    }
}

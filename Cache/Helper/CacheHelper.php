<?php

namespace Magenest\Cache\Helper;

use Magenest\Cache\Model\CustomerDirectory;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Cache\Type\Config;

class CacheHelper
{
    /**
     * @var StateInterface
     */
    protected $_cacheState;

    public function __construct(StateInterface $state)
    {
        $this->_cacheState = $state;
    }

    /**
     * Check Config cache availability
     *
     * @return bool
     */
    public function isCacheEnabled(): bool
    {
        return $this->_cacheState->isEnabled(Config::TYPE_IDENTIFIER);
    }

    /**
     * @param string $entityId
     * @return string
     */
    public function getCacheKeyCustomerDirectoryEntity(string $entityId): string
    {
        return CustomerDirectory::CACHE_TAG . '_' . $entityId;
    }
}

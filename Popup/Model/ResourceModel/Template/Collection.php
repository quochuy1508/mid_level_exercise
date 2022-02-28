<?php
namespace Magenest\Popup\Model\ResourceModel\Template;

use Magenest\Popup\Model\ResourceModel\Template;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = 'template_id';

    public function _construct()
    {
        $this->_init(\Magenest\Popup\Model\Template::class, Template::class);
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('template_id', 'template_name');
    }

    /**
     * @return Collection
     */
    public function reset()
    {
        $this->_totalRecords = null;
        return $this->_reset();
    }
}

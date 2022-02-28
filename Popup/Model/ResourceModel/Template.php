<?php
namespace Magenest\Popup\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Template extends AbstractDb
{
    public function _construct()
    {
        $this->_init('magenest_popup_templates', 'template_id');
    }

    /**
     * @param $data
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function insertMultiple($data)
    {
        $this->getConnection()->insertMultiple($this->getMainTable(), $data);
    }

    /**
     * @param $ids
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteMultiple($ids)
    {
        $this->getConnection()->delete($this->getMainTable(), "{$this->getIdFieldName()} in ({$ids})");
    }
}

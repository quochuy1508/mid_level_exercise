<?php
namespace Magenest\Popup\Model;

use Magento\Framework\Model\AbstractModel;

class Template extends AbstractModel
{
    const YESNO_BUTTON = 1;
    const CONTACT_FORM = 2;
    const SHARE_SOCIAL = 3;
    const SUBCRIBE     = 4;
    const STATIC_POPUP = 5;
    const HOT_DEAL = 6;

    public function _construct()
    {
        $this->_init(ResourceModel\Template::class);
    }

    /**
     * Retrieve template text wrapper
     *
     * @return string
     */
    public function getHtmlContent()
    {
        if (!$this->getData('html_content') && !$this->getTemplateId()) {
            $this->setData('html_content', null);
        }

        return $this->getData('html_content');
    }
}

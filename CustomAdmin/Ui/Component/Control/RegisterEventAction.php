<?php

namespace Magenest\CustomAdmin\Ui\Component\Control;

use Magento\Ui\Component\Control\Action;

/**
 * Class PdfAction
 */
class RegisterEventAction extends Action
{
    /**
     * Prepare
     *
     * @return void
     */
    public function prepare()
    {
//        $config = $this->getConfiguration();
//        $context = $this->getContext();
//        $config['url'] = $context->getUrl(
//            $config['pdfAction'],
//            ['order_id' => $context->getRequestParam('order_id')]
//        );
//        $this->setData('config', (array)$config);
        parent::prepare();
    }
}

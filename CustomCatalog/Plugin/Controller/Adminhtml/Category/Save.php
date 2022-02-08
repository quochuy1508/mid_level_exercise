<?php

namespace Magenest\CustomCatalog\Plugin\Controller\Adminhtml\Category;

/**
 * Class Plugin Before Save
 */
class Save
{
    /**
     * @param $subject
     * @param $data
     * @param null $stringToBoolInputs
     * @return array
     */
    public function beforeStringToBoolConverting($subject, $data, $stringToBoolInputs = null)
    {
        if (is_array($stringToBoolInputs)) {
            array_push($stringToBoolInputs, 'use_as_main_breadcrumb');
        }

        return [$data, $stringToBoolInputs];
    }
}

<?php

namespace Magenest\Configuration\Model;

use Magento\Framework\Config\Data;

class Config
{
    private $configData;

    public function __construct(Data $configData)
    {
        $this->configData = $configData;
    }

    public function get()
    {
        return $this->configData->get();
    }
}

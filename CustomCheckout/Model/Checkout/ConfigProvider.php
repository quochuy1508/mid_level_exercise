<?php

namespace Magenest\CustomCheckout\Model\Checkout;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\UrlInterface;
use Vertex\AddressValidation\Model\Config;

class ConfigProvider implements ConfigProviderInterface
{
    const DELIVERY_TIME_ADDRESS_VALIDATION_CONFIG = 'deliveryTimeAddressValidationConfig';
    const DELIVERY_TIME_ADDRESS_VALIDATION_URL = 'deliveryTimeAddressValidationConfigUrl';

    /** @var Config */
    private $config;

    /**
     * Url Builder
     *
     * @var UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        Config $config,
        UrlInterface $urlBuilder
    ) {
        $this->config = $config;
        $this->urlBuilder = $urlBuilder;
    }

    public function getConfig() : array
    {
        return [
            self::DELIVERY_TIME_ADDRESS_VALIDATION_CONFIG => [
                self::DELIVERY_TIME_ADDRESS_VALIDATION_URL   => $this->urlBuilder->getUrl('customCheckout/validate/deliveryTime')
            ]
        ];
    }
}

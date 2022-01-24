<?php

namespace Magenest\CustomCatalog\Block\Product\Renderer;

use Magento\Catalog\Model\Product;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Swatches\Block\Product\Renderer\Configurable;
use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as TypeConfigurable;

class RemainedAttribute extends TypeConfigurable implements IdentityInterface
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     * @since 100.1.0
     */
    public function getIdentities()
    {
        if ($this->product instanceof IdentityInterface) {
            return $this->product->getIdentities();
        } else {
            return [];
        }
    }

    /**
     * Set product to block
     *
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Override parent function
     *
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->product) {
            $this->product = parent::getProduct();
        }

        return $this->product;
    }
}

<?php

namespace Magenest\Configuration\Block;

use Magento\Framework\Acl\AclResource\TreeBuilder;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magenest\Configuration\Model\ConfigFactory;
use Magenest\Configuration\Model\Config;
use Magento\Integration\Helper\Data;

class Configuration extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Magenest_Configuration::custom_configuration.phtml';

    /**
     * @var ConfigFactory
     */
    private $configFactory;

    /**
     * @var Json
     */
    protected $encoder;

    /**
     * @var TreeBuilder
     */
    protected $_resourceTreeBuilder;

    /**
     * @var Data
     */
    protected $_integrationData;

    public function __construct(
        Template\Context $context,
        ConfigFactory $configFactory,
        Json $encoder,
        TreeBuilder $resourceTreeBuilder,
        Data $integrationData,
        array $data = []
    ) {
        $this->configFactory = $configFactory;
        $this->encoder = $encoder;
        $this->_resourceTreeBuilder = $resourceTreeBuilder;
        $this->_integrationData = $integrationData;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * Get Json Representation of Resource Tree
     *
     * @return string
     */
    public function getTree()
    {
        $aclResourcesTree =  $this->_integrationData->mapResources($this->getAclResources());
        return $this->encoder->serialize($aclResourcesTree);
    }

    /**
     * Get lit of all ACL resources declared in the system.
     *
     * @return array
     */
    public function getAclResources()
    {
        /** @var Config $config */
        $config = $this->configFactory->create();
        $resources = $config->get();
        if (!empty($resources['config']['acl']['resources'])) {
            $tree = $this->build($resources['config']['acl']['resources']);
        } else {
            $tree = [];
        }

        $configResource = array_filter(
            $tree,
            function ($node) {
                return isset($node['id'])
                    && $node['id'] == 'Magento_Frontend::customer';
            }
        );
        $configResource = reset($configResource);
        return $configResource['children'] ?? [];
    }

    /**
     * Transform resource list into sorted resource tree that includes only active resources
     *
     * @param array $resourceList
     * @return array
     */
    private function build(array $resourceList)
    {
        $result = [];
        foreach ($resourceList as $resource) {
            if ($resource['disabled']) {
                continue;
            }

            if ($resource['visible']) {
                continue;
            }
            unset($resource['disabled']);
            unset($resource['visible']);
            $resource['children'] = $this->build($resource['children']);
            $result[] = $resource;
        }
        usort($result, [$this, '_sortTree']);
        return $result;
    }

    /**
     * Sort ACL resource nodes
     *
     * @param array $nodeA
     * @param array $nodeB
     * @return int
     */
    private function _sortTree(array $nodeA, array $nodeB)
    {
        return $nodeA['sortOrder'] < $nodeB['sortOrder'] ? -1 : ($nodeA['sortOrder'] > $nodeB['sortOrder'] ? 1 : 0);
    }
}

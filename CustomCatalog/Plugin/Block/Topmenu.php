<?php

namespace Magenest\CustomCatalog\Plugin\Block;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\StateDependentCollectionFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class Topmenu extends \Magento\Catalog\Plugin\Block\Topmenu
{
    /**
     * Catalog category
     *
     * @var \Magento\Catalog\Helper\Category
     */
    protected $catalogCategory;

    /**
     * @var StateDependentCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Resolver
     */
    private $layerResolver;

    public function __construct(
        \Magento\Catalog\Helper\Category $catalogCategory,
        StateDependentCollectionFactory  $categoryCollectionFactory,
        StoreManagerInterface            $storeManager,
        Resolver                         $layerResolver
    ) {
        $this->catalogCategory = $catalogCategory;
        $this->collectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
        $this->layerResolver = $layerResolver;
        parent::__construct($catalogCategory, $categoryCollectionFactory, $storeManager, $layerResolver);
    }

    /**
     * Build category tree for menu block.
     *
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return void
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
                                          $outermostClass = '',
                                          $childrenWrapClass = '',
                                          $limit = 0
    )
    {
        $rootId = $this->storeManager->getStore()->getRootCategoryId();
        $storeId = $this->storeManager->getStore()->getId();
        /** @var Collection $collection */
        $collection = $this->getCategoryTree($storeId, $rootId);
        $currentCategory = $this->getCurrentCategory();
        $mapping = [$rootId => $subject->getMenu()];  // use nodes stack to avoid recursion
        foreach ($collection as $category) {
            $categoryParentId = $category->getParentId();
            if (!isset($mapping[$categoryParentId])) {
                $parentIds = $category->getParentIds();
                foreach ($parentIds as $parentId) {
                    if (isset($mapping[$parentId])) {
                        $categoryParentId = $parentId;
                    }
                }
            }

            /** @var Node $parentCategoryNode */
            $parentCategoryNode = $mapping[$categoryParentId];

            $categoryNode = new Node(
                $this->getCategoryAsArray(
                    $category,
                    $currentCategory,
                    $category->getParentId() == $categoryParentId
                ),
                'id',
                $parentCategoryNode->getTree(),
                $parentCategoryNode
            );
            $parentCategoryNode->addChild($categoryNode);

            $mapping[$category->getId()] = $categoryNode; //add node in stack
        }
    }

    /**
     * Get Category Tree
     *
     * @param int $storeId
     * @param int $rootId
     * @return Collection
     * @throws LocalizedException
     */
    protected function getCategoryTree($storeId, $rootId)
    {
        return parent::getCategoryTree($storeId, $rootId)->addAttributeToSelect('use_as_main_breadcrumb');
    }

    /**
     * Get current Category from catalog layer
     *
     * @return Category
     */
    private function getCurrentCategory()
    {
        $catalogLayer = $this->layerResolver->get();

        if (!$catalogLayer) {
            return null;
        }

        return $catalogLayer->getCurrentCategory();
    }

    /**
     * Convert category to array
     *
     * @param Category $category
     * @param Category $currentCategory
     * @param bool $isParentActive
     * @return array
     */
    private function getCategoryAsArray($category, $currentCategory, $isParentActive)
    {
        $categoryId = $category->getId();
        return [
            'name' => $category->getName(),
            'id' => 'category-node-' . $categoryId,
            'url' => $this->catalogCategory->getCategoryUrl($category),
            'has_active' => in_array((string)$categoryId, explode('/', (string)$currentCategory->getPath()), true),
            'is_active' => $categoryId == $currentCategory->getId(),
            'is_category' => true,
            'is_parent_active' => $isParentActive,
            'use_as_main_breadcrumb' => $category->getUseAsMainBreadcrumb() ?? false
        ];
    }
}

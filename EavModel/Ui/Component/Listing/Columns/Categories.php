<?php

namespace Magenest\EavModel\Ui\Component\Listing\Columns;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Categories extends Column
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface            $context,
        UiComponentFactory          $uiComponentFactory,
        CategoryRepositoryInterface $categoryRepository,
        array                       $components = [],
        array                       $data = []
    )
    {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * {@inheritdoc
     * @since 100.1.0
     */
    public function prepareDataSource(array $dataSource)
    {
        $dataSource = parent::prepareDataSource($dataSource);

        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (isset($item['category_ids'])) {
                $categoryIds = explode(',', $item['category_ids']);
                $item['category_ids'] = '';
                foreach ($categoryIds as $categoryId) {
                    $item['category_ids'] .= $this->categoryRepository->get($categoryId)->getName() . '<br>';
                }
            }
        }

        return $dataSource;
    }
}

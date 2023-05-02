<?php

namespace Test\Task\Block;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Test\Task\Helper\Data;
use Magento\Catalog\Block\Product\ListProduct;

class RandomProduct extends Template
{
    /**
     * @var Data
     */
    public $helper;
    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;
    /**
     * @var Visibility
     */
    protected $_productVisibility;
    /**
     * @var
     */
    protected $catalogHelper;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var ListProduct
     */
    protected $listProductBlock;

    /**
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param Visibility $productVisibility
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Data $helper
     * @param ListProduct $listProductBlock
     * @param array $data
     */
    public function __construct(
        Context                     $context,
        ProductRepositoryInterface  $productRepository,
        Visibility                  $productVisibility,
        SearchCriteriaBuilder       $searchCriteriaBuilder,
        Data                        $helper,
        ListProduct                 $listProductBlock,
        array                       $data = []
    )
    {
        $this->_productRepository       = $productRepository;
        $this->_productVisibility       = $productVisibility;
        $this->searchCriteriaBuilder    = $searchCriteriaBuilder;
        $this->helper                   = $helper;
        $this->listProductBlock         = $listProductBlock;
        parent::__construct($context, $data);
    }

    /**
     * @return ProductInterface|null
     */
    public function getRandomProduct()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('visibility', $this->_productVisibility->getVisibleInCatalogIds(), 'in')
            ->create();

        $products = $this->_productRepository->getList($searchCriteria);

        return $products->getItems()[array_rand($products->getItems())] ?? null;
    }

    /**
     * @param ProductInterface $product
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductPrice(ProductInterface $product): string
    {
        return $this->getLayout()
            ->getBlock('product.price.render.default')
            ->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                ['include_container' => false]
            );
    }

    /**
     * @param $product
     * @return array
     */
    public function getAddToCartPostParams($product)
    {
        return $this->listProductBlock->getAddToCartPostParams($product);
    }
}

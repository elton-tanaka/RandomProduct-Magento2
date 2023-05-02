<?php
namespace Test\Task\Controller\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NoSuchEntityException;

class AddToCartAndCheckout extends Action
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * AddToCartAndCheckout constructor.
     *
     * @param Context $context
     * @param Cart $cart
     * @param Session $checkoutSession
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        Cart $cart,
        Session $checkoutSession,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->cart = $cart;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $productId = $this->getRequest()->getParam('product_id');

        try {
            $product = $this->productRepository->getById($productId);
            $params = [
                'product' => $productId,
                'qty' => 1
            ];
            $this->cart->addProduct($product, $params);
            $this->cart->save();

            $this->checkoutSession->setCartWasUpdated(true);
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('checkout');
            return $resultRedirect;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__('There was a problem adding product to cart'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
    }
}

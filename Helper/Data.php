<?php
namespace Test\Task\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const PATH_BLOCK_TITLE = 'task/general/block_title';
    const PATH_REDIRECT_TO_CHECKOUT = 'task/general/redirect_to_checkout';

    /**
     * @return \Magento\Framework\Phrase|mixed
     */
    public function getBlockTitle() {
        $configValue = $this->scopeConfig->getValue(self::PATH_BLOCK_TITLE, ScopeInterface::SCOPE_STORE);
        return $configValue ?: __('Random Product');
    }

    /**
     * @return bool
     */
    public function getRedirectToCheckoutConfig() {
        return $this->scopeConfig->isSetFlag(self::PATH_REDIRECT_TO_CHECKOUT, ScopeInterface::SCOPE_STORE);
    }
}

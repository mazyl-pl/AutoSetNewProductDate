<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Mazyl\AutoSetNewProductDate\Observer\Catalog;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ProductSaveBefore implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $product = $observer->getProduct();
        if ($product->isObjectNew() && $this->isEnabled()) {
            $product->setNewsFromDate(time());
            $plusDays = time() + ($this->getConfig("days") * 86400);
            $product->setNewsToDate($plusDays);
        }
    }

    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag('autosetnewproductdate/general/enabled', ScopeInterface::SCOPE_STORE);
    }

    public function getConfig($name)
    {
        return $this->scopeConfig->getValue('autosetnewproductdate/general/'.$name, ScopeInterface::SCOPE_STORE);
    }
}


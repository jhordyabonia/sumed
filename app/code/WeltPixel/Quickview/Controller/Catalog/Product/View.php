<?php

namespace WeltPixel\Quickview\Controller\Catalog\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Catalog\Controller\Product\View
{
    public function execute()
    {
        $resultPage = parent::execute();
        $resultPage->getLayout()->unsetElement('mini.cart.float.block');
        $resultPage->getLayout()->unsetElement('login.columna');
        $resultPage->getLayout()->unsetElement('global_notices');
        return $resultPage;
    }
}

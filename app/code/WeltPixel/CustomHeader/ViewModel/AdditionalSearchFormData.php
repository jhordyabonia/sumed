<?php
namespace WeltPixel\CustomHeader\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class AdditionalSearchFormData implements ArgumentInterface
{
    /**
     * Return search query params
     *
     * @return array
     */
    public function getFormData(): array
    {
        return [];
    }
}

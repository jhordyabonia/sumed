<?php

namespace WeltPixel\NavigationLinks\Plugin;

/**
 * Class CatalogHelperData
 * @package WeltPixel\NavigationLinks\Plugin
 */
class CatalogHelperData
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    /**
     * @param \Magento\Catalog\Helper\Data $subject
     * @param array $result
     * @return array
     */
    public function afterGetBreadcrumbPath(\Magento\Catalog\Helper\Data $subject, $result)
    {
        $category = $subject->getCategory();
        if ($category) {
            $categories = $category->getParentCategories();
            foreach ($result as $categIdentifier => &$categOptions) {
                if (strpos($categIdentifier, 'category') !== false) {
                    $categId = ltrim($categIdentifier, 'category');
                    if (isset($categories[$categId]) && $categories[$categId]->getWeltpixelCategoryUrl()) {
                        $customCategoryUrl = $categories[$categId]->getWeltpixelCategoryUrl();
                        if (strpos($customCategoryUrl, 'http://') === 0 || strpos($customCategoryUrl, 'https://') === 0) {
                            $categOptions['link'] = $customCategoryUrl;
                        } elseif ($customCategoryUrl == '#') {
                            $categOptions['original_link'] = $categOptions['link'];
                            $categOptions['link'] = 's:void(0);';
                        } else {
                            $categOptions['link'] = $this->_storeManager->getStore()->getBaseUrl() . ltrim($customCategoryUrl, '//');
                        }
                    }
                }


            }
        }

        return $result;
    }

    /**
     * @param \Magento\Catalog\Observer\MenuCategoryData $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\Data\Tree\Node $category
     * @return mixed
     */
    public function aroundGetMenuCategoryData(
        \Magento\Catalog\Observer\MenuCategoryData $subject,
        \Closure $proceed,
        $category
    ) {
        $result = $proceed($category);

        $categoryMenuData = $category->getData();
        $customCategoryUrl = null;

        if (isset($categoryMenuData['weltpixel_category_url'])) {
            $customCategoryUrl = trim($categoryMenuData['weltpixel_category_url']);
        }

        if (isset($customCategoryUrl) && strlen($customCategoryUrl)) {

            if (strpos($customCategoryUrl, 'http://') === 0 || strpos($customCategoryUrl, 'https://') === 0) {
                $result['url'] = $customCategoryUrl;
            } elseif ($customCategoryUrl == '#') {
                $result['url'] = 'javascript:void(0);';
            } else {
                $result['url'] = $this->_storeManager->getStore()->getBaseUrl() . ltrim($customCategoryUrl, '//');
            }
        }

        $result['open_in_newtab'] = 0;
        if (isset($categoryMenuData['weltpixel_category_url_newtab']) && $categoryMenuData['weltpixel_category_url_newtab']) {
            $result['open_in_newtab'] = 1;
        }

        return $result;
    }
}

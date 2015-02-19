<?php

/**
 * @category    Inchoo
 * @package     Inchoo_KISSmetrics
 * @author      Branko Ajzele <ajzele@gmail.com>
 * @copyright   Copyright (c) Inchoo
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_KISSmetrics_Block_Event_Cms_View extends Mage_Core_Block_Template {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('inchoo/kissmetrics/event/cms/view.phtml');
    }

    public function getCurrentPage() {
        return Mage::getBlockSingleton('cms/page')->getPage();
    }

    public function getPageInfo() {
        $page = $this->getCurrentPage();

        return array(
            'page_id' => $page->getId(),
            'page_name' => $page->getTitle(),
            'store_id' => Mage::app()->getStore()->getId(),
        );
    }

    public function getJsonPageInfo() {
        return $this->helper('inchoo_kissmetrics')->getUtf8CleanJsonArray($this->getPageInfo());
    }

}

<?php

/**
 * @category    Inchoo
 * @package     Inchoo_KISSmetrics
 * @author      Branko Ajzele <ajzele@gmail.com>
 * @copyright   Copyright (c) Inchoo
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_KISSmetrics_Block_Customer extends Mage_Core_Block_Template {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('inchoo/kissmetrics/customer.phtml');
    }

    public function getIdentify() {
        $data = array('id'=>NULL,'name'=>NULL,'email'=>NULL);
        if ($this->helper('customer')->isLoggedIn()) {         
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $data['id']     = $customer->getId();
            $data['name']   = $customer->getName();
            $data['email']  = $customer->getEmail();
            return (array)$data;
        }else{
            $data['id'] = 0;
        }

        return (array)$data;
    }
}
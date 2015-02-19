<?php

/**
 * @category    Inchoo
 * @package     Inchoo_KISSmetrics
 * @author      Branko Ajzele <ajzele@gmail.com>
 * @copyright   Copyright (c) Inchoo
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Inchoo_KISSmetrics_Model_Observer {

    const ONEPAGE_CHECKOUT_STEP_LOGIN = 'customer.login'; //was checkout.onepage.login
    const ONEPAGE_CHECKOUT_STEP_BILLING = 'checkout.onepage.billing';
    const ONEPAGE_CHECKOUT_STEP_SHIPPING = 'checkout.onepage.shipping';
    const ONEPAGE_CHECKOUT_STEP_SHIPPING_METHOD = 'checkout.onepage.shipping_method';
    const ONEPAGE_CHECKOUT_STEP_PAYMENT = 'checkout.onepage.payment';
    const ONEPAGE_CHECKOUT_STEP_REVIEW = 'checkout.onepage.review';

    private $_helper = null;

    public function __construct() {
        $this->_helper = Mage::helper('inchoo_kissmetrics');
    }

    public function trackCheckoutStepBillingInformation($observer) {
        
        if (!$this->_helper->isModuleEnabled() OR !$this->_helper->isModuleOutputEnabled()) {
            return $this;
        }

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            /* START ONEPAGE_CHECKOUT_STEP_BILLING */
            if ($observer->getEvent()->getBlock()->getNameInLayout() === self::ONEPAGE_CHECKOUT_STEP_BILLING) {

                $js = Mage::app()->getLayout()
                        ->createBlock('Mage_Core_Block_Text', 'inchoo_kissmetrics_' . self::ONEPAGE_CHECKOUT_STEP_BILLING);
                $js->setText($js->getText() . '<script type="text/javascript">');
                $js->setText($js->getText() . '_kmq.push(["record", "One Page Checkout - Step - Billing Information", ' . $this->_helper->getUtf8CleanJsonArray($this->_helper->getCartInfo()) . ']);');
                $js->setText($js->getText() . '</script>');

                $observer->getEvent()->getTransport()->setHtml(
                        $observer->getEvent()->getTransport()->getHtml() . $js->toHtml()
                );
            }
            /* END ONEPAGE_CHECKOUT_STEP_BILLING */
        } else {
            /* START GUEST BILLING INFO */
            if ($observer->getEvent()->getBlock()->getNameInLayout() === self::ONEPAGE_CHECKOUT_STEP_BILLING) {
                $js = Mage::app()->getLayout()
                        ->createBlock('Mage_Core_Block_Text', 'inchoo_kissmetrics_' . self::ONEPAGE_CHECKOUT_STEP_BILLING);
                $js->setText($js->getText() . '<script type="text/javascript">');
                $js->setText($js->getText() . 'var inchooKISSmetricsGuestBillFireOnce = false;$("bill_form").observe("click", function(){ if (inchooKISSmetricsGuestBillFireOnce == false) {_kmq.push(["record", "One Page Checkout - Step - Guest Billing Info", ' . $this->_helper->getUtf8CleanJsonArray($this->_helper->getCartInfo()) . ']);');
                $js->setText($js->getText() . ' } inchooKISSmetricsGuestBillFireOnce = true; });</script>');

                $observer->getEvent()->getTransport()->setHtml(
                        $observer->getEvent()->getTransport()->getHtml() . $js->toHtml()
                );
            }
            /* END GUEST BILLING INFO */
            /*BEGIN ONEPAGE_CHECKOUT_STEP_LOGIN*/
            if ($observer->getEvent()->getBlock()->getNameInLayout() === self::ONEPAGE_CHECKOUT_STEP_LOGIN) {
                $js = Mage::app()->getLayout()
                        ->createBlock('Mage_Core_Block_Text', 'inchoo_kissmetrics_' . self::ONEPAGE_CHECKOUT_STEP_LOGIN);
                $js->setText($js->getText() . '<script type="text/javascript">');
                $js->setText($js->getText() . 'var inchooKISSmetricsLoginFireOnce = false;$("onepagecheckout_loginbox").observe("click", function(){ if (inchooKISSmetricsLoginFireOnce == false) {_kmq.push(["record", "One Page Checkout - Login Form Selected", ' . $this->_helper->getUtf8CleanJsonArray($this->_helper->getCartInfo()) . ']);');
                $js->setText($js->getText() . ' } inchooKISSmetricsLoginFireOnce = true; });</script>');

                $observer->getEvent()->getTransport()->setHtml(
                        $observer->getEvent()->getTransport()->getHtml() . $js->toHtml()
                );
            }
            /* END ONEPAGE_CHECKOUT_STEP_LOGIN */
        }

        return $this;
    }

    public function trackCheckoutStepShippingMethod($observer) {
        if (!$this->_helper->isModuleEnabled() OR !$this->_helper->isModuleOutputEnabled()) {
            return $this;
        }

        $responseBody = $observer->getEvent()->getControllerAction()
                        ->getResponse()->getBody();

        $responseBody = json_decode((string) $responseBody);

        if ($responseBody->update_section->{'shipping-method'}) {

            $js = Mage::app()->getLayout()
                    ->createBlock('Mage_Core_Block_Text', 'inchoo_kissmetrics_' . self::ONEPAGE_CHECKOUT_STEP_SHIPPING_METHOD);

            $js->setText($js->getText() . '<script type="text/javascript">');
            $js->setText($js->getText() . 'var inchooKISSmetricsShipMethodFireOnce = false;$("shipping-method").observe("click", function(){ if (inchooKISSmetricsShipMethodFireOnce == false) {_kmq.push(["record", "One Page Checkout - Step - Shipping Method", ' . $this->_helper->getUtf8CleanJsonArray($this->_helper->getCartInfo()) . ']);');
            $js->setText($js->getText() . ' } inchooKISSmetricsShipMethodFireOnce = true; });</script>');

            $responseBody->update_section->{'shipping-method'} = $responseBody->update_section->{'shipping-method'} . $js->getText();
        }

        $responseBody = json_encode($responseBody);

        $observer->getEvent()->getControllerAction()
                ->getResponse()->setBody($responseBody);
    }

    public function trackCheckoutStepShippingInformation($observer) {
        if (!$this->_helper->isModuleEnabled() OR !$this->_helper->isModuleOutputEnabled()) {
            return $this;
        }

        if ($observer->getEvent()->getBlock()->getNameInLayout() === self::ONEPAGE_CHECKOUT_STEP_SHIPPING) {
            $js = Mage::app()->getLayout()
                    ->createBlock('Mage_Core_Block_Text', 'inchoo_kissmetrics_' . self::ONEPAGE_CHECKOUT_STEP_SHIPPING);

            $js->setText($js->getText() . '<script type="text/javascript">');
            $js->setText($js->getText() . 'var inchooKISSmetricsFireOnce = false; $("ship_form").observe("click", function(){ if (inchooKISSmetricsFireOnce == false) { ');
            $js->setText($js->getText() . '_kmq.push(["record", "One Page Checkout - Step - Shipping Information", ' . $this->_helper->getUtf8CleanJsonArray($this->_helper->getCartInfo()) . ']);');
            $js->setText($js->getText() . ' } inchooKISSmetricsFireOnce = true; });');
            $js->setText($js->getText() . '</script>');

            $observer->getEvent()->getTransport()->setHtml(
                    $observer->getEvent()->getTransport()->getHtml() . $js->toHtml()
            );
        }
    }

    public function trackCheckoutStepPaymentInformation($observer) {
        if (!$this->_helper->isModuleEnabled() OR !$this->_helper->isModuleOutputEnabled()) {
            return $this;
        }

        $responseBody = $observer->getEvent()->getControllerAction()
                        ->getResponse()->getBody();

        $responseBody = json_decode((string) $responseBody);
        if ($responseBody->update_section->{'payment-method'}) {
            $js = Mage::app()->getLayout()
                    ->createBlock('Mage_Core_Block_Text', 'inchoo_kissmetrics_' . self::ONEPAGE_CHECKOUT_STEP_PAYMENT);

            $js->setText($js->getText() . '<script type="text/javascript">var inchooKISSmetricsPayFireOnce = false; $("checkout-payment-method-load").observe("click", function(){if (inchooKISSmetricsPayFireOnce == false) {');
            $js->setText($js->getText() . '_kmq.push(["record", "One Page Checkout - Step - Payment Information", ' . $this->_helper->getUtf8CleanJsonArray($this->_helper->getCartInfo()) . ']);');
            $js->setText($js->getText() . '}inchooKISSmetricsPayFireOnce = true; });</script>');

            $responseBody->update_section->{'payment-method'} = $responseBody->update_section->{'payment-method'} . $js->getText();     
        }

        $responseBody = json_encode($responseBody);

        $observer->getEvent()->getControllerAction()
                ->getResponse()->setBody($responseBody);
    }

    public function trackCheckoutStepOrderReview($observer) {
        if (!$this->_helper->isModuleEnabled() OR !$this->_helper->isModuleOutputEnabled()) {
            return $this;
        }

        $responseBody = $observer->getEvent()->getControllerAction()
                        ->getResponse()->getBody();

        $responseBody = json_decode((string) $responseBody);

        if($responseBody->update_section->review){    

            $js = Mage::app()->getLayout()
                    ->createBlock('Mage_Core_Block_Text', 'inchoo_kissmetrics_' . self::ONEPAGE_CHECKOUT_STEP_REVIEW);

            $js->setText($js->getText() . '<script type="text/javascript">var inchooKISSmetricsReviewFireOnce = false;$("checkout-review").observe("click", function(){if (inchooKISSmetricsReviewFireOnce == false) {');
            $js->setText($js->getText() . '_kmq.push(["record", "One Page Checkout - Step - Order Review", ' . $this->_helper->getUtf8CleanJsonArray($this->_helper->getCartInfo()) . ']);');
            $js->setText($js->getText() . '}inchooKISSmetricsReviewFireOnce = true; });</script>');
            
            $responseBody->update_section->review = $responseBody->update_section->review . $js->getText();
            
        }

        $responseBody = json_encode($responseBody);
        
        $observer->getEvent()->getControllerAction()->getResponse()
                ->setBody($responseBody);

    }

}
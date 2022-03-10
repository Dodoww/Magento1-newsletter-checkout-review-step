<?php
class FastMage_NewsletterCheckoutReviewStep_Model_Observer
{

    public function addCheckbox (Varien_Event_Observer $observer)
    {
        if ($observer->getBlock() instanceof Mage_Checkout_Block_Agreements
            && false === (boolean)(int)Mage::getStoreConfig('advanced/modules_disable_output/FastMage_NewsletterCheckoutReviewStep')
        ) {
            $checked = $this->isCheckboxCheckedByDefault() ? 'checked="checked"' : '';
            $html = $observer->getTransport()->getHtml();
            $checkboxHtml = '<li><p class="agree">'
                . '<input id="subscribe_newsletter" name="is_subscribed" checked="checked" value="1" '. $checked .'class="checkbox" type="checkbox" title="' . $this->__($this->getCheckboxLabelText() . '" />'
                . '<label for="subscribe_newsletter">' . $this->__($this->getCheckboxLabelText()) . '</label>'
                . '</p></li>';
            $html = str_replace('</ol>', $checkboxHtml . '</ol>', $html);
            $observer->getTransport()->setHtml($html);
        }
    }

    public function subscribe (Varien_Event_Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        if ($quote->getBillingAddress() && Mage::app()->getRequest()->getParam('is_subscribed', false)) {
            $status = Mage::getModel('newsletter/subscriber')
                ->setImportMode(true)
                ->subscribe($quote->getBillingAddress()->getEmail());
        }
    }

}

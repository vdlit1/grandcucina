<?php

class Atd_Quicklook_Model_Quicklook extends Varien_Object
{
    public function send()
    {
        Zend_Json::$useBuiltinEncoderDecoder = true;
        if ($this->getError()) $this->setR('error');
        else $this->setR('success');
        Mage::app()->getFrontController()->getResponse()->setBody(Zend_Json::encode($this->getData()));
    }
}

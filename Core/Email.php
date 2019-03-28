<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

namespace Ci\Oxid\FormBuilder\Core;

class Email extends Email_parent
{

    public function onFormbuilderMailSend($FormData)
    {

    }

    public function sendFormbuilderMail($FormData, $FieldData)
    {
        $this->sendFormbuilder2Owner($FormData, $FieldData);
        if(!empty($FormData['oxconfirm'])) {
            $this->sendFormbuilder2Customer($FormData, $FieldData);
        }
    }

    protected function sendFormbuilder2Owner($FormData, $FieldData)
    {
        $config = $this->getConfig();

        // create messages
        $evalSmartyContent = $this->_getSmarty();
        $evalSmartyContent->assign('template', $FormData['oxcontent']);
        foreach ($FieldData as $field => $fieldData) {
            $evalSmartyContent->assign($field, $fieldData['value']);
        }

        $evalSmartyContent = $evalSmartyContent->fetch($config->getTemplatePath('ci_formbuilder_eval.tpl', false));

        $shop = $this->_getShop();

        // cleanup
        $this->_clearMailer();
        $this->setFrom($shop->oxshops__oxowneremail->value);

        $language = \OxidEsales\Eshop\Core\Registry::getLang();
        $orderLanguage = $language->getObjectTplLanguage();

        // if running shop language is different from admin lang. set in config
        // we have to load shop in config language
        if ($shop->getLanguage() != $orderLanguage) {
            $shop = $this->_getShop($orderLanguage);
        }

        $this->setSmtp($shop);

        // create messages
        $smarty = $this->_getSmarty();

        foreach ($FormData as $key => $value) {
            $this->setViewData($key, $value);
        }
        foreach ($FieldData as $field => $element) {
            $this->setViewData($field, $element['value']);
        }

        // Process view data array through oxoutput processor
        $this->_processViewArray();

        $this->setBody($smarty->fetch($config->getTemplatePath('email/formbuilder/' . $FormData['oxhtmltemplate'] . '.tpl', false)));
        $this->setAltBody($evalSmartyContent);

        $this->setSubject($FormData['oxsubject']);

        $this->setRecipient($shop->oxshops__oxowneremail->value, $language->translateString("order"));

        $result = $this->send();

        $this->onFormbuilderMailSend($FormData);

        if ($config->getConfigParam('iDebug') == 6) {
            \OxidEsales\Eshop\Core\Registry::getUtils()->showMessageAndExit("");
        }

        return $result;
    }

    protected function sendFormbuilder2Customer($FormData, $FieldData)
    {
        $config = $this->getConfig();

        $Receiver = null;
        foreach($FieldData as $fieldName => $fieldData) {
            if(!empty($fieldData['OXCONFIRMFIELD'])) {
                $Receiver = $fieldData['value'];
            }
        }

        if(empty($Receiver)) {
            return;
        }

        // create messages
        $evalSmartyContent = $this->_getSmarty();
        $evalSmartyContent->assign('template', $FormData['oxcontent_confirm']);
        foreach ($FieldData as $field => $fieldData) {
            $evalSmartyContent->assign($field, $fieldData['value']);
        }

        $evalSmartyContent = $evalSmartyContent->fetch($config->getTemplatePath('ci_formbuilder_eval.tpl', false));

        $shop = $this->_getShop();

        // cleanup
        $this->_clearMailer();
        $this->setFrom($shop->oxshops__oxowneremail->value);

        $language = \OxidEsales\Eshop\Core\Registry::getLang();
        $orderLanguage = $language->getObjectTplLanguage();

        // if running shop language is different from admin lang. set in config
        // we have to load shop in config language
        if ($shop->getLanguage() != $orderLanguage) {
            $shop = $this->_getShop($orderLanguage);
        }

        $this->setSmtp($shop);

        // create messages
        $smarty = $this->_getSmarty();

        foreach ($FormData as $key => $value) {
            $this->setViewData($key, $value);
        }
        foreach ($FieldData as $field => $element) {
            $this->setViewData($field, $element['value']);
        }

        // Process view data array through oxoutput processor
        $this->_processViewArray();

        $this->setBody($smarty->fetch($config->getTemplatePath('email/formbuilder/customer/' . $FormData['oxhtmltemplate'] . '.tpl', false)));
        $this->setAltBody($evalSmartyContent);

        $this->setSubject($FormData['oxsubject_confirm']);

        $this->setRecipient($Receiver, $language->translateString("order"));
        if (method_exists($this, 'addAttachments') && !empty($FormData['oxattachments'])) {
            $this->addAttachments($FormData['oxattachments']);
        }

        $result = $this->send();

        $this->onFormbuilderMailSend($FormData);

        if ($config->getConfigParam('iDebug') == 6) {
            \OxidEsales\Eshop\Core\Registry::getUtils()->showMessageAndExit("");
        }

        return $result;
    }
}

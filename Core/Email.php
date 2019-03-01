<?php

namespace Ci\Oxid\FormBuilder\Core;

class Email extends Email_parent
{

    public function onFormbuilderMailSend($FormData) {

    }

    public function sendFormbuilderMail($FormData, $FieldData)
    {
        $config = $this->getConfig();


        // create messages
        $evalSmartyContent = $this->_getSmarty();
        $evalSmartyContent->assign('template', $FormData['content']);
        foreach($FieldData as $field => $fieldData) {
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

        foreach($FormData as $key => $value) {
            $this->setViewData($key, $value);
        }
        foreach($FieldData as $field => $element) {
            $this->setViewData($field, $element['value']);
        }

        // Process view data array through oxoutput processor
        $this->_processViewArray();
        

        $this->setBody($smarty->fetch($config->getTemplatePath('email/formbuilder/' . $FormData['htmltemplate'] . '.tpl', false)));
        $this->setAltBody($evalSmartyContent);

        $this->setSubject($FormData['subject']);

        $this->setRecipient($shop->oxshops__oxowneremail->value, $language->translateString("order"));

        $result = $this->send();

        $this->onFormbuilderMailSend($FormData);

        if ($config->getConfigParam('iDebug') == 6) {
            \OxidEsales\Eshop\Core\Registry::getUtils()->showMessageAndExit("");
        }

        return $result;
    }
}
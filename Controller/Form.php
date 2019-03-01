<?php

namespace Ci\Oxid\FormBuilder\Controller;

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;
use Ci\Oxid\FormBuilder\Model\Form as FormModel;
use Ci\Oxid\FormBuilder\Model\FormElements as ElementsModel;
use OxidEsales\Eshop\Core\Email;

class Form extends FrontendController
{
    // protected $_sThisTemplate = 'hotspot.tpl';

    public function init()
    {
        $config = $this->getConfig();
        $Form = oxNew(FormModel::class);
        $Form->loadWithFields(Registry::getConfig()->getRequestParameter('fid'));
        $FormData = $this->loadFormData($Form);
        
        $FieldConfig = oxNew(ElementsModel::class);
        $FieldConfig = $FieldConfig->findByParent($Form->getId());

        $_fc = [];
        foreach($FieldConfig as $field) {
            $_fc[$field['OXTITLE']] = $field;
            if(!empty($_POST['formbuilder'][$field['OXTITLE']])) {
                $_fc[$field['OXTITLE']]['value'] = $_POST['formbuilder'][$field['OXTITLE']];
            }
        }
        $FieldConfig = $_fc;
        unset($_fc);
        
        $url = rtrim($config->getCurrentShopUrl($this->isAdmin()), '/');
        $url .= '/' . ltrim($FormData['action'], '/');
        
        $Email = oxNew(Email::class);
        $Email->sendFormbuilderMail($FormData, $FieldConfig);
        
        Registry::getUtils()->redirect($url, (bool) $config->getRequestParameter('redirected'), 302);
    }

    protected function loadFormData($Form = null)
    {
        return [
            'oxid' => $Form->ci_form__oxid->value,
            'oxshopid' => $Form->ci_form__oxshopid->value,
            'title' => $Form->ci_form__oxtitle->value,
            'alias' => $Form->ci_form__oxalias->value,
            'htmltemplate' => $Form->ci_form__oxhtmltemplate->value,
            'cssclass' => $Form->ci_form__oxcssclass->value,
            'active' => $Form->ci_form__oxactive->value,
            'activefrom' => $Form->ci_form__oxactivefrom->value,
            'activeto' => $Form->ci_form__oxactiveto->value,
            'sort' => $Form->ci_form__oxsort->value,
            'timestamp' => $Form->ci_form__oxtimestamp->value,
            'action' => $Form->ci_form__oxaction->value,
            'fieldconfig' => $Form->ci_form__oxfieldconfig->value,
            'sendform' => $Form->ci_form__oxsendform->value,
            'receiver' => $Form->ci_form__oxreceiver->value,
            'subject' => $Form->ci_form__oxsubject->value,
            'content' => $Form->ci_form__oxcontent->value,
        ];
    }
}

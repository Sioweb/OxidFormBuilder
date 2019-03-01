<?php

namespace Ci\Oxid\FormBuilder\Controller;

use Ci\Oxid\FormBuilder\Model\Form as FormModel;
use Ci\Oxid\FormBuilder\Model\FormElements as ElementsModel;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Email;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\SmartyRenderer;

class Form extends FrontendController
{

    private $RenderedForm = '';
    private $FormData = [];
    private $FieldConfig = [];

    // protected $_sThisTemplate = 'hotspot.tpl';

    public function render()
    {
        if(!empty($_POST)) {
            return '';
        }
    
        $this->_aViewData['oxid'] = Registry::getConfig()->getRequestParameter('fid');
        $this->_aViewData['form'] = $this->RenderedForm;
        
        return 'ci_formbuilder_form.tpl';
    }

    public function init()
    {
        $config = $this->getConfig();
        $Form = oxNew(FormModel::class);
        $this->RenderedForm = $Form->loadWithFields(Registry::getConfig()->getRequestParameter('fid'));
        $this->FormData = $this->loadFormData($Form);

        $this->FieldConfig = oxNew(ElementsModel::class);
        $this->FieldConfig = $this->FieldConfig->findByParent($Form->getId());

        $_fc = [];
        foreach ($this->FieldConfig as $field) {
            $_fc[$field['OXTITLE']] = $field;
            if (!empty($_POST['formbuilder'][$field['OXTITLE']])) {
                $_fc[$field['OXTITLE']]['value'] = $_POST['formbuilder'][$field['OXTITLE']];
            }
        }
        $this->FieldConfig = $_fc;
        unset($_fc);

        if (!empty($_POST)) {
            $url = rtrim($config->getCurrentShopUrl($this->isAdmin()), '/');
            $url .= '/' . ltrim($this->FormData['action'], '/');

            $Email = oxNew(Email::class);
            $Email->sendFormbuilderMail($this->FormData, $this->FieldConfig);

            Registry::getUtils()->redirect($url, (bool) $config->getRequestParameter('redirected'), 302);
        }
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

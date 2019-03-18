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
                
                if(!empty($field['OXOPTIONS'])) {
                    $OptionValue = null;
                    foreach($field['OXOPTIONS'] as $optionSetIndex => $optionSet) {
                        if(!is_array($_fc[$field['OXTITLE']]['value'])) {
                            if($optionSet['key'] === $_fc[$field['OXTITLE']]['value']) {
                                $OptionValue = $optionSet['value'];
                            }
                        } else {
                            if(in_array($optionSet['key'], $_fc[$field['OXTITLE']]['value'])) {
                                $OptionValue[$optionSet['key']] = $optionSet['value'];
                            }
                        }
                    }
                    $_fc[$field['OXTITLE']]['value'] = $OptionValue;
                }
            }
        }
        
        $AdditionalPostData = array_diff_key($_POST['formbuilder'], $_fc);
        if(!empty($AdditionalPostData)) {
            foreach($AdditionalPostData as $name => $value) {
                $_fc[$name] = [
                    'additional' => true,
                    'value' => $value
                ];
            }
        }
        
        $this->FieldConfig = $_fc;
        unset($_fc);

        if (!empty($_POST) && $this->FormData['sendform']) {
            $Action = $this->beforeSendMail($this->FormData, $this->FieldConfig);
            if($Action === null || $Action == true) {
                $Email = oxNew(Email::class);
                $Email->sendFormbuilderMail($this->FormData, $this->FieldConfig);
            }
        }
        
        if (!empty($_POST) && $this->FormData['action']) {
            $url = rtrim($config->getCurrentShopUrl($this->isAdmin()), '/');
            $url .= '/' . ltrim($this->FormData['action'], '/');
            $Action = $this->beforeRedirect($this->FormData, $this->FieldConfig, $url);
            if($Action === null || $Action == true) {
                Registry::getUtils()->redirect($url, (bool) $config->getRequestParameter('redirected'), 302);
            }
        }
    }

    protected function beforeSendMail($FormData, $FieldConfig) {}
    

    protected function beforeRedirect($FormData, $FieldConfig, $url) {}
    
    protected function loadFormData($Form = null)
    {
        $FormFieldData = oxNew(\Ci\Oxid\FormBuilder\Form\Admin\Forms::class)->loadFieldConfig();
        $FormData = [];
        foreach($FormFieldData as $key => $elementConf) {
            $FormData[$key] = $Form->{'ci_form__' . $key}->value;
            // deprecated
            $FormData[str_replace('ox', '', $key)] = $Form->{'ci_form__' . $key}->value;
        }

        return $FormData;
    }
}

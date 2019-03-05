<?php

namespace Ci\Oxid\FormBuilder\Model;

use Ci\Oxid\FormBuilder\Core\FormRender;
use Ci\Oxid\FormBuilder\Model\FormElements as ElementsModel;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;
use Sioweb\Lib\Formgenerator\Core\Form as FormGenerator;

class Form extends MultiLanguageModel
{
    public $aList = [];

    private $smartyParameters = [];

    public function __construct()
    {
        parent::__construct();
        $this->init("ci_form");
    }

    public function setParams($params)
    {
        $this->smartyParameters = $params;
    }

    public function loadWithFields($oxid)
    {
        $this->load($oxid);

        $this->ci_form__oxfieldconfig->rawValue = json_decode($this->ci_form__oxfieldconfig->rawValue, 1);

        $FieldConfig = oxNew(ElementsModel::class);
        $FieldConfig = $FieldConfig->findByParent($oxid);

        $_fc = [];
        foreach ($FieldConfig as $field) {
            $_fc[$field['OXID']] = $field;
        }
        $FieldConfig = $_fc;
        unset($_fc);

        $FormData = [
            'formname' => 'myform',
            'fieldname' => 'formbuilder[[{ $FIELDNAME }]]',
            'palettes' => [
                'default' => [],
            ],
            'fields' => [

            ],
        ];

        foreach ($this->ci_form__oxfieldconfig->rawValue as $fieldKey => $fieldset) {
            $FormData['palettes']['default'][$fieldset['legend'] ?: $fieldKey]['fields'] = [];
            foreach ($fieldset['fields'] as $fieldId) {
                $FormData['palettes']['default'][$fieldset['legend'] ?: $fieldKey]['fields'][] = $FieldConfig[$fieldId]['OXTITLE'];
                $FormData['fields'][$FieldConfig[$fieldId]['OXTITLE']] = $this->format($FieldConfig[$fieldId]);
            }
        }

        foreach ($this->smartyParameters as $parameter => $parameterValue) {
            $FirstPalette = current(array_keys($FormData['palettes']['default']));
            array_unshift($FormData['palettes']['default'][$FirstPalette]['fields'], $parameter);
            $FormData['fields'][$parameter] = [
                'type' => 'hidden',
                'template' => 'hidden',
                'value' => $parameterValue,
                'name' => 'smarty[' . $parameter . ']',
            ];
        }

        $FormGenerator = new FormGenerator(new FormRender);
        $FormGenerator->setFormData(['form' => $FormData]);

        return implode("\n", $FormGenerator->generate());
    }

    private function format($Data)
    {
        $FieldData = [
            'label' => $Data['OXLABEL'],
            'type' => $Data['OXTYPE'],
            'template' => $Data['OXTYPE'],
            'value' => $Data['OXVALUE'],
            'class' => $Data['OXCSSCLASS'],
            'required' => $Data['OXREQUIRED'],
            'validation' => $Data['OXVALIDATION'],
            'placeholder' => $Data['OXPLACEHOLDER'],
            'options' => $Data['OXOPTIONS'],
        ];

        if(!empty($FieldData['options'])) {
            if(empty($FieldData['value'])) {
                $FieldData['value'] = $FieldData['options'];
            } else {
                foreach($FieldData['options'] as $key => $option) {
                    if($FieldData['value'] === $option['key']) {
                        $FieldData['options'][$key]['active'] = '1';
                    }
                }
            }
        }

        return $FieldData;
    }
}

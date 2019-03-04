<?php

namespace Ci\Oxid\FormBuilder\Form\Admin;

class Elements implements \Sioweb\Lib\Formgenerator\Core\FormInterface
{
    public function loadData()
    {
        return [
            'form' => [
                'formname' => 'myform',
                'table' => 'ci_form_element',
                'fieldname' => 'editval[[{ $TABLE }]__[{ $FIELDNAME }]]',
                'palettes' => $this->loadPalettes(),
                'fields' => $this->loadFieldConfig(),
            ],
        ];
    }

    public function loadPalettes()
    {
        return [
            'default' => [
                'default' => [
                    'class' => 'w50',
                    'fields' => ['oxtype', 'oxtitle', 'oxlabel'],
                ],
                'config' => [
                    'class' => 'w50',
                    'fields' => ['oxrequired', 'oxvalidation', 'oxplaceholder'],
                ],
                'submit' => [
                    'fields' => ['submit'],
                ],
            ],
            'textarea' => [
                'default' => [
                    'class' => 'w50',
                    'fields' => ['oxtype', 'oxtitle', 'oxlabel'],
                ],
                'config' => [
                    'class' => 'w50',
                    'fields' => ['oxrequired', 'oxplaceholder'],
                ],
                'submit' => [
                    'fields' => ['submit'],
                ],
            ],
            'submit' => [
                'default' => [
                    'fields' => ['oxtype', 'oxtitle', 'oxvalue'],
                ],
                'submit' => [
                    'fields' => ['submit'],
                ],
            ],
        ];
    }

    public function loadFieldConfig()
    {
        return [
            'oxtype' => [
                'palette' => 1,
                'type' => 'select',
                'submitOnChange' => true,
                'value' => 'textarea',
                'options' => ['default', 'textarea', 'submit'],
                'required' => true,
            ],
            'oxtitle' => [
                'type' => 'text',
                'required' => true,
            ],
            'oxlabel' => [
                'type' => 'text',
                'required' => true,
                'editable' => true,
            ],
            'oxvalue' => [
                'type' => 'text',
                'editable' => true,
            ],
            'oxrequired' => [
                'type' => 'checkbox',
                'editable' => true,
            ],
            'oxvalidation' => [
                'type' => 'select',
                'blank' => true,
                'options' => [
                    'number',
                    'email',
                ],
                'editable' => true,
            ],
            'oxplaceholder' => [
                'type' => 'text',
                'editable' => true,
            ],
            'submit' => [
                'name' => 'save',
                'type' => 'submit',
                'value' => 'Senden',
                'attributes' => [
                    'onclick="Javascript:document.myedit.fnc.value=\'save\'"',
                ],
                'editable' => true,
            ],
        ];
    }
}

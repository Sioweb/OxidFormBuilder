<?php

namespace Ci\Oxid\FormBuilder\Form\Admin;

class Elements implements \Sioweb\Lib\Formgenerator\Core\FormInterface
{
    public function loadData()
    {
        return [
            'form' => [
                'formname' => 'myform',
                'fieldname' => 'editval[ci_form_element__[{ $FIELDNAME }]]',
                'palettes' => $this->loadPalettes(),
                'fields' => $this->loadFieldConfig()
            ]
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
                    'fields' => ['oxrequire', 'oxvalidation', 'oxplaceholder'],
                ],
                'submit' => [
                    'fields' => ['submit']
                ]
            ],
            'textarea' => [
                'default' => [
                    'class' => 'w50',
                    'fields' => ['oxtype', 'oxtitle', 'oxlabel'],
                ],
                'config' => [
                    'class' => 'w50',
                    'fields' => ['oxrequire', 'oxplaceholder'],
                ],
                'submit' => [
                    'fields' => ['submit']
                ]
            ],
            'submit' => [
                'default' => [
                    'fields' => ['oxtype', 'oxtitle', 'oxvalue'],
                ],
                'submit' => [
                    'fields' => ['submit']
                ]
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
                'required' => true
            ],
            'oxtitle' => [
                'type' => 'text',
                'required' => true
            ],
            'oxlabel' => [
                'type' => 'text',
                'required' => true
            ],
            'oxvalue' => [
                'type' => 'text'
            ],
            'oxrequire' => [
                'type' => 'checkbox',
            ],
            'oxvalidation' => [
                'type' => 'select',
                'blank' => true,
                'options' => [ 
                    'number',
                    'email',
                ]
            ],
            'oxplaceholder' => [
                'type' => 'text'
            ],
            'submit' => [
                'name' => 'save',
                'type' => 'submit',
                'value' => 'Senden',
                'attributes' => [
                    'onclick="Javascript:document.myedit.fnc.value=\'save\'"'
                ]
            ]
        ];
    }
}
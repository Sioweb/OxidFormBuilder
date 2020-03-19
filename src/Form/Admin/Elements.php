<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

namespace Ci\Oxid\FormBuilder\Form\Admin;

class Elements implements \Sioweb\Lib\Formgenerator\Core\FormInterface, \Sioweb\Lib\Formgenerator\Core\SubpaletteInterface
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

    public function loadSubpalettes()
    {
        return [
            'oxvalidation_email' => [
                'oxconfirmfield'
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
            'select' => [
                'default' => [
                    'class' => 'w50',
                    'fields' => ['oxtype', 'oxtitle', 'oxlabel'],
                ],
                'values' => [
                    'class' => 'w50',
                    'fields' => ['oxoptions'],
                ],
                'config' => [
                    'class' => 'w50',
                    'fields' => ['oxrequired', 'oxvalidation', 'oxplaceholder'],
                ],
                'submit' => [
                    'fields' => ['submit'],
                ],
            ],
            'checkbox' => [
                'default' => [
                    'class' => 'w50',
                    'fields' => ['oxtype', 'oxtitle', 'oxlabel'],
                ],
                'values' => [
                    'class' => 'w50',
                    'fields' => ['oxoptions'],
                ],
                'config' => [
                    'class' => 'w50',
                    'fields' => ['oxrequired', 'oxvalidation', 'oxplaceholder'],
                ],
                'submit' => [
                    'fields' => ['submit'],
                ],
            ],
            'radio' => [
                'default' => [
                    'class' => 'w50',
                    'fields' => ['oxtype', 'oxtitle', 'oxlabel'],
                ],
                'values' => [
                    'class' => 'w50',
                    'fields' => ['oxoptions'],
                ],
                'config' => [
                    'class' => 'w50',
                    'fields' => ['oxrequired', 'oxvalidation', 'oxplaceholder'],
                ],
                'submit' => [
                    'fields' => ['submit'],
                ],
            ],
            'submit' => [
                'default' => [
                    'fields' => ['oxtype', 'oxtitle', 'oxvalue', 'oxcssclass'],
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
                'value' => 'default',
                'valueColumn' => 'type',
                'options' => ['default', 'textarea', 'select', 'checkbox', 'radio', 'submit'],
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
            'oxconfirmfield' => [
                'type' => 'checkbox',
                'editable' => true,
            ],
            'oxplaceholder' => [
                'type' => 'text',
                'editable' => true,
            ],
            'oxcssclass' => [
                'type' => 'text',
                'editable' => true,
            ],
            'oxoptions' => [
                'type' => 'optionsWidget',
                'json' => true
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

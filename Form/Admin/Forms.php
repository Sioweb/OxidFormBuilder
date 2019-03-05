<?php

namespace Ci\Oxid\FormBuilder\Form\Admin;

class Forms implements \Sioweb\Lib\Formgenerator\Core\FormInterface
{
    public function loadData()
    {
        return [
            'form' => [
                'formname' => 'myform',
                'table' => 'ci_form',
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
                    'class' => 'long',
                    'fields' => ['applyFields', 'oxtitle', 'oxhtmltemplate', 'oxaction'],
                ],
                'action' => [
                    'fields' => [
                        'oxsendForm', 'oxreceiver', 'oxsubject', 'oxcontent',
                    ],
                ],
                'config' => [
                    'class' => 'long',
                    'fields' => ['oxcssclass'],
                ],
                'publish' => [
                    'class' => 'long',
                    'fields' => ['oxactive', 'oxactivefrom', 'oxactiveto'],
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
            'oxtitle' => [
                'type' => 'text',
                'required' => true,
            ],
            'oxhtmltemplate' => [
                'type' => 'text',
            ],
            'oxaction' => [
                'type' => 'text',
                'required' => true,
            ],
            'oxcssclass' => [
                'type' => 'text',
            ],
            'oxsort' => [
                'type' => 'text',
            ],
            'oxactive' => [
                'type' => 'checkbox',
            ],
            'oxsendForm' => [
                'type' => 'checkbox',
            ],
            'oxreceiver' => [
                'type' => 'text',
                'validation' => 'email',
            ],
            'oxsubject' => [
                'type' => 'text',
            ],
            'oxcontent' => [
                'type' => 'textarea',
            ],
            'oxactivefrom' => [
                'type' => 'text',
                'class' => 'w50',
                'validation' => 'datetime',
                'autocomplete' => 'off',
                'attributes' => [
                    'data-datepicker'
                ],
            ],
            'oxactiveto' => [
                'type' => 'text',
                'class' => 'w50',
                'validation' => 'datetime',
                'autocomplete' => 'off',
                'attributes' => [
                    'data-datepicker'
                ],
            ],
            'applyFields' => [
                'type' => 'button',
                'value' => 'Eingabefelder hinzufügen',
                'attributes' => [
                    'onclick="JavaScript:showDialog(\'&cl=[{ $controller }]&aoc=1&oxid=[{ $oxid }]\');"',
                ],
            ],
            'submit' => [
                'name' => 'save',
                'type' => 'submit',
                'value' => 'Senden',
                'attributes' => [
                    'onclick="Javascript:document.myedit.fnc.value=\'save\'"',
                ],
            ],
        ];
    }
}

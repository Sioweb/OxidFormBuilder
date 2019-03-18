<?php

namespace Ci\Oxid\FormBuilder\Form\Admin;

class Forms implements \Sioweb\Lib\Formgenerator\Core\FormInterface, \Sioweb\Lib\Formgenerator\Core\SubpaletteInterface
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

    public function loadSubpalettes()
    {
        return [
            'oxsendform' => ['oxreceiver', 'oxsubject', 'oxcontent', 'oxconfirm'],
            'oxconfirm' => ['oxreceiver_confirm', 'oxsubject_confirm', 'oxcontent_confirm'],
        ];
    }

    public function loadPalettes()
    {
        return [
            'default' => [
                'action' => [
                    'fields' => [
                        'oxsendform',
                    ],
                ],
                'default' => [
                    'class' => 'long',
                    'fields' => ['applyFields', 'oxtitle', 'oxhtmltemplate', 'oxaction'],
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
            'oxsendform' => [
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
            'oxconfirm' => [
                'type' => 'checkbox'
            ],
            'oxsubject_confirm' => [
                'type' => 'text'
            ],
            'oxcontent_confirm' => [
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

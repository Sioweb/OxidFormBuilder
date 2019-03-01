<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = [
    'id'           => 'CiFormBuilder',
    'title'        => '<b style="color: #76787A">Creative Inneneinrichter</b> | Forms',
    'description'  => 'Tool to builod oxid forms',
    'version' => '2.0',
    'url' => 'https://www.seipp.com',
    'email' => 'marketing@seipp.com',
    'author' => 'Sascha Weidner',
    'extend'      => [
        \OxidEsales\Eshop\Core\UtilsView::class =>
            \Ci\Oxid\FormBuilder\Core\Utilsview::class,
        \OxidEsales\Eshop\Core\Email::class =>
            \Ci\Oxid\FormBuilder\Core\Email::class,
    ],
    'controllers' => [
        'formbuilder' => \Ci\Oxid\FormBuilder\Controller\Form::class,
        'ciadminforms' => \Ci\Oxid\FormBuilder\Controller\Admin\Form\Frame::class,
        'ciadminformlist' => \Ci\Oxid\FormBuilder\Controller\Admin\Form\Forms::class,
        'ciadminformmain' => \Ci\Oxid\FormBuilder\Controller\Admin\Form\Form::class,
        'ciadminformmain_ajax' => \Ci\Oxid\FormBuilder\Controller\Admin\Form\FormAjax::class,
        'ciadminformelement' => \Ci\Oxid\FormBuilder\Controller\Admin\Element\Frame::class,
        'ciadminformelementlist' => \Ci\Oxid\FormBuilder\Controller\Admin\Element\Elements::class,
        'ciadminformelementmain' => \Ci\Oxid\FormBuilder\Controller\Admin\Element\Element::class,
    ],
    'events'       => [
        'onActivate'   => 'Ci\Oxid\FormBuilder\Core\Events::onActivate',
        'onDeactivate' => 'Ci\Oxid\FormBuilder\Core\Events::onDeactivate'
    ],
    'templates' => [
        // Admin-Bereich
        'ci_admin_frame_form.tpl' => 'ci-haeuser/FormBuilder/views/admin/tpl/form/ci_admin_frame_form.tpl',
        'ci_admin_form_popup.tpl' => 'ci-haeuser/FormBuilder/views/admin/tpl/popups/ci_admin_form_popup.tpl',
        'ci_admin_form.tpl' => 'ci-haeuser/FormBuilder/views/admin/tpl/form/ci_admin_form.tpl',
        'ci_admin_forms.tpl' => 'ci-haeuser/FormBuilder/views/admin/tpl/form/ci_admin_forms.tpl',
        'ci_admin_frame_element.tpl' => 'ci-haeuser/FormBuilder/views/admin/tpl/element/ci_admin_frame_element.tpl',
        'ci_admin_element.tpl' => 'ci-haeuser/FormBuilder/views/admin/tpl/element/ci_admin_element.tpl',
        'ci_admin_elements.tpl' => 'ci-haeuser/FormBuilder/views/admin/tpl/element/ci_admin_elements.tpl',

        'ci_formbuilder_form.tpl' => 'ci-haeuser/FormBuilder/views/tpl/smarty/ci_formbuilder_form.tpl',
        'ci_formbuilder_eval.tpl' => 'ci-haeuser/FormBuilder/views/tpl/smarty/ci_formbuilder_eval.tpl',

        'ci_forumbuilder_element_text.tpl' => 'ci-haeuser/FormBuilder/views/tpl/element/ci_forumbuilder_element_text.tpl',
        'ci_forumbuilder_element_checkbox.tpl' => 'ci-haeuser/FormBuilder/views/tpl/element/ci_forumbuilder_element_checkbox.tpl',
        'ci_forumbuilder_element_select.tpl' => 'ci-haeuser/FormBuilder/views/tpl/element/ci_forumbuilder_element_select.tpl',
        'ci_forumbuilder_element_fieldset.tpl' => 'ci-haeuser/FormBuilder/views/tpl/element/ci_forumbuilder_element_fieldset.tpl',
        'ci_forumbuilder_element_button.tpl' => 'ci-haeuser/FormBuilder/views/tpl/element/ci_forumbuilder_element_button.tpl',
        'ci_forumbuilder_element_submit.tpl' => 'ci-haeuser/FormBuilder/views/tpl/element/ci_forumbuilder_element_submit.tpl',
        'ci_forumbuilder_element_textarea.tpl' => 'ci-haeuser/FormBuilder/views/tpl/element/ci_forumbuilder_element_textarea.tpl',
    ],
    'blocks' => [
        [
            'template' => 'headitem.tpl',
            'block' => 'admin_headitem_inccss',
            'file' => 'admin_headitem_inccss.tpl',
        ],
        [
            'template' => 'headitem.tpl',
            'block' => 'admin_headitem_incjs',
            'file' => 'admin_headitem_incjs.tpl',
        ],
    ],
];

<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

function smarty_function_formbuilderbutton($params, &$smarty)
{
    if (empty($params['oxid'])) {
        return '';
    }
    
    $Renderer = oxNew(\OxidEsales\Eshop\Application\Model\SmartyRenderer::class);
    // $Form = oxNew(\Ci\Oxid\FormBuilder\Model\Form::class);
    
    // $Form->setParams($params);

    return $Renderer->renderTemplate('ci_formbuilder_form_buttons.tpl', array(
        'oxid' => $params['oxid'],
        'class' => !empty($params['class']) ? $params['class'] : null,
        'title' => !empty($params['title']) ? $params['title'] : null,
        'headline' => !empty($params['headline']) ? $params['headline'] : null,
        'content' => !empty($params['content']) ? $params['content'] : null,
        'button' => !empty($params['button']) ? $params['button'] : null,
    ));
}

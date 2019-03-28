<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

function smarty_function_formbuilder($params, &$smarty)
{
    if (empty($params['oxid'])) {
        return '';
    }
    
    $Renderer = oxNew(\OxidEsales\Eshop\Application\Model\SmartyRenderer::class);
    $Form = oxNew(\Ci\Oxid\FormBuilder\Model\Form::class);
    
    $Form->setParams($params);

    return $Renderer->renderTemplate('ci_formbuilder_form.tpl', array(
        'oxid' => $params['oxid'],
        'form' => $Form->loadWithFields($params['oxid']),
    ));
}

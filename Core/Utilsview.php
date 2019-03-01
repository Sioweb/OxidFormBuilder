<?php

namespace Ci\Oxid\FormBuilder\Core;

use OxidEsales\Eshop\Core\Registry;

class Utilsview extends Utilsview_parent
{
    protected function _fillCommonSmartyProperties($oSmarty)
    {
        parent::_fillCommonSmartyProperties($oSmarty);
        array_unshift($oSmarty->plugins_dir, Registry::getConfig()->getModulesDir() . "/ci-haeuser/FormBuilder/Smarty/Plugins/");
    }
}

<?php

namespace Ci\Oxid\FormBuilder\Model;

use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class FormElement extends MultiLanguageModel
{
    public function __construct()
    {
        parent::__construct();
        $this->init("ci_form_element");
    }
}

<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

namespace Ci\Oxid\FormBuilder\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class Elements2Form extends MultiLanguageModel
{
    public $aList = [];

    public function __construct()
    {
        parent::__construct();
        $this->init("ci_form_element2form");
    }

    public function loadByElementInForm($FieldId, $FormId)
    {
        $Database = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        $sSelect = "SELECT
            ci_form_element2form.*
            FROM ci_form_element2form
                LEFT JOIN ci_form_element ON (ci_form_element.OXID = ci_form_element2form.OXELEMENTID)
            WHERE ci_form_element2form.OXELEMENTID = ? AND ci_form_element2form.OXFORMID = ?
        ";

        $this->load($Database->getOne($sSelect, [$FieldId, $FormId]));
    }
}

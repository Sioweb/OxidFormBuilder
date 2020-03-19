<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

namespace Ci\Oxid\FormBuilder\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class FormElement extends MultiLanguageModel
{
    public function __construct()
    {
        parent::__construct();
        $this->init("ci_form_element");
    }

    public function findByOverwritable($FieldId, $FormId)
    {
        $Database = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        $sSelect = "SELECT
            ci_form_element.*,
            ci_form_element2form.*
            FROM ci_form_element2form
                LEFT JOIN ci_form_element ON (ci_form_element.OXID = ci_form_element2form.OXELEMENTID)
            WHERE ci_form_element2form.OXELEMENTID = ? AND ci_form_element2form.OXFORMID = ?
            GROUP BY ci_form_element.OXID
        ";

        $this->load($Database->getOne($sSelect, [$FieldId, $FormId]));
        $Fields = $Database->select($sSelect, [$FieldId, $FormId])->fetchAll()[0];
        foreach ($Fields as $key => $value) {
            if (substr($key, 0, 1) === '_') {
                continue;
            }
            if (!empty($Fields['_' . $key])) {
                $this->assign([$key => $Fields['_' . $key]]);
            }
        }

        return $Fields;
    }
}

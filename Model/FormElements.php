<?php

namespace Ci\Oxid\FormBuilder\Model;

use Ci\Oxid\FormBuilder\Model\FormElement;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class FormElements extends MultiLanguageModel
{
    public $aList = [];

    public function __construct()
    {
        parent::__construct();
        $this->init("ci_form_element");

        $Database = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
        $aData = $Database->select("SELECT * FROM ci_form_element");
        $aData = $aData->fetchAll();

        $this->aList = [];
        if ($aData) {
            foreach ($aData as $data) {
                $FormElement = oxNew(FormElement::class);
                $FormElement->assign($data);
                $this->aList[] = $FormElement;
            }
        }
    }

    public function findByParent($ParentId)
    {
        $Database = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        $sSelect = "SELECT
            ci_form_element2form.*,
            ci_form_element.*
            FROM ci_form_element2form
                LEFT JOIN ci_form_element ON (ci_form_element.OXID = ci_form_element2form.OXELEMENTID)
            WHERE ci_form_element2form.OXFORMID = ?
            GROUP BY ci_form_element.OXID
        ";

        $Database->select($sSelect, [$ParentId]);
        $Fields = $Database->select($sSelect, [$ParentId])->fetchAll();
        foreach ($this->aList as $elementIndex => $Element) {
            if(!empty($Fields[$elementIndex]['OXOPTIONS'])) {
                $Fields[$elementIndex]['OXOPTIONS'] = json_decode($Fields[$elementIndex]['OXOPTIONS'], 1);
            }
            if(!empty($Fields[$elementIndex]['_OXOPTIONS'])) {
                $Fields[$elementIndex]['_OXOPTIONS'] = json_decode($Fields[$elementIndex]['_OXOPTIONS'], 1);
            }
            foreach ($Fields[$elementIndex] as $key => $value) {
                if (substr($key, 0, 1) === '_') {
                    continue;
                }
                
                if (!empty($Fields[$elementIndex]['_' . $key])) {
                    $Element->assign([$key => $Fields[$elementIndex]['_' . $key]]);
                    $Fields[$elementIndex][$key] = $Fields[$elementIndex]['_' . $key];
                }
                if (array_key_exists('_' . $key, $Fields[$elementIndex])) {
                    unset($Fields[$elementIndex]['_' . $key]);
                }
            }
        }

        return $Fields;
    }
}

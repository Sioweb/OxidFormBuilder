<?php

namespace Ci\Oxid\FormBuilder\Model;

use OxidEsales\Eshop\Core\Model\MultiLanguageModel;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\UtilsDate;
use OxidEsales\Eshop\Core\TableViewNameGenerator;
use Ci\Oxid\FormBuilder\Model\FormElement;

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
            ci_form_element.*
            FROM ci_form_element2form
                LEFT JOIN ci_form_element ON (ci_form_element.OXID = ci_form_element2form.OXELEMENTID)
            WHERE ci_form_element2form.OXFORMID = ?
            GROUP BY ci_form_element.OXID
        ";

        return $Database->select($sSelect, [$ParentId])->fetchAll();
    }
}

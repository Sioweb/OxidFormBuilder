<?php

namespace Ci\Oxid\FormBuilder\Controller\Admin\Element;

use Ci\Oxid\FormBuilder\Core\FormRender;
use Ci\Oxid\FormBuilder\Model\Elements2Form as Elements2FormModel;
use Ci\Oxid\FormBuilder\Model\FormElement as ElementModel;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Core\Registry;
use Sioweb\Lib\Formgenerator\Core\Form;
use stdClass;

class Element extends AdminDetailsController
{

    public function elementEdit()
    {

        $ElementConfig = new class() extends \Ci\Oxid\FormBuilder\Form\Admin\Elements
        {
            public function loadData()
            {
                $Data = parent::loadData();
                $Data['form']['noFieldsets'] = true;
                $Data['form']['updateValues'] = true;
                $Data['form']['defaultPalette'] = $_GET['palette'];
                return $Data;
            }

            public function loadFieldConfig()
            {
                $Data = parent::loadFieldConfig();
                $Data = array_filter($Data, function ($var) {
                    return !empty($var['editable']);
                });
                return $Data;
            }
        };

        $Form = new Form(
            new FormRender,
            $ElementConfig
        );

        $ElementModel = oxNew(ElementModel::class);
        $ElementModel->findByOverwritable($this->getEditObjectId(), $this->getConfig()->getRequestParameter("oxformid"));

        $FormData = ['editval' => []];
        $FormTable = $ElementConfig->loadData()['form']['table'];
        foreach ($ElementModel->getFieldNames() as $name) {
            if (!empty($ElementModel->{$FormTable . '__' . $name}->value)) {
                $FormData['editval'][$FormTable . '__' . $name] = $ElementModel->{$FormTable . '__' . $name}->value;
            }
        }

        $Form->setFieldValues($FormData);
        $Form->setFormData();

        die(implode("\n", $Form->generate()));
    }

    public function elementSave()
    {
        $soxId = $this->getEditObjectId();

        if ($soxId === -1) {
            return;
        }

        $ElementModel = oxNew(Elements2FormModel::class);
        $ElementModel->loadByElementInForm($soxId, $this->getConfig()->getRequestParameter("oxformid"));

        $_Editval = $this->getConfig()->getRequestEscapedParameter("editval");

        $Editval = [];
        foreach ($_Editval as $name => $value) {
            $name = end((explode('__', $name)));
            $Editval['_' . $name] = $value;
        }
        unset($_Editval);
        $ElementModel->assign($Editval);
        $ElementModel->save();
    }

    public function render()
    {
        $Form = new Form(
            new FormRender,
            new \Ci\Oxid\FormBuilder\Form\Admin\Elements
        );

        parent::render();
        $config = $this->getConfig();

        // check if we right now saved a new entry
        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();

        if ($this->isNewEditObject() !== true) {
            // load object
            $ElementModel = oxNew(ElementModel::class);
            $ElementModel->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $ElementModel->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                $ElementModel->loadInLang(key($oOtherLang), $soxId);
            }

            // $ElementModel->ci_form__oxroutes->rawValue = json_decode($ElementModel->ci_form__oxroutes->rawValue, true);

            $this->_aViewData["edit"] = $ElementModel;

            // remove already created languages
            $aLang = array_diff(Registry::getLang()->getLanguageNames(), $oOtherLang);

            if (count($aLang)) {
                $this->_aViewData["posslang"] = $aLang;
            }

            foreach ($oOtherLang as $id => $language) {
                $oLang = new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }

            $FormData = ['editval' => []];
            foreach ($ElementModel->getFieldNames() as $name) {
                if (!empty($ElementModel->{'ci_form_element__' . $name}->value)) {
                    $FormData['editval']['ci_form_element__' . $name] = $ElementModel->{'ci_form_element__' . $name}->value;
                }
            }

            $Form->setFieldValues($FormData);
        }

        $Form->setFormData();

        $this->_aViewData["form"] = implode("\n", $Form->generate());

        $iAoc = $this->getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oArticleExtendAjax = oxNew(FormsMainAjax::class);
            $this->_aViewData['oxajax'] = $oArticleExtendAjax->getColumns();

            return "ci_admin_element_popup.tpl";
        }

        return "ci_admin_element.tpl";
    }

    public function save()
    {
        $myConfig = $this->getConfig();
        parent::save();

        $soxId = $this->getEditObjectId();
        $aParams = Registry::getConfig()->getRequestParameter("editval");

        $oFormElement = oxNew(ElementModel::class);
        if ($soxId != "-1") {
            $oFormElement->load($soxId);
        } else {
            $aParams['ci_form_element__oxid'] = null;
        }

        // if (!$aParams['ci_form_element__oxactive']) {
        //     $aParams['ci_form_element__oxactive'] = 0;
        // }

        $oFormElement->setLanguage(0);
        $oFormElement->assign($aParams);
        $oFormElement->setLanguage($this->_iEditLang);
        // $oFormElement = Registry::get(UtilsFile::class)->processFiles($oFormElement);
        $oFormElement->save();

        // set oxid if inserted
        $this->setEditObjectId($oFormElement->getId());
    }

    /**
     * Saves changed selected hotspot parameters in different language.
     */
    public function saveinnlang()
    {
        $this->save();
    }
}

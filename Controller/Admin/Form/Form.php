<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

namespace Ci\Oxid\FormBuilder\Controller\Admin\Form;

use Ci\Oxid\FormBuilder\Core\FormRender;
use Ci\Oxid\FormBuilder\Core\StringUtil;
use Ci\Oxid\FormBuilder\Model\Form as FormModel;
use Ci\Oxid\FormBuilder\Model\FormElements as ElementModels;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Core\Model\ListModel;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use Sioweb\Lib\Formgenerator\Core\Form as FormGenerator;
use stdClass;

class Form extends AdminDetailsController
{
    public function render()
    {
        $FormGenerator = new FormGenerator(
            new FormRender,
            oxNew(\Ci\Oxid\FormBuilder\Form\Admin\Forms::class)
        );

        parent::render();
        $config = $this->getConfig();

        // check if we right now saved a new entry
        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();

        if ($this->isNewEditObject() !== true) {
            // load object
            $FormModel = oxNew(FormModel::class);
            $FormModel->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $FormModel->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                $FormModel->loadInLang(key($oOtherLang), $soxId);
            }

            $FormModel->ci_form__oxroutes->rawValue = json_decode($FormModel->ci_form__oxroutes->rawValue, true);
            // $FormModel->ci_form__oxfieldconfig->rawValue = json_decode($FormModel->ci_form__oxfieldconfig->rawValue, true);
            // $FormModel->ci_form__oxattachments->rawValue = json_decode($FormModel->ci_form__oxattachments->rawValue, true);

            $FormFieldData = oxNew(\Ci\Oxid\FormBuilder\Form\Admin\Forms::class)->loadFieldConfig();
            foreach($FormFieldData as $key => $fieldConfig) {
                if(!empty($fieldConfig['json'])) {
                    $FormModel->{'ci_form__' . $key}->rawValue = json_decode($FormModel->{'ci_form__' . $key}->rawValue, 1);
                }
            }

            if (empty($FormModel->ci_form__oxfieldconfig->rawValue)) {
                $FormModel->ci_form__oxfieldconfig->rawValue = [
                    [
                        'legend' => '',
                        'oxid' => [],
                    ],
                ];
            }

            $this->_aViewData["edit"] = $FormModel;

            // remove already created languages
            $aLang = array_diff(Registry::getLang()->getLanguageNames(), $oOtherLang);

            if (count($aLang)) {
                $this->_aViewData["posslang"] = $aLang;
            }

            $ElementModels = oxNew(ElementModels::class);
            $FieldConfig = $ElementModels->findByParent($soxId);
            $_fc = [];
            foreach ($FieldConfig as $field) {
                if (!empty($field['OXID'])) {
                    $_fc[$field['OXID']] = $field;
                }
            }
            $FieldConfig = $_fc;
            unset($_fc);

            $RemovedFields = [];
            $UnappliedFields = [];
            foreach ($FormModel->ci_form__oxfieldconfig->rawValue as $fieldKey => &$fieldset) {
                $_fs = [];
                foreach ($fieldset['fields'] as $fieldId) {
                    if (empty($FieldConfig[$fieldId])) {
                        $RemovedFields[$fieldId] = 1;
                    } else {
                        $UnappliedFields[$fieldId] = 1;
                        $_fs[$fieldId] = $FieldConfig[$fieldId];
                    }
                }

                if (!empty($_fs)) {
                    $fieldset['fields'] = $_fs;
                }
                unset($_fs);
            }

            unset($fieldset);
            $this->_aViewData["fieldconfig"] = $FormModel->ci_form__oxfieldconfig->rawValue;
            $this->_aViewData["unapplied"] = array_diff_key($FieldConfig, $UnappliedFields);
            $this->_aViewData["removedFields"] = $RemovedFields;

            foreach ($oOtherLang as $id => $language) {
                $oLang = new stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }

            $FormData = ['editval' => []];
            foreach ($FormModel->getFieldNames() as $name) {
                if (!empty($FormModel->{'ci_form__' . $name}->value)) {
                    $FormData['editval']['ci_form__' . $name] = $FormModel->{'ci_form__' . $name}->value;
                }
            }

            if (empty($FormData['editval']['ci_form__oxhtmltemplate'])) {
                $StringUtil = oxNew(StringUtil::class);
                $FormData['editval']['ci_form__oxhtmltemplate'] = $StringUtil->standardize($FormData['editval']['ci_form__oxtitle']);
            }

            $FormGenerator->setFieldValues($FormData);
        }

        $request = oxNew(Request::class);
        $FormGenerator->stringVariables([
            'oxid' => $soxId,
            'controller' => $request->getRequestEscapedParameter("cl"),
        ]);
        $FormGenerator->setFormData();

        $this->_aViewData["form"] = implode("\n", $FormGenerator->generate());

        // loading shop
        $oShop = oxNew(Shop::class);
        $oShop->loadInLang($this->_iEditLang, $config->getShopId());

        // loading static seo urls
        $sQ = "SELECT oxstdurl, oxobjectid, oxseourl FROM oxseo WHERE oxtype='static' && oxlang = ? && oxshopid = ? GROUP BY oxobjectid ORDER BY oxstdurl";

        $oStaticUrlList = oxNew(ListModel::class);
        $oStaticUrlList->init('oxbase', 'oxseo');
        $oStaticUrlList->selectString($sQ, [$this->_iEditLang, $oShop->getId()]);

        $this->_aViewData['aStaticUrls'] = $oStaticUrlList;

        $iAoc = $this->getConfig()->getRequestParameter("aoc");
        if ($iAoc == 1) {
            $oArticleExtendAjax = oxNew(FormAjax::class);
            $this->_aViewData['oxajax'] = $oArticleExtendAjax->getColumns();

            return "ci_admin_form_popup.tpl";
        }

        return "ci_admin_form.tpl";
    }

    public function save()
    {
        parent::save();

        $config = $this->getConfig();

        $Form = oxNew(FormModel::class);

        if ($this->isNewEditObject() !== true) {
            $Form->load($this->getEditObjectId());
        }


        if ($this->checkAccessToEditForm($Form) === true) {
            $Form->assign($this->getFormFormData());
            $Form->setLanguage($this->_iEditLang);
            $Form->save();

            $this->setEditObjectId($Form->getId());
        }
    }

    /**
     * Saves changed selected hotspot parameters in different language.
     */
    public function saveinnlang()
    {
        $this->save();
    }

    /**
     * Checks access to edit Form.
     *
     * @param FormModel $Form
     *
     * @return bool
     */
    protected function checkAccessToEditForm(FormModel $Form)
    {
        return true;
    }

    /**
     * Returns form data for Form.
     *
     * @return array
     */
    private function getFormFormData()
    {
        $request = oxNew(Request::class);
        $formData = $request->getRequestEscapedParameter("editval");
        $formData = $this->normalizeFormFormData($formData);
        $Fieldset = $request->getRequestEscapedParameter('fieldset');
        unset($Fieldset['x']);
        $formData['ci_form__oxfieldconfig'] = json_encode($Fieldset);
        $StringUtil = oxNew(StringUtil::class);
        if (empty($formData['ci_form__oxhtmltemplate'])) {
            $formData['ci_form__alias'] = $StringUtil->standardize($formData['ci_form__oxtitle']);
        }

        foreach($formData as $key => &$value) {
            if(is_array($value)) {
                $value = json_encode($value);
            }
        }
        unset($value);

        return $formData;
    }

    /**
     * Normalizes form data for Form.
     *
     * @param   array $formData
     *
     * @return  array
     */
    private function normalizeFormFormData($formData)
    {
        if ($this->isNewEditObject() === true) {
            $formData['ci_form__oxid'] = null;
        }

        if (!$formData['ci_form__oxactive']) {
            $formData['ci_form__oxactive'] = 0;
        }

        return $formData;
    }
}

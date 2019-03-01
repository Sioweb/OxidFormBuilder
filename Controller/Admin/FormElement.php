<?php

namespace Ci\Oxid\FormBuilder\Controller\Admin;


use stdClass;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Shop;
use OxidEsales\Eshop\Core\Model\ListModel;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use Ci\Oxid\FormBuilder\Model\FormElement as FormElementModel;

class FormElement extends AdminDetailsController
{
    public function render()
    {
        parent::render();
        $config = $this->getConfig();

        // check if we right now saved a new entry
        $soxId = $this->_aViewData["oxid"] = $this->getEditObjectId();
        
        if ($this->isNewEditObject() !== true) {
            // load object
            $Form = oxNew(FormElementModel::class);
            $Form->loadInLang($this->_iEditLang, $soxId);

            $oOtherLang = $Form->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                $Form->loadInLang(key($oOtherLang), $soxId);
            }

            $Form->ci_form__oxroutes->rawValue = json_decode($Form->ci_form__oxroutes->rawValue, true);
            
            $this->_aViewData["edit"] = $Form;
            
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
        }

        
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
            $oArticleExtendAjax = oxNew(FormsMainAjax::class);
            $this->_aViewData['oxajax'] = $oArticleExtendAjax->getColumns();

            return "ci_admin_form_element_popup.tpl";
        }

        return "ci_admin_form_element.tpl";
    }

    public function save()
    {
        parent::save();

        $config = $this->getConfig();

        $Form = oxNew(FormElementModel::class);

        if ($this->isNewEditObject() !== true) {
            $Form->load($this->getEditObjectId());
        }

        if ($this->checkAccessToEditForm($Form) === true) {
            $Form->assign($this->getFormFormData());
            $Form->setLanguage($this->_iEditLang);
            $Form = Registry::getUtilsFile()->processFiles($Form);

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
     * @param FormElementModel $Form
     *
     * @return bool
     */
    protected function checkAccessToEditForm(FormElementModel $Form)
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
        $request    = oxNew(Request::class);
        $formData   = $request->getRequestEscapedParameter("editval");
        $formData   = $this->normalizeFormFormData($formData);

        $formData['ci_form__oxroutes'] = json_encode($formData['ci_form__oxroutes']);
    
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
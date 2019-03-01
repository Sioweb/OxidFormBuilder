<?php

namespace Ci\Oxid\FormBuilder\Controller\Admin\Form;

use OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

class FormAjax extends ListComponentAjax
{
    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = [
        'container1' => [ // field , table, visible, multilanguage, ident
            ['oxlabel', 'ci_form_element', 1, 1, 0],
            ['oxtitle', 'ci_form_element', 1, 1, 0],
            ['oxid', 'ci_form_element', 0, 0, 1],
        ],
        'container2' => [
            ['oxlabel', 'ci_form_element', 1, 1, 0],
            ['oxtitle', 'ci_form_element', 1, 1, 0],
            ['oxvalue', 'ci_form_element', 0, 0, 1],
            ['oxrequire', 'ci_form_element', 0, 0, 1],
            ['oxvalidation', 'ci_form_element', 0, 0, 1],
            ['oxplaceholder', 'ci_form_element', 0, 0, 1],
            ['oxcssclass', 'ci_form_element', 0, 0, 1],
            ['oxsort', 'ci_form_element', 0, 0, 1],
            ['oxid', 'ci_form_element2form', 0, 0, 1],
            ['oxelementid', 'ci_form_element2form', 0, 0, 1],
            ['oxformid', 'ci_form_element2form', 0, 0, 1],
        ],
    ];

    /**
     * Returns SQL query for data to fetc
     *
     * @return string
     */
    protected function _getQuery()
    {
        $oDb = DatabaseProvider::getDb();
        $sArtId = Registry::getConfig()->getRequestParameter('oxid');
        $sSynchArtId = Registry::getConfig()->getRequestParameter('synchoxid');

        $sAttrViewName = $this->_getViewName('ci_form_element');
        $sO2AViewName = $this->_getViewName('ci_form_element2form');
        if ($sArtId) {
            // all categories article is in
            $sQAdd = " from {$sO2AViewName} left join {$sAttrViewName} " .
            "on {$sAttrViewName}.oxid={$sO2AViewName}.oxelementid " .
            " where {$sO2AViewName}.oxformid = " . $oDb->quote($sArtId) . " ";
        } else {
            $sQAdd = " from {$sAttrViewName} where {$sAttrViewName}.oxid not in ( select {$sO2AViewName}.oxelementid " .
            "from {$sO2AViewName} left join {$sAttrViewName} " .
            "on {$sAttrViewName}.oxid={$sO2AViewName}.oxelementid " .
            " where {$sO2AViewName}.oxformid = " . $oDb->quote($sSynchArtId) . " ) ";
        }

        return $sQAdd;
    }

    /**
     * Removes article attributes.
     */
    public function removeAttr()
    {
        $aChosenArt = $this->_getActionIds('ci_form_element2form.oxid');
        $sOxid = Registry::getConfig()->getRequestParameter('oxid');
        if (Registry::getConfig()->getRequestParameter('all')) {
            $sO2AViewName = $this->_getViewName('ci_form_element2form');
            $sQ = $this->_addFilter("delete $sO2AViewName.* " . $this->_getQuery());
            DatabaseProvider::getDb()->Execute($sQ);
        } elseif (is_array($aChosenArt)) {
            $sChosenArticles = implode(", ", DatabaseProvider::getDb()->quoteArray($aChosenArt));
            $sQ = "delete from ci_form_element2form where ci_form_element2form.oxid in ({$sChosenArticles}) ";
            DatabaseProvider::getDb()->Execute($sQ);
        }

        $this->onArticleAttributeRelationChange($sOxid);
    }

    /**
     * Adds attributes to article.
     */
    public function addAttr()
    {
        $aAddCat = $this->_getActionIds('ci_form_element.oxid');
        $soxId = Registry::getConfig()->getRequestParameter('synchoxid');

        if (Registry::getConfig()->getRequestParameter('all')) {
            $sAttrViewName = $this->_getViewName('ci_form_element');
            $aAddCat = $this->_getAll($this->_addFilter("select $sAttrViewName.oxid " . $this->_getQuery()));
        }

        if ($soxId && $soxId != "-1" && is_array($aAddCat)) {
            foreach ($aAddCat as $sAdd) {
                $oNew = oxNew(BaseModel::class);
                $oNew->init("ci_form_element2form");
                $oNew->ci_form_element2form__oxformid = new Field($soxId);
                $oNew->ci_form_element2form__oxelementid = new Field($sAdd);
                $oNew->save();
            }

            $this->onArticleAttributeRelationChange($soxId);
        }
    }

    /**
     * Saves attribute value
     *
     * @return null
     */
    public function saveAttributeValue()
    {
        $database = DatabaseProvider::getDb();
        $this->resetContentCache();

        $articleId = Registry::getConfig()->getRequestParameter("oxid");
        $attributeId = Registry::getConfig()->getRequestParameter("attr_oxid");
        $attributeValue = Registry::getConfig()->getRequestParameter("attr_value");

        $article = oxNew(Article::class);
        if ($article->load($articleId)) {
            if ($article->isDerived()) {
                return;
            }

            $this->onAttributeValueChange($article);

            if (isset($attributeId) && ("" != $attributeId)) {
                $viewName = $this->_getViewName("ci_form_element2form");
                $quotedArticleId = $database->quote($article->oxarticles__oxid->value);
                $select = "select * from {$viewName} where {$viewName}.oxformid= {$quotedArticleId} and
                            {$viewName}.oxattrid= " . $database->quote($attributeId);
                $objectToAttribute = oxNew(MultiLanguageModel::class);
                $objectToAttribute->setLanguage(Registry::getConfig()->getRequestParameter('editlanguage'));
                $objectToAttribute->init("ci_form_element2form");
                if ($objectToAttribute->assignRecord($select)) {
                    $objectToAttribute->ci_form_element2form__oxvalue->setValue($attributeValue);
                    $objectToAttribute->save();
                }
            }
        }
    }

    /**
     * Method is used to bind to attribute and article relation change action.
     *
     * @param string $articleId
     */
    protected function onArticleAttributeRelationChange($articleId)
    {
    }

    /**
     * Method is used to bind to attribute value change.
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $article
     */
    protected function onAttributeValueChange($article)
    {
    }
}

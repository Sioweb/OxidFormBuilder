<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

namespace Ci\Oxid\FormBuilder\Core;

use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;

use Sioweb\Lib\Formgenerator\Core\Form;

class FormRender extends \OxidEsales\Eshop\Core\Base implements \Sioweb\Lib\Formgenerator\Core\TemplateInterface
{

    /**
     * output handler object
     *
     * @see _getOuput
     *
     * @var oxOutput
     */
    protected $_oOutput = null;

    /**
     * errors to be displayed/returned
     *
     * @see _getErrors
     *
     * @var array
     */
    protected $_aErrors = null;

    /**
     * same as errors in session
     *
     * @see _getErrors
     *
     * @var array
     */
    protected $_aAllErrors = null;

    /**
     * same as controller errors in session
     *
     * @see _getErrors
     *
     * @var array
     */
    protected $_aControllerErrors = null;

    public function newInstance() {
        return new self();
    }

    public function render($FieldData, Form $FormObj = null)
    {
        $view = new class()
        {
            private $data = null;

            public function __destruct()
            {
                $this->data = null;
            }

            public function setViewData($Data)
            {
                $this->data = $Data;
                $Template = $this->data['type'];
                if (!empty($this->data['template'])) {
                    $Template = $this->data['template'];
                }
                if (!empty($this->data['options'])) {
                    $this->translateOptions();
                }

                $this->template = 'ci_forumbuilder_element_' . strtolower($Template) . '.tpl';
            }

            public function render()
            {
                return $this->template;
            }

            public function getViewData()
            {
                return $this->data;
            }

            public function getClassName()
            {
                return 'none';
            }

            public function getViewId()
            {
                return 'none';
            }

            private function translateOptions()
            {
                if(empty($this->data['options'])) {
                    return;
                }
                
                $Language = oxNew(Language::class);
                $options = [];
                foreach ($this->data['value'] as $key => $optionSet) {
                    if (!is_array($optionSet['value'])) {
                        $translationString = 'FORMBUILDER_VALUE_' . strtoupper($this->data['fieldId']) . '_' . $optionSet['value'];
                        $_translation = $Language->translateString($translationString);
                        if($Language->isTranslated()) {
                            $optionSet['value'] = $_translation;
                        }
                    } else {
                        foreach ($optionSet['value'] as $type => &$value) {
                            $_translation = $Language->translateString($value);
                            if(!$Language->isTranslated()) {
                                $translationString = 'FORMBUILDER_VALUE_' . strtoupper($this->data['fieldId']) . '_' . $optionSet['value'];
                                $_translation = $Language->translateString($translationString);
                                if(!$Language->isTranslated()) {
                                    $_translation = $value;
                                }
                            }

                            $value = $_translation;
                        }
                    }

                    array_push($options, $optionSet);
                }

                $this->data['value'] = $options;
            }
        };
        // set field config.forceLabel === true to load label
        $Language = oxNew(Language::class);
        
        if ($FieldData->legend !== false && empty($FieldData->legend)) {
            $FieldData->legend = $Language->translateString('FORMBUILDER_LEGEND_' . strtoupper($FieldData->fieldId));
            if($FieldData->legend === 'FORMBUILDER_LEGEND_' . strtoupper($FieldData->fieldId)) {
                unset($FieldData->legend);
            }
        } elseif (!empty($FieldData->legend)) {
            if(!is_array($FieldData->legend)) {
                $translation = $Language->translateString($FieldData->legend);
                // if($translation !== $FieldData->legend) {
                    $FieldData->legend = $translation;
                // } else {
                //     unset($FieldData->legend);
                // }
            } else {
                foreach($FieldData->legend as &$translateString) {
                    $translation = $Language->translateString($translateString);
                    if($translation !== $translateString) {
                        $translateString = $translation;
                    } else {
                        $translateString = '';
                    }
                }
                unset($translateString);
                $FieldData->legend = array_filter($FieldData->legend);
                $FieldData->legend = implode("\n", $FieldData->legend);
            }
        }
        
        if ($FieldData->label !== false && empty($FieldData->label)) {
            $FieldData->label = $Language->translateString('FORMBUILDER_LABEL_' . strtoupper($FieldData->fieldId));
            if($FieldData->label === 'FORMBUILDER_LABEL_' . strtoupper($FieldData->fieldId)) {
                unset($FieldData->label);
            }
        } elseif (!empty($FieldData->label)) {
            if(!is_array($FieldData->label)) {
                $translation = $Language->translateString($FieldData->label);
                // if($translation !== $FieldData->label) {
                    $FieldData->label = $translation;
                // } else {
                //     unset($FieldData->label);
                // }
            } else {
                foreach($FieldData->label as &$translateString) {
                    $translation = $Language->translateString($translateString);
                    if($translation !== $translateString) {
                        $translateString = $translation;
                    } else {
                        $translateString = '';
                    }
                }
                unset($translateString);
                $FieldData->label = array_filter($FieldData->label);
                $FieldData->label = implode("\n", $FieldData->label);
            }
        }
        
        if ($FieldData->help !== false && empty($FieldData->help)) {
            $FieldData->help = $Language->translateString('FORMBUILDER_LABEL_' . strtoupper($FieldData->fieldId) . '_HELP');
            if($FieldData->help === 'FORMBUILDER_LABEL_' . strtoupper($FieldData->fieldId) . '_HELP') {
                unset($FieldData->help);
            }
        } elseif (!empty($FieldData->help)) {
            if(!is_array($FieldData->help)) {
                $translation = $Language->translateString($FieldData->help);
                if($translation !== $FieldData->help) {
                    $FieldData->help = $translation;
                } else {
                    unset($FieldData->help);
                }
            } else {
                foreach($FieldData->help as &$translateString) {
                    $translation = $Language->translateString($translateString);
                    if($translation !== $translateString) {
                        $translateString = $translation;
                    } else {
                        $translateString = '';
                    }
                }
                unset($translateString);
                $FieldData->help = array_filter($FieldData->help);
                $FieldData->help = implode("\n", $FieldData->help);
            }
        }

        $view->setViewData((array) $FieldData);

        // get Smarty is important here as it sets template directory correct
        $smarty = Registry::getUtilsView()->getSmarty(1);

        // render it
        $templateName = $view->render();

        // check if template dir exists
        $templateFile = Registry::getConfig()->getTemplatePath($templateName, $this->isAdmin());
        if (!file_exists($templateFile)) {
            return null;
            $ex = oxNew(\OxidEsales\Eshop\Core\Exception\SystemComponentException::class);
            $ex->setMessage('EXCEPTION_SYSTEMCOMPONENT_TEMPLATENOTFOUND' . ' ' . $templateName);
            $ex->setComponent($templateName);

            $templateName = "message/exception.tpl";

            // if ($this->_isDebugMode()) {
            Registry::getUtilsView()->addErrorToDisplay($ex);
            // }
            $ex->debugOut();
        }

        // Output processing. This is useful for modules. As sometimes you may want to process output manually.
        $outputManager = $this->_getOutputManager();
        $viewData = $outputManager->processViewArray($view->getViewData(), $view->getClassName());
        $view->setViewData($viewData);


        //add all exceptions to display
        $errors = $this->_getErrors($view->getClassName());
        if (is_array($errors) && count($errors)) {
            Registry::getUtilsView()->passAllErrorsToView($viewData, $errors);
        }
        
        foreach (array_keys($viewData) as $viewName) {
            $smarty->assign_by_ref($viewName, $viewData[$viewName]);
        }

        // passing current view object to smarty
        $smarty->oxobject = $view;

        $output = $smarty->fetch($templateName, $view->getViewId());

        //Output processing - useful for modules as sometimes you may want to process output manually.
        $output = $outputManager->process($output, $view->getClassName());

        return $outputManager->addVersionTags($output);
    }

    /**
     * Return output handler.
     *
     * @return oxOutput
     */
    protected function _getOutputManager()
    {
        if (!$this->_oOutput) {
            $this->_oOutput = oxNew(\OxidEsales\Eshop\Core\Output::class);
        }

        return $this->_oOutput;
    }

    /**
     * Return page errors array.
     *
     * @param string $currentControllerName Class name
     *
     * @return array
     */
    protected function _getErrors($currentControllerName)
    {
        if (null === $this->_aErrors) {
            $this->_aErrors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('Errors');
            $this->_aControllerErrors = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('ErrorController');
            if (null === $this->_aErrors) {
                $this->_aErrors = [];
            }
            $this->_aAllErrors = $this->_aErrors;
        }
        // resetting errors of current controller or widget from session
        if (is_array($this->_aControllerErrors) && !empty($this->_aControllerErrors)) {
            foreach ($this->_aControllerErrors as $errorName => $controllerName) {
                if ($controllerName == $currentControllerName) {
                    unset($this->_aAllErrors[$errorName]);
                    unset($this->_aControllerErrors[$errorName]);
                }
            }
        } else {
            $this->_aAllErrors = [];
        }
        \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('ErrorController', $this->_aControllerErrors);
        \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('Errors', $this->_aAllErrors);

        return $this->_aErrors;
    }

}

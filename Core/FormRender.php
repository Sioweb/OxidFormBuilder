<?php

namespace Ci\Oxid\FormBuilder\Core;

use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;

class FormRender extends \OxidEsales\Eshop\Core\Base
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

    public function render($FieldData, $FormObj = null)
    {
        $view = new class()
        {

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

                $this->template = 'ci_forumbuilder_element_' . $Template . '.tpl';
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
                $Language = oxNew(Language::class);
                $options = [];
                foreach ($this->data['options'] as $option) {
                    $translationString = 'FORMBUILDER_VALUE_' . strtoupper($this->data['fieldId']) . '_' . $option;
                    $_translation = $Language->translateString($translationString);
                    if (!empty($_translation) && $_translation !== $translationString) {
                        $options[$option] = $_translation;
                    } else {
                        $options[$option] = $option;
                    }
                }

                $this->data['options'] = $options;
            }
        };

        // set field config.forceLabel === true to load label
        if ($FieldData->label !== false && empty($FieldData->label)) {
            $Language = oxNew(Language::class);
            $FieldData->label = $Language->translateString('FORMBUILDER_LABEL_' . strtoupper($FieldData->fieldId));
            $FieldData->help = $Language->translateString('FORMBUILDER_LABEL_' . strtoupper($FieldData->fieldId) . '_HELP');
        }

        $view->setViewData((array) $FieldData);

        // get Smarty is important here as it sets template directory correct
        $smarty = Registry::getUtilsView()->getSmarty();

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

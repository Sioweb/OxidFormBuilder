<?php

namespace Ci\Oxid\FormBuilder\Core\Module;

use OxidEsales\Eshop\Core\Module\Module as EshopModule;
use Ci\Oxid\FormBuilder\Core\Routing\Module\FormbuilderClassProviderStorage;

class ModuleInstaller extends ModuleInstaller_parent
{
    
    /**
     * Activate extension by merging module class inheritance information with shop module array
     *
     * @param EshopModule $module
     *
     * @return bool
     */
    public function activate(EshopModule $module)
    {
        if ($moduleId = $module->getId()) {
            $this->addModuleFormbuilderClasses($module->getInfo("formbuilder"), $moduleId);
        }
        return parent::activate($module);
    }

    /**
     * Add formbuilders map for a given module Id to config
     *
     * @param array  $moduleForms Map of formbuilder ids and class names
     * @param string $moduleId          The Id of the module
     */
    protected function addModuleFormbuilderClasses($moduleForms, $moduleId)
    {
        $this->validateModuleMetadataFormbuilderOnActivation($moduleForms);

        $classProviderStorage = $this->getFormbuilderProviderStorage();

        $classProviderStorage->add($moduleId, $moduleForms);
    }
    

    /**
     * Ensure integrity of the formbuilderMap before storing it.
     * Both keys and values must be unique with in the same shop or sub-shop.
     *
     * @param array $moduleForms
     *
     * @throws ModuleValidationException
     */
    protected function validateModuleMetadataFormbuilderOnActivation($moduleForms)
    {
        $moduleFormbuilderMapProvider = $this->getModuleFormbuilderMapProvider();
        $shopControllerMapProvider = $this->getShopControllerMapProvider();

        $moduleFormbuilderMap = $moduleFormbuilderMapProvider->getFormbuilderMap();
        $shopControllerMap = $shopControllerMapProvider->getControllerMap();

        $existingMaps = array_merge($moduleFormbuilderMap, $shopControllerMap);
        return;
        /**
         * Ensure, that formbuilder keys are unique.
         * As keys are always stored in lower case, we must test against lower case keys here as well
         */
        $duplicatedKeys = array_intersect_key(array_change_key_case($moduleForms, CASE_LOWER), $existingMaps);
        if (!empty($duplicatedKeys)) {
            throw new \OxidEsales\Eshop\Core\Exception\ModuleValidationException(implode(',', $duplicatedKeys));
        }

        /**
         * Ensure, that formbuilder values are unique.
         */
        $duplicatedValues = array_intersect($moduleForms, $existingMaps);
        if (!empty($duplicatedValues)) {
            throw new \OxidEsales\Eshop\Core\Exception\ModuleValidationException(implode(',', $duplicatedValues));
        }
    }

    /**
     * @return \Ci\Oxid\FormBuilder\Core\Contract\FormbuilderMapProviderInterface
     */
    protected function getModuleFormbuilderMapProvider()
    {
        return oxNew(\Ci\Oxid\FormBuilder\Core\Routing\ModuleFormbuilderMapProvider::class);
    }

    /**
     * @return object
     */
    protected function getFormbuilderProviderStorage()
    {
        $classProviderStorage = oxNew(FormbuilderClassProviderStorage::class);

        return $classProviderStorage;
    }

}

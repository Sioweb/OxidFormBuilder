<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace Ci\Oxid\FormBuilder\Core\Routing\Module;

use OxidEsales\Eshop\Core\Contract\ClassProviderStorageInterface;
use OxidEsales\Eshop\Core\Registry;

class FormbuilderClassProviderStorage implements ClassProviderStorageInterface
{
    /**
     * @var string The key under which the value will be stored.
     */
    const STORAGE_KEY = 'aModuleFormbuilder';

    /**
     * Get the stored formbuilder value from the oxconfig.
     *
     * @return null|array The formbuilders field of the modules metadata.
     */
    public function get()
    {
        return (array) $this->getConfig()->getShopConfVar(self::STORAGE_KEY);
    }

    /**
     * Set the stored formbuilder value from the oxconfig.
     *
     * @param array $value The formbuilders field of the modules metadata.
     */
    public function set($value)
    {
        $value = $this->toLowercase($value);
        $this->getConfig()->saveShopConfVar('aarr', self::STORAGE_KEY, $value);
    }

    /**
     * Add the formbuilders for the module, given by its ID, to the storage.
     *
     * @param string $moduleId    The ID of the module formbuilders to add.
     * @param array  $formbuilders The formbuilders to add to the storage.
     */
    public function add($moduleId, $formbuilders)
    {
        $formbuilderMap = $this->get();
        $formbuilderMap[$moduleId] = $formbuilders;

        $this->set($formbuilderMap);
    }

    /**
     * Delete the formbuilders for the module, given by its ID, from the storage.
     *
     * @param string $moduleId The ID of the module, for which we want to delete the formbuilders from the storage.
     */
    public function remove($moduleId)
    {
        $formbuilderMap = $this->get();
        unset($formbuilderMap[strtolower($moduleId)]);

        $this->set($formbuilderMap);
    }

    /**
     * Change the module IDs and the formbuilder keys to lower case.
     *
     * @param array $modulesFormbuilders The formbuilder arrays of several modules.
     *
     * @return array The given formbuilder arrays of several modules, with the module IDs and the formbuilder keys in lower case.
     */
    private function toLowercase($modulesFormbuilders)
    {
        $result = [];

        if (!is_null($modulesFormbuilders)) {
            foreach ($modulesFormbuilders as $moduleId => $formbuilders) {
                $result[strtolower($moduleId)] = $this->formbuilderKeysToLowercase($formbuilders);
            }
        }

        return $result;
    }

    /**
     * Change the formbuilder keys to lower case.
     *
     * @param array $formbuilders The formbuilders array of one module.
     *
     * @return array The given formbuilders array with the formbuilder keys in lower case.
     */
    private function formbuilderKeysToLowercase($formbuilders)
    {
        $result = [];

        foreach ($formbuilders as $formbuilderKey => $formbuilderClass) {
            $result[strtolower($formbuilderKey)] = $formbuilderClass;
        }

        return $result;
    }

    /**
     * Get the config object.
     *
     * @return \oxConfig The config object.
     */
    private function getConfig()
    {
        return Registry::getConfig();
    }
}

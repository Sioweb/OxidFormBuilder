<?php

namespace Ci\Oxid\FormBuilder\Core\Routing;

use Ci\Oxid\FormBuilder\Core\Routing\Module\FormbuilderClassProviderStorage;
use Ci\Oxid\FormBuilder\Core\Contract\FormbuilderMapProviderInterface;
use OxidEsales\Eshop\Core\Registry;

/**
 * Provide the formbuilder mappings from the metadata of all active modules.
 *
 * @internal Do not make a module extension for this class.
 * @see      http://wiki.oxidforge.org/Tutorials/Core_OXID_eShop_classes:_must_not_be_extended
 */
class ModuleFormbuilderMapProvider implements FormbuilderMapProviderInterface
{
    /**
     * Get the formbuilder map of the modules.
     *
     * Returns an associative array, where
     *  - the keys are the formbuilder ids
     *  - the values are the routed class names
     *
     * @return array
     */
    public function getFormbuilderMap()
    {
        $formbuilderMap = [];
        $moduleFormbuildersByModuleId = Registry::getUtilsObject()->getModuleVar(FormbuilderClassProviderStorage::STORAGE_KEY);

        if (is_array($moduleFormbuildersByModuleId)) {
            $formbuilderMap = $this->flattenFormbuildersMap($moduleFormbuildersByModuleId);
        }

        return $formbuilderMap;
    }

    /**
     * @param array $moduleFormbuildersByModuleId
     *
     * @return array The input array
     */
    protected function flattenFormbuildersMap(array $moduleFormbuildersByModuleId)
    {
        $moduleFormbuildersFlat = [];
        foreach ($moduleFormbuildersByModuleId as $moduleFormbuildersOfOneModule) {
            $moduleFormbuildersFlat = array_merge($moduleFormbuildersFlat, $moduleFormbuildersOfOneModule);
        }
        return $moduleFormbuildersFlat;
    }
}

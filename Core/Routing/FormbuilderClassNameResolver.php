<?php

namespace Ci\Oxid\FormBuilder\Core\Routing;

use Ci\Oxid\FormBuilder\Core\Routing\ModuleFormbuilderMapProvider;
use OxidEsales\Eshop\Core\Routing\ShopControllerMapProvider;
use OxidEsales\Eshop\Core\Contract\ClassNameResolverInterface;
use OxidEsales\Eshop\Core\Contract\ControllerMapProviderInterface;

/**
 * This class maps controller id to controller class name and vice versa.
 * It looks up map from ShopControllerMapProvider and if no match is found checks ModuleFormbuilderMapProvider.
 *
 * @internal Do not make a module extension for this class.
 * @see      https://oxidforge.org/en/core-oxid-eshop-classes-must-not-be-extended.html
 */
class FormbuilderClassNameResolver implements ClassNameResolverInterface
{
    /**
     * @var ModuleFormbuilderMapProvider
     */
    private $moduleFormbuilderMapProvider = null;

    /**
     * @var ShopControllerMapProvider
     */
    private $shopControllerMapProvider = null;

    /**
     * @param ShopControllerMapProvider   $shopControllerMapProvider   Shop map.
     * @param ModuleFormbuilderMapProvider $moduleFormbuilderMapProvider Module map.
     */
    public function __construct(ControllerMapProviderInterface $shopControllerMapProvider = null, ControllerMapProviderInterface $moduleFormbuilderMapProvider = null)
    {
        $this->shopControllerMapProvider = $shopControllerMapProvider;
        $this->moduleFormbuilderMapProvider = $moduleFormbuilderMapProvider;
    }

    /**
     * Map argument classId to related className.
     *
     * @param string $classId
     *
     * @return string|null
     */
    public function getClassNameById($classId)
    {
        $className = $this->getClassNameFromShopMap($classId);

        if (empty($className)) {
            $className = $this->getClassNameFromModuleMap($classId);
        }
        return $className;
    }

    /**
     * Map argument className to related classId.
     *
     * @param string $className
     *
     * @return string|null
     */
    public function getIdByClassName($className)
    {
        $classId = $this->getClassIdFromShopMap($className);

        if (empty($classId)) {
            $classId = $this->getClassIdFromModuleMap($className);
        }
        return $classId;
    }

    /**
     * Get class name from shop controller provider.
     *
     * @param string $classId
     *
     * @return string|null
     */
    protected function getClassNameFromShopMap($classId)
    {
        $shopControllerMapProvider = $this->getShopControllerMapProvider();
        $idToNameMap = $shopControllerMapProvider->getControllerMap();
        $className = $this->arrayLookup($classId, $idToNameMap);

        return $className;
    }

    /**
     * Get class name from module controller provider.
     *
     *  @param string $classId
     *
     * @return string|null
     */
    protected function getClassNameFromModuleMap($classId)
    {
        $moduleFormbuilderMapProvider = $this->getModuleFormbuilderMapProvider();
        $idToNameMap = $moduleFormbuilderMapProvider->getFormbuilderMap();
        $className = $this->arrayLookup($classId, $idToNameMap);

        return $className;
    }

    /**
     * Get class id from shop controller provider.
     *
     * @param string $className
     *
     * @return string|null
     */
    protected function getClassIdFromShopMap($className)
    {
        $shopControllerMapProvider = $this->getShopControllerMapProvider();
        $idToNameMap = $shopControllerMapProvider->getControllerMap();
        $classId = $this->arrayLookup($className, array_flip($idToNameMap));

        return $classId;
    }

    /**
     * Get class id from module controller provider.
     *
     * @param string $className
     *
     * @return string|null
     */
    protected function getClassIdFromModuleMap($className)
    {
        $moduleFormbuilderMapProvider = $this->getModuleFormbuilderMapProvider();
        $idToNameMap = $moduleFormbuilderMapProvider->getFormbuilderMap();
        $classId = $this->arrayLookup($className, array_flip($idToNameMap));

        return $classId;
    }

    /**
     * @param string $key
     * @param array  $keys2Values
     *
     * @return string|null
     */
    protected function arrayLookup($key, $keys2Values)
    {
        $keys2Values = array_change_key_case($keys2Values);
        $key = strtolower($key);
        $match = array_key_exists($key, $keys2Values) ? $keys2Values[$key] : null;

        return $match;
    }

    /**
     * Getter for ShopControllerMapProvider object
     *
     * @return ShopControllerMapProvider
     */
    protected function getShopControllerMapProvider()
    {
        if (is_null($this->shopControllerMapProvider)) {
            $this->shopControllerMapProvider = oxNew(ShopControllerMapProvider::class);
        }

        return $this->shopControllerMapProvider;
    }

    /**
     * Getter for ModuleFormbuilderMapProvider object
     *
     * @return ModuleFormbuilderMapProvider
     */
    protected function getModuleFormbuilderMapProvider()
    {
        if (is_null($this->moduleFormbuilderMapProvider)) {
            $this->moduleFormbuilderMapProvider = oxNew(ModuleFormbuilderMapProvider::class);
        }

        return $this->moduleFormbuilderMapProvider;
    }
}

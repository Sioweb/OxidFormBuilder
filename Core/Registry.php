<?php

namespace Ci\Oxid\FormBuilder\Core;

use Ci\Oxid\FormBuilder\Core\Routing\FormbuilderClassNameResolver;

/**
 * Object registry design pattern implementation. Stores the instances of objects
 */
class Registry extends Registry_parent
{

    /**
     * Return an instance of FormbuilderClassNameResolver
     *
     * @static
     *
     * @return FormbuilderClassNameResolver
     */
    public static function getFormbuilderClassNameResolver()
    {
        return self::getObject(FormbuilderClassNameResolver::class);
    }
}

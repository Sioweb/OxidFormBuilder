<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

namespace Ci\Oxid\FormBuilder\Core\Module;

class Module extends Module_parent
{
    /**
     * Returns associative array of module formbuilder classes.
     *
     * @return array
     */
    public function getFormbuilder()
    {
        if (isset($this->_aModule['formbuilder']) && ! is_array($this->_aModule['formbuilder'])) {
            throw new \InvalidArgumentException('Value for metadata key "formbuilder" must be an array');
        }

        return isset($this->_aModule['formbuilder']) ? array_change_key_case($this->_aModule['formbuilder']) : [];
    }
}
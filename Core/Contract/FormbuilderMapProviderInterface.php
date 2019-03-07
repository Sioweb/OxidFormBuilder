<?php

namespace Ci\Oxid\FormBuilder\Core\Contract;

/**
 * The implementation of this class determines the formbuilders which should be allowed to be called directly via
 * HTTP GET/POST Parameters, inside form actions or with oxid_include_widget.
 * Those formbuilders are specified e.g. inside a form action with a formbuilder key which is mapped to its class.
 *
 */
interface FormbuilderMapProviderInterface
{
    /**
     * Get all formbuilder keys and their assigned classes
     *
     * @return array
     */
    public function getFormbuilderMap();
}

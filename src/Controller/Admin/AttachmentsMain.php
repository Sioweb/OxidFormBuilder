<?php

/*
 * This file is part of FormBuilder for oxid.
 *
 * (c) Seipp Wohnen GmbH
 */

namespace Ci\Oxid\FormBuilder\Controller\Admin;

class AttachmentsMain extends AttachmentsMain_parent
{
    protected function loadEmailTypes()
    {
        return array_merge(parent::loadEmailTypes(), [
            'sendFormbuilder2Customer'
        ]);
    }
}

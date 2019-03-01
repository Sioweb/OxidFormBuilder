<?php

namespace Ci\Oxid\FormBuilder\Controller\Admin\Form;

use OxidEsales\Eshop\Application\Controller\Admin\AdminListController;

class Forms extends AdminListController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'ci_admin_forms.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = \Ci\Oxid\FormBuilder\Model\Form::class;

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = 'oxtitle';
}
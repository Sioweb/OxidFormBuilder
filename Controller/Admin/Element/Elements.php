<?php

namespace Ci\Oxid\FormBuilder\Controller\Admin\Element;

use OxidEsales\Eshop\Application\Controller\Admin\AdminListController;

class Elements extends AdminListController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'ci_admin_elements.tpl';

    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = \Ci\Oxid\FormBuilder\Model\FormElements::class;

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = 'oxtitle';
}
<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace Ci\Oxid\FormBuilder\Controller\Admin;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use Ci\Oxid\FormBuilder\Core\Routing\FormbuilderClassNameResolver;
use Ci\Oxid\FormBuilder\Core\Routing\Module\FormbuilderClassProviderStorage;
use OxidEsales\Eshop\Core\Registry;

/**
 * Admin article main deliveryset manager.
 * There is possibility to change deliveryset name, article, user
 * and etc.
 * Admin Menu: Shop settings -> Shipping & Handling -> Main Sets.
 */
class ModuleMain extends ModuleMain_parent
{

    /**
     * Activate module
     *
     * @return null
     */
    public function activateModule()
    {
        parent::activateModule();

        $ProviderStorage = new FormbuilderClassProviderStorage;
        $FormbuilderClasses = $ProviderStorage->get();
        foreach ($FormbuilderClasses as $ModuleId => $Classes) {
            foreach ($Classes as $FormbuilderKey => $FormbuilderClass) {
                $ClassName = $this->resolveFormbuilderClass($FormbuilderKey);
                $ClassName = oxNew($ClassName);
                $this->updateDatabase($ClassName->loadData()['form']);
            }
        }
    }

    protected function updateDatabase($FormConfig)
    {
        if(empty($FormConfig['fields'])) {
            return;
        }
        $FieldConfig = $FormConfig['fields'];
        $FormConfig = array_diff_key($FormConfig, array_flip(['fields', 'palettes', 'subpalettes']));

        if(empty($FormConfig['table'])) {
            return;
        }

        $SQL = [
            "varchar(255) NOT NULL default ''",
            "text NULL",
            "int(11) NOT NULL default '1'",
            "tinyint(1) NOT NULL default '0'",
            "datetime NOT NULL default '0000-00-00 00:00:00'",
            "timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP"
        ];

        $Database = DatabaseProvider::getDb();
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        foreach ($FieldConfig as $columnName => $Field) {
            if(!empty($Field['sql'])) {
                $ColumnDefinition = $Field['sql'];
            } else {
                $ColumnDefinition = "varchar(255) NOT NULL default ''";
            }
            $columnName = strtoupper($columnName);
            if (!$dbMetaDataHandler->fieldExists($columnName, $FormConfig['table'])) {
                $Database->execute(
                    "ALTER TABLE `{$FormConfig['table']}` ADD `{$columnName}` {$ColumnDefinition};"
                );
            }
        }
    }

    /**
     * Returns class id of controller which should be loaded.
     * When in doubt returns default start controller class.
     *
     * @param string $formbuilderKey Controller id
     *
     * @throws RoutingException
     * @return string
     */
    protected function resolveFormbuilderClass($formbuilderKey)
    {
        $resolvedClass = Registry::get(FormbuilderClassNameResolver::class)->getClassNameById($formbuilderKey);

        // If unmatched controller id is requested throw exception
        if (!$resolvedClass) {
            throw new \OxidEsales\Eshop\Core\Exception\RoutingException($formbuilderKey);
        }

        return $resolvedClass;
    }
}

<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace Ci\Oxid\FormBuilder\Controller\Admin;

use Ci\Oxid\FormBuilder\Core\Routing\FormbuilderClassNameResolver;
use Ci\Oxid\FormBuilder\Core\Routing\Module\FormbuilderClassProviderStorage;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
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
        if (empty($FormConfig['fields'])) {
            return;
        }
        $FieldConfig = $FormConfig['fields'];
        $FormConfig = array_diff_key($FormConfig, array_flip(['fields', 'palettes', 'subpalettes']));

        if (empty($FormConfig['table'])) {
            return;
        }

        $SQL = [
            "varchar(255) NOT NULL default ''",
            "text NULL",
            "int(11) NOT NULL default '1'",
            "tinyint(1) NOT NULL default '0'",
            "datetime NOT NULL default '0000-00-00 00:00:00'",
            "timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP",
        ];

        $Database = DatabaseProvider::getDb();

        $Database->execute($this->createTable($FormConfig));
        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        foreach ($FieldConfig as $columnName => $Field) {

            if (!empty($Field['static'])) {
                continue;
            }
            if (!empty($Field['sql'])) {
                $ColumnDefinition = $Field['sql'];
            } else {
                $ColumnDefinition = "varchar(255) NOT NULL default ''";
            }

            $columnName = strtoupper($columnName);
            if (!empty($Field['ignoreInvalidColumnNames']) && !preg_match('|^[a-zA-Z_][a-zA-Z0-9_]*$|', $columnName)) {
                throw new \Exception('Column name ' . $columnName . ' is not valid!');
            }
            if (!$dbMetaDataHandler->fieldExists($columnName, $FormConfig['table'])) {
                $Database->execute(
                    "ALTER TABLE `{$FormConfig['table']}` ADD `{$columnName}` {$ColumnDefinition};"
                );
            }
        }
    }

    protected function createTable($FormConfig)
    {
        return "CREATE TABLE IF NOT EXISTS `{$FormConfig['table']}` (
            `OXID` char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Element id',
            `OXSHOPID` int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)',
            `OXSORT` int( 5 ) NOT NULL DEFAULT '0' COMMENT 'Sorting',
            `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
            PRIMARY KEY  (`OXID`)
        ) ENGINE=InnoDB;";
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

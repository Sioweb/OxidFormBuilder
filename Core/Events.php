<?php

namespace Ci\Oxid\FormBuilder\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;

class Events
{

    public static function onActivate()
    {
        $Database = DatabaseProvider::getDb();
        $Database->execute("
            CREATE TABLE IF NOT EXISTS `ci_form` (
                `OXID` char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Form id',
                `OXSHOPID` int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)',
                `OXTITLE` varchar(255) NOT NULL default '',
                `OXALIAS` varchar(255) NOT NULL default '',
                `OXHTMLTEMPLATE` varchar(255) NOT NULL default '',
                `OXFIELDCONFIG` text NULL,
                `OXACTION` varchar(255) NOT NULL default '',
                `OXCSSCLASS` varchar(255) NULL default '' COMMENT 'Frontend CSS Class',
                `OXACTIVE` tinyint(1) NOT NULL default '1' COMMENT 'Active',
                `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Active from specified date',
                `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Active to specified date',
                `OXSORT` int( 5 ) NOT NULL DEFAULT '0' COMMENT 'Sorting',
                `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
                `OXSENDFORM` varchar(255) NOT NULL default '',
                `OXRECEIVER` varchar(255) NOT NULL default '',
                `OXSUBJECT` varchar(255) NOT NULL default '',
                `OXCONTENT` text NULL,
                PRIMARY KEY  (`OXID`),
                index(`OXSORT`)
            ) ENGINE=InnoDB;
        ");

        $Database->execute("
            CREATE TABLE IF NOT EXISTS `ci_form_element` (
                `OXID` char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Element id',
                `OXSHOPID` int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)',
                `OXTYPE` varchar(255) NOT NULL default '',
                `OXTITLE` varchar(255) NOT NULL default '',
                `OXLABEL` varchar(255) NULL default '',
                `OXVALUE` varchar(255) NULL default '',
                `OXREQUIRE` varchar(255) NULL default '',
                `OXVALIDATION` varchar(255) NULL default '',
                `OXPLACEHOLDER` varchar(255) NULL default '',
                `OXCSSCLASS` varchar(255) NULL default '' COMMENT 'Frontend CSS Class',
                `OXACTIVE` tinyint(1) NOT NULL default '1' COMMENT 'Active',
                `OXACTIVEFROM` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Active from specified date',
                `OXACTIVETO` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Active to specified date',
                `OXSORT` int( 5 ) NOT NULL DEFAULT '0' COMMENT 'Sorting',
                `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
                PRIMARY KEY  (`OXID`),
                index(`OXSORT`)
            ) ENGINE=InnoDB;
        ");

        $Database->execute("
            CREATE TABLE IF NOT EXISTS `ci_form_element2form` (
                `OXID` char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Element id',
                `OXELEMENTID` char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Element id',
                `OXFORMID` char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Element id',
                `OXTIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp',
                PRIMARY KEY  (`OXID`)
            ) ENGINE=InnoDB;
        ");

        $tableFields = [
            ['ci_form', 'OXALIAS', "varchar(255) NOT NULL default ''"],
            ['ci_form', 'OXHTMLTEMPLATE', "varchar(255) NOT NULL default ''"],
            ['ci_form', 'OXSENDFORM', "varchar(255) NOT NULL default ''"],
            ['ci_form', 'OXRECEIVER', "varchar(255) NOT NULL default ''"],
            ['ci_form', 'OXSUBJECT', "varchar(255) NOT NULL default ''"],
            ['ci_form', 'OXCONTENT', "text NULL"],
            ['ci_form', 'OXFIELDCONFIG', "text NULL"],

            ['ci_form_element', 'OXID', "char(32) character set utf8 collate utf8_general_ci NOT NULL COMMENT 'Element id'"],
            ['ci_form_element', 'OXSHOPID', "int(11) NOT NULL default '1' COMMENT 'Shop id (oxshops)'"],

            ['ci_form_element', 'OXTYPE', "varchar(255) NOT NULL default ''"],
            ['ci_form_element', 'OXTITLE', "varchar(255) NOT NULL default ''"],
            ['ci_form_element', 'OXLABEL', "varchar(255) NULL default ''"],
            ['ci_form_element', 'OXVALUE', "varchar(255) NULL default ''"],
            ['ci_form_element', 'OXREQUIRE', "varchar(255) NULL default ''"],
            ['ci_form_element', 'OXVALIDATION', "varchar(255) NULL default ''"],
            ['ci_form_element', 'OXPLACEHOLDER', "varchar(255) NULL default ''"],

            ['ci_form_element', 'OXCSSCLASS', "varchar(255) NULL default '' COMMENT 'Frontend CSS Class'"],
            ['ci_form_element', 'OXACTIVE', "tinyint(1) NOT NULL default '1' COMMENT 'Active'"],
            ['ci_form_element', 'OXACTIVEFROM', "datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Active from specified date'"],
            ['ci_form_element', 'OXACTIVETO', "datetime NOT NULL default '0000-00-00 00:00:00' COMMENT 'Active to specified date'"],
            ['ci_form_element', 'OXSORT', "int( 5 ) NOT NULL DEFAULT '0' COMMENT 'Sorting'"],
            ['ci_form_element', 'OXTIMESTAMP', "timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP COMMENT 'Timestamp'"],
        ];

        $dbMetaDataHandler = oxNew(DbMetaDataHandler::class);
        foreach ($tableFields as $fieldData) {
            if (!$dbMetaDataHandler->fieldExists($fieldData[1], $fieldData[0])) {
                $Database->execute(
                    "ALTER TABLE `{$fieldData[0]}` ADD `{$fieldData[1]}` {$fieldData[2]};"
                );
            }
        }
    }

    public static function onDeactivate() {}
}

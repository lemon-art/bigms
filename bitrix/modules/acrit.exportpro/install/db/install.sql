
create table if not exists `acrit_exportpro_profile`(
	`ID` int(11) NOT NULL auto_increment,
	`ACTIVE` CHAR(1) DEFAULT 'Y' NOT NULL,
	`NAME` VARCHAR(100)	NULL,
	`CODE` VARCHAR(100)	NULL,
    `DESCRIPTION` VARCHAR(255) NULL,
	`SHOPNAME` VARCHAR(100)	NULL,
	`COMPANY` VARCHAR(100) NULL,
	`DOMAIN_NAME` VARCHAR(100) NULL,
	`ENCODING` VARCHAR(100)	NULL,
	`LID` VARCHAR(10) NULL,
	`VIEW_CATALOG` CHAR(1)	DEFAULT 'Y' NOT NULL,
	`GOOGLEFEED` VARCHAR(100) NULL,
	`USE_SKU` CHAR(1) DEFAULT 'Y' NOT NULL,
	`CHECK_INCLUDE`	CHAR(1)	DEFAULT 'N' NOT NULL,
    `EXPORT_PARENT_CATEGORIES` CHAR(1) DEFAULT 'N' NOT NULL,
    `EXPORT_PARENT_CATEGORIES_TO_OFFER` CHAR(1) DEFAULT 'N' NOT NULL,
    `EXPORT_OFFER_CATEGORIES_TO_OFFER` CHAR(1) DEFAULT 'N' NOT NULL,
	`EXPORT_PARENT_CATEGORIES_WITH_IBLOCK_FIELDS` CHAR(1) DEFAULT 'N' NOT NULL,
    `USE_COMPRESS` CHAR(1) DEFAULT 'N' NOT NULL,
	`USE_MARKET_CATEGORY` CHAR(1) DEFAULT 'N' NOT NULL,
	`OTHER`	CHAR(1)	DEFAULT 'N' NOT NULL,
	`TYPE` VARCHAR(50) NULL,
	`CATEGORIES` VARCHAR(255) NULL,
	`SEND_LOG_EMAIL` VARCHAR(100) NULL,
	`TYPE_RUN` VARCHAR(15) NULL,
	`TIMESTAMP_X` timestamp not null,
	`START_LAST_TIME_X` DATETIME NULL,
	`LOG` LONGTEXT NULL,
	`IBLOCK_TYPE_ID` LONGTEXT NULL,
	`IBLOCK_ID` LONGTEXT NULL,
	`CATEGORY` LONGTEXT NULL,
	`FORMAT` LONGTEXT NULL,
    `XMLDATA` LONGTEXT NULL,
	`CONVERT_DATA` LONGTEXT NULL,
	`MARKET_CATEGORY` LONGTEXT NULL,
	`CURRENCY` LONGTEXT NULL,
	`CONDITIONS` LONGTEXT NULL,
	`SETUP` LONGTEXT NULL,
    `OFFER_TEMPLATE_FOOTER` TEXT NULL,
    `OFFER_TEMPLATE` TEXT NULL,
    `DATEFORMAT` VARCHAR(255) NULL,
    `SITE_PROTOCOL` VARCHAR(15) DEFAULT 'HTTP' NOT NULL,
    `NAMESCHEMA` LONGTEXT NULL,
    `CONDITION` LONGTEXT NULL,
    `CATEGORY_TEMPLATE` TEXT NULL,
    `CURRENCY_TEMPLATE` TEXT NULL,
    `USE_VARIANT` CHAR(1) DEFAULT 'N' NOT NULL,
    `VARIANT` LONGTEXT NULL,
    `OZON_APPID` VARCHAR(100) NULL,
    `OZON_APPKEY` VARCHAR(100) NULL,
    `HOTLINE_FIRM_ID` VARCHAR(100) NULL,
    `GOOGLE_GOOGLEFEED` VARCHAR(100) NULL,
    `UNLOADED_OFFERS` int(11) NULL,
    `UNLOADED_OFFERS_CORRECT` int(11) NULL,
    `UNLOADED_OFFERS_ERROR` int(11) NULL,
    PRIMARY KEY (ID)
);

ALTER TABLE `acrit_exportpro_profile` CHANGE COLUMN `NAMESCHEMA` `NAMESCHEMA` LONGTEXT NULL ;
ALTER TABLE `acrit_exportpro_profile` ADD COLUMN `EXPORT_PARENT_CATEGORIES_WITH_IBLOCK_FIELDS` CHAR(1) NOT NULL DEFAULT 'N' AFTER `EXPORT_OFFER_CATEGORIES_TO_OFFER`;
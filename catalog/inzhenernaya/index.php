<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Инженерная сантехника");
$arrFilter = Array(
	">CATALOG_PRICE_1"=>0
);
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"catalog", 
	array(
		"COMPONENT_TEMPLATE" => "catalog",
		"IBLOCK_TYPE" => "1c_catalog",
		"IBLOCK_ID" => "10",
		"HIDE_NOT_AVAILABLE" => "N",
		"TEMPLATE_THEME" => "blue",
		"COMMON_SHOW_CLOSE_POPUP" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"DETAIL_SHOW_MAX_QUANTITY" => "N",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"DETAIL_USE_VOTE_RATING" => "Y",
		"DETAIL_USE_COMMENTS" => "Y",
		"DETAIL_BRAND_USE" => "N",
		"SEF_MODE" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "N",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_SALE_BESTSELLERS" => "N",
		"USE_FILTER" => "Y",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"USE_REVIEW" => "Y",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_COMPARE" => "Y",
		"PRICE_CODE" => array(
			0 => "Интернет",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"CONVERT_CURRENCY" => "N",
		"BASKET_URL" => "/basket/",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"COMMON_ADD_TO_BASKET_ACTION" => "",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "BUY",
			1 => "ADD",
		),
		"SHOW_TOP_ELEMENTS" => "N",
		"TOP_ELEMENT_COUNT" => "9",
		"TOP_LINE_ELEMENT_COUNT" => "3",
		"TOP_ELEMENT_SORT_FIELD" => "sort",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_FIELD2" => "id",
		"TOP_ELEMENT_SORT_ORDER2" => "desc",
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"SECTION_COUNT_ELEMENTS" => "N",
		"SECTION_TOP_DEPTH" => "4",
		"SECTIONS_VIEW_MODE" => "LIST",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"PAGE_ELEMENT_COUNT" => "52",
		"LINE_ELEMENT_COUNT" => "4",
		"ELEMENT_SORT_FIELD" => "CATALOG_QUANTITY",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"LIST_PROPERTY_CODE" => array(
			0 => "ELEKTRICHESKAYA_MOSHCHNOST_NAPRYAZHENIE_VT_V",
			1 => "PRISOEDINITELNYY_RAZMER",
			2 => "MOSHCHNOST_ELEKTRICHESKAYA_KVT",
			3 => "GARANTIYA_LET",
			4 => "CML2_ARTICLE",
			5 => "VES_NETTO_BRUTTO_KG",
			6 => "CML2_MANUFACTURER",
			7 => "SHIRINA_SM",
			8 => "GLUBINA_MM",
			9 => "VYSOTA_SM",
			10 => "MATERIAL",
			11 => "MAKSIMALNAYA_TEMPERATURA_S",
			12 => "NAPOR_M",
			13 => "PROPUSKNAYA_SPOSOBNOST_KVS_M_CHAS",
			14 => "TEMPERATURA_RABOCHEY_SREDY_S",
			15 => "TOLSHCHINA_MM",
			16 => "BREND",
			17 => "DLINA_SM",
			18 => "OBYEM_L",
			19 => "RABOCHEE_DAVLENIE_BAR",
			20 => "GABARITNYE_RAZMERY_MM",
			21 => "VNUTRENNIY_BAK",
			22 => "MOSHCHNOST_TEPLOOBMENNIKA_KVT",
			23 => "VSTROENNYY_TEN_KVT",
			24 => "PODSOEDINENIE_KONTURA_OTOPLENIYA",
			25 => "PODSOEDINENIE_KONTURA_GVS",
			26 => "PROIZVODITELNOST_M_CHAS",
			27 => "POVERKHNOST_NAGREVA_M",
			28 => "PROIZVODITELNOST_GORYACHEY_VODY_PRI_T_25_L_M",
			29 => "TIP_MONTAZHA",
			30 => "KAMERA_SGORANIYA",
			31 => "DIAMETR_DYMOKHODA_MM",
			32 => "PODSOEDINENIE_KONTURA_KHVS",
			33 => "MEZHOSEVOE_RASSTOYANIE_MM",
			34 => "TEPLOOTDACHA_VT",
			35 => "STATUS_NALICHIYA_NA_SKLADE",
			36 => "TSVET",
			37 => "KRANBUKSA",
			38 => "AERATOR",
			39 => "TIP",
			40 => "KARTRIDZH",
			41 => "ARTIKUL",
			42 => "UPRAVLENIE",
			43 => "VYSOTA_IZLIVA_SM",
			44 => "DLINA_IZLIVA_SM",
			45 => "NAZNACHENIE_",
			46 => "STRANA_PROIZVODITEL",
			47 => "MAKSIMALNYY_KOMMUTIRUEMYY_TOK_A",
			48 => "KLASS_ZASHCHITY_IP",
			49 => "TEMPERATURA_OKRUZHAYUSHCHEY_SREDY_S",
			50 => "GRUPPIROVKA_DLYA_SAYTA",
			51 => "KOLLEKTSIYA",
			52 => "OBLAST_PRIMENENIYA",
			53 => "OTVERSTIE_DLYA_MONTAZHA",
			54 => "STILISTIKA_DIZAYNA",
			55 => "OSNASHCHENIE",
			56 => "KLASS_ZASHCHITY",
			57 => "GLUBINA_VSASYVANIYA_M",
			58 => "MOSHCHNOST_VT",
			59 => "PROIVODITENOST_L_CH",
			60 => "MAKSIMALNYY_NAPOR_M",
			61 => "DLINA_KABELYA_M",
			62 => "OTAPLIVAEMAYA_PLOSHCHAD_KV_M",
			63 => "BUKHTA_M",
			64 => "KOMPLEKTATSIYA",
			65 => "MAKSIMALNOE_DAVLENIE_BAR",
			66 => "DY",
			67 => "FURNITURA",
			68 => "VYPUSK_UNITAZA",
			69 => "PROIZVODITELNOST_GORYACHEY_VODY_RI_T_25",
			70 => "PROIZVODITELNOST_GORYACHEY_VODY_PRI_T_35_L_M",
			71 => "DIAMETR_DYMOOTVODA_TRUB_KOAKS_RAZDELNYKH_MM",
			72 => "MAKS_RASKHOD_PRIRODNOGO_SZHIZHENNOGO_GAZA_M_CH_KG_",
			73 => "MAKS_PROIZVODITELNOST_KPD_",
			74 => "EMKOST_L",
			75 => "PODACHA_GAZA",
			76 => "VKHOD_KHOLODNOY_VODY_V_KOTEL",
			77 => "VOZVRAT_IZ_SISTEMY_OTOPLENIYA",
			78 => "TSIRKULYATOR",
			79 => "STEKLO_MM",
			80 => "KONSTRUKTSIYA_DVEREY",
			81 => "SIDENE",
			82 => "ELEKTRONNOE_UPRAVLENIE",
			83 => "GIDROMASSAZH_SPINY_KOL_VO_FORSUNOK",
			84 => "TROPICHESKIY_DUSH",
			85 => "VENTILYATSIYA",
			86 => "ZERKALO",
			87 => "RADIO",
			88 => "ZADNYAYA_STENKA",
			89 => "ISPOLNENIE_STEKOL",
			90 => "PODSVETKA",
			91 => "PROFIL",
			92 => "SMESITEL",
			93 => "DIAMETR_MM",
			94 => "NOVINKA",
			95 => "RASPRODAZHA",
			96 => "LIDER_PRODAZH",
			97 => "DELIVERY",
			98 => "GARANTY",
			99 => "ARMIROVANIE",
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "ELEKTRICHESKAYA_MOSHCHNOST_NAPRYAZHENIE_VT_V",
			1 => "PRISOEDINITELNYY_RAZMER",
			2 => "MOSHCHNOST_ELEKTRICHESKAYA_KVT",
			3 => "GARANTIYA_LET",
			4 => "CML2_ARTICLE",
			5 => "VES_NETTO_BRUTTO_KG",
			6 => "CML2_MANUFACTURER",
			7 => "SHIRINA_SM",
			8 => "GLUBINA_MM",
			9 => "VYSOTA_SM",
			10 => "MATERIAL",
			11 => "MAKSIMALNAYA_TEMPERATURA_S",
			12 => "NAPOR_M",
			13 => "PROPUSKNAYA_SPOSOBNOST_KVS_M_CHAS",
			14 => "TEMPERATURA_RABOCHEY_SREDY_S",
			15 => "TOLSHCHINA_MM",
			16 => "BREND",
			17 => "DLINA_SM",
			18 => "OBYEM_L",
			19 => "RABOCHEE_DAVLENIE_BAR",
			20 => "GABARITNYE_RAZMERY_MM",
			21 => "VNUTRENNIY_BAK",
			22 => "MOSHCHNOST_TEPLOOBMENNIKA_KVT",
			23 => "VSTROENNYY_TEN_KVT",
			24 => "PODSOEDINENIE_KONTURA_OTOPLENIYA",
			25 => "PODSOEDINENIE_KONTURA_GVS",
			26 => "PROIZVODITELNOST_M_CHAS",
			27 => "POVERKHNOST_NAGREVA_M",
			28 => "PROIZVODITELNOST_GORYACHEY_VODY_PRI_T_25_L_M",
			29 => "TIP_MONTAZHA",
			30 => "KAMERA_SGORANIYA",
			31 => "DIAMETR_DYMOKHODA_MM",
			32 => "PODSOEDINENIE_KONTURA_KHVS",
			33 => "MEZHOSEVOE_RASSTOYANIE_MM",
			34 => "TEPLOOTDACHA_VT",
			35 => "STATUS_NALICHIYA_NA_SKLADE",
			36 => "TSVET",
			37 => "KRANBUKSA",
			38 => "AERATOR",
			39 => "TIP",
			40 => "KARTRIDZH",
			41 => "ARTIKUL",
			42 => "UPRAVLENIE",
			43 => "VYSOTA_IZLIVA_SM",
			44 => "DLINA_IZLIVA_SM",
			45 => "NAZNACHENIE_",
			46 => "STRANA_PROIZVODITEL",
			47 => "MAKSIMALNYY_KOMMUTIRUEMYY_TOK_A",
			48 => "KLASS_ZASHCHITY_IP",
			49 => "TEMPERATURA_OKRUZHAYUSHCHEY_SREDY_S",
			50 => "GRUPPIROVKA_DLYA_SAYTA",
			51 => "KOLLEKTSIYA",
			52 => "OBLAST_PRIMENENIYA",
			53 => "OTVERSTIE_DLYA_MONTAZHA",
			54 => "STILISTIKA_DIZAYNA",
			55 => "OSNASHCHENIE",
			56 => "KLASS_ZASHCHITY",
			57 => "GLUBINA_VSASYVANIYA_M",
			58 => "MOSHCHNOST_VT",
			59 => "PROIVODITENOST_L_CH",
			60 => "MAKSIMALNYY_NAPOR_M",
			61 => "DLINA_KABELYA_M",
			62 => "OTAPLIVAEMAYA_PLOSHCHAD_KV_M",
			63 => "BUKHTA_M",
			64 => "KOMPLEKTATSIYA",
			65 => "MAKSIMALNOE_DAVLENIE_BAR",
			66 => "DY",
			67 => "FURNITURA",
			68 => "VYPUSK_UNITAZA",
			69 => "PROIZVODITELNOST_GORYACHEY_VODY_RI_T_25",
			70 => "PROIZVODITELNOST_GORYACHEY_VODY_PRI_T_35_L_M",
			71 => "DIAMETR_DYMOOTVODA_TRUB_KOAKS_RAZDELNYKH_MM",
			72 => "MAKS_RASKHOD_PRIRODNOGO_SZHIZHENNOGO_GAZA_M_CH_KG_",
			73 => "MAKS_PROIZVODITELNOST_KPD_",
			74 => "EMKOST_L",
			75 => "PODACHA_GAZA",
			76 => "VKHOD_KHOLODNOY_VODY_V_KOTEL",
			77 => "VOZVRAT_IZ_SISTEMY_OTOPLENIYA",
			78 => "TSIRKULYATOR",
			79 => "STEKLO_MM",
			80 => "KONSTRUKTSIYA_DVEREY",
			81 => "SIDENE",
			82 => "ELEKTRONNOE_UPRAVLENIE",
			83 => "GIDROMASSAZH_SPINY_KOL_VO_FORSUNOK",
			84 => "TROPICHESKIY_DUSH",
			85 => "VENTILYATSIYA",
			86 => "ZERKALO",
			87 => "RADIO",
			88 => "ZADNYAYA_STENKA",
			89 => "ISPOLNENIE_STEKOL",
			90 => "PODSVETKA",
			91 => "PROFIL",
			92 => "SMESITEL",
			93 => "DIAMETR_MM",
			94 => "NOVINKA",
			95 => "RASPRODAZHA",
			96 => "LIDER_PRODAZH",
			97 => "DELIVERY",
			98 => "GARANTY",
			99 => "ARMIROVANIE",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"SHOW_DEACTIVATED" => "N",
		"DETAIL_DISPLAY_NAME" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => "IMG",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "N",
		"USE_STORE" => "N",
		"USE_BIG_DATA" => "N",
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"PAGER_TEMPLATE" => "bigms",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"LABEL_PROP" => "-",
		"TOP_VIEW_MODE" => "SECTION",
		"DETAIL_VOTE_DISPLAY_AS_RATING" => "rating",
		"DETAIL_BLOG_USE" => "Y",
		"DETAIL_BLOG_URL" => "catalog_comments",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "Y",
		"DETAIL_VK_USE" => "N",
		"DETAIL_FB_USE" => "N",
		"SEF_FOLDER" => "/catalog/inzhenernaya/",
		"FILTER_NAME" => "arrFilter",
		"FILTER_FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "ELEKTRICHESKAYA_MOSHCHNOST_NAPRYAZHENIE_VT_V",
			1 => "PRISOEDINITELNYY_RAZMER",
			2 => "MOSHCHNOST_ELEKTRICHESKAYA_KVT",
			3 => "GARANTIYA_LET",
			4 => "CML2_ARTICLE",
			5 => "VES_NETTO_BRUTTO_KG",
			6 => "CML2_MANUFACTURER",
			7 => "SHIRINA_SM",
			8 => "GLUBINA_MM",
			9 => "VYSOTA_SM",
			10 => "MATERIAL",
			11 => "MAKSIMALNAYA_TEMPERATURA_S",
			12 => "NAPOR_M",
			13 => "PROPUSKNAYA_SPOSOBNOST_KVS_M_CHAS",
			14 => "TEMPERATURA_RABOCHEY_SREDY_S",
			15 => "TOLSHCHINA_MM",
			16 => "BREND",
			17 => "DLINA_SM",
			18 => "OBYEM_L",
			19 => "RABOCHEE_DAVLENIE_BAR",
			20 => "GABARITNYE_RAZMERY_MM",
			21 => "VNUTRENNIY_BAK",
			22 => "MOSHCHNOST_TEPLOOBMENNIKA_KVT",
			23 => "VSTROENNYY_TEN_KVT",
			24 => "PODSOEDINENIE_KONTURA_OTOPLENIYA",
			25 => "PODSOEDINENIE_KONTURA_GVS",
			26 => "PROIZVODITELNOST_M_CHAS",
			27 => "POVERKHNOST_NAGREVA_M",
			28 => "PROIZVODITELNOST_GORYACHEY_VODY_PRI_T_25_L_M",
			29 => "TIP_MONTAZHA",
			30 => "KAMERA_SGORANIYA",
			31 => "DIAMETR_DYMOKHODA_MM",
			32 => "PODSOEDINENIE_KONTURA_KHVS",
			33 => "MEZHOSEVOE_RASSTOYANIE_MM",
			34 => "TEPLOOTDACHA_VT",
			35 => "TSVET",
			36 => "KRANBUKSA",
			37 => "AERATOR",
			38 => "TIP",
			39 => "KARTRIDZH",
			40 => "ARTIKUL",
			41 => "UPRAVLENIE",
			42 => "VYSOTA_IZLIVA_SM",
			43 => "DLINA_IZLIVA_SM",
			44 => "NAZNACHENIE_",
			45 => "STRANA_PROIZVODITEL",
			46 => "MAKSIMALNYY_KOMMUTIRUEMYY_TOK_A",
			47 => "KLASS_ZASHCHITY_IP",
			48 => "TEMPERATURA_OKRUZHAYUSHCHEY_SREDY_S",
			49 => "GRUPPIROVKA_DLYA_SAYTA",
			50 => "KOLLEKTSIYA",
			51 => "OBLAST_PRIMENENIYA",
			52 => "OTVERSTIE_DLYA_MONTAZHA",
			53 => "STILISTIKA_DIZAYNA",
			54 => "OSNASHCHENIE",
			55 => "KLASS_ZASHCHITY",
			56 => "GLUBINA_VSASYVANIYA_M",
			57 => "MOSHCHNOST_VT",
			58 => "PROIVODITENOST_L_CH",
			59 => "MAKSIMALNYY_NAPOR_M",
			60 => "DLINA_KABELYA_M",
			61 => "OTAPLIVAEMAYA_PLOSHCHAD_KV_M",
			62 => "BUKHTA_M",
			63 => "KOMPLEKTATSIYA",
			64 => "MAKSIMALNOE_DAVLENIE_BAR",
			65 => "DY",
			66 => "FURNITURA",
			67 => "VYPUSK_UNITAZA",
			68 => "PROIZVODITELNOST_GORYACHEY_VODY_RI_T_25",
			69 => "PROIZVODITELNOST_GORYACHEY_VODY_PRI_T_35_L_M",
			70 => "DIAMETR_DYMOOTVODA_TRUB_KOAKS_RAZDELNYKH_MM",
			71 => "MAKS_RASKHOD_PRIRODNOGO_SZHIZHENNOGO_GAZA_M_CH_KG_",
			72 => "MAKS_PROIZVODITELNOST_KPD_",
			73 => "EMKOST_L",
			74 => "PODACHA_GAZA",
			75 => "VKHOD_KHOLODNOY_VODY_V_KOTEL",
			76 => "VOZVRAT_IZ_SISTEMY_OTOPLENIYA",
			77 => "TSIRKULYATOR",
			78 => "STEKLO_MM",
			79 => "KONSTRUKTSIYA_DVEREY",
			80 => "SIDENE",
			81 => "ELEKTRONNOE_UPRAVLENIE",
			82 => "GIDROMASSAZH_SPINY_KOL_VO_FORSUNOK",
			83 => "TROPICHESKIY_DUSH",
			84 => "VENTILYATSIYA",
			85 => "ZERKALO",
			86 => "RADIO",
			87 => "ZADNYAYA_STENKA",
			88 => "ISPOLNENIE_STEKOL",
			89 => "PODSVETKA",
			90 => "PROFIL",
			91 => "SMESITEL",
			92 => "DIAMETR_MM",
			93 => "NOVINKA",
			94 => "RASPRODAZHA",
			95 => "LIDER_PRODAZH",
			96 => "DELIVERY",
			97 => "GARANTY",
			98 => "ARMIROVANIE",
		),
		"FILTER_PRICE_CODE" => array(
			0 => "Интернет",
		),
		"MESSAGES_PER_PAGE" => "10",
		"USE_CAPTCHA" => "Y",
		"REVIEW_AJAX_POST" => "Y",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"FORUM_ID" => "1",
		"URL_TEMPLATES_READ" => "",
		"SHOW_LINK_TO_FORUM" => "N",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "ELEKTRICHESKAYA_MOSHCHNOST_NAPRYAZHENIE_VT_V",
			1 => "PRISOEDINITELNYY_RAZMER",
			2 => "MOSHCHNOST_ELEKTRICHESKAYA_KVT",
			3 => "GARANTIYA_LET",
			4 => "CML2_ARTICLE",
			5 => "VES_NETTO_BRUTTO_KG",
			6 => "CML2_MANUFACTURER",
			7 => "SHIRINA_SM",
			8 => "GLUBINA_MM",
			9 => "VYSOTA_SM",
			10 => "MATERIAL",
			11 => "MAKSIMALNAYA_TEMPERATURA_S",
			12 => "NAPOR_M",
			13 => "PROPUSKNAYA_SPOSOBNOST_KVS_M_CHAS",
			14 => "TEMPERATURA_RABOCHEY_SREDY_S",
			15 => "TOLSHCHINA_MM",
			16 => "BREND",
			17 => "DLINA_SM",
			18 => "OBYEM_L",
			19 => "RABOCHEE_DAVLENIE_BAR",
			20 => "GABARITNYE_RAZMERY_MM",
			21 => "VNUTRENNIY_BAK",
			22 => "MOSHCHNOST_TEPLOOBMENNIKA_KVT",
			23 => "VSTROENNYY_TEN_KVT",
			24 => "PODSOEDINENIE_KONTURA_OTOPLENIYA",
			25 => "PODSOEDINENIE_KONTURA_GVS",
			26 => "PROIZVODITELNOST_M_CHAS",
			27 => "POVERKHNOST_NAGREVA_M",
			28 => "PROIZVODITELNOST_GORYACHEY_VODY_PRI_T_25_L_M",
			29 => "TIP_MONTAZHA",
			30 => "KAMERA_SGORANIYA",
			31 => "DIAMETR_DYMOKHODA_MM",
			32 => "PODSOEDINENIE_KONTURA_KHVS",
			33 => "MEZHOSEVOE_RASSTOYANIE_MM",
			34 => "TEPLOOTDACHA_VT",
			35 => "STATUS_NALICHIYA_NA_SKLADE",
			36 => "TSVET",
			37 => "KRANBUKSA",
			38 => "AERATOR",
			39 => "TIP",
			40 => "KARTRIDZH",
			41 => "ARTIKUL",
			42 => "UPRAVLENIE",
			43 => "VYSOTA_IZLIVA_SM",
			44 => "DLINA_IZLIVA_SM",
			45 => "NAZNACHENIE_",
			46 => "STRANA_PROIZVODITEL",
			47 => "MAKSIMALNYY_KOMMUTIRUEMYY_TOK_A",
			48 => "KLASS_ZASHCHITY_IP",
			49 => "TEMPERATURA_OKRUZHAYUSHCHEY_SREDY_S",
			50 => "GRUPPIROVKA_DLYA_SAYTA",
			51 => "KOLLEKTSIYA",
			52 => "OBLAST_PRIMENENIYA",
			53 => "OTVERSTIE_DLYA_MONTAZHA",
			54 => "STILISTIKA_DIZAYNA",
			55 => "OSNASHCHENIE",
			56 => "KLASS_ZASHCHITY",
			57 => "GLUBINA_VSASYVANIYA_M",
			58 => "MOSHCHNOST_VT",
			59 => "PROIVODITENOST_L_CH",
			60 => "MAKSIMALNYY_NAPOR_M",
			61 => "DLINA_KABELYA_M",
			62 => "OTAPLIVAEMAYA_PLOSHCHAD_KV_M",
			63 => "BUKHTA_M",
			64 => "KOMPLEKTATSIYA",
			65 => "MAKSIMALNOE_DAVLENIE_BAR",
			66 => "DY",
			67 => "FURNITURA",
			68 => "VYPUSK_UNITAZA",
			69 => "PROIZVODITELNOST_GORYACHEY_VODY_RI_T_25",
			70 => "PROIZVODITELNOST_GORYACHEY_VODY_PRI_T_35_L_M",
			71 => "DIAMETR_DYMOOTVODA_TRUB_KOAKS_RAZDELNYKH_MM",
			72 => "MAKS_RASKHOD_PRIRODNOGO_SZHIZHENNOGO_GAZA_M_CH_KG_",
			73 => "MAKS_PROIZVODITELNOST_KPD_",
			74 => "EMKOST_L",
			75 => "PODACHA_GAZA",
			76 => "VKHOD_KHOLODNOY_VODY_V_KOTEL",
			77 => "VOZVRAT_IZ_SISTEMY_OTOPLENIYA",
			78 => "TSIRKULYATOR",
			79 => "STEKLO_MM",
			80 => "KONSTRUKTSIYA_DVEREY",
			81 => "SIDENE",
			82 => "ELEKTRONNOE_UPRAVLENIE",
			83 => "GIDROMASSAZH_SPINY_KOL_VO_FORSUNOK",
			84 => "TROPICHESKIY_DUSH",
			85 => "VENTILYATSIYA",
			86 => "ZERKALO",
			87 => "RADIO",
			88 => "ZADNYAYA_STENKA",
			89 => "ISPOLNENIE_STEKOL",
			90 => "PODSVETKA",
			91 => "PROFIL",
			92 => "SMESITEL",
			93 => "DIAMETR_MM",
			94 => "NOVINKA",
			95 => "RASPRODAZHA",
			96 => "LIDER_PRODAZH",
			97 => "GARANTY",
			98 => "ARMIROVANIE",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "CATALOG_PRICE_1",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "Y",
		"ELEMENT_SORT_FIELD_BOX" => "name",
		"ELEMENT_SORT_ORDER_BOX" => "asc",
		"ELEMENT_SORT_FIELD_BOX2" => "id",
		"ELEMENT_SORT_ORDER_BOX2" => "desc",
		"COMPARE_POSITION_FIXED" => "Y",
		"COMPARE_POSITION" => "top left",
		"DETAIL_SHOW_BASIS_PRICE" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"PAGER_Продажные_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"FILE_404" => "",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE#/",
			"element" => "#SECTION_CODE#/#ELEMENT_CODE#/",
			"compare" => "compare/",
			"smart_filter" => "#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/",
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
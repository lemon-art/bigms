<?

if (!$_GET["market_stop"])
{
    define('DESCRIPTION_SIZE', 511);

    if (!CModule::IncludeModule("iblock"))
        die();

    $bCatalog = CModule::IncludeModule('catalog');

    /*     * ***********************************************************************
      Processing of received parameters
     * *********************************************************************** */

    if (!isset($arParams["CACHE_TIME"]))
        $arParams["CACHE_TIME"] = 3600;

    if (!isset($arParams["DO_NOT_INCLUDE_SUBSECTIONS"]))
        $arParams["DO_NOT_INCLUDE_SUBSECTIONS"] = "N";

    if (!is_array($arParams["PROPERTY_CODE"]))
        $arParams["PROPERTY_CODE"] = array();

    if (!$arParams['SKU_PROPERTY'])
        $arParams['SKU_PROPERTY'] = 'PROPERTY_CML2_LINK';

    $arParams['SKU_PROPERTY'] = strtoupper($arParams['SKU_PROPERTY']);

    foreach ($arParams["PROPERTY_CODE"] as $key => $value)
    {
        if ($value === "")
            unset($arParams["PROPERTY_CODE"][$key]);
        else
            $arProperty[] = "PROPERTY_" . trim($value);
    }

    if ($arParams['IBLOCK_AS_CATEGORY'] != 'N')
        $arParams['IBLOCK_AS_CATEGORY'] = 'Y';



    $arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);

    $arParams["COMPANY"] = trim($arParams["COMPANY"]);

    if (!is_array($arParams["IBLOCK_ID_IN"]))
        $arParams["IBLOCK_ID_IN"] = array();
    foreach ($arParams["IBLOCK_ID_IN"] as $k => $v)
        if ($v === "")
            unset($arParams["IBLOCK_ID_IN"][$k]);

    if ((count($arParams["IBLOCK_ID_IN"]) > 0 && $arParams["IBLOCK_ID_IN"][0] === '0'))
        $arParams["IBLOCK_ID_IN"] = '';


    if (!is_array($arParams["IBLOCK_ID_EX"]))
        $arParams["IBLOCK_ID_EX"] = array();
    foreach ($arParams["IBLOCK_ID_EX"] as $k => $v)
        if ($v === "")
            unset($arParams["IBLOCK_ID_EX"][$k]);

    if (strlen($arParams["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
    {
        $arrFilter = array();
    }
    else
    {
        //global $$arParams["FILTER_NAME"];
        //$arrFilter = ${$arParams["FILTER_NAME"]};
        $arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
        if (!is_array($arrFilter))
            $arrFilter = array();
    }
    if ($arParams["SHOW_PRICE_COUNT"] <= 0)
        $arParams["SHOW_PRICE_COUNT"] = 1;



    $arParams["CACHE_FILTER"] = ($arParams["CACHE_FILTER"] == "Y");
    if (!$arParams["CACHE_FILTER"] && count($arrFilter) > 0)
        $arParams["CACHE_TIME"] = 0;


    $arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

    if (empty($arParams["DISCOUNTS"]))
        $arParams["DISCOUNTS"] = "DISCOUNT_CUSTOM";

    if (!function_exists("charset_modifier"))
    {

        function charset_modifier($arg) {
            $ent = html_entity_decode($arg[0], ENT_QUOTES, LANG_CHARSET);

            if ($ent == $arg[0])
                return '';
            return $ent;
        }

    }

    if (!function_exists("xml_creator"))
    {

        function xml_creator($text, $bHSC = true, $bDblQuote = false) {
            $bDblQuote = (true == $bDblQuote ? true : false);

            if ($bHSC)
            {
                $text = htmlspecialcharsBx($text);
                if ($bDblQuote)
                    $text = str_replace('&quot;', '"', $text);
            }
            $text = preg_replace("/[\x1-\x8\xB-\xC\xE-\x1F]/", "", $text);
            $text = str_replace("'", "&apos;", $text);
            return $text;
        }

    }

    if ($arParams["DISCOUNTS"] == "PRICE_ONLY")
    {

        function webfly_ymarket_GetPrice($product_id, &$arPrices, &$arOffers, $isRoundPrice) {
            $arOffers[$product_id]["PRICE"] = 0;
            foreach ($arPrices as $arProductPrice)
            {
                if ($arProductPrice['PRICE'] && ($arProductPrice['PRICE'] < $arOffers[$product_id]["PRICE"] || !$arOffers[$product_id]["PRICE"]))
                {
                    if ($isRoundPrice == "Y")// Round Price if is Flag in arParams
                    {
                        $arProductPrice['PRICE'] = round($arProductPrice['PRICE']);
                        $arProductPrice['PRICE'] = $arProductPrice['PRICE'] . ".00";
                    }
                    $arOffers[$product_id]["PRICE"] = $arProductPrice['PRICE'];
                    $arOffers[$product_id]["CURRENCY"] = $arProductPrice["CURRENCY"];
                }
            }
        }

    }
    else
    if ($arParams["DISCOUNTS"] == "DISCOUNT_CUSTOM")//uproshchennyj algoritm
    {
        $arUserGroups = $GLOBALS["USER"]->GetUserGroupArray();

        function webfly_ymarket_GetPrice($product_id, &$arPrices, &$arOffers, $isRoundPrice, $isOldPrice) {
            global $arUserGroups;
            $price = 0;
            foreach ($arPrices as &$arProductPrice)
            {
                if ($arProductPrice['PRICE'] && ($arProductPrice['PRICE'] < $price || !$price))
                {
                    $price = $arProductPrice['PRICE'];
                    $arOffers[$product_id]["CURRENCY"] = $arProductPrice["CURRENCY"];
                }

                $arDiscounts = CCatalogDiscount::GetDiscountByProduct($product_id, $arUserGroups, "N", $arProductPrice['CATALOG_GROUP_ID'], SITE_ID);
                foreach ($arDiscounts as &$arDiscount)
                {
                    switch ($arDiscount["VALUE_TYPE"]) {
                        case 'P': $price_buf = $arProductPrice["PRICE"] - $arDiscount["VALUE"] * $arProductPrice["PRICE"] / 100; //v procentah
                            break;
                        case 'F': $price_buf = $arProductPrice["PRICE"] - $arDiscount["VALUE"]; //fiksirovannaya
                            break;
                        default: $price_buf = $arDiscount["VALUE"]; //ustanovit cenu na tovar
                            break;
                    }

                    if ($price_buf && ($price_buf < $price || !$price))
                    {
                        if ($isRoundPrice == "Y")// Round Price if is Flag in arParams
                        {
                            $price = round($price);
                            $price = $price . ".00";
                            $price_buf = round($price_buf);
                            $price_buf = $price_buf . ".00";
                        }
                        if ($isOldPrice == "Y")
                        {
                            $old_price = $price; //new
                        }
                        $price = $price_buf;
                        $arOffers[$product_id]["CURRENCY"] = $arProductPrice["CURRENCY"];
                    }
                }
                $arDiscounts = null;
            }
            if (!empty($old_price) and $isOldPrice == "Y")
            {
                $arOffers[$product_id]["OLD_PRICE"] = $old_price; //Fill Old Price
            }
            $arOffers[$product_id]["PRICE"] = $price; //new
            /* $arOffers[$product_id]["PRICE"] = $price; */
            CCatalogDiscount::ClearDiscountCache(array('PRODUCT' => 'Y'));
        }

    }
    else
    {
        // if($arParams["DISCOUNTS"] == "DISCOUNT_API")
        $GLOBALS["baseCurrency"] = CCurrency::GetBaseCurrency();

        function webfly_ymarket_GetPrice($product_id, &$arPrices, &$arOffers, $isRoundPrice, $isOldPrice) {
            global $baseCurrency;
            $arPrice = CCatalogProduct::GetOptimalPrice($product_id, 1, $GLOBALS["USER"]->GetUserGroupArray(), "N", $arPrices);
            if ($arPrice["CURRENCY"] != $baseCurrency)
            {
                $arPrices[0]["PRICE"] = CCurrencyRates::ConvertCurrency($arPrices[0]["PRICE"], $arPrice["PRICE"]["CURRENCY"], $baseCurrency);
            }
            if ($isRoundPrice == "Y")// Round Price if is Flag in arParams
            {
                $arPrices[0]["PRICE"] = round($arPrices[0]["PRICE"]);
                $arPrices[0]["PRICE"] = $arPrices[0]["PRICE"] . ".00";
                $arPrice["DISCOUNT_PRICE"] = round($arPrice["DISCOUNT_PRICE"]);
                $arPrice["DISCOUNT_PRICE"] = $arPrice["DISCOUNT_PRICE"] . ".00";
            }
            if (($arPrices[0]["PRICE"] > $arPrice["DISCOUNT_PRICE"] and $isOldPrice == "Y"))//new
            {
                $arOffers[$product_id]["OLD_PRICE"] = $arPrices[0]["PRICE"];
            }
            $arOffers[$product_id]["PRICE"] = $arPrice["DISCOUNT_PRICE"];
            $arOffers[$product_id]["CURRENCY"] = $arPrice["PRICE"]["CURRENCY"];
            CCatalogDiscount::ClearDiscountCache(array('PRODUCT' => 'Y'));
        }

    }


    if (!function_exists("webfly_ymarket_GetMinPrice"))
    {

        function webfly_ymarket_GetMinPrice($product_id, $arPriceTypesID) {
            if (CModule::IncludeModule("catalog"))
            {
                $dbProductPrices = CPrice::GetList(array(), array("PRODUCT_ID" => $product_id, "CATALOG_GROUP_ID" => $arPriceTypesID)); // ->Fetch();
                $arPrices = array();
                while ($arProductPrice = $dbProductPrices->Fetch()) {
                    $arPrices[] = $arProductPrice;
                }
                $arPrice = CCatalogProduct::GetOptimalPrice($product_id, 1, $GLOBALS["USER"]->GetUserGroupArray(), "N", $arPrices);
                return $arPrice['DISCOUNT_PRICE'];
            }
            return false;
        }

    }

    if (!function_exists("webfly_ymarket_GetCurrs"))
    {

        function webfly_ymarket_GetCurrs($product_id, $arPriceTypesID) {
            if (CModule::IncludeModule("catalog"))
            {
                $productPrice = CPrice::GetList(array(), array("PRODUCT_ID" => $product_id, "CATALOG_GROUP_ID" => $arPriceTypesID))->Fetch();

                $currency = $productPrice["CURRENCY"];
            }
            return $currency;
        }

    }


    /* AGENT */
    $agentResult = CAgent::GetList(array("ID" => "DESC"), array("MODULE_ID" => "webfly.ymarket", "NAME" => "wfYmarketAgent();"));
    $agentMass = array();
    while ($agentob = $agentResult->GetNext()) {
        $agentMass = $agentob;
    }
    if ($arParams ["AGENT_CHECK"] == "Y")
    {
        if (empty($agentMass))
        {
            $arResult["AGENT_FOLDER"] = $APPLICATION->GetCurDir();
            COption::SetOptionString("webfly.ymarket", "agentFolder", $arResult["AGENT_FOLDER"], false, false);

            /* Add webfly.ymarket's agent */
            CAgent::AddAgent(
                "wfYmarketAgent();", // function name
                "webfly.ymarket", // module's ID
                "N", 86400, // interval
                date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), time() + 10800), // first check - now
                "Y", // agent active
                date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), time() + 10800), // first start - now
                30);
        }
    }
    else
    {
        if (!empty($agentMass))
        {
            CAgent::RemoveAgent(
                "wfYmarketAgent();", "webfly.ymarket", false
            );
        }
    }

    /* HTTPS */
    if ($arParams ["HTTPS_CHECK"] == "Y")
        $http = "https";
    else
        $http = "http";

    //DELIVERY OPTIONS SHOP
    if (!empty($arParams["DELIVERY_OPTIONS_SHOP_EX"]))
    {
        $deliveryShop = explode("|", $arParams["DELIVERY_OPTIONS_SHOP_EX"]);
        foreach ($deliveryShop as $dsk => $dval)
        {
            $deliveryShop[$dsk] = explode(",", $dval);
        }
        foreach ($deliveryShop as $deliveryK => $deliveryVal)
        {
            if ($deliveryVal[0] == "")
                $deliveryShop[$deliveryK][0] = "0";
        }
        $arResult["DELIVERY_OPTION_SHOP"] = $deliveryShop;
        unset($deliveryShop);
    }

    //DELIVERY OPTIONS PRODUCT
    if (!empty($arParams["DELIVERY_OPTIONS_EX"]))
    {
        $productDeliv = explode("|", $arParams["DELIVERY_OPTIONS_EX"]);
        foreach ($productDeliv as $pdsk => $pdval)
        {
            $explodeVal = explode(",", $pdval);
            $productDeliv[$pdsk] = $explodeVal;
            if (empty($delivList))
                $delivList = $explodeVal;
            else
                $delivList = array_merge($delivList, $explodeVal);
            unset($explodeVal);
        }
    }

    //NAME_PROP_COMPILE
    if (!empty($arParams["NAME_PROP_COMPILE"]))
    {
        $nameCompile = explode("|", $arParams["NAME_PROP_COMPILE"]);
        foreach ($nameCompile as $nck => $ncval)
        {
            if ($nck == 0)
                $nameSelects = explode(",", $ncval);
            else
                $nameInps = explode(",", $ncval);
        }
    }

    $bDesignMode = is_object($GLOBALS["USER"]) && $GLOBALS["USER"]->IsAdmin();

    $bSaveInFile = $arParams["SAVE_IN_FILE"] == "Y";

    if (!$bDesignMode or $bSaveInFile)
    {
        $arResult["SAVE_IN_FILE"] = $bSaveInFile;

        if (!$bSaveInFile)
        {
            $APPLICATION->RestartBuffer();
            header("Content-Type: text/xml; charset=" . SITE_CHARSET);
            header("Pragma: no-cache");
        }
    }
    else
    {
        echo "<br/><b>" . GetMessage("ADMIN_TEXT") . "</b><br/>";
        return;
    }

    /*     * ***********************************************************************
      Work with cache
     * *********************************************************************** */
    $cache_id = serialize($arrFilter) . serialize($arParams); //.$USER->GetGroups() ;
    $cache_folder = '/y-market';

    if ($arParams["CACHE_NON_MANAGED"] == 'Y')
    {
        $obCache = new CPHPCache;
        $bCache = $obCache->StartDataCache($arParams["CACHE_TIME"], $cache_id, $cache_folder);
    }
    else
    {
        $bCache = $this->StartResultCache(false, $cache_id, $cache_folder);
    }

    if ($bCache)
    {
        $arResult["DATE"] = Date("Y-m-d H:i");
        $arResult["COMPANY"] = strip_tags(html_entity_decode($arParams["COMPANY"]));
        $arResult["SITE"] = $arParams["SITE"];
        $arResult["URL"] = $http . '://' . htmlspecialcharsEx(COption::GetOptionString("main", "server_name", ""));

// list of the element fields that will be used in selection
        $arSelect = array(
          "ID",
          "NAME",
          "IBLOCK_ID",
          "IBLOCK_SECTION_ID",
          "DATE_CREATE",
          "DETAIL_PAGE_URL",
          "DETAIL_TEXT",
          "PREVIEW_TEXT"
        );

        if (!$bCatalog && !empty($arParams["PRICE_CODE"]))
        {
            $arSelect[] = "PROPERTY_" . $arParams["PRICE_CODE"];
        }

        if ($arParams['MORE_PHOTO'])
        {
            $arSelect[] = "DETAIL_PICTURE";
            $arSelect[] = "PREVIEW_PICTURE";
        }

        if (is_array($arProperty))
            $arSelect = array_merge($arProperty, $arSelect);

        $arFilter = array(
          "IBLOCK_LID" => SITE_ID,
          "IBLOCK_ID" => $arParams["IBLOCK_ID_IN"],
          "SECTION_ID" => $arParams["IBLOCK_SECTION"],
          "INCLUDE_SUBSECTIONS" => "Y",
          "IBLOCK_ACTIVE" => "Y",
          "ACTIVE_DATE" => "Y",
          "ACTIVE" => "Y",
          "CHECK_PERMISSIONS" => "Y",
          "SECTION_ACTIVE" => "Y", //New bitrix API can't fetch from IBLOCK root with this filter
          "SECTION_GLOBAL_ACTIVE" => "Y"
        );

        if ($arParams['IBLOCK_AS_CATEGORY'] == 'Y')
        {
            unset($arFilter["SECTION_ACTIVE"]);
            unset($arFilter["SECTION_GLOBAL_ACTIVE"]);
        }

        if ($arParams["DO_NOT_INCLUDE_SUBSECTIONS"] == "Y")
            $arFilter["INCLUDE_SUBSECTIONS"] = "N";

        if ((count($arParams["IBLOCK_SECTION"]) == 1 && $arParams["IBLOCK_SECTION"][0] == 0) ||
            !$arParams["IBLOCK_SECTION"])
        {
            unset($arFilter["SECTION_ID"]);
        }

        $arSort = array(
          "ID" => "DESC",
        );


        $i = 0;

//EXECUTE

        if ($arParams["IBLOCK_TYPE"])
        {
            $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("ID" => $arParams["IBLOCK_ID_IN"], "TYPE" => $arParams["IBLOCK_TYPE"], "ACTIVE" => "Y"));
            $arFilter["IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE"];
        }
        else
        {
            $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("ID" => $arParams["IBLOCK_ID_IN"], "TYPE" => $arParams["IBLOCK_TYPE_LIST"], "ACTIVE" => "Y"));
            $arFilter["IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE_LIST"];
        }

        $arSKUiblockID = array();

        while ($res = $rsIBlock->GetNext()) {
            if ($arParams['IBLOCK_AS_CATEGORY'] == 'Y')
            {
                $arResult["CATEGORIES"][$res["ID"]] = Array("ID" => $res["ID"], "NAME" => xml_creator($res["NAME"], true), "CODE" => $res["CODE"]);
            }

            if ($bCatalog)
            {
                $rsSKU = CCatalog::GetList(array(), array("PRODUCT_IBLOCK_ID" => $res["ID"]), false, false, array("IBLOCK_ID"));
                if ($arSKUiBlock = $rsSKU->Fetch())
                {
                    $arSKUiblockID[$res["ID"]] = $arSKUiBlock["IBLOCK_ID"];
                }
                unset($rsSKU);
            }
        }
        unset($rsIBlock);

//fetch sections into categories list
        if ((count($arParams["IBLOCK_SECTION"]) == 1 && $arParams["IBLOCK_SECTION"][0] == 0))
        {
            $filter = Array("IBLOCK_TYPE" => $arFilter["IBLOCK_TYPE"], "IBLOCK_ID" => $arParams["IBLOCK_ID_IN"], "ACTIVE" => "Y", "IBLOCK_ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y");
            $bSections = false;
        }
        else
        {
            $filter = Array("IBLOCK_TYPE" => $arFilter["IBLOCK_TYPE"], "IBLOCK_ID" => $arParams["IBLOCK_ID_IN"], "ID" => $arParams["IBLOCK_SECTION"], "ACTIVE" => "Y", "IBLOCK_ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y");
            $bSections = true;
        }

        if ($arParams['IBLOCK_AS_CATEGORY'] == 'Y')
        {
            unset($filter['ACTIVE']);
            unset($filter['GLOBAL_ACTIVE']);
        }

        $db_acc = CIBlockSection::GetList(array("left_margin" => "asc"), $filter, false, array("ID", "NAME", "IBLOCK_ID", "CODE", "IBLOCK_SECTION_ID", "LEFT_MARGIN", "RIGHT_MARGIN", "DEPTH_LEVEL"));

        unset($filter["ID"]);
        unset($filter["IBLOCK_ID"]);

        while ($arAcc = $db_acc->Fetch()) {
            $id = $arAcc["IBLOCK_ID"] . $arAcc["ID"];
            if (array_key_exists($id, $arResult["CATEGORIES"]))
                continue;

            $arResult["CATEGORIES"][$id] = Array(
              "ID" => $id,
              "CODE" => $arAcc["CODE"],
              "NAME" => xml_creator($arAcc["NAME"], true),
              "PARENT" => ($arParams['IBLOCK_AS_CATEGORY'] == 'Y') ? $arAcc["IBLOCK_ID"] : NULL
            );

            if ($arParams["DO_NOT_INCLUDE_SUBSECTIONS"] != "Y" && $bSections)
            {
                $subFilter = array(
                  'IBLOCK_ID' => $arAcc['IBLOCK_ID'],
                  '>LEFT_MARGIN' => $arAcc['LEFT_MARGIN'],
                  '<RIGHT_MARGIN' => $arAcc['RIGHT_MARGIN'],
                  '>DEPTH_LEVEL' => $arAcc['DEPTH_LEVEL']
                );

                $db_sub = CIBlockSection::GetList(array("left_margin" => "asc"), array_merge($filter, $subFilter), false, array("ID", "NAME", "IBLOCK_ID", "CODE", "IBLOCK_SECTION_ID"));

                while ($arAcc2 = $db_sub->Fetch()) {
                    $id2 = $arAcc2["IBLOCK_ID"] . $arAcc2["ID"];
                    $arResult["CATEGORIES"][$id2] = Array(
                      "ID" => $id2,
                      "CODE" => $arAcc2["CODE"],
                      "NAME" => xml_creator($arAcc2["NAME"], true),
                      "PARENT" => ($arParams['IBLOCK_AS_CATEGORY'] == 'Y') ? $arAcc2["IBLOCK_ID"] : NULL
                    );
                    if (IntVal($arAcc2["IBLOCK_SECTION_ID"]) < 1)
                        continue;

                    $key2 = $arAcc2["IBLOCK_ID"] . $arAcc2["IBLOCK_SECTION_ID"];
                    if (!array_key_exists($key2, $arResult["CATEGORIES"]))
                        continue;

                    $arResult["CATEGORIES"][$id2]["PARENT"] = $key2;
                }
                unset($db_sub);
            }
            if (IntVal($arAcc["IBLOCK_SECTION_ID"]) < 1)
                continue;

            $key = $arAcc["IBLOCK_ID"] . $arAcc["IBLOCK_SECTION_ID"];
            if (!array_key_exists($key, $arResult["CATEGORIES"]))
                continue;

            $arResult["CATEGORIES"][$id]["PARENT"] = $key;
        }
        unset($arAcc);
        unset($db_acc);

//fetch elements
        $arParams["BIG_CATALOG_PROP"] = trim($arParams["BIG_CATALOG_PROP"]);
        if (!empty($arParams["BIG_CATALOG_PROP"]) and $arParams["SAVE_IN_FILE"] == "Y")
        {
            $wf_limit = $arParams["BIG_CATALOG_PROP"];

            if (empty($_GET["WF_PAGE"]))
            {
                unset($_SESSION["FINISH"]);
                $arResult["WF_NUM"] = 1;
            }
            else
            {
                if ($_SESSION["FINISH"] == "Yes")
                {
                    LocalRedirect($APPLICATION->GetCurDir());
                }
                else
                {
                    $arResult["WF_NUM"] = $_GET["WF_PAGE"];
                }
            }

            $arResult["WF_CURR"] = $wf_limit * $arResult["WF_NUM"];

            $rsElements = CIBlockElement::Getlist($arSort, array_merge($arrFilter, $arFilter), false, array("nPageSize" => $wf_limit, "iNumPage" => $arResult["WF_NUM"]), $arSelect);

            $arResult["WF_FULL"] = $rsElements->SelectedRowsCount();
        }
        else
        {
            $rsElements = CIBlockElement::Getlist($arSort, array_merge($arrFilter, $arFilter), false, false, $arSelect);
        }

        while ($arOffer = $rsElements->GetNext()) {
            $arOfferID[] = $arOffer["ID"];
            $arOffer["SKU"] = array();
            $arOffers[$arOffer["ID"]] = $arOffer;
        }

        unset($rsElements);

//work with module 'catalog'

        if ($bCatalog && $arParams['PRICE_FROM_IBLOCK'] != 'Y')
        {
            if (empty($arSKUiblockID))
            {
                $arAllID = $arOfferID; //ID of SKU and offers without any SKU
            }
            else
            {
//fetch SKU
                $arOfferInOb = CIBlockElement::GetList(array($arParams['SKU_PROPERTY'] => 'DESC'), array("IBLOCK_ID" => $arSKUiblockID, $arParams['SKU_PROPERTY'] => $arOfferID, 'ACTIVE' => 'Y'), false, false, $arSelect);

                $arAllID = array(); //ID of SKU and offers without any SKU
                $productKey = $arParams['SKU_PROPERTY'] . '_VALUE';

                while ($arOfferIn = $arOfferInOb->GetNext()) {
                    $arAllID[] = $arOfferIn["ID"];
                    $productID = $arOfferIn[$productKey];
                    $arOffers[$productID]["SKU"][] = $arOfferIn["ID"];
                    $arOffers[$arOfferIn["ID"]] = $arOfferIn;
                }
                unset($arOfferInOb);

                foreach ($arOfferID as $offerID)
                {
                    if (empty($arOffers[$offerID]["SKU"]))
                        $arAllID[] = $offerID;
                }
            }

//opredelenie dostupnosti tovara po odnomu iz trekh algoritmov (zdes tolko pervye dva)
            if ($arParams["AVAILABLE_ALGORITHM"] == "BITRIX_ALGORITHM" or $arParams["AVAILABLE_ALGORITHM"] == "QUANTITY_ZERO" or empty($arParams["AVAILABLE_ALGORITHM"]))
            {
//process catalog products
                $arProductSelect = array(
                  "ID",
                  "QUANTITY",
                  "QUANTITY_TRACE",
                  "CAN_BUY_ZERO"
                );
                $dbProducts = CCatalogProduct::GetList(array("ID" => "DESC"), array("@ID" => $arAllID), false, false, $arProductSelect);
                while ($tr = $dbProducts->Fetch()) {
                    $arOffers[$tr["ID"]]["AVAIBLE"] = "true";
                    $arOffers[$tr["ID"]]["QUANTITY"] = $tr["QUANTITY"];

                    switch ($arParams["AVAILABLE_ALGORITHM"]) {
                        case "BITRIX_ALGORITHM":default:
                            if ($tr["QUANTITY_TRACE"] == "N")//esli otklyuchen uchet - dostupen
                                continue;
//esli vklyuchen uchet
                            if ($tr["QUANTITY"] > 0)//esli kol-vo 0 - dostupen
                                continue;
                            if ($tr["CAN_BUY_ZERO"] == "Y")//esli mozhno pokupat pri kol-ve 0 - dostupen
                                continue;
                            $arOffers[$tr["ID"]]["AVAIBLE"] = "false";
                            break;
                        case "QUANTITY_ZERO":
                            if ($tr["QUANTITY"] > 0)//esli kol-vo 0 - dostupen
                                continue;
                            $arOffers[$tr["ID"]]["AVAIBLE"] = "false";
                            break;
                    }
                }
                unset($tr);
                unset($dbProducts);
            }

//fetch price types
            $dbPriceTypes = CCatalogGroup::GetList(array("SORT" => "ASC"), array("NAME" => $arParams["PRICE_CODE"], "CAN_BUY" => "Y"));

            while ($arPriceType = $dbPriceTypes->Fetch()) {
                $arPriceTypesID[] = $arPriceType['ID'];
            }
            unset($dbPriceTypes);

//fetch and process product prices
            $arPriceSelect = array('PRODUCT_ID', 'PRICE', 'CURRENCY', 'CATALOG_GROUP_ID');
            $dbProductPrices = CPrice::GetList(array("PRODUCT_ID" => "DESC"), array("@PRODUCT_ID" => $arAllID, "@CATALOG_GROUP_ID" => $arPriceTypesID), false, false, $arPriceSelect);

            $arPrices = array();
            if (count($arPriceTypesID) > 1)
            {
                $arProductPrice = $dbProductPrices->GetNext();
                $product_id = $arProductPrice["PRODUCT_ID"];
                $arPrices[] = $arProductPrice;
                while ($arProductPrice = $dbProductPrices->GetNext()) {
                    if ($arProductPrice["PRODUCT_ID"] != $product_id)
                    {
                        webfly_ymarket_GetPrice($product_id, $arPrices, $arOffers, $arParams["PRICE_ROUND"], $arParams["OLD_PRICE"]);

                        $product_id = $arProductPrice["PRODUCT_ID"];
                        $arPrices = array();
                    }
                    $arPrices[] = $arProductPrice;
                }
                webfly_ymarket_GetPrice($product_id, $arPrices, $arOffers, $arParams["PRICE_ROUND"], $arParams["OLD_PRICE"]);
            }
            else if ($arParams["DISCOUNTS"] == 'PRICE_ONLY')
            {
                while ($arPrice = $dbProductPrices->GetNext()) {
                    $arOffers[$arPrice["PRODUCT_ID"]]["PRICE"] = $arPrice["PRICE"];
                    $arOffers[$arPrice["PRODUCT_ID"]]["CURRENCY"] = $arPrice["CURRENCY"];
                }
            }
            else
            {
                $arAllPricesHolder = array();
                while ($tmpPrice = $dbProductPrices->GetNext()) {
                    $arPrices[0]["PRODUCT_ID"] = $tmpPrice["PRODUCT_ID"];
                    $arPrices[0]["PRICE"] = $tmpPrice["PRICE"];
                    $arPrices[0]["CURRENCY"] = $tmpPrice["CURRENCY"];
                    $arPrices[0]["CATALOG_GROUP_ID"] = $tmpPrice["CATALOG_GROUP_ID"];
                    $arAllPricesHolder[] = $arPrices;
                    unset($tmpPrice);
                }
                unset($arPrices);

                $arr_length = count($arAllPricesHolder);
                for ($i = 0; $i < $arr_length; $i++)
                {
                    webfly_ymarket_GetPrice($arAllPricesHolder[$i][0]["PRODUCT_ID"], $arAllPricesHolder[$i], $arOffers, $arParams["PRICE_ROUND"], $arParams["OLD_PRICE"]);
                }
                unset($arAllPricesHolder);
            }
            unset($dbProductPrices);
            CCatalogDiscount::ClearDiscountCache(array('SECTIONS' => 'Y', 'SECTION_CHAINS' => 'Y'));
        }

        $arResult['OFFER'] = array();
        $arResult['CURRENCIES'] = array();
//$arResult['WF_AMOUNTS']=array();
        //CPA_SHOP     
        if (is_numeric($arParams["CPA_SHOP"]))
            $arResult["CPA_SHOP"] = trim($arParams["CPA_SHOP"]);

        /* OFFER ITERATION */
        foreach ($arOfferID as &$offerID)
        {
            $arOffer = & $arOffers[$offerID];

            /* if ($bCatalog && empty($arOffer["SKU"]) && $arParams['PRICE_FROM_IBLOCK'] != 'Y')
              {
              if (intval($arOffer["PRICE"]) <= 0 && $arParams['PRICE_REQUIRED'] != 'N')
              continue;
              if ($arParams["IBLOCK_ORDER"] != "Y" && $arOffer["AVAIBLE"] == "false")
              continue;
              } */

//setting offer pictures
            if ($arOffer["DETAIL_PICTURE"])
            {
                $db_file = CFile::GetByID($arOffer["DETAIL_PICTURE"]);
                if ($ar_file = $db_file->Fetch())
                    $arOffer["PICTURE"] = $ar_file["~src"] ? $ar_file["~src"] : $http . "://" . $_SERVER["SERVER_NAME"] . "/" . ( COption::GetOptionString("main", "upload_dir", "upload")) . "/" . $ar_file["SUBDIR"] . "/" . implode("/", array_map("rawurlencode", explode("/", $ar_file["FILE_NAME"])));
                unset($ar_file);
                unset($db_file);
            }

            if ($arOffer["PREVIEW_PICTURE"] && !$arOffer["PICTURE"])
            {
                $db_file = CFile::GetByID($arOffer["PREVIEW_PICTURE"]);
                if ($ar_file = $db_file->Fetch())
                    $arOffer["PICTURE"] = $ar_file["~src"] ? $ar_file["~src"] : $http . "://" . $_SERVER["SERVER_NAME"] . "/" . (COption::GetOptionString("main", "upload_dir", "upload")) . "/" . $ar_file["SUBDIR"] . "/" . implode("/", array_map("rawurlencode", explode("/", $ar_file["FILE_NAME"])));
                unset($ar_file);
                unset($db_file);
            }

            /* MORE PHOTO START */
            if (!empty($arParams["MORE_PHOTO"]) && $arParams["MORE_PHOTO"] != "WF_EMPT")
            {
                $ph = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("value_id" => "asc"), Array("CODE" => $arParams["MORE_PHOTO"]));
                $arOffer["MORE_PHOTO"] = array();

                while (($ob = $ph->GetNext()) && count($arOffer["MORE_PHOTO"]) < 10) {
                    $arFile = CFile::GetFileArray($ob["VALUE"]);
                    if (!empty($arFile))
                    {
                        if (strpos($arFile["SRC"], $http) === false)
                        {
                            $pic = $http . "://" . $_SERVER["SERVER_NAME"] . implode("/", array_map("rawurlencode", explode("/", $arFile["SRC"])));
                        }
                        else
                        {
                            $ar = explode($http . "://", $arFile["SRC"]);
                            $pic = $http . "://" . implode("/", array_map("rawurlencode", explode("/", $ar[1])));
                        }
                        $arOffer["MORE_PHOTO"][] = $pic;
                    }
                    unset($ob);
                }
                unset($ph);

                if (!$arOffer["PICTURE"] && is_array($arOffer["MORE_PHOTO"]))
                    $arOffer['PICTURE'] = array_shift($arOffer["MORE_PHOTO"]);
                $arOffer["MORE_PHOTO"] = array_slice($arOffer["MORE_PHOTO"], 0, 9);
            }
            /* MORE PHOTO END */

            /* UTM START */
            if ($arParams ["UTM_CHECK"] == "Y")
            {
//Take utm-source properties
                if (!empty($arParams["UTM_SOURCE"]) and $arParams["UTM_SOURCE"] != "0")
                {
                    $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams["UTM_SOURCE"]))->Fetch();
                    if ($isExistProp)
                    {
                        $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_SOURCE"]))->GetNext();
                        $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                        $arOffer["UTM_SOURCE"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                    }
                    else
                    {
                        $arOffer["UTM_SOURCE"] = xml_creator($arParams["UTM_SOURCE"], true);
                    }
                    if ($arOffer["UTM_SOURCE"] == false)
                        $arOffer["UTM_SOURCE"] = "";
                    unset($arProps);
                    unset($dispProp);
                }

//Take utm-campaign properties
                if ($arParams ["UTM_CAMPAIGN"] == "0" or empty($arParams ["UTM_CAMPAIGN"]))
                {
                    $wf_arGr = CIBlockElement::GetElementGroups($arOffer["ID"]);
                    $wf_ar_group = $wf_arGr->Fetch();
                    $wf_groupid = $wf_ar_group["ID"];
                    $res = CIBlockSection::GetByID($wf_groupid);
                    if ($ar_res = $res->GetNext())
                        $group_code = $ar_res['CODE'];
                    $group_code = xml_creator($group_code, true);
                    $arOffer["UTM_CAMPAIGN"] = $group_code;
                    unset($res);
                    unset($wf_arGr);
                    unset($wf_ar_group);
                    if ($arParams["IBLOCK_AS_CATEGORY"] == 'Y' and empty($group_code))
                    {
                        $arOffer["UTM_CAMPAIGN"] = $arResult["CATEGORIES"][$arOffer["IBLOCK_ID"]]["CODE"];
                    }
                    if ($arOffer["UTM_CAMPAIGN"] == false)
                        $arOffer["UTM_CAMPAIGN"] = "";
                }
                else
                {
                    $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams["UTM_CAMPAIGN"]))->Fetch();
                    if ($isExistProp)
                    {
                        $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_CAMPAIGN"]))->GetNext();
                        $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                        $arOffer["UTM_CAMPAIGN"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                    }
                    else
                    {
                        $arOffer["UTM_CAMPAIGN"] = xml_creator($arParams["UTM_CAMPAIGN"], true);
                    }
                    if ($arOffer["UTM_CAMPAIGN"] == false)
                        $arOffer["UTM_CAMPAIGN"] = "";
                    unset($arProps);
                    unset($dispProp);
                }

//Take utm-medium properties
                if (!empty($arParams["UTM_MEDIUM"]) and $arParams["UTM_MEDIUM"] != "0")
                {
                    $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams["UTM_MEDIUM"]))->Fetch();
                    if ($isExistProp)
                    {
                        $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_MEDIUM"]))->GetNext();
                        $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                        $arOffer["UTM_MEDIUM"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                    }
                    else
                    {
                        $arOffer["UTM_MEDIUM"] = xml_creator($arParams["UTM_MEDIUM"], true);
                    }
                    if ($arOffer["UTM_MEDIUM"] == false)
                        $arOffer["UTM_MEDIUM"] = "";
                    unset($arProps);
                    unset($dispProp);
                }

//Take utm-term properties
                if (!empty($arParams["UTM_TERM"]) and $arParams["UTM_TERM"] != "0")
                {
                    $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams["UTM_TERM"]))->Fetch();
                    if ($isExistProp)
                    {
                        $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_TERM"]))->GetNext();
                        $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                        $arOffer["UTM_TERM"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                    }
                    else
                    {
                        $arOffer["UTM_TERM"] = xml_creator($arParams["UTM_TERM"], true);
                    }
                    if ($arOffer["UTM_TERM"] == false)
                        $arOffer["UTM_TERM"] = "";
                    unset($arProps);
                    unset($dispProp);
                }
                else
                {
                    $arOffer["UTM_TERM"] = $arOffer["CODE"];
                }

//offer URL
                //proverka na nalichie get-aparametrov v iskhodnom urle
                if (substr_count($arOffer["DETAIL_PAGE_URL"], "?") > 0)
                    $symbol = "&amp;";
                else
                    $symbol = "?";
                if (empty($arOffer["UTM_SOURCE"]))
                    $utm_source = "";
                else
                    $utm_source = $symbol . "utm_source=" . strip_tags($arOffer["UTM_SOURCE"]);

                if (empty($arOffer["UTM_CAMPAIGN"]))
                {
                    $utm_campaign = "";
                }
                else
                {
                    if (empty($arOffer["UTM_SOURCE"]))
                        $utm_campaign = $symbol . "utm_campaign=" . strip_tags($arOffer["UTM_CAMPAIGN"]);
                    else
                        $utm_campaign = "&amp;utm_campaign=" . strip_tags($arOffer["UTM_CAMPAIGN"]);
                }

                if (empty($arOffer["UTM_MEDIUM"]))
                {
                    $utm_medium = "";
                }
                else
                {
                    if (empty($arOffer["UTM_CAMPAIGN"]) and empty($arOffer["UTM_SOURCE"]))
                        $utm_medium = $symbol . "utm_medium=" . strip_tags($arOffer["UTM_MEDIUM"]);
                    else
                        $utm_medium = "&amp;utm_medium=" . strip_tags($arOffer["UTM_MEDIUM"]);
                }

                if (empty($arOffer["UTM_TERM"]))
                {
                    $utm_term = "";
                }
                else
                {
                    if (empty($arOffer["UTM_MEDIUM"]) and empty($arOffer["UTM_CAMPAIGN"]) and empty($arOffer["UTM_SOURCE"]))
                        $utm_term = $symbol . "utm_term=" . strip_tags($arOffer["UTM_TERM"]);
                    else
                        $utm_term = "&amp;utm_term=" . strip_tags($arOffer["UTM_TERM"]);
                }

                $arOffer["URL"] = $http . "://" . $_SERVER["SERVER_NAME"] . $arOffer["DETAIL_PAGE_URL"] . $utm_source . $utm_campaign . $utm_medium . $utm_term;
            }
            else
            {
//offer URL
                $arOffer["URL"] = $http . "://" . $_SERVER["SERVER_NAME"] . $arOffer["DETAIL_PAGE_URL"];
            }
            /* UTM END */
// LOCAL_DELIVERY_COST_OFFER, STORE_OFFER, PICKUP_OFFER, typePrefix, PROP_ALGORITHM_VALUE, DESCRIPTION
// PROPDUCT_PROP - dopolnitelnye svojstva tovarov vyvodimye v opisanii
            $propsArray = array("LOCAL_DELIVERY_COST_OFFER", "STORE_OFFER", "STORE_PICKUP", "PREFIX_PROP", "PROPDUCT_PROP", "DELIVERY_OPTIONS_EX", "AGE_CATEGORY", "BID", "CBID", "CPA_OFFERS", "EXPIRY", "WEIGHT", "DIMENSIONS");
//esli vybran algoritm opredeleniya dostupnosti tovara iz svojstva
            if ($arParams["AVAILABLE_ALGORITHM"] == "PROP_ALGORITHM")
                $propsArray[] = "PROP_ALGORITHM_VALUE";
            if (!empty($arParams["DESCRIPTION"]))
                $propsArray[] = "DESCRIPTION";
            foreach ($propsArray as $propKey => $propVal)
            {
                if (!empty($arParams[$propVal]))
                {
// dopolnitelnye svojstva tovarov vyvodimye v opisanii
                    if ($propVal == "PROPDUCT_PROP")
                    {
                        foreach ($arParams[$propVal] as $key => $productProp)
                        {
                            if (!empty($productProp))
                            {
                                $productProp = trim($productProp);
                                $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $productProp))->GetNext();

//nazvanie
                                $arProps["NAME"] = xml_creator($arProps["NAME"], true);

//znachenie
                                $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                                $arProps["VAL"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                                $arProps["VAL"] = strip_tags($arProps["VAL"]);

//nakoplenie v peremennuyu
                                if (!empty($arProps["VAL"]))
                                {
                                    if (empty($arOffer["DOP_PROPS"]))
                                        $arOffer["DOP_PROPS"] = $arProps["NAME"] . ": " . $arProps["VAL"];
                                    else
                                        $arOffer["DOP_PROPS"] = $arOffer["DOP_PROPS"] . ", " . $arProps["NAME"] . ": " . $arProps["VAL"];
                                }
                                unset($arProps);
                            }
                        }
                    }
                    else
                    {
                        if ($propVal == "DELIVERY_OPTIONS_EX")//opcii dostavki
                        {
                            foreach ($productDeliv as $dkey => $dProp)
                            {
                                foreach ($dProp as $dk => $dv)
                                {
                                    if (!empty($dv))
                                    {
                                        $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $dv))->GetNext();
                                        $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                                        $arOffer[$propVal][$dkey][$dk] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                                        $arOffer[$propVal][$dkey][$dk] = strip_tags($arOffer[$propVal][$dkey][$dk]);
                                    }
                                }
                            }
                        }
                        else
                        {
                            if ($propVal == "STORE_OFFER" or $propVal == "STORE_PICKUP")//vozmozhnost vpisyvaniya
                            {
                                $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams[$propVal]))->Fetch();
                            }
                            if (($propVal != "STORE_OFFER" and $propVal != "STORE_PICKUP") or ( ($propVal == "STORE_OFFER" or $propVal == "STORE_PICKUP") and $isExistProp))//vozmozhnost vpisyvaniya
                            {
                                $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams[$propVal]))->GetNext();
                                $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                                if ($propVal == "DESCRIPTION")
                                {
                                    if ($dispProp["~VALUE"])
                                        $arOffer[$propVal] = xml_creator(preg_replace_callback("'&[^;]*;'", "charset_modifier", strip_tags($dispProp["~VALUE"])), true);
                                    else
                                        $arOffer[$propVal] = xml_creator(preg_replace_callback("'&[^;]*;'", "charset_modifier", strip_tags($dispProp["~VALUE"]["TEXT"])), true);
                                }
                                else
                                {
                                    $arOffer[$propVal] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                                    $arOffer[$propVal] = strip_tags($arOffer[$propVal]);
                                }
                            }
                            if (($propVal == "STORE_OFFER" or $propVal == "STORE_PICKUP") and ! $isExistProp)//vozmozhnost vpisyvaniya
                            {
                                $arOffer[$propVal] = xml_creator($arParams[$propVal], true);
                            }

//dostupnost tovarov
                            if ($propVal == "PROP_ALGORITHM_VALUE")
                            {
                                $arOffer["AVAIBLE"] = "false";
                                if ($arOffer[$propVal] == "true" or $arOffer[$propVal] == "Y" or $arOffer[$propVal] == "1")
                                    $arOffer["AVAIBLE"] = "true";
                                if ($arOffer[$propVal] == "false" or $arOffer[$propVal] == "N" or $arOffer[$propVal] == "0" or empty($arOffer[$propVal]))
                                    $arOffer["AVAIBLE"] = "false";
                            }
                        }
                    }
                }
                unset($arProps);
                unset($dispProp);
                unset($isExistProp);
            }
            /* ADDITIONAL PROPS END */
            //NEW_IBLOCK_ORDER
            if ($bCatalog && empty($arOffer["SKU"]) && $arParams['PRICE_FROM_IBLOCK'] != 'Y')
            {
                if (intval($arOffer["PRICE"]) <= 0 && $arParams['PRICE_REQUIRED'] != 'N')
                    continue;
                if ($arParams["IBLOCK_ORDER"] != "Y" && $arOffer["AVAIBLE"] == "false")
                    continue;
            }
//NEW_IBLOCK_ORDER
            if ($arParams["NO_DESCRIPTION"] != "Y")
            {
                if (empty($arParams["DESCRIPTION"]))
                {
                    //setting offer description
                    if ($arOffer["PREVIEW_TEXT"])
                    {
                        $arOffer["PREVIEW_TEXT"] = xml_creator(($arOffer["PREVIEW_TEXT_TYPE"] == "html" ? preg_replace_callback("'&[^;]*;'", "charset_modifier", strip_tags($arOffer["~PREVIEW_TEXT"])) : $arOffer["~PREVIEW_TEXT"]), true);
                    }

                    if ($arOffer["DETAIL_TEXT"])
                    {
                        $arOffer["DETAIL_TEXT"] = xml_creator(($arOffer["DETAIL_TEXT_TYPE"] == "html" ? preg_replace_callback("'&[^;]*;'", "charset_modifier", strip_tags($arOffer["~DETAIL_TEXT"])) : $arOffer["~DETAIL_TEXT"]), true);
                    }

                    if ($arParams["DETAIL_TEXT_PRIORITET"] == "Y")
                    {
                        $arOffer["DESCRIPTION"] = $arOffer["DETAIL_TEXT"] ? $arOffer["DETAIL_TEXT"] : $arOffer["PREVIEW_TEXT"];
                    }
                    else
                    {
                        $arOffer["DESCRIPTION"] = $arOffer["PREVIEW_TEXT"] ? $arOffer["PREVIEW_TEXT"] : $arOffer["DETAIL_TEXT"];
                    }
                }
            }
            else
            {
                $arOffer["DESCRIPTION"] = '';
            }

            $arOffer["CATEGORY"] = $arOffer["IBLOCK_ID"] . $arOffer["IBLOCK_SECTION_ID"];

            if (!array_key_exists($arOffer["CATEGORY"], $arResult["CATEGORIES"]) && $arOffer["IBLOCK_SECTION_ID"])
            {
                $arGr = CIBlockElement::GetElementGroups($arOffer["ID"]);
                while ($ar_group = $arGr->Fetch()) {
                    if (!array_key_exists($arOffer["IBLOCK_ID"] . $ar_group["ID"], $arResult["CATEGORIES"]))
                        continue;
                    $arOffer["CATEGORY"] = $arOffer["IBLOCK_ID"] . $ar_group["ID"];
                    break;
                }
            }

            if ($arParams['SECTION_AS_VENDOR'] == 'Y')
            {
                if (!empty($arOffer['IBLOCK_SECTION_ID']))
                {
                    $arOffer["DEVELOPER"] = $arResult["CATEGORIES"][$arOffer["IBLOCK_ID"] . $arOffer['IBLOCK_SECTION_ID']]["NAME"];
                }
            }

            if ($arParams["MARKET_CATEGORY_CHECK"] == "Y")
            {
                if (!empty($arParams['MARKET_CATEGORY_PROP']))
                {
                    $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["MARKET_CATEGORY_PROP"]))->Fetch();

                    $arOffer["MARKET_CATEGORY"] = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
                    unset($arProps);
                }

                if (!$arOffer["MARKET_CATEGORY"])
                {
                    $arGr = CIBlockElement::GetElementGroups($arOffer["ID"]);
                    $ar_group = $arGr->Fetch();
                    $groupid = $ar_group["ID"];

                    $res = CIBlockSection::GetNavChain(false, $groupid);
                    while ($el = $res->GetNext()) {
                        $arOffer["MARKET_CATEGORY"] .= $el['NAME'];
                        $arOffer["MARKET_CATEGORY"] .= "/";
                    }
                    unset($res);
                    unset($arGr);
                    unset($ar_group);
                    if ($arParams["IBLOCK_AS_CATEGORY"] == 'Y')
                    {
                        $arOffer["MARKET_CATEGORY"] = $arResult["CATEGORIES"][$arOffer["IBLOCK_ID"]]["NAME"]
                            . '/'
                            . $arOffer["MARKET_CATEGORY"];
                    }
                    $arOffer["MARKET_CATEGORY"] = substr($arOffer["MARKET_CATEGORY"], 0, -1);
                }
            }

//setting offer name
            if (empty($arParams["NAME_PROP_COMPILE"]))//esli ne sostavnoe nazvanie
            {
                if (!empty($arParams['NAME_PROP']))
                {
                    $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams['NAME_PROP']))->Fetch();
                    $arOffer["MODEL"] = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
                    unset($arProps);
                }

                if (empty($arOffer["MODEL"]))
                {
                    $arOffer["MODEL"] = $arOffer["~NAME"];
                }
            }
            else//sbor sostavnogo nazvaniya
            {
                foreach ($nameSelects as $selKey => $selVal)
                {
                    switch ($selVal) {
                        case "WF_YM_WRITE":
                            $arOffer["MODEL"] .= $nameInps[$selKey] . " ";
                            break;
                        case "WF_PRODUCT_NAME":
                            $arOffer["MODEL"] .= $arOffer["~NAME"] . " ";
                            break;
                        default:
                            $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $selVal))->GetNext();
                            $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                            $selVal = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                            $selVal = strip_tags($selVal);
                            $arOffer["MODEL"] .= $selVal . " ";
                            unset($arProps);
                            unset($dispProp);
                            break;
                    }
                }
                $arOffer["MODEL"] = trim($arOffer["MODEL"]);
            }
            /* obrezka nazvaniya */
            if (!empty($arParams["NAME_CUT"]))
            {
                $arParams["NAME_CUT"] = trim($arParams["NAME_CUT"]);
                $arOffer["MODEL"] = substr($arOffer["MODEL"], 0, $arParams["NAME_CUT"]);
                $arOffer["MODEL"] = trim($arOffer["MODEL"]);
            }

            $arOffer["MODEL"] = xml_creator($arOffer["MODEL"], true);
            /* obrezka nazvaniya konec */

//work with offer SKU
            $flag = 0;
            foreach ($arOffer["SKU"] as &$arOfferInID)
            {
                $arOfferIn = & $arOffers[$arOfferInID];
                $flag = 1;

//check available status
                /* if ($arParams["IBLOCK_ORDER"] != "Y" && $arOfferIn["AVAIBLE"] == "false")
                  continue;

                  if (intval($arOfferIn["PRICE"]) <= 0)
                  continue; */

                if ($arParams["CURRENCIES_CONVERT"] != "NOT_CONVERT")
                {
                    if ($arParams["DISCOUNTS"] != "DISCOUNT_API")
                    {
                        $newval = CCurrencyRates::ConvertCurrency($arOfferIn["PRICE"], $arOfferIn["CURRENCY"], $arParams["CURRENCIES_CONVERT"]);
                        $arOfferIn["PRICE"] = round($newval, 2);
                    }
                    else
                    {
                        $arOfferIn["PRICE"] = round($arOfferIn["PRICE"], 2);
                    }
                    $arOfferIn["CURRENCY"] = $arParams["CURRENCIES_CONVERT"];
                }
                if ($arParams["PRICE_ROUND"] == "Y")
                {
                    $arOfferIn["PRICE"] = round($arOfferIn["PRICE"]);
                    $arOfferIn["PRICE"] = $arOfferIn["PRICE"] . ".00";
                }

                if (!in_array($arOfferIn["CURRENCY"], $arResult["CURRENCIES"]))
                    $arResult["CURRENCIES"][] = $arOfferIn["CURRENCY"];


                $arOfferIn["CATEGORY"] = $arOffer["CATEGORY"];

                if (empty($arParams["NAME_PROP_COMPILE"]))//nesostavnoe nazvanie
                {
                    $tmpName = $arOffer["MODEL"];

                    switch ($arParams["SKU_NAME"]) {
                        case "PRODUCT_NAME":
                            $arOfferIn["MODEL"] = xml_creator($tmpName, true);
                            break;

                        case "SKU_NAME":
                            $arOfferIn["MODEL"] = xml_creator(empty($arOfferIn["~NAME"]) ? $tmpName : $arOfferIn["~NAME"], true);
                            break;

                        default:
                            if (!empty($arOfferIn["~NAME"]))
                                $tmpName .= " / " . $arOfferIn["~NAME"];
                            $arOfferIn["MODEL"] = xml_creator($tmpName, true);
                            break;
                    }
                }
                else//sostavnoe nazvanie
                {
                    foreach ($nameSelects as $selKey => $selVal)
                    {
                        switch ($selVal) {
                            case "WF_YM_WRITE":
                                $arOfferIn["MODEL"] .= $nameInps[$selKey] . " ";
                                break;
                            case "WF_PRODUCT_NAME":
                                $arOfferIn["MODEL"] .= $arOfferIn["~NAME"] . " ";
                                break;
                            default:
                                $arProps = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $selVal))->GetNext();
                                if ($arProps["VALUE"] == "" or empty($arProps["VALUE"]))
                                {
                                    $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $selVal))->GetNext();
                                    $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                                }
                                else
                                {
                                    $dispProp = CIBlockFormatProperties::GetDisplayValue($arOfferIn, $arProps);
                                }
                                $selVal = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                                $selVal = strip_tags($selVal);
                                $arOfferIn["MODEL"] .= $selVal . " ";
                                unset($arProps);
                                unset($dispProp);
                                break;
                        }
                    }
                    $arOfferIn["MODEL"] = trim($arOfferIn["MODEL"]);
                }

                /* obrezka nazvaniya */
                if (!empty($arParams["NAME_CUT"]))
                {
                    $arOfferIn["MODEL"] = substr($arOfferIn["MODEL"], 0, $arParams["NAME_CUT"]);
                    $arOfferIn["MODEL"] = trim($arOfferIn["MODEL"]);
                }
                $arOfferIn["MODEL"] = xml_creator($arOfferIn["MODEL"], true);
                /* obrezka nazvaniya konec */

                /* UTM START */
                if ($arParams ["UTM_CHECK"] == "Y")
                {
//Take utm-source properties
                    if (!empty($arParams["UTM_SOURCE"]) and $arParams["UTM_SOURCE"] != "0")
                    {
                        $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams["UTM_SOURCE"]))->Fetch();
                        if ($isExistProp)
                        {
                            $arProps = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_SOURCE"]))->GetNext();
//esli u predlozheniya ne zapolneno svojstvo, vybiraem ego u tovara
                            if ($arProps["VALUE"] == "" or empty($arProps["VALUE"]))
                            {
                                $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_SOURCE"]))->GetNext();
                            }
                            $dispProp = CIBlockFormatProperties::GetDisplayValue($arOfferIn, $arProps);
                            $arOfferIn["UTM_SOURCE"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
//esli u predlozheniya ne zapolneno svojstvo, vybiraem ego u tovara end
                        }
                        else
                        {
                            $arOfferIn["UTM_SOURCE"] = xml_creator($arParams["UTM_SOURCE"], true);
                        }
                        if ($arOfferIn["UTM_SOURCE"] == false)
                            $arOfferIn["UTM_SOURCE"] = "";
                        unset($arProps);
                        unset($dispProp);
                    }

//Take utm-campaign properties
                    if ($arParams ["UTM_CAMPAIGN"] == "0" or empty($arParams ["UTM_CAMPAIGN"]))
                    {
                        $wf_arGr = CIBlockElement::GetElementGroups($arOfferIn["ID"]);
                        $wf_ar_group = $wf_arGr->Fetch();
//esli net roditelskoj gruppy predlozheniya - berem roditelya tovara
                        if (!$wf_ar_group)
                        {
                            $wf_arGr = CIBlockElement::GetElementGroups($arOffer["ID"]);
                            $wf_ar_group = $wf_arGr->Fetch();
                        }
                        $wf_groupid = $wf_ar_group["ID"];
                        $res = CIBlockSection::GetByID($wf_groupid);
                        if ($ar_res = $res->GetNext())
                            $group_code = $ar_res['CODE'];
                        $group_code = xml_creator($group_code, true);
                        $arOfferIn["UTM_CAMPAIGN"] = $group_code;
                        unset($res);
                        unset($wf_arGr);
                        unset($wf_ar_group);
                        if ($arParams["IBLOCK_AS_CATEGORY"] == 'Y' and empty($group_code))
                        {
                            $arOfferIn["UTM_CAMPAIGN"] = $arResult["CATEGORIES"][$arOffer["IBLOCK_ID"]]["CODE"];
                        }
                        if ($arOfferIn["UTM_CAMPAIGN"] == false)
                            $arOfferIn["UTM_CAMPAIGN"] = "";
                    }
                    else
                    {
                        $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams["UTM_CAMPAIGN"]))->Fetch();
                        if ($isExistProp)
                        {
                            $arProps = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_CAMPAIGN"]))->GetNext();
//esli u predlozheniya ne zapolneno svojstvo, vybiraem ego u tovara
                            if ($arProps["VALUE"] == "" or empty($arProps["VALUE"]))
                            {
                                $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_SOURCE"]))->GetNext();
                            }
                            $dispProp = CIBlockFormatProperties::GetDisplayValue($arOfferIn, $arProps);
                            $arOfferIn["UTM_CAMPAIGN"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                        }
                        else
                        {
                            $arOfferIn["UTM_CAMPAIGN"] = xml_creator($arParams["UTM_CAMPAIGN"], true);
                        }
                        if ($arOfferIn["UTM_CAMPAIGN"] == false)
                            $arOfferIn["UTM_CAMPAIGN"] = "";
                        unset($arProps);
                        unset($dispProp);
                    }

//Take utm-medium properties
                    if (!empty($arParams["UTM_MEDIUM"]) and $arParams["UTM_MEDIUM"] != "0")
                    {
                        $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams["UTM_MEDIUM"]))->Fetch();
                        if ($isExistProp)
                        {
                            $arProps = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_MEDIUM"]))->GetNext();
//esli u predlozheniya ne zapolneno svojstvo, vybiraem ego u tovara
                            if ($arProps["VALUE"] == "" or empty($arProps["VALUE"]))
                            {
                                $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_SOURCE"]))->GetNext();
                            }
                            $dispProp = CIBlockFormatProperties::GetDisplayValue($arOfferIn, $arProps);
                            $arOfferIn["UTM_MEDIUM"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                        }
                        else
                        {
                            $arOfferIn["UTM_MEDIUM"] = xml_creator($arParams["UTM_MEDIUM"], true);
                        }
                        if ($arOfferIn["UTM_MEDIUM"] == false)
                            $arOfferIn["UTM_MEDIUM"] = "";
                        unset($arProps);
                        unset($dispProp);
                    }

//Take utm-term properties
                    if (!empty($arParams["UTM_TERM"]) and $arParams["UTM_TERM"] != "0")
                    {
                        $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams["UTM_TERM"]))->Fetch();
                        if ($isExistProp)
                        {
                            $arProps = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_TERM"]))->GetNext();
//esli u predlozheniya ne zapolneno svojstvo, vybiraem ego u tovara
                            if ($arProps["VALUE"] == "" or empty($arProps["VALUE"]))
                            {
                                $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["UTM_SOURCE"]))->GetNext();
                            }
                            $dispProp = CIBlockFormatProperties::GetDisplayValue($arOfferIn, $arProps);
                            $arOfferIn["UTM_TERM"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                        }
                        else
                        {
                            $arOfferIn["UTM_TERM"] = xml_creator($arParams["UTM_TERM"], true);
                        }
                        if ($arOfferIn["UTM_TERM"] == false)
                            $arOfferIn["UTM_TERM"] = "";
                        unset($arProps);
                        unset($dispProp);
                    }
                    else
                    {
                        $arOfferIn["UTM_TERM"] = $arOfferIn["CODE"];
                    }

//offer URL
                    if (substr_count($arOfferIn["DETAIL_PAGE_URL"], "?") > 0)
                        $symbol = "&amp;";
                    else
                        $symbol = "?";
                    if (empty($arOfferIn["UTM_SOURCE"]))
                        $utm_source = "";
                    else
                        $utm_source = $symbol . "utm_source=" . strip_tags($arOfferIn["UTM_SOURCE"]);

                    if (empty($arOfferIn["UTM_CAMPAIGN"]))
                    {
                        $utm_campaign = "";
                    }
                    else
                    {
                        if (empty($arOfferIn["UTM_SOURCE"]))
                            $utm_campaign = $symbol . "utm_campaign=" . strip_tags($arOfferIn["UTM_CAMPAIGN"]);
                        else
                            $utm_campaign = "&amp;utm_campaign=" . strip_tags($arOfferIn["UTM_CAMPAIGN"]);
                    }

                    if (empty($arOfferIn["UTM_MEDIUM"]))
                    {
                        $utm_medium = "";
                    }
                    else
                    {
                        if (empty($arOfferIn["UTM_CAMPAIGN"]) and empty($arOfferIn["UTM_SOURCE"]))
                            $utm_medium = $symbol . "utm_medium=" . strip_tags($arOfferIn["UTM_MEDIUM"]);
                        else
                            $utm_medium = "&amp;utm_medium=" . strip_tags($arOfferIn["UTM_MEDIUM"]);
                    }

                    if (empty($arOfferIn["UTM_TERM"]))
                    {
                        $utm_term = "";
                    }
                    else
                    {
                        if (empty($arOfferIn["UTM_MEDIUM"]) and empty($arOfferIn["UTM_CAMPAIGN"]) and empty($arOfferIn["UTM_SOURCE"]))
                            $utm_term = $symbol . "utm_term=" . strip_tags($arOfferIn["UTM_TERM"]);
                        else
                            $utm_term = "&amp;utm_term=" . strip_tags($arOfferIn["UTM_TERM"]);
                    }
                    if (!$arOfferIn["DETAIL_PAGE_URL"])
                    {
                        $arOfferIn["URL"] = $http . "://" . $_SERVER["SERVER_NAME"] . $arOffer["DETAIL_PAGE_URL"] . "#" . $arOfferIn["ID"] . $utm_source . $utm_campaign . $utm_medium . $utm_term;
                    }
                    else
                    {
                        $arOfferIn["URL"] = $http . "://" . $_SERVER["SERVER_NAME"] . $arOfferIn["DETAIL_PAGE_URL"] . $utm_source . $utm_campaign . $utm_medium . $utm_term;
                    }
                }
                else
                {
                    if (!$arOfferIn["DETAIL_PAGE_URL"])
                    {
                        $arOfferIn["URL"] = $arOffer["URL"] . "#" . $arOfferIn["ID"];
                    }
                    else
                    {
                        $arOfferIn["URL"] = $http . "://" . $_SERVER["SERVER_NAME"] . $arOfferIn["DETAIL_PAGE_URL"];
                    }
                }

                /* ADDITIONAL PROPS OFFERS START */
// LOCAL_DELIVERY_COST_OFFER, STORE_OFFER, PICKUP_OFFER, typePrefix
// PROPDUCT_PROP - dopolnitelnye svojstva predlozhenij vyvodimye v opisanii
                $propsArrayOffers = array("LOCAL_DELIVERY_COST_OFFER", "STORE_OFFER", "STORE_PICKUP", "PREFIX_PROP", "OFFER_PROP", "DELIVERY_OPTIONS_EX", "AGE_CATEGORY", "BID", "CBID", "CPA_OFFERS", "EXPIRY", "WEIGHT", "DIMENSIONS");
//esli vybran algoritm opredeleniya dostupnosti tovara iz svojstva
                if ($arParams["AVAILABLE_ALGORITHM"] == "PROP_ALGORITHM")
                    $propsArrayOffers[] = "PROP_ALGORITHM_VALUE";
                if (!empty($arParams["DESCRIPTION"]))
                    $propsArrayOffers[] = "DESCRIPTION";
                foreach ($propsArrayOffers as $propKey => $propVal)
                {
                    if (!empty($arParams[$propVal]))
                    {
// dopolnitelnye svojstva sku, vyvodimye v opisanii
                        if ($propVal == "OFFER_PROP")
                        {
                            foreach ($arParams[$propVal] as $key => $productProp)
                            {
                                if (!empty($productProp))
                                {
                                    $productProp = trim($productProp);
                                    $arProps = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $productProp))->GetNext();
//nazvanie
                                    $arProps["NAME"] = xml_creator($arProps["NAME"], true);

//znachenie
                                    $dispProp = CIBlockFormatProperties::GetDisplayValue($arOfferIn, $arProps);
                                    $arProps["VAL"] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                                    $arProps["VAL"] = strip_tags($arProps["VAL"]);

//nakoplenie v peremennuyu
                                    if (!empty($arProps["VAL"]))
                                    {
                                        if (empty($arOfferIn["DOP_PROPS"]))
                                            $arOfferIn["DOP_PROPS"] = $arProps["NAME"] . ": " . $arProps["VAL"];
                                        else
                                            $arOfferIn["DOP_PROPS"] = $arOfferIn["DOP_PROPS"] . ", " . $arProps["NAME"] . ": " . $arProps["VAL"];
                                    }
                                    unset($arProps);
                                }
                            }
                        }
                        else
                        {
                            if ($propVal == "DELIVERY_OPTIONS_EX")//opcii dostavki
                            {
                                foreach ($productDeliv as $dkey => $dProp)
                                {
                                    foreach ($dProp as $dk => $dv)
                                    {
                                        if (!empty($dv))
                                        {
                                            $arProps = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $dv))->GetNext();
                                            //esli u predlozheniya ne zapolneno svojstvo, vybiraem ego u tovara
                                            if ($arProps["VALUE"] == "" or empty($arProps["VALUE"]))
                                            {
                                                $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $dv))->GetNext();
                                                $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                                            }
                                            else
                                            {
                                                $dispProp = CIBlockFormatProperties::GetDisplayValue($arOfferIn, $arProps);
                                            }
                                            $arOfferIn[$propVal][$dkey][$dk] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                                            $arOfferIn[$propVal][$dkey][$dk] = strip_tags($arOfferIn[$propVal][$dkey][$dk]);
                                        }
                                    }
                                }
                            }
                            else
                            {
                                if ($propVal == "STORE_OFFER" or $propVal == "STORE_PICKUP")//vozmozhnost vpisyvaniya
                                {
                                    $isExistProp = CIBlockProperty::GetList(Array("sort" => "asc", "name" => "asc"), Array("CODE" => $arParams[$propVal]))->Fetch();
                                }
                                if (($propVal != "STORE_OFFER" and $propVal != "STORE_PICKUP") or ( ($propVal == "STORE_OFFER" or $propVal == "STORE_PICKUP") and $isExistProp))//vozmozhnost vpisyvaniya
                                {
                                    $arProps = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $arParams[$propVal]))->GetNext();
//esli u predlozheniya ne zapolneno svojstvo, vybiraem ego u tovara
                                    if ($arProps["VALUE"] == "" or empty($arProps["VALUE"]))
                                    {
                                        $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams[$propVal]))->GetNext();
                                        $dispProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProps);
                                    }
                                    else
                                    {
                                        $dispProp = CIBlockFormatProperties::GetDisplayValue($arOfferIn, $arProps);
                                    }
                                    if ($propVal == "DESCRIPTION")
                                    {
                                        if ($dispProp["~VALUE"])
                                            $arOfferIn[$propVal] = xml_creator(preg_replace_callback("'&[^;]*;'", "charset_modifier", strip_tags($dispProp["~VALUE"])), true);
                                        else
                                            $arOfferIn[$propVal] = xml_creator(preg_replace_callback("'&[^;]*;'", "charset_modifier", strip_tags($dispProp["~VALUE"]["TEXT"])), true);
                                    }
                                    else
                                    {
                                        $arOfferIn[$propVal] = $dispProp["VALUE_ENUM"] ? xml_creator($dispProp["VALUE_ENUM"], true) : xml_creator($dispProp["DISPLAY_VALUE"], true);
                                        $arOfferIn[$propVal] = strip_tags($arOfferIn[$propVal]);
                                    }
                                }
                                if (($propVal == "STORE_OFFER" or $propVal == "STORE_PICKUP") and ! $isExistProp)//vozmozhnost vpisyvaniya
                                {
                                    $arOfferIn[$propVal] = xml_creator($arParams[$propVal], true);
                                }
//dostupnost tovarov
                                if ($propVal == "PROP_ALGORITHM_VALUE")
                                {
                                    $arOfferIn["AVAIBLE"] = "false";
                                    if ($arOfferIn[$propVal] == "true" or $arOfferIn[$propVal] == "Y" or $arOfferIn[$propVal] == "1")
                                        $arOfferIn["AVAIBLE"] = "true";
                                    if ($arOfferIn[$propVal] == "false" or $arOfferIn[$propVal] == "N" or $arOfferIn[$propVal] == "0" or empty($arOfferIn[$propVal]))
                                        $arOfferIn["AVAIBLE"] = "false";
                                }
                            }
                        }
                    }
                    unset($arProps);
                    unset($dispProp);
                }
                /* ADDITIONAL PROPS OFFERS END */
                //NEW_IBLOCK_ORDER 
                if ($arParams["IBLOCK_ORDER"] != "Y" && $arOfferIn["AVAIBLE"] == "false")
                    continue;

                if (intval($arOfferIn["PRICE"]) <= 0)
                    continue;
                //NEW_IBLOCK_ORDER 

                if ($arOfferIn["DETAIL_PICTURE"])
                {
                    $db_file = CFile::GetByID($arOfferIn["DETAIL_PICTURE"]);
                    if ($ar_file = $db_file->Fetch())
                        $arOfferIn["PICTURE"] = $ar_file["~src"] ? $ar_file["~src"] : $http . "://" . $_SERVER["SERVER_NAME"] . "/" . (COption::GetOptionString("main", "upload_dir", "upload")) . "/" . $ar_file["SUBDIR"] . "/" . implode("/", array_map("rawurlencode", explode("/", $ar_file["FILE_NAME"])));
                    unset($ar_file);
                    unset($db_file);
                }

                if ($arOfferIn["PREVIEW_PICTURE"] && !$arOfferIn["PICTURE"])
                {
                    $db_file = CFile::GetByID($arOfferIn["PREVIEW_PICTURE"]);
                    if ($ar_file = $db_file->Fetch())
                        $arOfferIn["PICTURE"] = $ar_file["~src"] ? $ar_file["~src"] : "http://" . $_SERVER["SERVER_NAME"] . "/" . (COption::GetOptionString("main", "upload_dir", "upload")) . "/" . $ar_file["SUBDIR"] . "/" . implode("/", array_map("rawurlencode", explode("/", $ar_file["FILE_NAME"])));
                    unset($ar_file);
                    unset($db_file);
                }

                if (!empty($arParams["MORE_PHOTO"]) && $arParams["MORE_PHOTO"] != "WF_EMPT")
                {

                    $ph = CIBlockElement::GetProperty($arOfferIn["IBLOCK_ID"], $arOfferIn["ID"], array("sort" => "asc"), Array("CODE" => $arParams["MORE_PHOTO"]));
                    $arOfferIn["MORE_PHOTO"] = array();

                    while (($ob = $ph->GetNext()) && count($arOfferIn["MORE_PHOTO"]) < 10) {
                        $arFile = CFile::GetFileArray($ob["VALUE"]);
                        if (!empty($arFile))
                        {
                            if (strpos($arFile["SRC"], $http) === false)
                            {
                                $pic = "http://" . $_SERVER["SERVER_NAME"] . implode("/", array_map("rawurlencode", explode("/", $arFile["SRC"])));
                            }
                            else
                            {
                                $ar = explode("http://", $arFile["SRC"]);
                                $pic = "http://" . implode("/", array_map("rawurlencode", explode("/", $ar[1])));
                            }
                            $arOfferIn["MORE_PHOTO"][] = $pic;
                        }
                        unset($ob);
                        unset($arFile);
                    }
                    unset($ph);
                }

                if (is_array($arOffer["MORE_PHOTO"]))
                    foreach ($arOffer["MORE_PHOTO"] as $pic)
                    {
                        if (!in_array($pic, $arOfferIn["MORE_PHOTO"]) && count($arOfferIn["MORE_PHOTO"]) < 10)
                            $arOfferIn["MORE_PHOTO"][] = $pic;
                    }

                if (!$arOfferIn["PICTURE"])
                {
                    if ($arOffer["PICTURE"])
                        $arOfferIn["PICTURE"] = $arOffer["PICTURE"];
                    else
                    if (is_array($arOfferIn["MORE_PHOTO"]))
                        $arOfferIn["PICTURE"] = array_shift($arOfferIn["MORE_PHOTO"]);
                }
                $arOfferIn["MORE_PHOTO"] = array_slice($arOfferIn["MORE_PHOTO"], 0, 9);

                if ($arParams["NO_DESCRIPTION"] != "Y")//ispolzovat description
                {
                    if (empty($arParams["DESCRIPTION"]))
                    {
                        if ($arOfferIn["PREVIEW_TEXT"])
                        {
                            $arOfferIn["PREVIEW_TEXT"] = xml_creator(($arOfferIn["PREVIEW_TEXT_TYPE"] == "html" ? preg_replace_callback("'&[^;]*;'", "charset_modifier", strip_tags($arOfferIn["~PREVIEW_TEXT"])) : $arOfferIn["~PREVIEW_TEXT"]), true);
                        }

                        if ($arOfferIn["DETAIL_TEXT"])
                        {
                            $arOfferIn["DETAIL_TEXT"] = xml_creator(($arOfferIn["DETAIL_TEXT_TYPE"] == "html" ? preg_replace_callback("'&[^;]*;'", "charset_modifier", strip_tags($arOfferIn["~DETAIL_TEXT"])) : $arOfferIn["~DETAIL_TEXT"]), true);
                        }

                        if ($arParams["DETAIL_TEXT_PRIORITET"] == "Y")//prioritet detalnogo teksta
                        {
                            $arOfferIn["DESCRIPTION"] = $arOfferIn["DETAIL_TEXT"] ? $arOfferIn["DETAIL_TEXT"] : $arOfferIn["PREVIEW_TEXT"];
                        }
                        else//prioritet anonsa teksta
                        {
                            $arOfferIn["DESCRIPTION"] = $arOfferIn["PREVIEW_TEXT"] ? $arOfferIn["PREVIEW_TEXT"] : $arOfferIn["DETAIL_TEXT"];
                        }
                        if (empty($arOfferIn["DESCRIPTION"]))
                            $arOfferIn["DESCRIPTION"] = $arOffer["DESCRIPTION"];
                    }
                }
                else//ne ispolzovat description
                {
                    $arOfferIn["DESCRIPTION"] = '';
                }


// MARKET_CATEGORY

                if ($arParams["MARKET_CATEGORY_CHECK"] == "Y")
                {
                    $arOfferIn["MARKET_CATEGORY"] = $arOffer["MARKET_CATEGORY"];
                }

// GROUP_ID
                $arOfferIn["GROUP_ID"] = $arOffer["ID"];
// ID Ibloka cataloga
                $arOfferIn["IBLOCK_ID_CATALOG"] = $arOffer["IBLOCK_ID"];

                if ($arParams['SECTION_AS_VENDOR'] == 'Y')
                {
                    if (!empty($arOffer['IBLOCK_SECTION_ID']))
                    {
                        $arOfferIn["DEVELOPER"] = $arOffer["DEVELOPER"];
                    }
                }

                $arResult["OFFER"][] = $arOfferIn;
            } // foreach ($arOffer["SKU"] as &$arOfferInID)

            if ($flag == 1)
                continue;

            if (!$bCatalog || $arParams['PRICE_FROM_IBLOCK'] == 'Y')
            {
                $arOffer["AVAIBLE"] = "true";
                if (isset($arParams["IBLOCK_QUANTITY"]) && $arParams["IBLOCK_QUANTITY"] != "WF_EMPT")
                {
                    $av = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams["IBLOCK_QUANTITY"]))->Fetch();
                    if (IntVal($av["VALUE"]) > 0)
                        $arOffer["AVAIBLE"] = "true";
                    else
                    {
                        if ($arParams["IBLOCK_ORDER"] == "Y")
                            $arOffer["AVAIBLE"] = "false";
                        else
                            continue;
                    }
                }
            }

            if ($bCatalog && $arParams['PRICE_FROM_IBLOCK'] != 'Y')
            {
                if ($arOffer['CURRENCY'] == "RUR")
                    $arOffer['CURRENCY'] = "RUB";

                if ($arParams["CURRENCIES_CONVERT"] != "NOT_CONVERT")
                {
                    if ($arParams["DISCOUNTS"] != "DISCOUNT_API")
                    {
                        $newval = CCurrencyRates::ConvertCurrency($arOffer["PRICE"], $arOffer["CURRENCY"], $arParams["CURRENCIES_CONVERT"]);
                        $arOffer["PRICE"] = round($newval, 2);
                    }
                    else
                    {
                        $arOffer["PRICE"] = round($arOffer["PRICE"], 2);
                    }
                    $arOffer["CURRENCY"] = $arParams["CURRENCIES_CONVERT"];
                }
                if ($arParams["PRICE_ROUND"] == "Y")
                {
                    $arOffer["PRICE"] = round($arOffer["PRICE"]);
                    $arOffer["PRICE"] = $arOffer["PRICE"] . ".00";
                }

                if (!in_array($arOffer["CURRENCY"], $arResult["CURRENCIES"]))
                    $arResult["CURRENCIES"][] = $arOffer["CURRENCY"];
            }
            else
            {

                $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer['ID'], array("sort" => "asc"), Array("CODE" => $arParams["PRICE_CODE"]))->Fetch();

                $arOffer["PRICE"] = $arProps["VALUE_ENUM"] ? $arProps["VALUE_ENUM"] : $arProps["VALUE"];
                $arOffer["PRICE"] = floatval(str_replace(" ", "", $arOffer["PRICE"]));
                if ($arParams["PRICE_ROUND"] == "Y")
                {
                    $arOffer["PRICE"] = round($arOffer["PRICE"]);
                    $arOffer["PRICE"] = $arOffer["PRICE"] . ".00";
                }
                unset($arProps);

                if (intval($arOffer["PRICE"]) <= 0 && $arParams['PRICE_REQUIRED'] != 'N')
                    continue;

                if (!empty($arParams["CURRENCIES_PROP"]))
                    $arProps = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer['ID'], array("sort" => "asc"), Array("CODE" => $arParams["CURRENCIES_PROP"]))->Fetch();

                $arOffer["CURRENCY"] = empty($arProps["VALUE_XML_ID"]) ? $arParams["CURRENCY"] : $arProps["VALUE_XML_ID"];
                $arProps = null;

                if (!in_array($arOffer["CURRENCY"], $arResult["CURRENCIES"]))
                    $arResult["CURRENCIES"][] = $arOffer["CURRENCY"];
            }

            $arResult["OFFER"][] = $arOffer;

            $i++;
        }

        //DELIVERY OPTIONS SETTINGS
        if (!empty($arResult["OFFER"]))
        {
            foreach ($arResult["OFFER"] as &$arOffer)
            {
                if (!empty($arOffer["DELIVERY_OPTIONS_EX"]))
                {
                    foreach ($arOffer["DELIVERY_OPTIONS_EX"] as $kde => $kdv)
                    {
                        foreach ($kdv as $kdvK => $kdvV)
                        {
                            if ($kdvK == 0 and $kdvV == "")
                            {
                                unset($arOffer["DELIVERY_OPTIONS_EX"][$kde]);
                            }
                        }
                    }
                }
            }
        }

        //CURRENCIES SETTINGS
        if (!empty($arParams["BIG_CATALOG_PROP"]) and $arParams["SAVE_IN_FILE"] == "Y" and $arParams["CURRENCIES_CONVERT"] == "NOT_CONVERT" and $arResult["WF_NUM"] == 1 and $arParams['PRICE_FROM_IBLOCK'] != 'Y')
        {
            $db_res = CCatalogGroup::GetList(
                    array(), array(
                  "CURRENCY" => $arParams["PRICE_CODE"]
                    ), false, false, array("ID")
            );
            while ($ar_res = $db_res->Fetch()) {
                $result[] = $ar_res["ID"];
            }
            foreach ($result as $k => $re)
            {
                $curInfo = CIBlockElement::GetList(array(), array(array_merge($arrFilter, $arFilter)), false, false, array("ID", "CATALOG_GROUP_" . $re));
                while ($curob = $curInfo->Fetch()) {
                    if (!in_array($curob["CATALOG_CURRENCY_" . $re], $resCur) and ! empty($curob["CATALOG_CURRENCY_" . $re]))
                        $resCur[] = $curob["CATALOG_CURRENCY_" . $re];
                }
            }
            $arResult["CURRENCIES"] = $resCur;
        }

        unset($arOffers);

        $this->IncludeComponentTemplate();

        if ($arParams["CACHE_NON_MANAGED"] == 'Y')
        {
            $obCache->EndDataCache();
        }
    }

    if (!$bDesignMode and ! $bSaveInFile)
    {
        $r = $APPLICATION->EndBufferContentMan();
        echo $r;
        if (defined("HTML_PAGES_FILE") && !defined("ERROR_404"))
            CHTMLPagesCache::writeFile(HTML_PAGES_FILE, $r);
        die();
    }
}
else
{
    echo GetMessage("ADMIN_TEXT_STOP");
}
?>
<?

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
        return $text;
    }

}

function getBaseCurrency() {
    if (CModule::IncludeModule('currency'))
    {
        $res = CCurrency::GetList(($by = "name"), ($order = "asc"), "RU");
        while ($arRes = $res->Fetch()) {
            if ($arRes["AMOUNT"] == 1)
                return $arRes["CURRENCY"];
        }
    }
}

$baseCur = getBaseCurrency();
if (!CModule::IncludeModule('currency'))
    $baseCur = $arParams["CURRENCY"];
$arCur = array();
$arCur[0] = $baseCur;
foreach ($arResult["CURRENCIES"] as $cur)
{
    $cur = trim($cur);
    if ($cur == 'RUR')
    {
        $cur = 'RUB';
    }

    if (!in_array($cur, $arCur))
        $arCur[] = $cur;

    if (CModule::IncludeModule('currency')) {
        /* Take curr curency START */
        $arFilter = array(
            "CURRENCY" => $cur,
        );
        $by = "date";
        $order = "asc";

        $db_rate = CCurrencyRates::GetList($by, $order, $arFilter);
        while ($ar_rate = $db_rate->Fetch()) {
            if ($ar_rate["RATE_CNT"] != "1")
                $resCurrency[$ar_rate["CURRENCY"]] = round($ar_rate["RATE_CNT"] / $ar_rate["RATE"], 4);
            else
                $resCurrency[$ar_rate["CURRENCY"]] = $ar_rate["RATE"];
        }
        if ($resCurrency == NULL) {
            $curTo = CCurrency::GetByID($cur);
            if (!in_array($curTo, $resCurrency)) {
                if ($curTo["AMOUNT_CNT"] != "1")
                    $resCurrency[$curTo["CURRENCY"]] = round($curTo["AMOUNT_CNT"] / $curTo["AMOUNT"], 4);
                else
                    $resCurrency[$curTo["CURRENCY"]] = $curTo["AMOUNT"];
            }
        }
        /* Take curr curency END */
    }
}

$arResult["CURRENCIES"] = $arCur;
$arResult["WF_AMOUNTS"] = $resCurrency;

foreach ($arResult["OFFER"] as &$arOffer)
{
    $code = "PLACE";
    $props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams[$code]))->Fetch();
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"] ? $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"] : strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);
    if (empty($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]))
    {
        $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE"]);
    }
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = xml_creator($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"], true);
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = str_replace("'", "&apos;", $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

    $code = "DATE";
    $props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams[$code]))->Fetch();
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"] ? $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"] : strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);
    if (empty($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]))
    {
        $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE"]);
    }
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = xml_creator($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"], true);
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = str_replace("'", "&apos;", $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

    $code = "IS_PREMIERE";
    $props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams[$code]))->Fetch();
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"] ? $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"] : strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);
    if (empty($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]))
    {
        $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE"]);
    }
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = xml_creator($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"], true);
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = str_replace("'", "&apos;", $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);

    $code = "IS_KIDS";
    $props = CIBlockElement::GetProperty($arOffer["IBLOCK_ID"], $arOffer["ID"], array("sort" => "asc"), Array("CODE" => $arParams[$code]))->Fetch();
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]] = CIBlockFormatProperties::GetDisplayValue($arResult["OFFER"], $props, "ym_out");
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"] ? $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE_ENUM"] : strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);
    if (empty($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]))
    {
        $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = strip_tags($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["VALUE"]);
    }
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = xml_creator($arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"], true);
    $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"] = str_replace("'", "&apos;", $arOffer["DISPLAY_PROPERTIES"][$arParams[$code]]["DISPLAY_VALUE"]);
}
?>

<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

/* IB Type */
$arTypes = CIBlockParameters::GetIBlockTypes(array("-"=>" "));

/* IB List */
$arIBlocks 		= array();
$DEF_IB_TYPE 	= ($arCurrentValues["IBLOCK_TYPE"] != "-") ? $arCurrentValues["IBLOCK_TYPE"] : "";
$rsIB 			= CIBlock::GetList(array("SORT" => "ASC"), array("SITE_ID" => $_REQUEST["site"], "TYPE" => $DEF_IB_TYPE));
while($arIB = $rsIB->Fetch())
{
	$arIBlocks [$arIB["ID"]] = $arIB["NAME"];
}

/* IB Props */
$arProperty 	= array();
$arPropertySort = array('NAME' => GetMessage('PR_RECRM_C_PROP_NAME_N'));
$idPropIB 		= isset($arCurrentValues["IBLOCK_ID"]) ? $arCurrentValues["IBLOCK_ID"] : $arCurrentValues["ID"];
$rsProp 		= CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$idPropIB));
while ($arProp = $rsProp->Fetch())
{
	$arProperty [$arProp["CODE"]] = $arProp["NAME"] . " [".$arProp["CODE"]."]";
	$arPropertySort ["PROPERTY_".$arProp["CODE"]] = $arProp["NAME"] . " [".$arProp["CODE"]."]";
}

$PARAMETERS = array();

/* Base */
$PARAMETERS ["IBLOCK_TYPE"] = array(
	"PARENT" 	=> "BASE",
	"NAME" 		=> GetMessage("PR_RECRM_C_IBLOCK_TYPE"),
	"TYPE" 		=> "LIST",
	"VALUES" 	=> $arTypes,
	"DEFAULT" 	=> "-",
	"REFRESH" 	=> "Y",
);
$PARAMETERS ["IBLOCK_ID"] = array(
	"PARENT"			=> "BASE",
	"NAME"				=> GetMessage("PR_RECRM_C_IBLOCK_ID"),
	"TYPE"				=> "LIST",
	"VALUES"			=> $arIBlocks,
	"DEFAULT"			=> '',
	"ADDITIONAL_VALUES"	=> "Y",
	"REFRESH"			=> "Y",
);

/* Order */
$PARAMETERS ["SORT_BY1"] = array(
	"PARENT" 				=> "DATA_SOURCE",
	"NAME" 					=> GetMessage("PR_RECRM_C_SORT_BY1"),
	"TYPE" 					=> "LIST",
	"DEFAULT" 				=> "PROPERTY_edit_datetime",
	"VALUES" 				=> $arPropertySort,
	"ADDITIONAL_VALUES" 	=> "Y",
);
$PARAMETERS ["SORT_ORDER1"] = array(
	"PARENT" 				=> "DATA_SOURCE",
	"NAME" 					=> GetMessage("PR_RECRM_C_SORT_ORDER1"),
	"TYPE" 					=> "LIST",
	"DEFAULT" 				=> "DESC",
	"VALUES" 				=> array(
		"ASC" 				=> GetMessage("PR_RECRM_C_SORT_ASC"),
		"DESC" 				=> GetMessage("PR_RECRM_C_SORT_DESC"),
	),
	"ADDITIONAL_VALUES" 	=> "Y",
);
$PARAMETERS ["SORT_BY2"] = array(
	"PARENT" 				=> "DATA_SOURCE",
	"NAME" 					=> GetMessage("PR_RECRM_C_SORT_BY2"),
	"TYPE" 					=> "LIST",
	"DEFAULT" 				=> "",
	"VALUES" 				=> $arPropertySort,
	"ADDITIONAL_VALUES" 	=> "Y",
);
$PARAMETERS ["SORT_ORDER2"] = array(
	"PARENT" 				=> "DATA_SOURCE",
	"NAME" 					=> GetMessage("PR_RECRM_C_SORT_ORDER2"),
	"TYPE" 					=> "LIST",
	"DEFAULT" 				=> "",
	"VALUES" 				=> array(
		"ASC" 				=> GetMessage("PR_RECRM_C_SORT_ASC"),
		"DESC" 				=> GetMessage("PR_RECRM_C_SORT_DESC"),
	),
	"ADDITIONAL_VALUES" 	=> "Y",
);

/* Filter */
$PARAMETERS ["FILTER_NAME"] = array(
	"PARENT" 	=> "DATA_SOURCE",
	"NAME" 		=> GetMessage("PR_RECRM_C_FILTER_NAME"),
	"TYPE" 		=> "STRING",
	"DEFAULT" 	=> "arFilter",
);

/* List Params */
$PARAMETERS ["LIST_MAP_BIG"] = array(
	"PARENT" 				=> "G_PARAMS_CAT",
	"NAME" 					=> GetMessage("PR_RECRM_C_LIST_MAP_BIG"),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "Y",
);
$PARAMETERS ["LIST_COUNT"] = array(
	"PARENT" 				=> "G_PARAMS_CAT",
	"NAME" 					=> GetMessage("PR_RECRM_C_LIST_COUNT"),
	"TYPE" 					=> "STRING",
	"DEFAULT" 				=> "20",
);
$PARAMETERS ["LIST_COVER_PHOTO"] = array(
	"PARENT" 				=> "G_PARAMS_CAT",
	"NAME" 					=> GetMessage("PR_RECRM_C_LIST_COVER_PHOTO"),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "Y",
);
$PARAMETERS ["LIST_PROPS_EMPTY"] = array(
	"PARENT" 				=> "G_PARAMS_CAT",
	"NAME" 					=> GetMessage("PR_RECRM_C_LIST_PROPS_EMPTY"),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "N",
);
$PARAMETERS ["LIST_DESC"] = array(
	"PARENT" 				=> "G_PARAMS_CAT",
	"NAME" 					=> GetMessage("PR_RECRM_C_LIST_DESC"),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "N",
);
$PARAMETERS ["LIST_PROPS"] = array(
	"PARENT" 				=> "G_PARAMS_CAT",
	"NAME" 					=> GetMessage("PR_RECRM_C_LIST_PROPS"),
	"TYPE" 					=> "LIST",
	"VALUES" 				=> $arProperty,
	"ADDITIONAL_VALUES" 	=> "N",
	"MULTIPLE" 				=> "Y",
	"SIZE" 					=> 13,
	"DEFAULT" 				=> "",
);

/* Detail Url */
$PARAMETERS ["DETAIL_URL"] = CIBlockParameters::GetPathTemplateParam(
	"DETAIL",
	"DETAIL_URL",
	GetMessage("PR_RECRM_C_DETAIL_URL"),
	"",
	"URL_TEMPLATES"
);

/* Ajax */
$PARAMETERS ["AJAX_MODE"] = array();

/* Cache */
$PARAMETERS ["CACHE_TIME"] = array(
	"DEFAULT" => 36000000
);
$PARAMETERS ["CACHE_GROUPS"] = array(
	"PARENT" 	=> "CACHE_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_CACHE_GROUPS"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "Y",
);

/* Additional Setting */
$PARAMETERS ["SET_TITLE"] = array();
$PARAMETERS ["SET_BROWSER_TITLE"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_SET_BROWSER_TITLE"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "Y",
);
$PARAMETERS ["SET_META_KEYWORDS"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_SET_META_KEYWORDS"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "Y",
);
$PARAMETERS ["SET_META_DESCRIPTION"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_SET_META_DESCRIPTION"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "Y",
);
$PARAMETERS ["SET_STATUS_404"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_SET_STATUS_404"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "N",
);
$PARAMETERS ["INCLUDE_IBLOCK_INTO_CHAIN"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_INC_IB_CHAIN"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "Y",
);

/* Groups */
$arComponentParameters = array(
	"GROUPS" => array(
		"G_PARAMS_CAT" => array(
			"NAME" => GetMessage("PR_RECRM_C_G_PARAMS_CAT"),
			"SORT" => "201",
		)
	),
	"PARAMETERS" => $PARAMETERS,
);

/* Pager */
CIBlockParameters::AddPagerSettings($arComponentParameters, GetMessage("PR_RECRM_C_PAGER_NAME"), true, true);
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
$arPropertySeo 	= array('-' => '', 'NAME' => GetMessage('PR_RECRM_C_PROP_NAME_N'));
$idPropIB 		= isset($arCurrentValues["IBLOCK_ID"]) ? $arCurrentValues["IBLOCK_ID"] : $arCurrentValues["ID"];
$rsProp 		= CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$idPropIB));
while ($arProp = $rsProp->Fetch())
{
	$arProperty [$arProp["CODE"]] = $arProp["NAME"] . " [".$arProp["CODE"]."]";
	$arPropertySeo [$arProp["CODE"]] = $arProp["NAME"] . " [".$arProp["CODE"]."]";
}

/* Group Per */
$arUGroupsEx = array();
$dbUGroups = CGroup::GetList($by = "c_sort", $order = "asc");
while($arUGroups = $dbUGroups -> Fetch())
{
	$arUGroupsEx[$arUGroups["ID"]] = $arUGroups["NAME"];
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
	"DEFAULT"			=> '={$_REQUEST["ID"]}',
	"ADDITIONAL_VALUES"	=> "Y",
	"REFRESH"			=> "Y",
);
$PARAMETERS ["ELEMENT_ID"] = array(
	"PARENT" 	=> "BASE",
	"NAME" 		=> GetMessage('PR_RECRM_C_ELEMENT_ID'),
	"TYPE" 		=> "STRING",
	"DEFAULT" 	=> '={$_REQUEST["ELEMENT_ID"]}',
);
$PARAMETERS ["ELEMENT_CODE"] = array(
	"PARENT" 	=> "BASE",
	"NAME" 		=> GetMessage('PR_RECRM_C_ELEMENT_CODE'),
	"TYPE" 		=> "STRING",
	"DEFAULT" 	=> '',
);

/* Detail Params */
$PARAMETERS ["DETAIL_PHOTOS"] = array(
	"PARENT" 				=> "G_PARAMS_CARD",
	"NAME" 					=> GetMessage('PR_RECRM_C_DETAIL_PHOTOS'),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "Y",
);
$PARAMETERS ["DETAIL_MAP"] = array(
	"PARENT" 				=> "G_PARAMS_CARD",
	"NAME" 					=> GetMessage('PR_RECRM_C_DETAIL_MAP'),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "Y",
);
$PARAMETERS ["DETAIL_VIDEO"] = array(
	"PARENT" 				=> "G_PARAMS_CARD",
	"NAME" 					=> GetMessage('PR_RECRM_C_DETAIL_VIDEO'),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "Y",
);
$PARAMETERS ["DETAIL_AGENT"] = array(
	"PARENT" 				=> "G_PARAMS_CARD",
	"NAME" 					=> GetMessage('PR_RECRM_C_DETAIL_AGENT'),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "Y",
);
$PARAMETERS ["DETAIL_TEXT"] = array(
	"PARENT" 				=> "G_PARAMS_CARD",
	"NAME" 					=> GetMessage('PR_RECRM_C_DETAIL_TEXT'),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "Y",
);
$PARAMETERS ["DETAIL_PROPS_EMPTY"] = array(
	"PARENT" 				=> "G_PARAMS_CARD",
	"NAME" 					=> GetMessage("PR_RECRM_C_DETAIL_PROPS_EMPTY"),
	"TYPE" 					=> "CHECKBOX",
	"DEFAULT" 				=> "N",
);
$PARAMETERS ["DETAIL_PROPS"] = array(
	"PARENT" 				=> "G_PARAMS_CARD",
	"NAME" 					=> GetMessage("PR_RECRM_C_DETAIL_PROPS"),
	"TYPE" 					=> "LIST",
	"VALUES" 				=> $arProperty,
	"ADDITIONAL_VALUES" 	=> "N",
	"MULTIPLE" 				=> "Y",
	"SIZE" 					=> 13,
	"DEFAULT" 				=> "",
);

/* IB Url */
$PARAMETERS ["IBLOCK_URL"] = CIBlockParameters::GetPathTemplateParam(
	"LIST",
	"IBLOCK_URL",
	GetMessage("PR_RECRM_C_IBLOCK_URL"),
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
	"REFRESH" 	=> "Y",
);
$PARAMETERS ["BROWSER_TITLE"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_BROWSER_TITLE"),
	"TYPE" 		=> "LIST",
	"MULTIPLE" 	=> "N",
	"DEFAULT" 	=> "-",
	"VALUES" 	=> $arPropertySeo,
	"HIDDEN" 	=> ($arCurrentValues['SET_BROWSER_TITLE'] == 'N' ? 'Y' : 'N')
);
$PARAMETERS ["SET_META_KEYWORDS"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_SET_META_KEYWORDS"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "Y",
	"REFRESH" 	=> "Y",
);
$PARAMETERS ["META_KEYWORDS"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_META_KEYWORDS"),
	"TYPE" 		=> "LIST",
	"MULTIPLE" 	=> "N",
	"DEFAULT" 	=> "-",
	"VALUES" 	=> $arPropertySeo,
	"HIDDEN" 	=> ($arCurrentValues['SET_META_KEYWORDS'] == 'N' ? 'Y' : 'N')
);
$PARAMETERS ["SET_META_DESCRIPTION"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_SET_META_DESCRIPTION"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "Y",
	"REFRESH" 	=> "Y"
);
$PARAMETERS ["META_DESCRIPTION"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_META_DESCRIPTION"),
	"TYPE" 		=> "LIST",
	"MULTIPLE" 	=> "N",
	"DEFAULT" 	=> "-",
	"VALUES" 	=> $arPropertySeo,
	"HIDDEN" 	=> ($arCurrentValues['SET_META_DESCRIPTION'] == 'N' ? 'Y' : 'N')
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
$PARAMETERS ["ADD_ELEMENT_CHAIN"] = array(
	"PARENT" 	=> "ADDITIONAL_SETTINGS",
	"NAME" 		=> GetMessage("PR_RECRM_C_ADD_EL_CHAIN"),
	"TYPE" 		=> "CHECKBOX",
	"DEFAULT" 	=> "N"
);
$PARAMETERS ["USE_PERMISSIONS"] = array(
	"PARENT"	=> "ADDITIONAL_SETTINGS",
	"NAME"		=> GetMessage("PR_RECRM_C_USE_PERMISSIONS"),
	"TYPE"		=> "CHECKBOX",
	"DEFAULT"	=> "N",
	"REFRESH"	=> "Y",
);
$PARAMETERS ["GROUP_PERMISSIONS"] = array(
	"PARENT"	=> "ADDITIONAL_SETTINGS",
	"NAME"		=> GetMessage("PR_RECRM_C_GROUP_PERMISSIONS"),
	"TYPE"		=> "LIST",
	"VALUES"	=> $arUGroupsEx,
	"DEFAULT"	=> array(1),
	"MULTIPLE"	=> "Y",
);

/* Groups */
$arComponentParameters = array(
	"GROUPS" => array(
		"G_PARAMS_CARD" => array(
			"NAME" => GetMessage("PR_RECRM_C_G_PARAMS_CARD"),
			"SORT" => "202",
		),
	),
	"PARAMETERS" => $PARAMETERS,
);

/* Pager */
CIBlockParameters::AddPagerSettings($arComponentParameters, GetMessage("PR_RECRM_C_PAGER_TEXT"), false, true);
unset($arComponentParameters["PARAMETERS"]["PAGER_SHOW_ALWAYS"]);

/* Permis */
if($arCurrentValues["USE_PERMISSIONS"] != "Y")
	unset($arComponentParameters["PARAMETERS"]["GROUP_PERMISSIONS"]);
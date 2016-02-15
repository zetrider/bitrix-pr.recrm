<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */
global $INTRANET_TOOLBAR;

/* Add JQuery */
CJSCore::Init(array("jquery"));

/* ReCrm */
if(!CModule::IncludeModule("pr.recrm")):
	ShowError(GetMessage("PR_RECRM_C_ERR_MODULE"));
	return;
endif;

$RECRM = new prReCrmData;

/* IB Type */
$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"]) <= 0 OR $arParams["IBLOCK_TYPE"] == "-"):
	ShowError(GetMessage("PR_RECRM_C_ERR_IBT"));
	return;
endif;

/* IB ID */
$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
if(strlen($arParams["IBLOCK_ID"]) <= 0):
	ShowError(GetMessage("PR_RECRM_C_ERR_IB"));
	return;
endif;

/* No Session */
CPageOption::SetOptionString("main", "nav_page_in_session", "N");

/* Cache Time */
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

/* Order */
$arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
if(strlen($arParams["SORT_BY1"]) <= 0)
	$arParams["SORT_BY1"] = "edit_datetime";

if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"]))
	$arParams["SORT_ORDER1"] = "DESC";

$arParams["SORT_BY2"] = trim($arParams["SORT_BY2"]);
if(strlen($arParams["SORT_BY2"]) <= 0)
	$arParams["SORT_BY2"] = "";

if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER2"]))
	$arParams["SORT_ORDER2"] = "";

/* Filter */
if(strlen($arParams["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])):
	$arrFilter = array();
else:
	$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arrFilter))
		$arrFilter = array();
endif;

/* Props */
$ADD_PROPS_COMP = array();
if(is_array($arParams["LIST_PROPS"]) AND count($arParams["LIST_PROPS"]) > 0):
	$arParams["LIST_PROPS"] = array_filter($arParams["LIST_PROPS"]);
else:
	$arParams["LIST_PROPS"] = array();
endif;

/* Cover Photo Prop */
if($arParams["LIST_COVER_PHOTO"] == "Y" AND !in_array("estatecoverphoto", $arParams["LIST_PROPS"])):
	$arParams["LIST_PROPS"][] = "estatecoverphoto";
	$ADD_PROPS_COMP[] = 'estatecoverphoto';
endif;

/* Big Map Prop */
if($arParams["LIST_MAP_BIG"] == "Y"):
	if(!in_array("zoom", $arParams["LIST_PROPS"])):
		$arParams["LIST_PROPS"][] = "zoom";
		$ADD_PROPS_COMP[] = 'zoom';
	endif;
	
	if(!in_array("latitude", $arParams["LIST_PROPS"])):
		$arParams["LIST_PROPS"][] = "latitude";
		$ADD_PROPS_COMP[] = 'latitude';
	endif;

	if(!in_array("longitude", $arParams["LIST_PROPS"])):
		$arParams["LIST_PROPS"][] = "longitude";
		$ADD_PROPS_COMP[] = 'longitude';
	endif;
endif;

/* Cover Size */
$arParams["LIST_COVER_PHOTO_SIZE"] = $RECRM->getCoverSize();

/* Detail Url */
$arParams["DETAIL_URL"] = trim($arParams["DETAIL_URL"]);

/* Desc */
$arParams["LIST_DESC"] = trim($arParams["LIST_DESC"]);

/* Count Elements */
$arParams["LIST_COUNT"] = intval($arParams["LIST_COUNT"]);
if($arParams["LIST_COUNT"] <= 0)
	$arParams["LIST_COUNT"] = 20;


/* Additional */
$arParams["SET_TITLE"] 					= $arParams["SET_TITLE"]!="N";
$arParams["SET_BROWSER_TITLE"] 			= (isset($arParams["SET_BROWSER_TITLE"]) && $arParams["SET_BROWSER_TITLE"] === 'N' ? 'N' : 'Y');
$arParams["SET_META_KEYWORDS"] 			= (isset($arParams["SET_META_KEYWORDS"]) && $arParams["SET_META_KEYWORDS"] === 'N' ? 'N' : 'Y');
$arParams["SET_META_DESCRIPTION"] 		= (isset($arParams["SET_META_DESCRIPTION"]) && $arParams["SET_META_DESCRIPTION"] === 'N' ? 'N' : 'Y');
$arParams["INCLUDE_IBLOCK_INTO_CHAIN"] 	= $arParams["INCLUDE_IBLOCK_INTO_CHAIN"]!="N";

/* Pager */
$arParams["DISPLAY_TOP_PAGER"] 					= $arParams["DISPLAY_TOP_PAGER"]=="Y";
$arParams["DISPLAY_BOTTOM_PAGER"] 				= $arParams["DISPLAY_BOTTOM_PAGER"]!="N";
$arParams["PAGER_TITLE"] 						= trim($arParams["PAGER_TITLE"]);
$arParams["PAGER_SHOW_ALWAYS"] 					= $arParams["PAGER_SHOW_ALWAYS"]=="Y";
$arParams["PAGER_TEMPLATE"] 					= trim($arParams["PAGER_TEMPLATE"]);
$arParams["PAGER_DESC_NUMBERING"] 				= $arParams["PAGER_DESC_NUMBERING"]=="Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] 	= intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] 					= $arParams["PAGER_SHOW_ALL"]=="Y";

if($arParams["DISPLAY_TOP_PAGER"] || $arParams["DISPLAY_BOTTOM_PAGER"]):
	$arNavParams = array(
		"nPageSize" 			=> $arParams["LIST_COUNT"],
		"bDescPageNumbering" 	=> $arParams["PAGER_DESC_NUMBERING"],
		"bShowAll" 				=> $arParams["PAGER_SHOW_ALL"],
	);
	$arNavigation = CDBResult::GetNavParams($arNavParams);
	if($arNavigation["PAGEN"] == 0 AND $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] > 0):
		$arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
	endif;
else:
	$arNavParams = array(
		"nTopCount" 			=> $arParams["LIST_COUNT"],
		"bDescPageNumbering" 	=> $arParams["PAGER_DESC_NUMBERING"],
	);
	$arNavigation = false;
endif;

/* Access */
$arParams["USE_PERMISSIONS"] = $arParams["USE_PERMISSIONS"]=="Y";
if(!is_array($arParams["GROUP_PERMISSIONS"]))
	$arParams["GROUP_PERMISSIONS"] = array(1);

$bUSER_HAVE_ACCESS = !$arParams["USE_PERMISSIONS"];
if($arParams["USE_PERMISSIONS"] && isset($GLOBALS["USER"]) && is_object($GLOBALS["USER"])):
	$arUserGroupArray = $USER->GetUserGroupArray();
	foreach($arParams["GROUP_PERMISSIONS"] as $PERM):
		if(in_array($PERM, $arUserGroupArray)):
			$bUSER_HAVE_ACCESS = true;
			break;
		endif;
	endforeach;
endif;

$arParams["PARENT_SECTION"] 		= 0;
$arParams["INCLUDE_SUBSECTIONS"] 	= true;
$arParams["CHECK_DATES"] 			= false;
$arParams["FIELD_CODE"] 			= array();
$arParams["PROPERTY_CODE"] 			= array();
$arParams["CACHE_FILTER"] 			= false;

if($this->StartResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $bUSER_HAVE_ACCESS, $arNavigation, $arrFilter)))
{
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("PR_RECRM_C_ERR_MOD_IB"));
		return;
	}
	if(is_numeric($arParams["IBLOCK_ID"]))
	{
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" 	=> "Y",
			"ID" 		=> $arParams["IBLOCK_ID"],
		));
	}
	else
	{
		$rsIBlock = CIBlock::GetList(array(), array(
			"ACTIVE" 	=> "Y",
			"CODE" 		=> $arParams["IBLOCK_ID"],
			"SITE_ID" 	=> SITE_ID,
		));
	}
	if($arResult = $rsIBlock->GetNext())
	{
		$arResult["USER_HAVE_ACCESS"] = $bUSER_HAVE_ACCESS;

		$arSelect = array(
			"ID",
			"IBLOCK_ID",
			"IBLOCK_SECTION_ID",
			"NAME",
			"ACTIVE_FROM",
			"DETAIL_PAGE_URL",
			"DETAIL_TEXT",
			"DETAIL_TEXT_TYPE",
			"PREVIEW_TEXT",
			"PREVIEW_TEXT_TYPE",
			"PREVIEW_PICTURE",
		);

		$arFilter = array(
			"IBLOCK_ID" 		=> $arParams["IBLOCK_ID"], 
			"IBLOCK_TYPE" 		=> $arParams["IBLOCK_TYPE"], 
			"IBLOCK_LID" 		=> SITE_ID,
			"ACTIVE" 			=> "Y",
			"CHECK_PERMISSIONS" => "Y",
		);

		$arOrder = array(
			$arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
			$arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
		);

		$arResult["SECTION"] 	= false;
		$arResult["ITEMS"] 		= array();
		$arResult["ELEMENTS"] 	= array();
		$arResult["MAP"] 		= array();
		/*
		if($arParams["LIST_PROPS_EMPTY"] == "Y"):
			$arFilterProps = array();
		else:
			$arFilterProps = array("EMPTY" => "N");
		endif;
		*/
		$rsElement = CIBlockElement::GetList($arOrder, array_merge($arFilter, $arrFilter), false, $arNavParams, $arSelect);
		$rsElement->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);		
		while($obElement = $rsElement->GetNextElement())
		{
			$arItem 	= $obElement->GetFields();
			$arProps 	= $obElement->GetProperties(array("SORT"=>"ASC")); //, $arFilterProps);
			
			$arItem["PROPERTIES"] = $arProps;
			
			$arButtons = CIBlock::GetPanelButtons(
				$arItem["IBLOCK_ID"],
				$arItem["ID"],
				0,
				array("SECTION_BUTTONS" => false, "SESSID" => false)
			);
			$arItem["EDIT_LINK"] 	= $arButtons["edit"]["edit_element"]["ACTION_URL"];
			$arItem["DELETE_LINK"] 	= $arButtons["edit"]["delete_element"]["ACTION_URL"];

			/* IP V */
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem["IBLOCK_ID"], $arItem["ID"]);
			$arItem["IPROPERTY_VALUES"] = $ipropValues->getValues();

			/* Cover Photo */
			$arItem['COVER_PHOTO'] = false;
			if($arParams["LIST_COVER_PHOTO"] == "Y"):
				if($arProps['estatecoverphoto']['VALUE'] != ''):
					$arItem['COVER_PHOTO']['URL'] = $arProps['estatecoverphoto']['VALUE'];

					$arItem["COVER_PHOTO"]["ALT"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"];
					if ($arItem["COVER_PHOTO"]["ALT"] == "")
						$arItem["COVER_PHOTO"]["ALT"] = $arItem["NAME"];

					$arItem["COVER_PHOTO"]["TITLE"] = $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"];
					if ($arItem["COVER_PHOTO"]["TITLE"] == "")
						$arItem["COVER_PHOTO"]["TITLE"] = $arItem["NAME"];
				endif;
			endif;

			/* Map */
			if($arParams["LIST_MAP_BIG"] == "Y"):
				if(intval($arProps['longitude']['VALUE']) > 0):
					$arResult["MAP"][] = array(
						'ID' => $arItem['ID'],
						'LOC' => array($arProps['latitude']['VALUE'], $arProps['longitude']['VALUE']),
					);
				endif;
			endif;

			/* Remove Prop for Data*/
			if(count($ADD_PROPS_COMP) > 0):
				foreach($ADD_PROPS_COMP AS $PROP_U):
					unset($arProps[$PROP_U]);
				endforeach;
			endif;

			/* Props */
			$arItem['PROPERTIES_RECRM'] = array();
			foreach($arProps AS $prop)
			{
				if(in_array($prop['CODE'], $arParams["LIST_PROPS"])):
					
					if($arParams["LIST_PROPS_EMPTY"] != "Y" AND $prop['VALUE'] == '')
						continue;

					/* Преобразуем данные для отображения */

					/* boll */
					if($prop['VALUE'] === 'true')
						$prop['VALUE'] = 'Да';
					if($prop['VALUE'] === 'false')
						$prop['VALUE'] = 'Нет';
					
					/* arr */
					if(is_array($prop['VALUE']))
						$prop['VALUE'] = implode('<br>', $prop['VALUE']);
					
					/* date */
					if(in_array($prop['CODE'], array('creation_date', 'edit_date')))
						$prop['VALUE'] = date(GetMessage("PR_RECRM_C_DATE"), $prop['VALUE']);

					/* datetime */
					if(in_array($prop['CODE'], array('creation_datetime', 'edit_datetime')))
						$prop['VALUE'] = date(GetMessage("PR_RECRM_C_DATETIME"), $prop['VALUE']);

					$arItem['PROPERTIES_RECRM'][$prop['CODE']] = $prop;
				endif;
			}
			
			$arResult["ITEMS"][] 	= $arItem;
			$arResult["ELEMENTS"][] = $arItem["ID"];		
		}

		$arResult["NAV_STRING"] 		= $rsElement->GetPageNavStringEx($navComponentObject, $arParams["PAGER_TITLE"], $arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
		$arResult["NAV_CACHED_DATA"] 	= $navComponentObject->GetTemplateCachedData();
		$arResult["NAV_RESULT"] 		= $rsElement;
		$this->SetResultCacheKeys(array(
			"ID",
			"IBLOCK_TYPE_ID",
			"LIST_PAGE_URL",
			"NAV_CACHED_DATA",
			"NAME",
			"SECTION",
			"ELEMENTS",
			"IPROPERTY_VALUES",
		));
		$this->IncludeComponentTemplate();
	}
	else
	{
		$this->AbortResultCache();
		ShowError(GetMessage("PR_RECRM_C_ERR_404"));
		@define("ERROR_404", "Y");
		if($arParams["SET_STATUS_404"] == "Y")
			CHTTP::SetStatus("404 Not Found");
	}
}

if(isset($arResult["ID"]))
{
	$arTitleOptions = null;
	if($USER->IsAuthorized())
	{
		if(
			$APPLICATION->GetShowIncludeAreas()
			|| (is_object($GLOBALS["INTRANET_TOOLBAR"]) && $arParams["INTRANET_TOOLBAR"]!=="N")
			|| $arParams["SET_TITLE"]
		)
		{
			if(CModule::IncludeModule("iblock"))
			{
				$arButtons = CIBlock::GetPanelButtons(
					$arResult["ID"],
					0,
					$arParams["PARENT_SECTION"],
					array("SECTION_BUTTONS"=>false)
				);

				if($APPLICATION->GetShowIncludeAreas())
					$this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));

				if(
					is_array($arButtons["intranet"])
					&& is_object($INTRANET_TOOLBAR)
					&& $arParams["INTRANET_TOOLBAR"]!=="N"
				)
				{
					$APPLICATION->AddHeadScript('/bitrix/js/main/utils.js');
					foreach($arButtons["intranet"] as $arButton)
						$INTRANET_TOOLBAR->AddButton($arButton);
				}

				if($arParams["SET_TITLE"])
				{
					$arTitleOptions = array(
						'ADMIN_EDIT_LINK' => $arButtons["submenu"]["edit_iblock"]["ACTION"],
						'PUBLIC_EDIT_LINK' => "",
						'COMPONENT_NAME' => $this->GetName(),
					);
				}
			}
		}
	}

	$this->SetTemplateCachedData($arResult["NAV_CACHED_DATA"]);

	if($arParams["SET_TITLE"])
	{
		if ($arResult["IPROPERTY_VALUES"] && $arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
			$APPLICATION->SetTitle($arResult["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arTitleOptions);
		elseif(isset($arResult["NAME"]))
			$APPLICATION->SetTitle($arResult["NAME"], $arTitleOptions);
	}

	if ($arResult["IPROPERTY_VALUES"])
	{
		if ($arParams["SET_BROWSER_TITLE"] === 'Y' && $arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"] != "")
			$APPLICATION->SetPageProperty("title", $arResult["IPROPERTY_VALUES"]["SECTION_META_TITLE"], $arTitleOptions);

		if ($arParams["SET_META_KEYWORDS"] === 'Y' && $arResult["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"] != "")
			$APPLICATION->SetPageProperty("keywords", $arResult["IPROPERTY_VALUES"]["SECTION_META_KEYWORDS"], $arTitleOptions);

		if ($arParams["SET_META_DESCRIPTION"] === 'Y' && $arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"] != "")
			$APPLICATION->SetPageProperty("description", $arResult["IPROPERTY_VALUES"]["SECTION_META_DESCRIPTION"], $arTitleOptions);
	}

	if($arParams["INCLUDE_IBLOCK_INTO_CHAIN"] && isset($arResult["NAME"]))
	{
		if($arParams["ADD_SECTIONS_CHAIN"] && is_array($arResult["SECTION"]))
			$APPLICATION->AddChainItem(
				$arResult["NAME"]
				,strlen($arParams["IBLOCK_URL"]) > 0? $arParams["IBLOCK_URL"]: $arResult["LIST_PAGE_URL"]
			);
		else
			$APPLICATION->AddChainItem($arResult["NAME"]);
	}

	if($arParams["ADD_SECTIONS_CHAIN"] && is_array($arResult["SECTION"]))
	{
		foreach($arResult["SECTION"]["PATH"] as $arPath)
		{
			if ($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != "")
				$APPLICATION->AddChainItem($arPath["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"], $arPath["~SECTION_PAGE_URL"]);
			else
				$APPLICATION->AddChainItem($arPath["NAME"], $arPath["~SECTION_PAGE_URL"]);
		}
	}

	return $arResult["ELEMENTS"];
}
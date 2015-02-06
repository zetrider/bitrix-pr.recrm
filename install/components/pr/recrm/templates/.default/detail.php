<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?$APPLICATION->IncludeComponent(
	"pr:recrm.detail",
	"",
	Array(
		"IBLOCK_TYPE"				=> $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"					=> $arParams["IBLOCK_ID"],
		"ELEMENT_ID"				=> $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE"				=> $arResult["VARIABLES"]["ELEMENT_CODE"],
		"DETAIL_PHOTOS"				=> $arParams["DETAIL_PHOTOS"],
		"DETAIL_MAP"				=> $arParams["DETAIL_MAP"],
		"DETAIL_VIDEO"				=> $arParams["DETAIL_VIDEO"],
		"DETAIL_AGENT"				=> $arParams["DETAIL_AGENT"],
		"DETAIL_TEXT"				=> $arParams["DETAIL_TEXT"],
		"DETAIL_PROPS_EMPTY"		=> $arParams["DETAIL_PROPS_EMPTY"],
		"DETAIL_PROPS"				=> $arParams["DETAIL_PROPS"],
		"DETAIL_URL" 				=> $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"IBLOCK_URL"				=> $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["list"],
		"AJAX_MODE"					=> $arParams["AJAX_MODE"],
		"AJAX_OPTION_JUMP"			=> $arParams["AJAX_OPTION_JUMP"],
		"AJAX_OPTION_STYLE"			=> $arParams["AJAX_OPTION_STYLE"],
		"AJAX_OPTION_HISTORY"		=> $arParams["AJAX_OPTION_HISTORY"],
		"CACHE_TYPE"				=> $arParams["CACHE_TYPE"],
		"CACHE_TIME"				=> $arParams["CACHE_TIME"],
		"CACHE_GROUPS"				=> $arParams["CACHE_GROUPS"],
		"SET_TITLE"					=> $arParams["SET_TITLE"],
		"SET_BROWSER_TITLE"			=> $arParams["SET_BROWSER_TITLE"],
		"BROWSER_TITLE"				=> $arParams["BROWSER_TITLE"],
		"SET_META_KEYWORDS"			=> $arParams["SET_META_KEYWORDS"],
		"META_KEYWORDS"				=> $arParams["META_KEYWORDS"],
		"SET_META_DESCRIPTION"		=> $arParams["SET_META_DESCRIPTION"],
		"META_DESCRIPTION"			=> $arParams["META_DESCRIPTION"],
		"SET_STATUS_404"			=> $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN"	=> $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_ELEMENT_CHAIN"			=> $arParams["ADD_ELEMENT_CHAIN"],
		"USE_PERMISSIONS"			=> $arParams["USE_PERMISSIONS"],
		"PAGER_TEMPLATE"			=> $arParams["DETAIL_PAGER_TEMPLATE"],
		"DISPLAY_TOP_PAGER"			=> $arParams["DETAIL_DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"		=> $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE"				=> $arParams["DETAIL_PAGER_TITLE"],
		"PAGER_SHOW_ALL"			=> $arParams["DETAIL_PAGER_SHOW_ALL"],
	),
	$component
);?>
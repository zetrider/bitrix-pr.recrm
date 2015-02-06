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
	"pr:recrm.list",
	"",
	Array(
		"IBLOCK_TYPE"						=> $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID"							=> $arParams["IBLOCK_ID"],
		"SORT_BY1"							=> $arParams["SORT_BY1"],
		"SORT_ORDER1"						=> $arParams["SORT_ORDER1"],
		"SORT_BY2"							=> $arParams["SORT_BY2"],
		"SORT_ORDER2"						=> $arParams["SORT_ORDER2"],
		"FILTER_NAME"						=> $arParams["FILTER_NAME"],
		"LIST_MAP_BIG"						=> $arParams["LIST_MAP_BIG"],
		"LIST_COUNT"						=> $arParams["LIST_COUNT"],
		"LIST_COVER_PHOTO"					=> $arParams["LIST_COVER_PHOTO"],
		"LIST_PROPS_EMPTY"					=> $arParams["LIST_PROPS_EMPTY"],
		"LIST_DESC"							=> $arParams["LIST_DESC"],
		"LIST_PROPS"						=> $arParams["LIST_PROPS"],
		"DETAIL_URL" 						=> $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"IBLOCK_URL" 						=> $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["list"],
		"AJAX_MODE"							=> $arParams["AJAX_MODE"],
		"AJAX_OPTION_JUMP"					=> $arParams["AJAX_OPTION_JUMP"],
		"AJAX_OPTION_STYLE"					=> $arParams["AJAX_OPTION_STYLE"],
		"AJAX_OPTION_HISTORY"				=> $arParams["AJAX_OPTION_HISTORY"],
		"CACHE_TYPE"						=> $arParams["CACHE_TYPE"],
		"CACHE_TIME"						=> $arParams["CACHE_TIME"],
		"CACHE_GROUPS"						=> $arParams["CACHE_GROUPS"],
		"SET_TITLE"							=> $arParams["SET_TITLE"],
		"SET_BROWSER_TITLE"					=> $arParams["SET_BROWSER_TITLE"],
		"SET_META_KEYWORDS"					=> $arParams["SET_META_KEYWORDS"],
		"SET_META_DESCRIPTION"				=> $arParams["SET_META_DESCRIPTION"],
		"SET_STATUS_404"					=> $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN"			=> $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"PAGER_TEMPLATE"					=> $arParams["PAGER_TEMPLATE"],
		"DISPLAY_TOP_PAGER"					=> $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"				=> $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE"						=> $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS"					=> $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING"				=> $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME"	=> $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL"					=> $arParams["PAGER_SHOW_ALL"],
	),
	$component
);?>
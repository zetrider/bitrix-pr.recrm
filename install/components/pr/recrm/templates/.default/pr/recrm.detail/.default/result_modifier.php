<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arParams['PHOTOS_EMPTY'] 	= $this->GetFolder().'/images/no_photo.png';
$arParams['AGENT_EMPTY'] 	= $this->GetFolder().'/images/no_photo.png';
$arParams['TEMPLATES_COL'] 	= 	($arParams['DETAIL_PHOTOS'] == "Y")
								OR ($arParams['DETAIL_VIDEO'] == "Y")
 								OR ($arParams['DETAIL_AGENT'] == "Y");
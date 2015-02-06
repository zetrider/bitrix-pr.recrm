<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME"				=> GetMessage("PR_RECRM_C_DETAIL_NAME"),
	"DESCRIPTION"		=> GetMessage("PR_RECRM_C_DETAIL_DESC"),
	"SORT"				=> 20,
	"CACHE_PATH"		=> "Y",
	"PATH"				=> array(
		"ID"			=> "pr",
		"NAME"			=> GetMessage("PR_NAME"),
		"CHILD"			=> array(
			"ID"		=> "pr_recrm",
			"NAME"		=> "ReCrm",
			"SORT"		=> 10,
			"CHILD"		=> array(
				"ID"	=> "pr_recrm",
			),
		),
	),
);
?>
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME"				=> GetMessage("PR_RECRM_NAME"),
	"DESCRIPTION"		=> GetMessage("PR_RECRM_DESCRIPTION"),
	"COMPLEX"			=> "Y",
	"PATH"				=> array(
		"ID"			=> "pr",
		"NAME"			=> GetMessage("PR_NAME"),
		"CHILD"			=> array(
			"ID"		=> "pr_recrm",
			"NAME"		=> GetMessage("PR_RECRM_PARENT_NAME"),
			"SORT"		=> 10,
			"CHILD"		=> array(
				"ID"	=> "pr_recrm",
			),
		),
	),
);
?>
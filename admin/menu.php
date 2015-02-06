<?
#################################################
#   Company developer: PrimeWeb                 #
#   Developer: Timur R. Kalimullin              #
#   Site: http://01pr.ru                        #
#   E-mail: mail@01pr.ru                        #
#   Copyright (c) 2015 PrimeWeb                 #
#################################################
?>
<?
IncludeModuleLangFile(__FILE__);
$aMenu = array(
	"parent_menu" 		=> "global_menu_services",
	"section" 			=> "pr.recrm",
	"sort" 				=> 100,
	"url" 				=> 'settings.php?lang='.LANGUAGE_ID.'&mid=pr.recrm',
	"text" 				=> GetMessage('PR_RECRM_MENU_TEXT'),
	"title" 			=> GetMessage('PR_RECRM_MENU_TITLE'),
	"icon" 				=> "pr_recrm_menu_icon",
	"page_icon" 		=> "pr_recrm_page_icon",
	"module_id" 		=> "pr.recrm",
	"items_id" 			=> "menu_pr_recrm",
);
return $aMenu;
?>
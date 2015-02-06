<?
#################################################
#   Company developer: PrimeWeb                 #
#   Developer: Timur R. Kalimullin              #
#   Site: http://01pr.ru                        #
#   E-mail: mail@01pr.ru                        #
#   Copyright (c) 2015 PrimeWeb                 #
#################################################
?>
<?if(!check_bitrix_sessid()) return;?>
<?
IncludeModuleLangFile(__FILE__);
echo CAdminMessage::ShowMessage(array(
	"MESSAGE" 			=> GetMessage("PR_RECRM_STEP"),
	"DETAILS" 			=> "#PROGRESS_BAR#",
	"HTML" 				=> true,
	"TYPE" 				=> "PROGRESS",
	"PROGRESS_TOTAL" 	=> 100,
	"PROGRESS_VALUE" 	=> 100,
));
?>
<form method="GET" action="<?echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>">
	<input type="submit" name="" value="<?=GetMessage("MOD_BACK")?>">
<form>
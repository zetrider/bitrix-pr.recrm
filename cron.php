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
define("PR_CRON", true);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define("NO_BITRIX_AUTOLOAD", true);
define("BX_NO_ACCELERATOR_RESET", true);

@ignore_user_abort(true);

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(3600);
ini_set('max_execution_time', 3600);

$module_id 	= "pr.recrm";

if (CModule::IncludeModule($module_id)) {
	$RECRM = new prReCrmData;
	$RECRM->cron();
}
?>
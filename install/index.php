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

Class pr_recrm extends CModule
{
	var $MODULE_ID = "pr.recrm";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $PARTNER_NAME;
	var $PARTNER_URI;

	public function pr_recrm()
	{
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path)-strlen("/index.php"));
		$arModuleVersion = array();

		include($path."/version.php");

		if (is_array($arModuleVersion) AND array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION 		= $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE 	= $arModuleVersion["VERSION_DATE"];
		}
		$this->MODULE_NAME 			= GetMessage("PR_RECRM_MODULE_NAME");
		$this->MODULE_DESCRIPTION 	= GetMessage("PR_RECRM_MODULE_DESC");
		$this->PARTNER_NAME 		= "PrimeWeb";
		$this->PARTNER_URI 			= "http://01pr.ru/";
	}
	public function InstallFiles()
	{
		CopyDirFiles($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".$this->MODULE_ID."/install/themes/", $_SERVER['DOCUMENT_ROOT']."/bitrix/themes/", true, true );
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/js/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/'.$this->MODULE_ID.'/install/components/', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/', true, true);
		return true;
	}
	public function UnInstallFiles()
	{
		DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/'.$this->MODULE_ID.'/install/themes/.default/' , $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/.default');
		DeleteDirFilesEx("/bitrix/themes/.default/icons/'.$this->MODULE_ID.'");
		DeleteDirFilesEx("/bitrix/js/'.$this->MODULE_ID.'");
		DeleteDirFilesEx("/bitrix/components/pr/recrm");
		DeleteDirFilesEx("/bitrix/components/pr/recrm.detail");
		DeleteDirFilesEx("/bitrix/components/pr/recrm.list");

		COption::RemoveOption($this->MODULE_ID);

		return true;
	}

	public function DoInstall()
	{
		global $DOCUMENT_ROOT, $APPLICATION;
		RegisterModule($this->MODULE_ID);
		$this->InstallFiles();
		$APPLICATION->IncludeAdminFile( GetMessage("PR_RECRM_INSTALL") . " " . $this->MODULE_ID, $DOCUMENT_ROOT."/bitrix/modules/".$this->MODULE_ID."/install/step.php");
		return true;
	}

	public function DoUninstall()
	{
		global $DOCUMENT_ROOT, $APPLICATION;
		UnRegisterModule($this->MODULE_ID);
		$this->UnInstallFiles();
		$APPLICATION->IncludeAdminFile( GetMessage("PR_RECRM_DEINSTALL") . " " . $this->MODULE_ID, $DOCUMENT_ROOT."/bitrix/modules/".$this->MODULE_ID."/install/unstep.php");
		return true;
	}
}
?>
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
$modulte_id = 'pr.recrm';

$arClasses = array(
	'prReCrmData' => 'classes/general/prReCrmData.class.php',
	'prReCrmProps' => 'classes/general/prReCrmProps.class.php',
);

if (method_exists(CModule, "AddAutoloadClasses"))
{
	CModule::AddAutoloadClasses($modulte_id, $arClasses);
}
else
{
	foreach ($arClasses AS $ClassFile)
	{
		require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/' . $modulte_id . '/' . $ClassFile);
	}
}
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
$module_id 	= "pr.recrm";
$RIGHT 		= $APPLICATION->GetGroupRight($module_id);
if($RIGHT >= "R"):

CModule::IncludeModule($module_id);
IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
CJSCore::Init(array("jquery"));

$APPLICATION->SetAdditionalCSS("/bitrix/js/pr.recrm/jquery-ui/jquery-ui.min.css");
$APPLICATION->AddHeadScript('/bitrix/js/pr.recrm/jquery-ui/jquery-ui.min.js');
$APPLICATION->AddHeadScript('/bitrix/js/pr.recrm/pr.recrm.js');

$RECRM 			= new prReCrmData;
$LAST_UPD 		= $RECRM->getLastUpdate();
$SELECT_T 		= $RECRM->getSelectTypes();
$SELECT_IB 		= $RECRM->getIB(true);
$CHECK_IB 		= $RECRM->checkIB();
$CHECK_KEY 		= $RECRM->checkKey();
$GET_KEY 		= $RECRM->getKey();
$TYPES 			= prReCrmProps::getTypes('name');
$CUR_MOD_PAGE 	= $APPLICATION->GetCurPage().'?lang='.LANGUAGE_ID.'&mid='.urlencode($module_id);
$ACTION 		= htmlspecialcharsEx($_GET['action']);
$STATUS 		= false;
$wLocation 		= '';
$wTime 			= 3000;
$error 			= array();

$arWRK 			= array('0' => GetMessage('PR_RECRM_N'), '1' => GetMessage('PR_RECRM_Y'));
$arSH 			= array('0' => GetMessage('PR_RECRM_N'), '1' => GetMessage('PR_RECRM_Y'));
$arCROP 		= array('0' => GetMessage('PR_RECRM_N'), '1' => GetMessage('PR_RECRM_Y'));

$arAllOptions [] = array("pr_recrm_key",			GetMessage("PR_RECRM_F_KEY"),			array("text"), 					GetMessage("PR_RECRM_F_KEY_NOTE"));
$arAllOptions [] = array("pr_recrm_s_step",			GetMessage("PR_RECRM_F_S_STEP"),		array("text"), 					GetMessage("PR_RECRM_F_S_STEP_NOTE"));
$arAllOptions [] = array("pr_recrm_img_w",			GetMessage("PR_RECRM_F_IMG_W"),			array("text"), 					GetMessage("PR_RECRM_F_IMG_W_NOTE"));
$arAllOptions [] = array("pr_recrm_img_h",			GetMessage("PR_RECRM_F_IMG_H"),			array("text"), 					GetMessage("PR_RECRM_F_IMG_H_NOTE"));
$arAllOptions [] = array("pr_recrm_img_crop",		GetMessage("PR_RECRM_F_IMG_CROP"),		array("selectbox",$arCROP), 	GetMessage("PR_RECRM_F_IMG_CROP_NOTE"));
$arAllOptions [] = array("pr_recrm_img_wrk",		GetMessage("PR_RECRM_F_IMG_WRK"),		array("selectbox",$arWRK), 		GetMessage("PR_RECRM_F_IMG_WRK_NOTE"));
$arAllOptions [] = array("pr_recrm_search_hidden",	GetMessage("PR_RECRM_SEARCH_HIDDEN"),	array("selectbox",$arSH), 		GetMessage("PR_RECRM_SEARCH_HIDDEN_NOTE"));
$arAllOptions [] = array("pr_recrm_types",			GetMessage("PR_RECRM_F_TYPES_SELECT"),	array("multiple", $TYPES), 		GetMessage("PR_RECRM_F_TYPES_SELECT_NOTE"));
$arAllOptions [] = array("pr_recrm_d_rep",			GetMessage("PR_RECRM_F_DESC_REP"),		array("textarea", "5", "40"), 	GetMessage("PR_RECRM_F_DESC_REP_NOTE"));
$arAllOptions [] = array("pr_recrm_last_upd",		GetMessage("PR_RECRM_F_LAST_UPD"),		array("text"), 					GetMessage("PR_RECRM_F_LAST_UPD_NOTE"));
foreach($TYPES AS $TYPE_k => $TYPE_v)
{
	$arAllOptions[] = array(
		'pr_recrm_ib_' . $TYPE_k,
		GetMessage("PR_RECRM_F_TYPES") . ' "' . $TYPE_v . '"',
		array("selectbox", $SELECT_IB),
		GetMessage("PR_RECRM_F_TYPES_NOTE") . ' "' . $TYPE_v . '"',
	);
}

$aTabs = array(
	array("DIV" => "index", 		"TAB" => GetMessage("PR_RECRM_TAB_STAT_NAME"), 			"TITLE" => GetMessage("PR_RECRM_TAB_STAT_NAME")),
	array("DIV" => "setting", 		"TAB" => GetMessage("MAIN_TAB_SET"), 					"TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
	array("DIV" => "properties", 	"TAB" => GetMessage("PR_RECRM_TAB_PROPERTIES_NAME"), 	"TITLE" => GetMessage("PR_RECRM_TAB_PROPERTIES_TITLE")),
	array("DIV" => "cron", 			"TAB" => GetMessage("PR_RECRM_TAB_CRON_NAME"), 			"TITLE" => GetMessage("PR_RECRM_TAB_CRON_NAME")),
	array("DIV" => "access", 		"TAB" => GetMessage("MAIN_TAB_RIGHTS"), 				"TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

/* Update Options */
if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT=="W" && check_bitrix_sessid())
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/perfmon/prolog.php");
	if (isset($_REQUEST["RestoreDefaults"]))
	{
		COption::RemoveOption($module_id);
	}
	else
	{
		foreach ($arAllOptions as $arOption)
		{
			$name 	= $arOption[0];
			if(is_array($_REQUEST[$name]))
			{
				$val = serialize($_REQUEST[$name]);
			}
			else
			{
				$val = trim($_REQUEST[$name], " \t\n\r");
				if ($arOption[2][0] == "checkbox" && $val != "Y")
				{
					$val = "N";
				}
			}
			COption::SetOptionString($module_id, $name, $val, $arOption[1]);
		}
		
		if(count($_REQUEST['IB_P_NAME'] > 0))
		{
			$IB_P_NAME = $_REQUEST['IB_P_NAME'];
			$IB_P_HINT = $_REQUEST['IB_P_HINT'];
			$IB_P_SORT = $_REQUEST['IB_P_SORT'];
			
			foreach($IB_P_NAME AS $IB_P_K => $IB_P_V)
			{
				$iBp = new CIBlockProperty;
				$iBp->Update($IB_P_K, Array(
					"NAME" => $IB_P_NAME[$IB_P_K],
					"HINT" => $IB_P_HINT[$IB_P_K],
					"SORT" => $IB_P_SORT[$IB_P_K],
				));
			}			
		}
	}
	
	ob_start();
	$Update = $Update.$Apply;
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
	ob_end_clean();
	
	if(strlen($_REQUEST["back_url_settings"]) > 0)
	{
		if((strlen($Apply) > 0) OR (strlen($RestoreDefaults) > 0))
		{
			LocalRedirect($CUR_MOD_PAGE."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
		}
		else
		{
			LocalRedirect($_REQUEST["back_url_settings"]);
		}
	}
	else
	{
		LocalRedirect($CUR_MOD_PAGE."&".$tabControl->ActiveTabParam());
	}
}

if($RIGHT=="W")
{

	if(count($SELECT_T) == 0) /* Проверка выбранных типов */
	{
		$error = array('progress' => '10', 'message' => GetMessage("PR_RECRM_ERR_NOT_SELECT_TYPES"));
	}
	elseif(count($CHECK_IB) > 0) /* Проверка инфоблоков*/
	{
		$error = array('progress' => '30', 'message' => GetMessage("PR_RECRM_ERR_NOT_IB_TYPES") . ": " . implode(',',$CHECK_IB));
	}
	elseif($GET_KEY == '') /* Проверка ключа для ReCrm */
	{
		$error = array('progress' => '60', 'message' => GetMessage("PR_RECRM_ERR_NOT_FOUND_KEY"));
	}
	elseif($CHECK_KEY === false) /* Проверка указанного ключа для ReCrm */
	{
		$error = array('progress' => '95', 'message' => GetMessage("PR_RECRM_ERR_BAD_KEY"));
	}
	
	/* Импорт из ReCrm */	
	if($ACTION == 'import_el' AND count($error) == 0)
	{
		$STATUS = $RECRM->importIBEl(0, 0, $_GET['start']);
		//var_dump($STATUS);
		
		if($STATUS === true)
		{
			$wLocation 	= $CUR_MOD_PAGE.'&action=success#sc';
			$wTime 		= 0;
		}
		elseif(is_array($STATUS))
		{				
			$wLocation = $CUR_MOD_PAGE.'&action=import_el&types='.implode(',', $STATUS['IBT']).'&rand='.time().'&step='.$STATUS['STEP'].'#sc';
		}
	}
	
	if($wLocation != '')
	{
		echo '
		<script type="text/javascript">
		function DoNext()
		{
			window.location="'.$wLocation.'";
		}
		setTimeout(\'DoNext()\', '.$wTime.');
		</script>
		';
	}
}
?>

<h1><?=GetMessage("PR_RECRM_MODULE_NAME"); ?></h1>
<form method="post" action="<?=$CUR_MOD_PAGE; ?>" id="sc">
<?
/* Tab Begin */
$tabControl->Begin();

/* Tab Index */
$tabControl->BeginNextTab();

	if(count($error) > 0) /* Ошибки */
	{
		echo CAdminMessage::ShowMessage(array(
			"MESSAGE" 			=> $error['message'],
			"DETAILS" 			=> "#PROGRESS_BAR#",
			"HTML" 				=> true,
			"TYPE" 				=> "PROGRESS",
			"PROGRESS_TOTAL" 	=> 100,
			"PROGRESS_VALUE" 	=> $error['progress'],
		));
	}
	else
	{
		if(is_array($STATUS)) /* Step Msg */
		{
			echo '<p>'.GetMessage("PR_RECRM_IMP_STEP").' '.$STATUS['STEP'].'.<br><br> '.$STATUS['MESS'].'</p><br><div class="pr_loader"></div>';
			echo CAdminMessage::ShowMessage(GetMessage('PR_RECRM_IMP_STEP_NOTE'));
		}
		else
		{
			if($ACTION == 'success') /* Import Success */
			{
				echo CAdminMessage::ShowMessage(array(
					"MESSAGE" 			=> GetMessage('PR_RECRM_IMP_SUCCESS'),
					"DETAILS" 			=> "#PROGRESS_BAR#",
					"HTML" 				=> true,
					"TYPE" 				=> "PROGRESS",
					"PROGRESS_TOTAL" 	=> 100,
					"PROGRESS_VALUE" 	=> 100,
				));
			}
			else /* Start Import Btn */
			{
				echo '<a href="'.$CUR_MOD_PAGE.'&action=import_el&types='.implode(',', $SELECT_T).'&rand='.time().'&start=Y#sc" class="adm-btn adm-btn-save adm-btn-add">'.GetMessage('PR_RECRM_BTN_IMPORT').'</a><br>';
			}
			/* Last Upd */
			echo '
				<h3>'.GetMessage('PR_RECRM_STAT_LIST').':</h3>
				<ul>
					<li>'.GetMessage('PR_RECRM_STAT_LAST_UPD').': '.(($LAST_UPD == 0) ? GetMessage('PR_RECRM_STAT_LAST_UPD_NULL') : date('d.m.Y - H:i:s', $LAST_UPD)) . '</li>
				</ul>
			';
		}
	}
	/* AD */
	echo '
<div class="pr_recrm_logo">
	<a href="http://recrm.ru/" class="recrm" target="_blank"></a>
	<div class="r">
		<a href="http://01pr.ru/solutions/recrm-import/?utm_source=bitrix.admin.site&utm_medium=modules&utm_campaign=pr.recrm" class="primeweb" target="_blank"></a>
	</div>
</div>
	';

/* Tab Setting */
$tabControl->BeginNextTab();
	
	$arNotes = array();
	foreach($arAllOptions as $arOption)
	{
		$val 	= COption::GetOptionString($module_id, $arOption[0]);
		$type 	= $arOption[2];
		if(isset($arOption[3])) $arNotes[] = $arOption[3];
?>
	<tr>
		<td width="40%" nowrap<?=$type[0] == "textarea" ? ' class="adm-detail-valign-top"' : ''?>>
			<?=isset($arOption[3]) ? '<span class="required"><sup>'.count($arNotes).'</sup></span>' : ''?>
			<label for="<?=htmlspecialcharsbx($arOption[0])?>"><?=$arOption[1]?>:</label>
		<td width="60%">
			<?
			if($type[0] == "checkbox")
			{
			?>
				<input type="checkbox" name="<?=htmlspecialcharsbx($arOption[0])?>" id="<?=htmlspecialcharsbx($arOption[0])?>" value="Y"<?=$val == "Y" ? " checked" : ""?>>
			<?
			}
			elseif($type[0] == "text")
			{
			?>
				<input type="text" size="<?=$type[1]?>" maxlength="255" value="<?=htmlspecialcharsbx($val)?>" name="<?=htmlspecialcharsbx($arOption[0])?>" id="<?=htmlspecialcharsbx($arOption[0])?>">
				<?=$arOption[0] == "slow_sql_time"? GetMessage("PERFMON_OPTIONS_SLOW_SQL_TIME_SEC") : ''; ?>
				<?=$arOption[0] == "large_cache_size"? GetMessage("PERFMON_OPTIONS_LARGE_CACHE_SIZE_KB") : ''; ?>
			<?
			}
			elseif($type[0]=="textarea")
			{
			?>
				<textarea rows="<?=$type[1]?>" cols="<?=$type[2]?>" name="<?=htmlspecialcharsbx($arOption[0])?>" id="<?=htmlspecialcharsbx($arOption[0])?>"><?=htmlspecialcharsbx($val)?></textarea>
			<?
			}
			elseif($type[0]=="selectbox")
			{
			?>
				<select name="<?=htmlspecialcharsbx($arOption[0])?>">
				<?
				foreach($type[1] AS $key => $value)
				{
					echo '<option value="'.htmlspecialcharsbx($key).'"'.(($key==$val) ? ' selected="selected"' : '').'>'.htmlspecialcharsEx($value).'</option>';
				}
				?>
				</select>
			<?
			}
			elseif($type[0]=="multiple")
			{
				$valStr = COption::GetOptionString($module_id, $arOption[0]);
				$valArr = $valStr == '' ? array() : unserialize($valStr);
			?>
				<select name="<?=htmlspecialcharsbx($arOption[0])?>[]" multiple size="10">
				<?
				foreach($type[1] AS $key => $value)
				{
					echo '<option value="'.htmlspecialcharsbx($key).'"'.((in_array($key, $valArr)) ? ' selected="selected"' : '').'>'.htmlspecialcharsEx($value).'</option>';
				}
				?>
				</select>
			<?
			}
			?>
		</td>
	</tr>
	<? } ?>
	
<?
/* Tab Properties */
$tabControl->BeginNextTab();
?>
<p><?=GetMessage("PR_RECRM_PROPS_DESC")?></p>

<?
	if(count($error) > 0)
	{
		echo CAdminMessage::ShowMessage(array(
			"MESSAGE" 			=> $error['message'],
			"DETAILS" 			=> "#PROGRESS_BAR#",
			"HTML" 				=> true,
			"TYPE" 				=> "PROGRESS",
			"PROGRESS_TOTAL" 	=> 100,
			"PROGRESS_VALUE" 	=> $error['progress'],
		));
	}
	else
	{
		echo '<ul class="pr_sortable">';
		$PROPS_ESTATE 	= CIBlock::GetProperties($RECRM->getIBId('estate'), Array('SORT'=>'ASC'), Array());
		while ($P = $PROPS_ESTATE->Fetch())
		{
			echo '
			<li id="'.$P['ID'].'">
			'.GetMessage("PR_RECRM_PROPS_NAME").': <input type="text" name="IB_P_NAME['.$P['ID'].']" value="'.htmlspecialcharsEx($P['NAME']).'"/>
			'.GetMessage("PR_RECRM_PROPS_HINT").': <input type="text" name="IB_P_HINT['.$P['ID'].']" value="'.htmlspecialcharsEx($P['HINT']).'"/>
			'.GetMessage("PR_RECRM_PROPS_SORT").': <input type="text" name="IB_P_SORT['.$P['ID'].']" class="s_sort" value="'.intval($P['SORT']).'" style="width: 20px"/>
			</li>';
		}
		echo '</ul>';
	}
?>

<?
/* Tab Cron */
$tabControl->BeginNextTab();

echo '<div class="pr_cron"></div>'.GetMessage('PR_RECRM_CRON_DESC');

/* Tab Rights */
$tabControl->BeginNextTab();

	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
	$tabControl->Buttons();
?>
	<input <?=$RIGHT<"W"?" disabled ":''?>type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input <?=$RIGHT<"W"?" disabled ":''?>type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<?
	if(strlen($_REQUEST["back_url_settings"])>0)
	{
	?>
		<input <?=$RIGHT<"W"?"disabled ":""?>type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?=htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?
	}
	?>
	<input type="submit" name="RestoreDefaults" title="<?=GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" onclick="return confirm('<?=AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?=GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
<?$tabControl->End();?>
</form>
<?
	if(!empty($arNotes))
	{
		echo BeginNote();
		foreach($arNotes as $i => $str)
		{
			$i++;
			echo '<span class="required"><sup>'. $i .'</sup></span>'.$str.'<br>';
		}
		echo EndNote();
	}
	
endif;
?>
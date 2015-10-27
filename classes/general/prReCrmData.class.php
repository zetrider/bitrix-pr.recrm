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
class prReCrmData
{
	
    static $module_id = "pr.recrm";
	
	/* Ключ */
	public function getKey()
	{
		return COption::GetOptionString(self::$module_id, 'pr_recrm_key');
	}
	
	/* Что выбрано для выгрузки */
	public function getSelectTypes()
	{
		$types = COption::GetOptionString(self::$module_id, 'pr_recrm_types');
		return $types == '' ? array() : unserialize($types);
	}
	
	/* Дата обновления */
	public function getLastUpdate()
	{
		return COption::GetOptionString(self::$module_id, 'pr_recrm_last_upd');
	}
	
	/* Старт обновления */
	public function getStartUpdate()
	{
		return COption::GetOptionString(self::$module_id, 'pr_recrm_start_upd');
	}
	
	/* Шаг */
	public function getStep()
	{
		$gStep = COption::GetOptionString(self::$module_id, 'pr_recrm_step');
		return $gStep + 1;
	}
	
	/* Время шага */
	public function getStepTime()
	{
		$gTime = COption::GetOptionString(self::$module_id, 'pr_recrm_s_step');
		if($gTime == '') return 30;
		if($gTime < 0) return 0;
		
		return intval($gTime);
	}
	
	/* Размеры для превью */
	public function getCoverSize()
	{
		$width 	= intval(COption::GetOptionString(self::$module_id, 'pr_recrm_img_w'));
		$height = intval(COption::GetOptionString(self::$module_id, 'pr_recrm_img_h'));
		return array(
			'width' 	=> (($width <= 0) ? 200 : $width),
			'height' 	=> (($height <= 0) ? 200 : $height)
		);
	}
	
	/* Кадрирование главного фото */
	public function getCrop()
	{
		return intval(COption::GetOptionString(self::$module_id, 'pr_recrm_img_crop'));
	}
	
	/* Водный знак */
	public function getWRK()
	{
		return intval(COption::GetOptionString(self::$module_id, 'pr_recrm_img_wrk'));
	}
	
	/* Выгружать скрытые объекты */
	public function getSH()
	{
		return intval(COption::GetOptionString(self::$module_id, 'pr_recrm_search_hidden'));
	}
	
	/* Выгружать скрытые объекты */
	public function getTZ()
	{
		$return = COption::GetOptionString(self::$module_id, 'pr_recrm_timezone');
		if(strlen($return) == 0) $return = 'Europe/Moscow';
		return $return;
	}
	
	/* Что вырезать из описания объекта */
	public function getDescStr()
	{
		return COption::GetOptionString(self::$module_id, 'pr_recrm_d_rep');
	}
	
	/* ID ИБ по ключам */
	public function getIBId($key = '')
	{
		return intval( COption::GetOptionString(self::$module_id, 'pr_recrm_ib_'.$key) );
	}
	
	/* Крон */
	public function cron()
	{
		//AddMessage2Log('Запуск крона');
		$this->importIBEl('Y', 0, 'Y');
	}
	
	/* Список всех ИБ */
	public function getIB($first = false) {
		$return 	= array();
		if(CModule::IncludeModule("iblock"))
		{
			if($first) $return[0] = '---';
			$query 		= CIBlock::GetList(array(), array('ACTIVE'=>'Y'));
			while($res 	= $query->Fetch())
			{
				$return[$res['ID']] = $res['NAME'];
			}
		}
		return $return;
	}
	
	/* Список сайтов */
	public function getSiteIDs()
	{
		if(CModule::IncludeModule("iblock"))
		{
			$res 	= array();
			$sites 	= CSite::GetList();
			while ($arr = $sites->Fetch())
			{
				$res[] = $arr["ID"];
			}
			return $res;
		}
	}
	
	/* Массив массиву */
	public function is_a($a = '', $check = false)   
	{
		$arr = is_array($a) ? $a : array();

		if($check) return array_diff($arr, array(''));

		return $arr;
	}
	
	/* Все свойства инфоблока */
	public function getIBAllProps($type = '')
	{
		$ARR 	= array();
		$IB_ID 	= $this->getIBId($type);
		$PROPS 	= CIBlock::GetProperties($IB_ID, Array('SORT'=>'ASC'), Array());
		while ($PROP = $PROPS->Fetch())
		{
			$ARR[] = $PROP['CODE'];
		}
		return $ARR;
	}
	
	/* Конвертируем фомар, TODO: проверить на utf */
	public function convertUtoW($a = '')   
	{
		if(is_array($a))
		{
			return array_map( array('prReCrmData', 'convertUtoW'), $a );
		}
		else
		{
			if(is_bool($a))
				return $a;
			
			global $APPLICATION;
			return $APPLICATION->ConvertCharset($a, 'utf-8', LANG_CHARSET);
			//return iconv('utf-8', 'windows-1251', $a);
		}
	}
	
	/* Запрос к API */
	public function getJson($type = '', $params = array())
	{
		$key 	= $this->getKey();
		$host 	= 'http://api.recrm.ru/json/';
		$sect 	= prReCrmProps::getTypes('url');
		$p_key 	= array('key' => $key);
		$p_opt 	= $this->is_a($params);
		$p_arr 	= array_merge($p_key, $p_opt);
		$vars 	= http_build_query($p_arr);
		$url 	= $host . $sect[$type];
		$ch 	= false;

		if(is_callable('curl_init'))
			$ch	= curl_init();
		
		if ($ch)
		{
			if($type == 'contragentsearch'):
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
			else:
				$url = $url . '?' . $vars;
			endif;
			
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			$data = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			if($type == 'contragentsearch'):
				$context = stream_context_create(array(
					"http" => array(
						"method" 	=> "POST",
						"header" 	=> "Content-type: application/x-www-form-urlencoded",
						"content" 	=> $vars,
					)
				));
			else:
				$context 	= NULL;
				$url 		= $url . '?' . $vars;
			endif;
			
			$data = file_get_contents($url, false, $context);
		}
		
		$j_arr 	= json_decode($data, true);

		if(SITE_CHARSET == "UTF-8"):
			$result = $j_arr;
		else:
			$result = array_map(array('prReCrmData', 'convertUtoW') , $j_arr);
		endif;

		return $result;
	}
	
	/* Проверка API Key */
	public function checkKey()
	{
		$q = $this->getJson('key');
		if(!is_array($q)) return false;
		if(array_key_exists('error', $q)) return false;
		
		return true;
	}
	
	/* Проверка выбранных ИБ */
	public function checkIB()
	{
		$ERR = array();
		$IBs = $this->getIB();
		foreach($this->getSelectTypes() AS $TYPE)
		{
			$IB_ID = $this->getIBId($TYPE);
			if($IB_ID == 0 OR $IBs[$IB_ID] == '')
				$ERR [] = '"'.prReCrmProps::getTypes('name', $TYPE).'"';
		}
		return $ERR;
	}
	
	/* Удаляем из массива массив */
	public function unsetArrEl($arr = array(), $del = array()) {
		foreach($del AS $k => $v)
		{
			if( isset($arr[$k]))
			{
				unset($arr[$k]);
			}
		}
		return $arr;
	}
	
	/* Элементы ИБ */
	public function getEliB($type = '') {
		if(CModule::IncludeModule("iblock"))
		{
			$iblock = $this->getIBId($type);
			$arr 		= array('id_recrm' => array(), 'id_btrx' => array());			
			$arFilter 	= array("IBLOCK_ID" => $iblock);
			$arSelect 	= array("ID", "PROPERTY_id");
			$res 		= CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
			while($ob = $res->GetNextElement())
			{
				$arF 						= $ob->GetFields();
				$id_crm 					= $arF['PROPERTY_ID_VALUE'];
				$arr['id_recrm'][$id_crm] 	= $id_crm;
				$arr['id_btrx'][$id_crm] 	= $arF['ID'];
			}
			return $arr;
		}
	}
	
	/* Формируем массив IDs из json: ID = ID из ReCrm */
	public function convertArrCheck($TYPE = '', $json = array()) {
		$new 	= array();
		$arr 	= prReCrmProps::getTypes('arr');
		if(is_array($json) AND count($json) > 0)
		{
			foreach($json[$arr[$TYPE]] AS $v)
			{
				$new [$v['id']] = $v['id'];
			}
		}
		return $new;
	}
	
	/* Проверка свойст ИБ */
	public function checkIBProps($TYPE = '', $PROPS = array())
	{
		$NEW_PROP = array();
		$IB_PROPS = $this->getIBAllProps($TYPE);
		if($TYPE == 'estate')
		{
			$PROPS['estatecoverphoto'] = array(
				'key' 	=> 'estatecoverphoto',
				'name' 	=> $this->convertNameProp('estatecoverphoto'),
				'type' 	=> 'S',
				'hint' 	=> '',
				'value' => '',
			);
			$PROPS['estatephoto'] = array(
				'key' 	=> 'estatephoto',
				'name' 	=> $this->convertNameProp('estatephoto'),
				'type' 	=> 'S',
				'hint' 	=> '',
				'value' => '',
				'multiple' => 'Y',
			);
			$PROPS['estatephotolayout'] = array(
				'key' 	=> 'estatephotolayout',
				'name' 	=> $this->convertNameProp('estatephotolayout'),
				'type' 	=> 'S',
				'hint' 	=> '',
				'value' => '',
				'multiple' => 'Y',
			);
			$PROPS['edit_date'] = array(
				'key' 	=> 'edit_date',
				'name' 	=> $this->convertNameProp('edit_date'),
				'type' 	=> 'S',
				'hint' 	=> '',
				'value' => '',
			);
			$PROPS['edit_datetime'] = array(
				'key' 	=> 'edit_datetime',
				'name' 	=> $this->convertNameProp('edit_datetime'),
				'type' 	=> 'S',
				'hint' 	=> '',
				'value' => '',
			);
		}
		elseif($TYPE == 'agent')
		{
			$PROPS['photo'] = array(
				'key' 	=> 'photo',
				'name' 	=> $this->convertNameProp('photo'),
				'type' 	=> 'S',
				'hint' 	=> '',
				'value' => '',
			);
		}
		foreach($PROPS AS $k => $v)
		{
			if(!in_array($v['key'], $IB_PROPS))
			{
				$NEW_PROP[$k] = $v;
			}
		}
		if(count($NEW_PROP) > 0)
		{
			$IBLOCK_ID = $this->getIBId($TYPE);
			foreach($NEW_PROP AS $K => $V)
			{
				if(trim($V['name']) == '')
					$V['name'] = $K;
				
				$propFields = Array(
					"NAME" 			=> $V['name'],
					"ACTIVE" 		=> "Y",
					"SORT" 			=> '500',
					"CODE" 			=> $K,
					"MULTIPLE" 		=> (($V['multiple'] == '') ? 'N' : 'Y'),
					"HINT" 			=> $V['hint'],
					"PROPERTY_TYPE" => $V['type'],
					"IBLOCK_ID" 	=> $IBLOCK_ID,
				);
				if($V['type'] == 'L')
				{
					$propFields["VALUES"] = $V['list'];
				}
				$insert_ibp = new CIBlockProperty;
				$insert_ibp->Add($propFields);
			}
		}
	}
	
	/* Код свойства */
	public function convertKeyProp($key = '') {
		$key = strtolower($key);
		$key = preg_replace("/[^a-z0-9_]/", "", $key);
		return $key;
	}
	
	/* Имя свойства */
	public function convertNameProp($key = '') {
		return prReCrmProps::getPropsNames($key);
	}
	
	/* Переформируем массив для импорта */
	public function convertArrImport($type = '', $a = array()) {
		$n 	= array();
		$p 	= array();
		$r 	= prReCrmProps::getTypes('arr', $type);
		if(is_array($a) AND count($a) > 0)
		{
			if($type == 'estate')
			{
				if(is_array($a[$r]['parameters']))
				{
					foreach($a[$r]['parameters'] AS $par)
					{
						$k = $this->convertKeyProp($par['name']);
						$p[$k] = array(
							'key' 	=> $k,
							'name' 	=> $par['title'],
							'type' 	=> ((is_numeric($par['value'])) ? 'N' : 'S'),
							'hint' 	=> $par['unit'],
							'value' => $par['value'],
						);
					}
				}
				unset($a[$r]['parameters']);
				
				foreach($a[$r] AS $a_k => $a_v)
				{
					$k = $this->convertKeyProp($a_k);
					$p[$k] = array(
						'key' 	=> $k,
						'name' 	=> $this->convertNameProp($k),
						'type' 	=> ((is_numeric($a_v)) ? 'N' : 'S'),
						'hint' 	=> '',
						'value' => $a_v,
					);
				}
				
				$n[$a[$r]['id']] = $p;
			}
			elseif($r == 'pictures')
			{
				foreach($a[$r] AS $a_k => $a_v)
				{

					$n [$a_k] = $a_v['url'];
					/*
					if( ($strPos = mb_strpos($imgUrl, "?") ) !== false ):
						$n [$a_k] = substr($imgUrl, 0, $strPos);
					else:
						$n [$a_k] = $imgUrl;
					endif;
					*/
				}
			}
			else
			{
				foreach($a[$r] AS $a_a)
				{
					foreach($a_a AS $a_k => $a_v)
					{
						$k = $this->convertKeyProp($a_k);
						$p[$k] = array(
							'key' 	=> $k,
							'name' 	=> $this->convertNameProp($k),
							'type' 	=> ((is_numeric($a_v)) ? 'N' : 'S'),
							'hint' 	=> '',
							'value' => $a_v,
						);
					}
					$n [$a_a['id']] = $p;
				}
			}
		}
		return $n;
	}
	
	/* Правим данные */
	public function dataChange($key = '', $val = '')
	{
		if(in_array($key, array('creation_date', 'creation_datetime', 'edit_date', 'edit_datetime')))
		{
			return strtotime($val);
		}
		elseif(is_bool($val))
		{
			if($val === true)
				return 'true';
			
			if($val === false)
				return 'false';
		}
		else
		{
			return $val;
		}
	}
	
	/* ... */
	public function tmpDb($arr = array())
	{
		$arr 	= $this->is_a($arr);
		$str 	= serialize($arr);
		$root 	= realpath(dirname(__FILE__)."/../../../../..");
		$f 		= fopen ($root . "/upload/recrm.txt","w+");
		fputs ($f, $str);
		fclose ($f);
	}
	
	/* Выбирам данные для импорта */
	public function arrIDs($TYPE = '', $STEP = 0)
	{
		if($STEP > 1)
		{
			$root 	= realpath(dirname(__FILE__)."/../../../../..");
			$FILE 	= $root . "/upload/recrm.txt";
			$fh 	= file_get_contents($FILE);
			return unserialize($fh);
		}
		
		$return 		= array();
		$json_params 	= array();
		$json_type 		= $TYPE == 'estate' ? 'estatesearch' : $TYPE;		
		
		if($TYPE == 'estate')
			$json_params 	= array('search_hidden' => $this->getSH(), 'start' => '0', 'count' => '10000000');
		
		/* Из CRM */
		$json_res 		= $this->getJson($json_type, $json_params);
		$ReCrmIDs 		= $this->convertArrCheck($json_type, $json_res);
		$ReCrmIDsUpd 	= $ReCrmIDs;
		
		/* Для измененных объектов */
		$UPD_TIME = $this->getLastUpdate();
		if($UPD_TIME > 0 AND $TYPE == 'estate')
		{
			date_default_timezone_set($this->getTZ());
			$json_params ['date_from'] 	= date('j.m.Y H:i', $UPD_TIME);
			$json_res_upd 				= $this->getJson($json_type, $json_params);
			$ReCrmIDsUpd 				= $this->convertArrCheck($json_type, $json_res_upd);
		}
		
		/* Объекты из БД */
		$DBArr 	= $this->getEliB($TYPE);
		
		/* Ищем элементы, которые нужно удалить, которых нет в CRM но есть в БД */
		$DEL 	= array_diff_assoc($DBArr['id_recrm'], $ReCrmIDs);
		
		/* Ищем элементы, которые нужно добавить, которых нет в БД но есть в CRM */
		$NEW 	= array_diff_assoc($ReCrmIDs, $DBArr['id_recrm']);
		
		/* Элементы, которые нужно обновить, убираем {удалить + новые} из массива ID Recrm */
		$UPD_U 	= $DEL + $NEW;
		$UPD 	= $this->unsetArrEl($ReCrmIDsUpd, $UPD_U);
		
		$return['DEL'] 	= $this->is_a($DEL, true);
		$return['NEW'] 	= $this->is_a($NEW, true);
		$return['UPD'] 	= $this->is_a($UPD, true);

		if($STEP == '1')
		{
			$this->tmpDb($return);

			/* Чтобы не пропустить объекты обновленные во время выгрузки */
			COption::SetOptionString(self::$module_id, 'pr_recrm_last_upd', time());
		}
		return $return;
	}
	
	/* Импорт */
	public function importIBEl($CRON = 0, $TYPES_ARR = array(), $START = 'N')
	{
		if($START == 'Y'):
			COption::SetOptionString(self::$module_id, 'pr_recrm_step', 0);
			COption::SetOptionString(self::$module_id, 'pr_recrm_start_upd', time());
		endif;
		
		CModule::IncludeModule("iblock");
		
		$STEP = $this->getStep();
		COption::SetOptionString(self::$module_id, 'pr_recrm_step', $STEP);
		
		if(!is_array($TYPES_ARR) AND $CRON === 'Y')
		{
			$TYPES_ARR = array();
			foreach($this->getSelectTypes() AS $T_V)
			{
				$TYPES_ARR [$T_V] = $T_V;
			}
		}
		
		$TYPES_G 	= is_array($TYPES_ARR) ? $TYPES_ARR : explode(',',$_GET['types']);
		$TYPES_G 	= array_filter($TYPES_G);
		$TYPES 		= $TYPES_G;
		
		//AddMessage2Log('Получены типы '.implode(', ',$TYPES_G));
		
		if(count($TYPES) == 0)
		{
			/* Callback Finish */
			$rsHandlers = GetModuleEvents(self::$module_id, "OnAfterImport");
			while($arHandler = $rsHandlers->Fetch())
			{
				ExecuteModuleEvent($arHandler);
			}
			//AddMessage2Log('Выгрузка завершена');
			COption::SetOptionString(self::$module_id, 'pr_recrm_start_upd', 0);
			COption::SetOptionString(self::$module_id, 'pr_recrm_step', '0');
			return true;
		}
		else
		{
			$TYPE 		= array_shift($TYPES);
			$arrIDs 	= $this->arrIDs($TYPE, $STEP);
			$el_import 	= $arrIDs['DEL'] + $arrIDs['NEW'] + $arrIDs['UPD'];
			$el_count 	= count($el_import);
			
			//AddMessage2Log('Начал выгружать '.$TYPE . ' количество ' . count($el_import));
			if($el_count == 0)
			{
				//AddMessage2Log('В типе '.$TYPE . ' больше нет объектов, вызываем метод выгрузки');
				COption::SetOptionString(self::$module_id, 'pr_recrm_step', '0');
				if($CRON === 'Y')
				{
					$this->importIBEl('Y', $TYPES);
				}
				else
				{
					return $this->importIBEl(0, $TYPES);
				}
			}
			else
			{
				$IBLOCK_ID 		= $this->getIBId($TYPE);
				$el_db 			= $this->getEliB($TYPE);
			}
			
			$StepTime = $this->getStepTime(); // Сколько шаг
			foreach($el_import AS $el_k)
			{
				if(intval($el_k) == 0) continue;
				
				/* Выход из цикла по времени */
				$LeftTime = time() - $this->getStartUpdate();
				if($CRON !== 'Y' AND $StepTime > 0 AND $LeftTime >= $StepTime) break;
				
				/* ID в Btrx */
				$elDbId = $el_db['id_btrx'][$el_k];
				
				if(in_array($el_k, $arrIDs['DEL'])):
					$el = new CIBlockElement;
					$el->Delete($elDbId);
					unset($arrIDs['DEL'][$el_k]);
					continue;
				endif;
				
				$el_title 			= '';
				$el_description 	= '';
				$PROP 				= array();
				
				/* Подробно о элемент из ReCrm */
				$get_json 			= $this->getJson($TYPE, array('id' => $el_k, 'description_format' => '1'));
				$el_dA 				= $this->convertArrImport($TYPE, $get_json);
				$el_d				= $el_dA[$el_k];
				
				/* Заголовки */
				if(is_array($el_d['title']))
				{
					$el_title = $el_d['title']['value'];
					unset($el_d['title']);
				}
				elseif(is_array($el_d['name']))
				{
					$el_title = $el_d['name']['value'];
					unset($el_d['name']);
				}
				
				/* Описание */
				if(is_array($el_d['description'])):
					$el_description = nl2br(str_replace($this->getDescStr(), '', $el_d['description']['value']));
					unset($el_d['description']);
				endif;
				
				foreach($el_d AS $el_prop_k => $el_prop_v)
				{
					$PROP[$el_prop_k] = $this->dataChange($el_prop_k, $el_prop_v['value']);
				}
				
				/* Изображения */
				if($TYPE == 'agent')
				{
					$PROP['photo'] = $this->convertArrImport('agentphoto',$this->getJson('agentphoto', array('agent_id' => $el_k, 'width' => '500', 'height' => '500', 'crop' => '0')));
				}
				elseif($TYPE == 'estate')
				{
					$getCoverSize = $this->getCoverSize();
					$PROP['estatecoverphoto'] 	= $this->convertArrImport('estatecoverphoto',$this->getJson('estatecoverphoto', array('estate_id' => $el_k, 'width' => $getCoverSize['width'], 'height' => $getCoverSize['height'], 'crop' => $this->getCrop(), 'watermark' => $this->getWRK())));
					$PROP['estatephoto'] 		= $this->convertArrImport('estatephoto',$this->getJson('estatephoto', array('estate_id' => $el_k, 'width' => '800', 'height' => '600', 'crop' => '0', 'watermark' => $this->getWRK())));
					$PROP['estatephotolayout'] 	= $this->convertArrImport('estatephotolayout',$this->getJson('estatephotolayout', array('estate_id' => $el_k, 'width' => '800', 'height' => '600', 'crop' => '0', 'watermark' => $this->getWRK())));
					if($PROP['edit_date'] == '') 		$PROP['edit_date'] 		= $PROP['creation_date'];
					if($PROP['edit_datetime'] == '') 	$PROP['edit_datetime'] 	= $PROP['creation_datetime'];
				}
				
				/* Добавляем свойства которых нет */
				$this->checkIBProps($TYPE, $el_d);
				
				$el_code = CUtil::translit($el_title.'_'.$el_k, "ru" , array(
					"max_len" 				=> "100",
					"change_case" 			=> "L",
					"replace_space" 		=> "_",
					"replace_other" 		=> "_",
					"delete_repeat_replace" => "true",
					"use_google" 			=> "false",
				));
				
				$arEl = array(
					"MODIFIED_BY" 		=> $GLOBALS['USER']->GetID(),
					"IBLOCK_ID" 		=> $IBLOCK_ID,
					"NAME" 				=> $el_title,
					"CODE" 				=> $el_code,
					"DETAIL_TEXT" 		=> $el_description,
					"DETAIL_TEXT_TYPE" 	=> 'html',
				);

				/*
				Callback
					has params TYPE - text, NEW - bool, PARAMS - arr, PROP - arr
					should return the array('PARAMS' => $arEl, 'PROPS' => $PROP);
				*/
				$rsHandlers = GetModuleEvents(self::$module_id, "OnBeforeImport");
				while($arHandler = $rsHandlers->Fetch())
				{
					$forEvent = array(
						'TYPE' 		=> $TYPE,
						'NEW' 		=> in_array($el_k, $arrIDs['NEW']),
						'PARAMS' 	=> $arEl,
						'PROP' 		=> $PROP,
					);
					$resEvent = ExecuteModuleEvent($arHandler, $forEvent);
					$arEl = $resEvent['PARAMS'];
					$PROP = $resEvent['PROP'];
				}

				/* Element */
				
				$el = new CIBlockElement;
				
				if(in_array($el_k, $arrIDs['UPD']) OR $elDbId > 0)
				{
					$cur_id = $el->Update($elDbId, $arEl);
					foreach($PROP AS $PROP_k => $PROP_v)
					{
						CIBlockElement::SetPropertyValues($elDbId, $IBLOCK_ID, $PROP_v, $PROP_k);
					}
					unset($arrIDs['UPD'][$el_k]);
					unset($arrIDs['NEW'][$el_k]); /* На случай */
				}
				elseif(in_array($el_k, $arrIDs['NEW']))
				{
					$arEl["PROPERTY_VALUES"] = $PROP;
					$cur_id = $el->Add($arEl);
					unset($arrIDs['NEW'][$el_k]);
				}
				
				$el_count--;
				
				//if($el->LAST_ERROR) AddMessage2Log('Ошибка на типе ' . $TYPE . ': ' . $el->LAST_ERROR);
			}
			
			//AddMessage2Log('Прошло сек '. $LeftTime . ' при времени шага '.$StepTime);
			//AddMessage2Log('Сброс цикла на типе '. $TYPE .' при количестве элементов '.$el_count);
			
			COption::SetOptionString(self::$module_id, 'pr_recrm_start_upd', time());
			$this->tmpDb($arrIDs);
			if($CRON === 'Y') $this->importIBEl('Y', $TYPES); // если крон - рекурсия
			return array(
				'MESS' 	=> GetMessage("PR_RECRM_STATUS_WHAT") . ': "'.prReCrmProps::getTypes('name', $TYPE).'", '.GetMessage("PR_RECRM_STATUS_COUNT").': '.$el_count.'.',
				'STEP' 	=> $STEP,
				'IBT' 	=> $TYPES_G,
			);
		}

	}

	/* ReCRM Redirect */
	public function redirect($arParams = array())
	{
		$ID = intval($_GET['recrm']);
		if(CModule::IncludeModule('iblock') AND $ID > 0)
		{
			$URL = false;
			$res = CIBlockElement::GetList(
				array(), 
				array(
					'IBLOCK_ID' 	=> $this->getIBId('estate'),
					'PROPERTY_id' 	=> $ID
				),
				false,
				false,
				array('ID', 'DETAIL_PAGE_URL')
			);
			while($ob = $res->GetNextElement())  
			{
				$ar 	= $ob->GetFields();
				$URL 	= $ar['DETAIL_PAGE_URL'];
			}
			if($URL != false)
			{
				LocalRedirect($URL);
			}
			else
			{
				LocalRedirect('/');
			}
		}
	}
	
}

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

	/* Параметры */
	public function getParams($arParams = array())
	{
		$RES 	= '';
		$TYPE 	= $arParams['TYPE'];
		$PARAMS = array(
			'pr_recrm_key'           => '',
			'pr_recrm_types'         => '',
			'pr_recrm_last_upd'      => '',
			'pr_recrm_last_load'     => '',
			'pr_recrm_start_upd'     => '',
			'pr_recrm_s_step'        => '',
			'pr_recrm_img_w'         => '',
			'pr_recrm_img_h'         => '',
			'pr_recrm_img_estate_w'  => '',
			'pr_recrm_img_estate_h'  => '',
			'pr_recrm_img_crop'      => '',
			'pr_recrm_img_wrk'       => '',
			'pr_recrm_search_hidden' => '',
			'pr_recrm_search_status' => '',
			'pr_recrm_timezone'      => '',
			'pr_recrm_d_rep'         => '',
			'pr_recrm_ib'            => '',
		);

		if(array_key_exists($TYPS, $PARAMS))
			$PARAMS = array($TYPE => '');

		foreach($PARAMS AS $KEY => $VAL)
		{
			if($KEY != 'pr_recrm_ib')
			{
				$RES = COption::GetOptionString(self::$module_id, $KEY);
			}

			if($KEY == 'pr_recrm_types'):

				$RES = $RES == '' ? array() : unserialize($RES);

			elseif($KEY == 'pr_recrm_last_upd' OR $KEY == 'pr_recrm_last_load'):

				$RES = intval($RES) == 0 ? 0 : $RES;

			elseif($KEY == 'pr_recrm_s_step'):

				$RES = intval($RES) == 0 ? 30 : $RES;
				$RES = $RES < 0 ? 0 : $RES;

			elseif($KEY == 'pr_recrm_img_w' OR $KEY == 'pr_recrm_img_h'):

				$RES = intval($RES) <= 0 ? 200 : $RES;

            elseif($KEY == 'pr_recrm_img_estate_w' OR $KEY == 'pr_recrm_img_estate_h'):

            	$RES = intval($RES) <= 0 ? 1000 : $RES;

			elseif($KEY == 'pr_recrm_img_crop' OR $KEY == 'pr_recrm_img_wrk' OR $KEY == 'pr_recrm_search_hidden' OR $KEY == 'pr_recrm_search_status'):

				$RES = intval($RES);

			elseif($KEY == 'pr_recrm_timezone'):

				$RES = strlen($return) == 0 ? 'Europe/Moscow' : $RES;

			elseif($KEY == 'pr_recrm_ib'):

				$RES = array();
				foreach(prReCrmProps::getTypes('name') AS $TYPE_k => $TYPE_v)
				{
					$RES[$TYPE_k] = COption::GetOptionString(self::$module_id, 'pr_recrm_ib_'.$TYPE_k);
				}

			endif;

			$PARAMS[$KEY] = $RES;
		}

		/*
		Callback
			has params TYPE - text, PARAMS - arr
			should return the array('TYPE' => $TYPE, 'PARAMS' => $PARAMS);
			p.s. you should know what you're doing
		*/
		$rsHandlers = GetModuleEvents(self::$module_id, "OnBeforeGetSettings");
		while($arHandler = $rsHandlers->Fetch())
		{
			$forEvent = array(
				'TYPE' 		=> $TYPE,
				'PARAMS' 	=> $PARAMS,
			);
			$resEvent = ExecuteModuleEvent($arHandler, $forEvent);
			$TYPE 	= $resEvent['TYPE'];
			$PARAMS = $resEvent['PARAMS'];
		}

		if(strlen($TYPE) > 0) return $PARAMS[$TYPE];

		return $PARAMS;
	}

	/* Ключ */
	public function getKey()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_key'));
	}

	/* Что выбрано для выгрузки */
	public function getSelectTypes()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_types'));
	}

	/* Дата обновления */
	public function getLastUpdate()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_last_upd'));
	}

	/* Дата последнего запроса к CRM */
	public function getLastLoad()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_last_load'));
	}

	/* Старт обновления */
	public function getStartUpdate()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_start_upd'));
	}

	/* Время шага */
	public function getStepTime()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_s_step'));
	}

	/* Размеры для превью */
	public function getCoverSize()
	{
		return array(
			'width' 	=> $this->getParams(array('TYPE' => 'pr_recrm_img_w')),
			'height' 	=> $this->getParams(array('TYPE' => 'pr_recrm_img_h')),
		);
	}

	/* Размеры для фото */
	public function getEstateSize()
	{
		return array(
			'width' 	=> $this->getParams(array('TYPE' => 'pr_recrm_img_estate_w')),
			'height' 	=> $this->getParams(array('TYPE' => 'pr_recrm_img_estate_h')),
		);
	}
	/* Кадрирование главного фото */
	public function getCrop()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_img_crop'));
	}

	/* Водный знак */
	public function getWRK()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_img_wrk'));
	}

	/* Выгружать скрытые объекты */
	public function getSH()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_search_hidden'));
	}

	/* Выгружать со всеми статусами */
	public function getSS()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_search_status'));
	}

	/* Выгружать скрытые объекты */
	public function getTZ()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_timezone'));
	}

	/* Что вырезать из описания объекта */
	public function getDescStr()
	{
		return $this->getParams(array('TYPE' => 'pr_recrm_d_rep'));
	}

	/* ID ИБ по ключам */
	public function getIBId($key = '')
	{
		$arr = $this->getParams(array('TYPE' => 'pr_recrm_ib'));
		return $arr[$key];
	}

	/* Крон */
	public function cron()
	{
		$RETURN = false;
		if(defined("PR_RECRM_DEBUG"))
		{
			AddMessage2Log('Start Cron');
		}
		$FILE = $this->MakeTmpData(array('CRON' => 'Y'));
		if(is_array($FILE))
		{
			$RETURN = $this->importIBEl('Y', 0);
		}
		return $RETURN;
	}

	/* Список всех ИБ */
	public function getIB($first = false) {
		$return 	= array();
		if(CModule::IncludeModule("iblock"))
		{
			if($first) $return[0] = '---';
			$query 		= CIBlock::GetList(array('ID' => 'ASC'), array('ACTIVE'=>'Y'));
			while($res 	= $query->Fetch())
			{
				$return[$res['ID']] = $res['IBLOCK_TYPE_ID'].' - ['.$res['ID'].']'.$res['NAME'];
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

	/* Конвертируем фомар, TODO */
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

		if(defined("PR_RECRM_DEBUG_QUERY"))
		{
			$rsHandlers = GetModuleEvents(self::$module_id, "OnAfterGetJson");
			while($arHandler = $rsHandlers->Fetch())
			{
				$forEvent = array(
					'type'     => $type,
					'url'      => $url,
					'vars'     => $vars,
					'response' => $data,
				);
				$resEvent = ExecuteModuleEvent($arHandler, $forEvent);
			}
		}

		$j_arr 	= json_decode($data, true);

		if(SITE_CHARSET == "UTF-8" OR SITE_CHARSET == "utf-8"):
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
	public function getEliB($IBLOCK_ID = '') {
		if(CModule::IncludeModule("iblock"))
		{
			$arr 		= array('id_recrm' => array(), 'id_btrx' => array());
			$arFilter 	= array("IBLOCK_ID" => $IBLOCK_ID);
			$arSelect 	= array("ID", "PROPERTY_id");

			/*
			Callback
				has params arFilter - array
			*/
			$rsHandlers = GetModuleEvents(self::$module_id, "OnBeforeGetEliBFilter");
			while($arHandler = $rsHandlers->Fetch())
			{
				$forEvent = $arFilter;
				$resEvent = ExecuteModuleEvent($arHandler, $forEvent);
				$arFilter = $resEvent;
			}

			$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
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

	/* Формируем массив IDs из json: ID = ID из ReCRM */
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
		$key = substr($key, 0, 50);
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
			elseif($r == 'contragent' OR $type == 'agentinfo')
			{
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
				$n [$a[$r]['id']] = $p;
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
	public function TmpDb($arParams = array())
	{
		$ROOT 	= realpath(dirname(__FILE__).'/../../../../..');
		$FILE 	= $ROOT . '/upload/'.$arParams['FILE'].'.txt';
		if($arParams['TYPE'] == 'W'):

			$ARRAY 	= $this->is_a($arParams['DATA']);
			$STR 	= serialize($ARRAY);
			$OPEN 	= fopen($FILE, "w+");
			fputs($OPEN, $STR);
			fclose($OPEN);

		elseif($arParams['TYPE'] == 'R'):

			if(!file_exists($FILE))
			{
				$this->TmpDb(array('TYPE' => 'W', 'FILE' => $arParams['FILE'], 'DATA' => array()));
			}

			$READ 	= file_get_contents($FILE);
			$ARR 	= unserialize($READ);
			if($arParams['FILE'] == 'recrm_files')
			{
				$ARR['SITE'] = $this->is_a($ARR['SITE']);
				$ARR['CRON'] = $this->is_a($ARR['CRON']);
			}
			return $ARR;

		elseif($arParams['TYPE'] == 'D'):

			if(file_exists($FILE))
			{
				unlink($FILE);
			}

		endif;
	}

	public function CheckResponse($response = null, $type = null, $params = array())
	{
		$res = true;
		if(is_null($response))
		{
			if(defined("PR_RECRM_DEBUG"))
			{
				AddMessage2Log('WARNING: Bad response for '.$type.' from Api');
			}

			$res = false;
		}

		$rsHandlers = GetModuleEvents(self::$module_id, "OnAfterCheckResponse");
		while($arHandler = $rsHandlers->Fetch())
		{
			$forEvent = array(
				'res'    => $res,
				'type'   => $type,
				'params' => $params,
			);
			$resEvent = ExecuteModuleEvent($arHandler, $forEvent);
		}

		return $res;
	}

	/* Создаем массив с ID для импорта */
	public function MakeTmpData($arParams = array())
	{
		date_default_timezone_set($this->getTZ());

		$result     = false;
		$UPD_TIME 	= time();
		$RECRM_KEY 	= $this->getKey();
		$FILE_NAME 	= 'recrm_'.$UPD_TIME.'_'.md5($RECRM_KEY);
		$LAST_LOAD 	= $this->getLastLoad();

		if($LAST_LOAD == 0) $LAST_LOAD = $this->getLastUpdate();

		$arData = array(
			'UPDATE_TIME' 	=> $UPD_TIME,
			'DATA' 			=> array(),
			'START' 		=> 'N',
		);

		/* Выбранные типы для импорта */
		$TYPES_ARR = array();
		foreach($this->getSelectTypes() AS $T_V)
		{
			$TYPES_ARR [] = $T_V;
		}

		$valid_data = true;

		/* Создаем массив данных для каждого типа */
		foreach($TYPES_ARR AS $TYPE)
		{
			$IBLOCK_ID 	= $this->getIBId($TYPE);

			if($IBLOCK_ID == 0) continue;

			$return 		= array();
			$json_params 	= array();

			$json_type 		= $TYPE;
			if($TYPE == 'estate')
			{
				$json_type   = 'estatesearch';
				$json_params = array('search_hidden' => $this->getSH(), 'start' => '0', 'count' => '10000000');
			}
			elseif($TYPE == 'contragent')
			{
				$json_type = 'contragentall';
			}

			/* Из CRM */
			/* Если выгружать объекты со всеми статусами */
			$ReCrmIDsS1 = array();
			$ReCrmIDsS2 = array();
			if($this->getSS() == '1' AND $TYPE == 'estate')
			{
				$json_params_status = $json_params;

				$json_params_status['status'] = '1';
				$json_res_s1 = $this->getJson($json_type, $json_params_status);
				if(!$this->CheckResponse($json_res_s1, $json_type, $json_params_status))
				{
					$valid_data = false;
					break;
				}
				$ReCrmIDsS1  = $this->convertArrCheck($json_type, $json_res_s1);

				$json_params_status['status'] = '2';
				$json_res_s2 = $this->getJson($json_type, $json_params_status);
				if(!$this->CheckResponse($json_res_s2, $json_type, $json_params_status))
				{
					$valid_data = false;
					break;
				}
				$ReCrmIDsS2  = $this->convertArrCheck($json_type, $json_res_s2);
			}

			/* Запрос */
			$json_res = $this->getJson($json_type, $json_params);
			if(!$this->CheckResponse($json_res, $json_type, $json_params))
			{
				$valid_data = false;
				break;
			}
			$ReCrmIDs 		= $this->convertArrCheck($json_type, $json_res) + $ReCrmIDsS1 + $ReCrmIDsS2;
			$ReCrmIDsUpd 	= $ReCrmIDs;

			/* Для измененных объектов, ищем только те, которые позднее последнего запроса к CRM занесенных в очередь на выгрузку */
			if($LAST_LOAD > 0 AND $TYPE == 'estate')
			{
				$json_params ['date_from'] 	= date('j.m.Y H:i', $LAST_LOAD);

				/* Если выгружать объекты со всеми статусами */
				$ReCrmIDsS1 = array();
				$ReCrmIDsS2 = array();
				if($this->getSS() == '1')
				{
					$json_params_status = $json_params;

					$json_params_status['status'] = '1';
					$json_res_s1 	= $this->getJson($json_type, $json_params_status);
					if(!$this->CheckResponse($json_res_s1, $json_type, $json_params_status))
					{
						$valid_data = false;
						break;
					}
					$ReCrmIDsS1 	= $this->convertArrCheck($json_type, $json_res_s1);

					$json_params_status['status'] = '2';
					$json_res_s2 	= $this->getJson($json_type, $json_params_status);
					if(!$this->CheckResponse($json_res_s2, $json_type, $json_params_status))
					{
						$valid_data = false;
						break;
					}
					$ReCrmIDsS2 	= $this->convertArrCheck($json_type, $json_res_s2);
				}

				$json_res_upd 	= $this->getJson($json_type, $json_params);
				if(!$this->CheckResponse($json_res_upd, $json_type, $json_params))
				{
					$valid_data = false;
					break;
				}
				$ReCrmIDsUpd 	= $this->convertArrCheck($json_type, $json_res_upd) + $ReCrmIDsS1 + $ReCrmIDsS2;
			}

			/* Объекты из БД */
			$DBArr 	= $this->getEliB($IBLOCK_ID);

			/* Ищем элементы, которые нужно удалить, которых нет в CRM но есть в БД */
			$DEL = array_diff_assoc($DBArr['id_recrm'], $ReCrmIDs);

			/* Ищем элементы, которые нужно добавить, которых нет в БД но есть в CRM */
			$NEW 	= array();
			$NEW 	= array_diff_assoc($ReCrmIDs, $DBArr['id_recrm']);

			/* Элементы, которые нужно обновить, убираем {удалить + новые} из массива ID Recrm */
			$UPD_U 	= $DEL + $NEW;
			$UPD 	= $this->unsetArrEl($ReCrmIDsUpd, $UPD_U);

			/* Заносим в массив данных */
			$arData['DATA'][$TYPE] = array(
				'IBLOCK_ID' => $IBLOCK_ID,
				'NEW' 		=> $this->is_a($NEW, true),
				'UPD' 		=> $this->is_a($UPD, true),
				'DEL' 		=> $this->is_a($DEL, true),
			);
		}

		if($valid_data === true)
		{
			/* Обновляем дату последнего запроса к CRM для последющего поиска обновленных объектов */
			COption::SetOptionString(self::$module_id, 'pr_recrm_last_load', $UPD_TIME);

			$FILES_TYPE = $arParams['CRON'] == 'Y' ? 'CRON' : 'SITE';

			/* От дублей */
			$arHASH = $arData;
			unset($arHASH['UPDATE_TIME']);
			$HASH = md5(serialize($arHASH));

			/* Список файлов в очереди */
			$FILES = $this->TmpDb(array('TYPE' => 'R', 'FILE' => 'recrm_files'));
			if(array_key_exists($HASH, $FILES[$FILES_TYPE]))
			{
				$result = false;
			}
			else
			{
				/* Создаем временный файл */
				$this->TmpDb(array('TYPE' => 'W', 'FILE' => $FILE_NAME, 'DATA' => $arData));

				/* Добавляем в список очереди новый файл */
				$FILES [$FILES_TYPE][$HASH] = $FILE_NAME;
				$this->TmpDb(array('TYPE' => 'W', 'FILE' => 'recrm_files', 'DATA' => $FILES));

				/* Возвращаем данные занесенные в файл */
				$result = $arData;
			}
		}

		return $result;
	}

	/* Импорт */
	public function importIBEl($CRON = 0, $START = 'N')
	{
		date_default_timezone_set($this->getTZ());
		$arLog = array();

		if($START == 'Y'):
			COption::SetOptionString(self::$module_id, 'pr_recrm_start_upd', time());
		endif;

		CModule::IncludeModule("iblock");

		$TYPE_IDS 		= array();
		$FILES 			= $this->TmpDb(array('TYPE' => 'R', 'FILE' => 'recrm_files'));
		$FILES_TYPE 	= $CRON === 'Y' ? 'CRON' : 'SITE';
		$FILES_COUNT 	= count($FILES[$FILES_TYPE]);

		if(count($FILES_COUNT))
		{
			$FILE 	= array_shift($FILES[$FILES_TYPE]);
			$DATA 	= $this->TmpDb(array('TYPE' => 'R', 'FILE' => $FILE));

			if($DATA['START'] != 'Y')
			{
				$DATA['START'] = 'Y';
				$this->TmpDb(array('TYPE' => 'W', 'FILE' => $FILE, 'DATA' => $DATA));
			}

			foreach($DATA['DATA'] AS $TYPE_KEY => $TYPE_DATA)
			{
				$TYPE_IDS = $this->is_a($TYPE_DATA['NEW']) + $this->is_a($TYPE_DATA['UPD']) + $this->is_a($TYPE_DATA['DEL']);
				if(count($TYPE_IDS))
				{
					$TYPE_NAME = prReCrmProps::getTypes('name', $TYPE_KEY);
					$IBLOCK_ID = $TYPE_DATA['IBLOCK_ID'];
					break;
				}
			}

			if(count($TYPE_IDS) <= 0)
			{
				/* Callback Finish */
				$rsHandlers = GetModuleEvents(self::$module_id, "OnAfterImport");
				while($arHandler = $rsHandlers->Fetch())
				{
					ExecuteModuleEvent($arHandler);
				}

				$arLog [] = sprintf(GetMessage('PR_RECRM_LOG_FILE_FINISH'), $FILE);

				$this->TmpDb(array('TYPE' => 'D', 'FILE' => $FILE));
				$this->TmpDb(array('TYPE' => 'W', 'FILE' => 'recrm_files', 'DATA' => $FILES));
				COption::SetOptionString(self::$module_id, 'pr_recrm_start_upd', 0);

				if(strlen($DATA['UPDATE_TIME']) > 0)
				{
					COption::SetOptionString(self::$module_id, 'pr_recrm_last_upd', $DATA['UPDATE_TIME']);
				}

				if(count($FILES[$FILES_TYPE]) == 0) // IMPORTANT
				{
					$arLog [] = GetMessage('PR_RECRM_LOG_FILES_NONE');
					if(defined("PR_RECRM_DEBUG"))
					{
						AddMessage2Log($arLog);
					}
					return true;
				}
				else
				{
					$arLog [] = GetMessage('PR_RECRM_LOG_FILE_NEXT');

					if($CRON === 'Y')
					{
						$this->importIBEl('Y'); // если крон - рекурсия
					}
					if(defined("PR_RECRM_DEBUG"))
					{
						AddMessage2Log($arLog);
					}
					return $arLog;
				}
			}
			else
			{
				$arLog [] = sprintf(GetMessage('PR_RECRM_LOG_FILES_LAST'), $FILES_COUNT);
				$arLog [] = sprintf(GetMessage('PR_RECRM_LOG_FILE_READ'), date('d.m.Y - H:i:s', $DATA['UPDATE_TIME']));

				$COUNT 	= count($TYPE_IDS);
				$IDS_DB = $this->getEliB($IBLOCK_ID);

				$arLog [] = sprintf(GetMessage('PR_RECRM_LOG_FILE_START'), $TYPE_NAME, count($TYPE_IDS));

				$STEP = $this->getStepTime(); // Сколько шаг
				foreach($TYPE_IDS AS $CRM_ID)
				{
					if(intval($CRM_ID) == 0) continue;

					/* Выход из цикла по времени */
					if($CRON !== 'Y')
					{
						$LEFT = time() - $this->getStartUpdate();

						if($STEP > 0 AND $LEFT >= $STEP)
							break;
					}

					/* ID в Btrx */
					$DB_ID = $IDS_DB['id_btrx'][$CRM_ID];

					if(in_array($CRM_ID, $DATA['DATA'][$TYPE_KEY]['DEL']))
					{
						/*
						Callback
							has params TYPE - text, IBLOCK_ID - int, CRM_ID - int, ELEMENT_ID - int
						*/
						$rsHandlers = GetModuleEvents(self::$module_id, "OnBeforeElementDelete");
						while($arHandler = $rsHandlers->Fetch())
						{
							$forEvent = array(
								'TYPE' 			=> $TYPE_KEY,
								'IBLOCK_ID' 	=> $IBLOCK_ID,
								'CRM_ID' 		=> $CRM_ID,
								'ELEMENT_ID' 	=> $DB_ID,
							);
							$resEvent = ExecuteModuleEvent($arHandler, $forEvent);
						}

						$EL = new CIBlockElement;
						$EL->Delete($DB_ID);

						unset($DATA['DATA'][$TYPE_KEY]['DEL'][$CRM_ID]);

						continue;
					}

					$TITLE 			= '';
					$DETAIL_TEXT 	= '';
					$PROP 			= array();

					/* Подробно о элемент из ReCrm */
					$GET_INFO_TYPE 		= $TYPE_KEY;
					$GET_INFO_PARAMS 	= array('id' => $CRM_ID);
					$DATA_CONVERT_TYPE 	= $TYPE_KEY;
					if($TYPE_KEY == 'agent')
					{
						$GET_INFO_TYPE 		= 'agentinfo';
						$DATA_CONVERT_TYPE 	= 'agentinfo';
					}
					elseif($TYPE_KEY == 'estate')
					{
						$GET_INFO_PARAMS['description_format'] = '1';
					}
					$GET_INFO 			= $this->getJson($GET_INFO_TYPE, $GET_INFO_PARAMS);
					$DATA_CONVERT 		= $this->convertArrImport($DATA_CONVERT_TYPE, $GET_INFO);
					$ELEMENT_DATA		= $DATA_CONVERT[$CRM_ID];

					/* Заголовки */
					if(is_array($ELEMENT_DATA['title']))
					{
						$TITLE = $ELEMENT_DATA['title']['value'];
						unset($ELEMENT_DATA['title']);
					}
					elseif(is_array($ELEMENT_DATA['name']))
					{
						$TITLE = $ELEMENT_DATA['name']['value'];
						unset($ELEMENT_DATA['name']);
					}

					if(strlen($TITLE) <= 0) $TITLE = $CRM_ID;

					/* Описание */
					if(is_array($ELEMENT_DATA['description']))
					{
						$DETAIL_TEXT = nl2br(str_replace($this->getDescStr(), '', $ELEMENT_DATA['description']['value']));
						unset($ELEMENT_DATA['description']);
					}

					foreach($ELEMENT_DATA AS $PROP_K => $PROP_V)
					{
						$PROP[$PROP_K] = $this->dataChange($PROP_K, $PROP_V['value']);
					}

					/* Изображения */
					if($TYPE_KEY == 'agent')
					{
						$PROP['photo'] = $this->convertArrImport('agentphoto',$this->getJson('agentphoto', array('agent_id' => $CRM_ID, 'width' => '500', 'height' => '500', 'crop' => '0')));
					}
					elseif($TYPE_KEY == 'estate')
					{
						$getCoverSize = $this->getCoverSize();
						$getEstateSize = $this->getEstateSize();
						$PROP['estatecoverphoto'] 	= $this->convertArrImport('estatecoverphoto',$this->getJson('estatecoverphoto', array('estate_id' => $CRM_ID, 'width' => $getCoverSize['width'], 'height' => $getCoverSize['height'], 'crop' => $this->getCrop(), 'watermark' => $this->getWRK())));
						$PROP['estatephoto'] 		= $this->convertArrImport('estatephoto',$this->getJson('estatephoto', array('estate_id' => $CRM_ID, 'width' => $getEstateSize['width'], 'height' => $getEstateSize['height'], 'crop' => '0', 'watermark' => $this->getWRK())));
						$PROP['estatephotolayout'] 	= $this->convertArrImport('estatephotolayout',$this->getJson('estatephotolayout', array('estate_id' => $CRM_ID, 'width' => $getEstateSize['width'], 'height' => $getEstateSize['height'], 'crop' => '0', 'watermark' => $this->getWRK())));
						if($PROP['edit_date'] == '') 		$PROP['edit_date'] 		= $PROP['creation_date'];
						if($PROP['edit_datetime'] == '') 	$PROP['edit_datetime'] 	= $PROP['creation_datetime'];
					}

					/* Добавляем свойства которых нет */
					$this->checkIBProps($TYPE_KEY, $ELEMENT_DATA);

					$CODE = CUtil::translit($TITLE.'_'.$CRM_ID, "ru" , array(
						"max_len" 				=> "100",
						"change_case" 			=> "L",
						"replace_space" 		=> "_",
						"replace_other" 		=> "_",
						"delete_repeat_replace" => "true",
						"use_google" 			=> "false",
					));

					$arEL = array(
						"MODIFIED_BY" 		=> $GLOBALS['USER']->GetID(),
						"IBLOCK_ID" 		=> $IBLOCK_ID,
						"NAME" 				=> $TITLE,
						"CODE" 				=> $CODE,
						"DETAIL_TEXT" 		=> $DETAIL_TEXT,
						"DETAIL_TEXT_TYPE" 	=> 'html',
					);

					/*
					Callback
						@TYPE
						has params TYPE - text, NEW - bool, PARAMS - arr, PROP - arr
						should return the array('PARAMS' => $arEL, 'PROPS' => $PROP);
					*/

					/**
					* Callback
					*
					* @param string $TYPE - Тип данных
					* @param bool $NEW - новый объект
					* @param array $PARAMS - Параметры элемента
					* @param array $PROP - Сформированные свойства элемента
					* @param int $IBLOCK_ID - IBLOCK ID
					* @param int $CRM_ID - CRM ID
					* @param mixed $ELEMENT_ID - ELEMENT ID - ID или null
					* @return array - массив
					*/
					$rsHandlers = GetModuleEvents(self::$module_id, "OnBeforeImport");
					while($arHandler = $rsHandlers->Fetch())
					{
						$forEvent = array(
							'TYPE'       => $TYPE_KEY,
							'NEW'        => in_array($CRM_ID, $DATA['DATA'][$TYPE_KEY]['NEW']),
							'PARAMS'     => $arEL,
							'PROP'       => $PROP,
							'IBLOCK_ID'  => $IBLOCK_ID,
							'CRM_ID'     => $CRM_ID,
							'ELEMENT_ID' => $DB_ID,
						);
						$resEvent = ExecuteModuleEvent($arHandler, $forEvent);
						$arEL = $resEvent['PARAMS'];
						$PROP = $resEvent['PROP'];
					}

					/* Element */
					$EL = new CIBlockElement;

					if(in_array($CRM_ID, $DATA['DATA'][$TYPE_KEY]['UPD']) OR $DB_ID > 0)
					{
						$EL->Update($DB_ID, $arEL);

						foreach($PROP AS $PROP_K => $PROP_V)
						{
							CIBlockElement::SetPropertyValues($DB_ID, $IBLOCK_ID, $PROP_V, $PROP_K);
						}
						unset($DATA['DATA'][$TYPE_KEY]['UPD'][$CRM_ID]);
						unset($DATA['DATA'][$TYPE_KEY]['NEW'][$CRM_ID]);
					}
					elseif(in_array($CRM_ID, $DATA['DATA'][$TYPE_KEY]['NEW']))
					{
						$arEL["PROPERTY_VALUES"] = $PROP;
						$EL->Add($arEL);
						unset($DATA['DATA'][$TYPE_KEY]['NEW'][$CRM_ID]);
					}

					$COUNT--;

					if($EL->LAST_ERROR) $arLog [] = sprintf(GetMessage('PR_RECRM_LOG_EL_ERROR'), $TYPE_NAME, $EL->LAST_ERROR);
				}

				if($CRON !== 'Y')
				{
					$arLog [] = sprintf(GetMessage('PR_RECRM_LOG_STEP_LEFT'), $LEFT, $STEP);
					$arLog [] = sprintf(GetMessage('PR_RECRM_LOG_STEP_BREAK'), $TYPE_NAME, $COUNT);
				}

				$arLog [] = GetMessage('PR_RECRM_LOG_CONTINUE');

				COption::SetOptionString(self::$module_id, 'pr_recrm_start_upd', time());

				$this->TmpDb(array('TYPE' => 'W', 'FILE' => $FILE, 'DATA' => $DATA));

				if($CRON === 'Y')
				{
					$this->importIBEl('Y'); // если крон - рекурсия
				}
				if(defined("PR_RECRM_DEBUG"))
				{
					AddMessage2Log($arLog);
				}
				return $arLog;
			}
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
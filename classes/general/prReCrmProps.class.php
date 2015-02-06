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
class prReCrmProps
{
	
	public static function getTypes($type = '', $name = '')
	{
		$arr = array(
			'url' => array(
				'key' 				=> 'countries',
				'countries' 		=> 'countries',
				'cities' 			=> 'cities',
				'districts' 		=> 'districts/all',
				'metrostations' 	=> 'metro/allstations',
				'estatetypesgroup' 	=> 'estatetypes/groups',
				'estatetypeslist' 	=> 'estatetypes',
				'estate' 			=> 'estate/info',
				'estatesearch' 		=> 'estate/search',
				'estatecoverphoto' 	=> 'picture/EstateCoverPhoto',
				'estatephoto' 		=> 'picture/EstatePhoto',
				'estatephotolayout'	=> 'picture/EstateLayout',
				'agent' 			=> 'agent/all',
				'agentphoto' 		=> 'picture/AgentPhoto',
				//'contragentsearch' 	=> 'contragent/search',
				//'contragent' 		=> 'contragent/info',
				//'request' 			=> 'request/info',
				//'requestsearch' 	=> 'request/search',
			),
			'arr' => array(
				'countries' 		=> 'countries',
				'cities' 			=> 'cities',
				'districts' 		=> 'districts',
				'metrostations' 	=> 'metro_stations',
				'estatetypesgroup' 	=> 'groups',
				'estatetypeslist' 	=> 'types',
				'estate' 			=> 'property',
				'estatesearch' 		=> 'results',
				'estatecoverphoto' 	=> 'pictures',
				'estatephoto' 		=> 'pictures',
				'estatephotolayout'	=> 'pictures',
				'agent' 			=> 'agents',
				'agentphoto' 		=> 'pictures',
				//'contragent' 		=> 'contragent',
				//'request' 			=> 'request',
				//'requestsearch' 	=> 'results',
			),
			'name' => array(
				'countries' 		=> GetMessage("PR_RECRM_NAME_COUNTRIES"),
				'cities' 			=> GetMessage("PR_RECRM_NAME_CITIES"),
				'districts' 		=> GetMessage("PR_RECRM_NAME_DISTRICTS"),
				'metrostations' 	=> GetMessage("PR_RECRM_NAME_METROSTATIONS"),
				'estatetypesgroup' 	=> GetMessage("PR_RECRM_NAME_ESTATETYPESGROUP"),
				'estatetypeslist' 	=> GetMessage("PR_RECRM_NAME_ESTATETYPESLIST"),
				'estate' 			=> GetMessage("PR_RECRM_NAME_ESTATE"),
				'agent' 			=> GetMessage("PR_RECRM_NAME_AGENT"),
				//'contragent' 		=> GetMessage("PR_RECRM_NAME_CONTRAGENT"),
				//'request' 			=> GetMessage("PR_RECRM_NAME_REQUEST"),
			)
		);
		
		if($name == '') return $arr[$type];
		
		return $arr[$type][$name];
	}
	
	public static function getPropsNames($key = '')
	{
		$arr = array(
			'id' 					=> GetMessage("PR_RECRM_PROP_ID"),
			'hidden' 				=> GetMessage("PR_RECRM_PROP_HIDDEN"),
			'name_prepositional' 	=> GetMessage("PR_RECRM_PROP_NAME_PREPOSITIONAL"),
			'hot' 					=> GetMessage("PR_RECRM_PROP_HOT"),
			'country_id' 			=> GetMessage("PR_RECRM_PROP_COUNTRY_ID"),
			'district_id' 			=> GetMessage("PR_RECRM_PROP_DISTRICT_ID"),
			'metro_id' 				=> GetMessage("PR_RECRM_PROP_METRO_ID"),
			'region_id' 			=> GetMessage("PR_RECRM_PROP_REGION_ID"),
			'region_district_id' 	=> GetMessage("PR_RECRM_PROP_REGION_DISTRICT_ID"),
			'city_id' 				=> GetMessage("PR_RECRM_PROP_CITY_ID"),
			'group_id' 				=> GetMessage("PR_RECRM_PROP_GROUP_ID"),
			'agent_id' 				=> GetMessage("PR_RECRM_PROP_AGENT_ID"),
			'metro_line_id' 		=> GetMessage("PR_RECRM_PROP_METRO_LINE_ID"),
			'email' 				=> GetMessage("PR_RECRM_PROP_EMAIL"),
			'phone' 				=> GetMessage("PR_RECRM_PROP_PHONE"),
			'mobile_phone' 			=> GetMessage("PR_RECRM_PROP_MOBILE_PHONE"),
			'position' 				=> GetMessage("PR_RECRM_PROP_POSITION"),
			'role' 					=> GetMessage("PR_RECRM_PROP_ROLE"),
			'group_name' 			=> GetMessage("PR_RECRM_PROP_GROUP_NAME"),
			'photo' 				=> GetMessage("PR_RECRM_PROP_PHOTO"),
			'estatecoverphoto' 		=> GetMessage("PR_RECRM_PROP_ESTATECOVERPHOTO"),
			'estatephoto' 			=> GetMessage("PR_RECRM_PROP_ESTATEPHOTO"),
			'estatephotolayout' 	=> GetMessage("PR_RECRM_PROP_ESTATEPHOTOLAYOUT"),
			'edit_date' 			=> GetMessage("PR_RECRM_PROP_EDIT_DATE"),
			'edit_datetime' 		=> GetMessage("PR_RECRM_PROP_EDIT_DATETIME"),
			'latitude' 				=> GetMessage("PR_RECRM_PROP_LATITUDE"),
			'longitude' 			=> GetMessage("PR_RECRM_PROP_LONGITUDE"),
			'zoom' 					=> GetMessage("PR_RECRM_PROP_ZOOM"),
			'meta_description' 		=> GetMessage("PR_RECRM_PROP_META_DESCRIPTION"),
			'meta_keywords' 		=> GetMessage("PR_RECRM_PROP_META_KEYWORDS"),
			'meta_title' 			=> GetMessage("PR_RECRM_PROP_META_TITLE"),
		);
		
		if($arr[$key] != '') return $arr[$key];
		
		return $key;
	}
	
}
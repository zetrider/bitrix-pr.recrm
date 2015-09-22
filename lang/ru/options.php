<?
$MESS ['PR_RECRM_F_TYPES'] 				= "Инфоблок для"; 
$MESS ['PR_RECRM_F_TYPES_NOTE'] 		= "Выберите инфоблок для выгрузки данных"; 
$MESS ['PR_RECRM_F_KEY'] 				= "Ключ ReCRM"; 
$MESS ['PR_RECRM_F_KEY_NOTE'] 			= "Ключ нужно запросить у поддержки системы ReCRM"; 
$MESS ['PR_RECRM_F_S_STEP'] 			= "Время одного шага при выгрузке (сек)"; 
$MESS ['PR_RECRM_F_S_STEP_NOTE'] 		= "0 без ограничений"; 
$MESS ['PR_RECRM_F_IMG_W'] 				= "Ширина изображения в списке объектов"; 
$MESS ['PR_RECRM_F_IMG_W_NOTE'] 		= "PREVIEW_PICTURE для объекта"; 
$MESS ['PR_RECRM_F_IMG_H'] 				= "Высота изображения в списке объектов"; 
$MESS ['PR_RECRM_F_IMG_H_NOTE'] 		= "PREVIEW_PICTURE для объекта";
$MESS ['PR_RECRM_F_IMG_CROP'] 			= "Обрезать главное изображение точно по размеру"; 
$MESS ['PR_RECRM_F_IMG_CROP_NOTE'] 		= "Кадрирует главное изображение estatecoverphoto (crop)"; 
$MESS ['PR_RECRM_F_IMG_WRK'] 			= "Нужно ли накладывать водный знак на изображение"; 
$MESS ['PR_RECRM_F_IMG_WRK_NOTE'] 		= "для этого водный знак должен быть указан в настройках CRM"; 
$MESS ['PR_RECRM_SEARCH_HIDDEN'] 		= "Включить в выгрузку скрытые объекты"; 
$MESS ['PR_RECRM_SEARCH_HIDDEN_NOTE'] 	= "Свойство объекта: Является ли объект скрытым"; 
$MESS ['PR_RECRM_F_TYPES_SELECT'] 		= "Какие данные выгружать из ReCRM"; 
$MESS ['PR_RECRM_F_TYPES_SELECT_NOTE'] 	= "Выберите один или несколько типов."; 
$MESS ['PR_RECRM_F_DESC_REP'] 			= "Вырезать из описания строку"; 
$MESS ['PR_RECRM_F_DESC_REP_NOTE'] 		= "Какую сроку вырезать из описания"; 
$MESS ['PR_RECRM_F_LAST_UPD'] 			= "Дата последнего обновления (unixtime)"; 
$MESS ['PR_RECRM_F_LAST_UPD_NOTE'] 		= "Параметр необходим для выборки только обновленных объектов в ReCRM. Редактировать не рекомендуется. Если параметр будет удален, все данные выгрузятся по новой."; 

$MESS ['PR_RECRM_TAB_STAT_NAME'] 		= "Статистика"; 
$MESS ['PR_RECRM_TAB_PROPERTIES_NAME'] 	= "Настройка свойств"; 
$MESS ['PR_RECRM_TAB_PROPERTIES_TITLE'] = "Настройка свойств объектов"; 
$MESS ['PR_RECRM_TAB_CRON_NAME'] 		= "Автоматическая выгрузка"; 

$MESS ['PR_RECRM_ERR_NOT_SELECT_TYPES'] = "Вы не выбрали типа данных для выгрузки. Перейдите по вкладке: Настройки"; 
$MESS ['PR_RECRM_ERR_NOT_IB_TYPES'] 	= "В настройках модуля не выбраны инфоблоки для"; 
$MESS ['PR_RECRM_ERR_NOT_FOUND_KEY'] 	= "Не указан API ключ от ReCRM, пожалуйста, нажмите на вкладку 'Настройки'."; 
$MESS ['PR_RECRM_ERR_BAD_KEY'] 			= "Указанный API ключ система ReCRM не принимает. Пожалуйста, укажите другой."; 

$MESS ['PR_RECRM_MODULE_NAME'] 			= "Выгрузка данных из ReCRM"; 

$MESS ['PR_RECRM_IMP_STEP'] 			= "Выполняется пошаговое обновление для текущего типа: Шаг №"; 
$MESS ['PR_RECRM_IMP_STEP_NOTE'] 		= "Внимание: Не обновляйте и не покидайте страницу, дождитесь сообщения об успешной выгрузке!"; 
$MESS ['PR_RECRM_IMP_SUCCESS'] 			= "Данные успешно выгружены."; 

$MESS ['PR_RECRM_STAT_LIST'] 			= "Статистика"; 
$MESS ['PR_RECRM_STAT_LAST_UPD'] 		= "Дата последней выгрузки"; 
$MESS ['PR_RECRM_STAT_LAST_UPD_NULL'] 	= "Данные еще не выгружались"; 

$MESS ['PR_RECRM_PROPS_DESC'] 			= "Все новые свойства добавляются автоматически.<br><br>В название впишите текст, который посетитель будет видеть на сайте.<br>Единица измерения отображается после содержимого свойства.<br>Вы можете отсортировать поля при помощи мыши."; 
$MESS ['PR_RECRM_PROPS_NAME'] 			= "Название"; 
$MESS ['PR_RECRM_PROPS_HINT'] 			= "Ед. изм"; 
$MESS ['PR_RECRM_PROPS_SORT'] 			= "Сорт."; 

$MESS ['PR_RECRM_CRON_DESC'] 			= "<p>Для настройки автоматического обновления информации на вашем сайте, вам необходимо настроить <b>CRON (планировщик задач)</b>. <br><br>Если у вас нет возможности настроить самостоятельно, можно отправить поддержкой вашего хостинга следующее сообщение:<br><br><i>Необходимо поставить задачу в CRON по расписанию: 'каждые 6 часов' для файла на сервере нашего сайта в папке: /bitrix/modules/pr.recrm/cron.php</i><br><br>После того как хостинг внесет задачу, каждые 6 часов на ваш сайт будет выгружена обновленная база из ReCRM.<br><br><b>Внимание:</b> за раз выгружаются все элементы без пошаговости.<br><br><b>Первую выгрузку рекомендуется произвести вручную!</b></p>"; 

$MESS ['PR_RECRM_BTN_IMPORT'] 			= "Выгрузить данные из ReCRM вручную";
$MESS ['PR_RECRM_Y'] 					= "Да";
$MESS ['PR_RECRM_N'] 					= "Нет";
?>
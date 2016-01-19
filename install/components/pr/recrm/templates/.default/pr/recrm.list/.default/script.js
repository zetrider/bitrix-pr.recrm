(function($) {

	/* Map */
	$.fn.recrm_map = function (data) {
		
		if($.isEmptyObject(data))
		{
			$(this).hide();
			return;
		}

		$.getScript('//api-maps.yandex.ru/2.1/?lang=ru_RU', function()
		{
			ymaps.ready(function()
			{

				var ReCrmMap = new ymaps.Map('recrm_map', {
					center		: data[0]['LOC'],
					zoom		: 10,
					controls	: ['zoomControl']
				});

				var ReCrmGeoObjects = [];
				var ReCrmObjDom = '';
				$.each(data, function(i, o)
				{
					ReCrmObjDom = $('#recrm_el_' + o['ID'] + ' .title');  // меняй на свое

					ReCrmGeoObjects[i] = new ymaps.GeoObject(
					{
						geometry: {
							type: "Point",
							coordinates: o['LOC']
						},
						properties: {
							clusterCaption: 		$('a', ReCrmObjDom).html(),
							balloonContentHeader: 	'',
							balloonContentBody: 	ReCrmObjDom.html(),
							balloonContentFooter: 	'',
							hintContent: 			$('a', ReCrmObjDom).html(),
						}
					}
					);
					
				});
				var ReCrmClusterer = new ymaps.Clusterer(); // {clusterDisableClickZoom: true}
				ReCrmClusterer.add(ReCrmGeoObjects);
				ReCrmMap.geoObjects.add(ReCrmClusterer);

				ReCrmBounds = ReCrmMap.geoObjects.getBounds();
				ReCrmMap.setBounds(ReCrmBounds, {checkZoomRange:true});
			});
		});
	}

})(jQuery);
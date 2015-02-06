jQuery(document).ready( function($) {

	if($('.pr_sortable').length > 0)
	{
		$('.pr_sortable').sortable({ 
			opacity: 0.8,
			cursor: 'move',
			update: function(event, ui) {
				//$('.s_sort', ui.item).val(ui.item.index() + 1);
				
				$.each($('.pr_sortable li'), function(i) {
					$('.s_sort', this).val(i+1);
				});
			},
			stop: function() {

			}
		});
	}
	
});
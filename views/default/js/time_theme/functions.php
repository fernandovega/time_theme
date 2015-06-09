<?php ?>
//<script>

var offset = 0;
var isloading = false;
function loadMore()
{	

	if (!istheremore || ($('#river_auto_update_loading').css("display") == "block")) {
		return;
	}
	
	offset += options['limit'];	
	
	$('#river_auto_update_load_more_button').hide();
	$('#river_auto_update_loading').css("display", "block");
	$.post(elgg.get_site_url() + 'activity/proc/loadmore', {'options':options, 'offset':offset}, function(response) {			
		if(response.valid) {		
			$('#river_auto_update_activity').append(response.content);						
			$('#river_auto_update_activity').children('ul:nth-child(2)').children('li').appendTo('#river_auto_update_activity ul:first'); // move all LIs to the first UL
			$('#river_auto_update_activity ul:last').remove();	// delete the extra ULs		
			
			if (!response.istheremore)
			{			
				istheremore = false;				
				$('#river_auto_update_load_more_button').css("display", "none");
			}
			else
			{
				$('#river_auto_update_load_more_button').show();
			}
		}		
		$('#river_auto_update_loading').css("display", "none");
	}, 'json');
}






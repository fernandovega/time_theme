<?php
/**
 * Main activity stream list page
 */

$options = array();

$page_type = preg_replace('[\W]', '', get_input('page_type', 'all'));
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));
if ($subtype) {
	$selector = "type=$type&subtype=$subtype";
} else {
	$selector = "type=$type";
}

if ($type != 'all') {
	$options['type'] = $type;
	if ($subtype) {
		$options['subtype'] = $subtype;
	}
}

switch ($page_type) {
	case 'mine':
		$title = elgg_echo('river:mine');
		$page_filter = 'mine';
		$options['subject_guid'] = elgg_get_logged_in_user_guid();
		break;
	case 'friends':
		$title = elgg_echo('river:friends');
		$page_filter = 'friends';
		$options['relationship_guid'] = elgg_get_logged_in_user_guid();
		$options['relationship'] = 'friend';
		break;
	default:
		$title = elgg_echo('river:all');
		$page_filter = 'all';
		break;
}

$options['count'] = true;
$count = elgg_get_river($options);
$options['count'] = false;

$options['pagination'] = false;
$options['offset'] = 0;
if(elgg_get_config('default_limit'))
	$options['limit'] = elgg_get_config('default_limit');
else
	$options['limit'] = 5;

$script_text = '<script type="text/javascript"> 
		var options = ' . json_encode($options) . '; 
		var numactivities = ' . $count . '; 
		</script>';

$activity = '<div id="river_auto_update_activity">';
$activity .= elgg_list_river($options);
if (!$activity) {
	$activity = elgg_echo('river:none');
}
$activity .= '</div>';

if ($count > $options['limit']) {
	// show load more button
	$activity .= '<center id="river_auto_update_load_more_button"><button style="padding:0 50px;" onClick="loadMore()">Load More</button></center>';
	
	$script_text .= '<script type="text/javascript"> var istheremore = true; </script>';
} else {
	$script_text .= '<script type="text/javascript"> var istheremore = false; </script>';
}
// show the loading gif
$activity .= '<img id="river_auto_update_loading" src="' . elgg_get_site_url() . '/mod/time_theme/graphics/loading.gif" height="25px;" style="display:none; margin-left:auto; margin-right:auto;">';

$content = elgg_view('core/river/filter', array('selector' => $selector));

$sidebar = elgg_view('core/river/sidebar');

$script_text .= '<script type="text/javascript">

 $(window).scroll(function() {   
   if($(window).scrollTop() + $(window).height() == $(document).height()) {			
		loadMore();		
   }
});

</script>';


$params = array(
	'title' => $title,
	'content' =>  $content . $activity . $script_text,
	'sidebar' => $sidebar,
	'filter_context' => $page_filter,
	'class' => 'elgg-river-layout',
);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);

?>


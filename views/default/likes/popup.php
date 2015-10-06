<?php

error_log(' HHOLA ALAL ');
$guid = (int) get_input('guid');

$list = elgg_list_annotations(array(
	'guid' => $guid,
	'annotation_name' => 'likes',
	'limit' => 99,
	'list_class' => 'elgg-list-likes'
));

echo elgg_format_element('div', ['class' => 'elgg-likes-popup'], $list);

<?php
/**
 * New bookmarks river entry
 *
 * @package Bookmarks
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$object = $item->getObjectEntity();
$excerpt = elgg_get_excerpt($object->description);

$attachments = elgg_view('output/url', array('href' => $object->address));

$attachments = '<a class="embedly-card" href="'.$object->address.'"></a>';;

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => $excerpt,
	'attachments' => $attachments,
));

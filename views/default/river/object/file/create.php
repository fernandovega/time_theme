<?php
/**
 * File river view.
 */

elgg_load_js('tidypics');
elgg_load_js('lightbox');
elgg_load_css('lightbox');

$item = $vars['item'];
/* @var ElggRiverItem $item */
$object = $item->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

$preview_size = 'medium';

$title = $object->title;
$objectname = $object->originalfilename;
$mime = $object->getMimeType();
$download_url = elgg_get_site_url() .  "file/download/$object->guid";

switch ($object->simpletype) {
    case 'image':
      $attachments = elgg_view_entity_icon($object, $preview_size, array(
        'href' => $object->getIconURL('largue'),
        'img_class' => 'file-photo',
        'link_class' => 'elgg-lightbox-photo',
           'is_trusted' => true,
      )).'<br>'.$objectname;
      break;
    case 'video':
      $attachments = '
      <div class="flowplayer">
         <video>
            <source src="'.$download_url.'" type="'.$mime.'">
         </video>
      </div>
      ';
      break;
    case 'audio':
      $attachments = '<audio controls>
        <source src="'.$download_url.'" type="'.$mime.'">
      Your browser does not support the audio element.
      </audio>';
      break;

    default:
      $attachments = elgg_view_entity_icon($object, "tiny", array(
        'href' => $download_url,
        'img_class' => 'file-photo',
        'is_trusted' => true,
      ));
      $attachments .= '<a href="'.$download_url.'">'.$objectname.'</a>';
      break;
  }  

echo elgg_view('river/elements/layout', array(
	'item' => $item,
	'message' => $excerpt,
  'attachments' => $attachments,
));
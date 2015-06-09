<?php
/**
 * Profile info box
 */

// if admin, display admin links
$admin_links = '';
if (elgg_is_admin_logged_in() && elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
  $text = elgg_echo('admin:options');

  $admin_links = '<ul class="profile-admin-menu-wrapper">';
  $admin_links .= "<li><a rel=\"toggle\" href=\"#profile-menu-admin\">$text&hellip;</a>";
  $admin_links .= '<ul class="profile-admin-menu" id="profile-menu-admin">';
  foreach ($admin as $menu_item) {
    $admin_links .= elgg_view('navigation/menu/elements/item', array('item' => $menu_item));
  }
  $admin_links .= '</ul>';
  $admin_links .= '</li>';
  $admin_links .= '</ul>';  
}

// content links
$content_menu = elgg_view_menu('owner_block', array(
  'entity' => elgg_get_page_owner_entity(),
  'class' => 'profile-content-menu',
));

?>

<?php /* We add mrn here because we're doing stupid things with the grid system. Remove this hack */ ?>
<div class="profile elgg-col-3of3 mrn profile-details">
	<div class="elgg-inner clearfix h-card vcard">
		<?php echo elgg_view('profile/owner_block'); ?>
		<?php echo elgg_view('profile/details'); ?>
	</div>
</div>
<div class="profile elgg-col-2of3">
  <div class=" clearfix">
    <?php echo elgg_view('profile/river'); ?>
  </div>
</div>

<div class="profile elgg-col-1of3">
  <div class="profile-menu">
    <?php echo $content_menu ?>
    <?php echo $admin_links ?>
  </div>
</div>


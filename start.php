<?php
/**
 * Time Theme plugin
 *
 * @package Time Theme  
 * */

elgg_register_event_handler('init','system','time_theme_init');

function time_theme_init() {

	elgg_register_event_handler('pagesetup', 'system', 'time_theme_pagesetup', 1000);

	// theme specific CSS
	elgg_extend_view('css/elgg', 'time_theme/css');
	// theme specific CSS
	elgg_extend_view('css/elgg', 'extractability/css');

	
	elgg_register_plugin_hook_handler('head', 'page', 'time_theme_setup_head');
	// registered with priority < 500 so other plugins can remove likes
	elgg_unregister_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup');
	elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup');
	elgg_unregister_plugin_hook_handler('register', 'menu:river', '_elgg_river_menu_setup');
	elgg_unregister_plugin_hook_handler('register', 'menu:entity', '_elgg_entity_menu_setup');

	elgg_register_plugin_hook_handler('register', 'menu:river', '_my_elgg_river_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:entity', '_my_elgg_entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:river', 'my_likes_river_menu_setup', 401);
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'my_likes_entity_menu_setup', 401);

	elgg_unregister_widget_type('river_widget');
	elgg_register_js('slidebars', 'mod/time_theme/lib/slidebars/slidebars.js', 'head');
	elgg_register_css('slidebars', 'mod/time_theme/lib/slidebars/slidebars.css');
	elgg_register_js('flowplayer', 'mod/time_theme/lib/flowplayer/flowplayer.min.js', 'head');
	elgg_register_css('flowplayer', 'mod/time_theme/lib/flowplayer/minimalist.css');
	elgg_register_css('font-awesome', 'mod/time_theme/lib/font-awesome/css/font-awesome.min.css');

	$base = elgg_get_plugins_path() . 'time_theme';
	
	// non-members do not get visible links to RSS feeds
	if (!elgg_is_logged_in()) {
		elgg_unregister_plugin_hook_handler('output:before', 'layout', 'elgg_views_add_rss_link');
	}

	// Register page handler
	elgg_unregister_page_handler('activity', 'elgg_river_page_handler');
	elgg_register_page_handler('activity', 'river_auto_update_page_handler');
	
	// extend js view
	elgg_extend_view("js/elgg", "js/time_theme/functions");
}

function river_auto_update_page_handler($page) {
	$base = elgg_get_plugins_path() . 'time_theme';
	
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

	// make ajax procedure visible to the activity page
	if ($page[0] == "proc") {		
		include($base."/procedures/" . $page[1] . ".php");			
	} 
	else {
		$page_type = elgg_extract(0, $page, 'all');
		$page_type = preg_replace('[\W]', '', $page_type);
		if ($page_type == 'owner') {
			$page_type = 'mine';
		}	
		set_input('page_type', $page_type);
	}	
	
	require_once($base."/pages/river.php");
	return true;
}

/**
 * Rearrange menu items
 */
function time_theme_pagesetup() {

	elgg_unextend_view('page/elements/sidebar', 'search/header');
	//elgg_extend_view('page/elements/topbar', 'search/header', 0);

	elgg_register_menu_item('topbar', array(
		'name' => 'sidebar',
		'href' => "#",
		'text' => '<i class="sb-toggle-left fa fa-bars fa-lg"></i>',
		'priority' => 50,
		'link_class' => '',
	));

	elgg_unregister_menu_item('footer','powered');
		
	if (elgg_is_logged_in()) {
		$user = elgg_get_logged_in_user_entity();
		$username = $user->username;


		elgg_unregister_menu_item('topbar','messages');
		$text = "<i class=\"fa fa-envelope fa-lg\"></i>";
		$tooltip = elgg_echo("messages");
		// get unread messages
		$num_messages = (int)messages_count_unread();
		if ($num_messages != 0) {
			$text .= "<span class=\"elgg-topbar-new\">$num_messages</span>";
			$tooltip .= ": ".elgg_echo("messages:unreadcount", array($num_messages));
		}

		elgg_register_menu_item('topbar', array(
			'name' => 'messages',
			'href' => "messages/inbox/$username",
			'text' => $text,
			'section' => 'alt',
			'priority' => 100,
			'title' => $tooltip,
		));
		
		elgg_register_menu_item('topbar', array(
			'href' => false,
			'name' => 'search',			
			'text' => '<i class="fa fa-search fa-lg"></i>'.elgg_view('search/header'),			
			'priority' => 0,
			'section' => 'alt',
		));

		$text = '<i class="fa fa-users fa-lg"></i>';
		$tooltip = elgg_echo("friends");
		$href = "/friends/".$username;
	  if (elgg_is_active_plugin('friend_request')) {
	  	elgg_unregister_menu_item('topbar', 'friend_request');
			$options = array(
				"type" => "user",
				"count" => true,
				"relationship" => "friendrequest",
				"relationship_guid" => $user->getGUID(),
				"inverse_relationship" => true
			);
			
			$count = elgg_get_entities_from_relationship($options);
			if (!empty($count)) {
				$text .= "<span class=\"elgg-topbar-new\">$count</span>";
				$tooltip = elgg_echo("friend_request:menu").": ".$count;
				$href = "friend_request/" . $username;
			}
	  }

		elgg_unregister_menu_item('topbar', 'friends');
		elgg_register_menu_item('topbar', array(
			'href' => $href,
			'name' => 'friends',
			'text' =>  $text,
			'section' => 'alt',
			'priority' => 200,
			'title' => $tooltip,
		));

		$viewer = elgg_get_logged_in_user_entity();
		elgg_unregister_menu_item('topbar', 'profile');
		elgg_register_menu_item('topbar', array(
			'name' => 'profile',
			'href' => $viewer->getURL(),
			'text' => elgg_view('output/img', array(
				'src' => $viewer->getIconURL('small'),
				'alt' => $viewer->name,
				'title' => elgg_echo('profile'),
				'class' => 'elgg-border-plain elgg-transition',
			)).'<span class="profile-text">'.elgg_get_excerpt($viewer->name, 20).'</span>',
			'priority' => 500,
			'link_class' => 'elgg-topbar-avatar',
			'item_class' => 'elgg-avatar elgg-avatar-topbar',
		));

		elgg_register_menu_item('topbar', array(
			'name' => 'home',
			'text' => '<i class="fa fa-home fa-lg"></i> ',
			'href' => "/",
			'priority' => 90,
			'section' => 'alt',
		));

		elgg_register_menu_item('topbar', array(
			'name' => 'account',
			'text' => '<i class="fa fa-cog fa-lg"></i> ',
			'href' => "#",
			'priority' => 300,
			'section' => 'alt',
			'link_class' => 'elgg-topbar-dropdown',
		));

		if (elgg_is_active_plugin('dashboard')) {
			$item = elgg_unregister_menu_item('topbar', 'dashboard');
			if ($item) {
				$item->setText(elgg_echo('dashboard'));
				$item->setSection('default');
				elgg_register_menu_item('site', $item);
			}
		}

		$item = elgg_unregister_menu_item('extras', 'bookmark');
		if ($item) {
			$item->setText('<i class="fa fa-bookmark fa-lg"></i>');
			elgg_register_menu_item('extras', $item);
		}

	  elgg_unregister_menu_item('extras', 'rss');
		/*if ($item) {
			$item->setText('<i class="fa fa-rss fa-lg"></i>');
			elgg_register_menu_item('extras', $item);
		}*/
		$url = elgg_format_url($url);
		elgg_register_menu_item('extras', array(
			'name' => 'rss',
			'text' => '<i class="fa fa-rss fa-lg"></i>',
			'href' => $url,
			'title' => elgg_echo('feed:rss'),
		));
		
		$item = elgg_get_menu_item('topbar', 'usersettings');
		if ($item) {
			$item->setParentName('account');
			$item->setText(elgg_echo('settings'));
			$item->setPriority(103);
		}

		$item = elgg_get_menu_item('topbar', 'logout');
		if ($item) {
			$item->setParentName('account');
			$item->setText(elgg_echo('logout'));
			$item->setPriority(104);
		}

		$item = elgg_get_menu_item('topbar', 'administration');
		if ($item) {
			$item->setParentName('account');
			$item->setText(elgg_echo('admin'));
			$item->setPriority(101);
		}

		if (elgg_is_active_plugin('site_notifications')) {
			$item = elgg_get_menu_item('topbar', 'site_notifications');
			if ($item) {
				$item->setParentName('account');
				$item->setText(elgg_echo('site_notifications:topbar'));
				$item->setPriority(102);
			}
		}

		if (elgg_is_active_plugin('reportedcontent')) {
			$item = elgg_unregister_menu_item('footer', 'report_this');
			if ($item) {
				$item->setText('<i class="fa fa-flag fa-lg"></i>');
				$item->setPriority(500);
				$item->setSection('default');
				elgg_register_menu_item('extras', $item);
			}
		}
		
	}
}

/**
 * Register items for the html head
 *
 * @param string $hook Hook name ('head')
 * @param string $type Hook type ('page')
 * @param array  $data Array of items for head
 * @return array
 */
function time_theme_setup_head($hook, $type, $data) {
	$data['metas']['viewport'] = array(
		'name' => 'viewport',
		'content' => 'width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no',
	);

	$data['links']['apple-touch-icon'] = array(
		'rel' => 'apple-touch-icon',
		'href' => elgg_normalize_url('mod/time_theme/graphics/favicon/favicon-128.png'),
	);

	// favicons
	$data['links']['icon-ico'] = array(
		'rel' => 'icon',
		'href' => elgg_normalize_url('mod/time_theme/graphics/favicon/favicon.ico'),
	);
	$data['links']['icon-vector'] = array(
		'rel' => 'icon',
		'sizes' => '16x16 32x32 48x48 64x64 128x128',
		'type' => 'image/svg+xml',
		'href' => elgg_normalize_url('mod/time_theme/graphics/favicon/favicon.svg'),
	);
	$data['links']['icon-16'] = array(
		'rel' => 'icon',
		'sizes' => '16x16',
		'type' => 'image/png',
		'href' => elgg_normalize_url('mod/time_theme/graphics/favicon/favicon-16.png'),
	);
	$data['links']['icon-32'] = array(
		'rel' => 'icon',
		'sizes' => '32x32',
		'type' => 'image/png',
		'href' => elgg_normalize_url('mod/time_theme/graphics/favicon/favicon-32.png'),
	);
	$data['links']['icon-64'] = array(
		'rel' => 'icon',
		'sizes' => '64x64',
		'type' => 'image/png',
		'href' => elgg_normalize_url('mod/time_theme/graphics/favicon/favicon-64.png'),
	);
	$data['links']['icon-128'] = array(
		'rel' => 'icon',
		'sizes' => '128x128',
		'type' => 'image/png',
		'href' => elgg_normalize_url('mod/time_theme/graphics/favicon/favicon-128.png'),
	);

	return $data;
}


/**
 * Add likes to entity menu at end of the menu
 */
function my_likes_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	/* @var ElggEntity $entity */

	if ($entity->canAnnotate(0, 'likes')) {
		$hasLiked = \Elgg\Likes\DataService::instance()->currentUserLikesEntity($entity->guid);
		
		// Always register both. That makes it super easy to toggle with javascript
		$return[] = ElggMenuItem::factory(array(
			'name' => 'likes',
			'href' => elgg_add_action_tokens_to_url("/action/likes/add?guid={$entity->guid}"),
			'text' => '<i class="fa fa-thumbs-o-up fa-lg"></i>',
			'title' => elgg_echo('likes:likethis'),
			'item_class' => $hasLiked ? 'hidden' : '',
			'priority' => 100,
		));
		
		$return[] = ElggMenuItem::factory(array(
			'name' => 'unlike',
			'href' => elgg_add_action_tokens_to_url("/action/likes/delete?guid={$entity->guid}"),
			'text' => '<i class="fa  fa-thumbs-up fa-lg" style="color:#50C28C"></i>',
			'title' => elgg_echo('likes:remove'),
			'item_class' => $hasLiked ? '' : 'hidden',
			'priority' => 100,
		));
	}
	
	// likes count
	$count = elgg_view('likes/count', array('entity' => $entity));
	if ($count) {
		$options = array(
			'name' => 'likes_count',
			'text' => $count,
			'href' => false,
			'priority' => 101,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Add a like button to river actions
 */
function my_likes_river_menu_setup($hook, $type, $return, $params) {
	if (!elgg_is_logged_in() || elgg_in_context('widgets')) {
		return;
	}

	$item = $params['item'];
	/* @var ElggRiverItem $item */

	// only like group creation #3958
	if ($item->type == "group" && $item->view != "river/group/create") {
		return;
	}

	// don't like users #4116
	if ($item->type == "user") {
		return;
	}

	if ($item->annotation_id != 0) {
		return;
	}

	$object = $item->getObjectEntity();
	if (!$object || !$object->canAnnotate(0, 'likes')) {
		return;
	}

	$hasLiked = \Elgg\Likes\DataService::instance()->currentUserLikesEntity($object->guid);

	// Always register both. That makes it super easy to toggle with javascript
	$return[] = ElggMenuItem::factory(array(
		'name' => 'likes',
		'href' => elgg_add_action_tokens_to_url("/action/likes/add?guid={$object->guid}"),
		'text' => '<i class="fa fa-thumbs-o-up fa-lg"></i>',
		'title' => elgg_echo('likes:likethis'),
		'item_class' => $hasLiked ? 'hidden' : '',
		'priority' => 100,
	));
	$return[] = ElggMenuItem::factory(array(
		'name' => 'unlike',
		'href' => elgg_add_action_tokens_to_url("/action/likes/delete?guid={$object->guid}"),
		'text' => '<i class="fa  fa-thumbs-up fa-lg" style="color:#50C28C"></i>',
		'title' => elgg_echo('likes:remove'),
		'item_class' => $hasLiked ? '' : 'hidden',
		'priority' => 100,
	));

	// likes count
	$count = elgg_view('likes/count', array('entity' => $object));
	if ($count) {
		$return[] = ElggMenuItem::factory(array(
			'name' => 'likes_count',
			'text' => $count,
			'href' => false,
			'priority' => 101,
		));
	}

	return $return;
}

/**
 * Add the comment and like links to river actions menu
 * @access private
 */
function _my_elgg_river_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$item = $params['item'];
		/* @var \ElggRiverItem $item */
		$object = $item->getObjectEntity();
		// add comment link but annotations cannot be commented on
		if ($item->annotation_id == 0) {
			if ($object->canComment()) {
				$options = array(
					'name' => 'comment',
					'href' => "#comments-add-$object->guid",
					'text' => '<i class="fa fa-comment-o fa-lg"></i>',
					'title' => elgg_echo('comment:this'),
					'rel' => 'toggle',
					'priority' => 200,
				);
				$return[] = \ElggMenuItem::factory($options);
			}
		}
		
		if (elgg_is_admin_logged_in()) {
			$options = array(
				'name' => 'delete',
				'href' => elgg_add_action_tokens_to_url("action/river/delete?id=$item->id"),
				'text' => '<i class="fa fa-trash-o fa-lg"></i>',
				'title' => elgg_echo('river:delete'),
				'confirm' => elgg_echo('deleteconfirm'),
				'priority' => 300,
				'item_class' => 'align-right',
			);
			$return[] = \ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Entity menu is list of links and info on any entity
 * @access private
 */
function _my_elgg_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}
	
	$entity = $params['entity'];
	/* @var \ElggEntity $entity */
	$handler = elgg_extract('handler', $params, false);

	// access
	if (elgg_is_logged_in()) {
		$access = elgg_view('output/access', array('entity' => $entity));
		$options = array(
			'name' => 'access',
			'text' => $access,
			'href' => false,
			'priority' => 100,
		);
		$return[] = \ElggMenuItem::factory($options);
	}
	
	if ($entity->canEdit() && $handler) {
		// edit link
		$options = array(
			'name' => 'edit',
			'text' => '<i class="fa fa-edit fa-lg"></i>',
			'title' => elgg_echo('edit:this'),
			'href' => "$handler/edit/{$entity->getGUID()}",
			'priority' => 200,
		);
		$return[] = \ElggMenuItem::factory($options);

		// delete link
		$options = array(
			'name' => 'delete',
			'text' => '<i class="fa fa-trash-o fa-lg"></i>',
			'title' => elgg_echo('delete:this'),
			'href' => "action/$handler/delete?guid={$entity->getGUID()}",
			'confirm' => elgg_echo('deleteconfirm'),
			'priority' => 300,
		);
		$return[] = \ElggMenuItem::factory($options);
	}

	return $return;
}


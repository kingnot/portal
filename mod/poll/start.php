<?php

elgg_register_event_handler('init','system','poll_init');

function poll_init() {

	elgg_register_library('elgg:poll', elgg_get_plugins_path() . 'poll/models/model.php');

	// Set up menu
	elgg_register_menu_item('site', array(
		'name' => 'poll',
		'text' => elgg_echo('poll'),
		'href' => 'poll/all'
	));

	// Extend system CSS with our own styles, which are defined in the poll/css view
	elgg_extend_view('css/elgg','poll/css');

	// Extend hover-over menu
	elgg_extend_view('profile/menu/links','poll/menu');

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('poll','poll_page_handler');
	// Register a fallback pagehandler if the polls plugin has been used previously
	elgg_register_page_handler('polls','polls_page_handler');

	// Register a URL handler for poll posts
	elgg_register_entity_url_handler('object','poll','poll_url');

	// notifications
	$send_notification = elgg_get_plugin_setting('send_notification', 'poll');
	if (!$send_notification || $send_notification != 'no') {
		register_notification_object('object', 'poll', elgg_echo('poll:new'));
		elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'poll_prepare_notification');
	}

	// add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'poll_owner_block_menu');

	// Register entity type
	elgg_register_entity_type('object','poll');

	// register the JavaScript
	$js = elgg_get_simplecache_url('js', 'poll/js');
	elgg_register_simplecache_view('js/poll/js');
	elgg_register_js('elgg.poll', $js);

	// add group widget
	$group_poll = elgg_get_plugin_setting('group_poll', 'poll');
	if (!$group_poll || $group_poll != 'no') {
		elgg_extend_view('groups/tool_latest', 'poll/group_module');
	}

	if (!$group_poll || ($group_poll == 'yes_default')) {
		add_group_tool_option('poll', elgg_echo('poll:enable_poll'), true);
	} else if ($group_poll == 'yes_not_default') {
		add_group_tool_option('poll', elgg_echo('poll:enable_poll'), false);
	}

	//add widgets
	elgg_register_widget_type('poll', elgg_echo('poll:my_widget_title'), elgg_echo('poll:my_widget_description'));
	elgg_register_widget_type('latestpoll', elgg_echo('poll:latest_widget_title'), elgg_echo('poll:latest_widget_description'), "dashboard");
	$poll_front_page = elgg_get_plugin_setting('front_page','poll');
	if($poll_front_page == 'yes') {
		elgg_register_widget_type('poll_individual', elgg_echo('poll:individual'), elgg_echo('poll_individual:widget:description'), "dashboard");
	}
	if (elgg_is_active_plugin('widget_manager')) {
		elgg_register_widget_type('latestpoll_index', elgg_echo('poll:latest_widget_title'), elgg_echo('poll:latest_widget_description'), "index");
		if (!$group_poll || $group_poll != 'no') {
			elgg_register_widget_type('latestgrouppoll', elgg_echo('poll:latestgroup_widget_title'), elgg_echo('poll:latestgroup_widget_description'), "groups");
		}
		if($poll_front_page == 'yes') {
			elgg_register_widget_type('poll_individual_index', elgg_echo('poll:individual'), elgg_echo('poll_individual:widget:description'), "index");
		}

		//register title urls for widgets
		elgg_register_plugin_hook_handler('widget_url', 'widget_manager', "poll_widget_urls", 499);
	}

	// Register actions
	$action_path = elgg_get_plugins_path() . 'poll/actions/poll';
	elgg_register_action("poll/edit","$action_path/edit.php");
	elgg_register_action("poll/delete","$action_path/delete.php");
	elgg_register_action("poll/vote","$action_path/vote.php");
	elgg_register_action("poll/convert","$action_path/convert.php", "admin");
}


/**
 * poll page handler; allows the use of fancy URLs
 *
 * @param array $page From the page_handler function
 * @return true|false Depending on success
 */
function poll_page_handler($page) {
	elgg_load_library('elgg:poll');

	$page_type = $page[0];
	switch($page_type) {
		case "view":
			echo poll_get_page_view($page[1]);
			break;
		case "all":
			echo poll_get_page_list($page_type);
			break;
		case "add":
		case "edit":
			$container = null;
			if(isset($page[1])){
				$container = $page[1];
			}
			echo poll_get_page_edit($page_type, $container);
			break;
		case "friends":
		case "owner":
			$username = $page[1];
			$user = get_user_by_username($username);
			$user_guid = $user->guid;
			echo poll_get_page_list($page_type, $user_guid);
			break;
		case "group":
			echo poll_get_page_list($page_type, $page[1]);
			break;
		default:
			$user = get_user_by_username($page_type);
			if ($user instanceof ElggUser) {
				if (isset($page[1])) {
					switch($page[1]) {
						case "read":
							forward("/poll/view/{$page[2]}");
							break;
						case "friends":
							forward("/poll/friends/{$user->username}");
							break;
					}
				// If the URL is just 'poll/username' forward to polls page of this user
				} else {
					forward("/poll/owner/{$user->username}");
					break;
				}
			}
			return false;
			break;
	}
	return true;
}

/**
 * polls page handler redirect any calls made to polls/* to poll/*
 */
function polls_page_handler($page) {

	$append = '';
	foreach($page as $segment) {
		$append .= $segment . "/";
	}
	forward("/poll/{$append}");
	return true;
}


/**
 * Populates the ->getUrl() method for poll objects
 *
 * @param ElggEntity $poll post poll post entity
 * @return string poll post URL
 */
function poll_url($poll) {
	$title = elgg_get_friendly_title($poll->title);
	return  "poll/view/" . $poll->guid . "/" . $title;
}

/**
 * Add a menu item to an owner block
 */
function poll_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "poll/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('poll', elgg_echo('poll'), $url);
		$return[] = $item;
	} else {
		elgg_load_library('elgg:poll');
		if (poll_activated_for_group($params['entity'])) {
			$url = "poll/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('poll', elgg_echo('poll:group_poll'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 *
 * Prepare a notification message about a created poll
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return mixed
 */
function poll_prepare_notification($hook, $type, $notification, $params) {

	$entity = $params['entity'];
	$to_entity = $params['to_entity'];
	$method = $params['method'];

	if (elgg_instanceof($entity, 'object', 'poll')) {
		$user = elgg_get_logged_in_user_entity();
		if (!$user) {
			$user = $entity->getOwnerEntity();
		}
		return elgg_echo('poll:notify:body', array($user->name, $entity->title, $entity->getURL()));

	}
	return null;
}

function poll_widget_urls($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];

	if(empty($result) && ($widget instanceof ElggWidget)) {
		$owner = $widget->getOwnerEntity();
		switch($widget->handler) {
			case "poll":
				if($owner instanceof ElggUser){
					$result = "/poll/owner/{$owner->username}/all";
				} else {
					$result = "/poll/all";
				}
				break;
			case "latestpoll":
			case "poll_individual":
			case "latestpoll_index":
			case "poll_individual_index":
				$result = "/poll/all";
				break;
			case "latestgrouppoll":
				if($owner instanceof ElggGroup){
					$result = "/poll/group/{$owner->guid}/all";
				} else {
					$result = "/poll/owner/{$owner->username}/all";
				}
				break;
		}
	}
	return $result;
}

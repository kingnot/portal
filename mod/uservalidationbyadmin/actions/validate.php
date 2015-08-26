<?php
/**
 * Validate a user or users by guid
 *
 * @package Elgg.Core.Plugin
 * @subpackage uservalidationbyadmin
 */
$user_guids = get_input('user_guids');
$code = get_input('code');
$error = FALSE;
elgg_set_ignore_access(true);

if (!$user_guids) {
	register_error(elgg_echo('uservalidationbyadmin:errors:unknown_users'));
	forward(REFERRER);
}

$access = access_get_show_hidden_status();
access_show_hidden_entities(TRUE);

foreach ($user_guids as $guid) {
	$user = get_entity($guid);
	if (!$user instanceof ElggUser) {
		$error = TRUE;
		continue;
	}

	//check the code from the email
	if($code === uservalidationbyadmin_generate_code($user->guid, $user->email)){
		// only validate if not validated
		$is_validated = elgg_get_user_validation_status($guid);
		$validate_success = elgg_set_user_validation_status($guid, TRUE, 'manual');
	}
	if ($is_validated !== FALSE || !($validate_success && $user->enable())) {
		$error = TRUE;
		continue;
	} else {
		$site = elgg_get_site_entity();
		$subject = elgg_echo('user:validate:subject', array($user->name));
		$body = elgg_echo('user:validate:body', array($user->name, $site->name, $user->username, $site->name, $site->url));
		$result = notify_user($user->guid, $site->guid, $subject, $body, NULL, 'email');	
	}
}

access_show_hidden_entities($access);

if (count($user_guids) == 1) {
	$message_txt = elgg_echo('uservalidationbyadmin:messages:validated_user');
	$error_txt = elgg_echo('uservalidationbyadmin:errors:could_not_validate_user');
} else {
	$message_txt = elgg_echo('uservalidationbyadmin:messages:validated_users');
	$error_txt = elgg_echo('uservalidationbyadmin:errors:could_not_validate_users');
}

if ($error) {
	register_error($error_txt);
} else {
	system_message($message_txt);
}
elgg_set_ignore_access(false);
forward(REFERRER);

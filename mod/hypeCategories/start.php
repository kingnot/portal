<?php

/**
 * Traverse Categories for Elgg
 *
 * @package hypeJunction
 * @subpackage Categories
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyright (c) 2011-2014, Ismayil Khayredinov
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

namespace hypeJunction\Categories;

const PLUGIN_ID = 'hypeCategories';
const PAGEHANDLER = 'categories';

// Load libraries
require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/lib/events.php';
require_once __DIR__ . '/lib/hooks.php';
require_once __DIR__ . '/lib/page_handlers.php';
require_once __DIR__ . '/lib/integrations.php';

define('HYPECATEGORIES_RELEASE', 1394789886);

define('HYPECATEGORIES_RELATIONSHIP', 'filed_in');
define('HYPECATEGORIES_SUBTYPE', 'hjcategory');
define('HYPECATEGORIES_ENTITY_MENU', elgg_get_plugin_setting('entity_menu', PLUGIN_ID));
define('HYPECATEGORIES_GROUP_CATEGORIES', elgg_get_plugin_setting('group_categories', PLUGIN_ID));
define('HYPECATEGORIES_GROUP_TREE_SITE', elgg_get_plugin_setting('group_tree_site', PLUGIN_ID));
define('HYPECATEGORIES_INPUT_MULTIPLE', (bool) elgg_get_plugin_setting('input_multiple', PLUGIN_ID));

/**
 * Init events
 */
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\setup_taxonomy');
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');
elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init_groups');
elgg_register_event_handler('pagesetup', 'system', __NAMESPACE__ . '\\pagesetup');
elgg_register_event_handler('upgrade', 'system', __NAMESPACE__ . '\\upgrade');



/**
 * Entity events
 */
elgg_register_event_handler('create', 'all', __NAMESPACE__ . '\\update_entity_categories');
elgg_register_event_handler('update', 'all', __NAMESPACE__ . '\\update_entity_categories');


function init() {

	/**
	 * JS and CSS
	 */
	elgg_register_js('jquery.nestedsortable.js', '/mod/hypeCategories/vendors/nestedSortable/jquery.mjs.nestedSortable.js');

	elgg_register_simplecache_view('js/framework/categories/manage');
	elgg_register_js('categories.manage.js', elgg_get_simplecache_url('js', 'framework/categories/manage'));

	elgg_register_simplecache_view('js/framework/categories/tree');
	elgg_register_js('categories.tree.js', elgg_get_simplecache_url('js', 'framework/categories/tree'));

	elgg_extend_view('css/elgg', 'css/framework/categories/base');
	elgg_extend_view('css/admin', 'css/framework/categories/base');

	/**
	 * Actions
	 */
	elgg_register_action('categories/manage', __DIR__ . '/actions/categories/manage.php');
	elgg_register_action(PLUGIN_ID . '/settings/save', __DIR__ . '/actions/settings/save.php', 'admin');

	/**
	 * URL and Page handlers
	 */
	elgg_register_page_handler(PAGEHANDLER, __NAMESPACE__ . '\\page_handler');
	elgg_register_page_handler('category', __NAMESPACE__ . '\\page_handler'); // alias

	elgg_register_entity_url_handler('object', HYPECATEGORIES_SUBTYPE, __NAMESPACE__ . '\\category_url_handler');

	elgg_register_plugin_hook_handler('entity:icon:url', 'object', __NAMESPACE__ . '\\category_icon_url');
	
	/**
	 * Widget
	 */
	elgg_register_widget_type('hypeCategories', elgg_echo('categories'), elgg_echo('categories:group_module'), "groups");
	
	/**
	 * Menus
	 */
	elgg_register_plugin_hook_handler('register', 'menu:entity', __NAMESPACE__ . '\\entity_menu_setup');
	elgg_register_plugin_hook_handler('register', 'menu:categories', __NAMESPACE__ . '\\tree_menu_setup');

	/**
	 * Search
	 */
	elgg_register_entity_type('object', HYPECATEGORIES_SUBTYPE);

	// Register universal_categories metadata for search
	elgg_register_tag_metadata_name('universal_categories');

	/**
	 * Views  Groups
	 */


	elgg_extend_view('page/elements/sidebar', 'framework/categories/sidebar');
	elgg_extend_view('groups/tool_latest', 'framework/categories/group_module');


	// Load fonts
	elgg_extend_view('page/elements/head', 'framework/fonts/font-awesome');
	elgg_extend_view('page/elements/head', 'framework/fonts/open-sans');
	
}

function setup_taxonomy() {

	$type_subtype_pairs = elgg_get_plugin_setting('type_subtype_pairs', PLUGIN_ID);
	elgg_set_config('taxonomy_type_subtype_pairs', ($type_subtype_pairs) ? unserialize($type_subtype_pairs) : array());

	$types = array();
	$subtypes = array();

	$taxonomy_type_subtype_pairs = elgg_get_config('taxonomy_type_subtype_pairs');
	if ($taxonomy_type_subtype_pairs) {
		foreach ($taxonomy_type_subtype_pairs as $tsp) {
			list($type, $subtype) = explode(':', $tsp);
			$types[] = $type;
			$subtypes[] = $subtype;
		}
	}

	elgg_set_config('taxonomy_types', array_unique($types));
	elgg_set_config('taxonomy_subtypes', array_unique($subtypes));
}

/**
 * Initialize group related functionality if the settings say so
 * @return void
 */
function init_groups() {
	if (!HYPECATEGORIES_GROUP_CATEGORIES) {
		return;
	}
	add_group_tool_option('categories', elgg_echo('categories:groupoption:enable'), true);
}






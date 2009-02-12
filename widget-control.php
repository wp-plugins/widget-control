<?php
/*
 Plugin Name: Widget Control
 Plugin URI: http://www.feedmeastraycat.net/widget-control/
 Description: Widget Control helps you keep more Control of your Widgets.
 Version: 0.0.1
 Author: David M&aring;rtensson
 Author URI: http://www.feedmeastraycat.net/
 */

/*  Copyright 2009  David Mårtennsson  (email: david.martensson@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



/**
 * Set Widget Control version
 */
define('WidgetControl_VERSION', '0.0.1');


/**
 * Set text domain
 */
load_plugin_textdomain('WidgetControl');


/**
 * Activate plugin
 */
function WidgetControl_activate() {
	
	// Need upgrade?
	$installed_version = get_option('WidgetControl_version', '0.0.1');
	if (version_compare($installed_version, WidgetControl_VERSION, '<')) {
		WidgetControl_upgrade($installed_version);
	}
	
}
register_activation_hook(__FILE__, 'WidgetControl_activate');


/**
 * Upgrade plugin
 */
function WidgetControl_upgrade($current_version) {
	
	// 0.0.1 to 1.0.0 upgrade (placeholder for future use)
	/*
	if (version_compare($installed_version, '1.0.0', '<')) {
	}
	*/
	
	// Uppdate/Add version
	if (!update_option('WidgetControl_version', WidgetControl_VERSION)) {
		add_option('WidgetControl_version', WidgetControl_VERSION);
	}
}


/**
 * Init plugin
 */
function WidgetControl_init() {
	
	// Run admin action
	if (is_admin() && !empty($_POST['WidgetControl_action'])) {
		WidgetControl_admin_actions();
	}
	
}
add_action('init', 'WidgetControl_init');


/**
 * Add options page
 */
function WidgetControl_admin_menu() {
	if (current_user_can('manage_options')) {
		add_options_page(
			'Widget Control',
			'Widget Control',
			1,
			basename(__FILE__),
			'WidgetControl_options_page'
		);
	}
}
add_action('admin_menu', 'WidgetControl_admin_menu');


/**
 * Options page
 */
function WidgetControl_options_page() {
	global $wp_registered_widgets;
	$active_widgets = array();
	foreach ($wp_registered_widgets AS $widget) {
		$sidebar = is_active_widget($widget['callback'], $widget['id']);
		if ($sidebar) {
			$active_widgets[] = $widget['name'];
		}
	}
	?>
	<div class="wrap" id="WidgetControl-options">
		<h2><?=__('Widget Control', 'WidgetControl')?></h2>
		<p>
			<strong><?=__('Active widgets', 'WidgetControl')?>:</strong><br/>
			<?php
			if (!empty($active_widgets)) {
				print(implode($active_widgets, ", "));
			}
			else {
				print("<em>".__('You have no active widgets ...', 'WidgetControl')."</em>");
			}
			?>
		</p>
		<form 
			method="post" 
			action="options-general.php" 
			name="WidgetControl_deactivate_widgets"
			onsubmit="return confirm('<?=htmlspecialchars(__('Do you wish to deactivate all widgets? You will have to activate them again via Appearence > Widgets.'))?>');"
		>
		<input type="hidden" name="WidgetControl_action" value="WidgetControl_deactivate_widgets" />
		<input 
			type="submit" 
			name="WidgetControl-deactivate-all-widgets"
			id="WidgetControl-deactivate-all-widgets" 
			class="button-primary" 
			value="<?=htmlspecialchars(__('Disable all widgets', 'WidgetControl'))?>"
		/>
		</form>
	</div>
	<?php
}


/**
 * Runs a Widget Control action
 */
function WidgetControl_admin_actions() {
	global $wpdb;
	switch ($_POST['WidgetControl_action']) {
		
		// Disable all Widgets
		case "WidgetControl_deactivate_widgets":
			WidgetControl_deactivateAllWidgets();
		break;
		
	}
	
	// Relocate back
	header("Location: options-general.php?page=".basename(__FILE__)."&updated=true");
	exit;
}


/**
 * Deactivate all widgets
 */
function WidgetControl_deactivateAllWidgets() {
	/*
	global $wp_registered_widgets;
	$active_widgets = array();
	foreach ($wp_registered_widgets AS $widget) {
		$sidebar = is_active_widget($widget['callback'], $widget['id']);
		if ($sidebar) {
			echo $widget['id'];die;
			unregister_sidebar_widget('calendar');
			break;
		}
	}
	*/
	wp_set_sidebars_widgets(array());
}





?>
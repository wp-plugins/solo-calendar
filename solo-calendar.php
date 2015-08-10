<?php
/*
Plugin Name: SoloCalendar
Plugin URI:  http://solocalendar.com
Description: This plugin adds a little button in your post editor with all booking forms from your SoloCalendar account.
Version:     1.0
Author:      Overseas Services LP
Author URI:  http://solocalendar.com
*/
defined('ABSPATH') or die( 'Access denied' );

require_once(dirname(__FILE__).'/class.php');
require_once(dirname(__FILE__).'/widget.php');

$solocalendar = new Solocalendar();

// register admin editor tinyMCE button sf plugin
add_action('admin_head', array($solocalendar, 'addMceButton'));

// prepare direct call to attach js.php file with plugin
add_filter('query_vars', array($solocalendar, 'tinyMcePluginQueryVars'));
add_action('parse_request', array($solocalendar, 'tinyMcePluginParseRequest'));


// add admin style and scripts
add_action('admin_enqueue_scripts', array($solocalendar, 'addAdminScript'));

// register admin menu
add_action('admin_menu', array($solocalendar, 'addAdminMenu'));

// register frontend code handler
add_shortcode('solocalendar', array($solocalendar, 'addShortcodeHandler'));

// register sidebar widget
add_action('widgets_init', create_function('', 'return register_widget("SolocalendarWidget");'));

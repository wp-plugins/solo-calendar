<?php

class Solocalendar
{
	const PLUGIN_NAME = 'solocalendar';
	const PLUGIN_FOLDER = 'solo-calendar';


	public function addMceButton() {
		// ignore users with no access
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;

		// ignore not rich editor mode
		if (!get_user_option('rich_editing') == 'true') return;

		// register plugin
		add_filter('mce_external_plugins', array($this, 'registerPlugin'));
		add_filter('mce_buttons', array($this, 'registerButton'));
	}

	public function registerButton($buttons)
	{
		array_push($buttons, "separator", self::PLUGIN_NAME);
		return $buttons;
	}

	public function registerPlugin($plugin_array)
	{
		if (get_user_option('rich_editing') == 'true') {
			$plugin_array[self::PLUGIN_NAME] = get_site_url().'/index.php?scplugin';
			//plugins_url(self::PLUGIN_FOLDER.'/tinymce-plugin.js.php');
			return $plugin_array;
		}
	}

	public function addAdminScript()
	{
		wp_enqueue_script('jquery');
		wp_register_style('solo_calendar_admin_style', plugins_url(self::PLUGIN_FOLDER.'/css/admin.css'));
		wp_enqueue_style('solo_calendar_admin_style');
	}

	function addShortcodeHandler($snippet)
	{
		if ((!is_array($snippet)) || (empty($snippet['uid']))) return;

		$code = '<script type="text/javascript" src="'.$this->getCreateOrderUrl($snippet['uid']).'"></script>';
		return do_shortcode($code) ;
	}

	public function addAdminMenu()
	{
		add_menu_page('Solo Calendar', 'Solo Calendar', 'manage_options', 'solo-calendar-settings', array($this, 'renderSettings'), plugins_url(self::PLUGIN_FOLDER.'/images/logo16.png'));

		add_submenu_page('solo-calendar-settings', 'Settings', 'Settings', 'manage_options', 'solo-calendar-settings', array($this, 'renderSettings'));

		add_submenu_page('solo-calendar-settings', 'About', 'About', 'manage_options', 'solo-calendar-about', array($this, 'renderAbout'));
	}

	public function renderSettings()
	{
		include(dirname(__FILE__).'/admin/settings.php');
	}

	public function renderAbout()
	{
		include(dirname(__FILE__).'/admin/about.php');
	}

	function tinyMcePluginQueryVars($vars)
	{
		$vars[] = 'scplugin';
		return $vars;
	}

	function tinyMcePluginParseRequest($wp)
	{
		if (array_key_exists('scplugin', $wp->query_vars)) {
			require( dirname( __FILE__ ).'/tinymce-plugin.js.php' );
			die;
		}
	}

	public static function getButtonsList($key = null)
	{
		// get the key
		if (!$key) $key = get_option('solocalendar_api_key');

		// check errors
		if (!$key)
			return 'Please set up Solo Calendar api key';

		if (($content = file_get_contents(self::getApiUrl($key)))===false)
			return 'Cannot access Solo Calendar API service';

		if (!$data = json_decode($content, true))
			return 'Invalid response';

		if (!empty($data['error']))
			return $data['error'];

		if (empty($data['forms']))
			return 'Invalid data';

		// return
		return $data['forms'];
	}

	private function getCreateOrderUrl($uid)
	{
		$domain = $_SERVER['REMOTE_ADDR']=='127.0.0.1' ? 'solocalendar.loc' : 'solocalendar.com';
		return "http://{$domain}/solos/createorder/loader/uid/{$uid}";
	}

	private static function getApiUrl($key)
	{
		$domain = $_SERVER['REMOTE_ADDR']=='127.0.0.1' ? 'solocalendar.loc' : 'solocalendar.com';
		return "http://{$domain}/api/plugin/forms/api_key/{$key}";
	}
}
<?php
if (!is_user_logged_in()) die('You must be logged in to access this script.');
require_once(dirname(__FILE__).'/class.php');
$forms = Solocalendar::getButtonsList();
if (!is_array($forms)) exit;
?>
(function() {
	tinymce.PluginManager.add('<?=Solocalendar::PLUGIN_NAME; ?>', function( editor, url ) {
		editor.addButton( '<?=Solocalendar::PLUGIN_NAME; ?>', {
			title: 'Insert SoloCalendar Form',
			type: 'menubutton',
			icon: 'icon solo-calendar-icon',
			menu: [
			<?php foreach ($forms as $key=>$val) { ?>
				{
					text: '<?=addslashes($val['title']); ?>',
					value: '[solocalendar<?=empty($val['uid']) ? '' : ' uid="'.addslashes($val['uid']).'"'; ?><?=empty($val['title']) ? '' : ' title="'.addslashes($val['title']).'"'; ?>]',
					onclick: function() {
						editor.insertContent(this.value());
					}
				},
			<?php } ?>
           ]
        });
    });

})();
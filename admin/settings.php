<?php
global $wpdb;
$sc_message = '';
$sc_message_class = 'error';
$sc_forms = array();


if (isset($_POST['solocalendar_api_key'])) {
	$key = trim($_POST['solocalendar_api_key']);
	if (!preg_match('/^[a-zA-Z0-9\-_]{32}$/', $key)) {
		$sc_message = 'Invalid format of API key';
	} else {
		$sc_forms = Solocalendar::getButtonsList($key);
		if (!is_array($sc_forms)) $sc_message = $sc_forms;
	}

	if (!$sc_message) {
		update_option('solocalendar_api_key', $key);
		$sc_message = 'Settings updated successfully';
		$sc_message_class = 'notice';
	}
} else {
	if ($key = get_option('solocalendar_api_key')) {
		$sc_forms = Solocalendar::getButtonsList($key);
		if (!is_array($sc_forms)) $sc_message = $sc_forms;
	}
}

?>
<div class="wrap">
	<h2>Solo Calendar Settings</h2>

	<?php if ($sc_message):?>
		<div class="updated settings-error <?=$sc_message_class;?> is-dismissible">
			<p><?=$sc_message;?></p>
			<button class="notice-dismiss" type="button">
				<span class="screen-reader-text">Dismiss this notice.</span>
			</button>
		</div>
	<?php endif;?>


	<form method="post">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="solocalendar_api_key">SoloCalendar API key</label>
				</th>
				<td>
					<input id="solocalendar_api_key" class="regular-text" type="text" value="<?=isset($_POST['solocalendar_api_key']) ?  $_POST['solocalendar_api_key'] : get_option('solocalendar_api_key') ?>" name="solocalendar_api_key">
				</td>
			</tr>
			</tbody>
		</table>

		<p class="submit">
			<input id="submit" class="button button-primary" type="submit" value="Save Changes" name="submit">
		</p>
	</form>


	<?php if ($sc_forms && is_array($sc_forms)):?>
	<h2>Available Solo Calendar forms</h2>
	<table class="widefat">
		<thead>
		<tr>
			<th>Title</th>
<!--			<th>UID</th>-->
		</tr>
		</thead>
		<tbody>
		<?php foreach ($sc_forms as $row): ?>
		<tr>
			<td><?=$row['title'];?></td>
<!--			<td>--><?//$row['uid'];?><!--</td>-->
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif;?>
</div>

<?php
class SolocalendarWidget extends WP_Widget
{

	public function __construct() {
		parent::__construct(
			'solo_calendar_widget',
			__( 'Solo Calendar', 'text_domain' ), // Name
			array( 'description' => __( 'Add solo calendar form to your sidebar', 'text_domain' ), )
		);
	}
 
    function widget($args, $instance)
	{
		echo $args['before_widget'];
		if (!empty( $instance['title'])) {
			echo $args['before_title'].apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		if (!empty($instance['uid'])) echo do_shortcode("[solocalendar uid='{$instance['uid']}']");

		echo $args['after_widget'];
    }
 
    function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags(trim($new_instance['title']));
		$instance['uid'] = strip_tags(trim($new_instance['uid']));
        return $instance;
    }
 
    function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : __('New title', 'text_domain');
		$uid = !empty($instance['uid']) ? $instance['uid'] : __('', 'text_domain');
		$data = Solocalendar::getButtonsList();
		if (!is_array($data)) $data = array();
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('uid'); ?>"><?php _e('Choose form :'); ?></label>
			<select name="<?php echo $this->get_field_name('uid'); ?>">
				<?php
				foreach ($data as $entry) {
					?>
					<option value="<?php echo $entry['uid'];?>" <?php if($uid==$entry['uid'])echo "selected"; ?>><?php echo $entry['title'];?></option>
				<?php
				}
				?>
			</select>
		</p>
		<?php
    }

}
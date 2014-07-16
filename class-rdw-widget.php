<?php
/**
 * Adds the Ravelry Designs Widget... widget
 */
class Rdw_Widget extends WP_Widget
{
	/**
	 * Register widget with WordPress.
	 */
	public function __construct()
	{
		parent::__construct(
			'ravelry_designs_widget',
			__('Ravelry Designs', 'ravelry-designs-widget'),
			array(
				'description'   => __('Display a list of your own designs from Ravelry', 'ravelry-designs-widget'),
			)
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget($args, $instance)
	{
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];

		if (!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

                $name = 'Michelle May';
                $url = RAVELRY_BASE_URL . '/patterns/search.json?query=' . urlencode($name);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, RAVELRY_BASE_URL . '/patterns/search.json?designer=' . urlencode($name));
                curl_setopt($ch, CURLOPT_USERPWD, RAVELRY_ACCESS_KEY . ':' . RAVELRY_PERSONAL_KEY);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                $output = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);

                $data = json_decode($output);

                //var_dump($data->patterns[0]);
                //var_dump($info);
                
                $pattern_list = '<ul>';
                
                foreach( $data->patterns as $pattern ) {
                    $pattern_list .= '<li><a href="' . $pattern->permalink . '">' . $pattern->name . '</a></li>';
                }
                
                $pattern_list .= '</ul>';
                
                echo $pattern_list;

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
 	public function form($instance)
 	{
 		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		else {
			$title = __('My Ravelry Patterns', 'ravelry-designs-widget');
		}
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
				<input
					type="text"
					class="widefat"
					id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>"
					value="<?php echo esc_attr($title); ?>" />
			</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

		return $instance;
	}

}
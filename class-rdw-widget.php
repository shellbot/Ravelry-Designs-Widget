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
                
                if( empty( $instance['rav_designer_name'] ) ) {
                    
                    echo '<p>Valid Ravelry designer name required.</p>';
                    
                } else {
                    
                    if ( false === ( $output = get_transient( 'rdw_ravelry_data' ) ) ) {        
                        
                        $secret = 'CVBR21QepTC1Zwj0MEvHz+1rvmv285bH7XsF9tir'; // your secret key

                        $data = array();

                        $data['access_key'] = '7B78C7930DB53FE4C60D'; // your access key
                        $data['designer'] = $instance['rav_designer_name']; // the store search query for full text search
                        $data['page_size'] = $instance['show_num']; // for example
                        $data['timestamp'] = date('c'); // gets the current date/time



                        $string = RAVELRY_API_URL . '/patterns/search.json?' . http_build_query($data);

                        $signature = base64_encode(hash_hmac('sha256', $string, $secret, true));

                        $data['signature'] = $signature;


                        $final = http_build_query($data);
                        $final = RAVELRY_API_URL . '/patterns/search.json?' . $final;
                         //echo '<pre>'.print_r($final, true).'</pre>';
                        // Begin CURL section - getting the response from the URL that 
                        // was built above.

                        $ch = curl_init();
                        // set URL and other appropriate options
                        curl_setopt($ch, CURLOPT_URL, $final);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

                        // grab URL and pass it to the browser
                        $output = curl_exec($ch);

                        // close cURL resource to free up system resources
                        curl_close($ch); 

                        set_transient( 'rdw_ravelry_data', $output, 60*10 );        
                        
                    }
                    
                    $data = json_decode($output); 
                    
                    //echo '<pre>'.print_r($data, true).'</pre>';

                    $i = 1;

                    $pattern_list = '<ul class="' . $instance['layout'] . '">';

                    foreach( $data->patterns as $pattern ) {
                        
                        if( $i > $instance['show_num'] ) {
                            continue;
                        } 
                        
                        if( $instance['new_tab'] == 'yes' ) {
                            $target = 'target="_blank"';
                        } else {
                            $target = '';
                        }
                        
                        if( $instance['layout'] == 'layout_1' ) {
                            $photo = $pattern->first_photo->square_url;
                            $pattern_list .= '<li><a href="' . RAVELRY_BASE_URL . $pattern->permalink . '" ' . $target . '><img src="' . $photo .'" height="40" width="40">' . $pattern->name . '</a></li>';
                        } else {
                            $photo = $pattern->first_photo->medium_url;
                            $pattern_list .= '<li><div class="rav-container">'
                                    . '<div class="rav-dummy"></div>'
                                    . '<div class="rav-element" style="background: url('.$photo.') no-repeat center center; background-size: cover;">'
                                    . '<span class="pattern-name"><a href="' . RAVELRY_BASE_URL . $pattern->permalink . '" ' . $target . '>' . $pattern->name . '</a></span>'
                                    . '</div>'
                                    . '</div></li>';
                        }          
                        
                        $i++;
                        
                    }

                    $pattern_list .= '</ul>';

                    echo $pattern_list;
                    
                }

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
                        $designer = $instance['rav_designer_name'];
                        $show_num = $instance['show_num'];
                        $layout = $instance['layout'];
                        $new_tab = $instance['new_tab'];
                        $show_price = $instance['show_price'];
		}
		else {
			$title = __('My Ravelry Patterns', 'ravelry-designs-widget');
                        $designer = '';
                        $show_num = '3';
                        $layout = 'layout_1';
                        $new_tab = 'no';
                        $show_price = 'show';
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
			<p>
				<label for="<?php echo $this->get_field_id('rav_designer_name'); ?>"><?php _e('Ravelry designer name:'); ?></label> 
				<input
					type="text"
					class="widefat"
					id="<?php echo $this->get_field_id('rav_designer_name'); ?>"
					name="<?php echo $this->get_field_name('rav_designer_name'); ?>"
					value="<?php echo esc_attr($designer); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('show_num'); ?>"><?php _e('Number of patterns to show:'); ?></label> 
				<input
					type="text"
					id="<?php echo $this->get_field_id('show_num'); ?>"
					name="<?php echo $this->get_field_name('show_num'); ?>"
					value="<?php echo esc_attr($show_num); ?>" size="3"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:'); ?></label> 
				<select
					id="<?php echo $this->get_field_id('layout'); ?>"
					name="<?php echo $this->get_field_name('layout'); ?>">
                                    
                                        <option value="layout_1" <?php if( $layout == "layout_1" ) { echo 'selected="selected"'; } ?>>Layout 1</option>
                                        <option value="layout_2" <?php if( $layout == "layout_2" ) { echo 'selected="selected"'; } ?>>Layout 2</option>
                                        
                                </select>
			</p>
			<p>
				<label for="new_tab_yes"><?php _e('Open links in new tab:'); ?></label> 
				<input
					type="radio"
					id="new_tab_yes"
					name="<?php echo $this->get_field_name('new_tab'); ?>"
					value="yes"
                                        <?php if( $new_tab  == "yes" ) { echo 'checked="checked"'; } ?>/>
                                <label for="new_tab_no"><?php _e('Same tab:'); ?></label> 
				<input
					type="radio"
					id="new_tab_no"
					name="<?php echo $this->get_field_name('new_tab'); ?>"
					value="no"
                                        <?php if( $new_tab == "no" ) { echo 'checked="checked"'; } ?>/>
			</p>
			<p>
				<label for="show_price_show"><?php _e('Show price'); ?></label> 
				<input
					type="radio"
					id="show_price_show"
					name="<?php echo $this->get_field_name('show_price'); ?>"
					value="show"
                                        <?php if( $show_price == "show" ) { echo 'checked="checked"'; } ?>/>
                                <label for="show_price_hide"><?php _e('Hide price'); ?></label> 
				<input
					type="radio"
					id="show_price_hide"
					name="<?php echo $this->get_field_name('show_price'); ?>"
					value="hide"
                                        <?php if( $show_price == "hide" ) { echo 'checked="checked"'; } ?>/>
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
            
            delete_transient( 'rdw_ravelry_data' );
            
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['rav_designer_name'] = (!empty($new_instance['rav_designer_name'])) ? strip_tags($new_instance['rav_designer_name']) : '';
            $instance['show_num'] = (!empty($new_instance['show_num'])) ? strip_tags($new_instance['show_num']) : '';
            $instance['layout'] = (!empty($new_instance['layout'])) ? strip_tags($new_instance['layout']) : '';
            $instance['new_tab'] = (!empty($new_instance['new_tab'])) ? strip_tags($new_instance['new_tab']) : '';
            $instance['show_price'] = (!empty($new_instance['show_price'])) ? strip_tags($new_instance['show_price']) : '';


            return $instance;
	}

}
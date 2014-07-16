<?php
/**
 * @package Ravelry Designs Widget
 */
/*
Plugin Name: Ravelry Designs Widget
Plugin URI: http://shellbotics.com/wordpress-plugins/ravelry-designs-widget/
Description: Display your own knitting & crochet designs straight from Ravelry.
Version: 0.0.1
Author: Shellbot
Author URI: http://shellbotics.com
Text Domain: ravelry-designs-widget
*/

function _dbg($thing) {
	print_r($thing, true);
}

define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RAVELRY_ACCESS_KEY', '5779FC7FD7A5C6A65EC6');
define('RAVELRY_PERSONAL_KEY', '54Ykizksal6sWRJp7tiuvoVtjPT6kbhgv5rhKAzB');
define('RAVELRY_BASE_URL', 'https://api.ravelry.com');

// $url = RAVELRY_BASE_URL . '/patterns/search.json?query=' . urlencode($name);

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, RAVELRY_BASE_URL . '/patterns/search.json?query=');
// curl_setopt($ch, CURLOPT_USERPWD, RAVELRY_ACCESS_KEY . ':' . RAVELRY_PERSONAL_KEY);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
// $output = curl_exec($ch);
// $info = curl_getinfo($ch);
// curl_close($ch);

// // var_dump(json_decode($output));
// $data = json_decode($output);
// var_dump($data);
// // var_dump($data->patterns[0]->pattern_sources);
// // var_dump($info);
// die();

require_once PLUGIN_PATH . 'class-ravelry-designs-widget.php';

// function register_rdw_widget()
// {
// 	if (function_exists('register_widget')) {
// 		return register_widget('Ravelry_Designs_Widget');
// 	}
// }
// add_action('widgets_init', 'register_rdw_widget');

// function register_rdw_menu()
// {
// 	if (function_exists('register_widget')) {
// 		add_options_page('Ravelry Designs Widget Options', 'Ravelry Designs Widget', 'manage_options', 'rdw-options', 'rdw_options');
// 	}
// }
// add_action('admin_menu', 'register_rdw_menu');

// function rdw_options() {
// 	if (!current_user_can('manage_options')) {
// 		wp_die(__('You do not have sufficient permissions to access this page. Go away, naughty person.'));
// 	}
// 	echo '<div class="wrap">';
// 	echo '<p>Here is where the form would go if I actually had options.</p>';
// 	echo '</div>';
// }
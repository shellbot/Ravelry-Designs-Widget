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
define('RAVELRY_API_URL', 'https://api.ravelry.com');
define('RAVELRY_BASE_URL', 'http://www.ravelry.com/patterns/library/');

require_once PLUGIN_PATH . 'class-ravelry-designs-widget.php';
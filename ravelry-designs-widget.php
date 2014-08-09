<?php
/**
 * @package Ravelry Designs Widget
 */
/*
Plugin Name: Ravelry Designs Widget
Plugin URI: http://shellbotics.com/wordpress-plugins/ravelry-designs-widget/
Description: Display your own knitting & crochet designs straight from Ravelry.
Version: 1.0.0
Author: Shellbot
Author URI: http://shellbotics.com
Text Domain: ravelry-designs-widget
*/

function _dbg($thing) {
	print_r($thing, true);
}

define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RAVELRY_API_URL', 'http://api.ravelry.com');
define('RAVELRY_BASE_URL', 'http://www.ravelry.com/patterns/library/');

require_once PLUGIN_PATH . 'class-ravelry-designs-widget.php';


/* -------------------------------------------------------------------------- */

add_action( 'wp_print_styles', 'rdw_add_styles' );

function rdw_add_styles() {
    $css = '<style type="text/css">'
            . '.rav-container { display: inline-block; position: relative; width: 100%; }'
            . '.rav-dummy { margin-top: 100%; }'
            . '.rav-element { position: absolute;top: 0;bottom: 0;left: 0;right: 0;}'
            . '.widget_ravelry_designs_widget ul, .widget_ravelry_designs_widget li { list-style-type: none !important; }'
            . '.widget_ravelry_designs_widget .layout_1 li { margin-bottom: 5px; }'
            . '.widget_ravelry_designs_widget .layout_1 img { display: inline-block; margin-right: 5px; vertical-align: middle; }'
            . '.widget_ravelry_designs_widget .layout_2 .pattern-name { background: rgba(0,0,0,0.7); bottom: 0; display: block; margin-left: 0; position: absolute; width: 100%;}'
            . '.widget_ravelry_designs_widget .layout_2 .pattern-name a {color: #fff !important; display: block; padding: 10px; text-align: center; text-decoration: none;}'
            . '</style>';
    
    echo $css;
}
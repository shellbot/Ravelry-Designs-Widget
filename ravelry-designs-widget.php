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

class sb_ravelry_designs_widget {

    function __construct() {
        define('PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('RAVELRY_API_URL', 'http://api.ravelry.com');
        define('RAVELRY_BASE_URL', 'http://www.ravelry.com/patterns/library/');
        
        add_action( 'wp_print_styles', array( $this, 'rdw_add_styles' ) );

        require_once PLUGIN_PATH . 'class-ravelry-designs-widget.php'; 
    }

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
    
    function show_patterns( $args ) {

        if( empty( $args['rav_designer_name'] ) ) {
            echo '<p>Valid Ravelry designer name required.</p>';
        } else {
            if ( false === ( $output = get_transient( 'rdw_ravelry_data' ) ) ) {        

                $secret = 'CVBR21QepTC1Zwj0MEvHz+1rvmv285bH7XsF9tir'; // your secret key

                $data = array();

                $data['access_key'] = '7B78C7930DB53FE4C60D'; // your access key
                $data['designer'] = $args['rav_designer_name']; // the store search query for full text search
                $data['page_size'] = $args['show_num']; // for example
                $data['timestamp'] = date('c'); // gets the current date/time

                $string = RAVELRY_API_URL . '/patterns/search.json?' . http_build_query($data);

                $signature = base64_encode(hash_hmac('sha256', $string, $secret, true));

                $data['signature'] = $signature;


                $final = http_build_query($data);
                $final = RAVELRY_API_URL . '/patterns/search.json?' . $final;
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

            $i = 1;

            $pattern_list = '<ul class="' . $args['layout'] . '">';

            foreach( $data->patterns as $pattern ) {

                if( $i > $args['show_num'] ) {
                    continue;
                } 

                if( $args['new_tab'] == 'yes' ) {
                    $target = 'target="_blank"';
                } else {
                    $target = '';
                }

                if( $args['layout'] == 'layout_1' ) {
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
    }   

}

$sbrdw = new sb_ravelry_designs_widget();


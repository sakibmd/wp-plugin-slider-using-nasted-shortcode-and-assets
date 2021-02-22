<?php

/**
 * Plugin Name:       TinySlider
 * Plugin URI:        https://sakibmd.xyz/
 * Description:       Generate a tinyslider using wp shortcode
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sakib Mohammed
 * Author URI:        https://sakibmd.xyz/
 * License:           GPL v2 or later
 * License URI:
 * Text Domain:       tinyslider
 * Domain Path:       /languages
 */

function tinys_load_textdomain()
{
    load_plugin_textdomain('tinyslider', false, dirname(__FILE__) . "/languages");
}
add_action("plugins_loaded", "tinys_load_textdomain"); //**tinys = prefix

function tinys_init(){
	add_image_size('tiny-slider',800,600,true);
}
add_action('init','tinys_init');

function tinys_assets()
{
    wp_enqueue_style('tinyslider-css', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css', null, '1.0');
    wp_enqueue_script('tinyslider-js', '//cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.2/min/tiny-slider.js', null, '1.0', true);

    wp_enqueue_script('tinyslider-main-js', plugin_dir_url(__FILE__) . "/assets/js/main.js", array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'tinys_assets');

function tinys_shortcode_tslider($arguments, $content)
{
    $defaults = array(
        'width' => 1200,
        'height' => 800,
        'id' => '',
    );
    $attributes = shortcode_atts($defaults, $arguments);
    $content = do_shortcode($content);

    $shortcode_output = <<<EOD
<div id="{$attributes['id']}" style="width:{$attributes['width']};height:{$attributes['height']}">
	<div class="slider">
	{$content}
	</div>
</div>
EOD;
    return $shortcode_output;
}

add_shortcode('tslider', 'tinys_shortcode_tslider');

function tinys_shortcode_tslide($arguments)
{
    $defaults = array(
        'caption' => '',
        'id' => '',
        'size' => 'tiny-slider',
    );
    $attributes = shortcode_atts($defaults, $arguments);

    $image_src = wp_get_attachment_image_src($attributes['id'], $attributes['size']);

    $shorcode_output = <<<EOD
        <div class='slide'>
        <p><img src="{$image_src[0]}" alt="{$attributes['caption']}"></p>
        <p>{$attributes['caption']}</p>
        </div>
    EOD;

    return $shorcode_output;
}

add_shortcode('tslide', 'tinys_shortcode_tslide');

// use like this
// [tslider width=1200 height=700 ][tslide caption='Slider 1' id='40' /][tslide caption='Slider 2' id='41' /][/tslider]

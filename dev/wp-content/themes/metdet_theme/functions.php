<?php

add_action('wp_head','metdet_load_scripts',1);

function metdet_load_scripts()
{
	wp_register_script('metdet', get_stylesheet_directory_uri().'/assets/scripts.js', array('jquery'));
	wp_enqueue_script('metdet');
}
?>
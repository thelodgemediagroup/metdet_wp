<?php

add_action('wp_head','metdet_load_scripts',1);

function metdet_load_scripts()
{
	wp_register_script('metdet', get_stylesheet_directory_uri().'/assets/scripts.js', array('jquery'));
	wp_enqueue_script('metdet');
}

function metdet_is_month($test_month)
{
	$metdet_month_array = array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
		);

	if (in_array($test_month, $metdet_month_array))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function metdet_is_year($test_year)
{
	$metdet_year_array = array();
	$current_year = date('Y');
	while ($current_year >= 2009)
	{
		array_push($metdet_year_array, $current_year);
		$current_year--;
	}

	if (in_array($test_year, $metdet_year_array))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}
?>
<?php
/*
Plugin Name: MetDet Distributors
Plugin URI: http://thelodgemediagroup.com
Description: Manges Metropolitan Detroit distributors to be displayed on the website.
Author: The Lodge Media Group
Author URI: http://thelodgemediagroup.com
Version: 0.5.0
*/

// Gather necessary globals and files for the plugin
global $wpdb;
define('MDD_TABLE', $wpdb->prefix.'metdet_distributors');
define('MDD_CITIES', $wpdb->prefix.'metdet_cities');
require_once(ABSPATH . 'wp-config.php');
require_once(ABSPATH . 'wp-load.php');

// Activate the plugin
register_activation_hook(__FILE__,'mdd_install');

// Create the menu in the admin dashboard
add_action('admin_menu', 'mdd_create_menu');
add_action('admin_init', 'load_mdd_scripts');
add_action('wp_ajax_display_mdd_results', 'display_mdd_results');
add_action('wp_ajax_mdd_show_cities', 'mdd_show_cities');

function mdd_create_menu()
{
		global $mdd_settings;
		$mdd_settings = add_menu_page('MetDet Distributors Settings','Distributors', 'administrator', __FILE__, 'mdd_action');
}

// Install the plugin, configure a table if it hasn't been configured yet
function mdd_install()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "metdet_distributors";

	$sql = "CREATE TABLE ".$table_name." (
mdd_id INT NOT NULL AUTO_INCREMENT,
mdd_location VARCHAR(255) NOT NULL,
mdd_address VARCHAR(255) NOT NULL,
mdd_city_id VARCHAR(255) NOT NULL,
mdd_state VARCHAR(2) NOT NULL,
mdd_zip VARCHAR(255) NOT NULL,
mdd_phone VARCHAR(255) NOT NULL,
mdd_web VARCHAR(255) NOT NULL,
UNIQUE KEY  mdd_id (mdd_id)
);";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

$sql = "CREATE TABLE ".MDD_CITIES." (
	mdd_city_id INT NOT NULL AUTO_INCREMENT,
	mdd_city_name VARCHAR(255) NOT NULL,
	UNIQUE KEY mdd_city_id (mdd_city_id)
	);";

dbDelta($sql);

}

function mdd_action()
{
	global $wpdb;

	$action  = !empty($_REQUEST['action']) ? $_REQUEST['action'] : 'add';

	if ( $action == 'add' )
	{
		metdet_distributor_add();
	}
	else if ( $action == 'edit' )
	{
		metdet_distributor_edit();
	}
	else if ( $action == 'delete' )
	{
		metdet_distributor_delete();
	}
}

function metdet_distributor_add()
{
		if (isset($_POST['mdd_add']) && $_POST['mdd_add'] == 'Add Distributor')
	{
		$mdd_location = $_POST['mdd_location'];
		$mdd_address = $_POST['mdd_address'];
		$mdd_city_id = $_POST['mdd_city_id'];
		$mdd_state = $_POST['mdd_state'];
		$mdd_zip = $_POST['mdd_zip'];
		$mdd_phone = $_POST['mdd_phone'];
		$mdd_web = $_POST['mdd_web'];

		global $wpdb;

		$sql = $wpdb->prepare("INSERT INTO `".MDD_TABLE."` (`mdd_location`, `mdd_address`, `mdd_city_id`, `mdd_state`, `mdd_zip`, `mdd_phone`, `mdd_web`) VALUES (%s,%s,%d,%s,%s,%s,%s)", $mdd_location, $mdd_address, $mdd_city_id, $mdd_state, $mdd_zip, $mdd_phone, $mdd_web);

		$wpdb->query($sql);

		echo "Distributor added successfully";
	}

	if (isset($_POST['city_add']) && $_POST['city_add'] == 'Add City')
	{
		$mdd_city_name = $_POST['mdd_city_name'];

		global $wpdb;

		$sql = $wpdb->prepare("INSERT INTO `".MDD_CITIES."` (`mdd_city_name`) VALUES (%s)", $mdd_city_name);

		$wpdb->query($sql);

		echo 'City added successfully';
	}

	?>
	<h2>Add City</h2>
	
		<form name="city_add" action="" method="post">
			<div class="postbox">
				<table>
					<tr>
						<td><legend>Add City:</legend></td>
						<td><input type="text" id="mdd_city_name" name="mdd_city_name"></td>
					</tr>
				</table>
			</div> <!-- END POSTBOX -->
			<input type="submit" id="city_add" class="button-primary" name="city_add" value="Add City">
		</form>
	


	<h2>Add Distributor</h2>
	<div id="distributor-upload-form">
		<form name="distributor_add" action="" method="post">
			<div class="postbox">
				<table>
					<tr>
						<td><legend>Location Name:</legend></td>
						<td><input type="text" name="mdd_location"></td>
					</tr>
					<tr>
						<td><legend>Address:</legend></td>
						<td><input type="text" name="mdd_address"></td>
					</tr>
					<tr>
						<td><legend>City:</legend></td>
						<td><select id="city_select" name="mdd_city_id"></select></td>
					</tr>
					<tr>
						<td><legend>State:</legend></td>
						<td><input type="text" name="mdd_state"></td>
					</tr>
					<tr>
						<td><legend>Zip:</legend></td>
						<td><input type="text" name="mdd_zip"></td>
					</tr>
					<tr>
						<td><legend>Phone Number:</legend></td>
						<td><input type="text" name="mdd_phone"></td>
					</tr>
					<tr>
						<td><legend>Web Site:</legend></td>
						<td><input type="text" name="mdd_web"></td>
					</tr>
				</table>
			</div> <!-- postbox class -->
			<input type="submit" method="post" class="button-primary" id="mdd_upload" name="mdd_add" value="Add Distributor">
		</form>
	</div> <!-- end distributor upoload form -->

	<div id="mdd-results"></div>

	<?php
}

function metdet_distributor_edit()
{
		if (isset($_POST['mdd_edit']) && $_POST['mdd_edit'] == 'Update Distributor')
	{
		$mdd_location = $_POST['mdd_location'];
		$mdd_address = $_POST['mdd_address'];
		$mdd_city_id = $_POST['mdd_city_id'];
		$mdd_state = $_POST['mdd_state'];
		$mdd_zip = $_POST['mdd_zip'];
		$mdd_phone = $_POST['mdd_phone'];
		$mdd_web = $_POST['mdd_web'];
		$edit_mdd_id = mysql_real_escape_string($_GET['mdd_id']);

		global $wpdb;

		$sql = $wpdb->prepare("UPDATE `".MDD_TABLE."` SET `mdd_location`=%s, `mdd_address`=%s, `mdd_city_id`=%s, `mdd_state`=%s, `mdd_zip`=%s, `mdd_phone`=%s, `mdd_web`=%s WHERE `mdd_id`=%d", $mdd_location, $mdd_address, $mdd_city_id, $mdd_state, $mdd_zip, $mdd_phone, $mdd_web, $edit_mdd_id);

		$wpdb->query($sql);

		echo "Distributor updated successfully";
	}
	
	global $wpdb;

	$edit_mdd_id = mysql_real_escape_string($_GET['mdd_id']);

	$sql = "SELECT * FROM ".MDD_TABLE.", ".MDD_CITIES." WHERE ".MDD_TABLE.".mdd_city_id = ".MDD_CITIES.".mdd_city_id AND mdd_id=".$edit_mdd_id.";";

	$results = $wpdb->get_results($sql);

	foreach($results as $result)

	?>

	<h2>Edit Distributor</h2>
	<div id="distributor-edit-form">
		<form name="distributor_edit" action="" method="post">
			<div class="postbox">
				<table>
					<tr>
						<td><legend>Location Name:</legend></td>
						<td><input type="text" name="mdd_location" value="<?php echo $result->mdd_location; ?>"></td>
					</tr>
					<tr>
						<td><legend>Address:</legend></td>
						<td><input type="text" name="mdd_address" value="<?php echo $result->mdd_address; ?>"></td>
					</tr>
					<tr>
						<td><legend>City:</legend></td>
						<!--<td><input type="text" name="mdd_city_id" value="<?php echo $result->mdd_city_id; ?>"></td> --> 
						<td><select id="city_select" name="mdd_city_id"><option value="<?php echo $result->mdd_city_id; ?>"><?php echo $result->mdd_city_name; ?></option></select></td>
					</tr>
					<tr>
						<td><legend>State:</legend></td>
						<td><input type="text" name="mdd_state" value="<?php echo $result->mdd_state; ?>"></td>
					</tr>
					<tr>
						<td><legend>Zip:</legend></td>
						<td><input type="text" name="mdd_zip" value="<?php echo $result->mdd_zip; ?>"></td>
					</tr>
					<tr>
						<td><legend>Phone Number:</legend></td>
						<td><input type="text" name="mdd_phone" value="<?php echo $result->mdd_phone; ?>"></td>
					</tr>
					<tr>
						<td><legend>Web Site:</legend></td>
						<td><input type="text" name="mdd_web" value="<?php echo $result->mdd_web; ?>"></td>
					</tr>
				</table>
			</div> <!-- postbox class -->
			<input type="submit" method="post" class="button-primary" id="mdd_edit" name="mdd_edit" value="Update Distributor">
		</form>
	</div> <!-- end distributor edit form -->

	<div id="mdd-results"></div>

	<?php
}

function metdet_distributor_delete()
{
	$mdd_delete_id = mysql_real_escape_string($_GET['mdd_id']);

	if(!empty($mdd_delete_id))
	{
		global $wpdb;
		$sql = $wpdb->prepare("DELETE FROM `".MDD_TABLE."` WHERE `mdd_id`=%d", $mdd_delete_id);
		$wpdb->query($sql);

		metdet_distributor_add();
	}
}

function mdd_show_cities()
{
		global $wpdb;

		$sql = "SELECT * FROM ".MDD_CITIES.";";

		$results = $wpdb->get_results($sql);

		foreach ($results as $result)
		{
			echo '<option value="'.$result->mdd_city_id.'">'.$result->mdd_city_name.'</option>';
		}
		die();
}

function display_mdd_results()
{
	if (!isset( $_POST['mdd_nonce']))
	{
		die('Permissions check failed');
	}

	global $wpdb;

	$sql = "SELECT * FROM ".MDD_TABLE.", ".MDD_CITIES." WHERE ".MDD_TABLE.".mdd_city_id = ".MDD_CITIES.".mdd_city_id ORDER BY mdd_city_name;";
	$distributors = $wpdb->get_results($sql);

	echo '<h2>Distributors</h2>';
	echo '<table class="widefat page fixed"><thead><tr><th>ID</th><th>Location</th><th>Address</th><th>City</th><th>State</th><th>Zip</th><th>Phone Number</th><th>Website</th><th>Edit</th><th>Delete</th></tr></thead>';

	foreach ($distributors as $distributor)
	{
		echo '<tr>';
			echo '<td>'.$distributor->mdd_id.'</td>';
			echo '<td>'.$distributor->mdd_location.'</td>';
			echo '<td>'.$distributor->mdd_address.'</td>';
			echo '<td>'.$distributor->mdd_city_name.'</td>';
			echo '<td>'.$distributor->mdd_state.'</td>';
			echo '<td>'.$distributor->mdd_zip.'</td>';
			echo '<td>'.$distributor->mdd_phone.'</td>';
			echo '<td>'.$distributor->mdd_web.'</td>';
			echo '<td>'.'<a href="admin.php?page=metdet_distributors/metdet_distributors.php&amp;action=edit&amp;mdd_id='.$distributor->mdd_id.'">Edit</a>'.'</td>';
			echo '<td>'.'<a href="admin.php?page=metdet_distributors/metdet_distributors.php&amp;action=delete&amp;mdd_id='.$distributor->mdd_id.'" onclick="return confirm(\'Are you sure you want to delete this distributor?\')">Delete</a>'.'</td>';
		echo '</tr>';
	}

	echo '</table>';
	die();
}

function load_mdd_scripts()
{
	$mdd_distributor_ajax = plugins_url( 'mdd_distributor_ajax.js', __FILE__);
	wp_enqueue_script('mdd_distributor_ajax', $mdd_distributor_ajax, array('jquery'));
	wp_localize_script('mdd_distributor_ajax', 'mdd_vars', array(
		'mdd_nonce' => wp_create_nonce('mdd_nonce')
		));
}


function display_all_metdet_distributors()
{
	global $wpdb;
	$sql = "SELECT * FROM `".MDD_TABLE."`, `".MDD_CITIES."` WHERE `".MDD_TABLE."`.mdd_city_id = `".MDD_CITIES."`.mdd_city_id ORDER BY `".MDD_CITIES."`.mdd_city_id;";
	$results = $wpdb->get_results($sql);
	$id_sql = "SELECT DISTINCT * FROM `".MDD_CITIES."`;";
	$id_count = $wpdb->get_results($id_sql);
	$row_count = count($results);
	$breakpoint = round($row_count/2);
	$dist_loop_counter = 0;

	echo '<div id="distributor-row-left">';
	
	foreach ($id_count as $id_city)
	{
		echo '<div class="mdd-city"><h2>'.$id_city->mdd_city_name.'</h2></div><br>';

		foreach ($results as $result)
		{
			if ($id_city->mdd_city_id == $result->mdd_city_id)
			{
				if ($dist_loop_counter == $breakpoint)
				{
					echo '</div><div id="distributor-row-right">';
				}
				?>
					<div class="mdd-distributor">
						<h3><?php echo $result->mdd_location; ?></h3>
						<p><?php echo $result->mdd_address.'<br />'.$result->mdd_city_name.', '.$result->mdd_state.' '.$result->mdd_zip.'<br />'.$result->mdd_phone.'<br />'.'<span class="lowercase"><a href="'.stripslashes($result->mdd_web).'">'.stripslashes($result->mdd_web).'</a></span>'; ?></p>
					</div>
				<?php

				$dist_loop_counter++;
			} 
		}
	}
	echo '</div>';
}

?>
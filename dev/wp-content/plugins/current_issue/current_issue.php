<?php
/*
Plugin Name: Current Issue
Plugin URI: http://thelodgemediagroup.com
Description: This plugin allows you to add issues to the Metropolitan Detroit archive and it chooses the newest issue to display on the homepage.
Author: The Lodge Media Group
Author URI: http://thelodgemediagroup.com
Version: 0.5.0
*/

// Define tabes used in Current Issue
global $wpdb;
define('CURRENT_ISSUE_TABLE', $wpdb->prefix . 'current_issue');
define('IMAGE_PATH', '../wp-content/plugins/current_issue/current_issue_folder/images/');
define('ISSUE_PATH', '../wp-content/plugins/current_issue/current_issue_folder/issues/');
require_once(ABSPATH . 'wp-config.php');
require_once(ABSPATH . 'wp-load.php');
	

// Import the logic from file

require_once(ABSPATH . "wp-admin" . '/includes/image.php');
require_once(ABSPATH . "wp-admin" . '/includes/file.php');
require_once(ABSPATH . "wp-admin" . '/includes/media.php');


// Create a database if it doesn't exists upon plugin activation
register_activation_hook(__FILE__,'install_current_issue_plugin');

// Function to add the current issue menus
add_action('admin_menu', 'current_issue_create_menu');
add_action('admin_init', 'load_current_issue_scripts');
add_action('wp_ajax_current_issue_display_results', 'current_issue_display_results');

// Function to load up the settings page
function current_issue_create_menu()
{
	// Create a new top-level menu
	global $current_issue_settings;
	$current_issue_settings = add_menu_page('Current Issue Settings','Current Issue', 'administrator', __FILE__, 'cci_action');
}

function current_issue_add()
{
?>

	<h2>Add MetDet Issue</h2>
	<div id="issue-upload-form">
		<form enctype="multipart/form-data" name="upload_form" action="admin.php?page=current_issue/current_issue.php" method="post">
			<div class="postbox">
			<table>
				<tr>
					<td><legend>Issue File</legend></td>
					<td><input type="file" class="button" name="issue_file"></td>
				</tr>
				<tr>
					<td><legend>Issue Thumbnail Image</legend></td>
					<td><input type="file" class="button" name="issue_img_thumb"></td>
				</tr>
				<tr>
					<td><legend>Issue Year - (yyyy)</legend></td>
					<td><input type="text" name="issue_year" size="4" maxlength="4"></td>
				</tr>
				<tr>
					<td><legend>Issue Month - (mm)</legend></td>
					<td><input type="text" name="issue_month" size="2" maxlength="2"></td>
				</tr>
				<tr>
					<td><legend>Issue Abstract - (255 chars)</legend></td>
					<td><textarea rows="5" cols="60" maxlength="255" name="issue_abstract"></textarea></td>
				</tr>
			</table>
			</div>	<!-- class postbox -->
				<input type="submit" method="post" class="button-primary" id="upload_form" name="upload_form" value="Upload">
		</form>
	</div> <!-- upload form -->

	<div id="current-issue-results"></div>

	<?php

	if (isset($_POST['upload_form']) && $_POST['upload_form'] == 'Upload')
	{

		$issue_year = $_POST['issue_year'];
		$issue_month = $_POST['issue_month'];
		$issue_abstract = $_POST['issue_abstract'];

		// Deal with the issue files. Move file to upload path, move file name to db
		$fileName_issue_file = $_FILES['issue_file']['name'];
		$fileName_img_file = $_FILES['issue_img_thumb']['name'];
		$fileTempLoc_issue_file = $_FILES['issue_file']['tmp_name'];
		$fileTempLoc_img_file = $_FILES['issue_img_thumb']['tmp_name'];
		$fileType_issue_file = $_FILES['issue_file']['type'];
		$fileType_img_file = $_FILES['issue_img_thumb']['type'];
		$fileSize_issue_file = $_FILES['issue_file']['size'];
		$fileSize_img_file = $_FILES['issue_img_thumb']['size'];
		$fileError_issue_file = $_FILES['issue_file']['error'];
		$fileError_img_file = $_FILES['issue_img_thumb']['error'];
		$explode_issue_file = explode(".", $fileName_issue_file);
		$explode_img_file = explode(".", $fileName_img_file);
		$fileExt_issue_file = $explode_issue_file[1];
		$fileExt_img_file = $explode_img_file[1];

		// Error handling, check that files exist and that they are of valid type
		if (!$fileTempLoc_issue_file)
		{
			echo "Error, browse for a file before clicking the upload button.";
			exit();
		}
		else if (!$fileTempLoc_img_file)
		{
			echo "Error, browse for a preview image before clicking the upload button.";
			exit();
		}
		else if ($fileSize_issue_file > 15728640)
		{
			echo "Error, the issue file is greater than 15mb in size.";
			exit();
		}
		else if ($fileSize_img_file > 5242880)
		{
			echo "Error, the image is greater than 5MB in size.";
			exit();
		}
		else if ($fileError_issue_file === 1)
		{
			echo "Error, an unexpected event occurred while processing the issue file. Please try again.";
			exit();
		}
		else if ($fileError_img_file === 1)
		{
			echo "Error, an unexpected event occurred while processing the image. Please try again.";
			exit();
		}
		// End Error Handling

		$move_issue_file = move_uploaded_file($fileTempLoc_issue_file, ISSUE_PATH.$fileName_issue_file);
		$move_image_file = move_uploaded_file($fileTempLoc_img_file, IMAGE_PATH.$fileName_img_file);

		// Make sure the file was moved
		if ($move_issue_file != true)
		{
			echo "Error, issue file not uploaded. Try again.";
			unlink($fileTempLoc_issue_file);
			exit();
		}
		else if ($move_image_file != true)
		{
			echo "Error, image file not uploaded. Try again.";
			exit();
		} 
		// End file move check

		global $wpdb;

		$sql = $wpdb->prepare(
			"INSERT INTO `".CURRENT_ISSUE_TABLE."` (`issue_path`,`issue_img_path`,`issue_year`,`issue_month`,`issue_abstract`) VALUES (%s,%s,%d,%d,%s)", $fileName_issue_file, $fileName_img_file, $issue_year, $issue_month, $issue_abstract);
		
		$wpdb->query($sql);
		
		echo "Files uploaded successfully";


	}

}

function current_issue_edit()
{

	if (isset($_POST['update_form']) && $_POST['update_form'] == "Update")
	{
		$issue_year = $_POST['issue_year'];
		$issue_month = $_POST['issue_month'];
		$issue_abstract = $_POST['issue_abstract'];
		$issue_edit_id = mysql_escape_string($_GET['issue_id']);

		global $wpdb;

		$sql = $wpdb->prepare("UPDATE `".CURRENT_ISSUE_TABLE."` SET `issue_abstract`=%s, `issue_year`=%d, `issue_month`=%d WHERE `issue_id`=%d", $issue_abstract, $issue_year, $issue_month, $issue_edit_id);

		$wpdb->query($sql);

		echo '<div class="update">Issue changes saved.</div>';

	}

		global $wpdb;

		$edit_search_id = mysql_real_escape_string($_GET['issue_id']);

		$sql = "SELECT * FROM ".CURRENT_ISSUE_TABLE." WHERE issue_id=".$edit_search_id.";";
		
		$results = $wpdb->get_results($sql);

		foreach($results as $result)


?>

	<h2>Edit MetDet Issue</h2>
	<div id="issue-update-form">
		<form enctype="multipart/form-data" name="update_form" action="admin.php?page=current_issue/current_issue.php&amp;action=edit&amp;issue_id=<?php echo $result->issue_id; ?>" method="post">
			<div class="postbox">
			<table>
				<tr>
					<td><legend>Issue Year - (yyyy)</legend></td>
					<td><input type="text" name="issue_year" size="4" maxlength="4" value="<?php echo $result->issue_year ?>"></td>
				</tr>
				<tr>
					<td><legend>Issue Month - (mm)</legend></td>
					<td><input type="text" name="issue_month" size="2" maxlength="2" value="<?php echo $result->issue_month ?>"></td>
				</tr>
				<tr>
					<td><legend>Issue Abstract - (255 chars)</legend></td>
					<td><textarea rows="5" cols="60" maxlength="255" name="issue_abstract"><?php echo stripslashes($result->issue_abstract) ?></textarea></td>
				</tr>
			</table>
			</div>	<!-- class postbox -->
				<input type="submit" method="post" class="button-primary" id="update_form" name="update_form" value="Update">
		</form>
	</div> <!-- upload form -->

	<div id="current-issue-results"></div>

	<?php

	$issue_edit_id = $result->issue_id;

	
}

function current_issue_delete()
{
	$issue_delete_id = mysql_real_escape_string($_GET['issue_id']);

	if(!empty($issue_delete_id))
	{
		global $wpdb;
		$sql = $wpdb->prepare("DELETE FROM `".CURRENT_ISSUE_TABLE."` WHERE `issue_id`=%d", $issue_delete_id);
		$wpdb->query($sql);

		current_issue_add();
	}
}

function cci_action()
{
	global $wpdb;

	$action  = !empty($_REQUEST['action']) ? $_REQUEST['action'] : 'add';

	if ( $action == 'add' )
	{
		current_issue_add();
	}
	else if ( $action == 'edit' )
	{
		current_issue_edit();
	}
	else if ( $action == 'delete' )
	{
		current_issue_delete();
	}
}

function install_current_issue_plugin()
{
	global $wpdb;
	$table_name = $wpdb->prefix . "current_issue";

	$sql = "CREATE TABLE ".$table_name." (
issue_id INT NOT NULL AUTO_INCREMENT,
issue_path VARCHAR(255) NOT NULL,
issue_img_path VARCHAR(255) NOT NULL,
issue_year INT NOT NULL,
issue_month INT NOT NULL,
issue_abstract VARCHAR(255) NOT NULL,
UNIQUE KEY  issue_id (issue_id)
);";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

}

function current_issue_display_results()
{

if (!isset( $_POST['current_issue_nonce']))
{
	die('Permissions check failed');
}

global $wpdb;
	$sql = "SELECT * FROM ".CURRENT_ISSUE_TABLE." ORDER BY issue_year DESC, issue_month DESC;";
	$issues = $wpdb->get_results($sql);
	// display all of the issues currently uploaded WORK IN PROGRESS
	
	echo '<h2>Uploaded Issues</h2>';
	echo '<table class="widefat page fixed"><thead><tr><th>ID</th><th>Issue</th><th>Issue Image</th><th>Year</th><th>Month</th><th>Abstract</th><th>Edit</th><th>Delete</th></tr></thead>';
			
			foreach ($issues as $issue)
			{

			switch($issue->issue_month)
			{
				case 1:
					$issue->issue_month = 'January';
					break;
				case 2:
					$issue->issue_month = 'Februrary';
					break;
				case 3:
					$issue->issue_month = 'March';
					break;
				case 4:
					$issue->issue_month = 'April';
					break;
				case 5:
					$issue->issue_month = 'May';
					break;
				case 6:
					$issue->issue_month = 'June';
					break;
				case 7:
					$issue->issue_month = 'July';
					break;
				case 8:
					$issue->issue_month = 'August';
					break;
				case 9:
					$issue->issue_month = 'September';
					break;
				case 10:
					$issue->issue_month = 'October';
					break;
				case 11:
					$issue->issue_month = 'November';
					break;
				case 12:
					$issue->issue_month = 'December';
					break;
			}

			echo '<tr>';
				echo '<td>'.$issue->issue_id.'</td>';
				echo '<td>'.$issue->issue_path.'</td>';
				echo '<td>'.$issue->issue_img_path.'</td>';
				echo '<td>'.$issue->issue_year.'</td>';
				echo '<td>'.$issue->issue_month.'</td>';
				echo '<td>'.stripslashes($issue->issue_abstract).'</td>';
				echo '<td>'.'<a href="admin.php?page=current_issue/current_issue.php&amp;action=edit&amp;issue_id='.$issue->issue_id.'">Edit</a>'.'</td>';
				echo '<td>'.'<a href="admin.php?page=current_issue/current_issue.php&amp;action=delete&amp;issue_id='.$issue->issue_id.'" onclick="return confirm(\'Are you sure you want to delete this issue?\')">Delete</a>'.'</td>';
			echo '</tr>';
			}
	
	echo '</table>';
	die();
	
}

function load_current_issue_scripts()
{
	$current_issue_ajax = plugins_url( 'current_issue_ajax.js', __FILE__ );
    wp_enqueue_script('current_issue_ajax', $current_issue_ajax, array('jquery'));
    wp_localize_script('current_issue_ajax', 'current_issue_vars', array(
    		'current_issue_nonce' => wp_create_nonce('current_issue_nonce')
    	));
}

function display_the_current_issue()
{
	global $wpdb;

	$sql = "SELECT issue_path, issue_img_path, issue_year, issue_month, issue_abstract FROM ".CURRENT_ISSUE_TABLE." ORDER BY issue_year DESC, issue_month DESC LIMIT 1;";
		
	$results = $wpdb->get_results($sql);

		foreach ($results as $result)
		{
			switch($result->issue_month)
			{
				case 1:
					$result->issue_month = 'January';
					break;
				case 2:
					$result->issue_month = 'Februrary';
					break;
				case 3:
					$result->issue_month = 'March';
					break;
				case 4:
					$result->issue_month = 'April';
					break;
				case 5:
					$result->issue_month = 'May';
					break;
				case 6:
					$result->issue_month = 'June';
					break;
				case 7:
					$result->issue_month = 'July';
					break;
				case 8:
					$result->issue_month = 'August';
					break;
				case 9:
					$result->issue_month = 'September';
					break;
				case 10:
					$result->issue_month = 'October';
					break;
				case 11:
					$result->issue_month = 'November';
					break;
				case 12:
					$result->issue_month = 'December';
					break;
			}
			?>
			<div class="current-issue">
				<a href="<?php echo ISSUE_PATH.$result->issue_path; ?>"><img src="<?php echo IMAGE_PATH.$result->issue_img_path; ?>" width="184" height="275" alt="The Metropolitan Detroit <?php echo $result->issue_month.' '.$result->issue_year; ?>" title="The Metropolitan Detroit, <?php echo $result->issue_month.' '.$result->issue_year; ?>"></a>
			</div>
			<?php
		}
}

function display_all_issues()
{
	global $wpdb;

	$sql = "SELECT issue_path, issue_img_path, issue_year, issue_month, issue_abstract FROM ".CURRENT_ISSUE_TABLE." ORDER BY issue_year DESC, issue_month DESC;";
		
	$results = $wpdb->get_results($sql);

		foreach ($results as $result)
		{
			switch($result->issue_month)
			{
				case 1:
					$result->issue_month = 'January';
					break;
				case 2:
					$result->issue_month = 'Februrary';
					break;
				case 3:
					$result->issue_month = 'March';
					break;
				case 4:
					$result->issue_month = 'April';
					break;
				case 5:
					$result->issue_month = 'May';
					break;
				case 6:
					$result->issue_month = 'June';
					break;
				case 7:
					$result->issue_month = 'July';
					break;
				case 8:
					$result->issue_month = 'August';
					break;
				case 9:
					$result->issue_month = 'September';
					break;
				case 10:
					$result->issue_month = 'October';
					break;
				case 11:
					$result->issue_month = 'November';
					break;
				case 12:
					$result->issue_month = 'December';
					break;
			}
			?>
			<div class="display-issue">
				<a href="<?php echo ISSUE_PATH.$result->issue_path; ?>"><img src="<?php echo IMAGE_PATH.$result->issue_img_path; ?>" width="184" height="275" alt="The Metropolitan Detroit <?php echo $result->issue_month.' '.$result->issue_year; ?>" title="The Metropolitan Detroit, <?php echo $result->issue_month.' '.$result->issue_year; ?>"></a>
				<h2><?php echo $result->issue_month.' '.$result->issue_year; ?></h2>
				<p><?php echo $result->issue_abstract; ?></p>
			</div>
			<?php
		}
}

function display_issues_by_year()
{
	if (isset($_GET['issue_year']))
	{	
		$issue_year= mysql_real_escape_string($_GET['issue_year']);

		global $wpdb;

		$sql = "SELECT issue_path, issue_img_path, issue_year, issue_month, issue_abstract FROM ".CURRENT_ISSUE_TABLE." WHERE issue_year=$issue_year ORDER BY issue_year DESC, issue_month DESC;";
			
		$results = $wpdb->get_results($sql);

			foreach ($results as $result)
			{
				switch($result->issue_month)
				{
					case 1:
						$result->issue_month = 'January';
						break;
					case 2:
						$result->issue_month = 'Februrary';
						break;
					case 3:
						$result->issue_month = 'March';
						break;
					case 4:
						$result->issue_month = 'April';
						break;
					case 5:
						$result->issue_month = 'May';
						break;
					case 6:
						$result->issue_month = 'June';
						break;
					case 7:
						$result->issue_month = 'July';
						break;
					case 8:
						$result->issue_month = 'August';
						break;
					case 9:
						$result->issue_month = 'September';
						break;
					case 10:
						$result->issue_month = 'October';
						break;
					case 11:
						$result->issue_month = 'November';
						break;
					case 12:
						$result->issue_month = 'December';
						break;
				}
				?>
				<div class="display-issue">
					<a href="<?php echo ISSUE_PATH.$result->issue_path; ?>"><img src="<?php echo IMAGE_PATH.$result->issue_img_path; ?>" width="184" height="275" alt="The Metropolitan Detroit <?php echo $result->issue_month.' '.$result->issue_year; ?>" title="The Metropolitan Detroit, <?php echo $result->issue_month.' '.$result->issue_year; ?>"></a>
					<h2><?php echo $result->issue_month.' '.$result->issue_year; ?></h2>
					<p><?php echo $result->issue_abstract; ?></p>
				</div>
				<?php
			}
	}
	else
	{
		return;
	}
}

?>
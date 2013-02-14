<?php
/*
Plugin Name: Current Issue
Plugin URI: http://thelodgemediagroup.com
Description: This plugin allows you to add current issues to your website and choose which one is displayed to users.
Author: The Lodge Media Group
Author URI: http://thelodgemediagroup.com
Version: 0.1.0
*/

// Define tabes used in Current Issue
global $wpdb;
define('CURRENT_ISSUE_TABLE', $wpdb->prefix . 'current_issue');
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

// Function to load up the settings page
function current_issue_create_menu()
{
	// Create a new top-level menu
	add_menu_page('Current Issue Settings','Current Issue', 'administrator', __FILE__, 'current_issue_settings_page');
}

//Settings page display
function current_issue_settings_page()
{
?>

	<h2>Edit MetDet Issue</h2>
	<div class="issue-upload-form">
		<form enctype="multipart/form-data" name="upload_form" action="admin.php?page=current issue/current_issue.php" method="post">
			<table>
				<tr>
					<td><legend>Issue File</legend></td>
					<td><input type="file" name="issue_file"></td>
				</tr>
				<tr>
					<td><legend>Issue Thumbnail Image</legend></td>
					<td><input type="file" name="issue_img_thumb"></td>
				</tr>
				<tr>
					<td><legend>Issue Year</legend></td>
					<td><input type="text" name="issue_year" size="4" maxlength="4"></td>
				</tr>
				<tr>
					<td><legend>Issue Month</legend></td>
					<td><input type="text" name="issue_month" size="2" maxlength="2"></td>
				</tr>
			</table>
				<input type="submit" method="post" name="upload_form" value="Upload">
		</form>
	</div>	

	<?php

	current_issue_admin_display();

	if ($_POST['upload_form'] == 'Upload')
	{

		$issue_year = $_POST['issue_year'];
		$issue_month = $_POST['issue_month'];

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

		$move_issue_file = move_uploaded_file($fileTempLoc_issue_file, "../wp-content/plugins/current issue/current_issue_folder/issues/$fileName_issue_file");
		$move_image_file = move_uploaded_file($fileTempLoc_img_file, "../wp-content/plugins/current issue/current_issue_folder/images/$fileName_img_file");

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

		unlink($fileTempLoc_issue_file);
		unlink($fileTempLoc_img_file);

		global $wpdb;

		$sql = $wpdb->prepare(
			"INSERT INTO `".CURRENT_ISSUE_TABLE."` (`issue_path`,`issue_img_path`,`issue_year`,`issue_month`) VALUES (%s,%s,%d,%d)", $fileName_issue_file, $fileName_img_file, $issue_year, $issue_month);
		
		$wpdb->query($sql);
		
		echo "Files uploaded successfully";

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
UNIQUE KEY  issue_id (issue_id)
);";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

}

function current_issue_admin_display()
{
	global $wpdb;
	$sql = "SELECT * FROM ".CURRENT_ISSUE_TABLE." ORDER BY issue_year DESC, issue_month DESC;";
	$issues = $wpdb->get_results($sql);
	// display all of the issues currently uploaded WORK IN PROGRESS
	?>

	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Issue</th>
				<th>Issue Image</th>
				<th>Year</th>
				<th>Month</th>
			</tr>
		</thead>
	<?php		
			foreach ($issues as $issue)
			{
			echo '<tr>';
				echo '<td>'.$issue->issue_id.'</td>';
				echo '<td>'.$issue->issue_path.'</td>';
				echo '<td>'.$issue->issue_img_path.'</td>';
				echo '<td>'.$issue->issue_year.'</td>';
				echo '<td>'.$issue->issue_month.'</td>';
			echo '</tr>';
			}
	?>
	</table>
	<?php
}

?>
<?php
/*
Template Name: Archive API
*/

$issue_year = $_POST['issue_year'];
if (is_numeric($issue_year))
{
	if ( function_exists('display_issues_by_year') ) { $issue_display = display_issues_by_year($issue_year); echo $issue_display; } 
}

?>
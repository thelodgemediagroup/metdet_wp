<?php
/*
Template Name: Archive API
*/

$year = $_POST['year'];
if (is_numeric($year))
{
	display_issues_by_year($year);
}
?>
<?php
	require_once '../core/init.php';
	if (!is_logged_in()) {
		header('location: login.php');
	}
	require_once '../helpers/helpers.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
?>

Administrator Home
<?php include 'includes/footer.php';?>
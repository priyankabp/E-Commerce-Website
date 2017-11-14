<?php
	require_once '../core/init.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	if (!has_role('admin')) {
		role_error_redirect('index.php');
	}
	require_once '../helpers/helpers.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
?>

Users Page
<?php include 'includes/footer.php';?>
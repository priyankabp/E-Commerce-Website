<?php
	$db = mysqli_connect('localhost','root','PriyaMysql91','e_commerce');
	if (mysqli_connect_errno()) {
		echo 'Database connection failed with following errors :'. mysqli_connect_error();
		die();	
	}
	// Session start
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'].'/E-Commerce-Website/config.php';
	require_once BASEURL.'helpers/helpers.php';

	if (isset($_SESSION['User'])) {
		$user_id = $_SESSION['User'];
		$query = $db->query("SELECT * FROM users WHERE id = '$user_id'");
		$user_data = mysqli_fetch_assoc($query);
		$full_name = explode(' ', $user_data['full_name']);
		$user_data['firstname'] = $full_name[0];
		$user_data['lastname'] = $full_name[1];
	}
	if (isset($_SESSION['success_flash'])) {
		echo '<div class="alert alert-success" role="alert">'.$_SESSION['success_flash'].'</div>';
		unset($_SESSION['success_flash']);
	}

	if (isset($_SESSION['error_flash'])) {
		echo '<div class="alert alert-danger" role="alert">'.$_SESSION['error_flash'].'</p></div>';
		unset($_SESSION['error_flash']);
	}

?>
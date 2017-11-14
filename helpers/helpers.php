<?php

	function display_errors($errors){
		$display = '<ul class="bg-danger">';
		foreach ($errors as $error) {
			$display .= '<li>'.$error.'</li>';
		}
		$display .= '</ul>';
		return $display;
	}

	function sanitize($dirty){
		return htmlentities($dirty,ENT_QUOTES,"UTF-8");
	}

	function money($price){
		return '$'.number_format($price,2);
	}

	function login($user_id){
		$_SESSION['User'] = $user_id;
		global $db;
		$date = date("Y-m-d H:i:s");
		$db->query("UPDATE users SET last_login = '$date' WHERE id = '$user_id'");
		$_SESSION['success_flash'] = 'You are logged in!';
		header('location: index.php');
	}

	function is_logged_in(){
		if (isset($_SESSION['User']) && $_SESSION['User'] > 0) {
			return true;
		}
		return false;
	}

	function login_error_redirect($url = 'login.php'){
		header('location: login.php');
		$_SESSION['error_flash'] = "You must log in first!";
		
	}

	function has_role($role = 'admin'){
		global $user_data;
		$roles = explode(',', $user_data['role']);
		if (in_array($role,$roles,true)) {
			return true;
		}
		return false;
	}

	function role_error_redirect($url = 'brands.php'){
		header('location: brands.php');
		$_SESSION['error_flash'] = "You don't have permission to access that page!";
		
	}
?>
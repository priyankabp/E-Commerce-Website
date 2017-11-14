<?php
    require_once '../core/init.php';
    if (!is_logged_in()) {
		login_error_redirect();
	}
    require_once '../helpers/helpers.php';
    include 'includes/head.php';
    $hashed = $user_data['password'];
    $old_password = ((isset($_POST['old_password']))?$_POST['old_password']:'');
    $old_password = trim($old_password);
    $password = ((isset($_POST['password']))?$_POST['password']:'');
    $password = trim($password);
    $confirm_password = ((isset($_POST['confirm_password']))?$_POST['confirm_password']:'');
    $confirm_password = trim($confirm_password);
    $new_hashed = password_hash($password, PASSWORD_DEFAULT);
    $user_id = $user_data['id'];
    $errors = array();
?>
<style type="text/css">
	body{
		background-image: url("/E-Commerce-Website/images/headerlogo/login.jpg");
		background-size: 100vw 100vh;
		background-attachment: fixed;
	}
</style>
<div id="login-form">
	<div>
		<?php
			if ($_POST) {
				//form validation
				if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm_password'])){
					$errors[] = 'Please fill all the fields!';
				}
			
				// password is more than 4 characters
				if (strlen($password)<4) {
					$errors[] = "Password must be at least 4 characters!";
				}

				// New password matches confirm password
				if ($password != $confirm_password) {
					$errors[] = "Password do not match!";
				}	

				//check if password match in database
				if (!password_verify($old_password,$hashed)) {
					$errors[] = "Old Password does not match records. Please try again!";
				}			

				//check for errors
				if (!empty($errors)) {
					echo display_errors($errors);
				}
				else{
					// Change Password
					$db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
					$_SESSION['success_flash'] = 'Password updated!';
					header('location: index.php');					

				}
			}
		?>
	</div>
	<h2 class="text-center">Change Password</h2>
	<form action="change_password.php" method="post">
		<div class="form-group">
			<label for="old_password">Old Password:</label>
			<input type="password" name="old_password" id="old_password" class="form-control" value="<?php echo $old_password;?>">
		</div>
		<div class="form-group">
			<label for="password">New Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?php echo $password;?>">
		</div>
		<div class="form-group">
			<label for="confirm_password">Confirm Password:</label>
			<input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password;?>">
		</div>
		<div class="form-group">
			<a href="index.php" class="btn btn-primary">Cancel</a>
			<input type="submit" name="" value="Update Password" class="text-center btn btn-primary">
		</div>
	</form>
</div>
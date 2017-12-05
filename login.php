<?php
    require_once 'core/init.php';
    require_once 'helpers/helpers.php';
    include 'includes/head.php';
    $email = ((isset($_POST['email']))?$_POST['email']:'');
    $email = trim($email);
    $password = ((isset($_POST['password']))?$_POST['password']:'');
    $password = trim($password);
    $errors = array();
?>
<style type="text/css">
	body{
		background-image: url("/E-Commerce-Website/images/headerlogo/login.jpg");
		background-size: 100vw 100vh;
		background-attachment: fixed;
		padding: 20px;
	}
</style>
<div id="login-form">
	<div>
		<?php
			if ($_POST) {
				//form validation
				if(empty($_POST['email']) || empty($_POST['password'])){
					$errors[] = 'You must provide email and password!';
				}
				//validate email
				if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
					$errors[] = "Enter valid email!";
				}
				// password is more than 4 characters
				if (strlen($password)<4) {
					$errors[] = "Password must be at least 4 characters!";
				}

				if (isset($_POST['page']) && ($_POST['page'] == "registration")){
                    $full_name = $_POST['full_name'];
                    $role = "customer";
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO users (full_name, email, password, join_date, last_login, role)
                            SELECT '$full_name', '$email', '$password_hash', current_timestamp(), current_timestamp(), '$role'
                            FROM dual WHERE not exists
                            (SELECT * FROM users WHERE email='$email') LIMIT 1";
                    if ($db->query($sql) === TRUE) {
                       // echo "New record created successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $db->error;
                    }
                }


				//check if email exists in database
				$user_query = $db->query("SELECT * FROM users WHERE email = '$email'");
				$user_run = mysqli_fetch_assoc($user_query);
				$user_count = mysqli_num_rows($user_query);
				if ($user_count<1) {
					$errors[] = "Email does not exist!";
				}	

				//check if password match in database
				if (!password_verify($password,$user_run['password'])) {
					$errors[] = "Password does not match. Please try again!";
				}			

				//check for errors
				if (!empty($errors)) {
					echo display_errors($errors);
				}
				else{
					//log user in
					$user_id = $user_run['id'];
					$user_role = $user_run['role'];
                    login($user_id, $user_role);

				}
			}
		?>
	</div>
	<h2 class="text-center">Login</h2>
	<form action="login.php" method="post" class="form-horizontal">
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" class="form-control" value="<?php echo $email;?>">
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" class="form-control" value="<?php echo $password;?>">
		</div>
		<div class="form-group">
			<input type="submit" name="" value="Login" class="text-center btn btn-primary">
		</div>
	</form>
	<p class="text-right"><a href="registration.php" alt="registration">Not Regsitered? Register</a></p>
</div>
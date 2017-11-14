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
	

	if (isset($_GET['delete'])) {
	    $delete_id = $_GET['delete'];
	    
	    $del_query = "DELETE FROM users WHERE id = '$delete_id'";
	    if(mysqli_query($db,$del_query)){
	        $del_msg = "User has been deleted";
	        $_SESSION['success_flash'] = "User has been deleted";
	        header('location: users.php');
	    }
	    else{
	        $del_error = "User has not been deleted";
	    }
	}

	// When user clicks on add User
	if (isset($_GET['add'])) {

		// Reading Values
		$name = ((isset($_POST['name']))?$_POST['name']:'');
		$email = ((isset($_POST['email']))?$_POST['email']:'');
		$password = ((isset($_POST['password']))?$_POST['password']:'');
		$confirm_password = ((isset($_POST['confirm_password']))?$_POST['confirm_password']:'');
		$roles = ((isset($_POST['roles']))?$_POST['roles']:'');

		//Form Validation
		$errors = array();
		if ($_POST) {

			$email_check = $db->query("SELECT * FROM users WHERE email = '$email'");
			$emailcount = mysqli_num_rows($email_check);
			if ($emailcount != 0 ) {
				$errors[] = "Email already exists! Please use another!";
			}

			$required = array('name','email','password','confirm_password','roles');
			foreach ($required as $field) {
				if (empty($_POST[$field])) {
					$errors[] = "All fields are required";
					break;
				}
			}

			// Password is less than 6
			if (strlen($password)<4) {
				$errors[] = "Password must be at least 4 characters";
			}

			// New password matches confirm password
			if ($password != $confirm_password) {
				$errors[] = "Password do not match!";
			}	

			// Email validation
			if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
				$errors[] = "Enter valid email!";
			}
			

			if (!empty($errors)) {
				//Errors
				echo display_errors($errors);
			}
			else{
				// Encrypting password before adding to database
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);

				//Add User to database query
				$db->query("INSERT INTO `users` (full_name,email,password,role) VALUES ('$name','$email','$hashed_password','$roles')");
				$_SESSION['success_flash'] = "New user added!";
				header('location: users.php');
			}
		}

?><!--php close -- >

		<!--Add New User Form-->
		<h2 class="text-center">Add New User</h2><hr>
		<form action="users.php?add=1" method="post">
			<!-- Full Name-->
			<div class="form-group col-md-6">
				<label for="name">Full Name:</label>
				<input type="text" name="name" id="name" class="form-control" value="<?php echo $name;?>">
			</div>
			<!-- Email-->
			<div class="form-group col-md-6">
				<label for="email">Email:</label>
				<input type="email" name="email" id="email" class="form-control" value="<?php echo $email;?>">
			</div> 

			<!-- Password-->
			<div class="form-group col-md-6">
				<label for="password">Password:</label>
				<input type="password" name="password" id="password" class="form-control" value="<?php echo $password;?>">
			</div>

			<!-- Confirm Password-->
			<div class="form-group col-md-6">
				<label for="confirm_password">Confirm Password:</label>
				<input type="password" name="confirm_password" id="confirm_password" class="form-control" value="<?php echo $confirm_password;?>">
			</div>

			<!-- Role -->
			<div class="form-group col-md-6">
				<label for="role">Role:</label>
				<select class="form-control" name="roles">
					<option value=""<?=(($roles == '')?' selected':'')?>></option>
					<option value="vendor"<?=(($roles == 'vendor')?' selected':'')?>>Vendor</option>
					<option value="admin,vendor,mediator"<?=(($roles == 'admin,vendor')?' selected':'')?>>Admin</option>
					<option value="mediator"<?=(($roles == 'mediator')?' selected':'')?>>Mediator</option>
				</select>
			</div>

			<!-- Submit & Cancel Buttons -->
			<div class="form-group col-md-6 text-right" style="padding-top: 25px">
				<a href="users.php" class="btn btn-primary">Cancel</a>
				<input type="submit" value="Add User" class="btn btn-primary">
			</div>

		</form>

<?php //php open
	}
	else{
		$users_query = $db->query("SELECT * FROM users ORDER BY full_name");
?>

<h2 class="text-center">Users</h2>
<a href="users.php?add=1" class="btn btn-primary pull-right" id="add-user-btn">Add New User</a><br>
<hr>
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<tr class="bg-primary">
			<th>Name</th>
			<th>Email</th>
			<th>Join Date</th>
			<th>Last Login</th>
			<th>Roles</th>
			<th>Edit</th>
			<th>Delete</th>
		</tr>
	</thead>
	<tbody>
		<?php while($user = mysqli_fetch_assoc($users_query)):
			$user_id = $user['id'];
			$user_fullname = $user['full_name'];
			$user_email = $user['email'];
			$user_join_date = $user['join_date'];
			$user_last_login = $user['last_login'];
			$user_roles = $user['role'];
		?>
			<tr>
				<td><?php echo $user_fullname;?></td>
				<td><?php echo $user_email;?></td>
				<td><?php echo $user_join_date;?></td>
				<td><?php echo (($user_last_login == null)?'Never':$user_last_login);?></td>
				<td><?php echo $user_roles;?></td>
				<td><a href="users.php?edit=<?php echo $user_id;?>"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
				<?php if($user_id != $user_data['id']):?>
					<td><a href="users.php?delete=<?php echo $user_id;?>"><i class="fa fa-times" aria-hidden="true"></i></a></td>
				<?php endif; ?>
			</tr>
	   <?php endwhile;?>
	</tbody>
</table>
<?php 
	} //adduser - else close
	include 'includes/footer.php';
?>
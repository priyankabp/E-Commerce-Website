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
	$users_query = $db->query("SELECT * FROM users ORDER BY full_name");

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
?>

<h2 class="text-center">Users</h2>
<a href="users.php?add=1" class="btn btn-primary pull-right" id="add-user-btn">Add New User</a><br>
<hr>
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<th>Name</th>
		<th>Email</th>
		<th>Join Date</th>
		<th>Last Login</th>
		<th>Roles</th>
		<th>Edit</th>
		<th>Delete</th>
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
				<td><?php echo $user_last_login;?></td>
				<td><?php echo $user_roles;?></td>
				<td><a href="users.php?edit=<?php echo $user_id;?>"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
				<?php if($user_id != $user_data['id']):?>
					<td><a href="users.php?delete=<?php echo $user_id;?>"><i class="fa fa-times" aria-hidden="true"></i></a></td>
				<?php endif; ?>
			</tr>
	   <?php endwhile;?>
	</tbody>
</table>
<?php include 'includes/footer.php';?>
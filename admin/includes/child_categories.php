<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/E-Commerce-Website/core/init.php';
	
	$parentID = $_POST['parentID'];
	$selected = $_POST['selected'];
	$get_child_query = $db->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");
	ob_start();
?>
	<option value=""></option>
	<?php while($child = mysqli_fetch_assoc($get_child_query)): ?>
			<option value="<?php echo $child['id'];?>"<?php echo (($selected == $child['id'])?' selected':'');?>><?php echo $child['category'];?></option>
	<?php endwhile; ?>
<?php
	echo ob_get_clean();
?>
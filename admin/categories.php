<?php
	require_once '../core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
?>
<h2 class="text-center">Categories</h2><hr>
<div class="row">

	<!--Form-->
	<?php
	$get_category_parent = "SELECT * FROM `e_commerce`.`categories` WHERE parent = 0";
    $get_category_parent_run = mysqli_query($db,$get_category_parent);
        if (mysqli_num_rows($get_category_parent_run)>0) {
    ?>
	<div class="col-md-6">
		<form class="form" action="categories.php" method="post">
			<div class="form-group">
				<label for="parent">Parent</label>
				<select class="form-control" name="parent" id="parent">
					<option value="0">Parent</option>
					<?php
						while ($parent_row = mysqli_fetch_array($get_category_parent_run)) {
							$parent_id = $parent_row['id'];
							$parent_name = $parent_row['category'];
					?>
					<option value="<?php echo $parent_id?>"><?php echo $parent_name; ?></option>
					<?php } ?>
				</select>
			</div>
		</form>
	</div>
	<?php 
		} 
        else{
            echo "<center><h3>No categories Found</h3></center>";
        }
    ?>


	<!--Category Table-->
	<?php
        //get categories from db
        $get_category_parent = "SELECT * FROM `e_commerce`.`categories` WHERE parent = 0";
        $get_category_parent_run = mysqli_query($db,$get_category_parent);
        if (mysqli_num_rows($get_category_parent_run)>0) {
            if (isset($del_msg)) {
                echo "<span class='pull-right' style='color:green;'>$del_msg</span>";
            }
            elseif (isset($del_error)) {
                echo "<span class='pull-right' style='color:red;'>$del_error</span>";
            }
    ?>
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<th>Category</th>
				<th>Parent</th>
				<th>Edit</th>
				<th>Delete</th>
			</thead>
			<tbody>
				<?php
                    while ($get_categories_parent_row = mysqli_fetch_array($get_category_parent_run)) {
                    	$parent_category_id = $get_categories_parent_row['id'];
                        $parent_category_name = $get_categories_parent_row['category'];

                        $get_category_child = "SELECT * FROM `e_commerce`.`categories` WHERE parent = $parent_category_id";
                        $get_category_child_run = mysqli_query($db,$get_category_child);
                ?>
				<tr class="bg-primary">
					<td><?php echo $parent_category_name;?></td>
					<td>Parent</td>
					<td>
						<a href="categories.php?edit=<?php echo $parent_category_id;?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
					</td>
					<td>
						<a href="categories.php?delete=<?php echo $parent_category_id;?>"><i class="fa fa-times" aria-hidden="true"></i></a>
					</td>
				</tr>
				<?php while($get_categories_child_row = mysqli_fetch_array($get_category_child_run)){
						$child_category_id = $get_categories_child_row['id'];
                        $child_category_name = $get_categories_child_row['category'];

				?>
				<tr class="bg-warning">
					<td><?php echo $child_category_name;?></td>
					<td><?php echo $parent_category_name; ?></td>
					<td>
						<a href="categories.php?edit=<?php echo $child_category_id;?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
					</td>
					<td>
						<a href="categories.php?delete=<?php echo $child_category_id;?>"><i class="fa fa-times" aria-hidden="true"></i></a>
					</td>
				</tr>
				<?php } ?><!--Inner while close -->
				<?php } ?><!--Outer while close -->
			</tbody>
		</table>
	</div>
	<?php 
		} 
        else{
            echo "<center><h3>No categories Found</h3></center>";
        }
    ?>
</div>
<?php include 'includes/footer.php';?>
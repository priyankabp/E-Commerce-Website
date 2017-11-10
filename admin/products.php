<?php
	  require_once '../core/init.php';
	  include 'includes/head.php';
	  include 'includes/navigation.php';

	  if (isset($_GET['featured'])) {
	  	$id = $_GET['id'];
	  	$featured = $_GET['featured'];
	  	$featured_query = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
	  	if (mysqli_query($db,$featured_query)) {
        	$update_msg = "Product featured";
	    }
	    else{
	        $update_error = "Failed to feature Product";
	    }
	  }
?>
	<h2 class="text-center">Products</h2><hr>
	<?php
		$get_products = "SELECT * FROM products WHERE deleted = 0";
	  	$get_products_run = mysqli_query($db,$get_products);
	  	if (mysqli_num_rows($get_products_run)>0) {
	?>
	<?php 
        if (isset($update_msg)) {
            echo "<span class='pull-right' style='color:green;'>$update_msg</span>";
        }
        elseif (isset($update_error)) {
            echo "<span class='pull-right' style='color:red;'>$update_rror</span>";
        }
    ?>
	<table class="table table-bordered table-condensed table-stripped">
		<thead>
			<th>Product</th>
			<th>Price</th>
			<th>Category</th>
			<th>Featured</th>
			<th>Sold</th>
			<th>Edit</th>
			<th>Delete</th>
		</thead>
		<tbody>
			<?php while($get_product_row = mysqli_fetch_array($get_products_run)){
                    $product_id = $get_product_row['id'];
                    $product_title = $get_product_row['title'];
                    $product_price = $get_product_row['price'];
                    $product_list_price = $get_product_row['list_price'];
                    $product_brand = $get_product_row['brand'];

                    $product_category_id = $get_product_row['categories'];
                    $category_query = "SELECT * FROM categories WHERE id = $product_category_id";
                    $category_run = mysqli_query($db,$category_query);
                    if (mysqli_num_rows($category_run)>0) {
                    	$category_row = mysqli_fetch_array($category_run);
	                    $child = $category_row['category'];
	                    $parent_ID = $category_row['parent'];
                    }
                    $parent_query = "SELECT * FROM categories WHERE id = $parent_ID";
                    $parent_run = mysqli_query($db,$parent_query);
                    if (mysqli_num_rows($parent_run)>0) {
                    	$parent_row = mysqli_fetch_array($parent_run);
	                    $child_parent = $parent_row['category'];
                    }
                   
                    $product_category = $child_parent.' / '.$child;

                    $product_image = $get_product_row['image'];
                    $product_description = $get_product_row['description'];
                    $product_featured = $get_product_row['featured'];
                    $product_weights = $get_product_row['weights'];
                    $product_deleted = $get_product_row['deleted'];
                ?>
                <tr>
                  <td><?php echo $product_title;?></td>
                  <td><?php echo money($product_price);?></td>
                  <td><?php echo $product_category;?></td>
                  <td>
                  		<a href="products.php?featured=<?php echo (($product_featured == 0)?'1':'0');?>&id=<?php echo $product_id;?>" class="">
                  		<span class="glyphicon glyphicon-<?php echo (($product_featured == 1)?'minus':'plus'); ?>"></span>
                  	    </a>
                  	    &nbsp <?php echo (($product_featured == 1)?'Featured Product':'');?>
                  </td>
                  <td>0</td>
                  <td><a href="products.php?edit=<?php echo $product_id;?>"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                  <td><a href="products.php?delete=<?php echo $product_id;?>"><i class="fa fa-times" aria-hidden="true"></i></a></td>
                </tr>
            <?php } ?>
		</tbody>
	</table>
	<?php 
        } 
        else{
            echo "<center><h3>No categories Found</h3></center>";
        }
     ?>
<?php require_once('includes/footer.php'); ?>
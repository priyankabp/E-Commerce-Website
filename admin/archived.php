<?php
	require_once '../core/init.php';
	if (!is_logged_in()) {
		login_error_redirect();
	}
	require_once '../helpers/helpers.php';
	include 'includes/head.php';
	include 'includes/navigation.php';

	if (isset($_GET['restore'])) {
          $id = $_GET['id'];
          $restore = $_GET['restore'];
          $restore_query = "UPDATE `products` SET `deleted` = '$restore' WHERE `id` = '$id'";
          if (mysqli_query($db,$restore_query)) {
              $update_msg = "Product Restored";
          }
          else{
              $update_error = "Failed to Restore Product";
          }
    }
?>

<h2 class="text-center">Archived Products</h2><hr>
		<?php
          $get_products = "SELECT * FROM `products` WHERE deleted = 1";
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
	        	<tr class="bg-primary">
		            <th>Product</th>
		            <th>Price</th>
		            <th>Category</th>
		            <th>Sold</th>
		            <th>Restore</th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php while($get_product_row = mysqli_fetch_array($get_products_run)){
                          $product_id = $get_product_row['id'];
                          $product_title = $get_product_row['title'];
                          $product_price = $get_product_row['price'];
                          $product_category_id = $get_product_row['categories'];
                          $category_query = "SELECT * FROM `categories` WHERE id = '$product_category_id'";
                          $category_run = mysqli_query($db,$category_query);
                          if (mysqli_num_rows($category_run)>0) {
                            $category_row = mysqli_fetch_array($category_run);
                            $child = $category_row['category'];
                            $parent_ID = $category_row['parent'];
                          }
                          $parent_query = "SELECT * FROM categories WHERE id = '$parent_ID'";
                          $parent_run = mysqli_query($db,$parent_query);
                          if (mysqli_num_rows($parent_run)>0) {
                            $parent_row = mysqli_fetch_array($parent_run);
                            $child_parent = $parent_row['category'];
                          }
                         
                          $product_category = $child_parent.' / '.$child;
                          $product_deleted = $get_product_row['deleted'];
                      ?>
                      <tr>
                        <td><?php echo $product_title;?></td>
                        <td><?php echo money($product_price);?></td>
                        <td><?php echo $product_category;?></td>
                        <td>0</td>
                        <td>
                            <a href="archived.php?restore=<?php echo (($product_deleted == 1)?'0':'1');?>&id=<?php echo $product_id;?>" class="">
                            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                              </a>
                              &nbsp <?php echo (($product_deleted == 1)?'Restore?':'');?>
                        </td>
                      </tr>
                  <?php } ?>
          </tbody>
        </table>
        <?php 
              } 
              else{
                  echo "<center><h3>No Products Available</h3></center>";
              }
        ?>
<?php include 'includes/footer.php';?>
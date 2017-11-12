<?php
	  require_once '../core/init.php';
    require_once '../helpers/helpers.php';
	  include 'includes/head.php';
	  include 'includes/navigation.php';


    #UI display code when Add Product is not clicked 
    if (isset($_GET['add'])) {
      $get_brand = $db->query("SELECT * FROM brands ORDER BY brand");
      $get_parent = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

      $weightsArray = array();
      if ($_POST) {
        //get products parameters
        $title = $_POST['title'];
        $brand = $_POST['brand'];
        $categories = $_POST['child'];
        $price = $_POST['price'];
        $list_price = $_POST['listprice'];
        $weights = $_POST['weights'];
        $description = $_POST['description'];
        $dbpath = '';

        $errors = array();
          if (!empty($_POST['weights'])) {
              $weightString = $_POST['weights'];
              $trimWeightString = rtrim($weightString,','); 
              $weightsArray = explode(',',$trimWeightString);
              echo $trimWeightString;
              $wArray = array();
              $qArray = array();
              foreach ($weightsArray as $ws) {
                  $w = explode(':', $ws);
                  $q = explode(':', $ws);
                  $wArray[] = $w[0];
                  $qArray[] = $q[1]; 
              }
          }
          else{
              $weightsArray = array();
          }

          $required = array('title','brand','price','parent','child','weights');
          foreach ($required as $field) {
              if ($_POST[$field]=='') {
                $errors[] = "All fields (*) are required";
                break;
              }
          }
          if (!empty($_FILES)) {
              var_dump($_FILES);
              $photo = $_FILES['photo'];
              $name = $photo['name'];
              $nameArray = explode('.',$name);
              $fileName = $nameArray[0];
              $fileExt = $nameArray[1];
              $mime = explode('/', $photo['type']);
              $mimeType = $mime[0];
              $mimeExt = $mime[1];
              $tmpLoc = $photo['tmp_name'];
              $fileSize = $photo['size'];
              $allowed = array('png','jpg','jpeg','gif');
              $uploadName = md5(microtime()).'.'.$fileExt;
              $uploadPath = BASEURL.'images/products/'.$uploadName;
              $dbpath = '/E-Commerce-Website/images/products/'.$uploadName;
              if ($mimeType != 'image') {
                  $errors[] = "File must be an image";
              }
              if (!in_array($fileExt,$allowed)) {
                  $errors[] = "Photo must be png, jpg, jpeg or gif.";
              }
              if ($fileSize > 15000000) {
                  $errors[] = "File size must be under 15MB.";
              }
              if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
                  $errors[] = "File extension does not match.";
              }
          }
          if (!empty($errors)) {
              echo display_errors($errors);
          }
          else{
              //upload file and insert into database
              $insert_product_query = "INSERT INTO `products` (`title`,`price`,`list_price`,`brand`,`categories`,`image`,`description`,`weights`) VALUES ('$title','$price','$list_price','$brand','$categories','$dbpath','$description','$weights')";
              if (mysqli_query($db,$insert_product_query)) {
                    $msg = "New Product Added";
                    move_uploaded_file($tmpLoc, $uploadPath);
              }
              else{
                    echo "Error: " . $insert_product_query . "<br>" . $connection->error;
                    $error = "New Product Not Added";
              }
              header('location: products.php');
          }
      }

?>
  <!-- Add Product Form -->
  <h2 class="text-center">Add New Product</h2><hr>
  <?php 
      if (isset($error)) {
          echo "<span class='pull-right' style='color:red;'>$error</span>";
      }
      elseif (isset($msg)) {
          echo "<span class='pull-right' style='color:green;'>$msg</span>";
      }
  ?>
  <form action="products.php?add=1" method="post" enctype="multipart/form-data">
      <div class="form-group col-md-3">
          <label for="title">Title*:</label>
          <input type="text" name="title" class="form-control" id="title" value="<?php echo ((isset($_POST['title']))?$_POST['title']:'');?>">
      </div>
      <div class="form-group col-md-3">
          <label for="brand">Brand*:</label>
          <select class="form-control" id="brand" name="brand">
              <option value=""<?php echo ((isset($_POST['brand']) && $_POST['brand'] == '')?' selected':''); ?>></option>
              <?php while($get_brand_row = mysqli_fetch_assoc($get_brand)): ?>
                <option value="<?php echo $get_brand_row['id']; ?>" <?php ((isset($_POST['brand']) && $_POST['brand'] == $get_brand_row['id'])? ' selected':'');?> ><?php echo $get_brand_row['brand']; ?></option>
              <?php endwhile; ?>
          </select>
      </div>
      <div class="form-group col-md-3">
          <label for="parent">Parent Category*:</label>
          <select class="form-control" id="parent" name="parent">
              <option value=""<?php echo ((isset($_POST['parent']) && $_POST['parent'] == '')?' selected':''); ?>></option>
              <?php while($get_parent_row = mysqli_fetch_assoc($get_parent)): ?>
                <option value="<?php echo $get_parent_row['id']; ?>" <?php ((isset($_POST['parent']) && $_POST['parent'] == $get_parent_row['id'])? ' selected':'');?> ><?php echo $get_parent_row['category']; ?></option>
              <?php endwhile; ?>
          </select>
      </div>
      <div class="form-group col-md-3">
          <label for="child">Child Category*:</label>
          <select class="form-control" id="child" name="child">
            
          </select>
      </div>
      <div class="form-group col-md-3">
          <label for="price">Price*:</label>
          <input id="price" type="text" name="price" class="form-control" value="<?php echo ((isset($_POST['price']))?$_POST['price']:'');?>">
      </div>
      <div class="form-group col-md-3">
          <label for="list_price">List Price:</label>
          <input id="list_price" type="text" name="listprice" class="form-control" value="<?php echo ((isset($_POST['list_price']))?$_POST['list_price']:'');?>">
      </div>
      <div class="form-group col-md-3">
          <label>Quantity & Weights *:</label>
          <button class="btn btn-primary form-control" onclick="$('#weightsModal').modal('toggle');return false;">Quanity & Weights</button>
      </div>
      <div class="form-group col-md-3">
          <label for="weights">Weights & Qty Preview</label>
          <input class="form-control" type="text" name="weights" id="weights" value="<?php echo ((isset($_POST['weights']))?$_POST['sizes']:'');?>" readonly>
      </div>
      <div class="form-group col-md-6">
          <label for="photo">Product Photo:</label>
          <input type="file" name="photo" id="photo" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label for="description">Description:</label>
        <textarea class="form-control" rows="6" type="text" name="description" id="description"><?php echo ((isset($_POST['description']))? $_POST['description']:'');?></textarea>
      </div>
      <div class="form-group pull-right">
          <input type="submit" value="Add Product" class="form-control btn btn-primary pull-right" name="">
      </div><div class="clearfix"></div>
  </form>

  <!-- Modal -->
<div class="modal fade" id="weightsModal" tabindex="-1" role="dialog" aria-labelledby="weightsModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="weightsModalLabel">Quantity & Weights</h4>
      </div>
      <div class="modal-body">
          <div class="container-fluid">
              <?php for ($i=1; $i <=12 ; $i++): ?>
                  <div class="form-group col-md-4">
                      <label for="weight<?=$i;?>">Weight:</label>
                      <input type="text" name="weight<?=$i;?>" id="weight<?=$i;?>" value="<?=((!empty($wArray[$i-1]))? $wArray[$i-1]:'');?>" class="form-control">
                  </div>
                  <div class="form-group col-md-2">
                      <label for="quantity<?=$i;?>">Quantity:</label>
                      <input type="number" name="quantity<?=$i;?>" id="quantity<?=$i;?>" value="<?=((!empty($qArray[$i-1]))? $qArray[$i-1]:'');?>" min="0" class="form-control">
                  </div>
              <?php endfor; ?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateWeights(); $('weightsModal').modal('toggel');return false; ">Save changes</button>
      </div>
    </div>
  </div>
</div> 

<!--UI display code when Add Product is not clicked -->
<?php
    }
    else{
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
      	<h2 class="text-center">Products</h2>
        <a href="products.php?add=1" class="btn btn-primary pull-right" id="add-product-btn">Add Product</a>
        <div class="clearfix"></div>
        <hr>
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
<?php 
    }#else close
    require_once('includes/footer.php'); ?>
<?php
	require_once '../core/init.php';
  if (!is_logged_in()) {
    login_error_redirect();
  }
	include 'includes/head.php';
	include 'includes/navigation.php';


  if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
  }
  if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
      $del_query = "DELETE FROM brands WHERE id = '$delete_id'";
        if(mysqli_query($db,$del_query)){
          $del_msg = "Brand has been deleted";
        }
        else{
          $del_error = "Brand has not been deleted";
        }
  }
  if (isset($_POST['submit'])) {
    $brand_name = mysqli_real_escape_string($db,strtolower($_POST['brand_name']));
    if (empty($brand_name)) {
      $error = "Please add brand name";
    }
    else{
      $check_query = "SELECT * FROM brands WHERE brand = '$brand_name'";
      $check_run = mysqli_query($db,$check_query);
      if (mysqli_num_rows($check_run) > 0) {
        $error = ucfirst($brand_name)." already exits";
      }
      else{
        $insert_query = "INSERT INTO `e_commerce`.`brands` (`brand`) VALUES ('$brand_name');";
        if (mysqli_query($db,$insert_query)) {
          $msg = "New Brand added";
        }
        else{
          $error = "Failed to add New Brand";
        }
      }
    }
  }
  if (isset($_POST['update'])) {
    $brand_name = mysqli_real_escape_string($db,strtolower($_POST['brand_name']));
    if (empty($brand_name)) {
      $update_error = "Please add brand name";
    }
    else{
      $check_query = "SELECT * FROM brands WHERE brand = '$brand_name'";
      $check_run = mysqli_query($db,$check_query);
      if (mysqli_num_rows($check_run) > 0) {
        $update_error = ucfirst($brand_name)." already exits";
      }
      else{
        $update_query = "UPDATE `brands` SET `brand`='$brand_name' WHERE `id`='$edit_id'";
        if (mysqli_query($db,$update_query)) {
          $update_msg = "Brand name updated";
        }
        else{
          $update_error = "Failed to update Brand Name";
        }
      }
    }
  }

	
?>
<div class="row">

                    <?php
                    //get brands from db
                      $get_brand = "SELECT * FROM `e_commerce`.`brands` ORDER BY brand";
                      $get_brand_run = mysqli_query($db,$get_brand);
                      if (mysqli_num_rows($get_brand_run)>0) {
                        if (isset($del_msg)) {
                          echo "<span class='pull-right' style='color:green;'>$del_msg</span>";
                        }
                        elseif (isset($del_error)) {
                          echo "<span class='pull-right' style='color:red;'>$del_error</span>";
                        }
                    ?>
                    <h2 class="text-center">Brands </h2><hr>

                      <form action="" method="post" class="form-inline text-center">
                        <div class="form-group">
                          <label for="brand">Brand Name:</label>
                          <?php 
                            if (isset($msg)) {
                              echo "<span class='pull-right' style='color:green;'>$msg</span>";
                            }
                            elseif (isset($error)) {
                              echo "<span class='pull-right' style='color:red;'>$error</span>";
                            }
                          ?>
                          <input type="text" placeholder="Brand Name" class="form-control" name="brand_name">
                          <input type="submit" value="Add Brand" name="submit" class="btn btn-primary">
                        </div>
                      </form>

                      <hr>

                      <?php 
                        if (isset($_GET['edit'])) {
                          $edit_check_query = "SELECT * FROM brands WHERE id = $edit_id";
                          $edit_check_run = mysqli_query($db,$edit_check_query);
                          if (mysqli_num_rows($edit_check_run) > 0 ) {
                            
                          $edit_row = mysqli_fetch_array($edit_check_run);
                          $update_brand = $edit_row['brand'];
                          
                      ?>
                        <form action="" method="post" class="form-inline text-center">
                          <div class="form-group">
                              <label for="brand">Update Brand Name:</label>
                              <?php 
                                if (isset($update_msg)) {
                                  echo "<span class='pull-right' style='color:green;'>$update_msg</span>";
                                }
                                elseif (isset($update_error)) {
                                  echo "<span class='pull-right' style='color:red;'>$update_rror</span>";
                                }
                              ?>
                              <input type="text" value="<?php echo $update_brand;?>" placeholder="Brand Name" class="form-control" name="brand_name">
                          </div>
                          <input type="submit" value="Update Brand" name="update" class="btn btn-primary">
                          </form>

                          <hr>
                      <?php
                          }
                        }
                      ?>

                      

                      <table class="table table-bordered table-striped table-hover table-auto">
                        <thead>
                          <tr class="bg-primary">
                            <th>#</th>
                            <th>Brand</th>
                            <th>Edit</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            while ($get_brands_row = mysqli_fetch_array($get_brand_run)) {
                              $brand_id = $get_brands_row['id'];
                              $brand_name = $get_brands_row['brand'];
                          ?>
                          <tr>
                            <td><?php echo $brand_id;?></td>
                            <td><?php echo ucfirst($brand_name);?></td>
                            <td><a href="brands.php?edit=<?php echo $brand_id;?>"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                            <td><a href="brands.php?delete=<?php echo $brand_id;?>"><i class="fa fa-times" aria-hidden="true"></i></a></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table> 

                      <?php } 
                        else{
                            echo "<center><h3>No Brands Found</h3></center>";
                        }
                      ?>
            
</div>
<?php include 'includes/footer.php';?>
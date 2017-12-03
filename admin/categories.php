<?php 
  require_once '../core/init.php';
  if (!is_logged_in()) {
    login_error_redirect();
  }
  include 'includes/head.php';
  include 'includes/navigation.php';

//Edit Category
if (isset($_GET['edit'])) {
  $edit_id = $_GET['edit'];
}

//Delete Category
if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $get_delete_parent = "SELECT * FROM categories WHERE id = '$delete_id'";
  $get_delete_parent_run = mysqli_query($db,$get_delete_parent);
  $get_delete_parent_category = mysqli_fetch_assoc($get_delete_parent_run);
  if ($get_delete_parent_category['parent']==0) {
    $delete_query = "DELETE FROM categories WHERE parent = '$delete_id'";
    if(mysqli_query($db,$delete_query)){
        $del_msg = "Category has been deleted";
      }
      else{
        $del_error = "Category has not been deleted";
      }
  }
    $del_query = "DELETE FROM categories WHERE id = '$delete_id'";
      if(mysqli_query($db,$del_query)){
        $del_msg = "Category has been deleted";
      }
      else{
        $del_error = "Category has not been deleted";
      }
}

//Add Category
if (isset($_POST['submit'])) {
  $parent_name = $_POST['parent'];
  $category_name = mysqli_real_escape_string($db,strtolower($_POST['category_name']));
  if (empty($category_name)) {
    $error = "Please add category name";
  }
  else{
    $check_query = "SELECT * FROM categories WHERE `category` = '$category_name' AND `parent` = '$parent_name'";
    $check_run = mysqli_query($db,$check_query);
    if (mysqli_num_rows($check_run) > 0) {
      $error = "Category name already exits";
    }
    else{
      $insert_query = "INSERT INTO `categories` (category,parent) VALUES ('$category_name','$parent_name');";
      if (mysqli_query($db,$insert_query)) {
        $msg = "New Category added";
      }
      else{
        $error = "Failed to add New Category";
      }
    }
  }
}

if (isset($_POST['update'])) {
  $parent_name = $_POST['parent'];
  $category_name = mysqli_real_escape_string($db,strtolower($_POST['category_name']));
  if (empty($category_name)) {
    $update_error = "Please add category name";
  }
  else{
    $check_query = "SELECT * FROM categories WHERE `category` = '$category_name' and `parent` = '$parent_name'";
    $check_run = mysqli_query($db,$check_query);
    if (mysqli_num_rows($check_run) > 0) {
      $update_error = "Category name already exits";
    }
    else{
      $update_query = "UPDATE `categories` SET `category`='$category_name',`parent`='$parent_name' WHERE `id`='$edit_id'";
      if (mysqli_query($db,$update_query)) {
        $update_msg = "Category name updated";
      }
      else{
        $update_error = "Failed to update Category Name";
      }
    }
  }
}
?> 
    <h2 class="text-center">Categories</h2><hr>
    <div class="row">

        <!--Form-->
        <?php
            $get_category_parent = "SELECT * FROM `categories` WHERE parent = 0";
            $get_category_parent_run = mysqli_query($db,$get_category_parent);
            if (mysqli_num_rows($get_category_parent_run)>0) {
        ?>
        <div class="col-md-6">
                <form action="" method="post">
                  <legend>Add a Category</legend>

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

                  <div class="form-group">
                    <label for="category">Category Name:</label>
                    <?php 
                      if (isset($msg)) {
                        echo "<span class='pull-right' style='color:green;'>$msg</span>";
                      }
                      elseif (isset($error)) {
                        echo "<span class='pull-right' style='color:red;'>$error</span>";
                      }
                    ?>
                    <input type="text" placeholder="Category Name" class="form-control" name="category_name">
                  </div>
                  <input type="submit" value="Add Category" name="submit" class="btn btn-primary">
                </form>

                <?php 
                  if (isset($_GET['edit'])) {
                    $edit_check_query = "SELECT * FROM categories WHERE id = '$edit_id'";
                    $edit_check_run = mysqli_query($db,$edit_check_query);
                    if (mysqli_num_rows($edit_check_run) > 0 ) {
                      

                    $edit_row = mysqli_fetch_array($edit_check_run);
                    $update_parent = $edit_row['parent'];
                    $update_category = $edit_row['category'];
                    
                ?>
                <hr>
                    <?php
                        $get_category_parent = "SELECT * FROM `categories` WHERE parent = 0";
                        $get_category_parent_run = mysqli_query($db,$get_category_parent);
                        if (mysqli_num_rows($get_category_parent_run)>0) {
                    ?>

                    <form action="" method="post">

                      <legend>Update Category</legend>

                      <div class="form-group">
                          <label for="parent">Parent</label>
                          <select class="form-control" name="parent" id="parent">
                              <option value="0">Parent</option>
                              <?php
                                  while ($parent_row = mysqli_fetch_array($get_category_parent_run)) {
                                  $parent_id = $parent_row['id'];
                                  $parent_name = $parent_row['category'];
                              ?>
                              
                          <option value="<?php echo $update_parent?>"><?php echo $parent_name; ?></option>
                          <?php } ?>
                        </select>
                      </div>  

                      <div class="form-group">
                        <label for="category">Update Category Name:</label>
                        <?php 
                          if (isset($update_msg)) {
                            echo "<span class='pull-right' style='color:green;'>$update_msg</span>";
                          }
                          elseif (isset($update_error)) {
                            echo "<span class='pull-right' style='color:red;'>$update_error</span>";
                          }
                        ?>
                        <input type="text" value="<?php echo $update_category;?>" placeholder="Category Name" class="form-control" name="category_name">
                      </div>
                      <input type="submit" value="Update Category" name="update" class="btn btn-primary">
                    </form>
                  <?php
                          } 
                          else{
                              echo "<center><h3>No categories Found</h3></center>";
                          }
                      }
                  }
                  ?>
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
                    $get_category_parent = "SELECT * FROM `categories` WHERE parent = 0";
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
  <?php require_once('includes/footer.php'); ?>
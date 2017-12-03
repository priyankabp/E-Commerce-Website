<?php
  $sql = "SELECT * FROM categories WHERE parent = 0";
  $parentquery = $db->query($sql);
?>
<!-- Top navbar -->
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">

        <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a href="index.php" class="navbar-brand"> E-Farmarket</a>
        </div>

        <div class="container collapse navbar-collapse" id="bs-example-navbar-collapse-2">
            <ul class="nav navbar-nav navbar-right">
                <?php while($parent = mysqli_fetch_assoc($parentquery)) : ?>

                  <?php 
                    $parent_id = $parent['id']; 
                    $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
                    $childquery = $db->query($sql2);
                  ?>
                  <!-- Menu Items -->
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <?php echo $parent['category']; ?>
                      <span class="caret"></span></a>
                      <ul class="dropdown-menu" role="menu">
                        <?php while($child = mysqli_fetch_assoc($childquery)) : ?>
                          <li><a href="category.php?category=<?php echo $child['id'];?>"><?php echo $child['category']; ?></a></li>
                        <?php endwhile; ?>
                      </ul>
                  </li>
                <?php endwhile; ?>
                <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?php echo $user_data['firstname'];?>!
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role=menu>
                      <li><a href="change_password.php">Change Password</a></li>
                      <li><a href="logout.php">Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
  </nav>
<?php
  $sql = "SELECT * FROM categories WHERE parent = 0";
  $parentquery = $db->query($sql);
?>
<!-- Top navbar -->
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <a href="index.php" class="navbar-brand"> Online Farm Products</a>
      <ul class="nav navbar-nav">
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
                  <li><a href="#"><?php echo $child['category']; ?></a></li>
                <?php endwhile; ?>
              </ul>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>
  </nav>
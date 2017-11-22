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
            <a class="navbar-brand" href="index.php">Admin | E-Farmarket</a>
      </div>

      <div class="container collapse navbar-collapse" id="bs-example-navbar-collapse-2">
        <ul class="nav navbar-nav navbar-right">
            <!-- Menu Items -->
            <?php if(has_role('vendor')): ?>
              <li><a href="index.php">My Dashboard</a></li>
              <li><a href="brands.php">Brands</a></li>
              <li><a href="categories.php">Categories</a></li>
              <li><a href="products.php">Products</a></li>
              <li><a href="archived.php">Archived</a></li>
            <?php endif; ?>
            <?php if(has_role('admin')): ?>
              <li><a href="users.php">Users</a></li>
            <?php endif; ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?php echo $user_data['firstname'];?>!
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role=menu>
                  <li><a href="change_password.php">Change Password</a></li>
                  <li><a href="logout.php">Log Out</a></li>
                </ul>
            </li>
            <!-- <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php echo $parent['category']; ?>
                <span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#"></a></li>
                </ul>
            </li> -->
        </ul>
      </div>
    </div>
  </nav>
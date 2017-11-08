<?php
	require_once '../core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';

	//get brands from db
	$get_brand = "SELECT * FROM `e_commerce`.`brand` ORDER BY brand";
	$get_brand_run = mysqli_query($db,$get_brand);
?>
<h2 class="text-center">Brands </h2>
<table class="table table-bordered table-striped table-hover table-auto">
  <thead>
    <tr>
      <th>#</th>
      <th>Brand</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody>
  
  	<tr>
      <td>1</td>
      <td>Levis</td>
      <td><a href="brands.php?edit=1"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
      <td><a href="brands.php?delete=1"><i class="fa fa-times" aria-hidden="true"></i></a></td>
    </tr>
    
  </tbody>
</table> 

<?php include 'includes/footer.php';?>
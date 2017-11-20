<?php 
  require_once 'core/init.php';
  include 'includes/head.php'; 
  include 'includes/navigation.php';
  include 'includes/headerpartial.php';
  include 'includes/leftbar.php';

  $sql = "SELECT * FROM products";
  $category_id = (($_POST['category'] != '')? $_POST['category']:'');
  if ($category_id == ''){
    $sql .= " WHERE deleted = 0";
  }
  else{
    $sql .= " WHERE categories = '{$category_id}' AND deleted = 0 ";
  }
  $price_sort = (($_POST['price_sort'] != '')? $_POST['price_sort']:'');
  $min_price = (($_POST['min_price'] != '')? $_POST['min_price']:'');
  $max_price = (($_POST['max_price'] != '')? $_POST['max_price']:'');
  $brand = (($_POST['brand'] != '')? $_POST['brand']:'');

  if ($min_price != '') {
    $sql .= " AND price >= '{$min_price}'";
  }
  if ($max_price != '') {
    $sql .= " AND price <= '{$max_price}'";
  }
  if ($brand != '') {
    $sql .= " AND brand = '{$brand}'";
  }
  if ($price_sort == 'low') {
    $sql .= " ORDER BY price";
  }
  if ($price_sort == 'high') {
    $sql .= " ORDER BY price DESC";
  }
  $category_product = $db->query($sql);
  $category = get_category($category_id);
?>
  
  <!-- Main content -->
  <div class="col-md-8">
    <div class="row">
      <?php if($category_id != ''): ?>
        <h2 class="text-center"><?php echo $category['parent'].' '.$category['child'];?></h2>
    <?php else: ?>
        <h2 class="text-center">E-Farmarket</h2>
    <?php endif;?>
      <?php while($product = mysqli_fetch_assoc($category_product)) : ?>
        <div class="col-md-3">
          <h4 class="text-center"><?= $product['title']; ?></h4>
          <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb" />
          <p class="list-price text-danger text-center">List Price: <s>$<?= $product['list_price']; ?></s></p>
          <p class="price text-center">Our Price : $<?= $product['price']; ?></p>
          <!-- TODO: Move button to center -->
          <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
  <script type="text/javascript">
    function closeModal(){
      $('#details-modal').modal('hide');
      setTimeout(function(){
        $('#details-modal').remove();
        $('.modal-backdrop').remove();
      },500);
    }
  </script>

<?php
  include 'includes/rightbar.php';
  include 'includes/footer.php';
?>
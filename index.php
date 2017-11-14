<?php 
  require_once 'core/init.php';
  include 'includes/head.php'; 
  include 'includes/navigation.php';
  include 'includes/headerfull.php';
  include 'includes/leftbar.php';

  $sql = "SELECT * FROM products WHERE featured = 1";
  $featured = $db->query($sql);
?>
  
  <!-- Main content -->
  <div class="col-md-8">
    <div class="row">
      <h2 class="text-center">Featured Products</h2>
      <?php while($product = mysqli_fetch_assoc($featured)) : ?>
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
<?php
	  require_once 'core/init.php';
	  include 'includes/head.php'; 
	  include 'includes/navigation.php';
	  include 'includes/headerpartial.php';
	  if ($cart_id != '') {
	  	$cart_query = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
	  	$result = mysqli_fetch_assoc($cart_query);
	  	$items = json_decode($result['items'],true);
	  	$i = 1;
	  	$subtotal = 0;
	  	$item_count = 0;
	  }
?>

<div class="col-md-12">
	<div class="row">
		<h2 class="text-center">My Shopping Cart</h2><hr>
		<?php if($cart_id == ''):?>
			<div class="bg-warning">
				<p class="text-center">
					Your Shopping Cart is Empty!
				</p>
			</div>
		<?php else: ?>
			<table class="table table-bordered table-condensed table-stripped">
				<thead class="bg-primary">
					<tr>
						<th>#</th>
						<th>Item</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Size</th>
						<th>Subtotal</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach ($items as $item) {
							$product_id = $item['id'];
							$product_query = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
							$product = mysqli_fetch_assoc($product_query);
							$weightArray = explode(',', $product['weight']);
							foreach ($weightArray as $weightString) {
								$w = explode(':', $weightString);
								if ($w[0] == $item['size']) {
									$available = $w[1];
								}
							}
					?>
						 <tr>
							<td><?php echo $i;?></td>
							<td><?php echo $product['title'];?></td>
							<td><?php echo money($product['price']);?></td>
							<td><?php echo $item['quantity'];?></td>
							<td><?php echo $item['weight'];?></td>
							<td><?php echo money($item['quantity'] * $product['price']);?></td>
					     </tr>
					<?php 
						$i++;
						$item_count += $item['quantity'];
						$subtotal += ($product['price'] * $item['quantity']);
						} 

						$tax = TAXRATE * $subtotal;
						$tax = number_format($tax,2);
						$grand_total = $tax + $subtotal;
					?>
				</tbody>
			</table>

			<table class="table table-bordered table-condensed text-right">
				<legend>Totals</legend>
				<thead class="bg-primary total-table-header">
					<tr>
						<th>Total Items</th>
						<th>Sub Total</th>
						<th>Tax</th>
						<th>Grand Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $item_count;?></td>
						<td><?php echo money($subtotal);?></td>
						<td><?php echo money($tax);?></td>
						<td class="bg-warning"><?php echo money($grand_total);?></td>
					</tr>
				</tbody>
			</table>
			<!-- Checkout Button -->
			<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
			  <span class="glyphicon glyphicon-shopping-cart"></span> Check Out >>
			</button>

			<!-- Modal -->
			<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
			      </div>
			      <div class="modal-body">
			        ...
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        <button type="button" class="btn btn-primary">Save changes</button>
			      </div>
			    </div>
			  </div>
			</div>
		<?php endif;  ?>
	</div>
</div>
<?php include 'includes/footer.php'; ?>
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
							$weightArray = explode(',', $product['weights']);
							foreach ($weightArray as $weightString) {
								$w = explode(':', $weightString);
								if ($w[0] == $item['weight']) {
									$available = $w[1];
								}
							}
					?>
						 <tr>
							<td><?php echo $i;?></td>
							<td><?php echo $product['title'];?></td>
							<td><?php echo money($product['price']);?></td>
							<td>
								 <button class="btn btn-xs btn-primary" onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['weight'];?>');">-</button>
								 <?php echo $item['quantity'];?>	
								 <?php if($item['quantity']< $available): ?>
								     <button class="btn btn-xs btn-primary" onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['weight'];?>');">+</button>
							     <?php else:?>
							     	 <span class="text-danger">Max</span>
							     <?php endif;?>
							</td>
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
			  <div class="modal-dialog modal-lg" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
			      </div>
			      <div class="modal-body">
			      	<div class="row">
				        <form action="thankyou.php" method="post" id="payment-form">
				        	<span class="bg-warning" id="payment-errors"></span>
				        	<input type="hidden" name="tax" value="<?=$tax;?>">
				        	<input type="hidden" name="sub_total" value="<?=$subtotal;?>">
				        	<input type="hidden" name="grand_total" value="<?=$grand_total;?>">
				        	<input type="hidden" name="cart_id" value="<?=$cart_id;?>">
				        	<input type="hidden" name="description" value="<?=$item_count.' item'.(($item_count>1)?'s':'').' from E-Farmarket !';?>">
				        	<div id="step1" style="display: block;">
				        		<div class="form-group col-md-6">
				        			<label for="full_name">Full Name:</label>
				        			<input class="form-control" id="full_name" type="text" name="full_name">
				        		</div>

				        		<div class="form-group col-md-6">
				        			<label for="email">Email:</label>
				        			<input class="form-control" id="email" type="email" name="email">
				        		</div>

				        		<div class="form-group col-md-6">
				        			<label for="street">Street Address:</label>
				        			<input class="form-control" id="street" type="text" name="street">
				        		</div>

				        		<div class="form-group col-md-6">
				        			<label for="street2">Street Address 2:</label>
				        			<input class="form-control" id="street2" type="text" name="street2">
				        		</div>

				        		<div class="form-group col-md-6">
				        			<label for="city">City:</label>
				        			<input class="form-control" id="city" type="text" name="city">
				        		</div>

				        		<div class="form-group col-md-6">
				        			<label for="state">State:</label>
				        			<input class="form-control" id="state" type="text" name="state">
				        		</div>

				        		<div class="form-group col-md-6">
				        			<label for="zip_code">Zip Code:</label>
				        			<input class="form-control" id="zip_code" type="text" name="zip_code">
				        		</div>

				        		<div class="form-group col-md-6">
				        			<label for="country">Country:</label>
				        			<input class="form-control" id="country" type="text" name="country">
				        		</div>
				        	</div>
				        	<div id="step2" style="display: none;">
				        		<h4><p class="modal-body"> You can still go back and make changes, Just in case ! </p></h4>
				        	</div>

			
			        </div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        <button type="button" class="btn btn-primary" id="next_button" onclick="check_address();">Next >></button>
			        <button type="button" class="btn btn-primary" id="back_button" onclick="back_address();" style="display: none;"><< Back</button>
			        <button type="submit" class="btn btn-primary" id="checkout_button" style="display: none;">Check Out >></button>
			        </form>
			      </div>
			    </div>
			  </div>
			</div>
		<?php endif;  ?>
	</div>
</div>

<script type="text/javascript">

	function back_address(){
		$('#payment-errors').html("");
		$('#step1').css("display","block");
		$('#step2').css("display","none");
		$('#next_button').css("display","inline-block");
		$('#back_button').css("display","none");
		$('#checkout_button').css("display","none");
		$('#checkoutModalLabel').html("Shipping Address");
	}
	function check_address(){
		var data = {
			'full_name' : $('#full_name').val(),
			'email'     : $('#email').val(),
			'street'    : $('#street').val(),
			'street2'   : $('#street2').val(),
			'city'      : $('#city').val(),
			'state'     : $('#state').val(),
			'zip_code'  : $('#zip_code').val(),
			'country'   : $('#country').val(),
		};
		$.ajax({
			url : '/E-Commerce-Website/admin/includes/check_address.php',
			method : 'POST',
			data : data,
			success : function(data){
				if (data != 'passed') {
					$('#payment-errors').html(data);
				}
				if (data == 'passed') {
					$('#payment-errors').html("");
					$('#step1').css("display","none");
					$('#step2').css("display","block");
					$('#next_button').css("display","none");
					$('#back_button').css("display","inline-block");
					$('#checkout_button').css("display","inline-block");
					$('#checkoutModalLabel').html("Final Checkout");
				}
			},
			error : function(){alert("Something went wrong");},
		});
	}




</script>
<?php include 'includes/footer.php'; ?>
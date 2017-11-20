<h3 class="text-center">Shopping Cart</h3><hr>
<div>
	<?php if (empty($cart_id)): ?>
		<p class="text-center">Your cart is empty</p>
		
	<?php else: 
		$cartQ = $db->query("SELECT * FROM cart WHERE id = '$cart_id'"); 
		$results = mysqli_fetch_assoc($cartQ);
		$items = json_decode($results['items'],true);
		$subtotal = 0;
	?>
	<table class="table table-condensed" id="cart_widget">
		<tbody>
			<?php foreach ($items as $item): 
				$productQ = $db->query("SELECT * FROM products WHERE id = '{$item['id']}'");
				$product = mysqli_fetch_assoc($productQ);
			?>
			<tr class="text-center">
				<td><?=$item['quantity'];?></td>
				<td><?=substr($product['title'], 0,15); ?></td>
				<td><?=money($item['quantity'] * $product['price']);?></td>
			</tr>
			<?php 
				$subtotal += ($item['quantity']* $product['price']);
			endforeach; ?>
				<tr class="text-center">
					<td></td>
					<td>Sub total</td>
					<td><?=money($subtotal);?></td>
				</tr>
		</tbody>
	</table>
	<a href="cart.php" class="btn btn-xs btn-primary pull-right">View Cart</a>
	<div class="clearfix"></div>
	<?php endif; ?>
</div>
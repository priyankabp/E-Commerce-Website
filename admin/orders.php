<?php
	require_once "../core/init.php";
	if (!is_logged_in()) {
		header('location: login.php');
	}
	include 'includes/head.php';
	include 'includes/navigation.php';

	// Complete Order
	if (isset($_GET['complete']) && $_GET['complete'] == 1) {
		$cart_id = (int)$_GET['cart_id'];
		$db->query("UPDATE cart SET shipped = 1 WHERE id = '$cart_id'");
		$_SESSION['success_flash'] = "The order has been completed.";
		header('location: index.php');
	}

	$txn_id = (int)$_GET['txn_id'];
	$transactionQuery = $db->query("SELECT * FROM transactions WHERE id = '{$txn_id}'");
	$transaction = mysqli_fetch_assoc($transactionQuery);
	$cart_id = $transaction['cart_id'];

	$cartQuery = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
	$cart = mysqli_fetch_assoc($cartQuery);

	$items = json_decode($cart['items'],true);
	$idArray = array();
	$products = array();
	foreach ($items as $item) {
		$idArray[] = $item['id']; 
	}
	$ids = implode(',', $idArray);
	$productQuery = $db->query("
		SELECT i.id as 'id',i.title as 'title',c.id as 'cid',c.category as 'child',p.category as 'parent'
		FROM products i
		LEFT JOIN categories c ON i.categories = c.id
		LEFT JOIN categories p ON c.parent = p.id
		WHERE i.id IN ({$ids});
	");

	while ($product = mysqli_fetch_assoc($productQuery)) {
		foreach ($items as $item) {
			if ($item['id'] == $product['id']) {
				$x = $item;
				continue;
			}
		}
		$products[] = array_merge($x,$product);
	}
?>
<h2 class="text-center">Items Ordered</h2>
<table class="table table-condensed table-stripped table-bordered">
	<thead class="bg-primary">
		<th>Quantity</th>
		<th>Title</th>
		<th>Category</th>
		<th>Weight</th>
	</thead>
	<tbody>
		<?php foreach($products as $product): ?>
			<tr>
				<td><?php echo $product['quantity'];?></td>
				<td><?php echo $product['title'];?></td>
				<td><?php echo $product['parent'].' / '.$product['child'];?></td>
				<td><?php echo $product['weight'];?></td>
			</tr>
	    <?php endforeach; ?>
	</tbody>
</table>

<div class="row">
	<div class="col-md-6">
		<h2>Order Details</h2>
		<table class="table table-condensed table-stripped table-bordered">
			<tbody class="bg-warning">
				<tr>
					<td><strong>Sub total</strong></td>
					<td><?php echo money($transaction['sub_total']);?></td>
				</tr>
				<tr>
					<td><strong>Tax</strong></td>
					<td><?php echo money($transaction['tax']);?></td>
				</tr>
				<tr>
					<td><strong>Grand Total</strong></td>
					<td><?php echo money($transaction['grand_total']);?></td>
				</tr>
				<tr>
					<td><strong>Order Date</strong></td>
					<td><?php echo $transaction['txn_date'];?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h2>Shipping Address</h2>
		<address>
			<?php echo $transaction['full_name']; ?><br>
			<?php echo $transaction['street']; ?><br>
			<?php echo (($transaction['street2'] != '')?$transaction['street2'].'<br>':''); ?>
			<?php echo $transaction['city'].','.$transaction['state'].'-'.$transaction['zip_code']; ?><br>
			<?php echo $transaction['country']; ?>
		</address>
	</div>
	<div class="pull-right">
		<a href="index.php" class="btn btn-primary"> Cancel </a>
		<a href="orders.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-primary">Complete Order</a>
	</div>
</div>
<?php include 'includes/footer.php'; ?>
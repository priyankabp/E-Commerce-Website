<?php
	require_once '../core/init.php';
	if (!is_logged_in()) {
		header('location: login.php');
	}
	require_once '../helpers/helpers.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
?>

<!-- Orders To Fill -->
<?php
	$transaction_query = "SELECT t.id,t.cart_id,t.full_name,t.description,t.txn_date,t.grand_total,c.items,c.paid,c.shipped 
	FROM transactions t 
	LEFT JOIN cart c ON t.cart_id = c.id
	WHERE c.paid=1 AND c.shipped = 0
	ORDER BY t.txn_date";

	$transaction_query_result = $db->query($transaction_query);
?>
<div class="col-md-12">
	<h3 class="text-center">Order To Ship</h3>
	<table class="table table-condensed table-bordered table-stripped">
		<thead class="bg-primary">
			<tr>
				<th></th>
				<th>Name</th>
				<th>Description</th>
				<th>Total</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			<?php while($order = mysqli_fetch_assoc($transaction_query_result)):?>
				<tr>
					<td><a href="orders.php?txn_id=<?=$order['id'];?>" class="btn btn-xs btn-info">Details</a></td>
					<td><?php echo $order['full_name'];?></td>
					<td><?php echo $order['description'];?></td>
					<td><?php echo money($order['grand_total']);?></td>
					<td><?php echo $order['txn_date'];?></td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php include 'includes/footer.php';?>
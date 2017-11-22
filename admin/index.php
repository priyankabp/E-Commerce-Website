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
<div class="row">
	<!-- Sales by Month -->
	<?php

		date_default_timezone_set('UTC');
		$thisYear = date("Y");
		$lastYear = $thisYear - 1;
		$thisYearQuery = $db->query("SELECT grand_total, txn_date FROM transactions WHERE YEAR(txn_date) = '{$thisYear}'");
		$lastYearQuery = $db->query("SELECT grand_total, txn_date FROM transactions WHERE YEAR(txn_date) = '{$lastYear}'");
		$current = array();
		$last = array();
		$current_total = 0;
		$last_total = 0;
		while ($x = mysqli_fetch_assoc($thisYearQuery)) {
			$month = date("m",strtotime($x['txn_date']));
			if (!array_key_exists($month, $current)) {
				$current[(int)$month] = $x['grand_total'];
			}
			else{
				$current[(int)$month] += $x['grand_total'];
			}
			$current_total += $x['grand_total'];
		}

		while ($y = mysqli_fetch_assoc($lastYearQuery)) {
			$month = date("m",strtotime($y['txn_date']));
			if (!array_key_exists($month, $last)) {
				$last[(int)$month] = $y['grand_total'];
			}
			else{
				$last[(int)$month] += $y['grand_total'];
			}
			$last_total += $y['grand_total'];
		}
	?>
	<div class="col-md-4">
		<h3 class="text-center">Sales By Month</h3> 
		<table class="table table-condensed table-bordered table-stripped">
			<thead class="bg-primary">
				<th></th>
				<th><?php echo $lastYear?></th>
				<th><?php echo $thisYear?></th>
			</thead>
			<tbody>
				<?php for($i=1;$i<=12;$i++):
					$dt = date('F',mktime(0,0,0,$i, 1, date('Y')));
				?>
					<tr <?php echo (date('m') == $i)?' class="info"':'';?>>
						<td><?php echo $dt;?></td>
						<td><?php echo (array_key_exists($i, $last))?money($last[$i]):money(0);?></td>
						<td><?php echo (array_key_exists($i, $current))?money($current[$i]):money(0);?></td>
					</tr>
				<?php endfor; ?>
					<tr class="bg-primary">
						<td>Total</td>
						<td><?php echo money($last_total);?></td>
						<td><?php echo money($current_total);?></td>
					</tr>
			</tbody>
		</table>
	</div>

	<!-- Inventory -->
	<?php
		$inventoryQuery = $db->query("SELECT * FROM products WHERE deleted = 0");
		$lowItems = array();
		while($product = mysqli_fetch_assoc($inventoryQuery)){
			$item = array();
			$weights = weightsToArray($product['weights']);
			foreach ($weights as $weight) {
				if($weight['quantity'] != $weight['threshold']){
					$category = get_category($product['categories']);
					$item = array(
						'title' => $product['title'],
						'weight' => $weight['weight'],
						'quantity' => $weight['quantity'],
						'threshold' => $weight['threshold'],
						'category' => $category['parent'].' / '.$category['child']
					);
					$lowItems[] = $item;
			    }
			}
		}
	?>
	<div class="col-md-8">
		<h3 class="text-center">Low Inventory</h3>
		<table class="table-condensed table-stripped table-bordered table">
			<thead class="bg-primary">
				<th>Product</th>
				<th>Category</th>
				<th>Weight</th>
				<th>Quantity</th>
				<th>Threshold</th>
			</thead>
			<tbody>
				<?php foreach($lowItems as $item):?>
					<tr <?php echo ($item['quantity'] == 0 )? ' class="danger"':'';?>>
						<td><?php echo $item['title'];?></td>
						<td><?php echo $item['category'];?></td>
						<td><?php echo $item['weight'];?></td>
						<td><?php echo $item['quantity'];?></td>
						<td><?php echo $item['threshold'];?></td>
					</tr>
			    <?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<?php include 'includes/footer.php';?>
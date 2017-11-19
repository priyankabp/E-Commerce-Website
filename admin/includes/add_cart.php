<?php
	require_once '../../core/init.php';
	$product_id = $_POST['product_id'];
	$weight = $_POST['size'];
	$quantity = $_POST['quantity'];
	$available = $_POST['available'];
	$item = array();
	$item[] = array(
		'id'       => $product_id,
		'weight'   => $weight,
		'quantity' => $quantity,
	);

	$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
	$query = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
	$product = mysqli_fetch_assoc($query);
	$_SESSION['success_flash'] = $product['title'].' added to your cart.';

	//check if cart cookie exists
	if ($cart_id != '') {
		//get cart id
		$cart_query = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
		$cart = mysqli_fetch_assoc($cart_query);
		$previous_items = json_decode($cart['items'],true);
		$item_match = 0;
		$new_items = array();
		// check if new added item is equal to any previous item
		foreach ($previous_items as $previous_item) {
			if ($item[0]['id'] == $previous_item['id'] && $item[0]['weight'] == $previous_item['weight']) {
				$previous_item['quantity'] = $previous_item['quantity']+$item[0]['quantity'];
				if ($previous_item['quantity']> $available) {
					$previous_item['quantity'] = $available;
				}
				$item_match = 1;
			}
			$new_items[] = $previous_item;
		}

		// if new item does not equal to previous items, merge the two item arrays
		if ($item_match != 1) {
			$new_items = array_merge($item,$previous_items);
		}

		$items_json = json_encode($new_items);
		$cart_expire = date("Y-m-d H:i:s",strtotime("+ 30 days"));
		$db->query("UPDATE cart SET items = '{$items_json}',expire_date = '{$cart_expire}' WHERE id = '{$cart_id}'");
		setcookie(CART_COOKIE,'',1,'/',$domain,false);
		setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
	}
	else{
		//add product to cart, database and set cookie
		$items_json = json_encode($item);
		$cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
		$db->query("INSERT INTO cart (items,expire_date) VALUES ('{$items_json}','{$cart_expire}') ");
		$cart_id = $db->insert_id;
		setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
	}
?>
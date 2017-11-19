<?php
	require_once 'core/init.php';

	// get post data
	$full_name = sanitize($_POST['full_name']);
	$email = sanitize($_POST['email']);
	$street = sanitize($_POST['street']);
	$street2 = sanitize($_POST['street2']);
	$city = sanitize($_POST['city']);
	$state = sanitize($_POST['state']);
	$zip_code = sanitize($_POST['zip_code']);
	$country = sanitize($_POST['country']);
	$tax = sanitize($_POST['tax']);
	$sub_total = sanitize($_POST['sub_total']);
	$grand_total = sanitize($_POST['grand_total']);
	$cart_id = sanitize($_POST['cart_id']);
	$description = sanitize($_POST['description']);

	$db->query("UPDATE cart SET paid = 1 WHERE id = '$cart_id'");
	$db->query("INSERT INTO `e_commerce`.`transactions` (`cart_id`,`full_name`,`email`,`street`,`street2`,`city`,`state`,`zip_code`,`country`,`sub_total`,`tax`,`grand_total`,`description`) VALUES ('$cart_id','$full_name','$email','$street','$street2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description')");

	$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
	setcookie(CART_COOKIE,'',1,'/',$domain,false);

	include 'includes/head.php'; 
	include 'includes/navigation.php';
	include 'includes/headerpartial.php';
?>

<h1 class="text-center text-success">Thank You !</h1> <hr>
<p> Your have to pay <?php echo money($grand_total);?>.One of our Delivery guy will be out for delivery soon. Thanks for shopping with us.</p>
<p> Your receipt number is : <strong><?php echo $cart_id;?></strong></p>
<p> Your order will be shipped to the address below.</p>
<address>
	<?php echo $full_name; ?><br>
	<?php echo $street; ?><br>
	<?php echo (($street2 != '')?$street2.'<br>':''); ?><br>
	<?php echo $city.','.$state.'-'.$zip_code; ?><br>
	<?php echo $country; ?>
</address>


<?php include 'includes/footer.php'; ?>
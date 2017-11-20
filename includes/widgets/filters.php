<?php
	$category_id = ((isset($_REQUEST['category']))?$_REQUEST['category']:'');
	$price_sort = ((isset($_REQUEST['price_sort']))?$_REQUEST['price_sort']:'');
	$min_price = ((isset($_REQUEST['min_price']))?$_REQUEST['min_price']:'');
	$max_price = ((isset($_REQUEST['max_price']))?$_REQUEST['max_price']:'');
	$b = ((isset($_REQUEST['brand']))?$_REQUEST['brand']:'');
	$brandQ = $db->query("SELECT * FROM brands ORDER BY brand");
?>
<h3>Search By :</h3>
<h4>By Price</h4>
<form action="search.php" method="post">
	<input type="hidden" name="category" value="<?=$category_id?>">
	<input type="hidden" name="price_sort" value="0">
	<input type="radio" name="price_sort" value="low" <?=(($price_sort == 'low')?' checked':'')?>>Low to High<br>
	<input type="radio" name="price_sort" value="high" <?=(($price_sort == 'high')?' checked':'')?>>High to Low<br><br>
	<input type="text" name="min_price" class="price_range" placeholder="$ min" value="<?=$min_price;?>">To
	<input type="text" name="max_price" class="price_range" placeholder="$ max" value="<?=$max_price;?>"><br><br>

	<h4>By Brand</h4>
	<input type="radio" name="brand" value=""<?=(($b=='')?' checked':'');?>>All<br>
	<?php while ($brand = mysqli_fetch_assoc($brandQ)): ?>
		<input type="radio" name="brand" value="<?=$brand['id'];?>"<?=(($b == $brand['id'])?' checked':'')?>><?=ucfirst($brand['brand']);?><br>
	<?php endwhile;?>
	<br>
	<input type="submit" name="submit" value="Search" class="btn btn-sm btn-primary">
 
</form>
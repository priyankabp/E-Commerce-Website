<?php
	function sanitize($dirty){
		return htmlentities($dirty,ENT_QUOTES,"UTF-8");
	}

	function money($price){
		return '$'.number_format($price,2);
	}
?>
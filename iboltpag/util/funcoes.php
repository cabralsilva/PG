<?php
	function zerosEsquerda($number, $sizeField) {
		$number = str_pad ( $number, $sizeField, '0', STR_PAD_LEFT );
		return $number;
	}
?>
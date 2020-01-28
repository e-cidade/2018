<?php
# function a_accumulate_price
# $string_column é a coluna selecionada 
# $array_row é a tupla corrente do relatório
# $array_row é a tupla anterior do relatório
# $row_num é o número da linha atual do relatório 
# $col_num é o número da coluna atual do relatório 
# .3
function a_accumulate_price($string_column, $array_row, $array_last_row, $row_num, $col_num)
{
	if ($array_last_row[1] !== $array_row[1]) // mudou o valor da coluna 2
	{
		return $string_column;
	}
	else
	{
		return $string_column + $array_last_row[$col_num-1];
	}
}
?>

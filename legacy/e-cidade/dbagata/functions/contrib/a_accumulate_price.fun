<?php
# function a_accumulate_price
# $string_column � a coluna selecionada 
# $array_row � a tupla corrente do relat�rio
# $array_row � a tupla anterior do relat�rio
# $row_num � o n�mero da linha atual do relat�rio 
# $col_num � o n�mero da coluna atual do relat�rio 
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

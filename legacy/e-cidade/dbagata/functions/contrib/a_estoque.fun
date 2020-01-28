<?php
# function a_estoque
# $string_column é a coluna selecionada 
# $array_row é a tupla corrente do relatório
# $array_row é a tupla anterior do relatório
# $row_num é o número da linha atual do relatório 
# $col_num é o número da coluna atual do relatório 

function a_estoque($string_column, $array_row, $array_last_row, $row_num, $col_num)
{
	if ($array_row['Amount'] < 200)
	{
		return 'Comprar';
	}
	else
	{
		return 'Normal';
	}
}
?>

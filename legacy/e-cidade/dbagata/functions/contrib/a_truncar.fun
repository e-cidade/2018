<?
# function a_truncar
# $string_column � a coluna selecionada 
# $array_row � a tupla corrente do relat�rio
# $array_row � a tupla anterior do relat�rio
# $row_num � o n�mero da linha atual do relat�rio 
# $col_num � o n�mero da coluna atual do relat�rio 

function a_truncar($string_column, $array_row, $array_last_row, $row_num, $col_num)
{
	return substr($string_column, 0, 20);
}
?>
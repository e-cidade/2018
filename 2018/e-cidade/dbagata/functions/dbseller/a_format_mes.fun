<?
# function a_mes
# $string_column � a coluna selecionada 
# $array_row � a tupla corrente do relat�rio
# $array_row � a tupla anterior do relat�rio
# $row_num � o n�mero da linha atual do relat�rio 
# $col_num � o n�mero da coluna atual do relat�rio 

function a_format_mes($string_column, $array_row, $array_last_row, $row_num, $col_num)
{
    $mes = str_pad($string_column, 2, '0', STR_PAD_LEFT);
	return $mes;
}
?>

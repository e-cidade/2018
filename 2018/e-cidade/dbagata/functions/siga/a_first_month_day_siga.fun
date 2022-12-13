<?
# function a_first_month_day
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_first_month_day_siga($string_column, $array_row)
{
	return date('Y') . date('m') . '01';
}
?>

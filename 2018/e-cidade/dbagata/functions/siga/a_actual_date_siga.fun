<?
# function a_actual_date
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_actual_date_siga($string_column, $array_row)
{
	return date('Y') . date('m') . date('d');
}
?>

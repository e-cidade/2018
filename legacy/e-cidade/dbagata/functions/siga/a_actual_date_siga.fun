<?
# function a_actual_date
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_actual_date_siga($string_column, $array_row)
{
	return date('Y') . date('m') . date('d');
}
?>

<?
# function a_first_month_day
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_first_month_day_siga($string_column, $array_row)
{
	return date('Y') . date('m') . '01';
}
?>

<?
# function a_formata_data
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_formata_data($string_column, $array_row)
{
	$year = substr($string_column,0,4);
	$month= substr($string_column,5,2);
	$day  = substr($string_column,8,2);
	return "$day/$month/$year";
}
?>

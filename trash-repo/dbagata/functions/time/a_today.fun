<?
# function a_today
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_today($string_column, $array_row)
{
    $dia = str_pad(date('d'), 2, '0', STR_PAD_LEFT);
	return $dia;
}
?>

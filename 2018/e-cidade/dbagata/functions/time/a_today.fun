<?
# function a_today
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_today($string_column, $array_row)
{
    $dia = str_pad(date('d'), 2, '0', STR_PAD_LEFT);
	return $dia;
}
?>

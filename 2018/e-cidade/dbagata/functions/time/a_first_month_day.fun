<?
# function a_first_month_day
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_first_month_day($string_column, $array_row)
{
    $mes = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
    $ano = str_pad(date('Y'), 4, '0', STR_PAD_LEFT);
    
	return "01/{$mes}/{$ano}";
}
?>

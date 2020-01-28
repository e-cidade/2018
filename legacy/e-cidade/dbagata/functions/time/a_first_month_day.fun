<?
# function a_first_month_day
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_first_month_day($string_column, $array_row)
{
    $mes = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
    $ano = str_pad(date('Y'), 4, '0', STR_PAD_LEFT);
    
	return "01/{$mes}/{$ano}";
}
?>

<?
# function a_last_month_day
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_last_month_day($string_column, $array_row)
{
    $dia = str_pad(date('t',mktime(0,0,0,date('m'),1,date('Y'))), 2, '0', STR_PAD_LEFT);
    $mes = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
    $ano = str_pad(date('Y'), 4, '0', STR_PAD_LEFT);
    
    return "$dia/{$mes}/{$ano}";
}
?>

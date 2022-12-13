<?
# function a_last_month_first_day
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_last_month_first_day($string_column, $array_row)
{
    $dia = '01';
    $mes = str_pad(date('m', mktime(0, 0, 0, date("m")-1, date("d"),  date("Y"))), 2, '0', STR_PAD_LEFT);
    $ano = str_pad(date('Y', mktime(0, 0, 0, date("m")-1, date("d"),  date("Y"))), 4, '0', STR_PAD_LEFT);

    return "$dia/{$mes}/{$ano}";
}
?>

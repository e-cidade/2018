<?
# function a_last_month_last_day
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_last_month_last_day($string_column, $array_row)
{
    $dia = str_pad(date('t', mktime(0, 0, 0, date("m")-1, date("d"),  date("Y"))), 2, '0', STR_PAD_LEFT);
    $mes = str_pad(date('m', mktime(0, 0, 0, date("m")-1, date("d"),  date("Y"))), 2, '0', STR_PAD_LEFT);
    $ano = str_pad(date('Y', mktime(0, 0, 0, date("m")-1, date("d"),  date("Y"))), 4, '0', STR_PAD_LEFT);

    return "$dia/{$mes}/{$ano}";
}
?>

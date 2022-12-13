<?
# function a_weekday
# $string_column é a coluna selecionada 
# $array_row é a linha atual do relatório

function a_weekday($string_column, $array_row)
{
    return str_pad(date('w'), 2, '0', STR_PAD_LEFT);
}
?>

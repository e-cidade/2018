<?
# function a_weekday
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_weekday($string_column, $array_row)
{
    return str_pad(date('w'), 2, '0', STR_PAD_LEFT);
}
?>

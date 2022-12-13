<?
# function a_first_15_day
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_first_15_day($string_column, $array_row)
{
    $dia = str_pad(date('d'), 2, '0', STR_PAD_LEFT);
    $mes = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
    $ano = str_pad(date('Y'), 4, '0', STR_PAD_LEFT);
    $qui = $dia > 15 ? 16 : 1;
    $qui = str_pad($qui, 2, '0', STR_PAD_LEFT);
    return  "{$qui}/{$mes}/{$ano}";
}
?>

<?php
function a_vencto_producao($string_column, $array_row)
{
    $ano = substr($string_column,0,4);
    $mes = substr($string_column,5,2);
    $dia = substr($string_column,8,2);
    $string_column = mktime ( 24, 59, 59, $mes, $dia, $ano);
    
    $hoje = strtotime('+6 day' );
    
    if ( $string_column < $hoje )
    {
        return 1;
    }
    else
    {
        return 0;
    }
}
?>

<?
# function a_tira_acento
# Remove acentua��o das palavras
# By Maur�cio de Castro
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_tira_acento($string_column, $array_row)
{
    $string = $string_column;
    
    set_time_limit(240);
    
    $acentos = '1234567890�������������������������������Ǫ���';
    $letras  = '1234567890aeiouAEIOUaAAaEeoOuUiUoOnNaAoOcCaoaA';
    
    $new_string = '';
    
    for($x=0; $x<strlen($string); $x++)
    {
        $let = substr($string, $x, 1);
        
        for($y=0; $y<strlen($acentos); $y++)
        {
            if($let==substr($acentos, $y, 1))
            {
                $let=substr($letras, $y, 1);
                break;
            }
        }
        
        $new_string = $new_string . $let;
    }
    
    return $new_string;

}

?>


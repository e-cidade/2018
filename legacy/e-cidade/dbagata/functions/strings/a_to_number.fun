<?
# function a_to_number
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_to_number($string_column, $array_row)
{
    for ($n=0; $n<=strlen($string_column); $n++)
    {
        $char = substr($string_column,$n,1);
        if (is_numeric($char))
        {
            $return .= $char;
        }
    }
    return $return;
}
?>
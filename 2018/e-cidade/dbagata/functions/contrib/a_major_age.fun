<?
# function a_major_age
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_major_age($string_column, $array_row)
{
	if ($string_column >= 21)
	{
		return 1;
	}
	return 0;
}
?>
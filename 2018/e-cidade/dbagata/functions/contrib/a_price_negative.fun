<?
# function price_negative
# $string_column � a coluna selecionada 
# $array_row � a linha atual do relat�rio

function a_price_negative($string_column, $array_row)
{
	if ($string_column <0)
	{
		return '1';
	}
	else
	{
		return '0';
	}
}
?>
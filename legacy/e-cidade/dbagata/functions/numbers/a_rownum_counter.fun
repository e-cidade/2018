<?
# function rownum_counter
# $string_column is the actual column
# $array_row � the current tuple of the report
# $row_num � the current line number

function a_rownum_counter($string_column, $array_row, $array_last_row, $row_num, $col_num)
{
	return sprintf('%03.0f', $row_num);
}
?>

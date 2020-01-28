<?php
/**
 * a_date_to_br
 * @param $string_column � o valor atual da coluna 
 * @param $array_row � um vetor contendo a linha atual
 * @param $array_last_row � um vetor contendo a linha anterior
 * @param $row_num � o n�mero da linha atual 
 * @param $col_num � o n�mero da coluna atual
 * @param $alias � o alias da coluna
 * @param $format formato do relat�rio (html, pdf, rtf)
 * @param $parameters par�metros do relat�rio
 * @param $report_object relat�rio na forma de objeto PHP
 * @param $field_array propriedades do campo na forma de um vetor
 **/
function a_date_to_br($string_column, $array_row, $array_last_row, $row_num, $col_num, $alias=null, $format=null, $parameters=null, $report_object=null, $field_array=null)
{
    return substr($string_column, 8,2) . '/' .
           substr($string_column, 5,2) . '/' .
           substr($string_column, 0,4);
}
?>

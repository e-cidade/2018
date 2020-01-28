<?php
/**
 * dbIntervaloDiasUteis
 * Utiliza a fun��o getIntervaloDiasUteis da classe db_stdClass para calcular os dias uteis com base na tabela "calend"
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
function dbIntervaloDiasUteis($string_column, $array_row, $array_last_row, $row_num, $col_num, $alias, $format, $parameters, $report_object, $field_array)
{
    $dtAtual = strtotime(date("Y-m-d"));
    return date("d/m/Y", db_stdClass::getIntervaloDiasUteis($dtAtual, $string_column));
}
?>
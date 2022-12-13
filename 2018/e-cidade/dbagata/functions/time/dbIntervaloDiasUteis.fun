<?php
/**
 * dbIntervaloDiasUteis
 * Utiliza a funзгo getIntervaloDiasUteis da classe db_stdClass para calcular os dias uteis com base na tabela "calend"
 * @param $string_column й o valor atual da coluna 
 * @param $array_row й um vetor contendo a linha atual
 * @param $array_last_row й um vetor contendo a linha anterior
 * @param $row_num й o nъmero da linha atual 
 * @param $col_num й o nъmero da coluna atual
 * @param $alias й o alias da coluna
 * @param $format formato do relatуrio (html, pdf, rtf)
 * @param $parameters parвmetros do relatуrio
 * @param $report_object relatуrio na forma de objeto PHP
 * @param $field_array propriedades do campo na forma de um vetor
 **/
function dbIntervaloDiasUteis($string_column, $array_row, $array_last_row, $row_num, $col_num, $alias, $format, $parameters, $report_object, $field_array)
{
    $dtAtual = strtotime(date("Y-m-d"));
    return date("d/m/Y", db_stdClass::getIntervaloDiasUteis($dtAtual, $string_column));
}
?>
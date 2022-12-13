<?php
/**
 * a_toggle_data - Converte uma data do banco para formto portugues
 * @param $string_column   - é o valor atual da coluna 
 * @param $array_row       - é um vetor contendo a linha atual
 * @param $array_last_row  - é um vetor contendo a linha anterior
 * @param $row_num         - é o número da linha atual 
 * @param $col_num         - é o número da coluna atual
 * @param $alias           - é o alias da coluna
 * @param $format          - formato do relatório (html, pdf, rtf)
 * @param $parameters      - parémetros do relatório
 * @param $report_object   - relatório na forma de objeto PHP
 * @param $field_array     - propriedades do campo na forma de um vetor
 **/
function a_toggle_data($string_column, $array_row, $array_last_row, $row_num, $col_num, $alias=null, $format=null, $parameters=null, $report_object=null, $field_array=null) 
{
    return substr($string_column, 8,2) . '-' .
           substr($string_column, 5,2) . '-' .
           substr($string_column, 0,4);
}
?>
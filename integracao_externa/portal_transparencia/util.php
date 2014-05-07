<?php

/**
 * Funчуo que realiza uma consulta no banco de dados
 */
function consultaBD($origem, $sql){
    $resultadoConsulta = db_query($origem, $sql);
    if(!$resultadoConsulta) throw new Exception('A consulta nуo foi realizada corretamente.');
    return $resultadoConsulta;
}

?>
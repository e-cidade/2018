<?php
require_once('sql_queries.php');

/**
 * Função que realiza uma consulta no banco de dados
 */
function consultaBD($origem, $sql=null){
    if(is_null($sql))
        $resultadoConsulta = db_query($origem);
    else
        $resultadoConsulta = db_query($origem, $sql);
    if(!$resultadoConsulta) throw new Exception('A consulta não foi realizada corretamente.');
    return $resultadoConsulta;
}

/**
*  O script abaixo corrige possíveis erros de base na tabela conplano, sendo elas podendo ser originárias
*  da tabela orcdotacao ou orcreceita
*/
function corrigeConplano($connOrigem, $tabelaOrigem, $iExercicio){
    
    $sSqlCorrigeConplano = consultaCorrecaoConplano($tabelaOrigem);
    $rsCorrigeConplano      = consultaBD($connOrigem,$sSqlCorrigeConplano);
    $iLinhasCorrigeConplano = pg_num_rows($rsCorrigeConplano);
    
    for ( $iInd=0; $iInd < $iLinhasCorrigeConplano; $iInd++ ) {
        $oConplano = db_utils::fieldsMemory($rsCorrigeConplano,$iInd);
        $sSqlOrc = consultaAuxiliarConplano($tabelaOrigem, $oConplano);
        $rsOrc      = consultaBD($connOrigem,$sSqlOrc);
        $iLinhasOrc = pg_num_rows($rsOrc);
    /**
     *  Caso exista registros na orcfontes ou na orcelemento, então será inserido um registro na conplano com base nesse registro
     *  caso contrário será procurado na conplano algum registro da mesma conta em outro exercício.
     */
    if ( $iLinhasOrc > 0 ) {
        $oOrc = db_utils::fieldsMemory($rsOrc,0);
        $sTabelaPlano = "conplano";
        if (USE_PCASP && $iExercicio >= ANO_IMPLANTACAO_PCASP) {
            $sTabelaPlano = "conplanoorcamento";
        }
        $sSqlInsereConplano = insereConplanoPorFontesOuElemento($tabelaOrigem, $sTabelaPlano, $oOrc, $oConplano);
    } else {

        $sSqlConplano = consultaConplano($tabelaOrigem, $oConplano);
        $rsConplano      = consultaBD($connOrigem,$sSqlConplano);
        $iLinhasConplano = pg_num_rows($rsConplano);

        if ($iLinhasConplano > 0 ) {
            $sTabelaPlano = "conplano";
            if (USE_PCASP && $iExercicio >= ANO_IMPLANTACAO_PCASP) {
               $sTabelaPlano = "conplanoorcamento";
            }    
        $oConplanoOrigem    = db_utils::fieldsMemory($rsConplano,0);
        $sSqlInsereConplano = insereConplanoPorChaveConplano($tabelaOrigem, $sTabelaPlano, $oConplano, $oConplanoOrigem);
        
      } else {
        throw new Exception("ERRO-0: 1 - Erro na correção da tabela conplano ");
      }
    }
  }
    
}
?>

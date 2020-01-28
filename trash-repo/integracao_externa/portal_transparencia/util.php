<?php
require_once('sql_queries.php');
define('INSERE', 1);
define('PERSISTE', 2);

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
 *  A função corrigePlano corrige possíveis erros de base na tabela conplano, sendo elas podendo ser originárias
 *  da tabela orcdotacao ou orcreceita
 *  @param $connOrigem - conexão com o BD
 *  @param $tabelaOrigem - tabela de origem (orcdotação ou orcreceita)
 */
function corrigeConplano($connOrigem, $tabelaOrigem, $iExercicio, $ano_implantacao_pcasp){

    if($tabelaOrigem != "orcdotacao" && $tabelaOrigem != "orcreceita") throw new Exception('A tabela de origem deve ser orcdotacao ou orcreceita');

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
            if (USE_PCASP && $iExercicio >= $ano_implantacao_pcasp) {
                $sTabelaPlano = "conplanoorcamento";
            }
            $sSqlInsereConplano = insereConplanoPorFontesOuElemento($tabelaOrigem, $sTabelaPlano, $oOrc, $oConplano);
        } else {
            $sSqlConplano = consultaConplano($tabelaOrigem, $oConplano);
            $rsConplano      = consultaBD($connOrigem,$sSqlConplano);
            $iLinhasConplano = pg_num_rows($rsConplano);

            if ($iLinhasConplano > 0 ) {
                $sTabelaPlano = "conplano";
                if (USE_PCASP && $iExercicio >= $ano_implantacao_pcasp) {
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

/**
 * Configura a tabela de importação
 * @param $sArquivoLog 
 * @param $iParamLog 
 * @param $dtDataHoje 
 * @param $sHoraHoje 
 */
function configuraTabelaImportacao($sArquivoLog, $iParamLog, $dtDataHoje, $sHoraHoje, $connDestino){
    db_logTitulo(" CONFIGURA TABELA DE IMPORTAÇÃO",$sArquivoLog,$iParamLog);
    $sSqlInsereImportacoes = " INSERT INTO importacoes (data,hora)
        VALUES ('{$dtDataHoje}',
                '$sHoraHoje') ";
    $rsInsereImportacoes   = consultaBD($connDestino,$sSqlInsereImportacoes);
    if ( !$rsInsereImportacoes ) throw new Exception("ERRO-0: Erro ao inserir tabela de importações!");
}

/**
* Função para inserir registros em uma tableDataManager.
* @param $oTB tableDataManager a ser manipulada.
* @param $flag Flag para sinalizar que operações devem ser realizadas. Pode ser usada INSERE, PERSISTE ou INSERE | PERSISTE. (default: INSERE)
*/
function insereRegistros($oTB, $flag = INSERE) {
    $metodos = ($flag == INSERE ? array("insertValue") :
                               ($flag == PERSISTE ? array("persist") : array("insertValue", "persist")));
    try {
        foreach ($metodos as &$metodo) {
            $oTB->$metodo();
        }
    } catch (Exception $eException) {
        throw new Exception("ERRO-0: {$eException->getMessage()}");
    }
}


?>

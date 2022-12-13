<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oJson    = new Services_JSON();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();

$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch ($oParam->exec) {
    
    case "getPeriodosReativados" : 
      
      $aPeriodosReativados = array();
      $iAcordo             = $oParam->iAcordo;
      $oAcordo             = AcordoRepository::getByCodigo($iAcordo);
      $aParalisacao        = $oAcordo->getParalisacoes();
      
      foreach ($aParalisacao as $oParalisacao) {
        
        foreach ($oParalisacao->getPeriodos() as $oPeriodoReativado) {
          
          if ($oPeriodoReativado->iTipo == 2) {
            
            $oDaoReativados = new cl_acordoposicao();
            
            $sCampos  = " ac36_sequencial, ac36_numero, ac36_descricao, ac36_datainicial, ac36_datafinal, ";
            $sCampos .= " (select coalesce(sum(ac29_quantidade), 0) as execucao ";
            $sCampos .= "         from acordoitemexecutado ";
            $sCampos .= "         inner join acordoitemexecutadoperiodo on ac38_acordoitemexecutado = ac29_sequencial" ;
            $sCampos .= "         inner join acordoitemprevisao         on ac37_sequencial          = ac38_acordoitemprevisao";
            $sCampos .= "   where ac37_acordoperiodo = ac36_sequencial) as execucao ";
            
            $sWhere  = "     ac36_sequencial = {$oPeriodoReativado->iCodigoPeriodo} ";            
            $sSqlReativados = $oDaoReativados->sql_queryReativados(null, $sCampos, null, $sWhere);
            $rsReativados   = $oDaoReativados->sql_record($sSqlReativados);
            if ($oDaoReativados->numrows > 0) {
              
              $oDados = db_utils::fieldsMemory($rsReativados, 0);
              
              if ($oDados->execucao > 0) {
                throw new Exception("O acordo possui períodos executados.");
              }
              $oDadosPeriodo = new stdClass();
              $oDadosPeriodo->iCodigo    = $oDados->ac36_sequencial;
              $oDadosPeriodo->iNumero    = $oDados->ac36_numero;
              $oDadosPeriodo->sDescricao = urlencode($oDados->ac36_descricao);
              $oDadosPeriodo->dtInicial  = urlEncode(db_formatar($oDados->ac36_datainicial, 'd'));
              $oDadosPeriodo->dtTermino  = urlEncode(db_formatar($oDados->ac36_datafinal, 'd'));
              $aPeriodosReativados[] = $oDadosPeriodo;
            }
          }
        }
      }
      $oRetorno->aPeriodos = $aPeriodosReativados;
      
    break;  
    
    case "getDadosParalisacao" :
      
      $oAcordo      = AcordoRepository::getByCodigo($oParam->iAcordo);
      $oParalisacao = $oAcordo->getUltimaParalisacao();

      $oDados = new StdClass();
      $oDados->dtInicial   = '';
      $oDados->dtTermino   = '';
      $oDados->sObservacao = '';

      if ($oParalisacao->getDataInicio() instanceof DBDate) {
        $oDados->dtInicial = $oParalisacao->getDataInicio()->getDate("d/m/Y");
      }
      if ($oParalisacao->getDataTermino() instanceof DBDate) {
        $oDados->dtTermino = $oParalisacao->getDataTermino()->getDate("d/m/Y"); 
      }

      $oDados->sObservacao = urlEncode($oParalisacao->getUltimaMovimentacao()->getObservacao());
      $oRetorno->oDados = $oDados; 
      
    break; 

    case 'alterarParalisacao' :
      
      db_inicio_transacao();
      $sObservacao  = addslashes(db_stdClass::normalizeStringJson($oParam->sObservacao));
      $oData        = new DBDate($oParam->dtInicial);
      $oAcordo      = AcordoRepository::getByCodigo($oParam->iAcordo);
      
      $oUltimaParalisacao = $oAcordo->getUltimaParalisacao();
      $oUltimaParalisacao->setDataInicio($oData);
      $oUltimaParalisacao->setObservacao($sObservacao);
      $oUltimaParalisacao->salvar();

      $oRetorno->message = urlencode("Paralisação do acordo alterada com sucesso.");
      db_fim_transacao(false);

    break;  

    case "salvarParalisacao" :
      
      db_inicio_transacao();
      
      $sObservacao  = addslashes(db_stdClass::normalizeStringJson($oParam->sObservacao));
      $oData        = new DBDate($oParam->dtInicial);
      $oAcordo      = AcordoRepository::getByCodigo($oParam->iAcordo);
      $oAcordo->paralisar($oData, $sObservacao);
      
      db_fim_transacao(false);
      
      $oRetorno->message = urlencode("Acordo paralisado com sucesso.");
      
    break;  

    case "excluirParalisacao" :
      
      db_inicio_transacao();
      
      $oAcordo            = AcordoRepository::getByCodigo($oParam->iAcordo);
      $sObservacao        = addslashes(db_stdClass::normalizeStringJson($oParam->sObservacao));
      $oUltimaParalisacao = $oAcordo->getUltimaParalisacao();
      $oUltimaParalisacao->setObservacao($sObservacao);
      $oUltimaParalisacao->remover();
      $oRetorno->message = urlencode("Paralisação removida com sucesso.");
      
      db_fim_transacao( false );
      
    break;  

    /**
     * Reativar acordo
     *
     * @param iAcordo
     * @param dtRetorno
     * @param sObservacao
     */
    case "reativarAcordo" :

      db_inicio_transacao();

      $sObservacao = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);
      $oAcordo = AcordoRepository::getByCodigo($oParam->iAcordo);
      $oAcordo->reativar($oParam->aPeriodos, new DBDate($oParam->dtRetorno), $sObservacao);
      $oRetorno->message = urlEncode("Acordo reativado com sucesso.");

      db_fim_transacao(false);

    break; 

    /**
     * Cancelar reativacao do acordo
     *
     * @param iAcordo
     * @param dtRetorno
     * @param sObservacao
     */
    case "cancelarReativacao" :

      db_inicio_transacao();
      
      $iAcordo     = $oParam->iAcordo;
      $sObservacao = db_stdClass::normalizeStringJsonEscapeString($oParam->sObservacao);
      $aPeriodos   = $oParam->aPeriodos;
      $oAcordo     = AcordoRepository::getByCodigo($iAcordo);
      $oAcordo->cancelarReativacao($aPeriodos, $sObservacao);
      
      db_fim_transacao(false);
      
      $oRetorno->message = urlEncode("Reativação de acordo cancelada com sucesso.");


    break; 

    /**
     * Buscar periodos
     *
     * @param iAcordo
     * @param dtInicial
     * @param dtRetorno
     */
    case "buscarPeriodos" :

      $oDataInicioParalisacao  = new DBDate($oParam->dtInicial);
      $oDataTerminoParalisacao = new DBDate($oParam->dtTermino);
      $aPeriodos    = array();

      /**
       * Posicoes do acordo
       */
      foreach (AcordoRepository::getByCodigo($oParam->iAcordo)->getPosicoes() as $oPosicao) {

        /**
         * Periodo das posicoes
         */
        foreach ($oPosicao->getPosicaoPeriodo() as $oPeriodo) {
        
          $oDataInicial = new DBDate($oPeriodo->dtIni);
          $oDataFinal   = new DBDate($oPeriodo->dtFin);

          $lIntervaloInicial = DBDate::dataEstaNoIntervalo($oDataInicial, $oDataInicioParalisacao, $oDataTerminoParalisacao); 
          $lIntervaloFinal = DBDate::dataEstaNoIntervalo($oDataFinal, $oDataInicioParalisacao, $oDataTerminoParalisacao); 

          /**
           * Data do periodo não esta no intervalo da paralisacao
           */
          if (!$lIntervaloInicial && !$lIntervaloFinal) {
            continue;
          }

          $oDadosPeriodo = new StdClass();
          $oDadosPeriodo->iCodigo    = $oPeriodo->codigo;
          $oDadosPeriodo->iNumero    = $oPeriodo->periodo;
          $oDadosPeriodo->sDescricao = urlEncode($oPeriodo->descrPer);
          $oDadosPeriodo->dtInicial  = urlEncode($oDataInicial->getDate(DBDate::DATA_PTBR));
          $oDadosPeriodo->dtTermino  = urlEncode($oDataFinal->getDate(DBDate::DATA_PTBR));

          $aPeriodos[] = $oDadosPeriodo;
        }

      }

      if (empty($aPeriodos)) {

        throw new Exception("Nenhum período encontrado.");
        break;
      }

      $oRetorno->aPeriodos = $aPeriodos;

    break; 
    
    case "buscaDocumentoTemplate":
      
      $aOrigem[1] = 33; //'Processo de Compras'
      $aOrigem[2] = 34; //'Licitação'
      $aOrigem[3] = 35; //'Manual'
      $aOrigem[6] = 36; //'Empenho
      
      if (!isset($aOrigem[$oParam->iOrigem]) || empty($aOrigem[$oParam->iOrigem])) {
        throw BusinessException("Origem desconhecida");
      }
      
      $sWhere        = " db82_templatetipo = {$aOrigem[$oParam->iOrigem]}";
      $oDaoDocumento = new cl_db_documentotemplate();
      $sSqlDocumento = $oDaoDocumento->sql_query_file(null, "db82_sequencial, db82_descricao", "db82_descricao", $sWhere);
      $rsDocumento   = $oDaoDocumento->sql_record($sSqlDocumento);
      $iLinhas       = $oDaoDocumento->numrows;
      
      if ($iLinhas == 0) {
        throw new BusinessException("Nenhum documento cadastrado.");
      }
      
      $aDocumentoRetorno = array();
      for ($i = 0; $i < $iLinhas; $i++) {
        
        $oDadoDocumento = db_utils::fieldsMemory($rsDocumento, $i);
        $oDocumento     = new stdClass();
        
        $oDocumento->iCodigo        = $oDadoDocumento->db82_sequencial;
        $oDocumento->sDescricao     = urlencode($oDadoDocumento->db82_descricao);
        $aDocumentoRetorno[]        = $oDocumento;
      }
      $oRetorno->iTipoDocumento    = $aOrigem[$oParam->iOrigem];
      $oRetorno->aDocumentoRetorno = $aDocumentoRetorno;
      
      break;
  }
  
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->message = urlencode($oErro->getMessage());
  $oRetorno->status = 2;
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->message = urlencode($oErro->getMessage());
  $oRetorno->status = 2;
} catch (FileException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->message = urlencode($oErro->getMessage());
  $oRetorno->status = 2;
}

echo $oJson->encode($oRetorno);

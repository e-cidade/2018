<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("model/Acordo.model.php");
require_once("model/AcordoHomologacao.model.php");
require_once("model/AcordoAssinatura.model.php");
require_once("model/AcordoAnulacao.model.php");
require_once('model/AcordoComissao.model.php');
require_once('model/AcordoItem.model.php');
require_once('model/AcordoComissaoMembro.model.php');
require_once("model/AcordoPenalidade.model.php");
require_once("model/AcordoGarantia.model.php");
require_once("model/CgmFactory.model.php");
require_once('model/CgmBase.model.php');
require_once('model/CgmFisico.model.php');
require_once('model/CgmJuridico.model.php');
require_once('model/Dotacao.model.php');
require_once("model/MaterialCompras.model.php");
require_once("model/empenho/AutorizacaoEmpenho.model.php");
require_once("model/ItemAutorizacao.model.php");
require_once("model/AcordoPosicao.model.php");
require_once("model/licitacao.model.php");
require_once("model/ProcessoCompras.model.php");
require_once("model/contrato/AcordoItemTipoCalculoFactory.model.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBTime.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
$oJson    = new services_json();
$oRetorno = new stdClass();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $_POST["json"])));

$oRetorno->status   = 1;
$oRetorno->message  = '';
$oRetorno->itens    = array();
if (isset($oParam->observacao)) {
	$sObservacao = utf8_decode($oParam->observacao);
}

switch($oParam->exec) {
    
  /*
   * Pesquisa as posicoes do acordo
   */
  case "getItensAditar":
      
     if (isset ($_SESSION["oContrato"])) {
       unset($_SESSION["oContrato"]);
     }

     $oContrato  = new Acordo($oParam->iAcordo);
     
     $_SESSION["oContrato"] = $oContrato;

     $oPosicao                    = $oContrato->getUltimaPosicao();
     $oRetorno->tipocontrato      = $oContrato->getOrigem();
     $oRetorno->datainicial       = $oContrato->getDataInicial();
     $oRetorno->datafinal         = $oContrato->getDataFinal();
     $oRetorno->valores           = $oContrato->getValorContrato();
     $oRetorno->itens             = $oPosicao->getItensAditar($oParam->renovacao);
     
  break;
    
  case "getPosicaoItens":

    if (isset ($_SESSION["oContrato"])) {
      
      $oContrato = $_SESSION["oContrato"];
      $aItens    = array();       

      foreach ($oContrato->getPosicoes() as $oPosicaoContrato) {
        
        if ($oPosicaoContrato->getCodigo() == $oParam->iPosicao) {

          foreach ($oPosicaoContrato->getItens() as $oItem) {
              
              $oItemRetorno                 = new stdClass();
              $oItemRetorno->codigo         = $oItem->getCodigo(); 
              $oItemRetorno->material       = $oItem->getMaterial()->getDescricao(); 
              $oItemRetorno->codigomaterial = urlencode($oItem->getMaterial()->getMaterial()); 
              $oItemRetorno->elemento       = $oItem->getElemento(); 
              $oItemRetorno->valorunitario  = $oItem->getValorUnitario(); 
              $oItemRetorno->valortotal     = $oItem->getValorTotal(); 
              $oItemRetorno->quantidade     = $oItem->getQuantidade(); 
              
              foreach ($oItem->getDotacoes() as $oDotacao) {
                
                $oDotacaoSaldo = new Dotacao($oDotacao->dotacao, $oDotacao->ano);
                $oDotacao->saldoexecutado = 0;;
                $oDotacao->valorexecutar  = 0;
                $oDotacao->saldodotacao   = $oDotacaoSaldo->getSaldoFinal();
              }

              $oItemRetorno->dotacoes       = $oItem->getDotacoes();
              $oItemRetorno->saldos         = $oItem->getSaldos();
              $oItemRetorno->servico        = $oItem->getMaterial()->isServico();
              $oRetorno->itens[]            = $oItemRetorno;
          }
          break;
        }
      }
    } else {
      
      $oRetorno->status   = 2;
      $oRetorno->message  = urlencode('Inconsistencia na consulta pesquise novamente os dados do acordo');
    }

  break;
    
  case "processarAditamento":
    
    $oContrato = $_SESSION["oContrato"];
    try {
      
      db_inicio_transacao();

      $oContrato->aditar($oParam->aItens, $oParam->tipoaditamento, $oParam->datainicial, $oParam->datafinal, $oParam->sNumeroAditamento );

      db_fim_transacao(false);
      
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

  break;
    
  case "getUnidades":
    
    $oDaoMatUnid  = db_utils::getDao("matunid"); 
    $sSqlUnidades = $oDaoMatUnid->sql_query_file(null, 
                                                 "m61_codmatunid,substr(m61_descr,1,20) as m61_descr",
                                                 "m61_descr"
                                                );
    $rsUnidades      = $oDaoMatUnid->sql_record($sSqlUnidades);
    $iNumRowsUnidade = $oDaoMatUnid->numrows;
    for ($i = 0; $i < $iNumRowsUnidade; $i++) {
              
      $oUnidade = db_utils::fieldsMemory($rsUnidades, $i);
      $aUnidades[] = $oUnidade;
    }
    $oRetorno->itens = $aUnidades;

  break;

  /**
   * case para validar periodo executado  
   * - percorre os periodos de um item e valida se nao existe execucao nas datas previstas
   */
  case 'validarPeriodosExecutados' :

    try {

      $oContrato = $_SESSION["oContrato"];

      foreach ( $oParam->aPeriodos as $oPeriodo ) {

        $oDataInicial =  new DBDate($oPeriodo->dtDataInicial); 
        $oDataFinal   =  new DBDate($oPeriodo->dtDataFinal);

        $lTemExecucaoPeriodo = $oContrato->verificaSeTemExecucaoPeriodo($oPeriodo->ac41_sequencial, $oDataInicial, $oDataFinal);

        if ( !$lTemExecucaoPeriodo ) {

          $oDadosMensagem = new stdClass();
          $oDadosMensagem->sDataInicial = $oDataInicial->getDate(DBDate::DATA_PTBR);
          $oDadosMensagem->sDataFinal   = $oDataFinal->getDate(DBDate::DATA_PTBR);
          throw new Exception (_M("patrimonial.contratos.Acordo.periodo_com_execucao", $oDadosMensagem));
        }
      }


    }catch (Exception $eErro) {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

  break;

}

echo $oJson->encode($oRetorno);   
?>
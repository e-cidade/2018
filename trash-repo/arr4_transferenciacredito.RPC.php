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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_libpessoal.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");

require_once ("model/arrecadacao/Credito.model.php");
require_once ("model/arrecadacao/CreditoManual.model.php");
require_once ("model/arrecadacao/CreditoTransferencia.model.php");
require_once ("model/arrecadacao/RegraCompensacao.model.php");
require_once ("model/CgmFactory.model.php");
require_once ("std/DBDate.php");
require_once ("std/db_stdClass.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("libs/exceptions/FileException.php");
require_once ("model/recibo.model.php");
require_once ("model/processoProtocolo.model.php");

$oJson             = new services_json();
$oParametros       = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new db_stdClass();

$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {
  
  switch ($oParametros->sExec) {

    case "listaCreditos":
      
      $aCreditos      = array();
      
      $oDaoAbatimento = db_utils::getDao('abatimento');
      
      $sCampos        = "abatimento.k125_sequencial,                                            ";
      $sCampos       .= "abatimentorecibo.k127_numprerecibo,                                    ";
      $sCampos       .= "case                                                                   ";
      $sCampos       .= "  when k00_matric is not null  then 'M-'||k00_matric::varchar          ";
      $sCampos       .= "  when k00_inscr  is not null  then 'I-'||k00_inscr::varchar           ";
      $sCampos       .= "  else 'C-'||k00_numcgm::varchar                                       ";
      $sCampos       .= "end  as k00_origem,                                                    ";
      $sCampos       .= "abatimento.k125_valor,                                                 ";
      $sCampos       .= "abatimento.k125_valordisponivel                                        ";
      
      $sWhere         = "    arrenumcgm.k00_numcgm           = {$oParametros->iCodigoCgmOrigem} ";
      $sWhere        .= "and abatimento.k125_valordisponivel > 0                                ";
      $sOrderBy       = "abatimento.k125_sequencial                                             ";
      
      $sSqlCreditos   = $oDaoAbatimento->sql_queryListaCreditosTransferencia($sCampos, $sWhere, $sOrderBy);
      
      $rsCreditos     = $oDaoAbatimento->sql_record($sSqlCreditos);
      
      if ($oDaoAbatimento->numrows == 0) {
        
         $oRetorno->iStatus   = 2;   
         $oRetorno->sMensagem = "Nenhum registro de crédito encontrado para o CGM {$oParametros->iCodigoCgmOrigem}"; 

      } else {
        
        for ($iIndice = 0; $iIndice < $oDaoAbatimento->numrows; $iIndice++) {
          
          $oAbatimento = db_utils::fieldsMemory($rsCreditos, $iIndice, true, false, true);
          
          $oCredito    = new stdClass();
          
          $oCredito->iCodigoCredito   = $oAbatimento->k125_sequencial;
          $oCredito->iNumpre          = $oAbatimento->k127_numprerecibo;
          $oCredito->sOrigem          = $oAbatimento->k00_origem;
          $oCredito->nValor           = $oAbatimento->k125_valor;
          $oCredito->nValorDisponivel = $oAbatimento->k125_valordisponivel;
          
          $aCreditos[]                = $oCredito;
          
        }
        
        $oRetorno->aCreditos = $aCreditos;
        
      }
      
      break;
      
    case 'processarTransferencia';
      
      db_inicio_transacao();
    
      if(count($oParametros->aSelecionados) == 0) {
        
        throw new Exception('Nenhum crédito selecionado para transferência.');
        
      }
            
      foreach ($oParametros->aSelecionados as $oCreditosSelecionados) {
        
        $oCreditoTransferencia = new CreditoTransferencia();
        
        try {
          
          $oCreditoTransferencia->setCgmDestino     (CgmFactory::getInstanceByCgm($oParametros->iCodigoCgmDestino));
          
        } catch (Exception $oErro) {
          
          throw new Exception('CGM de destino não informado para a transferência dos créditos.');
          
        }
        
        $oCreditoTransferencia->setCredito          (new CreditoManual($oCreditosSelecionados->iCodigoCredito));
        $oCreditoTransferencia->setObservacao       (db_stdClass::normalizeStringJson($oParametros->sObservacao));
        $oCreditoTransferencia->setDataTransferencia(new DBDate(date('Y-m-d', db_getsession('DB_datausu'))));
        $oCreditoTransferencia->setUsuario          (db_getsession('DB_id_usuario'));
        $oCreditoTransferencia->setHoraTransferencia(date('H:i'));
        $oCreditoTransferencia->setInstituicao      (db_getsession('DB_instit'));
        
        if (!empty($oParametros->lProcessoSistema)) {

          $oCreditoTransferencia->setProcessoSistema($oParametros->lProcessoSistema == 'S' ? true : false);

          if ($oCreditoTransferencia->isProcessoSistema()) {

            $oCreditoTransferencia->setProcessoProtocolo(new processoProtocolo($oParametros->iCodigoProcessoSistema));
             
          } else {

            $oCreditoTransferencia->setNumeroProcessoExterno($oParametros->sNumeroProcessoExterno);
            $oCreditoTransferencia->setNomeTitularProcessoExterno(db_stdClass::normalizeStringJson($oParametros->sNomeTitularProcessoExterno));
             
            if (!empty($oParametros->dDataProcessoExterno)) {
              $oCreditoTransferencia->setDataProcessoExterno(new DBDate($oParametros->dDataProcessoExterno));
            }
             
          }

        }
        
        $oCreditoTransferencia->setValor($oCreditosSelecionados->nValorTransferido);
        $oCreditoTransferencia->salvar();
      
      }
    
      db_fim_transacao();
      
      break;
  }
    
} catch (Exception $eErro) {
  
  $oRetorno->iStatus   = 2;
  
  $oRetorno->sMessage  = urlEncode($eErro->getMessage());
  
  db_fim_transacao(true);
  
}

$oRetorno->sMensagem = urlEncode($oRetorno->sMensagem);

echo $oJson->encode($oRetorno);
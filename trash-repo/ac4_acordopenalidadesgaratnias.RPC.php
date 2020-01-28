<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once('classes/db_acordocomissao_classe.php');
require_once('classes/db_acordocomissaomembro_classe.php');
require_once("classes/db_acordotipo_classe.php");
require_once("classes/db_acordopenalidade_classe.php");
require_once("classes/db_acordogarantia_classe.php");
require_once('model/AcordoComissao.model.php');
require_once('model/Acordo.model.php');
require_once('model/AcordoItem.model.php');
require_once('model/AcordoComissaoMembro.model.php');
require_once("model/AcordoPenalidade.model.php");
require_once("model/AcordoGarantia.model.php");
require_once("model/CgmFactory.model.php");
require_once('model/CgmBase.model.php');
require_once('model/CgmFisico.model.php');
require_once('model/CgmJuridico.model.php');
require_once("model/MaterialCompras.model.php");
require_once("model/AcordoPosicao.model.php");
require_once("model/licitacao.model.php");
require_once("model/ProcessoCompras.model.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
$oJson    = new services_json();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$oRetorno->itens   = array();
switch($oParam->exec) {

  case 'getDadosPenalidadeGarantia' :
      
    try {
      
      if ($oParam->iTipo == 1) {   
        if (!empty($oParam->iCodigo)) {
          
          $oPenalidade         = new AcordoPenalidade($oParam->iCodigo);
          $oRetorno->descricao = urlencode($oPenalidade->getDescricao());
          $oRetorno->texto     = urlencode($oPenalidade->getTextoPadrao());
        }
      } else if ($oParam->iTipo == 2) {   
        
        if (!empty($oParam->iCodigo)) {
          
          $oGarantia           = new AcordoGarantia($oParam->iCodigo);
          $oRetorno->descricao = urlencode($oGarantia->getDescricao());
          $oRetorno->texto     = urlencode($oGarantia->getTextoPadrao());
        }
      }
    }
    catch (Exception $eErro) {
           
       $oRetorno->status = 2;
       $oRetorno->messag = urlencode($eErro->getMessage()); 
     }
    break;
    
  Case "getPenalidades" :

    if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
      
      //oContrato    = new Acordo();
      $oContrato    = $_SESSION["oContrato"];
      $aPenalidades = $oContrato->getPenalidades();
      $oRetorno->isUpdate = false;
      foreach ($aPenalidades as $oPenalidade) {
        
        if ($oParam->iPenalidade != '') {
          
          if ($oPenalidade->getCodigo() == $oParam->iPenalidade) {
            
            $oRetorno->isUpdate = true;
            $oPenal  = new stdClass();
            $oPenal->codigo    = $oPenalidade->getCodigo();
            $oPenal->descricao = urlencode($oPenalidade->getDescricao());
            $oPenal->texto     = urlencode($oPenalidade->getTextoPadrao());
            $oRetorno->itens[] = $oPenal;
          }
        } else {
          
        
          $oPenal  = new stdClass();
          $oPenal->codigo    = $oPenalidade->getCodigo();
          $oPenal->descricao = urlencode($oPenalidade->getDescricao());
          $oPenal->texto     = urlencode(str_replace("\n","<br>",$oPenalidade->getTextoPadrao()));
          $oRetorno->itens[] = $oPenal;
        
        }
      }
    }
    
    break;
    
  case "salvarPenalidade":

    if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
      
      try {
        
        db_inicio_transacao();
        $oContrato    = $_SESSION["oContrato"];
        $oPenalidade  = new AcordoPenalidade($oParam->iPenalidade);
        $oContrato->adicionarPenalidades($oPenalidade, utf8_decode($oParam->sTexto))
                  ->save();
        db_fim_transacao(false);                  
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
    }
    break;
    
    case "excluirPenalidade":

      if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
        
        try {
          
          db_inicio_transacao();
          $oContrato    = $_SESSION["oContrato"];
          $oContrato->removerPenalidade($oParam->iPenalidade)
                    ->save();
          db_fim_transacao(false);                  
        } catch (Exception $eErro) {
          
          db_fim_transacao(true);
          $oRetorno->status = 2;
          $oRetorno->message = urlencode($eErro->getMessage());
        }
      }
      break;
      
    Case "getGarantias" :

    if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
      
      //oContrato    = new Acordo();
      $oContrato    = $_SESSION["oContrato"];
      $aGarantias = $oContrato->getGarantias();
      $oRetorno->isUpdate = false;
      foreach ($aGarantias as $oGarantia) {
        
        if ($oParam->iGarantia != '') {
          
          if ($oGarantia->getCodigo() == $oParam->iGarantia) {
            
            $oRetorno->isUpdate = true;
            $oGaran  = new stdClass();
            $oGaran->codigo    = $oGarantia->getCodigo();
            $oGaran->descricao = urlencode($oGarantia->getDescricao());
            $oGaran->texto     = urlencode($oGarantia->getTextoPadrao());
            $oRetorno->itens[] = $oGaran;
          }
        } else {
          
        
          $oGaran  = new stdClass();
          $oGaran->codigo    = $oGarantia->getCodigo();
          $oGaran->descricao = urlencode($oGarantia->getDescricao());
          $oGaran->texto     = urlencode(str_replace("\n","<br>",$oGarantia->getTextoPadrao()));
          $oRetorno->itens[] = $oGaran;
        
        }
      }
    }
    
    break;
     
  case "salvarGarantia":

    if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
      
      try {
        
        db_inicio_transacao();
        $oContrato    = $_SESSION["oContrato"];
        $oGarantia  = new AcordoGarantia($oParam->iGarantia);
        $oContrato->adicionarGarantias($oGarantia, utf8_decode($oParam->sTexto))
                  ->save();
        db_fim_transacao(false);                  
      } catch (Exception $eErro) {
        
        db_fim_transacao(true);
        $oRetorno->status = 2;
        $oRetorno->message = urlencode($eErro->getMessage());
      }
    }
    break;

   case "excluirGarantia":

      if (isset($_SESSION["oContrato"]) && $_SESSION["oContrato"] instanceof Acordo) {
        
        try {
          
          db_inicio_transacao();
          $oContrato    = $_SESSION["oContrato"];
          $oContrato->removerGarantia($oParam->iGarantia)
                    ->save();
          db_fim_transacao(false);                  
        } catch (Exception $eErro) {
          
          db_fim_transacao(true);
          $oRetorno->status = 2;
          $oRetorno->message = urlencode($eErro->getMessage());
        }
      }
      break;  
}
echo $oJson->encode($oRetorno);   
?>
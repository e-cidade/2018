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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_app.utils.php");

$oJson              = new services_json();
$oRetorno           = new stdClass();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

$lErro = false;

switch ($oParam->sExec) {
  
  case 'getCertidoes' :
    
    $iCodigoInicialInicio = $oParam->iCodigoInicialInicio;
    
    $iCodigoInicialFinal  = $oParam->iCodigoInicialFinal;
                          
    $oDaoInicialCert      = db_utils::getDao('inicialcert');
                          
    $sWhereInicialCert    = "v51_inicial between {$iCodigoInicialInicio} and {$iCodigoInicialFinal} and v50_situacao = 1 and v50_instit = ". db_getsession('DB_instit');
                          
    $sSqlInicialCert      = $oDaoInicialCert->sql_query(null, null, "v51_certidao as certidao, v51_inicial as inicial", "v51_inicial, v51_certidao", $sWhereInicialCert);
                          
    $rsInicialCert        = $oDaoInicialCert->sql_record($sSqlInicialCert);
                          
    
    if ($oDaoInicialCert->numrows > 0) {
      
      $oRetorno->aDadosIniciais = array();
      
      foreach (db_utils::getCollectionByRecord($rsInicialCert) as $oDadosInicial) {
  
        $oResultado                                        = new stdClass();
        $oResultado->iNumeroInicial                        = $oDadosInicial->inicial;
        $oRetorno->aDadosIniciais[$oDadosInicial->inicial] = $oResultado;
        $aCertidoes[$oDadosInicial->inicial][]             = $oDadosInicial->certidao;
      }
      
      foreach ($oRetorno->aDadosIniciais as $oResultado) {
        $oResultado->aCertidoes = $aCertidoes[$oResultado->iNumeroInicial];
      }
    } else {
      
      $oRetorno->iStatus  = 2;
      
      $oRetorno->sMessage = _M('tributario.juridico.jur1_emiteinicialanula001.nenhum_registro_encontrado'); 
      
    }  
    
    break;
    
  case 'anulaIniciais' :
      
      db_app::import('inicial');
      
      $oInicial = new inicial();
      
      $aIniciasSelecionadas = $oParam->aIniciaisSelecionadas;
      
      $sObservacaoAnulacao  = $oParam->sObservacao;
      
      db_inicio_transacao();
      
      $oRetorno->aLogIniciais   = array();
      
      for ($iIndice = 0; $iIndice < count($aIniciasSelecionadas); $iIndice++) {
      
        $iCodigoInicial = $aIniciasSelecionadas[$iIndice];
        
        try {
          
          $oInicial->setObservacaoMovimentacao($sObservacaoAnulacao);
          
          $oInicial->anulaInicial($iCodigoInicial, 9);
          
          
          $oLogIniciais = new stdClass();
          
          $oLogIniciais->lAnulada   = true;

          $oLogIniciais->iInicial   = $iCodigoInicial;
          
          $oLogIniciais->sResposta  = urlEncode('Inicial anulada.');
          
          $oRetorno->aLogIniciais[] = $oLogIniciais;
          
          
        } catch (BusinessException $oBusinessException) {
          
          $oLogIniciais = new stdClass();
          
          $oLogIniciais->lAnulada   = false;
          
          $oLogIniciais->iInicial   = $iCodigoInicial;
          
          $oLogIniciais->sResposta  = urlEncode($oBusinessException->getMessage());
          
          $oRetorno->aLogIniciais[] = $oLogIniciais;
          
          $oRetorno->iStatus        = 2;
          
        } catch (Exception $eException) {
          
          $lErro = true;
          
          $oRetorno->iStatus  = 3;
          
          $oRetorno->sMessage = urlEncode($eException->getMessage());
          
        }
            
      }
      
      db_fim_transacao($lErro);
      
      
    break;    
  
}

echo $oJson->encode($oRetorno);
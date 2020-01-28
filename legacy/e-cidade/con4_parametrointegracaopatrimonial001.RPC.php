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
require_once ("libs/JSON.php");  
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/contabilidade/ParametroIntegracaoPatrimonial.model.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';
$aDadosRetorno      = array();

try {

  switch ($oParam->sExec) {
    
    case "salvar" :

      db_inicio_transacao();
      $aDatasImplantacao = array();
      
      if ( ! empty($oParam->dtContrato) ) {
        $aDatasImplantacao[ParametroIntegracaoPatrimonial::CONTRATO] = $oParam->dtContrato;
      }

      if ( ! empty( $oParam->dtMaterial) ) {
        $aDatasImplantacao[ParametroIntegracaoPatrimonial::MATERIAL] = $oParam->dtMaterial;
      }

      if ( ! empty($oParam->dtPatrimonial) ) {
        $aDatasImplantacao[ParametroIntegracaoPatrimonial::PATRIMONIO] = $oParam->dtPatrimonial;
      }

      foreach ( $aDatasImplantacao as $iModulo => $sDataImplantacao ) {

        $oImplantacao = new ParametroIntegracaoPatrimonial();
        $oImplantacao->setModulo($iModulo);
        $oImplantacao->setDataImplantacao(new DBDate($sDataImplantacao));
        $oImplantacao->setInstituicao(new Instituicao(db_getsession('DB_instit')));
        $oImplantacao->salvar();
      }

      $oRetorno->sMessage = _M('financeiro.contabilidade.con4_parametrointegracaopatrimonial001.parametro_salvo');      
      db_fim_transacao(false);

    break;  
    
    case "getDados":
      
      $oInstituicao = new Instituicao(db_getsession("DB_instit"));
      $aParametros = ParametroIntegracaoPatrimonial::getParametroPorInstituicao($oInstituicao);
      
      $oDadosRetorno = new stdClass();
      $oDadosRetorno->dtContratos   = ''; 
      $oDadosRetorno->dtMaterial    = ''; 
      $oDadosRetorno->dtPatrimonial = ''; 

      foreach ($aParametros as $oParametro) {

        $sDataImplantacao = $oParametro->getDataImplantacao()->getDate();
        switch ($oParametro->getModulo()) {

          case ParametroIntegracaoPatrimonial::CONTRATO :
            $oDadosRetorno->dtContratos = $sDataImplantacao;
          break;

          case ParametroIntegracaoPatrimonial::MATERIAL :
            $oDadosRetorno->dtMaterial = $sDataImplantacao;
          break;

          case ParametroIntegracaoPatrimonial::PATRIMONIO :
            $oDadosRetorno->dtPatrimonial = $sDataImplantacao;
          break;
        }
      }
     
     $aDadosRetorno[] = $oDadosRetorno;
     $oRetorno->aDadosRetorno = $aDadosRetorno;     
      
    break;    
    
    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;
    
  }

  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);  
}
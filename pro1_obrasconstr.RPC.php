<?
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

/**
 * @fileoverview Controla Aчѕes no cadastro de contruчуo da obra
 * @version   $Revision: 1.3 $
 * @revision  $Author: dbrafael.nery $
 */
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  

require_once ("dbforms/db_funcoes.php");

require_once ("classes/db_caracter_classe.php");
require_once ("classes/db_iptuconstrobrasconstr_classe.php");
require_once ("classes/db_obrasalvara_classe.php");
require_once ("classes/db_obrasconstr_classe.php");
require_once ("classes/db_obrasconstrcaracter_classe.php");
require_once ("classes/db_obrasender_classe.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
/**
 * Camada de Tentativas do RPC
 */
try {
  
  switch ($oParam->sExec) {
    
    case "getCaracteristicasConstrucoes":
    
      $oDaoCaracter              = new cl_caracter();
      $oDaoIptuConstrObrasConstr = new cl_iptuconstrobrasconstr();
      $oDaoObrasConstrCaracter   = new cl_obrasconstrcaracter();
      
      $sSqlCaracter              = $oDaoObrasConstrCaracter->sql_query_selecoesCaracteristicas($oParam->iCodigoObra);
      $rsCaracter                = db_query($sSqlCaracter);
      
      if ( !$rsCaracter ) {
        throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_caracteristicas_grupo_construcoes'));
      }
      
      $aCaracter                 = db_utils::getCollectionByRecord($rsCaracter, false, false,true );
      $aDadosCaracter            = array();

      /**
       * Cria nova estrutura do array a ser retornado, para evitar dados duplicados
       */
      foreach ($aCaracter as $oCaracter) {
        
        $oDadosCaracter = new stdClass;
        $oDadosCaracter->iCodigoGrupo          = $oCaracter->j32_grupo;
        $oDadosCaracter->sDescricao            = $oCaracter->j32_descr;
                                               
        $oDetalheCaracteristica                = new stdClass();
        $oDetalheCaracteristica->j31_codigo    = $oCaracter->j31_codigo;
        $oDetalheCaracteristica->j31_descr     = $oCaracter->j31_descr;
        $oDetalheCaracteristica->lSelecionada  = $oCaracter->selecionada;
        
        
        if ( isset($aDadosCaracter[$oCaracter->j32_grupo]) ) {
          
          $oDadosExistentes                    = $aDadosCaracter[$oCaracter->j32_grupo];
          $oDadosCaracter->aCaracteristicas    = $oDadosExistentes->aCaracteristicas; 
          $oDadosCaracter->aCaracteristicas[]  = $oDetalheCaracteristica;
        } else {                               
          $oDadosCaracter->aCaracteristicas[]  = $oDetalheCaracteristica;
        }                                      
        $aDadosCaracter[$oCaracter->j32_grupo] = $oDadosCaracter;
      }
      $oRetorno->aDadosCaracteristicas      = $aDadosCaracter;

      break;

    case "salvar":
       
      $oDaoObrasConstr         = new cl_obrasconstr();
      $oDaoObrasEnder          = new cl_obrasender();
      $oDaoObrasConstrCaracter = new cl_obrasconstrcaracter();
      try {
        
        db_inicio_transacao();
        $oDaoObrasConstr->ob08_codobra    = $oParam->oDados->ob08_codobra;
        $oDaoObrasConstr->ob08_ocupacao   = $oParam->oDados->ob08_ocupacao;
        $oDaoObrasConstr->ob08_tipoconstr = $oParam->oDados->ob08_tipoconstr;
        $oDaoObrasConstr->ob08_area       = $oParam->oDados->ob08_area;
        $oDaoObrasConstr->ob08_tipolanc   = $oParam->oDados->ob08_tipolanc;
        
        if ( empty($oParam->oDados->ob08_codconstr) ) {
          $oDaoObrasConstr->incluir("");
        } else {
          $oDaoObrasConstr->ob08_codconstr = $oParam->oDados->ob08_codconstr;
          $oDaoObrasConstr->alterar($oParam->oDados->ob08_codconstr);
        }
        
        if ( (int)$oDaoObrasConstr->erro_status == 0 ) {
          
          $oParms = new stdClass();
          $oParms->sErroBanco = $oDaoObrasConstr->erro_banco;
          throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_salvar_construcao', $oParms));
        }
        /**
         * Manutenчуo na tabela obrasender
         */
        $oDaoObrasEnder->ob07_codconstr   = $oDaoObrasConstr->ob08_codconstr;
        $oDaoObrasEnder->ob07_codobra     = $oParam->oDados->ob08_codobra;
        $oDaoObrasEnder->ob07_lograd      = $oParam->oDados->ob07_lograd;   
        $oDaoObrasEnder->ob07_numero      = $oParam->oDados->ob07_numero;    
        $oDaoObrasEnder->ob07_compl       = $oParam->oDados->ob07_compl;     
        $oDaoObrasEnder->ob07_bairro      = $oParam->oDados->ob07_bairro;
        $oDaoObrasEnder->ob07_areaatual   = $oParam->oDados->ob07_areaatual; 
        $oDaoObrasEnder->ob07_unidades    = $oParam->oDados->ob07_unidades;
        $oDaoObrasEnder->ob07_pavimentos  = $oParam->oDados->ob07_pavimentos;
        $oDaoObrasEnder->ob07_inicio      = $oParam->oDados->ob07_inicio;
        $oDaoObrasEnder->ob07_fim         = $oParam->oDados->ob07_fim;
        
        if ( empty($oParam->oDados->ob08_codconstr) ) {
          $oDaoObrasEnder->incluir($oDaoObrasConstr->ob08_codconstr);
        } else {
          $oDaoObrasEnder->alterar_alternativo($oDaoObrasConstr->ob08_codconstr);
        }
        
        if ( (int)$oDaoObrasEnder->erro_status == 0 ) {

          $oParms = new stdClass();
          $oParms->sErroBanco = $oDaoObrasEnder->erro_banco;
          throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_salvar_endereco', $oParms));
        }
        /**
         * Valida se houveram modificaчѕes nas caracteristicas
         */
        if ( isset($oParam->oDados->oCaracteristicas) ) {
          
          $lExclusao = $oDaoObrasConstrCaracter->excluir(null, "ob34_obrasconstr = {$oDaoObrasConstr->ob08_codconstr}");
          
          if ( $lExclusao ) {
             
            foreach ( $oParam->oDados->oCaracteristicas as $iGrupo => $iCaracteristica ) {
            	
              if ( (int)$iCaracteristica != 0 ) {
              	
              	$oDaoObrasConstrCaracter = new cl_obrasconstrcaracter();
              	$oDaoObrasConstrCaracter->ob34_obrasconstr = $oDaoObrasConstr->ob08_codconstr;
              	$oDaoObrasConstrCaracter->ob34_caracter    = $iCaracteristica;
              	$oDaoObrasConstrCaracter->incluir("");

              	if ( (int)$oDaoObrasConstrCaracter->erro_status == 0 ) {
              	  
              	  $oParms = new stdClass();
              	  $oParms->sErroBanco = $oDaoObrasEnder->erro_banco;
                  throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_salvar_caracteristica', $oParms));
              	}
              }
            }
          } else {
            
            $oParms = new stdClass();
            $oParms->sErroBanco = $oDaoObrasEnder->erro_banco;
            throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_excluir_caracteristica', $oParms));
          }
        }
        $oRetorno->sMessage = $oDaoObrasConstr->erro_msg;
        db_fim_transacao(false);
        
      } catch (Exception $eErroBanco) {
        
        db_fim_transacao(true);
        throw new Exception( $eErroBanco->getMessage() );
      }
      break;
       
    case "excluir":
       
      try {
      	
        db_inicio_transacao();
        
        $oDaoObrasConstr         = new cl_obrasconstr();
        $oDaoObrasEnder          = new cl_obrasender();
        $oDaoObrasConstrCaracter = new cl_obrasconstrcaracter();
        $oDaoObrasAlvara         = new cl_obrasalvara();
        
        
        /**
         * Valida a existencia de alvara para a obra
         */
        $sSqlObrasAlvara         = $oDaoObrasAlvara->sql_query_file($oParam->iCodigoObra);
        $rsObrasAlavara          = $oDaoObrasAlvara->sql_record($sSqlObrasAlvara);
        
        if ( $oDaoObrasAlvara->numrows != 0 ) {
          throw new Exception(_M('tributario.projetos.pro1_obrasconstr.obra_com_alvara_liberado'));                    
        } 
        
        /**
         * Tenta excluir registros da caracteristicas da construчуo
         */        
        $oDaoObrasConstrCaracter->excluir(null,"ob34_obrasconstr = {$oParam->iCodigoConstrucao}");
        
        if ( (int)$oDaoObrasConstrCaracter->erro_status == 0 ) {
        	throw new Exception($oDaoObrasConstrCaracter->erro_msg);
        }
        
        /**
         * Tenta excluir os Registros do endereчo da obra
         */
        $oDaoObrasEnder->excluir($oParam->iCodigoConstrucao);
        
        if ( (int)$oDaoObrasEnder->erro_status == 0 ) {
        	throw new Exception($oDaoObrasEnder->erro_msg);
        }
        
        /**
        * Tenta excluir os Dados da construчуo
        */
        $oDaoObrasConstr->excluir($oParam->iCodigoConstrucao);
        
        if ( (int)$oDaoObrasConstr->erro_status == 0 ) {
        	throw new Exception($oDaoObrasConstr->erro_msg);
        }
        
        $oRetorno->sMessage = _M('tributario.projetos.pro1_obrasconstr.construcao_excluida');
        
        db_fim_transacao(false);
      } catch ( Exception $eErroBanco ) {
      	
      	db_fim_transacao(true);
      	throw new Exception( $eErroBanco->getMessage() );
      }
      break;
      
    default:
      throw new Exception(_M('tributario.projetos.pro1_obrasconstr.nenhum_opcao_definida'));
    break;
  }


  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>
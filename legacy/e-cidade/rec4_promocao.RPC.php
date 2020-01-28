<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * @fileoverview Controle controle referente as promoções
 * @version   $Revision: 1.1 $
 * @revision  $Author: dbrafael.nery $
 */
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  

require_once ("classes/db_assenta_classe.php");
require_once ("classes/db_rhpromocao_classe.php");

require_once ("dbforms/db_funcoes.php");

require_once ("model/recursosHumanos/Promocao.model.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode( str_replace("\\", "", urldecode($_POST["json"]) ) );

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

/**
 * Camada de Tentativas do RPC
 */
try {
  
  switch ($oParam->sExec) {
    
    /**
     * Consulta de perdas
     */   
    case "getPerdas":
      
      $oDaoAssenta = new cl_assenta;
      $sSqlAssenta = $oDaoAssenta->sql_query_perdasMatricula($oParam->iMatricula, 
                                                             $oParam->dtInicial, 
                                                             $oParam->dtFinal, 
                                                             "h70_sequencial, h70_descricao,sum(h16_quant) as h16_quant, h70_dias",
                                                             "group by h70_sequencial, h70_descricao, h70_dias");

      
      $rsAssenta   = $oDaoAssenta->sql_record($sSqlAssenta);
      
      if( $oDaoAssenta->erro_status == '0' && !empty($oDaoAssenta->erro_banco) ) {
        throw new Exception($oDaoAssenta->erro_msg);
      }
      
      $oRetorno->aDadosRetorno = array();

      if($oDaoAssenta->numrows > 0) {

        $aAssenta = db_utils::getCollectionByRecord($rsAssenta, false, false, true);

        foreach($aAssenta as $oAssenta) {

          $oRetornoAssenta                 = new stdClass();
          $oRetornoAssenta->h70_sequencial = $oAssenta->h70_sequencial;
          $oRetornoAssenta->h70_descricao  = $oAssenta->h70_descricao;
          $oRetornoAssenta->h16_quant      = $oAssenta->h16_quant;
          $oRetornoAssenta->h70_dias       = $oAssenta->h70_dias;
          $oRetorno->aDadosRetorno[]       = $oRetornoAssenta;
          
        }
      }

    break;
    
    /**
     * Fechamento da promoção
     */   
    case "fechamentoPromocao":

      try {
      	
        $oPromocao = new Promocao($oParam->iCodigoPromocao);
        $oPromocao->setDataFimPromocao($oParam->dtFinal);
        $oPromocao->setObservacaoPromocao($oParam->observacao);
        
      	$oDaoAssenta = new cl_assenta();
      	 
        

        $aCursos          = $oParam->aCursos;
        $iPontosAvaliacao = $oParam->totalPontos;
        $aTipoPerdas      = $oParam->aTipoPerdas;

        $aCodigoTipoPerda = array();

        foreach ($aTipoPerdas as $oTipoPerdas) {
        	 
        	if ( $oTipoPerdas->iValorPerda > $oTipoPerdas->iMaximoPermitidos ) {
        		
        		throw new Exception("Valor do assentamento maior que o máximo permitido." .
        				                "\n\nTipo de Perda: ".$oTipoPerdas->sDescricaoPerda .
        				                "\nTotal:{$oTipoPerdas->iValorPerda}"                 .
        				                "\nMáximo Permitido:{$oTipoPerdas->iMaximoPermitidos}");
        	}
        	$aCodigoTipoPerda[] = $oTipoPerdas->iCodigoTipoPerda;
        }


        $aAssentamentos = array();
        
        if ( count($aCodigoTipoPerda) > 0 ) {

          $sSqlAssentamentos = $oDaoAssenta->sql_query_perdasTipoPerda($oParam->iMatricula, $aCodigoTipoPerda, $oParam->dtInicial, $oParam->dtFinal);
          $rsAssentamentos   = $oDaoAssenta->sql_record($sSqlAssentamentos);

          if( $oDaoAssenta->erro_status == '0' && !empty($oDaoAssenta->erro_banco) ) {
            throw new Exception("Fechamento Promocao: ".$oDaoAssenta->erro_msg);
          }

          
          if ( $oDaoAssenta->numrows > 0 ) {

          	$aObjAssentamentos = db_utils::getCollectionByRecord($rsAssentamentos);


          	foreach ($aObjAssentamentos as $oAssentamento) {
          		$aAssentamentos[] = $oAssentamento->h16_codigo;
          	}
          }
        }

        db_inicio_transacao();
        /**
         * Valida se existem cursos selecionados
         * Percorre os cursos selecionados no fechamento e os liga a promoção
         */

        if (count($aCursos) > 0) {
        	 
        	foreach ($aCursos as $oCursos){
        		$oPromocao->adicionarCurso($oCursos->iCodigoCurso);
        	}
        }


        $oPromocao->fechamentoPromocao($iPontosAvaliacao, $aAssentamentos);

        db_fim_transacao(false);
        $oRetorno->sMessage = "Fechamento Promoção {$oPromocao->getCodigoPromocao()}: \nEfetuado com sucesso. ";

      } catch(Exception $oErroFechamento) {
      	 
      	throw new Exception ($oErroFechamento->getMessage());
      	db_fim_transacao(true);
      }

      break;

    default:
      throw new Exception("Nenhuma Opção Definida");
    break;
  }
  
  
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
} catch (Exception $eErro){
  
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}
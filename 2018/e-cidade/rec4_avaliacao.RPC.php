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

///dbportal_prj/rec4_avaliacao.RPC.php
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");  

require_once ("dbforms/db_funcoes.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();

try {


  switch ($oParam->exec) {

    case "tipoAvaliacao":
       
      require_once("classes/db_rhtipoavaliacao_classe.php");
      $oRhTipoAvaliacao  = new cl_rhtipoavaliacao;
      $sCampos  = "h69_sequencial,    ";
      $sCampos .= "h69_descricao,     ";
      $sCampos .= "h69_quantminima,   ";
      $sCampos .= "h69_quantmaxima,   ";
      $sCampos .= "h68_tipolancamento ";
      $sSqlTipoAvaliacao = $oRhTipoAvaliacao->sql_query(null, $sCampos, null, "h68_tipolancamento <> 3");
      $rsTipoAvaliacao   = $oRhTipoAvaliacao->sql_record($sSqlTipoAvaliacao);
      $aTipoAvaliacao    = db_utils::getCollectionByRecord($rsTipoAvaliacao, false, false, true);
      $aDadosAvaliacao   = array();
       
      foreach ($aTipoAvaliacao as $iIndiceTipo => $oValorTipo){

        $oDados = new stdClass();
        $oDados->h69_sequencial     = $oValorTipo->h69_sequencial;
        $oDados->h69_descricao      = $oValorTipo->h69_descricao;
        $oDados->h69_quantminima    = $oValorTipo->h69_quantminima;
        $oDados->h69_quantmaxima    = $oValorTipo->h69_quantmaxima;
        $oDados->h68_tipolancamento = $oValorTipo->h68_tipolancamento;
        $aDadosAvaliacao[] = $oDados;
      }
       
      $oRetorno->dados = $aDadosAvaliacao;

    break;

    ////////////////////////////////////////// SALVAR /////////////////////////////////
    
    case "salvar":
      
      
      db_inicio_transacao();
      
      /**
       * Camada de tentaiva para operações com o banco para salvar os dados
       */
      try {

        require_once('classes/db_rhtipoavaliacao_classe.php');
        require_once("model/recursosHumanos/Promocao.model.php");

        $oRhtipoavaliacao  = new cl_rhtipoavaliacao();
        $dDataAvaliacao    = implode("-", array_reverse(explode("/",$oParam->dDataAvaliacao)));
        $sObservacao       = $oParam->sObservacao;
        $aTiposAvaliacao   = $oParam->aDados;
        $aCursos           = $oParam->aCursos;
        $iPontoCursos      = $oParam->iPontoCursos;

        $sSqlTipoAvaliacao = $oRhtipoavaliacao->sql_query(null, "h69_rhgrupotipoavaliacao, h69_sequencial", null, "h69_rhgrupotipoavaliacao = 3");
        $rsTipoAvaliacao   = $oRhtipoavaliacao->sql_record($sSqlTipoAvaliacao);
        if ($oRhtipoavaliacao->numrows == 0) {
          throw new Exception("Não Existe Tipo de Avaliação vinculada ao grupo Conhecimento ");
        }
        
        $iTipoAvaliacaoCalculado = db_utils::fieldsMemory($rsTipoAvaliacao, 0)->h69_sequencial;
        $iPromocao               = $oParam->iPromocao;

        $oPromocao  = new Promocao($iPromocao);
        $oAvaliacao = $oPromocao->adicionarAvaliacao($dDataAvaliacao, $sObservacao);

        /**
         * Percorre os Cursos e os adiciona a Promoção e a avaliacao
         */
        foreach ( $aCursos as $oCursos ) {

          $oCurso     = $oPromocao->adicionarCurso($oCursos->iCodigoCurso);
          $oAvaliacao->adicionarCurso($oCurso);
        }
        /**
         * Percorre os Tipos de Avaliacao as adiciona a avaliacao
         */
        foreach ( $aTiposAvaliacao as $oTipoAvaliacao ) {
          
          /**
           *  verificamos se o tipoLancamento é != de 2 e valor está entre o minimo e o maximo
           */
          if ( $oTipoAvaliacao->iValor < $oTipoAvaliacao->iMinimo || $oTipoAvaliacao->iValor > $oTipoAvaliacao->iMaximo ) {
            throw new Exception('Valor não está entre o Minimo e Maximo Permitido para o Tipo de Avaliação');
          }
       
          $oAvaliacao->adicionarTipoAvaliacao($oTipoAvaliacao->iSequencial, $oTipoAvaliacao->iValor);
        }
        /**
         * Adicionado Fora do laço por ser regra especifica
         * quando for calculado e total de Cursos for maior que 0
         */
        if ($iPontoCursos <> 0) {
          $oAvaliacao->adicionarTipoAvaliacao($iTipoAvaliacaoCalculado, $iPontoCursos);
        }
        db_fim_transacao(false);
        $oRetorno->sMessage = "Avaliação {$oAvaliacao->getCodigoAvaliacao()}:\n  Processada com sucesso";        
      } catch (Exception $eErroClasses) {
        
        db_fim_transacao(true);
        throw new Exception( "Erro no processamento dos dados : \n".$eErroClasses->getMessage());
      }
      break;

      
      ////////////////////////////  LISTA DE AVALIAÇÂO /////////////////
    case "listaAvaliacao" :
       
      require_once("classes/db_rhavaliacao_classe.php");
      require_once("classes/db_rhavaliacaotipoavaliacao_classe.php");
      require_once("model/recursosHumanos/Promocao.model.php");
      
      $oPromocao         = new Promocao($oParam->iPromocao);
      $aAvaliacoes       = $oPromocao->getAvaliacoes();
    	$aDadosRhAvaliacao = array();

    	if (count($aAvaliacoes) == 0) {
      	throw new Exception("Nenhuma Avaliação Encontrada para a Matricula");
      }
      
      foreach ($aAvaliacoes as $oAvaliacao) {

      	$oDadosRhAvaliacao                  = new stdClass();
      	$oDadosRhAvaliacao->h73_sequencial  = $oAvaliacao->getCodigoAvaliacao();
      	$oDadosRhAvaliacao->h73_dtavaliacao = db_formatar($oAvaliacao->getDataAvaliacao(), "d");
      	$aDadosRhAvaliacao[]                = $oDadosRhAvaliacao;
      }
      
      $oRetorno->dados = $aDadosRhAvaliacao;
    break;	
    
    ///////////////////////////// CANCELAMENTO DE AVALIACAO
    
    case "cancelarAvaliacao":

    	require_once("classes/db_rhavaliacao_classe.php");
      require_once("classes/db_rhavaliacaotipoavaliacao_classe.php");
      require_once("model/recursosHumanos/Promocao.model.php");
      
    	$aListaCancelar = $oParam->aDados;
    	$iPromocao      = $oParam->iPromocao;
    	
    	if (empty($aListaCancelar)) {
    		throw new Exception("Selecione uma Avaliação para ser Cancelada");
    	}
    	
    	$oPromocao  = new Promocao($iPromocao);
    	
    	try {
    		
    		db_inicio_transacao();
      /**
       * percorremos as avaliaçoes a serem canceladas
       */
    		foreach ($aListaCancelar as $iAvaliacoesCancelar) {

    	    $oPromocao->removerAvaliacao($iAvaliacoesCancelar);

    		}
    		 
    	  $oRetorno->sMessage = "Cancelamento Efetuado com Sucesso.";
    		db_fim_transacao(false);
    	} catch (Exception $oErro) {

    		throw new Exception($oErro->getMessage());
    	}
    	
    break;	
    
    case "getTotalTiposAvaliacoes":
    
    	require_once("classes/db_rhtipoavaliacao_classe.php");
    	$oRhTipoAvaliacao  = new cl_rhtipoavaliacao;
    
    	$sCampos  = "h69_sequencial,     ";
    	$sCampos .= "h69_descricao,      ";
    	$sCampos .= "h68_tipolancamento, ";
    	$sCampos .= "h76_pontos          ";
    
    	$sSqlTipoAvaliacao = $oRhTipoAvaliacao->sql_query_somaRequisitos($oParam->iCodigoPromocao);
    	$rsTipoAvaliacao   = $oRhTipoAvaliacao->sql_record($sSqlTipoAvaliacao);
    	$aTipoAvaliacao    = db_utils::getCollectionByRecord($rsTipoAvaliacao, false, false, true);
    	$aDadosAvaliacao   = array();
    	$iTotalPontos = 0;
    	foreach ($aTipoAvaliacao as $iIndiceTipo => $oValorTipo){
    
    		$oDados = new stdClass();
    		$oDados->h69_sequencial     = $oValorTipo->h69_sequencial;
    		$oDados->h69_descricao      = $oValorTipo->h69_descricao;
    		$oDados->h76_pontos         = $oValorTipo->h76_pontos;
    		$aDadosAvaliacao[]          = $oDados;
    		$iTotalPontos              += $oValorTipo->h76_pontos;
    	}
    
    	$oRetorno->iTotalPontosAvaliacoes = $iTotalPontos;
    	$oRetorno->dados = $aDadosAvaliacao;
    
    	break;
    
   	
    default:
      throw new Exception("Nenhuma Opção Definida");
    break;
  }
  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
	db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>
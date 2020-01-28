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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");

require_once('classes/db_db_cadattdinamicoatributosvalor_classe.php');
require_once('classes/db_db_cadattdinamicovalorgrupo_classe.php');

require_once('model/DBAttDinamico.model.php');
require_once('model/DBAttDinamicoAtributo.model.php');

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMsg    = '';

try {
	
  /**
   * 
   * Consulta todos atributos cadastrados a partir do código agrupador de atributos
   *  
   */
  if ( $oParam->sMethod == 'consultarAtributos' ) {

    $oDBAttDinamico = new DBAttDinamico($oParam->iGrupoAtt);
    
    $oRetorno->iGrupoAtt  = $oDBAttDinamico->getCodigo();
    $oRetorno->sTitulo    = $oDBAttDinamico->getDescricao();
    $oRetorno->aAtributos = array();
    
    foreach ($oDBAttDinamico->getAtributos() as $oAtributo ) {
      
      if ($oAtributo->getCampo()) {
        $oAtributo->iCodCampo   = $oAtributo->getCampo()->iCodigo; 
        $oAtributo->sDescrCampo = urlencode($oAtributo->getCampo()->sDescricao);
      } else {
        $oAtributo->iCodCampo   = ''; 
        $oAtributo->sDescrCampo = '';        
      }      
      
      $oRetorno->aAtributos[] = $oAtributo;
    }
    
    $_SESSION['oDBAttDinamico'] = serialize($oDBAttDinamico);

    
  /**
   * 
   * Salva as informações do objeto em sessão na base de dados 
   * 
   */            
  } else if ( $oParam->sMethod == 'confirmar' ) {

    if (isset($_SESSION['oDBAttDinamico'])) {
      
      $oDBAttDinamico = unserialize($_SESSION['oDBAttDinamico']);
    } else {
      
      $oDBAttDinamico = new DBAttDinamico($oParam->iGrupoAtt);
    }    
    
    db_inicio_transacao();
    
    $oDBAttDinamico->salvar();
    
    $oRetorno->iGrupoAtt = $oDBAttDinamico->getCodigo();
    
    db_fim_transacao(false);
    
    
  /**
   * 
   * Adiciona ou altera os atributos do objeto em sessão 
   * 
   */        
  } else if ( $oParam->sMethod == 'salvarAtributo' ) {
    
    
    if (isset($_SESSION['oDBAttDinamico'])) {
      
      $oDBAttDinamico = unserialize($_SESSION['oDBAttDinamico']);
    } else {
      
      $oDBAttDinamico = new DBAttDinamico($oParam->iGrupoAtt);
      $oDBAttDinamico->setDescricao($oParam->sTitulo);
    }

    $oDBAttDinamicoAtributo = new DBAttDinamicoAtributo();
    $oDBAttDinamicoAtributo->setCodigo       ($oParam->iCodigo);
    $oDBAttDinamicoAtributo->setDescricao    ($oParam->sDescricao);
    $oDBAttDinamicoAtributo->setCampo        ($oParam->iCampo);
    $oDBAttDinamicoAtributo->setGrupoAtributo($oParam->iGrupoAtt);
    $oDBAttDinamicoAtributo->setTipo         ($oParam->iTipo);
    $oDBAttDinamicoAtributo->setValorDefault ($oParam->sValorDefault);

    if (isset($oParam->iIndAtributo) && trim($oParam->iIndAtributo) != '') {
      $oDBAttDinamico->alterarAtributo($oParam->iIndAtributo,$oDBAttDinamicoAtributo);
    } else {
      $oDBAttDinamico->adicionarAtributo($oDBAttDinamicoAtributo);
    }
    
    $oRetorno->aAtributos = array();
    
    foreach ($oDBAttDinamico->getAtributos() as $oAtributo ) {
      
      if ($oAtributo->getCampo()) {
        $oAtributo->iCodCampo   = $oAtributo->getCampo()->iCodigo; 
        $oAtributo->sDescrCampo = urlencode($oAtributo->getCampo()->sDescricao);
      } else {
        $oAtributo->iCodCampo   = ''; 
        $oAtributo->sDescrCampo = '';        
      }
      
      $oRetorno->aAtributos[] = $oAtributo;
    }
    
    $_SESSION['oDBAttDinamico'] = serialize($oDBAttDinamico);

    
  /**
   * 
   * Remove os atributos do objeto em sessão 
   * 
   */    
  } else if ( $oParam->sMethod == 'removerAtributo' ) {
    
    $oDBAttDinamico = unserialize($_SESSION['oDBAttDinamico']);
    
    $oDBAttDinamico->removerAtributo($oParam->iIndAtributo);
    
    $oRetorno->aAtributos = array();
    
    foreach ($oDBAttDinamico->getAtributos() as $oAtributo ) {
      
      if ($oAtributo->getCampo()) {
        $oAtributo->iCodCampo   = $oAtributo->getCampo()->iCodigo; 
        $oAtributo->sDescrCampo = urlencode($oAtributo->getCampo()->sDescricao);
      } else {
        $oAtributo->iCodCampo   = ''; 
        $oAtributo->sDescrCampo = '';        
      }      
      
      $oRetorno->aAtributos[] = $oAtributo;
    }    
    
    $_SESSION['oDBAttDinamico'] = serialize($oDBAttDinamico);

    
    
  /**
   * 
   * Salva os valores informados na tela
   * 
   */    
  } else if ( $oParam->sMethod == 'salvarValorAtributo' ) {
    

    $clCadAttValor      = new cl_db_cadattdinamicoatributosvalor();    
    $clCadAttValorGrupo = new cl_db_cadattdinamicovalorgrupo();
    
    $iGrupoAtributos    = $oParam->iGrupoAtributos;
    
    
    db_inicio_transacao();
    
    /**
     * Caso não exista valor para o agrupador de registros então gerado um novo 
     */
    if ($oParam->iGrupoValor == null) {

      $clCadAttValorGrupo->db120_sequencial = $oParam->iGrupoValor; 
      $clCadAttValorGrupo->incluir($oParam->iGrupoValor);
      
      $iGrupoValor = $clCadAttValorGrupo->db120_sequencial;
    } else {
      $iGrupoValor = $oParam->iGrupoValor;
    }
    
    $oRetorno->iGrupoValor = $iGrupoValor;
    
    /**
     * Exclui todos os valores já lançados para o agrupador de valor informado 
     */    
    $clCadAttValor->excluir(null," db110_cadattdinamicovalorgrupo = {$iGrupoValor} ");
    
    if ($clCadAttValor->erro_status == 0) {
      throw new Exception($clCadAttValor->erro_msg);
    }

    
    /**
     * Inclui na base de dados todos os valores informados na tela
     */
    foreach ($oParam->aAtributos as $oAtributo) {
      
      $clCadAttValor->db110_cadattdinamicovalorgrupo   = $iGrupoValor;
      $clCadAttValor->db110_db_cadattdinamicoatributos = $oAtributo->iCodigoAtributo;
      $clCadAttValor->db110_valor                      = $oAtributo->sValor;
      
      $clCadAttValor->incluir(null);
      
      if ($clCadAttValor->erro_status == 0) {
        throw new Exception($clCadAttValor->erro_msg);
      }
    }
    
    db_fim_transacao(false);
    
  /**
   * 
   *  Consulta os valores lançados a partir de um agrupador de valores lançados
   * 
   */    
  } else if ( $oParam->sMethod == 'consultaAtributosValor' ) {
    

    $oRetorno->aValoresAtributos  = array();
    $oRetorno->aAtributos         = array();
    
    $clCadAttValorGrupo    = new cl_db_cadattdinamicovalorgrupo();
    
    $rsDadosAtributosValor = $clCadAttValorGrupo->sql_record($clCadAttValorGrupo->sql_query($oParam->iGrupoValor)); 
    
    if ($clCadAttValorGrupo->numrows > 0) {
          
      $oRetorno->aValoresAtributos = db_utils::getColectionByRecord($rsDadosAtributosValor,false,false,true);
      
      $iGrupoAtributos = $oRetorno->aValoresAtributos[0]->db109_db_cadattdinamico; 

      $oDBAttDinamico = new DBAttDinamico($iGrupoAtributos);

      foreach ($oDBAttDinamico->getAtributos() as $oAtributo) {
        $oRetorno->aAtributos[] = $oAtributo;
      }
      
    } else {
      throw new Exception('Nenhum valor encontrado para os atributos informados!');
    }

    
  /**
   * 
   *  Apaga o objeto em sessão
   * 
   */    
  } else if ( $oParam->sMethod == 'finalizarSessao' ) {

    unset($_SESSION['oDBAttDinamico']);
    
  }  
  
} catch (Exception $eException) {

	if ( db_utils::inTransaction() ) {
		db_fim_transacao(true);
	}
	
  $oRetorno->iStatus  = 2;
  $oRetorno->sMsg     = urlencode(str_replace("\\n","\n",$eException->getMessage()));	
	
}


echo $oJson->encode($oRetorno);   
?>
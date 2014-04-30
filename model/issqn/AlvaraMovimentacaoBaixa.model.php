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

require_once("model/issqn/AlvaraMovimentacao.model.php");
require_once("classes/db_issmovalvarabaixa_classe.php");
require_once("classes/db_issalvara_classe.php");

/**
 * @deprecated
 * @see model/issqn/alvara/BaixaAlvara.model.php
 */
class AlvaraMovimentacaoBaixa extends AlvaraMovimentacao {
  
  
  function __construct($iCodigoAlvara){
    parent::__construct($iCodigoAlvara);
    
  }
  
  
  
  function baixar($iTipo, $iAlvara){
  	
  	$oIssAlvara = new cl_issalvara;
  	$oBaixa     = new cl_issmovalvarabaixa;

  	parent::salvar();
  	
  	$oBaixa->q129_issmovalvara = $this->getCodigoMovimentacao();
  	$oBaixa->q129_tipobaixa    = $iTipo;
  	$oBaixa->incluir(null);
  	if($oBaixa->erro_status == "0"){
  		
  		throw new ErrorException($oBaixa->erro_msg);
  	}
  	
  	$oIssAlvara->q123_sequencial = $iAlvara;
  	$oIssAlvara->q123_situacao = 2;
  	$oIssAlvara->alterar($oIssAlvara->q123_sequencial);
    
  	if($oIssAlvara->erro_status == "0"){
      
      throw new ErrorException($oIssAlvara->erro_msg);
    } 	
    

  }
  
  
  
}

?>
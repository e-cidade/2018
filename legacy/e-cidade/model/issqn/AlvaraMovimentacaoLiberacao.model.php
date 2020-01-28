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

/**
 * @deprecated
 * @see model/issqn/alvara/LiberacaoAlvara.model.php
 */
class AlvaraMovimentacaoLiberacao extends AlvaraMovimentacao { 
  
  
  function __construct($iCodigoAlvara){
    parent::__construct($iCodigoAlvara);
    
  }
  
  function liberar(){
  	
  	 parent::salvar();
  }
  
  public function gravaDocumentos() {
    parent::gravaDocumentos();
    
  }
  
  public function atualizaTipoAlvara($iTipoAlvara){
  	
  	require_once("classes/db_issalvara_classe.php");
  	$oIssAlvara = new cl_issalvara;
  	$oIssAlvara->q123_sequencial    = $this->getCodigoAlvara();
  	$oIssAlvara->q123_isstipoalvara = $iTipoAlvara;
  	$oIssAlvara->alterar($oIssAlvara->q123_sequencial);
  	if($oIssAlvara->erro_status == "0"){
  		
  		throw new Exception($oIssAlvara->erro_msg);
  	}
  	
  	
  }
  
}

?>
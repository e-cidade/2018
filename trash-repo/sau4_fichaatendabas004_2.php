<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require ("libs/db_utils.php");
require ("libs/JSON.php");

include("classes/db_prontuarios_classe.php");

include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);

$clprontuarios = new cl_prontuarios;

if( $oPost->strAction == 'gravar' ){

	db_inicio_transacao();
	
	$clprontuarios->sd24_i_codigo      = $oPost->sd24_i_codigo;
	$clprontuarios->sd24_t_diagnostico = $oPost->sd24_t_diagnostico;
	$clprontuarios->sd24_c_digitada    = $oPost->sd24_c_digitada;
	$clprontuarios->alterar($oPost->sd24_i_codigo );

  	db_fim_transacao();
  	
  	$booErro = $clprontuarios->numrows_alterar==0;
  	
}

if($oPost->strAction == 'gravar' ){
	$arrRetorno = array("mensagem"=>urlencode($clprontuarios->erro_msg), 
						"erro"=>$booErro, 
						"action"=>$oPost->strAction);
	
	$oJson = new services_json();	
    echo $oJson->encode($arrRetorno);
}

?>
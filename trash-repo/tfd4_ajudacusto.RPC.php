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
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");

db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");
db_app::import("exceptions.*");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {

    /**
     * Busca o valor padrão de um procedimento
     */
  	case 'valorPadraoProcedimento':

  	  $aWhere = array();
  	  if ( !empty($oParam->iCodigo) ) {
  	    $aWhere[] = "sd63_i_codigo = {$oParam->iCodigo}"; 
  	  }
  	  
  	  $sCampos = " (sd63_f_sh + sd63_f_sa + sd63_f_sp) as valor_procedimento ";
  	  $sWhere  = implode(" and ", $aWhere);
  	  
  	  $oDaoProcedimento      = new cl_sau_procedimento();
  	  $sSqlValorProcedimento = $oDaoProcedimento->sql_query_file(null, $sCampos, null, $sWhere);
  	  $rsValorProcedimento   = $oDaoProcedimento->sql_record($sSqlValorProcedimento); 
  	  
  	  if ($oDaoProcedimento->numrows == 0) {
  	  	throw new BusinessException("Não foi possível encontrar procedimento informado.");
  	  }
  	  
  	  $oRetorno->nValorProcedimento = db_utils::fieldsMemory($rsValorProcedimento, 0)->valor_procedimento;
  	  
  	  break;
  }
} catch (ParameterException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (BusinessException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
} catch (DBException $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}

unset($_SESSION["DB_desativar_account"]);
echo $oJson->encode($oRetorno);
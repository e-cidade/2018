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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhempenhoelementopcasp_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoRhempenhoelementopcasp = new cl_rhempenhoelementopcasp;
$db_botao    = false;
$db_opcao    = 33;
$sPosScripts = "";

if (isset($excluir)) {

  db_inicio_transacao();
  $db_opcao = 3;
  $oDaoRhempenhoelementopcasp->excluir($rh119_sequencial);
  db_fim_transacao();

  $sPosScripts .= 'alert("' . $oDaoRhempenhoelementopcasp->erro_msg . '");' . "\n";

  if ($oDaoRhempenhoelementopcasp->erro_status != "0") {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $oDaoRhempenhoelementopcasp->sql_record( $oDaoRhempenhoelementopcasp->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 33) {
  $sPosScripts .= "document.form1.pesquisar.click();";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "rh119_rhelementoempdef", true, 1, "rh119_rhelementoempdef", true);';
$sPosScripts .=  'js_pesquisarh119_rhelementoempdef(false, true);';
$sPosScripts .=  'js_pesquisarh119_rhelementoempnov(false, true);';

include("forms/db_frmrhempenhoelementopcasp.php");
?>
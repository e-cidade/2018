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
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_ppadotacao_classe.php");
include("dbforms/db_funcoes.php");
$clppadotacao = new cl_ppadotacao();
$oPost        = db_utils::postMemory($_POST); 
$oGet         = db_utils::postMemory($_GET); 
$db_opcao     = 3;
$db_opcao2    = 3;
$db_botao = true;
$lSqlErro = false;
$sErroMsg = "";
if (isset($oPost->excluir)) {
  
  
  db_inicio_transacao(); 
  /**
   * Verificamos se existe uma dotacao do ppa vinculada a
   * uma dotacao do orçamento
   */
  $oDaoPPaOrcDotacao = db_utils::getDao("ppadotacaoorcdotacao");
  $sSqlOrcDotacao    = $oDaoPPaOrcDotacao->sql_query_file(null,"*", null,"o19_ppadotacao= {$oPost->o08_sequencial}");
  $rsOrcDotacao      = $oDaoPPaOrcDotacao->sql_record($sSqlOrcDotacao);
  if ($oDaoPPaOrcDotacao->numrows > 0) {
    
    $oOrcDotacao = db_utils::fieldsMemory($rsOrcDotacao, 0);
    $oDaoPPaOrcDotacao->excluir($oOrcDotacao->o19_sequencial);
    if ($oDaoPPaOrcDotacao->erro_status == 0) {

      $sErroMsg = $oDaoPPaOrcDotacao->erro_msg;
      $lSqlErro = true;
      
    }
  }
  if (!$lSqlErro) {
    
    /**
     * deletamos a estimativa da dotacaçao
     */
    $oDaoPPaEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $oDaoPPaEstimativaDespesa->excluir(null, "o07_ppaestimativa = {$oPost->o05_sequencial}");
    if ($oDaoPPaEstimativaDespesa->erro_status == 0) {
      
      $sErroMsg = $oDaoPPaEstimativaDespesa->erro_msg;
      $lSqlErro = true;
      
    }
  }
  if (!$lSqlErro){
    
    $oDaoPPaEstimativa = db_utils::getDao("ppaestimativa");
    $oDaoPPaEstimativa->excluir($oPost->o05_sequencial);
    if ($oDaoPPaEstimativa->erro_status == 0) {
      
      $sErroMsg = $oDaoPPaEstimativa->erro_msg;
      $lSqlErro = true;
      
    }
  }
  if (!$lSqlErro) {
    
    $clppadotacao->excluir($oPost->o08_sequencial);
    if ($clppadotacao->erro_status == 0) {
    
      $sErroMsg = $clppadotacao->erro_msg;
      $lSqlErro = true;
    }
  }
  db_fim_transacao($lSqlErro);
}
if (isset($oGet->chavepesquisa)) {
    
    
    $sSql = $clppadotacao->sql_query_estimativa($oGet->chavepesquisa);
    $rsDotacao = $clppadotacao->sql_record($sSql);
    if ($clppadotacao->numrows > 0) {
      
      $db_opcao  = 3;
	  $db_opcao2 = 3;
      db_fieldsmemory($rsDotacao, 0);
      $o05_valor = round($o05_valor);
      
    }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<?
 require_once("forms/db_frmppadotacaoestimativa.php");
 if ($lSqlErro) {
   db_msgbox($sErroMsg);
 } else if (isset($oPost->excluir) && !$lSqlErro) {
   
   db_msgbox("Estimativa excluida com sucesso");
   echo "<script>js_pesquisa()</script>";
 }
 
 if ($db_opcao==3 && !isset($oGet->chavepesquisa)) {
   echo "<script>document.form1.pesquisar.click();</script>";
 } 

?>
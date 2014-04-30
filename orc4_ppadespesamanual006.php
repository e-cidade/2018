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
require("libs/db_app.utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_ppadotacao_classe.php");
include("classes/db_ppadotacaoorcdotacao_classe.php");
include("dbforms/db_funcoes.php");
$clppadotacao           = new cl_ppadotacao();
$clppadotacaoorcdotacao = new cl_ppadotacaoorcdotacao();
$oPost                  = db_utils::postMemory($_POST); 
$oGet                   = db_utils::postMemory($_GET); 

$db_opcao  = 1;
$db_opcao2 = 1;
$db_botao  = true;
$lSqlErro  = false;
$sErroMsg  = "";
if (isset($oPost->alterar)) {
  
  
  db_inicio_transacao(); 
  $clppadotacao->o08_sequencial = $o08_sequencial;
  $clppadotacao->alterar($o08_sequencial);
  $sErroMsg = $clppadotacao->erro_msg;
  if ($clppadotacao->erro_status == 0) {
    $lSqlErro = true;
  }
  if (!$lSqlErro) {
    /**
     * Alteramos o valor da estimativa
     */ 
    $oDaoPPaEstimativa = db_utils::getDao("ppaestimativa");
    $oDaoPPaEstimativa->o05_valor      =  $o05_valor;
    $oDaoPPaEstimativa->o05_sequencial = $o05_sequencial;
    $oDaoPPaEstimativa->alterar($o05_sequencial);
    $sErroMsg = $oDaoPPaEstimativa->erro_msg;
    if ($oDaoPPaEstimativa->erro_status == 0) {
      $lSqlErro = true;
    }
  }
  db_fim_transacao($lSqlErro);
}
if (isset($oGet->chavepesquisa)) {

  $campos = "o08_sequencial,
             o08_ano,
			 o05_ppaversao,
			 o01_descricao,
			 o08_orgao,
			 orcorgao.o40_descr,
			 o08_unidade,
			 o08_funcao,
			 o52_descr,	
             o08_subfuncao,
			 o53_descr,
			 o08_programa,
			 o54_descr,
			 o08_projativ,
			 o55_descr,
			 o08_elemento,
			 o56_elemento,
			 o56_codele,
             o08_recurso,
			 o15_codigo,
			 o08_recurso,
			 o15_descr,
			 o08_localizadorgastos,
			 o11_descricao,
			 o05_valor,
			 o05_sequencial";    
//	$campos = "*";		 	

    $sSql = $clppadotacao->sql_query_estimativa($oGet->chavepesquisa, $campos);
    $rsDotacao = $clppadotacao->sql_record($sSql);
    if ($clppadotacao->numrows > 0) {
      
	  $clppadotacaoorcdotacao->sql_record($clppadotacaoorcdotacao->sql_query_file($oGet->chavepesquisa)); 
      if($clppadotacaoorcdotacao->numrows > 0){
       $db_opcao  = 22;
	  }else{
	   $db_opcao  = 2;
	  }
 	  
	  $db_opcao2  = 2;
	  
      db_fieldsmemory($rsDotacao,0);
      $o05_valor = round($o05_valor);
      
    }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?
 require_once("forms/db_frmppadotacaoestimativa.php");
 if (isset($oPost->alterar)) {
   db_msgbox($sErroMsg);
   if($lSqlErro == false){
   	db_redireciona();
   }
 }  
 
 if ($db_opcao==22 && !isset($oGet->chavepesquisa)) {
   echo "<script>document.form1.pesquisar.click();</script>";
 } 

?>
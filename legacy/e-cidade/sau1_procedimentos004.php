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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_procedimentos_classe.php");
include("classes/db_procservicos_classe.php");
include("classes/db_proctipoa_classe.php");
include("classes/db_procgrupoa_classe.php");
include("classes/db_procfaixaetaria_classe.php");
include("classes/db_procespecialidades_classe.php");
include("classes/db_procvalores_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clprocservicos = new cl_procservicos;
$clproctipoa = new cl_proctipoa;
$clprocgrupoa = new cl_procgrupoa;
$clprocfaixaetaria = new cl_procfaixaetaria;
$clprocespecialidade = new cl_procespecialidades;
$clprocvalores = new cl_procvalores;
$clprocedimentos = new cl_procedimentos;
$db_botao = false;
$db_opcao = 33;
$db_opcao1 = 33;
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clprocservicos->excluir("sd19_i_procedimento = $sd09_i_codigo");
  $clproctipoa->excluir("sd20_i_procedimento = $sd09_i_codigo");
  $clprocgrupoa->excluir("sd17_i_procedimento = $sd09_i_codigo");
  $clprocfaixaetaria->excluir("sd16_i_procedimento = $sd09_i_codigo");
  $clprocespecialidade->excluir("sd18_i_procedimento = $sd09_i_codigo");
  $clprocvalores->excluir($sd09_i_codigo);
  $clprocedimentos->excluir($sd09_i_codigo);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clprocedimentos->sql_record($clprocedimentos->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmprocedimentos.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($clprocedimentos->erro_status=="0"){
    $clprocedimentos->erro(true,false);
  }else{
    $clprocedimentos->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
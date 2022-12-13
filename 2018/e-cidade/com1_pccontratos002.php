<?
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){
  echo "<script>location.href='com1_pccontratos005.php?db_opcao=2'</script>";
  exit;
}
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_pccontratos_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_pccontrdep_classe.php");
include("classes/db_pccontrlic_classe.php");
include("classes/db_pccontrdot_classe.php");
include("classes/db_pccontrcompra_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpccontrcompra = new cl_pccontrcompra;
$clpccontrdot = new cl_pccontrdot;
$clpccontrlic = new cl_pccontrlic;
$clpccontratos = new cl_pccontratos;
$clpccontrdep = new cl_pccontrdep;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $sqlerro = false;
  $clpccontratos->alterar($p71_codcontr);
  if($clpccontratos->erro_status == "0"){
    $sqlerro=true;
    $erro = $clpccontratos->erro_msg;
  }else{
    $ok = $clpccontratos->erro_msg;
  }
/*   $result = $clpccontrdep->sql_record($clpccontrdep->sql_query($p71_codcontr)); 
   if($clpccontrdep->numrows > 0){
    $clpccontrdep->excluir($p71_codcontr);
    $dep=1;
   }
   $result = $clpccontrlic->sql_record($clpccontrlic->sql_query($p71_codcontr)); 
   if($clpccontrlic->numrows > 0){
    $clpccontrlic->excluir($p71_codcontr);
    $lic=1;
   }
   $result = $clpccontrcompra->sql_record($clpccontrcompra->sql_query($p71_codcontr)); 
   if($clpccontrcompra->numrows > 0){
    $clpccontrcompra->excluir($p71_codcontr);
    $com=1;
   }*/
  if(isset($p74_valor)){
    $clpccontrdep->p74_codcontr = $p71_codcontr;
    $clpccontrdep->alterar($p71_codcontr);
    if($clpccontrdep->erro_status == "0"){
      $sqlerro=true;
      $erro = $clpccontrdep->erro_msg;
    }
  }
  if(isset($p75_tipo)){
    $clpccontrlic->p75_codcontr = $p71_codcontr;
    $clpccontrlic->alterar($p71_codcontr);
    if($clpccontrlic->erro_status == "0"){
      $sqlerro=true;
      $erro = $clpccontrlic->erro_msg;
    }
  }
  if(isset($p72_codcom)){
    $clpccontrcompra->p72_codcontr = $p71_codcontr;
    $clpccontrcompra->alterar($p71_codcontr);
    if($clpccontrcompra->erro_status == "0"){
      $sqlerro=true;
      $erro = $clpccontrcompra->erro_msg;
    }
  }
  $db_botao = true;
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clpccontratos->sql_record($clpccontratos->sql_query($chavepesquisa,"p71_codcontr,p71_datalanc,p71_numcgm,p71_codtipo as p71_codtipo,p71_codtipo as p71_codtipo_old,p71_dtini,p71_dtfim")); 
   db_fieldsmemory($result,0);
   $result = $clpccontrdep->sql_record($clpccontrdep->sql_query($chavepesquisa)); 
   if($clpccontrdep->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $result = $clpccontrlic->sql_record($clpccontrlic->sql_query($chavepesquisa)); 
   if($clpccontrlic->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $result = $clpccontrcompra->sql_record($clpccontrcompra->sql_query($chavepesquisa)); 
   if($clpccontrcompra->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $db_botao = true;
    echo "
         <script>
	   parent.iframe_dotacao.location.href='com1_pccontrdot001.php?p73_codcontr=".$chavepesquisa."&abas=1';\n
	   parent.document.formaba.dotacao.disabled = false;
	</script>";   
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmpccontratos.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
if($sqlerro == false){
  db_msgbox($ok);
    echo "
         <script>
	   parent.iframe_contratos.location.href='com1_pccontratos002.php?chavepesquisa=".$p71_codcontr."&abas=1';\n
	</script>";   
}else{
  db_msgbox($erro);
}
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
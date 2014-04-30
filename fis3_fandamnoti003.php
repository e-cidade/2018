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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){
  echo "<script>location.href='fis3_fandamnoti005.php?db_opcao=3'</script>";
  exit;
}
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_fiscal_classe.php");
include("classes/db_fiscaltipo_classe.php");
include("classes/db_fiscalandam_classe.php");
include("classes/db_fiscalultandam_classe.php");
include("classes/db_fandam_classe.php");
include("classes/db_fandamusu_classe.php");
include("classes/db_fiscalrua_classe.php");
include("classes/db_fiscbairro_classe.php");
include("classes/db_fiscalusuario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo        = new rotulocampo;
$clfiscal     = new cl_fiscal;
$clfiscaltipo = new cl_fiscaltipo;
$clfiscalandam = new cl_fiscalandam;
$clfiscalultandam = new cl_fiscalultandam;
$clfandam        = new cl_fandam;
$clfandamusu     = new cl_fandamusu;
$clfiscalusuario   = new cl_fiscalusuario;
$clfiscalrua   = new cl_fiscalrua;
$clfiscbairro  = new cl_fiscbairro;
$clrotulo->label("y39_codandam");
$clrotulo->label("y30_codnoti");
$db_botao = false;
$pesqandam = 1;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 3;
  $db_botao = false;
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamnotiusu001.php?primeira=1';</script>";
  echo "<script>parent.document.formaba.fiscais.disabled=true;</script>";
  $result = $clfandamusu->sql_record($clfandamusu->sql_query("","","*",""," y40_codandam = $y39_codandam"));
  if($clfandamusu->numrows > 0){
    $sqlerro=false;
    $numrows = $clfandamusu->numrows;
    for($i=0;$i<$numrows;$i++){ 
      db_fieldsmemory($result,$i);
      $clfandamusu->excluir($y40_codandam,$y40_id_usuario);
      $erro=$clfandamusu->erro_msg;
      if($clfandamusu->erro_status==0){
        $sqlerro = true;
      }
    }
  }
  $result = $clfiscalultandam->sql_record($clfiscalultandam->sql_query($y30_codnoti));  
  if($clfiscalultandam->numrows > 0){
    db_fieldsmemory($result,0);
  }
  $clfiscalandam->excluir($y30_codnoti,$y19_codandam);
  $clfiscalultandam->y19_codnoti=$y30_codnoti;
  $clfiscalultandam->excluir($y30_codnoti);
  $clfandam->excluir($y39_codandam);
  $result = $clfiscalandam->sql_record($clfiscalandam->sql_query("",""," max(y49_codandam) as ultimo",""," y49_codnoti <> $y39_codandam and y49_codnoti = $y30_codnoti")); 
  db_fieldsmemory($result,0);
  $clfiscalultandam->incluir($y30_codnoti,$ultimo);
  db_fim_transacao();
}
$result = $clfiscalultandam->sql_record($clfiscalultandam->sql_query("","","*"," y19_codandam desc")); 
if($clfiscalultandam->numrows > 0){
  db_fieldsmemory($result,0);
  $result = $clfiscal->sql_record($clfiscal->sql_query($y19_codnoti,"*")); 
  if($clfiscal->numrows > 0){
    db_fieldsmemory($result,0);
    $result = $clfiscalrua->sql_record($clfiscalrua->sql_query($y30_codnoti)); 
    if($clfiscalrua->numrows > 0){
      db_fieldsmemory($result,0);
    }
    $result = $clfiscbairro->sql_record($clfiscbairro->sql_query($y30_codnoti)); 
    if($clfiscbairro->numrows > 0){
      db_fieldsmemory($result,0);
    }
  }
  $result = $clfandam->sql_record($clfandam->sql_query($y19_codandam)); 
  if($clfandam->numrows > 0){
    db_fieldsmemory($result,0);
  }
}else{
  echo "<script>alert('Não existem notificações cadastradas!');</script>";
  echo "<script>document.form1.db_opcao.disabled=true;</script>";
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamnotiusu001.php?abas=1';</script>";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#cccccc">
    <td align="center">
      <strong><small>* VOCÊ SÓ PODE EXCLUIR O ÚLTIMO ANDAMENTO</small></strong>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC"> 
    <fieldset width="100%">
    <legend align="center">NOTICAÇÃO</legend>
    <center>
	<?
	  $db_opcao = 3;
	  db_ancora(@$Ly30_codnoti,"js_fiscal(true);",1);
	  db_input('y30_codnoti',20,$Iy30_codnoti,true,'text',3,"")
	?>
    </center>
    </fieldset>	
	</td>
  </tr>
  <tr>  
    <td height="230" width="100%" align="center" valign="top" bgcolor="#CCCCCC"> 
    <fieldset>
    <legend align="center">ANDAMENTO</legend>
    <center>
	<?
	$db_opcao=3;
        if($db_opcao==3 && !isset($chavepesquisa)){
	  $db_opcao=33;
        }
        $db_botao = true;
	include("forms/db_frmfandam.php");
	?>
    </center>
    </fieldset>	
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clfandam->erro_status=="0"){
    $clfandam->erro(true,false);
  }else{
    if(@$sqlerro==true){
      db_msgbox($erro);
    }else{
      $clfandam->erro(true,false);
      echo "<script>parent.document.formaba.fiscais.disabled=true;</script>";
      echo "<script>parent.iframe_fandam.location.href='fis3_fandamnoti003.php?abas=1&y30_codnoti=$y30_codnoti';</script>";
    }
  };
};
if(isset($sqlerro) && $sqlerro==false){
   $result = $clfiscal->sql_record($clfiscal->sql_query($y30_codnoti,"*")); 
   if($clfiscal->numrows > 0){
     db_fieldsmemory($result,0);
     $result = $clfiscalusuario->sql_record($clfiscalusuario->sql_query($y30_codnoti)); 
     if($clfiscalusuario->numrows == 0){
       $db_opcao = 22;
       echo "<script>alert('Não existem fiscais cadastrados para esta notificação!');</script>";
       echo "<script>document.form1.db_opcao.disabled=true;</script>";
       exit;
       $db_botao = false;
     }
   }else{
     echo "<script>alert('Você só pode excluir o último andamento');</script>";
   }
}
?>
<script>
function js_fiscal(mostra){
  var noti=document.form1.y30_codnoti.value;
  js_OpenJanelaIframe('','db_iframe','fis3_fiscal006.php?y30_codnoti='+noti,'Consulta',true,0);
}
</script>
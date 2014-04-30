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
  echo "<script>location.href='fis3_fandamnoti005.php'</script>";
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
include("classes/db_fiscalusuario_classe.php");
include("classes/db_fiscalrua_classe.php");
include("classes/db_fiscbairro_classe.php");
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
$db_opcao = 1;
$db_botao = true;
if(isset($y30_codnoti) && !isset($HTTP_POST_VARS["db_opcao"])){
   $db_opcao = 3;
   $result = $clfiscal->sql_record($clfiscal->sql_query($y30_codnoti,"*",null," y30_instit = ".db_getsession('DB_instit') )); 
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
     $result = $clfiscalusuario->sql_record($clfiscalusuario->sql_query($y30_codnoti)); 
     if($clfiscalusuario->numrows == 0){
       $db_opcao = 1;
       echo "<script>alert('Não existem fiscais cadastrados para esta notificação!');</script>";
       include("fis3_fandamnoti004.php");
       exit;
     }
     $db_botao = false;
   }else{
     $db_opcao = 1;
     echo "<script>alert('Código da Notificação inválido!');</script>";
     include("fis3_fandamnoti004.php");
     exit;
   }
}
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  $sqlerro = false;
  $clfandam->incluir($y39_codandam);
  $erro=$clfandam->erro_msg;
  if($clfandam->erro_status==0){
    $sqlerro = true;
  }
  $clfiscalultandam->y19_codnoti=$y30_codnoti;
  $clfiscalultandam->excluir($y30_codnoti);
  $clfiscalultandam->incluir($y30_codnoti,$clfandam->y39_codandam);
  $clfiscalandam->incluir($y30_codnoti,$clfandam->y39_codandam);
  $erro=$clfiscalultandam->erro_msg;
  if($clfiscalultandam->erro_status==0){
    $sqlerro = true;
  }
  db_fim_transacao();
}
if(!isset($pri)){
  include("fis3_fandamnoti004.php");
  exit;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" bgcolor="#cccccc" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="40" align="center" valign="top" bgcolor="#CCCCCC"> 
    <fieldset>
    <legend align="center">NOTIFICAÇÂO</legend>
    <center>
    <?
      db_ancora(@$Ly30_codnoti,"js_fiscal(true);",1);
      db_input('y30_codnoti',20,$Iy30_codnoti,true,'text',3,"")
    ?>
    </center>
    </fieldset>	
	</td>
  </tr>
  <tr>  
    <td height="100%" align="center" width="100%" valign="top" bgcolor="#CCCCCC"> 
    <fieldset>
    <legend align="center">ANDAMENTO</legend>
    <center>
	<?
	$db_opcao=1;
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
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clfandam->erro_status=="0"){
    $clfandam->erro(true,false);
    $db_botao=true;
    if($clfandam->erro_campo!=""){
      echo "<script> document.form1.".$clfandam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfandam->erro_campo.".focus();</script>";
    };
  }else{
    if($sqlerro == true){
      db_msgbox($erro);
    }else{
      $clfandam->erro(true,false);
      echo "<script>parent.iframe_fiscais.location.href='fis3_fandamnotiusu001.php?y38_codnoti=$y30_codnoti&y39_codandam=".$clfandam->y39_codandam."';</script>";
      echo "<script>parent.mo_camada('fiscais');</script>";
      echo "<script>parent.document.formaba.fiscais.disabled=false;</script>";
      echo "<script>parent.iframe_fandam.location.href='fis3_fandamnoti002.php?abas=1&y30_codnoti=$y30_codnoti&chavepesquisa=".$clfandam->y39_codandam."';</script>";
    }
  };
};
?>
<script>
function js_fiscal(mostra){
  var noti=document.form1.y30_codnoti.value;
  js_OpenJanelaIframe('','db_iframe','fis3_fiscal006.php?y30_codnoti='+noti,'Consulta',true,0);
}
</script>
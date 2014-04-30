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
  echo "<script>location.href='fis3_fandamauto005.php?db_opcao=3'</script>";
  exit;
}
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_auto_classe.php");
include("classes/db_autotipo_classe.php");
include("classes/db_autoandam_classe.php");
include("classes/db_autoultandam_classe.php");
include("classes/db_fandam_classe.php");
include("classes/db_fandamusu_classe.php");
include("classes/db_autolocal_classe.php");
include("classes/db_autoexec_classe.php");
include("classes/db_autousu_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo        = new rotulocampo;
$clauto     = new cl_auto;
$clautotipo = new cl_autotipo;
$clautoandam = new cl_autoandam;
$clautoultandam = new cl_autoultandam;
$clfandam        = new cl_fandam;
$clfandamusu     = new cl_fandamusu;
$clautousu   = new cl_autousu;
$clautolocal   = new cl_autolocal;
$clautoexec  = new cl_autoexec;
$clrotulo->label("y39_codandam");
$clrotulo->label("y50_codauto");
$db_botao = false;
$auto=1;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 3;
  $db_botao = false;
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamautousu001.php?primeira=1';</script>";
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
  $result = $clautoultandam->sql_record($clautoultandam->sql_query($y50_codauto,null,"*",null," y50_instit = ".db_getsession('DB_instit') ));  
  if($clautoultandam->numrows > 0){
    db_fieldsmemory($result,0);
  }
  $clautoandam->excluir($y50_codauto,$y16_codandam);
  $clautoultandam->y19_codnoti=$y50_codauto;
  $clautoultandam->excluir($y50_codauto);
  $clfandam->excluir($y39_codandam);
  $result = $clautoandam->sql_record($clautoandam->sql_query("",""," max(y58_codandam) as ultimo",""," y58_codauto <> $y39_codandam and y58_codauto = $y50_codauto and y50_intit = ".db_getsession('DB_instit') )); 
  db_fieldsmemory($result,0);
  $clautoultandam->incluir($y50_codauto ,$ultimo);
  db_fim_transacao();
}
$result = $clautoultandam->sql_record($clautoultandam->sql_query("","","*"," y16_codandam desc"," y50_instit = ".db_getsession('DB_instit') )); 
if($clautoultandam->numrows > 0){
  db_fieldsmemory($result,0);
  $result = $clauto->sql_record($clauto->sql_query($y16_codauto,"*",null," y50_instit = ".db_getsession('DB_instit') )); 
  if($clauto->numrows > 0){
    db_fieldsmemory($result,0);
    $result = $clautolocal->sql_record($clautolocal->sql_query($y16_codauto)); 
    if($clautolocal->numrows > 0){
      db_fieldsmemory($result,0);
    }
    $result = $clautoexec->sql_record($clautoexec->sql_query($y16_codauto)); 
    if($clautoexec->numrows > 0){
      db_fieldsmemory($result,0);
    }
  }
  $result = $clfandam->sql_record($clfandam->sql_query($y16_codandam)); 
  if($clfandam->numrows > 0){
    db_fieldsmemory($result,0);
  }
}else{
  echo "<script>alert('Não existe auto de infração cadastrado!');</script>";
  $desliga="1";
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamautousu001.php?abas=1';</script>";
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
    <legend align="center">AUTO DE INFRAÇÃO</legend>
    <center>
	<?
	  $db_opcao = 3;
	  db_ancora(@$Ly50_codauto,"js_auto(true);",1);
	  db_input('y50_codauto',20,$Iy50_codauto,true,'text',3,"")
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
        if(isset($desliga)){  
	  echo "<script>document.form1.db_opcao.disabled=true;</script>";
	}
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
      echo "<script>parent.iframe_fandam.location.href='fis3_fandamauto003.php?abas=1&y50_codauto=$y50_codauto';</script>";
    }
  };
};
if(isset($sqlerro) && $sqlerro==false){
   $result = $clauto->sql_record($clauto->sql_query($y50_codauto,"*")); 
   if($clauto->numrows > 0){
     db_fieldsmemory($result,0);
     $result = $clautousu->sql_record($clautousu->sql_query($y50_codauto)); 
     if($clautousuario->numrows == 0){
       $db_opcao = 22;
       echo "<script>alert('Não existem fiscais cadastrados para este auto de infração!');</script>";
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
function js_auto(mostra){
  var auto=document.form1.y50_codauto.value;
  js_OpenJanelaIframe('','db_iframe','fis3_auto006.php?y50_codauto='+auto,'Consulta',true,0);
}
</script>
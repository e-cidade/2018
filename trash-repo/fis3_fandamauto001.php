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
include("classes/db_auto_classe.php");
include("classes/db_autotipo_classe.php");
include("classes/db_autoandam_classe.php");
include("classes/db_autoultandam_classe.php");
include("classes/db_fandam_classe.php");
include("classes/db_fandamusu_classe.php");
include("classes/db_autousu_classe.php");
include("classes/db_autolocal_classe.php");
include("classes/db_autoexec_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){
  echo "<script>location.href='fis3_fandamauto005.php'</script>";
  exit;
}
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
$db_opcao = 1;
$db_botao = true;
$auto = 1;
if(isset($y50_codauto) && !isset($HTTP_POST_VARS["db_opcao"])){
   $db_opcao = 3;
   $result = $clauto->sql_record($clauto->sql_query($y50_codauto,"*")); 
   if($clauto->numrows > 0){
     db_fieldsmemory($result,0);
//     echo($clautolocal->sql_query($y50_codauto,"*",null," y50_codauto = $y50_codauto and y50_instit = ".db_getsession('DB_instit') )."<br>"); 
     $result = $clautolocal->sql_record($clautolocal->sql_query($y50_codauto,"*",null," y50_codauto = $y50_codauto and y50_instit = ".db_getsession('DB_instit') )); 
     if($clautolocal->numrows > 0){
       db_fieldsmemory($result,0);
     }
//     echo ($clautoexec->sql_query($y50_codauto,"*",null," y50_codauto = $y50_codauto and y50_instit = ".db_getsession('DB_instit') )."<br>"); 
     $result = $clautoexec->sql_record($clautoexec->sql_query($y50_codauto,"*",null," y50_codauto = $y50_codauto and y50_instit = ".db_getsession('DB_instit') )); 
     if($clautoexec->numrows > 0){
       db_fieldsmemory($result,0);
     }
//    echo ($clautousu->sql_query($y50_codauto,null,"*",null," y50_codauto = $y50_codauto and y50_instit = ".db_getsession('DB_instit'))."<br>"); 
     $result = $clautousu->sql_record($clautousu->sql_query($y50_codauto,null,"*",null," y50_codauto = $y50_codauto and y50_instit = ".db_getsession('DB_instit')));
           
     if($clautousu->numrows == 0){
       $db_opcao = 1;
       echo "<script>alert('Não existem fiscais cadastrados para este auto de infração!');</script>";
       include("fis3_fandamauto004.php");
       exit;
     }
     $db_botao = false;
   }else{
     $db_opcao = 1;
     echo "<script>alert('Código do auto de infração inválido!');</script>";
     include("fis3_fandamauto004.php");
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
  $clautoultandam->y16_codnoti=$y50_codauto;
  $clautoultandam->excluir($y50_codauto);
  $clautoultandam->incluir($y50_codauto,$clfandam->y39_codandam);
  $clautoandam->incluir($y50_codauto,$clfandam->y39_codandam);
  $erro=$clautoultandam->erro_msg;
  if($clautoultandam->erro_status==0){
    $sqlerro = true;
  }
  db_fim_transacao();
}
if(!isset($pri)){
  include("fis3_fandamauto004.php");
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
    <legend align="center">AUTO DE INFRAÇÃO</legend>
    <center>
    <?
      db_ancora(@$Ly50_codauto,"js_auto(true);",1);
      db_input('y50_codauto',20,$Iy50_codauto,true,'text',3,"");
      
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
      echo "<script>parent.iframe_fiscais.location.href='fis3_fandamautousu001.php?y50_codauto=$y50_codauto&y39_codandam=".$clfandam->y39_codandam."';</script>";
      echo "<script>parent.mo_camada('fiscais');</script>";
      echo "<script>parent.document.formaba.fiscais.disabled=false;</script>";
      echo "<script>parent.iframe_fandam.location.href='fis3_fandamauto002.php?abas=1&y50_codauto=$y50_codauto&chavepesquisa=".$clfandam->y39_codandam."';</script>";
    }
  };
};
?>
<script>
function js_auto(mostra){
  var auto=document.form1.y50_codauto.value;
  js_OpenJanelaIframe('','db_iframe','fis3_auto006.php?y50_codauto='+auto,'Consulta',true,0);
}
</script>
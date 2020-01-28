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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_escola_classe.php");
include("classes/db_censouf_classe.php");
include("classes/db_censomunic_classe.php");
include("classes/db_censodistrito_classe.php");
include("classes/db_censoorgreg_classe.php");
include("classes/db_censolinguaindig_classe.php");
include("classes/db_db_depart_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$clescola = new cl_escola;
$clcensouf = new cl_censouf;
$clcensomunic = new cl_censomunic;
$clcensodistrito = new cl_censodistrito;
$clcensoorgreg = new cl_censoorgreg;
$clcensolinguaindig = new cl_censolinguaindig;
$cldb_depart = new cl_db_depart;
$db_botao = true;
function PegaValores($array,$tamanho){
 $retorno = "";
 for($x=1;$x<=$tamanho;$x++){
  $tem = false;
  for($y=0;$y<count($array);$y++){
   if($array[$y]==$x){
    $retorno .= "1";
    $tem = true;
    break;
   }
  }
  if($tem==false){
   $retorno .= "0";
  }
 }
 return $retorno;
}
if(isset($incluir)){
 $db_opcao = 1;
 $tmp_name = $_FILES["ed18_c_logo"]["tmp_name"];
 $name     = $_FILES["ed18_c_logo"]["name"];
 $type     = $_FILES["ed18_c_logo"]["type"];
 $size     = $_FILES["ed18_c_logo"]["size"];
 if($type!="image/jpeg" && $tmp_name!=""){
  db_msgbox("Utilizar somente imagens no formato JPG ou JPEG!");
  $ed18_c_logo = "";
 }else{
  @$ed18_c_mantprivada = PegaValores($ed18_c_mantprivada,4);
  db_inicio_transacao();
  $clescola->ed18_c_tipo = 'S';
  $clescola->ed18_c_logo = $name;
  $clescola->ed18_c_mantprivada = $ed18_c_mantprivada;
  $clescola->incluir($ed18_i_codigo);
  db_fim_transacao();
 }
}elseif(isset($alterar)){
 $db_opcao = 2;
 $tmp_name = $_FILES["ed18_c_logo"]["tmp_name"];
 $name     = $_FILES["ed18_c_logo"]["name"];
 $type     = $_FILES["ed18_c_logo"]["type"];
 $size     = $_FILES["ed18_c_logo"]["size"];
 if($type!="image/jpeg" && $tmp_name!=""){
  db_msgbox("Utilizar somente imagens no formato JPG ou JPEG!");
  $ed18_c_logo = "";
 }else{
  @$ed18_c_mantprivada = PegaValores($ed18_c_mantprivada,4);
  db_inicio_transacao();
  $clescola->ed18_c_tipo = 'S';
  $clescola->ed18_c_logo = $name;
  $clescola->ed18_c_mantprivada = $ed18_c_mantprivada;
  $clescola->alterar($ed18_i_codigo);
  db_fim_transacao();
 }
}elseif(isset($excluirfoto)){
 $sql = "UPDATE escola SET ed18_c_logo = '' WHERE ed18_i_codigo = $ed18_i_codigo";
 $result = pg_query($sql);
 unlink(exec("pwd")."/imagens/".$ed18_c_logo);
 db_redireciona("edu1_escola002.php");
}else{
 $ed18_i_codigo = db_getsession("DB_coddepto");
 $result = $clescola->sql_record($clescola->sql_query($ed18_i_codigo));
 $result_depto = $cldb_depart->sql_record($cldb_depart->sql_query_file("","*","","coddepto = $ed18_i_codigo"));
 db_fieldsmemory($result_depto,0);
 if($clescola->numrows!=0){
  db_fieldsmemory($result,0);
  $db_opcao = 2;
  $db_opcao1 = 1;
  if(isset($cp06_cep)){
   if($cp06_cep!=""){
    $ed18_c_cep = $cp06_cep;
   }
  }
  ?>
  <script>
   parent.document.formaba.a2.disabled = false;
   parent.document.formaba.a2.style.color = "black";
   top.corpo.iframe_a2.location.href='edu1_cursobase001.php?escola=<?=$ed18_i_codigo?>&ed18_c_nome=<?=$ed18_c_nome?>';
  </script>
  <?
 }else{
  $ed18_c_nome = $descrdepto;
  $db_opcao = 1;
 }
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Dados da Escola</b></legend>
    <?include("forms/db_frmescola.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($clescola->erro_status=="0"){
  $clescola->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clescola->erro_campo!=""){
   echo "<script> document.form1.".$clescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clescola->erro_campo.".focus();</script>";
  };
 }else{
  if($tmp_name!=""){
   ///enviar para pasta imagens
   $destino = exec("pwd")."/imagens/";
   if(!file_exists($destino.$name)){
    if(!@copy($tmp_name,$destino.$name)){
     db_msgbox("N�O FOI POSS�VEL EFETUAR UPLOAD. VERIFIQUE PERMISS�O DO DIRET�RIO $destino");
    }
   }
  }
  ?>
  <script>
   parent.document.formaba.a2.disabled = false;
   parent.document.formaba.a2.style.color = "black";
   top.corpo.iframe_a2.location.href='edu1_cursobase001.php?escola=<?=$ed18_i_codigo?>&ed18_c_nome=<?=$ed18_c_nome?>';
  </script>
  <?
  $clescola->erro(true,true);
 }
}
if(isset($alterar)){
 if($clescola->erro_status=="0"){
  $clescola->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clescola->erro_campo!=""){
   echo "<script> document.form1.".$clescola->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clescola->erro_campo.".focus();</script>";
  };
 }else{
  if($tmp_name!=""){
   ///enviar para pasta imagens
   $destino = exec("pwd")."/imagens/";
   if(!file_exists($destino.$name)){
    if(!@copy($tmp_name,$destino.$name)){
     db_msgbox("N�O FOI POSS�VEL EFETUAR UPLOAD. VERIFIQUE PERMISS�O DO DIRET�RIO $destino");
    }
   }
  }
  $clescola->erro(true,true);
 }
}
?>
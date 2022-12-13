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
  echo "<script>location.href='fis3_fandam005.php?db_opcao=3'</script>";
  exit;
}
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_vistorias_classe.php");
include("classes/db_vistoriaandam_classe.php");
include("classes/db_vistusuario_classe.php");
include("classes/db_fandam_classe.php");
include("classes/db_fandamusu_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrotulo        = new rotulocampo;
$clvistorias = new cl_vistorias;
$clvistoriaandam = new cl_vistoriaandam;
$clvistusuario   = new cl_vistusuario;
$clfandam        = new cl_fandam;
$clfandamusu     = new cl_fandamusu;
$clrotulo->label("y39_codandam");
$db_botao = false;
$db_opcao = 33;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  db_inicio_transacao();
  $db_opcao = 3;
  $db_botao = false;
  echo "<script>parent.iframe_fiscais.location.href='fis3_fandamusu001.php?primeira=1';</script>";
  echo "<script>parent.document.formaba.fiscais.disabled=true;</script>";
  $result = $clfandamusu->sql_record($clfandamusu->sql_query("","","*",""," y40_codandam = $y39_codandam"));
  if($clfandamusu->numrows > 0){
    $sqlerro=false;
    $numrows =  $clfandamusu->numrows;
    for($i=0;$i<$numrows;$i++){ 
      db_fieldsmemory($result,$i);
      $clfandamusu->excluir($y40_codandam,$y40_id_usuario);
      $erro=$clfandamusu->erro_msg;
      if($clfandamusu->erro_status==0){
        $sqlerro = true;
      }
    }
  }
  $clvistoriaandam->excluir($y70_codvist,$y39_codandam);
  $result = $clvistoriaandam->sql_record($clvistoriaandam->sql_query("",""," max(y68_codandam) as ultimo",""," y68_codandam <> $y39_codandam and y68_codvist = $y70_codvist")); 
  db_fieldsmemory($result,0);
  $clvistorias->y70_ultandam = $ultimo;
  $clvistorias->alterar($y70_codvist);
  $clfandam->excluir($y39_codandam);
  db_fim_transacao();
}

$result = $clvistorias->sql_record($clvistorias->sql_query("","*"," y70_ultandam desc","y70_instit = ".db_getsession('DB_instit') )); 
db_fieldsmemory($result,0);

$result = $clfandam->sql_record($clfandam->sql_query(""," max(y39_codandam)")); 
db_fieldsmemory($result,0);

$result = $clfandam->sql_record($clfandam->sql_query($max)); 
db_fieldsmemory($result,0);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#cccccc">
    <td>
      <strong><small>* VOCÊ SÓ PODE EXCLUIR O ÚLTIMO ANDAMENTO</small></strong>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC"> 
    <fieldset width="100%">
    <legend align="center">VISTORIA</legend>
	<?
        $clvistorias->rotulo->label();
        db_ancora(@$Ly70_codvist,"js_vist('".@$y70_codvist."');",$db_opcao);
        db_input('y70_codvist',20,$Iy70_codvist,true,'text',3,"");
	?>
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
<script>
function js_vist(codigo){
  js_OpenJanelaIframe('','db_iframe_vistorias','fis3_vistorias006.php?y70_codvist='+codigo,'Pesquisa',true);
}
</script>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir"){
  if($clfandam->erro_status=="0"){
    $clfandam->erro(true,false);
  }else{
    if($sqlerro==true){
      db_msgbox($erro);
    }else{
      $clfandam->erro(true,false);
      echo "<script>parent.document.formaba.fiscais.disabled=true;</script>";
      echo "<script>parent.iframe_fandam.location.href='fis3_fandam003.php?abas=1&y70_codvist=$y70_codvist';</script>";
    }
  };
};
if(@$sqlerro==false){
   $result = $clvistorias->sql_record($clvistorias->sql_query($y70_codvist,"vistorias.*,tipovistorias.y77_descricao",null,"y70_instit = ".db_getsession('DB_instit') )); 
   if($clvistorias->numrows > 0){
     db_fieldsmemory($result,0);
     $result = $clvistusuario->sql_record($clvistusuario->sql_query($y70_codvist)); 
     if($clvistusuario->numrows == 0){
       $db_opcao = 22;
       echo "<script>alert('Não existem fiscais cadastrados para esta vistoria!');</script>";
       echo "<script>document.form1.db_opcao.disabled=true;</script>";
       exit;
       $db_botao = false;
     }
   }else{
     echo "<script>alert('Você só pode excluir o último andamento');</script>";
   }
}
?>
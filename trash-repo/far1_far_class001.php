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
include("classes/db_far_class_classe.php");
include("classes/db_far_parametros_classe.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clfar_class = new cl_far_class;
$cldb_estrut = new cl_db_estrut;
$cl_far_parametros = new cl_far_parametros;
$db_opcao = 1;
$db_botao = true;
$result_fa02_i_codigo = $cl_far_parametros->sql_record($cl_far_parametros->sql_query_file(null,"fa02_i_dbestrutura"));
if($cl_far_parametros->numrows==0){
  db_msgbox('Por Favor Cadastre a Estrutura do Codigo de Classificacao no opcao Parametros');
}
/*if(isset($incluir)){
  db_inicio_transacao();
  $clfar_class->incluir($fa05_i_codigo);
  db_fim_transacao();
}*/
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if($sqlerro==false){
    $clfar_class->fa05_c_class = str_replace(".","",$fa05_c_class);
    $clfar_class->fa05_c_descr = $fa05_c_descr;
    $clfar_class->fa05_t_obs = $fa05_t_obs;
    $clfar_class->fa05_c_tipo = "$fa05_c_tipo";    
    $clfar_class->incluir($fa05_i_codigo);
    if($clfar_class->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clfar_class->erro_msg;
    db_fim_transacao($sqlerro);
    if($sqlerro == false){
      $fa05_i_codigo = "";
      $fa05_c_class = "";	
      $fa05_c_descr = "";
      $fa05_t_obs = "";
	  $fa05_c_tipo = "";
    }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
	<br>
    <center>
	<?
  if($cl_far_parametros->numrows>0){
   	include("forms/db_frmfar_class.php");
  }
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
<script>
js_tabulacaoforms("form1","fa05_c_class",true,1,"fa05_c_class",true);
</script>
<?
if(isset($incluir)){
  if($clfar_class->erro_status=="0"){
    $clfar_class->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clfar_class->erro_campo!=""){
      echo "<script> document.form1.".$clfar_class->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clfar_class->erro_campo.".focus();</script>";
    }
  }else{
    $clfar_class->erro(true,true);
  }
}
?>
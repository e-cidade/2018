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
include("classes/db_dialetivo_classe.php");
include("classes/db_diasemana_classe.php");
include("classes/db_rechumanohoradisp_classe.php");
include("classes/db_regenciahorario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cldialetivo = new cl_dialetivo;
$cldiasemana = new cl_diasemana;
$clregenciahorario = new cl_regenciahorario;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$db_opcao = 1;
$db_botao = true;
$ed04_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome = db_getsession("DB_nomedepto");
if(isset($gravar)){
 $result = $cldiasemana->sql_record($cldiasemana->sql_query("","ed32_i_codigo","ed32_i_codigo",""));
 for($x=0;$x<$cldiasemana->numrows;$x++){
  db_fieldsmemory($result,$x);
  $ed04_i_codigo = "";
  $codigo = $ed32_i_codigo;
  if(@$_POST[$codigo] == "ativo"){
   $letivo = "S";
  } else {
   $letivo = "N";
  }
  $sql1 = $cldialetivo->sql_query_file("","ed04_i_codigo",""," ed04_i_escola = $ed04_i_escola AND ed04_i_diasemana = $codigo");
  $result1 = $cldialetivo->sql_record($sql1);
  db_inicio_transacao();
  if($cldialetivo->numrows==0){
   $cldialetivo->ed04_c_letivo = $letivo;
   $cldialetivo->ed04_i_diasemana = $codigo;
   $cldialetivo->ed04_i_escola = $ed04_i_escola;
   $cldialetivo->incluir($ed04_i_codigo);
  }else{
   db_fieldsmemory($result1,0);
   $cldialetivo->ed04_c_letivo = $letivo;
   $cldialetivo->ed04_i_codigo = $ed04_i_codigo;
   $cldialetivo->alterar($ed04_i_codigo);
  }
  db_fim_transacao();
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
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Dias Letivos na Escola</b></legend>
    <?include("forms/db_frmdialetivo.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<?
if(isset($gravar)){
 $cldialetivo->erro(true,true);
}
?>
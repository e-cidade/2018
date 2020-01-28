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
include("classes/db_unidades_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clunidades = new cl_unidades;
$db_opcao = 22;
$db_opcao1 = 3;
$db_botao = false;
if(isset($alterar)){
 db_inicio_transacao();
 $db_opcao = 2;
 $clunidades->alterar($sd02_i_codigo);
 db_fim_transacao();
}else if(isset($chavepesquisa)){
 $db_opcao = 2;
 //echo $clunidades->sql_query($chavepesquisa,"db_depart.*,unidades.*,cgm.*,diretorcgm.z01_nome as diretor");
 $sSql   = $clunidades->sql_query($chavepesquisa, "db_depart.*, unidades.*, cgm.*, sau_distritosanitario.*,".
                                  " diretorcgm.z01_nome as diretor"
                                 );
 $result = $clunidades->sql_record($sSql);
 db_fieldsmemory($result,0);
 $db_botao = true;
 ?>
 <script>
  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a3.disabled = false;
  parent.document.formaba.a4.disabled = false;
  parent.document.formaba.a5.disabled = false;
  top.corpo.iframe_a2.location.href='sau1_unidadescaracter001.php?chavepesquisa=<?=$sd02_i_codigo?>';
  top.corpo.iframe_a3.location.href='sau1_unidadesconvvig001.php?chavepesquisa=<?=$sd02_i_codigo?>';
  top.corpo.iframe_a4.location.href='sau1_unidadeservicos001.php?chavepesquisa=<?=$sd02_i_codigo?>';
  top.corpo.iframe_a5.location.href='sau1_upsparalisada001.php?chavepesquisa=<?=$sd02_i_codigo?>';
 </script>
 <?
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
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
    <?include("forms/db_frmunidades.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd02_v_cnes",true,1,"sd02_v_cnes",true);
</script>
<?
/*if(isset($chavepesquisa)){
 ?><script>iframe_dados.location.href = "sau1_unidades004.php?chavepesquisa=<?=$sd02_i_numcgm?>";</script><?
}*/
if(isset($alterar)){
 if($clunidades->erro_status=="0"){
  $clunidades->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clunidades->erro_campo!=""){
   echo "<script> document.form1.".$clunidades->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clunidades->erro_campo.".focus();</script>";
  }
 }else{
  $clunidades->erro(true,false);
  db_redireciona("sau1_unidades002.php?chavepesquisa=$sd02_i_codigo");
 }
}
if($db_opcao==22){
 echo "<script>document.form1.pesquisar.click();</script>";
}
?>
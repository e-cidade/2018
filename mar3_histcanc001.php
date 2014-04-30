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
include("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br>
<form name="form1" method="post">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td valign="top">
   <table align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
     <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
     <td nowrap><b>Escolha o período:</b></td>
     <td nowrap>&nbsp;&nbsp;&nbsp;<b>Tipo:</b></td>
    </tr>
    <tr>
     <td>
      De:
      <?db_inputdata('data_ini',@$ma01_d_data_dia,@$ma01_d_data_mes,@$ma01_d_data_ano,true,'text',1,"")?>
      Até:
      <?db_inputdata('data_fim',@$ma01_d_data_dia,@$ma01_d_data_mes,@$ma01_d_data_ano,true,'text',1,"")?>
     </td>
     <td>
      &nbsp;&nbsp;&nbsp;
      <?
      $tipo = array("Todos"=>"Todos","Cancelamentos"=>"Cancelamentos","Reativações"=>"Reativações");
      db_select("escolha",$tipo,true,2);
      ?>
     </td>
    </tr>
    <tr>
     <td colspan="2" align="center">
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
     </td>
    </tr>
   </table>
   <table width="100%">
    <tr height="350">
     <td align="center">
      <iframe src="mar3_histcanc002.php" name="framemarca" id="framemarca" width="100%" height="100%"></iframe>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisa(){
 elementos = document.form1.elements.length;
 branco = "";
 for(i=0;i<elementos;i++){
  if(document.form1[i].value==""){
   alert("Campo não informado");
   document.form1[i].focus();
   document.form1[i].style.backgroundColor="#99A9AE";
   branco = "sim";
   break;
  }
 }
 if(branco==""){
  framemarca.location.href="mar3_histcanc002.php?escolha="+document.form1.escolha.value+"&data_ini="+document.form1.data_ini_ano.value+"-"+document.form1.data_ini_mes.value+"-"+document.form1.data_ini_dia.value+"&data_fim="+document.form1.data_fim_ano.value+"-"+document.form1.data_fim_mes.value+"-"+document.form1.data_fim_dia.value;
 }
}
</script>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
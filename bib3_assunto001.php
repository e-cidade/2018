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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgm_classe.php");
include("classes/db_tipoitem_classe.php");
include("classes/db_classiliteraria_classe.php");
$cltipoitem = new cl_tipoitem;
$clclassiliteraria = new cl_classiliteraria;
$clcgm = new cl_cgm;
$clcgm->rotulo->label("z01_nome");
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
<?if(!isset($pop)){?>
 <br>
<?}?>
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<form name="form1" method="post">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td valign="top">
   <br>
   <fieldset style="width:95%"><legend><b>Consulta de Acervo por Assunto</b></legend>
   <table border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td nowrap>
      <b>Digite o(s) termo(s) para consulta:</b>
      <?db_input("z01_nome",40,$Iz01_nome,true,"text",4,"");?>
      <input name="pesquisar" type="submit" value="Pesquisar">
     </td>
    </tr>
    <tr>
     <td nowrap>
      <b>Filtrar por Tipo:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <!--<input type="radio" name="filtro" value="" <?=@$filtro==""?"checked":""?>>TODOS-->
      <?
     $result = $cltipoitem->sql_record($cltipoitem->sql_query("","*","",""));
	  db_selectrecord("bi05_nome",$result,"","","","","","0 - TODAS","",1);
	  ?>
      <?if(isset($pop)){?>
       <input type="hidden" name="pop" value="">
      <?}?>
     </td>
    </tr>
    <tr>
     <td nowrap>
      <b>Filtrar por Classe:</b>
      <!--<input type="radio" name="filtro2" value="" <?=@$filtro2==""?"checked":""?>>TODAS-->
      <?
      $result = $clclassiliteraria->sql_record($clclassiliteraria->sql_query("","*","",""));
	  db_selectrecord("bi03_classificacao",$result,"","","","","","0 - TODAS","",1);
	  ?>
      <?if(isset($pop)){?>
       <input type="hidden" name="pop" value="">
      <?}?>
     </td>
    </tr>
   </table>
   <table width="100%">
    <tr>
     <td align="center" colspan="3">
      <?
       include ("bib3_assunto002.php");
      ?>
     </td>
    </tr>
   </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
<script>
document.form1.z01_nome.focus();
</script>
<?
if(!isset($pop)){
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
</body>
</html>
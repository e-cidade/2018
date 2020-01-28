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
include("classes/db_tipoitem_classe.php");
db_postmemory($HTTP_POST_VARS);
$cltipo = new cl_tipoitem;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<center>
<table width="100%" align="center">
 <tr>
  <td>
   <br>
   <fieldset width="95%"><legend><b>Relatório de Baixas</b></legend>
   <table  align="center">
    <form name="form1" method="post" action="">
    <tr>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
    </tr>
    <tr>
     <td align="left" nowrap title="Período">
     <strong>Período:&nbsp;&nbsp;</strong>
     </td>
     <td>
      <?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
      Até
      <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>
     </td>
    </tr>
    <tr>
     <td align="left" nowrap title="Período">
     <strong>Filtro:&nbsp;&nbsp;</strong>
     </td>
     <td>
      <?
      $result_tipo = $cltipo->sql_record($cltipo->sql_query());
      db_selectrecord("tipo",$result_tipo,true,2,"","","",1,"",1);
      ?>
     </td>
    </tr>
    <tr>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
    </tr>
    <tr>
     <td colspan="2" align = "center">
      <input  name="emite" id="emite" type="button" value="Processar" onclick="js_emite();" >
     </td>
    </tr>
    </form>
   </table>
   </fieldset>
  </td>
 </tr>
</table>
</center>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_emite(){
 obj = document.form1;
 count=0;
 if((obj.data1_dia.value !='') && (obj.data1_mes.value !='') && (obj.data1_ano.value !='') && (obj.data1_dia.value !='') && (obj.data2_mes.value !='') && (obj.data2_ano.value !='')){
  query ="data1="+obj.data1_ano.value+"-"+obj.data1_mes.value+"-"+obj.data1_dia.value+"&data2="+obj.data2_ano.value+"-"+obj.data2_mes.value+"-"+obj.data2_dia.value;
  count=1;
 }
 if(count==0 ){
  alert("Preencha os Campos Corretamente!");
 }else{
  jan = window.open('bib2_baixa002.php?'+query+'&filtro='+obj.tipo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
 }
}
</script>
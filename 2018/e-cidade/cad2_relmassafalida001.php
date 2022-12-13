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
include("classes/db_iptubase_classe.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$db_opcao=1;
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="430" width="790" border="1" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr> 
  <td align="center" valign="top" bgcolor="#cccccc">     
  <form name="form1" method="post" action="cad2_relmassafalida002.php" target='rel' >
  <input type="hidden" name="tipo" value="<?=@$tipo?>">
   <table border="0" cellspacing="0" cellpadding="0">
   <br>
        <tr>
          <td nowrap title="Data de inclusão da Massa Falida" colspan="4">
	    <b>Data de inclusão:</b>
<?
db_inputdata('dtini',"","","",true,'text',$db_opcao,"")
?>
	  <b> À </b>
<?
db_inputdata('dtfim',"","","",true,'text',$db_opcao,"")
?>

          </td>
        </tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
    <tr>
    <td nowrap width="50"> 
      <fieldset>
      <legend><strong>Ordem: </strong></legend>
      <select name="ordem">
        <option value="j01_matric">Matrícula</option>
        <option value="z01_nome">Nome</option>
      </select>
      </fieldset>
    </td>
    <td nowrap width="50"> 
      <fieldset>
      <legend><strong>Tipo: </strong></legend>
      <select name="resumido">
        <option value="t">Resumido</option>
        <option value="f" selected>Completo</option>
      </select>
      </fieldset>
    </td>
    <td nowrap width="50"> 
      <fieldset>
      <legend><strong>Modo: </strong></legend>
      <select name="modo">
        <option value="asc">Ascendente</option>
        <option value="desc">Descendente</option>
      </select>
      </fieldset>
    </td>
  </tr>

<script>
function js_testacamp(){
  jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
  document.form1.submit();
}   
</script>
     <tr>
       <td colspan="4" align="center">
     <br>
	   <input type="button" name="relatorio" value="relatório" onclick="return js_testacamp();" >
       </td>   	 
     </tr>	 
    </table> 	 
  </form>
  </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe3','func_massafalida.php?funcao_js=parent.js_mostramatri|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}
</script>
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
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clrotulo->label("r70_codigo");
$clrotulo->label("r70_descr");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
include("dbforms/db_classesgenericas.php");
$geraform = new cl_formulario_rel_pes;

$geraform->lo1nome = "r70_codigo";              // NOME DO CAMPO DA LOTA플O INICIAL
$geraform->usalota = true;                      // PERMITIR SELE플O DE LOTA합ES
$geraform->unilota = true;                     // PERMITIR SELE플O DE LOTA합ES
$geraform->manomes = false;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="if(document.form1.r70_codigo)document.form1.r70_codigo.focus();">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<BR>
<table border="0" cellspacing="0" cellpadding="0">
  <form name="form1" method="post">
  <tr> 
   <?
   $geraform->gera_form();
   ?>
  </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
  <tr> 
    <td colspan="2" align="center">
      <input type="button" value="Consultar" name="pesquisar" onclick="js_abrejan();">
    </td>
  </tr>
  </form>
</table>
</center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_abrejan(){
  qry = "";
  rog = "?";
  if(document.form1.r70_codigo.value!=""){
    qry = rog+"lotacao="+document.form1.r70_codigo.value;
  }
  location.href = 'pes3_consrhlotacao002.php'+qry;
}
function js_pesquisarlotacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlota','func_rhlota.php?funcao_js=parent.js_mostralotacao1|r70_codigo|r70_descr','Pesquisa',true);
  }else{
     if(document.form1.r70_codigo.value != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_rhlota','func_rhlota.php?pesquisa_chave='+document.form1.r70_codigo.value+'&funcao_js=parent.js_mostralotacao','Pesquisa',false);
     }else{
       document.form1.r70_descr.value = ''; 
     }
  }
}
function js_mostralotacao(chave,erro){
  document.form1.r70_descr.value  = chave;
  if(erro==true){
    document.form1.r70_codigo.value = '';
    document.form1.r70_codigo.focus();
  }
}
function js_mostralotacao1(chave1,chave2){
  document.form1.r70_codigo.value = chave1;
  document.form1.r70_descr.value  = chave2;
  db_iframe_rhlota.hide();
}
</script>
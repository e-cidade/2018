<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_solicita_classe.php");

$clsolicita = new cl_solicita;
$clrotulo = new rotulocampo;
$clsolicita->rotulo->label();
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" onload="">
<center>
<form name="form1" method="post" action="com3_conssolic002.php">
  <table border='0'>
    <tr height="20px">
      <td >&nbsp;</td>
      <td >&nbsp;</td>
    </tr>
    <tr> 
      <td align="left" nowrap title="<?=$Tpc10_numero?>"> <? db_ancora(@$Lpc10_numero,"js_pesquisapc10_numero(true);",1);?></td>
      <td align="left" nowrap>
      <?
      db_input('pc10_numero',8,$Ipc10_numero,true,"text",1,"onchange='js_pesquisapc10_numero(false);'");
      ?>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input name="enviar" type="button" id="enviar" value="Enviar dados" onclick='js_verifica();'>
      </td>
    </tr>
  </table>
</form>
</center>
</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>
function js_verifica(){
  if(document.form1.pc10_numero.value==''){
    alert("Informe o número da solicitação.");
  }else{
    document.form1.submit();
  }
}
function js_pesquisapc10_numero(mostra){
  qry = "&nada=true";
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrapcorcamitem1|pc10_numero'+qry,'Pesquisa',true);
  }else{
    if(document.form1.pc10_numero.value!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrapcorcamitem&pesquisa_chave='+document.form1.pc10_numero.value+qry,'Pesquisa',false);
    }else{
      document.form1.pc10_numero.value = "";
    }
  }
}
function js_mostrapcorcamitem1(chave1,chave2){
  document.form1.pc10_numero.value = chave1;
  db_iframe_solicita.hide();
}
function js_mostrapcorcamitem(chave1,erro){
  if(erro==true){
    document.form1.pc10_numero.value = "";
  }
}
</script>
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
include("classes/db_pcsubgrupo_classe.php");

db_postmemory($HTTP_POST_VARS);

$clpcsubgrupo = new cl_pcsubgrupo;
$clpcsubgrupo->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
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
<table align="center" border="0" cellpadding="2" cellspacing="2">
  <form name="form1" method="post">
    <tr><td nowrap>&nbsp;</td></tr>
    <tr> 
      <td nowrap><b>Ordem:</b></td>
      <td nowrap><?
         $matriz = array("A"=>"Alfabética","N"=>"Numérica","D"=>"Data");
         db_select("ordem",$matriz,true,4);
      ?></td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tpc04_codsubgrupo?>"><?
       db_ancora(@$Lpc04_codsubgrupo,"js_pesquisapc04_codsubgrupo(true);",4);
    ?></td>
      <td nowrap> 
    <?
       db_input("pc04_codsubgrupo",6,$Ipc04_codsubgrupo,true,"text",4," onchange='js_pesquisapc04_codsubgrupo(false);'");
       db_input("pc04_descrsubgrupo",40,$Ipc04_descrsubgrupo,true,"text",3,"")
    ?>
      </td>
    </tr>
    <tr>
      <td nowrap colspan="2" align="center"><input type="submit" value="Imprimir" onClick="js_enviardados();"></td>
    </tr>
  </form>    
</table>
<script>
function js_enviardados(){
  var query = "";

  query = "ordem="+document.form1.ordem.value;
  if (document.form1.pc04_codsubgrupo.value != ""){
       query += "&pc04_codsubgrupo="+document.form1.pc04_codsubgrupo.value;
  }

  jan = window.open('com2_relcertforne002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisapc04_codsubgrupo(mostra){
  if(mostra==true){
      js_OpenJanelaIframe("top.corpo","db_iframe_pcsubgrupo","func_pcsubgrupo.php?funcao_js=parent.js_mostrapcsubgrupo1|pc04_codsubgrupo|pc04_descrsubgrupo","Pesquisa",true);
  }else{
     if(document.form1.pc04_codsubgrupo.value != ""){ 
         js_OpenJanelaIframe("top.corpo","db_iframe_pcsubgrupo","func_pcsubgrupo.php?pesquisa_chave="+document.form1.pc04_codsubgrupo.value+"&funcao_js=parent.js_mostrapcsubgrupo","Pesquisa",false);
     }else{
         document.form1.pc04_descrsubgrupo.value = ""; 
     }
  }
}
function js_mostrapcsubgrupo(chave,erro){
  document.form1.pc04_descrsubgrupo.value = chave; 
  if(erro==true){ 
      document.form1.pc04_codsubgrupo.focus(); 
      document.form1.pc04_codsubgrupo.value = ''; 
  }
}
function js_mostrapcsubgrupo1(chave1,chave2){
  document.form1.pc04_codsubgrupo.value   = chave1;
  document.form1.pc04_descrsubgrupo.value = chave2;
  db_iframe_pcsubgrupo.hide();
}
</script>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
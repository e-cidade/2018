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
$db_opcao = 1;
$db_botao = true;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clrotulo = new rotulocampo;
$clrotulo->label("d02_contri");
$clrotulo->label("j14_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_matricontri(){
  db_iframe.jan.location.href = 'func_iptubasealt.php?funcao_js=parent.js_matricontri1|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_matricontri1(matric){
  js_OpenJanelaIframe('top.corpo','db_iframe','con1_contrib006.php?j01_matric='+matric,'Pesquisa',true);
}
function js_matricontri2(matri,refant,nome,setor,quadra,lote,zona,total,desconto,idbql,testada){
  matriculas.js_incluirlinha(matri,refant,nome,setor,quadra,lote,zona,total,desconto,idbql,testada);
  db_iframe.hide();
}
function js_pesquisa_lotes(){
  var expr = new RegExp("[^0-9\.]+");
  var contri = document.form1.d02_contri.value;
  if(contri == ""){
     alert('Escolha a contribuição.');
  }else if(contri.match(expr)) {
          alert("Este campo deve preenchido somente com números decimais!");
          contri = document.form1.d02_contri.focus();;
  }else{ 
      document.form1.seleciona.disabled=true; 
      document.getElementById('matriculas').src = "con1_contrib005.php?contri="+contri;
      document.form1.confirma.style.visibility='visible';
      document.form1.matricontri.style.visibility='visible';
      document.form1.contri.value=contri;
  }
}
  </script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table width="790" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
  <form name="form1" method="post" action="">
  <center>
  <table border="0">
  <tr>
    <td>
    <input name="contri" type="hidden">
    <input name="testada" type="hidden">
    </td>
  </tr>
    <tr>
      <td nowrap title="<?=@$Td02_contri?>">
      <?
        db_ancora(@$Ld02_contri,"js_contri(true);",$db_opcao);
      ?>
      </td>
      <td> 
  <?
  db_input('d02_contri',7,$Id02_contri,true,'text',$db_opcao," onchange='js_contri(false);'");
  db_input('j14_nome',40,$Ij14_nome,true,'text',3);
  ?>
      </td>
    </tr>
  </table>
  <table>
  <tr>
  <td colspan="2">
  <br>
  <iframe name="matriculas" id="matriculas" src="" width="750" height="340">
  </iframe>
  </td>
  </tr>
  </table>
  <input name="seleciona" type="button" onclick="js_pesquisa_lotes();" id="Seleciona" value="Seleciona Matrículas" >
  <input name="confirma" type="button" style='visibility:hidden' onclick='matriculas.js_confirma()' id="confirma" value="Confirma Matrículas" >
  <input name="matricontri" type="button" style='visibility:hidden' onclick='js_matricontri()' id="matricontri" value="Outras Matriculas" >
  </center>
</form>
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
function js_contri(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalrua.php?funcao_js=parent.js_mostracontri1|d02_contri|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_editalrua.php?pesquisa_chave='+document.form1.d02_contri.value+'&funcao_js=parent.js_mostracontri','Pesquisa',false);
  }  
}
function js_mostracontri(chave,erro){
  if(erro==true){ 
    document.form1.d02_contri.focus(); 
    document.form1.j14_nome.value = "";
    document.form1.d02_contri.value = "";
  }else{
     document.form1.seleciona.disabled=false; 
     document.form1.j14_nome.value = chave;
  }  
}
function js_mostracontri1(chave1,chave2){
  document.form1.d02_contri.value = chave1;
  document.form1.j14_nome.value = chave2;
  document.form1.seleciona.disabled=false; 
  db_iframe.hide();
}
</script>
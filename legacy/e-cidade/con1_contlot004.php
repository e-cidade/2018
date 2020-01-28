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
include("classes/db_projmelhorias_classe.php");
include("classes/db_contlot_classe.php");
include("classes/db_contlotv_classe.php");
include("classes/db_lote_classe.php");
include("classes/db_editalserv_classe.php");
include("dbforms/db_funcoes.php");
$clprojmelhorias = new cl_projmelhorias;
$cllote = new cl_lote;
$clcontlot = new cl_contlot;
$clcontlotv = new cl_contlotv;
$cleditalserv = new cl_editalserv;
$db_opcao = 1;
$db_botao = true;
db_postmemory($HTTP_POST_VARS);

$clprojmelhorias->rotulo->label();
$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("j14_nome");
$clrotulo->label("d42_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("d02_contri");
$clrotulo->label("d40_trecho");
$clrotulo->label("d40_codigo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_label(liga,evt,descr,quant,vlr){
  evt= (evt)?evt:(window.event)?window.event:""; 
  if(liga){
     document.getElementById('descr').innerHTML=descr;
     document.getElementById('quant').innerHTML=quant;
     document.getElementById('vlr').innerHTML=vlr;
   //  document.getElementById('divlabel').style.left=evt.clientX;
   //  document.getElementById('divlabel').style.top=evt.clientY;
     document.getElementById('divlabel').style.visibility='visible';
  }else{
    document.getElementById('divlabel').style.visibility='hidden';
  }  
}
function js_porface(){
  document.form1.confirma.style.visibility='hidden';
  document.form1.lotecontri.style.visibility='hidden';
  document.form1.conface.style.visibility='visible';
  
}
function js_nocontri(){
  document.form1.lotecontri.style.visibility='hidden';
  document.form1.conface.style.visibility='hidden';
  document.form1.confirma.style.visibility='hidden';
  alert("Contribuição inválida.");
}
function js_lotecontri(){
  js_OpenJanelaIframe('top.corpo','db_iframe','func_lote.php?funcao_js=parent.js_lotecontri1|j34_idbql','Pesquisa',true);
}
function js_lotecontri1(lote){
  contri=document.form1.contri.value;
  js_OpenJanelaIframe('top.corpo','db_iframe','con1_contlot006.php?j34_idbql='+lote+'&d02_contri='+contri,'Pesquisa',true);
}
  function js_lotecontri2(idbql,setor,quadra,lote,zona,dad,testadas,testada){
  db_iframe.hide();
  matriculas.js_incluirlinha(idbql,setor,quadra,lote,zona,dad,testadas,testada);
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
      document.getElementById('matriculas').src = "con1_contlot005.php?contri="+contri;
      document.form1.confirma.style.visibility='visible';
      document.form1.lotecontri.style.visibility='visible';
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
<form name="form1" method="post" action="">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <div align="left" id="divlabel" style="position:absolute; z-index:1; top:25; left:600; visibility: hidden; border: 1px none #000000; background-color: #CCCCCC; background-color:#999999; font-weight:bold;">
                <span id="descr"></span><br> 
          Quant: <span id="quant"></span><br> 
        Valor R$:<span id="vlr"></span><br> 
      </div>
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
          <td align="" width="40%">  
            <div id='lab' style='visibility:hidden' ></div>
          </td>	
        </tr>
      </table>
      <table>
        <tr>
          <td colspan="2">
            <br>
            <iframe name="matriculas" id="matriculas" src="" width="750" height="330">
            </iframe>
          </td>
        </tr>
      </table>
      <input name="seleciona" type="button" onclick="js_pesquisa_lotes();" id="SEleciona" value="Seleciona Lotes" >
      <input name="confirma" type="button" style='visibility:hidden' onclick='matriculas.js_confirma()' id="confirma" value="Confirma Lotes" >
      <input name="lotecontri" type="button" style='visibility:hidden' onclick='js_lotecontri()' id="lotecontri" value="Outros Lotes" >
      <input name="conface" type="button" style='visibility:hidden' onclick='matriculas.js_conface()' id="conface" value="Confirma Faces" >
    </center>
    </td>
  </tr>
</table>
</form>
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
    document.form1.d02_contri.value=""; 
    document.form1.j14_nome.value=""; 
  }else{
      document.form1.seleciona.disabled=false;
      document.form1.j14_nome.value = chave;
  }  
}
function js_mostracontri1(chave1,chave2){
  document.form1.seleciona.disabled=false;
  document.form1.d02_contri.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
</script>
<?
if($clcontlot->erro_status=="0"){
  $clcontlot->erro(true,false);
  $db_botao=true;
  if($clcontlot->erro_campo!=""){
    echo "<script> document.form1.".$clcontlot->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clcontlot->erro_campo.".focus();</script>";
  }
}else{
    $clcontlot->erro(true,false);
}
?>
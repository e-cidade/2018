<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_lote_classe.php");
require_once("libs/db_app.utils.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$cllote = new cl_lote;
$clrotulo = new rotulocampo;
$clrotulo->label("j01_matric");
$clrotulo->label("j34_setor");
$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$db_opcao=1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
 db_app::load('estilos.css');
 db_app::load('scripts.js, prototype.js, strings.js, DBViewPesquisaSetorQuadraLote.js, dbcomboBox.widget.js');
?>
</head>
<body bgcolor=#CCCCCC>
<form name="form1" method="post" action="cad3_consultaitbinew001.php">
<center>
<fieldset style="margin: 30px auto 10px; width: 600px;">
<legend><strong>Consulta ITBI</strong></legend>
<table>
  <tr>
    <td>
      <strong>Guia:</strong>
    </td>
    <td> 
     <?
       db_input('guia',10,"",true,'text',1,"");
     ?>
    </td>
  </tr>
  <tr>
    <td title="Pesquisa por nome do adquirente">
      
      <?
       db_ancora("<strong>Adquirente</strong>",' js_cgm(true); ',1);
      ?>
    </td>
    <td> 
     <?

       db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'","z_numcgm");
       db_input('z01_nome',36,0,true,'text',3,"","adquirente");

       //db_input('adquirente',50,"",true,'text',1,"");
     ?>
    </td>
  </tr>
  <tr>
    <td title="Pesquisa por nome do transmitente">
      <strong>Transmitente: </strong>
    </td>
    <td> 
     <?
       db_input('transmitente',48,"",true,'text',1,"");
     ?>
    </td>
  </tr>
  <tr>
    <td title="Data inicial">
      <strong>Data inicial: </strong>
    </td>
    <td>
      <?
        db_inputdata("dataini",@$dataini_dia,@$dataini_mes,@$dataini_ano,true,'text',1);
      ?>
    </td>
  </tr>
  <tr>
    <td title="Data final">
      <strong>Data final: </strong>
    </td>
    <td>
      <?
        db_inputdata("datafim",@$datafim_dia,@$datafim_mes,@$datafim_ano,true,'text',1);
      ?>
    </td>
  </tr>


  <tr>
    <td title="Pesquisa por tipo de ITBI">
      <strong>Tipo de ITBI: </strong>
    </td>
    <td> 
     <?
      $x = array("u"=>"Urbana","r"=>"Rural");
      db_select('tipo',$x,true,1,"onClick='js_controlatipo(this.value);'");
     ?>
    </td>
  </tr>
</table>
<div id="dadosurbana">
<BR>
<fieldset>

<legend><strong>Dados ITBI Urbano</strong></legend>

<table>
  <tr>
    <td title="<?=@$Tj01_matric?>">
       <?
       db_ancora(@$Lj01_matric,"js_matric(true);",1);
       ?>
    </td>
    <td> 
     <?
       db_input('j01_matric',10,$Ij01_matric,true,'text',1," onChange='js_matric(false)'");
       db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td title="<?=@$Tj34_setor?>">
       <?=$Lj34_setor?>
    </td>
    <td> 
    <?
     db_input('j34_setor',10,$Ij34_setor,true,'text',$db_opcao);
     ?>
     <?=$Lj34_quadra?>
     <?
       db_input('j34_quadra',10,$Ij34_quadra,true,'text',$db_opcao);
     ?>
     
     <?=$Lj34_lote?>
     <?
       db_input('j34_lote',10,$Ij34_lote,true,'text',$db_opcao);
     ?>
    </td>
  </tr>
  <tr> 
    <td title="<?=@$Tj14_nome?>"> 
       <?
       db_ancora(@$Lj14_nome,"js_ruas(true);",$db_opcao);
       ?>
    </td>
    <td> 
      <?
       	db_input('j14_codigo',10,$Ij14_codigo,true,'text',$db_opcao," onChange='js_ruas(false)'");
      ?>
      <?
       	db_input('j14_nome',40,$Ij14_nome,true,'text',3);
      ?>
    </td>
  </tr>
  
	<tr> 
		<td colspan="2" align="center"> 
			<div id="pesquisa"></div>
		</td>
	</tr>	  
</table>
</fieldset>
</div>
</fieldset>
<input name="consultar" type="button" value="Consultar" onClick="js_consultaitbi();">

<script>

function js_controlatipo(valor){
   if(valor == "u"){
      document.getElementById('dadosurbana').style.display = '';
   }else{
      document.getElementById('dadosurbana').style.display = 'none';
   }  
  
}

function js_limpacampos(){
	document.form1.guia.value  		  = '';
    document.form1.j01_matric.value   = ''; 
    document.form1.z01_nome.value     = ''; 
    document.form1.j34_setor.value    = ''; 
    document.form1.j34_quadra.value   = ''; 
    document.form1.j14_codigo.value   = ''; 
    document.form1.j14_nome.value     = ''; 
    document.form1.z_numcgm.value     = '';
    document.form1.adquirente.value   = '';
    document.form1.transmitente.value = '';
    document.form1.dataini_ano.value  = '';
    document.form1.dataini_mes.value  = '';
    document.form1.dataini_dia.value  = '';
    document.form1.datafim_ano.value  = '';
    document.form1.datafim_mes.value  = '';
    document.form1.datafim_dia.value  = '';
}
onLoad = js_limpacampos();
</script>
  </table>
  </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_consultaitbi() {
  
  querystring = '';
  dtini = document.form1.dataini_ano.value+'-'+document.form1.dataini_mes.value+"-"+document.form1.dataini_dia.value;
  dtfim = document.form1.datafim_ano.value+'-'+document.form1.datafim_mes.value+"-"+document.form1.datafim_dia.value;
  if(document.form1.guia.value == '' && document.form1.j01_matric.value == '' && document.form1.j14_codigo.value == '' && document.form1.j34_setor.value == '' && document.form1.j34_quadra.value == '' && document.form1.j34_lote.value == '' && document.form1.adquirente.value == '' && document.form1.transmitente.value == '' && dtini == '' && dtfim == ''){
     alert('Preencha a pelo menos um dos campos para contiuar a pesquisa ');
  }else{
     if (document.form1.tipo.value == 'u'){
       querystring  = 'matric='+document.form1.j01_matric.value;
       querystring += '&setor='+document.form1.j34_setor.value;
       querystring += '&codrua='+document.form1.j14_codigo.value;
       querystring += '&quadra='+document.form1.j34_quadra.value;
       querystring += '&lote='+document.form1.j34_lote.value;
       querystring += '&setorloc='+document.form1.setor.value;
       querystring += '&quadraloc='+document.form1.quadra.value;
       querystring += '&loteloc='+document.form1.lote.value;
     }else{
       querystring  = 'matric=';
       querystring += '&setor=';
       querystring += '&codrua=';
       querystring += '&quadra=';
       querystring += '&lote=';
       querystring += '&setorloc=';
       querystring += '&quadraloc=';
       querystring += '&loteloc=';
     }
     querystring += '&adquirente='+document.form1.adquirente.value;
     querystring += '&transmitente='+document.form1.transmitente.value;
     querystring += '&tipo='+document.form1.tipo.value;
     if(dtini != '--'){
       querystring += '&dtini='+dtini;
     }else{
       querystring += '&dtini=';     
     }
     if(dtfim != '--'){
       querystring += '&dtfim='+dtfim;
     }else{
       querystring += '&dtfim=';       
     }
     if (document.form1.guia.value != ""){
       querystring += '&codguia='+document.form1.guia.value;
     }
     querystring += '&funcao_js=parent.js_abreconsulta|it01_guia';
     js_OpenJanelaIframe('','db_iframe_consultaitbi','cad3_consultaitbinew002.php?'+querystring,'Pesquisa',true,30,(screen.availWidth)-(screen.availWidth-50),screen.availWidth - 100);
     js_limpacampos();
  }
}
function js_abreconsulta(chave){
  js_OpenJanelaIframe('','db_iframe_consulta','itb4_consultaitbi001.php?it01_guia='+chave,'Pesquisa',true,30);
  db_iframe_consultaitbi.hide();
}
  function js_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matric','func_iptubaseitbi.php?funcao_js=parent.js_mostramatric1|j01_matric|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_matric','func_iptubaseitbi.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_mostramatric','Pesquisa',false);
  }
}

function js_cgm(mostra){
  var cgm=document.form1.z_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe2','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z_numcgm.value = chave1;
  document.form1.adquirente.value = chave2;
  db_iframe2.hide();
}

function js_mostracgm1(erro, nome) {

  if (erro) {
    document.form1.z_numcgm.value = '';
  }
  document.form1.adquirente.value = nome;
} 
function js_mostramatric(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}
function js_mostramatric1(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_matric.hide();
}
function js_setor(){
  js_OpenJanelaIframe('','db_iframe_setor','func_setor.php?funcao_js=parent.js_mostrasetor|j30_codi','Pesquisa',true,30);
}
function js_mostrasetor(chave1,chave2){
  document.form1.j34_setor.value = chave1;
  db_iframe_setor.hide();
}
function js_ruas(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruasitbi.php?rural=1&funcao_js=parent.js_preenchepesquisa|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruasitbi.php?rural=1&funcao_js=parent.js_preenchepesquisa1&pesquisa_chave='+document.form1.j14_codigo.value,'Pesquisa',false);
  }
}
function js_preenchepesquisa(chave,chave1){
  document.form1.j14_codigo.value = chave;
  document.form1.j14_nome.value = chave1;
  db_iframe_ruas.hide();
}
function js_preenchepesquisa1(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro == true){
    document.form1.j14_codigo.value = '';
    document.form1.j14_codigo.focus();
  }
  db_iframe_ruas.hide();
}
</script>
<?
if(isset($j01_matric) && $j01_matric != "" || 1==1){
//  echo "<script>js_OpenJanelaIframe('','db_iframe_itbi','cad3_consultaitbinew003.php?j01_matric=$j01_matric','Pesquisa',true,30);</script>";
}elseif(isset($j34_setor) && $j34_setor != ""){
//  echo "<script>js_OpenJanelaIframe('','db_iframe_consultaitbi','cad3_consultaitbinew002.php?j34_setor=$j34_setor&j14_codigo=$j14_codigo&j34_quadra=$j34_quadra&funcao_js=parent.js_abreconsulta|j01_matric','Pesquisa',true,30);</script>";
}
?>   

<script>
var oPesquisa = new DBViewPesquisaSetorQuadraLote('pesquisa', 'oPesquisa');
    oPesquisa.show();
    oPesquisa.appendForm();
<? 
	//echo "oPesquisa.setValues('{$setorCodigo}','{$quadra}','{$lote}');"; 
?>
</script>
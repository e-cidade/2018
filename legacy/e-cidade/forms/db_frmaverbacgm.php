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

//MODULO: cadastro
require_once("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$claverbacgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j75_codigo");
$clrotulo->label("z01_nome");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro==false ) ){     
     $j76_codigo = "";     
     $j76_numcgm = "";
     $z01_nome = "";
     $j76_tipo = "";
     $j76_principal = "";
   }
} 
 $result = $claverbacao->sql_record($claverbacao->sql_query_file($j76_averbacao)); 
   db_fieldsmemory($result,0);
   if ($j75_situacao==2){
   	$db_opcao = 3;
   	$db_botao = false;
   }
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?//=@$Tj76_codigo?>">
       <?//=@$Lj76_codigo?>
    </td>
    <td> 
<?
db_input('j76_codigo',6,$Ij76_codigo,true,'hidden',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj76_averbacao?>">
       <?
       db_ancora(@$Lj76_averbacao,"js_pesquisaj76_averbacao(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('j76_averbacao',6,$Ij76_averbacao,true,'text',3," onchange='js_pesquisaj76_averbacao(false);'")
?>       
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj76_numcgm?>">
       <?
       db_ancora(@$Lj76_numcgm,"js_pesquisaj76_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('j76_numcgm',10,$Ij76_numcgm,true,'text',$db_opcao," onchange='js_pesquisaj76_numcgm(false);'")
?>
       <?
db_input('z01_nome',50,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <!--
  <tr>
    <td nowrap title="<?=@$Tj76_tipo?>">
       <?=@$Lj76_tipo?>
    </td>
    <td> 
<?
$x = array('1'=>'Proprietario','2'=>'Promitente');
db_select('j76_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  -->
  <tr>
    <td nowrap title="<?=@$Tj76_principal?>">
       <?=@$Lj76_principal?>
    </td>
    <td> 
<?

$result_principal=$claverbacgm->sql_record($claverbacgm->sql_query_file(null,"*",null,"j76_averbacao=$j76_averbacao and j76_principal is true"));
if ($claverbacgm->numrows>0){
	$x = array("f"=>"Não");
}else{
	$x = array("t"=>"Sim","f"=>"Não");
}
db_select('j76_principal',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("j76_codigo"=>@$j76_codigo);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $claverbacgm->sql_query(null,"*",null,"j76_averbacao=$j76_averbacao");
	 $cliframe_alterar_excluir->campos  ="j76_codigo,j76_numcgm,z01_nome,j76_principal";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir(1);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisaj76_averbacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_averbacgm','db_iframe_averbacao','func_averbacao.php?funcao_js=parent.js_mostraaverbacao1|j75_codigo|j75_codigo','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.j76_averbacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_averbacgm','db_iframe_averbacao','func_averbacao.php?pesquisa_chave='+document.form1.j76_averbacao.value+'&funcao_js=parent.js_mostraaverbacao','Pesquisa',false);
     }else{
       document.form1.j75_codigo.value = ''; 
     }
  }
}
function js_mostraaverbacao(chave,erro){
  document.form1.j75_codigo.value = chave; 
  if(erro==true){ 
    document.form1.j76_averbacao.focus(); 
    document.form1.j76_averbacao.value = ''; 
  }
}
function js_mostraaverbacao1(chave1,chave2){
  document.form1.j76_averbacao.value = chave1;
  document.form1.j75_codigo.value = chave2;
  db_iframe_averbacao.hide();
}
function js_pesquisaj76_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_averbacgm','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome&testanome=1','Pesquisa');
  }else{
     if(document.form1.j76_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_averbacgm','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.j76_numcgm.value+'&funcao_js=parent.js_mostracgm&testanome=1','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.j76_numcgm.focus(); 
    document.form1.j76_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.j76_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
</script>
<?php
/**
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

$clautorec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y50_codauto");
$clrotulo->label("y59_codauto");
$clrotulo->label("y50_nome");
$clrotulo->label("k02_descr");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_receitas.location.href='fis1_autorec002.php?chavepesquisa=$y57_codauto&chavepesquisa1=$y57_receit&y59_codauto=$y59_codauto'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_receitas.location.href='fis1_autorec003.php?chavepesquisa=$y57_codauto&chavepesquisa1=$y57_receit&y59_codauto=$y59_codauto'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty57_codauto?>">
       <?
       db_ancora(@$Ly57_codauto,"js_pesquisay57_codauto(true);",3);
       ?>
    </td>
    <td>
      <?
      db_input('y57_codauto',10,$Iy57_codauto,true,'text',3," onchange='js_pesquisay57_codauto(false);'");
      echo "<script>document.form1.y57_codauto.value='$y59_codauto'</script>";
      db_input('y59_codauto',10,$Iy59_codauto,true,'hidden',3,"");
      ?>
       <?
db_input('y50_nome',40,$Iy50_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty57_receit?>">
       <?
       db_ancora(@$Ly57_receit,"js_pesquisay57_receit(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('y57_receit',10,$Iy57_receit,true,'text',$db_opcao," onchange='js_pesquisay57_receit(false);'");
if($db_opcao == 2){
  db_input('y57_receit',10,$Iy57_receit,true,'hidden',$db_opcao,"","y57_receit_old");
  echo "<script>document.form1.y57_receit_old.value = '$y57_receit'</script>";
}
?>
       <?
db_input('k02_descr',40,$Ik02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty57_descr?>">
       <?=@$Ly57_descr?>
    </td>
    <td>
<?
db_input('y57_descr',54,$Iy57_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr class="hide">
    <td nowrap title="<?=@$Ty57_valor?>">
       <?=@$Ly57_valor?>
    </td>
    <td>
<?php
db_input('y57_valor',10,$Iy57_valor,true,'hidden',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?
      if(($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33)){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_autorec001.php?y59_codauto=<?=$y59_codauto?>'">
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="top">
   <?
    $chavepri= array("y57_codauto"=>@$y57_codauto,"y57_receit"=>@$y57_receit);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y57_codauto,y57_receit,y57_descr";
    $cliframe_alterar_excluir->sql=$clautorec->sql_query("","","*",""," y57_codauto = $y59_codauto");
    $cliframe_alterar_excluir->legenda="Receitas do Auto de Infração";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum Registro Encontrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    $clautorec1 = new cl_autorec;
    $result = $clautorec1->sql_record($clautorec1->sql_query($y59_codauto));
   ?>

    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_setatabulacao(){
  js_tabulacaoforms("form1","y57_receit",true,1,"y57_receit",true);
}
function js_pesquisay57_codauto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?funcao_js=parent.js_mostraauto1|y50_codauto|y50_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?pesquisa_chave='+document.form1.y57_codauto.value+'&funcao_js=parent.js_mostraauto','Pesquisa',false);
  }
}
function js_mostraauto(chave,erro){
  document.form1.y50_nome.value = chave;
  if(erro==true){
    document.form1.y57_codauto.focus();
    document.form1.y57_codauto.value = '';
  }
}
function js_mostraauto1(chave1,chave2){
  document.form1.y57_codauto.value = chave1;
  document.form1.y50_nome.value = chave2;
  db_iframe_auto.hide();
}
function js_pesquisay57_receit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.y57_receit.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_descr.value = chave;
  if(erro==true){
    document.form1.y57_receit.focus();
    document.form1.y57_receit.value = '';
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.y57_receit.value = chave1;
  document.form1.k02_descr.value = chave2;
  db_iframe_tabrec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_autorec','func_autorec.php?funcao_js=parent.js_preenchepesquisa|y57_codauto|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_autorec.hide();
}
</script>
<?
if(isset($y59_codauto) && $y59_codauto != ""){
  echo "<script>js_pesquisay57_codauto(false)</script>";
}
?>
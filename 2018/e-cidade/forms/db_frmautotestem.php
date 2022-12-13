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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clautotestem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("y50_nome");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_testem.location.href='fis1_autotestem002.php?chavepesquisa=$y24_codauto&chavepesquisa1=$y24_numcgm'</script>";
}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_testem.location.href='fis1_autotestem003.php?chavepesquisa=$y24_codauto&chavepesquisa1=$y24_numcgm'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty24_codauto?>">
       <?
       db_ancora(@$Ly24_codauto,"js_pesquisay24_codauto(true);",3);
       ?>
    </td>
    <td>
<?
db_input('y24_codauto',10,$Iy24_codauto,true,'text',3," onchange='js_pesquisay24_codauto(false);'")
?>
       <?
db_input('y50_nome',40,$Iy50_nome,true,'text',3,'');
echo "<script>js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?pesquisa_chave=$y24_codauto&funcao_js=parent.js_mostraauto','Pesquisa',false);</script>";
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty24_numcgm?>">
       <?
       db_ancora(@$Ly24_numcgm,"js_pesquisay24_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('y24_numcgm',10,$Iy24_numcgm,true,'text',$db_opcao," onchange='js_pesquisay24_numcgm(false);'");
if($db_opcao == 2){
  db_input('y24_numcgm',10,$Iy24_numcgm,true,'hidden',$db_opcao," ","y24_numcgm_old");
  echo "<script>document.form1.y24_numcgm_old.value='$y24_numcgm'</script>";
}
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?
      if(($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33)){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_autotestem001.php?y24_codauto=<?=$y24_codauto?>'">
      <?
      }
      ?>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?
    $chavepri= array("y24_codauto"=>$y24_codauto,"y24_numcgm"=>@$y24_numcgm);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->campos="y24_codauto,y24_numcgm,z01_nome";
    $cliframe_alterar_excluir->sql=$clautotestem->sql_query("","","*",""," y24_codauto = $y24_codauto");
    $cliframe_alterar_excluir->legenda="TESTEMUNHAS DO AUTO DE INFRAÇÃO";
    $cliframe_alterar_excluir->msg_vazio ="<font size='1'>Nenhum registro encontrado!</font>";
    $cliframe_alterar_excluir->textocabec ="darkblue";
    $cliframe_alterar_excluir->textocorpo ="black";
    $cliframe_alterar_excluir->fundocabec ="#aacccc";
    $cliframe_alterar_excluir->fundocorpo ="#ccddcc";
    $cliframe_alterar_excluir->iframe_height ="170";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
   ?>
   </td>
 </tr>
  </table>
  </center>
</form>
<script>
function js_setatabulacao(){
  js_tabulacaoforms("form1","y24_numcgm",true,1,"y24_numcgm",true);
}
function js_pesquisay24_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.y24_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(x,chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.y24_numcgm.focus();
    document.form1.y24_numcgm.value = '';
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.y24_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisay24_codauto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_auto','func_auto.php?funcao_js=parent.js_mostraauto1|y50_codauto|y50_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_auto','func_auto.php?pesquisa_chave='+document.form1.y24_codauto.value+'&funcao_js=parent.js_mostraauto','Pesquisa',false);
  }
}
function js_mostraauto(chave,erro){
  document.form1.y50_nome.value = chave;
  if(erro==true){
    document.form1.y24_codauto.focus();
    document.form1.y24_codauto.value = '';
  }
}
function js_mostraauto1(chave1,chave2){
  document.form1.y24_codauto.value = chave1;
  document.form1.y50_nome.value = chave2;
  db_iframe_auto.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_autotestem','func_autotestem.php?funcao_js=parent.js_preenchepesquisa|y24_numcgm|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_autotestem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>
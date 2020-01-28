<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

include_once("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clautousu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y50_codauto");
$clrotulo->label("y59_codauto");
$clrotulo->label("y50_nome");
$clrotulo->label("y39_codandam");
$clrotulo->label("nome");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
if(isset($opcao) && $opcao == "alterar"){
  echo "<script>parent.iframe_fiscais.location.href='fis1_autousu002.php?chavepesquisa=$y56_codauto&chavepesquisa1=$y56_id_usuario&y59_codauto=$y59_codauto&y39_codandam=$y39_codandam'</script>";}
if(isset($opcao) && $opcao == "excluir"){
  echo "<script>parent.iframe_fiscais.location.href='fis1_autousu003.php?chavepesquisa=$y56_codauto&chavepesquisa1=$y56_id_usuario&y59_codauto=$y59_codauto&y39_codandam=$y39_codandam'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty56_codauto?>">
       <?php
       db_ancora(@$Ly56_codauto,"js_pesquisay56_codauto(true);",3);
       ?>
    </td>
    <td>
      <?php

        db_input('y56_codauto',10,$Iy56_codauto,true,'text',3," onchange='js_pesquisay56_codauto(false);'");
        echo "<script>document.form1.y56_codauto.value='$y59_codauto'</script>";
        db_input('y59_codauto',10,$Iy59_codauto,true,'hidden',3,"");
        db_input('y39_codandam',20,$Iy39_codandam,true,'hidden',3,"");
        db_input('y50_nome',40,$Iy50_nome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty56_id_usuario?>">
       <?php
       db_ancora(@$Ly56_id_usuario,"js_pesquisay56_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td>
       <?php

        db_input('y56_id_usuario',10,1,true,'text',$db_opcao," onchange='js_pesquisay56_id_usuario(false);'");
        if($db_opcao == 2){

          db_input('y56_id_usuario',10,1,true,'hidden',$db_opcao,"","y56_id_usuario_old");
          echo "<script>document.form1.y56_id_usuario_old.value = '$y56_id_usuario'</script>";
        }

        db_input('nome',40,$Inome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty56_obs?>">
       <?=@$Ly56_obs?>
    </td>
    <td>
      <?php
        db_textarea('y56_obs',3,50,$Iy56_obs,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
      <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
      <?php
      if(($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33)){
      ?>
        <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_autousu001.php?y59_codauto=<?=$y59_codauto?>'">
      <?php
      }
      ?>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
   <?php
     $chavepri = array("y56_codauto"=>@$y56_codauto, "y56_id_usuario"=>@$y56_id_usuario);
     $cliframe_alterar_excluir->chavepri      = $chavepri;
     $cliframe_alterar_excluir->campos        = "y56_codauto,y56_id_usuario,y56_obs,nome";
     $cliframe_alterar_excluir->sql           = $clautousu->sql_query("",""," autousu.*,db_usuarios.*",""," y56_codauto = $y59_codauto");
     $cliframe_alterar_excluir->legenda       = "Fiscais da Vistoria";
     $cliframe_alterar_excluir->msg_vazio     = "<font size='1'>Nenhum Usuário Cadastrado!</font>";
     $cliframe_alterar_excluir->textocabec    = "darkblue";
     $cliframe_alterar_excluir->textocorpo    = "black";
     $cliframe_alterar_excluir->fundocabec    = "#aacccc";
     $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
     $cliframe_alterar_excluir->iframe_height = "170";
     $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
     $clautousu1 = new cl_autousu;
     $clautousu1->sql_record($clautousu1->sql_query("",""," autousu.*,db_usuarios.*",""," y56_codauto = $y59_codauto"));

     if($clautousu1->numrows == 0){
   ?>
      <p align="center"><small>*O auto de infração deve ter no mínimo um fiscal cadastrado!</small></p>
   <?php
     }
   ?>
   </td>
 </tr>
  </table>
  </center>
</form>
<script>
function js_setatabulacao(){
  js_tabulacaoforms("form1","y56_id_usuario",true,1,"y56_id_usuario",true);
}
function js_pesquisay56_codauto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?funcao_js=parent.js_mostraauto1|y50_codauto|y50_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?pesquisa_chave='+document.form1.y56_codauto.value+'&funcao_js=parent.js_mostraauto','Pesquisa',false);
  }
}
function js_mostraauto(chave,erro){
  document.form1.y50_nome.value = chave;
  if(erro==true){
    document.form1.y56_codauto.focus();
    document.form1.y56_codauto.value = '';
  }
}
function js_mostraauto1(chave1,chave2){
  document.form1.y56_codauto.value = chave1;
  document.form1.y50_nome.value = chave2;
  db_iframe_auto.hide();
}
function js_pesquisay56_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe','func_cadfiscaisdepto.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_cadfiscaisdepto.php?pesquisa_chave='+document.form1.y56_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
  }
}
function js_mostradb_usuarios(chave,chave1,erro){
  document.form1.nome.value = chave1;
  if(erro==true){
    document.form1.y56_id_usuario.focus();
    document.form1.y56_id_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.y56_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe','func_autousu.php?funcao_js=parent.js_preenchepesquisa|y56_codauto|1','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe.hide();
}
</script>
<?php
if(isset($y59_codauto) && $y59_codauto != ""){
  echo "<script>js_pesquisay56_codauto(false)</script>";
}
?>
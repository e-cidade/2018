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

require_once("dbforms/db_classesgenericas.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clauto->rotulo->label();
$clautolevanta->rotulo->label();
$cllevanta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y50_codauto");
$clrotulo->label("y117_sequencial");
$clrotulo->label("y50_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("q02_inscr");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_POST_VARS);

 if(isset($opcao) && $opcao == "alterar"){
   echo "<script>parent.iframe_autolevanta.location.href='fis1_autolevanta002.php?chavepesquisa=$y50_codauto&chavepesquisa1=$y117_levanta'</script>";
 }
 if(isset($opcao) && $opcao == "excluir"){
   echo "<script>parent.iframe_autolevanta.location.href='fis1_autolevanta003.php?chavepesquisa=$y50_codauto&chavepesquisa1=$y117_levanta'</script>";
 }
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <?php
  db_input('z01_numcgm',40,"",true,'hidden',$db_opcao,'','z01_numcgm');
  if (isset($z01_numcgm)) {
    echo "<script>document.form1.z01_numcgm.value = '$z01_numcgm'</script>";
  }

  db_input('q02_inscr',40,"",true,'hidden',$db_opcao,'','q02_inscr');
  if (isset($q02_inscr)) {
    echo "<script>document.form1.q02_inscr.value = '$q02_inscr'</script>";
  }

  db_input('z01_nomecgminscr',40,"",true,'hidden',$db_opcao,'','z01_nomecgminscr');
  if (isset($z01_nomecgminscr)) {
    echo "<script>document.form1.z01_nomecgminscr.value = '$z01_nomecgminscr'</script>";
  }

  if ( ($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33) ) {
    db_input('y117_sequencial',10,$Iy117_sequencial,true,'hidden',$db_opcao,"","y117_sequencial");
    echo "<script>document.form1.y117_sequencial.value = '$y117_sequencial'</script>";
  }
  ?>
  <tr>
     <td nowrap title="<?=@$Ty50_codauto?>">
       <?
       db_ancora(@$Ly50_codauto,"js_pesquisay50_codauto(true);",3);
       ?>
    </td>
    <td>
      <?
        db_input('y50_codauto',10,$Iy50_codauto,true,'text',3," onchange='js_pesquisay50_codauto(false);'");
        db_input('y50_nome',40,$Iy50_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <tr>
     <td nowrap title="<?=@$Ty60_codlev?>">
       <?
       db_ancora(@$Ly60_codlev,"js_lev(true);",1);
       ?>
    </td>
    <td>
      <?
        db_input('y60_codlev',10,$Iy60_codlev,true,'text',$db_opcao," onchange='js_lev(false);'");
        db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
        if ($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33) {
          echo "<script>document.form1.z01_nome.value = '$dbtxtnome_origem'</script>";
        }
      ?>
    </td>
  </tr>

  <tr>
    <td align="center" colspan="2">
      <?php
        if ($db_opcao == 1) {
          $sValorBotao = "Incluir";
        }else if ($db_opcao==2||$db_opcao==22) {
          $sValorBotao = "Alterar";
        } else if ($db_opcao==3||$db_opcao==33) {
          $sValorBotao = "Excluir";
        } else {
          $sValorBotao = $db_opcao;
        }
      ?>
      <input name="db_opcao" type="submit" id="db_opcao" value="<?php echo $sValorBotao?>" <?php echo ($db_botao==false?"disabled":"")?> >
        <?php
          if ( ($db_opcao==2||$db_opcao==22||$db_opcao==3||$db_opcao==33) ) {
        ?>
            <input name="novo" type="button" id="novo" value="Novo" onclick="location.href='fis1_autolevanta001.php?y50_codauto=<?php echo $y50_codauto?>'">
        <?php
          }
        ?>
    </td>
  </tr>
  <tr>
    <td align="top" colspan="2">
      <?php
       $chavepri= array("y117_auto"=>@$y50_codauto,"y117_levanta"=>@$y60_codlev);
       $cliframe_alterar_excluir->chavepri = $chavepri;
       $cliframe_alterar_excluir->campos   = "y117_auto, y117_levanta";
       $cliframe_alterar_excluir->sql      = $clautolevanta->sql_query(""," autolevanta.*",""," y117_auto = $y50_codauto");
       $cliframe_alterar_excluir->legenda       = "Levantamentos vinculados ao Auto";
       $cliframe_alterar_excluir->msg_vazio     = "<font size='1'>Nenhum Levantamento Cadastrado!</font>";
       $cliframe_alterar_excluir->textocabec    = "darkblue";
       $cliframe_alterar_excluir->textocorpo    = "black";
       $cliframe_alterar_excluir->fundocabec    = "#aacccc";
       $cliframe_alterar_excluir->fundocorpo    = "#ccddcc";
       $cliframe_alterar_excluir->iframe_height = "170";
       $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
       ?>
    </td>
  </tr>
</table>
</center>
</form>
<script type="text/javascript">

function js_lev(mostra){

  var z01_numcgm        = document.form1.z01_numcgm.value;
  var q02_inscr         = document.form1.q02_inscr.value;
  var z01_nomecgminscr  = document.form1.z01_nomecgminscr.value;
  var nomeVar           = "";

  if (q02_inscr != "") {
    nomeVar = "z01_nomeinscr";
  }
  if (z01_numcgm != "") {
    nomeVar = "z01_nomecgm";
  }

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe','func_levanta.php?todos=true&responsavel=true&'+nomeVar+'='+z01_nomecgminscr+'&z01_numcgm='+z01_numcgm+'&q02_inscr='+q02_inscr+'&funcao_js=parent.js_mostralev1|y60_codlev|dbtxtnome_origem','Pesquisa',true);
  }else{

    console.log(z01_numcgm);
    console.log(q02_inscr);

    var y60_codlev = document.form1.y60_codlev.value;
    js_OpenJanelaIframe('','db_iframe','func_levanta.php?todos=true&responsavel=true&'+nomeVar+'='+z01_nomecgminscr+'&pesquisa_chave='+y60_codlev+'&z01_numcgm='+z01_numcgm+'&q02_inscr='+q02_inscr+'&funcao_js=parent.js_mostralev','Pesquisa',false);
  }
}
function js_mostralev(chave,erro){

  if(erro==true){

    alert('Levantamento inválido.');
    document.form1.y60_codlev.value = "";
    document.form1.z01_nome.value   = "";
    document.form1.y60_codev.focus();
  } else{
    document.form1.z01_nome.value = chave;
  }
}
function js_mostralev1(chave1,chave2){
  document.form1.y60_codlev.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe.hide();
}


function js_pesquisay50_codauto(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?funcao_js=parent.js_mostraauto1|y50_codauto|y50_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_auto','func_auto.php?pesquisa_chave='+document.form1.y50_codauto.value+'&funcao_js=parent.js_mostraauto','Pesquisa',false);
  }
}
function js_mostraauto(chave,erro){

  document.form1.y50_nome.value = chave;
  if(erro==true){
    document.form1.y50_codauto.focus();
    document.form1.y50_codauto.value = '';
  }
}
function js_mostraauto1(chave1,chave2){

  document.form1.y50_codauto.value = chave1;
  document.form1.y50_nome.value    = chave2;
  db_iframe_auto.hide();
}
</script>
<?php
if(isset($y50_codauto) && $y50_codauto != ""){
  echo "<script>js_pesquisay50_codauto(false)</script>";
}
?>
<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: material
require_once(modification("classes/db_matunid_classe.php"));
$clmatunid = new cl_matunid;
$clmatmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m61_descr");
$clrotulo->label("m62_codmatunid");
$clrotulo->label("m63_codpcmater");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<tr>
<td>
<fieldset><legend><b>Dados do Material</b></legend>
<table>
<!--
  <tr>
    <td nowrap title="<?=@$Tm63_codpcmater?>">
       <?
        db_ancora(@$Lm63_codpcmater,"js_pesquisam63_codpcmater(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?
        db_input('m63_codpcmater',10,$Im63_codpcmater,true,'text',$db_opcao,"onchange='js_pesquisam63_codpcmater(false);'");
        db_input('pc01_descrmater',40,'',true,'text',3)
      ?>
    </td>
  </tr>
  -->
  <tr>
    <td nowrap title="<?=@$Tm60_codmater?>">
       <?=@$Lm60_codmater?>
    </td>
    <td>
      <?
        db_input('m60_codmater',10,$Im60_codmater,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm60_descr?>">
       <?=@$Lm60_descr?>
    </td>
    <td>
      <?
        db_input('m60_descr',60,$Im60_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm60_codmatunid?>">
       <?
         db_ancora(@$Lm60_codmatunid,"js_pesquisam60_codmatunid(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?
        if($db_opcao == 1 && (!isset($m60_codmatunid) || (isset($m60_codmatunid) && trim($m60_codmatunid)=="")) && (!isset($m62_codmatunid) || (isset($m62_codmatunid) && trim($m62_codmatunid)==""))){
          $result_unidade=$clmatunid->sql_record($clmatunid->sql_query_file('1'));
          if ($clmatunid->numrows!=0){
            db_fieldsmemory($result_unidade,0);
            $m60_codmatunid=$m61_codmatunid;
            $m62_codmatunid=$m61_codmatunid;
            $m61_descr=$m61_descr;
            $descr_uni=$m61_descr;
          }
        }
        db_input('m60_codmatunid',10,$Im60_codmatunid,true,'text',$db_opcao," onchange='js_pesquisam60_codmatunid(false);'")
      ?>
       <?
         db_input('m61_descr',40,$Im61_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm62_codmatunid?>">
      <?
        db_ancora(@$Lm62_codmatunid,"js_pesquisam62_codmatunid(true);",$db_opcao);
      ?>
    </td>
    <td>
      <?
        db_input('m62_codmatunid',10,$Im62_codmatunid,true,'text',$db_opcao," onchange='js_pesquisam62_codmatunid(false);'");
        db_input('descr_uni',40,'',true,'text',3);
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <b>
        <?
        /*
         * modificação para liberar a alteração do grupo do material, somente para usuário DBSeller
         */
          $iLiberaGrupo = $db_opcao;
          if ($iLiberaGrupo == 2 && ( db_getsession('DB_login') != "dbseller" && db_getsession("DB_id_usuario") != "1"  ) ) {
          	$iLiberaGrupo = 3;
          }
          
          db_ancora('Grupo:',"js_pesquisaGrupo(true);",$iLiberaGrupo);
        ?>
      </b>
    </td>
    <td>
      <?
        db_input('m65_sequencial',10,'',true,'text',$iLiberaGrupo," onchange='js_pesquisaGrupo(false);'");
        db_input('db121_descricao',40,'text',$iLiberaGrupo,'');
      ?>
    </td>
  </tr>
    <td nowrap title="<?=@$Tm60_quantent?>">
       <?=@$Lm60_quantent?>
    </td>
    <td>
      <?php

        $opc=3;
        if (isset($m60_codmatunid)&&$m60_codmatunid!="") {

          $result_unidade=$clmatunid->sql_record($clmatunid->sql_query_file($m60_codmatunid));
          if ($clmatunid->numrows!=0){
             db_fieldsmemory($result_unidade,0);
             if ($m61_usaquant=='t'){
               $opc=1;
             }
          }
        }

        if (empty($m60_quantent) || $opc == 3) {
          $m60_quantent = 1;
        }

        db_input('m60_quantent',15,$Im60_quantent,true,'text',$opc,"");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm60_codant?>">
       <?=@$Lm60_codant?>
    </td>
    <td>
      <?
        db_input('m60_codant',20,$Im60_codant,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap align=right title="<?=@$Tm60_ativo?>">
       <?=@$Lm60_ativo?>
    </td>
    <td>
      <?
        $xx = array("t"=>"SIM","f"=>"NAO");
        db_select('m60_ativo',$xx,true,$db_opcao,"");
      ?>
    </td>
  </tr>
    <tr>
    <td nowrap align=right title="<?=@$Tm60_controlavalidade?>">
       <?=@$Lm60_controlavalidade?>
    </td>
    <td>
    <?
     if (!isset($m60_controlavalidade)) {
       $m60_controlavalidade = 3;
     }
     db_select('m60_controlavalidade',getValoresPadroesCampo("m60_controlavalidade"),true,$db_opcao,"");
    ?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>

<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit"
       id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao == false?"disabled":"")?> />
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
<?
  if($db_opcao==2||$db_opcao==22){
    ?>
    <input name="novo" type="button" id="novo" value="Novo" onclick="js_novo();" />
    <?
  }
?>
  </center>
</form>
<script>
//----------- lokup grupo ----

function js_pesquisaGrupo(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('','db_iframe_grupo','func_materialestoquegrupo.php?lGruposAtivos=true&iTipoConta=2&funcao_js=parent.js_mostraGrupo1|m65_sequencial|dl_descrição_do_grupo','Pesquisa',true);
  } else {

     if(document.form1.m65_sequencial.value != '') {
        js_OpenJanelaIframe('','db_iframe_grupo','func_materialestoquegrupo.php?lGruposAtivos=true&iTipoConta=2&pesquisa_chave='+document.form1.m65_sequencial.value+'&funcao_js=parent.js_mostraGrupo','Pesquisa',false);
     }else{
       document.form1.m65_sequencial.value = '';
     }
  }
}
function js_mostraGrupo(chave,chave2,erro) {

  document.form1.m65_sequencial.value = chave;
  document.form1.db121_descricao.value  = chave2;
  if (erro == true) {

    document.form1.m65_sequencial.focus();
    document.form1.m65_sequencial.value = '';
  }
}
function js_mostraGrupo1(chave1,chave2) {

  document.form1.m65_sequencial.value = chave1;
  document.form1.db121_descricao.value = chave2;
  db_iframe_grupo.hide();
}


//-----------------------

function js_pesquisam60_codmatunid(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('','db_iframe_matunid','func_matunid.php?funcao_js=parent.js_mostramatunid1|m61_codmatunid|m61_descr','Pesquisa',true,0);
  } else {

     if (document.form1.m60_codmatunid.value != '') {
        js_OpenJanelaIframe('','db_iframe_matunid','func_matunid.php?pesquisa_chave='+document.form1.m60_codmatunid.value+'&funcao_js=parent.js_mostramatunid','Pesquisa',false);
     } else {
       document.form1.m61_descr.value = '';
     }
  }
}
function js_mostramatunid(chave,erro) {

  document.form1.m61_descr.value = chave;
  if (erro == true) {

    document.form1.m60_codmatunid.focus();
    document.form1.m60_codmatunid.value = '';
  } else {
    document.form1.submit();
  }
}
function js_mostramatunid1(chave1,chave2) {

  document.form1.m60_codmatunid.value = chave1;
  document.form1.m61_descr.value = chave2;
  db_iframe_matunid.hide();
  document.form1.submit();
}
//-----------------------------------------------------------
function js_pesquisam63_codpcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater','Pesquisa',true);
  }else{
     if(document.form1.m63_codpcmater.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.m63_codpcmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
        document.form1.pc01_descrmater.value = "";
     }
  }
}
function js_mostrapcmater(chave,erro) {

  document.form1.pc01_descrmater.value = chave;
  if (erro==true) {

    document.form1.m63_codpcmater.focus();
    document.form1.m63_codpcmater.value = '';
  } else {
    document.form1.m60_descr.value=chave;
  }
}
function js_mostrapcmater1(chave1,chave2) {

  document.form1.m63_codpcmater.value = chave1;
  document.form1.pc01_descrmater.value = chave2;
  document.form1.m60_descr.value=chave2;
  db_iframe_pcmater.hide();
}
//--------------------------------------------------
function js_pesquisam62_codmatunid(mostra) {
  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_matunid','func_matunid.php?funcao_js=parent.js_mostramatunid3|m61_codmatunid|m61_descr','Pesquisa',true,0);
  } else {

     if (document.form1.m62_codmatunid.value != '') {
        js_OpenJanelaIframe('','db_iframe_matunid','func_matunid.php?pesquisa_chave='+document.form1.m62_codmatunid.value+'&funcao_js=parent.js_mostramatunid2','Pesquisa',false);
     } else {
       document.form1.m61_descr_unisai.value = '';
     }
  }
}
function js_mostramatunid2(chave,erro) {

  document.form1.descr_uni.value = chave;
  if (erro==true) {

    document.form1.m62_codmatunid.focus();
    document.form1.m62_codmatunid.value = '';
  }
}
function js_mostramatunid3(chave1,chave2) {

  document.form1.m62_codmatunid.value = chave1;
  document.form1.descr_uni.value = chave2;
  db_iframe_matunid.hide();
}
//-------------------------------------------------=
function js_pesquisa() {
  <?if($db_opcao!=1){?>
     js_OpenJanelaIframe('','db_iframe_matmater','func_matmateralt.php?funcao_js=parent.js_preenchepesquisa|m60_codmater','Pesquisa',true,0);
  <?}else{?>
     js_OpenJanelaIframe('','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_preenchepesquisa|m60_codmater','Pesquisa',true,0);
  <?}?>
}

function js_preenchepesquisa(chave) {

  db_iframe_matmater.hide();
  <?
  if($db_opcao!=1) {
    echo "location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}


function js_novo() {
  parent.location.href = "mat1_matmateraba001.php";
}
</script>
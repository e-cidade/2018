<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

//MODULO: juridico
$clcartorio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
  <fieldset>
    <legend>Cadastro de Cartório</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tv82_sequencial?>">
          <label for='v82_sequencial'><?=@$Lv82_sequencial?></label>
        </td>
        <td>
          <?
            db_input('v82_sequencial',10,$Iv82_sequencial,true,'text',3,"");
            db_input('v82_extrajudicial',10,$v82_extrajudicial,true,'hidden');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv82_descricao?>">
          <label for='v82_descricao'><?=@$Lv82_descricao?></label>
        </td>
        <td>
          <?
            db_input('v82_descricao',50,$Iv82_descricao,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv82_numcgm?>">
          <?
            db_ancora(@$Lv82_numcgm,"js_pesquisav82_numcgm(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?
            db_input('v82_numcgm',10,$Iv82_numcgm,true,'text',$db_opcao," onchange='js_pesquisav82_numcgm(false);'")
          ?>
          <?
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tv82_obs?>" colspan="2">
          <fieldset class="separator">
            <legend><label for='v82_obs'><?=@$Lv82_obs?></label></legend>
            <?
              db_textarea('v82_obs',5,50,$Iv82_obs,true,'text',$db_opcao,"")
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

function js_pesquisav82_numcgm(mostra){

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','func_nome','func_cgm.php?lNovoDetalhe=1&funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  } else {

    if(document.form1.v82_numcgm.value != ''){
       js_OpenJanelaIframe('top.corpo','func_nome','func_cgm.php?lNovoDetalhe=1&pesquisa_chave='+document.form1.v82_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostracgm(chave,erro) {

  document.form1.z01_nome.value = chave;
  if (erro == true) {

    document.form1.v82_numcgm.focus();
    document.form1.v82_numcgm.value = '';
  }
}

function js_mostracgm1(chave1,chave2) {

  document.form1.v82_numcgm.value    = chave1;
  document.form1.z01_nome.value      = chave2;
  func_nome.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo','db_iframe_cartorio','func_cartorio.php?v82_extrajudicial='+$F('v82_extrajudicial')+'&funcao_js=parent.js_preenchepesquisa|v82_sequencial','Pesquisa',true);
}

function js_preenchepesquisa(chave) {

  db_iframe_cartorio.hide();
  <?php
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

$("v82_sequencial").addClassName("field-size2");
$("v82_descricao").addClassName("field-size9");
$("v82_numcgm").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");

</script>
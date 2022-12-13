<?php
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

require_once(modification("classes/db_empprestaitemempagemov_classe.php"));
require_once(modification("libs/db_utils.php"));

//MODULO: empenho
$clemppresta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e44_descr");
$clrotulo->label("e60_codemp");
$clrotulo->label("e45_codmov");
$clrotulo->label("e45_sequencial");

if ($db_opcao == 1) {
  $db_action = "emp1_emppresta004.php";
} else if ($db_opcao ==2 || $db_opcao == 22) {
  $db_action = "emp1_emppresta005.php";
} else if ($db_opcao == 3 || $db_opcao == 33) {
  $db_action = "emp1_emppresta006.php";
}

?>
<form name="form1" method="post" action="<?=$db_action?>">
  <center>
    <fieldset style="width: 500px">
      <legend><b>Prestação de Contas</b></legend>

      <table border="0">

        <tr style="display:none">
          <td nowrap title="<?php echo $Te45_sequencial; ?>">
             <?php echo $Le45_sequencial; ?>
          </td>
          <td>
            <?php
              db_input('e45_sequencial',10,$Ie45_sequencial,true,'hidden',3);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Te60_codemp?>">
             <?=@$Le60_codemp?>
          </td>
          <td>
            <?
            db_input('e60_codemp',10,$Ie60_codemp,true,'text',3);
            db_input('e45_numemp',10,$Ie45_numemp,true,'hidden',3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Te45_codmov; ?>">
            <?php echo $Le45_codmov; ?>
          </td>
          <td>
            <?php db_input('e45_codmov', 10, $Ie45_codmov, true); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te45_tipo?>"><strong>Tipo de Evento: </strong></td>
          <td>
            <?php
              db_input('e45_tipo',8,$Ie45_tipo,true,'text',3," onchange='js_pesquisae45_tipo(false);'");
              db_input('e44_descr',40,$Ie44_descr,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te45_data?>">
             <?=@$Le45_data?>
          </td>
          <td>
            <?
            db_inputdata('e45_data',@$e45_data_dia,@$e45_data_mes,@$e45_data_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Te45_datalimiteaplicacao; ?>">
            <label for="e45_datalimiteaplicacao" id="lbl_e45_datalimiteaplicacao"><?php echo $Le45_datalimiteaplicacao; ?></label>
          </td>
          <td>
            <?php
              if(!isset($e45_datalimiteaplicacao_dia)) {
                $e45_datalimiteaplicacao_dia = '';
              }
              if(!isset($e45_datalimiteaplicacao_mes)) {
                $e45_datalimiteaplicacao_mes = '';
              }
              if(!isset($e45_datalimiteaplicacao_ano)) {
                $e45_datalimiteaplicacao_ano = '';
              }
              db_inputdata('e45_datalimiteaplicacao', $e45_datalimiteaplicacao_dia, $e45_datalimiteaplicacao_mes, $e45_datalimiteaplicacao_ano, true, 'text', $db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Te45_processoadministrativo; ?>">
            <label for="e45_processoadministrativo" id="lbl_e45_processoadministrativo"><?php echo $Le45_processoadministrativo; ?></label>
          </td>
          <td>
            <?php db_input('e45_processoadministrativo', 23, $Ie45_processoadministrativo, true, 'text', $db_opcao); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Te45_obs?>" colspan="2">
            <fieldset>
              <legend><b>Observações</b></legend>
              <?
              db_textarea('e45_obs',5,70,$Ie45_obs,true,'text',$db_opcao,"")
              ?>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>

    <br/>

    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>
<script>

function js_pesquisae45_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_emppresta','db_iframe_empprestatip','func_empprestatip.php?funcao_js=parent.js_mostraempprestatip1|e44_tipo|e44_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.e45_tipo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_emppresta','db_iframe_empprestatip','func_empprestatip.php?pesquisa_chave='+document.form1.e45_tipo.value+'&funcao_js=parent.js_mostraempprestatip','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.e44_descr.value = '';
     }
  }
}

function js_mostraempprestatip(chave,erro){
  document.form1.e44_descr.value = chave;
  if(erro==true){
    document.form1.e45_tipo.focus();
    document.form1.e45_tipo.value = '';
  }
}
function js_mostraempprestatip1(chave1,chave2){
  document.form1.e45_tipo.value = chave1;
  document.form1.e44_descr.value = chave2;
  db_iframe_empprestatip.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe( 'CurrentWindow.corpo.iframe_emppresta',
                       'db_iframe_emppresta',
                       'func_empprestamovimento.php?lEncerrados=0&funcao_js=parent.js_preenchepesquisa|e60_numemp|e81_codmov',
                       'Pesquisa',
                       true,
                       '0' );
}

function js_preenchepesquisa(iCodigoEmpemnho, iCodigoMovimento){
  db_iframe_emppresta.hide();

  <?php
    if ($db_opcao!=1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) .
           "?chavepesquisa=' + iCodigoEmpemnho + '&chavemovimento=' + iCodigoMovimento";
    }
  ?>

}
</script>

<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: escola
$oDaoAvaliacaoEstruturaNota = new cl_avaliacaoestruturanota();
$oDaoRegraArredondamento    = new cl_regraarredondamento();
$oDaoAvaliacaoEstruturaNota->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db77_descr");
$clrotulo->label("ed316_descricao");
$clrotulo->label("ed316_sequencial");
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed315_ano");

$lBloquearAlteracao = false;
$iAno               = db_getsession("DB_anousu");
$sDisabled          = '';

if (isset($ed315_sequencial) && !empty($ed315_sequencial)) {

  $oDaoRegencia = new cl_regencia();

  $sWhereRegenciaTotal = "ed57_i_escola = {$iCodEscola} AND ed52_i_ano = {$ed315_ano} ";
  $sSqlRegenciaTotal   = $oDaoRegencia->sql_query(null, "ed57_i_codigo", null, $sWhereRegenciaTotal);
  $rsRegenciaTotal     = $oDaoRegencia->sql_record($sSqlRegenciaTotal);
  $iTotalRegencias     = $oDaoRegencia->numrows;

  $sWhereRegenciaEncerrada  = "ed59_c_encerrada = 'S' and ed57_i_escola = {$iCodEscola} AND ed52_i_ano = {$ed315_ano}";
  $sSqlRegenciaEncerrada    = $oDaoRegencia->sql_query(null, "ed57_i_codigo", null, $sWhereRegenciaEncerrada);
  $rsRegenciaEncerrada      = $oDaoRegencia->sql_record($sSqlRegenciaEncerrada);
  $iTotalRegenciasEncerrada = $oDaoRegencia->numrows;

  if ($iTotalRegencias > $iTotalRegenciasEncerrada && $iTotalRegenciasEncerrada != 0) {
    $lBloquearAlteracao = true;
  }

  if ($lBloquearAlteracao) {

    db_msgbox("Não é possível alterar a regra, pois existem turmas encerradas.");
    $db_opcao = 5;
    $db_botao = false;
  }
}

MsgAviso(db_getsession("DB_coddepto"),"escola");
?>
<form name="form1" method="post" action="">
  <div style="display: table">
    <fieldset>
      <legend><b>Estrutural da Nota</b></legend>
      <center>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Ted315_sequencial?>">
               <?=@$Led315_sequencial?>
            </td>
            <td>
              <?
                db_input('ed315_sequencial',10,$Ied315_sequencial,true,'text',3,"");
              ?>
            </td>
          </tr>
          <tr style="display: none">
            <td nowrap title="<?=@$Ted315_escola?>">
              <?
                db_ancora(@$Led315_escola,"js_pesquisaed315_escola(true);",$db_opcao);
              ?>
            </td>
            <td>
              <?
                db_input('ed315_escola',10,$Ied315_escola,true,'text',$db_opcao," onchange='js_pesquisaed315_escola(false);'");
              ?>
              <?
                db_input('ed18_i_codigo',40,$Ied18_i_codigo,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted315_db_estrutura?>">
              <?
                db_ancora(@$Led315_db_estrutura,"js_pesquisaed315_db_estrutura(true);",$db_opcao);
              ?>
            </td>
            <td>
              <?
                db_input('ed315_db_estrutura',10,$Ied315_db_estrutura,true,'text',$db_opcao,
                         " onchange='js_pesquisaed315_db_estrutura(false);'")
              ?>
              <?
                db_input('db77_descr',40,$Idb77_descr,true,'text',3,'');
                db_input('ed315_sequencial',40,$Idb77_descr,true,'hidden',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted315_ativo?>">
              <?=@$Led315_ativo?>
            </td>
            <td>
              <?
                $x = array("f"=>"NAO","t"=>"SIM");
                db_select('ed315_ativo',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted315_arredondamedia?>">
              <?=@$Led315_arredondamedia?>
            </td>
            <td>
              <?
                $x = array("f"=>"NAO","t"=>"SIM");
                db_select('ed315_arredondamedia',$x,true,$db_opcao," onchange='js_verificaArredondar();'");
              ?>
            </td>
          </tr>
          <tr id="ctnRegraArredondamento" style="visibility: hidden">
            <td nowrap title="<?=@$Ted316_sequencial?>">
              <?
                db_ancora(@$Led316_sequencial,"js_pesquisaed316_regraarredondamento(true);",$db_opcao);
              ?>
            </td>
            <td>
              <?
                db_input('ed316_sequencial', 10, $Ied316_sequencial, true, 'text',$db_opcao,
                         " onchange='js_pesquisaed316_regraarredondamento(false);'");
                db_input('ed316_descricao', 40, $Ied316_descricao, true, 'text', 3, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted315_ano?>" >
              <?=@$Led315_ano?>
            </td>
            <td>
              <?
                $db_opcaoano = $db_opcao;
                if ($db_opcao  == 2) {
                  $db_opcaoano = 33;
                }
                db_input('ed315_ano',10,$Ied315_ano,true,'text',$db_opcaoano);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted315_observacao?>" colspan="2">
              <fieldset>
                <legend><b><?=@$Led315_observacao?></b></legend>
                <?
                  db_textarea('ed315_observacao',5, 74,$Ied315_observacao,true,'text',$db_opcao,"");
                ?>
              </fieldset>
            </td>
          </tr>
        </table>
      </center>
    </fieldset>
  </div>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit"
         id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22||$db_opcao==5?"Alterar":"Excluir"))?>"
         <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed315_db_estrutura(mostra) {
  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframedb_estrutura',
                        'func_db_estrutura.php?funcao_js=parent.js_mostradb_estrutura1|db77_codestrut|db77_descr',
                        'Pesquisa',
                        true
                       );
  } else {

    if (document.form1.ed315_db_estrutura.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframedb_estrutura',
                          'func_db_estrutura.php?pesquisa_chave='+document.form1.ed315_db_estrutura.value+'&funcao_js=parent.js_mostradb_estrutura',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.db77_descr.value = '';
    }
  }
}

function js_mostradb_estrutura(chave,erro) {

  document.form1.db77_descr.value = chave;
  if (erro == true) {

    document.form1.ed315_db_estrutura.focus();
    document.form1.ed315_db_estrutura.value = '';
  }
}

function js_mostradb_estrutura1(chave1,chave2) {

  document.form1.ed315_db_estrutura.value = chave1;
  document.form1.db77_descr.value = chave2;
  db_iframedb_estrutura.hide();
}

function js_pesquisaed315_escola(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_escola',
                        'func_escola.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_i_codigo',
                        'Pesquisa',
                        true
                       );
  } else {
    if (document.form1.ed315_escola.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframe_escola',
                          'func_escola.php?pesquisa_chave='+document.form1.ed315_escola.value+'&funcao_js=parent.js_mostraescola',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.ed18_i_codigo.value = '';
    }
  }
}

function js_mostraescola(chave,erro) {

  document.form1.ed18_i_codigo.value = chave;
  if (erro == true) {

    document.form1.ed315_escola.focus();
    document.form1.ed315_escola.value = '';
  }
}

function js_mostraescola1(chave1,chave2) {

  document.form1.ed315_escola.value = chave1;
  document.form1.ed18_i_codigo.value = chave2;
  db_iframe_escola.hide();
}

function js_pesquisaed316_regraarredondamento(mostra) {

  /*
   * Parametro passado na função de pesquisa, para identificar que a pesquisa foi originada
   * do formulário Estrutural Nota, e que deve mostrar apenas as regras ativas
   */
  var sEstrutural = 'E';
  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_regraarredondamento',
                        'func_regraarredondamento.php?pesquisa='+sEstrutural+'&funcao_js=parent.js_mostraregraarredondamento1|ed316_sequencial|ed316_descricao',
                        'Pesquisa',
                        true
                       );
  } else {

    if (document.form1.ed316_sequencial.value != "") {

      js_OpenJanelaIframe(
                          'CurrentWindow.corpo',
                          'db_iframe_regraarredondamento',
                          'func_regraarredondamento.php?pesquisa_chave='+document.form1.ed316_sequencial.value+
                                                      '&funcao_js=parent.js_mostraregraarredondamento',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.ed316_descricao.value = '';
    }
  }
}

function js_mostraregraarredondamento(chave, erro) {

  document.form1.ed316_descricao.value = chave;
  if (erro == true) {

    document.form1.ed316_sequencial.focus();
    document.form1.ed316_sequencial.value = '';
  }
}

function js_mostraregraarredondamento1(chave1, chave2) {

  document.form1.ed316_sequencial.value = chave1;
  document.form1.ed316_descricao.value  = chave2;
  db_iframe_regraarredondamento.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_avaliacaoestruturanota',
                      'func_avaliacaoestruturanota.php?funcao_js=parent.js_preenchepesquisa|ed315_sequencial',
                      'Pesquisa',
                      true
                     );
}

function js_preenchepesquisa(chave) {

  db_iframe_avaliacaoestruturanota.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}

function js_pesquisaregraarredondamento() {
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_regraarredondamento',
                      'func_regraarredondamento.php?funcao_js=parent.js_preenchepesquisaregraarredondamento|ed316_sequencial',
                      'Pesquisa',
                      true
                     );
}

function js_preenchepesquisaregraarredondamento(chave) {

  db_iframe_regraarredondamento.hide();
  <?
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}

function js_verificaArredondar() {

  var iArredondar = $F('ed315_arredondamedia');

  if (iArredondar == 't') {
    $('ctnRegraArredondamento').style.visibility = "visible";
  } else {

    $('ctnRegraArredondamento').style.visibility = "hidden";
    document.form1.ed316_sequencial.value = '';
    document.form1.ed316_descricao.value  = '';
  }
}

function js_validarCampos() {

  var iArredondar     = $F('ed315_arredondamedia');
  var iCodigoRegra    = $F('ed316_sequencial');

  if (iArredondar == 't') {

    if (iCodigoRegra == '') {

      alert ('Opção Arredondar Média setada como Sim. Deve ser informado o código da regra de arredondamento.');
      document.form1.ed316_sequencial.focus();
      return false;
    }
  }
}

js_verificaArredondar();
</script>

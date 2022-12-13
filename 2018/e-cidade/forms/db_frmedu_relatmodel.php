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

$iDesabilitaQuandoAlteracao = $db_opcao == 2 || $db_opcao == 22 ? 3 : $db_opcao;
$sDescricaoTela             = $db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir");
$cledu_relatmodel->rotulo->label();
?>
<form name="form1" method="post" action="">
  <fieldset >
    <legend><?php echo $sDescricaoTela?> Modelo de Relatório</legend>

    <table class='form-container'>

      <tr>
        <td nowrap='nowrap' class='field-size3' title="<?=@$Ted217_i_codigo?>">
           <?= $Led217_i_codigo?>
        </td>
        <td  nowrap='nowrap' >
          <?php db_input('ed217_i_codigo',10,$Ied217_i_codigo,true,'text',3,"")?>
        </td>
      </tr>
      <tr>
        <td  nowrap='nowrap' class='field-size3' title="<?=@$Ted217_c_nome?>">
          <?= $Led217_c_nome?>
        </td>
        <td  nowrap='nowrap'>
          <?php db_input('ed217_c_nome',20,$Ied217_c_nome,true,'text',$db_opcao,"")?>
        </td>
      </tr>
      <tr>
        <td  nowrap='nowrap' class='field-size3' title="<?=@$Ted217_i_relatorio?>">
          <?= $Led217_i_relatorio?>
        </td>
        <td  nowrap='nowrap'>
          <?php
            $x = array(""=>"",
                       "1"=>"HISTÓRICO ESCOLAR",
                       "2"=>"CERTIFICADO DE CONCLUSÃO",
                       "3"=>"ATA DE RESULTADOS FINAIS",
                       "4"=>"QUADRO DE RESULTADOS FINAIS",
                       "5"=>"ALUNOS VOTANTES",
                       "6"=>"RESPONSÁVEIS VOTANTES"
                      );
            db_select('ed217_i_relatorio',$x,true,$iDesabilitaQuandoAlteracao,"Onchange=js_remove();");
          ?>
        </td>
      </tr>
      <tr id="divTipoModelo" style="display:none">
        <td  nowrap='nowrap' class='field-size3' title="<?=@$Ted217_i_tipomodelo?>" >
          <?= $Led217_i_tipomodelo?>
        </td>
        <td  nowrap='nowrap' >
          <?php
            $aModelos = array("" => "", "1" => "Modelo 1", "2" => "Modelo 2");
            db_select('ed217_i_tipomodelo', $aModelos, true, $db_opcao, "onChange='js_validaTipoModelo()'");
          ?>
        </td>
      </tr>
    </table>

    <div id="div_orientacao" style="visibility:hidden;position:absolute;">
      <table class="form-container">
        <tr>
          <td rel='ignore-css' nowrap='nowrap' class='field-size3' title="<?=@$Ted217_orientacao?>">
            <?=@$Led217_orientacao?>
          </td>
          <td nowrap='nowrap'  >
            <?php
              $xy = array("0"=>"",
                          "1"=>"PAISAGEM",
                          "2"=>"RETRATO"
               );
              db_select('ed217_orientacao',$xy,true,$db_opcao,"Onchange=js_orientacao();");
            ?>
          </td>
        </tr>
        <tr id="tipoBrasao" style="display:none">
          <td rel='ignore-css' nowrap='nowrap' class='field-size3'>
            <?= $Led217_brasao;?>
          </td>
          <td  nowrap='nowrap' >
            <?php
              $aTipoBrasao = array( 1 => "REPÚBLICA", 2 => "MUNICÍPIO" );
              db_select( 'ed217_brasao', $aTipoBrasao, true, $db_opcao );
            ?>
          </td>
        </tr>
        <tr id="ctnExibirTurma" style="display: none;">
          <td rel='ignore-css' nowrap='nowrap'  class='field-size3' title="<?=@$Ted217_exibeturma?>">
           <?= $Led217_exibeturma?>
          </td>
          <td  nowrap='nowrap'>
           <?php
             $xy = array("f"=>"NÃO", "t"=>"SIM");
             db_select('ed217_exibeturma',$xy,true,$db_opcao);
           ?>
         </td>
       </tr>

       <tr id="ctnExibeCargaHoraria" style="display: none;">
          <td  rel='ignore-css' nowrap='nowrap' class='field-size3' title="<?=@$Ted217_exibecargahoraria?>">
           <?=@$Led217_exibecargahoraria?>
          </td>
          <td nowrap='nowrap' >
            <?php
              $xy = array("f"=>"NÃO", "t"=>"SIM");
              db_select('ed217_exibecargahoraria',$xy,true,$db_opcao);
            ?>
          </td>
        </tr>
      </table>
    </div>

    <fieldset class='separator'>
      <legend>Texto do Cabeçalho</legend>
      <label>(Máximo cinco linhas)</label></br>
      <?php
        db_textarea('ed217_t_cabecalho', 5, 100, $Ied217_t_cabecalho, true, 'text', $db_opcao,
                    "onkeypress='return limitTextArea(this, event)'", "", "", 200 );
      ?>
    </fieldset>


    <fieldset class='separator'>
      <legend>Texto do Rodapé</legend>
      <?php db_textarea('ed217_t_rodape',5,100,$Ied217_t_rodape,true,'text',$db_opcao,"","","",200); ?>
    </fieldset>

    <fieldset class='separator'>

      <legend>Observações Gerais a Todos os Alunos</legend>
      <?php db_textarea('ed217_t_obs', 4, 100, $Ied217_t_obs, true, 'text', $db_opcao, "", "", ""); ?>
      <br>
      <div id='containerLimitCaracter' align='right'>
        <span style='float:left;color:red;font-weight:bold' id='ed217_t_obserrobar'></span>
        <b> Caracteres Digitados : </b>
        <input type='text' name='ed217_t_obsobsdig' id='ed217_t_obsobsdig' size='3' value='0' style='color: #000;' disabled>
        <b> - Limite <label for='limiteObservacao' id='limiteObservacao'></b>
      </div>
    </fieldset>

    <div id='div_tamfontes' class="subcontainer" style="visibility:hidden;position:absolute; width:100%;">
      <fieldset class='separator' style=''>
        <legend>Tamanho das fontes</legend>
        <table class='form-container'>
          <tr>
            <td class='field-size3' nowrap='nowrap' title="<?=@$Ted217_gradenotas?>">
              <?= $Led217_gradenotas?>
            </td>
            <td  nowrap='nowrap'>
              <?php
                $x = array("0"=>"", "1"=>"6", "2"=>"8" );
                db_select('ed217_gradenotas',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size3'  nowrap='nowrap' title="<?=@$Ted217_gradeetapas?>">
              <?=@$Led217_gradeetapas?>
            </td>
            <td  nowrap='nowrap'>
              <?php
                $x = array("0"=>"", "1"=>"6", "2"=>"8" );
                db_select('ed217_gradeetapas',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size3' nowrap='nowrap' title="<?=@$Ted217_observacao?>">
              <?=@$Led217_observacao?>
            </td>
            <td  nowrap='nowrap'>
              <?php
                $x = array("0"=>"", "1"=>"6", "2"=>"8" );
                db_select('ed217_observacao',$x,true,$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </div>
  </fieldset>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
         type="submit"
         id="db_opcao"
         value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
                <?=($db_botao==false?"disabled":"")?>
         onclick="return js_validaSubmit();">
  <input name="pesquisar"
         type="button"
         id="pesquisar"
         value="Pesquisar"
         onclick="js_pesquisa();" >
  <input name="novo"
         type="button"
         id="novo"
         value="Novo Registro"
         onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>

</form>

<script  type="text/javascript">

var MSG_MODELOS_RELATORIO = 'educacao.secretariaeducacao.db_frmedu_relatmodel.';

js_init();
function js_init() {

  js_remove();
  js_orientacao();
}
function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo',
		              'db_iframe_edu_relatmodel',
		              'func_edu_relatmodel.php?funcao_js=parent.js_preenchepesquisa|ed217_i_codigo',
		              'Pesquisa',
		              true
		             );
}

function js_preenchepesquisa(chave) {
  db_iframe_edu_relatmodel.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_novo() {
  location.href="edu1_relatmodel001.php";
}

function js_remove() {

  js_limitaObservacao(300);
  $('ed217_t_cabecalho').disabled  = false;
  $('divTipoModelo').style.display = 'none';

  if (document.form1.ed217_i_relatorio.value == 3) {

    document.form1.ed217_t_rodape.disabled = true;
    document.form1.ed217_t_obs.disabled    = false;
  } else if (document.form1.ed217_i_relatorio.value == 4) {

  	document.form1.ed217_t_rodape.disabled = true;
  	document.form1.ed217_t_obs.disabled    = true;
  	$('ed217_i_tipomodelo').value          = '';
  } else {

  	document.form1.ed217_t_rodape.disabled = false;
  	document.form1.ed217_t_obs.disabled    = false;
  	$('ed217_i_tipomodelo').value          = '';
  }

  if (document.form1.ed217_i_relatorio.value == 1 || document.form1.ed217_i_relatorio.value == 2) {

    if ($('ed217_i_relatorio').value == 1) {
      js_limitaObservacao(600);
    }
  	document.getElementById("div_orientacao").style.visibility = "visible";
  	document.getElementById("div_orientacao").style.position = "relative";
  	$('ctnExibirTurma').style.display        = "table-row";
  	$('ctnExibeCargaHoraria').style.display  = "table-row";
    $('tipoBrasao').style.display            = "table-row";

  } else if ($('ed217_i_relatorio').value == 3) {

    $('divTipoModelo').style.display  = 'table-row';
    document.getElementById("div_orientacao").style.visibility = "hidden";
  	document.getElementById("div_orientacao").style.position = "absolute";
  } else {

  	document.getElementById("div_orientacao").style.visibility = "hidden";
  	document.getElementById("div_orientacao").style.position = "absolute";
    document.form1.ed217_orientacao.value = "0";

  }

  js_orientacao();
  js_validaTipoModelo();
}

function js_orientacao() {

  $('containerLimitCaracter').style.display = '';

  if ( [1,2].in_array( $F('ed217_orientacao') ) ) {
    $('ed217_orientacao').options[0].setAttribute('disabled', 'disabled');
  }

  /**
   * Sempre que trocar orientação de retrato para paisagem, devemos se observação ultrapassou o limite de 500 caracteres
   */
  if ($F('ed217_orientacao') == 1)  {

    if ($F('ed217_t_obs').length > 500) {


      var sMsgConfirm  = _M( MSG_MODELOS_RELATORIO + "confirma_troca_orientacao");
      if (confirm(sMsgConfirm)) {

        $('ed217_t_obs').value       = $F('ed217_t_obs').substr(0, 500);
        $('ed217_t_obsobsdig').value = $F('ed217_t_obs').length;
        js_limitaObservacao(500);
      } else {

        $('ed217_orientacao').value = 2;
        return;
      }
    }
    $('ed217_t_obsobsdig').value = $F('ed217_t_obs').length;

  }

  if (document.form1.ed217_orientacao.value == 2) {

  	document.getElementById("div_tamfontes").style.visibility = "visible";
  	document.getElementById("div_tamfontes").style.position   = "relative";
  	document.form1.ed217_t_rodape.disabled = true;

    if ( [1,2].in_array($F('ed217_i_relatorio')) ) {
      js_removeLimitacaoCaracter();
    }

  } else {

    document.getElementById("div_tamfontes").style.visibility = "hidden";
    document.getElementById("div_tamfontes").style.position = "absolute";
    document.form1.ed217_gradenotas.value  = "0";
    document.form1.ed217_gradeetapas.value = "0";
    document.form1.ed217_observacao.value  = "0";
    document.form1.ed217_t_rodape.disabled = false;
    js_validaTipoModelo();
  }

}

function limitTextArea(text, event) {

  var str         = text.value;
  var newStr      = "";
  var linhas      = new Array();
  var replaceLine = false;
  var aLinhas     = str.split("\n");
  var cont        = linhas.length;

  if (event.keyCode == 8 || event.keyCode == 16 || event.keyCode  == 20 ||
      event.keyCode == 18 || event.keyCode == 46) {
    return true;
  }

  /**
   * Verificamos se o tipo de relatorio eh Ata - Modelo 2, e se este atingiu o limite permitido de ate 60 caracteres
   */
  if ($('ed217_i_relatorio').value == 3 && $('ed217_i_tipomodelo').value == 2 && str.length > 59) {


    alert(_M( MSG_MODELOS_RELATORIO + "ata_resultados_finais_modelo2"));
    return false;
  }

  if (aLinhas.length > 5) {
    return false;
  }
  return true;
}

function js_validaSubmit() {

  if ( (document.form1.ed217_i_relatorio.value == 1 || document.form1.ed217_i_relatorio.value == 2) && document.form1.ed217_orientacao.value == 0) {

    alert(_M( MSG_MODELOS_RELATORIO + "orientacao_nao_informado"));
    return false;
  }
  if ( document.form1.ed217_orientacao.value == 2 && (document.form1.ed217_gradenotas.value == 0 || document.form1.ed217_gradeetapas.value == 0 || document.form1.ed217_observacao.value == 0) ) {

    alert(_M( MSG_MODELOS_RELATORIO + "campos_tamanhos_fontes"));
    return false;
  }

  _M( MSG_MODELOS_RELATORIO + "informe_tipo_modelo")
  if ($('ed217_i_relatorio').value == '3' && $('ed217_i_tipomodelo').value == '') {

    alert(_M( MSG_MODELOS_RELATORIO + "informe_tipo_modelo"));
    return false;
  }
  return true;
}

/**
 * Verificamos o tipo de modelo selecionado
 * Caso 1: Limpamos o conteudo e bloqueamos o campo ed217_t_cabecalho
 */
function js_validaTipoModelo() {

  js_limitaObservacao(300);
  $('ed217_t_cabecalho').disabled = false;

  if ($('ed217_i_tipomodelo').value == 1) {

    $('ed217_t_cabecalho').value    = '';
    $('ed217_t_cabecalho').disabled = true;
  } else {
    js_limitaObservacao(500);
  }
}

/**
 * Seta o limite de caracteres do campo observacao
 */
function js_limitaObservacao(iLimite) {

  $('limiteObservacao').innerHTML = iLimite;
  $('ed217_t_obs').stopObserving('keyup');
  $('ed217_t_obs').observe('keyup', function(event) {
    js_maxlenghttextarea($('ed217_t_obs'), event, iLimite);
  });
}

function js_removeLimitacaoCaracter (argument) {

  $('containerLimitCaracter').style.display = 'none';
  $('ed217_t_obs').stopObserving('keyup');
  // $('ed217_t_obs').removeAttribute('onkeyup');
  $('ed217_t_obs').removeAttribute('keyup');


}

</script>
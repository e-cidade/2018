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
?>
<form name="form2">
 <table>
  <tr>
   <td valign="top" bgcolor="#CCCCCC">
    <input id="escolaRede" type="radio" name="opcao" value="ER" onclick="js_direciona1();"> <label for="escolaRede">Escolas da rede municipal</label><br>
   </td>
   <td>
    <input id="escolaFora" type="radio" name="opcao" value="EF" onclick="js_direciona2();" <?=$db_opcao==1?"checked":""?>> <label for="escolaFora">Outras escolas</label><br><br>
   </td>
  </tr>
 </table>
</form>
<script>
function js_direciona1() {

  location.href = "edu1_historicomps001.php?ed62_i_historico=<?=$ed99_i_historico?>&ed29_c_descr=<?=$ed29_c_descr?>"+
                  "&ed29_i_codigo=<?=$ed29_i_codigo?>&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>"+
                  "&ed11_i_ensino=<?=$ed11_i_ensino?>";
}

function js_direciona2() {

  location.href = "edu1_historicompsfora001.php?ed99_i_historico=<?=$ed99_i_historico?>"+
                  "&ed29_c_descr=<?=$ed29_c_descr?>&ed29_i_codigo=<?=$ed29_i_codigo?>"+
                  "&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>"+
                  "&ed11_i_ensino=<?=$ed11_i_ensino?>";
}

parent.disciplina.location.href = "edu1_historicodisciplinafora.php?ed100_i_historicompsfora=<?=@$chavepesquisa?>";
</script>
<?
if ( isset($situacao) && $situacao == "CONCLUÍDO") {
  $db_botao = false;
}

//MODULO: educação
$oDaoHistoricoMpsFora->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed61_i_codigo");
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed06_i_codigo");
$clrotulo->label("ed11_i_ensino");
$clrotulo->label("ed11_i_sequencia");
$clrotulo->label("ed29_c_descr");
$clrotulo->label("ed29_i_codigo");
$clrotulo->label("ed11_c_descr");
$clrotulo->label("ed82_c_nome");
$clrotulo->label("ed06_c_descr");

$lExcluir = false;
?>
<form name="form1" method="post" action="" class="container">
  <table class="form-container">
    <tr>
      <td nowrap title="<?=$Ted99_i_codigo?>">
        <?=$Led99_i_codigo?>
      </td>
      <td>
        <?php
        db_input( 'ed99_i_codigo', 20, $Ied99_i_codigo, true, 'text', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted99_i_historico?>">
        <?php
        db_ancora( $Led99_i_historico, "js_pesquisaed99_i_historico(true);", 3 );
        ?>
      </td>
      <td>
        <?php
        $sScript = " onchange='js_pesquisaed99_i_historico(false);'";
        db_input( 'ed99_i_historico', 15, $Ied99_i_historico, true, 'text',   3, $sScript );
        db_input( 'ed29_c_descr',     40, $Ied29_c_descr,     true, 'text',   3 );
        db_input( 'ed29_i_codigo',    40, $Ied29_i_codigo,    true, 'hidden', 3 );
        db_input( 'ed11_i_ensino',    40, $Ied11_i_ensino,    true, 'hidden', 3 );
        db_input( 'ed11_i_sequencia', 40, $Ied11_i_sequencia, true, 'hidden', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted99_i_serie?>">
        <?php
        db_ancora( $Led99_i_serie, "js_pesquisaed99_i_serie(true);", $db_opcao1 );
        ?>
      </td>
      <td>
        <?php
        db_input( 'ed99_i_serie', 15, $Ied99_i_serie,  true, 'text', 3, " onchange='js_pesquisaed99_i_serie(false);'" );
        db_input( 'ed11_c_descr', 20, $Ied11_c_descr, true, 'text', 3 );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted99_i_anoref?>">
        <?=$Led99_i_anoref?>
      </td>
      <td>
        <?php
        db_input( 'ed99_i_anoref', 4, $Ied99_i_anoref, true, 'text', $db_opcao );
        echo $Led99_i_periodoref;
        db_input( 'ed99_i_periodoref', 4, 1, true, 'text', $db_opcao );
        echo $Led99_c_turma;
        db_input( 'ed99_c_turma', 30, $Ied99_c_turma, true, 'text', $db_opcao );
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted99_i_escolaproc?>">
        <?php
        db_ancora( $Led99_i_escolaproc, "js_pesquisaed99_i_escolaproc(true);", $db_opcao );
        ?>
      </td>
      <td>
        <?php
        $sScript = " onchange='js_pesquisaed99_i_escolaproc(false);'";
        db_input( 'ed99_i_escolaproc', 15, $Ied99_i_escolaproc, true, 'text', $db_opcao, $sScript );
        db_input( 'ed82_c_nome',       30, $Ied82_c_nome,      true, 'text', 3 );
        ?>
        <input name="novo"
               id="btnNovo"
               type="button"
               value="Nova"
               onclick="js_novaescola();"
               <?=($db_botao==false?"disabled":"")?>
               style="visibility:visible;">
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Ted99_c_situacao?>">
        <?=$Led99_c_situacao?>
      </td>
      <td>
        <?php
        $x = array(
                    ''               => '',
                    'CONCLUÍDO'      => 'CONCLUÍDO',
                    'AMPARADO'       => 'AMPARADO',
                    'TRANSFERIDO'    => 'TRANSFERIDO',
                    'CANCELADO'      => 'CANCELADO',
                    'EVADIDO'        => 'EVADIDO',
                    'FALECIDO'       => 'FALECIDO',
                    'RECLASSIFICADO' => 'RECLASSIFICADO'
                  );
        db_select( 'ed99_c_situacao', $x, true, $db_opcao, " onchange='js_situacao(this);'" );
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <fieldset>
          <legend class="bold">Observação</legend>
        <?php
        db_textarea( 'ed99_observacao', 4, 10, $Ied99_observacao, true, '', 1, '', '', '', 500 );
        ?>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div name="situacao" id="situacao">
          <fieldset>
            <legend>
              <input type="text"
                     name="legenda"
                     value="<?=@$ed99_c_situacao?>"
                     size="15"
                     style="border: 0px; background: #cccccc; font-weight: bold; text-align: center;">
            </legend>
            <table>
              <tr id="justificativa" style="display: none;">
                <td nowrap title="<?=$Ted99_i_justificativa?>">
                  <?php
                  db_ancora( $Led99_i_justificativa, "js_pesquisaed99_i_justificativa(true);", $db_opcao );
                  ?>
                </td>
                <td>
                  <?php
                  $sScript = " onchange='js_pesquisaed99_i_justificativa(false);'";
                  db_input( 'ed99_i_justificativa', 10, $Ied99_i_justificativa, true, 'text', $db_opcao, $sScript );
                  db_input( 'ed06_c_descr',         30, $Ied06_c_descr,        true, 'text', 3 );
                  ?>
                </td>
              </tr>
              <tr id="resultado">
                <td nowrap title="<?=$Ted99_c_resultadofinal?>">
                  <?=$Led99_c_resultadofinal?>
                </td>
                <td>
                  <?php
                  $x = array( '' => '', 'A' => 'APROVADO', 'R' => 'REPROVADO' );
                  db_select( 'ed99_c_resultadofinal', $x, true, $db_opcao );
                  ?>
                </td>
                <td>
                  <?=$Led99_i_diasletivos?>
                </td>
                <td>
                  <?php
                  db_input( 'ed99_i_diasletivos', 10, $Ied99_i_diasletivos, true, 'text', $db_opcao );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=$Ted99_i_qtdch?>">
                  <?=$Led99_i_qtdch?>&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                  <?php
                  db_input( 'ed99_i_qtdch', 10, $Ied99_i_qtdch, true, 'text', $db_opcao );
                  ?>
                </td>
                <td nowrap title="<?=$Ted99_c_minimo?>" id="labelMinimo">
                  <?=$Led99_c_minimo?>
                </td>
                <td id="inputMinimo">
                  <?php
                  db_input( 'ed99_c_minimo', 20, $Ied99_c_minimo, true, 'text', $db_opcao );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?=$Led99_c_termofinal?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                  <?php
                  db_input( 'ed99_c_termofinal', 10, $Ied99_c_termofinal, true, 'text', $db_opcao );
                  ?>
                </td>
                <td>
                   <?=$Led99_percentualfrequencia?>
                 </td>
                 <td>
                   <?php
                   db_input( 'ed99_percentualfrequencia', 10, $Ied99_percentualfrequencia, true, 'text', $db_opcao, 'onblur="js_validaFrequencia(this)"', '', '', '', '5' );
                   ?>
                 </td>
              </tr>
              <tr style="display: none;">
               <td>
                 <input id="lExcluir" name="lExcluir" value="<?=$lExcluir;?>">
               </td>
             </tr>
            </table>
          </fieldset>
        </div>
      </td>
    </tr>
  </table>
  <input type="hidden" id="iOpcao" value="<?=$db_opcao?>">
  <?php
  if( $db_opcao == 1 ) {
  ?>

    <input name="incluir"
           type="submit"
           id="btnIncluir"
           value="Incluir"
           <?=( $db_botao == false ? "disabled" : "" )?>
           onclick="return js_minimoaprov();">
  <?php
  } else {
  ?>

    <input name="alterar"
           type="submit"
           id="btnAlterar"
           value="Alterar"
           <?=( $db_botao == false ? "disabled" : "" )?>
           onclick="return js_minimoaprov();">
    <input name="excluir"
           type="button"
           id="btnExcluir"
           value="Excluir"
           <?=( $db_botao == false ? "disabled" : "" )?>
           onclick="js_confirmaExclusao();" >
  <?php
  }
  ?>
</form>
<script>
var iOrdemEtapaSelecionada = $F('ed11_i_sequencia');
$('ed99_i_diasletivos').setAttribute('maxlength', '3');
$('ed99_i_qtdch').setAttribute('maxlength', '7');

var sCaminhoMensagem = "educacao.escola.db_frmhistoricompsfora.";
<?php
if (
         isset($ed99_c_situacao) && trim($ed99_c_situacao) == "CONCLUÍDO"
      || isset($ed99_c_situacao) && trim($ed99_c_situacao) == "RECLASSIFICADO"
   ) {?>

  document.getElementById("resultado").style.display     = "";
  document.getElementById("labelMinimo").style.display   = "";
  document.getElementById("inputMinimo").style.display   = "";
  document.getElementById("justificativa").style.display = "none";
<?php
} else if (isset($ed99_c_situacao) && trim($ed99_c_situacao) == "AMPARADO") {
?>

  document.getElementById("justificativa").style.display = "";
  document.getElementById("resultado").style.display     = "none";
  document.getElementById("labelMinimo").style.display   = "none";
  document.getElementById("inputMinimo").style.display   = "none";
<?php
} else {
?>
  document.getElementById("situacao").style.display = "none";
<?php
}
?>

function js_situacao(campo) {

  if (campo.value == "CONCLUÍDO" || campo.value == "RECLASSIFICADO") {

    document.getElementById("situacao").style.display      = "";
    document.getElementById("justificativa").style.display = "none";
    document.getElementById("resultado").style.display     = "";
    document.getElementById("labelMinimo").style.display   = "";
    document.getElementById("inputMinimo").style.display   = "";
    document.form1.legenda.value                           = campo.value;
    document.getElementById("ed99_c_resultadofinal").value = "A";
  } else if (campo.value == "AMPARADO") {

    document.getElementById("situacao").style.display      = "";
    document.getElementById("justificativa").style.display = "";
    document.getElementById("resultado").style.display     = "none";
    document.getElementById("labelMinimo").style.display   = "none";
    document.getElementById("inputMinimo").style.display   = "none";
    document.form1.legenda.value                           = "AMPARADO";
    document.getElementById("ed99_c_resultadofinal").value = "";
  } else {

    document.getElementById("situacao").style.display      = "none";
    document.getElementById("justificativa").style.display = "none";
    document.getElementById("resultado").style.display     = "none";
    document.getElementById("labelMinimo").style.display   = "none";
    document.getElementById("inputMinimo").style.display   = "none";
    document.getElementById("ed99_c_resultadofinal").value = "";
  }
}

function js_pesquisaed99_i_escolaproc(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_escolaproc',
    	                'func_escolaproc.php?funcao_js=parent.dados.js_mostraescolaproc1|ed82_i_codigo|ed82_c_nome',
    	                'Pesquisa de Escolas de Procedência', true
    	               );
  } else {

    if (document.form1.ed99_i_escolaproc.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_escolaproc',
    	                  'func_escolaproc.php?pesquisa_chave='+document.form1.ed99_i_escolaproc.value+
    	                  '&funcao_js=parent.dados.js_mostraescolaproc&lRetornoPadrao=true', 'Pesquisa', false
    	                 );
    } else {
      document.form1.ed18_c_nome.value = '';
    }
  }
}

function js_mostraescolaproc(chave, erro) {

  var sDescricao = chave;

  if ( typeof(erro) !== 'boolean') {
    sDescricao = erro;
  }

  document.form1.ed82_c_nome.value = sDescricao;

  if (erro == true) {

    document.form1.ed99_i_escolaproc.focus();
    document.form1.ed99_i_escolaproc.value = '';
  }
}

function js_mostraescolaproc1(chave1, chave2) {

  document.form1.ed99_i_escolaproc.value = chave1;
  document.form1.ed82_c_nome.value       = chave2;
  parent.db_iframe_escolaproc.hide();
}

function js_pesquisaed99_i_serie(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_serie',
    	                'func_seriehist.php?historico=' + $F('ed99_i_historico')    +
                      '&iStatusAlteracaoHistorico='   + iStatusAlteracaoHistorico +
                      '&iOrdemEtapaAtual='            + iOrdemEtapaAtual          +
    	                '&funcao_js=parent.dados.js_mostraserie1|ed11_i_codigo|ed11_c_descr|ed11_i_sequencia',
    	                'Pesquisa de Etapas', true
    	                );
  } else {

    if (document.form1.ed99_i_serie.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_serie',
    	                  'func_seriehist.php?historico=' + $F('ed99_i_historico')    +
                        '&iStatusAlteracaoHistorico='   + iStatusAlteracaoHistorico +
                        '&iOrdemEtapaAtual='            + iOrdemEtapaAtual          +
    	                  '&pesquisa_chave='              + $F('ed99_i_serie')        +
                        '&funcao_js=parent.dados.js_mostraserie',
    	                  'Pesquisa', false
    	                 );
    } else {
      document.form1.ed11_c_descr.value = '';
    }
  }
}

function js_mostraserie(chave, erro) {

  document.form1.ed11_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed99_i_serie.focus();
    document.form1.ed99_i_serie.value = '';
  }
}

function js_mostraserie1(chave1, chave2) {

  document.form1.ed99_i_serie.value = chave1;
  document.form1.ed11_c_descr.value = chave2;

  $('ed11_i_sequencia').value = arguments[2];
  iOrdemEtapaSelecionada = arguments[2]

  validaBloquearBotao();
  parent.db_iframe_serie.hide();
}

function js_pesquisaed99_i_justificativa(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('parent', 'db_iframe_justificativa',
    	                'func_justificativa.php?funcao_js=parent.dados.js_mostrajustificativa1|'+
    	                'ed06_i_codigo|ed06_c_descr', 'Pesquisa de Justificativas', true
    	               );
  } else {

    if (document.form1.ed99_i_justificativa.value != '') {

      js_OpenJanelaIframe('parent', 'db_iframe_justificativa',
    	                  'func_justificativa.php?pesquisa_chave='+document.form1.ed99_i_justificativa.value+
    	                  '&funcao_js=parent.dados.js_mostrajustificativa', 'Pesquisa', false
    	                 );
    } else {
      document.form1.ed06_c_descr.value = '';
    }
  }
}

function js_mostrajustificativa(chave, erro) {

  document.form1.ed06_c_descr.value = chave;
  if (erro == true) {

    document.form1.ed99_i_justificativa.focus();
    document.form1.ed99_i_justificativa.value = '';
  }
}

function js_mostrajustificativa1(chave1, chave2) {

  document.form1.ed99_i_justificativa.value = chave1;
  document.form1.ed06_c_descr.value         = chave2;
  parent.db_iframe_justificativa.hide();
}

function js_novaescola() {

  js_OpenJanelaIframe('parent', 'db_iframe_escolaprocedencia', 'edu1_escolaproc001.php?db_opcao=1&lOrigemTransferencia=true',
		              'Nova Escola de Procedência', true, 40, 0, screen.availWidth-20, screen.availHeight-100
		             );
}

function js_minimoaprov() {

  if (document.form1.ed99_c_situacao.value == "CONCLUÍDO" && document.form1.ed99_c_minimo.value == "") {

    alert( _M( sCaminhoMensagem + 'preencha_minimo_aprovacao' ) );
    return false;
  }

  if (document.getElementById('ed99_i_escolaproc').value == '') {

    alert( _M( sCaminhoMensagem + 'preencha_escola' ) );
    return false;
  }

  if (document.getElementById('ed99_i_serie').value == '') {

    alert( _M( sCaminhoMensagem + 'preencha_etapa' ) );
    return false;
  }

  if (document.getElementById('ed99_i_anoref').value == '') {

    alert( _M( sCaminhoMensagem + 'preencha_ano' ) );
    return false;
  }

  if( !empty( $F('ed99_i_qtdch') ) ) {

    var aValorCargaHoraria = $F('ed99_i_qtdch').split( '.' );

    if(    aValorCargaHoraria[0] == ''
        || ( aValorCargaHoraria[0] != '' && aValorCargaHoraria[0].length > 4 )
      ) {

      alert( _M( sCaminhoMensagem + 'valor_invalido_carga_horaria' ) );
      $('ed99_i_qtdch').focus();
      return false;
    }

    if( aValorCargaHoraria.length > 1 ) {

      if(     aValorCargaHoraria[1] == ''
           || ( aValorCargaHoraria[1] != '' && ( aValorCargaHoraria[1].length == 0 || aValorCargaHoraria[1].length > 2 ) )
        ) {

        alert( _M( sCaminhoMensagem + 'valor_invalido_carga_horaria' ) );
        $('ed99_i_qtdch').focus();
        return false;
      }
    }
  }

  return true;
}

function $ ( sElemento ) {
  return document.getElementById(sElemento);
}

$('ed99_i_codigo')      .setAttribute("class", 'field-size2');
$('ed99_i_historico')   .setAttribute("class", 'field-size2');
$('ed29_c_descr')       .setAttribute("class", 'field-size7');
$('ed99_i_serie')       .setAttribute("class", 'field-size2');
$('ed11_c_descr')       .setAttribute("class", 'field-size7');
$('ed99_i_anoref')      .setAttribute("class", 'field-size2');
$('ed99_i_periodoref')  .setAttribute("class", 'field-size2');
$('ed99_c_turma')       .style.width = '118px';
$('ed99_i_escolaproc')  .setAttribute("class", 'field-size2');
$('ed82_c_nome')        .setAttribute("class", 'field-size7');
$('ed99_c_situacao')    .setAttribute("rel", 'ignore-css');
$('ed99_c_situacao')    .setAttribute("class", 'field-size9');

function js_confirmaExclusao() {

  if ( confirm( _M( sCaminhoMensagem + "confirma_exclusao" ) ) ) {

    $('lExcluir').value = true;
    document.form1.submit();
  }
}

function js_validaFrequencia(oPercentualFrequencia) {

  var aPercentualFrequencia = oPercentualFrequencia.value.split('-');
  if (aPercentualFrequencia.length > 1) {

    alert( _M( sCaminhoMensagem + 'percentual_frequencia_negativo' ) );
    return false;
  }

  if (oPercentualFrequencia.value > 100.00) {

    alert( _M( sCaminhoMensagem + 'percentual_frequencia_maximo' ) );
    return false;
  }

  if (oPercentualFrequencia.value < 0.00) {

    alert( _M( sCaminhoMensagem + 'percentual_frequencia_negativo' ) );
    return false;
  }
}

/*
 * Valida se Escola selecionada tem permissão de manutenção do histórico do aluno.
 */
var iOrdemEtapaAtual          = CurrentWindow.corpo.oDadosManutencaoHistorico.aSenquenciaEtapas[$F('ed11_i_ensino')];
var iStatusAlteracaoHistorico = CurrentWindow.corpo.oDadosManutencaoHistorico.iStatusAlteracaoHistorico;

function validaBloquearBotao() {

  liberarBotao();
  var oHistorico = new HistoricoEscolar( iStatusAlteracaoHistorico,
                                         iOrdemEtapaAtual,
                                         iOrdemEtapaSelecionada );

  if ( !oHistorico.permiteManutencao() ) {

    if ( $F('iOpcao') == 1 ) {

      $('btnIncluir').setAttribute('disabled', 'disabled');
      $('btnNovo').setAttribute('disabled', 'disabled');
    }

    if ( $F('iOpcao') == 2 ) {

      $('btnNovo').setAttribute('disabled', 'disabled');
      $('btnAlterar').setAttribute('disabled', 'disabled');
      $('btnExcluir').setAttribute('disabled', 'disabled');
    }
  }
};

function liberarBotao() {

  if ( $('btnIncluir') ){
    $('btnIncluir').removeAttribute('disabled');
  }
  if ( $('btnAlterar') ){
    $('btnAlterar').removeAttribute('disabled');
  }
  if ( $('btnExcluir') ){
    $('btnExcluir').removeAttribute('disabled');
  }
};

validaBloquearBotao();
</script>
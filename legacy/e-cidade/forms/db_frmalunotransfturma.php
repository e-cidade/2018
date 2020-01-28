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

//MODULO: educação
$clalunotransfturma->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed60_i_codigo");
$clrotulo->label("ed60_matricula");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed60_d_datamatricula");
$clrotulo->label("ed47_v_nome");
$clrotulo->label("ed10_c_descr");


?>
<form name="form1" method="post" action="">
  <div class="container">
    <fieldset>
      <legend>Trocar Aluno de Turma</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?=$Ted69_i_codigo?>">
            <?=$Led69_i_codigo?>
          </td>
          <td>
            <?php db_input( 'ed69_i_codigo', 15, $Ied69_i_codigo, true, 'text', 3 )?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Ted69_i_matricula?>">
            <?db_ancora( $Led69_i_matricula, "js_pesquisaed69_i_matricula(true);", $db_opcao );?>
          </td>
          <td>
            <?php
              db_input( 'ed60_matricula',   15, $Ied60_matricula,   true, 'text',   $db_opcao, " onchange='js_pesquisaed69_i_matricula(false);'" );
              db_input( 'ed69_i_matricula', 15, $Ied69_i_matricula, true, 'hidden', 3 );
              db_input( 'ed47_v_nome',      50, $Ied47_v_nome,      true, 'text',   3 );
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?=$Led60_d_datamatricula?>
          </td>
          <td>
            <?db_input( 'datamatricula', 10, '', true, 'text', 3 );?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>
                <?php db_ancora( $Led69_i_turmaorigem, "", 3 );?>
              </legend>
              <table>
                <tr>
                  <td>
                    <b>Turma:</b>
                  </td>
                  <td>
                    <?php
                      db_input( 'ed69_i_turmaorigem', 15, $Ied69_i_turmaorigem, true, 'text',   3 );
                      db_input( 'ed57_c_origem',      20, '', true, 'text',   3 );
                      db_input( 'etapaorigem',        20, '', true, 'hidden', 3 );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Etapa:</b>
                  </td>
                  <td>
                    <?php db_input( 'ed11_c_origem', 30, '', true, 'text', 3 );?>
                    <b>Calendário:</b>
                    <?php db_input( 'ed52_c_origem', 20, '', true, 'text', 3 );?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Ensino:</b>
                  </td>
                  <td>
                    <?php db_input( 'ed10_c_origem', 40, $Ied10_c_descr, true, 'text', 3 );?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset class="separator">
              <legend>
                <?php db_ancora( $Led69_i_turmadestino, "js_pesquisaed69_i_turmadestino(true);", $db_opcao );?>
              </legend>
              <table>
                <tr id="linhaTurmaDestino">
                  <td>
                     <b>Turma:</b>
                  </td>
                  <td>
                    <?php
                      db_input( 'ed69_i_turmadestino', 15, $Ied69_i_turmadestino, true, 'text',   3, " onchange='js_pesquisaed69_i_turmadestino(false);'" );
                      db_input( 'ed57_c_destino',      20, '', true, 'text',   3 );
                      db_input( 'etapadestino',        20, '', true, 'hidden', 3 );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Etapa:</b>
                  </td>
                  <td>
                    <?php db_input( 'ed11_c_destino', 30, '', true, 'text', 3 );?>
                    <b>Calendário:</b>
                    <?php db_input( 'ed52_c_destino', 20, '', true, 'text', 3 );?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Ensino:</b>
                  </td>
                  <td>
                    <?php db_input( 'ed10_c_destino', 40, '', true, 'text', 3 );?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
  </div>
  <div>
    <iframe style="display: none;" id="iframe_trocaturma" name="iframe_trocaturma" src="" width="100%" height="800" frameborder="0"></iframe>
  </div>
</form>
<script>
var oTurmaTurno                    = null;
var sRPCTurmas                     = "edu4_turmas.RPC.php";
const CAMINHO_MENSAGENS_TROCATURMA = 'educacao.escola.db_frmalunotransfturma.';

function js_pesquisaed69_i_matricula(mostra) {

  document.getElementById("iframe_trocaturma").style.display = "none;";
  if ( mostra == true ) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_matricula',
                        'func_matriculatransf.php?funcao_js=parent.js_mostramatricula1|ed60_i_codigo'
                                                                                    +'|ed47_v_nome'
                                                                                    +'|ed60_i_turma'
                                                                                    +'|ed57_c_descr'
                                                                                    +'|dl_serie'
                                                                                    +'|ed10_c_descr'
                                                                                    +'|dl_calendario'
                                                                                    +'|ed60_d_datamatricula'
                                                                                    +'|etapaorigem'
                                                                                    +'|ed60_matricula',
                        'Pesquisa de Matrículas para Transferência de Turma',
                        true);
  } else {

    if (document.form1.ed60_matricula.value != '') {

     js_OpenJanelaIframe(
                          'top.corpo',
                          'db_iframe_matricula',
                          'func_matriculatransf.php?pesquisa_chave='+document.form1.ed60_matricula.value
                                                 +'&funcao_js=parent.js_mostramatricula',
                          'Pesquisa',
                          false
                        );
    } else {

      document.form1.ed47_v_nome.value         = '';
      document.form1.ed69_i_turmaorigem.value  = '';
      document.form1.ed57_c_origem.value       = '';
      document.form1.etapaorigem.value         = '';
      document.form1.ed11_c_origem.value       = '';
      document.form1.ed10_c_origem.value       = '';
      document.form1.ed52_c_origem.value       = '';
      document.form1.datamatricula.value       = '';
      document.form1.ed69_i_turmadestino.value = "";
      document.form1.etapadestino.value        = "";
      document.form1.ed57_c_destino.value      = "";
      document.form1.ed11_c_destino.value      = "";
      document.form1.ed10_c_destino.value      = "";
      document.form1.ed52_c_destino.value      = "";
    }
  }
}

function js_mostramatricula(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, erro, chave9) {

  document.form1.ed47_v_nome.value        = chave1
  document.form1.ed69_i_turmaorigem.value = chave2;
  document.form1.ed57_c_origem.value      = chave3;
  document.form1.ed11_c_origem.value      = chave4;
  document.form1.ed10_c_origem.value      = chave5;
  document.form1.ed52_c_origem.value      = chave6;
  document.form1.ed69_i_matricula.value   = chave9

  if ( chave7 != "" ) {
    document.form1.datamatricula.value = chave7.substr(8,2)+"/"+chave7.substr(5,2)+"/"+chave7.substr(0,4);
  } else {
    document.form1.datamatricula.value = '';
  }

  document.form1.etapaorigem.value         = chave8;
  document.form1.ed69_i_turmadestino.value = "";
  document.form1.etapadestino.value        = "";
  document.form1.ed57_c_destino.value      = "";
  document.form1.ed11_c_destino.value      = "";
  document.form1.ed10_c_destino.value      = "";
  document.form1.ed52_c_destino.value      = "";

  if ( erro == true ) {

    document.form1.ed60_matricula.focus();
    document.form1.ed69_i_matricula.value = '';
    document.form1.ed60_matricula.value   = '';
  }
}

function js_mostramatricula1( chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9, chave10 ) {

  document.form1.ed69_i_matricula.value   = chave1;
  document.form1.ed47_v_nome.value        = chave2;
  document.form1.ed69_i_turmaorigem.value = chave3;
  document.form1.ed57_c_origem.value      = chave4;
  document.form1.ed11_c_origem.value      = chave5;
  document.form1.ed10_c_origem.value      = chave6;
  document.form1.ed52_c_origem.value      = chave7;
  document.form1.ed60_matricula.value     = chave10;

  if ( chave8 != "" ) {
    document.form1.datamatricula.value = chave8.substr(8,2)+"/"+chave8.substr(5,2)+"/"+chave8.substr(0,4);
  }

  document.form1.etapaorigem.value         = chave9;
  document.form1.ed69_i_turmadestino.value = "";
  document.form1.etapadestino.value        = "";
  document.form1.ed57_c_destino.value      = "";
  document.form1.ed11_c_destino.value      = "";
  document.form1.ed10_c_destino.value      = "";
  document.form1.ed52_c_destino.value      = "";
  db_iframe_matricula.hide();
}

function js_pesquisaed69_i_turmadestino(mostra) {

  document.getElementById("iframe_trocaturma").style.display = "none;";

  if ( document.form1.ed69_i_matricula.value == "" ) {

    alert( _M( CAMINHO_MENSAGENS_TROCATURMA + 'informe_matricula' ) );
    document.form1.ed69_i_turmadestino.value              = '';
    document.form1.ed69_i_matricula.style.backgroundColor = '#99A9AE';
    document.form1.ed69_i_matricula.focus();
  } else {

    if ( mostra == true ) {
      js_OpenJanelaIframe(
                           'top.corpo',
                           'db_iframe_turma',
                           'func_turmatransf.php?turma='+document.form1.ed69_i_turmaorigem.value
                                              +'&turmasprogressao=f'
                                              +'&etapaorig='+document.form1.etapaorigem.value
                                              +'&matricula='+document.form1.ed69_i_matricula.value
                                              +'&funcao_js=parent.js_mostraturma1|ed57_i_codigo|ed57_c_descr|nomeetapa'
                                                                               +'|ed10_c_descr|ed57_i_calendario|codetapa',
                           'Pesquisa de Turmas',
                           true
                        );
    }
  }
}
function js_mostraturma1( chave1, chave2, chave3, chave4, chave5, chave6 ) {

  document.form1.ed69_i_turmadestino.value = chave1;
  document.form1.ed57_c_destino.value      = chave2;
  document.form1.ed11_c_destino.value      = chave3;
  document.form1.ed10_c_destino.value      = chave4;
  document.form1.ed52_c_destino.value      = chave5;
  document.form1.etapadestino.value        = chave6;
  db_iframe_turma.hide();

  if ( !empty( oTurmaTurno ) ) {
    oTurmaTurno.limpaLinhasCriadas();
  }

  oTurmaTurno = new DBViewFormularioEducacao.TurmaTurnoReferente( $('linhaTurmaDestino'), $('ed69_i_turmadestino' ).value );
  oTurmaTurno.show();
  compararRegenciasEntreTurmas();

}

/**
 * Compara entre duas Turmas se suas Disciplinas possuem o mesmo Procedimento de Avaliação vinculados há Regência
 */
function compararRegenciasEntreTurmas() {

  var oParametros              = new Object();
    oParametros.exec           = "comparaRegenciasEntreTurmas";
    oParametros.iTurmaAtual    = $F('ed69_i_turmaorigem');
    oParametros.iTurmaDestino  = $F('ed69_i_turmadestino');
    oParametros.sEtapasDestino = $F('etapadestino'); // pode ser mais de uma em caso de turma multetapa. Ex (1,2,3)
    oParametros.iEtapaOrigem   = $F('etapaorigem');

  var oAjaxRequest = new AjaxRequest( sRPCTurmas, oParametros, retornoCompararRegenciasEntreTurmas );
    oAjaxRequest.setMessage( _M( CAMINHO_MENSAGENS_TROCATURMA + 'comparando_procedimentos_turma' ) );
    oAjaxRequest.execute();
}

function retornoCompararRegenciasEntreTurmas( oRetorno, lErro ) {

  if ( oRetorno.lPossuiMesmoProcedimentos == false ) {


    if ($('iframe_trocaturma')) {

      $('iframe_trocaturma').style.display = 'none';
      $('iframe_trocaturma').contentWindow.document.body.innerHTML = '';
    }
    alert( _M( CAMINHO_MENSAGENS_TROCATURMA + 'procedimentos_diferentes_entre_turmas' ) );
    return;
  }

  dadosImportacao();
}

function dadosImportacao() {

  if ( !oTurmaTurno.temTurnoSelecionado() ) {

    document.getElementById('iframe_trocaturma').style.display = 'none';
    alert( _M( CAMINHO_MENSAGENS_TROCATURMA + 'sem_turno_selecionada' ) );
    return;
  }

  var sGet  = '';
      sGet += 'matricula='+document.form1.ed69_i_matricula.value
      sGet += '&turmaorigem='+document.form1.ed69_i_turmaorigem.value
      sGet += '&turmadestino='+document.form1.ed69_i_turmadestino.value
      sGet += '&codetapaorigem='+document.form1.etapaorigem.value
      sGet += '&iMatriculaOrigem='+document.form1.ed60_matricula.value;

  aTurnosSelecionados = new Array();
  for ( var iContador = 1; iContador <= 3; iContador++ ) {

    if ( $('check_turno' + iContador ) ) {

      if ( oTurmaTurno.getVagasDisponiveis( iContador ).length == 0 && $('check_turno' + iContador ).checked ) {

        $('check_turno' + iContador ).checked  = false;
        $('check_turno' + iContador ).readOnly = true;
      }

      if ( $('check_turno' + iContador ).checked ) {
        aTurnosSelecionados.push( iContador );
      }

      $('check_turno' + iContador ).setAttribute( "onclick", "dadosImportacao();" );
    }
  }

  if ( !oTurmaTurno.temVagasDisponiveis() ) {

    document.getElementById('iframe_trocaturma').style.display = 'none';
    alert( _M( CAMINHO_MENSAGENS_TROCATURMA + 'turma_sem_vagas' ) );
    return;
  }

  sGet += '&sTurno=' + aTurnosSelecionados.join( "," );

  iframe_trocaturma.location.href = 'edu1_alunotransfturma002.php?' + sGet;
  document.getElementById("iframe_trocaturma").style.display = "";
}

$("ed69_i_codigo").addClassName("field-size2");
$("ed60_matricula").addClassName("field-size2");
$("ed47_v_nome").addClassName("field-size7");
$("datamatricula").addClassName("field-size2");
$("ed69_i_turmaorigem").addClassName("field-size2");
$("ed57_c_origem").style.width = "340px";
$("ed11_c_origem").addClassName("field-size2");
$("ed10_c_origem").style.width = "426px";
$("ed52_c_origem").addClassName("field-size6");
$("ed69_i_turmadestino").addClassName("field-size2");
$("ed57_c_destino").style.width = "340px";
$("ed11_c_destino").addClassName("field-size2");
$("ed10_c_destino").style.width = "426px";
$("ed52_c_destino").addClassName("field-size6");

</script>
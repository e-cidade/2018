<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label("ed60_matricula");
$oRotulo->label("ed60_i_codigo");
$oRotulo->label("ed60_d_datamatricula");
$oRotulo->label("ed47_v_nome");
$oRotulo->label("ed60_i_turma");
$oRotulo->label("ed57_c_descr");
$oRotulo->label("ed11_i_codigo");
$oRotulo->label("ed10_c_descr");
$oRotulo->label("ed11_c_descr");
$oRotulo->label("ed52_c_descr");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
     db_app::load("scripts.js, prototype.js, datagrid.widget.js, strings.js, webseller.js");
     db_app::load("DBGridMultiCabecalho.widget.js, dbmessageBoard.widget.js, widgets/windowAux.widget.js");
     db_app::load("DBViewConsultaAvaliacoesAluno.classe.js, dbcomboBox.widget.js");
     db_app::load("estilos.css, grid.style.css");
    ?>
    <style>
      fieldset.separador {

        border: 0px;
        border-top: 2px groove white;
      }

      fieldset.separador table tr td:first-child {

        white-space: nowrap;
        width: 135px;
      }

    </style>
  </head>
  <body bgcolor="#CCCCCC" style='margin-top: 25px'>
    <form id='frmTrocaTurma'>
      <?php
        if (db_getsession("DB_modulo") == 1100747) {
          MsgAviso(db_getsession("DB_coddepto"),"escola");
        }
      ?>
      <center>
        <div style="display:table;">
          <fieldset>
            <legend><b>Troca de Turma Sem Registro de Movimentação</b></legend>
            <fieldset class="separador">
              <legend><b>Dados da Matrícula</b></legend>
              <table>
                <tr>
                  <td>
                  <?
                    db_ancora("Matrícula:", "js_pesquisaMatricula();", 1);
                  ?>
                  </td>
                  <td>
                  <?
                    db_input("ed60_matricula", 10, $Ied60_matricula, true, "text", 3);
                    db_input("ed60_i_codigo", 10, $Ied60_i_codigo, true, "hidden", 3);
                  ?>
                  </td>
                  <td colspan="2">
                  <?
                    db_input("ed47_v_nome", 60, $Ied47_v_nome, true, "text", 3);
                  ?>
                  </td>
                </tr>
                <tr>
                  <td><b>Turma:</b></td>
                  <td>
                  <?
                    db_input("ed60_i_turma", 10, $Ied60_i_turma, true, "text", 3);
                    db_input("ed11_i_codigo", 10, $Ied11_i_codigo, true, "hidden", 3);
                  ?>
                  </td>
                  <td  colspan="2" >
                  <?
                    db_input("ed57_c_descr", 40, $Ied57_c_descr, true, "text", 3, "", "", "", "width: 100%");
                  ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Ensino: </b>
                  </td>
                  <td colspan="3">
                    <?
                      db_input("ed10_c_descr", 50, $Ied10_c_descr, true, "text", 3, "", "", "", "width: 100%");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b><?=$Led11_c_descr?> </b>
                  </td>
                  <td>
                    <?
                      db_input("ed11_c_descr", 10, $Ied11_c_descr, true, "text", 3, "", "", "", "width: 100%");
                    ?>
                  </td>
                  <td style="text-align: right">
                    <b><?=$Led60_d_datamatricula?> </b>
                  </td>
                  <td>
                    <?
                      db_inputdata("ed60_d_datamatricula", '', '', '',true, "text", 3);
                    ?>
                  </td>
                </tr>
                 <tr>
                  <td>
                    <b><?=$Led52_c_descr?> </b>
                  </td>
                  <td colspan="3">
                    <?
                      db_input("ed52_c_descr", 50, $Ied52_c_descr, true, "text", 3, "", "", "", "width: 100%");
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <fieldset class="separador">
              <legend><b>Turma de Destino</b></legend>
              <table>
                <tr>
                  <td>
                  <?
                    db_ancora("Turma:", "js_pesquisaTurma();", 1);
                  ?>
                  </td>
                  <td>
                  <?
                    db_input("ed60_i_turma", 10, $Ied60_i_turma, true, "text", 3, "", "turma_destino");
                  ?>
                  </td>
                  <td  colspan="2" >
                  <?
                    db_input("ed57_c_descr", 60, $Ied57_c_descr, true, "text", 3, "", "nome_turma_destino",'', "width: 100%");
                  ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Ensino: </b>
                  </td>
                  <td colspan="3">
                    <?
                      db_input("ed10_c_descr", 50, $Ied10_c_descr, true, "text", 3, "", "ensino_destino", "", "width: 100%");
                    ?>
                  </td>
                </tr>
              </table>
            </fieldset>
          </fieldset>
              <div style="text-align: center;padding-top: 4px">
                <input type="button" id="btnAvaliacoes" name="btnAvaliacoes" value="Avaliações" >
              </div>
          <fieldset>
            <legend>
              <b>Disciplina(s) inconsistente(s) entre as turmas</b>
            </legend>
            <div id="ctnGridConflitos">
            </div>
          </fieldset>
        </div>
        <input type="button" id="btnSalvar" name="btnSalvar" value="Salvar" >
      </center>
    </form>
  </body>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script>
  $('ed60_d_datamatricula').style.width = '100%';

  var sUrlRpc                         = 'edu4_turmas.RPC.php';
  var lTurmaProcedimentoInconsistente = false;

  var oGridConflitos          = new DBGrid('gridConflitos');
  oGridConflitos.nameInstance = 'oGridConflitos';
  oGridConflitos.setHeader(new Array("Origem", "Destino", "regencia_origem"));
  oGridConflitos.setCellWidth(new Array("50%", "50%"));
  oGridConflitos.setHeight(200);
  oGridConflitos.aHeaders[2].lDisplayed = false;
  oGridConflitos.show($('ctnGridConflitos'));

  /**
   * Pesquisa as matrículas que podem realizar a troca de turma
   */
  function js_pesquisaMatricula() {

    js_OpenJanelaIframe(
                        'top.corpo',
                        'db_iframe_matricula',
                        'func_matriculatransf.php?funcao_js=parent.js_mostraMatricula|ed60_i_codigo'
                                                                                   +'|ed47_v_nome'
                                                                                   +'|ed60_i_turma'
                                                                                   +'|ed57_c_descr'
                                                                                   +'|dl_serie'
                                                                                   +'|ed10_c_descr'
                                                                                   +'|dl_calendario'
                                                                                   +'|ed60_d_datamatricula'
                                                                                   +'|etapaorigem'
                                                                                   +'|ed60_matricula',
                        'Pesquisa Matrícula',
                        true
                       );
  }

  function js_mostraMatricula() {

    $('ed60_i_codigo').value        = arguments[0];
    $('ed47_v_nome').value          = arguments[1];
    $('ed60_i_turma').value         = arguments[2];
    $('ed57_c_descr').value         = arguments[3];
    $('ed11_c_descr').value         = arguments[4];
    $('ed10_c_descr').value         = arguments[5];
    $('ed52_c_descr').value         = arguments[6];
    $('ed60_d_datamatricula').value = js_formatar(arguments[7], 'd') ;
    $('ed11_i_codigo').value        = arguments[8];
    $('ed60_matricula').value       = arguments[9];
    db_iframe_matricula.hide();
    js_pesquisaTurma();
  }

  /**
   * Pesquisa turmas compatíveis com a turma de origem do aluno
   */
  function js_pesquisaTurma() {

    if ($F('ed60_i_turma') != '' && $F('ed11_i_codigo') != '') {

      js_OpenJanelaIframe(
                          'top.corpo',
                          'db_iframe_turma',
                          'func_turmatransf.php?turmasprogressao=f'
                                             +'&apenasensinodaturma=1'
                                             +'&turma='+$F('ed60_i_turma')
                                             +'&etapaorig='+$F('ed11_i_codigo')
                                             +'&funcao_js=parent.js_mostraTurma|ed57_i_codigo|ed57_c_descr|ed10_c_descr',
                          'Escolha Nova Turma do Aluno',
                          true
                         );
    }
  }

  function js_mostraTurma() {

    $('turma_destino').value      = arguments[0];
    $('nome_turma_destino').value = arguments[1];
    $('ensino_destino').value     = arguments[2];
    db_iframe_turma.hide();
    js_getRegenciasInconsistentes();
  }

  /**
   * CallBack para as avaliações do aluno na turma de origem
   */
  $('btnAvaliacoes').observe("click", function() {

    var iMatricula = $F('ed60_i_codigo');

    delete oViewAvaliacao;

    if (iMatricula != "") {

      oViewAvaliacao = new DBViewConsultaAvaliacoesAluno("oViewAvaliacao", iMatricula);
      oViewAvaliacao.show();
    }
  });

  /**
   * Busca as regencias que possuem inconsistencia com as regencias da turma de destino
   */
  function js_getRegenciasInconsistentes() {

    var oParametro           = new Object();
    oParametro.exec          = 'getRegenciasTurmaInconsistente';
    oParametro.iMatricula    = $F('ed60_i_codigo');
    oParametro.iTurmaDestino = $F('turma_destino');

    js_divCarregando('Aguarde. Verificando possíveis inconsistências entre as disciplinas das turmas.', 'msgBox');
    var oAjax = new Ajax.Request(
                                 sUrlRpc,
                                 {
                                   method:     'post',
                                   parameters: 'json='+Object.toJSON(oParametro),
                                   onComplete: js_retornaRegenciasInconsistentes
                                 }
                                );
  }

  function js_retornaRegenciasInconsistentes(oResponse) {

    js_removeObj("msgBox");
    var oRetorno = eval('('+oResponse.responseText+')');

    oGridConflitos.clearAll(true);
    oGridConflitos.setStatus('');
    lTurmaProcedimentoInconsistente = oRetorno.lPeriodosInconsistentes;

    if (oRetorno.lPeriodosInconsistentes) {
      return false;
    }

    if (oRetorno.aRegenciasInconsistentes.length > 0) {

      oRetorno.aRegenciasInconsistentes.each(function(oLinha, iSeq) {

        var aLinha = new Array();
        aLinha[0]  = oLinha.sDisciplina.urlDecode();
        aLinha[1]  = js_getComboDisciplinasTurmaDestino(iSeq, oRetorno.aRegenciasDestino);
        if (oRetorno.aRegenciasDestino.length == 0) {
          aLinha[1] = 'Sem Disciplina para vínculo';
        }
        aLinha[2]  = oLinha.iCodigo;

        oGridConflitos.addRow(aLinha);
      });
      oGridConflitos.renderRows();
    }

    if (oRetorno.aRegenciasInconsistentes.length == 0) {
      oGridConflitos.setStatus('Sem disciplinas Inconsistentes.');
    }
  }

  /**
   * Preenche o combo com das disciplinas que podem ser vinculadas na turma de destino
   */
  function js_getComboDisciplinasTurmaDestino(iLinha, aDisciplinasTurmaDestino) {

    var oCombo = new DBComboBox('oCombo'+iLinha, 'oCombo'+iLinha, new Array(), '100%');
    oCombo.addItem('', '');
    aDisciplinasTurmaDestino.each(function(oDisciplina, iSeq) {
      oCombo.addItem(oDisciplina.iCodigo, oDisciplina.sDisciplina.urlDecode());
    });
    return oCombo;
  }

  /**
   * CallBack para realizar a troca de turma do aluno.
   */
  $('btnSalvar').observe("click", function() {

    var sNomeAluno        = $F('ed47_v_nome').trim();
    var sNomeTurmaDestino = $F('nome_turma_destino').trim();
    if ($F('ed60_i_codigo') == '') {

      alert('Escolha um aluno para realizar a troca de turma.');
      js_pesquisaMatricula();
      return false;
    }

    if ($F('turma_destino') == '') {

      alert('Escolha uma nova turma para o aluno '+sNomeAluno+'.');
      js_pesquisaTurma();
      return false;
    }

    var aDisciplinasVinculadas         = new Array();
    var sMensagemDisciplinasSemVinculo = '';
    oGridConflitos.aRows.each(function(oLinha, iSeq) {

      var oDadosVinculo            = new Object();
      oDadosVinculo.iCodigoOrigem  = oLinha.aCells[2].getValue();
      oDadosVinculo.iCodigoDestino = oLinha.aCells[1].getValue();
      var oCombo                   = $(oLinha.aCells[1].sId).childNodes[0];
      if (oCombo.nodeName != 'SELECT') {
        oDadosVinculo.iCodigoDestino = '';
      }
      if (oDadosVinculo.iCodigoDestino == '') {

        sMensagemDisciplinasSemVinculo += "  "+oLinha.aCells[0].getValue().trim()+"\n";
      }
      aDisciplinasVinculadas.push(oDadosVinculo);
    });

    /**
     * Validamos se o usuario marcou a mesma disciplina da turma de destino para varias disciplinas na turma de origem
     */
    var lVinculoDuplo   = false;
    var sNomeDisciplina = '';
    if (aDisciplinasVinculadas.length > 0) {

      aDisciplinasVinculadas.each(function(oLinha, iSeq) {

        var oLinhaGrid = oGridConflitos.aRows[iSeq];
        aDisciplinasVinculadas.each(function(oOutroVinculo, iOutroContador) {

           if (iOutroContador > iSeq &&
               (oLinha.iCodigoDestino != '' && oLinha.iCodigoDestino == oOutroVinculo.iCodigoDestino)) {

             lVinculoDuplo   = true;
             var oCombo      = $(oLinhaGrid.aCells[1].sId).childNodes[0];
             if (oCombo.nodeName == 'SELECT') {
               sNomeDisciplina = oCombo.options[oCombo.selectedIndex].innerHTML;
             }
           }
        });
      });

      if (lVinculoDuplo) {

        var sMensagemVinculoDuplo  = 'A disciplina '+sNomeDisciplina+' não pode ser vinculada a mais de uma disciplina ';
        sMensagemVinculoDuplo     += 'da turma origem';
        alert(sMensagemVinculoDuplo);
        return false;
      }
    }

    var sMensagemConfirmacaoTrocaTurma = 'O aluno '+sNomeAluno+' será trocado para a turma '+sNomeTurmaDestino+'.';
    if (sMensagemDisciplinasSemVinculo != '') {

      sMensagemConfirmacaoTrocaTurma += ' As disciplinas abaixo, não foram vinculadas na turma de destino.\n';
      sMensagemConfirmacaoTrocaTurma += "As mesmas não terão seu aproveitamento importado.\n";
      sMensagemConfirmacaoTrocaTurma += sMensagemDisciplinasSemVinculo;
    }

    if (lTurmaProcedimentoInconsistente) {

      var sMensagemProcedimento       = '\nO sistema não permitira a importação das avaliações do aluno, pois o procedimento ';
      sMensagemProcedimento          += 'de avaliação da turma de origem difere do procedimento da turma de destino.';
      sMensagemConfirmacaoTrocaTurma += sMensagemProcedimento;
    }

    sMensagemConfirmacaoTrocaTurma += "\nConfirma a troca de turma?";
    if (!confirm(sMensagemConfirmacaoTrocaTurma)) {
      return false;
    }

    /**
     * Chamada para salvar a troca de turma
     */
    var oParametro                    = {};
    oParametro.exec                   = 'salvarTrocaTurmaSemRegistro';
    oParametro.iMatricula             = $F('ed60_i_codigo');
    oParametro.iTurmaDestino          = $F('turma_destino');
    oParametro.aDisciplinasVinculadas = aDisciplinasVinculadas;

    var sMensagemSalvando = 'Aguarde, realizando a troca de turma do aluno '+sNomeAluno+' para a turma '+sNomeTurmaDestino;
    js_divCarregando(sMensagemSalvando, "msgBox");
    $('btnSalvar').disabled = true;
    var oAjax = new Ajax.Request(
                                 sUrlRpc,
                                 {
                                   method:     'post',
                                   parameters: 'json='+Object.toJSON(oParametro),
                                   onComplete: js_retornoSalvarVinculos
                                 }
                                );
  });

  function js_retornoSalvarVinculos(oResponse) {

    js_removeObj("msgBox");
    $('btnSalvar').disabled = false;
    var oRetorno = eval('('+oResponse.responseText+')');

    if (oRetorno.status == 1) {

      alert("Troca de Turma realizada com sucesso.");
      $('frmTrocaTurma').reset();
      oGridConflitos.clearAll(true);
      js_pesquisaMatricula();
    } else {

      alert(oRetorno.message.urlDecode());
      return false;
    }
  }

  js_pesquisaMatricula();
</script>
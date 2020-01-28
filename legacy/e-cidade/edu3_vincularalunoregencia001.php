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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_stdlibwebseller.php");

$clrotulo = new rotulocampo;
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed57_c_descr");
$clrotulo->label("ed60_i_turma");
$clrotulo->label("ed52_c_descr");
$clrotulo->label("ed57_i_calendario");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed29_c_descr");
$clrotulo->label("ed223_i_serie");
$clrotulo->label("ed11_c_descr");
$clrotulo->label("ed57_i_turno");
$clrotulo->label("ed15_c_nome");
$clrotulo->label("ed57_i_nummatr");

?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?
	  db_app::load("scripts.js");
	  db_app::load("prototype.js");
	  db_app::load("widgets/windowAux.widget.js");
	  db_app::load("strings.js");
	  db_app::load("dbcomboBox.widget.js");
	  db_app::load("dbtextField.widget.js");
	  db_app::load("dbtextFieldData.widget.js");
	  db_app::load("DBGridMultiCabecalho.widget.js");
	  db_app::load("dbmessageBoard.widget.js");
	  db_app::load("datagrid.widget.js");
	  db_app::load("webseller.js");
	  db_app::load("estilos.css, grid.style.css");
	?>
  <script type="text/javascript" src="scripts/classes/educacao/DBViewFormularioEducacao.classe.js"></script>
  <script type="text/javascript" src="scripts/classes/educacao/escola/TurmaTurnoReferente.classe.js"></script>
	<style>
	 fieldset.separator {border:0px;border-top: 2px groove white}
	 button.btnMove {border:1px solid #999999; width: 40px}
	</style>
</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<center>
  <div style='display: table; width: 70%'>
    <fieldset>
      <legend>
        <b>Vincular Alunos de Progressão à Turma</b>
      </legend>
      <table>
        <tr>
          </td>
          <td>
            <?db_ancora($Led60_i_turma, "js_pesquisaed60_i_turma();", '');?>
          </td>
          <td colspan='3'>
            <?db_input('ed60_i_turma', 10, $Ied60_i_turma, true, 'text', 3, '')?>
            <?db_input('ed57_c_descr', 30, $Ied57_c_descr, true, 'text', 3, '')?>
          </td>
          <td>
            <?=$Led57_i_calendario?>
          </td>
          <td>
            <?db_input('ed52_c_descr', 20, $Ied52_c_descr, true, 'text', 3, '')?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted31_i_curso?>">
            <?=$Led31_i_curso?>
          </td>
          <td colspan='3'>
            <?db_input('ed29_c_descr', '40', $Ied29_c_descr, true, 'text', 3, '')?>
          </td>
          <td>
            <?=$Led223_i_serie?>
          </td>
          <td>
            <?db_input('ed11_c_descr', 10, $Ied11_c_descr, true, 'text', 3, '')?>
          </td>
        </tr>
        <tr id="linhaTurno">
          <td>
            <?=$Led57_i_turno?>
          </td>
          <td colspan='3'>
            <?db_input('ed15_c_nome', 20, $Ied15_c_nome, true, 'text', 3, '')?>
          </td>
        </tr>
      </table>
    </fieldset>
      <center>
        <input type='button' value='Vincular Alunos' onclick="js_abrirJanelavincularAlunos()">
      </center>
    <div>
      <fieldset>
        <legend>
          <b>Alunos Vínculados</b>
        </legend>
        <div id='ctnGridAlunos'></div>
      </fieldset>
    </div>
  </div>
</center>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
<script>
var sUrlRPC       = 'edu4_vincularalunoturma.RPC.php';
var sDataDia      = '<?=date("d/m/Y", db_getsession("DB_datausu"));?>';
var oTurmaTurno   = null;
var iCodigoEtapa;

$('ed29_c_descr').style.width   = '100%';
$('ed52_c_descr').style.width   = '100%';
$('ed15_c_nome').style.width    = '100%';

oDbGridAlunosVinculados = new DBGridMultiCabecalho('gridAlunosVinculados');
oDbGridAlunosVinculados.nameInstance = 'oDbGridAlunosVinculados';
oDbGridAlunosVinculados.setCellWidth(new Array('15%', '30%', '30%', '15%', '10%'));
oDbGridAlunosVinculados.setHeader(new Array('Cod.', 'Aluno', 'Disciplina', 'Vínculo', 'Ação'));
oDbGridAlunosVinculados.show($('ctnGridAlunos'));
aDisciplinasVinculadas = new Array();

function js_pesquisaed60_i_turma() {

  js_OpenJanelaIframe('',
                      'db_iframe_turma',
                      'func_turmadependencia.php?funcao_js=parent.js_getDadosTurma|ed57_i_codigo|ed11_i_codigo',
		                  'Pesquisa de Turmas',
		                  true
		             );
}


function js_abrirJanelavincularAlunos() {

  if ($F('ed60_i_turma') == '') {

    alert('selecione uma turma para realizar o vínculo.');
    js_pesquisaed60_i_turma();
    return false;
  }
  aDisciplinasVinculadas = new Array();
  oWindowVinculoAluno    = new windowAux("wndVinculoAluno",
                                         "Vincular Alunos",
                                         800,
                                         500);

  closeWindow = function () {

    delete aDisciplinasVinculadas;
    aDisciplinasVinculadas = new Array();
    oWindowVinculoAluno.destroy();
  }
  oWindowVinculoAluno.setShutDownFunction(closeWindow);

  var sConteudo  = '<div>';
  sConteudo     += '  <fieldset>';
  sConteudo     += '    <legend>';
  sConteudo     += '      <b>Vincular Alunos</b>';
  sConteudo     += '    </legend>';
  sConteudo     += '    <table>';
  sConteudo     += '      <tr>';
  sConteudo     += '        <td>';
  sConteudo     += '           <b>Buscar alunos da rede:</b>';
  sConteudo     += '        </td>';
  sConteudo     += '        <td id="ctnCboAlunosRede" colspan="3">';
  sConteudo     += '        </td>';
  sConteudo     += '      </tr>';
  sConteudo     += '      <tr>';
  sConteudo     += '        <td>';
  sConteudo     += '           <b>Disciplina:</b>';
  sConteudo     += '        </td>';
  sConteudo     += '        <td id="ctnComboDisciplinas">';
  sConteudo     += '        </td>';
  sConteudo     += '        <td>';
  sConteudo     += '           <b>Data:</b>';
  sConteudo     += '        </td>';
  sConteudo     += '        <td id="ctnDataVinculo">';
  sConteudo     += '        </td>';
  sConteudo     += '      </tr>';
  sConteudo     += '    </table>';
  sConteudo     += '    <fieldset class="separator">';
  sConteudo     += '      <legend>';
  sConteudo     += '        <b>Alunos com Dependência</b>';
  sConteudo     += '      </legend>';
  sConteudo     += '      <table style="width:100%">';
  sConteudo     += '      <tr>';
  sConteudo     += '        <td id="ctnAlunos" style="width:48%"></td>';
  sConteudo     += '        <td style="width:5%">';
  sConteudo     += '          <button type="button" class="btnMove" id="btnMoveOneRightToLeft">&gt;</button><br>';
  sConteudo     += '          <button type="button" class="btnMove" id="btnMoveAllRightToLeft">&gt;&gt;</button><br>';
  sConteudo     += '          <button type="button" class="btnMove" id="btnMoveOneLeftToRight">&lt;</button><br>';
  sConteudo     += '          <button type="button" class="btnMove" id="btnMoveAllLeftToRight">&lt;&lt;</button>';
  sConteudo     += '      </td>';
  sConteudo     += '        <td id="ctnAlunosSelecionados" style="width:48%"></td>';
  sConteudo     += '        </tr>';
  sConteudo     += '      </table>';
  sConteudo     += '    </fieldset>';
  sConteudo     += '  </fieldset>';
  sConteudo     += '  <center>';
  sConteudo     += '    <input type="button" value="Vincular" id="btnVincular" onclick="js_vincularAlunos()">';
  sConteudo     += '    <input type="button" value="Fechar"   id="btnFechar" onclick="closeWindow()">';
  sConteudo     += '  </center>';
  sConteudo     += '</div>';
  oWindowVinculoAluno.setContent(sConteudo);


  oCboAlunosRede = new DBComboBox ('oCboAlunosRede', 'oCboAlunosRede', new Array(), 200);
  oCboAlunosRede.addEvent('onChange', 'js_getAlunosComProgressaoSemVinculo()');
  oCboAlunosRede.show($('ctnCboAlunosRede'));
  oCboAlunosRede.clearItens();
  oCboAlunosRede.addItem(1, 'Não');
  oCboAlunosRede.addItem(2, 'Sim');
  oCboAlunosRede.setValue(1);


  oCboDisciplinas = new DBComboBox ('oCboDisciplinas', 'oCboDisciplinas', new Array(), 200);
  oCboDisciplinas.addEvent('onChange', 'js_getAlunosComProgressaoSemVinculo()');
  oCboDisciplinas.show($('ctnComboDisciplinas'));

  oCboAlunos = new DBComboBox ('oCboAlunos', 'oCboAlunos', new Array(), '100%', 10);
  oCboAlunos.setMultiple(true);
  oCboAlunos.addEvent("onDblClick", "moveSelected(oCboAlunos, oCboAlunosSelecionados, js_adicionarDisciplina)");
  oCboAlunos.show($('ctnAlunos'));


  oTxtDataVinculo = new DBTextFieldData('oTxtDataVinculo','oTxtDataVinculo', sDataDia);
  oTxtDataVinculo.show($('ctnDataVinculo'));

  oCboAlunosSelecionados = new DBComboBox('oCboAlunosSelecionados',
                                          'oCboAlunosSelecionadoss',
                                           new Array(),
                                           '100%',
                                           10);
  oCboAlunosSelecionados.setMultiple(true);
  oCboAlunosSelecionados.addEvent("onDblClick", "moveSelected(oCboAlunosSelecionados, oCboAlunos, js_removerDisciplina)");
  oCboAlunosSelecionados.show($('ctnAlunosSelecionados'));

  oMessageBoard = new DBMessageBoard('msgBoardVinculo',
                                     'Escolha os alunos para vincular na turma '+$F('ed57_c_descr'),
                                     'Selecione uma disciplina para listar os alunos com dependência nessa etapa.',
                                      oWindowVinculoAluno.getContentContainer()
                                    );
  oWindowVinculoAluno.show();

  $('btnMoveOneRightToLeft').observe("click", function() {

    moveSelected(oCboAlunos, oCboAlunosSelecionados, js_adicionarDisciplina);
  });

  $('btnMoveAllRightToLeft').observe("click", function() {
    moveAll(oCboAlunos, oCboAlunosSelecionados, js_adicionarDisciplina);
  });

  $('btnMoveOneLeftToRight').observe("click", function() {

    moveSelected(oCboAlunosSelecionados, oCboAlunos, js_removerDisciplina);
  });

  $('btnMoveAllLeftToRight').observe("click", function() {
    moveAll(oCboAlunosSelecionados, oCboAlunos, js_removerDisciplina);
  });

  js_getRegenciasTurma();
}

function js_getRegenciasTurma() {

  var oParametro          = new Object();
  oParametro.exec         = 'getDisciplinaTurma';
  oParametro.iCodigoTurma = $F('ed60_i_turma');
  oParametro.iEtapa       = iCodigoEtapa;
  js_divCarregando('Aguarde, carregando dados da turma.', 'msgBox');
  var oAjax = new Ajax.Request(sUrlRPC,
                    {method:'post',
                     parameters:'json='+Object.toJSON(oParametro),
                     onComplete: function (oResponse) {

                       js_removeObj('msgBox');
                       var oRetorno = eval('('+oResponse.responseText+')');
                       oCboDisciplinas.clearItens();
                       oCboDisciplinas.addItem(0, 'Selecione um Disciplina');
                       oRetorno.aDisciplinas.each(function (oDisciplina, iSeq) {
                         oCboDisciplinas.addItem(oDisciplina.iRegencia, oDisciplina.sDescricaoDisciplina.urlDecode());
                       });
                       if (oRetorno.aDisciplinas.length == 1) {

                         oCboDisciplinas.setValue(oRetorno.aDisciplinas[0].iRegencia);
                         js_getAlunosComProgressaoSemVinculo();

                       }
                     }
                    });
  delete oAjax;
}

function moveSelected(oComboOrigin, oComboDestiny, callback) {

  if (oComboOrigin.getValue() != null) {

    var aItens = oComboOrigin.getValue();
    aItens.each(function(oItem, iSeq) {

      if (oItem.value != "") {
        var lAdicionar = callback(oItem, oCboDisciplinas.getValue());
        oItem = oComboOrigin.aItens[oItem];
        if (!lAdicionar) {
          oComboDestiny.addItem(oItem.id, oItem.descricao);
        }
        oComboOrigin.removeItem(oItem.id);
      }
    });
  }
}

function moveAll(oComboOrigin, oComboDestiny, callback) {

   oComboOrigin.aItens.each(function(oItem, iSeq) {

     if (oItem.id != "") {

       lAdicionar = callback(oItem.id, oCboDisciplinas.getValue());
       if (!lAdicionar) {
         oComboDestiny.addItem(oItem.id, oItem.descricao);
       }
       oComboOrigin.removeItem(oItem.id);
     }
  });
}

function js_getDadosTurma(iCodigoTurma, iEtapa) {

  db_iframe_turma.hide();

  var oParametro          = new Object();
  oParametro.exec         = 'getDadosTurma';
  oParametro.iCodigoTurma = iCodigoTurma;
  oParametro.iEtapa       = iEtapa;
  iCodigoEtapa            = iEtapa;
  js_divCarregando('Aguarde, carregando dados da turma.', 'msgBox');
  var oAjax = new Ajax.Request(sUrlRPC,
                    {method:'post',
                     parameters:'json='+Object.toJSON(oParametro),
                     onComplete: js_preencheDadosTurma
                    });
  delete oAjax;
}

function js_preencheDadosTurma (oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oResponse.responseText+')');

  $('ed60_i_turma').value   = oRetorno.oDadosTurma.iCodigoTurma;
  $('ed57_c_descr').value   = oRetorno.oDadosTurma.sNomeTurma.urlDecode();
  $('ed52_c_descr').value   = oRetorno.oDadosTurma.sCalendario.urlDecode();
  $('ed29_c_descr').value   = oRetorno.oDadosTurma.sCurso.urlDecode();
  $('ed11_c_descr').value   = oRetorno.oDadosTurma.aEtapas[0].sEtapa.urlDecode();
  $('ed15_c_nome').value    = oRetorno.oDadosTurma.sTurno.urlDecode();

  carregaTurno();
  js_getAlunosVinculados();
}

function js_getAlunosVinculados() {

  var oParametro          = new Object();
  oParametro.exec         = 'getAlunosVinculados';
  oParametro.iCodigoTurma = $F('ed60_i_turma');
  oParametro.iEtapa       = iCodigoEtapa;
  js_divCarregando('Aguarde, carregando alunos vinculados a turma.', 'msgBox');
  new Ajax.Request(sUrlRPC,
                  {method:'post',
                   parameters:'json='+Object.toJSON(oParametro),
                   onComplete: js_preencherAlunosVinculados
                  });
}

function js_preencherAlunosVinculados(oResponse) {

  js_removeObj('msgBox');
  oDbGridAlunosVinculados.clearAll(true);
  var oRetorno  = eval("("+oResponse.responseText+")");
  oRetorno.dados.each(function(oAluno, iSeq) {


    var aLinha    = new Array();
    var lComNota       = oAluno.temResultadoFinal;
    var lDisabled      = '';
    var sFuncaoExcluir = "onclick=\"js_removerVinculo('"+oAluno.iCodigoProgressaoParcial+"',"+lComNota+")\"";
    if (oAluno.encerrado) {

       lDisabled      = " disabled ";
       sFuncaoExcluir = '';
    }
    aLinha[0]    = oAluno.iCodigoAluno;
    aLinha[1]    = oAluno.sNomeAluno.urlDecode();
    aLinha[2]    = oAluno.sDisciplina.urlDecode();
    aLinha[3]    = js_formatar(oAluno.dtVinculo, 'd');
    aLinha[4]    = '<input type="button" value="E" '+lDisabled+' '+sFuncaoExcluir+'>';
    oDbGridAlunosVinculados.addRow(aLinha);
    if (oAluno.encerrado) {
      oDbGridAlunosVinculados.aRows[iSeq].setClassName('disabled');
    }
  });
  oDbGridAlunosVinculados.renderRows();
}

function js_removerVinculo(iCodigoProgressao, lTemNota) {

  var sMensagem = '';
  if (lTemNota) {
     sMensagem += "O aluno possui resultado lançado para essa disciplina.\n";

  }
  sMensagem += 'Confirma a remoção do vínculo?';
  if (!confirm(sMensagem)) {
    return false;
  }

  var oParametro                      = new Object();
  oParametro.exec                     = 'removerVinculo';
  oParametro.iCodigoProgressaoParcial = iCodigoProgressao;
  js_divCarregando('Aguarde, removendo o vínculo selecionado.', 'msgBox');
  new Ajax.Request(sUrlRPC,
                  {method:'post',
                   parameters:'json='+Object.toJSON(oParametro),
                   onComplete: js_retornoRemoverVinculo
                  });
}

function js_retornoRemoverVinculo(oResponse) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {

    alert('Vínculo Removido com sucesso!');
    js_getDadosTurma($F('ed60_i_turma'), iCodigoEtapa);


  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_getAlunosComProgressaoSemVinculo() {

  var iDisciplina = $F('oCboDisciplinas');
  if (iDisciplina == 0) {
    return;
  }

  var oParametro           = new Object();
  oParametro.exec          = 'getAlunosParaVincular';
  oParametro.iCodigoTurma  = $F('ed60_i_turma');
  oParametro.iRegencia     = iDisciplina;
  oParametro.iEtapa        = iCodigoEtapa;
  oParametro.iAlunosEscola = $F('oCboAlunosRede');
  js_divCarregando('Aguarde, carregando alunos', 'msgBox');
  aAlunosVinculados = new Array();
  for (var i = 0; i < $('oCboAlunosSelecionados').options.length; i++) {
    aAlunosVinculados.push($('oCboAlunosSelecionados').options[i].value);
  }
  var oAjax = new Ajax.Request(sUrlRPC,
                    {method:'post',
                     parameters:'json='+Object.toJSON(oParametro),
                     onComplete: function (oResponse) {

                       js_removeObj('msgBox');
                       var oRetorno = eval('('+oResponse.responseText+')');
                       oCboAlunos.clearItens();
                       oRetorno.aAlunos.each(function (oAluno, iSeq) {

                          if (!js_search_in_array(aAlunosVinculados, oAluno.iCodigoProgressao)) {

                            var sAluno = oAluno.sNomeAluno.urlDecode()+"("+oAluno.sDisciplina.urlDecode()+")";
                            oCboAlunos.addItem(oAluno.iCodigoProgressao, sAluno);
                          }
                        });
                      }
                    });
  delete oAjax;
}


function js_vincularAlunos() {

  if (oTxtDataVinculo.getValue() == "") {

    alert("Informe a data do vínculo.");
    return false;
  }
  var aAlunos = new Array();
  aDisciplinasVinculadas.each(function(oVinculo, iSeq) {

    var oAluno                      = new Object();
    oAluno.iCodigoProgressaoParcial = oVinculo.iCodigoProgressao;
    oAluno.iRegencia                = oVinculo.iDisciplina;
    oAluno.dtVinculo                = oTxtDataVinculo.getValue();
    aAlunos.push(oAluno);
  });

  if (aAlunos.length == 0) {

    alert("Nenhum aluno selecionado para vincular");
    return false;
  }

  if (!confirm("Confirma o vinculo dos alunos selecionados a turma?")) {
    return false;
  }
  var oParametro       = new Object();
  oParametro.exec      = 'vincular';
  oParametro.aVincular = aAlunos;
  js_divCarregando('Aguarde, vinculando alunos com a turma', 'msgBox');
  var oAjax = new Ajax.Request(sUrlRPC,
                              {method:'post',
                               parameters:'json='+Object.toJSON(oParametro),
                               onComplete: js_retornoVinculo
                              });
  delete oAjax;
}

function  js_retornoVinculo (oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oResponse.responseText+')');
  if (oRetorno.status == 1) {

    alert("Vínculos realizados com sucesso.");
    oWindowVinculoAluno.destroy();
    js_getDadosTurma($F('ed60_i_turma'), iCodigoEtapa);
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

aDisciplinasVinculadas = new Array();
function js_getVinculoDisciplina(iProgressao, iDisciplina) {

  var oVinculoRetorno = '';
  aDisciplinasVinculadas.each(function(oVinculo, iSeq) {

    if (oVinculo.iCodigoProgressao == iProgressao && oVinculo.iDisciplina == iDisciplina) {

      oVinculoRetorno = oVinculo;
      throw $break;
    }
  });
  return oVinculoRetorno;
}

function js_removerDisciplina(iProgressao, iDisciplina) {

  if (iProgressao == '') {
    return true;
  }
  var lRemover = false;
  aDisciplinasVinculadas.each(function(oVinculo, iSeq) {

    if (oVinculo.iCodigoProgressao == iProgressao) {

      if (iDisciplina != oVinculo.iDisciplina) {
        lRemover = true;
      }
      aDisciplinasVinculadas.splice(iSeq, 1);
      throw $break;
    }
  });
  return lRemover;
}

function js_adicionarDisciplina(iProgressao, iDisciplina) {

  if (iProgressao == null) {
    return true;
  }
  if (js_getVinculoDisciplina() == '') {

    oVinculo                   = new Object();
    oVinculo.iCodigoProgressao = iProgressao;
    oVinculo.iDisciplina       = iDisciplina;
    aDisciplinasVinculadas.push(oVinculo);
  }
  return false;
}

/**
 * Carrega a linha com as informações dos turnos referentes
 */
function carregaTurno() {

  if ( !empty( oTurmaTurno ) ) {
    oTurmaTurno.limpaLinhasCriadas();
  }

  oTurmaTurno = new DBViewFormularioEducacao.TurmaTurnoReferente( $('linhaTurno'), $('ed60_i_turma').value );
  oTurmaTurno.escondeLinhasTurnoTurma();
  oTurmaTurno.show();

  var aTurnosSelecionados = new Array();
  for ( var iContador = 1; iContador <= 3; iContador++ ) {

    if ( $('check_turno' + iContador ) ) {

      if ( oTurmaTurno.getVagasDisponiveis( iContador ).length == 0 && $('check_turno' + iContador ).checked ) {

        $('check_turno' + iContador ).checked  = false;
        $('check_turno' + iContador ).readOnly = true;
      }

      if ( $('check_turno' + iContador ).checked ) {
        aTurnosSelecionados.push( iContador );
      }
    }
  }
}

js_pesquisaed60_i_turma();
</script>
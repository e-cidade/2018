<?
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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_regenciahorario_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_rechumano_classe.php"));

$oDaoRegenciaHorario = db_utils::getDao("regenciahorario");
$oDaoRegenciaHorario->rotulo->label();

$oDaoCgm = db_utils::getDao("cgm");
$oDaoCgm->rotulo->label();

$oDaoRecHumano = db_utils::getDao("rechumano");
$oDaoRecHumano->rotulo->label();

?>
<form name="form1" id='frmVinculaProfessorDisciplina' method="post" style="display:none">
  <center>
    <br>
      <fieldset style="width:25%">
        <legend><b>Disciplinas:</b></legend>
        <table>
          <tr>
            <td id='ctnDisciplinas'></td>
            <td>
              <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;</button><br>
              <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
              <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
              <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
            </td>
            <td id='ctnDisciplinasSelecionadas'></td>
          </tr>
        </table>
      </fieldset>
      <table border="0">
        <tr>
          <td><b>Turno: </b></td>
          <td id="ctnTurno"></td>
        </tr>
        <tr>
          <td>
            <?php
            db_ancora("<b>Regente: </b>", "js_pesquisaRecHumano(true)", 1);
            ?>
          </td>
          <td>
            <?php
            db_input("ed20_i_codigo", 10, $Ied20_i_codigo, true, "text", 1, "onChange='js_pesquisaRecHumano(false);'");
            ?>
          </td>
          <td>
            <?php
            db_input("z01_nome", 40, $Iz01_nome, true, "text", 3);
            ?>
          </td>
        </tr>
      </table>
    <input type="button" id="btnVincular" name="btnVincular" value="Vincular" />
  </center>
</form>
<div id="divVinculos" style="display:none">
<center>
  <fieldset style="width:75%">
    <legend><b>Vínculos Realizados</b></legend>
    <div id="ctnGridVinculos"></div>
  </fieldset>
  <div>
    <table border="0">
    <tr>
      <td><b>Informe o regente conselheiro desta turma: </b></td>
      <td id="ctnConselheiro"></td>
    </tr>
    </table>
  </div>
</center>
</div>
<script>
var iTurma         = <?=$ed59_i_turma?>;
var iEtapa         = <?=$ed59_i_serie?>;
var sUrlRpcRegente = 'edu4_regente.RPC.php';
var sUrlRpcTurmas  = 'edu4_turmas.RPC.php';

var oCboDisciplinas = new DBComboBox("cboDisciplinas", "oCboDisciplinas", null, "200px", 10);
oCboDisciplinas.setMultiple(true);
oCboDisciplinas.addEvent("onDblClick", "moveSelected(oCboDisciplinas, oCboDisciplinasSelecionadas)");
oCboDisciplinas.show($('ctnDisciplinas'));

var oCboDisciplinasSelecionadas = new DBComboBox("cboDisciplinasSelecionadas", "oCboDisciplinasSelecionadas", null, "200px", 10);
oCboDisciplinasSelecionadas.setMultiple(true);
oCboDisciplinasSelecionadas.addEvent("onDblClick", "moveSelected(oCboDisciplinasSelecionadas, oCboDisciplinas)");
oCboDisciplinasSelecionadas.show($('ctnDisciplinasSelecionadas'));

var oCboTurno = new DBComboBox("cboTurno", "oCboTurno", null, "92px");
oCboTurno.show($('ctnTurno'));

var oCboConselheiro = new DBComboBox("cboConselheiro", "oCboConselheiro", null, "400px");
oCboConselheiro.addEvent("onChange", "js_salvarRegenteConselheiro()");
oCboConselheiro.show($('ctnConselheiro'));

var oDataGridVinculos          = new DBGrid("gridVinculos");
oDataGridVinculos.nameInstance = "oDataGridVinculos";
oDataGridVinculos.setCellWidth(new Array("520px", "350px", "100px", "80px", "50px"));
oDataGridVinculos.setCellAlign(new Array("left", "left", "left", "left", "center"));
oDataGridVinculos.setHeader(new Array("Regente", "Disciplina(s)", "Turno", "Conselheiro", "Ação"));
oDataGridVinculos.show($('ctnGridVinculos'));

/**
 * Busca as disciplinas da turma que nao tenham nenhum professor vinculado
 */
function js_pesquisaDisciplinas() {

  var oParametro    = new Object();
  oParametro.exec   = 'buscaDisciplinasParaVincularComRegente';
  oParametro.iTurma = iTurma;
  oParametro.iEtapa = iEtapa;

  js_divCarregando("Aguarde, pesquisando as disciplinas possíveis de serem vinculadas.", "msgBox");
  var oAjax = new Ajax.Request(
                                sUrlRpcRegente,
                                {
                                  method: 'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornaPesquisaDisciplinas
                                }
                              );
}

/**
 * Retorno da busca das disciplinas
 */
function js_retornaPesquisaDisciplinas(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aDisciplinas.length > 0) {

    oRetorno.aDisciplinas.each(function(oLinha, iSeq) {
      oCboDisciplinas.addItem(oLinha.iRegencia, oLinha.sDescricao.urlDecode());
    });
  }
}

/**
 * Pesquisa os vinculos ja existentes na turma, para disciplina/regente
 */
function js_buscaVinculosRealizados() {

  var oParametro    = new Object();
  oParametro.exec   = 'buscaVinculosRealizados';
  oParametro.iTurma = iTurma;
  oParametro.iEtapa = iEtapa;

  js_divCarregando("Aguarde, pesquisando os vínculos realizados.", "msgBox");
  var oAjax = new Ajax.Request(
                                sUrlRpcRegente,
                                {
                                  method: 'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornaBuscaVinculosRealizados
                                }
                              );
}

/**
 * Retorno da pesquisa pelos vinculos ja realizados
 */
function js_retornaBuscaVinculosRealizados(oResponse) {

  js_removeObj("msgBox");
  oDataGridVinculos.clearAll(true);
  oCboConselheiro.clearItens();
  oCboConselheiro.addItem("", "");
  
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aDados.length > 0) {

    oRetorno.aDados.each(function(oLinha, iSeq) {

      var lConselheiroAdicionado = false;
      if (oCboConselheiro.aItens.length == 0) {
        oCboConselheiro.addItem(oLinha.iRecHumano, oLinha.sRegente.urlDecode());
      } else {
        
        oCboConselheiro.aItens.each(function(oConselheiro, iSeq) {

          if (oConselheiro.id == oLinha.iRecHumano) {
            lConselheiroAdicionado = true;
          }
        });

        if (!lConselheiroAdicionado) {
          oCboConselheiro.addItem(oLinha.iRecHumano, oLinha.sRegente.urlDecode());
        }
      }
      
      var sConselheiro = "Não";
      if (oLinha.lConselheiro) {

        sConselheiro = "Sim";
        oCboConselheiro.setValue(oLinha.iRecHumano);
      }
      
      var aLinha = new Array();
      aLinha[0]  = oLinha.sRegente.urlDecode();
      aLinha[1]  = oLinha.sDisciplina.urlDecode();
      aLinha[2]  = oLinha.sTurno.urlDecode();
      aLinha[3]  = sConselheiro;
      aLinha[4]  = '<input type="button" value="E" onclick="js_desvincularRegenteDisciplina('+oLinha.iRegencia
                                                                                        +', '+oLinha.iRecHumano
                                                                                      +', \''+oLinha.sRegente.urlDecode()
                                                                                      +'\', \''+oLinha.sDisciplina.urlDecode()+'\')" />';

      oDataGridVinculos.addRow(aLinha);
    });
  }
  oDataGridVinculos.renderRows();
}

/**
 * Salva o regente conselheiro escolhido para a turma
 */
function js_salvarRegenteConselheiro() {

  var oParametro        = new Object();
  oParametro.exec       = 'salvarRegenteConselheiro';
  oParametro.iTurma     = iTurma;
  oParametro.iRecHumano = oCboConselheiro.getValue();

  js_divCarregando("Aguarde, salvando o Regente Conselheiro da turma.", "msgBox");
  var oAjax = new Ajax.Request(
                                sUrlRpcRegente,
                                {
                                  method: 'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornaSalvarRegenteConselheiro
                                }
                              );
}

function js_retornaSalvarRegenteConselheiro(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.status != 2) {
    alert('Regente Conselheiro salvo com sucesso.');
  } else {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  js_buscaVinculosRealizados();
}

/**
 * Busca o turno vinculado a turma
 */
function js_pesquisaTurnoTurma() {

  var oParametro    = new Object();
  oParametro.exec   = 'pesquisaTurno';
  oParametro.iTurma = iTurma;

  js_divCarregando("Aguarde, pesquisando o turno em que se encontra a turma.", "msgBox");
  var oAjax = new Ajax.Request(
                                sUrlRpcTurmas,
                                {
                                  method: 'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornaPesquisaTurnoTurma
                                }
                              );
}

function js_retornaPesquisaTurnoTurma(oResponse) {

  js_removeObj("msgBox");
  oCboTurno.clearItens();
  
  var oRetorno = eval('('+oResponse.responseText+')');

  oRetorno.aTurnos.each (function(oTurno, iSeq) {
    oCboTurno.addItem(oTurno.iCodigo, oTurno.sTurno.urlDecode());
  });
}

/**
 * Pesquisa o rechumano do regente
 */
function js_pesquisaRecHumano(lMostra) {

  var iTurno = $F('cboTurno');

  if (lMostra) {

    js_OpenJanelaIframe('',
      'db_iframe_rechumano',
      'func_rechumanovinculodisciplina.php?funcao_js=parent.js_mostrarechumano1|ed20_i_codigo|z01_nome'
      + '&iTurma=' + iTurma + '&iTurno=' + iTurno,
      'Pesquisa Regente',
      true
    );

  } else {

    if (document.form1.ed20_i_codigo.value != '') {
      js_OpenJanelaIframe('',
        'db_iframe_rechumano',
        'func_rechumanovinculodisciplina.php?pesquisa_chave=' + document.form1.ed20_i_codigo.value
        + '&funcao_js=parent.js_mostrarechumano'
        + '&iTurma=' + iTurma + '&iTurno=' + iTurno,
        'Pesquisa',
        false
      );

    } else {
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostrarechumano(chave1, erro) {

  document.form1.z01_nome.value = chave1;
  if (erro == true) {
    
    document.form1.ed20_i_codigo.focus();
    document.form1.ed20_i_codigo.value = '';
    document.form1.z01_nome.value      = chave1;
  }
}

function js_mostrarechumano1(chave1,chave2) {
	
  document.form1.ed20_i_codigo.value = chave1;
  document.form1.z01_nome.value      = chave2;
  db_iframe_rechumano.hide();
}
 
/**
 * Controla ações de movimentos entre os select box
 */
function moveSelected(oCboOrigem, oCboDestino) {
  
	if(oCboOrigem.getValue() != null) {

		var aItens = oCboOrigem.getValue();
		aItens.each(function(oLinha, iContador) {

			oLinha = oCboOrigem.aItens[oLinha];
			oCboDestino.addItem(oLinha.id, oLinha.descricao);
			oCboOrigem.removeItem(oLinha.id);
		});
	}
}

function moveAll(oCboOrigem, oCboDestino) {

	oCboOrigem.aItens.each(function(oLinha, iContador) {

		oCboDestino.addItem(oLinha.id, oLinha.descricao);
		oCboOrigem.removeItem(oLinha.id);
	});
}

$('btnMoveOneRightToLeft').observe("click", function() {
	moveSelected(oCboDisciplinas, oCboDisciplinasSelecionadas);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboDisciplinasSelecionadas, oCboDisciplinas);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboDisciplinas, oCboDisciplinasSelecionadas);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboDisciplinasSelecionadas, oCboDisciplinas);
});

/**
 * Salva os vínculos selecionados
 */
function js_salvarVinculos(aDisciplinasParaVincular) {

  var oParametro        = new Object();
  oParametro.exec       = 'vincularRegenteDisciplina';
  oParametro.iRecHumano = $('ed20_i_codigo').value;
  oParametro.aRegencias = aDisciplinasParaVincular;
  oParametro.iTurma     = iTurma;

  js_divCarregando("Aguarde, vinculando Regente com a(s) disciplina(s) selecionada(s).", "msgBox");
  var oAjax = new Ajax.Request(
                                sUrlRpcRegente,
                                {
                                  method: 'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornoSalvarVinculos
                                }
                              );
}

/**
 * Retorno dos vinculos salvos ou com erro
 */
function js_retornoSalvarVinculos(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.status != 2) {
    alert('Vínculos salvos com sucesso.');
  } else {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  js_limparDados();
  js_init();
}

/**
 * Remove o vinculo selecionado
 */
function js_desvincularRegenteDisciplina(iRegencia, iRecHumano, sRegente, sDisciplina) {

  var sMsg = "Confirma a exclusão do vínculo do regente "+sRegente+" com a disciplina "+sDisciplina+" ?";
  if (confirm(sMsg)) {
    
    var oParametro        = new Object();
    oParametro.exec       = 'desvincularRegenteDisciplina';
    oParametro.iRegencia  = iRegencia;
    oParametro.iTurma     = iTurma;
    oParametro.iRecHumano = iRecHumano;
  
    var oAjax = new Ajax.Request(
                                  sUrlRpcRegente,
                                  {
                                    method:     'post',
                                    parameters: 'json='+Object.toJSON(oParametro),
                                    onComplete: js_retornaDesvincularRegenteDisciplina
                                  }
                                );
  }
}

/**
 * Retorno da remocao do vinculo
 */
function js_retornaDesvincularRegenteDisciplina(oResponse) {

   var oRetorno = eval('('+oResponse.responseText+')');

   if (oRetorno.status != 2) {
     alert('Vínculo removido com sucesso.');
   } else {

     alert(oRetorno.message.urlDecode());
     return false;
   }

   js_limparDados();
   js_init();
}

$('btnVincular').observe("click", function(event) {

  var aDisciplinasParaVincular = new Array();

  oCboDisciplinasSelecionadas.aItens.each(function(oItem, iSeq) {

    if (oItem.id !== "") {
      aDisciplinasParaVincular.push(oItem.id);
    }
  });

  if (aDisciplinasParaVincular == null || aDisciplinasParaVincular.length == 0) {

    alert('Nenhuma disciplina selecionada');
    return false;
  }

  if ($('ed20_i_codigo').value == "") {

    alert('É necessário informar um regente para vincular a(s) disciplina(s).');
    return false;
  }

  js_salvarVinculos(aDisciplinasParaVincular);
});

function js_limparDados() {

  oCboDisciplinas.clearItens();
  oCboDisciplinasSelecionadas.clearItens();
  $('ed20_i_codigo').value = '';
  $('z01_nome').value      = '';
  oCboTurno.clearItens();
}

function js_init() {
  
  js_pesquisaDisciplinas();
  js_pesquisaTurnoTurma();
  js_buscaVinculosRealizados();
}

js_init();
</script>
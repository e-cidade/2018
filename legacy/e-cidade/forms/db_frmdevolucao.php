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

//MODULO: biblioteca
$cldevolucaoacervo->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("bi18_carteira");
$oRotulo->label("bi23_codigo");
$oRotulo->label("bi23_codbarras");
$oRotulo->label("ov02_nome");
$opcao = 1;
?>

<form class="container" name="form2" method="post" action="">
  <fieldset>
    <legend>Devolução de Acervos</legend>
    <fieldset class="separator">
      <legend>Escolha uma das opções:</legend>
      <table class="form-container">
        <tbody>
          <tr>
            <td>
              <label for="bi18_carteira"> <?php db_ancora($Lbi18_carteira, "pesquisaCarteira(true);", $opcao);?> </label>
            </td>
            <td>
              <?php
                db_input('bi18_carteira', 10, $Ibi18_carteira, true, 'text', $opcao, "onchange='pesquisaCarteira(false);'");
                db_input('ov02_nome',     50, $Iov02_nome, true, 'text', 3, "");
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="codigo"><?php db_ancora('Exemplar:', "pesquisaExemplar(true);", $opcao);?></label>
            </td>
            <td>
              <input type='text', class="field-size2" name='codigo' id='codigo' />
              <input type='text', class="field-size8 readonly" name='titulo' id='titulo' disabled="disabled" />
              <input type="submit" name="proximo" id="proximo" value="Próximo" style="visibility:hidden;position:absolute;" />
            </td>
          </tr>
          <tr id='cntPesquisaExemplarBarras' style="display:none;">
            <td colspan="2">
              <b>Pesquisar por Código de Barras:</b>
              <input type="text"   name="bi23_codbarras" value="" size="20" onChange="js_codbarras();" id='bi23_codbarras'>
              <input type="button" name="lancarbarras" value="Pesquisar" size="" onClick="js_codbarras();">
              <iframe src="" name="iframe_verificadata" id="iframe_verificadata" width="0" height="0" frameborder="0"></iframe>
            </td>
          </tr>
        </tbody>
      </table>
    </fieldset>

  </fieldset>
</form>
<div class="subcontainer" style="width:1000px;">
  <fieldset>
    <legend>Exemplar(es) Emprestado(s)</legend>
    <div id='ctnGridExemplares'> </div>
  </fieldset>
  <label for='chkComprovanteDevolucao'> Emitir Comprovante </label>
  <input type='checkbox' name='chkComprovanteDevolucao' id='chkComprovanteDevolucao'/>
  <input name="confirma" type="button" id="btnConfirmar" value="Confirmar Devolução" onclick="devolver();">
  <input name="renovar"  type="button" id="btnRenovar"   value="Renovar Empréstimo" onclick="renovar();">
  <input name="cancelar" type="button" id="btnCancelar"  value="Cancelar" onclick="location='bib1_devolucao001.php'">
</div>

<div id='qualquer'></div>
<script type="text/javascript">


$('bi18_carteira').addClassName('field-size2');
$('ov02_nome').addClassName('field-size8');

$('codigo').addEventListener('input', function (event) {
  js_ValidaCampos(this,1,'Código do Exemplar','f','f',event);
});

$('codigo').addEventListener('change', function (event) {
  pesquisaExemplar(false);
});

var oConfiguracao    = {};
var aListaExemplares = [];

var oGrid      = new DBGrid('ctnGridExemplares');
var aHeaders   = [ 'Cód. Barras', 'Título', 'Leitor', 'Emprestado', 'Devolver', 'emprestimo', 'emprestimoacervo' ];
var aCellWidth = [ '15%', '30%', '30%', '12%', '13%' ];
var aCellAlign = [ 'center', 'left', 'left', 'center', 'center' ];

oGrid.nameInstance = 'oGrid';
oGrid.setCheckbox(6);
oGrid.setCellWidth(aCellWidth);
oGrid.setCellAlign(aCellAlign);
oGrid.setHeader(aHeaders);
oGrid.aHeaders[6].lDisplayed = false;
oGrid.aHeaders[7].lDisplayed = false;
oGrid.setHeight(130);
oGrid.show($('ctnGridExemplares'));

(function() {

  situacaoBotoes(false);

  var oAjax = new AjaxRequest('bib4_biblioteca.RPC.php', {exec: 'buscaPametros'},
    function (oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.sMessage);
        return;
      }
      oConfiguracao = oRetorno.oConfiguracao;
      if ( oRetorno.oConfiguracao.lLeitorBarras ) {
        $('cntPesquisaExemplarBarras').style.display = 'table-row';
      }
    }
  );
  oAjax.setMessage('Buscando parâmetros biblioteca...');
  oAjax.execute();
})();


/**
 * Pesquisa os empréstimos por leitor
 */
function pesquisaCarteira(lMostra) {

  var sUrl = 'func_leitorproc.php?lNaoValidaCarteira=true';
  if (lMostra) {

    sUrl += '&funcao_js=parent.js_mostraleitor1|bi16_codigo|ov02_nome';
    js_OpenJanelaIframe('', 'db_iframe_leitor', sUrl, 'Pesquisa Leitor', true);
  } else if ( $F('bi18_carteira') != '' ) {

      sUrl += '&funcao_js=parent.js_mostraleitor';
      sUrl += '&pesquisa_chave=' + $F('bi18_carteira');
      js_OpenJanelaIframe('', 'db_iframe_leitor', sUrl, 'Pesquisa Leitor', false);
  } else {
    limparFormPesquisa(true);
  }
}

function limparFormPesquisa( lLeitor, lExemplar ) {

  if ( lLeitor ) {

    $('bi18_carteira').value = '';
    $('ov02_nome').value   = '';
  }

  if ( lExemplar ) {

    $('codigo').value         = '';
    $('titulo').value         = '';
    $('bi23_codbarras').value = '';
  }
}

function limparTudo() {

  limparFormPesquisa(true, true);
  oGrid.clearAll(true);
  aListaExemplares = [];
}

function js_mostraleitor(chave, erro) {

  $('ov02_nome').value = chave;
  limparFormPesquisa(false, true);

  if (erro) {

    $('bi18_carteira').focus();
    $('bi18_carteira').value = '';
  } else {
    buscarEmprestimos();
  }
}

function js_mostraleitor1 (chave1, chave2) {

  $('bi18_carteira').value = chave1;
  $('ov02_nome').value     = chave2;

  limparFormPesquisa(false, true);
  db_iframe_leitor.hide();
  buscarEmprestimos();
}

/**
 * Pesquisa os empréstimos por exemplar
 */
function pesquisaExemplar( lMostra ) {

  var sUrl = 'func_exemplardevol.php';
  if ( lMostra ) {

    sUrl += '?funcao_js=parent.js_mostraexemplar1|bi23_codigo|bi06_titulo';
    js_OpenJanelaIframe('', 'db_iframe_exemplar', sUrl, 'Pesquisa Exemplar', true);

  } else if ($F('codigo') != '') {

    sUrl += '?funcao_js=parent.js_mostraexemplar';
    sUrl += '&pesquisa_chave='+$F('codigo');
    js_OpenJanelaIframe('', 'db_iframe_exemplar', sUrl, 'Pesquisa Exemplar', false);
  } else {
    limparFormPesquisa(false, true);
  }
}

function js_mostraexemplar(chave1,erro) {

  $('bi23_codbarras').value = '';
  limparFormPesquisa(true, false);
  $('titulo').value = chave1;

  if ( erro ) {

    $('codigo').value = '';
    $('codigo').focus();
  }else {
    buscarEmprestimos();
  }
}

function js_mostraexemplar1(chave1,chave2) {

  $('bi23_codbarras').value = '';
  limparFormPesquisa(true, false);

  $('codigo').value = chave1;
  $('titulo').value = chave2;
  db_iframe_exemplar.hide();
  buscarEmprestimos()
}


function situacaoBotoes(lLiberar) {

  $('btnConfirmar').setAttribute('disabled', 'disabled');
  $('btnRenovar').setAttribute('disabled', 'disabled');
  $('btnCancelar').setAttribute('disabled', 'disabled');

  if (lLiberar) {

    $('btnConfirmar').removeAttribute('disabled', 'disabled');
    $('btnRenovar').removeAttribute('disabled', 'disabled');
    $('btnCancelar').removeAttribute('disabled', 'disabled');
  }
}
function buscarEmprestimos() {

  var oParametros = {exec: 'buscarEmprestimoParaDevolucao'};
  oParametros.iCodigoCarteira = $F('bi18_carteira');
  oParametros.iCodigoExemplar = $F('codigo');
  oParametros.iBiblioteca     = oConfiguracao.iBiblioteca;

  aListaExemplares = [];
  oGrid.clearAll(true);
  var oAjax = new AjaxRequest('bib4_emprestimo.RPC.php', oParametros,
    function (oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.sMessage);
        situacaoBotoes(false);
        return;
      }
      montarGradeEmprestimo(oRetorno.aEmprestimos);
    }
  );

  oAjax.setMessage('Buscando parâmetros biblioteca...');
  oAjax.execute();
}

function montarGradeEmprestimo(aEmprestimos) {

  aListaExemplares = aEmprestimos;
  situacaoBotoes(true);
  aEmprestimos.each( function(oEmprestimo) {

    var aLinha = [];
    aLinha.push(oEmprestimo.codigo_barras);
    aLinha.push(oEmprestimo.titulo);
    aLinha.push(oEmprestimo.leitor);
    aLinha.push( js_formatar( oEmprestimo.data_retirada, 'd') );
    aLinha.push( js_formatar( oEmprestimo.data_devolucao, 'd') );
    aLinha.push(oEmprestimo.emprestimo);
    aLinha.push(oEmprestimo.emprestimoacervo);

    oGrid.addRow(aLinha);
  });

  oGrid.renderRows();
  oGrid.setHighlight();

  oParametros = {iWidth:'200', oPosition : {sVertical : 'T', sHorizontal : 'R'}};
  aEmprestimos.each( function(oEmprestimo, i) {

    oGrid.aRows[i].aCells[2].addClassName('elipse');
    oGrid.aRows[i].aCells[3].addClassName('elipse');

    var sStyle = 'form-sucess';
    if ( oEmprestimo.lVencido ) {
      sStyle   = 'form-error';
    }

    oGrid.aRows[i].aCells[5].addClassName(sStyle);
    oGrid.setHint(i, 2, oEmprestimo.titulo,  oParametros);
    oGrid.setHint(i, 3, oEmprestimo.leitor,  oParametros);
  });
}

function buscarExemplaresSelecionados() {

  var aSelecionados = [];

  oGrid.getSelection('object').each(function (oLinhaSelecionada) {

    aSelecionados.push( {'iEmprestimoAcervo' : oLinhaSelecionada.aCells[0].getValue(),
                         'iEmprestimo'       : oLinhaSelecionada.aCells[6].getValue(),
                         'iNumeroLinha'      : oLinhaSelecionada.getRowNumber()});
  });

  if ( aSelecionados.length == 0) {

    alert('Nenhum Exemplar selecionado.');
    return;
  }
  return aSelecionados;
}

function devolver() {

  aSelecionados = buscarExemplaresSelecionados();

  if (aSelecionados.length == 0) {
    return false;
  }

  var oAjax = new AjaxRequest('bib4_emprestimo.RPC.php', {'exec': 'devolver', 'aEmprestimos' : aSelecionados },
    function (oRetorno, lErro) {

      alert(oRetorno.sMessage);
      if (lErro) {
        return;
      }

      if ($('chkComprovanteDevolucao').checked) {
        imprimirComprovanteDevolucao(aSelecionados);
      }

      if ( aSelecionados.length == oGrid.getNumRows() ) {

        limparTudo();
        return;
      }

      var aLinhasRemover = [];
      aSelecionados.each( function( oSelecionado ) {
        aLinhasRemover.push(oSelecionado.iNumeroLinha);
      });

      oGrid.removeRow(aLinhasRemover);
      oGrid.renderizar();
    }
  );

  oAjax.setMessage('Realizando devolução, aguarde...');
  oAjax.execute();
}

function imprimirComprovanteDevolucao(aSelecionados) {

  var aDevolvidos = [];
  for ( var oSelecionado of aSelecionados ) {
    aDevolvidos.push(oSelecionado.iEmprestimo);
  }

  var sUrl  = 'bib2_emprestimo002.php';
      sUrl += '?emp=' + aDevolvidos.implode(',');
      sUrl += '&tipo=1';
  window.open(sUrl,'','scrollbars=1,location=0 ');
}

function renovar() {

  var aItensRenovar = [];
  var aListaNoPrazo = [];
  $$('input[type="checkbox"]:checked').each(function(oElement) {

    aListaExemplares.each( function(oDados) {

      if (oDados.emprestimoacervo == oElement.value) {

        aItensRenovar.push(oDados);
        if ( !oDados.lVencido ) {
          aListaNoPrazo.push(oDados.titulo);
        }
      }
    });
  });

  if (aItensRenovar.length == 0){

    alert('Nenhum exemplar selecionado para renovar.');
    return;
  }

  if ( aListaNoPrazo.length > 0) {

    sLista    = aListaNoPrazo.implode(', ');
    var sMsg  = 'O(s) exemplar(es) "' + sLista + '"  possui(em) data de devolução maior que a data atual.\n';
        sMsg += 'Confirma a renovação para este(s) exemplar(es)?';
    if ( !confirm(sMsg) ) {
      return;
    }
  }

  var oRenovacao = new DBViewRenovacao (oConfiguracao, aItensRenovar);
  oRenovacao.show();
}

function js_codbarras() {

  if ($F('bi23_codbarras') != "") {
    iframe_verificadata.location = "bib1_devolucao002.php?bi23_codbarras="+$F('bi23_codbarras');
  }
}
</script>
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("prototype.js, scripts.js, strings.js, datagrid.widget.js, dbcomboBox.widget.js");
    db_app::load("estilos.css");
    ?>
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px;">
    <form class="container" method="post" action="">
      <div>
        <fieldset>
          <legend class="bold">Dados para vincular o aluno</legend>
            <table>
              <tr>
               <td>
                  <label class="bold">Escola:</label>
                </td>
                <td id="ctnCboEscola" colspan="2">
               </td>
              </tr>
              <tr>
                <td><a href="#" onClick='js_pesquisaLinhas(true);' class="bold">Linha:</a></td>
                <td id="inputCodigoLinha"></td>
                <td id="inputDescricaoLinha"></td>
              </tr>
              <tr>
                <td><label class="bold">Itinerário:</label></td>
                <td id="selectItinerario" colspan="2"></td>
              </tr>
              <tr>
                <td><a href="#" onClick='js_pesquisaPontosParada(true);' class="bold">Ponto de Parada:</a></td>
                <td id="inputCodigoPontoParada"></td>
                <td id="inputDescricaoPontoParada"></td>
              </tr>
              <tr>
                <td><a href="#" onClick='js_pesquisaAluno(true);' class="bold">Aluno:</a></td>
                <td id="inputCodigoAluno"></td>
                <td id="inputDescricaoAluno"></td>
              </tr>
            </table>
        </fieldset>
      </div>
      <div>
        <input type="button" id="btnAdicionarAluno" value="Adicionar"/>
      </div>
      <div>
        <fieldset style="width: 1000px">
          <legend class="bold">Vínculos do aluno</legend>
          <div id="gridAlunoPontoParada">
          </div>
        </fieldset>
      </div>
      <div>
        <input type="button" id="btnRemoverAluno" value="Remover Selecionados"/>
      </div>
    </form>
  </body>
</html>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
var sUrlRpc = 'tre4_pontoparada.RPC.php';

/**
 * Elementos INPUT
 */

var oCboEscola = new DBComboBox("cboEscola", "oCboEscola", null, "385px");
oCboEscola.addItem("", "Selecione");
oCboEscola.addEvent("onChange", "js_limparCampos();");
oCboEscola.show($('ctnCboEscola'));

var oInputCodigoLinha                       = document.createElement('input');
    oInputCodigoLinha.id                    = 'oInputCodigoLinha';
    oInputCodigoLinha.style.backgroundColor = '#DEB887';
    oInputCodigoLinha.readOnly               = true;
    oInputCodigoLinha.addClassName('field-size2');
$('inputCodigoLinha').appendChild(oInputCodigoLinha);

var oInputDescricaoLinha                       = document.createElement('input');
    oInputDescricaoLinha.id                    = 'oInputDescricaoLinha';
    oInputDescricaoLinha.readOnly              = true;
    oInputDescricaoLinha.style.backgroundColor = '#DEB887';
    oInputDescricaoLinha.addClassName('field-size7');
$('inputDescricaoLinha').appendChild(oInputDescricaoLinha);

var oInputCodigoPontoParada             = document.createElement('input');
    oInputCodigoPontoParada.id          = 'oInputCodigoPontoParada';
    oInputCodigoPontoParada.addClassName('field-size2');
$('inputCodigoPontoParada').appendChild(oInputCodigoPontoParada);

var oInputCodigoItinerarioPontoParada               = document.createElement('input');
    oInputCodigoItinerarioPontoParada.id            = 'oInputCodigoItinerarioPontoParada';
    oInputCodigoItinerarioPontoParada.style.display = 'none';
$('inputCodigoPontoParada').appendChild(oInputCodigoItinerarioPontoParada);

var oInputDescricaoPontoParada                       = document.createElement('input');
    oInputDescricaoPontoParada.id                    = 'oInputDescricaoPontoParada';
    oInputDescricaoPontoParada.readOnly              = true;
    oInputDescricaoPontoParada.style.backgroundColor = '#DEB887';
    oInputDescricaoPontoParada.addClassName('field-size7');
$('inputDescricaoPontoParada').appendChild(oInputDescricaoPontoParada);

var oInputCodigoAluno                       = document.createElement('input');
    oInputCodigoAluno.id                    = 'oInputCodigoAluno';
    oInputCodigoAluno.readOnly              = true;
    oInputCodigoAluno.style.backgroundColor = '#DEB887';
    oInputCodigoAluno.addClassName('field-size2');
$('inputCodigoAluno').appendChild(oInputCodigoAluno);

var oInputDescricaoAluno                       = document.createElement('input');
    oInputDescricaoAluno.id                    = 'oInputDescricaoAluno';
    oInputDescricaoAluno.readOnly              = true;
    oInputDescricaoAluno.style.backgroundColor = '#DEB887';
    oInputDescricaoAluno.addClassName('field-size7');
$('inputDescricaoAluno').appendChild(oInputDescricaoAluno);

/**
 * Elemento SELECT
 */
var oSelectItinerario             = document.createElement('select');
    oSelectItinerario.id          = 'oSelectItinerario';
    oSelectItinerario.style.width = '100%';
    oSelectItinerario.add(new Option('Ida', 1));
    oSelectItinerario.add(new Option('Volta', 2));
$('selectItinerario').appendChild(oSelectItinerario);

/**
 * Grid dos vínculos dos alunos
 */
var oGridVinculosAluno              = new DBGrid('gridVinculosAluno');
    oGridVinculosAluno.nameInstance = 'oGridVinculosAluno';
    oGridVinculosAluno.setCheckbox(0);
    oGridVinculosAluno.setHeader(new Array("Código Ponto Parada", "Código", "Aluno", "Ponto de Parada", "Escola", "Itinerário"));
    oGridVinculosAluno.setCellAlign(new Array("center", "center", "left", "left", "left", "left"));
    oGridVinculosAluno.setCellWidth(new Array("0%", "3%", "50%", "20%", "20%", "7%"));
    oGridVinculosAluno.aHeaders[1].lDisplayed = false;
    oGridVinculosAluno.aHeaders[2].lDisplayed = false;
    oGridVinculosAluno.show($('gridAlunoPontoParada'));

/**
 * Eventos dos inputs
 */
$('oInputCodigoLinha').observe("change", function() {
  js_pesquisaLinhas(false);
});

$('oInputCodigoPontoParada').observe("change", function() {
  js_pesquisaPontosParada(false);
});

$('oInputCodigoAluno').observe("change", function() {
  js_pesquisaAluno(false);
});

$('btnAdicionarAluno').observe("click", function() {
  js_adicionaVinculo();
});

$('btnRemoverAluno').observe('click', function() {
  js_removeVinculo();
})

$('oSelectItinerario').observe("change", function() {

  oInputCodigoPontoParada.value           = '';
  oInputDescricaoPontoParada.value        = '';
  oInputCodigoItinerarioPontoParada.value = '';
  oInputCodigoAluno.value                 = '';
  oInputDescricaoAluno.value              = '';
});

/**
 * Pesquisa as linhas cadastradas
 */
function js_pesquisaLinhas(lMostra) {

  if ($F('cboEscola') == '') {

    alert(_M('educacao.transporteescolar.tre4_vincularalunos.codigo_escola_vazio'));
    return false;
  }

  var sUrl  = 'func_linhatransporte.php?funcao_js=parent.js_mostraLinhas';

  if (lMostra) {
    sUrl += '|tre06_sequencial|tre06_nome';
  } else {

    if (!empty(oInputCodigoLinha.value)) {
      sUrl += '&pesquisa_chave='+oInputCodigoLinha.value;
    } else {
      oInputDescricaoLinha.value = '';
    }
  }
  sUrl += '&iEscola='+cboEscola.value;
  
  js_OpenJanelaIframe('top.corpo', 'db_iframe_linhatransporte', sUrl, 'Pesquisa Linhas de Transporte', lMostra);
}

/**
 * Retorno das linhas cadastradas
 */
function js_mostraLinhas() {

  js_limparCampos();
  db_iframe_linhatransporte.hide();
  if (arguments[1] !== true && arguments[1] !== false) {

    oInputCodigoLinha.value    = arguments[0];
    oInputDescricaoLinha.value = arguments[1];
  }

  if (arguments[1] === true) {

    oInputCodigoLinha.value    = '';
    oInputDescricaoLinha.value = arguments[0];
  }

  if (arguments[1] === false) {
    oInputDescricaoLinha.value = arguments[0];
  }

  js_getAlunosVinculados();
}

/**
 * Pesquisa um ponto de parada vinculado a linha de transporte e itinerário selecionados
 */
function js_pesquisaPontosParada(lMostra) {

  if (empty(oInputCodigoLinha.value)) {

    oInputCodigoPontoParada.value = '';
    alert(_M('educacao.transporteescolar.tre4_vincularalunos.codigo_linha_vazio'));
    return false;
  }

  var sUrl  = 'func_pontoparada.php?lPontoParadaLinhaTransporteItinerario&iLinhaTransporte='+oInputCodigoLinha.value;
      sUrl += '&iItinerario='+oSelectItinerario.value+'&funcao_js=parent.js_mostraPontoParada';

  if (lMostra) {
    sUrl += '|tre04_sequencial|tre04_nome|tre11_sequencial';
  } else {

    if (!empty(oInputCodigoPontoParada.value)) {
      sUrl += '&pesquisa_chave='+oInputCodigoPontoParada.value;
    } else {
      oInputDescricaoPontoParada.value = '';
    }
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_pontoparada', sUrl, 'Pesquisa Pontos de Parada', lMostra);
}

/**
 * Retorna os pontos de parada vinculados
 */
function js_mostraPontoParada() {

  db_iframe_pontoparada.hide();

  if (arguments[1] !== true && arguments[1] !== false) {

    oInputCodigoPontoParada.value           = arguments[0];
    oInputDescricaoPontoParada.value        = arguments[1];
    oInputCodigoItinerarioPontoParada.value = arguments[2];
  }

  if (arguments[1] === true) {

    oInputCodigoItinerarioPontoParada.value = ''
    oInputCodigoPontoParada.value           = '';
    oInputDescricaoPontoParada.value        = arguments[0];
  }

  if (arguments[1] === false) {
    oInputDescricaoPontoParada.value        = arguments[2];
    oInputCodigoItinerarioPontoParada.value = arguments[3];
  }
}

/**
 * Pesquisa os alunos da rede que utilizam transporte público e que seja municipal
 */
function js_pesquisaAluno(lMostra) {

  if ($F('cboEscola') == '') {

    alert(_M('educacao.transporteescolar.tre4_vincularalunos.codigo_escola_vazio'));
    return false;
  }

  var sUrl  = 'func_aluno.php?lPesquisaTransportePublico&iTransporte=2&iUtilizaTransporte=1';
      sUrl += '&iEscola='+ $F('cboEscola');
      sUrl += '&funcao_js=parent.js_mostraAluno';
      
  if (lMostra) {
	  
	var sAlunos = '0';

	oGridVinculosAluno.aRows.each(function(oRow) {

		if (($F('oSelectItinerario') == 1 && oRow.aCells[6].getContent() == 'Ida') || 
		    ($F('oSelectItinerario') == 2 && oRow.aCells[6].getContent() == 'Volta')) {
			sAlunos += ','+oRow.aCells[2].getContent();
		}
    });
	
    sUrl += '|ed47_i_codigo|ed47_v_nome';
    sUrl += '&sAlunos='+sAlunos;
  } else {

    if (!empty(oInputCodigoAluno.value)) {
      sUrl += '&pesquisa_chave2='+oInputCodigoAluno.value;
    } else {
      oInputCodigoAluno.value = '';
    }
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_aluno', sUrl, 'Pesquisa Alunos', lMostra);
}

/**
 * Retorno do aluno
 */
function js_mostraAluno() {

  db_iframe_aluno.hide();
  if (arguments[1] !== true && arguments[1] !== false) {

    oInputCodigoAluno.value    = arguments[0];
    oInputDescricaoAluno.value = arguments[1];
  }

  if (arguments[1] === true) {

    oInputDescricaoAluno.value = _M(
                                    'educacao.transporteescolar.tre4_vincularalunos.chave_nao_encontrada',
                                    {iAluno: oInputCodigoAluno.value}
                                   );
    oInputCodigoAluno.value    = '';
  }

  if (arguments[1] === false) {
    oInputDescricaoAluno.value = arguments[0];
  }
}

/**
 * Adiciona um vínculo do aluno com o ponto de parada. Envia como parâmetros o código do ponto e o código do aluno
 */
function js_adicionaVinculo() {

  if (js_validacoes()) {

    var oParametro                    = new Object();
        oParametro.exec               = 'salvarVinculoAluno';
        oParametro.iCodigoPontoParada = oInputCodigoItinerarioPontoParada.value;
        oParametro.iCodigoAluno       = oInputCodigoAluno.value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoAdicionarVinculo;

    js_divCarregando(_M('educacao.transporteescolar.tre4_vincularalunos.adicionando_vinculo'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da adição do vinculo
 */
function js_retornoAdicionarVinculo(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.message.urlDecode());

  if (oRetorno.status == 1) {

    oInputCodigoAluno.value    = '';
    oInputDescricaoAluno.value = '';
    js_getAlunosVinculados();
  }
}

function js_limparCampos() {

  oInputCodigoLinha.value                 = '';
  oInputDescricaoLinha.value              = '';
  oInputCodigoPontoParada.value           = '';
  oInputDescricaoPontoParada.value        = '';
  oInputCodigoItinerarioPontoParada.value = '';
  oInputCodigoAluno.value                 = '';
  oInputDescricaoAluno.value              = '';
  oGridVinculosAluno.clearAll(true);
}

/**
 * Valida os campos antes de adicionar o vinculo
 */
function js_validacoes() {

  if (empty(oInputCodigoPontoParada.value)) {

    alert(_M('educacao.transporteescolar.tre4_vincularalunos.codigo_ponto_parada_nao_informado'));
    return false;
  }

  if (empty(oInputCodigoAluno.value)) {

    alert(_M('educacao.transporteescolar.tre4_vincularalunos.codigo_aluno_nao_informado'));
    return false;
  }

  return true;
}

/**
 * Faz a chamada para remoção do vinculo do aluno com o ponto de parada
 */
function js_removeVinculo() {

  if (confirm(_M('educacao.transporteescolar.tre4_vincularalunos.confirma_remocao_vinculos'))) {

    var oParametro         = new Object();
        oParametro.exec    = 'removerVinculoAluno';
        oParametro.aAlunos = new Object();

    oGridVinculosAluno.aRows.each(function(oRow) {

      if (oRow.isSelected) {
        if (oParametro.aAlunos[oRow.aCells[1].getContent()] == null) {
          oParametro.aAlunos[oRow.aCells[1].getContent()] = {
                iCodigoPontoParada : '',
                aAlunos : new Array()
              };
        }

        oParametro.aAlunos[oRow.aCells[1].getContent()].iCodigoPontoParada = oRow.aCells[1].getContent();
        oParametro.aAlunos[oRow.aCells[1].getContent()].aAlunos.push(oRow.aCells[2].getContent());
      }
    });

    if (!Object.keys(oParametro.aAlunos).length) {

      alert(_M('educacao.transporteescolar.tre4_vincularalunos.nenhum_aluno_selecionado'));
      return false;
    }

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoRemoverVinculo;

    js_divCarregando(_M('educacao.transporteescolar.tre4_vincularalunos.removendo_vinculos'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da remoção do vinculo
 */
function js_retornoRemoverVinculo(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.message.urlDecode());

  if (oRetorno.status == 1) {
    js_getAlunosVinculados();
  }
}

/**
 * Busca os alunos que possuem vínculo com um ponto de parada da linha de transporte selecionada
 */
function js_getAlunosVinculados() {

  var oParametro                    = new Object();
      oParametro.exec               = 'getAlunosVinculadosLinha';
      oParametro.iLinha             = oInputCodigoLinha.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoAlunosVinculados;

  js_divCarregando(_M('educacao.transporteescolar.tre4_vincularalunos.buscando_alunos_vinculados'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno dos alunos vinculados
 */
function js_retornoAlunosVinculados(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  oGridVinculosAluno.clearAll(true);
  
  if (oRetorno.aAlunos.length > 0) {

    oRetorno.aAlunos.each(function(oAluno) {

      var aLinha = new Array();
          aLinha.push(oAluno.iCodigoItinerarioPontoParada);
          aLinha.push(oAluno.iCodigoAluno);
          aLinha.push(oAluno.sNome.urlDecode());
          aLinha.push(oAluno.sPontoParada.urlDecode());
          aLinha.push(oAluno.sEscola.urlDecode());
          aLinha.push(oAluno.sItinerario.urlDecode());

      oGridVinculosAluno.addRow(aLinha);
    });

    oGridVinculosAluno.renderRows();
  }
}

/**
 * Busca as escolas
 */
function js_pesquisarEscolas() {

  var oParametro  = new Object();
  oParametro.exec = 'pesquisaEscola';
  js_divCarregando("Aguarde, pesquisando escolas.", "msgBox");

  var oAjax = new Ajax.Request(
                                sUrlRpc,
                               {
                                method:     'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_retornaPesquisarEscolas
                               }
                              );
}

/**
 * Retorna da busca pelas escolas
 */
function js_retornaPesquisarEscolas(oResponse) {

  oCboEscola.clearItens();

  js_removeObj("msgBox");

  var oRetorno = eval('('+oResponse.responseText+')');
  oCboEscola.addItem("", "Selecione");
  oRetorno.dados.each(function (oEscola, iSeq) {

    oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
    if (oRetorno.dados.length == 1) {
      oCboEscola.setValue(oEscola.codigo_escola);
    }
  });
}
js_pesquisarEscolas();
</script>
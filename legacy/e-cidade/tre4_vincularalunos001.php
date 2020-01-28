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
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px;">
    <form class="container" method="post" action="">
      <div>
        <fieldset>
          <legend class="bold">Dados Para Vincular o Aluno</legend>
          <table>
            <tr>
              <td>
                <label class="bold" for="selectTipoEscola">Tipo da Escola:</label>
              </td>
              <td colspan="5">
                <select id="selectTipoEscola" class="field-size-max">
                  <option value="1" selected="selected">Escola da Rede</option>
                  <option value="2">Escola de Procedência</option>
                </select>
              </td>
            </tr>
            <tr>
             <td>
               <label class="bold" for="cboEscola">Escola:</label>
             </td>
             <td id="ctnCboEscola" colspan="5">
             </td>
            </tr>
            <tr>
              <td>
                <a href="#" onClick='js_pesquisaLinhas(true);' class="bold">
                  <label for="oInputCodigoLinha">Linha:</label>
                </a>
              </td>
              <td id="inputCodigoLinha"></td>
              <td id="inputDescricaoLinha" colspan="4"></td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="selectItinerario">Itinerário:</label>
              </td>
              <td id="selectItinerario" colspan="5"></td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="ctnHorarios">Horário / Veículo:</label>
              </td>
              <td id="ctnHorarios" colspan="5"></td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="ctnVagas">Vagas:</label>
              </td>
              <td id="ctnVagas"></td>
              <td>
                <label class="bold" for="ctnPassageirosVinculados">Passageiros Vinculados:</label>
              </td>
              <td id="ctnPassageirosVinculados"></td>
              <td>
                <label class="bold" for="ctnVagasRestantes">Vagas Restantes:</label>
              </td>
              <td id="ctnVagasRestantes"></td>
            </tr>
            <tr>
              <td>
                <a href="#" onClick='js_pesquisaPontosParada(true);' class="bold">
                  <label for="inputCodigoPontoParada">Ponto de Parada:</label>
                </a>
              </td>
              <td id="inputCodigoPontoParada"></td>
              <td id="inputDescricaoPontoParada" colspan="4"></td>
            </tr>
            <tr>
              <td>
                <a href="#" onClick='js_pesquisaAluno(true);' class="bold">
                  <label for="inputCodigoAluno">Aluno:</label>
                </a>
              </td>
              <td id="inputCodigoAluno"></td>
              <td id="inputDescricaoAluno" colspan="4"></td>
            </tr>
          </table>
        </fieldset>
      </div>
      <div>
        <input type="button" id="btnAdicionarAluno" value="Adicionar"/>
      </div>
    </form>
    <div class="container">
      <div>
        <fieldset style="width: 1500px">
          <legend class="bold">Vínculos da Linha</legend>
          <div id="gridAlunoPontoParada">
          </div>
        </fieldset>
      </div>
      <div>
        <input type="button" id="btnRemoverAluno" value="Remover Selecionados"/>
      </div>
    </div>
  </body>
</html>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
var   sUrlRpc                  = 'tre4_pontoparada.RPC.php';
const MENSAGENS_VINCULO_ALUNOS = 'educacao.transporteescolar.tre4_vincularalunos.';
var   aHorarios                = [];

/**
 * Elementos INPUT
 */
var oCboEscola = new DBComboBox("cboEscola", "oCboEscola", null, "385px");
    oCboEscola.addItem("", "Selecione");
    oCboEscola.addStyle('width', '100%');
    oCboEscola.show($('ctnCboEscola'));

var oInputCodigoLinha                       = document.createElement('input');
    oInputCodigoLinha.id                    = 'oInputCodigoLinha';
    oInputCodigoLinha.style.backgroundColor = '#DEB887';
    oInputCodigoLinha.readOnly              = true;
    oInputCodigoLinha.addClassName('field-size2');
$('inputCodigoLinha').appendChild(oInputCodigoLinha);

var oInputDescricaoLinha                       = document.createElement('input');
    oInputDescricaoLinha.id                    = 'oInputDescricaoLinha';
    oInputDescricaoLinha.readOnly              = true;
    oInputDescricaoLinha.style.backgroundColor = '#DEB887';
    oInputDescricaoLinha.addClassName('field-size-max');
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
    oInputDescricaoPontoParada.addClassName('field-size-max');
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
    oInputDescricaoAluno.addClassName('field-size-max');
$('inputDescricaoAluno').appendChild(oInputDescricaoAluno);

var oInputVagas                       = document.createElement( 'input' );
    oInputVagas.id                    = 'inputVagas';
    oInputVagas.readOnly              = true;
    oInputVagas.style.backgroundColor = '#DEB887';
    oInputVagas.addClassName('field-size2');
$('ctnVagas' ).appendChild( oInputVagas );

var oInputPassageirosVinculados                       = document.createElement( 'input' );
    oInputPassageirosVinculados.id                    = 'inputPassageirosVinculados';
    oInputPassageirosVinculados.readOnly              = true;
    oInputPassageirosVinculados.style.backgroundColor = '#DEB887';
    oInputPassageirosVinculados.addClassName('field-size2');
$('ctnPassageirosVinculados' ).appendChild( oInputPassageirosVinculados );

var oInputVagasRestantes                       = document.createElement( 'input' );
    oInputVagasRestantes.id                    = 'inputVagasRestantes';
    oInputVagasRestantes.readOnly              = true;
    oInputVagasRestantes.style.backgroundColor = '#DEB887';
    oInputVagasRestantes.addClassName('field-size2');
$('ctnVagasRestantes' ).appendChild( oInputVagasRestantes );

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
 * Elemento SELECT dos horários de um itinerário
 */
var oSelectHorarios             = document.createElement( 'select' );
    oSelectHorarios.id          = 'selectHorarios';
    oSelectHorarios.style.width = '100%';
    oSelectHorarios.add( new Option( 'Selecione', 0 ) );
$('ctnHorarios' ).appendChild( oSelectHorarios );

/**
 * Grid dos vínculos dos alunos
 */
var oGridVinculosAluno              = new DBGrid('gridVinculosAluno');
    oGridVinculosAluno.nameInstance = 'oGridVinculosAluno';
    oGridVinculosAluno.setCheckbox(0);
    oGridVinculosAluno.setHeader( new Array("Código Ponto Parada", "Código", "Aluno", "Embarque", "Desembarque", "Itinerário", "Hora Saída", "Hora Chegada", "Código Ponto Parada Aluno"));
    oGridVinculosAluno.setCellAlign(new Array("center", "center", "left", "left", "left", "left", "left", "left", "center"));
    oGridVinculosAluno.setCellWidth(new Array("0%", "1%", "41%", "20%", "20%", "5%", "7%", "7%", "0%"));
    oGridVinculosAluno.aHeaders[1].lDisplayed = false;
    oGridVinculosAluno.aHeaders[2].lDisplayed = false;
    oGridVinculosAluno.aHeaders[9].lDisplayed = false;
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
});

$('oSelectItinerario').observe("change", function() {

  oInputCodigoPontoParada.value           = '';
  oInputDescricaoPontoParada.value        = '';
  oInputCodigoItinerarioPontoParada.value = '';
  oInputCodigoAluno.value                 = '';
  oInputDescricaoAluno.value              = '';
});

$('oSelectItinerario').onchange = function() {
  buscaHorariosLinha();
};

$('selectHorarios').onchange = function() {
  preencheVagas();
};

$('selectTipoEscola').onchange = function() {
  js_pesquisarEscolas();
};

$('cboEscola').onchange = function() {
  js_limparCampos();
};

/**
 * Pesquisa as linhas cadastradas
 */
function js_pesquisaLinhas(lMostra) {

  if ($F('cboEscola') == '') {

    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'codigo_escola_vazio' ) );
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

  if( $F('selectTipoEscola') == 2 ) {
    sUrl += '&lEscolaProcedencia';
  }
  
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

  buscaHorariosLinha();
  js_getAlunosVinculados();
}

/**
 * Pesquisa um ponto de parada vinculado a linha de transporte e itinerário selecionados
 */
function js_pesquisaPontosParada(lMostra) {

  if (empty(oInputCodigoLinha.value)) {

    oInputCodigoPontoParada.value = '';
    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'codigo_linha_vazio' ) );
    return false;
  }

  var sUrl  = 'func_pontoparada.php?lPontoParadaLinhaTransporteItinerario&iLinhaTransporte='+oInputCodigoLinha.value;
      sUrl += '&iItinerario='+oSelectItinerario.value+'&funcao_js=parent.js_mostraPontoParada';

  if( lMostra ) {
    sUrl += '|tre04_sequencial|tre04_nome|tre11_sequencial';
  } else {

    if( !empty( $F('oInputCodigoPontoParada') ) ) {
      sUrl += '&pesquisa_chave='+$F('oInputCodigoPontoParada');
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

    oInputCodigoItinerarioPontoParada.value = '';
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

    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'codigo_escola_vazio' ) );
    return false;
  }

  var sUrl        = 'func_alunotransporteescolar.php?iEscola='+ $F('cboEscola');
  var sEscolaRede = '&lEscolaRede=true';

  if( $F('selectTipoEscola') == 2 ) {
    sEscolaRede = '&lEscolaRede=false';
  }

  sUrl += sEscolaRede + '&funcao_js=parent.js_mostraAluno';
      
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

    oInputDescricaoAluno.value = _M( MENSAGENS_VINCULO_ALUNOS + 'chave_nao_encontrada', {iAluno: oInputCodigoAluno.value} );
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

    var oParametro                      = {};
        oParametro.exec                 = 'salvarVinculoAluno';
        oParametro.iCodigoPontoParada   = oInputCodigoItinerarioPontoParada.value;
        oParametro.iCodigoAluno         = oInputCodigoAluno.value;
        oParametro.iLinhaHorarioVeiculo = $('selectHorarios').value;

    var oDadosRequisicao            = {};
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoAdicionarVinculo;

    js_divCarregando( _M( MENSAGENS_VINCULO_ALUNOS + 'adicionando_vinculo' ), "msgBox" );
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

    var iCodigoLinha    = oInputCodigoLinha.value;
    var sDescricaoLinha = oInputDescricaoLinha.value;

    js_limparCampos();

    oInputCodigoLinha.value    = iCodigoLinha   ;
    oInputDescricaoLinha.value = sDescricaoLinha;
    
    buscaHorariosLinha();
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
  js_limpaHorarios();
  limpaVagas();
}

function limpaVagas() {

  $('inputVagas').value                 = '';
  $('inputPassageirosVinculados').value = '';
  $('inputVagasRestantes').value        = '';
}

/**
 * Valida os campos antes de adicionar o vinculo
 */
function js_validacoes() {

  if (empty(oInputCodigoPontoParada.value)) {

    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'codigo_ponto_parada_nao_informado' ) );
    return false;
  }

  if (empty(oInputCodigoAluno.value)) {

    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'codigo_aluno_nao_informado' ) );
    return false;
  }

  if ( empty( $('selectHorarios' ).value ) ) {

    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'nenhum_horario_selecionado' ) );
    return false;
  }

  return true;
}

/**
 * Faz a chamada para remoção do vinculo do aluno com o ponto de parada
 */
function js_removeVinculo() {

  var oParametro        = {};
      oParametro.exec   = 'removerVinculoAluno';
      oParametro.aDados = [];

  oGridVinculosAluno.aRows.each(function(oRow) {

    if (oRow.isSelected) {

      oAlunos                    = {};
      oAlunos.iCodigoPontoParada = oRow.aCells[1].getContent();
      oAlunos.iCoddigoAluno      = oRow.aCells[2].getContent();
      oAlunos.iCodigoVinculo     = oRow.aCells[9].getContent();

      oParametro.aDados.push(oAlunos);
    }
  });

  if (!Object.keys(oParametro.aDados).length) {

    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'nenhum_aluno_selecionado' ) );
    return false;
  }

  if ( confirm( _M( MENSAGENS_VINCULO_ALUNOS + 'confirma_remocao_vinculos') ) ) {

    var oDadosRequisicao            = {};
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoRemoverVinculo;

    js_divCarregando( _M( MENSAGENS_VINCULO_ALUNOS + 'removendo_vinculos' ), "msgBox" );
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

  var oParametro                    = {};
      oParametro.exec               = 'getAlunosVinculadosLinha';
      oParametro.iLinha             = oInputCodigoLinha.value;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoAlunosVinculados;

  js_divCarregando( _M( MENSAGENS_VINCULO_ALUNOS + 'buscando_alunos_vinculados' ), "msgBox" );
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

      var aLinha = [];
          aLinha.push(oAluno.iCodigoItinerarioPontoParada);
          aLinha.push(oAluno.iCodigoAluno);
          aLinha.push(oAluno.sNome.urlDecode());
          aLinha.push(oAluno.sEmbarque.urlDecode());
          aLinha.push(oAluno.sDesembarque.urlDecode());
          aLinha.push(oAluno.sItinerario.urlDecode());
          aLinha.push(oAluno.sHoraSaida.urlDecode());
          aLinha.push(oAluno.sHoraChegada.urlDecode());
          aLinha.push(oAluno.iCodigoLinhaTransportePontoParadaAluno.urlDecode());

      oGridVinculosAluno.addRow(aLinha);
    });

    oGridVinculosAluno.renderRows();
  }
}

/**
 * Busca as escolas conforme o tipo selecionado
 * Tipo da Escola:
 *   1 - Escola da Rede
 *   2 - Escola de Procedência
 */
function js_pesquisarEscolas() {

  js_limparCampos();

  var oParametro = {};
  var sExecuta   = 'pesquisaEscola';

  if( $F('selectTipoEscola') == 2 ) {

    sExecuta = 'pesquisaEscolasProcedencia';
    oParametro.lSomenteAlunosForaRede = true;
  }

  oParametro.exec = sExecuta;

  var oAjaxRequest = new AjaxRequest( sUrlRpc, oParametro, js_retornaPesquisarEscolas );
      oAjaxRequest.setMessage( _M( MENSAGENS_VINCULO_ALUNOS + 'pesquisando_escolas' ) );
      oAjaxRequest.execute();
}

/**
 * Retorna da busca pelas escolas
 */
function js_retornaPesquisarEscolas( oRetorno, lErro ) {

  oCboEscola.clearItens();

  oCboEscola.addItem("", "Selecione");
  oRetorno.dados.each(function (oEscola ) {

    oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
    if (oRetorno.dados.length == 1) {
      oCboEscola.setValue(oEscola.codigo_escola);
    }
  });
}

/**
 * Busca os horários da linha e informações referentes a cada horário
 */
function buscaHorariosLinha() {

  if ( empty( $('cboEscola').value ) ) {

    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'escola_nao_selecionada' ) );
    return false;
  }

  if ( empty( $('oInputCodigoLinha').value ) ) {

    alert( _M( MENSAGENS_VINCULO_ALUNOS + 'linha_nao_informada' ) );
    return false;
  }

  var oParametros             = {};
      oParametros.exec        = 'getHorariosLinha';
      oParametros.iLinha      = $('oInputCodigoLinha').value;
      oParametros.iItinerario = $('oSelectItinerario').value;

  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoBuscaHorariosLinhas;

  js_divCarregando( _M( MENSAGENS_VINCULO_ALUNOS + 'buscando_horarios' ), "msgBox" );
  new Ajax.Request( sUrlRpc, oDadosRequisicao );
}

/**
 * Retorno da busca das informações dos horários da linha
 * @param oResponse
 */
function retornoBuscaHorariosLinhas( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '(' + oResponse.responseText + ')' );

  js_limpaHorarios();
  limpaVagas();

  aHorarios = oRetorno.aHorarioLinha;

  oRetorno.aHorarioLinha.each(function( oDados ) {

    var sHora  = oDados.hora_saida.urlDecode();
        sHora += " às " + oDados.hora_chegada.urlDecode();
        sHora += " - " + oDados.nome_veiculo.urlDecode();
        sHora += " ( " + oDados.placa.urlDecode() + " ) ";
    oSelectHorarios.add( new Option( sHora, oDados.vinculo_veiculo_horario ) );
  });
}

/**
 * Limpa o select dos horários
 */
function js_limpaHorarios() {

  if (oSelectHorarios.length > 0) {

    var iTotalHorarios = oSelectHorarios.length;
    for ( var iContador = 0; iContador < iTotalHorarios; iContador++ ) {
      oSelectHorarios.options.remove( iContador );
    }
  }

  oSelectHorarios.add( new Option( 'Selecione', 0 ) );
}

function preencheVagas() {

  if ( empty( $('selectHorarios' ).value ) ) {
    limpaVagas();
  }

  aHorarios.each(function( oHorario ) {

    if ( oHorario.vinculo_veiculo_horario == $('selectHorarios' ).value ) {

      $('inputVagas').value                 = oHorario.vagas;
      $('inputPassageirosVinculados').value = oHorario.vagas_ocupadas;
      $('inputVagasRestantes').value        = oHorario.vagas - oHorario.vagas_ocupadas;
    }
  });
}

js_pesquisarEscolas();
</script>
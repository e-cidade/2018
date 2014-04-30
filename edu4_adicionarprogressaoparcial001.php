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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label("ed29_i_codigo");
$oRotulo->label("ed12_i_codigo");
$oRotulo->label("ed11_i_codigo");

?>

<html>  <head>    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />    <meta http-equiv="Expires" CONTENT="0" />    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/DadosAluno.classe.js"></script>    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">  </head>  <body bgcolor=#CCCCCC >
    <?php
      /**
       * Validamos se estamos no módulo escola
       */
      if (db_getsession("DB_modulo") == 1100747) {
      	MsgAviso(db_getsession("DB_coddepto"),"escola");
      }
    ?>
    <div class='container'>
      <fieldset>
        <legend>Incluir aluno em progressão parcial</legend>
        <div  id='ctnViewDadosAluno' style="width: 800px;"></div>
        
        <fieldset class='separator'>
          <legend>Dados etapa destino</legend>
          <table class='form-container'>
            <tr>
              <td nowrap="nowrap" class='field-size3'>
                <?php db_ancora("Curso:", "js_buscaCurso(true);", 1); ?>
              </td>
              <td nowrap="nowrap">
                <?php 
                  db_input('iCursoSelecionado', 10, $Ied29_i_codigo, true, 'text', 1, " onchange='js_buscaCurso(false);'");
                  db_input('iEnsinoSelecionado', 10, "", false, 'hidden', 3, "");
                  db_input('sNomeCurso', 77, "", true, 'text',   3, '');
                ?>
              </td>
            </tr>
          </table>
          
          <fieldset class='form-container'>
            <legend>Lançamento de Disciplinas</legend>
            <table>
              <tr>
                <td nowrap="nowrap" class='field-size2 bold'>
                  Ano da Progressão:
                </td>
                <td nowrap="nowrap" >
                  <?php 
                    db_input('iAnoProgressao', 10, $Ied11_i_codigo, true, 'text', 1, "onchange='js_validaAno();'");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap="nowrap" class='field-size2'>
                  <?php db_ancora("Etapa:", "js_buscaEtapa(true);", 1); ?>
                </td>
                <td nowrap="nowrap" >
                  <?php 
                    db_input('iEtapa', 10, $Ied11_i_codigo, true, 'text', 1, " onchange='js_buscaEtapa(false);'");
                    db_input('sNomeEtapa', 76, "", true, 'text', 3, '');
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap="nowrap" class='field-size2'>
                  <?php db_ancora("Disciplina:", "js_buscaDisciplina(true);", 1); ?>
                </td>
                <td nowrap="nowrap" >
                  <?php 
                    db_input('iDisciplina', 10, $Ied12_i_codigo, true, 'text', 1, " onchange='js_buscaDisciplina(false);'");
                    db_input('sNomeDisciplina', 76, "", true, 'text', 3, '');
                  ?>
                </td>
              </tr>
            </table>
            
            <input type="button" value='Adicionar Disciplina' name='adicionar' id='adicionarDisciplina'  />
            
            <br /> 
            <div id='ctnGridDisciplinaLancada'> </div>
            
          </fieldset>
        </fieldset>
      </fieldset>
      
      <input type="button" value="Salvar" id='salvarProgressao' name='salvarProgressao' />  
    </div>
    
  </body>
  
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

var oParametrosProgressao = null;

/**
 * Formas de controle da progressão parcial
 */
const CONTROLE_BASE_CURRICULAR = 2;
const CONTROLE_ETAPA           = 1;

const URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001 = "educacao.escola.edu4_adicionarprogressaoparcial001.";

/**
 * Instancia da grid
 * @var {DBGrid}
 */
var oGridDisciplina = new DBGrid("gridDisciplina");

var aHeadersGrid    = new Array("Etapa", "Disciplina", "Ano", "Ação", "código etapa", "código disciplina");
var aCellWidthGrid  = new Array("15%", "65%", "10%", "10%");
var aCellAlign      = new Array("center", "left", "left", "center", "center", "center", "center", "left", "center");

oGridDisciplina.nameInstance = 'oGridDisciplina';
oGridDisciplina.setHeader(aHeadersGrid);
oGridDisciplina.setCellWidth(aCellWidthGrid);
oGridDisciplina.setCellAlign(aCellAlign);
oGridDisciplina.setHeight(100);
oGridDisciplina.aHeaders[4].lDisplayed = false;
oGridDisciplina.aHeaders[5].lDisplayed = false;
oGridDisciplina.show($('ctnGridDisciplinaLancada'));
oGridDisciplina.clearAll(true);

/**
 * Array com as progressões informadas para o Aluno
 * @var {Array}
 */
var aProgressoesInformadas = new Array();

/**
 * View com os dados do Aluno
 * @var {DBViewFormularioEducacao.DadosAluno} 
 */
var oViewDadosAluno = new DBViewFormularioEducacao.DadosAluno();
oViewDadosAluno.modoSeparador(true);
oViewDadosAluno.setLegend();
oViewDadosAluno.show($('ctnViewDadosAluno'));

var fFunctionRetornoDadosAluno = function () {
  
  $('iCursoSelecionado').value = oViewDadosAluno.getCodigoCurso();
  js_buscaCurso(false);
  js_buscaProgressoesJaInclusas(oViewDadosAluno.getCodigoAluno());
};

var fFunctionLimpaAluno = function () {

  js_limpaCurso();
  js_limpaEtapa();
  js_limpaDisciplina();
  $('iAnoProgressao').value = '';  
  oGridDisciplina.clearAll(true);
  aProgressoesInformadas = new Array();
}

oViewDadosAluno.setCallBackRetornoAluno(fFunctionRetornoDadosAluno);
oViewDadosAluno.setCallBackLimpaDados(fFunctionLimpaAluno);

/**
 * Busca o curso pela ação do clique na ancora ou digitação do código
 * @param {boolean} lMostra
 */
function js_buscaCurso (lMostra) {

  if ($F('codigoAluno') == '') {
    
    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"selecione_aluno") );
    js_limpaCurso();
    return false;
  } 

  var sUrl = "func_cursoedu.php?";
  
  if ( lMostra ) {
    
    sUrl += 'funcao_js=parent.js_mostraCurso|ed29_i_codigo|ed10_i_codigo|ed29_c_descr';
    js_OpenJanelaIframe('', 'db_iframe_curso', sUrl, 'Pesquisa Curso', true);
  } else if ( $F('iCursoSelecionado') != '' ) {

    sUrl += 'funcao_js=parent.js_mostraCurso&pesquisa_chave='+$F('iCursoSelecionado');
    js_OpenJanelaIframe('', 'db_iframe_curso', sUrl, 'Pesquisa Curso', false);
  } else {
    
    $('iCursoSelecionado').value = '';
    $('sNomeCurso').value        = '';
  }
}

function js_limpaCurso() {
  
  $('iCursoSelecionado').value = '';
  $('sNomeCurso').value        = '';
}
 
/**
 * Retorno da busca pelo curso
 * Variáveis preenchidas através do uso de arguments 
 */
function js_mostraCurso() {

  if (typeof arguments[1] == 'boolean') {

    $('sNomeCurso').value         = arguments[0];
    $('iEnsinoSelecionado').value = arguments[2];
    if ( arguments[1] ) {
      
      $('iCursoSelecionado').value  = '';
      $('iEnsinoSelecionado').value = '';
    }
  } else {

    $('iCursoSelecionado').value  = arguments[0];
    $('iEnsinoSelecionado').value = arguments[1];
    $('sNomeCurso').value         = arguments[2];
  }
  
  db_iframe_curso.hide();
}

function js_buscaEtapa(lMostra) {

  if ( $F('iCursoSelecionado') == '' ) {

    alert(_M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"selecione_curso"));
    js_limpaEtapa();
    return false;  
  }

  var sUrl  = "func_serie.php?iEtapa="+oViewDadosAluno.getCodigoEtapa()+"&curso="+$F('iCursoSelecionado');
  sUrl     += "&funcao_js=parent.js_mostraEtapa";

  if ( lMostra ) {
    sUrl += "|ed11_i_codigo|ed11_c_descr";
  } else if ( $F('iEtapa') != '') {
    sUrl += "&pesquisa_chave=" + $F('iEtapa');
  } else {
    
    js_limpaEtapa();
    return; 
  }
  
  js_OpenJanelaIframe( '', 'db_iframe_serie', sUrl, 'Pesquisa Etapa', lMostra );
}

function js_limpaEtapa() {

  $('iEtapa').value     = '';
  $('sNomeEtapa').value = '';
}

function js_mostraEtapa() {

  if (typeof arguments[1] == 'boolean') {

    $('sNomeEtapa').value = arguments[0]; 
    if ( arguments[1] ) {
      $('iEtapa').value = '';
    }
  } else {

    $('iEtapa').value     = arguments[0];
    $('sNomeEtapa').value = arguments[1];
  }
  db_iframe_serie.hide();
}
 
/**
 * Busca as disciplinas de acordo com o curso selecionado
 * @param {boolean} lMostra
 */
function js_buscaDisciplina(lMostra) {

  var sUrl = "func_disciplina.php?";

  if ( $F('iCursoSelecionado') == '') {

    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"selecione_curso") );
    js_limpaDisciplina();
    return false;
  }
  
  if ( lMostra ) {
    
    sUrl += 'funcao_js=parent.js_mostraDisciplina|ed12_i_codigo|ed232_c_descr';
    sUrl += '&curso='+$F('iCursoSelecionado');
    js_OpenJanelaIframe('', 'db_iframe_disciplina', sUrl, 'Pesquisa Disciplina', true);
  } else if ( $F('iDisciplina') != '' ) {

    sUrl += 'funcao_js=parent.js_mostraDisciplina&pesquisa_chave='+$F('iDisciplina');
    sUrl += '&curso='+$F('iCursoSelecionado');
    js_OpenJanelaIframe('', 'db_iframe_disciplina', sUrl, 'Pesquisa Disciplina', true);
  } else {
    js_limpaDisciplina();
  }
}

function js_limpaDisciplina() {
  
  $('iDisciplina').value     = '';
  $('sNomeDisciplina').value = '';
}

/**
 * Retorno da busca pelas disciplinas
 */
function js_mostraDisciplina() {

  if (typeof arguments[1] == 'boolean') {

    $('sNomeDisciplina').value = arguments[0]; 
    if ( arguments[1] ) {
      $('iDisciplina').value = '';
    }
  } else {

    $('iDisciplina').value     = arguments[0];
    $('sNomeDisciplina').value = arguments[1];
  }
  db_iframe_disciplina.hide();
}

/**
 * Valida se os dados para progressão estao todos informados
 * @return {Boolean}
 */
function validaDadosFormularioProgressao() {

  if ( $F('iAnoProgressao') == '' ) {

    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"informe_ano_progressao") );
    return false;
  }

  if ( $F('iEtapa') == '' ) {

    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"informe_etapa_progressao") );
    return false;
  }

  if ( $F('iDisciplina') == '' ) {
    
    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"informe_disciplina_progressao") );
    return false;
  }  
  return true;
}

/**
 * Valida os lançamentos de progressão para o aluno
 */
function js_validaDadosProgressao() {

  if (oParametrosProgressao == null) {

    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"parametro_nao_configurado") );
    return false;
  }

  if ( !js_validaProgressaoJaInformada() ) {
    return false;
  }
  
  /**
   * Validamos os parâmetros configurado para escola e se forma de controle bate com a quantidade informada 
   */ 
  switch ( parseInt( oParametrosProgressao.iFormaControle ) ) {

    case CONTROLE_BASE_CURRICULAR:

      if (!js_validaControlePorBaseCurricular()) {
        return false;
      }
      break;

    case CONTROLE_ETAPA:

      if ( !js_validaControlePorEtapa() ) {
        return false;
      }
      break;

    default:

      alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"parametro_nao_configurado") );
      return false;
      break;
  }
  return true;
}

/**
 * Validamos se a progressão já não foi informada para o aluno
 * @return {Boolean}
 */ 
function js_validaProgressaoJaInformada() {

  var lJaInformada = false;
  aProgressoesInformadas.each( function(aLinha, indiceLinha) {

    aLinha.each( function (oDadosLinha, indiceDados) {
      
      if ( (oDadosLinha.iAno        == $F('iAnoProgressao')) &&
           (oDadosLinha.iEtapa      == $F('iEtapa')) &&
           (oDadosLinha.iDisciplina == $F('iDisciplina'))
         ) {

        alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"progressao_ja_informada") );
        lJaInformada = true;   
        return;
      }
    });
    if (lJaInformada) {
      return; 
    }
  });
  
  if (lJaInformada) {
    return false;
  }

  return true;
}


/**
 * Valida o número de disciplinas quando Forma de Controle esta configurado como 'Por Base Curricula'
 * @return {Boolean}
 */
function js_validaControlePorBaseCurricular() {

  var oMsg = {};
  oMsg.iNumeroDisciplina = oParametrosProgressao.iNumeroDisciplina;
  oMsg.sFormaControle    = "Por Base Curricular";

  if (aProgressoesInformadas.length == parseInt( oParametrosProgressao.iNumeroDisciplina )) {
    
    alert( _M( URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"numero_disciplinas_atingiu_limite_configurado", oMsg ) );
    return false;
  }
  return true;
}

/**
 * Valida o número de disciplinas quando Forma de Controle esta configurado como 'Por Etapa'
 * @return {Boolean}
 */
function js_validaControlePorEtapa() {

  var oMsg = {};
  oMsg.iNumeroDisciplina = oParametrosProgressao.iNumeroDisciplina;
  oMsg.sFormaControle    = "Por Etapa";
  
  var aControleEtapa  = new Array();
  var lAtingiuLimite  = false;

  aProgressoesInformadas.each( function(aLinha, indiceLinha) {

    aLinha.each( function (oDadosLinha, indiceDados) {

      /**
       * Esse if garante que estou validando somente a etapa que esta selecionada
       */
      if (oDadosLinha.iEtapa != $F('iEtapa')) {
        return;
      }
      
      var oControleEtapa         = {};
      oControleEtapa.iEtapa      = oDadosLinha.iEtapa;
      oControleEtapa.iQuantidade = 1;

      var lEncontrouEtapa = false;
      var iIndiceEncontro = 0;
      // Localizo se a etapa selecionada já foi adicionada
      aControleEtapa.each( function (oEtapa, indice) {

        if (oDadosLinha.iEtapa == oEtapa.iEtapa) {
          
          lEncontrouEtapa = true;
          iIndiceEncontro = indice;
          return;
        }
      }); 

      /**
       * Se já foi adicionada disciplinas para a etapa selecionada, verificamos se ela não estorou o limite 
       * configurado no parâmetro
       */
      if (lEncontrouEtapa) {
        
        aControleEtapa[iIndiceEncontro].iQuantidade ++;
        if ( aControleEtapa[iIndiceEncontro].iQuantidade == parseInt(oParametrosProgressao.iNumeroDisciplina) ) {
          
          alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"numero_disciplinas_atingiu_limite_configurado", oMsg) );
          lAtingiuLimite = true;
          return;
        }
        
      } else {
        aControleEtapa.push(oControleEtapa);
      }
      
    });
    if (lAtingiuLimite) {
      return;
    }
  });
  if (lAtingiuLimite) {
    return false;
  }

  return true;
}

/**
 * Botão para adicioar uma etapa + disciplina + ano em progressão parcial para o aluno
 */
$('adicionarDisciplina').observe("click", function () {

  /**
   * Validamos se os dados necessários estão preenchidos
   */
  if ( !validaDadosFormularioProgressao() ) {
    return false;
  }

  if ( !js_validaDadosProgressao()) {

    return false;
  }

  var oDadosProgressao          = {};
  oDadosProgressao.sEtapa       = $F('sNomeEtapa');
  oDadosProgressao.sDisciplina  = $F('sNomeDisciplina');
  oDadosProgressao.iAno         = $F('iAnoProgressao');
  oDadosProgressao.iEtapa       = $F('iEtapa');
  oDadosProgressao.iDisciplina  = $F('iDisciplina');
  oDadosProgressao.lJaExistente = false;

  var aDadosLinha = new Array();
  aDadosLinha.push(oDadosProgressao);

  aProgressoesInformadas.push(aDadosLinha);

  $('iDisciplina').value     = '';
  $('sNomeDisciplina').value = '';
  
  js_renderizarGrid();

});

/**
 * Percorre o Array com todas as progressões informadas para usuário e as renderiza na grid para vizualização do usuário 
 */
function js_renderizarGrid() {

  oGridDisciplina.clearAll(true);
  
  aProgressoesInformadas.each( function(aLinha, indiceLinha) {

    aLinha.each( function (oDadosLinha, indiceDados) {

      var sDisabled = "";
      if ( oDadosLinha.lJaExistente ) {
        sDisabled = " disabled='disabled'";
      } 
      var sBtnRemover = "<input type='button'  value = 'Remover', name = 'remover"+indiceLinha+"'";
      sBtnRemover    += " onclick='js_removeLinhaGrid("+indiceLinha+");' "+ sDisabled +"/>";

      var aLinhaGrid = new Array();          
      aLinhaGrid[0]  = oDadosLinha.sEtapa;
      aLinhaGrid[1]  = oDadosLinha.sDisciplina; 
      aLinhaGrid[2]  = oDadosLinha.iAno;
      aLinhaGrid[3]  = sBtnRemover;          
      aLinhaGrid[4]  = oDadosLinha.iEtapa;   
      aLinhaGrid[5]  = oDadosLinha.iDisciplina;

      oGridDisciplina.addRow(aLinhaGrid);
    });
  
  });
  
  oGridDisciplina.renderRows();
  
}

/**
 * Remove uma linha da grid
 */
function js_removeLinhaGrid(indexLinha) {

  aProgressoesInformadas.splice(indexLinha, 1);
  js_renderizarGrid();
}


(function () {

   /**
   * Busca a configuração dos parâmetros para progressão parcial
   */
  var oParametros = {'exec':'getDados'};
  
  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = true;
  oRequest.onComplete   = function (oAjax) {
    
    var oRetorno = eval("("+oAjax.responseText+")");
    
    oParametrosProgressao                   = {};
    oParametrosProgressao.lHabilitado       = oRetorno.dados.lHabilitado;      
    oParametrosProgressao.iFormaControle    = oRetorno.dados.iFormaControle;   
    oParametrosProgressao.iNumeroDisciplina = oRetorno.dados.iNumeroDisciplina;

  };

  new Ajax.Request( "edu4_parametrodependencia.RPC.php", oRequest);
  
})();


function js_validaAno() {

  if ( $F('iAnoProgressao').length < 4 || $F('iAnoProgressao').length > 4) {

    $('iAnoProgressao').value = '';
    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"ano_invalido") );
    return false;
  }
  
  if ( $F('iAnoProgressao') >= oViewDadosAluno.getAnoCalendario() ) {

    $('iAnoProgressao').value = '';
    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"ano_maior_que_calendario_matriculado") );
    return false;
  }
  
} 

function js_validarSalvar() {

  if (oViewDadosAluno.getCodigoAluno() == null) {

    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"selecione_aluno") );
    return false;
  }

  if (aProgressoesInformadas.length == 0) {

    alert( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001+"adicione_disciplna_progressao") );
    return false;
  }

  return true;
}

/**
 * Salva as progressões lançadas para o aluno
 */
$('salvarProgressao').observe( 'click', function () {

  if ( !js_validarSalvar() ) {
    return false;
  }

  var aProgressoesAluno = new Array();
  aProgressoesInformadas.each( function(aLinha, indiceLinha) {

    aLinha.each( function (oDadosLinha, indiceDados) {

      if ( !oDadosLinha.lJaExistente ) {
        
        var oProgressao = {"iAno":oDadosLinha.iAno, "iEtapa": oDadosLinha.iEtapa, "iDisciplina":oDadosLinha.iDisciplina};
        aProgressoesAluno.push(oProgressao);
      }
    });
  });

  var oParametros          = {};
  oParametros.exec         = 'salvarProgressaoAluno';
  oParametros.iAluno       = oViewDadosAluno.getCodigoAluno();
  oParametros.aProgressoes = aProgressoesAluno;

  $('salvarProgressao').setAttribute('disabled', 'disabled');
  js_divCarregando( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001 + "aguarde_salvando_progressao"), "msgBox");
  
  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json=' + Object.toJSON(oParametros);
  oRequest.onComplete = function (oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    alert(oRetorno.message.urlDecode());
    if ( parseInt(oRetorno.status) == 2 ) {
      return false;
    }
    
    location.href = "edu4_adicionarprogressaoparcial001.php";
  } 

  new Ajax.Request( "edu4_vincularalunoturma.RPC.php", oRequest);
  
});


/**
 * Busca as progressões já vinculadas/inclusas ao aluno selecionado
 */
function js_buscaProgressoesJaInclusas( iAluno ) {

  var oParametros    = {};
  oParametros.exec   = 'buscaProgressaoAluno';
  oParametros.iAluno = iAluno;

  js_divCarregando( _M(URL_MENSAGEM_EDU_ADICIONAPROGRESSAOPARCIAL001 + "aguarde_verificando_progressoes"), "msgBoxB");
  
  var oRequest = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json=' + Object.toJSON(oParametros);
  oRequest.onComplete = function (oAjax) {

    js_removeObj('msgBoxB');
    var oRetorno = eval("("+oAjax.responseText+")");

    if ( parseInt( oRetorno.status ) == 2 ) {

      alert( oRetorno.message.urlDecode() );
      return false;
    }


    oRetorno.aProgressao.each( function (oProgressao) {

      var aDadosLinha               = new Array();
      var oDadosProgressao          = {};
      oDadosProgressao.sEtapa       = oProgressao.sEtapa.urlDecode();
      oDadosProgressao.sDisciplina  = oProgressao.sDisciplina.urlDecode();
      oDadosProgressao.iAno         = oProgressao.iAno;
      oDadosProgressao.iEtapa       = oProgressao.iEtapa;
      oDadosProgressao.iDisciplina  = oProgressao.iDisciplina;
      oDadosProgressao.lJaExistente = true;

      aDadosLinha.push(oDadosProgressao);
      aProgressoesInformadas.push(aDadosLinha);
    });

    if ( aProgressoesInformadas.length > 0) {
      js_renderizarGrid();
    }
    
  };

  new Ajax.Request( "edu4_vincularalunoturma.RPC.php", oRequest);
};
</script>
</html>
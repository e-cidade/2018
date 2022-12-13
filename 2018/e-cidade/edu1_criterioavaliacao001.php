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
require_once("libs/db_stdlibwebseller.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory( $_GET );

$sValueBotaoCriterioAvaliacao = "Incluir";
switch ($oGet->db_opcao) {
  case 2:
    $sValueBotaoCriterioAvaliacao = "Alterar";
    break;
  case 3:
    $sValueBotaoCriterioAvaliacao = "Excluir";
    break;
}

?>
<html>
  
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="estilos.css" />
    <link rel="stylesheet" type="text/css" href="estilos/DBtab.style.css" />
    <link rel="stylesheet" type="text/css" href="estilos/dbtreeview.style.css" />
    <link rel="stylesheet" type="text/css" href="estilos/grid.style.css" />
    <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBTreeView.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBOrderRows.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewArvoreTurma.classe.js" ></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js" ></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js" ></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/DBViewCriterioAvaliacaoOrdenar.classe.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/DBViewCriterioAvaliacaoTurma.classe.js"></script>
  </head>
  
  <body bgcolor="#cccccc">

    <?php
      if (db_getsession("DB_modulo") == 1100747) {
        MsgAviso(db_getsession("DB_coddepto"), "escola");
      }
    ?>
    
    <div style="margin-top: 15px;" id = 'ctnAbas'></div>
    
    <!-- ************************************************************************************************************ 
         *************************************** ABA CRITÉRIO DE AVALIAÇÃO ******************************************
         ************************************************************************************************************ -->
    <div id='ctnAbaCriterioAvaliacao' class="container">
      
      <form name="form1" method="post" action="" >
        <fieldset style="width:800px;">
          <legend>Critério de Avaliação</legend>
          <table class="form-container">
            <tr>
              <td nowrap='nowrap'>Descrição:</td>
              <td>
                <?php
                  db_input('iCriterioAvaliacao', 10,  '', true, 'hidden', 3, '');
                  db_input('sCriterioAvaliacao', 100, '', true, 'text', $oGet->db_opcao, '', '', '', '', 150);
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap='nowrap'>Abreviatura:</td>
              <td>
                <?php db_input('sCriterioAbreviado', 100, '', true, 'text', $oGet->db_opcao, '', '', '', '', 20); ?>
              </td>
            </tr>
          </table>
          <br>
          <fieldset class="separador" >
            <legend>Disciplinas</legend>
            <div id='ctnDisciplinas'> </div>
          </fieldset>
          
          <fieldset class="separador" >
            <legend>Períodos de Avaliação</legend>
            <div id='ctnPeriodos'> </div>
          </fieldset>
          
        </fieldset>
        <input type="button" id='salvarCriterio' name="salvarCriterio" value="<?=$sValueBotaoCriterioAvaliacao?>" />
        <input type="button" id='pesquisarCriterio' name="pesquisar" value="Pesquisar" />
        <input type="button" id='ordenar' name="ordenar" value="Ordenar" />
        <input type="button" id='novoRegistro' name="novoRegistro" value="Novo Registro" />
      </form>
      
    </div>
    
    
    <!-- ************************************************************************************************************ 
         ********************************** ABA CRITÉRIO DE AVALIAÇÃO POR TURMA *************************************
         ************************************************************************************************************ -->
    <div class="container" id='ctnAbaCriterioAvaliacaoTurma'>
      <div id="ctnViewTurmasVincular">
      </div>
    </div>
    
  </body>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script type="text/javascript">

$('sCriterioAvaliacao').style.textTransform = 'uppercase';
$('sCriterioAbreviado').style.textTransform = 'uppercase';

var oGet = js_urlToObject();

/**
 * Estrutura com as disciplinas cadastradas vinculadas com os ensinos.
 * Quando uma disciplina é vinculada a um Critério de avaliação, possui:
 * -> uma flag no objeto quenos informa se devemos marcar o checkbox
 * ..... O nome da flag é: lMarcar
 * -> uma falg no objeto que informa se devemos desabilitar as disciplinas selecionadas
 * ..... O nome da flag é: lDisabled
 * @type Array
 */
var aDisciplinas = [];

/**
 * Estrutura com os Periodos de Avaliação cadastrados
 * Quando um Período é vinculado a um Critério de avaliação, possui:
 * -> uma flag no objeto quenos informa se devemos marcar o checkbox
 * ..... O nome da flag é: lMarcar
 * -> uma falg no objeto que informa se devemos desabilitar os períodos selecionados
 * ..... O nome da flag é: lDisabled
 * @type Array
 */
var aPeriodosAvaliacao = [];

const MENSAGEM_CRITERIOAVALIACAO = 'educacao.escola.edu1_criterioavaliacao.';

var oGridDisciplina          = new DBGrid('gridDisciplina');
oGridDisciplina.nameInstance = 'oGridDisciplina';
oGridDisciplina.setCheckbox(3);
oGridDisciplina.setCellWidth(new Array("40%", "45%", "15%", '0%'));
oGridDisciplina.setCellAlign(new Array("left", "left", "left", "rigth"));
oGridDisciplina.setHeader(new Array("Disciplina", "Ensino", "Abreviatura", 'Código'));
oGridDisciplina.setHeight(130);
oGridDisciplina.aHeaders[4].lDisplayed = false;
oGridDisciplina.show($('ctnDisciplinas'));

var oGridPeriodo          = new DBGrid('gridPeriodo');
oGridPeriodo.nameInstance = 'oGridPeriodo';
oGridPeriodo.setCheckbox(2);
oGridPeriodo.setCellWidth(new Array("85%", "15%", '0%'));
oGridPeriodo.setCellAlign(Array("left", "left", "rigth"));
oGridPeriodo.setHeader(new Array("Período", "Abreviatura", 'Código'));
oGridPeriodo.setHeight(130);
oGridPeriodo.aHeaders[3].lDisplayed = false;
oGridPeriodo.show($('ctnPeriodos'));


/**
 * Cria abas
 */
var oDBAba                     = new DBAbas($('ctnAbas'));
var oAbaCriterioAvaliacao      = oDBAba.adicionarAba("Critério de Avaliação", $('ctnAbaCriterioAvaliacao'));
var oAbaCriterioAvaliacaoTurma = oDBAba.adicionarAba("Critério de Avaliação por Turma", $('ctnAbaCriterioAvaliacaoTurma'));

oAbaCriterioAvaliacaoTurma.lBloqueada = true;

/**
 * Carrega as informações conforme ação selecionada no menu.
 * -> Inclusão
 * -> Alteração
 * -> Exclusão
 */
(function () {

  switch( parseInt( oGet.db_opcao ) ) {
    case 1:
      
      $('salvarCriterio').value = "Incluir";
      js_buscaDisciplinasEscola();
      js_buscaPeriodosAvaliacaoEscola();
      break;
      
    case 2:
    case 3:
      
      if ( oGet.iCodigoCriterio ) {

        $('iCriterioAvaliacao').value = oGet.iCodigoCriterio;
        js_buscaDisciplinasEscola();
        js_buscaPeriodosAvaliacaoEscola();
        js_buscarDadosCriterioAvaliacao();
      } else {
        js_pesquisaCriterios(); 
      }
      
      if ( parseInt( oGet.db_opcao ) == 2) {
        
        $('salvarCriterio').value = "Alterar";
        
        oAbaCriterioAvaliacaoTurma.lBloqueada = false;
        js_buscaTurmas();
        if ( oGet.lRedireciona ) {

          oAbaCriterioAvaliacao.setVisibilidade(false);
          oAbaCriterioAvaliacaoTurma.setVisibilidade(true);
        }
        
        return;
      }
      
      $('salvarCriterio').value = "Excluir";
      js_bloquearCamposFormulario();
      js_carregaDadosGridDisciplina();
      js_carregaDadosGridPeriodos();
      break;
  }
  
  
})();

/**
 * Busca as disciplinas vinculadas ao Níveis de ensino no módulo Secretaria da Educação
 * @returns {void}
 */
function js_buscaDisciplinasEscola() {
  
  var oParametros       = {};
  oParametros.sExecucao = 'getDisciplinas';
  
  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function ( oAjax ) {
    
    js_removeObj('msgBoxA');
    var oRetorno = eval('(' + oAjax.responseText + ')');
    
    if ( oRetorno.iStatus == 2 ) {
      
      alert( _M( MENSAGEM_CRITERIOAVALIACAO+'erro_buscar_disciplinas' ) );
      return;
    }
    
    oRetorno.aDisciplinas.each( function ( oDisciplina ) {
      
      oDisciplina.lMarcar   = false;
      oDisciplina.lDisabled = false;
      aDisciplinas.push(oDisciplina);
    });
    
    js_carregaDadosGridDisciplina();
    
  };

  js_divCarregando ( _M(MENSAGEM_CRITERIOAVALIACAO+'aguarde_disciplinas'), 'msgBoxA' );
  new Ajax.Request("edu4_ensino.RPC.php", oRequest);  
}

/**
 * Renderiza os dados da grid Disciplinas 
 * @returns {void}
 */
function js_carregaDadosGridDisciplina() {
  
  oGridDisciplina.clearAll(true);
    
  aDisciplinas.each( function ( oDisciplina ) {

    var aLinha = [];
    aLinha.push(oDisciplina.sDisciplina.urlDecode());
    aLinha.push(oDisciplina.sEnsino.urlDecode());
    aLinha.push(oDisciplina.sEnsinoAbrev.urlDecode());
    aLinha.push(oDisciplina.iDisciplina);
    oGridDisciplina.addRow(aLinha, null, oDisciplina.lDisabled, oDisciplina.lMarcar);

  });
  oGridDisciplina.renderRows();
}

/**
 * Busca os períodos de avaliação cadastrado na secretária da educação
 * @returns {void}
 */
function js_buscaPeriodosAvaliacaoEscola() {
  
  var oParametros  = {};
  oParametros.exec = 'getPeriodosAvaliacao';
  
  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function ( oAjax ) {
    
    js_removeObj('msgBoxB');
    
    var oRetorno = eval('(' + oAjax.responseText + ')');
    
    if ( parseInt(oRetorno.status) == 2 ) {
      
      alert( _M( MENSAGEM_CRITERIOAVALIACAO+'erro_buscar_periodos') );
      return;
    }
    
    oRetorno.aPeriodosAvaliacao.each( function ( oPeriodo ) {
      
      oPeriodo.lMarcar   = false;
      oPeriodo.lDisabled = false;
      aPeriodosAvaliacao.push( oPeriodo );
    });
    js_carregaDadosGridPeriodos();
  };
  
  js_divCarregando ( _M(MENSAGEM_CRITERIOAVALIACAO+'aguarde_periodos'), 'msgBoxB' );
  new Ajax.Request("edu_educacaobase.RPC.php", oRequest);  
}


/**
 * Renderiza os dados da grid Disciplinas 
 * @returns {void}
 */
function js_carregaDadosGridPeriodos() {
  
  oGridPeriodo.clearAll(true);
  aPeriodosAvaliacao.each( function ( oPeriodo ) {

    var aLinha = [];
    aLinha.push(oPeriodo.sPeriodoAvaliacao.urlDecode());
    aLinha.push(oPeriodo.sPeriodoAbrev.urlDecode());
    aLinha.push(oPeriodo.iPeriodoAvaliacao);
    oGridPeriodo.addRow(aLinha, null, oPeriodo.lDisabled, oPeriodo.lMarcar);
  });
  oGridPeriodo.renderRows();
};

/**
 * Busca os dados de um critério de avaliação
 * @returns {void}
 */
function js_buscarDadosCriterioAvaliacao() {
  
  var oParametros                = {};
  oParametros.sExecucao          = 'getDadosCriterio';
  oParametros.iCriterioAvaliacao = $F('iCriterioAvaliacao');
  
  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = js_retornoDadosCriterio;  
  
  js_divCarregando ( _M(MENSAGEM_CRITERIOAVALIACAO+'aguarde_carregando_criterio'), 'msgBoxC' );
  new Ajax.Request("edu4_criterioavaliacao.RPC.php", oRequest);
}

function js_retornoDadosCriterio( oAjax ) {
  
  js_removeObj('msgBoxC');
  var oRetorno = eval('(' + oAjax.responseText + ')');
  
  $('sCriterioAvaliacao').value = oRetorno.sDescricao.urlDecode();
  $('sCriterioAbreviado').value = oRetorno.sAbreviatura.urlDecode();
  
  /**
   * Marca na grid as disciplinas que estão vinculados ao critério de avaliação
   */
  var aLinhasHintDisciplina = [];
  if (oRetorno.aDisciplinas.length > 0) {
    
    oRetorno.aDisciplinas.each( function (oDisciplina) {
      
      for ( iIndice in aDisciplinas ) {

        if (typeof aDisciplinas[iIndice] == 'function') {
          continue;
        }
        
        if ( oDisciplina.iDisciplina == aDisciplinas[iIndice].iDisciplina ) {
          
          aDisciplinas[iIndice].lMarcar = true;
          if ( oDisciplina.lVinculadaTurma ) {
            
            aDisciplinas[iIndice].lDisabled = true;
            aLinhasHintDisciplina.push( {iLinha : iIndice, sTurmas : oDisciplina.sTurmasVinculadas.urlDecode()} );
          }
          break;
        }
      }
    });

    js_carregaDadosGridDisciplina();

    /**
     * Coloca o hint na grid
     */
    if ( aLinhasHintDisciplina.length > 0 ) {
      
      for (var i = 0; i < aLinhasHintDisciplina.length; i++) {
        
        var sHint = "Esta disciplina não pode ser desvinculada, sem antes remover o(s) vínculo(s) com a(s) turma(s): ";
           sHint += aLinhasHintDisciplina[i].sTurmas;
        var oParametros = {iWidth:'200', oPosition : {sVertical : 'T', sHorizontal : 'L'}};
        oGridDisciplina.setHint(aLinhasHintDisciplina[i].iLinha, 0, sHint,  oParametros);
      }
    }
    
  }
  
  /**
   * Marca na grid as disciplinas que estão vinculados ao critério de avaliação
   */
  if ( oRetorno.aPeriodos.length > 0 ) {
    
    oRetorno.aPeriodos.each( function ( oPeriodo ) {
      
      for ( iIndice in aPeriodosAvaliacao ) {

        if (typeof aPeriodosAvaliacao[iIndice] == 'function') {
          continue;
        }
        
        if ( oPeriodo.iPeriodo == aPeriodosAvaliacao[iIndice].iPeriodoAvaliacao ) {
          
          aPeriodosAvaliacao[iIndice].lMarcar = true;
          break;
        }
      }
    });
    
    js_carregaDadosGridPeriodos();
  }


}


/**
 * Abre a função de pesquisa 
 */
function js_pesquisaCriterios() {
  
  var sUrl  = 'func_criterioavaliacao.php?';
      sUrl += 'funcao_js=parent.js_retornoCriterio|ed338_sequencial';
  js_OpenJanelaIframe('', 'db_iframe_criterioavaliacao', sUrl, 'Pesquisa Critérios de Avaliação', true);
  
}

function js_retornoCriterio() {
  
  if ( arguments && arguments[0] != '' ) {
    
    /**
     * O redirecionamento deve ser sempre como "Alteração" exceto quando acessado rotina pelo menu de "Exclusão"
     */
    var iOpcao = oGet.db_opcao == 3 ? oGet.db_opcao : 2;
    
    db_iframe_criterioavaliacao.hide();
    location.href = 'edu1_criterioavaliacao001.php?db_opcao='+iOpcao+'&iCodigoCriterio='+arguments[0];
  }
}

/**
 * 
 * @type Ação ao clicar no botão de pesquisar
 */
$('pesquisarCriterio').observe('click', function () {
  js_pesquisaCriterios();
});

/**
 * Ação ao clicar no botão novo registro.
 */
$('novoRegistro').observe('click', function () {
  location.href = 'edu1_criterioavaliacao001.php?db_opcao=1';
});

/**
 * Instancia a view de critério de avaliação e o mostra na tela para que possa ser realizado a reordenação.
 */
$('ordenar').observe('click', function () {

  var oDBViewCriterioAvaliacaoOrdenar = new DBViewCriterioAvaliacaoOrdenar();
  oDBViewCriterioAvaliacaoOrdenar.show();
});

/**
 * Ação ao clicar no botão (Incluir, Alterar ou Excluir)
 */
$('salvarCriterio').observe('click', function () {
  
  if ( oGet.db_opcao == 3 ) {
    
    js_removerCriterioAvaliacao();
    return;
  }
  
  js_salvarCriterioAvaliacao();
});

/**
 * Valida os dados do formulário para inclusão ou alteração
 * @returns {Boolean}
 */
function js_validaDados() {

  if ( $F('sCriterioAvaliacao')  == '' ) {
    
    alert( _M(MENSAGEM_CRITERIOAVALIACAO + 'aviso_informe_descricao') );
    return false;
  }

  if ( $F('sCriterioAvaliacao').length > 150 ) {
    $('sCriterioAvaliacao').value = $('sCriterioAvaliacao').value.slice(0,149);
  }

  if ( $F('sCriterioAbreviado')  == '' ) {
    
    alert( _M(MENSAGEM_CRITERIOAVALIACAO + 'aviso_informe_abreviatura') );
    return false;
  }
  
  if ( $F('sCriterioAbreviado').length > 20 ) {
    $('sCriterioAbreviado').value = $('sCriterioAbreviado').value.slice(0,19);
  }

  if ( oGridDisciplina.getSelection('array').length == 0 ) {
    
    alert( _M(MENSAGEM_CRITERIOAVALIACAO + 'aviso_selecione_disciplina') );
    return false;
  }
  
  if ( oGridPeriodo.getSelection('array').length == 0 ) {
    
    alert( _M(MENSAGEM_CRITERIOAVALIACAO + 'aviso_selecione_periodo') );
    return false;
  }
  
  return true;
}

/**
 * Inclui / Altera um critério de avaliação e realiza os vínculos com as disciplinas e os períodos
 * @returns {void}
 */
function js_salvarCriterioAvaliacao() {

  if ( !js_validaDados() ) {
    return;
  }
    
  var oParametros = {};
  oParametros.sExecucao          = 'salvar';
  oParametros.iCriterioAvaliacao = $F('iCriterioAvaliacao');
  oParametros.sDescricao         = encodeURIComponent(tagString( $F('sCriterioAvaliacao') ));
  oParametros.sAbreviatura       = encodeURIComponent(tagString( $F('sCriterioAbreviado') ));
  oParametros.aDisciplinas       = new Array();
  oParametros.aPeriodos          = new Array();
  
  $aLinhasDisciplinasSelecionadas = oGridDisciplina.getSelection('array');
  $aLinhasDisciplinasSelecionadas.each ( function (aDisciplina) {
    oParametros.aDisciplinas.push(aDisciplina[0]);
  });
  
  $aLinhaPeriodosSelecionados = oGridPeriodo.getSelection('array');
  $aLinhaPeriodosSelecionados.each( function (aPeriodoAvaliacao) {
    oParametros.aPeriodos.push( parseInt(aPeriodoAvaliacao[3]) );
  });

  var oRequest = {};
  
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = true;
  oRequest.onComplete   = function (oAjax) {
    
    js_removeObj('msgBoxZ');
    var oRetorno = eval('(' + oAjax.responseText + ')');
    
    var sAcao = parseInt(oGet.db_opcao) == 2 ? 'alterar' : 'incluir';
    
    if ( oRetorno.iStatus == 2 ) {
      
      alert( _M(MENSAGEM_CRITERIOAVALIACAO + 'erro_inserir_alterar_criterio', {'acao' : sAcao}) );
      return;
    }
    
    sAcao = parseInt(oGet.db_opcao) == 2 ? 'alterado' : 'incluso';
    alert(  _M(MENSAGEM_CRITERIOAVALIACAO + 'incluido_alterado_criterio_sucesso', {'acao' : sAcao}) );
    
    $('iCriterioAvaliacao').value = oRetorno.iCriterioAvaliacao;
    location.href = 'edu1_criterioavaliacao001.php?db_opcao=2&lRedireciona=true&iCodigoCriterio='+oRetorno.iCriterioAvaliacao;
  };
  
  var sMensgem = "aguarde_incluindo_criterio";
  if ( parseInt(oGet.db_opcao) == 2 ) {
    sMensgem = "aguarde_alterando_criterio";
  }
  
  js_divCarregando ( _M(MENSAGEM_CRITERIOAVALIACAO+sMensgem), 'msgBoxZ' );
  new Ajax.Request("edu4_criterioavaliacao.RPC.php", oRequest);
  
}

/**
 * Apaga o critério de avalição e todos vínculos
 * @returns {void}
 */
function js_removerCriterioAvaliacao() {

  if ( !confirm( _M(MENSAGEM_CRITERIOAVALIACAO+"confirma_exclusao") ) ) {
    return;
  }

  var oParametros = {};
  oParametros.sExecucao          = 'excluir';
  oParametros.iCriterioAvaliacao = $F('iCriterioAvaliacao');
  
  var oRequest = {};
  
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = true;
  oRequest.onComplete   = function (oAjax) {
    
    js_removeObj('msgBoxY');
    
    var oRetorno = eval('(' + oAjax.responseText + ')');
    
    if ( oRetorno.iStatus == 2 ) {
      
      alert( _M(MENSAGEM_CRITERIOAVALIACAO + 'erro_excluir_criterio') );
      return;
    }
    
    alert( _M(MENSAGEM_CRITERIOAVALIACAO + 'excluido_criterio_sucesso') );
    location.href = 'edu1_criterioavaliacao001.php?db_opcao=3';
    
  };
  
  js_divCarregando ( _M(MENSAGEM_CRITERIOAVALIACAO + 'aguarde_excluindo_criterio'), 'msgBoxY' );
  new Ajax.Request("edu4_criterioavaliacao.RPC.php", oRequest);
}

/**
 * Bloqueia a grid para seleção
 * @returns {void}
 */
function js_bloquearCamposFormulario() {
  
  aDisciplinas.each( function ( oDisciplina ) {
    oDisciplina.lDisabled = true;
  });
  
  aPeriodosAvaliacao.each( function ( oPeriodo ) {
    oPeriodo.lDisabled = true;
  });
  
}

function js_buscaTurmas() {

  aDisciplinasSelecionadas = new Array();
  
  $aLinhasDisciplinasSelecionadas = oGridDisciplina.getSelection('array');
  $aLinhasDisciplinasSelecionadas.each ( function (aDisciplina) {
    aDisciplinasSelecionadas.push(aDisciplina[0]);
  });

  var oCriterio = {
                   iCriterioAvaliacao:$F('iCriterioAvaliacao'),
                   sCriterioAvaliacao:$F('sCriterioAvaliacao'),
                   sCriterioAbreviado:$F('sCriterioAbreviado'),
                   aDisciplinas      : aDisciplinasSelecionadas
                  };

  oCriterioAvaliacaoTurma = new DBViewCriterioAvaliacaoTurma( oCriterio );
  oCriterioAvaliacaoTurma.show($('ctnViewTurmasVincular'));
}

</script>

/**
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
require_once('scripts/widgets/windowAux.widget.js');
require_once('scripts/widgets/dbmessageBoard.widget.js');
require_once('scripts/strings.js');
require_once('scripts/AjaxRequest.js');
require_once('scripts/widgets/DBLookUp.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js');

var DBViewManutencaoServidoresAssentamentoSubstituicao      =  function() {

  this.oMessageBoard       = {}; //new DBMessageBoard();
  this.iAno                = '';
  this.iMes                = '';
  this.sMatriculaServidor  = '';
  this.sAction             = '';
  this.oRequisicaoAjax     =  new AjaxRequest('pes4_assentamento.RPC.php', {});
  this.oRequisicaoAjax.setMessage(DBViewManutencaoServidoresAssentamentoSubstituicao.MENSAGEM + 'loading');
};

DBViewManutencaoServidoresAssentamentoSubstituicao.MENSAGEM = 'recursoshumanos/pessoal/DBViewManutencaoServidoresAssentamentoSubstituicao.';

DBViewManutencaoServidoresAssentamentoSubstituicao.getInstance                       = function() {

  if ( !DBViewManutencaoServidoresAssentamentoSubstituicao.oInstance ) {
    DBViewManutencaoServidoresAssentamentoSubstituicao.oInstance = new DBViewManutencaoServidoresAssentamentoSubstituicao();
  }

  return DBViewManutencaoServidoresAssentamentoSubstituicao.oInstance;
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.loadMessageBoard = function() {

  this.oMessageBoard = new DBMessageBoard(
    'messageBoardSubstituicoesServidor',
    'Assentamentos',
    this.getConteudoCabecalhoJanela(),
    $('content')
  );
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.getConteudoCabecalhoJanela = function() {

  var sConteudo = "Selecione os assentamentos de substituição à pagar ao Servidor";

  if ( this.sAction != 'lancar' ) {
    sConteudo = 'Todos os regitros lançados serão removidos do ponto. '; 
  }
  return sConteudo;
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.show = function() {

  var oSelf = this;

  if ( $('servidorAssentamentos')) {
    $('servidorAssentamentos').remove();
  }

  var sFormulario = 'forms/db_frmassentaloteregistroponto.php?action='+this.sAction;
  var sTitulo     = 'Substituições do Servidor';

  this.oWindow  = new windowAux('servidorAssentamentos', sTitulo, 715, 460);
  this.oWindow.setContent('<div id="content" style="width: calc(100% - 4px);"></div>');
  this.oWindow.setShutDownFunction(this.oWindow.destroy.bind(this.oWindow));
  this.oWindow.show( null, null, true);

  this.oWindow.getContentContainer().load(
    sFormulario,
    function() {

      oSelf.loadMessageBoard();
      oSelf.loadGrid(oSelf.sMatriculaServidor);
      oSelf.makeEvents();
      $("rh01_regist").value = oSelf.sMatriculaServidor;
      $("z01_nome").value = oSelf.sNomeServidor;

      if(oSelf.sAction == 'lancar') {
        $('processar').value = 'Processar';
      }

      if(oSelf.sAction == 'cancelar') {
        $('processar').value = 'Processar Cancelamento';
      }
    }
  );
}

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.makeEvents = function() {
  $('processar').observe( "click", this.processar.bind(this) );
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.lancar = function(sMatriculaServidor, sNomeServidor) {

  this.sMatriculaServidor = sMatriculaServidor;
  this.sNomeServidor      = sNomeServidor;
  this.sAction            = 'lancar';
  this.show();
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.cancelar = function(sMatriculaServidor, sNomeServidor) {

  this.sMatriculaServidor = sMatriculaServidor;
  this.sNomeServidor      = sNomeServidor;
  this.sAction            = 'cancelar';
  this.show();
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.setAnoCompetencia = function(iAno) {
  this.iAno = iAno;
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.setMesCompetencia = function(iMes) {
  this.iMes = iMes;
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.loadGrid = function(sMatriculaServidor) {

  var aHeader = ["Codigo", "Início", "Fim", "Nº Dias", "Valor"];

  window.gridLancamentoSubstituicaoServidorPonto = new DBGrid("assentamentosServidor");
  gridLancamentoSubstituicaoServidorPonto.nameInstance = 'gridLancamentoSubstituicaoServidorPonto';
  gridLancamentoSubstituicaoServidorPonto.hasCheckbox  = true;
  gridLancamentoSubstituicaoServidorPonto.setHeader(aHeader);
  gridLancamentoSubstituicaoServidorPonto.setCellWidth([null, null, null, "80", "140"]);
  gridLancamentoSubstituicaoServidorPonto.setCellAlign(["center", "center", "center", "center", "center"]);
  gridLancamentoSubstituicaoServidorPonto.show( $("grid_servidor_assentamentos") );

  this.loadData(sMatriculaServidor);
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.loadData = function(sMatriculaServidor) {

  this.oRequisicaoAjax.setParameters({                                        
    "exec"        : "buscarAssentamentosServidor",
    "iMatricula"  : sMatriculaServidor,
    "iAno"        : this.iAno,
    "iMes"        : this.iMes
  });

  this.oRequisicaoAjax.setCallBack(this.carregarDadosGrid.bind(this));
  this.oRequisicaoAjax.setMessage("Buscando Substituições...");
  this.oRequisicaoAjax.execute();
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.carregarDadosGrid = function(oResponse, lErro) {

  var oSelf = this;

  if (lErro) {

    alert(oResponse.message.urlDecode());
    return false;
  } 

  if ( !!oResponse.message ) {

    alert( oResponse.message.urlDecode() );
    window.carregarServidores();
    this.oWindow.destroy();
    return;
  }

  gridLancamentoSubstituicaoServidorPonto.clearAll(true);

  /**
   * Percorremos os assentamentos cadastrados para o servidor atual
   */
  for (iInd = 0; iInd < oResponse.aItems.length; iInd++) {

    var oAssentamento = oResponse.aItems[iInd];
    var lDisabled     = null;
    var lSelected     = null;
    var sClass        = 'normal';

    if ( this.sAction == 'lancar') {
     
      lSelected = false;
      if ( oAssentamento.hasLote || oAssentamento.valor_substituicao == 0 ) {
        lDisabled = true;
        sClass = 'readOnly';
      }

      if (oAssentamento.hasLote) {
        sClass = 'readOnly';
      }
    } else { //sAction == 'cancelar';

      lDisabled = true;
      lSelected = false;
  
      if ( oAssentamento.hasLote ) {
        lSelected = true;
      } else {
        continue;
      }
      
    }
  

    if(!oAssentamento.isLoteFolhaFechada) {
      
      var iLinhasGrid = gridLancamentoSubstituicaoServidorPonto.getRows().length;
      gridLancamentoSubstituicaoServidorPonto.addRow([ oAssentamento.codigo,
                                                       oAssentamento.dataConcessao,
                                                       oAssentamento.dataTermino,
                                                       oAssentamento.dias,
                                                       js_formatar(oAssentamento.valor_substituicao, 'f')
                                                     ], 
                                                     false, 
                                                     lDisabled, 
                                                     lSelected);

      gridLancamentoSubstituicaoServidorPonto.getRows()[iLinhasGrid].sClassName =  sClass;
    }
  }
  gridLancamentoSubstituicaoServidorPonto.renderRows();
};

DBViewManutencaoServidoresAssentamentoSubstituicao.prototype.processar = function() {

  if($F('iFolha') == 0 && this.sAction == 'lancar') {

    alert(_M(DBViewManutencaoServidoresAssentamentoSubstituicao.MENSAGEM +"selecione_tipo_folha"));
    return false;
  }

  var aListaAssentamentos = gridLancamentoSubstituicaoServidorPonto.getSelection('array'); 
  var sAcaoRPC;

  if(this.sAction == 'lancar') {
    sAcaoRPC = "lancarAssentamentosSubstituicaoPonto";
  }

  if(this.sAction == 'cancelar') {
    sAcaoRPC = "cancelarLancamentoAssentamentosSubstituicaoPonto";
  }
  
  this.oRequisicaoAjax.setParameters({                                        
    "exec"        : sAcaoRPC,
    "iMatricula"  : $F('rh01_regist'),
    "iAnousu"     : this.iAno,
    "iMesusu"     : this.iMes,
    "iFolha"      : $F('iFolha'),
    "aRegistros"  : aListaAssentamentos
  });

  this.oRequisicaoAjax.setCallBack(this.carregarDadosGrid.bind(this));
  this.oRequisicaoAjax.setMessage("Processando Substituições...");
  this.oRequisicaoAjax.execute();
};

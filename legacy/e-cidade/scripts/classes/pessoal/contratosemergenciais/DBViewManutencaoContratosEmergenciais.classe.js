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
/**
 * 
 */
var DBViewManutencaoContratosEmergenciais                            = function() {
  
  var fCallBackRequisicao = function(oResposta, lErro) {};

  this.oRequisicaoAjax    =  new AjaxRequest('pes4_contratosemergenciais.RPC.php', {}, fCallBackRequisicao);
  this.oRequisicaoAjax.setMessage(DBViewManutencaoContratosEmergenciais.MENSAGEM + 'loading');

  this.oGridRenovacoes    = {}; //new DBGrid();
  this.oLookupServidor    = {}; //new DBLookUp();

  $("processar").observe("click", DBViewManutencaoContratosEmergenciais.prototype.processar.bind(this));
};

DBViewManutencaoContratosEmergenciais.MENSAGEM                       = 'recursoshumanos/pessoal/DBViewManutencaoContratosEmergenciais.';


DBViewManutencaoContratosEmergenciais.prototype.setMatricula         = function(iMatricula) {
  this.iMatricula    = iMatricula;
};

DBViewManutencaoContratosEmergenciais.prototype.popularCampos        = function(iSequencialRenovacao, sDataInicio, sDataFim) {

  $("sequencialRenovacao").value = iSequencialRenovacao;
  $("rh164_datainicio").value    = sDataInicio;
  $("rh164_datafim").value       = sDataFim;
  $("dataFimAnterior").value     = sDataFim;
};

DBViewManutencaoContratosEmergenciais.prototype.renovar              = function(iSequencialContrato, iSequencialRenovacao, sDataInicio, sDataFim) {

  var oDataInicioProximoPeriodo  = somaDataDiaMesAno(new Date(js_formatar(sDataFim, 'd')), 1, 0, 0);
  var sDataInicioProximoPeriodo  = js_strLeftPad(oDataInicioProximoPeriodo.getUTCDate(), 2, '0');
  sDataInicioProximoPeriodo     += "/"+ js_strLeftPad(parseInt(oDataInicioProximoPeriodo.getUTCMonth()+1), 2, '0');
  sDataInicioProximoPeriodo     += "/"+ oDataInicioProximoPeriodo.getUTCFullYear();

  this.limparFormulario();
  this.popularCampos(iSequencialRenovacao,  sDataInicioProximoPeriodo, sDataFim);
  $("sequencialContrato").value  = iSequencialContrato;
  $("rh164_datafim").value       = '';
  this.acao = "renovar";
};

DBViewManutencaoContratosEmergenciais.prototype.alterar              = function(iSequencialRenovacao, sDataInicio, sDataFim) {

  this.limparFormulario();
  $("rh164_datainicio").writeAttribute('readonly', '');
  $("rh164_datainicio").writeAttribute('style', 'background-color: #DEB887');
  $("dtjs_rh164_datainicio").hide();
  this.popularCampos(iSequencialRenovacao, sDataInicio, sDataFim);
  this.acao = "alterar";
};

DBViewManutencaoContratosEmergenciais.prototype.excluir              = function(iSequencialRenovacao, sDataInicio, sDataFim) {

  var oSelf = this;
  
  this.oRequisicaoAjax.setParameters({                                        
    "exec"            : "excluirRenovacaoContratoEmergencial",
    "iRenovacao"      : iSequencialRenovacao,
    "sDataFimAtual"   : js_formatar(sDataFim, 'd')
  });

  // console.log(this.oRequisicaoAjax.oParameters);

  this.oRequisicaoAjax.setCallBack(function(oResponse, lErro){
  
    alert(oResponse.sMessage.urlDecode());
    if(lErro) {
      return false;
    }

    oSelf.loadData();
  });
  this.oRequisicaoAjax.setMessage("Excluindo Contrato Emergencial...");
  this.oRequisicaoAjax.execute();
  
  oSelf.limparFormulario();
};

DBViewManutencaoContratosEmergenciais.prototype.processar           = function() {

  var oSelf = this;
  var sMensagem;

  if(this.acao.trim() != "renovar" && this.acao.trim() != "alterar") {
    return false;
  }

  if(!this.validaDataFim()){
    return false;
  }

  if(this.acao == "renovar") {

    this.oRequisicaoAjax.setParameters({                                        
      "exec"                 : "incluirRenovacaoContratoEmergencial",
      "iContrato"            : $F("sequencialContrato"),
      "sDataInicio"          : js_formatar($F("rh164_datainicio"), 'd'),
      "sDataFim"             : js_formatar($F("rh164_datafim"), 'd'),
      "sDataUltimaRenovacao" : js_formatar($F("dataFimAnterior"), 'd'),
    });
    sMensagem     = "Renovando Contrato...";

  }

  if(this.acao == "alterar") {

    this.oRequisicaoAjax.setParameters({                                        
      "exec"           : "alterarRenovacaoContratoEmergencial",
      "iRenovacao"     : $F("sequencialRenovacao"),
      "sDataFimAtual"  : js_formatar($F("dataFimAnterior"), 'd'),
      "sDataFimNova"   : js_formatar($F("rh164_datafim"), 'd'),
    });
    sMensagem     = "Alterando Renovação...";

  }

  // console.log(this.oRequisicaoAjax.oParameters);

  this.oRequisicaoAjax.setCallBack(function(oResponse, lErro){

    alert(oResponse.sMessage.urlDecode());
    if(lErro) {
      return false;
    }

    oSelf.loadData();
  });
  this.oRequisicaoAjax.setMessage(sMensagem);
  this.oRequisicaoAjax.execute();
  
  oSelf.limparFormulario();
};

DBViewManutencaoContratosEmergenciais.prototype.validaDataFim      = function() {


  if($("rh164_datainicio").value.trim() == "") {
    alert(_M(DBViewManutencaoContratosEmergenciais.MENSAGEM +"informe_data_inicial"));
    return false;
  }

  if($("rh164_datafim").value.trim() == "") {
    alert(_M(DBViewManutencaoContratosEmergenciais.MENSAGEM +"informe_data_final"));
    return false;
  }

  var sDataInicio            = js_formatar($F("rh164_datainicio"), 'd');
  var sDataFim               = js_formatar($F("rh164_datafim"), 'd');
  var sDataFimUltimoPeriodo  = js_formatar($F("dataFimAnterior"), 'd');

  if(this.acao == "renovar") {
    if(sDataInicio <= sDataFimUltimoPeriodo) {
      alert(_M(DBViewManutencaoContratosEmergenciais.MENSAGEM +"data_inicial_maior_data_final"));
      return false;
    }
  }

  if(sDataFim <= sDataInicio) {
    alert(_M(DBViewManutencaoContratosEmergenciais.MENSAGEM +"data_final_maior_data_inicial"));
    return false;
  }

  return true;
};

DBViewManutencaoContratosEmergenciais.prototype.limparFormulario  = function() {

  this.acao = "";

  $("sequencialContrato").value  = "";
  $("sequencialRenovacao").value = "";
  $("rh164_datainicio").value    = "";
  $("rh164_datafim").value       = "";
  $("dataFimAnterior").value     = "";

  $("rh164_datainicio").writeAttribute('readonly', false);
  $("rh164_datainicio").writeAttribute('style', 'backgroud-color: #fff');
  $("dtjs_rh164_datainicio").show();
};

DBViewManutencaoContratosEmergenciais.prototype.show              = function() {

  this.setMatricula($F("rh01_regist"));
  this.loadLookups();
  this.loadDataGrid();
};

DBViewManutencaoContratosEmergenciais.prototype.loadLookups       = function() {

  var oSelf = this;

  this.oLookupServidor = new DBLookUp($("procurarMatricula"), $("rh01_regist"), $("z01_nome"), {
    "sArquivo"              : "func_rhpessoal.php",
    "sObjetoLookUp"         : "db_iframe_rhpessoal",
    "aParametrosAdicionais" : ["testarescisao=true&contratosEmergenciais=1"]
  });

  this.oLookupServidor.setCallBack("onClick", DBViewManutencaoContratosEmergenciais.prototype.reloadData.bind(this));

  this.oLookupServidor.setCallBack("onChange", DBViewManutencaoContratosEmergenciais.prototype.reloadData.bind(this));
};

DBViewManutencaoContratosEmergenciais.prototype.reloadData        = function() {

  var oSelf = this;

  oSelf.setMatricula($F("rh01_regist"));
  oSelf.loadData();
};

DBViewManutencaoContratosEmergenciais.prototype.loadDataGrid      = function() {

  var aHeader = ["Descrição", "Data Início", "Data Término", "Opção"];

  this.oGridRenovacoes = new DBGrid("gridRenovacoes");
  this.oGridRenovacoes.setHeader(aHeader);
  this.oGridRenovacoes.setCellWidth(["20%", "20%", "20%", "40%"]);
  this.oGridRenovacoes.setCellAlign(["left", "center", "center", "center"]);
  this.oGridRenovacoes.show( $("gridRenovacoes") );

  this.loadData();
};

DBViewManutencaoContratosEmergenciais.prototype.loadData         = function() {

  this.oRequisicaoAjax.setParameters({                                        
    "exec"         : "buscarRenovacoesContratosEmergenciais",
    "iMatricula"   : this.iMatricula
  });

  this.oRequisicaoAjax.setCallBack(DBViewManutencaoContratosEmergenciais.prototype.carregarDadosGridRenovacoes.bind(this));
  this.oRequisicaoAjax.setMessage("Buscando Renovações...");
  this.oRequisicaoAjax.execute();
};

DBViewManutencaoContratosEmergenciais.prototype.carregarDadosGridRenovacoes = function(oResponse, lErro) {

  var oSelf = this;

  if (lErro) {
    alert(_M(DBViewManutencaoContratosEmergenciais.MENSAGEM +"erro_buscar_renovações"));
    return false;
  }
      
  this.oGridRenovacoes.clearAll(true);

  /**
   * Percorremos as Renovacoes
   */
  for (ind = 0; ind < oResponse.aRenovacoes.length; ind++) {

    oRenovacao = oResponse.aRenovacoes[ind];

    var oButtons  = "<input type='button' class='botao_acao' value='Renovar' dataInicio='"+ oRenovacao.sDataInicio +"' dataFim='"+ oRenovacao.sDataFim +"' sequencialRenovacao='"+ oRenovacao.iSequencialRenovacao +"' sequencialContrato='"+ oRenovacao.iSequencialContrato +"' />";
        oButtons += " <input type='button' class='botao_acao' value='Alterar' dataInicio='"+ oRenovacao.sDataInicio +"' dataFim='"+ oRenovacao.sDataFim +"' sequencialRenovacao='"+ oRenovacao.iSequencialRenovacao +"' />";

    if(oResponse.aRenovacoes.length > 1) {
      oButtons += " <input type='button' class='botao_acao' value='Excluir' dataInicio='"+ oRenovacao.sDataInicio +"' dataFim='"+ oRenovacao.sDataFim +"' sequencialRenovacao='"+ oRenovacao.iSequencialRenovacao +"' />";
    }

    /**
     * Desabilita os botões para as demais renovações
     */
    if(ind > 0){
      oButtons = '';
    }

    this.oGridRenovacoes.addRow([oRenovacao.sDescricao.urlDecode(),
                                  oRenovacao.sDataInicio,
                                  oRenovacao.sDataFim,
                                  oButtons
                                ]); 
  }

  this.oGridRenovacoes.renderRows();

  $$('.botao_acao').each(function(oElemento, iIndice) {

    var iSequencialRenovacao = oElemento.getAttribute('sequencialRenovacao');
    var sDataInicio          = oElemento.getAttribute('dataInicio');
    var sDataFim             = oElemento.getAttribute('dataFim');
    
    oElemento.onclick = function() {

      if(oElemento.value == "Renovar") {
        oSelf.renovar(oElemento.getAttribute('sequencialContrato'), iSequencialRenovacao, sDataInicio, sDataFim);
      }

      if(oElemento.value == "Alterar") {
        oSelf.alterar(iSequencialRenovacao, sDataInicio, sDataFim);
      }
      
      if(oElemento.value == "Excluir") {
        var sMensagemConfirmacao = "Deseja realmente excluir essa renovação?";
        if ( confirm(sMensagemConfirmacao) ) {
          oSelf.excluir(iSequencialRenovacao, sDataInicio, sDataFim);
        }
      }
    };
  });
};
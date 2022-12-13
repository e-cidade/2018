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
var DBViewManutencaoLotesRegistroPonto                               = function() {


  this.oRequisicaoAjax  =  new AjaxRequest('pes4_loteregistrosponto.RPC.php', {});
  this.oRequisicaoAjax.setMessage(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'loading');
};

DBViewManutencaoLotesRegistroPonto.MENSAGEM                          = 'recursoshumanos/pessoal/DBViewManutencaoLotesRegistroPonto.';

DBViewManutencaoLotesRegistroPonto.prototype.setCodigo               = function(iCodigo) {
  this.iCodigo    = iCodigo;
};

DBViewManutencaoLotesRegistroPonto.prototype.setDescricao            = function(sDescricao) {
  this.sDescricao = sDescricao;
};

DBViewManutencaoLotesRegistroPonto.prototype.setAnoCompetencia       = function(iAnoCompetencia) {
  this.iAnoCompetencia = iAnoCompetencia;
};

DBViewManutencaoLotesRegistroPonto.prototype.setMesCompetencia       = function(iMesCompetencia) {
  this.iMesCompetencia = iMesCompetencia;
};

DBViewManutencaoLotesRegistroPonto.prototype.alterar                 = function() {

  var oSelf                  = this;  
  this.setDescricao($F('rh155_descricao'));
  this.oRequisicaoAjax.setParameters({
    'exec'           : 'salvarLote', 
    'sDescricaoLote' : this.sDescricao.replace(/"/g,"'").urlEncode(),
    'iCodigoLote'    : this.iCodigo
  });

  this.oRequisicaoAjax.setCallBack(function (oResposta, lErro) {

    if (!lErro) {

      alert(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'alterar_lote_sucesso'));
      oSelf.definirEstadoFormulario('inclusao');
      window.carregarLotes();
      return;
    }
    alert(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'alterar_lote_erro'));
  });
  this.oRequisicaoAjax.setMessage(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'alterar_loading'));
  this.oRequisicaoAjax.execute();
  return;
};

DBViewManutencaoLotesRegistroPonto.prototype.excluir                 = function() {

  this.oRequisicaoAjax.setParameters({
    'exec'           : 'excluirLote', 
    'iCodigoLote'    : this.iCodigo
  });
  var oSelf                  = this;  
  this.oRequisicaoAjax.setCallBack(function (oResposta, lErro) {

    if (!lErro) {

      oSelf.definirEstadoFormulario('inclusao');

      carregarLotes();
      alert(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'excluir_lote_sucesso'));
      return;
    }
    alert(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'excluir_lote_sucesso'));
  });

  this.oRequisicaoAjax.setMessage(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'excluir_lote_sucesso'));
  this.oRequisicaoAjax.execute();

  return;
};

DBViewManutencaoLotesRegistroPonto.prototype.incluir                 = function(){

  var oSelf                  = this;  
  var sDescricao = $F("rh155_descricao");

  if ( !sDescricao ) {
    alert(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'descricao_obrigatorio'));
    return;
  }

  this.oRequisicaoAjax.setParameters({
    'exec': 'salvarLote', 
    'sDescricaoLote' : sDescricao.replace(/"/g,"'").urlEncode()
  });

  this.oRequisicaoAjax.setCallBack( function(oResposta, lErro) {

    if (!lErro) {
      oSelf.definirEstadoFormulario('inclusao');
      window.carregarLotes();
      alert(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'incluir_lote_sucesso'));
      return;
    }

    alert(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'incluir_lote_sucesso'));
  });

  this.oRequisicaoAjax.setMessage(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'incluir_lote_sucesso'));
  this.oRequisicaoAjax.execute();

  $("rh155_descricao").setValue('');
};

DBViewManutencaoLotesRegistroPonto.prototype.fechar                  = function() {
  
  if ( confirm(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'confirmar_fechar_lote', {sDescricao: this.sDescricao }) ) ) {

    var oSelf = this; 

    this.oRequisicaoAjax.setParameters({
      'exec': 'fecharLote', 
      'iCodigoLote'    : this.iCodigo
    });

    this.oRequisicaoAjax.setCallBack( function(oResposta, lErro) {

      if (!lErro) {
        window.carregarLotes();
        alert(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'fechar_lote_sucesso', {sDescricao: oSelf.sDescricao}));
        return;
      }

      alert(oResposta.message.urlDecode());
    });

    this.oRequisicaoAjax.setMessage(_M(DBViewManutencaoLotesRegistroPonto.MENSAGEM + 'fechar_loading', {sDescricao: this.sDescricao}));
    this.oRequisicaoAjax.execute();
  }
  return;
};

DBViewManutencaoLotesRegistroPonto.prototype.lancarPorRubrica        = function() {
  
  require_once('scripts/classes/pessoal/loteregistrosponto/DBViewLancamentoRegistros.classe.js');
  var oViewLancamentos = new DBViewLancamentoRegistros(this.iCodigo, this.sDescricao, this.iAnoCompetencia, this.iMesCompetencia, true);
  oViewLancamentos.show();
  return;
};

DBViewManutencaoLotesRegistroPonto.prototype.lancarPorServidor       = function() {

  require_once('scripts/classes/pessoal/loteregistrosponto/DBViewLancamentoRegistros.classe.js');
  var oViewLancamentos = new DBViewLancamentoRegistros(this.iCodigo, this.sDescricao, this.iAnoCompetencia, this.iMesCompetencia, false);
  oViewLancamentos.show();
  return;
};

DBViewManutencaoLotesRegistroPonto.getInstance                       = function(iCodigo) {

  if ( !DBViewManutencaoLotesRegistroPonto.oInstance ) {
    DBViewManutencaoLotesRegistroPonto.oInstance = new DBViewManutencaoLotesRegistroPonto(iCodigo);
  }

  return DBViewManutencaoLotesRegistroPonto.oInstance;

};

DBViewManutencaoLotesRegistroPonto.prototype.definirEstadoFormulario = function(sTipoAcao) {

  var oSelf = this;
  var sDescricao, sLabelBotaoProcesar, sLabelBotaoLimpar, fAcaoBotaoProcessar, fAcaoBotaoLimpar, lReadOnly;

  switch (sTipoAcao) {


    case 'alteracao':

      lReadOnly           = false;
      sDescricao          = this.sDescricao;
      sLabelBotaoProcesar = 'Alterar';
      sLabelBotaoLimpar   = 'Cancelar';
      fAcaoBotaoProcessar = function() {
        oSelf.alterar();
      };
      fAcaoBotaoLimpar    = function() {
        oSelf.definirEstadoFormulario('inclusao');
      };
      break;
    case 'exclusao':

      lReadOnly           = true;
      sDescricao          = this.sDescricao;
      sLabelBotaoProcesar = 'Excluir';
      sLabelBotaoLimpar   = 'Cancelar';
      fAcaoBotaoProcessar = function() {
        oSelf.excluir();
      };
      fAcaoBotaoLimpar    = function() {
        oSelf.definirEstadoFormulario('inclusao');
      };
      break;
    case 'inclusao':

      lReadOnly           = false;
      sDescricao          = '';
      sLabelBotaoProcesar = 'Incluir';
      sLabelBotaoLimpar   = 'Limpar';
      fAcaoBotaoProcessar = function() { 
        oSelf.incluir() ;
      };
      fAcaoBotaoLimpar    = function() {
        $('rh155_descricao').setValue(''); 
      };
      break;
  }


  $('rh155_descricao').readOnly = !!lReadOnly;

  if ( lReadOnly ) {
    $('rh155_descricao').addClassName('readonly');
  } else {
    $('rh155_descricao').removeClassName('readonly');
  }

  $('rh155_descricao').setValue(sDescricao);

  $('limpar').setValue(sLabelBotaoLimpar);
  $('processar').setValue(sLabelBotaoProcesar);
  $('processar').onclick = fAcaoBotaoProcessar;
  $('limpar').onclick    = fAcaoBotaoLimpar;
  return;
};

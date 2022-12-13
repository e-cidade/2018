require_once('scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js');
/**
 * Atribui tabIndex aos campos da grade de avaliação 
 * @param aPeriodos
 * @param iTabIndex
 * @returns {DBViewAvaliacao.TabIndexNotaAluno}
 */
DBViewAvaliacao.TabIndexNotaAluno = function (aPeriodos, iTabIndex) {
  
  /**
   * Array com objeto de perídos com as 
   * [{iCodigoAvaliacao:'', 
   *   iOrdemAvaliacao:"1",
   *   sTipoAvaliacao:'',  
   *   sTipoFormaAvaliacao:''}]
   */
  this.aPeriodo  = aPeriodos;
  
  /**
   * Código do tipo de tabIndex configurado para a escola (parâmetro)
   * @var {integer}
   */
  this.iTabIndex = iTabIndex;

  this.oPrimeiroElemento = null;
};


/**
 * Limpa todos os tabIndex da grid
 */
DBViewAvaliacao.TabIndexNotaAluno.prototype.limpaTabIndex = function() {
  
  this.aPeriodo.each(function (oPeriodo, iSeq) {
    
    if (oPeriodo.sTipoFormaAvaliacao.urlDecode() == 'PARECER' || oPeriodo.sTipoAvaliacao == 'R') {
      return false;
    } 
    
    $$("."+oPeriodo.iOrdemAvaliacao+"_nota").each(function (oElemento, id) {
      
      oElemento.removeAttribute("tabIndex");
    });
    
    $$("."+oPeriodo.iOrdemAvaliacao+"_falta").each(function (oElemento, id) {
      
      oElemento.removeAttribute("tabIndex");
    });
  });
  
  $('btnSalvar').removeAttribute("tabIndex");
};


/**
 * Funcao para ordenar o tabIndex de acordo com o parametro configurado para a escola
 * O TabIndex obedece o parametro (Deslocamento do Cursor) e 
 * ordena pelo periodo selecionado (ComboBox "Lançar disciplinas por periodo")   
 */
DBViewAvaliacao.TabIndexNotaAluno.prototype.reordenarTabIndex = function () {
  
  var iPrimeiroPeriodo = null;
  switch (new Number(this.iTabIndex).valueOf()) {
    
    case 1:
      
      iPrimeiroPeriodo = this.tabIndexNotaFalta();
      break;
    case 2:
      
      iPrimeiroPeriodo = this.tabIndexNotaNota();
      break;
      
    default:
      
      this.tabIndexNotaFalta();
      break;
  }
  
  if (iPrimeiroPeriodo != null) {
    
    $$("."+iPrimeiroPeriodo+'_nota').each(function (oNota, iInd) {
      
      oNota.focus();
      throw $break;  
    });
  }

};


/**
 * Ordena o tabIndex do input nota para o input falta do 
 * periodo selecionado (ComboBox  "Lançar disciplinas por periodo")
 */
DBViewAvaliacao.TabIndexNotaAluno.prototype.tabIndexNotaFalta = function () {
 
  var iIndexNota       = 1;
  var iIndexFalta      = 2;
  var iPrimeiroPeriodo = null;
  var oSelf            = this;
  
  this.aPeriodo.each(function (oPeriodo, iSeq) {
    
    if (oPeriodo.sFormaAvaliacao.urlDecode() == 'PARECER' || oPeriodo.sTipoAvaliacao == 'R') {
      return;
    }
    
    if (iPrimeiroPeriodo == null) {
      iPrimeiroPeriodo = oPeriodo.iOrdemAvaliacao;
    }
    
    $$("."+oPeriodo.iOrdemAvaliacao+"_nota").each(function (oNota, i) {
      
      if ( oNota.getAttribute('readonly') ) {
        return;
      } 
      oNota.setAttribute('tabIndex', iIndexNota);
      
      if (iIndexNota == 1) {
        oSelf.oPrimeiroElemento = oNota;
      }
      iIndexNota += 2;
    });
    
    $$("."+oPeriodo.iOrdemAvaliacao+'_falta').each(function (oFalta, i) {
      
      if ( oFalta.getAttribute('readonly') ) {
        return;
      } 
      oFalta.setAttribute('tabIndex', iIndexFalta);
      iIndexFalta += 2;
    });
  });
  
  $('btnSalvar').setAttribute('tabIndex', iIndexFalta);
  
  return iPrimeiroPeriodo;
};


/**
 * Ordena o tabIndex do input nota para o proximo input nota do 
 * periodo selecionado (ComboBox  "Lançar disciplinas por periodo")
 */
DBViewAvaliacao.TabIndexNotaAluno.prototype.tabIndexNotaNota = function () {
  
  var iIndice          = 1;
  var iPrimeiroPeriodo = null;
  var oSelf            = this;
  
  this.aPeriodo.each(function (oPeriodo, iSeq) {
    
    if (oPeriodo.sFormaAvaliacao.urlDecode() == 'PARECER' || oPeriodo.sTipoAvaliacao == 'R') {
      return false;
    }

    if (iPrimeiroPeriodo == null) {
      iPrimeiroPeriodo = oPeriodo.iOrdemAvaliacao;
    }

    $$("."+oPeriodo.iOrdemAvaliacao+"_nota").each(function (oNota, id) {
      
      if ( oNota.getAttribute('readonly') ) {
        return;
      } 
      oNota.setAttribute('tabIndex', iIndice);
      
      if (iIndice == 1) {
        oSelf.oPrimeiroElemento = oNota;
      }
      
      iIndice ++;
    });
  });

  $('btnSalvar').setAttribute('tabIndex', iIndice);

  return iPrimeiroPeriodo;
};
/**
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 *
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */

PeriodoEfetividade = function() {

  this.dataInicio = new Element('input', {
    'id'    : 'periodoInicio',
    'type'  : 'text',
  });

  this.dataFim    = new Element('input', {
    'id'    : 'periodoFim',
    'type'  : 'text',
  });

  this.labelDataFim = new Element('label', {
    'id'         : 'lbl_periodoFim',
    'for'        : 'periodoFim',
  });
  
  this.labelDataFim.innerHTML    = 'até: ';
  this.labelDataFim.style.margin = '0 10px';

  this.__initDataSugerida();

  this.PT_BR = "$3/$2/$1";
  this.EN_US = "$1-$2-$3";
};

PeriodoEfetividade.prototype = {


  '__initDataSugerida' : function () {

    var dataAtual = new Date();
    var diaFim    = 31;

    switch(parseInt(dataAtual.toJSON().replace(/\d{4}\-(\d{2}).*/, "$1"))) {

      case 4:
      case 6:
      case 9:
      case 11:
        diaFim = 30;
        break;
        
      case 2:

        diaFim = 28;

        if( !(parseInt(dataAtual.toJSON().replace(/(\d{4})\-\d{2}.*/, "$1")) % 4) ) {
          diaFim = 29;
        }
        break;
    }
  
    this.dataInicio.value =  dataAtual.toJSON().replace(/(\d{4})\-(\d{2}).*/, "01/$2/$1");
    this.dataFim.value    =  dataAtual.toJSON().replace(/(\d{4})\-(\d{2}).*/, diaFim+"/$2/$1");
  },
  
  '__initComponentesData' : function() {
    this.dataInicio = new DBInputDate(this.dataInicio);
    this.dataFim    = new DBInputDate(this.dataFim);
  },

  'limpar' : function() {

    this.dataInicio.inputElement.value = '';
    this.dataFim.inputElement.value    = '';
  },

  'getDataInicio' : function () {
    return this.dataInicio.getValue();
  },
  
  'getDataFim' : function () {
    return this.dataFim.getValue();
  },

  'getPeriodo' : function () {
    return {
      dataInicio : this.getDataInicio(),
      dataFim    : this.getDataFim(),
    }
  },

  getDataFormatada : function (date, formato) {

    if(formato == null || typeof formato == 'undefined' || formato == '' || formato.trim() == '') {
      formato = this.EN_US;
    }

    return date.toJSON().replace(/(\d{4})\-(\d{2})\-(\d{2}).*/, formato);
  },

  'validarPreenchimentoPeriodo' : function () {
    
    if(this.getDataInicio() == '' || this.getDataInicio() == null) {
      return false;
    }
      
    if(this.getDataFim() == '' || this.getDataFim() == null) {
      return false;
    }

    return true;
  },

  /**
   * Constrói os elementos de período
   * @param oElemento
   */
  'show' : function(oElemento) {

    oElemento.appendChild(this.dataInicio);
    oElemento.appendChild(this.labelDataFim);
    oElemento.appendChild(this.dataFim);
    
    this.__initComponentesData();
  },
};
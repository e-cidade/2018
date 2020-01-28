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

(function(exports) {

  var MarcacaoPonto = function (tipo, hora, dia, data, manual, justificativa) {

    if(tipo === null) {
      throw TypeError("Informe um tipo de marcacao");
    }

    this.codigo        = null;
    this.tipo          = tipo;
    this.hora          = hora || null;
    this.diaPonto      = dia || null;

    if(this.diaPonto !== null && (data === '' || data === null || typeof data == 'undefined')) {
      data = this.diaPonto.data;
    }

    this.date          = new Date(data);
    this.data          = null;
    
    this.manual        = manual || false;
    this.justificativa = justificativa || null;

    this.__setaData();
  };

  MarcacaoPonto.prototype = {

    ENTRADA1  : 1,
    SAIDA1    : 2,
    ENTRADA2  : 3,
    SAIDA2    : 4,
    ENTRADA3  : 5,
    SAIDA3    : 6,

    '__setaData' : function() {
      this.data = this.date.toJSON().match(/^\d{4}\-\d{2}\-\d{2}/) !== null ? this.date.toJSON().match(/^\d{4}\-\d{2}\-\d{2}/)[0] : null;
    },

    'getTipo' : function() {
      
      var descricaoTipo = 'ENTRADA';
      var codigoTipo    = this.tipo;
      
      if(this.tipo > 1) {

        codigoTipo -= 1;

        if(this.tipo > 3) {
          codigoTipo -= 1;
        }
      }

      if([MarcacaoPonto.prototype.SAIDA1, MarcacaoPonto.prototype.SAIDA2, MarcacaoPonto.prototype.SAIDA3].indexOf(this.tipo) > -1) {
        descricaoTipo = 'SAIDA';
        codigoTipo    = this.tipo/2;
      }

      return descricaoTipo + ' ' + codigoTipo;
    },

    'modifyDate' : function (formato) {

      if(formato.match(/^\+{1}\d*\s{1}\w*$/) === null) {
        alert('Não foi possível alterar a data da marcação');
        return;
      }
      
      var sinal            = formato.substr(0,1);
      var qtde             = formato.split(' ')[0];
      var periodo          = formato.split(' ')[1].substr(0,1);
      var periodoModificar = 0;

      switch (periodo) {
        case 'd': //dia, day
          periodoModificar = qtde * 24 * 3600 * 1000;
          break;

        case 'm': //mês, month
          periodoModificar = qtde * 30 * 24 * 3600 * 1000;
          break;

        case 'y': //year
        case 'a': //ano
          periodoModificar = qtde * 12 * 30 * 24 * 3600 * 1000;
          break;
      }

      if(sinal == '-') {
        periodoModificar = periodoModificar * (-1);
      }

      this.date.setTime(this.date.getTime() + periodoModificar);
      this.__setaData();
    },

    'validarHoraMenor' : function (horaMarcacaoAnterior, horaPrimeiraMarcacao, horaMarcada) {

      if(horaMarcacaoAnterior !== null && horaPrimeiraMarcacao !== null) {
        
        if(horaMarcacaoAnterior.trim() !== '' && horaPrimeiraMarcacao.trim() !== '') {

          if(horaMarcada.replace(/\:/g, '') <= horaMarcacaoAnterior.replace(/\:/g, '')) {

            if(horaMarcada.replace(/\:/g, '') > horaPrimeiraMarcacao.replace(/\:/g, '') || (this.tipo == 2 && horaMarcada != '')) {
              alert('A hora informada deve ser MAIOR que a hora anterior');
              return false;
            }
          }
        }
      }

      return true;
    },

    'validarHoraMaior' : function (horaMarcacaoPosterior, horaMarcada) {

      if(horaMarcacaoPosterior !== null) {
        
        if(horaMarcacaoPosterior.trim() !== '') {

          if(horaMarcada.replace(/\:/g, '') >= horaMarcacaoPosterior.replace(/\:/g, '')) {

            alert('A hora informada deve ser MENOR que a hora posterior');
            return false;
          }
        }
      }

      return true;
    },
  };

  /**
   *  Construtor estatico que não pode ser reescrito/sobrecarregado
   *  @return MarcacaoPonto;
   */
  Object.freeze(MarcacaoPonto.create = function() {
    return new MarcacaoPonto();
  });

  exports.MarcacaoPonto = MarcacaoPonto;
  return MarcacaoPonto;

})(this);

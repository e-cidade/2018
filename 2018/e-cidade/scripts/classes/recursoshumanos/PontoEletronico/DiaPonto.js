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


  var DiaPonto = function (data, matricula) {

    this.codigo  = null;

    this.date    = new Date(data) || new Date();
    this.data    = null;

    this.horasTrabalhadas      = null;
    this.horasAtraso           = null;
    this.horasFalta            = null;
    this.horasExtras50         = null;
    this.horasExtras75         = null;
    this.horasExtras100        = null;
    this.horasAdicinalNoturno  = null;

    this.matricula             = matricula || null;
    this.marcacoes             = {};
    this.jornada               = {} || null;

    this.feriado          = false;
    this.dsrFolga         = false;
    this.descricaoJornada = '';
    this.afastamento      = {};

    this.__init();
  };

  DiaPonto.prototype = {

    '__init'     : function () {
      this.__setaData();
      this.marcacoes = Collection.create().setId('tipo');
    },

    '__setaData' : function() {
      this.data = this.date.toJSON().match(/^\d{4}\-\d{2}\-\d{2}/) !== null ? this.date.toJSON().match(/^\d{4}\-\d{2}\-\d{2}/)[0] : null;
    },

    'getMarcacoes' : function () {
      return this.marcacoes.get();
    },

    'getMarcacoesEntrada' : function () {

      var marcacoesEntrada = [];

      try {
        if(this.marcacoes.get(MarcacaoPonto.prototype.ENTRADA1)) {
          marcacoesEntrada.push(this.marcacoes.get(MarcacaoPonto.prototype.ENTRADA1));
        }
      } catch (e) {
        console.error(e);
      }

      try{
        if(this.marcacoes.get(MarcacaoPonto.prototype.ENTRADA2)) {
          marcacoesEntrada.push(this.marcacoes.get(MarcacaoPonto.prototype.ENTRADA2));
        }
      } catch (e) {
        console.error(e);
      }

      try{
        if(this.marcacoes.get(MarcacaoPonto.prototype.ENTRADA3)) {
          marcacoesEntrada.push(this.marcacoes.get(MarcacaoPonto.prototype.ENTRADA3));
        }
      } catch (e) {
        console.error(e);
      }

      return marcacoesEntrada;
    },

    'getMarcacoesSaida' : function () {

      var marcacoesSaida = [];

      try {
        if(this.marcacoes.get(MarcacaoPonto.prototype.SAIDA1)) {
          marcacoesSaida.push(this.marcacoes.get(MarcacaoPonto.prototype.SAIDA1));
        }
      } catch (e) {
        console.error(e);
      }

      try {
        if(this.marcacoes.get(MarcacaoPonto.prototype.SAIDA2)) {
          marcacoesSaida.push(this.marcacoes.get(MarcacaoPonto.prototype.SAIDA2));
        }
      } catch (e) {
        console.error(e);
      }

      try {
        if(this.marcacoes.get(MarcacaoPonto.prototype.SAIDA3)) {
          marcacoesSaida.push(this.marcacoes.get(MarcacaoPonto.prototype.SAIDA3));
        }
      } catch (e) {
        console.error(e);
      }

      return marcacoesSaida;
    },

    'getMarcacao' : function(tipo) {

      try {
        if(tipo !== null){
          return this.marcacoes.get(tipo);
        }
      } catch (e) {
        console.error(e);
      }

      return null;
    },

    'addMarcacao' : function (marcacao) {
      this.marcacoes.add(marcacao);
    },

    'validarMarcacoes' : function(horaMarcada, tipoMarcacao) {

      var retornoValidacao = true;

      this.getMarcacoes().each(function (marcacao) {

        if(tipoMarcacao == marcacao.tipo) {

          if(marcacao.tipo > MarcacaoPonto.prototype.ENTRADA1) {

            var horaMarcacaoAnterior = this.getMarcacao(marcacao.tipo - 1) ? this.getMarcacao(marcacao.tipo - 1).hora : null;
            var horaPrimeiraMarcacao = this.getMarcacao(MarcacaoPonto.prototype.ENTRADA1) ? this.getMarcacao(MarcacaoPonto.prototype.ENTRADA1).hora : null;

            if(horaPrimeiraMarcacao == null) {
              horaPrimeiraMarcacao = this.jornada.horas.get(MarcacaoPonto.prototype.ENTRADA1).oHora.date.match(/\d{1,2}\:\d{2}/g)[0];
            }

            retornoValidacao = marcacao.validarHoraMenor(horaMarcacaoAnterior, horaPrimeiraMarcacao, horaMarcada);
          }

          if(retornoValidacao) {

            if(marcacao.tipo < MarcacaoPonto.prototype.SAIDA3) {

              var horaMarcacaoPosterior = this.getMarcacao(marcacao.tipo + 1) ? this.getMarcacao(marcacao.tipo + 1).hora : null;

              retornoValidacao = marcacao.validarHoraMaior(horaMarcacaoPosterior, horaMarcada);
            }
          }
        }
      }.bind(this));

      return retornoValidacao;
    },

    'verificarDatasMarcacoes' : function (tipoMarcacao) {

      this.getMarcacoes().each(function (marcacao) {

        if(tipoMarcacao == marcacao.tipo || marcacao.tipo > tipoMarcacao) {

          if(marcacao.tipo > MarcacaoPonto.prototype.ENTRADA1) {

            var primeiraHoraJornada = this.jornada.horas.itens[0].sHora;

            marcacao.date = new Date(this.data);
            marcacao.__setaData();

            if(    marcacao.hora !== null
              && marcacao.hora.trim() !== ''
              && marcacao.hora.replace(/\:/g, '') < primeiraHoraJornada.replace(/\:/g, '')) {
              marcacao.modifyDate('+1 day');
            }
          }
        }
      }.bind(this));
    },

    'moverMarcacao' : function (tipoOrigem, tipoDestino) {

      var marcacaoOrigem  = this.getMarcacao(tipoOrigem);
      var marcacaoDestino = this.getMarcacao(tipoDestino);

      if(marcacaoOrigem.hora == null) {
        return;
      }

      if(marcacaoDestino.hora != null) {
        return false;
      }

      marcacaoDestino.hora          = marcacaoOrigem.hora;
      marcacaoDestino.justificativa = marcacaoOrigem.justificativa;
      marcacaoDestino.manual        = marcacaoOrigem.alterado || marcacaoOrigem.manual;

      marcacaoOrigem.hora          = null;
      marcacaoOrigem.justificativa = null;
      marcacaoOrigem.manual        = null;

      delete marcacaoOrigem.alterado;
    }
  };

  /**
   *  Construtor estatico que não pode ser reescrito/sobrecarregado
   *  @return DiaPonto;
   */
  Object.freeze(DiaPonto.create = function() {
    return new DiaPonto();
  });

  exports.DiaPonto = DiaPonto;
  return DiaPonto;

})(this);

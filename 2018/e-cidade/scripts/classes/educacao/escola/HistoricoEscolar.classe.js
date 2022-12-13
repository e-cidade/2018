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

/**
 *  Classe para controle do Hist�rico Escolar
 */
var HistoricoEscolar = (function() {

  /*
  * Quando a escola tem permiss�o total para dar manuten��o no hist�rico do aluno
  */
  HistoricoEscolar.PERMITE_MANUTENCAO = 1;

  /*
   * Quando a �ltima matr�cula do aluno pertencer � escola atual e o per�odo de manuten��o de hist�rico da escola
   * anterior ainda estiver vigente
   */
  HistoricoEscolar.PERMITE_MANUTENCAO_ETAPAS_MAIORES_OU_IGUAIS = 2;

  /*
   * Quando a escola anterior quiser dar manuten��o e o per�odo de manuten��o do hist�rico estiver vigente
   */
  HistoricoEscolar.PERMITE_MANUTENCAO_ETAPAS_MENORES = 3;

  /*
   * Quando a escola n�o pode alterar o hist�rico
   */
  HistoricoEscolar.NAO_PERMITE_MANUTENCAO = 4;

  /**
   * Construtor da classe
   *
   * @param {integer} iStatusHistorico       C�digo do status de permiss�o de manuten��o do hist�rico escolar
   * @param {integer} iOrdemEtapaAtual       Ordem da etapa atual na qual o aluno se encontra
   * @param {integer} iOrdemEtapaSelecionada Ordem da etapa selecionada na tela na qual se deseja dar manuten��o
   */
  function HistoricoEscolar( iStatusHistorico, iOrdemEtapaAtual, iOrdemEtapaSelecionada ) {

    this.iStatusHistorico       = iStatusHistorico;
    this.iOrdemEtapaAtual       = iOrdemEtapaAtual;
    this.iOrdemEtapaSelecionada = iOrdemEtapaSelecionada;
  }

  /**
   * Retorna se a Escola possui permiss�o de manuten��o do hist�rico para a etapa selecionada, de acordo com o status
   * de altera��o do hist�rico
   *
   * @returns boolean
   */
  HistoricoEscolar.prototype.permiteManutencao = function() {

    var lPermiteManutencao = true;

    switch( this.iStatusHistorico ) {
      
      case HistoricoEscolar.PERMITE_MANUTENCAO:
      case HistoricoEscolar.PERMITE_MANUTENCAO_ETAPAS_MAIORES_OU_IGUAIS && undefined === this.iOrdemEtapaAtual:
        break;

      case HistoricoEscolar.PERMITE_MANUTENCAO_ETAPAS_MAIORES_OU_IGUAIS:

        if ( this.iOrdemEtapaAtual > this.iOrdemEtapaSelecionada ) {
          lPermiteManutencao = false;
        }

        break;

      case HistoricoEscolar.PERMITE_MANUTENCAO_ETAPAS_MENORES:

        if ( this.iOrdemEtapaAtual <= this.iOrdemEtapaSelecionada ) {
          lPermiteManutencao = false;
        }

        break;

      case HistoricoEscolar.NAO_PERMITE_MANUTENCAO:
        lPermiteManutencao = false;
        break;
    }

    return lPermiteManutencao;
  };

  return HistoricoEscolar;
})();
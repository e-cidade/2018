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
 *  Classe para controle do Histórico Escolar
 */
var HistoricoEscolar = (function() {

  /*
  * Quando a escola tem permissão total para dar manutenção no histórico do aluno
  */
  HistoricoEscolar.PERMITE_MANUTENCAO = 1;

  /*
   * Quando a última matrícula do aluno pertencer à escola atual e o período de manutenção de histórico da escola
   * anterior ainda estiver vigente
   */
  HistoricoEscolar.PERMITE_MANUTENCAO_ETAPAS_MAIORES_OU_IGUAIS = 2;

  /*
   * Quando a escola anterior quiser dar manutenção e o período de manutenção do histórico estiver vigente
   */
  HistoricoEscolar.PERMITE_MANUTENCAO_ETAPAS_MENORES = 3;

  /*
   * Quando a escola não pode alterar o histórico
   */
  HistoricoEscolar.NAO_PERMITE_MANUTENCAO = 4;

  /**
   * Construtor da classe
   *
   * @param {integer} iStatusHistorico       Código do status de permissão de manutenção do histórico escolar
   * @param {integer} iOrdemEtapaAtual       Ordem da etapa atual na qual o aluno se encontra
   * @param {integer} iOrdemEtapaSelecionada Ordem da etapa selecionada na tela na qual se deseja dar manutenção
   */
  function HistoricoEscolar( iStatusHistorico, iOrdemEtapaAtual, iOrdemEtapaSelecionada ) {

    this.iStatusHistorico       = iStatusHistorico;
    this.iOrdemEtapaAtual       = iOrdemEtapaAtual;
    this.iOrdemEtapaSelecionada = iOrdemEtapaSelecionada;
  }

  /**
   * Retorna se a Escola possui permissão de manutenção do histórico para a etapa selecionada, de acordo com o status
   * de alteração do histórico
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
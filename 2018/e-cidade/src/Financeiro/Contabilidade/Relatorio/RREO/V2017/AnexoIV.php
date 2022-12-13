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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;

/**
 * Class AnexoIV
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017
 */
class AnexoIV extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal {

  /**
   * Caminho das mensagens utilizada pelo relatórioa
   * @type string
   */
  const MENSAGEM = 'financeiro.contabilidade.AnexoIV.';

  /**
   * Código do relatório no E-Cidade
   * @type integer
   */
  const CODIGO_RELATORIO = 164;

  /**
   * @var \Instituicao[]
   */
  private $aInstituicoesRPPS = array();

  /**
   * @var \DBDate
   */
  private $oDataInicialExercicioAnterior;

  /**
   * @var \DBDate
   */
  private $oDataFinalExercicioAnterior;

  /**
   * @var integer
   */
  private $iAnoExercicioAnterior;

  /**
   * AnexoIV constructor.
   *
   * @param int $iAnoSessao
   * @param int $iCodigoPeriodo
   * @throws \BusinessException
   */
  public function __construct($iAnoSessao, $iCodigoPeriodo) {

    parent::__construct($iAnoSessao, self::CODIGO_RELATORIO, $iCodigoPeriodo);

    $aTiposInstituicoes      = array(\Instituicao::TIPO_RPPS_EXCETO_AUTARQUIA, \Instituicao::TIPO_AUTARQUIA_RPPS);
    $this->aInstituicoesRPPS = \InstituicaoRepository::getInstituicoesPorTipo($aTiposInstituicoes);
    if (count($this->aInstituicoesRPPS) == 0) {

      $aItensTiposInstituicoes      = \InstituicaoRepository::getTiposIntituicao($aTiposInstituicoes);
      $aDescricoesTiposInstituicoes = array();
      foreach ($aItensTiposInstituicoes as $itemTipoInstituicao) {
        $aDescricoesTiposInstituicoes[] = $itemTipoInstituicao->db21_codtipo .' - '. $itemTipoInstituicao->db21_nome;
      }

      $sDescricaoTiposInstituicoes  = implode("\n", $aDescricoesTiposInstituicoes);

      $oStdMensagem = (object)array('descricao' => $sDescricaoTiposInstituicoes);
      throw new \BusinessException(_M(self::MENSAGEM . 'tipo_instituicao_nao_encontrado', $oStdMensagem));
    }

    $aCodigosInstituicoes = array();
    foreach ($this->aInstituicoesRPPS as $oInstituicao) {
      $aCodigosInstituicoes[] = $oInstituicao->getCodigo();
    }
    $this->sListaInstit = implode(',', $aCodigosInstituicoes);

    $this->oDataInicialExercicioAnterior = clone $this->oDataInicial;
    $this->oDataInicialExercicioAnterior->modificarIntervalo('-1 year');
    $this->oDataFinalExercicioAnterior   = clone $this->oDataFinal;
    $this->oDataFinalExercicioAnterior->modificarIntervalo('-1 year');
    $this->iAnoExercicioAnterior = ($this->iAnoUsu - 1);
  }

  /**
   * @return \stdClass[]
   */
  public function getDados() {

    if (empty($this->aLinhasConsistencia)) {

      parent::getDados();
      $this->processaBalanceteReceita();
      $this->processaBalanceteDespesa();
      $this->processaTotalizadores($this->aLinhasConsistencia);
      $this->totalizarResultadosPrevidenciarios();
      $this->arredondarValores();
    }
    return $this->aLinhasConsistencia;
  }

  /**
   * Executa o cálculo entre receita e despesa
   */
  private function totalizarResultadosPrevidenciarios() {

    $this->aLinhasConsistencia[51]->dot_ini         = ($this->aLinhasConsistencia[34]->prev_ini   - $this->aLinhasConsistencia[50]->dot_ini);
    $this->aLinhasConsistencia[51]->dot_atual       = ($this->aLinhasConsistencia[34]->prev_atual - $this->aLinhasConsistencia[50]->dot_atual);
    $this->aLinhasConsistencia[51]->liq_atebim      = ($this->aLinhasConsistencia[34]->rec_atebim - $this->aLinhasConsistencia[50]->liq_atebim);
    $this->aLinhasConsistencia[51]->liq_atebimexant = ($this->aLinhasConsistencia[34]->recbiexant - $this->aLinhasConsistencia[50]->liq_atebimexant);

    $this->aLinhasConsistencia[110]->dot_ini         = ($this->aLinhasConsistencia[93]->prev_ini   - $this->aLinhasConsistencia[109]->dot_ini);
    $this->aLinhasConsistencia[110]->dot_atual       = ($this->aLinhasConsistencia[93]->prev_atual - $this->aLinhasConsistencia[109]->dot_atual);
    $this->aLinhasConsistencia[110]->liq_atebim      = ($this->aLinhasConsistencia[93]->rec_atebim - $this->aLinhasConsistencia[109]->liq_atebim);
    $this->aLinhasConsistencia[110]->liq_atebimexant = ($this->aLinhasConsistencia[93]->recbiexant - $this->aLinhasConsistencia[109]->liq_atebimexant);
  }

  /**
   * Executa o balancete da receita para o exercicio anterior
   */
  private function processaBalanceteReceita() {

    $sWhereReceita      = "o70_instit in ({$this->getInstituicoes()})";
    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
                                          $sWhereReceita, $this->iAnoExercicioAnterior,
                                          $this->oDataInicialExercicioAnterior->getDate(),
                                          $this->oDataFinalExercicioAnterior->getDate()
    );

    foreach ($this->aLinhasProcessarReceita as $iLinha ) {

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->getColunasPorLinha($oLinha, array(3));
      $oLinha->recbiexant = 0;

      \RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceita,
                                                  $oLinha,
                                                  $aColunasProcessar,
                                                  \RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
      );

      $this->processaValorManualPorLinhaEColuna($iLinha, 3);
    }
    $this->limparEstruturaBalanceteReceita();
  }

  /**
   * Processa o balancete da despesa para os exercícios anteriores.
   */
  private function processaBalanceteDespesa() {

    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, " o58_instit in({$this->getInstituicoes()}) ",
                                          $this->iAnoExercicioAnterior,
                                          $this->oDataInicialExercicioAnterior->getDate(),
                                          $this->oDataFinalExercicioAnterior->getDate());

    foreach ($this->aLinhasProcessarDespesa as $iLinha) {

      /**
       * Linha VALOR não possui colunas, sendo para cálculo, utilizando a fórmula da dotação.
       * Neste caso, não deve calcular nada
       */
      if($iLinha == 53) {
        continue;
      }

      $aColunas = array(3, 5);
      if ($this->oPeriodo->getCodigo() == 11) {
        $aColunas[] = 7;
      }

      $oLinha            = $this->aLinhasConsistencia[$iLinha];
      $aColunasProcessar = $this->getColunasPorLinha($oLinha, $aColunas);

      $oLinha->liq_atebimexant = 0;
      $oLinha->emp_atebimexant = 0;
      $oLinha->rp_nprocexant   = 0;

      \RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                  $oLinha,
                                                  $aColunasProcessar,
                                                  \RelatoriosLegaisBase::TIPO_CALCULO_DESPESA);


      foreach ($aColunas as $iColuna) {
        $this->processaValorManualPorLinhaEColuna($iLinha, $iColuna);
      }
    }
    $this->limparEstruturaBalanceteDespesa();
  }

  /**
   * Retorna os dados do relatórios simplificado
   * @return \stdClass
   */
  public function getDadosSimplificado() {

    $this->getDados();
    $oDadosSimplificado = new \stdClass();
    $oDadosSimplificado->nReceitasRealizadas = $this->aLinhasConsistencia[34]->rec_atebim;
    $oDadosSimplificado->nDespesasLiquidadas = $this->aLinhasConsistencia[50]->liq_atebim;
    $oDadosSimplificado->nDespesasEmpenhadas = $this->aLinhasConsistencia[50]->emp_atebim;

    return $oDadosSimplificado;
  }

  /**
   * @return int
   */
  public function getExercicioAnterior() {
    return $this->iAnoExercicioAnterior;
  }
}

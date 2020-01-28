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

require_once modification("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
/**
 * Avaliacao periodica
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.16 $
 */
class AvaliacaoPeriodica implements IElementoAvaliacao {

  private $iCodigo;

  /**
   * Forma de avaliacao
   * @var FormaAvaliacao
   */
  private $oFormaAvaliacao;

  /**
   * Periodo de avaliacao
   * @var PeriodoAvaliacao
   */
  private $oPeriodoAvaliacao;

  /**
   * Ordem de apresentacao da Avaliacao
   * @var integer
   */
  private $iOrdemSequencia;


  /**
   * Elemento dependente
   * @var IElementoAvaliacao
   */
  private $oElementoDependente;

  /**
   * Numero máximo de disciplinas para recuperacao
   * @var integer
   */
  private $iQuantidadeMaximaDeDisciplinasParaRecuperacao;


  /**
   * Recuperacao possui o julgamento da menor Nota
   * @var bool
   */
  private $lJulgaMenorNota = false;

  private $lApareceNoBoletim = true;

  /**
   * Método construtor
   * @param integer $iCodigo Código da avaliacao periodica
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoAvaliacaoPeriodica = db_utils::getDao('procavaliacao');
      $sSqlAvaliacaoPeriodica = $oDaoAvaliacaoPeriodica->sql_query_file($iCodigo);
      $rsAvaliacaoPeriodica   = $oDaoAvaliacaoPeriodica->sql_record($sSqlAvaliacaoPeriodica);

      if ($oDaoAvaliacaoPeriodica->numrows > 0) {

        $oAvaliacaoPeriodica     = db_utils::fieldsMemory($rsAvaliacaoPeriodica, 0);
        $this->iCodigo           = $oAvaliacaoPeriodica->ed41_i_codigo;
        $this->oFormaAvaliacao   = FormaAvaliacaoRepository::getByCodigo($oAvaliacaoPeriodica->ed41_i_formaavaliacao);
        $this->oPeriodoAvaliacao = PeriodoAvaliacaoRepository::getPeriodoAvaliacaoByCodigo($oAvaliacaoPeriodica->ed41_i_periodoavaliacao);
        $this->lApareceNoBoletim = $oAvaliacaoPeriodica->ed41_c_boletim == 'S';

        $this->iQuantidadeMaximaDeDisciplinasParaRecuperacao = $oAvaliacaoPeriodica->ed41_numerodisciplinasrecuperacao;
        if (empty($this->iQuantidadeMaximaDeDisciplinasParaRecuperacao)) {
          $this->iQuantidadeMaximaDeDisciplinasParaRecuperacao = 999;
        }
        $this->iOrdemSequencia                               = $oAvaliacaoPeriodica->ed41_i_sequencia;
        if (!empty($oAvaliacaoPeriodica->ed41_i_procavalvinc)) {

          $this->oElementoDependente = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oAvaliacaoPeriodica->ed41_i_procavalvinc);
        }
        if (!empty($oAvaliacaoPeriodica->ed41_i_procresultvinc)) {
          $this->oElementoDependente = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oAvaliacaoPeriodica->ed41_i_procresultvinc);
        }

        $this->lJulgaMenorNota = $oAvaliacaoPeriodica->ed41_julgamenoravaliacao == 't';
        unset($oAvaliacaoPeriodica);
      }
    }
  }

  /**
   * Retona a descricao do periodo de avaliacao
   * @see IElementoAvaliacao::getDescricao()
   * @return string
   */
  public function getDescricao() {
    return $this->getPeriodoAvaliacao()->getDescricao();
  }

  /**
   * Retorna a forma de avaliacao definida para o periodo
   * @see IElementoAvaliacao::getFormaDeAvaliacao()
   * @return FormaAvaliacao
   */
  public function getFormaDeAvaliacao() {
    return $this->oFormaAvaliacao;
  }

  /**
   * Define a forma de avaliacao do periodo
   * @param FormaAvaliacao $oFormaAvaliacao instancia de forma de FormaAvaliacao
   */
  public function setFormaDeAvaliacao(FormaAvaliacao $oFormaAvaliacao) {
    $this->oFormaAvaliacao  = $oFormaAvaliacao;
  }

  /**
   * Retorna o codigo do periodo de avaliacao do procedimento
   * @see IElementoAvaliacao::getCodigo()
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o periodo de avaliacao da avaliacao
   * @param PeriodoAvaliacao $oPeriodoAvaliacao
   */
  public function setPeriodoAvaliacao (PeriodoAvaliacao $oPeriodoAvaliacao) {
    $this->oPeriodoAvaliacao = $oPeriodoAvaliacao;
  }

  /**
   * Retorna o periodo de avaliacao da avaliacao
   * @return PeriodoAvaliacao
   */
  public function getPeriodoAvaliacao () {
    return $this->oPeriodoAvaliacao;
  }

  /**
   * Define a ordem de apresentacao da Avaliacao
   * @param integer $iOrdemSequencia
   */
  public function setOrdemSequencia($iOrdemSequencia) {

    $this->iOrdemSequencia = $iOrdemSequencia;
  }

  /**
   * Retorna a ordem de apresentacao da Avaliacao
   * @see IElementoAvaliacao::getOrdemSequencia()
   */
  public function getOrdemSequencia() {

    return $this->iOrdemSequencia;
  }

  /**
   * Verifica se o periodo é um resultado
   */
  public function isResultado() {
    return false;
  }

  /**
   * Retorna o aproveitamento minimo para a aprovacao no resultado
   * @return Mixed
   */
  public function getAproveitamentoMinimo() {
    return $this->getFormaDeAvaliacao()->getAproveitamentoMinino();
  }

  /**
   * Retorna o elemetno de avaliacao de qual a avaliacao depende
   * @return AvaliacaoPeriodica|ResultadoAvaliacao
   */
  public function getElementoAvaliacaoVinculado() {
    return $this->oElementoDependente;
  }

  /**
   * Retorna a descricao Abreviada do periodo
   * @return string
   */
  public function getDescricaoAbreviada() {
    return $this->getPeriodoAvaliacao()->getDescricaoAbreviada();
  }

  /**
   * Retorna a quantidade maxima de disciplinas que o aluno pode reprovar para estar apto a recuperação
   * @return int
   */
  public function quantidadeMaximaDisciplinasParaRecuperacao() {
    return $this->iQuantidadeMaximaDeDisciplinasParaRecuperacao;
  }

  /**
   * Verifica se a Recuperacao substitui a menor nota
   * @return bool
   */
  public function temJulgamentoMenorNota() {
    return $this->lJulgaMenorNota;
  }

  /**
   * Retorna true se o período é uma recuperação
   * @return boolean
   */
  public function isRecuperacao() {

    if ( !empty($this->oElementoDependente) ) {
      return true;
    }
    return false;
  }

  /**
   * Verifica se a Avaliação vai ser impressa no boletim
   * @return boolean
   */
  public function imprimeNoBoletim() {
    return $this->lApareceNoBoletim;
  }
}
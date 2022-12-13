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

/**
 * Procedimentos de avaliacao da disciplina
 * @author     Fabio Esteves - fabio.esteves@dbseller.com.br
 * @package    educacao
 * @subpackage avaliacao
 * @version    $Revision: 1.167 $
 */
class DiarioAvaliacaoDisciplina extends DiarioDisciplina {

  const CALCULAR_PROPORCIONALIDADE = 1;

  /**
   * Verifica se o diario esta encerrado
   * 'S' = true
   * 'N' = false
   * @var boolean
   */
  private $lEncerrado;

  /**
   * Instancia de Disciplina
   * @var Disciplina
   */
  private $oDisciplina;

  /**
   * Instancia da classe Regencia
   * @var Regencia
   */
  protected $oRegencia;

  /**
   * Instanciia
   * @var DiarioClasse
   */
  protected $oDiario;
  /**
   * Colecao de AvaliacaoAproveitamento
   * @var AvaliacaoAproveitamento[]
   */
  private $aAvaliacaoAproveitamento = array();

  /**
   * Caso disciplina tenha uma avaliação alternativa
   * Só quando procedimento de avaliação igual a soma
   * @var AvaliacaoAlternativa
   */
  private $oAvaliacaoAlternativa = null;

  /**
   * Guarda a ordem dos períodos da proporcionalidade
   * @var array|null
   */
  private $aOrdemPeriodoProporcionalidade = null;

  public function __construct(DiarioAvaliacaoDisciplinaVO $oDadosDiario = null) {

    if (!empty($oDadosDiario)) {

      $this->iCodigoDiario = $oDadosDiario->getCodigoDiario();
      $this->oRegencia     = $oDadosDiario->getRegencia();
      $this->oDisciplina   = $oDadosDiario->getRegencia()->getDisciplina();
      $this->lEncerrado    = $oDadosDiario->isEncerrado();
      unset($oDadosDiario);
    }
  }

  public function setDiario(DiarioClasse $oDiarioClasse) {
    $this->oDiario = $oDiarioClasse;
  }

  /**
   * Retorna o codigo da regencia
   * @return Regencia
   */
  public function getRegencia() {
    return $this->oRegencia;
  }

  /**
   * Retorna o status de encerramento do diario
   * 'S' = true
   * 'N' = false
   *  @return boolean
   */
  public function isEncerrado() {
    return $this->lEncerrado;
  }

  /**
   * Atribui um status de encerramento do diario
   * 'S' = true
   * 'N' = false
   * @param boolean $lEncerrado
   * @throws ParameterException quando parâmetro nao for um boolean
   */
  public function setEncerrado($lEncerrado) {

    if (!is_bool($lEncerrado)) {
      throw new ParameterException('Parâmetro lEncerrado informado deve ser um boolean.');
    }
    $this->lEncerrado = $lEncerrado;
  }

  /**
   * Retorna a Disciplina vinculada ao diario
   * @return Disciplina
   */
  public function getDisciplina() {
    return $this->oDisciplina;
  }

  /**
   * Adiciona as informacoes de uma avaliacao
   *
   */
  public function adicionarAvaliacao(IElementoAvaliacao $oElementoAvaliacao, $mValorAproveitamento='', $iNumeroFaltas='') {

    $aAvaliacaoesLancadas = $this->getAvaliacoes();
    $lJaLancada           = false;

    /**
     * Verificamo se a disciplina já está lancadada para o periodo.
     * caso já esteja, apenas alteramos o valor do aproveitamento e o numero de faltas.
     */
    foreach ($aAvaliacaoesLancadas as $oAvaliacao) {

      if ($oAvaliacao->getElementoAvaliacao()->getOrdemSequencia() == $oElementoAvaliacao->getOrdemSequencia()) {

        $lJaLancada = true;
        $oAvaliacao->setNumeroFaltas($iNumeroFaltas);
        $oAvaliacao->setValorAproveitamento($mValorAproveitamento);
        break;
      }
    }

    if (!$lJaLancada) {

      $oAvaliacaoAproveitamento = new AvaliacaoAproveitamento();
      $oAvaliacaoAproveitamento->setElementoAvaliacao($oElementoAvaliacao);
      $oAvaliacaoAproveitamento->setValorAproveitamento($mValorAproveitamento);
      $oAvaliacaoAproveitamento->setNumeroFaltas($iNumeroFaltas);
      $this->aAvaliacaoAproveitamento[] = $oAvaliacaoAproveitamento;
    }

    /**
     * Verificamos se existe algum periodo que dependa do resultado desse aproveitamento.
     * caso exista algo, e o aproveitamento minimo do que estmos adicionando for atigindo, devemos
     * limpar o valor do aproveitamento dependente.
     */
    $oAproveitamentoDependente = $this->getAvaliacaoDependentesDoPeriodo($oElementoAvaliacao);
    if ($oAproveitamentoDependente != '') {
      $oAproveitamentoDependente->getValorAproveitamento()->setAproveitamento('');
    }
  }

  /**
   * Retorna o aproveitamento da avaliacao
   * @return AvaliacaoAproveitamento[]
   */
  public function getAvaliacoes() {

    if (count($this->aAvaliacaoAproveitamento) == 0 && $this->iCodigoDiario != "") {

      $oDaoDiario          = new cl_diario;
      $sSqlDiarioAvaliacao = $oDaoDiario->sql_query_avaliacoes_periodo($this->iCodigoDiario);
      $rsDiarioAvaliacao   = db_query( $sSqlDiarioAvaliacao );

      if ( !$rsDiarioAvaliacao ) {
        throw new DBException('Falha ao buscar os dados do aproveitamento da avaliação.');
      }

      $iTotalLinhas = pg_num_rows( $rsDiarioAvaliacao );

      for ($iDiario = 0; $iDiario < $iTotalLinhas; $iDiario++) {

        $oDadosDiario             = db_utils::fieldsMemory($rsDiarioAvaliacao, $iDiario);
        $oAvaliavaoAproveitamento = new AvaliacaoAproveitamento($oDadosDiario->codigo);
        if ($oDadosDiario->tipo_elemento == "A") {

          $oElementoAvaliacao = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oDadosDiario->codigo_elemento);
        } else {
          $oElementoAvaliacao = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDadosDiario->codigo_elemento);
        }

        $oAvaliavaoAproveitamento->setDiarioAvaliacaoDisciplina($this);
        $oAvaliavaoAproveitamento->setElementoAvaliacao($oElementoAvaliacao);
        $oAvaliavaoAproveitamento->setNumeroFaltas($oDadosDiario->numero_faltas);
        $oAvaliavaoAproveitamento->setParecerPadronizado($oDadosDiario->parecerpadronizado);
        $oAvaliavaoAproveitamento->setAmparado(trim($oDadosDiario->amparo) == "S" ? true : false);
        $oAvaliavaoAproveitamento->setConvertido(trim($oDadosDiario->convertido) == "S" ? true : false);
        $oAvaliavaoAproveitamento->setObservacao($oDadosDiario->observacao);

        if ( !empty($oDadosDiario->codigo_faltas_abonadas) ) {
          $oAvaliavaoAproveitamento->setFaltasAbonadas(AbonoFaltaRepository::getByCodigo($oDadosDiario->codigo_faltas_abonadas));
        }

        $sTipoAvaliacao = $oElementoAvaliacao->getFormaDeAvaliacao()->getTipo();
        if ($this->oDiario->getMatricula()->isAvaliadoPorParecer()) {
          $sTipoAvaliacao = 'PARECER';
        }

        $oValorAproveitamento = null;
        switch ($sTipoAvaliacao) {

          case 'NOTA' :

            $oValorAproveitamento = new ValorAproveitamentoNota($oDadosDiario->valor_nota);
            $oValorAproveitamento->setAproveitamentoReal( $oDadosDiario->valor_nota_real );
            $oAvaliavaoAproveitamento->setParecer($oDadosDiario->parecer);
            break;

          case 'PARECER' :

            $oValorAproveitamento = new ValorAproveitamentoParecer($oDadosDiario->parecer);
            break;

         case 'NIVEL' :

            $oValorAproveitamento = new ValorAproveitamentoNivel($oDadosDiario->valor_conceito,
                                                                 $oDadosDiario->ordem_conceito);
            $oAvaliavaoAproveitamento->setParecer($oDadosDiario->parecer);
            break;
        }
        $oAvaliavaoAproveitamento->setValorAproveitamento($oValorAproveitamento);
        $oAvaliavaoAproveitamento->setAproveitamentoMinimo($oDadosDiario->minimo == "S" ? true : false);
        $oAvaliavaoAproveitamento->setEmRecuperacao($oDadosDiario->em_recuperacao == "t");
        $lAvaliacaoExterna = false;

        /**
         * a Nota sera externa quando a escola que lancou a avaliacao for
         * diferente da escola atual, ou a origem da nota for 'F', que informa que a nota é de fora da escola.
         */
        if ($oDadosDiario->tipo_elemento == "A") {

          $oAvaliavaoAproveitamento->setEscola(EscolaRepository::getEscolaByCodigo($oDadosDiario->escola));
          $oAvaliavaoAproveitamento->setTipo($oDadosDiario->origem);
          if ($oAvaliavaoAproveitamento->getTipo() == "F") {
            $oAvaliavaoAproveitamento->setEscola(EscolaProcedenciaRepository::getEscolaByCodigo($oDadosDiario->escola));
          }
          if ($oAvaliavaoAproveitamento->getEscola()->getCodigo() != $this->oDiario->getTurma()->getEscola()->getCodigo() ||
              $oAvaliavaoAproveitamento->getTipo() == 'F') {

            $lAvaliacaoExterna = true;
          }
        }
        $oAvaliavaoAproveitamento->setAvaliacaoExterna($lAvaliacaoExterna);
        $this->aAvaliacaoAproveitamento[] = $oAvaliavaoAproveitamento;
      }

      /**
       * Verificamos se todos os Resultados possuem Registros
       */
      $iTotalSemResultado = 0;
      $iAno               = $this->oDiario->getTurma()->getCalendario()->getAnoExecucao();
      foreach ($this->getPeriodosAvaliacao() as $oPeriodo) {

        if ($oPeriodo->isResultado()) {

          $lPossuiResultado = false;
          foreach ($this->aAvaliacaoAproveitamento as $oAvaliacao) {
            if ($oPeriodo->getOrdemSequencia() == $oAvaliacao->getElementoAvaliacao()->getOrdemSequencia()) {

              $lPossuiResultado = true;
              break;
            }
          }
          if (!$lPossuiResultado) {

            $iTotalSemResultado ++;
            $oRetorno = $oPeriodo->getResultado( $this->aAvaliacaoAproveitamento, false, $iAno );

            if (!$oRetorno instanceof ValorAproveitamento) {
              $oRetorno = FormaObtencao::getTipoValorAproveitamento($oPeriodo->getFormaDeAvaliacao());
            }

            $this->adicionarAvaliacao($oPeriodo, $oRetorno);
          }
        }
      }
    }

    return $this->aAvaliacaoAproveitamento;
  }

  /**
   * Salvar os dados da Avaliacao,
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Não existe transação com o banco de dados ativa");
    }

    if (isset($this->iCodigoDiario) && !empty($this->iCodigoDiario)) {

      $oDaoDiario                   = db_utils::getDao("diario");
      $oDaoDiario->ed95_c_encerrado = $this->isEncerrado() ? 'S' : 'N';
      $oDaoDiario->ed95_i_codigo    = $this->getCodigoDiario();
      $oDaoDiario->alterar($oDaoDiario->ed95_i_codigo);

      if ($oDaoDiario->erro_status == 0) {
        throw new BusinessException("Erro ao salvar o diario");
      }
    }

    foreach ($this->getAvaliacoes() as $oAvaliacao) {

      if ($oAvaliacao->getElementoAvaliacao()->isResultado()) {
        $this->salvarDadosResultado($oAvaliacao);
      } else {
        $this->salvarDadosAvaliacao($oAvaliacao);
      }
    }
  }

  /**
   * Persiste as Informacoes das avaliacoes
   *
   * @param AvaliacaoAproveitamento $oAvaliacaoAproveitamento
   * @throws BusinessException
   */
  protected function salvarDadosAvaliacao(AvaliacaoAproveitamento $oAvaliacaoAproveitamento) {

    $oDaoDiarioAvaliacao = db_utils::getDao("diarioavaliacao");
    $oDaoDiarioAvaliacao->ed72_c_amparo        = $oAvaliacaoAproveitamento->isAmparado() ? "S" : "N";
    $oDaoDiarioAvaliacao->ed72_c_tipo          = $oAvaliacaoAproveitamento->getTipo();
    $oDaoDiarioAvaliacao->ed72_i_procavaliacao = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo();
    $oDaoDiarioAvaliacao->ed72_i_diario        = $this->getCodigoDiario();
    $oDaoDiarioAvaliacao->ed72_i_escola        = $oAvaliacaoAproveitamento->getEscola()->getCodigo();
    $oDaoDiarioAvaliacao->ed72_i_numfaltas     = $oAvaliacaoAproveitamento->getNumeroFaltas();
    $oDaoDiarioAvaliacao->ed72_i_valornota     = '';
    $oDaoDiarioAvaliacao->ed72_c_valorconceito = '';
    $oDaoDiarioAvaliacao->ed72_t_parecer       = '';
    $oDaoDiarioAvaliacao->ed72_t_obs           = $oAvaliacaoAproveitamento->getObservacao();
    $oDaoDiarioAvaliacao->ed72_c_convertido    = $oAvaliacaoAproveitamento->isConvertido() ? 'S' : 'N';

    $nValorAproveitamento = $oAvaliacaoAproveitamento->getValorAproveitamento()->getAproveitamento();
    $oElementoAvaliacao   = $oAvaliacaoAproveitamento->getElementoAvaliacao();
    $sFormaAvaliacao      = $this->getRegencia()->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo();

    if ($this->oDiario->getMatricula()->isAvaliadoPorParecer()) {
      $sFormaAvaliacao = 'PARECER';
    }

    switch ($sFormaAvaliacao) {

      case 'NOTA':

        $oDaoDiarioAvaliacao->ed72_i_valornota = "{$nValorAproveitamento}";
        if ($nValorAproveitamento < $oElementoAvaliacao->getAproveitamentoMinimo()) {
          $oDaoDiarioAvaliacao->ed72_c_aprovmin = 'N';
        }
        break;

      case 'NIVEL':

        $oDaoDiarioAvaliacao->ed72_c_valorconceito = "{$nValorAproveitamento}";

        $oAproveitamento = $oElementoAvaliacao->getFormaDeAvaliacao()->getConceitoMinimo();

        if ( !is_null($oAproveitamento) ) {

          $iOrdemAvaliacao = $oElementoAvaliacao->getFormaDeAvaliacao()->getConceitoMinimo()->iOrdem;
          if ($oAvaliacaoAproveitamento->getValorAproveitamento()->getOrdem() < $iOrdemAvaliacao) {
            $oDaoDiarioAvaliacao->ed72_c_aprovmin = 'N';
          }
        }
        break;

     case 'PARECER':

        $oDaoDiarioAvaliacao->ed72_t_parecer = pg_escape_string(("{$nValorAproveitamento}"));
        break;
    }

    if ($sFormaAvaliacao != 'PARECER') {
      $oDaoDiarioAvaliacao->ed72_t_parecer = pg_escape_string(("{$oAvaliacaoAproveitamento->getParecer()}"));
    }

    /**
     * Quando aluno amparado, sempre tem aproveitamento minimo;
     */
    if ($oAvaliacaoAproveitamento->isAmparado()) {
      $oDaoDiarioAvaliacao->ed72_c_aprovmin = 'S';
    }

    $oDaoDiarioAvaliacao->ed72_c_aprovmin = $oAvaliacaoAproveitamento->temAproveitamentoMinimo()?"S":"N";
    if ($oAvaliacaoAproveitamento->getCodigo() == '') {

      $oDaoDiarioAvaliacao->ed72_c_convertido = 'N';
      $oDaoDiarioAvaliacao->incluir(null);
      $oAvaliacaoAproveitamento->setCodigo($oDaoDiarioAvaliacao->ed72_i_codigo);
    } else {

      $oDaoDiarioAvaliacao->ed72_i_codigo  = $oAvaliacaoAproveitamento->getCodigo();
      $oDaoDiarioAvaliacao->alterar($oDaoDiarioAvaliacao->ed72_i_codigo);
    }
    if ($oDaoDiarioAvaliacao->erro_status == 0) {
      throw new BusinessException("Erro ao salvar aproveitamento da avaliação");
    }

    $oDaoParecerPadronizado = db_utils::getDao("pareceraval");
    $oDaoParecerPadronizado->excluir(null," ed93_i_diarioavaliacao = {$oAvaliacaoAproveitamento->getCodigo()}");
    if ($oAvaliacaoAproveitamento->getParecerPadronizado() != "") {

      $oDaoParecerPadronizado->ed93_i_diarioavaliacao = $oAvaliacaoAproveitamento->getCodigo();
      $oDaoParecerPadronizado->ed93_t_parecer         = trim($oAvaliacaoAproveitamento->getParecerPadronizado());
      $oDaoParecerPadronizado->incluir(null);
      if ($oDaoParecerPadronizado->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados do parecer padronizado da avaliacao\n{$oDaoParecerPadronizado->erro_msg}");
      }
    }
  }

  /**
   * Persiste as informações das avaliações
   * @param AvaliacaoAproveitamento $oAvaliacaoAproveitamento
   * @return bool
   * @throws BusinessException
   * @throws DBException
   * @throws Exception
   */
  protected function salvarDadosResultado(AvaliacaoAproveitamento $oAvaliacaoAproveitamento) {

    $lCaracterReprobatorio = $this->getRegencia()->possuiCaracterReprobatorio();
    $iAnoCalendario        = $this->oRegencia->getTurma()->getCalendario()->getAnoExecucao();

    $GLOBALS["HTTP_POST_VARS"]["ed73_i_valornota"]     = '';
    $GLOBALS["HTTP_POST_VARS"]["ed73_c_valorconceito"] = '';
    $GLOBALS["HTTP_POST_VARS"]["ed73_t_parecer"]       = '';

    $oDaoDiarioResultado                       = new cl_diarioresultado();
    $oDaoDiarioResultado->ed73_c_amparo        = $oAvaliacaoAproveitamento->isAmparado() ? "S" : "N";
    $oDaoDiarioResultado->ed73_i_procresultado = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo();
    $oDaoDiarioResultado->ed73_i_diario        = $this->getCodigoDiario();
    $oDaoDiarioResultado->ed73_i_numfaltas     = $oAvaliacaoAproveitamento->getNumeroFaltas();
    $oDaoDiarioResultado->ed73_i_valornota     = '';
    $oDaoDiarioResultado->ed73_c_valorconceito = '';
    $oDaoDiarioResultado->ed73_t_parecer       = '';

    $oElementoAvaliacao         = $oAvaliacaoAproveitamento->getElementoAvaliacao();
    $nValorAproveitamento       = $oAvaliacaoAproveitamento->getValorAproveitamento()->getAproveitamento();
    $nAproveitamentoReal        = $oAvaliacaoAproveitamento->getValorAproveitamento()->getAproveitamentoReal();
    $iTotalReprovacoesNoPeriodo = count($this->oDiario->getDisciplinasReprovadasNoPeriodo($oElementoAvaliacao, false));

    $lTemDireitoRecuperacao = true;
    $oRecuperacao           = AvaliacaoPeriodicaRepository::getAvaliacaoDependente($oElementoAvaliacao);
    $sFormaAvaliacao        = $oElementoAvaliacao->getFormaDeAvaliacao()->getTipo();


    /**
     * Disciplinas apenas com Frequencia nao tem direito a recuperacao
     */
    if (trim($this->getRegencia()->getFrequenciaGlobal()) == 'F') {

      $lTemDireitoRecuperacao = false;
      $oAvaliacaoAproveitamento->emRecuperacao(false);
      unset($oRecuperacao);
    }

    if (!empty($oRecuperacao)) {

      $iTotalDisciplinasRecuperacao = $oRecuperacao->quantidadeMaximaDisciplinasParaRecuperacao();

      /**
       * Número máximo de Reprovações atingidas
       */
      $lTemDireitoRecuperacao = $iTotalDisciplinasRecuperacao > 0 && $iTotalReprovacoesNoPeriodo <= $iTotalDisciplinasRecuperacao;

      /**
       * Caso o aluno tenha algum aproveitamento na recuperacao, o mesmo nao deverá ficar mais em recuperação, pois
       * já concluiu o mesmo.
       */
      $oAproveitamentoNaRecuperacao = $this->oDiario->getDisciplinasPorRegenciaPeriodo($this->getRegencia(),
                                                                                       $oRecuperacao
                                                                                      );
      if (!empty($oAproveitamentoNaRecuperacao)) {

        if ($oAproveitamentoNaRecuperacao->getValorAproveitamento()->getAproveitamento() != "") {

          $oAvaliacaoAproveitamento->setEmRecuperacao(false);
          $lTemDireitoRecuperacao = false;
        }
      }

      if( $oAvaliacaoAproveitamento->emRecuperacao() ) {

        foreach( $this->getAvaliacoes() as $oAvaliacaoAproveitamentoRecuperacao ) {

          if(    $oAvaliacaoAproveitamentoRecuperacao->getElementoAvaliacao()->getCodigo() == $oRecuperacao->getCodigo()
              && $oAvaliacaoAproveitamentoRecuperacao->isAmparado()
            ) {

            $oAvaliacaoAproveitamento->setEmRecuperacao( false );
            $lTemDireitoRecuperacao = false;
          }
        }
      }
    }

    if ( !$lCaracterReprobatorio ) {
      $lTemDireitoRecuperacao = false;
    }

    if ($this->oDiario->getMatricula()->isAvaliadoPorParecer()) {
      $sFormaAvaliacao = 'PARECER';
    }

    if (!$this->isEncerrado()) {

      $oResultadoAvaliacao = $oAvaliacaoAproveitamento->getElementoAvaliacao();
      $nAproveitamento     = '';
      $oAproveitamento     = $oResultadoAvaliacao->getResultado( $this->getAvaliacoes(), false, $iAnoCalendario );

      if (!empty($oAproveitamento) && is_null($oAproveitamento->getAproveitamentoReal()) ) {
        $oAproveitamento->setAproveitamentoReal( $oAproveitamento->getAproveitamento() );
      }

      $mNotaReal = DiarioAvaliacaoDisciplina::calcularResultadoReal( $oResultadoAvaliacao, $this->oDiario, $this->getAvaliacoes(), $iAnoCalendario);

      if (!empty($oAproveitamento) && !is_null( $mNotaReal ) ) {
        $oAproveitamento->setAproveitamentoReal( $mNotaReal );
      }

      if (!empty($oAproveitamento)) {

        $nAproveitamento     = $oAproveitamento->getAproveitamento();
        $nAproveitamentoReal = $oAproveitamento->getAproveitamentoReal();
      }

      /**
       * Atualiza o valor calculado do resultado na classe
       */
      $oAvaliacaoAproveitamento->setValorAproveitamento($oAproveitamento);

      $nValorAproveitamento = ArredondamentoNota::arredondar($nAproveitamento, $iAnoCalendario);
      if(    $this->calcularPercentualFrequencia() < $this->oDiario->getProcedimentoDeAvaliacao()->getPercentualFrequencia()
          && !$this->reclassificadoPorBaixaFrequencia()
        ) {

        $oAvaliacaoAproveitamento->setEmRecuperacao( false );
        $lTemDireitoRecuperacao = false;
      }

      /**
       * Validação necessária para tratamento do Resultado Final tratando o tipo de avaliação
       */
      switch ($sFormaAvaliacao) {

        /**
         * NOTA:> Temos que avaliar o valor definido para aproveitamento mínimo
         */
        case 'NOTA':

          $oAvaliacaoAproveitamento->setAproveitamentoMinimo(true);
          $oAvaliacaoAproveitamento->setEmRecuperacao(false);

          /**
           * Alterado validação para testar com o tipo mais condição
           * Da forma como estava  sempre estava entrando e alterando o aproveitamento minimo para false, mesmo quando
           * resultado informado vinha vazio
           */
          if ( !($nValorAproveitamento === '')
               && ( ((int) $nValorAproveitamento === 0)
                    || $nValorAproveitamento < $oResultadoAvaliacao->getAproveitamentoMinimo())
             ) {

            if ( $lTemDireitoRecuperacao && !empty($oRecuperacao)) {
              $oAvaliacaoAproveitamento->setEmRecuperacao(true);
            }

            $oAvaliacaoAproveitamento->setAproveitamentoMinimo(false);
          }

          break;

        /**
         * NIVEL:> Temos que avaliar a ordem das avaliações
         */
        case 'NIVEL':

          $oAvaliacaoAproveitamento->setAproveitamentoMinimo(true);
          $oAvaliacaoAproveitamento->setEmRecuperacao(false);

          if (    $oAvaliacaoAproveitamento->getValorAproveitamento()->hasOrdem()
               && $oAvaliacaoAproveitamento->getValorAproveitamento()->getOrdem() < $oResultadoAvaliacao->getFormaDeAvaliacao()->getConceitoMinimo()->iOrdem
             ) {

            if (!empty($oRecuperacao) && $lTemDireitoRecuperacao) {
              $oAvaliacaoAproveitamento->setEmRecuperacao(true);
            }
            $oAvaliacaoAproveitamento->setAproveitamentoMinimo(false);
          }

          break;

        /**
         * PARECER:> Sempre de acordo com informado
         */
        case 'PARECER':
          break;
      }
    }

    if (    !empty($oAproveitamentoNaRecuperacao)
         && $oAproveitamentoNaRecuperacao->isAmparado()
         && !$lCaracterReprobatorio
       ) {
      $oAvaliacaoAproveitamento->setEmRecuperacao(false);
    }

    switch ($sFormaAvaliacao) {

      case 'NOTA':

        $oDaoDiarioResultado->ed73_i_valornota = "{$nValorAproveitamento}";
        $nValorAproveitamento                  = ArredondamentoNota::arredondar($nAproveitamentoReal, $iAnoCalendario);
        $oDaoDiarioResultado->ed73_valorreal   = "{$nValorAproveitamento}";
        break;

      case 'NIVEL':

        $oDaoDiarioResultado->ed73_c_valorconceito = $nValorAproveitamento;
        break;

     case 'PARECER':

        $oDaoDiarioResultado->ed73_t_parecer = $nValorAproveitamento;
        break;
    }

    $oDaoDiarioResultado->ed73_c_aprovmin = $oAvaliacaoAproveitamento->temAproveitamentoMinimo() ? "S" : "N";

    if ($oAvaliacaoAproveitamento->getCodigo() == '') {

      $oDaoDiarioResultado->incluir(null);
      $oAvaliacaoAproveitamento->setCodigo($oDaoDiarioResultado->ed73_i_codigo);
    } else {

      $oDaoDiarioResultado->ed73_i_codigo = $oAvaliacaoAproveitamento->getCodigo();
      $oDaoDiarioResultado->alterar($oDaoDiarioResultado->ed73_i_codigo);
    }

    if ($oDaoDiarioResultado->erro_status == 0) {
      throw new BusinessException("Erro ao salvar Resultado da avaliacao ");
    }

    /**
     * Excluimos a informacao da recuperacao
     */
    $oDaoDiarioResultadoRecuperacao = new cl_diarioresultadorecuperacao();
    $oDaoDiarioResultadoRecuperacao->excluir(null, "ed116_diarioresultado = {$oAvaliacaoAproveitamento->getCodigo()}");

    if ($oDaoDiarioResultadoRecuperacao->erro_status == 0) {
      throw new BusinessException("Erro ao salvar Resultado da avaliacao ");
    }

    if ($oAvaliacaoAproveitamento->emRecuperacao()) {

      $oDaoDiarioResultadoRecuperacao                        = new cl_diarioresultadorecuperacao();
      $oDaoDiarioResultadoRecuperacao->ed116_diarioresultado = $oAvaliacaoAproveitamento->getCodigo();
      $oDaoDiarioResultadoRecuperacao->incluir(null);

      if ($oDaoDiarioResultadoRecuperacao->erro_status == 0) {
        throw new BusinessException("Erro ao salvar Resultado da avaliacao ");
      }
    }

    $oDaoParecerPadronizado = db_utils::getDao("parecerresult");
    $oDaoParecerPadronizado->excluir(null, " ed63_i_diarioresultado = {$oAvaliacaoAproveitamento->getCodigo()}");

    if ($oAvaliacaoAproveitamento->getParecerPadronizado() != "") {

      $oDaoParecerPadronizado->ed63_i_diarioresultado = $oAvaliacaoAproveitamento->getCodigo();
      $oDaoParecerPadronizado->ed63_t_parecer         = trim($oAvaliacaoAproveitamento->getParecerPadronizado());
      $oDaoParecerPadronizado->incluir(null);

      if ($oDaoParecerPadronizado->erro_status == 0) {
        throw new BusinessException("Erro ao salvar dados do parecer padronizado do resultado");
      }
    }

    if ($oAvaliacaoAproveitamento->getElementoAvaliacao()->geraResultadoFinal()) {

      $oAvaliacaoResultadoFinal = $this->getResultadoFinal();
      $nPercentualPresenca      = $this->calcularPercentualFrequencia();
      $sResultadoFrequencia     = 'A';

      $sFormaControleFrequenciaDisciplina = $this->oRegencia->getFrequenciaGlobal();

      if (   $oAvaliacaoAproveitamento->getElementoAvaliacao()->reprovaPorFrequencia()
          && $sFormaControleFrequenciaDisciplina  <> 'A') {

        $nPercentualMinimoFrequencia = $this->oDiario->getProcedimentoDeAvaliacao()->getPercentualFrequencia();

        if ( $nPercentualPresenca < $nPercentualMinimoFrequencia && !$this->reclassificadoPorBaixaFrequencia() ) {
          $sResultadoFrequencia = 'R';
        }
      }

      /**
       * Se o tipo da Avaliacao for PARECER não salvamos o aproveitamento e sim a palavra 'Parecer'
       */
      $sTipoAvaliacao = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo();

      if ($this->oDiario->getMatricula()->isAvaliadoPorParecer()) {
        $sTipoAvaliacao = 'PARECER';
      }

      $sResultadoFinal     = '';
      $sResultadoAprovacao = '';

      if ($sTipoAvaliacao == 'PARECER') {

        $nValorAproveitamento = 'Parecer';
        $sResultadoFinal      = 'A';
        $sResultadoAprovacao  = 'A';

        if (!$oAvaliacaoAproveitamento->temAproveitamentoMinimo() ) {

          $sResultadoFinal      = 'R';
          $sResultadoAprovacao  = 'R';
        }
      }

      if ($nValorAproveitamento !== '' && $sTipoAvaliacao != 'PARECER' ) {

        $sResultadoAprovacao = "A";

        if( $lCaracterReprobatorio && !$oAvaliacaoAproveitamento->temAproveitamentoMinimo() ) {
          $sResultadoAprovacao = "R";
        }

        if ($sFormaControleFrequenciaDisciplina == 'F') {
          $sResultadoAprovacao = '';
        }

        $sResultadoFinal = 'R';
        if ($sResultadoAprovacao <> 'R' && $sResultadoFrequencia <> 'R') {
          $sResultadoFinal = 'A';
        }
      }

      if ( $this->getTotalFaltas() != 0  && $sResultadoFrequencia == 'R' && !$this->reclassificadoPorBaixaFrequencia() ) {
        $sResultadoFinal = 'R';
      }

      /**
       * Aluno não pode reprovar por frequencia, se disciplina não possue Caracter Reprobatorio
       */
      if ( !$lCaracterReprobatorio ) {

        $sResultadoFrequencia = "A";
        if (!$this->temAproveitamentoLancado() ) {
          $sResultadoAprovacao  = "A";
        }
      }

      if ($sFormaControleFrequenciaDisciplina == 'F') {
        $sResultadoAprovacao = "A";
      }

      if ( $sResultadoAprovacao == 'A' && $sResultadoFrequencia == 'A') {
        $sResultadoFinal = 'A';
      }

      $oAmparo = $this->getAmparo();
      if(    ( $oAmparo != null && $oAmparo->isTotal() )
          || ( !empty( $this->aPeriodosCalcularProporcionalidade ) && $this->proporcionalidadeComAmparoTotal() ) ) {
        $sResultadoFinal = 'A';
      }

      foreach ( $this->getDiario()->getTurma()->getEtapas() as $oEtapaTurma ) {

        if ($oEtapaTurma->getEtapa()->getCodigo() == $this->getDiario()->getMatricula()->getEtapaDeOrigem()->getCodigo()
             && $oEtapaTurma->temAprovacaoAutomatica() ) {

          $sResultadoAprovacao  = "A";
          $sResultadoFinal      = "A";
          $sResultadoFrequencia = "A";
        }
      }

      /**
       * Validado qual o último Resultado que gera Resultado Final e verifica se ele está sendo utilizado para gerar
       * o Resultado Final
       */
      $oUltimoResultado         = $this->getUltimoResultadoFinal();
      $sResultadoFinalJaLancado = '';

      if ( $oUltimoResultado->getOrdemSequencia() == $oAvaliacaoAproveitamento->getOrdemSequencia() ) {

        $sResultadoFinalJaLancado = $oAvaliacaoResultadoFinal->getResultadoFinal();

        if ( count( $this->getElementosGeramResultadoFinal() ) > 1 && $sResultadoFinalJaLancado == "" ) {
          return true;
        }


        if (   ( ($sResultadoFinalJaLancado == 'A' || ($oAvaliacaoResultadoFinal->getResultadoFrequencia() == "R"
            && $oAvaliacaoResultadoFinal->getResultadoAprovacao() == "A") ) )
            && $oAvaliacaoResultadoFinal->getResultadoAvaliacao()->getCodigo() != $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo()) {
          return true;
        }

        /**
         * Se procedimento, tem recuperação, mas o aluno excedeu o limite de disciplinas, o aluno deve ser reprovado.
         */
        $oResultadoTemRecuperacao = '';

        if(    $oAvaliacaoResultadoFinal->getProcResultado()      != null
            && $oAvaliacaoResultadoFinal->getResultadoAvaliacao() != null
          ) {
          $oResultadoTemRecuperacao = AvaliacaoPeriodicaRepository::getAvaliacaoDependente($oAvaliacaoResultadoFinal->getResultadoAvaliacao());
        }

        if ( !empty($oResultadoTemRecuperacao) ) {

          $iTotalDisciplinasRecuperacao = $oResultadoTemRecuperacao->quantidadeMaximaDisciplinasParaRecuperacao();
          $iTotalReprovacoesNoPeriodo   = count($this->oDiario->getDisciplinasReprovadasNoPeriodo($oAvaliacaoResultadoFinal->getResultadoAvaliacao(), false));
          $lTemDireitoRecuperacao       = $iTotalDisciplinasRecuperacao > 0 && $iTotalReprovacoesNoPeriodo <= $iTotalDisciplinasRecuperacao;

          if ( ($oAvaliacaoResultadoFinal->getResultadoAprovacao() == "R") && !$lTemDireitoRecuperacao) {
            return true;
          }
        }
      }

      /**
       * Caso haja 2 resultados que geram resultados finais, verifica se o Resultado Final já foi lançado e se o Valor
       * Aproveitamento do segundo resultado está em branco e retorna, fazendo com que não seja setado os valores vazios
       * para o Diario Final.
       */
      if ( $nValorAproveitamento === '' && !empty($sResultadoFinalJaLancado) && count( $this->getElementosGeramResultadoFinal() ) > 1 ) {
        return true;
      }

      $oAvaliacaoResultadoFinal->setResultadoAvaliacao($oAvaliacaoAproveitamento->getElementoAvaliacao());
      $oAvaliacaoResultadoFinal->setValorAprovacao($nValorAproveitamento);
      $oAvaliacaoResultadoFinal->setResultadoAprovacao($sResultadoAprovacao);
      $oAvaliacaoResultadoFinal->setResultadoFinal($sResultadoFinal);
      $oAvaliacaoResultadoFinal->setPercentualFrequencia($nPercentualPresenca);
      $oAvaliacaoResultadoFinal->setResultadoFrequencia($sResultadoFrequencia);
      $oAvaliacaoResultadoFinal->salvar();
    }
  }

  /**
   * Verifica se a lançamento de faltas lançadas por periodo de aula
   * @param PeriodoAvaliacao $oPeriodoAvaliacao periodo de avaliacao que está sendo verificado
   * @return boolean
   */
  public function  hasFaltasPorPeriodoDeAula(PeriodoAvaliacao $oPeriodoAvaliacao) {
    return $this->getTotalDeFaltasPorPeriodoDeAula($oPeriodoAvaliacao) > 0;
  }

  /**
   * Retorna todas as faltas do aluno no periodo de avaliacao $oPeriodoAvaliacao
   *
   * @param PeriodoAvaliacao $oPeriodoAvaliacao erro na consulta das faltas do aluno no periodo
   * @throws BusinessException
   * @return Falta[]
   */
  public function getFaltasPorPeriodoDeAvaliacao(PeriodoAvaliacao $oPeriodoAvaliacao) {

    $aFaltasNoPeriodo = array();
    $oCalendario      = $this->oDiario->getTurma()->getCalendario();

    /**
     * Verificamos qual as datas de vigencia do periodo dentro do calendario da turma
     */
    $oPeriodoCalendario = null;
    foreach ($oCalendario->getPeriodos() as $oPeriodo) {

      if ($oPeriodo->getPeriodoAvaliacao()->getCodigo() == $oPeriodoAvaliacao->getCodigo()) {

        $oPeriodoCalendario = $oPeriodo;
        break;
      }
    }
    if ($oPeriodoCalendario != null) {

      $sDtInicial        = $oPeriodoCalendario->getDataInicio()->convertTo(DBDate::DATA_EN);
      $sDtFinal          = $oPeriodoCalendario->getDataTermino()->convertTo(DBDate::DATA_EN);
      $oDaoDiarioClasse  = new cl_diarioclassealunofalta;
      $sWhereFaltas      = "ed58_i_regencia  = {$this->getRegencia()->getCodigo()}";
      $sWhereFaltas     .= " and ed301_aluno = {$this->oDiario->getMatricula()->getAluno()->getCodigoAluno()}";
      $sWhereFaltas     .= " and ed300_datalancamento between '{$sDtInicial}' and '{$sDtFinal}'";
      $sSqlFaltas        = $oDaoDiarioClasse->sql_query_aluno_falta(null,
                                                                    'ed58_i_periodo, ed300_datalancamento,
                                                                    ed301_sequencial',
                                                                    "ed300_datalancamento, ed58_i_periodo",
                                                                    $sWhereFaltas);

      $rsFaltas = db_query($sSqlFaltas);
      if (!$rsFaltas) {
        throw new BusinessException('Erro ao retornar faltas do aluno');
      }
      $iNumeroFaltas = pg_num_rows($rsFaltas);
      for($iFalta = 0; $iFalta < $iNumeroFaltas; $iFalta++) {

        $oDadosFalta = db_utils::fieldsMemory($rsFaltas, $iFalta);
        $oFalta      = new Falta($oDadosFalta->ed301_sequencial);
        $oFalta->setData(new DBDate($oDadosFalta->ed300_datalancamento));
        $oFalta->setDisciplina($this->getRegencia()->getDisciplina());
        $oFalta->setPeriodo($oDadosFalta->ed58_i_periodo);
        $aFaltasNoPeriodo[] = $oFalta;
      }
    }
    return $aFaltasNoPeriodo;
  }

  /**
   * Retorna o total de Faltas do periodo quando existe lançamento de faltas por periodo de aula
   * @param PeriodoAvaliacao $oPeriodoAvaliacao periodo de avaliacao que está sendo verificado
   * @return integer;
   */
  public function getTotalDeFaltasPorPeriodoDeAula(PeriodoAvaliacao $oPeriodoAvaliacao) {

    $iTotalFaltas = count($this->getFaltasPorPeriodoDeAvaliacao($oPeriodoAvaliacao));
    return $iTotalFaltas;
  }

  /**
   * Retornamos os dados do resultado final
   * @return AvaliacaoResultadoFinal
   */
  public function getResultadoFinal() {

    if (empty($this->oResultadoFinal)) {
      $this->oResultadoFinal = new AvaliacaoResultadoFinal($this);
    }
    return $this->oResultadoFinal;
  }

  /**
   * Realiza o calculo da frequencia conforme o procedimento de avaliacao
   *
   * @throw BusinessException turma sem procedimento de Avaliacao
   * @throws BusinessException
   * @return float
   */
  public function calcularPercentualFrequencia() {

    $iAno                   = $this->oDiario->getTurma()->getCalendario()->getAnoExecucao();
    $nPercentualFrequencia  = 100;
    $oProcedimentoAvaliacao = $this->oDiario->getProcedimentoDeAvaliacao();

    if (!$oProcedimentoAvaliacao) {
      throw new BusinessException('Não existe procedimento de avaliação para a etapa de origem do aluno');
    }

    switch ($oProcedimentoAvaliacao->getFormaCalculoFrequencia()) {

      case 1:

        $nPercentualFrequencia = $this->calculoDeFrequenciaIndividual();
        break;

      case 2:

        $nPercentualFrequencia = $this->calculoDeFrequenciaGlobal();
        break;
    }

    return ArredondamentoFrequencia::arredondar($nPercentualFrequencia, $iAno);
  }

  /**
   * Verifica se a disciplina foi aprovada com progressao parcial
   * @return boolean
   */
  public function aprovadoComProgressaoParcial() {

    $lAprovadoProgressaoParcial = false;
    if ( $this->getResultadoFinal() != null && $this->getResultadoFinal()->getCodigoResultadoFinal() != null ) {

      $sWhere              = "ed107_diariofinal = {$this->getResultadoFinal()->getCodigoResultadoFinal()}";
      $oDaoProgressaoAluno = new cl_progressaoparcialalunodiariofinalorigem();
      $sSqlProgressao      = $oDaoProgressaoAluno->sql_query( null, "1", null, $sWhere );
      $rsDadosProgressao   = $oDaoProgressaoAluno->sql_record( $sSqlProgressao );
      if ($rsDadosProgressao && $oDaoProgressaoAluno->numrows > 0) {
        $lAprovadoProgressaoParcial = true;
      }
    }
    return $lAprovadoProgressaoParcial;
  }

  /**
   * Retorna o amparo da Disciplina
   * @return AmparoDisciplina Amparo da Disciplina
   */
  public function getAmparo() {

    if( $this->getCodigoDiario() != "" && $this->oAmparo == null ) {
      $this->oAmparo = AmparoDisciplinaRepository::getByDiarioAvaliacaoDisciplina( $this );
    }

    return $this->oAmparo;
  }

  /**
   * Calcula a nota parcial do aluno na disciplina
   *
   * o Cálculo é apenas realizado para turma em que o resultado final é calculado por Nota
   * @param iElementoAvaliacao $oElementoAvaliacao elemento de avaliacao em que nota deverá ser calculada
   * @return float
   */
  public function getNotaParcial(iElementoAvaliacao $oElementoAvaliacao) {

    $iAno                    = $this->oDiario->getTurma()->getCalendario()->getAnoExecucao();
    $oElementoResultadoFinal = $this->getElementoResultadoFinal();

    if (empty($oElementoResultadoFinal)) {
      return '';
    }

    $oElementoResultadoFinal = $oElementoResultadoFinal->getElementoAvaliacao();
    if ($oElementoResultadoFinal->getFormaDeAvaliacao()->getTipo() != "NOTA") {
      return '';
    }

    $nNotaParcial       = '';
    $aElementosCalcular = array();
    foreach ($this->getAvaliacoes() as $oAvaliacaoAproveitamento) {

      if ($oAvaliacaoAproveitamento->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo() != "NOTA") {

        continue;
      }

      $iOrdemAvaliacao = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getOrdemSequencia();
      if ($iOrdemAvaliacao < $oElementoAvaliacao->getOrdemSequencia()) {

        /**
         * Quando o período é uma recuperação, (tem $oElementoVinculado) é que permitimos que ele
         * tenha aproveitamento em branco.
         * Nos outros casos, nunca podemos calcular nota parcial com uma nota só.
         */
        $oElementoVinculado = null;
        if (!$oAvaliacaoAproveitamento->getElementoAvaliacao()->isResultado()) {
          $oElementoVinculado = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getElementoAvaliacaoVinculado();
        }

        if (empty($oElementoVinculado) && $oAvaliacaoAproveitamento->getValorAproveitamento()->getAproveitamento() == '') {
          continue;
        }
        $aElementosCalcular[] = $oAvaliacaoAproveitamento;
      }
    }

    if (count($aElementosCalcular) > 0) {

      $oAvaliacaoAproveitamentoAtual = $this->getAvaliacoesPorOrdem($oElementoAvaliacao->getOrdemSequencia());

      if (!$oAvaliacaoAproveitamentoAtual->getValorAproveitamento()->getAproveitamento() == '') {
         $aElementosCalcular[] = $oAvaliacaoAproveitamentoAtual;
      }

      $oNotaParcial = $oElementoResultadoFinal->getResultado( $aElementosCalcular, true, $iAno );

      if ( is_null($oNotaParcial->getAproveitamentoReal() ) ) {
        $oNotaParcial->setAproveitamentoReal($oNotaParcial->getAproveitamento());
      }

      $mNotaReal = DiarioAvaliacaoDisciplina::calcularResultadoReal($oElementoResultadoFinal, $this->oDiario, $aElementosCalcular, $iAno);
      if ( !is_null($mNotaReal) ) {
        $oNotaParcial->setAproveitamentoReal( $mNotaReal );
      }

      if (!empty($oNotaParcial)) {
        $nNotaParcial = $oNotaParcial->getAproveitamentoReal();
      }

    }

    return $nNotaParcial;
  }

  /**
   * Retorna uma instancia de DiarioClasse
   * @return DiarioClasse
   */
  public function getDiario() {
    return $this->oDiario;
  }

  /**
   * Valida se esta disciplina esta em recuperação
   * @return boolean
   */
  public function emRecuperacao() {

    $oDaoRecuperacao = new cl_diarioresultadorecuperacao();
    $sWhere          = " ed95_i_codigo = {$this->iCodigoDiario} ";
    $sSqlRecuperacao = $oDaoRecuperacao->sql_query(null, "1", null, $sWhere);
    $rsRecuperacao   = db_query( $sSqlRecuperacao );

    if ( !$rsRecuperacao ) {
      throw new DBException('Falha ao verificar se o aluno está em recuperação na disciplina.');
    }

    if (pg_num_rows( $rsRecuperacao ) > 0) {
      return true;
    }

    return false;
  }

  /**
   * Retorna a nota projetada de um aluno
   * @param IElementoAvaliacao $oElementoAvaliacao
   * @return float|string
   */
  public function getNotaProjetada ( IElementoAvaliacao $oElementoAvaliacao ) {

    $aObtencoesCalculaveis  = array("SO", "ME", "MP");
    $oTurma                 = $this->oDiario->getTurma();
    $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $this->getRegencia()->getEtapa() );

    $aElementosAvaliacoesAnteriores = $oProcedimentoAvaliacao->getElementosAvaliacoesAnteriores( $oElementoAvaliacao );
    $aElementosCalcular             = array();


    $oElementoResultadoFinal = $this->getElementoResultadoFinal();

    if (empty($oElementoResultadoFinal)) {
      return '';
    }

    $oElementoResultadoFinal = $oElementoResultadoFinal->getElementoAvaliacao();
    if (!in_array($oElementoResultadoFinal->getFormaDeObtencao(), $aObtencoesCalculaveis)) {
      return '';
    }

    foreach ( $aElementosAvaliacoesAnteriores as $oElementoAvaliacaoAnterior ) {

      if ( $oElementoAvaliacaoAnterior->getFormaDeAvaliacao()->getTipo() != "NOTA" ) {
        continue;
      }

      $oAvaliacaoAproveitamentoAtual = $this->getAvaliacoesPorOrdem($oElementoAvaliacaoAnterior->getOrdemSequencia());
      if ( $oAvaliacaoAproveitamentoAtual->getValorAproveitamento()->getAproveitamento() == '' ||
           $oAvaliacaoAproveitamentoAtual->isAmparado()) {
        continue;
      }
      $aElementosCalcular[] = $oAvaliacaoAproveitamentoAtual;
    }

    $nNotaProjetada = $oElementoResultadoFinal->getNotaProjetada($aElementosCalcular);
    return ArredondamentoNota::arredondar($nNotaProjetada, $oTurma->getCalendario()->getAnoExecucao() );
  }

  /**
   * Verifica se o diário possui alguma falta lançada e se controla reprovação pela frequencia
   * @return boolean
   */
  public function controlaReprovacaoFrequencia() {

    $lControlaReprovacaoFrequencia = false;
    $lTemFaltaLancada              = false;
    $lReprovaFrequencia            = false;

    foreach( $this->getAvaliacoes() as $oAvaliacaoAproveitamento ) {

      if( $oAvaliacaoAproveitamento->getNumeroFaltas() != '' || $oAvaliacaoAproveitamento->getNumeroFaltas() != '' ) {
        $lTemFaltaLancada = true;
      }

      if(    $oAvaliacaoAproveitamento->getElementoAvaliacao() instanceof ResultadoAvaliacao
          && $oAvaliacaoAproveitamento->getElementoAvaliacao()->reprovaPorFrequencia()
        ) {
        $lReprovaFrequencia = true;
      }
    }

    if( $lTemFaltaLancada && $lReprovaFrequencia ) {
      $lControlaReprovacaoFrequencia = true;
    }

    return $lControlaReprovacaoFrequencia;
  }

  /**
   * Retorna os periodos de avaliação do procedimento de avaliação da Regência
   * @return AvaliacaoPeriodica[]|ResultadoAvaliacao[]
   */
  public function getPeriodosAvaliacao() {

    $aPeriodosAvaliacao = array();

    foreach ( $this->oRegencia->getProcedimentoAvaliacao()->getElementos() as $oPeriodoAvaliacao) {
      $aPeriodosAvaliacao[] = $oPeriodoAvaliacao;
    }

    return $aPeriodosAvaliacao;
  }

  /**
   * Retorna o período de avaliação de acordo com  a ordem informada
   *
   * @param  int $iOrdem   Ordem sequencial do período de avaliação
   * @return AvaliacaoPeriodica|ResultadoAvaliacao|null
   */
  public function getPeriodoAvaliacaoPorOrdemSequencial($iOrdem) {

    foreach ($this->getPeriodosAvaliacao() as $oPeriodoAvaliacao) {
      if ($oPeriodoAvaliacao->getOrdemSequencia() == $iOrdem ) {
        return $oPeriodoAvaliacao;
      }
    }
    return null;
  }

  /**
   * Retorna a ordem dos períodos que devem ser aplicado o cálculo da proporcionalidade
   * @return array
   */
  public function getOrdemPeriodosAplicaProporcionalidade() {

    if( is_array( $this->aOrdemPeriodoProporcionalidade ) ) {
      return $this->aOrdemPeriodoProporcionalidade;
    }

    $oDaoDiarioRegra    = new cl_diarioregracalculo();
    $sWhereDiarioRegra  = "ed125_diario = {$this->iCodigoDiario}";
    $sWhereDiarioRegra .= " and ed125_regracalculo = " . self::CALCULAR_PROPORCIONALIDADE;
    $sSqlDiarioRegra    = $oDaoDiarioRegra->sql_query_file( null, "ed125_ordemperiodo", null, $sWhereDiarioRegra );
    $rsDiarioRegra      = db_query($sSqlDiarioRegra);

    $this->aOrdemPeriodoProporcionalidade = array();
    if ( $rsDiarioRegra && pg_num_rows($rsDiarioRegra) > 0 ) {

      $iLinhas = pg_num_rows($rsDiarioRegra);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aOrdemPeriodoProporcionalidade[] = db_utils::fieldsMemory($rsDiarioRegra, $i)->ed125_ordemperiodo;
      }
    }

    return $this->aOrdemPeriodoProporcionalidade;
  }

  /**
   * Limpa a order dos períodos com proporcionalidade, quando necessário atualizar a ordem dos períodos
   */
  public function limpaPeriodosAplicaProporcionalidade() {
    $this->aOrdemPeriodoProporcionalidade = null;
  }

  /**
   * Retorna os periodos de avaliação que devem compor o calculo do resultado final
   * @return AvaliacaoAproveitamento[]
   */
  public function getPeriodosAvaliacaoProporcionalidade() {

    $this->aPeriodosCalcularProporcionalidade = array();

    foreach ( $this->getAvaliacoes() as $oAvaliacaoAproveitamento ) {

      if( !$oAvaliacaoAproveitamento->getElementoAvaliacao()->isResultado() ) {
        continue;
      }

      $oResultadoAvaliacao = $oAvaliacaoAproveitamento->getElementoAvaliacao();

      if (    $oResultadoAvaliacao->getFormaDeObtencao() == 'SO'
           && $oResultadoAvaliacao->utilizaProporcionalidade()
         ) {

        if( count( $this->getOrdemPeriodosAplicaProporcionalidade() ) > 0 ) {

          foreach ( $this->getAvaliacoes() as $oAvaliacao ) {

            if( in_array($oAvaliacao->getElementoAvaliacao()->getOrdemSequencia(), $this->getOrdemPeriodosAplicaProporcionalidade()) ) {
              $this->aPeriodosCalcularProporcionalidade[] = $oAvaliacao;
            }
          }
        }
      }
    }

    return $this->aPeriodosCalcularProporcionalidade;
  }

  /**
   * Verifica se os periodos considerados para proporcionalidade, estao todos amparados
   * @return bool
   */
  public function proporcionalidadeComAmparoTotal() {

    $lAmparoTotal = true;
    $this->getPeriodosAvaliacaoProporcionalidade();
    if( !empty($this->aPeriodosCalcularProporcionalidade) ) {

      foreach( $this->aPeriodosCalcularProporcionalidade as $oAvaliacaoAproveitamento ) {

        if( $oAvaliacaoAproveitamento->isAmparado() ) {
          continue;
        }

        $lAmparoTotal = false;
        break;
      }
    } else {
      $lAmparoTotal = false;
    }

    return $lAmparoTotal;
  }

  /**
   * Retorna a regra da avaliacao alternativa, se houver uma avaliação alternativa configurada para o diário
   * @return AvaliacaoAlternativa|null
   */
  public function getAvaliacaoAlternativa() {

    if ( is_null($this->oAvaliacaoAlternativa) ) {

      $oAvaliacaoAlternativa = AvaliacaoAlternativaRepository::getByDiario($this->iCodigoDiario);

      if ( !empty($oAvaliacaoAlternativa) ) {
        $this->oAvaliacaoAlternativa = $oAvaliacaoAlternativa;
      }
    }

    return $this->oAvaliacaoAlternativa;
  }

  /**
   * Salva o vínculo de um diario( DiarioAvaliacaoDisciplina ) com uma avaliação alternativa
   * @param  AvaliacaoAlternativa
   * @throws DBException
   */
  public function salvarAvaliacaoAlternativa( AvaliacaoAlternativa $oAvaliacaoAlternativa ) {

    if( !db_utils::inTransaction() ) {
      throw new DBException( _M( MENSAGENS_DIARIOAVALIACAOALTERNATIVA . 'sem_transacao' ) );
    }

    $oDaoDiarioAvaliacaoAlternativa                            = new cl_diarioavaliacaoalternativa();
    $oDaoDiarioAvaliacaoAlternativa->ed136_diario              = $this->getCodigoDiario();
    $oDaoDiarioAvaliacaoAlternativa->ed136_procavalalternativa = $oAvaliacaoAlternativa->getCodigo();
    $oDaoDiarioAvaliacaoAlternativa->incluir( null );

    if( $oDaoDiarioAvaliacaoAlternativa->erro_status == "0" ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoDiarioAvaliacaoAlternativa->erro_msg;
      throw new DBException( _M( MENSAGENS_DIARIOAVALIACAOALTERNATIVA . 'erro_incluir_diario_avaliacaoalternativa', $oErro ) );
    }

    $this->oAvaliacaoAlternativa = $oAvaliacaoAlternativa;
  }

  /**
   * Exclui o vínculo de um diario com uma avaliação alternativa
   * @throws DBException
   */
  public function excluirAvaliacaoAlternativa() {

    if( !db_utils::inTransaction() ) {
      throw new DBException( _M( MENSAGENS_DIARIOAVALIACAOALTERNATIVA . 'sem_transacao' ) );
    }

    $oDaoDiarioAvaliacaoAlternativa = new cl_diarioavaliacaoalternativa();
    $sWhereExclusao                 = " ed136_diario = {$this->getCodigoDiario()}";

    $oDaoDiarioAvaliacaoAlternativa->excluir( null, $sWhereExclusao );

    if( $oDaoDiarioAvaliacaoAlternativa->erro_status == "0" ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoDiarioAvaliacaoAlternativa->erro_msg;
      throw new DBException( _M( MENSAGENS_DIARIOAVALIACAOALTERNATIVA . 'erro_excluir_avaliacaoalternativa', $oErro ) );
    }
    $this->oAvaliacaoAlternativa = null;
  }

  /**
   * Implementado metoto que valida a existencia de avaliação alterativa para disciplina
   * @return boolean
   */
  public function hasAvaliacaoAlternativa() {

    $this->getAvaliacaoAlternativa();
    if ( is_null($this->oAvaliacaoAlternativa) ) {
      return false;
    }
    return true;
  }

  /**
   * Calcula o aproveitamento Real de um aluno somente quando o sistema foi configurado para apresentar o resultado Real
   * --> Escola > Procedimentos > Parâmetros Globais > Apresentar nota proporcional: NÃO
   *
   * Neste caso sistema recalcula a avaliação do aluno aplicando SOMENTE a soma dos períodos com avaliação
   *
   * @param  ResultadoAvaliacao        $oResultadoAvaliacao [description]
   * @param  DiarioClasse              $oDiario             [description]
   * @param  AvaliacaoAproveitamento[] $aElementosCalcular  [description]
   * @param  integer                   $iAno                [description]
   * @return ValorAproveitamentoNota|null
   */
  static function calcularResultadoReal( ResultadoAvaliacao $oResultadoAvaliacao, DiarioClasse $oDiario, $aElementosCalcular, $iAno) {

    $mNotaReal = null;
    if ( $oResultadoAvaliacao->getFormaDeObtencao() == 'SO' && !$oDiario->apresentarNotaProporcional() ) {

      $oFormaObtencaoSoma = new FormaObtencaoSoma();
      $oFormaObtencaoSoma->setResultadoAvaliacao($oResultadoAvaliacao);
      $mNotaReal = $oFormaObtencaoSoma->calcularResultado( $aElementosCalcular, $iAno );
    }
    return $mNotaReal;
  }
}
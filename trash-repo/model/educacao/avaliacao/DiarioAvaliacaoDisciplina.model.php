<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * @version    $Revision: 1.83 $
 */
class DiarioAvaliacaoDisciplina {

  /**
   * Codigo sequencial do diario
   * @var integer
   */
  private $iCodigoDiario;

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
   * @var AvaliacaoAproveitamento
   */
  private $aAvaliacaoAproveitamento = array();

  /**
   * Resultado final da disciplina
   * @var AvaliacaoResultadoFinal
   */
  private $oResultadoFinal;
  /**
   * Caso o calculo da frequencia seja global, usaremos o $nPercentualGlobal
   * @var number:null
   */
  private $nPercentualGlobal = null;

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
   * Retorna o codigo sequencial do diario
   * @return integer
   */
  public function getCodigoDiario() {
    return $this->iCodigoDiario;
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
      $rsDiarioAvaliacao   = $oDaoDiario->sql_record($sSqlDiarioAvaliacao);
      $iTotalLinhas        = $oDaoDiario->numrows;
      for ($iDiario = 0; $iDiario< $iTotalLinhas; $iDiario++) {

        $oDadosDiario             = db_utils::fieldsMemory($rsDiarioAvaliacao, $iDiario);
        $oAvaliavaoAproveitamento = new AvaliacaoAproveitamento($oDadosDiario->codigo);
        if ($oDadosDiario->tipo_elemento == "A") {

          $oElementoAvaliacao = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oDadosDiario->codigo_elemento);
        } else {
          $oElementoAvaliacao = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDadosDiario->codigo_elemento);
        }

        $oAvaliavaoAproveitamento->setElementoAvaliacao($oElementoAvaliacao);
        $oAvaliavaoAproveitamento->setNumeroFaltas($oDadosDiario->numero_faltas);
        $oAvaliavaoAproveitamento->setParecerPadronizado($oDadosDiario->parecerpadronizado);
        $oAvaliavaoAproveitamento->setAmparado(trim($oDadosDiario->amparo) == "S" ? true : false);
        $oAvaliavaoAproveitamento->setConvertido(trim($oDadosDiario->convertido) == "S" ? true : false);
        $oAvaliavaoAproveitamento->setObservacao($oDadosDiario->observacao);
        $sTipoAvaliacao = $oElementoAvaliacao->getFormaDeAvaliacao()->getTipo();
        if ($this->oDiario->getMatricula()->isAvaliadoPorParecer()) {
          $sTipoAvaliacao = 'PARECER';
        }
        switch ($sTipoAvaliacao) {

          case 'NOTA' :

            $oValorAproveitamento = new ValorAproveitamentoNota($oDadosDiario->valor_nota);
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
      foreach ($this->oDiario->getPeriodoAvaliacao() as $oPeriodo) {

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

            $oRetorno = $oPeriodo->getResultado($this->aAvaliacaoAproveitamento);
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
   * @param AvaliacaoAproveitamento $oAvaliacaoAproveitamento
   */
  protected function salvarDadosAvaliacao(AvaliacaoAproveitamento $oAvaliacaoAproveitamento) {

    $oDaoDiarioAvaliacao = db_utils::getDao("diarioavaliacao");
    $oDaoDiarioAvaliacao->ed72_c_amparo        = $oAvaliacaoAproveitamento->isAmparado() ? "S" : "N";
    $oDaoDiarioAvaliacao->ed72_c_aprovmin      = $oAvaliacaoAproveitamento->temAproveitamentoMinimo()?"S":"N";
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
    $sFormaAvaliacao      = $oElementoAvaliacao->getFormaDeAvaliacao()->getTipo();
    if ($this->oDiario->getMatricula()->isAvaliadoPorParecer()) {
      $sFormaAvaliacao = 'PARECER';
    }
    switch ($sFormaAvaliacao) {

      case 'NOTA':

        $oDaoDiarioAvaliacao->ed72_i_valornota = "{$nValorAproveitamento}";
        break;

      case 'NIVEL':

        $oDaoDiarioAvaliacao->ed72_c_valorconceito = "{$nValorAproveitamento}";
        break;

     case 'PARECER':

        $oDaoDiarioAvaliacao->ed72_t_parecer = pg_escape_string(("{$nValorAproveitamento}"));
        break;
    }

    if ($sFormaAvaliacao != 'PARECER') {
      $oDaoDiarioAvaliacao->ed72_t_parecer = pg_escape_string(("{$oAvaliacaoAproveitamento->getParecer()}"));
    }

    if ($nValorAproveitamento < $oElementoAvaliacao->getAproveitamentoMinimo()) {
      $oDaoDiarioAvaliacao->ed72_c_aprovmin = 'N';
    }
    /**
     * Quando aluno amparado, sempre tem aproveitamento minimo;
     */
    if ($oAvaliacaoAproveitamento->isAmparado()) {
      $oDaoDiarioAvaliacao->ed72_c_aprovmin = 'S';
    }

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
   * Persiste as Informacoes das avaliacoes
   * @param AvaliacaoAproveitamento $oAvaliacaoAproveitamento
   */
  protected function salvarDadosResultado(AvaliacaoAproveitamento $oAvaliacaoAproveitamento) {

    $iAnoCalendario = $this->oRegencia->getTurma()->getCalendario()->getAnoExecucao();

    $GLOBALS["HTTP_POST_VARS"]["ed73_i_valornota"]     = '';
    $GLOBALS["HTTP_POST_VARS"]["ed73_c_valorconceito"] = '';
    $GLOBALS["HTTP_POST_VARS"]["ed73_t_parecer"]       = '';

    $oDaoDiarioResultado                       = db_utils::getDao("diarioresultado");
    $oDaoDiarioResultado->ed73_c_amparo        = $oAvaliacaoAproveitamento->isAmparado() ? "S" : "N";
    $oDaoDiarioResultado->ed73_c_aprovmin      = $oAvaliacaoAproveitamento->temAproveitamentoMinimo()?"S":"N";
    $oDaoDiarioResultado->ed73_i_procresultado = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo();
    $oDaoDiarioResultado->ed73_i_diario        = $this->getCodigoDiario();
    $oDaoDiarioResultado->ed73_i_numfaltas     = $oAvaliacaoAproveitamento->getNumeroFaltas();
    $oDaoDiarioResultado->ed73_i_valornota     = '';
    $oDaoDiarioResultado->ed73_c_valorconceito = '';
    $oDaoDiarioResultado->ed73_t_parecer       = '';

    $oElementoAvaliacao         = $oAvaliacaoAproveitamento->getElementoAvaliacao();
    $nValorAproveitamento       = $oAvaliacaoAproveitamento->getValorAproveitamento()->getAproveitamento();
    $iTotalReprovacoesNoPeriodo = count($this->oDiario->getDisciplinasReprovadasNoPeriodo($oElementoAvaliacao));

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
    $iTotalDisciplinasRecuperacao = 0;
    if (!empty($oRecuperacao)) {

      $iTotalDisciplinasRecuperacao = $oRecuperacao->quantidadeMaximaDisciplinasParaRecuperacao();

      /**
       * Número máximo de Reprovações atingidas
       */
      $lTemDireitoRecuperacao = $iTotalDisciplinasRecuperacao > 0 && $iTotalReprovacoesNoPeriodo <= $iTotalDisciplinasRecuperacao;

      /**
       * Caso o aluno tenha algum aproveitamento na recuperacao, o mesmo nao deverá ficar mais em recuperaçã, pois
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
    }
    if ($this->oDiario->getMatricula()->isAvaliadoPorParecer()) {
      $sFormaAvaliacao = 'PARECER';
    }
    if (!$this->isEncerrado()) {

      $oResultadoAvaliacao = $oAvaliacaoAproveitamento->getElementoAvaliacao();
      $nAproveitamento     = '';     
      $oAproveitamento     = $oResultadoAvaliacao->getResultado($this->getAvaliacoes());
      if (!empty($oAproveitamento)) {
        $nAproveitamento = $oAproveitamento->getAproveitamento();
      }
      $nValorAproveitamento = ArredondamentoNota::arredondar($nAproveitamento, $iAnoCalendario);

      
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
          if ( !empty( $nValorAproveitamento ) && $nValorAproveitamento < $oResultadoAvaliacao->getAproveitamentoMinimo() ) {


            if (!empty($oRecuperacao) && $lTemDireitoRecuperacao) {
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
          if ( $oAvaliacaoAproveitamento->getValorAproveitamento()->hasOrdem() && 
               $oAvaliacaoAproveitamento->getValorAproveitamento()->getOrdem() < $oResultadoAvaliacao->getFormaDeAvaliacao()->getConceitoMinimo()->iOrdem ) {

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

    switch ($sFormaAvaliacao) {

      case 'NOTA':
        
        $oDaoDiarioResultado->ed73_i_valornota = "{$nValorAproveitamento}";
        break;

      case 'NIVEL':

        $oDaoDiarioResultado->ed73_c_valorconceito = $nValorAproveitamento;
        break;

     case 'PARECER':
       
        $oDaoDiarioResultado->ed73_t_parecer = $nValorAproveitamento;
        break;
    }

    if ($oAvaliacaoAproveitamento->getCodigo() == '') {

      $oDaoDiarioResultado->incluir(null);
      $oAvaliacaoAproveitamento->setCodigo($oDaoDiarioResultado->ed73_i_codigo);
    } else {

      $oDaoDiarioResultado->ed73_i_codigo  = $oAvaliacaoAproveitamento->getCodigo();
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

      $oDaoDiarioResultadoRecuperacao = new cl_diarioresultadorecuperacao();
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
        throw new BusinessException("Erro ao salvar dados do parecer padronizado do resultado\n{$oDaoParecerPadronizado->erro_msg}");
      }
    }

    if ($oAvaliacaoAproveitamento->getElementoAvaliacao()->geraResultadoFinal()) {

      $oAvaliacaoResultadoFinal = $this->getResultadoFinal();
      $nPercentualPresenca      = $this->calcularPercentualFrequencia();
      $sResultadoFrequencia     = 'A';

      $sFormaControleFrequenciaDisciplina = $this->oRegencia->getFrequenciaGlobal();
      if ($oAvaliacaoAproveitamento->getElementoAvaliacao()->reprovaPorFrequencia()
          && $sFormaControleFrequenciaDisciplina  <> 'A') {

        $nPercentualMinimoFrequencia = $this->oDiario->getProcedimentoDeAvaliacao()->getPercentualFrequencia();
        if ($nPercentualPresenca < $nPercentualMinimoFrequencia) {
          $sResultadoFrequencia = 'R';
        }
      }
      $oAvaliacaoResultadoFinal->setResultadoAvaliacao($oAvaliacaoAproveitamento->getElementoAvaliacao());

      /**
       * Se o tipo da Avaliacao for PARECER não salvamos o aproveitamento e sim a palavre 'Parecer'
       */
      $sTipoAvaliacao = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getFormaDeAvaliacao()->getTipo();
      if ($this->oDiario->getMatricula()->isAvaliadoPorParecer()) {
        $sTipoAvaliacao = 'PARECER';
      }
      if ($sTipoAvaliacao == 'PARECER') {
        $nValorAproveitamento = 'Parecer';
      }
      $sResultadoFinal     = '';
      $sResultadoAprovacao = '';
      if ($nValorAproveitamento != '') {

        $sResultadoAprovacao = $oAvaliacaoAproveitamento->temAproveitamentoMinimo()?"A":"R";
        if ($sFormaControleFrequenciaDisciplina == 'F') {
          $sResultadoAprovacao = '';
        }

        if ($sResultadoAprovacao <> 'R' && $sResultadoFrequencia <> 'R') {
          $sResultadoFinal  = 'A';
        } else {
          $sResultadoFinal  = 'R';
        }
      }
      $oAmparo = $this->getAmparo();
      if ($oAmparo != null && $oAmparo->isTotal()) {
        $sResultadoFinal  = 'A';
      }
      
      foreach ( $this->getDiario()->getTurma()->getEtapas() as $oEtapaTurma ) {
        
        if (    $oEtapaTurma->getEtapa()->getCodigo() == $this->getDiario()->getMatricula()->getEtapaDeOrigem()->getCodigo()
             && $oEtapaTurma->temAprovacaoAutomatica() ) {
          
          $sResultadoAprovacao  = "A";
          $sResultadoFinal      = "A";
          $sResultadoFrequencia = "A";
        }
      }
      
      $oAvaliacaoResultadoFinal->setValorAprovacao($nValorAproveitamento);
      $oAvaliacaoResultadoFinal->setResultadoAprovacao($sResultadoAprovacao);
      $oAvaliacaoResultadoFinal->setResultadoFinal($sResultadoFinal);
      $oAvaliacaoResultadoFinal->setPercentualFrequencia($nPercentualPresenca);
      $oAvaliacaoResultadoFinal->setResultadoFrequencia($sResultadoFrequencia);
      $oAvaliacaoResultadoFinal->salvar();
    }
  }

  /**
   * Retorna os aproveitamentos do periodo
   * @param IElementoAvaliacao $oPeriodo
   * @return AvaliacaoAproveitamento
   */
  public function getAproveitamentosDoPeriodo($iPeriodo) {

    foreach ($this->getAvaliacoes() as $oAvaliacao) {
      if ($oAvaliacao->getElementoAvaliacao()->getCodigo() == $iPeriodo) {
        return $oAvaliacao;
      }
    }
  }

  /**
   * Retorna os aproveitamentos do periodo, pela ordem sequencial
   * @param IElementoAvaliacao $oPeriodo
   * @return AvaliacaoAproveitamento
   */
  public function getAvaliacoesPorOrdem($iOrdem) {

    foreach ($this->getAvaliacoes() as $oAvaliacao) {
      if ($oAvaliacao->getElementoAvaliacao()->getOrdemSequencia() == $iOrdem) {
        return $oAvaliacao;
      }
    }
  }

  /**
   * Retorna os resultados da disciplina
   * @return AvaliacaoAproveitamento;
   */
  public function getResultados() {

    $aResultados = array();
    foreach ($this->getAvaliacoes() as $oAvaliacao) {

      if ($oAvaliacao->getElementoAvaliacao()->isResultado()) {

        $aResultados[] = $oAvaliacao;
      }
    }
    return $aResultados;
  }

  /**
   * Retornar qual avaliacao depende do resultado do periodo $oElementoAvaliacao
   * @param IElementoAvaliacao $oElementoAvaliacao elemento de avaliacao
   * @return AvaliacaoAproveitamento
   */
  public function getAvaliacaoDependentesDoPeriodo(IElementoAvaliacao $oElementoAvaliacao) {

    foreach ($this->getAvaliacoes() as $oAvaliacao) {

      if (!$oAvaliacao->getElementoAvaliacao()->isResultado() &&
          $oAvaliacao->getElementoAvaliacao()->getElementoAvaliacaoVinculado() != "") {

        if ($oAvaliacao->getElementoAvaliacao()->getElementoAvaliacaoVinculado()->getOrdemSequencia() == $oElementoAvaliacao->getOrdemSequencia()) {

          return $oAvaliacao;
          break;
        }
      }
    }
  }

  /**
   * Retorna o elemento que gera o resultado final do procedimento
   * @return AvaliacaoAproveitamento
   */
  public function getElementoResultadoFinal() {

    $oElementoAvaliacaoFinal = null;
    foreach ($this->getResultados() as $oResultado) {

      if ($oResultado->getElementoAvaliacao()->geraResultadoFinal()) {

       $oElementoAvaliacaoFinal = $oResultado;
       break;
      }
    }
    return $oElementoAvaliacaoFinal;
  }

  /**
   * Retorna o total de faltas do diario;
   * Os periodos que  são levadas em conta são apenas os periodos somados no periodo
   * que gera a resultado final.
   */
  public function getTotalFaltas() {

    $iTotalFaltas            = 0;
    $oElementoResultadoFinal = $this->getElementoResultadoFinal();
    if ($oElementoResultadoFinal != null && $oElementoResultadoFinal->getElementoAvaliacao() != null) {

      $oResultadoFinal = $oElementoResultadoFinal->getElementoAvaliacao();
      /**
       * Percorremos todos os elementos que compoe o calculo da falta.
       */
      foreach ($oResultadoFinal->getElementosCalculoFaltas() as $oElementoFalta) {

        foreach ($this->getAvaliacoes() as $oAvaliacao) {
          if ($oAvaliacao->isAmparado()) {
            continue;
          }
          if ($oElementoFalta->getOrdemSequencia() == $oAvaliacao->getOrdemSequencia()) {
            $iTotalFaltas += $oAvaliacao->getNumeroFaltas();
          }
        }
      }
    }
    return $iTotalFaltas;
  }

  public function getTotalFaltasPorPeriodo (PeriodoAvaliacao $oPeriodoAvaliacao) {

    $iTotalFaltasPeriodo = 0;
    $aAvaliacoes         = $this->getAvaliacoes();

    if ($aAvaliacoes != null) {

      /**
       * Percorremos todos os elementos que compoe o calculo da falta.
       */
      foreach ($aAvaliacoes as $oElementoFalta) {

        if ($oElementoFalta->getElementoAvaliacao() instanceof AvaliacaoPeriodica) {

          if ($oPeriodoAvaliacao->getCodigo() == $oElementoFalta->getElementoAvaliacao()->getPeriodoAvaliacao()->getCodigo()) {

            $iTotalFaltasPeriodo += $oElementoFalta->getNumeroFaltas();
          }
        }
      }
    }
    return $iTotalFaltasPeriodo;
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
   * Retorna o total de Faltas do periodo quando existe lançamento de faltas por periodo de aula
   * @param PeriodoAvaliacao $oPeriodoAvaliacao periodo de avaliacao que está sendo verificado
   * @return integer;
   */
  public function getTotalDeFaltasPorPeriodoDeAula(PeriodoAvaliacao $oPeriodoAvaliacao) {

    $iTotalFaltas = 0;
    $oCalendario  = $this->oDiario->getTurma()->getCalendario();

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
      $oDaoDiarioClasse  = db_utils::getDao("diarioclassealunofalta");
      $sWhereFaltas      = "ed58_i_regencia  = {$this->getRegencia()->getCodigo()}";
      $sWhereFaltas     .= " and ed301_aluno = {$this->oDiario->getMatricula()->getAluno()->getCodigoAluno()}";
      $sWhereFaltas     .= " and ed300_datalancamento between '{$sDtInicial}' and '{$sDtFinal}'";
      $sSqlFaltas        = $oDaoDiarioClasse->sql_query_aluno_falta(null, 'count(*) as totalfaltas', null, $sWhereFaltas);
      $rsFaltas          = $oDaoDiarioClasse->sql_record($sSqlFaltas);
      $iTotalFaltas      = db_utils::fieldsMemory($rsFaltas, 0)->totalfaltas;
    }
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
   * Retorna o total de faltas abonadas da disciplina/regencia;
   * @return integer
   */
  public function getTotalFaltasAbonadas() {

    $iTotalFaltasAbonadas = 0;
    $aAvaliacao           = $this->getAvaliacoes();

    foreach ($aAvaliacao as $oAvaliacao) {

      if ($oAvaliacao->getElementoAvaliacao()->isResultado()) {
        continue;
      }

      $iTotalFaltasAbonadas += $oAvaliacao->getFaltasAbonadas();
    }

    return $iTotalFaltasAbonadas;
  }


  /**
   * Realiza o calculo da frequencia conforme o procedimento de avaliacao
   * @return float
   */
  public function calcularPercentualFrequencia() {

    $iAno = $this->oDiario->getTurma()->getCalendario()->getAnoExecucao();
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
   * Retorna o percentual de presenca do aluno quando o calculo for Global
   * @todo testar valor da variavel $iTotalAulas for zero (division by zero)
   * @return float
   */
  private function calculoDeFrequenciaGlobal() {

    if ($this->nPercentualGlobal == null) {

      $iTotalAulas     = 0;
      $iTotalFaltas    = 0;
      $iFaltasAbonadas = 0;
      foreach ($this->oDiario->getDisciplinas() as $oDisciplinaDiario) {

        $iTotalAulas     += $oDisciplinaDiario->getTotalDeAulasParaCalculo();
        $iTotalFaltas    += $oDisciplinaDiario->getTotalFaltas();
        $iFaltasAbonadas += $oDisciplinaDiario->getTotalFaltasAbonadas();
      }

      $nTotalFaltasSemAbono    = $iTotalFaltas - $iFaltasAbonadas;
      $iTotalAulasPresentes    = $iTotalAulas  - $nTotalFaltasSemAbono;
      $this->nPercentualGlobal = 0;
      if ($iTotalAulas > 0) {
        $this->nPercentualGlobal = ($iTotalAulasPresentes * 100) / $iTotalAulas;
      }
    }

    return $this->nPercentualGlobal;
  }
  
  /**
   * Retorna o total de aulas Para realizar calculos de frequencia
   * Neste metodo,nao sao contabilizas aulas nos periodos em que o aluno está amparado.
   * @return integer
   */
  public function getTotalDeAulasParaCalculo() {
    
    $iTotalDeAulasDadas = $this->getRegencia()->getTotalDeAulas();
    $aPeriodosComAmparo = array();
    foreach ($this->getAvaliacoes() as $oAvaliacao) {

      if ($oAvaliacao->isAmparado() && !$oAvaliacao->getElementoAvaliacao()->isResultado()) {
        $aPeriodosComAmparo[] = $oAvaliacao->getElementoAvaliacao()->getPeriodoAvaliacao();
      }
    }
    
    foreach($aPeriodosComAmparo as $oPeriodoAvaliacao) {
      $iTotalDeAulasDadas -= $this->getRegencia()->getTotalDeAulasNoPeriodo($oPeriodoAvaliacao);
    }
    return $iTotalDeAulasDadas;
  }

  /**
   * Retorna o percentual de presenca do aluno quando o calculo for individual
   * @param stdClass $oDadosFrequencia Dados do calculo individual
   * @return float
   */
  private function calculoDeFrequenciaIndividual() {

    $iTotalDeAulas         = $this->getTotalDeAulasParaCalculo();
    $nTotalFaltasSemAbono  = $this->getTotalFaltas() - $this->getTotalFaltasAbonadas();
    $iTotalAulasPresentes  = $iTotalDeAulas - $nTotalFaltasSemAbono;
    $nPercentualIndividual = "";
    if ($iTotalDeAulas > 0) {
      $nPercentualIndividual = ($iTotalAulasPresentes * 100) / $iTotalDeAulas;
    }
    return $nPercentualIndividual;
  }


  /**
   * Verifica se o aluno foi reclassificado por baixa frequencia na disciplina .
   * @return boolean true para reclassificado , false para não -reclassificado
   */
  public function reclassificadoPorBaixaFrequencia() {

    $lAprovadoBaixaFrequencia     = false;
    $oDaoAprovConselho            = db_utils::getDao("aprovconselho");
    $sWhere                       = "ed253_i_diario = {$this->getCodigoDiario()}";
    $sWhere                      .= " and ed253_aprovconselhotipo = 2";
    $sSqlAprovadoBaixaFrequencia  = $oDaoAprovConselho->sql_query_file(null, '1', null, $sWhere);
    $rsAprovadoBaixaFrequencia    = $oDaoAprovConselho->sql_record($sSqlAprovadoBaixaFrequencia);
    if ($rsAprovadoBaixaFrequencia && $oDaoAprovConselho->numrows > 0) {
      $lAprovadoBaixaFrequencia = true;
    }

    return $lAprovadoBaixaFrequencia;
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
   * Retorna o ampardo da Disciplina
   * @return AmparoDisciplina Amparo da Disciplina
   */
  public function getAmparo() {

    $oAmparo = null;
    if ($this->getCodigoDiario() != "") {

      $oAmparo = new AmparoDisciplina($this);
      if ($oAmparo->getCodigo() == "") {
        $oAmparo = null;
      }
    }
    return $oAmparo;
  }


  /**
   * Adiciona amparo para os períodos informados
   * @param array $aPeriodosAvaliacao[] de AvaliacaoAproveitamento
   * @param Object $oTipoAmparo Pode ser uma instancia de Justificativa ou Convencao
   * @param boolean $lAproveitaCargaHoraria
   * @return void
   */
  public function salvarAmparo (array $aPeriodosAvaliacao, $oTipoAmparo, $lAproveitaCargaHoraria) {

    $oAmparo = new AmparoDisciplina($this);

    /**
     * Valida o tipo de amparo
     */
    if ($oTipoAmparo instanceof Justificativa) {
      $oAmparo->setJustificativa($oTipoAmparo);
    } else if ($oTipoAmparo instanceof Convencao) {
      $oAmparo->setConvencao($oTipoAmparo);
    } else {
      throw new BusinessException("Não foi informado o tipo de justificativa");
    }

    foreach ($aPeriodosAvaliacao as $oAvaliacaoAproveitamento) {

      $oAmparo->adicionarPeriodo($oAvaliacaoAproveitamento);
    }
    $oAmparo->setAproveitaCargaHoraria($lAproveitaCargaHoraria);

    $oAmparo->salvar();
  }

  /**
   * Remove o amparo da disciplina
   * @return void
   */
  public function removerAmparo () {

    $oAmparo = new AmparoDisciplina($this);
    $oAmparo->excluir();
  }

  /**
   * Calcula a nota parcial do aluno na disciplina
   * o Cálculo é apenas realizado para turma em que o resultado final é calculado por Nota
   * @param iElementoAvaliacao $oElementoAvaliacao elemento de avaliacao em que nota deverá ser calculada
   * @return float
   */
  public function getNotaParcial(iElementoAvaliacao $oElementoAvaliacao) {

    $iAno = $this->oDiario->getTurma()->getCalendario()->getAnoExecucao();
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
      $nNotaParcial         = $oElementoResultadoFinal->getResultado($aElementosCalcular, true);

      if (!empty($nNotaParcial)) {
        $nNotaParcial = $nNotaParcial->getAproveitamento();
      }
    }
    
    return ArredondamentoNota::arredondar($nNotaParcial, $iAno);
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
    $sWhere = " ed95_i_codigo = {$this->iCodigoDiario} ";
    $sSqlRecuperacao = $oDaoRecuperacao->sql_query(null, "1", null, $sWhere);
    $oDaoRecuperacao->sql_record($sSqlRecuperacao);
    
    if ($oDaoRecuperacao->numrows > 0) {
      return true;
    }
    return false;
  }
}
?>
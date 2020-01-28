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

use \ECidade\Educacao\Secretaria\EstruturalNotaValidacao;


/**
 * Procedimento de Avaliacao
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.17 $
 */
class ProcedimentoAvaliacao {

  /**
   * Codigo sequencial do Procedimento de Avaliacao
   * @var integer
   */
  private $iCodigo;

  /**
   * Descricao do Procedimento de Avaliacao
   * @var string
   */
  private $sDescricao;

  /**
   * Valor inteiro para calculo de frequencia minima do aluno
   * @var integer
   */
  private $iPercentualFrequencia;

  /**
   * Regra para calcular a frequencia
   * 1 = calculo de frequencia individual por disciplina
   * 2 = calculo de frequencia geral
   * @var integer
   */
  private $iFormaCalculoFrequencia;

  /**
   * Instancia da Forma de Avaliacao
   * @var FormaAvaliacao
   */
  private $oFormaAvaliacao;

  /**
   * Array com os elementos que compõe o Procedimento de Avaliacao
   * @var array
   */
  private $aElementos = array();

  /**
   * @param integer $iCodigoProcedimento
   */
  public function __construct($iCodigoProcedimento = null) {

    if (!empty($iCodigoProcedimento)) {

      $oDaoProcedimento = db_utils::getDao('procedimento');
      $sSqlProcedimento = $oDaoProcedimento->sql_query_file($iCodigoProcedimento);
      $rsProcedimento   = $oDaoProcedimento->sql_record($sSqlProcedimento);

      if ($oDaoProcedimento->numrows >0) {

        $oProcedimento                 = db_utils::fieldsMemory($rsProcedimento, 0);
        $this->iCodigo                 = $oProcedimento->ed40_i_codigo;
        $this->sDescricao              = $oProcedimento->ed40_c_descr;
        $this->iPercentualFrequencia   = $oProcedimento->ed40_i_percfreq;
        $this->iFormaCalculoFrequencia = $oProcedimento->ed40_i_calcfreq;
        $this->oFormaAvaliacao         = FormaAvaliacaoRepository::getByCodigo($oProcedimento->ed40_i_formaavaliacao);
      }
    }
  }

  /**
   * retorna o codigo sequencial do Procedimento de Avaliacao
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * atribui uma descricao ao Procedimento de Avaliacao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }

  /**
   * retorna uma descricao ao Procedimento de Avaliacao
   * @return string
   */
  public function getDescricao() {

    return $this->sDescricao;
  }

  /**
   * atribui um valor inteiro para calculo de frequencia minima do aluno
   * @param integer $iPercentualFrequencia
   */
  public function setPercentualFrequencia($iPercentualFrequencia) {

    $this->iPercentualFrequencia = $iPercentualFrequencia;
  }

  /**
   * retorna um valor inteiro para calculo de frequencia minima do aluno
   * @return integer
   */
  public function getPercentualFrequencia() {

    return $this->iPercentualFrequencia;
  }

  /**
   * Atribui uma regra para calcular a frequencia
   * 1 = calculo de frequencia individual por disciplina
   * 2 = calculo de frequencia geral
   * @param integer $iFormaCalculoFrequencia
   */
  public function setFormaCalculoFrequencia($iFormaCalculoFrequencia) {

    $this->iFormaCalculoFrequencia = $iFormaCalculoFrequencia;
  }

  /**
   * Retorna uma regra para calcular a frequencia
   * 1 = calculo de frequencia individual por disciplina
   * 2 = calculo de frequencia geral
   * @return integer
   */
  public function getFormaCalculoFrequencia() {

    return $this->iFormaCalculoFrequencia;
  }

  /**
   * Atribui uma forma de Avaliacao para o Procedimento de Avaliacao
   * @param FormaAvaliacao $oFormaAvaliacao
   */
  public function setFormaAvaliacao(FormaAvaliacao $oFormaAvaliacao) {

    $this->oFormaAvaliacao = $oFormaAvaliacao;
  }

  /**
   * Retorna uma forma de Avaliacao para o Procedimento de Avaliacao
   * @return FormaAvaliacao
   */
  public function getFormaAvaliacao() {

    return $this->oFormaAvaliacao;
  }

  /**
   * Busca os elementos de avaliacao do procedimento
   * @throws BusinessException
   * @return AvaliacaoPeriodica|ResultadoAvaliacao
   */
  public function getElementos() {

    if (count($this->aElementos) == 0 && !empty($this->iCodigo)) {

      $oDaoElementos  = db_utils::getDao('procedimento');
      $sWhereProcAval = " {$this->getCodigo()} ";
      $sWhereProcRes  = " {$this->getCodigo()} ";
      $sSqlElementos  = $oDaoElementos->sql_query_procedimentoavaliacao($sWhereProcAval, $sWhereProcRes);
      $rsElementos    = $oDaoElementos->sql_record($sSqlElementos);
      $iTotalLinhas   = $oDaoElementos->numrows;

      if ($iTotalLinhas > 0) {

        for($iContador = 0; $iContador < $iTotalLinhas; $iContador++) {

          $oElementos = db_utils::fieldsMemory($rsElementos, $iContador);

          switch ($oElementos->tipo) {

            case 'A':

              $this->aElementos[$oElementos->sequencia] = new AvaliacaoPeriodica($oElementos->codigo_elemento);
              break;

            case 'R':

              $this->aElementos[$oElementos->sequencia] = new ResultadoAvaliacao($oElementos->codigo_elemento);
              break;

            default:
              throw new BusinessException("Nenhum elemento encontrado para o procedimento.");
          }
        }
      }
    }
    return $this->aElementos;
  }

  /**
   * Verifica se os procedimentos de avaliacao sao equivalentes
   * @param ProcedimentoAvaliacao $oProcedimentoAvaliacaoEquivalente
   * @return boolean
   */
  public function temEquivalencia(ProcedimentoAvaliacao $oProcedimentoAvaliacaoEquivalente) {

    if ($this->getCodigo() == $oProcedimentoAvaliacaoEquivalente->getCodigo()) {
      return true;
    }

    /**
     * verifcamos se todos os resultados sao compativeis, isto é, possuem o mesmo resultado e forma de avaliacao para
     * resultados
     */
    $aResultados                        = $this->getResultados();
    $aResultadosProcedimentoEquivalente = $oProcedimentoAvaliacaoEquivalente->getResultados();
    $iResultadoEquivalentes             = 0;

    foreach ($aResultados as $oResultadoAvaliacao) {

      $oFormaDeAvaliacao = $oResultadoAvaliacao->getFormaDeAvaliacao();
      $oTipoDeResultado  = $oResultadoAvaliacao->getTipoResultado();
      foreach ($aResultadosProcedimentoEquivalente as $oResultadoEquivalente) {

        if ($oTipoDeResultado->getCodigo() == $oResultadoEquivalente->getTipoResultado()->getCodigo() &&
            $oFormaDeAvaliacao->getCodigo() == $oResultadoEquivalente->getFormaDeAvaliacao()->getCodigo()) {
          $iResultadoEquivalentes++;
        }
      }
    }

    /**
     * verifcamos se todos as avaliacoes periodicas sao compativeis, isto é, possuem o mesmo periodo de avaliacao
     * e forma de avaliacao
     */
    $aAvaliacoes                        = $this->getAvaliacoes();
    $aAvaliacoesProcedimentoEquivalente = $oProcedimentoAvaliacaoEquivalente->getAvaliacoes();
    $iTotalAvaliaçõesEquivalentes       = 0;
    foreach ($aAvaliacoes as $oAvaliacao) {

      $oFormaDeAvaliacao = $oAvaliacao->getFormaDeAvaliacao();
      $oPeriodo          = $oAvaliacao->getPeriodoAvaliacao();
      foreach ($aAvaliacoesProcedimentoEquivalente as $oAvaliacaoEquivalente) {

        if ($oFormaDeAvaliacao->getCodigo() == $oAvaliacaoEquivalente->getCodigo() &&
            $oPeriodo->getCodigo() == $oAvaliacao->getCodigo()) {
         $iTotalAvaliaçõesEquivalentes++;
        }
      }
    }

    if (count($aResultados) != $iResultadoEquivalentes && count($aAvaliacoes) != $iTotalAvaliaçõesEquivalentes) {
      return false;
    }
    return true;
  }

  /**
   * Retorna um array com os elementos que são Resultados
   * @return ResultadoAvaliacao[] - coleção de ResultadoAvaliacao
   */
  public function getResultados() {

    $aElementos = array();

    foreach ($this->getElementos() as $oElemento) {

      if ($oElemento->isResultado()) {
        $aElementos[] = $oElemento;
      }
    }

    return $aElementos;
  }

  /**
   * Retorna um array com os elementos que são AvaliacaoPeriodica
   * @return AvaliacaoPeriodica - coleção de AvaliacaoPeriodica
   */
  public function getAvaliacoes() {

    $aElementos = array();

    foreach ($this->getElementos() as $oElemento) {

      if (!$oElemento->isResultado()) {
        $aElementos[] = $oElemento;
      }
    }

    return $aElementos;
  }

  /**
   * Retorna o elemento de avaliação anterior ao elemento passado por parâmetro
   * @param IElementoAvaliacao $oElementoAvaliacao
   * @return AvaliacaoPeriodica|null,
   */
  public function getElementoAvaliacaoAnterior(IElementoAvaliacao $oElementoAvaliacao) {

    $iOrdemElementoAnterior = $oElementoAvaliacao->getOrdemSequencia() - 1;

    foreach ( $this->getAvaliacoes() as $oElemento ) {

      if ( $oElemento->getOrdemSequencia() == $iOrdemElementoAnterior ) {
        return $oElemento;
      }
    }
    return null;
  }

  /**
   * Retorna todos os elementos de avaliações anteriores ao elemento passado por parâmetro
   * @param IElementoAvaliacao $oElementoAvaliacao
   * @return AvaliacaoPeriodica[]
   */
  public function getElementosAvaliacoesAnteriores( IElementoAvaliacao $oElementoAvaliacao  ) {

    $aElementosAvaliacoesAnteriores = array();

    foreach ( $this->getAvaliacoes() as $oElemento ) {

      if ( $oElemento->getOrdemSequencia() < $oElementoAvaliacao->getOrdemSequencia() ) {
        $aElementosAvaliacoesAnteriores[] = $oElemento;
      }
    }

    return $aElementosAvaliacoesAnteriores;
  }

  /**
   * Retorna o Elemento de avaliação de acordo com a ordem do elemento
   *
   * @param  int $iOrdemSequencial
   * @return AvaliacaoPeriodica|ResultadoAvaliacao|null
   */
  public function getElementoAvaliacaoByOrdem($iOrdemSequencial = null) {

    foreach ($this->getElementos() as $oElemento) {

      if ($oElemento->getOrdemSequencia() == $iOrdemSequencial) {
        return $oElemento;
      }
    }
    return null;
  }

  /**
   * Retorna os Elementos de avaliação do procedimento em um array de stdClass
   *
   * @param IElementoAvaliacao[] $aElementos
   * @return stdClass[]
   */
  public static function getElementosToJson( $aElementos) {

    $aPeriodosAvaliacao = array();
    foreach ($aElementos as $oAvaliacao) {

      $oPeriodosAvaliacao                              = new stdClass();
      $oPeriodosAvaliacao->iTotalDisciplinasReprovadas = 0;
      $oPeriodosAvaliacao->iPeriodo                    = $oAvaliacao->getCodigo();
      $oPeriodosAvaliacao->iOrdemAvaliacao             = $oAvaliacao->getOrdemSequencia();
      $oPeriodosAvaliacao->lGeraResultadoFinal         = false;
      $oPeriodosAvaliacao->sFormaAvaliacao             = $oAvaliacao->getFormaDeAvaliacao()->getTipo();
      $oPeriodosAvaliacao->iPeriodoDependenteAprovacao = '';
      $oPeriodosAvaliacao->mMinimoAprovacao            = $oAvaliacao->getAproveitamentoMinimo();
      $oPeriodosAvaliacao->lControlaFrequencia         = false;
      $oPeriodosAvaliacao->lRecuperacao                = false;
      $oPeriodosAvaliacao->aConceitos                  = array();
      switch ($oPeriodosAvaliacao->sFormaAvaliacao) {

        case 'NOTA':

          $oPeriodosAvaliacao->iMenorValor = $oAvaliacao->getFormaDeAvaliacao()->getMenorValor();
          $oPeriodosAvaliacao->iMaiorValor = $oAvaliacao->getFormaDeAvaliacao()->getMaiorValor();
          $oPeriodosAvaliacao->nVariacao   = $oAvaliacao->getFormaDeAvaliacao()->getVariacao();
          break;
        case 'NIVEL':

          foreach ($oAvaliacao->getFormaDeAvaliacao()->getConceitos() as $oConceito) {

            $oDadoConceito                     = new stdClass();
            $oDadoConceito->iCodigoConceito    = $oConceito->iCodigo;
            $oDadoConceito->sDescricaoConceito = $oConceito->sConceito;
            $oDadoConceito->iOrdem             = $oConceito->iOrdem;
            $oPeriodosAvaliacao->aConceitos[]  = $oDadoConceito;
          }

          break;
      }

      if ($oAvaliacao instanceof ResultadoAvaliacao) {

        $oTipoResultado                                 = $oAvaliacao->getTipoResultado();
        $oPeriodosAvaliacao->sDescricaoPeriodo          = urlencode($oTipoResultado->getDescricao());
        $oPeriodosAvaliacao->sDescricaoPeriodoAbreviado = urlencode($oTipoResultado->getDescricaoAbreviada());
        $oPeriodosAvaliacao->sTipoAvaliacao             = "R";
        $oPeriodosAvaliacao->lGeraResultadoFinal        = $oAvaliacao->geraResultadoFinal();
        $oPeriodosAvaliacao->sFormaObtencao             = $oAvaliacao->getFormaDeObtencao();
      }

      if ($oAvaliacao instanceof AvaliacaoPeriodica) {

        $oPeriodo                                       = $oAvaliacao->getPeriodoAvaliacao();
        $oPeriodosAvaliacao->sDescricaoPeriodo          = urlencode($oPeriodo->getDescricao());
        $oPeriodosAvaliacao->sDescricaoPeriodoAbreviado = urlencode($oPeriodo->getDescricaoAbreviada());
        $oPeriodosAvaliacao->sTipoAvaliacao             = 'A';
        $oPeriodosAvaliacao->lControlaFrequencia        = $oAvaliacao->getPeriodoAvaliacao()->hasControlaFrequencia();
        $oPeriodosAvaliacao->iLimiteReprovacao          = $oAvaliacao->quantidadeMaximaDisciplinasParaRecuperacao();

        $oElementoDependente = $oAvaliacao->getElementoAvaliacaoVinculado();
        if ( !is_null($oElementoDependente) ) {

          $oPeriodosAvaliacao->iPeriodoDependenteAprovacao = $oElementoDependente->getOrdemSequencia();
          $oPeriodosAvaliacao->iTotalDisciplinasReprovadas = $oElementoDependente->quantidadeMaximaDisciplinasParaRecuperacao();
          $oPeriodosAvaliacao->lRecuperacao                = true;
        }
      }

      $aPeriodosAvaliacao[] = $oPeriodosAvaliacao;
    }

    return $aPeriodosAvaliacao;
  }

  /**
   * Importa um procedimento de educação da Secretaria de educação na escola
   *
   * @param  Escola                $oEscola              escola destino
   * @param  ProcedimentoAvaliacao $oProcedimentoOrigem
   * @param  integer               $iAno                 ano para o qual será importado
   * @return boolean
   */
  public static function importar( Escola $oEscola, $oProcedimentoOrigem, $iAno ) {

    /**
     * Não deve permitir importar se houver na escola de destino, uma configuração da nota ativa para o ano informado.
     * - A não ser que as configurações sejam exatamente igual.
     * - Se não haver configuração na escola, deve incluir.
     */
    if ( $oProcedimentoOrigem->getFormaAvaliacao()->getTipo() == 'NOTA') {

      $oSecretariaEstruturalNota = SecretariaEstruturalNotaRepository::getAtivoByAno($iAno);
      $oMsgErro       = new stdClass();
      $oMsgErro->iAno = $iAno;
      if ( is_null($oSecretariaEstruturalNota) ) {
        throw new Exception( _M(EstruturalNota::ESTRUTURAL_NOTA . "sem_configuracao_secretaria", $oMsgErro) );
      }

      $oEscolaEstruturalNota = EscolaEstruturalNotaRepository::getAtivoByAno($oEscola, $iAno);

      if ( !is_null($oEscolaEstruturalNota) ) {

        if ( $oEscolaEstruturalNota->isAtivo() &&
             EstruturalNotaValidacao::isDifirente($oSecretariaEstruturalNota, $oEscolaEstruturalNota) ) {
          throw new Exception(_M(EstruturalNota::ESTRUTURAL_NOTA . "ja_existe_paramentro_na_escola",  $oMsgErro));
        }
      }

      if ( is_null($oEscolaEstruturalNota) ) {
        EscolaEstruturalNotaRepository::clonaEstruturalNaEscola($oEscola, $oSecretariaEstruturalNota);
      }
    }

    /**
     * Clona as formas de avaliação
     */
    $aFormasAvaliacao       = FormaAvaliacaoRepository::getFormasDoProcedimento($oProcedimentoOrigem);
    $aDeParaFormasAvaliacao = FormaAvaliacaoRepository::clonarFormasAvaliacaoEscola($aFormasAvaliacao, $oEscola);

    $oDaoProcEscola           = new cl_procescola;
    $oDaoProcedimento         = new cl_procedimento;

    $oFormaAvaliacao       = $oProcedimentoOrigem->getFormaAvaliacao();
    $iCodigoFormaAvaliacao = $aDeParaFormasAvaliacao[$oFormaAvaliacao->getCodigo()];

    $oDaoProcedimento->ed40_i_codigo         = null;
    $oDaoProcedimento->ed40_i_formaavaliacao = $iCodigoFormaAvaliacao;
    $oDaoProcedimento->ed40_c_descr          = $oProcedimentoOrigem->getDescricao();
    $oDaoProcedimento->ed40_i_percfreq       = $oProcedimentoOrigem->getPercentualFrequencia();
    $oDaoProcedimento->ed40_c_contrfreqmpd   = 'I';
    $oDaoProcedimento->ed40_i_calcfreq       = $oProcedimentoOrigem->getFormaCalculoFrequencia();
    $oDaoProcedimento->ed40_desativado       = 'f';

    $oDaoProcedimento->incluir(null);
    if ( $oDaoProcedimento->erro_status == 0 ) {
      throw new Exception("Não foi possível copiar o Procedimento.");
    }
    $iCodigoNovoProcedimento = $oDaoProcedimento->ed40_i_codigo;

    $oDaoProcEscola->ed86_i_codigo       = null;
    $oDaoProcEscola->ed86_i_escola       = $oEscola->getCodigo();
    $oDaoProcEscola->ed86_i_procedimento = $iCodigoNovoProcedimento;
    $oDaoProcEscola->incluir(null);
    if ( $oDaoProcEscola->erro_status == 0 ) {
      throw new Exception("Não foi possível víncular o Procedimento clonado a escola.");
    }

    /**
     * O array $aDeParaAvaliacoes possui como index o código sequencial das avaliações e resultados dos
     * elementos que compõem o procedimento de avaliação.
     * O valor é o novo código gerado ao incluir (avaliação/procedimento)
     *
     * Os dados são indexados na ordem de inclusão dos elementos
     */
    $aDeParaAvaliacoes = array();
    foreach ($oProcedimentoOrigem->getElementos() as $oElemento) {

      $oFormaAvaliacao       = $oElemento->getFormaDeAvaliacao();
      $iCodigoFormaAvaliacao = $aDeParaFormasAvaliacao[$oFormaAvaliacao->getCodigo()];

      if ( $oElemento instanceof AvaliacaoPeriodica ) {

        $oElementoDependente = $oElemento->getElementoAvaliacaoVinculado();
        $oDaoProcAvaliacao   = new cl_procavaliacao;

        $oDaoProcAvaliacao->ed41_i_codigo                     = null;
        $oDaoProcAvaliacao->ed41_i_procedimento               = $iCodigoNovoProcedimento;
        $oDaoProcAvaliacao->ed41_i_periodoavaliacao           = $oElemento->getPeriodoAvaliacao()->getCodigo();
        $oDaoProcAvaliacao->ed41_i_formaavaliacao             = $iCodigoFormaAvaliacao;
        $oDaoProcAvaliacao->ed41_i_procavalvinc               = 0;
        $oDaoProcAvaliacao->ed41_i_procresultvinc             = 0;
        $oDaoProcAvaliacao->ed41_c_boletim                    = $oElemento->imprimeNoBoletim() ? 'S' : 'N';
        $oDaoProcAvaliacao->ed41_i_sequencia                  = $oElemento->getOrdemSequencia();
        $oDaoProcAvaliacao->ed41_numerodisciplinasrecuperacao = $oElemento->quantidadeMaximaDisciplinasParaRecuperacao();
        $oDaoProcAvaliacao->ed41_julgamenoravaliacao          = $oElemento->temJulgamentoMenorNota() ? 't' : 'false';

        if ( !is_null($oElementoDependente) && $oElementoDependente instanceof AvaliacaoPeriodica ) {
          $oDaoProcAvaliacao->ed41_i_procavalvinc   = $aDeParaAvaliacoes[$oElementoDependente->getCodigo()];
        } else if ( !is_null($oElementoDependente) && $oElementoDependente instanceof ResultadoAvaliacao ) {
          $oDaoProcAvaliacao->ed41_i_procresultvinc = $aDeParaAvaliacoes[$oElementoDependente->getCodigo()];
        }

        $oDaoProcAvaliacao->incluir(null);
        if ( $oDaoProcAvaliacao->erro_status == 0 ) {
          throw new Exception("Não foi possível incluir as avaliações do Procedimento." . $oDaoProcAvaliacao->erro_msg);
        }

        $aDeParaAvaliacoes[$oElemento->getCodigo()] = $oDaoProcAvaliacao->ed41_i_codigo;
      } else if ( $oElemento instanceof ResultadoAvaliacao ) {

        $oDaoProcResultado                         = new cl_procresultado;
        $oDaoProcResultado->ed43_i_codigo          = null;
        $oDaoProcResultado->ed43_i_procedimento    = $iCodigoNovoProcedimento;
        $oDaoProcResultado->ed43_i_resultado       = $oElemento->getTipoResultado()->getCodigo();
        $oDaoProcResultado->ed43_i_formaavaliacao  = $iCodigoFormaAvaliacao;
        $oDaoProcResultado->ed43_c_minimoaprov     = $oElemento->getAproveitamentoMinimo();
        $oDaoProcResultado->ed43_c_obtencao        = $oElemento->getFormaDeObtencao();
        $oDaoProcResultado->ed43_c_geraresultado   = $oElemento->geraResultadoFinal()   ? 'S' : 'N';
        $oDaoProcResultado->ed43_c_boletim         = $oElemento->imprimeNoBoletim()     ? 'S' : 'N';
        $oDaoProcResultado->ed43_c_reprovafreq     = $oElemento->reprovaPorFrequencia() ? 'S' : 'N';
        $oDaoProcResultado->ed43_i_sequencia       = $oElemento->getOrdemSequencia();
        $oDaoProcResultado->ed43_proporcionalidade = $oElemento->utilizaProporcionalidade() ? 't' : 'false';;
        $oDaoProcResultado->ed43_c_arredmedia      = 'N';
        $oDaoProcResultado->ed43_c_tipoarred       = 'C';

        $oDaoProcResultado->incluir(null);
        if ( $oDaoProcResultado->erro_status == 0 ) {
          throw new Exception("Não foi possível incluir o resultado do Procedimento.");
        }

        $aDeParaAvaliacoes[$oElemento->getCodigo()] = $oDaoProcResultado->ed43_i_codigo;

        /**
         * clona os elementos que calcula o resultado
         */
        foreach ($oElemento->getElementosComposicaoResultado() as $oElementoCalculo) {

          // avaliação ou resultado usado no calculo
          $iCodigoAvaliacao = $aDeParaAvaliacoes[$oElementoCalculo->getElementoAvaliacao()->getCodigo()];
          $iCodigoResultado = $aDeParaAvaliacoes[$oElemento->getCodigo()];

          $oDaoElementosResultado = new cl_avalcompoeres;
          if ( $oElementoCalculo->getElementoAvaliacao() instanceof AvaliacaoPeriodica ) {

            $oDaoElementosResultado->ed44_i_codigo        = null;
            $oDaoElementosResultado->ed44_i_procavaliacao = $iCodigoAvaliacao;
            $oDaoElementosResultado->ed44_i_procresultado = $iCodigoResultado;
            $oDaoElementosResultado->ed44_i_peso          = $oElementoCalculo->getPeso();
            $oDaoElementosResultado->ed44_c_obrigatorio   = $oElementoCalculo->isObrigatorio() ? 'S' : 'N';
            $oDaoElementosResultado->ed44_c_minimoaprov   = $oElementoCalculo->getMinimoAprovacao();
          } else {

            $oDaoElementosResultado                        = new cl_rescompoeres;
            $oDaoElementosResultado->ed68_i_codigo         = null;
            $oDaoElementosResultado->ed68_i_procresultado  = $iCodigoResultado;
            $oDaoElementosResultado->ed68_i_procresultcomp = $iCodigoAvaliacao;
            $oDaoElementosResultado->ed68_i_peso           = $oElementoCalculo->getPeso();
            $oDaoElementosResultado->ed68_c_minimoaprov    = $oElementoCalculo->getMinimoAprovacao();
          }

          $oDaoElementosResultado->incluir(null);
          if ( $oDaoElementosResultado->erro_status == 0 ) {
            throw new Exception("Não foi possível incluir os elementos para calculo do resultado do Procedimento.");
          }
        }


        /**
         * clona os elementos que calcula frequência
         */
        foreach ($oElemento->getElementosCalculoFaltas() as $oCalculoFrequencia) {

          $oDaoCalculoFrequencia = new cl_avalfreqres;

           // avaliação ou resultado usado no calculo
          $iCodigoAvaliacao = $aDeParaAvaliacoes[$oCalculoFrequencia->getCodigo()];
          $iCodigoResultado = $aDeParaAvaliacoes[$oElemento->getCodigo()];

          $oDaoCalculoFrequencia->ed67_i_codigo        = null;
          $oDaoCalculoFrequencia->ed67_i_procavaliacao = $iCodigoAvaliacao;
          $oDaoCalculoFrequencia->ed67_i_procresultado = $iCodigoResultado;

          $oDaoCalculoFrequencia->incluir(null);
          if ( $oDaoCalculoFrequencia->erro_status == 0 ) {
            throw new Exception("Não foi possível incluir os elementos para calculo da frequência do Procedimento.");
          }
        }

        $aDeParaAvaliacaoAlternativa = array();
        foreach ($oElemento->getAvaliacoesAlternativas() as $oAvaliacaoAlternativa) {

          $oDaoAvaliacaoAlternativa      = new cl_procavalalternativa;
          $oDaoAvaliacaoAlternativaRegra = new cl_procavalalternativaregra;

          $sWhereRegras = " ed282_i_procavalalternativa = {$oAvaliacaoAlternativa->getCodigo()} ";
          $sSqlRegras   = $oDaoAvaliacaoAlternativaRegra->sql_query_file(null, "*", 'ed282_i_codigo', $sWhereRegras);
          $rsRegras     = db_query($sSqlRegras);
          if ( !$rsRegras || pg_num_rows($rsRegras) == 0 ) {
            throw new Exception("Erro ao buscar regras.");
          }

          $aRegras = db_utils::getCollectionByRecord($rsRegras);

          $oDaoAvaliacaoAlternativa->ed281_i_codigo        = null;
          $oDaoAvaliacaoAlternativa->ed281_i_procresultado = $aDeParaAvaliacoes[$oElemento->getCodigo()];
          $oDaoAvaliacaoAlternativa->ed281_i_alternativa   = $oAvaliacaoAlternativa->getAlternativa();

          $oDaoAvaliacaoAlternativa->incluir(null);
          if ( $oDaoAvaliacaoAlternativa->erro_status == 0 ) {
            throw new Exception("Não foi possível incluir avaliacão alternativa do Procedimento.");
          }

          $aDeParaAvaliacaoAlternativa[$oAvaliacaoAlternativa->getCodigo()] = $oDaoAvaliacaoAlternativa->ed281_i_codigo;

          foreach ($aRegras as $oRegra) {

            $iAvaliacaoAlternativa = $aDeParaAvaliacaoAlternativa[$oRegra->ed282_i_procavalalternativa];

            $oDaoAvaliacaoAlternativaRegra                              = new cl_procavalalternativaregra;
            $oDaoAvaliacaoAlternativaRegra->ed282_i_codigo              = null;
            $oDaoAvaliacaoAlternativaRegra->ed282_i_procavalalternativa = $iAvaliacaoAlternativa;
            $oDaoAvaliacaoAlternativaRegra->ed282_i_codavaliacao        = $oRegra->ed282_i_codavaliacao;
            $oDaoAvaliacaoAlternativaRegra->ed282_i_tipoaval            = $oRegra->ed282_i_tipoaval;
            $oDaoAvaliacaoAlternativaRegra->ed282_i_formaavaliacao      = $oRegra->ed282_i_formaavaliacao;
            $oDaoAvaliacaoAlternativaRegra->incluir(null);

            if ( $oDaoAvaliacaoAlternativaRegra->erro_status == 0 ) {
              throw new Exception("Não foi possível incluir regra para avaliacão alternativa do Procedimento.");
            }
          }
        }
      }
    }

    return $iCodigoNovoProcedimento;
  }
}
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


require_once(modification("model/educacao/avaliacao/iElementoAvaliacao.interface.php"));
/**
 * Resultado da Avaliacao
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 *         Iuri Guntchnigg <iuri@dbseller.com.br>
 * @version $Revision: 1.27 $
 */
class ResultadoAvaliacao implements IElementoAvaliacao {

  private $iCodigo;

  /**
   * Forma de avaliacao
   * @var FormaAvaliacao
   */
  private $oFormaAvaliacao;

  /**
   * Tipo do resultado
   * @var TipoResultado
   */
  private $oTipoResultado;

  /**
   * Elementos (periodo ou resultado) que compõem o resultado
   * @var array
   */
  private $aElementosAvaliacao = array();

  /**
   * Ordem de apresentacao da Avaliacao
   * @var integer
   */
  private $iOrdemSequencia;

  /**
   * Forma de obtencao para realizar o calculo da avaliacao
   * tabela: procresultado    campo: ed43_c_obtencao
   */
  private $sFormaObtencao;

  /**
   *
   */
  private $lResultadoFinal = false;

  /**
   * Minimo para Aprovacao;
   */
  private $nMinimoParaAprovacao;


  /**
   * Elementos que são Utilizados para o calculo do % de frequencia
   */
  private $aElementosFalta = array();


  /**
   * Verifica se aparece ou nao no boletim
   * @var boolean
   */
  private $lApareceNoBoletim;

  /**
   * Existe reprovação por frequencia
   * @var boolean
   */
  private $lReprovaPorFrequencia = false;

  /**
   * Verifica se utiliza a regra de proporcionalidade para realizar o cálculo da aprovação do aluno
   * @var boolean
   */
  private $lUtilizaProporcionalidade = false;

  /**
   * Caso procedimento de avaliação seja soma, este pode ter configurado avaliações alternativas.
   * @var AvaliacaoAlternativa[]
   */
  private $aAvaliacoesAlternativas = array();

  /**
   * Método construtor
   * @param integer $iCodigo Código da avaliacao periodica
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoResultadoAvaliacao = db_utils::getDao('procresultado');
      $sSqlResultadoAvaliacao = $oDaoResultadoAvaliacao->sql_query_file($iCodigo);
      $rsResultadoAvaliacao   = $oDaoResultadoAvaliacao->sql_record($sSqlResultadoAvaliacao);

      if ($oDaoResultadoAvaliacao->numrows > 0) {

        $oResultadoAvaliacao = db_utils::fieldsMemory($rsResultadoAvaliacao, 0);
        $this->iCodigo                   = $oResultadoAvaliacao->ed43_i_codigo;
        $this->oFormaAvaliacao           = FormaAvaliacaoRepository::getByCodigo($oResultadoAvaliacao->ed43_i_formaavaliacao);
        $this->oTipoResultado            = new TipoResultado($oResultadoAvaliacao->ed43_i_resultado);
        $this->iOrdemSequencia           = $oResultadoAvaliacao->ed43_i_sequencia;
        $this->sFormaObtencao            = $oResultadoAvaliacao->ed43_c_obtencao;
        $this->lResultadoFinal           = $oResultadoAvaliacao->ed43_c_geraresultado == 'S' ? true : false;
        $this->nMinimoParaAprovacao      = $oResultadoAvaliacao->ed43_c_minimoaprov;
        $this->lReprovaPorFrequencia     = $oResultadoAvaliacao->ed43_c_reprovafreq == 'S' ? true: false;
        $this->lApareceNoBoletim         = $oResultadoAvaliacao->ed43_c_boletim == "S" ? true : false;
        $this->lUtilizaProporcionalidade = $oResultadoAvaliacao->ed43_proporcionalidade == 't';
        unset($oResultadoAvaliacao);
      }
    }
  }

  /**
   * Retorna o codigo do resultado da avaliacao
   * @see IElementoAvaliacao::getCodigo()
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retona a descricao do resultado da avaliacao
   * @see IElementoAvaliacao::getDescricao()
   * @return string
   */
  public function getDescricao() {
    return $this->getTipoResultado()->getDescricao();
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
   * Define a forma de avaliacao do resultado
   * @param FormaAvaliacao $oFormaAvaliacao instancia de forma de FormaAvaliacao
   */
  public function setFormaDeAvaliacao(FormaAvaliacao $oFormaAvaliacao) {
    $this->oFormaAvaliacao  = $oFormaAvaliacao;
  }

  /**
   * Retorna o tipo de resultado
   * @return TipoResultado
   */
  public function getTipoResultado () {

    return $this->oTipoResultado;
  }

  /**
   * define o Tipo de Resultado
   * @param TipoResultado $oTipoResultado
   */
  public function setTipoResultado (TipoResultado $oTipoResultado) {

    $this->oTipoResultado = $oTipoResultado;
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
   * Define a forma de obtencao para realizar o calculo da avaliacao
   * @param string $sFormaObtencao
   */
  public function setFormaDeObtencao($sFormaObtencao) {

    $this->sFormaObtencao = $sFormaObtencao;
  }

  /**
   * Retorna a forma de obtencao para realizar o calculo da avaliacao
   * tabela: procresultado    campo: ed43_c_obtencao
   * @return string
   */
  public function getFormaDeObtencao() {
    return $this->sFormaObtencao;
  }


  /**
   * Adiciona um Elemento de avaliacao ao Resultado da Avaliacao
   * @param iElementoAvaliacao $oElementoAvaliacao
   */
  public function adicionarElemento(iElementoAvaliacao $oElementoAvaliacao, $lObrigatorio = false,
                                    $iMinimoAprovacao = 0, $iPeso = 0 ) {

    $oElemento = new ResultadoAvaliacaoComposicao();
    $oElemento->setElementoAvaliacao($oElementoAvaliacao);
    $oElemento->setObrigatorio($lObrigatorio);
    $oElemento->setPeso($iPeso);
    $oElemento->setMinimoAprovacao($iMinimoAprovacao);
    $oElemento->setOrdem($oElementoAvaliacao->getOrdemSequencia());
    $this->aElementosAvaliacao[$oElementoAvaliacao->getOrdemSequencia()] = $oElemento;
  }

  /**
   * Busca os Elementos que compoem o resultado de uma avaliacao
   * @return ResultadoAvaliacaoComposicao[]
   */
  public function getElementosComposicaoResultado() {

    if (count($this->aElementosAvaliacao) == 0 && !empty($this->iCodigo)) {

      $oDaoProcResultado     = db_utils::getDao('procresultado');
      $sSqlProcResultado     = $oDaoProcResultado->sql_query_composicaoresultado($this->getCodigo());
      $rsProcResultado       = $oDaoProcResultado->sql_record($sSqlProcResultado);
      $iTotalLinhas          = $oDaoProcResultado->numrows;

      if ($iTotalLinhas > 0) {

        for ($i = 0; $i < $iTotalLinhas; $i++) {

          $oDadosComposicaoResultado = db_utils::fieldsMemory($rsProcResultado, $i);
          $oComposicaoResultado      = new ResultadoAvaliacaoComposicao();
          $lObrigatorio              = $oDadosComposicaoResultado->obrigatorio == 'S' ? true : false;

          $oComposicaoResultado->setObrigatorio($lObrigatorio);
          $oComposicaoResultado->setPeso($oDadosComposicaoResultado->peso);
          $oComposicaoResultado->setMinimoAprovacao($oDadosComposicaoResultado->minimo);
          $oComposicaoResultado->setOrdem($oDadosComposicaoResultado->sequencia);
          if ($oDadosComposicaoResultado->tipo_elemento == 'R') {
            $oElementoAvaliacao  = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDadosComposicaoResultado->elemento);
          } else {
            $oElementoAvaliacao  = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oDadosComposicaoResultado->elemento);
          }

          $oComposicaoResultado->setElementoAvaliacao($oElementoAvaliacao);
          $this->aElementosAvaliacao[$oDadosComposicaoResultado->sequencia] = $oComposicaoResultado;
          unset($oDadosComposicaoResultado);
        }
      }
    }
    return $this->aElementosAvaliacao;
  }

  /**
   * Retorna o valor do resultado/conceito/parecer dependendo da forma de Obtencao:
   * @param array   $aElementosAvaliacao
   * @param bool    $lCalculoParcial
   * @param integer $iAno
   * @throws Exception
   */
  public function getResultado( $aElementosAvaliacao, $lCalculoParcial = false, $iAno = null ) {

    try {

      $oForma = $this->getInstanciaFormaObtencao();
      $oForma->setResultadoAvaliacao($this);
      $oForma->setCalculoNotaParcial($lCalculoParcial);
      return $oForma->processarResultado( $aElementosAvaliacao, $iAno );
    } catch ( Exception $oErro ) {
      throw new Exception ( $oErro->getMessage() );
    }
  }

  /**
   * Verifica se o resultado gera o restuldo final das avaliacoes.
   */
  public function geraResultadoFinal() {
    return $this->lResultadoFinal;
  }

  /**
   * Verifica se o periodo é um resultado
   */
  public function isResultado() {
    return true;
  }

  /**
   * Retorna o aproveitamento minimo para a aprovacao no resultado
   * @return Mixed
   */
  public function getAproveitamentoMinimo() {
    return $this->nMinimoParaAprovacao;
  }

  /**
   * Retorna os elementos que compoe o calculo de faltas do aluno
   * @return array com os elementos que fazer parte do calculo da média Final
   */
  public function getElementosCalculoFaltas() {

    if (count($this->aElementosFalta) == 0 && $this->getCodigo()) {

      $oDaoAvaliacaoFrequencia = db_utils::getDao("avalfreqres");
      $sWhere                  = "ed67_i_procresultado = {$this->getCodigo()}";
      $sSqlAvaliacaoFrequencia = $oDaoAvaliacaoFrequencia->sql_query_file(null,
                                                                          "ed67_i_procavaliacao as avaliacao",
                                                                          null,
                                                                          $sWhere
                                                                         );
      $rsAvaliacaoFrequencia   = $oDaoAvaliacaoFrequencia->sql_record($sSqlAvaliacaoFrequencia);
      $aElementos              = db_utils::getCollectionByRecord($rsAvaliacaoFrequencia);
      foreach ($aElementos as $oElemento) {
        $this->aElementosFalta[] = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oElemento->avaliacao);
      }
      unset($aElementos);
    }
    return $this->aElementosFalta;
  }

  /**
   * Verifica se o Resultado vai ser impresso no boletim
   * @return boolean
   */
  public function imprimeNoBoletim() {
    return $this->lApareceNoBoletim;
  }

  public function getDescricaoAbreviada() {
    return $this->getTipoResultado()->getDescricaoAbreviada();
  }

  /**
   * Verifica se o periodo reprova por frequencia
   * @return boolean
   */
  public function reprovaPorFrequencia() {
    return $this->lReprovaPorFrequencia;
  }

  /**
   * criado para manter a consistencia com AvaliacaoAproveitamento
   * @return string
   */
  public function getObservacao() {

    return '';
  }

  /**
   * Retorna a quantidade maxima de disciplinas que o aluno pode reprovar para estar apto a recuperação
   * @return int
   */
  public function quantidadeMaximaDisciplinasParaRecuperacao() {
    return null;
  }

  /**
   * Retorna uma instância de Forma de Obtenção do calculo da nota
   * @return FormaObtencaoAtribuida|FormaObtencaoMaiorNota|FormaObtencaoMediaAritmetica|FormaObtencaoMediaPonderada|FormaObtencaoSoma|FormaObtencaoUltimoNivel|null
   * @throws BusinessException
   */
  protected function getInstanciaFormaObtencao() {

    $oForma = null;
    switch ($this->getFormaDeObtencao()) {

      /**
       * AT - Atribuido
       */
      case 'AT':

        $oForma = new FormaObtencaoAtribuida();
        break;
      /**
       * ME - Media
       */
      case 'ME':

        $oForma = new FormaObtencaoMediaAritmetica();
        break;

      /**
       * MN - Maior Nota
       */
      case 'MN':

        $oForma = new FormaObtencaoMaiorNota();
        break;

      /**
       * MP - Media Ponderada
       */
      case 'MP':

        $oForma = new FormaObtencaoMediaPonderada();
        break;

      /**
       * SO - Soma
       */
      case 'SO':

        $oForma = new FormaObtencaoSoma();
        break;

      /**
       * UC - Ultima Nivel
       */
      case 'UC':

        $oForma = new FormaObtencaoUltimoNivel();
        break;
      /**
       * UC - Ultima Nivel
       */
      case 'MC':

        $oForma = new FormaObtencaoMaiorNivel();
        break;
      /**
       * UN - Ultima Nota
       */
      case 'UN':

        $oForma = new FormaObtencaoUltimaNota();
        break;
      default:

        throw new BusinessException("Não foi possível identificar a forma de Obtenção para o resultado {$this->getCodigo()} - {$this->getFormaDeObtencao()}");
        break;
    }
    return $oForma;
  }

  /**
   * Retorna a nota projetada do aluno
   * @param $aElementosCalcular
   * @return string
   * @throws Exception
   */
  public function getNotaProjetada( $aElementosCalcular) {

    try {

      $oForma = $this->getInstanciaFormaObtencao();
      $oForma->setResultadoAvaliacao($this);
      return $oForma->calcularNotaProjetada($aElementosCalcular);
    } catch ( Exception $oErro ) {
      throw new Exception ( $oErro->getMessage() );
    }

  }

  /**
   * Define se utiliza ou não a Proporcionalidade para calcular a aprovação do aluno
   * @param boolean $lUtilizaProporcionalidade
   */
  public function setUtilizaProporcionalidade( $lUtilizaProporcionalidade ) {
    $this->lUtilizaProporcionalidade = $lUtilizaProporcionalidade;
  }

  /**
   * Retorna se utiliza a Proporcionalidade para calcular a aprovação do aluno
   * @return boolean
   */
  public function utilizaProporcionalidade() {
    return $this->lUtilizaProporcionalidade;
  }

  /**
   * Retorna as avaliações alternativas para o resultado.
   * Somente quando resultado for igual a SOMA
   * @return AvaliacaoAlternativa[]|array()
   */
  public function getAvaliacoesAlternativas() {

    if ($this->getFormaDeObtencao() == 'SO') {
      $this->aAvaliacoesAlternativas = AvaliacaoAlternativaRepository::getByResultado($this);
    }
    return $this->aAvaliacoesAlternativas;
  }
}
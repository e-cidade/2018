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
 * Controle dos amparos dos alunos
 *
 * Um amparo pode ser de dois tipos:
 *   Por Justificativa: onde é definido os períodos que serão amparados
 *   Por Conveção:      onde o aluno é amparado em todos os períodos <b>a partir</b> do período selecionado
 *
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package educacao
 * @subpackage avaliacao
 * @version $Revision: 1.12 $
 *
 */
class AmparoDisciplina {

  /**
   * codigo do amparo
   * @var  integer
   */
  private $iCodigo;

  /**
   * Se o amparo é para todos os períodos de avaliação do procedimento ou parcial de alguns periodos de avaliação
   * @var boolean
   */
  private $lAmparoTotal;

  /**
   * Soma na carga horaria do aluno
   * true  -> periodo de avaliação irá contar na soma da carga horária para o histórico
   * false -> periodo de avaliação não irá contar na soma da carga horária para o histórico
   * @var Boolean
   */
  private $lSomaCargaHoraria;

  /**
   * Codigo da justificativa
   * @var integer
   */
  private $iCodigoJustificativa;

  /**
   * Codigo da convencao amparo
   * @var integer
   */
  private $iCodigoConvencaoAmparo;

  /**
   * Conveção para o amparo
   * @var Convencao
   */
  private $oConvencao = null;

  /**
   * Justificativa para o amparo
   * @var Justificativa
   */
  private $oJustificativa = null;

  /**
   *
   * @var DiarioAvaliacaoDisciplina
   */
  private $oDiarioAvaliacaoDisciplina;

  /**
   * Lista de periodos amparados
   * @var array
   */
  private $aPeriodosAmparados =  array();

  /**
   * O Amparo é feito por convencao
   * @var integer
   */
  const AMPARO_CONVENCAO     = 1;

  /**
   * O Amparo é feito por uma justificativa
   * @var integer
   */
  const AMPARO_JUSTIFICATIVA = 2;

  /**
   * tipo do amparo
   * @var integer
   */
  private $iTipoAmparo;

  /**
   * Instancia um novo amparo, ou carrega os dados de um amparo existente para uma discipplina de um aluno
   * @param DiarioAvaliacaoDisciplina $oDiarioAvaliacaoDisciplina
   */
  public function __construct(DiarioAvaliacaoDisciplina $oDiarioAvaliacaoDisciplina) {

    $this->oDiarioAvaliacaoDisciplina = $oDiarioAvaliacaoDisciplina;

    $oDaoAmparo           = db_utils::getDao("amparo");
    $sWhere               = "ed81_i_diario = {$oDiarioAvaliacaoDisciplina->getCodigoDiario()}";
    $sSqlAmparoDisciplina = $oDaoAmparo->sql_query_file(null, "*", null, $sWhere);
    $rsAmparoDisciplina   = db_query($sSqlAmparoDisciplina);
    if (!$rsAmparoDisciplina) {
      throw new DBException("Erro ao buscar amparo. \n". pg_last_error());
    }

    if (pg_num_rows($rsAmparoDisciplina) > 0) {

      $oDadosAmparo                  = db_utils::fieldsMemory($rsAmparoDisciplina, 0);
      $this->iCodigo                 = $oDadosAmparo->ed81_i_codigo;
      $this->iCodigoJustificativa    = $oDadosAmparo->ed81_i_justificativa;
      $this->lAmparoTotal            = $oDadosAmparo->ed81_c_todoperiodo == "S" ? true : false;
      $this->lSomaCargaHoraria       = $oDadosAmparo->ed81_c_aprovch     == "S" ? true : false;
      $this->iCodigoConvencaoAmparo  = $oDadosAmparo->ed81_i_convencaoamp;

      if (!empty($oDadosAmparo->ed81_i_justificativa)) {
        $this->setJustificativa(new Justificativa($oDadosAmparo->ed81_i_justificativa));
      }
      if (!empty($oDadosAmparo->ed81_i_convencaoamp)) {
        $this->setConvencao(new Convencao($oDadosAmparo->ed81_i_convencaoamp));
      }
    }
  }

  /**
   * Verifica se o amparo é para todos os periodos do ano letivo
   * @return boolean
   */
  public function isTotal() {
    return $this->lAmparoTotal;
  }

  /**
   * Define se ampara todo período
   * @param $lTodoPeriodo
   */
  private function amparaTodosPeriodos($lTodoPeriodo) {
    $this->lAmparoTotal = $lTodoPeriodo;
  }

  /**
   * Verfica se os periodos amparados devem ser somados na carga horaria do aluno
   * @return boolean
   */
  public function isAdicionadoNaCargaHoraria() {
    return $this->lSomaCargaHoraria;
  }

  /**
   * Define se SomaCargaHoraria
   * @param $lSomaCargaHoraria
   */
  public function setAproveitaCargaHoraria($lSomaCargaHoraria) {
    $this->lSomaCargaHoraria = $lSomaCargaHoraria;
  }

  /**
   * Retorna o Codigo do Amparo
   * Retorna o Codigo do amparo.
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o codigo da Justificativa utilizado no amparo
   * @return number
   */
  public function getCodigoJustificativa() {
    return $this->iCodigoJustificativa;
  }

  /**
   * Retorna o codigo da convenção do amparo
   * @return number
   */
  public function getCodigoConvencaoAmparo() {
    return $this->iCodigoConvencaoAmparo;
  }

  /**
   * Retorna uma instancia de Justificativa para o amparo
   * @return Justificativa
   */
  public function getJustificativa() {
    return $this->oJustificativa;
  }

  /**
   * Seta Justificativa para o amparo
   * @param $oJustificativa
   */
  public function setJustificativa(Justificativa $oJustificativa) {

    $this->oJustificativa         = $oJustificativa;
    $this->iCodigoJustificativa   = $oJustificativa->getCodigo();
    $this->iTipoAmparo            = AmparoDisciplina::AMPARO_JUSTIFICATIVA;
    $this->oConvencao             = null;
    $this->iCodigoConvencaoAmparo = null;
  }

  /**
   * Retorna uma instancia de convenção
   * @return Convencao
   */
  public function getConvencao() {
    return $this->oConvencao;
  }

  /**
   * Define uma convenção para o Amparo
   * @param $oConvencao
   */
  public function setConvencao(Convencao $oConvencao) {

    $this->oJustificativa         = null;
    $this->iCodigoJustificativa   = null;
    $this->oConvencao             = $oConvencao;
    $this->iCodigoConvencaoAmparo = $oConvencao->getCodigo();
    $this->iTipoAmparo            = AmparoDisciplina::AMPARO_CONVENCAO;
  }

  /**
   * Retorna todos os períodos amparados
   * @param bool $lRetornarResultados Se deve retornar os períodos que sejam Resultados
   * @return AvaliacaoAproveitamento[]
   */
  public function getPeriodosAmparados( $lRetornarResultados = false ) {

    if (count($this->aPeriodosAmparados) == 0) {

      foreach ($this->oDiarioAvaliacaoDisciplina->getAvaliacoes() as $oAvaliacaoAproveitamento) {

        if ( !$lRetornarResultados && $oAvaliacaoAproveitamento->getElementoAvaliacao()->isResultado() ) {
          continue;
        }
        if ($oAvaliacaoAproveitamento->isAmparado()) {

          $iCodigoPeriodo                            = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo();
          $this->aPeriodosAmparados[$iCodigoPeriodo] = $oAvaliacaoAproveitamento;
        }
      }
    }
    return $this->aPeriodosAmparados;
  }

  /**
   * Adiciona um periodo a ser amparado
   * @param AvaliacaoAproveitamento $oAvaliacaoAproveitamento
   */
  public function adicionarPeriodo(AvaliacaoAproveitamento $oAvaliacaoAproveitamento) {

    $iCodigoPeriodo = $oAvaliacaoAproveitamento->getElementoAvaliacao()->getCodigo();

    if( !array_key_exists( $iCodigoPeriodo, $this->aPeriodosAmparados ) ) {
      $this->aPeriodosAmparados[$iCodigoPeriodo] = $oAvaliacaoAproveitamento;
    }
  }

  /**
   * Salva um amparo
   *
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados.");
    }

    $oDaoAmparo = new cl_amparo();
    /**
     * Verificamos se o diario já possiu amparo lançado
     */
    $sWhere     = "ed81_i_diario = {$this->oDiarioAvaliacaoDisciplina->getCodigoDiario()}";
    $oSqlAmparo = $oDaoAmparo->sql_query_file(null, "ed81_i_codigo", null, $sWhere);
    $rsAmparo   = $oDaoAmparo->sql_record($oSqlAmparo);

    $oDaoAmparo->ed81_i_codigo = null;
    if ($oDaoAmparo->numrows > 0) {
      $oDaoAmparo->ed81_i_codigo = db_utils::fieldsMemory($rsAmparo, 0)->ed81_i_codigo;
    }

    $iJustificativa = 'null';
    $iConvencao     = 'null';
    if ( !empty($this->oJustificativa) ) {
      $iJustificativa = $this->oJustificativa->getCodigo();
    }
    if ( !empty($this->oConvencao) ) {

      $iConvencao = $this->oConvencao->getCodigo();
      $this->adicionarPeriodosConvencao();
    }

    $iNumeroDePeriodos = 0;

    /**
     * Removemos todos os amaparos dos periodos de avaliações
     */
    foreach ($this->oDiarioAvaliacaoDisciplina->getAvaliacoes() as $oAvaliacaoAproveitamento) {

      if ( $oAvaliacaoAproveitamento->getElementoAvaliacao()->isResultado() ) {

        /**
         * Remove amparo do Resultado... o Resultado final só será amparado se for todos os períodos amparados
         */
        $oAvaliacaoAproveitamento->setAmparado(false);
        continue;
      }
      $oAvaliacaoAproveitamento->setAmparado(false);
      $iNumeroDePeriodos ++;
    }

    if (count($this->aPeriodosAmparados) == $iNumeroDePeriodos) {
      $this->amparaTodosPeriodos(true);
    }

    /**
     * Quando todos períodos foram amparados, devemos amparar o resultado final
     */
    if ($this->isTotal()) {

      foreach ($this->oDiarioAvaliacaoDisciplina->getAvaliacoes() as $oAvaliacaoAproveitamento) {

        if ( $oAvaliacaoAproveitamento->getElementoAvaliacao()->isResultado() ) {
          $oAvaliacaoAproveitamento->setAmparado(true);
        }
      }
    }

    $oDaoAmparo->ed81_i_diario        = $this->oDiarioAvaliacaoDisciplina->getCodigoDiario();
    $oDaoAmparo->ed81_i_justificativa = $iJustificativa;
    $oDaoAmparo->ed81_c_todoperiodo   = $this->isTotal() ? "S" : "N";
    $oDaoAmparo->ed81_c_aprovch       = $this->isAdicionadoNaCargaHoraria() ? "S" : "N";
    $oDaoAmparo->ed81_i_convencaoamp  = $iConvencao;

    /**
     * Incluimos ou alteramos
     */
    if (!empty($oDaoAmparo->ed81_i_codigo)) {
      $oDaoAmparo->alterar($oDaoAmparo->ed81_i_codigo);
    } else {

      $oDaoAmparo->incluir(null);
      $this->iCodigo = $oDaoAmparo->ed81_i_codigo;
    }

    if ($oDaoAmparo->erro_status == 0) {

      $sMsgErro  = "Erro ao salvar amparo.\n";
      $sMsgErro .= str_replace('\\n', "\n", $oDaoAmparo->erro_msg);
      throw new BusinessException($sMsgErro);
    }

    /**
     * Seta como amparados os períodos informados
     */
    if (count($this->getPeriodosAmparados()) > 0) {

      foreach ($this->getPeriodosAmparados() as $oAvaliacaoAproveitamento) {
        $oAvaliacaoAproveitamento->setAmparado(true);
      }

      if ( !empty($this->oJustificativa) ) {

        foreach ($this->getResultadosQueDevemSeremAmparados() as $oAvaliacaoResultado) {
          $oAvaliacaoResultado->setAmparado(true);
        }
      }
    }

    $this->oDiarioAvaliacaoDisciplina->salvar();
  }

  /**
   * Quando convenção, devemos incluir amparo para todos os períodos a partir do menor período selecionado.
   * @return void
   */
  private function adicionarPeriodosConvencao() {

    $iMenorPeriodo = null;

    if (count($this->getPeriodosAmparados()) > 0) {

      /**
       * Verifica qual o menor periodo adicionado
       */
      foreach ($this->getPeriodosAmparados() as $oAvaliacaoAmparada) {

        $iOrdemPeriodo = $oAvaliacaoAmparada->getElementoAvaliacao()->getOrdemSequencia();
        if (empty($iMenorPeriodo)) {

          $iMenorPeriodo = $iOrdemPeriodo;
        }

        if ($iOrdemPeriodo < $iMenorPeriodo) {
          $iMenorPeriodo = $iOrdemPeriodo;
        }
      }
    }

    /**
     * Percorre todos períodos de avaliação, buscando os períodos que são maiores que o menor período já adicionado
     */
    foreach ($this->oDiarioAvaliacaoDisciplina->getAvaliacoes() as $oAvaliacaoAproveitamento) {

      if ( $oAvaliacaoAproveitamento->getElementoAvaliacao()->isResultado() ) {
        continue;
      }

      if ($oAvaliacaoAproveitamento->getElementoAvaliacao()->getOrdemSequencia() > $iMenorPeriodo) {
        $this->adicionarPeriodo($oAvaliacaoAproveitamento);
      }
    }
  }

  /**
   * Exclui o amparo para uma disciplina
   * @throws DBException
   * @throws BusinessException
   */
  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new DBException("Sem transação com banco de dados.");
    }

    /**
     * Remove o amparo de todas avaliações (Períodos e resultados)
     */
    foreach ($this->oDiarioAvaliacaoDisciplina->getAvaliacoes() as $oAvaliacaoAproveitamento) {

      $oAvaliacaoAproveitamento->setAmparado(false);
    }
    $this->oDiarioAvaliacaoDisciplina->salvar();

    $oDaoAmparo           = new cl_amparo();
    $oDaoAmparo->excluir($this->iCodigo);
    if ($oDaoAmparo->erro_status == 0) {

      $sMsgErro  = "Erro ao remover amparo.\n";
      $sMsgErro .= str_replace('\\n', "\n", $oDaoAmparo->erro_msg);
      throw new BusinessException($sMsgErro);
    }
  }

  /**
   * Retorna o tipo do amparo
   */
  public function getTipoAmparo() {
    return $this->iTipoAmparo;
  }

  /**
   * Busca os Resultados que compõem a avaliação do aluno que devem ser amparados.
   * @return AvaliacaoAproveitamento[] Resultados que devem ser amparados
   */
  private function getResultadosQueDevemSeremAmparados() {

    // busca todos resultados que não gerem resultado final
    $aResultados = array();
    foreach ($this->oDiarioAvaliacaoDisciplina->getResultados() as $oAvaliacaoResultado) {

      if ( $oAvaliacaoResultado->getElementoAvaliacao()->geraResultadoFinal() ) {
        continue;
      }
      $aResultados[] = $oAvaliacaoResultado;
    }

    /**
     * Valida se todos os elementos de avaliação que compõem um resultado esta amparado.
     * Se sim amparamos os resultados
     */
    $aResultadosQueDevemSeremAmparados = array();
    foreach ($aResultados as $oAvaliacaoResultado) {

      $iElementosAmparadosDoResultado = 0;
      $iElementosCompoemResultado     = count($oAvaliacaoResultado->getElementoAvaliacao()->getElementosComposicaoResultado());
      //percorre os elementos que compõem o resultado e valida se estão amparados
      foreach ( $oAvaliacaoResultado->getElementoAvaliacao()->getElementosComposicaoResultado() as $oElemento ) {

        foreach ($this->aPeriodosAmparados as $oPeriodoAmparado) {

          if ( $oPeriodoAmparado->getOrdemSequencia() == $oElemento->getElementoAvaliacao()->getOrdemSequencia() ) {
            $iElementosAmparadosDoResultado ++;
          }
        }
      }

      if ($iElementosCompoemResultado == $iElementosAmparadosDoResultado) {
        $aResultadosQueDevemSeremAmparados[] = $oAvaliacaoResultado;
      }
    }

    return $aResultadosQueDevemSeremAmparados;
  }
}
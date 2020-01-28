<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 23/03/17
 * Time: 08:32
 */

namespace ECidade\Patrimonial\Acordo\RegimeCompetencia\Model;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\RegimeCompetencia as RegimeCompetenciaRepository;

/**
 * Classe para reconhecimento das competencias dos acordos
 * Class Reconhecimento
 * @package ECidade\Patrimonial\Acordo\RegimeCompetencia\Model
 */
class Reconhecimento {

  /**
   * @var integer
   */
  protected $codigo;


  /**
   * @var \Acordo
   */
  protected $acordo;

  /**
   * @var \DBCompetencia
   */
  protected $competencia;

  /**
   * @var Float
   */
  protected $valor = 0;

  /**
   * Valor reconhecido
   * @var Float
   */
  protected $valorReconhecido = 0;

  /**
   * Valor realizado
   * @var Float
   */
  protected $valorRealizado = 0;

    /**
     * @var bool
     */
  protected $isDispesaAntecipada = false;

    /**
   * @return int
   */
  public function getCodigo() {

    return $this->codigo;
  }

  /**
   * @param int $codigo
   */
  public function setCodigo($codigo) {

    $this->codigo = $codigo;
  }

  /**
   * @return \Acordo
   */
  public function getAcordo() {
    return $this->acordo;
  }

  /**
   * @param \Acordo $acordo
   */
  public function setAcordo($acordo) {
    $this->acordo = $acordo;
  }

  /**
   * @return \DBCompetencia
   */
  public function getCompetencia() {
    return $this->competencia;
  }

  /**
   * @param \DBCompetencia $competencia
   */
  public function setCompetencia($competencia) {
    $this->competencia = $competencia;
  }

  /**
   * @return Float
   */
  public function getValor() {

    return $this->valor;
  }

  /**
   * @param Float $valor
   */
  public function setValor($valor) {
    $this->valor = $valor;
  }

  /**
   * @return float
   */
  public function getValorReconhecido() {

    return $this->valorReconhecido;
  }

  /**
   * @param float $valorReconhecido
   */
  public function setValorReconhecido($valorReconhecido) {

    $this->valorReconhecido = $valorReconhecido;
  }


  /**
   * Retorna o valor realizado
   * @return Float
   */
  public function getValorRealizado() {
    return $this->valorRealizado;
  }

  /**
   * Seta o Valor realizado.
   * @param Float $valorRealizado
   */
  public function setValorRealizado($valorRealizado) {
    $this->valorRealizado = $valorRealizado;
  }

  /**
   * @param integer $ano
   *
   * @throws \BusinessException
   */
  public function processar($ano) {

    $regimeRepository  = new RegimeCompetenciaRepository();
    $parcela = $regimeRepository->getParcelaPorAcordoECompetencia($this->getAcordo(), $this->getCompetencia());
    $regimeCompetencia = $regimeRepository->getByAcordo($this->getAcordo());
    $documento         = 4000;
    $iContaLiquidacao  = null;

    $oLancamentoAuxiliar = new \LancamentoAuxiliarReconhecimentoCompetencia();
    $oLancamentoAuxiliar->setContaDebito($regimeCompetencia->getConta());

    if ($regimeCompetencia->isDespesaAntecipada()) {

      $documento = 4002;
      $aEmpenhos = $this->getAcordo()->getEmpenhos();



      if (count($aEmpenhos) == 0) {


        $sMensagemEmpenho  = "Para o acordo ({$this->getAcordo()->getCodigo()}), foi realizada a programação do regime de competência como despesa antecipada.\n";
        $sMensagemEmpenho .= "Não foram encontrados empenhos para esse acordo.\nVerifique.";
        throw new \BusinessException($sMensagemEmpenho);
      }
      $contaLiquidacao = \cl_translan::getContaLiquidacao($aEmpenhos[0]->getNumero(), array(3, 23), $ano, 2);
      if (empty($contaLiquidacao)) {

        $sMensagemLiquidacao = "Para o acordo ({$this->getAcordo()->getCodigo()}), foi realizada a programação do regime de competência como despesa antecipada.\n";
        $sMensagemLiquidacao.= "Não foram encontradas liquidações para os empenhos  deste acordo.\nVerifique.";
        throw new \BusinessException($sMensagemLiquidacao);
      }

      $oLancamentoAuxiliar->setContaCredito(\ContaPlanoPCASPRepository::getContaPorReduzido($contaLiquidacao, $ano));
    }

    $parcela->setReconhecida(true);
    $regimeRepository->persistirParcela($regimeCompetencia, $parcela);
    $oAcordo = $this->getAcordo();
    $sTexto  = "Reconhecimento de regime da competência ".$parcela->getCompetencia()->getCompetencia(\DBCompetencia::FORMATO_MMAAAA);
    $sTexto .= " do acordo {$oAcordo->getNumero()}/{$oAcordo->getAno()}";
    $oLancamentoAuxiliar->setAcordo($this->getAcordo());
    $oLancamentoAuxiliar->setParcela($parcela);
    $oLancamentoAuxiliar->setObservacaoHistorico($sTexto);
    $oLancamentoAuxiliar->setValorTotal($parcela->getValor());
    $oLancamentoAuxiliar->setFavorecido($this->getAcordo()->getContratado()->getCodigo());
    $oEventoContabil = new \EventoContabil($documento, $ano);
    $oEventoContabil->executaLancamento($oLancamentoAuxiliar);
  }

    /**
     * @param boolean $lBoolean
     */
    public function setDispesaAntecipada($lBoolean)
    {
        $this->isDispesaAntecipada = $lBoolean;
    }

    /**
     * @return bool
     */
    public function isDispesaAntecipada()
    {
        return $this->isDispesaAntecipada;
    }

}

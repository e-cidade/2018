<?php
namespace ECidade\RecursosHumanos\RH\PontoEletronico\Validacao;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\ProcessamentoPontoEletronico;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento as EventoModel;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Repository\Evento as EventoRepository;

/**
 * Class Servidor
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Validacao
 */
abstract class PontoEletronico {

  /**
   * @type integer
   */
  const POSSUI_AFASTAMENTO_NO_RH_NA_DATA = 1;

  /**
   * @type integer
   */
  const POSSUI_ESCALA_NA_DATA = 2;

  /**
   * @type integer
   */
  const POSSUI_JUSTIFICATIVA_NA_DATA = 3;

  /**
   * @type integer
   */
  const POSSUI_LOTACAO_CONFIGURADA_NO_PONTO_ELETRONICO = 4;

  /**
   * @type integer
   */
  const POSSUI_LOTACAO_CONFIGURADA = 5;

  /**
   * @type integer
   */
  const POSSUI_CONFLITO_ENTRE_EVENTOS = 6;

  /**
   * @var \Servidor
   */
  protected $servidor;

  /**
   * Servidor constructor.
   */
  protected function __construct() {}

  /**
   * @return PontoEletronico|Evento
   */
  public static function create() {
    return new static;
  }

  /**
   * @param $servidor
   * @return $this
   */
  public function setServidor(\Servidor $servidor) {
    $this->servidor = $servidor;
  }

  /**
   * @return \Servidor
   */
  public function getServidor() {
    return $this->servidor;
  }

  /**
   * @return bool
   */
  protected function possuiLotacaoConfiguradaParaServidor() {

    $iCodigoLotacaoServidor = $this->servidor->getCodigoLotacao();
    return !empty($iCodigoLotacaoServidor);
  }

  /**
   * @return bool
   */
  protected function possuiLotacaoConfiguradaNoPontoEletronico() {

    $oConfiguracoesLotacao = ParametrosRepository::create()->getConfiguracoesLotacao($this->servidor->getCodigoLotacao());
    return !empty($oConfiguracoesLotacao);
  }

  /**
   * @param \DBDate $data
   * @return bool
   */
  protected function possuiEscalaNaData(\DBDate $data) {

    $escalasServidor = $this->servidor->getEscalas();

    if(empty($escalasServidor)) {
      return false;
    }
    
    $escala = ProcessamentoPontoEletronico::getEscalaNaData($escalasServidor, $data);
    return !empty($escala);
  }

  /**
   * @param EventoModel $evento
   * @return bool
   */
  protected function possuiConflitoDeEvento(EventoModel $evento) {
    return EventoRepository::getInstance()->existeConflitoEntreDataServidor($evento, $this->servidor);
  }

  /**
   * @param \DBDate $data
   * @return bool
   */
  protected function possuiAfastamentoNoRHNaData(\DBDate $data) {
    return $this->servidor->isAfastadoNoRH($data);
  }

  /**
   * @param \DBDate $data
   * @return bool
   */
  protected function possuiJustificativaNaData(\DBDate $data) {

    $assentamento = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($this->servidor, "S", $data, \Assentamento::NATUREZA_JUSTIFICATIVA);
    return !empty($assentamento);
  }

}

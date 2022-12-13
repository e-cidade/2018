<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;

/**
 * Classe com métodos padrões para os tipos de hora
 * Class BaseHora
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class BaseHora {

  const HORAS_ADICIONAL_NOTURNO    = 1;
  const HORAS_EXTRA50              = 2;
  const HORAS_EXTRA75              = 3;
  const HORAS_EXTRA100             = 4;
  const HORAS_FALTA                = 5;
  const HORAS_TRABALHO             = 6;
  const HORAS_EXTRA50_NOTURNA      = 7;
  const HORAS_EXTRA75_NOTURNA      = 8;
  const HORAS_EXTRA100_NOTURNA     = 9;
  const HORAS_EXTRAS_EVENTO        = 10;
  const HORAS_EXTRA_CALCULO        = 11;

  const TOLERANCIA_BUSCA_MARCACOES_ANTES  = 5;
  const TOLERANCIA_BUSCA_MARCACOES_DEPOIS = 8;

  /**
   * Tipo de hora instanciada
   * @var int
   */
  private $iTipoHora;

  /**
   * @var DiaTrabalho
   */
  private $oDiaTrabalho;

  private $oHoraintervalo;

  /**
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao
   */
  private $oConfiguracoesLotacao;

  /**
   * @var \DateTime
   */
  private $oHorasTrabalhadas;

  /**
   * @var \DateTime
   */
  private $oHoraZerada;

  /**
   * @var MarcacoesPontoCollection
   */
  private $aMarcacoesCollection;

  public function __construct() {

    $this->oConfiguracoesLotacao = $this->oDiaTrabalho->getConfiguracoesLotacao();
    $this->oHoraZerada           = new \DateTime($this->getDiaTrabalho()->getData()->getDate() . ' 00:00');
    $this->aMarcacoesCollection  = $this->oDiaTrabalho->getMarcacoes();

    if(    $this->oDiaTrabalho->getParametrosPontoEletronico()->horaExtraSomenteComAutorizacao()
      && ($this instanceof Trabalho || $this instanceof AdicionalNoturno)
    ) {
      $this->guardaMarcacoesReais();
    }
  }

  /**
   * @return \DateTime
   */
  public function getHoraZerada() {
    return clone $this->oHoraZerada;
  }

  /**
   * @param int $iTipoHora
   */
  public function setTipoHora($iTipoHora) {
    $this->iTipoHora = $iTipoHora;
  }

  /**
   * @param DiaTrabalho $oDiaTrabalho
   */
  public function setDiaTrabalho(DiaTrabalho $oDiaTrabalho) {
    $this->oDiaTrabalho = $oDiaTrabalho;
  }

  /**
   * @param \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao $oConfiguracoesLotacao
   */
  public function setConfiguracoesLotacao($oConfiguracoesLotacao) {
    $this->oConfiguracoesLotacao = $oConfiguracoesLotacao;
  }

  /**
   * Define as horas trabalhadas
   *
   * @param \DateTime $oHorasTrabalhadas
   */
  public function setHorasTrabalhadas($oHorasTrabalhadas) {
    $this->oHorasTrabalhadas = $oHorasTrabalhadas;
  }

  /**
   * Retorna as horas trabalhadas
   *
   * @return \DateTime $oHorasTrabalhadas
   */
  public function getHorasTrabalhadas() {
    return $this->oHorasTrabalhadas;
  }

  /**
   * Retorna o tipo de hora
   * @return int
   */
  public function getTipoHora() {
    return $this->iTipoHora;
  }

  /**
   * Retorna o objeto do dia de trabalho
   * @return DiaTrabalho
   */
  public function getDiaTrabalho() {
    return $this->oDiaTrabalho;
  }

  /**
   * @return \DBDate
   */
  public function getDataPonto() {
    return $this->oDiaTrabalho->getData();
  }

  /**
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosLotacao|mixed|null
   */
  public function getConfiguracoesLotacao() {
    return $this->oConfiguracoesLotacao;
  }

  /**
   * Retorna a hora da jornada especificada pela posição do array
   * @param int $iPosicao
   * @return string
   */
  public function getHoraJornada($iPosicao = 0) {

    $aHorasJornada = $this->oDiaTrabalho->getJornada()->getHoras();
    return new \DateTime($aHorasJornada[$iPosicao]->oHora->format('Y-m-d H:i'));
  }

  /**
   * Retorna instâncias de DateTime da hora de marcação e da hora da jornada
   * @return \stdClass
   */
  public function getPrimeirasHoras() {

    $aMarcacoes    = $this->oDiaTrabalho->getMarcacoes();
    $oHoraMarcacao = !$aMarcacoes->isEmpty() ? $aMarcacoes->getMarcacao(1)->getMarcacao() : null;

    if($aMarcacoes->getMarcacao(1) != null && $aMarcacoes->getMarcacao(1)->getJustificativa() != null) {
      $oHoraMarcacao = null;
    }

    $oDadosHoras                = new \stdClass();
    $oDadosHoras->oHoraMarcacao = $oHoraMarcacao;
    $oDadosHoras->oHoraJornada  = $this->getHoraJornada();

    return $oDadosHoras;
  }

  /**
   * Retorna instâncias de DateTime da hora de marcação e da hora da jornada
   * @return \stdClass
   */
  public function getUltimasHoras() {

    $aMarcacoes                 = $this->oDiaTrabalho->getMarcacoes();
    $oUltimaMarcacao            = $aMarcacoes->getUltimaMarcacaoComRegistro();
    $oHoraMarcacao              = !$aMarcacoes->isEmpty() ? $oUltimaMarcacao->getMarcacao() : null;
    $iUltimaPosicaoHorasJornada = count($this->oDiaTrabalho->getJornada()->getHoras()) - 1;
    $oDiferencaUltimoPeriodo    = null;

    if($oUltimaMarcacao != null && $oUltimaMarcacao->getJustificativa() != null) {
      $oHoraMarcacao = null;
    }

    if($oUltimaMarcacao != null && $oUltimaMarcacao->getTipo() == MarcacaoPonto::SAIDA_3) {

      $oSaida2   = $aMarcacoes->getMarcacaoSaida2();
      $oEntrada3 = $aMarcacoes->getMarcacaoEntrada3();

      if($oSaida2 != null) {
        if(!$oSaida2->hasMarcacaoLancada()) {
          $oDiferencaUltimoPeriodo = $oSaida2->getMarcacao()->diff($oEntrada3->getMarcacao());
        }
      }
    }

    $oDadosHoras                = new \stdClass();
    $oDadosHoras->oHoraMarcacao = $oHoraMarcacao;
    $oDadosHoras->oHoraJornada  = $this->getHoraJornada($iUltimaPosicaoHorasJornada);

    if($oDiferencaUltimoPeriodo !== null && $oHoraMarcacao !== null) {
      $oDadosHoras->oHoraMarcacao->sub($oDiferencaUltimoPeriodo);
    }

    return $oDadosHoras;
  }

  /**
   * Retorna a diferença entre 2 horas
   * @param \DateTime $oHora1
   * @param \DateTime $oHora2
   * @return bool|\DateInterval
   */
  public function getDiferencaHoras(\DateTime $oHora1, \DateTime $oHora2) {
    return $oHora1->diff($oHora2);
  }

  /**
   * Soma as horas de 2 DateTime
   * @param \DateTime $oHora1 - DateTime que receberá o valor somado
   * @param \DateTime $oHora2 - DateTime com o valor a ser somado
   * @return \DateTime
   */
  protected function somaHoras(\DateTime $oHora1, \DateTime $oHora2) {

    $iHora   = $oHora2->format('H');
    $iMinuto = $oHora2->format('i');

    $oDateInterval   = new \DateInterval("PT{$iHora}H{$iMinuto}M");
    $oHoraExtraTotal = new \DateTime($oHora1->format('Y-m-d H:i'));

    return $oHoraExtraTotal->add($oDateInterval);
  }

  /**
   * Retorna as Horas de Intervalo do servidor
   * @return bool|\DateInterval
   */
  protected function getHorasIntervalo() {

    if (!empty($this->oHoraintervalo)) {
      return $this->oHoraintervalo;
    }

    $sStringData   = $this->getDiaTrabalho()->getData()->getDate();
    $aHorasJornada = $this->getDiaTrabalho()->getJornada()->getHoras();

    foreach ($aHorasJornada as $iChave => $oHorario) {

      if ($oHorario->iTipoRegistro == 2 && array_key_exists($iChave + 1, $aHorasJornada)) {

        $oHoraInicio = new \DateTime($sStringData . " " . $oHorario->oHora->format('H:i'));
        $oHoraSaida  = $aHorasJornada[$iChave + 1];
        $oHoraFim    = new \DateTime($sStringData . " " . $oHoraSaida->oHora->format('H:i'));
        $this->oHoraintervalo = $oHoraFim->diff($oHoraInicio);
        break;
      }
    }

    if(is_null($this->oHoraintervalo)) {
      $this->oHoraintervalo = new \DateInterval("PT0H0M");
    }

    return $this->oHoraintervalo;
  }

  /**
   * Calcula o total de horas de trabalho na jornada
   * @param bool $lComIntervalo
   * @return \DateTime
   */
  public function totalHorasJornada($lComIntervalo = true) {

    $oPrimeirasHora  = $this->getPrimeirasHoras();
    $oUltimasHoras   = $this->getUltimasHoras();
    $oHoraTotal      = new \DateTime('00:00');

    $oDiferencaHoras = $this->getDiferencaHoras($oUltimasHoras->oHoraJornada, $oPrimeirasHora->oHoraJornada);
    $oHoraTotal->setTime($oDiferencaHoras->h, $oDiferencaHoras->i);

    if($lComIntervalo) {
      return $oHoraTotal;
    }

    return $oHoraTotal->add($this->getHorasIntervalo());
  }

  /**
   * Converte o intervalo em minutos
   * @param \DateInterval $dateInterval
   * @return float
   */
  public static function converterIntervaloEmMinutos (\DateInterval $dateInterval) {

    $segundos = ($dateInterval->y * 365 * 24 * 60 * 60) +
      ($dateInterval->m * 30 * 24 * 60 * 60) +
      ($dateInterval->d * 24 * 60 * 60) +
      ($dateInterval->h * 60 * 60) +
      ($dateInterval->i * 60) +
      $dateInterval->s;
    return floor($segundos/60);
  }

  /**
   * Retorna se a hora informada está no intervalo informado
   *
   * @param \DateTime $oHoraVerificar
   * @param \DateTime $oHoraInicio
   * @param \DateTime $oHoraFim
   *
   * @return boolean
   */
  protected function horaEstaNoIntervalo(\DateTime $oHoraVerificar, \DateTime $oHoraInicio, \DateTime $oHoraFim) {

    if($oHoraVerificar->diff($oHoraInicio)->invert || ($oHoraVerificar->format('H:i') == $oHoraInicio->format('H:i'))) {
      if($oHoraFim->diff($oHoraVerificar)->invert || ($oHoraVerificar->format('H:i') == $oHoraFim->format('H:i'))) {
        return true;
      }
    }

    return false;
  }

  /**
   * Monta uma nova coleção de marcações quando horas extras são autorizadas somente com autorização, e a instância
   * é de AdicionalNoturno ou Trabalho
   * Isto faz-se necessário, pois no caso das horas extras somente com autorização, as marcações são atualizadas
   * dentro do limite permitido, porém as horas trabalhadas e adicional noturno não devem sofrer influência nesse
   * cálculo
   */
  protected function guardaMarcacoesReais() {

    $this->aMarcacoesCollection = new MarcacoesPontoCollection();

    foreach($this->oDiaTrabalho->getMarcacoes()->getMarcacoes() as $oMarcacaoPonto) {

      $novaInstancia = clone $oMarcacaoPonto;
      $this->aMarcacoesCollection->add($novaInstancia);
    }
  }

  /**
   * @return MarcacoesPontoCollection
   */
  public function getMarcacoesReais() {
    return $this->aMarcacoesCollection;
  }


  /**
   *
   * @param $iMinutos
   * @return \DateInterval
   */
  public static function converterMinutosEmInterval($iMinutos) {

     $horas   = floor($iMinutos / 60);
     $minutos = $iMinutos % 60;
     $intervalo  = "PT{$horas}H{$minutos}M";
     return new \DateInterval($intervalo);
  }

  /**
   * Rrtorna minutos em formato H:i
   * @param $iMinutos
   * @return string
   */
  public static function converterMinutosEmHoraMinuto($iMinutos) {
    $hora = self::converterMinutosEmInterval($iMinutos);
    return $hora->format("%H:%I");
  }
}

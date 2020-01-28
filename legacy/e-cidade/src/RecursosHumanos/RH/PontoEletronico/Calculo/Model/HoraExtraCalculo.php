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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\HoraExtra;

/**
 * Class HoraExtraCalculo
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author John Lenon Reis <john.reis@dbseller.com.br>
 */
class HoraExtraCalculo extends HoraExtra {

  /**
   * @var Extra
   */
  private $oHorasExtra50;

  /**
   * @var Extra
   */
  private $oHorasExtra75;

  /**
   * @var Extra
   */
  private $oHorasExtra100;


  /**
   * @var \DateTime
   */
  private $oInicioHorarioNoturno;

  /**
   * @var \DateTime
   */
  private $oFinalHorarioNoturno;

  /**
   * @var int
   */
  private $iMaximoHorasExtras50;

  /**
   * @var int
   */
  private $iMaximoHorasExtras75;

  /**
   * @var int
   */
  private $iMaximoHorasExtras100;

  /**
   * @var int
   */
  private $iHorasExtrasAutorizadas;

  /**
   * @var HoraExtraArtefato[]
   */
  private $horasCalculadas = array();

  /**
   * @return \DateTime
   */
  public function getInicioHorarioNoturno()
  {
    return $this->oInicioHorarioNoturno;
  }

  /**
   * @param \DateTime $oInicioHorarioNoturno
   */
  public function setInicioHorarioNoturno($oInicioHorarioNoturno)
  {
    $this->oInicioHorarioNoturno = $oInicioHorarioNoturno;
  }

  /**
   * @return \DateTime
   */
  public function getFinalHorarioNoturno()
  {
    return $this->oFinalHorarioNoturno;
  }

  /**
   * @param \DateTime $oFinalHorarioNoturno
   */
  public function setFinalHorarioNoturno($oFinalHorarioNoturno)
  {
    $this->oFinalHorarioNoturno = $oFinalHorarioNoturno;
  }

  /**
   * @return int
   */
  public function getMaximoHorasExtras50()
  {
    return $this->iMaximoHorasExtras50;
  }

  /**
   * @param int $iMaximoHorasExtras50
   */
  public function setMaximoHorasExtras50($iMaximoHorasExtras50)
  {
    $this->iMaximoHorasExtras50 = $iMaximoHorasExtras50;
  }

  /**
   * @return int
   */
  public function getMaximoHorasExtras75()
  {
    return $this->iMaximoHorasExtras75;
  }

  /**
   * @param int $iMaximoHorasExtras75
   */
  public function setMaximoHorasExtras75($iMaximoHorasExtras75)
  {
    $this->iMaximoHorasExtras75 = $iMaximoHorasExtras75;
  }

  /**
   * @return int
   */
  public function getMaximoHorasExtras100()
  {
    return $this->iMaximoHorasExtras100;
  }

  /**
   * @param int $iMaximoHorasExtras100
   */
  public function setMaximoHorasExtras100($iMaximoHorasExtras100)
  {
    $this->iMaximoHorasExtras100 = $iMaximoHorasExtras100;
  }

  /**
   * @return Extra
   */
  public function getHorasExtras50()
  {
    return $this->oHorasExtra50;
  }

  /**
   * @param Extra $oHorasExtra50
   */
  public function setHorasExtras50($oHorasExtra50)
  {
    $this->oHorasExtra50 = $oHorasExtra50;
  }

  /**
   * @return Extra
   */
  public function getHorasExtras75()
  {
    return $this->oHorasExtra75;
  }

  /**
   * @param Extra $oHorasExtra75
   */
  public function setHorasExtras75($oHorasExtra75)
  {
    $this->oHorasExtra75 = $oHorasExtra75;
  }

  /**
   * @return Extra
   */
  public function getHorasExtras100()
  {
    return $this->oHorasExtra100;
  }

  /**
   * @param Extra $oHorasExtra100
   */
  public function setHorasExtras100($oHorasExtra100)
  {
    $this->oHorasExtra100 = $oHorasExtra100;
  }

  /**
   * @return int
   */
  public function getHorasExtrasAutorizadas()
  {
    return $this->iHorasExtrasAutorizadas;
  }

  /**
   * @param int $iHorasExtrasAutorizadas
   */
  public function setHorasExtrasAutorizadas($iHorasExtrasAutorizadas)
  {
    $this->iHorasExtrasAutorizadas = $iHorasExtrasAutorizadas;
  }


  /**
   * HoraExtraCalculo constructor.
   * @param DiaTrabalho $oDiaTrabalho
   */
  public function __construct(DiaTrabalho $oDiaTrabalho)
  {
    $this->setDiaTrabalho($oDiaTrabalho);

    parent::__construct();

    $this->oInicioHorarioNoturno = new \DateTime($this->getDiaTrabalho()->getData()->getDate(). " 22:00");
    $this->oFinalHorarioNoturno =  new \DateTime($this->getDiaTrabalho()->getData()->getDate(). " 05:00");
    $this->oFinalHorarioNoturno->modify("+1 day");

    $this->converteConfiguracaoHorasExtrasEmMinutos();

    /**
     * Se o parâmetro para calcular horas extras somente com autorização estiver como SIM e possuir assentamento de autorização
     */
    if($this->getDiaTrabalho()->getHorasExtrasAutorizadas()){

      $oIntervaloAutorizado = $this->converteDateTimeParaInterval($this->getDiaTrabalho()->getHorasExtrasAutorizadas());
      $iMinutosAutorizados  = BaseHora::converterIntervaloEmMinutos($oIntervaloAutorizado);
      $this->setHorasExtrasAutorizadas($iMinutosAutorizados);

      $this->atualizaLimitesAutorizados();
    }

    $this->verificaFeriadoDSR();

    $this->setHorasExtras50(new Extra($this->getMaximoHorasExtras50()));
    $this->setHorasExtras75(new Extra($this->getMaximoHorasExtras75()));
    $this->setHorasExtras100(new Extra($this->getMaximoHorasExtras100()));

  }

  public function calcular() {

    $diaTrabalho = $this->getDiaTrabalho();

    if ($diaTrabalho->getJornada()->isFolga() && $this->getDiaTrabalho()->getFeriado() == null) {
      $this->processarFolga();
    } else {

      $oHoraExtraInicial   = $this->getHoraExtraInicial();
      $oHoraExtraIntervalo = $this->getHoraExtraIntervalo();
      $oHoraExtraFinal     = $this->getHoraExtraFinal();

      if (!empty($oHoraExtraInicial) && !$this->isZero($oHoraExtraInicial)) {

        $oInicioExtraInicial = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada1()->getMarcacao();
        $oExtraInicialSeparada = $this->getValorDiurnoNoturno($oInicioExtraInicial, $oHoraExtraInicial);

        if($oExtraInicialSeparada->getIniciaEm() == Extra::TIPO_DIURNA) {

          $this->horasCalculadas[] = new ExtraCalculada($oExtraInicialSeparada->getMinutosDiurnos(), Extra::TIPO_DIURNA);
          $this->horasCalculadas[] = new ExtraCalculada($oExtraInicialSeparada->getMinutosNoturnos(), Extra::TIPO_NOTURNA);
        } else {

          $this->horasCalculadas[] = new ExtraCalculada($oExtraInicialSeparada->getMinutosNoturnos(), Extra::TIPO_NOTURNA);
          $this->horasCalculadas[] = new ExtraCalculada($oExtraInicialSeparada->getMinutosDiurnos(), Extra::TIPO_DIURNA);
        }

      }

      if (!empty($oHoraExtraIntervalo) && !$this->isZero($oHoraExtraIntervalo)) {

        $oInicioExtraIntervalo = $this->getDiaTrabalho()->getMarcacoes()->getMarcacaoEntrada2()->getMarcacao();
        $oExtraIntervaloSeparada = $this->getValorDiurnoNoturno($oInicioExtraIntervalo, $oHoraExtraIntervalo);

        if($oExtraIntervaloSeparada->getIniciaEm() == Extra::TIPO_DIURNA) {

          $this->horasCalculadas[] = new ExtraCalculada($oExtraIntervaloSeparada->getMinutosDiurnos(), Extra::TIPO_DIURNA);
          $this->horasCalculadas[] = new ExtraCalculada($oExtraIntervaloSeparada->getMinutosNoturnos(), Extra::TIPO_NOTURNA);
        } else {

          $this->horasCalculadas[] = new ExtraCalculada($oExtraIntervaloSeparada->getMinutosNoturnos(), Extra::TIPO_NOTURNA);
          $this->horasCalculadas[] = new ExtraCalculada($oExtraIntervaloSeparada->getMinutosDiurnos(), Extra::TIPO_DIURNA);
        }
      }


      if (!empty($oHoraExtraFinal) && !$this->isZero($oHoraExtraFinal)) {

        $aHorasJornada       = $this->getDiaTrabalho()->getJornada()->getHoras();
        $oInicioExtraFinal   = end($aHorasJornada)->oHora;
        $oExtraFinalSeparada = $this->getValorDiurnoNoturno($oInicioExtraFinal, $oHoraExtraFinal);

        if($oExtraFinalSeparada->getIniciaEm() == Extra::TIPO_DIURNA) {

          $this->horasCalculadas[] = new ExtraCalculada($oExtraFinalSeparada->getMinutosDiurnos(), Extra::TIPO_DIURNA);
          $this->horasCalculadas[] = new ExtraCalculada($oExtraFinalSeparada->getMinutosNoturnos(), Extra::TIPO_NOTURNA);
        } else {

          $this->horasCalculadas[] = new ExtraCalculada($oExtraFinalSeparada->getMinutosNoturnos(), Extra::TIPO_NOTURNA);
          $this->horasCalculadas[] = new ExtraCalculada($oExtraFinalSeparada->getMinutosDiurnos(), Extra::TIPO_DIURNA);
        }
      }
    }

    /**
     * Realiza o processo de soma de valores de horas extras diurnas e noturnas
     */
    foreach ($this->horasCalculadas as $horaCalculada) {
      $this->processaHorasExtras($horaCalculada);
    }


    $diaTrabalho->setHorasExtra50(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras50()->getDiurnas()));
    $diaTrabalho->setHorasExtra50Noturna(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras50()->getNoturnas()));

    $diaTrabalho->setHorasExtra75(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras75()->getDiurnas()));
    $diaTrabalho->setHorasExtra75Noturna(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras75()->getNoturnas()));

    $diaTrabalho->setHorasExtra100(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras100()->getDiurnas()));
    $diaTrabalho->setHorasExtra100Noturna(BaseHora::converterMinutosEmHoraMinuto($this->getHorasExtras100()->getNoturnas()));
  }

  /**
   * Busca limites de horas extras configurados e converte em minutos
   */
  private function converteConfiguracaoHorasExtrasEmMinutos() {

    $dataTrabalho   = $this->getHoraZerada()->format("Y-m-d")." ";
    $oExtra50Maximo = $this->converteDateTimeParaInterval(new \DateTime($dataTrabalho.$this->getDiaTrabalho()->getConfiguracoesLotacao()->getHoraExtra50()));
    $this->setMaximoHorasExtras50(BaseHora::converterIntervaloEmMinutos($oExtra50Maximo));

    $oExtra75Maximo = $this->converteDateTimeParaInterval(new \DateTime($dataTrabalho.$this->getDiaTrabalho()->getConfiguracoesLotacao()->getHoraExtra75()));
    $this->setMaximoHorasExtras75(BaseHora::converterIntervaloEmMinutos($oExtra75Maximo));

    $oExtra100Maximo = $this->converteDateTimeParaInterval(new \DateTime($dataTrabalho.$this->getDiaTrabalho()->getConfiguracoesLotacao()->getHoraExtra100()));
    $this->setMaximoHorasExtras100(BaseHora::converterIntervaloEmMinutos($oExtra100Maximo));
  }

  public function converteDateTimeParaInterval(\DateTime $dateTime) {
    return $this->getDiferencaHoras($dateTime, $this->getHoraZerada());
  }

  public function getValorDiurnoNoturno($oInicio, $oValor) {

    $oHoraExtraArtefato = new HoraExtraArtefato( $this->getInicioHorarioNoturno(), $this->getFinalHorarioNoturno(), $oInicio, $oValor );
    return $oHoraExtraArtefato;
  }

  /**
   * Realiza a separacao das horas extras dentro dos limites configurados.
   * @param ExtraCalculada $horaCalculada
   */
  protected function processaHorasExtras(ExtraCalculada $horaCalculada) {

    // Se valor atual 50 >= maximo minutos 50 : passa reto
    if ($this->getHorasExtras50()->deveCalcular()) {

      $resto = $this->getHorasExtras50()->incrementar($horaCalculada);
      if ($resto > 0) {

        $horaCalculada->setMinutos($resto);
        $this->processaHorasExtras($horaCalculada);
      }
      return;
   }


    // Se valor atual 75 >= maximo minutos 75 : passa reto
    if ($this->getHorasExtras75()->deveCalcular()) {
      $resto = $this->getHorasExtras75()->incrementar($horaCalculada);
      if ($resto) {
        $horaCalculada->setMinutos($resto);
        $this->processaHorasExtras($horaCalculada);
      }
      return;
    }

    // Se valor atual 100 >= maximo minutos 100 : passa reto
    if ($this->getHorasExtras100()->deveCalcular()) {

      $resto = $this->getHorasExtras100()->incrementar($horaCalculada);
      if ($resto) {

        $horaCalculada->setMinutos($resto);
        $this->processaHorasExtras($horaCalculada);
      }
    }
  }

  /**
   * Caso o parâmetro de autorização de hora extra for SIM e possuir assentamento de autorização
   * Atualiza os limites das horas extras com base nas horas autorizadas no assentamento
   * @return bool
   */
  private function atualizaLimitesAutorizados() {

    $iMinutosAutorizados = $this->getHorasExtrasAutorizadas();
    $iSomaLimitesConfigurados = $this->iMaximoHorasExtras50 + $this->iMaximoHorasExtras75 + $this->iMaximoHorasExtras100;

    if ($iSomaLimitesConfigurados <= $iMinutosAutorizados) {
      return false;
    }

    if ($this->iMaximoHorasExtras50 >= $iMinutosAutorizados) {
      $this->setMaximoHorasExtras50($iMinutosAutorizados);
      $this->setMaximoHorasExtras75(0);
      $this->setMaximoHorasExtras100(0);
      $iMinutosAutorizados = 0;
    } else{
      $iMinutosAutorizados -= $this->iMaximoHorasExtras50;
    }

    if ($this->iMaximoHorasExtras75 >= $iMinutosAutorizados) {
      $this->setMaximoHorasExtras75($iMinutosAutorizados);
      $this->setMaximoHorasExtras100(0);
      $iMinutosAutorizados = 0;
    } else {
      $iMinutosAutorizados -= $this->iMaximoHorasExtras75;
    }

    if ($this->iMaximoHorasExtras100 >= $iMinutosAutorizados) {
      $this->setMaximoHorasExtras100($iMinutosAutorizados);
    }

    return true;
  }

  /**
   * Realiza o ajuste das Horas extras na folga
   */
  private function verificaFeriadoDSR() {

    if ($this->getDiaTrabalho()->getJornada()->isDSR() || ($this->getDiaTrabalho()->getFeriado())) {

      $aHoraSeparada = explode(":", $this->getDiaTrabalho()->getHorasTrabalho());
      $iMinutosTrabalhados  = BaseHora::converterIntervaloEmMinutos(new \DateInterval("PT{$aHoraSeparada[0]}H{$aHoraSeparada[1]}M"));
      $iSomaTotaisExtras =  + $this->iMaximoHorasExtras50 + $this->iMaximoHorasExtras75 + $this->iMaximoHorasExtras100;

      $this->setMaximoHorasExtras100($iMinutosTrabalhados);
      if($this->getDiaTrabalho()->getHorasExtrasAutorizadas() && $iMinutosTrabalhados > $iSomaTotaisExtras) {
        $this->setMaximoHorasExtras100($iSomaTotaisExtras);
      }

      $this->setMaximoHorasExtras50(0);
      $this->setMaximoHorasExtras75(0);
      return;
    }
  }

  private function processarFolga()  {

    $this->setMaximoHorasExtras50(0);
    $this->setMaximoHorasExtras75(0);
    $this->setMaximoHorasExtras100(0);

    $aHorasTrabalhadas = explode(":", $this->getDiaTrabalho()->getHorasTrabalho());
    $aHorasNoturnas    = explode(":", $this->getDiaTrabalho()->getHorasAdicionalNoturno());

    $iMinutos2Horas       = 120;
    $iMinutosTrabalhados  = BaseHora::converterIntervaloEmMinutos(new \DateInterval("PT{$aHorasTrabalhadas[0]}H{$aHorasTrabalhadas[1]}M"));
    $iMinutosNoturnos     = BaseHora::converterIntervaloEmMinutos(new \DateInterval("PT{$aHorasNoturnas[0]}H{$aHorasNoturnas[1]}M"));
    $iMinutosDiurnos      = $iMinutosTrabalhados - $iMinutosNoturnos;

    /**
     * Quando é folga, segue a seguinte regra:
     * 2 primeiras horas são 50%
     * Horas seguintes são 75%
     * Horario noturno é inteiro 100%
     */

    $this->setMaximoHorasExtras50($iMinutosDiurnos);
    $this->setMaximoHorasExtras100($iMinutosNoturnos);

    if($iMinutosDiurnos > $iMinutos2Horas){

      $this->setMaximoHorasExtras50($iMinutos2Horas);
      $this->setMaximoHorasExtras75($iMinutosDiurnos - $iMinutos2Horas);
    }

    /**
     * Quanto tiver autorizaçao de hora extra, atualiza os limites conforme as horas autorizadas
     */
    if($this->getDiaTrabalho()->getHorasExtrasAutorizadas()) {
      $this->atualizaLimitesAutorizados();
    }

    $this->getHorasExtras50()->setLimite($this->getMaximoHorasExtras50());
    $this->getHorasExtras75()->setLimite($this->getMaximoHorasExtras75());
    $this->getHorasExtras100()->setLimite($this->getMaximoHorasExtras100());

    $this->horasCalculadas[] = new ExtraCalculada( $iMinutosDiurnos, Extra::TIPO_DIURNA);
    $this->horasCalculadas[] = new ExtraCalculada( $iMinutosNoturnos, Extra::TIPO_NOTURNA);
  }
}

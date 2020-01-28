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
use ECidade\Configuracao\Cadastro\Model\Feriado;

/**
 * Class Extra100Diurna
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Extra100Diurna extends Extra100 implements Horas {

  /**
   * Extra75Diurna constructor.
   * @param DiaTrabalho $oDiaTrabalho
   */
  public function __construct(DiaTrabalho $oDiaTrabalho) {

    parent::__construct($oDiaTrabalho);
    $this->setTipoHora(BaseHora::HORAS_EXTRA100);
  }

  /**
   * Calcula o número de horas extra 100% em determinado dia
   * @return \DateTime
   */
  public function calcular() {

    $this->atualizaMaximoExtra100();

    $oHoraExtra = $this->getHoraZerada();

    if($this->getDiaTrabalho()->getFeriado() instanceof Feriado) {

      if($this->getDiaTrabalho()->getFeriado()->getData()->getDate() == $this->getDiaTrabalho()->getData()->getDate()) {
        return $this->calcularExtrasFeriadoDSR($oHoraExtra);
      }
    }

    if($this->getDiaTrabalho()->getJornada()->isDSR()) {
      return $this->calcularExtrasFeriadoDSR($oHoraExtra);
    }

    if($this->getDiaTrabalho()->getJornada()->isFolga()) {
      return $this->calcularExtrasFolga($oHoraExtra);
    }

    return $this->calcularExtras($oHoraExtra);
  }

  public function calcularExtrasFeriadoDSR(\DateTime $oHoraExtra) {

    $this->totalHorasExtrasFeriadoFolga();

    $oHoraExtraNoturna = clone $this->calcularHorasExtrasFolgaFeriadoNoturna();

    if($this->getHoraExtraTotal()->format('H') == 0 && $this->getHoraExtraTotal()->format('i') == 0) {
      return $oHoraExtra;
    }

    $diferencaExtrasNoturnasETotal = $oHoraExtraNoturna->diff($this->getHoraExtraTotal());

    $oHoraExtra->setTime($diferencaExtrasNoturnasETotal->h, $diferencaExtrasNoturnasETotal->i);

    return $oHoraExtra;
  }

  public function calcularExtrasFolga(\DateTime $oHoraExtra) {

    $this->totalHorasExtrasFeriadoFolga();

    $oMaximoExtra100 = $this->verificarExistenciaHorasExtras($oHoraExtra, BaseHora::HORAS_EXTRA100);

    /**
     * Se não restou saldo para calcular horas extras retorna zerado
     */
    if(empty($oMaximoExtra100)) {
      return $this->getHoraZerada();
    }

    return $this->calcularExtras($oHoraExtra);
  }

  public function calcularExtras(\DateTime $oHoraExtra) {

    $oMaximoExtra100 = $this->verificarExistenciaHorasExtras($oHoraExtra, BaseHora::HORAS_EXTRA100);

    /**
     * Se não restou saldo para calcular horas extras retorna zerado
     */
    if(empty($oMaximoExtra100)) {
      return $this->getHoraZerada();
    }

    $oSomaHorasExtra50E75Diurnas = $this->somarHorasExtra50E75Diurnas();
    $oSomaHorasExtra50E75Diurnas->setDate(
      $this->getDiaTrabalho()->getData()->getAno(),
      $this->getDiaTrabalho()->getData()->getMes(),
      $this->getDiaTrabalho()->getData()->getDia()
    );


    // As extras 100% noturnas é a diferença do total de noturnas com a soma de extras 50 e 75 noturnas
    $oHoraExtra->setTime(
      $oSomaHorasExtra50E75Diurnas->diff($this->getHorasExtrasDiurnas())->h,
      $oSomaHorasExtra50E75Diurnas->diff($this->getHorasExtrasDiurnas())->i
    );

    return $oHoraExtra;
  }
}

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

/**
 * Classe responsável pelo cálculo de hora extra 50% de um servidor em um dia de trabalho
 * Class Extra50
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Extra50 extends HoraExtra {

  /**
   * @var \DateTime
   */
  private $oMaximoHorasExtras50;

  /**
   * Construtor da classe. Seta o tipo de hora instanciado
   */
  public function __construct(DiaTrabalho $oDiaTrabalho, $iTipoHoraExtra) {

    $this->setDiaTrabalho($oDiaTrabalho);
    $this->setTipoHora($iTipoHoraExtra);
    parent::__construct();

    $this->oMaximoHorasExtras50 = new \DateTime(
      $this->getDiaTrabalho()->getData()->getDate() .' '. $this->getConfiguracoesLotacao()->getHoraExtra50()
    );
  }

  /**
   * @return \DateTime
   */
  protected function getMaximoHorasExtras50() {
    return $this->oMaximoHorasExtras50;
  }

  /**
   * @param \DateTime $oHoraExtraPeriodo
   * @param \DateTime $oHoraExtraTotal
   * @return \DateTime|null
   */
  protected function totalHorasExtraIgualTotal(\DateTime $oHoraExtraPeriodo, \DateTime $oHoraExtraTotal) {

    $oHoraExtra = null;

    if($oHoraExtraPeriodo->format('H:i') == $oHoraExtraTotal->format('H:i')) {

      $oHoraExtra = $this->getHoraZerada();

      // Se o total de horas extras for MENOR que o máximo de horas extras 50% retorna tudo como noturna
      if($this->oMaximoHorasExtras50->diff($oHoraExtraTotal)->invert) {
        $oHoraExtra->setTime($oHoraExtraTotal->format('H'), $oHoraExtraTotal->format('i'));
      }

      // Se o total de horas extras for MAIOR que o máximo de horas extras 50% retorna o máximo de extras 50%
      if(!$this->oMaximoHorasExtras50->diff($oHoraExtraTotal)->invert) {
        $oHoraExtra->setTime($this->oMaximoHorasExtras50->format('H'), $this->oMaximoHorasExtras50->format('i'));
      }
    }

    return $oHoraExtra;
  }
}
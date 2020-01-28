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

namespace ECidade\RecursosHumanos\RH\Efetividade\Model;

/**
 * Classe com as informações sobre a jornada de trabalho
 *
 * Class Jornada
 * @package ECidade\RecursosHumanos\RH\Efetividade\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Jornada {

  const DSR   = 1;
  const FOLGA = 2;

  const TIPO_DIA_TRABALHO = 'T';
  const TIPO_FOLGA        = 'F';
  const TIPO_DSR          = 'D';

  public static $aTiposJornada = array(
    Jornada::TIPO_DIA_TRABALHO => 'Dia de Trabalho',
    Jornada::TIPO_FOLGA        => 'Folga',
    Jornada::TIPO_DSR          => 'DSR'
  );

  /**
   * Código da jornada
   * @var int
   */
  private $iCodigo;

  /**
   * Descrição da jornada
   * @var string
   */
  private $sDescricao;

  /**
   * Controla se a jornada é fixa ou configurável
   * @var bool
   */
  private $lFixo;

  /**
   * Controla se a jornada é uma folga
   * @var bool
   */
  private $lFolga;

  /**
   * Controla se a jornada é um dsr
   * @var bool
   */
  private $lDSR;

  /**
   * Controla se a jornada é um dia trabalhado
   * @var bool
   */
  private $lDiaTrabalhado;

  /**
   * Coleção com as horas configuradas para jornada
   * @var array
   */
  private $aHoras = array();

  /**
   * @var string
   */
  private $sTipoDescricao;

  /**
   * Retorna o código da jornada
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descrição da jornada
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna uma coleção de objetos com as informações das horas da jornada
   * @return \stdClass[]
   */
  public function getHoras() {
    return $this->aHoras;
  }

  /**
   * Retorna se a jornada é fixa ou configurável
   * @return bool
   */
  public function isFixo() {
    return $this->lFixo;
  }

  /**
   * Retorna se a jornada é um folga
   * @return bool
   */
  public function isFolga() {
    return $this->lFolga;
  }

  /**
   * Retorna se a jornada é um DSR
   * @return bool
   */
  public function isDSR() {
    return $this->lDSR;
  }

  /** 
   * Retorna se a jornada é um dia trabalhado
   * @return bool
   */
  public function isDiaTrabalhado() {
    return $this->lDiaTrabalhado;
  }

  /**
   * @return string
   */
  public function getTipoDescricao() {
    return $this->sTipoDescricao;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @param array $aHoras
   */
  public function setHoras($aHoras) {
    $this->aHoras = $aHoras;
  }

  /**
   * @param bool $lFixo
   */
  public function setFixo($lFixo) {
    $this->lFixo = $lFixo;
  }

  /**
   * Define se a jornada é uma folga
   * @param bool $lFolga
   */
  public function setFolga($lFolga) {
    $this->lFolga = $lFolga;
  }
  
  /**
   * Define se a jornada é um DSR
   * @param bool $lDSR
   */
  public function setDSR($lDSR) {
    $this->lDSR = $lDSR;
  }

  /**
   * Define se a jornada é um dia trabalhado
   * @param bool @lDiaTrabalhado
   */
  public function setDiaTrabalhado($lDiaTrabalhado) {
    $this->lDiaTrabalhado = $lDiaTrabalhado;
  }

  /**
   * @param $sTipoDescricao
   */
  public function setTipoDescricao($sTipoDescricao) {
    $this->sTipoDescricao = Jornada::$aTiposJornada[$sTipoDescricao];
  }

  /**
   * Ajusta a data dos horários da jornada
   */
  public function ajustarDatasJornada($dataOriginal) {

    if(count($this->aHoras) > 0) {

      foreach ($this->aHoras as $key => $horas) {

        $data = clone $dataOriginal;

        if($key > 0 && $horas->oHora->format('H:i') < $this->aHoras[0]->oHora->format('H:i')) {
          $data->adiantarPeriodo(1, 'd');
        }

        $horas->oHora->setDate($data->getAno(), $data->getMes(), $data->getDia());
      }
    }
  }

  /**
   * @return bool
   */
  public function temIntervalo() {
    return count($this->aHoras) > 2 ? true : false;
  }
}
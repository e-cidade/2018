<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBseller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\Model;

use \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa as JustificativaModel;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto as MarcacaoPontoModel;

/**
 * Class MarcacaoPontoJustificativa
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\Model
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class MarcacaoPontoJustificativa {

  /**
   * @var int
   */
  private $iCodigo;

  /**
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacaoPonto
   */
  private $oMarcacaoPonto;

  /**
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa
   */
  private $oJustificativa;

  /**
   * @var int
   */
  private $iTipo;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @return MarcacaoPontoModel
   */
  public function getMarcacaoPonto() {
    return $this->oMarcacaoPonto;
  }

  /**
   * @return JustificativaModel
   */
  public function getJustificativa() {
    return $this->oJustificativa;
  }

  /**
   * @return int
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @param MarcacaoPontoModel $oMarcacaoPonto
   */
  public function setMarcacaoPonto(MarcacaoPontoModel $oMarcacaoPonto) {
    $this->oMarcacaoPonto = $oMarcacaoPonto;
  }

  /**
   * @param JustificativaModel $oJustificativa
   */
  public function setJustificativa(JustificativaModel $oJustificativa) {
    $this->oJustificativa = $oJustificativa;
  }

  /**
   * @param int $iTipo
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }
}
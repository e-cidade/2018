<?php
/**
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
 * Class VeiculoMotorista
 */
class VeiculoMotorista {

  /**
   * @type integer
   */
  private $iCodigo;

  /**
   * @type integer
   */
  private $iCodigoCGM;

  /**
   * @type CgmFisico|CgmJuridico
   */
  private $oMotorista;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return int
   */
  public function getCodigoCGM() {
    return $this->iCodigoCGM;
  }

  /**
   * @param int $iCodigoCGM
   */
  public function setCodigoCGM($iCodigoCGM) {
    $this->iCodigoCGM = $iCodigoCGM;
  }

  /**
   * @return CgmFisico|CgmJuridico
   */
  public function getCGMMotorista() {

    if (empty($this->oMotorista) && !empty($this->iCodigo)) {
      $this->setMotorista(CgmFactory::getInstanceByCgm($this->getCodigoCGM()));
    }
    return $this->oMotorista;
  }

  /**
   * @param CgmBase  $oMotorista
   */
  public function setMotorista(CgmBase $oMotorista) {
    $this->oMotorista = $oMotorista;
  }

  /**
   * @param $iCodigo
   *
   * @return VeiculoMotorista
   * @throws DBException
   * @throws ParameterException
   */
  public static function getInstanciaPorCodigo($iCodigo) {

    if (empty($iCodigo)) {
      throw new ParameterException("Código do motorista não informado.");
    }

    $oDaoMotorista = new cl_veicmotoristas();
    $sSqlBuscaMotorista = $oDaoMotorista->sql_query_file(null, "*", null, "ve05_codigo = {$iCodigo}");
    $rsBuscaMotorista   = $oDaoMotorista->sql_record($sSqlBuscaMotorista);
    if (!empty($oDaoMotorista->erro_banco)) {
      throw new DBException("Não foi possível buscar o motorista de código {$iCodigo}.");
    }

    $oStdMotorista = db_utils::fieldsMemory($rsBuscaMotorista, 0);
    $oMotorista = new VeiculoMotorista();
    $oMotorista->setCodigo($iCodigo);
    $oMotorista->setCodigoCGM($oStdMotorista->ve05_numcgm);
    unset($oStdMotorista);
    return $oMotorista;
  }
}
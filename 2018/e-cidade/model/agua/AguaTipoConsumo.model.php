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

class AguaTipoConsumo {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var integer
   */
  private $iCodigoReceita;

  /**
   * @var integer
   */
  private $iCodigoHistorico;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    if ($iCodigo) {

      $oDaoAguaTipoConsumo = new cl_aguaconsumotipo;
      $sSql = $oDaoAguaTipoConsumo->sql_query_file($iCodigo);
      $rsDados = db_query($sSql);

      if (!$rsDados) {
        throw new DBException('Ocorreu um erro ao buscar o Tipo de Consumo.');
      }

      if (pg_num_rows($rsDados) === 0) {
        throw new BusinessException('Não foi possível encontrar o Tipo de Consumo.');
      }

      $oDados = db_utils::fieldsMemory($rsDados, 0);
      $this->iCodigo          = (integer) $oDados->x25_codconsumotipo;
      $this->iCodigoReceita   = (integer) $oDados->x25_receit;
      $this->iCodigoHistorico = (integer) $oDados->x25_codhist;
      $this->sDescricao       = $oDados->x25_descr;
    }
  }

  /**
   * @return integer $iCodigo
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return integer $iCodigoReceita
   */
  public function getCodigoReceita() {
    return $this->iCodigoReceita;
  }

  /**
   * @param integer $iCodigoReceita
   */
  public function setCodigoReceita($iCodigoReceita) {
    $this->iCodigoReceita = $iCodigoReceita;
  }

  /**
   * @return integer $iCodigoHistorico
   */
  public function getCodigoHistorico() {
    return $this->iCodigoHistorico;
  }

  /**
   * @param integer $iCodigoHistorico
   */
  public function setCodigoHistorico($iCodigoHistorico) {
    $this->iCodigoHistorico = $iCodigoHistorico;
  }

  /**
   * @return string $sDescricao
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

}
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

/**
 * Material do almoxarifado
 * @package Material
 */
class MaterialAlmoxarifado {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @var integer
   */
  private $iGrupo;

  /**
   * @var MaterialGrupo
   */
  private $oGrupo;

  /**
   * @var UnidadeMaterial
   */
  private $oUnidade;

  /**
   * @type integer
   */
  private $iUnidade;

  /**
   * @var boolean
   */
  private $lAtivo;

  /**
   * @type array
   */
  private $aPontosPedido = array();

  /**
   * @param null $iCodigo
   *
   * @throws Exception
   */
  public function __construct($iCodigo = null) {

    if (empty($iCodigo)) {
      return;
    }

    $oDaoMaterial = new cl_matmater();
    $sSqlDados = $oDaoMaterial->sql_query_com_pcmater($iCodigo);
    $rsDados = $oDaoMaterial->sql_record($sSqlDados);

    if ($oDaoMaterial->erro_status == '0') {
      throw new Exception("Erro ao buscar dados do material do almoxarifado.");
    }

    $oDados = db_utils::fieldsMemory($rsDados, 0);

    $this->iCodigo    = $iCodigo;
    $this->iGrupo     = $oDados->m68_materialestoquegrupo;
    $this->sDescricao = $oDados->m60_descr;
    $this->iUnidade   = $oDados->m60_codmatunid;
    $this->lAtivo     = $oDados->m60_ativo == 't';
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @return MaterialGrupo | void
   */
  public function getGrupo() {

    if (empty($this->oGrupo) && !empty($this->iGrupo)) {
      $this->oGrupo = new MaterialGrupo($this->iGrupo);
    }

    return $this->oGrupo;
  }

  /**
   * @param Almoxarifado $oAlmoxarifado
   *
   * @return mixed
   * @throws DBException
   */
  public function getPontoDePedidoNoAlmoxarifado(Almoxarifado $oAlmoxarifado) {

    if (!isset($this->aPontosPedido[$oAlmoxarifado->getCodigo()])) {

      $this->aPontosPedido[$oAlmoxarifado->getCodigo()] = 0;
      $oDaoMatMaterialEstoque = new cl_matmaterestoque();

      $sWhere               = "m64_matmater = {$this->getCodigo()}";
      $sWhere              .= " and m64_almox = {$oAlmoxarifado->getCodigoAlmoxarifado()}";
      $sSqlDadosPontoPedido = $oDaoMatMaterialEstoque->sql_query_file(null, "m64_pontopedido", null, $sWhere);
      $rsDadosPontoPedido   = db_query($sSqlDadosPontoPedido);

      if (!$rsDadosPontoPedido) {
        throw new DBException("Erro ao buscar ponto de pedido do item: {$this->getDescricao()}.");
      }

      if (pg_num_rows($rsDadosPontoPedido) > 0) {
        $this->aPontosPedido[$oAlmoxarifado->getCodigo()] = db_utils::fieldsMemory($rsDadosPontoPedido, 0)->m64_pontopedido;
      }
    }

    return $this->aPontosPedido[$oAlmoxarifado->getCodigo()];
  }

  /**
   * @return UnidadeMaterial
   */
  public function getUnidade() {

    if (empty($this->oUnidade) && !empty($this->iUnidade)) {
      $this->oUnidade = UnidadeMaterialRepository::getByCodigo($this->iUnidade);
    }
    return $this->oUnidade;
  }

  /**
   * @param UnidadeMaterial $oUnidade
   */
  public function setUnidade(UnidadeMaterial $oUnidade) {
    $this->oUnidade = $oUnidade;
  }

  /**
   * @return boolean
   */
  public function ativo() {
    return $this->lAtivo;
  }

  /**
   * @param boolean $lAtivo
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }

}

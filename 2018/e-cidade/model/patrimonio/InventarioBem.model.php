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
 * Model responsavel pelo BEM adicionado a um inventario
 * @author matheus.felini
 * @package patrimonio
 * @version $Revision: 1.9 $
 */
class InventarioBem {

  /**
   * Codigo do Bem dentro do inventario
   * @var integer
   */
  protected $iCodigo;

  /**
   * Inventario a qual este bem pertece
   * @var Inventario
   */
  protected $oInventario;

  /**
   * Bem
   * @var Bem
   */
  protected $oBem;

  /**
   * Departamento
   * @var DBDepartamento
   */
  protected $oDepartamento;

  /**
   * Divisao
   * @var DBDivisaoDepartamento
   */
  protected $oDivisaoDepartamento;

  /**
   * Codigo da Situacao do bem
   * @var integer
   */
  protected $iSituacao;

  /**
   * Valor que o bem pode depreciar ainda
   * @var number
   */
  protected $nValorDepreciavel;

  /**
   * Valor Residual do bem, apos a depreciacao deve ficar com este valor
   * @var number
   */
  protected $nValorResidual;

  /**
   * Vida util total do bem
   * @var integer
   */
  protected $iVidaUtil;


  /**
   * Seta as propriedades do bem dentro do inventario
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo=null) {

    $this->iCodigo = $iCodigo;
    if (!empty($this->iCodigo)) {

      $oDaoInventarioBem      = new cl_inventariobem();
      $sSqlBuscaInventarioBem = $oDaoInventarioBem->sql_query_file($this->iCodigo);
      $rsBuscaInventarioBem   = $oDaoInventarioBem->sql_record($sSqlBuscaInventarioBem);
      if ($oDaoInventarioBem->erro_status == "0") {
        throw new BusinessException(_M('patrimonial.patrimonio.InventarioBem_model.bem_nao_localizado'));
      }

      $oDadoInventarioBem         = db_utils::fieldsMemory($rsBuscaInventarioBem, 0);
      $this->iCodigo              = $oDadoInventarioBem->t77_sequencial;
      $this->oInventario          = new Inventario($oDadoInventarioBem->t77_inventario);
      $this->oBem                 = new Bem($oDadoInventarioBem->t77_bens);
      $this->oDepartamento        = new DBDepartamento($oDadoInventarioBem->t77_db_depart);
      $this->oDivisaoDepartamento = new DBDivisaoDepartamento($oDadoInventarioBem->t77_departdiv);
      $this->iSituacao            = $oDadoInventarioBem->t77_situabens;
      $this->nValorDepreciavel    = $oDadoInventarioBem->t77_valordepreciavel;
      $this->nValorResidual       = $oDadoInventarioBem->t77_valorresidual;
      $this->iVidaUtil            = $oDadoInventarioBem->t77_vidautil;
      unset($oDadoInventarioBem);
    }
  }

  /**
   * Salva os dados do bem dentro do inventario
   * @throws BusinessException
   * @return boolean
   */
  public function salvar() {

    $oDaoInventarioBem                       = new cl_inventariobem();
    $oDaoInventarioBem->t77_sequencial       = $this->getCodigo();
    $oDaoInventarioBem->t77_inventario       = $this->getInventario()->getInventario();
    $oDaoInventarioBem->t77_bens             = $this->getBem()->getCodigoBem();
    $oDaoInventarioBem->t77_situabens        = $this->getSituacao();
    $oDaoInventarioBem->t77_valordepreciavel = $this->getValorDepreciavel();
    $oDaoInventarioBem->t77_valorresidual    = $this->getValorResidual();
    $oDaoInventarioBem->t77_vidautil         = $this->getVidaUtil();
    $oDaoInventarioBem->t77_departdiv        = null;
    $oDaoInventarioBem->t77_db_depart        = null;

    if ( $this->getDivisaoDepartamento() instanceof DBDivisaoDepartamento ) {
      $oDaoInventarioBem->t77_departdiv = $this->getDivisaoDepartamento()->getCodigo() == "" ? null : $this->getDivisaoDepartamento()->getCodigo();
    }
    if ($this->getDepartamento() instanceof DBDepartamento) {
      $oDaoInventarioBem->t77_db_depart = $this->getDepartamento()->getCodigo();
    }

    if ($this->getCodigo() == "") {

      $oDaoInventarioBem->incluir(null);
      $this->iCodigo = $oDaoInventarioBem->t77_sequencial;
    } else {
      $oDaoInventarioBem->alterar($this->getCodigo());
    }

    if ($oDaoInventarioBem->erro_status == "0") {
      throw new BusinessException(_M('patrimonial.patrimonio.InventarioBem_model.nao_foi_possivel_salvar', $oDaoInventarioBem));
    }
    return true;
  }


  /**
   * Retorna Codigo do bem dentro do inventario
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta Codigo do bem dentro do Inventario
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o Inventario a qual o bem pertence
   * @return Inventario
   */
  public function getInventario() {
    return $this->oInventario;
  }

  /**
   * Seta o Inventario a qual o bem pertece
   * @param Inventario $oInventario
   */
  public function setInventario(Inventario $oInventario) {
    $this->oInventario = $oInventario;
  }

  /**
   * Retorna o BEM
   * @return Bem
   */
  public function getBem() {
    return $this->oBem;
  }

  /**
   * Seta o Bem
   * @param Bem $oBem
   */
  public function setBem(Bem $oBem) {
    $this->oBem = $oBem;
  }

  /**
   * Retorna o Departamento
   * @return DBDepartamento
   */
  public function getDepartamento() {
    return $this->oDepartamento;
  }

  /**
   * Seta o Departamento
   * @param DBDepartamento $oDepartamento
   */
  public function setDepartamento(DBDepartamento $oDepartamento) {
    $this->oDepartamento = $oDepartamento;
  }

  /**
   * Retorna a Divisao
   * @return DBDivisaoDepartamento
   */
  public function getDivisaoDepartamento() {
    return $this->oDivisaoDepartamento;
  }

  /**
   * Seta a Divisao do Bem
   * @param DBDivisaoDepartamento $oDivisaoDepartamento
   */
  public function setDivisaoDepartamento(DBDivisaoDepartamento $oDivisaoDepartamento) {
    $this->oDivisaoDepartamento = $oDivisaoDepartamento;
  }

  /**
   * Retorna a Situacao
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * Seta a Situacao
   * @param integer $iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Seta o Valor Depreciavel
   * @return float
   */
  public function getValorDepreciavel() {
    return $this->nValorDepreciavel;
  }

  /**
   * Seta o Valor Depreciavel
   * @param float $nValorDepreciavel
   */
  public function setValorDepreciavel($nValorDepreciavel) {
    $this->nValorDepreciavel = $nValorDepreciavel;
  }

  /**
   * Retorna o valor Residual
   * @return float
   */
  public function getValorResidual() {
    return $this->nValorResidual;
  }

  /**
   * Seta o valor residual
   * @param float $nValorResidual
   */
  public function setValorResidual($nValorResidual) {
    $this->nValorResidual = $nValorResidual;
  }

  /**
   * Retorna a Vida Util
   * @return integer
   */
  public function getVidaUtil() {
    return $this->iVidaUtil;
  }

  /**
   * Seta a vida util
   * @param integer $iVidaUtil
   */
  public function setVidaUtil($iVidaUtil) {
    $this->iVidaUtil = $iVidaUtil;
  }

  /**
   * Busca os dados da última reavaliação do bem, caso existam reavalizações do bem
   * @param Bem $oBem
   *
   * @return InventarioBem|null
   * @throws DBException
   */
  public static function buscaDadosDaReavaliacaoBem (Bem $oBem) {

    $oBemReavaliado    = null;
    $iCodigoBem        = $oBem->getCodigoBem();

    if (empty($iCodigoBem)) {
      throw new DBException('Erro Técnico: Código do bem não informado.');
    }

    $oDaoInventarioBem = new cl_inventariobem();
    $sWhere            = "t75_situacao = 3 and t77_bens = {$iCodigoBem}";
    $sOrder            = "t77_sequencial desc limit 1";
    $sSql              = $oDaoInventarioBem->sql_query(null,"t77_sequencial", $sOrder, $sWhere);
    $rsResultadoQuery  = db_query($sSql);

    if (!$rsResultadoQuery) {
      throw new DBException("Erro Técnico: problema ao buscar dados da reavalização do bem.");
    }

    if (pg_num_rows($rsResultadoQuery) > 0) {

      $oStdDadosReavaliacao = db_utils::fieldsMemory($rsResultadoQuery, 0);
      $oBemReavaliado       = new  InventarioBem($oStdDadosReavaliacao->t77_sequencial);
    }

    return $oBemReavaliado;
  }

}

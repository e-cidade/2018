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
 * Class VeiculoManutencao
 */
class VeiculoManutencao {

  /**
   * @type integer
   */
  protected $iCodigo;

  /**
   * @type integer
   */
  protected $iCodigoVeiculo;

  /**
   * @type Veiculo
   */
  protected $oVeiculo;

  /**
   * @type DBDate
   */
  protected $dtManutencao;

  /**
   * @type float
   */
  protected $nValorMaoDeObra;

  /**
   * @type Float
   */
  protected $nValorDePecas;

  /**
   * @type string
   */
  protected $sDescricao;

  /**
   * @type string
   */
  protected $sNotaFiscal;

  /**
   * @type float
   */
  protected $nMedida;

  /**
   * @type integer
   */
  protected $iCodigoTipoManutencao;

  /**
   * @type VeiculoTipoManutencao
   */
  protected $oTipoManutencao;

  /**
   * @type integer
   */
  protected $iCodigoUsuario;

  /**
   * @type UsuarioSistema
   */
  protected $oUsuario;

  /**
   * @type DBDate
   */
  protected $dtDataInclusao;

  /**
   * @type string
   */
  protected $sHora;

  /**
   * @type string
   */
  protected $sObservacao;

  /**
   * @type integer
   */
  protected $iNumeroDoAno;

  /**
   * @type integer
   */
  protected $iAno;

  /**
   * @type integer
   */
  protected $iCodigoCGMOficina;

  /**
   * @type CgmFisico|CgmJuridico
   */
  protected $oCGMOficina;

  /**
   * @type integer
   */
  protected $iCodigoMotorista;

  /**
   * @type CgmFisico
   */
  protected $oMotorista;

  /**
   * @type VeiculoManutencaoItem[]
   */
  protected $aManutencaoItem = array();

  const SITUACAO_PENDENTE  = 1;
  const SITUACAO_REALIZADO = 2;

  public function __construct() {

  }


  public static function getInstanciaPorCodigo($iCodigo) {

    if (empty($iCodigo)) {
      throw new ParameterException("Código da Manutenção não informado.");
    }

    $oDaoManutencao      = new cl_veicmanut();
    $sSqlBuscaManutencao = $oDaoManutencao->sql_query_file($iCodigo);
    $rsBuscaManutencao   = $oDaoManutencao->sql_record($sSqlBuscaManutencao);
    if (!$rsBuscaManutencao || $oDaoManutencao->erro_status == "0") {
      throw new BusinessException("Não foi encontrado manutenção com o código {$iCodigo}.");
    }

    $oStdManutencao              = db_utils::fieldsMemory($rsBuscaManutencao, 0);
    $oManutencao = new VeiculoManutencao();
    $oManutencao->setCodigo($oStdManutencao->ve62_codigo);
    $oManutencao->setCodigoVeiculo($oStdManutencao->ve62_veiculos);
    $oManutencao->setDataManutencao(new DBDate($oStdManutencao->ve62_dtmanut));
    $oManutencao->setDataInclusao(new DBDate($oStdManutencao->ve62_data));
    $oManutencao->setHora($oStdManutencao->ve62_hora);
    $oManutencao->setValorMaoDeObra($oStdManutencao->ve62_vlrmobra);
    $oManutencao->setValorDePecas($oStdManutencao->ve62_vlrpecas);
    $oManutencao->setDescricao($oStdManutencao->ve62_descr);
    $oManutencao->setNotaFiscal($oStdManutencao->ve62_notafisc);
    $oManutencao->setMedida($oStdManutencao->ve62_medida);
    $oManutencao->setCodigoTipoManutencao($oStdManutencao->ve62_veiccadtiposervico);
    $oManutencao->setCodigoUsuario($oStdManutencao->ve62_usuario);
    $oManutencao->setObservacao($oStdManutencao->ve62_observacao);
    $oManutencao->setNumero($oStdManutencao->ve62_numero);
    $oManutencao->setAno($oStdManutencao->ve62_anousu);
    $oManutencao->setCodigoMotorista($oStdManutencao->ve62_veicmotoristas);
    unset($oStdManutencao);
    return $oManutencao;
  }


  /**
   * @param  $iAno
   * @return integer
   * @throws Exception
   */
  public static function getProximoNumero($iAno) {

    if (empty($iAno)) {
      throw new ParameterException("Informe o ano de referência para o próximo número.");
    }

    $sCampos = "coalesce(max(ve62_numero), 0)+1 as proximo_numero";
    $sWhere  = "ve62_anousu = {$iAno}";
    $oDaoManutencao         = new cl_veicmanut();
    $sSqlBuscaProximoNumero = $oDaoManutencao->sql_query_file(null, $sCampos, null, $sWhere);
    $rsBuscaProximoNumero   = $oDaoManutencao->sql_record($sSqlBuscaProximoNumero);

    if (!$rsBuscaProximoNumero || $oDaoManutencao->erro_status == "0") {
      throw new Exception("Não foi possível buscar os dados para o próximo número.");
    }
    return db_utils::fieldsMemory($rsBuscaProximoNumero,0)->proximo_numero;
  }

  /**
   * @return bool
   * @throws BusinessException
   * @throws DBException
   */
  public function atualizarValores() {

    $oValores = $this->getValoresAtualizados();

    $oDaoManutencao = new cl_veicmanut();
    $oDaoManutencao->ve62_codigo   = $this->getCodigo();
    $oDaoManutencao->ve62_vlrmobra = $oValores->getValorMaoDeObra();
    $oDaoManutencao->ve62_vlrpecas = ($oValores->getValorPecas() + $oValores->getValorLavagem());
    $oDaoManutencao->alterar($oDaoManutencao->ve62_codigo);
    if ($oDaoManutencao->erro_status == "0") {
      throw new DBException("Não foi possível alterar os valores da manutenção.");
    }
    return true;
  }

  /**
   * @return VeiculoManutencaoValor
   * @throws BusinessException
   */
  public function getValoresAtualizados() {

    $nValorMaoDeObra = 0;
    $nValorPecas     = 0;
    $nValorLavagem   = 0;
    $aItens = $this->getItens();

    foreach ($aItens as $oItemManutencao) {

      switch ($oItemManutencao->getTipoItem()) {

        case VeiculoManutencaoItem::TIPO_SERVICO_MAO_DE_OBRA:
          $nValorMaoDeObra += $oItemManutencao->getValorTotalComDesconto();
          break;

        case VeiculoManutencaoItem::TIPO_SERVICO_PECA:
          $nValorPecas += $oItemManutencao->getValorTotalComDesconto();
          break;

        case VeiculoManutencaoItem::TIPO_SERVICO_LAVAGEM:
          $nValorLavagem += $oItemManutencao->getValorTotalComDesconto();
          break;
      }
    }

    $oManutencaoValor = new VeiculoManutencaoValor();
    $oManutencaoValor->setValorMaoDeObra($nValorMaoDeObra);
    $oManutencaoValor->setValorPecas($nValorPecas);
    $oManutencaoValor->setValorLavagem($nValorLavagem);
    return $oManutencaoValor;
  }

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
  public function getCodigoVeiculo() {
    return $this->iCodigoVeiculo;
  }

  /**
   * @param int $iCodigoVeiculo
   */
  public function setCodigoVeiculo($iCodigoVeiculo) {
    $this->iCodigoVeiculo = $iCodigoVeiculo;
  }

  /**
   * @return DBDate
   */
  public function getDataManutencao() {
    return $this->dtManutencao;
  }

  /**
   * @param DBDate $dtManutencao
   */
  public function setDataManutencao(DBDate $dtManutencao) {
    $this->dtManutencao = $dtManutencao;
  }

  /**
   * @return float
   */
  public function getValorMaoDeObra() {
    return $this->nValorMaoDeObra;
  }

  /**
   * @param float $nValorMaoDeObra
   */
  public function setValorMaoDeObra($nValorMaoDeObra) {
    $this->nValorMaoDeObra = $nValorMaoDeObra;
  }

  /**
   * @return Float
   */
  public function getValorDePecas() {
    return $this->nValorDePecas;
  }

  /**
   * @param Float $nValorDePecas
   */
  public function setValorDePecas($nValorDePecas) {
    $this->nValorDePecas = $nValorDePecas;
  }

  /**
   * @return string
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

  /**
   * @return string
   */
  public function getNotaFiscal() {
    return $this->sNotaFiscal;
  }

  /**
   * @param string $sNotaFiscal
   */
  public function setNotaFiscal($sNotaFiscal) {
    $this->sNotaFiscal = $sNotaFiscal;
  }

  /**
   * @return float
   */
  public function getMedida() {
    return $this->nMedida;
  }

  /**
   * @param float $nMedida
   */
  public function setMedida($nMedida) {
    $this->nMedida = $nMedida;
  }

  /**
   * @return int
   */
  public function getCodigoTipoManutencao() {
    return $this->iCodigoTipoManutencao;
  }

  /**
   * @param int $iCodigoTipoManutencao
   */
  public function setCodigoTipoManutencao($iCodigoTipoManutencao) {
    $this->iCodigoTipoManutencao = $iCodigoTipoManutencao;
  }

  /**
   * @return int
   */
  public function getCodigoUsuario() {
    return $this->iCodigoUsuario;
  }

  /**
   * @param int $iCodigoUsuario
   */
  public function setCodigoUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**
   * @return DBDate
   */
  public function getDataInclusao() {
    return $this->dtDataInclusao;
  }

  /**
   * @param DBDate $dtDataInclusao
   */
  public function setDataInclusao(DBDate $dtDataInclusao) {
    $this->dtDataInclusao = $dtDataInclusao;
  }

  /**
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * @param $iNumero
   */
  public function setNumero($iNumero) {
    $this->iNumeroDoAno = $iNumero;
  }

  /**
   * @return integer
   */
  public function getNumero() {
    return $this->iNumeroDoAno;
  }

  /**
   * @param $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @return integer
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @return Float
   */
  public function getValorTotalGeral() {

    $oValorAtualizado = $this->getValoresAtualizados();
    return $oValorAtualizado->getValorTotal();
  }


  /**
   * @param VeiculoTipoManutencao $oTipoManutencao
   */
  public function setTipoManutencao(VeiculoTipoManutencao $oTipoManutencao) {
    $this->oTipoManutencao = $oTipoManutencao;
  }

  public function getTipoManutencao() {

    if (empty($this->oTipoManutencao) && !empty($this->iCodigoTipoManutencao)) {
      $this->setTipoManutencao(new VeiculoTipoManutencao($this->iCodigoTipoManutencao));
    }
    return $this->oTipoManutencao;
  }

  /**
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * @return UsuarioSistema
   */
  public function getUsuario() {

    if (empty($this->oUsuario) && !empty($this->iCodigoUsuario)) {
      $this->setUsuario(UsuarioSistemaRepository::getPorCodigo($this->iCodigoUsuario));
    }
    return $this->oUsuario;
  }

  /**
   * @param Veiculo $oVeiculo
   */
  public function setVeiculo(Veiculo $oVeiculo) {
    $this->oVeiculo = $oVeiculo;
  }

  /**
   * @return Veiculo
   */
  public function getVeiculo() {

    if (empty($this->oVeiculo) && !empty($this->iCodigoVeiculo)) {
      $this->setVeiculo(new Veiculo($this->iCodigoVeiculo));
    }
    return $this->oVeiculo;
  }

  /**
   * @param $iCodigoCGM
   */
  public function setCodigoOficina($iCodigoCGM) {
    $this->iCodigoCGMOficina = $iCodigoCGM;
  }

  /**
   * @param CgmBase $oCGM
   */
  public function setOficina(CgmBase $oCGM) {
    $this->oCGMOficina = $oCGM;
  }

  /**
   * @return CgmFisico|CgmJuridico
   * @throws Exception
   */
  public function getOficina() {

    if (empty($this->oCGMOficina)) {

      $oDaoBuscaOficina = new cl_veicmanutoficina();
      $sSqlBuscaOficina = $oDaoBuscaOficina->sql_query(null, 've27_numcgm', null, 've66_veicmanut = '.$this->iCodigo);
      $rsBuscaOficina   = $oDaoBuscaOficina->sql_record($sSqlBuscaOficina);

      if ($oDaoBuscaOficina->numrows == 0) {
        throw new Exception("Código da oficina não informado.");
      }

      $iCodigoCGM = db_utils::fieldsMemory($rsBuscaOficina, 0)->ve27_numcgm;
      $this->setOficina(CgmFactory::getInstanceByCgm($iCodigoCGM));
    }
    return $this->oCGMOficina;
  }

  /**
   * @return array|VeiculoManutencaoItem[]
   * @throws BusinessException
   * @throws ParameterException
   */
  public function getItens() {

    $this->aManutencaoItem = array();
    if (empty($this->iCodigo)) {

      return array();
    }

    $oDaoItem    = new cl_veicmanutitem();
    $sSqlItem    = $oDaoItem->sql_query_file(null, 've63_codigo', 've63_codigo', 've63_veicmanut = '.$this->iCodigo);
    $rsBuscaItem = $oDaoItem->sql_record($sSqlItem);

    if (!empty($oDaoItem->erro_banco)) {
      throw new BusinessException("Ocorreu um erro ao buscar os itens da manutenção {$this->iCodigo}.");
    }

    for ($iRowItem = 0; $iRowItem < $oDaoItem->numrows; $iRowItem++) {
      $this->aManutencaoItem[] = VeiculoManutencaoItem::getInstanciaPorCodigo(db_utils::fieldsMemory($rsBuscaItem, $iRowItem)->ve63_codigo);
    }
    return $this->aManutencaoItem;
  }

  /**
   * Retorna o percentual de desconto aplicado sobre as peças da manutenção
   * @return integer|null
   */
  public function getPercentualDesconto() {

    if (!$this->getCodigo()) {
      throw new BusinessException("Manutenção não cadastrada.");
    }

    $oDaoItem = new cl_veicmanutitem();
    $sSqlItem = $oDaoItem->sql_query_file( null,
                                           "ve63_codigo",
                                           null,
                                           "ve63_veicmanut = {$this->iCodigo} and ve63_tipoitem = " . VeiculoManutencaoItem::TIPO_SERVICO_PECA);
    $rsBuscaItem = $oDaoItem->sql_record("{$sSqlItem} limit 1");

    if (!empty($oDaoItem->erro_banco)) {
      throw new BusinessException("Ocorreu um erro ao buscar os itens da manutenção {$this->iCodigo}.");
    }

    if (!$oDaoItem->numrows) {
      return null;
    }

    $oItemManutencao = VeiculoManutencaoItem::getInstanciaPorCodigo( db_utils::fieldsMemory($rsBuscaItem, 0)->ve63_codigo );
    $nValorDesconto  = $oItemManutencao->getValorTotal() - $oItemManutencao->getValorTotalComDesconto();

    return round($nValorDesconto*100/$oItemManutencao->getValorTotal(), 2);
  }

  /**
   * @param integer $iCodigoMotorista
   */
  public function setCodigoMotorista($iCodigoMotorista) {
    $this->iCodigoMotorista = $iCodigoMotorista;
  }

  /**
   * @param VeiculoMotorista $oMotorista
   */
  public function setMotorista(VeiculoMotorista $oMotorista) {

    $this->setCodigoMotorista($oMotorista->getCodigo());
    $this->oMotorista = $oMotorista;
  }

  /**
   * @return VeiculoMotorista
   * @throws DBException
   * @throws ParameterException
   */
  public function getMotorista() {

    if (!empty($this->oMotorista)) {
      return $this->oMotorista;
    }

    $oDaoVeiculoRetirada = new cl_veicmanutretirada();
    $sSqlBuscaRetirada   = $oDaoVeiculoRetirada->sql_query_file(null, 've65_veicretirada', null, 've65_veicmanut = '.$this->iCodigo);
    $rsBuscaRetirada     = $oDaoVeiculoRetirada->sql_record($sSqlBuscaRetirada);
    if ($oDaoVeiculoRetirada->numrows == 1) {

      $oRetirada = RetiradaVeiculo::getInstanciaPorCodigo(db_utils::fieldsMemory($rsBuscaRetirada, 0)->ve65_veicretirada);
      $this->setMotorista($oRetirada->getMotorista());

    } else {
      $this->setMotorista(VeiculoMotorista::getInstanciaPorCodigo($this->iCodigoMotorista));
    }
    return $this->oMotorista;
  }
}
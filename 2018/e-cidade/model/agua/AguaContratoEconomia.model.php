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

use ECidade\Tributario\Agua\Repository\Isencao as IsencaoRepository;

class AguaContratoEconomia {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var integer
   */
  private $iCodigoContrato;

  /**
   * @var integer
   */
  private $iCodigoCgm;

  /**
   * @var integer
   */
  private $iCodigoCategoriaConsumo;

  /**
   * @var DBDate
   */
  private $oDataValidadeCadastro;

  /**
   * @var string
   */
  private $sNis;

  /**
   * @var AguaContrato
   */
  private $oContrato;

  /**
   * @var CgmBase
   */
  private $oCgm;

  /**
   * @var AguaCategoriaConsumo
   */
  private $oCategoriaConsumo;

  /**
   * @var cl_aguacontratoeconomia
   */
  private $oDaoAguaContratoEconomia;

  /**
   * @var cl_aguacondominiomat
   */
  private $oDaoAguaCondominioMatricula;

  /**
   * @var IsencaoRepository
   */
  private $oIsencaoRepository;

  /**
   * @var string
   */
  private $sComplemento;

  /**
   * @var string
   */
  private $sObservacoes;

  /**
   * @var boolean
   */
  private $lEmitirOutrosDebitos;

  public function __construct() {
    $this->oIsencaoRepository = new IsencaoRepository();
  }

  /**
   * @param cl_aguacontratoeconomia $oDaoAguaContratoEconomia
   */
  public function setDaoAguaContratoEconomia(cl_aguacontratoeconomia $oDaoAguaContratoEconomia) {
    $this->oDaoAguaContratoEconomia = $oDaoAguaContratoEconomia;
  }

  /**
   * @return cl_aguacontratoeconomia
   */
  public function getDaoAguaContratoEconomia() {

    if (!$this->oDaoAguaContratoEconomia) {
      $this->oDaoAguaContratoEconomia = new cl_aguacontratoeconomia;
    }

    return $this->oDaoAguaContratoEconomia;
  }

  /**
   * @return cl_aguacondominiomat
   */
  public function getDaoAguaCondominioMatricula() {

    if (!$this->oDaoAguaCondominioMatricula) {
      $this->oDaoAguaCondominioMatricula = new cl_aguacondominiomat;
    }

    return $this->oDaoAguaCondominioMatricula;
  }

  /**
   * @param cl_aguacondominiomat $oDaoAguaCondominioMatricula
   */
  public function setDaoAguaCondominio(cl_aguacondominiomat $oDaoAguaCondominioMatricula) {
    $this->oDaoAguaCondominioMatricula = $oDaoAguaCondominioMatricula;
  }

  /**
   * @param integer $iCodigo
   *
   * @throws BusinessException
   * @throws ParameterException
   */
  public function carregar($iCodigo) {

    if (empty($iCodigo)) {
      throw new ParameterException('Código não informado.');
    }

    $oDaoAguaContratoEconomia = $this->getDaoAguaContratoEconomia();
    $sSql    = $oDaoAguaContratoEconomia->sql_query_file($iCodigo);
    $rsDados = db_query($sSql);
    if (pg_num_rows($rsDados) === 0) {
      throw new BusinessException('Economia não encontrada.');
    }

    $oDados = db_utils::fieldsMemory($rsDados, 0);

    $this->iCodigo                 = (integer) $oDados->x38_sequencial;
    $this->iCodigoContrato         = (integer) $oDados->x38_aguacontrato;
    $this->iCodigoCgm              = (integer) $oDados->x38_cgm;
    $this->iCodigoCategoriaConsumo = (integer) $oDados->x38_aguacategoriaconsumo;
    $this->sNis                    = $oDados->x38_nis;
    $this->sComplemento            = $oDados->x38_complemento;
    $this->lEmitirOutrosDebitos    = $oDados->x38_emitiroutrosdebitos == 't';
    $this->sObservacoes            = $oDados->x38_observacoes;

    if ($oDados->x38_datavalidadecadastro) {
      $this->oDataValidadeCadastro = new DBDate($oDados->x38_datavalidadecadastro);
    }

  }

  /**
   * @return int
   * @throws BusinessException
   * @throws DBException
   */
  public function salvar() {

    if (empty($this->iCodigoContrato)) {
      throw new BusinessException('O Código do Contrato não foi informado.');
    }

    if (empty($this->iCodigoCgm)) {
      throw new BusinessException('O campo Nome/Razão Social é de preenchimento obrigatório.');
    }

    if (empty($this->iCodigoCategoriaConsumo)) {
      throw new BusinessException('O campo Categoria de Consumo é de preenchimento obrigatório.');
    }

    $oDaoAguaContratoEconomia = $this->getDaoAguaContratoEconomia();
    $oDaoAguaContratoEconomia->x38_sequencial           = (integer) $this->iCodigo;
    $oDaoAguaContratoEconomia->x38_aguacontrato         = (integer) $this->iCodigoContrato;
    $oDaoAguaContratoEconomia->x38_cgm                  = (integer) $this->iCodigoCgm;
    $oDaoAguaContratoEconomia->x38_aguacategoriaconsumo = (integer) $this->iCodigoCategoriaConsumo;
    $oDaoAguaContratoEconomia->x38_nis                  = $this->sNis;
    $oDaoAguaContratoEconomia->x38_complemento          = $this->sComplemento;
    $oDaoAguaContratoEconomia->x38_observacoes          = $this->sObservacoes;
    $oDaoAguaContratoEconomia->x38_datavalidadecadastro = null;
    $oDaoAguaContratoEconomia->x38_emitiroutrosdebitos  = $this->lEmitirOutrosDebitos === true ? 't' : 'f';

    if ($this->oDataValidadeCadastro) {
      $oDaoAguaContratoEconomia->x38_datavalidadecadastro = $this->oDataValidadeCadastro->getDate();
    }

    if ($this->iCodigo) {
      $oDaoAguaContratoEconomia->alterar($this->iCodigo);
    } else {
      $oDaoAguaContratoEconomia->incluir(null);
      $this->iCodigo = $oDaoAguaContratoEconomia->x38_sequencial;
    }

    if ($oDaoAguaContratoEconomia->erro_status == 0) {
      throw new DBException('Não foi possível salvar a Economia.');
    }

    return $this->iCodigo;
  }

  /**
   * @throws DBException
   * @return boolean
   */
  public function excluir() {

    $oDaoAguaContratoEconomia = $this->getDaoAguaContratoEconomia();
    $oDaoAguaContratoEconomia->excluir($this->iCodigo);

    if ($oDaoAguaContratoEconomia->erro_status == '0') {
      throw new DBException('Não foi possível excluir a Economia.');
    }

    return true;
  }

  /**
   * @param integer $iContrato
   * @param integer $iCategoriaConsumo
   *
   * @return bool
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   * @internal param int $iCodigoMatricula Código da matrícula do condomínio
   */
  public function importarEconomias($iContrato, $iCategoriaConsumo) {

    if (!db_utils::inTransaction()) {
      throw new DBException('Transação não iniciada.');
    }

    if (empty($iContrato)) {
      throw new ParameterException('Contrato não informado.');
    }

    $oContrato        = new AguaContrato($iContrato);
    $iCodigoMatricula = $oContrato->getCodigoMatricula();

    if (empty($iCodigoMatricula)) {
      throw new BusinessException('Não é possível realizar a importação. A Matrícula do condomínio não foi informada.');
    }

    if (count($oContrato->getEconomias()) > 0) {
      throw new BusinessException('Não é possível realizar a importação. Já existem economias cadastradas.');
    }

    if (empty($iCategoriaConsumo)) {
      throw new ParameterException('Categoria de Consumo não informada.');
    }

    $oDaoAguaCondominioMatricula = $this->getDaoAguaCondominioMatricula();
    $sWhere       = "x31_matric = {$iCodigoMatricula}";
    $sSql         = $oDaoAguaCondominioMatricula->sql_query(null, null, 'x40_matric', null, $sWhere);
    $rsMatriculas = db_query($sSql);

    if (!$rsMatriculas) {
      throw new DBException('Não foi possível procurar as matrículas.');
    }

    $iQuantidadeMatriculas = pg_num_rows($rsMatriculas);
    if ($iQuantidadeMatriculas === 0) {
      throw new BusinessException('Nenhuma Matrícula encontrada.');
    }

    for ($iMatricula = 0; $iMatricula < $iQuantidadeMatriculas; $iMatricula++) {

      $iCodigoMatricula = db_utils::fieldsMemory($rsMatriculas, $iMatricula)->x40_matric;
      $oMatricula = new AguaMatricula($iCodigoMatricula);

      $oAguaContratoEconomia = new AguaContratoEconomia;
      $oAguaContratoEconomia->setCodigoContrato($iContrato);
      $oAguaContratoEconomia->setCodigoCgm($oMatricula->getCodigoProprietario());
      $oAguaContratoEconomia->setCodigoCategoriaConsumo($iCategoriaConsumo);
      if ($oMatricula->getCodigoPromitente()) {
        $oAguaContratoEconomia->setCodigoCgm($oMatricula->getCodigoPromitente());
      }
      $oAguaContratoEconomia->salvar();
    }

    return true;
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
   * @return integer $iCodigoContrato
   */
  public function getCodigoContrato() {
    return $this->iCodigoContrato;
  }

  /**
   * @param integer $iCodigoContrato
   */
  public function setCodigoContrato($iCodigoContrato) {
    $this->iCodigoContrato = $iCodigoContrato;
  }

  /**
   * @return integer $iCodigoCgm
   */
  public function getCodigoCgm() {
    return $this->iCodigoCgm;
  }

  /**
   * @param integer $iCodigoCgm
   */
  public function setCodigoCgm($iCodigoCgm) {
    $this->iCodigoCgm = $iCodigoCgm;
  }

  /**
   * @return integer $iCodigoCategoriaConsumo
   */
  public function getCodigoCategoriaConsumo() {
    return $this->iCodigoCategoriaConsumo;
  }

  /**
   * @param integer $iCodigoCategoriaConsumo
   */
  public function setCodigoCategoriaConsumo($iCodigoCategoriaConsumo) {
    $this->iCodigoCategoriaConsumo = $iCodigoCategoriaConsumo;
  }

  /**
   * @return DBDate $oDataValidadeCadastro
   */
  public function getDataValidadeCadastro() {
    return $this->oDataValidadeCadastro;
  }

  /**
   * @param DBDate $oDataValidadeCadastro
   */
  public function setDataValidadeCadastro(DBDate $oDataValidadeCadastro = null) {
    $this->oDataValidadeCadastro = $oDataValidadeCadastro;
  }

  /**
   * @return string $sNis
   */
  public function getNis() {
    return $this->sNis;
  }

  /**
   * @param string $sNis
   */
  public function setNis($sNis) {
    $this->sNis = $sNis;
  }

  /**
   * @param bool $lEmitirOutrosDebitos
   */
  public function setEmitirOutrosDebitos($lEmitirOutrosDebitos) {
    $this->lEmitirOutrosDebitos = $lEmitirOutrosDebitos;
  }

  /**
   * @return bool
   */
  public function getEmitirOutrosDebitos() {
    return $this->lEmitirOutrosDebitos;
  }

  /**
   * @return AguaContrato $oContrato
   */
  public function getContrato() {

    if (!$this->oContrato && $this->iCodigoContrato) {
      $this->oContrato = new AguaContrato($this->iCodigoContrato);
    }

    return $this->oContrato;
  }

  /**
   * @param AguaContrato $oContrato
   */
  public function setContrato(AguaContrato $oContrato) {

    $this->iCodigoContrato = $oContrato->getCodigo();
    $this->oContrato = $oContrato;
  }

  /**
   * @return CgmBase $oCgm
   */
  public function getCgm() {

    if (!$this->oCgm && $this->iCodigoCgm) {
      $this->oCgm = CgmFactory::getInstanceByCgm($this->iCodigoCgm);
    }

    return $this->oCgm;
  }

  /**
   * @param CgmBase $oCgm
   */
  public function setCgm(CgmBase $oCgm) {

    $this->iCodigoCgm = $oCgm->getCodigo();
    $this->oCgm = $oCgm;
  }

  /**
   * @return string
   */
  public function getObservacoes() {
    return $this->sObservacoes;
  }

  /**
   * @param string $sObservacoes
   */
  public function setObservacoes($sObservacoes) {
    $this->sObservacoes = $sObservacoes;
  }

  /**
   * @param $sComplemento
   */
  public function setComplemento($sComplemento) {
    $this->sComplemento = $sComplemento;
  }

  /**
   * @return string
   */
  public function getComplemento() {
    return $this->sComplemento;
  }

  /**
   * @return AguaCategoriaConsumo $oCategoriaConsumo
   */
  public function getCategoriaConsumo() {

    if (!$this->oCategoriaConsumo && $this->iCodigoCategoriaConsumo) {
      $this->oCategoriaConsumo = new AguaCategoriaConsumo($this->iCodigoCategoriaConsumo);
    }

    return $this->oCategoriaConsumo;
  }

  /**
   * @param AguaCategoriaConsumo $oCategoriaConsumo
   */
  public function setCategoriaConsumo(AguaCategoriaConsumo $oCategoriaConsumo) {

    $this->iCodigoCategoriaConsumo = $oCategoriaConsumo->getCodigo();
    $this->oCategoriaConsumo = $oCategoriaConsumo;
  }

  /**
   * Se existir isenção válida, retorna uma instância do model.
   *
   * @return AguaIsencaoCgm|null
   * @throws ParameterException
   */
  public function getIsencao() {

    if (!$this->iCodigoCgm) {
      throw new ParameterException('CGM não informado.');
    }

    return $this->oIsencaoRepository->getIsencaoValida($this->iCodigoCgm);
  }

  /**
   * @return IsencaoRepository
   */
  public function getIsencaoRepository() {
    return $this->oIsencaoRepository;
  }

  /**
   * @param IsencaoRepository $oIsencaoRepository
   */
  public function setIsencaoRepository($oIsencaoRepository) {
    $this->oIsencaoRepository = $oIsencaoRepository;
  }
}

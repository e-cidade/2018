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

class AguaHidrometro
{
  /**
   * @var integer Código do Hidrômetro
   */
  private $iCodigo;

  /**
   * @var integer Código da Marca
   */
  private $iCodigoMarca;

  /**
   * @var integer Código da Matrícula
   */
  private $iCodigoMatricula;

  /**
   * @var integer Código do Hiâmetro
   */
  private $iCodigoDiametro;

  /**
   * @var integer Número do Hidrômetro
   */
  private $sNumero;

  /**
   * @var integer Leitura Inicial
   */
  private $iLeituraInicial;

  /**
   * @var DBDate Data da Instalação
   */
  private $oDataInstalacao;

  /**
   * @var integer Quantidade de Dígitos
   */
  private $iQuantidadeDigitos;

  /**
   * @var string Aviso p/ o Leiturista
   */
  private $sAvisoLeiturista;

  /**
   * @var string Observação Durante Instalação
   */
  private $sObservacao;

  /**
   * AguaHidrometro constructor.
   * @param integer|null $iCodigo
   * @throws DBException
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (!$this->iCodigo) {
      return;
    }

    $oDaoAguaHidroMatric = new cl_aguahidromatric();
    $sSqlAguaHidroMatric = $oDaoAguaHidroMatric->sql_query_file($this->getCodigo());
    $rsAguaHidroMatric   = db_query($sSqlAguaHidroMatric);

    if (!$rsAguaHidroMatric || pg_num_rows($rsAguaHidroMatric) == 0) {
      throw new DBException("Não foi possível encontrar o Hidrômetro.");
    }

    $oAguaHidrometro = db_utils::fieldsMemory($rsAguaHidroMatric, 0);

    $this->iCodigoMarca       = $oAguaHidrometro->x04_codmarca;
    $this->sNumero            = $oAguaHidrometro->x04_nrohidro;
    $this->iQuantidadeDigitos = $oAguaHidrometro->x04_qtddigito;
    $this->iCodigoDiametro    = $oAguaHidrometro->x04_coddiametro;
    $this->iCodigoMatricula   = $oAguaHidrometro->x04_matric;
    $this->iLeituraInicial    = $oAguaHidrometro->x04_leitinicial;
    $this->sObservacao        = $oAguaHidrometro->x04_observacao;
    $this->sAvisoLeiturista   = $oAguaHidrometro->x04_avisoleiturista;

    if ($oAguaHidrometro->x04_dtinst) {
      $this->oDataInstalacao = new DBDate($oAguaHidrometro->x04_dtinst);
    }
  }

  /**
   * @return int
   * @throws DBException
   */
  public function salvar() {

    $oDaoAguaHidroMatric = new cl_aguahidromatric();
    $oDaoAguaHidroMatric->x04_codmarca        = $this->getCodigoMarca();
    $oDaoAguaHidroMatric->x04_nrohidro        = $this->getNumero();
    $oDaoAguaHidroMatric->x04_qtddigito       = $this->getQuantidadeDigitos();
    $oDaoAguaHidroMatric->x04_coddiametro     = $this->getCodigoDiametro();
    $oDaoAguaHidroMatric->x04_matric          = $this->getCodigoMatricula();
    $oDaoAguaHidroMatric->x04_leitinicial     = $this->getLeituraInicial();
    $oDaoAguaHidroMatric->x04_avisoleiturista = $this->getAvisoLeiturista();
    $oDaoAguaHidroMatric->x04_observacao      = $this->getObservacao();

    if ($this->getDataInstalacao()) {
      $oDaoAguaHidroMatric->x04_dtinst = $this->getDataInstalacao()->getDate();
    }

    if ($this->getCodigo()) {
      $oDaoAguaHidroMatric->x04_codhidrometro = $this->getCodigo();
      $oDaoAguaHidroMatric->alterar($this->getCodigo());
    }

    if (!$this->getCodigo()) {
      $oDaoAguaHidroMatric->incluir(null);
    }

    if ($oDaoAguaHidroMatric->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações do Hidrômetro.");
    }

    $this->setCodigo($oDaoAguaHidroMatric->x04_codhidrometro);

    return $oDaoAguaHidroMatric->x04_codhidrometro;
  }

  /**
   * @return bool
   * @throws DBException
   * @throws ParameterException
   */
  public function excluir() {

    if (!$this->getCodigo()) {
      throw new ParameterException('Código do Hidrômetro não informado.');
    }

    $oDaoAguaHidroMatric = new cl_aguahidromatric();
    $oDaoAguaHidroMatric->excluir($this->getCodigo());

    if ($oDaoAguaHidroMatric->erro_status == '0') {
      throw new DBException("Não foi possível excluir o Hidrômetro.");
    }

    return true;
  }

  /**
   * @param $iMes
   * @param $iAno
   * @return int
   * @throws DBException
   */
  public function calcularConsumoMes($iMes, $iAno) {

    $sCampos = 'coalesce(sum(x21_consumo) + sum(case when x21_excesso > 0 then x21_excesso else 0 end), 0) as consumo';
    $sJoin   = 'inner join aguahidromatric on x21_codhidrometro = x04_codhidrometro';
    $sWhere = implode(' and ', array(
      "x21_codhidrometro = {$this->getCodigo()}",
      "x21_exerc = {$iAno}",
      "x21_mes  = {$iMes}",
      "x21_status = " . AguaLeitura::STATUS_ATIVA
    ));

    $sSqlConsumo = "select {$sCampos} from agualeitura {$sJoin} where {$sWhere}";
    $rsConsumo = db_query($sSqlConsumo);

    if (!$rsConsumo) {
      throw new DBException('Não foi possível calcular o consumo do mês/ano.');
    }

    $oConsumo = pg_fetch_object($rsConsumo);
    return (integer) $oConsumo->consumo;
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
  public function getCodigoMarca() {
    return $this->iCodigoMarca;
  }

  /**
   * @param int $iCodigoMarca
   */
  public function setCodigoMarca($iCodigoMarca) {
    $this->iCodigoMarca = $iCodigoMarca;
  }

  /**
   * @return int
   */
  public function getCodigoMatricula() {
    return $this->iCodigoMatricula;
  }

  /**
   * @param int $iCodigoMatricula
   */
  public function setCodigoMatricula($iCodigoMatricula) {
    $this->iCodigoMatricula = $iCodigoMatricula;
  }

  /**
   * @return int
   */
  public function getCodigoDiametro() {
    return $this->iCodigoDiametro;
  }

  /**
   * @param int $iCodigoDiametro
   */
  public function setCodigoDiametro($iCodigoDiametro) {
    $this->iCodigoDiametro = $iCodigoDiametro;
  }

  /**
   * @return int
   */
  public function getNumero() {
    return $this->sNumero;
  }

  /**
   * @param int $sNumero
   */
  public function setNumero($sNumero) {
    $this->sNumero = $sNumero;
  }

  /**
   * @return int
   */
  public function getLeituraInicial() {
    return $this->iLeituraInicial;
  }

  /**
   * @param int $iLeituraInicial
   */
  public function setLeituraInicial($iLeituraInicial) {
    $this->iLeituraInicial = $iLeituraInicial;
  }

  /**
   * @return DBDate
   */
  public function getDataInstalacao() {
    return $this->oDataInstalacao;
  }

  /**
   * @param DBDate $oDataInstalacao
   */
  public function setDataInstalacao($oDataInstalacao) {
    $this->oDataInstalacao = $oDataInstalacao;
  }

  /**
   * @return int
   */
  public function getQuantidadeDigitos() {
    return $this->iQuantidadeDigitos;
  }

  /**
   * @param int $iQuantidadeDigitos
   */
  public function setQuantidadeDigitos($iQuantidadeDigitos) {
    $this->iQuantidadeDigitos = $iQuantidadeDigitos;
  }

  /**
   * @return string
   */
  public function getAvisoLeiturista() {
    return $this->sAvisoLeiturista;
  }

  /**
   * @param string $sAvisoLeiturista
   */
  public function setAvisoLeiturista($sAvisoLeiturista) {
    $this->sAvisoLeiturista = $sAvisoLeiturista;
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
}

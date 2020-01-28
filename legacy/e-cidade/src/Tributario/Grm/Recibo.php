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

namespace ECidade\Tributario\Grm;

/**
 * Class Recibo
 * @package ECidade\Tributario\Grm
 */
class Recibo {

  /**
   * Código do Recibo
   * @var integer
   */
  protected $codigo;

  /**
   * @var \CgmFisico|\CgmJuridico
   */
  protected $cgm;

  /**
   * @var UnidadeGestora
   */
  protected $unidadeGestora;

  /**
   * @var TipoRecolhimento
   */
  protected $tipoRecolhimento;

  /**
   * Numpre do Recibo Gerado
   * @var integer
   */
  protected $numpre;

  /**
   * @var \DBCompetencia
   */
  protected $competencia;

  /**
   * @var \DBDate
   */
  protected $dataEmissao;

  /**
   * @var \DBDate
   */
  protected $dataVencimento;

  /**
   * número de Referência
   * @var string
   */
  protected $numeroReferencia;

  /**
   * @var float;
   */
  protected $valor = 0;

  /**
   * @var float;
   */
  protected $valorDesconto = 0;

  /**
   * @var float;
   */
  protected $valorMulta = 0;

  /**
   * @var float;
   */
  protected $valorJuros = 0;

  /**
   * @var float;
   */
  protected $valorOutrosAcrescimento = 0;

  /**
   * @var float
   */
  protected $valorTotal = 0;

  /**
   *
   * Outras deduçoes
   * @var float
   */
  protected $valorOutrasDeducoes =0;
  /**
   * @var \DBDate
   */
  protected $dataPagamento;

  /**
   * @var \DBDate
   */
  protected $data;

  /**
   * @var \processoProtocolo
   */
  protected $processo;

  protected $atributos = array();

  /**
   * @var string
   */
  protected $linhaDigitavel;

  /**
   * @var string
   */
  protected $codigoBarras;

  /**
   * @var \Cidadao
   */
  protected $cidadao;

  /**
   * @param \Cidadao $cidadao
   */
  public function setCidadao(\Cidadao $cidadao) {
    $this->cidadao = $cidadao;
  }

  /**
   * @return \Cidadao
   */
  public function getCidadao() {
    return $this->cidadao;
  }

  /**
   * @return string
   */
  public function getLinhaDigitavel() {
    return $this->linhaDigitavel;
  }

  /**
   * @param string $linhaDigitavel
   */
  public function setLinhaDigitavel($linhaDigitavel) {
    $this->linhaDigitavel = $linhaDigitavel;
  }

  /**
   * @return string
   */
  public function getCodigoBarras() {
    return $this->codigoBarras;
  }

  /**
   * @param string $codigoBarras
   */
  public function setCodigoBarras($codigoBarras) {
    $this->codigoBarras = $codigoBarras;
  }

  public function __construct() {

  }

  /**
   * @return \CgmFisico|\CgmJuridico
   */
  public function getCgm() {
    return $this->cgm;
  }

  /**
   * @param \CgmFisico|\CgmJuridico|\CgmBase $cgm
   */
  public function setCgm(\CgmBase $cgm) {
    $this->cgm = $cgm;
  }

  /**
   * @return \ECidade\Tributario\Grm\UnidadeGestora
   */
  public function getUnidadeGestora() {
    return $this->unidadeGestora;
  }

  /**
   * @param \ECidade\Tributario\Grm\UnidadeGestora $unidadeGestora
   */
  public function setUnidadeGestora(UnidadeGestora $unidadeGestora) {
    $this->unidadeGestora = $unidadeGestora;
  }

  /**
   * @return \ECidade\Tributario\Grm\TipoRecolhimento
   */
  public function getTipoRecolhimento() {
    return $this->tipoRecolhimento;
  }

  /**
   * @param \ECidade\Tributario\Grm\TipoRecolhimento $tipoRecolhimento
   */
  public function setTipoRecolhimento(TipoRecolhimento $tipoRecolhimento) {
    $this->tipoRecolhimento = $tipoRecolhimento;
  }

  /**
   * @return int
   */
  public function getNumpre() {
    return $this->numpre;
  }

  /**
   * @param int $numpre
   */
  public function setNumpre($numpre) {
    $this->numpre = $numpre;
  }

  /**
   * Retorna o código de arrecadação
   * @return string
   */
  public function getCodigoArrecadacao() {

    $iNumpreFormatado = db_sqlformatar($this->numpre, 8, '0') . '000999';
    return $iNumpreFormatado . db_CalculaDV($iNumpreFormatado,11);
  }

  /**
   * @return \DBCompetencia
   */
  public function getCompetencia() {
    return $this->competencia;
  }

  /**
   * @param \DBCompetencia $competencia
   */
  public function setCompetencia($competencia) {
    $this->competencia = $competencia;
  }

  /**
   * @return \DBDate
   */
  public function getDataEmissao() {
    return $this->dataEmissao;
  }

  /**
   * @param \DBDate $dataEmissao
   */
  public function setDataEmissao($dataEmissao) {
    $this->dataEmissao = $dataEmissao;
  }

  /**
   * @return string
   */
  public function getNumeroReferencia() {
    return $this->numeroReferencia;
  }

  /**
   * @param string $numeroReferencia
   */
  public function setNumeroReferencia($numeroReferencia) {
    $this->numeroReferencia = $numeroReferencia;
  }

  /**
   * @return float
   */
  public function getValor() {
    return $this->valor;
  }

  /**
   * @param float $valor
   */
  public function setValor($valor) {
    $this->valor = $valor;
  }

  /**
   * @return float
   */
  public function getValorDesconto() {
    return $this->valorDesconto;
  }

  /**
   * @param float $valorDesconto
   */
  public function setValorDesconto($valorDesconto) {
    $this->valorDesconto = $valorDesconto;
  }

  /**
   * @return float
   */
  public function getValorMulta() {
    return $this->valorMulta;
  }

  /**
   * @param float $valorMulta
   */
  public function setValorMulta($valorMulta) {
    $this->valorMulta = $valorMulta;
  }

  /**
   * @return float
   */
  public function getValorJuros() {
    return $this->valorJuros;
  }

  /**
   * @param float $valorJuros
   */
  public function setValorJuros($valorJuros) {
    $this->valorJuros = $valorJuros;
  }

  /**
   * @return float
   */
  public function getValorOutrosAcrescimento() {
    return $this->valorOutrosAcrescimento;
  }

  /**
   * @param float $valorOutrosAcrescimento
   */
  public function setValorOutrosAcrescimento($valorOutrosAcrescimento) {
    $this->valorOutrosAcrescimento = $valorOutrosAcrescimento;
  }

  /**
   * @return int
   */
  public function getValorTotal() {
    return $this->valorTotal;
  }

  /**
   * @param int $valorTotal
   */
  public function setValorTotal($valorTotal) {
    $this->valorTotal = $valorTotal;
  }

  /**
   * @param \DBDate $dataVencimento
   */
  public function setDataVencimento(\DBDate $dataVencimento) {
    $this->dataVencimento = $dataVencimento;
  }

  /**
   * @return \DBDate
   */
  public function getDataVencimento() {
    return $this->dataVencimento;
  }

  /**
   * @return \DBDate
   */
  public function getDataPagamento() {
    return $this->dataPagamento;
  }

  /**
   * @param \DBDate $dataPagamento
   */
  public function setDataPagamento(\DBDate $dataPagamento) {
    $this->dataPagamento = $dataPagamento;
  }

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->codigo;
  }

  /**
   * @param int $codigo
   */
  public function setCodigo($codigo) {
    $this->codigo = $codigo;
  }

  /**
   * @return \processoProtocolo
   */
  public function getProcesso() {

    if (empty($this->processo) && !empty($this->data->k174_processo)) {
      $this->processo = new \processoProtocolo($this->data->k174_processo);
    }
    return $this->processo;
  }

  /**
   * @param \processoProtocolo $processo
   */
  public function setProcesso($processo) {
    $this->processo = $processo;
  }

  /**
   * Define os dadso  do objeto
   * @param $data
   */
  public function setPlainData($data) {
    $this->data = $data;
  }

  /**
   * @return float
   */
  public function getValorOutrasDeducoes() {
    return $this->valorOutrasDeducoes;
  }

  /**
   * @param float $valorOutrasDeducoes
   */
  public function setValorOutrasDeducoes($valorOutrasDeducoes) {
    $this->valorOutrasDeducoes = $valorOutrasDeducoes;
  }

  /**
   * Retorna um \StdClass com os atribufo
   * @return array
   * @throws \BusinessException
   */
  public function getAtributos() {

    if (empty($this->atributos) && !empty($this->data->k174_atributodinamicovalor)) {

      $oDaoAtributoDinamicoValor = new \cl_db_cadattdinamicoatributosvalor();
      $sWhere        = "db110_cadattdinamicovalorgrupo = {$this->data->k174_atributodinamicovalor}";
      $sSqlAtributos = $oDaoAtributoDinamicoValor->sql_query_file(null, "*", "db110_db_cadattdinamicoatributos", $sWhere);
      $rsAtributos   = db_query($sSqlAtributos);
      if (!$rsAtributos) {
        throw new \BusinessException("Erro ao pesquisar atributos dinamicos da guia");
      }
      $this->atributos      = \db_utils::makeCollectionFromRecord($rsAtributos, function($oDados) {

        $oAtributoDinamico  = \DBAttDinamicoAtributoRepository::getDBAttDinamicoAtributoPorCodigo($oDados->db110_db_cadattdinamicoatributos);

        $oAtributo                  = new \stdClass();
        $oAtributo->codigo_atributo = $oAtributoDinamico->getCodigo();
        $oAtributo->nome            = $oAtributoDinamico->getDescricao();
        $oAtributo->valor           = \DBAttDinamicoAtributo::formatarValor($oAtributoDinamico, $oDados->db110_valor);
        $oAtributo->valor_plano     = $oDados->db110_valor;
        return $oAtributo;
      });
    }
    return $this->atributos;
  }

  /**
   * @param $atributo
   * @param $valor
   */
  public function setAtributo($atributo, $valor) {
    $this->atributos[$atributo] = $valor;
  }



}
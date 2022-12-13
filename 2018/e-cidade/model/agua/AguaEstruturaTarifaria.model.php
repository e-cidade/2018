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

class AguaEstruturaTarifaria {

  const TIPO_VALOR_FIXO    = 1;
  const TIPO_PERCENTUAL    = 2;
  const TIPO_FAIXA_CONSUMO = 3;

  /**
   * Código sequencial do registro
   *
   * @var integer
   */
  private $iCodigo;

  /**
   * Código do tipo de consumo
   *
   * @var integer
   */
  private $iCodigoTipoConsumo;

  /**
   * Tipo de Consumo
   *
   * @var AguaTipoConsumo
   */
  private $oTipoConsumo;

  /**
   * Tipo de estrutura tarifária
   *
   * @var integer
   */
  private $iCodigoTipoEstrutura;

  /**
   * Valor inicial da faixa de consumo em m³
   *
   * @var integer
   */
  private $iValorInicial;

  /**
   * Valor final da faixa de consumo em m³
   *
   * @var integer
   */
  private $iValorFinal;

  /**
   * Valor da tarifa, caso o tipo de estrutura seja valor fixo ou faixa de consumo.
   *
   * @var float
   */
  private $nValor;

  /**
   * Percentual sobre outras tarifas, caso o tipo de estrutura seja percentual.
   *
   * @var integer
   */
  private $iPercentual;

  /**
   * Código da categoria de consumo vinculada
   *
   * @var integer
   */
  private $iCodigoCategoriaConsumo;

  /**
   * Ordem da faixa de consumo
   *
   * @var integer
   */
  private $iOrdem;

  /**
   * Constroí o objeto e preenche com valores.
   *
   * @param integer $iCodigo
   * @throws DBException
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    if ($iCodigo) {

      $oDaoAguaEstruturaTarifaria = new cl_aguaestruturatarifaria;
      $sSql = $oDaoAguaEstruturaTarifaria->sql_query_file($iCodigo);
      $rsDados = db_query($sSql);

      if (!$rsDados) {
        throw new DBException('Ocorreu um erro ao tentar encontrar a Estrutura Tarifária informada.');
      }

      if (pg_num_rows($rsDados) === 0) {
        throw new BusinessException('Não foi possível encontrar a Estrutura Tarifária informada.');
      }

      $oDados = db_utils::fieldsMemory($rsDados, 0);

      $this->iCodigo                 = (integer) $oDados->x37_sequencial;
      $this->iCodigoTipoConsumo      = (integer) $oDados->x37_aguaconsumotipo;
      $this->iCodigoTipoEstrutura    = (integer) $oDados->x37_tipoestrutura;
      $this->iValorInicial           = (integer) $oDados->x37_valorinicial;
      $this->iValorFinal             = (integer) $oDados->x37_valorfinal;
      $this->nValor                  = (float)   $oDados->x37_valor;
      $this->iPercentual             = (integer) $oDados->x37_percentual;
      $this->iCodigoCategoriaConsumo = (integer) $oDados->x37_aguacategoriaconsumo;
    }
  }

  public function isValorFixo() {
    return $this->iCodigoTipoEstrutura === self::TIPO_VALOR_FIXO;
  }

  public function isFaixaConsumo() {
    $this->iCodigoTipoEstrutura === self::TIPO_FAIXA_CONSUMO;
  }

  public function isPercentual() {
    return $this->iCodigoTipoEstrutura === self::TIPO_PERCENTUAL;
  }

  /**
   * Retorna os tipos de estrutura válidos, juntamente com as descrições
   *
   * @return array
   */
  public static function getTiposEstrutura() {

    return array(
      self::TIPO_VALOR_FIXO    => 'Valor Fixo',
      self::TIPO_PERCENTUAL    => 'Percentual',
      self::TIPO_FAIXA_CONSUMO => 'Faixa de Consumo',
    );
  }

  /**
   *
   * @throws BusinessException
   * @throws DBException
   * @return boolean
   */
  public function excluir() {

    if (!$this->iCodigo) {
      throw new BusinessException('Estrutura Tarifária não carregada.');
    }

    $oDaoAguaEstruturaTarifaria = new cl_aguaestruturatarifaria;
    $oDaoAguaEstruturaTarifaria->excluir($this->iCodigo);

    if ($oDaoAguaEstruturaTarifaria->erro_status == '0') {
      throw new DBException('Não foi possível excluir a Estrutura Tarifária.');
    }

    return true;
  }

  /**
   * Valida as informações e salva o registro.
   *
   * @throws BusinessException
   * @throws DBException
   * @return integer Código do registro salvo.
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Sem transação iniciada.');
    }

    if (!$this->iCodigoCategoriaConsumo) {
      throw new BusinessException('O campo Categoria de Consumo é de preenchimento obrigatório.');
    }

    if (!$this->iCodigoTipoConsumo) {
      throw new BusinessException('O campo Tipo de Consumo é de preenchimento obrigatório.');
    }

    if (!$this->iCodigoTipoEstrutura) {
      throw new BusinessException('O campo Tipo de Estrutura é de preenchimento obrigatório.');
    }

    /**
     * Valida se valor inicial maior que final, apenas se valor final for preenchido
     */
    if ($this->iCodigoTipoEstrutura === self::TIPO_FAIXA_CONSUMO && $this->iValorInicial > $this->iValorFinal && $this->iValorFinal) {
      throw new BusinessException('O valor inicial da Faixa de Consumo não pode ser maior que o valor final.');
    }

    /**
     * Valida se pelo menos algum dos campos foi preenchido
     */
    if ($this->iCodigoTipoEstrutura === self::TIPO_FAIXA_CONSUMO && !$this->iValorFinal && !$this->iValorInicial) {
      throw new BusinessException('O campo Faixa de Consumo é de preenchimento obrigatório.');
    }

    if (in_array($this->iCodigoTipoEstrutura, array(self::TIPO_FAIXA_CONSUMO, self::TIPO_VALOR_FIXO)) && !$this->nValor) {
      throw new BusinessException('O campo Valor é de preenchimento obrigatório.');
    }

    if ($this->iCodigoTipoEstrutura === self::TIPO_PERCENTUAL && !$this->iPercentual) {
      throw new BusinessException('O campo Percentual é de preenchimento obrigatório.');
    }

    if ($this->iCodigoTipoEstrutura === self::TIPO_PERCENTUAL && $this->nValor) {
      throw new BusinessException('O campo Valor não deve ser informado para esse Tipo de Estrutura.');
    }

    if (in_array($this->iCodigoTipoEstrutura, array(self::TIPO_PERCENTUAL, self::TIPO_VALOR_FIXO)) && ($this->iValorInicial || $this->iValorFinal)) {
      throw new BusinessException('O campo Faixa de Consumo não deve ser informado para esse Tipo de Estrutura.');
    }

    if (in_array($this->iCodigoTipoEstrutura, array(self::TIPO_FAIXA_CONSUMO, self::TIPO_VALOR_FIXO)) && $this->iPercentual) {
      throw new BusinessException('O campo Percentual não deve ser informado para esse Tipo de Estrutura.');
    }

    $oDaoAguaEstruturaTarifaria  = new cl_aguaestruturatarifaria;
    $oDaoAguaEstruturaTarifaria->x37_sequencial           = (integer) $this->iCodigo;
    $oDaoAguaEstruturaTarifaria->x37_aguaconsumotipo      = (integer) $this->iCodigoTipoConsumo;
    $oDaoAguaEstruturaTarifaria->x37_tipoestrutura        = (integer) $this->iCodigoTipoEstrutura;
    $oDaoAguaEstruturaTarifaria->x37_valorinicial         = (integer) $this->iValorInicial;
    $oDaoAguaEstruturaTarifaria->x37_valorfinal           = (integer) $this->iValorFinal;
    $oDaoAguaEstruturaTarifaria->x37_valor                = (float)   $this->nValor;
    $oDaoAguaEstruturaTarifaria->x37_percentual           = (integer) $this->iPercentual;
    $oDaoAguaEstruturaTarifaria->x37_aguacategoriaconsumo = (integer) $this->iCodigoCategoriaConsumo;

    if ($this->iCodigo) {
      $oDaoAguaEstruturaTarifaria->alterar($this->iCodigo);
    } else {
      $oDaoAguaEstruturaTarifaria->incluir(null);
    }

    if ($oDaoAguaEstruturaTarifaria->erro_status == '0') {
      throw new DBException('Não foi possível salvar a Estrutura Tarifária.');
    }

    $this->iCodigo = $oDaoAguaEstruturaTarifaria->x37_sequencial;
    return $this->iCodigo;
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
   * @return integer $iCodigoTipoConsumo
   */
  public function getCodigoTipoConsumo() {
    return $this->iCodigoTipoConsumo;
  }

  /**
   * @param integer $iCodigoTipoConsumo
   */
  public function setCodigoTipoConsumo($iCodigoTipoConsumo) {
    $this->iCodigoTipoConsumo = $iCodigoTipoConsumo;
  }

  /**
   * @return integer $iCodigoTipoEstrutura
   */
  public function getCodigoTipoEstrutura() {
    return $this->iCodigoTipoEstrutura;
  }

  /**
   * @param integer $iCodigoTipoEstrutura
   * @throws ParameterException
   */
  public function setCodigoTipoEstrutura($iCodigoTipoEstrutura) {

    if (!in_array($iCodigoTipoEstrutura, array_keys(self::getTiposEstrutura()))) {
      throw new ParameterException('Tipo de Estrutura inválido.');
    }

    $this->iCodigoTipoEstrutura = $iCodigoTipoEstrutura;
  }

  /**
   * @return integer $iValorInicial
   */
  public function getValorInicial() {
    return $this->iValorInicial;
  }

  /**
   * @param integer $iValorInicial
   */
  public function setValorInicial($iValorInicial) {
    $this->iValorInicial = $iValorInicial;
  }

  /**
   * @return integer $iValorFinal
   */
  public function getValorFinal() {
    return $this->iValorFinal;
  }

  /**
   * @param integer $iValorFinal
   */
  public function setValorFinal($iValorFinal) {
    $this->iValorFinal = $iValorFinal;
  }

  /**
   * @return float $nValor
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * @return integer $iPercentual
   */
  public function getPercentual() {
    return $this->iPercentual;
  }

  /**
   * @param integer $iPercentual
   */
  public function setPercentual($iPercentual) {
    $this->iPercentual = $iPercentual;
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
   * @return AguaTipoConsumo
   */
  public function getTipoConsumo() {

    if (!$this->oTipoConsumo) {
      $this->oTipoConsumo = new AguaTipoConsumo($this->iCodigoTipoConsumo);
    }

    return $this->oTipoConsumo;
  }

  /**
   * @param AguaTipoConsumo $oTipoConsumo
   */
  public function setTipoConsumo($oTipoConsumo) {
    $this->oTipoConsumo = $oTipoConsumo;
  }

  /**
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * @param integer $iOrdem
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

}

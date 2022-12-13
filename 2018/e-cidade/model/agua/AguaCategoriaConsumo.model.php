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

class AguaCategoriaConsumo {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var integer
   */
  private $iExercicio;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @var AguaEstruturaTarifaria[]
   */
  private $aEstruturasTarifarias;

  /**
   * @param integer $iCodigo
   * @throws DBException
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {

    if ($iCodigo) {

      $oDaoAguaCategoriaConsumo = new cl_aguacategoriaconsumo;
      $sSql = $oDaoAguaCategoriaConsumo->sql_query_file($iCodigo);
      $rsDados = db_query($sSql);

      if (!$rsDados) {
        throw new DBException('Ocorreu um erro ao buscar a Categoria de Consumo.');
      }

      if (pg_num_rows($rsDados) === 0) {
        throw new BusinessException('Não foi possível encontrar a Categoria de Consumo.');
      }

      $oDados = db_utils::fieldsMemory($rsDados, 0);
      $this->iCodigo    = (integer) $oDados->x13_sequencial;
      $this->iExercicio = (integer) $oDados->x13_exercicio;
      $this->sDescricao = $oDados->x13_descricao;
    }
  }

  /**
   * @throws DBException
   * @throws BusinessException
   * @return boolean
   */
  public function excluir() {

    if (!$this->iCodigo) {
      throw new BusinessException('Categoria de Consumo não carregada.');
    }

    if (!db_utils::inTransaction()) {
      throw new DBException('É necessário uma transação aberta.');
    }

    if (is_array($this->getEstruturas())) {

      /**
       * Apaga todas as estruturas tarifárias vinculadas
       */
      foreach ($this->getEstruturas() as $oEstrutura) {
        $oEstrutura->excluir();
      }
    }

    $oDaoAguaCategoriaConsumo = new cl_aguacategoriaconsumo;
    $oDaoAguaCategoriaConsumo->excluir($this->iCodigo);

    if ($oDaoAguaCategoriaConsumo->erro_status == '0') {
      throw new DBException('Não foi possível excluir a Categoria de Consumo.');
    }

    return true;
  }

  /**
   * @throws DBException
   * @throws BusinessException
   * @return integer
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('É necessário uma transação aberta.');
    }

    if (empty($this->sDescricao)) {
      throw new BusinessException('O campo Descrição é de preenchimento obrigatório.');
    }

    if (empty($this->iExercicio)) {
      throw new BusinessException('O campo Exercício é de preenchimento obrigatório.');
    }

    $oDaoAguaCategoriaConsumo = new cl_aguacategoriaconsumo;
    $oDaoAguaCategoriaConsumo->x13_sequencial = $this->iCodigo;
    $oDaoAguaCategoriaConsumo->x13_descricao  = pg_escape_string($this->sDescricao);
    $oDaoAguaCategoriaConsumo->x13_exercicio  = $this->iExercicio;

    if ($this->iCodigo) {
      $oDaoAguaCategoriaConsumo->alterar($this->iCodigo);
    } else {
      $oDaoAguaCategoriaConsumo->incluir(null);
    }

    if ($oDaoAguaCategoriaConsumo->erro_status == '0') {
      throw new DBException('Não foi possível salvar a Categoria de Consumo.');
    }

    $this->iCodigo = $oDaoAguaCategoriaConsumo->x13_sequencial;
    return $this->iCodigo;
  }

  /**
   * @param AguaEstruturaTarifaria $oEstrutura
   */
  public function adicionarEstrutura(AguaEstruturaTarifaria $oEstrutura) {

    if ($this->aEstruturasTarifarias === null) {
      $this->aEstruturasTarifarias = array();
    }
    /**
     * @todo persistir ordenação e remover essa lógica
     */
    if ($oEstrutura->getCodigoTipoEstrutura() === AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {

      $iOrdem = 1;
      $aEstruturas = $this->getEstruturasPorTipo(AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO);
      if (!empty($aEstruturas)) {

        $oUltimaEstrutura = end($aEstruturas);
        $iOrdem = $oUltimaEstrutura->getOrdem() + 1;
      }
      $oEstrutura->setOrdem($iOrdem);
    }
    $this->aEstruturasTarifarias[$oEstrutura->getCodigo()] = $oEstrutura;
  }

  /**
   *
   * @param AguaEstruturaTarifaria $oEstrutura
   */
  public function removerEstrutura(AguaEstruturaTarifaria $oEstrutura) {

    if ($this->aEstruturasTarifarias !== null) {
      unset($this->aEstruturasTarifarias[$oEstrutura->getCodigo()]);
    }
  }

  /**
   *
   * @return AguaEstruturaTarifaria[]
   */
  public function getEstruturas() {

    if ($this->iCodigo && $this->aEstruturasTarifarias === null) {

      $oDaoAguaEstruturaTarifaria = new cl_aguaestruturatarifaria;
      $sWhere  = "x37_aguacategoriaconsumo = {$this->iCodigo}";
      $sOrder  = "x37_tipoestrutura, x37_valorinicial";
      $sSql    = $oDaoAguaEstruturaTarifaria->sql_query_file(null, 'x37_sequencial', $sOrder, $sWhere);
      $rsDados = db_query($sSql);

      if (!$rsDados) {
        throw new DBException('Não foi possível buscar as Estruturas Tarifárias para a Categoria de Consumo.');
      }

      /**
       * @todo persistir ordenação e remover essa lógica
       */
      $iOrdem = 1;
      $iQuantidadeEstruturas = pg_num_rows($rsDados);
      for ($iRegistro = 0; $iRegistro < $iQuantidadeEstruturas; $iRegistro++) {

        $iCodigoEstrutura = db_utils::fieldsMemory($rsDados, $iRegistro)->x37_sequencial;
        $oEstrutura = new AguaEstruturaTarifaria($iCodigoEstrutura);
        if ($oEstrutura->getCodigoTipoEstrutura() === AguaEstruturaTarifaria::TIPO_FAIXA_CONSUMO) {

          $oEstrutura->setOrdem($iOrdem);
          $iOrdem++;
        }
        $this->adicionarEstrutura($oEstrutura);
      }
    }
    return $this->aEstruturasTarifarias;
  }

  /**
   * @return AguaEstruturaTarifaria[]
   */
  public function getEstruturasPorTipo($iTipo) {

    $aEstruturas = array();
    foreach ($this->getEstruturas() as $oEstrutura) {

      if ($oEstrutura->getCodigoTipoEstrutura() == $iTipo) {
        $aEstruturas[$oEstrutura->getCodigo()] = $oEstrutura;
      }
    }

    return $aEstruturas;
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
   * @return integer $iExercicio
   */
  public function getExercicio() {
    return $this->iExercicio;
  }

  /**
   * @param integer $iExercicio
   */
  public function setExercicio($iExercicio) {
    $this->iExercicio = $iExercicio;
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

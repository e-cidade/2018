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
 * Class Recurso
 */
class Recurso {

	/**
	 * Código do recurso.
	 * @var int
	 */
  protected $iCodigoRecurso;

  /**
   * Descrição da finalidade do recurso.
   * @var string
   */
  protected $sFinalidadeRecurso;

  /**
   * Tipo do recurso.
   * @var int
   */
  protected $iTipoRecurso;

  /**
   * Data limite do recurso.
   * @var string
   */
  protected $sDataLimiteRecurso;

  protected $oDBEstruturaValor;

  private $lNovo = true;

  /**
   * @var string
   */
  private $sDescricao = '';

  const LIVRE = 1;
  const VINCULADO = 2;

  /**
   * Tipo do estrutural.
   *
   * @var string
   */
  protected $sTipoEstrutural = 'Recurso';

  function __construct($iCodigoRecurso = null) {

    if ($iCodigoRecurso != null) {

	    $oDaoOrcTipoRec   = db_utils::getDao("orctiporec");
	    $sWhereOrcTipoRec = "orctiporec.o15_codigo = {$iCodigoRecurso}";
	    $sSqlOrcTipoRec   = $oDaoOrcTipoRec->sql_query(null,
	                                                   'orctiporec.*',
	                                                    null,
	                                                    $sWhereOrcTipoRec
	                                                  );
	    $rsSqlOrcTipoRec = $oDaoOrcTipoRec->sql_record($sSqlOrcTipoRec);
	    if ($oDaoOrcTipoRec->numrows > 0) {

	      $this->lNovo  = false;
	      $oOrcTipoRec = db_utils::fieldsMemory($rsSqlOrcTipoRec, 0);
	      $this->iCodigoRecurso         = $iCodigoRecurso;
	      $this->sDescricao             = $oOrcTipoRec->o15_descr;
	      $this->sEstrutural            = $oOrcTipoRec->o15_codtri;
	      $this->sFinalidadeRecurso     = $oOrcTipoRec->o15_finali;
	      $this->iTipoRecurso           = $oOrcTipoRec->o15_tipo;
	      $this->sDataLimiteRecurso     = $oOrcTipoRec->o15_datalimite;
	      $this->setEstruturaValor(new TribunalEstrutura($oOrcTipoRec->o15_db_estruturavalor));
	    }
    }

  }

  /**
   * Retorna o código do recurso.
   *
   * @deprecated
   * @see getCodigo
   * @return $this->iCodigoRecurso
   */
  public function getCodigoRecurso() {
    return $this->iCodigoRecurso;
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigoRecurso;
  }

  /**
   * Retorna o tipo de recurso diponivel.
   * @return int
   */
  public function getTipoRecurso() {
    return $this->iTipoRecurso;
  }

  /**
   * Retorna a data limite do recurso.
   * @return string
   */
  public function getDataLimiteRecurso() {
    return $this->sDataLimiteRecurso;
  }

  /**
   * @return string
   */
  public function getFinalidadeRecurso() {
    return $this->sFinalidadeRecurso;
  }

  /**
   * Seta um novo código para o recurso.
   *
   * @param int $iCodigoRecurso
   * @return Recurso
   */
  public function setCodigoRecurso($iCodigoRecurso) {
    $this->iCodigoRecurso = $iCodigoRecurso;
    return $this;
  }

  /**
   * Seta um novo tipo para o recurso.
   *
   * @param int $iTipoRecurso
   * @return Recurso
   */
  public function setTipoRecurso($iTipoRecurso) {
    $this->iTipoRecurso = $iTipoRecurso;
    return $this;
  }

  /**
   * Seta uma nova data de limite para o recurso.
   *
   * @param string $sDataLimiteRecurso
   * @return Recurso
   */
  public function setDataLimiteRecurso($sDataLimiteRecurso) {

    $this->sDataLimiteRecurso = $sDataLimiteRecurso;
    return $this;
  }

  /**
   * Seta uma nova finalidade para o recurso.
   *
   * @param string $sFinalidadeRecurso
   * @return Recurso
   */
  public function setFinalidadeRecurso($sFinalidadeRecurso) {

    $this->sFinalidadeRecurso = $sFinalidadeRecurso;
    return $this;
  }

  /**
   * Retorna o código da estrutura.
   * @param int $iCodigoEstrutura
   * @return $iCodigoRecurso
   */
  static public function getCodigoByEstrutura($iCodigoEstrutura) {

    $iCodigoRecurso = null;
    $oDaoOrcTipoRec = db_utils::getDao("orctiporec");
    $sSqlOrcTipoRec = $oDaoOrcTipoRec->sql_query_file(null,
                                                      'o15_codigo',
                                                      null,
                                                      "o15_db_estruturavalor = {$iCodigoEstrutura}"
                                                     );

    $rsSqlOrcTipoRec = $oDaoOrcTipoRec->sql_record($sSqlOrcTipoRec);
    if ($oDaoOrcTipoRec->numrows > 0) {
      $iCodigoRecurso = db_utils::fieldsMemory($rsSqlOrcTipoRec, 0)->o15_codigo;
    }

    return $iCodigoRecurso;
  }

  /**
   * @return $this
   * @throws Exception
   */
  function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Não existe transação ativa.");
    }

    $oDaoOrcTipoRec = db_utils::getDao("orctiporec");
    $oDaoOrcTipoRec->o15_descr             = $this->getEstruturaValor()->getDescricao();
    $oDaoOrcTipoRec->o15_codtri            = $this->getEstruturaValor()->getEstrutural();
    $oDaoOrcTipoRec->o15_finali            = $this->getFinalidadeRecurso();
    $oDaoOrcTipoRec->o15_tipo              = $this->getTipoRecurso();
    $oDaoOrcTipoRec->o15_datalimite        = $this->getDataLimiteRecurso();
    $oDaoOrcTipoRec->o15_db_estruturavalor = $this->getEstruturaValor()->getCodigo();

    $iCodigoRecurso   = (int)$this->getCodigoRecurso();
    $sWhereOrcTipoRec = "orctiporec.o15_codigo = {$iCodigoRecurso}";
    $sSqlOrcTipoRec   = $oDaoOrcTipoRec->sql_query(null,
                                                   'orctiporec.*',
                                                    null,
                                                    $sWhereOrcTipoRec
                                                  );
    $rsSqlOrcTipoRec = $oDaoOrcTipoRec->sql_record($sSqlOrcTipoRec);
    if ($oDaoOrcTipoRec->numrows > 0 && !$this->lNovo) {

      $oDaoOrcTipoRec->o15_codigo = $iCodigoRecurso;
      $oDaoOrcTipoRec->alterar($oDaoOrcTipoRec->o15_codigo);
    } else {

    	$oDaoOrcTipoRec->o15_codigo = $iCodigoRecurso;
      $oDaoOrcTipoRec->incluir($oDaoOrcTipoRec->o15_codigo);

      $this->setCodigoRecurso($oDaoOrcTipoRec->o15_codigo);
    }

    if ($oDaoOrcTipoRec->erro_status == 0) {
      throw new Exception($oDaoOrcTipoRec->erro_msg);
    }

    return $this;
  }

  /**
   * @throws Exception
   */
  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Não existe transação ativa.");
    }

    $iCodigoRecurso = $this->getCodigoRecurso();
    if (empty($iCodigoRecurso) && $iCodigoRecurso != 0) {
      throw new Exception("Código do recurso não informado!\\nExclusão não efetuada.");
    }

    $oDaoOrcTipoRec   = db_utils::getDao("orctiporec");
    $sWhereOrcTipoRec = "orctiporec.o15_codigo = {$iCodigoRecurso}";
    $oDaoOrcTipoRec->excluir(null, $sWhereOrcTipoRec);
    if ($oDaoOrcTipoRec->numrows_excluir == 0) {
      throw new Exception($oDaoOrcTipoRec->erro_msg);
    }

    $this->getEstruturaValor()->remover();
    //parent::remover();
  }

  /**
   * @param DBEstruturaValor $oEstruturaValor
   * @return $this
   */
  public function setEstruturaValor(DBEstruturaValor $oEstruturaValor) {

    $this->oDBEstruturaValor = $oEstruturaValor;
    return $this;
  }

  /**
   * Retorna uma instancia de DBEstruturaValor
   * @return DBEstruturaValor
   */
  public function getEstruturaValor() {

    return $this->oDBEstruturaValor;
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
}

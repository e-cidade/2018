<?php
/**
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
 * Class AberturaExercicioOrcamento
 */
class AberturaExercicioOrcamento {

  /**
   * @var int
   */
  const DOC_ABERTURA_DESPESA = 2001;

  /**
   * @var int
   */
  const DOC_ESTORNO_ABERTURA_DESPESA = 2002;


  /**
   * @type int
   */
  private $iCodigo;

  /**
   * @type int
   */
  private $iCodigoUsuario;

  /**
   * @type int
   */
  private $iCodigoInstituicao;

  /**
   * @type int
   */
  private $iAno;

  /**
   * @type DBDate
   */
  private $dtProcessamento;

  /**
   * @type bool
   */
  private $lProcessado;

  /**
   * AberturaExercicioOrcamento constructor.
   * @param null $iCodigo
   * @throws Exception
   */
  public function __construct($iCodigo = null) {

    $this->iCodigo = $iCodigo;
    if (!empty($this->iCodigo)) {

      $oDaoAbertura = new cl_aberturaexercicioorcamento();
      $rsAbertura   = $oDaoAbertura->sql_record($oDaoAbertura->sql_query_file($this->iCodigo));
      if ($oDaoAbertura->erro_status == "0") {
        throw new Exception("Nao foi possivel localizar a abertura do exercicio o orçamento.");
      }

      $oStdAbertura = db_utils::fieldsMemory($rsAbertura, 0);
      $this->iCodigo            = $oStdAbertura->c104_sequencial;
      $this->iCodigoUsuario     = $oStdAbertura->c104_usuario;
      $this->iCodigoInstituicao = $oStdAbertura->c104_instit;
      $this->iAno               = $oStdAbertura->c104_ano;
      $this->dtProcessamento    = new DBDate($oStdAbertura->c104_data);
      $this->lProcessado        = $oStdAbertura->c104_processado == "t";
      unset($oDaoAbertura, $oStdAbertura);
    }
  }

  /**
   * @param $iAno
   * @param $iInstituicao
   *
   * @return AberturaExercicioOrcamento
   * @throws Exception
   */
  public static function getInstanciaPorAnoInstituicao($iAno, $iInstituicao) {

    $sWhere       = "c104_ano = {$iAno} and c104_instit = {$iInstituicao}";
    $oDaoAbertura = new cl_aberturaexercicioorcamento();
    $sSqlBusca    = $oDaoAbertura->sql_query_file(null, 'c104_sequencial', null, $sWhere);
    $rsAbertura   = $oDaoAbertura->sql_record($sSqlBusca);

    if ($oDaoAbertura->numrows == 0) {
      return new AberturaExercicioOrcamento(null);
    }
    return new AberturaExercicioOrcamento(db_utils::fieldsMemory($rsAbertura, 0)->c104_sequencial);
  }


  /**
   * @return bool
   * @throws Exception
   */
  public function salvar() {

    $oDaoAbertura = new cl_aberturaexercicioorcamento();
    $oDaoAbertura->c104_sequencial = $this->iCodigo;
    $oDaoAbertura->c104_instit     = $this->iCodigoInstituicao;
    $oDaoAbertura->c104_usuario    = $this->iCodigoUsuario;
    $oDaoAbertura->c104_ano        = $this->iAno;
    $oDaoAbertura->c104_data       = $this->dtProcessamento->getDate();
    $oDaoAbertura->c104_processado = $this->lProcessado ? "true" : "false";
    if ( empty($oDaoAbertura->c104_sequencial) ) {

      $oDaoAbertura->incluir($oDaoAbertura->c104_sequencial);
      $this->iCodigo = $oDaoAbertura->c104_sequencial;
    } else {
      $oDaoAbertura->alterar($oDaoAbertura->c104_sequencial);
    }

    if ($oDaoAbertura->erro_status == "0") {
      throw new Exception("Não foi possível salvar a abertura de exercício para o orçamento. {$oDaoAbertura->erro_msg}");
    }
    return true;
  }

  /**
   * Este método verifica se existe abertura de exercício processada para o ano e instituição
   * @param $iAno
   * @param $iCodigoInstituicao
   * @throws ParameterException
   * @return bool
   */
  public static function possuiAberturaProcessadaParaAnoInstituicao($iAno, $iCodigoInstituicao) {

    if (empty($iAno) || empty($iCodigoInstituicao)) {
      throw new ParameterException("Parâmetros informados não são válidos.");
    }

    $aWhere = array(
      "c104_ano = {$iAno}",
      "c104_instit = {$iCodigoInstituicao}",
      "c104_processado is true"
    );
    $oDaoAbertura = new cl_aberturaexercicioorcamento();
    $oDaoAbertura->sql_record($oDaoAbertura->sql_query_file(null, "*", null, implode(' and ', $aWhere)));
    return $oDaoAbertura->numrows > 0;
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
   * @return int
   */
  public function getCodigoInstituicao() {
    return $this->iCodigoInstituicao;
  }

  /**
   * @param int $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {
    $this->iCodigoInstituicao = $iCodigoInstituicao;
  }

  /**
   * @return int
   */
  public function getAno() {
    return $this->iAno;
  }

  /**
   * @param int $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @return DBDate
   */
  public function getDataProcessamento() {
    return $this->dtProcessamento;
  }

  /**
   * @param DBDate $dtProcessamento
   */
  public function setDataProcessamento(DBDate $dtProcessamento) {
    $this->dtProcessamento = $dtProcessamento;
  }

  /**
   * @return boolean
   */
  public function processado() {
    return $this->lProcessado;
  }

  /**
   * @param boolean $lProcessado
   */
  public function setProcessado($lProcessado) {
    $this->lProcessado = $lProcessado;
  }

}

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
 * Controla os abonos de faltas dos alunos nos períodos de avaliação
 * @package educacao
 * @author  Gilnei Freitas <gilnei@dbseller.com.br>
 * @author  Andrio Costa   <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 *
 */
class AbonoFalta {

  const URL_MENSAGEM_ABONOFALTA = "educacao.escola.AbonoFalta.";

  /**
   * Código sequencial
   * @var integer
   */
  private $iCodigo;

  /**
   * Código do Diário Avaliação
   * @var integer
   */
  private $iDiarioAvaliacao;

  /**
   * Código da justificativa
   * @var integer
   */
  private $iJustificativa;

  /**
   * Número de faltas
   * @var integer
   */
  private $iNumFaltas;

  public function __construct($iCodigo = null ) {

    if ($iCodigo != null) {

      $oDaoAbonoFalta = new cl_abonofalta();
      $sSqlAbonoFalta = $oDaoAbonoFalta->sql_query_file($iCodigo);
      $rsAbonoFalta   = db_query($sSqlAbonoFalta);

      if ( !$rsAbonoFalta ) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new DBException( _M(self::URL_MENSAGEM_ABONOFALTA."erro_buscar_abonofalta", $oMsgErro) );
      }

      if (pg_num_rows($rsAbonoFalta) == 0) {
        throw new Exception(_M(self::URL_MENSAGEM_ABONOFALTA."abonofalta_nao_encontrado"));
      }
      $oDados = db_utils::fieldsMemory($rsAbonoFalta, 0);

      $this->iCodigo          = $oDados->ed80_i_codigo;
      $this->iDiarioAvaliacao = $oDados->ed80_i_diarioavaliacao;
      $this->iJustificativa   = $oDados->ed80_i_justificativa;
      $this->iNumFaltas       = $oDados->ed80_i_numfaltas;
    }
  }

  /**
   * Retorna codigo
   * @return integer $iCodigo
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna codigo do diário de avaliação
   * @return integer $iDiarioAvaliacao
   */
  public function getDiarioAvaliacao() {
    return $this->iDiarioAvaliacao;
  }

  /**
   * Retorna codigo da justificativa
   * @return integer $iJustificativa
   */
  public function getJustificativa() {
    return $this->iJustificativa;
  }

  /**
   * Retorna número de faltas
   * @return integer $iNumFaltas
   */
  public function getNumeroFaltas() {
    return $this->iNumFaltas;
  }

  /**
   * Define o código do diario avaliação
   * @param integer $iDiarioAvaliacao
   */
  public function setDiarioAvaliacao($iDiarioAvaliacao) {
    $this->iDiarioAvaliacao = $iDiarioAvaliacao;
  }

  /**
   * Define o código da justificativa
   * @param integer $iJustificativa
   */
  public function setJustificativa($iJustificativa) {
    $this->iJustificativa = $iJustificativa;
  }

  /**
   * Define o numero de faltas
   * @param integer $iNumFaltas
   */
  public function setNumeroFaltas($iNumFaltas) {
    $this->iNumFaltas = $iNumFaltas;
  }

  /**
   * Salva um abono de falta ao periodo/diario de avaliação
   * @throws DBException
   * @return boolean
   */
  public function salvar() {

    $oDaoAbonoFalta                         = new cl_abonofalta();
    $oDaoAbonoFalta->ed80_i_justificativa   = $this->getJustificativa();
    $oDaoAbonoFalta->ed80_i_numfaltas       = $this->getNumeroFaltas();
    $oDaoAbonoFalta->ed80_i_diarioavaliacao = $this->getDiarioAvaliacao();

    if ( $this->getCodigo() == '' ) {
      $oDaoAbonoFalta->incluir(null);
    } else {

      $oDaoAbonoFalta->ed80_i_codigo = $this->getCodigo();
      $oDaoAbonoFalta->alterar($this->getCodigo());
    }

    if ($oDaoAbonoFalta->erro_status == "0") {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = $oDaoAbonoFalta->erro_sql;
      throw new DBException( _M( self::URL_MENSAGEM_ABONOFALTA."erro_salvar_abono", $oMsgErro ) );
    }

    $this->iCodigo = $oDaoAbonoFalta->ed80_i_codigo;

    return true;
  }

  /**
   * Exclui um abono para um periodo/diario de avaliacao
   * @throws DBException
   * @return boolean
   */
  public function excluir(){

    $oDaoAbonoFalta = new cl_abonofalta();
    $oDaoAbonoFalta->excluir( $this->getCodigo() );

    if ($oDaoAbonoFalta->erro_status == "0") {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = $oDaoAbonoFalta->erro_sql;
      throw new DBException( _M( self::URL_MENSAGEM_ABONOFALTA."erro_excluir_abono", $oMsgErro ) );
    }
    return true;
  }

}
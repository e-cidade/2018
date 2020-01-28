<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

class CondicionanteTipoLicenca {

  /**
   * Define constante com o caminho do arquivo de mensagens
   */
  const ARQUIVO_MENSAGEM = 'tributario.meioambiente.CondicionanteTipoLicenca.';

  /**
   * Sequencial
   * @var inteiro
   */
  private $iSequencial = null;

  /**
   * Condicionante
   * @var Condicionante
   */
  private $oCondicionante = null;

  /**
   * Tipo de Licença
   * @var TipoLicenca
   */
  private $oTipoLicenca = null;

  /**
   * Método construtor
   *
   * @param integer       $iSequencial
   * @param Condicionante $iCondicionante
   * @param TipoLicenca   $iTipoLicenca
   */
  public function __construct( $iSequencial = null, $iCondicionante = null, $iTipoLicenca = null ) {

    $oDaoCondicionanteTipoLicenca = new cl_condicionantetipolicenca();
    $rsCondicionanteTipoLicenca   = null;

    if ( !empty($iSequencial) ) {

      $sSqlCondicionanteTipoLicenca = $oDaoCondicionanteTipoLicenca->sql_query_file($iSequencial);
      $rsCondicionanteTipoLicenca   = $oDaoCondicionanteTipoLicenca->sql_record($sSqlCondicionanteTipoLicenca);
    }

    if ( empty($iSequencial) ) {

      $sWhere = "";
      if ( !empty($iCondicionante) ) {
        $sWhere .= "am17_condicionante = {$iCondicionante}";
      }

      if ( !empty($iTipoLicenca) ) {

        if ( !empty($sWhere) ) {
          $sWhere .= " and ";
        }

        $sWhere .= "am17_tipolicenca = {$iTipoLicenca}";
      }

      $sSqlCondicionanteTipoLicenca = $oDaoCondicionanteTipoLicenca->sql_query_file(null , "*", "am17_sequencial desc", $sWhere);
      $rsCondicionanteTipoLicenca   = $oDaoCondicionanteTipoLicenca->sql_record($sSqlCondicionanteTipoLicenca);
    }

    if ( !empty($rsCondicionanteTipoLicenca) ) {

      $oCondicionanteTipoLicenca = db_utils::fieldsMemory($rsCondicionanteTipoLicenca, 0);

      $this->iSequencial    = $oCondicionanteTipoLicenca->am17_sequencial;
      $this->oCondicionante = new Condicionante( $oCondicionanteTipoLicenca->am17_condicionante );
      $this->oTipoLicenca   = new TipoLicenca( $oCondicionanteTipoLicenca->am17_tipolicenca );
    }
  }

  /**
   * Busca o sequencial
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Busca a Condicionante
   * @return object Condicionante
   */
  public function getCodicionante() {
    return $this->oCondicionante;
  }

  /**
   * Altera a Condicionante
   * @param Condicionante $oCondicionante
   */
  public function setCondicionante( Condicionante $oCondicionante ) {
    $this->oCondicionante = $oCondicionante;
  }

  /**
   * Busca o Tipo de Licença
   *
   * @return object TipoLicenca
   */
  public function getTipoLicenca() {
    return $this->oTipoLicenca;
  }

  /**
   * Altera o Tipo de Licença
   * @param TipoLicenca $oTipoLicenca
   */
  public function setTipoLicenca( TipoLicenca $oTipoLicenca ) {
    $this->oTipoLicenca = $oTipoLicenca;
  }

  /**
   * Incluimos os dados na CondicionanteTipoLicenca
   *
   * @throws Exception
   * @return void
   */
  public function incluir(){

    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( self::ARQUIVO_MENSAGEM . "sem_transacao_ativa" ) );
    }

    if ( is_null($this->oCondicionante->getSequencial()) ) {
      throw new Exception( _M( self::ARQUIVO_MENSAGEM . "condicionante_obrigatorio") );
    }

    if ( is_null($this->oTipoLicenca->getSequencial()) ) {
      throw new Exception( _M( self::ARQUIVO_MENSAGEM . "tipolicenca_obrigatorio") );
    }

    $oDaoCondicionanteTipoLicenca = new cl_condicionantetipolicenca();
    $oDaoCondicionanteTipoLicenca->am17_condicionante = $this->oCondicionante->getSequencial();
    $oDaoCondicionanteTipoLicenca->am17_tipolicenca   = $this->oTipoLicenca->getSequencial();
    $oDaoCondicionanteTipoLicenca->incluir(null);

    if ($oDaoCondicionanteTipoLicenca->erro_status == 0) {
      throw new Exception( _M( self::ARQUIVO_MENSAGEM . "erro_incluir_condicionantetipolicenca") );
    }
  }

  /**
   * Excluimos os tipos de licença vinculados a Condicionante
   *
   * @param  integer $iCodigoCondicionante
   * @access public
   */
  public static function excluirVinculoCondicionante( $iCodigoCondicionante ) {

    $oDaoCondicionanteTipoLicenca = new cl_condicionantetipolicenca();
    $sWhere = " am17_condicionante = {$iCodigoCondicionante} ";
    $oDaoCondicionanteTipoLicenca->excluir(null, $sWhere);
  }

}
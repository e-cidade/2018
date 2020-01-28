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
define( 'MENSAGENS_DOCUMENTO', 'configuracao.configuracao.Documento.' );

/**
 * Model para tratar todas ações dos documentos do sistema
 */
class Documento {

  function __construct() {}

  /**
   * Retorna um array contendo todos atributos do documento apartir do código do cadastro
   * do documento passado por parâmetro ( db44_sequencial da tabela caddocumento )
   *
   * @param string $iCodCadDocumento
   * @return array|stdClass[]
   * @throws DBException
   */
  public static function getAtributosByCadDocumento($iCodCadDocumento = '') {

    $oDaoDocumentoAtributo   = new cl_caddocumentoatributo();
    $sWhereDocumentoAtributo = "db45_caddocumento = {$iCodCadDocumento} ";
    $sSqlDocumentoAtributo   = $oDaoDocumentoAtributo->sql_query_file(null, "*", "db45_sequencial", $sWhereDocumentoAtributo);
    $rsDocumentoAtributo     = db_query($sSqlDocumentoAtributo);

    if( !is_resource( $rsDocumentoAtributo ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_DOCUMENTO . 'erro_buscar_documento', $oErro ) );
    }

    $aAtributos = db_utils::getCollectionByRecord($rsDocumentoAtributo,false,false);
    $aAtributos = self::consultaReferencia($aAtributos);

    return $aAtributos;
  }

  /**
   * Retorna um array contendo todos atributos do documento apartir do código do documento
   * passado por parâmetro ( db58_sequencial da tabela documento )
   *
   * @param $iCodDocumento
   * @return array|stdClass[]
   */
  public static function getAtributosByDocumento($iCodDocumento = '') {

    $oDaoDocumentoAtributo      = new cl_caddocumentoatributo();
    $oDaoDocumentoAtributoValor = new cl_caddocumentoatributovalor();

    $sSqlCadDocumento = $oDaoDocumentoAtributoValor->sql_query(null,"db44_sequencial",null,"db58_sequencial = {$iCodDocumento}");
    $rsCadDocumento   = $oDaoDocumentoAtributoValor->sql_record($sSqlCadDocumento);
    $iCodCadDocumento = db_utils::fieldsMemory($rsCadDocumento,0)->db44_sequencial;

    $sWhereDocumentoAtributo = "db45_caddocumento = {$iCodCadDocumento} ";
    $sSqlDocumentoAtributo   = $oDaoDocumentoAtributo->sql_query_file(null, "*", "db45_sequencial",$sWhereDocumentoAtributo);
    $rsDocumentoAtributo     = $oDaoDocumentoAtributo->sql_record($sSqlDocumentoAtributo);

    $aAtributos = db_utils::getCollectionByRecord($rsDocumentoAtributo,false,false,true);
    $aAtributos = self::consultaReferencia($aAtributos);

    return $aAtributos;
  }

  /**
   * Consulta os dados dos campos com referência de todos os atributos passados por parâmetro
   *
   * @param  array $aAtributos
   * @return array
   */
  private static function consultaReferencia($aAtributos=array()){

  	$oDaoSysArqCamp = db_utils::getDao('db_sysarqcamp');

    for ($iInd=0; $iInd < count($aAtributos); $iInd++) {

      if ($aAtributos[$iInd]->db45_codcam != "") {

        $sSqlSysArqCamp = $oDaoSysArqCamp->sql_query(null, null, null, "*",null,"db_syscampo.codcam=".$aAtributos[$iInd]->db45_codcam);
        $rsSysArqCamp   = $oDaoSysArqCamp->sql_record($sSqlSysArqCamp);

        if ($rsSysArqCamp) {

          $oSysCam = db_utils::fieldsMemory($rsSysArqCamp,0);
          $aAtributos[$iInd]->referencia = array("campo" =>$oSysCam->nomecam,
                                                 "tabela"=>$oSysCam->nomearq);
        }
      } else {
        $aAtributos[$iInd]->referencia = null;
      }
    }

    return $aAtributos;

  }

  /**
   * Inclui um novo documento e retorna o código do documento gerado
   * @param array $aAtributos
   * @return int
   * @throws Exception
   */
  public function incluirDocumento($aAtributos=array()){

    $sMsgErro = "Inclusão de documento abortada!\n";

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}Nenhuma transação encontrada!");
    }

  	if ( empty($aAtributos) ) {
  		throw new Exception("{$sMsgErro}Nenhum atributo informado!");
  	}

  	$oDaoDocumento        = db_utils::getDao('documento');
    $oDaoCadAtributoValor = db_utils::getDao('caddocumentoatributovalor');

    $oDaoDocumento->incluir(null);

    if ($oDaoDocumento->erro_status == "0") {
      throw new Exception($sMsgErro.$oDaoDocumento->erro_msg);
    }

    $iCodDocumento = $oDaoDocumento->db58_sequencial;

    foreach ( $aAtributos as $oAtributo ) {

      $oDaoCadAtributoValor->db43_caddocumentoatributo = $oAtributo->atributo;
      $oDaoCadAtributoValor->db43_valor                = $oAtributo->valor;
      $oDaoCadAtributoValor->db43_documento            = $iCodDocumento;
      $oDaoCadAtributoValor->incluir(null);

      if ($oDaoCadAtributoValor->erro_status == "0") {
        throw new Exception($sMsgErro.$oDaoCadAtributoValor->erro_msg);
      }
    }

    return $iCodDocumento;
  }

  /**
   * Altera os valores do atributo de um documento passado por parâmetro
   * @param int $iCodDocumento
   * @param array  $aAtributos
   * @throws Exception
   */
  public function alterarDocumento($iCodDocumento = null,$aAtributos = array()){

    $sMsgErro = "Alteração de documento abortada!\n";

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}Nenhuma transação encontrada!");
    }

    if ( trim($iCodDocumento) == '' ) {
      throw new Exception("{$sMsgErro}Código do documento não informado!");
    }

    if ( empty($aAtributos) ) {
      throw new Exception("{$sMsgErro}Nenhum atributo informado!");
    }

    $oDaoCadAtributoValor = db_utils::getDao('caddocumentoatributovalor');

    $oDaoCadAtributoValor->excluir(null,"db43_documento = {$iCodDocumento}");

    if ($oDaoCadAtributoValor->erro_status == "0") {
      throw new Exception($sMsgErro.$oDaoCadAtributoValor->erro_msg);
    }

    foreach ($aAtributos as $oAtributo) {

      $oDaoCadAtributoValor->db43_caddocumentoatributo = $oAtributo->atributo;
      $oDaoCadAtributoValor->db43_documento            = $iCodDocumento;
      $oDaoCadAtributoValor->db43_valor                = $oAtributo->valor;
      $oDaoCadAtributoValor->incluir(null);

      if ($oDaoCadAtributoValor->erro_status == "0") {
        throw new Exception($sMsgErro.$oDaoCadAtributoValor->erro_msg);
      }
    }
  }

  /**
   * @param int $iCodDocumento
   * @throws Exception
   */
  public function excluirDocumento($iCodDocumento = null){

    $sMsgErro = "Exclusão de documento abortada!\n";

    if ( !db_utils::inTransaction() ){
      throw new Exception("{$sMsgErro}Nenhuma transação encontrada!");
    }

    if ( trim($iCodDocumento) == '' ) {
      throw new Exception("{$sMsgErro}Código do documento não informado!");
    }

    $oDaoDocumento        = db_utils::getDao('documento');
    $oDaoCadAtributoValor = db_utils::getDao('caddocumentoatributovalor');


    $oDaoCadAtributoValor->excluir(null,"db43_documento = {$iCodDocumento}");
    if ($oDaoCadAtributoValor->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoCadAtributoValor->erro_msg);
    }

    $oDaoDocumento->excluir(null, "db58_sequencial = {$iCodDocumento}");
    if ($oDaoDocumento->erro_status == 0) {
      throw new Exception($sMsgErro.$oDaoDocumento->erro_msg);
    }

  }

  /**
   * Retorna os valores correspondentes a cada atributo de acordo com o documento informado
   * @param  int $iDocumento
   * @return array
   * @throws DBException
   * @throws ParameterException
   */
  public static function getAtributosValorByDocumento( $iDocumento ) {

    if( empty( $iDocumento ) ) {
      throw new ParameterException( 'Documento não informado.' );
    }

    /**
     * Ordenado pelo código do atributo para que os dados sempre retornem na mesma ordem.
     * Sem o order, se a tabela sofresse alteração, iria trazer os campos em ordens diferentes
     */
    $oDaoAtributoValor    = new cl_caddocumentoatributovalor();
    $sCamposAtributoValor = "db43_caddocumentoatributo, db43_valor";
    $sWhereAtributoValor  = "db43_documento = {$iDocumento}";
    $sSqlAtributoValor    = $oDaoAtributoValor->sql_query_file(null, $sCamposAtributoValor, "db43_caddocumentoatributo", $sWhereAtributoValor);
    $rsAtributoValor      = db_query($sSqlAtributoValor);

    if( !is_resource( $rsAtributoValor ) ) {
      throw new DBException( 'Erro ao buscar os valores dos atributos.' );
    }

    $aAtributoValor = db_utils::makeCollectionFromRecord($rsAtributoValor, function( $oResultado ) {

      $oAtributoValor           = new stdClass();
      $oAtributoValor->atributo = $oResultado->db43_caddocumentoatributo;
      $oAtributoValor->valor    = $oResultado->db43_valor;

      return $oAtributoValor;
    });

    return $aAtributoValor;
  }
}
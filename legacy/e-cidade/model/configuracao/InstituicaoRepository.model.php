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
 * Dependencias
 */
require_once modification("model/configuracao/Instituicao.model.php");

/**
 * Classe repository para classes Instituicao
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package configuracao
 */
class InstituicaoRepository {

  /**
   * Collection de Instituicao
   * @var array
   */
  private $aInstituicao = array();

  /**
   * Instancia da classe
   * @var InstituicaoRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia de Instituicao pelo Codigo
   * @param integer $iCodigoInstituicao Codigo do Instituicao
   * @return Instituicao
   */
  public static function getInstituicaoByCodigo($iCodigoInstituicao) {

    if (!array_key_exists($iCodigoInstituicao, InstituicaoRepository::getInstance()->aInstituicao)) {
      InstituicaoRepository::getInstance()->aInstituicao[$iCodigoInstituicao] = new Instituicao($iCodigoInstituicao);
    }
    return InstituicaoRepository::getInstance()->aInstituicao[$iCodigoInstituicao];
  }

  /**
   * Retorna a instancia da classe
   * @return InstituicaoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new InstituicaoRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um Instituicao dao repositorio
   * @param Instituicao $oInstituicao Instancia do Instituicao
   * @return boolean
   */
  public static function adicionarInstituicao(Instituicao $oInstituicao) {

    if(!array_key_exists($oInstituicao->getSequencial(), InstituicaoRepository::getInstance()->aInstituicao)) {
      InstituicaoRepository::getInstance()->aInstituicao[$oInstituicao->getSequencial()] = $oInstituicao;
    }
    return true;
  }

  /**
   * Remove o Instituicao passado como parametro do repository
   * @param Instituicao $oInstituicao
   * @return boolean
   */
  public static function removerInstituicao(Instituicao $oInstituicao) {
    /**
     *
     */
    if (array_key_exists($oInstituicao->getSequencial(), InstituicaoRepository::getInstance()->aInstituicao)) {
      unset(InstituicaoRepository::getInstance()->aInstituicao[$oInstituicao->getSequencial()]);
    }
    return true;
  }

  /**
   * Retorna o total de cidadoes existentes no repositorio;
   * @return integer;
   */
  public static function getTotalInstituicao() {
    return count(InstituicaoRepository::getInstance()->aInstituicao);
  }

  /**
   * Retortna a Instituição da Sessão
   *
   * @return Instituicao
   */
  public static function getInstituicaoSessao() {
    return InstituicaoRepository::getInstituicaoByCodigo( db_getsession("DB_instit") );
  }

  /**
   * Busca a instituição do tipo prefeitura
   * @return bool|\Instituicao
   */
  public static function getInstituicaoPrefeitura() {

    $oDaoConfiguracao    = new cl_db_config();
    $sSqlBuscaPrefeitura = $oDaoConfiguracao->sql_query_file(null, "codigo", null, "prefeitura is true");
    $rsBuscaInstituicao  = $oDaoConfiguracao->sql_record($sSqlBuscaPrefeitura);
    if ($oDaoConfiguracao->numrows == 0) {
      return false;
    }
    return self::getInstituicaoByCodigo(db_utils::fieldsMemory($rsBuscaInstituicao, 0)->codigo);
  }

  /**
   * Retorna as instituições para o tipo passado
   * @param $aTipos
   *
   * @return array
   * @throws Exception
   */
  public static function getInstituicoesPorTipo($aTipos) {

    if (empty($aTipos)) {
      throw new Exception("Tipo da instituição não informado.");
    }

    $cldb_config = new cl_db_config();

    $rsInstituicao = $cldb_config->sql_record( $cldb_config->sql_query_file(null, "codigo", null, "db21_tipoinstit in (" . implode(',', $aTipos) . ")") );
    $aListaInstituicoes  = array();

    if ( $cldb_config->numrows > 0 ) {

      for ($iInd = 0; $iInd < $cldb_config->numrows; $iInd++) {
        $aListaInstituicoes[] = self::getInstituicaoByCodigo( db_utils::fieldsMemory($rsInstituicao, 0)->codigo );
      }
    }
    return $aListaInstituicoes;
  }

  /**
   * @return Instituicao[]
   * @throws Exception
   */
  public static function getInstituicoes() {

    $oDaoInstituicao      = new cl_db_config();
    $sSqlBuscaInstituicao = $oDaoInstituicao->sql_query_file(null, "codigo");
    $rsBuscaInstituicao   = $oDaoInstituicao->sql_record($sSqlBuscaInstituicao);
    if (!$rsBuscaInstituicao || $oDaoInstituicao->erro_status == "0") {
      throw new Exception('Ocorreu um erro ao consultar as instituções cadastradas.');
    }
    for ($iRowInstituicao = 0; $iRowInstituicao < $oDaoInstituicao->numrows; $iRowInstituicao++) {
      self::getInstituicaoByCodigo(db_utils::fieldsMemory($rsBuscaInstituicao, $iRowInstituicao)->codigo);
    }
    return self::getInstance()->aInstituicao;
  }

  /**
   * Retorna os tipos de instituicao
   * @param array $aCodigosTipos
   * @return array
   * @throws \DBExeption
   */
  public static function getTiposIntituicao($aCodigosTipos = null) {

    $oDaoTipoInstituicao = new cl_db_tipoinstit;

    $sWhereTipoInstituicao = "1=1";
    if(is_array($aCodigosTipos) && count($aCodigosTipos) > 0) {
      $sWhereTipoInstituicao = "db21_codtipo IN (". implode(', ', $aCodigosTipos) .")";
    }

    $rsTipoInstituicao = db_query($sSqlTipoInstituicao = $oDaoTipoInstituicao->sql_query_file(null, "*", null, $sWhereTipoInstituicao));
    if(!$rsTipoInstituicao) {
      throw new \DBException("Ocorreu um erro ao consultar os tipos de Instituicao.\nContate o suporte.");
    }

    $aTiposInstituicoes = array();
    $aTiposInstituicoes = \db_utils::makeCollectionFromRecord($rsTipoInstituicao, function ($oRetorno) {
      return $oRetorno;
    });

    return $aTiposInstituicoes;
  }
}

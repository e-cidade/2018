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
 * Repositorio de instancias Regime
 *
 * @author   Renan Silva <renan.silva@dbseller.com.br>
 * @author   Rafael Nery <rafael.nery@dbseller.com.br>
 * @id       $Id: RegimeRepository.model.php,v 1.7 2015/07/22 12:18:29 dbrafael.nery Exp $
 * @package  Pessoal
 */
 class RegimeRepository {

  const MENSAGEM = 'recursoshumanos.pessoal.RegimeRepository.';

  /**
   * Representa a instancia a classe
   * @var DBRepository
   */
  private static   $oInstance;

  /**
   * Previne a criação do objeto externamente
   */
  private function __construct() {
    return;
  }

  /**
   * Previne o clone
   * @return void
   */
  private function __clone() {
    return;
  }

  /**
   * Coleção de instancias de Regime
   * @var Regime[]
   */
  private $aColecao = array();


  /**
   * Retorna a instancia do repositório
   *
   * @return RegimeRepository
   */
  public static function getInstance() {

    if (empty(self::$oInstance)) {

      $sClasse  = get_class();
      self::$oInstance = new RegimeRepository();
    }

    return self::$oInstance;
  }

  /**
   * Adiciona um Regime a Coleção
   *
   * @param  Regime $oItem
   * @return void
   */
  public static function add(Regime $oItem) {

    $oRepository = self::getInstance();
    $oRepository->aColecao[$oItem->getCodigo()] = $oItem;
  }

  /**
   * Retorna a instancia do Regime pelo seu código unico
   *
   * @return Regime
   */
  public static function getInstanciaPorCodigo($iCodigoRegime) {

    $oRepository = self::getInstance();

    if ( !array_key_exists($iCodigoRegime, $oRepository->aColecao) ) {
      self::add($oRepository->make($iCodigoRegime));
    }

    return $oRepository->aColecao[$iCodigoRegime];
  }

  /**
   * Monta um objeto Regime
   * @return Regime
   */
  public function make($iCodigoRegime) {

    $oDaoRhCadRegime    = new cl_rhcadregime();
    $oCompetencia       = DBPessoal::getCompetenciaFolha();
    $oInstituicao       = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit', false));
    $sJoinRhCadRegime  = "     rh158_ano    = ". $oCompetencia->getAno();
    $sJoinRhCadRegime .= " and rh158_mes    = ". $oCompetencia->getMes();
    $sJoinRhCadRegime .= " and rh158_instit = ". $oInstituicao->getSequencial();
    $sWhereRhCadRegime = "  rh52_regime  = {$iCodigoRegime}";

    $sSqlRhCadRegime    = $oDaoRhCadRegime->sql_query_com_bases(null, "*", null, $sWhereRhCadRegime, $sJoinRhCadRegime);
    $rsRhCadRegime      = db_query($sSqlRhCadRegime);

    if( !$rsRhCadRegime ) {
      throw new DBException( "Erro ao buscar os dados do regime." . pg_last_error() );
    }

    if ( pg_num_rows($rsRhCadRegime) == 0) {
      throw new BusinessException( "Nenhum Regime encontrado");
    }

    $oStdRhCadRegime = db_utils::fieldsMemory($rsRhCadRegime, 0);

    $oRegime = new Regime($iCodigoRegime);
    $oRegime->setDescricao($oStdRhCadRegime->rh52_descr);

    if ( !empty($oStdRhCadRegime->rh158_basesubstituido) ) {
      $oRegime->setBaseServidorSubstituido(BaseRepository::getBase($oStdRhCadRegime->rh158_basesubstituido,
                                                                   $oCompetencia,
                                                                   $oInstituicao));
    }

    if ( !empty($oStdRhCadRegime->rh158_basesubstituto) ) {
      $oRegime->setBaseServidorSubstituto(BaseRepository::getBase($oStdRhCadRegime->rh158_basesubstituto,
                                                                   $oCompetencia,
                                                                   $oInstituicao));
    }

    return $oRegime;
  }

  public static function persist() {
    ;
  }

}

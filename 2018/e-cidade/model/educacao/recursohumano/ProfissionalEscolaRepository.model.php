<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 * Representa os dados do profissional da escola
 * @package   Educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.5 $
 */
class ProfissionalEscolaRepository {

  const MSG_PROFISSIONALESCOLAREPOSITORY = "educacao.escola.ProfissionalEscola.";

  /**
   * Collection de ProfissionalEscola
   * @var array
   */
  private $aProfissionalEscola = array();

  /**
   * Instancia da classe
   * @var ProfissionalEscolaRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia do profissional da escola pelo Codigo
   * @param integer $iCodigo Codigo do rechumanoescola
   * @return ProfissionalEscola
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, ProfissionalEscolaRepository::getInstance()->aProfissionalEscola)) {
      ProfissionalEscolaRepository::getInstance()->aProfissionalEscola[$iCodigo] = new ProfissionalEscola($iCodigo);
    }
    return ProfissionalEscolaRepository::getInstance()->aProfissionalEscola[$iCodigo];
  }

  /**
   * Retorna a instancia da classe
   * @return ProfissionalEscolaRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new ProfissionalEscolaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um ProfissionalEscola dao repositorio
   * @param ProfissionalEscola $oProfissionalEscola Instancia do ProfissionalEscola
   * @return boolean
   */
  public static function adicionarProfissional(ProfissionalEscola $oProfissionalEscola) {

    if(!array_key_exists($oProfissionalEscola->getCodigo(), ProfissionalEscolaRepository::getInstance()->aProfissionalEscola)) {
      ProfissionalEscolaRepository::getInstance()->aProfissionalEscola[$oProfissionalEscola->getCodigo()] = $oProfissionalEscola;
    }
    return true;
  }

  /**
   * Remove o ProfissionalEscola passado como parametro do repository
   * @param ProfissionalEscola $oProfissionalEscola
   * @return boolean
   */
  public static function removerProfissional(ProfissionalEscola $oProfissionalEscola) {

    if (array_key_exists($oProfissionalEscola->getCodigo(), ProfissionalEscolaRepository::getInstance()->aProfissionalEscola)) {
      unset(ProfissionalEscolaRepository::getInstance()->aProfissionalEscola[$oProfissionalEscola->getCodigo()]);
    }
    return true;
  }

  /**
   * Retorna todos as escolas que um CGM está vinculado
   * @param  CGMBase $oCgm
   * @return ProfissionalEscola[]
   */
  public static function getEscolasProfissionalByCGM( CGMBase $oCgm ) {

    $aProfissionalEscola     = array();
    $oDaoRecHumanoEscola     = new cl_rechumanoescola();
    $sWhereRecHumanoEscola   = "case ";
    $sWhereRecHumanoEscola  .= "  when ed20_i_tiposervidor = 1 ";
    $sWhereRecHumanoEscola  .= "    then cgmrh.z01_numcgm ";
    $sWhereRecHumanoEscola  .= "    else cgmcgm.z01_numcgm ";
    $sWhereRecHumanoEscola  .= "   end = {$oCgm->getCodigo()}";
    $sSqlRecHumanoEscola     = $oDaoRecHumanoEscola->sql_query( null, 'distinct ed75_i_codigo as codigo', null, $sWhereRecHumanoEscola );
    $rsRecHumanoEscola       = db_query( $sSqlRecHumanoEscola );

    if( $rsRecHumanoEscola && pg_num_rows( $rsRecHumanoEscola ) > 0 ) {

      for( $iContador = 0; $iContador < pg_num_rows( $rsRecHumanoEscola ); $iContador++ ) {

        $iRecHumanoEscola      = db_utils::fieldsMemory( $rsRecHumanoEscola, $iContador )->codigo;
        $aProfissionalEscola[] = ProfissionalEscolaRepository::getInstance()->getByCodigo( $iRecHumanoEscola );
      }
    }

    return $aProfissionalEscola;
  }
  public static function getUltimoVinculoByRecHumanoEscola($iRecHumano, $oEscola) {

    $sWhere  = "  ed75_i_escola = {$oEscola->getCodigo()} ";
    $sWhere .= " and ed75_i_rechumano = {$iRecHumano} ";

    $oDaoRecHumanoEscola = new cl_rechumanoescola();
    $sSqlRecHumanoEscola = $oDaoRecHumanoEscola->sql_query_file(null, " max(ed75_i_codigo) as codigo ", null, $sWhere);
    $rsRecHumanoEscola   = db_query($sSqlRecHumanoEscola);

    $oMsgErro = new stdClass();

    if ( !$rsRecHumanoEscola ) {

      $oMsgErro->sErro  = pg_last_error();
      throw new DBException( _M(MSG_PROFISSIONALESCOLAREPOSITORY . "erro_buscar_profissional", $oMsgErro) );
    }

    $iCodigo = db_utils::fieldsMemory($rsRecHumanoEscola, 0)->codigo;

    return ProfissionalEscolaRepository::getInstance()->getByCodigo( $iCodigo );

  }
}
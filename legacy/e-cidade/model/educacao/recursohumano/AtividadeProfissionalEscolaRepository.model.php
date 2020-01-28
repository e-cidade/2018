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
 * Representa uma coleção de Atividades escolar
 * @package    Educacao
 * @subpackage recursohumano
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 * @version    $Revision: 1.2 $
 */
class AtividadeProfissionalEscolaRepository {

  /**
   * Collection de AtividadeProfissionalEscola
   * @var array
   */
  private $aAtividade = array();

  /**
   * Instancia da classe
   * @var AtividadeProfissionalEscolaRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia de uma atividade profissional
   * @param integer $iCodigo
   * @return AtividadeProfissionalEscola
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, AtividadeProfissionalEscolaRepository::getInstance()->aAtividade)) {
      AtividadeProfissionalEscolaRepository::getInstance()->aAtividade[$iCodigo] = new AtividadeProfissionalEscola($iCodigo);
    }
    return AtividadeProfissionalEscolaRepository::getInstance()->aAtividade[$iCodigo];
  }

  /**
   * Retorno as atividades escolar de um profissional
   * @param ProfissionalEscola $oProfissional
   * @return AtividadeProfissionalEscola[]
   */
  public static function getByProfissional(ProfissionalEscola $oProfissional) {

    $sWhere            = " ed22_i_rechumanoescola = {$oProfissional->getCodigo()} ";
    $oDaoRecHumanoAtiv = new cl_rechumanoativ();
    $sSqlAtividade     = $oDaoRecHumanoAtiv->sql_query_file(null, "ed22_i_codigo", null, $sWhere);
    $rsAtividade       = db_query($sSqlAtividade);

    $oMsgErro = new stdClass();
    if (!$rsAtividade) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "erro_buscar_atividade", $oMsgErro) );
    }

    $aAtividades = array();
    if (pg_num_rows($rsAtividade) == 0) {
      return $aAtividades;
    }

    $iLinhas = pg_num_rows($rsAtividade);
    for ($i = 0; $i < $iLinhas; $i++) {

      $iCodigo       = db_utils::fieldsMemory($rsAtividade, $i)->ed22_i_codigo;
      $aAtividades[] = AtividadeProfissionalEscolaRepository::getByCodigo($iCodigo);
    }

    return $aAtividades;
  }

  /**
   * Retorna a instancia da classe
   * @return AtividadeProfissionalEscolaRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new AtividadeProfissionalEscolaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um AtividadeProfissionalEscola ao repositorio
   * @param AtividadeProfissionalEscola $oAtividadeProfissionalEscola Instancia de AtividadeProfissionalEscola
   * @return boolean
   */
  public static function adicionarAtividade(AtividadeProfissionalEscola $oAtividadeProfissionalEscola) {

    if(!array_key_exists($oAtividadeProfissionalEscola->getCodigo(), AtividadeProfissionalEscolaRepository::getInstance()->aAtividade)) {
      AtividadeProfissionalEscolaRepository::getInstance()->aAtividade[$oAtividadeProfissionalEscola->getCodigo()] = $oAtividadeProfissionalEscola;
    }
    return true;
  }

  /**
   * Remove o AtividadeProfissionalEscola passado como parametro do repository
   * @param AtividadeProfissionalEscola $oAtividadeProfissionalEscola
   * @return boolean
   */
  public static function removerAtividade(AtividadeProfissionalEscola $oAtividadeProfissionalEscola) {

    if (array_key_exists($oAtividadeProfissionalEscola->getCodigo(), AtividadeProfissionalEscolaRepository::getInstance()->aAtividade)) {
      unset(AtividadeProfissionalEscolaRepository::getInstance()->aAtividade[$oAtividadeProfissionalEscola->getCodigo()]);
    }
    return true;
  }

}
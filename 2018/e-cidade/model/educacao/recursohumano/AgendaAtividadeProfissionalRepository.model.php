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
 * @version    $Revision: 1.1 $
 */
class AgendaAtividadeProfissionalRepository {

  /**
   * Collection de AgendaAtividadeProfissional
   * @var array
   */
  private $aAgenda = array();

  /**
   * Instancia da classe
   * @var AgendaAtividadeProfissionalRepository
   */
  private static $oInstance;

  private function __construct() {

  }
  private function __clone() {

  }

  /**
   * Retorno uma instancia de uma atividade profissional
   * @param integer $iCodigo
   * @return AgendaAtividadeProfissional
   */
  public static function getByCodigo($iCodigo) {

    if (!array_key_exists($iCodigo, AgendaAtividadeProfissionalRepository::getInstance()->aAgenda)) {
      AgendaAtividadeProfissionalRepository::getInstance()->aAgenda[$iCodigo] = new AgendaAtividadeProfissional($iCodigo);
    }
    return AgendaAtividadeProfissionalRepository::getInstance()->aAgenda[$iCodigo];
  }

  /**
   * Retorna uma agenda do exercício da atividade do profissional
   * @param AtividadeProfissionalEscola $oAtividade
   * @return AgendaAtividadeProfissional[]
   */
  public static function getByAtividadeProfissional(AtividadeProfissionalEscola $oAtividade) {

    $sWhere     = " ed129_rechumanoativ = {$oAtividade->getCodigo()} ";
    $oDaoAgenda = new cl_agendaatividade();
    $sSqlAgenda = $oDaoAgenda->sql_query_file(null, "ed129_codigo", null, $sWhere);
    $rsAgenda   = db_query($sSqlAgenda);

    $oMsgErro = new stdClass();
    if (!$rsAgenda) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(self::MSG_AGENDAATIVIDADEPROFISSIONAL . "erro_buscar_atividade", $oMsgErro) );
    }

    $aAgendas = array();
    if (pg_num_rows($rsAgenda) == 0) {
      return $aAgendas;
    }

    $iLinhas = pg_num_rows($rsAgenda);
    for ($i = 0; $i < $iLinhas; $i++) {

      $iCodigo    = db_utils::fieldsMemory($rsAgenda, $i)->ed129_codigo;
      $aAgendas[] = AgendaAtividadeProfissionalRepository::getByCodigo($iCodigo);
    }

    return $aAgendas;
  }

  /**
   * Retorna a instancia da classe
   * @return AgendaAtividadeProfissionalRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {

      self::$oInstance = new AgendaAtividadeProfissionalRepository();
    }
    return self::$oInstance;
  }

  /**
   * Adiciona um AgendaAtividadeProfissional ao repositorio
   * @param AgendaAtividadeProfissional $oAgendaAtividadeProfissional Instancia de AgendaAtividadeProfissional
   * @return boolean
   */
  public static function adicionarAgenda(AgendaAtividadeProfissional $oAgendaAtividadeProfissional) {

    if(!array_key_exists($oAgendaAtividadeProfissional->getCodigo(), AgendaAtividadeProfissionalRepository::getInstance()->aAgenda)) {
      AgendaAtividadeProfissionalRepository::getInstance()->aAgenda[$oAgendaAtividadeProfissional->getCodigo()] = $oAgendaAtividadeProfissional;
    }
    return true;
  }

  /**
   * Remove o AgendaAtividadeProfissional passado como parametro do repository
   * @param AgendaAtividadeProfissional $oAgendaAtividadeProfissional
   * @return boolean
   */
  public static function removerAgenda(AgendaAtividadeProfissional $oAgendaAtividadeProfissional) {

    if (array_key_exists($oAgendaAtividadeProfissional->getCodigo(), AgendaAtividadeProfissionalRepository::getInstance()->aAgenda)) {
      unset(AgendaAtividadeProfissionalRepository::getInstance()->aAgenda[$oAgendaAtividadeProfissional->getCodigo()]);
    }
    return true;
  }

}
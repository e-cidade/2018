<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\Efetividade\Repository;

use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada as JornadaModel;

/**
 * Classe responsável pelas buscas e ações referentes a jornada
 * Class Jornada
 * @package ECidade\RecursosHumanos\RH\Efetividade\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Jornada extends \BaseClassRepository {

  /**
   * Sobrescreve o atributo da classe pai para
   * manter apenas as referências da classe atual
   */
  protected static $oInstance;

  /**
   * Retorna uma instância de Jornada
   * @param  $iCodigo
   * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada|null
   * @throws \DBException
   */
  protected function make($iCodigo) {

    $oDaoJornada = new \cl_jornada();
    $sSqlJornada = $oDaoJornada->sql_query_file(null, '*', null, "rh188_sequencial = {$iCodigo}");
    $rsJornada   = db_query($sSqlJornada);

    if(!$rsJornada) {
      throw new \DBException("Erro ao buscar as informações da jornada.");
    }

    if(pg_num_rows($rsJornada) == 0) {
      return null;
    }

    return \db_utils::makeFromRecord($rsJornada, function($oRetorno) {

      $oJornada = new JornadaModel();
      $oJornada->setCodigo($oRetorno->rh188_sequencial);
      $oJornada->setDescricao($oRetorno->rh188_descricao);
      $oJornada->setHoras(JornadaHoras::getHorasPorJornada($oJornada));
      $oJornada->setFixo($oRetorno->rh188_fixo == 't');
      $oJornada->setDSR($oRetorno->rh188_tipo == 'D');
      $oJornada->setFolga($oRetorno->rh188_tipo == 'F');
      $oJornada->setDiaTrabalhado($oRetorno->rh188_tipo == 'T');
      $oJornada->setTipoDescricao($oRetorno->rh188_tipo);

      return $oJornada;
    }, 0);
  }

  public static function getInstanciaByCodigo($iCodigo) {
    return self::getInstanciaPorCodigo($iCodigo);
  }
}

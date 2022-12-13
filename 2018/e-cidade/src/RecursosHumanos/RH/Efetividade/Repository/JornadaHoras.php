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
 * Classe referente as informações de horas de uma jornada
 * Class JornadaHoras
 * @package ECidade\RecursosHumanos\RH\Efetividade\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class JornadaHoras {

  /**
   * Retorna uma coleção de objetos com as informações das horas de uma jornada
   * @param JornadaModel $oJornada
   * @return \stdClass[]
   * @throws \DBException
   */
  public static function getHorasPorJornada(JornadaModel $oJornada) {

    $oDaoJornadaHoras = new \cl_jornadahoras();
    $sSqlJornadaHoras = $oDaoJornadaHoras->sql_query_file(
      null,
      'rh189_tiporegistro, rh189_hora',
      'rh189_tiporegistro',
      "rh189_jornada = {$oJornada->getCodigo()}"
    );
    $rsJornadaHoras   = db_query($sSqlJornadaHoras);

    if(!$rsJornadaHoras) {
      throw new \DBException('Erro ao buscar as horas da jornada.');
    }

    if(pg_num_rows($rsJornadaHoras) == 0) {
      return array();
    }

    return \db_utils::makeCollectionFromRecord($rsJornadaHoras, function($oRetorno) {

      $oDadosJornadaHora                = new \stdClass();
      $oDadosJornadaHora->iTipoRegistro = $oRetorno->rh189_tiporegistro;
      $oDadosJornadaHora->sTipoRegistro = JornadaHoras::getTipoEntrada($oRetorno->rh189_tiporegistro);
      $oDadosJornadaHora->oHora         = new \DateTime($oRetorno->rh189_hora);
      $oDadosJornadaHora->sHora         = $oRetorno->rh189_hora;

      return $oDadosJornadaHora;
    });
  }

  /**
   * Tipos de entradas permitidas no cadastro de horas da jornada
   * @param integer $iTipoEntrada
   * @return string
   */
  public static function getTipoEntrada($iTipoEntrada) {

    $aTipoEntrada = array(
      1 => 'ENTRADA 1',
      2 => 'SAIDA 1',
      3 => 'ENTRADA 2',
      4 => 'SAIDA 2'
    );

    return $aTipoEntrada[$iTipoEntrada];
  }
}

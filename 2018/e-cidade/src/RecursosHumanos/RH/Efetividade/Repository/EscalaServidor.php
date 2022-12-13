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

use ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor as EscalaServidorModel;

/**
 * Classe responsável pelas buscas e ações referentes a escala do servidor
 *
 * Class EscalaServidor
 * @package ECidade\RecursosHumanos\RH\Efetividade\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class EscalaServidor {

  /**
   * Retorna uma coleção de EscalaServidor ou um item de escal de servidor se informado uma data no parâmetro
   *
   * @param \Servidor $oServidor
   * @param \DBDate   $oDataPonto
   * @return mixed
   * @throws \DBException
   */
  public static function getEscalas(\Servidor $oServidor, $oDataPonto = null) {

    $oDaoEscalaServidor     = new \cl_escalaservidor();
    $sCaseEscalaPosterior   = "case when";
    $sCaseEscalaPosterior  .= " exists(select 1";
    $sCaseEscalaPosterior  .= "          from escalaservidor es";
    $sCaseEscalaPosterior  .= "         where escalaservidor.rh192_sequencial <> es.rh192_sequencial";
    $sCaseEscalaPosterior  .= "           AND escalaservidor.rh192_dataescala > es.rh192_dataescala)";
    $sCaseEscalaPosterior  .= "     then true";
    $sCaseEscalaPosterior  .= "     else false";
    $sCaseEscalaPosterior  .= " end as tem_escala_posterior";
    $sCamposEscalaServidor  = "escalaservidor.*, {$sCaseEscalaPosterior}";
    $aWhereEscalaServidor   = array();
    
    $aWhereEscalaServidor[] = "rh192_regist = {$oServidor->getMatricula()}";
    $aWhereEscalaServidor[] = "rh192_instit = " . db_getsession("DB_instit");
    
    $sOrderEscalaServidor   = null;

    if(!empty($oDataPonto)) {

      $aWhereEscalaServidor[] = "rh192_dataescala <= '{$oDataPonto->getDate()}'";
      $sOrderEscalaServidor   = "rh192_dataescala desc limit 1";
    }

    $sSqlEscalaServidor = $oDaoEscalaServidor->sql_query_file(
      null,
      $sCamposEscalaServidor,
      $sOrderEscalaServidor,
      implode(" AND ", $aWhereEscalaServidor)
    );
    $rsEscalaServidor   = db_query($sSqlEscalaServidor);

    if(!$rsEscalaServidor) {
      throw new \DBException("Erro ao buscar a escala do servidor.");
    }

    if(pg_num_rows($rsEscalaServidor) == 0) {
      return null;
    }

    $oClosureTrataRetorno = function($oRetorno) use ($oServidor) {

      $oEscalaServidor = new EscalaServidorModel();
      $oEscalaServidor->setCodigo($oRetorno->rh192_sequencial);
      $oEscalaServidor->setServidor($oServidor);
      $oEscalaServidor->setEscalaTrabalho(EscalaTrabalho::getInstanciaPorCodigo($oRetorno->rh192_gradeshorarios));
      $oEscalaServidor->setDataEscala(new \DBDate($oRetorno->rh192_dataescala));

      if($oRetorno->tem_escala_posterior) {

        $oEscalaPosterior = EscalaServidor::getEscalaPosterior($oEscalaServidor);
        if(!is_null($oEscalaPosterior)) {
          $oEscalaServidor->setEscalaPosterior($oEscalaPosterior);
        }
      }

      return $oEscalaServidor;
    };
    
    if(!empty($oDataPonto)) {
      return \db_utils::makeFromRecord($rsEscalaServidor, $oClosureTrataRetorno, 0);
    }

    return \db_utils::makeCollectionFromRecord($rsEscalaServidor, $oClosureTrataRetorno);
  }

  /**
   * Retorna uma instância de EscalaServidor de uma escala cadastrada com data posterior a escala atual
   * @param EscalaServidorModel $oEscalaServidor
   * @return EscalaServidorModel|null
   * @throws \DBException
   */
  public static function getEscalaPosterior(EscalaServidorModel $oEscalaServidor) {

    $oDaoEscalaServidor    = new \cl_escalaservidor();
    $sWhereEscalaServidor  = "     rh192_regist     = {$oEscalaServidor->getServidor()->getMatricula()}";
    $sWhereEscalaServidor .= " AND rh192_dataescala > '{$oEscalaServidor->getDataEscala()->getDate()}'";
    $sWhereEscalaServidor .= " AND rh192_sequencial <> {$oEscalaServidor->getCodigo()}";
    $sSqlEscalaServidor    = $oDaoEscalaServidor->sql_query_file(null, '*', null, $sWhereEscalaServidor);
    $rsEscalaServidor      = db_query($sSqlEscalaServidor);

    if(!$rsEscalaServidor) {
      throw new \DBException('Erro ao verificar se o servidor possui mais escalas.');
    }

    if(pg_num_rows($rsEscalaServidor) == 0) {
      return null;
    }

    return \db_utils::makeFromRecord($rsEscalaServidor, function($oRetorno) {

      $oEscalaServidor = new EscalaServidorModel();
      $oEscalaServidor->setCodigo($oRetorno->rh192_sequencial);
      $oEscalaServidor->setServidor(\ServidorRepository::getInstanciaByCodigo($oRetorno->rh192_regist));
      $oEscalaServidor->setEscalaTrabalho(EscalaTrabalho::getInstanciaPorCodigo($oRetorno->rh192_gradeshorarios));
      $oEscalaServidor->setDataEscala(new \DBDate($oRetorno->rh192_dataescala));

      return $oEscalaServidor;
    });
  }
}

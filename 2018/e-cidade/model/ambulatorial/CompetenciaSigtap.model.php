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
 * Singleton para retornar a ultima competência da importação dos procedimentos do SIGTAP
 *
 * @package  ambulatorial
 * @author   Andrio Costa  <andrio.costa@dbseller.com.br>
 * @author   Mariana Reck  <mariana.reck@dbseller.com.br>
 * @revision $Revision $
 */
class CompetenciaSigtap {

  /**
   * Ultima competencia da importação das tabelas do SIGTAP
   * @var DBCompetencia
   */
  static private $oCompetencia;

  private function __construct(){}
  private function __clone() {}

  /**
   * Retorna a última competência do SIGTAP
   *
   * @return DBCompetencia
   * @throws Exception
   */
  public static function getCompetencia() {

    if ( !self::$oCompetencia ) {

      $oDaoAtualiza = new cl_sau_atualiza();
      $sSqlAtualiza = $oDaoAtualiza->sql_query_file(null, "s100_i_codigo, s100_i_mescomp, s100_i_anocomp", "1 desc limit 1");
      $rsAtualiza   = db_query($sSqlAtualiza);

      if ( !$rsAtualiza ) {
        throw new Exception( "Não foi possível buscar a ultima competência." . pg_last_error() );
      }

      $oDados             = db_utils::fieldsMemory($rsAtualiza, 0);
      self::$oCompetencia = new DBCompetencia($oDados->s100_i_anocomp, $oDados->s100_i_mescomp);
    }

    return self::$oCompetencia;
  }

}

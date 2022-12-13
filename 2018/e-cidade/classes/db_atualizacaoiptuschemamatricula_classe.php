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
 * Class cl_atualizacaoiptuschemamatricula
 */
class cl_atualizacaoiptuschemamatricula extends DAOBasica {

  public function __construct() {
    parent::__construct('cadastro.atualizacaoiptuschemamatricula');
  }

  /**
   * Retorna todas as Matriculas do lote coma sua situaчlуo de importaчуo
   * @param $sSchema
   * @param $idBql
   * @return \stdClass[]
   * @throws \DBException
   */
  public function matriculaNoLoteDaImportacao($sSchema, $idBql) {

    $sSqlMatriculas  = "select j01_matric as matricula, ";
    $sSqlMatriculas .= "  (select j144_situacao ";
    $sSqlMatriculas .= "     from  cadastro.atualizacaoiptuschema";
    $sSqlMatriculas .= "           inner join cadastro.atualizacaoiptuschemamatricula on j144_atualizacaoiptuschema = j142_sequencial";
    $sSqlMatriculas .= "     where j142_schema    = '{$sSchema}'";
    $sSqlMatriculas .= "       and j144_matricula = j01_matric) as situacao";
    $sSqlMatriculas .= "  from {$sSchema}.iptubase ";
    $sSqlMatriculas .= " where j01_idbql = {$idBql} ";
    $sSqlMatriculas .= "    and j01_baixa is null ";

    $rsDadosMatriculas = db_query($sSqlMatriculas);
    if (!$rsDadosMatriculas) {
      throw new DBException("Erro ao consultar matriculas no lote {$idBql} da importaчуo {$sSchema}");
    }
    if (pg_num_rows($rsDadosMatriculas) == 0) {
      return array();
    }

    $matriculas = \db_utils::makeCollectionFromRecord($rsDadosMatriculas, function($dados) {
       return $dados;
    });
    return $matriculas;
  }

  public function buscaSetoresQuadras( $sCampos, $sWhere, $sGroup, $sOrder, $sSchema = 'cadastro' ) {

    $aSql = array();
    $aSql[] = "select {$sCampos} from atualizacaoiptuschemamatricula";
    $aSql[] = "inner join {$sSchema}.iptubase on {$sSchema}.iptubase.j01_matric = atualizacaoiptuschemamatricula.j144_matricula";
    $aSql[] = "inner join {$sSchema}.lote     on {$sSchema}.lote.j34_idbql      = {$sSchema}.iptubase.j01_idbql";
    $aSql[] = "inner join {$sSchema}.setor    on {$sSchema}.setor.j30_codi      = {$sSchema}.lote.j34_setor";
    $aSql[] = "where {$sWhere}";
    $aSql[] = "group by {$sGroup}";
    $aSql[] = "order by {$sOrder}";

    return implode(' ', $aSql);
  }
}

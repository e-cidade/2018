<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

class cl_escalaservidor extends DAOBasica {

  function __construct() {
    parent::__construct("recursoshumanos.escalaservidor");
  }

  function sql_diasTrabalhados ($iMatricula, $dDataInicioEfetividade, $dDataFechamentoEfetividade)  {

    $sSqlDiasEscala  = "select *                                                                                                                                      ";
    $sSqlDiasEscala .= "  from (select escalaservidor.rh192_regist,                                                                                                   ";
    $sSqlDiasEscala .= "       gradeshorarios.rh190_sequencial,                                                                                                       ";
    $sSqlDiasEscala .= "       gradeshorarios.rh190_descricao,                                                                                                        ";
    $sSqlDiasEscala .= "       escalasperiodo.data,                                                                                                                   ";
    $sSqlDiasEscala .= "       (escalasperiodo.data - gradeshorarios.rh190_database) % (select max(rh191_ordemhorario)                                                ";
    $sSqlDiasEscala .= "                                                                  from recursoshumanos.gradeshorariosjornada                                  ";
    $sSqlDiasEscala .= "                                                                 where rh191_gradehorarios = gradeshorarios.rh190_sequencial) + 1 as ordem    ";
    $sSqlDiasEscala .= "  from ( select (select rh192_sequencial                                                                                                      ";
    $sSqlDiasEscala .= "                   from recursoshumanos.escalaservidor                                                                                        ";
    $sSqlDiasEscala .= "                  where rh192_regist = {$iMatricula}                                                                                          ";
    $sSqlDiasEscala .= "                    and rh192_dataescala <= escalas.data                                                                                      ";
    $sSqlDiasEscala .= "                  order by rh192_dataescala desc                                                                                              ";
    $sSqlDiasEscala .= "                  limit 1)                        as codigo_escala,                                                                           ";
    $sSqlDiasEscala .= "                to_char(data, 'YYYY-mm-dd')::date as data                                                                                     ";
    $sSqlDiasEscala .= "           from (select generate_series(('{$dDataInicioEfetividade}'::date)::timestamp,                                                       ";
    $sSqlDiasEscala .= "                                        ('{$dDataFechamentoEfetividade}'::date)::timestamp, '1 day') as data ) as escalas ) as escalasperiodo ";
    $sSqlDiasEscala .= "                inner join recursoshumanos.escalaservidor on escalaservidor.rh192_sequencial = escalasperiodo.codigo_escala                         ";
    $sSqlDiasEscala .= "                inner join recursoshumanos.gradeshorarios on gradeshorarios.rh190_sequencial = escalaservidor.rh192_gradeshorarios                  ";
    $sSqlDiasEscala .= "          group by gradeshorarios.rh190_sequencial,                                                                                           ";
    $sSqlDiasEscala .= "                   gradeshorarios.rh190_descricao,                                                                                            ";
    $sSqlDiasEscala .= "                   escalasperiodo.data,                                                                                                       ";
    $sSqlDiasEscala .= "                   gradeshorarios.rh190_database,                                                                                             ";
    $sSqlDiasEscala .= "                   escalaservidor.rh192_dataescala,                                                                                           ";
    $sSqlDiasEscala .= "                   escalaservidor.rh192_regist                                                                                                ";
    $sSqlDiasEscala .= "          order by escalasperiodo.data) as escaladias                                                                                         ";
    $sSqlDiasEscala .= " inner join recursoshumanos.gradeshorariosjornada on gradeshorariosjornada.rh191_gradehorarios = escaladias.rh190_sequencial                  ";
    $sSqlDiasEscala .= "                                                 and gradeshorariosjornada.rh191_ordemhorario  = escaladias.ordem                             ";

    return $sSqlDiasEscala;
  }

  function sqlEscalaTrabalhoJornada($sCampos = '*', $sOrdenacao = '', array $aWhere = array()) {

    $sSql  = "select {$sCampos}";
    $sSql .= "  from escalaservidor";
    $sSql .= "       inner join gradeshorarios        on rh192_gradeshorarios = rh190_sequencial";
    $sSql .= "       inner join gradeshorariosjornada on rh191_gradehorarios  = rh190_sequencial";
    $sSql .= "       inner join jornada               on rh188_sequencial     = rh191_jornada";

    if(count($aWhere) > 0) {
      $sSql .= " where " . implode(' AND ', $aWhere);
    }

    if(!empty($sOrdenacao)) {
      $sSql .= " order by {$sOrdenacao}";
    }

    return $sSql;
  }
}
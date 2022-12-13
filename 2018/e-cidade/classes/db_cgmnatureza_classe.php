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

class cl_cgmnatureza extends DAOBasica {

  public function __construct() {
    parent::__construct("caixa.cgmnatureza");
  }

  /**
   * Busca os empenhos com movimentos na agenda que tenham natureza de CGM configurada
   * @param string $fields
   * @param null   $order
   * @param null   $where
   * @return string
   */
  public function sql_query_empenho($fields = "*", $order = null, $where = null) {

    $sql  = " select {$fields} ";
    $sql .= "   from cgmnatureza";
    $sql .= "        inner join cgm        on cgm.z01_numcgm = cgmnatureza.c05_numcgm";
    $sql .= "        inner join empempenho on empempenho.e60_numcgm = cgm.z01_numcgm";
    $sql .= "        inner join empagemov  on empagemov.e81_numemp = empempenho.e60_numemp";

    if (!empty($where)) {
      $sql .= " where {$where} ";
    }
    if (!empty($order)) {
      $sql .= " order by {$order} ";
    }
    return $sql;
  }

  /**
   * Busca os slips com movimentos na agenda que tenham natureza de CGM configurada
   * @param string $fields
   * @param null   $order
   * @param null   $where
   * @return string
   */
  public function sql_query_slip($fields = "*", $order = null, $where = null) {

    $sql  = " select {$fields} ";
    $sql .= "   from cgmnatureza";
    $sql .= "        inner join slipnum   on slipnum.k17_numcgm = cgmnatureza.c05_numcgm";
    $sql .= "        inner join slip      on slip.k17_codigo = slipnum.k17_codigo ";
    $sql .= "        inner join empageslip on empageslip.e89_codigo = slip.k17_codigo ";
    $sql .= "        inner join empagemov  on empagemov.e81_codmov = empageslip.e89_codmov";

    if (!empty($where)) {
      $sql .= " where {$where} ";
    }
    if (!empty($order)) {
      $sql .= " order by {$order} ";
    }
    return $sql;
  }
}

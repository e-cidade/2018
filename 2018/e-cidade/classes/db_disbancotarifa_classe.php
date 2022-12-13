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
 * Class cl_disbancotarifa
 */
class cl_disbancotarifa extends DAOBasica {

  /**
   * cl_disbancotarifa constructor.
   */
  public function __construct() {
    parent::__construct('caixa.disbancotarifa');
  }


  /**
   * @param null $sequencial
   * @param string $campos
   * @param null $order
   * @param null $where
   * @return string
   */
  public function sql_query_tarifas($sequencial = null, $campos = "*", $order = null, $where = null) {

    $sql  = " select {$campos} ";
    $sql .= "   from disbancotarifa";
    $sql .= "        inner join formaarrecadacao on formaarrecadacao.k178_sequencial = disbancotarifa.k179_formaarrecadacao ";
    $sql .= "        inner join disbanco on disbanco.idret = disbancotarifa.k179_idret ";
    $sql .= "        inner join disarq on disarq.codret = disbanco.codret ";

    if (!empty($sequencial)) {
      $sql .= " where k179_sequencial = {$sequencial} ";
    } else {

      if (!empty($where)) {
        $sql .= " where $where ";
      }

      if (!empty($order)) {
        $sql .= " order by $order ";
      }
    }
    return $sql;
  }
}
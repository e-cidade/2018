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

class cl_limiteatendimento extends DAOBasica {

  function __construct() {
    parent::__construct("laboratorio.limiteatendimento");
  }

  public function sql_limiteLaboratorio($campos = "*", $order = null, $where = null) {

    $sSql  = "select {$campos} ";

    $sSql .= " from limiteatendimento ";
    $sSql .= " join lab_laboratorio  on lab_laboratorio.la02_i_codigo   = limiteatendimento.la45_lab_laboratorio ";
    $sSql .= " join lab_labsetor     on lab_labsetor.la24_i_laboratorio = lab_laboratorio.la02_i_codigo ";
    $sSql .= " join lab_setorexame   on lab_setorexame.la09_i_labsetor  = lab_labsetor.la24_i_codigo ";
    $sSql .= " join lab_exame        on lab_exame.la08_i_codigo         = lab_setorexame.la09_i_exame ";

    if (!empty($where)) {
      $sSql .= " where {$where}";
    }

    if (!empty($order)) {
      $sSql .= " order by {$order}";
    }
    return $sSql;
  }
}
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

class cl_assentamentofuncional extends DAOBasica {

  function __construct() {
    parent::__construct("recursoshumanos.assentamentofuncional");
  }

  function sql_query($iCodigo = null, $mCampos = "*", $mOrdem = null, $sWhere = null, $sJoin = null) {

    $sql = "SELECT ";

    if(!empty($mCampos)) {
      if(is_array($mCampos) && count($mCampos) > 0) {
        $sql .= join($mCampos);
      } else {
        $sql .= $mCampos;
      }
    } else {
      $sql .= "*";
    }

    $sql .= " FROM recursoshumanos.assentamentofuncional ";

    if(empty($sJoin)) {
      $sql .= " INNER JOIN assenta on rh193_assentamento_funcional = h16_codigo";
    } else {
      $sql .= $sJoin;
    }

    if(!empty($iCodigo)) {
      $sql .= " WHERE rh193_assentamento_funcional = {$iCodigo}";
    } else {
      $sql .= " WHERE ". $sWhere;
    }

    if(!empty($mOrdem)) {
      if(is_array($mOrdem) && count($mOrdem)) {
        $sql .= " ORDER BY ". join($mOrdem);
      } else {
        $sql .= " ORDER BY {$mOrdem}";
      }
    }

    return $sql;
  }
}
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

class cl_pontoeletronicoconfiguracoeslotacao extends DAOBasica {

  function __construct() {
    parent::__construct("recursoshumanos.pontoeletronicoconfiguracoeslotacao");
  }

  public function sql_query_join_cgm($sCodigo = null, $sCampos = "*", $sOrdem = null, $sWhere = null) {
    
    $sSql = $this->sql_query($sCodigo, $sCampos, $sOrdem, $sWhere);

    if(!empty($sWhere)) {
      $sSql = preg_replace("/(where)/i", "INNER JOIN cgm ON z01_numcgm = rh01_numcgm $1", $sSql);
    } else {
      
      if(empty($sOrdem)) {
        $sSql .= " INNER JOIN cgm ON z01_numcgm = rh01_numcgm ";
      } else {
        $sSql = preg_replace("/(order by)/i", "INNER JOIN cgm ON z01_numcgm = rh01_numcgm $1", $sSql);
      }

    }
    
    return $sSql;
  }
}
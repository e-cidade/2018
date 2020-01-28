<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

class cl_pontoeletronicoregistrojustificativa extends DAOBasica {
  
  function __construct() {
    parent::__construct('recursoshumanos.pontoeletronicoregistrojustificativa');
  }

  /**
   * @param array $aCampos
   * @param string $sOrdenacao
   * @param array $aWhere
   * @return string
   */
  public function sqlJustificativasData($aCampos = array(), $sOrdenacao = '', $aWhere = array()) {

    $sCampos = count($aCampos) == 0 ? '*' : implode(', ', $aCampos);
    $sWhere  = count($aWhere) == 0 ? '' : ' WHERE ' . implode(' AND ', $aWhere);

    $sSql  = "SELECT {$sCampos}";
    $sSql .= "  FROM pontoeletronicoregistrojustificativa ";
    $sSql .= "       INNER JOIN pontoeletronicoarquivodataregistro ON pontoeletronicoarquivodataregistro.rh198_sequencial = pontoeletronicoregistrojustificativa.rh199_pontoeletronicoarquivodataregistro ";
    $sSql .= "       INNER JOIN pontoeletronicojustificativa       ON pontoeletronicojustificativa.rh194_sequencial       = pontoeletronicoregistrojustificativa.rh199_pontoeletronicojustificativa ";
    $sSql .= "       INNER JOIN pontoeletronicoarquivodata         ON pontoeletronicoarquivodata.rh197_sequencial         = pontoeletronicoarquivodataregistro.rh198_pontoeletronicoarquivodata ";
    $sSql .= "  {$sWhere}";
    $sSql .= "  {$sOrdenacao}";

    return $sSql;
  }
}
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
class cl_guiarecolhimento extends DAOBasica {

  public function __construct() {
    parent::__construct("caixa.guiarecolhimento");
  }

  public function sql_query_guias_arrepaga($campos="*", $where = null, $order = null) {

    $sSql  = "select {$campos} ";
    $sSql .= "  from caixa.guiarecolhimento ";
    $sSql .= "       inner join protocolo.cgm          on k174_cgm = z01_numcgm ";
    $sSql .= "       inner join caixa.tiporecolhimento on k174_tiporecolhimento = k172_sequencial ";
    $sSql .= "       inner join caixa.unidadegestora   on k174_unidadegestora   = k171_sequencial ";
    $sSql .= "       left  join caixa.arrepaga on k00_numpre = k174_numpre ";
    $sSql .= "       left  join guiarecolhimentocidadao on  guiarecolhimentocidadao.k177_guiarecolhimento = guiarecolhimento.k174_sequencial";
    $sSql .= "       left  join cidadao  on  cidadao.ov02_sequencial = guiarecolhimentocidadao.k177_cidadao ";
    $sSql .= "                          and  cidadao.ov02_seq = guiarecolhimentocidadao.k177_cidadaoseq ";
    if (!empty($where)) {
      $sSql .= " where {$where}";
    }

    if (!empty($order)) {
      $sSql .= " order by {$order}";
    }
    return $sSql;
  }

  /**
   * Retorna todas as guias pagas que possuem processo de workflow
   * @param string $campos
   * @param null   $where
   * @param null   $order
   * @return string
   */
  public function sql_query_guias_workflow($campos="*", $where = null, $order = null) {

    $sSql  = "select {$campos} ";
    $sSql .= "  from caixa.guiarecolhimento ";
    $sSql .= "       inner join protocolo.cgm          on k174_cgm = z01_numcgm ";
    $sSql .= "       inner join caixa.tiporecolhimento on k174_tiporecolhimento = k172_sequencial ";
    $sSql .= "       inner join caixa.unidadegestora   on k174_unidadegestora   = k171_sequencial ";
    $sSql .= "       inner join caixa.arrepaga         on k00_numpre = k174_numpre ";
    $sSql .= "       left join protocolo.protprocesso  on k174_processo = p58_codproc ";
    $sSql .= "       left  join protocolo.procandam    on p58_codandam  = p61_codandam ";
    if (!empty($where)) {
      $sSql .= " where {$where}";
    }

    if (!empty($order)) {
      $sSql .= " order by {$order}";
    }
    return $sSql;

  }

  /**
   * Retorna um stdClass com os valores da PK
   * @param $value
   * @return null|object
   * @throws \DBException
   */
  public function findBydId($value) {

    $sSqlQuery = $this->sql_query_guias_arrepaga("*", "k174_sequencial = ".$value);
    $rsDados   = db_query($sSqlQuery);
    if (!$rsDados) {
      throw  new \DBException('Erro ao pesquisar dados de guia de recolhimento');
    }
    if (pg_num_rows($rsDados) > 0) {
      return pg_fetch_object($rsDados, 0);
    }
    return null;
  }
}
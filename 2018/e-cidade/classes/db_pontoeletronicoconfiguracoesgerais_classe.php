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

class cl_pontoeletronicoconfiguracoesgerais extends DAOBasica {

  function __construct() {
    parent::__construct("recursoshumanos.pontoeletronicoconfiguracoesgerais");
  }

  public function sql_query_join_tipoasse($iCodigo, $sCampos, $sOrdem, $sWhere) {

    $sSql = $this->sql_query($iCodigo, $sCampos, $sOrdem, $sWhere);
    $sSql = str_replace('AND', 'OR', $sSql);
    return $sSql;
  }

  public function sql_query_configuracoes($sOrdem, $sWhere) {

    $sCamposConfiguracoesGerais = " distinct 
                                    unnest(array[
                                      'rh200_tipoasse_extra50diurna',
                                      'rh200_tipoasse_extra75diurna',
                                      'rh200_tipoasse_extra100diurna',
                                      'rh200_tipoasse_extra50noturna',
                                      'rh200_tipoasse_extra75noturna',
                                      'rh200_tipoasse_extra100noturna',
                                      'rh200_tipoasse_adicionalnoturno',
                                      'rh200_tipoasse_falta',
                                      'rh200_tipoasse_faltas_dsr'
                                    ]) as tipo,
                                    unnest(array[
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_extra50diurna,
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_extra75diurna,
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_extra100diurna,
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_extra50noturna,
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_extra75noturna,
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_extra100noturna,
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_adicionalnoturno,
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_falta,
                                      pontoeletronicoconfiguracoesgerais.rh200_tipoasse_faltas_dsr
                                    ]) as codigo,
                                    tipoasse.h12_codigo,
                                    tipoasse.h12_assent,
                                    tipoasse.h12_descr,
                                    rh200_autorizahoraextra";
    $sSqlConfiguracoesGerais = $this->sql_query_join_tipoasse(null, $sCamposConfiguracoesGerais, $sOrdem, $sWhere);

    return "SELECT * FROM ({$sSqlConfiguracoesGerais}) as dados WHERE codigo = h12_codigo ORDER BY codigo";
  }
}
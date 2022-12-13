<?php
/**
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

class CalculoEfetividade {

  public $iDiasAfastados;

  public $iDiasTrabalhados;

  static function calculaEfetividade($iMatricula, $iAnoAfastamento, $iMesAfastamento) {

    if (empty($iMatricula)) {
      throw new Exception ('Matrícula não informada.');
    }

    if (empty($iAnoAfastamento)) {
      throw new Exception ('Ano Afastamento não informado.');
    }

    if (empty($iMesAfastamento)) {
      throw new Exception ('Mês Afastamento não informado.');
    }

    $iMesAfastamento                      = str_pad($iMesAfastamento, 2, '0', STR_PAD_LEFT);
    $oDaoConfiguracoesdatasafastamento    = new cl_configuracoesdatasefetividade();
    $sWhereConfiguracoesdatasafastamento  = "     rh186_exercicio   = {$iAnoAfastamento}";
    $sWhereConfiguracoesdatasafastamento .= " and rh186_competencia = '{$iMesAfastamento}'";
    $sWhereConfiguracoesdatasafastamento .= " and rh186_instituicao = " . db_getsession("DB_instit");
    $sSqlConfiguracoesdatasafastamento    = $oDaoConfiguracoesdatasafastamento->sql_query_file(
      null,
      "*",
      "rh186_competencia",
      $sWhereConfiguracoesdatasafastamento
    );
    $rsConfiguracoesdatasafastamento      = db_query($sSqlConfiguracoesdatasafastamento);

    if (pg_num_rows($rsConfiguracoesdatasafastamento) == 0) {
      throw new Exception("Competência {$iMesAfastamento}/{$iAnoAfastamento} não configurada nas datas da efetividade.");
    }

    $oConfiguracoesdatasafastamento    = db_utils::fieldsmemory($rsConfiguracoesdatasafastamento, 0);

    $iAnoEfetividade = db_anofolha();
    $iMesEfetividade = db_mesfolha() + 2;

    if ($iMesEfetividade > 12) {
      $iMesEfetividade = $iMesEfetividade - 12;
      $iAnoEfetividade = $iAnoEfetividade + 1;
    }

    $iMesEfetividade                      = str_pad($iMesEfetividade, 2, '0', STR_PAD_LEFT);
    $oDaoConfiguracoesdatasefetividade    = new cl_configuracoesdatasefetividade();
    $sWhereConfiguracoesdatasefetividade  = "     rh186_exercicio   = {$iAnoAfastamento}";
    $sWhereConfiguracoesdatasefetividade .= " and rh186_competencia = '{$iMesAfastamento}'";
    $sWhereConfiguracoesdatasefetividade .= " and rh186_instituicao = " . db_getsession("DB_instit");
    $sSqlConfiguracoesdatasefetividade    = $oDaoConfiguracoesdatasefetividade->sql_query_file(
      null,
      "*",
      "rh186_competencia",
      $sWhereConfiguracoesdatasefetividade
    );
    $rsConfiguracoesdatasefetividade      = db_query($sSqlConfiguracoesdatasefetividade);

    if (pg_num_rows($rsConfiguracoesdatasafastamento) == 0) {
      throw new Exception("Competência {$iMesEfetividade}/{$iAnoEfetividade} não configurada nas datas da efetividade.");
    }

    $oConfiguracoesdatasefetividade    = db_utils::fieldsmemory($rsConfiguracoesdatasefetividade, 0);

    $oDaoEscalaServidor                = new cl_escalaservidor();
    $sSqlDiasEscala                    = $oDaoEscalaServidor->sql_diasTrabalhados ($iMatricula, $oConfiguracoesdatasefetividade->rh186_datainicioefetividade, $oConfiguracoesdatasefetividade->rh186_datafechamentoefetividade);
    $rsDiasEscala                      = db_query($sSqlDiasEscala);
    $aEventoDia                        = array();

    //foreach ($aDiasEscala as $oDiaEscala) {
    for ($iDiaEscala =0; $iDiaEscala < pg_num_rows($rsDiasEscala); $iDiaEscala++) {

      $oDiaEscala                      = db_utils::fieldsmemory($rsDiasEscala, $iDiaEscala);

      $oEvento                         = new stdClass();
      $oEvento->sTipo                  = 'jornada';
      $oEvento->lDiaTrabalhado         = in_array($oDiaEscala->jornada, array (1, 2)) ? false : true;
      $aEventoDia[$oDiaEscala->data]   = $oEvento;

    }

    $iAnoFolha            = db_anofolha();
    $iMesFolha            = db_mesfolha();

    $sSqlFeriadosLotacao  = "select r62_data                                                                                                                                              ";
    $sSqlFeriadosLotacao .= "  from rhpessoalmov                                                                                                                                          ";
    $sSqlFeriadosLotacao .= " inner join rhlotacalend on rhlotacalend.rh64_lota = rhpessoalmov.rh02_lota                                                                                  ";
    $sSqlFeriadosLotacao .= " inner join calendf      on calendf.r62_calend     = rhlotacalend.rh64_calend                                                                                ";
    $sSqlFeriadosLotacao .= " where rhpessoalmov.rh02_anousu = {$iAnoFolha}                                                                                                               ";
    $sSqlFeriadosLotacao .= "   and rhpessoalmov.rh02_mesusu = {$iMesFolha}                                                                                                               ";
    $sSqlFeriadosLotacao .= "   and rhpessoalmov.rh02_regist = {$iMatricula}                                                                                                              ";
    $sSqlFeriadosLotacao .= "   and rhpessoalmov.rh02_instit = " . db_getsession('DB_instit') . "                                                                                         ";
    $sSqlFeriadosLotacao .= "   and calendf.r62_data between '{$oConfiguracoesdatasefetividade->rh186_datainicioefetividade}' and '{$oConfiguracoesdatasefetividade->rh186_datafechamentoefetividade}'";
    $rsFeriadosLotacao    = db_query($sSqlFeriadosLotacao);

    for ($iFeriadoLotacao = 0; $iFeriadoLotacao < pg_num_rows($rsFeriadosLotacao); $iFeriadoLotacao++) {

      $oFeriadoLotacao                        = db_utils::fieldsmemory($rsFeriadosLotacao, $iFeriadoLotacao);
      $oEvento                                = new stdClass();
      $oEvento->sTipo                         = 'feriado';
      $oEvento->lDiaTrabalhado                = false;
      $aEventoDia[$oFeriadoLotacao->r62_data] = $oEvento;

    }

    $iDiasTrabalhados = 0;
    foreach ( $aEventoDia as $oEventoDia ) {

      if ($oEventoDia->lDiaTrabalhado) {
        $iDiasTrabalhados++;
      }

    }

    $sSqlDiasEscala     = $oDaoEscalaServidor->sql_diasTrabalhados ($iMatricula, $oConfiguracoesdatasafastamento->rh186_datainicioefetividade, $oConfiguracoesdatasafastamento->rh186_datafechamentoefetividade);
    $rsDiasEscala       = db_query($sSqlDiasEscala);
    $aEventoAfastamento = array();

    for ($iDiaEscala = 0; $iDiaEscala < pg_num_rows($rsDiasEscala); $iDiaEscala++) {

      $oDiaEscala                            = db_utils::fieldsmemory($rsDiasEscala, $iDiaEscala);

      $oEvento                               = new stdClass();
      $oEvento->sTipo                        = 'jornada';
      $oEvento->lDiaTrabalhado               = in_array($oDiaEscala->jornada, array (1, 2)) ? false : true;
      $aEventoAfastamento[$oDiaEscala->data] = $oEvento;

    }

    $sSqlFeriadosLotacao  = "select r62_data                                                                                                                                              ";
    $sSqlFeriadosLotacao .= "  from rhpessoalmov                                                                                                                                          ";
    $sSqlFeriadosLotacao .= " inner join rhlotacalend on rhlotacalend.rh64_lota = rhpessoalmov.rh02_lota                                                                                  ";
    $sSqlFeriadosLotacao .= " inner join calendf      on calendf.r62_calend     = rhlotacalend.rh64_calend                                                                                ";
    $sSqlFeriadosLotacao .= " where rhpessoalmov.rh02_anousu = {$iAnoFolha}                                                                                                               ";
    $sSqlFeriadosLotacao .= "   and rhpessoalmov.rh02_mesusu = {$iMesFolha}                                                                                                               ";
    $sSqlFeriadosLotacao .= "   and rhpessoalmov.rh02_regist = {$iMatricula}                                                                                                              ";
    $sSqlFeriadosLotacao .= "   and rhpessoalmov.rh02_instit = " . db_getsession('DB_instit') . "                                                                                         ";
    $sSqlFeriadosLotacao .= "   and calendf.r62_data between '{$oConfiguracoesdatasafastamento->rh186_datainicioefetividade}' and '{$oConfiguracoesdatasafastamento->rh186_datafechamentoefetividade}'";
    $rsFeriadosLotacao    = db_query($sSqlFeriadosLotacao);

    for ($iFeriadoLotacao = 0; $iFeriadoLotacao < pg_num_rows($rsFeriadosLotacao); $iFeriadoLotacao++) {

      $oFeriadoLotacao                                = db_utils::fieldsmemory($rsFeriadosLotacao, $iFeriadoLotacao);

      $oEvento                                        = new stdClass();
      $oEvento->sTipo                                 = 'feriado';
      $oEvento->lDiaTrabalhado                        = false;
      $aEventoAfastamento[$oFeriadoLotacao->r62_data] = $oEvento;
    }

    $oDaoAssenta         = new cl_assenta();
    $sCamposAssenta      = "h12_assent,                                                                                                                         ";
    $sCamposAssenta     .= "to_date(generate_series((case when h16_dtconc < '$oConfiguracoesdatasafastamento->rh186_datainicioefetividade'                            ";
    $sCamposAssenta     .= "                            then '$oConfiguracoesdatasafastamento->rh186_datainicioefetividade'                                           ";
    $sCamposAssenta     .= "                            else h16_dtconc                                                                                         ";
    $sCamposAssenta     .= "                        end)::timestamp,                                                                                            ";
    $sCamposAssenta     .= "                        (case when h16_dtterm is null or h16_dtterm > '{$oConfiguracoesdatasafastamento->rh186_datafechamentoefetividade}'";
    $sCamposAssenta     .= "                          then '{$oConfiguracoesdatasafastamento->rh186_datafechamentoefetividade}'                                       ";
    $sCamposAssenta     .= "                          else h16_dtterm                                                                                           ";
    $sCamposAssenta     .= "                        end)::timestamp, '1 day')::varchar, 'YYYY-mm-dd') as h16_dtconc                                             ";

    $sWhereAssenta       = "    (h12_tipo   = 'A'                                                                                                                                  ";
    $sWhereAssenta      .= "  or trim(h12_assent) = 'D-EXT')                                                                                                                       ";
    $sWhereAssenta      .= "and h16_regist = {$iMatricula}                                                                                                                         ";
    $sWhereAssenta      .= "and ((h16_dtconc::date || ' 00:00:00')::timestamp, 
                                   case when h16_dtterm is null then '$oConfiguracoesdatasafastamento->rh186_datafechamentoefetividade 23:59:59'::timestamp else (h16_dtterm::date || ' 23:59:59')::timestamp end) 
                         overlaps 
                                    ('$oConfiguracoesdatasafastamento->rh186_datainicioefetividade 00:00:00'::timestamp, 
                                     '$oConfiguracoesdatasafastamento->rh186_datafechamentoefetividade 23:59:59'::timestamp)  ";

    $sSqlAssenta         = $oDaoAssenta->sql_query_tipo(null, $sCamposAssenta, "h16_dtconc", $sWhereAssenta);
    $rsAssenta           = db_query($sSqlAssenta);
    $iDiasAfastados      = 0;

    for ($iAfastamento = 0; $iAfastamento < pg_num_rows($rsAssenta); $iAfastamento++) {

      $oAfastamento = db_utils::fieldsmemory($rsAssenta, $iAfastamento);

      if (isset($aEventoAfastamento[$oAfastamento->h16_dtconc])
        and $aEventoAfastamento[$oAfastamento->h16_dtconc]->lDiaTrabalhado == true
        and trim($oAfastamento->h12_assent) != 'D-EXT') {

        $iDiasAfastados++;
      }

      //Caso o afastamento for D-EXT(DIA EXTRA), soma mais um dia aos dias trabalhados
      if (trim($oAfastamento->h12_assent) == 'D-EXT') {
        $iDiasTrabalhados++;
      }
    }

    return (Object) array('iDiasAfastados' => $iDiasAfastados, 'iDiasTrabalhados' => $iDiasTrabalhados);
  }
}
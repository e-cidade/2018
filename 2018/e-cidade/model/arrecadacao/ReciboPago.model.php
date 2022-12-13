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

class ReciboPago {

  private $oDataInicio;

  private $oDataFim;

  private $iCodigoArrecadacao;

  private $iTipoDebito;

  public function getDataInicio(){
    return $this->oDataInicio;
  }

  public function getDataFim(){
    return $this->oDataFim;
  }

  public function getCodigoArrecadacao(){
    return $this->iCodigoArrecadacao;
  }

  public function getTipoDebito(){
    return $this->iTipoDebito;
  }

  public function setDataInicio(DateTime $oDataInicio){
    $this->oDataInicio = $oDataInicio;
  }

  public function setDataFim(DateTime $oDataFim){
    $this->oDataFim = $oDataFim;
  }

  public function setCodigoArrecadacao($iCodigoArrecadacao){
    $this->iCodigoArrecadacao = $iCodigoArrecadacao;
  }

  public function setTipoDebito($iTipoDebito){
    $this->iTipoDebito = $iTipoDebito;
  }

  public function getReciboPagoCgm($iCgm){

    $sSqlInnerArreNumCgmArreIdRet = " inner join arrenumcgm on arrenumcgm.k00_numpre = arreidret.k00_numpre ";
    $sSqlInnerArreNumCgmArrePaga  = " inner join arrenumcgm on arrenumcgm.k00_numpre = arrepaga.k00_numpre  ";
    $sSqlWhereArreNumCgm = " arrenumcgm.k00_numcgm = {$iCgm} ";

    return $this->getReciboPago($sSqlInnerArreNumCgmArreIdRet, $sSqlInnerArreNumCgmArrePaga, $sSqlWhereArreNumCgm);
  }

  public function getReciboPagoMatric($iMatric){

    $sSqlInnerArreMatricArreIdRet = " inner join arrematric on arrematric.k00_numpre = arreidret.k00_numpre ";
    $sSqlInnerArreMatricArrePaga  = " inner join arrematric on arrematric.k00_numpre = arrepaga.k00_numpre  ";
    $sSqlWhereArreMatric = " arrematric.k00_matric = {$iMatric} ";

    return $this->getReciboPago($sSqlInnerArreMatricArreIdRet, $sSqlInnerArreMatricArrePaga, $sSqlWhereArreMatric);
  }

  public function getReciboPagoInscr($iInscr){

    $sSqlInnerArreInscrArreIdRet = " inner join arreinscr on arreinscr.k00_numpre = arreidret.k00_numpre ";
    $sSqlInnerArreInscrArrePaga  = " inner join arreinscr on arreinscr.k00_numpre = arrepaga.k00_numpre  ";
    $sSqlWhereArreInscr = " arreinscr.k00_inscr = {$iInscr} ";

    return $this->getReciboPago($sSqlInnerArreInscrArreIdRet, $sSqlInnerArreInscrArrePaga, $sSqlWhereArreInscr);
  }

  private function getReciboPago($sSqlInnerArreIdRet, $sSqlInnerArrePaga, $sSqlWhere){

    $sSqlWhereFiltros = null;

    if(!empty($this->iCodigoArrecadacao)){
      $sSqlWhereFiltros = " where codigoarrecadacao = '".substr($this->iCodigoArrecadacao, 0, 11)."' ";
    }

    if(!empty($this->iTipoDebito)){

      $sSqlAux = " where ";
      if(!empty($sSqlWhereFiltros)){
        $sSqlAux = " and ";
      }
      $sSqlWhereFiltros .= $sSqlAux . " tipodebito = {$this->iTipoDebito} ";
    }

    if(!empty($this->oDataInicio)){

      $sSqlAux = " where ";
      if(!empty($sSqlWhereFiltros)){
        $sSqlAux = " and ";
      }
      $sSqlWhereFiltros .= $sSqlAux . " datapagamento >= '".$this->oDataInicio->format('Y-m-d')."'";
    }

    if(!empty($this->oDataFim)){

      $sSqlAux = " where ";
      if(!empty($sSqlWhereFiltros)){
        $sSqlAux = " and ";
      }
      $sSqlWhereFiltros .= $sSqlAux . " datapagamento <= '".$this->oDataFim->format('Y-m-d')."'";
    }

    ///////////////////////////////////////////////////////////////////////////
    // Union |      Origem       | Pagamento | Tipo Pagamento | Tipo | Numpre//
    //   1   | Recibo            | Baixa     | Normal         | 1    |  1    //
    //   2   | Recibo            | Baixa     | Parcial        | 1    |  1    //
    //  1/2  | Recibo            | Baixa     | Credito        | 1    |  1    //
    //   1   | Recibo            | Baixa     | Normal         | 1    |  2+   //
    //   2   | Recibo            | Baixa     | Parcial        | 1    |  2+   //
    //  1/2  | Recibo            | Baixa     | Credito        | 1    |  2+   //
    //   4   | Recibo            | Caixa     | Normal         | 1    |  1    //
    //   4   | Recibo            | Caixa     | Normal         | 1    |  2+   //
    //   1   | Carne             | Baixa     | Normal         | 1    |  1    //
    //   2   | Carne             | Baixa     | Parcial        | 1    |  1    //
    //   2   | Carne             | Baixa     | Credito        | 1    |  1    //
    //   6   | Carne             | Caixa     | Normal         | 1    |  1    //
    //   3   | Recibo avulso     | Baixa     | Normal         | 1    |  1    //
    //   3   | Recibo avulso     | Baixa     | Parcial        | 1    |  1    //
    //   3   | Recibo avulso     | Baixa     | Credito        | 1    |  1    //
    //   5   | Recibo avulso     | Caixa     | Normal         | 1    |  1    //
    //  1/2  | Recibo p/2        | Baixa     | Normal         | 1    |  1    //
    //  1/2  | Recibo p/2        | Baixa     | Parcial        | 1    |  1    //
    //  1/2  | Recibo p/2        | Baixa     | Credito        | 1    |  1    //
    //  1/2  | Recibo p/2        | Baixa     | Normal         | 1    |  2+   //
    //  1/2  | Recibo p/2        | Baixa     | Parcial        | 1    |  2+   //
    //  1/2  | Recibo p/2        | Baixa     | Credito        | 1    |  2+   //
    //   N   | Recibo p/2        | Caixa     | Normal         | 1    |  1    //
    //   N   | Recibo p/2        | Caixa     | Normal         | 1    |  2+   //
    //  1/2  | Carne p/2         | Baixa     | Normal         | 1    |  1    //
    //  1/2  | Carne p/2         | Baixa     | Parcial        | 1    |  1    //
    //  1/2  | Carne p/2         | Baixa     | Credito        | 1    |  1    //
    //   N   | Carne p/2         | Caixa     | Normal         | 1    |  1    //
    //   3   | Recibo avulso p/2 | Baixa     | Normal         | 1    |  1    //
    //   3   | Recibo avulso p/2 | Baixa     | Parcial        | 1    |  1    //
    //   3   | Recibo avulso p/2 | Baixa     | Credito        | 1    |  1    //
    //   N   | Recibo avulso p/2 | Caixa     | Normal         | 1    |  1    //
    //   1   | Recibo            | Baixa     | Normal         | 2    |  2+   //
    //   2   | Recibo            | Baixa     | Parcial        | 2    |  2+   //
    //  1/2  | Recibo            | Baixa     | Credito        | 2    |  2+   //
    //   4   | Recibo            | Caixa     | Normal         | 2    |  2+   //
    //  1/2  | Recibo p/2        | Baixa     | Normal         | 2    |  2+   //
    //   2   | Recibo p/2        | Baixa     | Parcial        | 2    |  2+   //
    //  1/2  | Recibo p/2        | Baixa     | Credito        | 2    |  2+   //
    //   N   | Recibo p/2        | Caixa     | Normal         | 2    |  2+   //
    //   1   | Carne p/ numpre   | Baixa     | Normal         | 1    |  1    //
    //   2   | Carne p/ numpre   | Baixa     | Parcial        | 1    |  1    //
    //   2   | Carne p/ numpre   | Baixa     | Credito        | 1    |  1    //
    ///////////////////////////////////////////////////////////////////////////

    $sSql  = "   select codigo,                                                                                                                                            ";
    $sSql .= "          codigo_order,                                                                                                                                      ";
    $sSql .= "          codigoarrecadacao,                                                                                                                                 ";
    $sSql .= "          tipo,                                                                                                                                              ";
    $sSql .= "          array_agg(distinct tipodebito) as tipodebito,                                                                                                      ";
    $sSql .= "          array_to_string(array_agg(distinct (select k00_descr from arretipo where k00_tipo = tipodebito)), ' / ') as tipodebitodescricao,                   ";
    $sSql .= "          datavencimento,                                                                                                                                    ";
    $sSql .= "          datapagamento,                                                                                                                                     ";
    $sSql .= "          sum(valor) as valor                                                                                                                                ";
    $sSql .= "     from (                                                                                                                                                  ";

                                             ///////////////
                                             //  union 1  //
                                             ///////////////

    $sSql .= "           select distinct                                                                                                                                                 ";
    $sSql .= "                  disbanco.k00_numpre as codigo_order,                                                                                                                     ";
    $sSql .= "                  disbanco.k00_numpre::text as codigo,                                                                                                                     ";
    $sSql .= "                  case db_reciboweb.k99_tipo                                                                                                                               ";
    $sSql .= "                    when 2 then case (select count(*)                                                                                                                      ";
    $sSql .= "                                        from recibopaga recibopagacarne                                                                                                    ";
    $sSql .= "                                       where recibopagacarne.k00_numnov = recibopaga.k00_numnov                                                                            ";
    $sSql .= "                                         and recibopagacarne.k00_numpar <> recibopaga.k00_numpar                                                                           ";
    $sSql .= "                                     )                                                                                                                                     ";
    $sSql .= "                                  when 0 then rpad(lpad(recibopaga.k00_numpre::text, 8, '0'), (11 - length(arrepaga.k00_numpar::text)), '0') || arrepaga.k00_numpar        ";
    $sSql .= "                                  else rpad(lpad(recibopaga.k00_numpre::text, 8, '0'), 11, '0')                                                                            ";
    $sSql .= "                                end                                                                                                                                        ";
    $sSql .= "                    else rpad(lpad(disbanco.k00_numpre::text, 8, '0'), 11, '0')                                                                                            ";
    $sSql .= "                  end as codigoarrecadacao,                                                                                                                                ";
    $sSql .= "                  'Normal' as tipo,                                                                                                                                        ";
    $sSql .= "                  arrecant.k00_tipo as tipodebito,                                                                                                                         ";
    $sSql .= "                  case db_reciboweb.k99_tipo                                                                                                                               ";
    $sSql .= "                    when 2 then case (select count(*)                                                                                                        ";
    $sSql .= "                                        from recibopaga recibopagacarne                                                                                      ";
    $sSql .= "                                       where recibopagacarne.k00_numnov = recibopaga.k00_numnov                                                              ";
    $sSql .= "                                         and recibopagacarne.k00_numpar <> recibopaga.k00_numpar                                                             ";
    $sSql .= "                                     )                                                                                                                       ";
    $sSql .= "                                  when 0 then arrecant.k00_dtvenc                                                                                            ";
    $sSql .= "                                  else (select min(recibopagacarne.k00_dtvenc)                                                                               ";
    $sSql .= "                                        from recibopaga recibopagacarne                                                                                      ";
    $sSql .= "                                       where recibopagacarne.k00_numnov = recibopaga.k00_numnov                                                              ";
    $sSql .= "                                     )                                                                                                                       ";
    $sSql .= "                                end                                                                                                                          ";
    $sSql .= "                    else recibopaga.k00_dtoper                                                                                                               ";
    $sSql .= "                  end as datavencimento,                                                                                                                     ";
    $sSql .= "                  disbanco.dtpago       as datapagamento,                                                                                                    ";
    $sSql .= "                  arrepaga.k00_numpre   as arrepaganumpre,                                                                                                   ";
    $sSql .= "                  arrepaga.k00_numpar   as arrepaganumpar,                                                                                                   ";
    $sSql .= "                  arrepaga.k00_receit   as arrepagareceit,                                                                                                   ";
    $sSql .= "                  tabrec.k02_tabrectipo as tiporec,                                                                                                          ";
    $sSql .= "                  arrepaga.k00_valor    as valor                                                                                                             ";
    $sSql .= "             from disbanco                                                                                                                                   ";
    $sSql .= "                  inner join arreidret on arreidret.idret = disbanco.idret                                                                                   ";
    $sSql .= "                  {$sSqlInnerArreIdRet}                                                                                                                      ";
    $sSql .= "                  inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre                                                                       ";
    $sSql .= "                  inner join arrepaga on arrepaga.k00_numpre = recibopaga.k00_numpre                                                                         ";
    $sSql .= "                                     and arrepaga.k00_numpar = recibopaga.k00_numpar                                                                         ";
    $sSql .= "                                     and arrepaga.k00_receit = recibopaga.k00_receit                                                                         ";
    $sSql .= "                  left  join arrecant on arrecant.k00_numpre = recibopaga.k00_numpre                                                                         ";
    $sSql .= "                                     and arrecant.k00_numpar = recibopaga.k00_numpar                                                                         ";
    $sSql .= "                  inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                                                               ";
    $sSql .= "                  inner join db_reciboweb on db_reciboweb.k99_numpre_n = disbanco.k00_numpre                                                                 ";
    $sSql .= "            where disbanco.classi is true                                                                                                                    ";
    $sSql .= "              and {$sSqlWhere}                                                                                                                               ";
    $sSql .= "         group by codigo,                                                                                                                                    ";
    $sSql .= "                  codigo_order,                                                                                                                              ";
    $sSql .= "                  codigoarrecadacao,                                                                                                                         ";
    $sSql .= "                  tipo,                                                                                                                                      ";
    $sSql .= "                  tipodebito,                                                                                                                                ";
    $sSql .= "                  datavencimento,                                                                                                                            ";
    $sSql .= "                  datapagamento,                                                                                                                             ";
    $sSql .= "                  arrepaganumpre,                                                                                                                            ";
    $sSql .= "                  arrepaganumpar,                                                                                                                            ";
    $sSql .= "                  arrepagareceit,                                                                                                                            ";
    $sSql .= "                  tiporec,                                                                                                                                   ";
    $sSql .= "                  valor                                                                                                                                      ";

                                             ///////////////
                                             //  union 2  //
                                             ///////////////

    $sSql .= "            union all                                                                                                                                        ";
    $sSql .= "           select distinct                                                                                                                                   ";
    $sSql .= "                  case abatimento.k125_tipoabatimento                                                                                                        ";
    $sSql .= "                    when 1 then disbanco.k00_numpre                                                                                                          ";
    $sSql .= "                    when 3 then abatimentorecibo.k127_numpreoriginal                                                                                         ";
    $sSql .= "                  end as codigo_order,                                                                                                                       ";
    $sSql .= "                  case abatimento.k125_tipoabatimento                                                                                                        ";
    $sSql .= "                    when 1 then disbanco.k00_numpre::text                                                                                                    ";
    $sSql .= "                    when 3 then case db_reciboweb.k99_tipo                                                                                                   ";
    $sSql .= "                                  when 2 then abatimentorecibo.k127_numpreoriginal::text                                                                     ";
    $sSql .= "                                  when 1 then case (select ar.k127_numprerecibo                                                                              ";
    $sSql .= "                                                      from abatimentorecibo as ar                                                                            ";
    $sSql .= "                                                     where ar.k127_numpreoriginal = abatimentorecibo.k127_numpreoriginal                                     ";
    $sSql .= "                                                     limit 1)                                                                                                ";
    $sSql .= "                                                when abatimentorecibo.k127_numprerecibo then abatimentorecibo.k127_numpreoriginal::text                      ";
    $sSql .= "                                                else abatimentorecibo.k127_numprerecibo::text                                                                ";
    $sSql .= "                                              end                                                                                                            ";
    $sSql .= "                                end                                                                                                                          ";
    $sSql .= "                  end as codigo,                                                                                                                             ";
    $sSql .= "                  case db_reciboweb.k99_tipo                                                                                                                                            ";
    $sSql .= "                    when 2 then case (select count(*)                                                                                                                                   ";
    $sSql .= "                                        from recibopaga recibopagacarne                                                                                                                 ";
    $sSql .= "                                       where recibopagacarne.k00_numnov = recibopaga.k00_numnov                                                                                         ";
    $sSql .= "                                         and recibopagacarne.k00_numpar <> recibopaga.k00_numpar                                                                                        ";
    $sSql .= "                                     )                                                                                                                                                  ";
    $sSql .= "                                  when 0 then case abatimento.k125_tipoabatimento                                                                                                       ";
    $sSql .= "                                                  when 1 then rpad(lpad(recibopaga.k00_numpre::text, 8, '0'), (11 - length(arreckey.k00_numpar::text)),   '0') || arreckey.k00_numpar   ";
    $sSql .= "                                                  when 3 then rpad(lpad(recibopaga.k00_numpre::text, 8, '0'), (11 - length(recibopaga.k00_numpar::text)), '0') || recibopaga.k00_numpar ";
    $sSql .= "                                              end                                                                                                                                       ";
    $sSql .= "                                  else rpad(lpad(recibopaga.k00_numpre::text, 8, '0'), 11, '0')                                                                                                       ";
    $sSql .= "                                end                                                                                                                                                     ";
    $sSql .= "                    else rpad(lpad(abatimentorecibo.k127_numpreoriginal::text, 8, '0'), 11, '0')                                                                                                      ";
    $sSql .= "                  end as codigoarrecadacao,                                                                                                                                             ";
    $sSql .= "                  case abatimento.k125_tipoabatimento                                                                                                        ";
    $sSql .= "                    when 1 then 'Parcial'                                                                                                                    ";
    $sSql .= "                    when 3 then 'Normal'                                                                                                                     ";
    $sSql .= "                  end as tipo,                                                                                                                               ";
    $sSql .= "                  arreckey.k00_tipo as tipodebito,                                                                                                           ";
    $sSql .= "                  case db_reciboweb.k99_tipo                                                                                                                 ";
    $sSql .= "                    when 2 then case (select count(*)                                                                                                        ";
    $sSql .= "                                        from recibopaga recibopagacarne                                                                                      ";
    $sSql .= "                                       where recibopagacarne.k00_numnov = recibopaga.k00_numnov                                                              ";
    $sSql .= "                                         and recibopagacarne.k00_numpar <> recibopaga.k00_numpar                                                             ";
    $sSql .= "                                     )                                                                                                                       ";
    $sSql .= "                                  when 0 then recibopaga.k00_dtvenc                                                                                          ";
    $sSql .= "                                  else (select min(recibopagacarne.k00_dtvenc)                                                                               ";
    $sSql .= "                                        from recibopaga recibopagacarne                                                                                      ";
    $sSql .= "                                       where recibopagacarne.k00_numnov = recibopaga.k00_numnov                                                              ";
    $sSql .= "                                     )                                                                                                                       ";
    $sSql .= "                                end                                                                                                                          ";
    $sSql .= "                    else recibopaga.k00_dtoper                                                                                                               ";
    $sSql .= "                  end as datavencimento,                                                                                                                     ";
    $sSql .= "                  case db_reciboweb.k99_tipo                                   ";
    $sSql .= "                    when 2 then disbanco.dtpago                                ";
    $sSql .= "                    when 1 then coalesce(disbanco.dtpago, arrepaga.k00_dtpaga) ";
    $sSql .= "                  end as datapagamento,                                        ";
    $sSql .= "                  arreckey.k00_numpre   as arrepaganumpre,                                                                                                   ";
    $sSql .= "                  arreckey.k00_numpar   as arrepaganumpar,                                                                                                   ";
    $sSql .= "                  arreckey.k00_receit   as arrepagareceit,                                                                                                   ";
    $sSql .= "                  tabrec.k02_tabrectipo as tiporec,                                                                                                          ";
    $sSql .= "                  (abatimentoarreckey.k128_valorabatido +                                                                                                    ";
    $sSql .= "                   abatimentoarreckey.k128_correcao     +                                                                                                    ";
    $sSql .= "                   abatimentoarreckey.k128_juros        +                                                                                                    ";
    $sSql .= "                   abatimentoarreckey.k128_multa) as valor                                                                                                   ";
    $sSql .= "             from disbanco                                                                                                                                   ";
    $sSql .= "                  inner join arreidret on arreidret.idret = disbanco.idret                                                                                   ";
    $sSql .= "                  {$sSqlInnerArreIdRet}                                                                                                                      ";
    $sSql .= "                  inner join abatimentorecibo on abatimentorecibo.k127_numprerecibo = disbanco.k00_numpre                                                    ";
    $sSql .= "                  inner join recibopaga on recibopaga.k00_numnov = abatimentorecibo.k127_numpreoriginal                                                      ";
    $sSql .= "                  inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento                                                     ";
    $sSql .= "                  inner join db_reciboweb on db_reciboweb.k99_numpre_n = abatimentorecibo.k127_numpreoriginal                                                ";
    $sSql .= "                  inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial                                           ";
    $sSql .= "                  inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey                                                          ";
    $sSql .= "                  left join arrepaga on arrepaga.k00_numpre = abatimentorecibo.k127_numprerecibo                                                             ";
    $sSql .= "                  inner join tabrec on tabrec.k02_codigo = arreckey.k00_receit                                                                               ";
    $sSql .= "            where disbanco.classi is true                                                                                                                    ";
    $sSql .= "              and {$sSqlWhere}                                                                                                                               ";
    $sSql .= "         group by codigo,                                                                                                                                    ";
    $sSql .= "                  codigo_order,                                                                                                                              ";
    $sSql .= "                  codigoarrecadacao,                                                                                                                         ";
    $sSql .= "                  tipo,                                                                                                                                      ";
    $sSql .= "                  tipodebito,                                                                                                                                ";
    $sSql .= "                  datavencimento,                                                                                                                            ";
    $sSql .= "                  datapagamento,                                                                                                                             ";
    $sSql .= "                  arrepaganumpre,                                                                                                                            ";
    $sSql .= "                  arrepaganumpar,                                                                                                                            ";
    $sSql .= "                  arrepagareceit,                                                                                                                            ";
    $sSql .= "                  tiporec,                                                                                                                                   ";
    $sSql .= "                  valor                                                                                                                                      ";

                                             ///////////////
                                             //  union 3  //
                                             ///////////////

    $sSql .= "            union all                                                                                                                                        ";
    $sSql .= "           select distinct                                                                                                                                   ";
    $sSql .= "                  disbanco.k00_numpre as codigo_order,                                                                                                       ";
    $sSql .= "                  disbanco.k00_numpre::text as codigo,                                                                                                       ";
    $sSql .= "                  rpad(lpad(disbanco.k00_numpre::text, 8, '0'), 11, '0') as codigoarrecadacao,                                                                             ";
    $sSql .= "                  case when arrepaga.k00_valor < recibo.k00_valor                                                                                            ";
    $sSql .= "                    then 'Parcial'                                                                                                                           ";
    $sSql .= "                    else 'Normal'                                                                                                                            ";
    $sSql .= "                  end as tipo,                                                                                                                               ";
    $sSql .= "                  recibo.k00_tipo as tipodebito,                                                                                                             ";
    $sSql .= "                  recibo.k00_dtvenc as datavencimento,                                                                                                       ";
    $sSql .= "                  arrepaga.k00_dtoper as datapagamento,                                                                                                      ";
    $sSql .= "                  arrepaga.k00_numpre as arrepaganumpre,                                                                                                     ";
    $sSql .= "                  arrepaga.k00_numpar as arrepaganumpar,                                                                                                     ";
    $sSql .= "                  arrepaga.k00_receit as arrepagareceit,                                                                                                     ";
    $sSql .= "                  tabrec.k02_tabrectipo as tiporec,                                                                                                          ";
    $sSql .= "                  arrepaga.k00_valor as valor                                                                                                                ";
    $sSql .= "             from disbanco                                                                                                                                   ";
    $sSql .= "                  inner join arreidret on arreidret.idret = disbanco.idret                                                                                   ";
    $sSql .= "                  {$sSqlInnerArreIdRet}                                                                                                                      ";
    $sSql .= "                  inner join recibo on recibo.k00_numpre = disbanco.k00_numpre                                                                               ";
    $sSql .= "                  inner join arrepaga on arrepaga.k00_numpre = recibo.k00_numpre                                                                             ";
    $sSql .= "                                      and arrepaga.k00_numpar = recibo.k00_numpar                                                                            ";
    $sSql .= "                                      and arrepaga.k00_receit = recibo.k00_receit                                                                            ";
    $sSql .= "                  inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                                                               ";
    $sSql .= "                  left  join abatimentorecibo on abatimentorecibo.k127_numprerecibo = disbanco.k00_numpre                                                    ";
    $sSql .= "            where disbanco.classi is true                                                                                                                    ";
    $sSql .= "              and abatimentorecibo.k127_numprerecibo is null                                                                                                 ";
    $sSql .= "              and {$sSqlWhere}                                                                                                                               ";
    $sSql .= "         group by codigo,                                                                                                                                    ";
    $sSql .= "                  codigo_order,                                                                                                                              ";
    $sSql .= "                  codigoarrecadacao,                                                                                                                         ";
    $sSql .= "                  tipo,                                                                                                                                      ";
    $sSql .= "                  tipodebito,                                                                                                                                ";
    $sSql .= "                  datavencimento,                                                                                                                            ";
    $sSql .= "                  datapagamento,                                                                                                                             ";
    $sSql .= "                  arrepaganumpre,                                                                                                                            ";
    $sSql .= "                  arrepaganumpar,                                                                                                                            ";
    $sSql .= "                  arrepagareceit,                                                                                                                            ";
    $sSql .= "                  tiporec,                                                                                                                                   ";
    $sSql .= "                  valor                                                                                                                                      ";

                                             ///////////////
                                             //  union 4  //
                                             ///////////////

    $sSql .= "            union all                                                                                                                                        ";
    $sSql .= "         select distinct                                                                                                                                     ";
    $sSql .= "                  cornump.k12_numnov as codigo_order,                                                                                                        ";
    $sSql .= "                  cornump.k12_numnov::text as codigo,                                                                                                        ";
    $sSql .= "                  rpad(lpad(cornump.k12_numnov::text, 8, '0'), 11, '0') as codigoarrecadacao,                                                                              ";
    $sSql .= "                  'Normal' as tipo,                                                                                                                          ";
    $sSql .= "                  arrecant.k00_tipo as tipodebito,                                                                                                           ";
    $sSql .= "                  recibopaga.k00_dtoper as datavencimento,                                                                                                   ";
    $sSql .= "                  cornump.k12_data as datapagamento,                                                                                                         ";
    $sSql .= "                  arrepaga.k00_numpre as arrepaganumpre,                                                                                                     ";
    $sSql .= "                  arrepaga.k00_numpar as arrepaganumpar,                                                                                                     ";
    $sSql .= "                  arrepaga.k00_receit as arrepagareceit,                                                                                                     ";
    $sSql .= "                  tabrec.k02_tabrectipo as tiporec,                                                                                                          ";
    $sSql .= "                  arrepaga.k00_valor as valor                                                                                                                ";
    $sSql .= "             from arrepaga                                                                                                                                   ";
    $sSql .= "                  {$sSqlInnerArrePaga}                                                                                                                       ";
    $sSql .= "                  inner join cornump on cornump.k12_numpre = arrepaga.k00_numpre                                                                             ";
    $sSql .= "                                    and cornump.k12_numpar = arrepaga.k00_numpar                                                                             ";
    $sSql .= "                  inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                                                               ";
    $sSql .= "                  inner join recibopaga on recibopaga.k00_numnov = cornump.k12_numnov                                                                        ";
    $sSql .= "                  left  join arrecant on arrecant.k00_numpre = arrepaga.k00_numpre                                                                           ";
    $sSql .= "                                     and arrecant.k00_numpar = arrepaga.k00_numpar                                                                           ";
    $sSql .= "            where {$sSqlWhere}                                                                                                                               ";
    $sSql .= "         group by codigo,                                                                                                                                    ";
    $sSql .= "                  codigo_order,                                                                                                                              ";
    $sSql .= "                  codigoarrecadacao,                                                                                                                         ";
    $sSql .= "                  tipo,                                                                                                                                      ";
    $sSql .= "                  tipodebito,                                                                                                                                ";
    $sSql .= "                  datavencimento,                                                                                                                            ";
    $sSql .= "                  datapagamento,                                                                                                                             ";
    $sSql .= "                  arrepaganumpre,                                                                                                                            ";
    $sSql .= "                  arrepaganumpar,                                                                                                                            ";
    $sSql .= "                  arrepagareceit,                                                                                                                            ";
    $sSql .= "                  tiporec,                                                                                                                                   ";
    $sSql .= "                  valor                                                                                                                                      ";

                                             ///////////////
                                             //  union 5  //
                                             ///////////////

    $sSql .= "            union all                                                                                                                                        ";
    $sSql .= "           select distinct                                                                                                                                   ";
    $sSql .= "                  cornump.k12_numnov as codigo_order,                                                                                                        ";
    $sSql .= "                  cornump.k12_numnov::text as codigo,                                                                                                        ";
    $sSql .= "                  rpad(lpad(cornump.k12_numnov::text, 8, '0'), 11, '0') as codigoarrecadacao,                                                                              ";
    $sSql .= "                  'Normal' as tipo,                                                                                                                          ";
    $sSql .= "                  recibo.k00_tipo as tipodebito,                                                                                                             ";
    $sSql .= "                  recibo.k00_dtoper as datavencimento,                                                                                                       ";
    $sSql .= "                  cornump.k12_data as datapagamento,                                                                                                         ";
    $sSql .= "                  arrepaga.k00_numpre as arrepaganumpre,                                                                                                     ";
    $sSql .= "                  arrepaga.k00_numpar as arrepaganumpar,                                                                                                     ";
    $sSql .= "                  arrepaga.k00_receit as arrepagareceit,                                                                                                     ";
    $sSql .= "                  tabrec.k02_tabrectipo as tiporec,                                                                                                          ";
    $sSql .= "                  arrepaga.k00_valor as valor                                                                                                                ";
    $sSql .= "             from arrepaga                                                                                                                                   ";
    $sSql .= "                  {$sSqlInnerArrePaga}                                                                                                                       ";
    $sSql .= "                  inner join cornump on cornump.k12_numpre = arrepaga.k00_numpre                                                                             ";
    $sSql .= "                                    and cornump.k12_numpar = arrepaga.k00_numpar                                                                             ";
    $sSql .= "                  inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                                                               ";
    $sSql .= "                  inner join recibo on recibo.k00_numpre = cornump.k12_numnov                                                                                ";
    $sSql .= "            where {$sSqlWhere}                                                                                                                               ";
    $sSql .= "         group by codigo,                                                                                                                                    ";
    $sSql .= "                  codigo_order,                                                                                                                              ";
    $sSql .= "                  codigoarrecadacao,                                                                                                                         ";
    $sSql .= "                  tipo,                                                                                                                                      ";
    $sSql .= "                  tipodebito,                                                                                                                                ";
    $sSql .= "                  datavencimento,                                                                                                                            ";
    $sSql .= "                  datapagamento,                                                                                                                             ";
    $sSql .= "                  arrepaganumpre,                                                                                                                            ";
    $sSql .= "                  arrepaganumpar,                                                                                                                            ";
    $sSql .= "                  arrepagareceit,                                                                                                                            ";
    $sSql .= "                  tiporec,                                                                                                                                   ";
    $sSql .= "                  valor                                                                                                                                      ";

                                             ///////////////
                                             //  union 6  //
                                             ///////////////

    $sSql .= "            union all                                                                                                                                        ";
    $sSql .= "           select distinct                                                                                                                                   ";
    $sSql .= "                  cornump.k12_numnov as codigo_order,                                                                                                        ";
    $sSql .= "                  (cornump.k12_numpre::text || cornump.k12_numpar::text)::text as codigo,                                                                    ";
    $sSql .= "                  rpad(lpad(arrepaga.k00_numpre::text, 8, '0'), (11 - length(arrepaga.k00_numpar::text)), '0') || arrepaga.k00_numpar as codigoarrecadacao,  ";
    $sSql .= "                  'Normal' as tipo,                                                                                                                          ";
    $sSql .= "                  arrecant.k00_tipo as tipodebito,                                                                                                           ";
    $sSql .= "                  arrecant.k00_dtvenc as datavencimento,                                                                                                     ";
    $sSql .= "                  cornump.k12_data as datapagamento,                                                                                                         ";
    $sSql .= "                  arrepaga.k00_numpre as arrepaganumpre,                                                                                                     ";
    $sSql .= "                  arrepaga.k00_numpar as arrepaganumpar,                                                                                                     ";
    $sSql .= "                  arrepaga.k00_receit as arrepagareceit,                                                                                                     ";
    $sSql .= "                  tabrec.k02_tabrectipo as tiporec,                                                                                                          ";
    $sSql .= "                  arrepaga.k00_valor as valor                                                                                                                ";
    $sSql .= "             from arrepaga                                                                                                                                   ";
    $sSql .= "                  {$sSqlInnerArrePaga}                                                                                                                       ";
    $sSql .= "                  inner join cornump on cornump.k12_numpre = arrepaga.k00_numpre                                                                             ";
    $sSql .= "                                    and cornump.k12_numpar = arrepaga.k00_numpar                                                                             ";
    $sSql .= "                  inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                                                               ";
    $sSql .= "                  left  join recibo on recibo.k00_numpre = cornump.k12_numnov                                                                                ";
    $sSql .= "                  left  join recibopaga on recibopaga.k00_numnov = cornump.k12_numnov                                                                        ";
    $sSql .= "                  left  join arrecant on arrecant.k00_numpre = cornump.k12_numpre                                                                            ";
    $sSql .= "                                     and arrecant.k00_numpar = cornump.k12_numpar                                                                            ";
    $sSql .= "            where recibopaga.k00_numnov is null                                                                                                              ";
    $sSql .= "              and recibo.k00_numpre is null                                                                                                                  ";
    $sSql .= "              and {$sSqlWhere}                                                                                                                               ";
    $sSql .= "         group by codigo,                                                                                                                                    ";
    $sSql .= "                  codigo_order,                                                                                                                              ";
    $sSql .= "                  codigoarrecadacao,                                                                                                                         ";
    $sSql .= "                  tipo,                                                                                                                                      ";
    $sSql .= "                  tipodebito,                                                                                                                                ";
    $sSql .= "                  datavencimento,                                                                                                                            ";
    $sSql .= "                  datapagamento,                                                                                                                             ";
    $sSql .= "                  arrepaganumpre,                                                                                                                            ";
    $sSql .= "                  arrepaganumpar,                                                                                                                            ";
    $sSql .= "                  arrepagareceit,                                                                                                                            ";
    $sSql .= "                  tiporec,                                                                                                                                   ";
    $sSql .= "                  valor                                                                                                                                      ";
    $sSql .= "          ) as r                                                                                                                                             ";
    $sSql .= "          {$sSqlWhereFiltros}                                                                                                                                ";
    $sSql .= " group by codigo,                                                                                                                                            ";
    $sSql .= "          codigo_order,                                                                                                                                      ";
    $sSql .= "          codigoarrecadacao,                                                                                                                                 ";
    $sSql .= "          tipo,                                                                                                                                              ";
    $sSql .= "          datavencimento,                                                                                                                                    ";
    $sSql .= "          datapagamento                                                                                                                                      ";
    $sSql .= " order by datapagamento desc,                                                                                                                                ";
    $sSql .= "          codigo_order desc                                                                                                                                  ";

    $rsReciboPago = db_query($sSql);

    if(empty($rsReciboPago)){
      throw new Exception("Erro ao consultar recibos pagos.");
    }

    return db_utils::getCollectionByRecord($rsReciboPago);
  }

  public function getReciboPagoCodigoArrecadacao(){

    /////////////////////////////////////////////////////////////////////////////
    //  Union |      Origem       | Pagamento | Tipo Pagamento | Tipo | Numpre //
    //    1   | Recibo            | Baixa     | Normal         | 1    |  1     //
    //    3   | Recibo            | Baixa     | Parcial        | 1    |  1     //
    //   1/3  | Recibo            | Baixa     | Credito        | 1    |  1     //
    //    1   | Recibo            | Baixa     | Normal         | 1    |  2+    //
    //    3   | Recibo            | Baixa     | Parcial        | 1    |  2+    //
    //   1/3  | Recibo            | Baixa     | Credito        | 1    |  2+    //
    //    4   | Recibo            | Caixa     | Normal         | 1    |  1     //
    //    4   | Recibo            | Caixa     | Normal         | 1    |  2+    //
    //    1   | Carne             | Baixa     | Normal         | 1    |  1     //
    //    2   | Carne             | Baixa     | Parcial        | 1    |  1     //
    //   1/2  | Carne             | Baixa     | Credito        | 1    |  1     //
    //    6   | Carne             | Caixa     | Normal         | 1    |  1     //
    //    3   | Recibo avulso     | Baixa     | Normal         | 1    |  1     //
    //    3   | Recibo avulso     | Baixa     | Parcial        | 1    |  1     //
    //    3   | Recibo avulso     | Baixa     | Credito        | 1    |  1     //
    //    5   | Recibo avulso     | Caixa     | Normal         | 1    |  1     //
    //   1/3  | Recibo p/2        | Baixa     | Normal         | 1    |  1     //
    //   1/3  | Recibo p/2        | Baixa     | Parcial        | 1    |  1     //
    //   1/3  | Recibo p/2        | Baixa     | Credito        | 1    |  1     //
    //   1/3  | Recibo p/2        | Baixa     | Normal         | 1    |  2+    //
    //   1/3  | Recibo p/2        | Baixa     | Parcial        | 1    |  2+    //
    //   1/3  | Recibo p/2        | Baixa     | Credito        | 1    |  2+    //
    //    N   | Recibo p/2        | Caixa     | Normal         | 1    |  1     //
    //    N   | Recibo p/2        | Caixa     | Normal         | 1    |  2+    //
    //    2   | Carne p/2         | Baixa     | Normal         | 1    |  1     //
    //   1/2  | Carne p/2         | Baixa     | Parcial        | 1    |  1     //
    //    2   | Carne p/2         | Baixa     | Credito        | 1    |  1     //
    //    N   | Carne p/2         | Caixa     | Normal         | 1    |  1     //
    //    3   | Recibo avulso p/2 | Baixa     | Normal         | 1    |  1     //
    //    3   | Recibo avulso p/2 | Baixa     | Parcial        | 1    |  1     //
    //    3   | Recibo avulso p/2 | Baixa     | Credito        | 1    |  1     //
    //    N   | Recibo avulso p/2 | Caixa     | Normal         | 1    |  1     //
    //    1   | Recibo            | Baixa     | Normal         | 2    |  2+    //
    //    3   | Recibo            | Baixa     | Parcial        | 2    |  2+    //
    //   1/3  | Recibo            | Baixa     | Credito        | 2    |  2+    //
    //    4   | Recibo            | Caixa     | Normal         | 2    |  2+    //
    //   1/3  | Recibo p/2        | Baixa     | Normal         | 2    |  2+    //
    //    3   | Recibo p/2        | Baixa     | Parcial        | 2    |  2+    //
    //   1/3  | Recibo p/2        | Baixa     | Credito        | 2    |  2+    //
    //    N   | Recibo p/2        | Caixa     | Normal         | 2    |  2+    //
    //    1   | Carne p/ numpre   | Baixa     | Normal         | 1    |  1     //
    //    2   | Carne p/ numpre   | Baixa     | Parcial        | 1    |  1     //
    //    2   | Carne p/ numpre   | Baixa     | Credito        | 1    |  1     //
    //    7   | Carne             | ?????     | ???            | ?    |  ?     //
    /////////////////////////////////////////////////////////////////////////////

                                             ///////////////
                                             //  union 1  //
                                             ///////////////

    $sSql  = "   select distinct                                                                                          ";
    $sSql .= "          0                   as abatimento,                                                                ";
    $sSql .= "          arrepaga.k00_numpre as numpre,                                                                    ";
    $sSql .= "          arrepaga.k00_numpar as parcela,                                                                   ";
    $sSql .= "          arrepaga.k00_numtot as total,                                                                     ";
    $sSql .= "          'Normal'            as tipo,                                                                      ";
    $sSql .= "          arretipo.k00_tipo   as tipodebito,                                                                ";
    $sSql .= "          arretipo.k00_descr  as tipodebitodescricao,                                                       ";
    $sSql .= "          tabrec.k02_codigo   as receita,                                                                   ";
    $sSql .= "          tabrec.k02_drecei   as receitadescricao,                                                          ";
    $sSql .= "          arrecant.k00_dtvenc as datavencimento,                                                            ";
    $sSql .= "          arrepaga.k00_dtpaga as datapagamento,                                                             ";
    $sSql .= "          disbanco.dtpago     as dataefetivacao,                                                            ";
    $sSql .= "          arrepaga.k00_valor  as valor                                                                      ";
    $sSql .= "     from disbanco                                                                                          ";
    $sSql .= "          inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre                              ";
    $sSql .= "          inner join arrepaga on arrepaga.k00_numpre = recibopaga.k00_numpre                                ";
    $sSql .= "                             and arrepaga.k00_numpar = recibopaga.k00_numpar                                ";
    $sSql .= "                             and arrepaga.k00_receit = recibopaga.k00_receit                                ";
    $sSql .= "          inner join arrecant on arrecant.k00_numpre = recibopaga.k00_numpre                                ";
    $sSql .= "                             and arrecant.k00_numpar = recibopaga.k00_numpar                                ";
    $sSql .= "          inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                      ";
    $sSql .= "          inner join db_reciboweb on db_reciboweb.k99_numpre_n = disbanco.k00_numpre                        ";
    $sSql .= "          inner join arretipo on arretipo.k00_tipo = arrecant.k00_tipo                                      ";
    $sSql .= "    where disbanco.classi is true                                                                           ";
    $sSql .= "      and disbanco.k00_numpre = {$this->iCodigoArrecadacao}                                                 ";
    $sSql .= " group by abatimento,                                                                                       ";
    $sSql .= "          numpre,                                                                                           ";
    $sSql .= "          parcela,                                                                                          ";
    $sSql .= "          total,                                                                                            ";
    $sSql .= "          tipo,                                                                                             ";
    $sSql .= "          tipodebito,                                                                                       ";
    $sSql .= "          tipodebitodescricao,                                                                              ";
    $sSql .= "          receita,                                                                                          ";
    $sSql .= "          receitadescricao,                                                                                 ";
    $sSql .= "          datavencimento,                                                                                   ";
    $sSql .= "          datapagamento,                                                                                    ";
    $sSql .= "          dataefetivacao,                                                                                   ";
    $sSql .= "          valor                                                                                             ";

                                             ///////////////
                                             //  union 2  //
                                             ///////////////

    $sSql .= "    union all                                                                                               ";
    $sSql .= "   select distinct                                                                                          ";
    $sSql .= "           arrepaga.k00_numpre   as abatimento,                                                             ";
    $sSql .= "           recibopaga.k00_numpre as numpre,                                                                 ";
    $sSql .= "           recibopaga.k00_numpar as parcela,                                                                ";
    $sSql .= "           recibopaga.k00_numtot as total,                                                                  ";
    $sSql .= "           case abatimento.k125_tipoabatimento                                                              ";
    $sSql .= "             when 1 then 'Parcial'                                                                          ";
    $sSql .= "             when 3 then 'Crédito'                                                                          ";
    $sSql .= "           end                 as tipo,                                                                     ";
    $sSql .= "           arretipo.k00_tipo   as tipodebito,                                                               ";
    $sSql .= "           arretipo.k00_descr  as tipodebitodescricao,                                                      ";
    $sSql .= "           tabrec.k02_codigo   as receita,                                                                  ";
    $sSql .= "           tabrec.k02_drecei   as receitadescricao,                                                         ";
    $sSql .= "           recibopaga.k00_dtvenc as datavencimento,                                                         ";
    $sSql .= "           arrepaga.k00_dtpaga as datapagamento,                                                            ";
    $sSql .= "           disbanco.dtpago     as dataefetivacao,                                                           ";
    $sSql .= "           case (select count(*)                                                                                      ";
    $sSql .= "                   from recibopaga recibopagacarne                                                                    ";
    $sSql .= "                  where recibopagacarne.k00_numnov = recibopaga.k00_numnov                                            ";
    $sSql .= "                    and recibopagacarne.k00_numpar <> recibopaga.k00_numpar)                                          ";
    $sSql .= "             when 0 then arrepaga.k00_valor                                                                           ";
    $sSql .= "             else (select (abatimentoarreckeycarne.k128_valorabatido +                                                                                  ";
    $sSql .= "                           abatimentoarreckeycarne.k128_correcao     +                                                                                  ";
    $sSql .= "                           abatimentoarreckeycarne.k128_juros        +                                                                                  ";
    $sSql .= "                           abatimentoarreckeycarne.k128_multa)                                                                                          ";
    $sSql .= "                     from arreckey arreckeycarne                                                                                                        ";
    $sSql .= "                          inner join abatimentoarreckey abatimentoarreckeycarne on abatimentoarreckeycarne.k128_arreckey = arreckeycarne.k00_sequencial ";
    $sSql .= "                    where arreckeycarne.k00_numpre = recibopaga.k00_numpre                                                                              ";
    $sSql .= "                      and arreckeycarne.k00_numpar = recibopaga.k00_numpar                                                                              ";
    $sSql .= "                      and arreckeycarne.k00_receit = tabrec.k02_codigo                                                                                  ";
    $sSql .= "                      and arreckeycarne.k00_sequencial = arreckeycarne.k00_sequencial                                                                   ";
    $sSql .= "                      and abatimentoarreckeycarne.k128_abatimento = abatimentoarreckey.k128_abatimento)                                                 ";
    $sSql .= "             end as valor                                                                                   ";
    $sSql .= "      from disbanco                                                                                         ";
    $sSql .= "           inner join abatimentorecibo on abatimentorecibo.k127_numprerecibo = disbanco.k00_numpre          ";
    $sSql .= "           inner join recibopaga on recibopaga.k00_numnov = abatimentorecibo.k127_numpreoriginal            ";
    $sSql .= "           inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento           ";
    $sSql .= "           inner join db_reciboweb on db_reciboweb.k99_numpre_n = abatimentorecibo.k127_numpreoriginal      ";
    $sSql .= "           inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial ";
    $sSql .= "           inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey                ";
    $sSql .= "           left  join arrepaga on arrepaga.k00_numpre = abatimentorecibo.k127_numprerecibo                  ";
    $sSql .= "           inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                     ";
    $sSql .= "           inner join arretipo on arretipo.k00_tipo = arreckey.k00_tipo                                     ";
    $sSql .= "     where disbanco.classi is true                                                                          ";
    $sSql .= "       and db_reciboweb.k99_tipo = 2                                                                        ";
    $sSql .= "       and case abatimento.k125_tipoabatimento                                                              ";
    $sSql .= "             when 1 then disbanco.k00_numpre                                                                ";
    $sSql .= "             when 3 then abatimentorecibo.k127_numpreoriginal                                               ";
    $sSql .= "           end = {$this->iCodigoArrecadacao}                                                                ";
    $sSql .= "  group by abatimento,                                                                                      ";
    $sSql .= "           numpre,                                                                                          ";
    $sSql .= "           parcela,                                                                                         ";
    $sSql .= "           total,                                                                                           ";
    $sSql .= "           tipo,                                                                                            ";
    $sSql .= "           tipodebito,                                                                                      ";
    $sSql .= "           tipodebitodescricao,                                                                             ";
    $sSql .= "           receita,                                                                                         ";
    $sSql .= "           receitadescricao,                                                                                ";
    $sSql .= "           datavencimento,                                                                                  ";
    $sSql .= "           datapagamento,                                                                                   ";
    $sSql .= "           dataefetivacao,                                                                                  ";
    $sSql .= "           valor                                                                                            ";

                                             ///////////////
                                             //  union 3  //
                                             ///////////////

    $sSql .= "     union all                                                                                              ";
    $sSql .= " select distinct                                                                                            ";
    $sSql .= "           arrepaga.k00_numpre as abatimento,                                                               ";
    $sSql .= "           arreckey.k00_numpre as numpre,                                                                   ";
    $sSql .= "           arreckey.k00_numpar as parcela,                                                                  ";
    $sSql .= "           case                                                                                             ";
    $sSql .= "             when arrecad.k00_numtot is not null then arrecad.k00_numtot                                    ";
    $sSql .= "             when arrecant.k00_numtot is not null then arrecant.k00_numtot                                  ";
    $sSql .= "             else arrepaga.k00_numtot                                                                       ";
    $sSql .= "           end as total,                                                                                    ";
    $sSql .= "           case abatimento.k125_tipoabatimento                                                              ";
    $sSql .= "             when 1 then 'Parcial'                                                                          ";
    $sSql .= "             when 3 then 'Crédito'                                                                          ";
    $sSql .= "           end                 as tipo,                                                                     ";
    $sSql .= "           arretipo.k00_tipo   as tipodebito,                                                               ";
    $sSql .= "           arretipo.k00_descr  as tipodebitodescricao,                                                      ";
    $sSql .= "           tabrec.k02_codigo   as receita,                                                                  ";
    $sSql .= "           tabrec.k02_drecei   as receitadescricao,                                                         ";
    $sSql .= "           case                                                                                             ";
    $sSql .= "             when arrecad.k00_dtvenc is not null then arrecad.k00_dtvenc                                    ";
    $sSql .= "             when arrecant.k00_dtvenc is not null then arrecant.k00_dtvenc                                  ";
    $sSql .= "             else arrepaga.k00_dtvenc                                                                       ";
    $sSql .= "           end as datavencimento,                                                                           ";
    $sSql .= "           case                                                                                             ";
    $sSql .= "             when arrecad.k00_dtoper is not null then arrecad.k00_dtoper                                    ";
    $sSql .= "             when arrecant.k00_dtoper is not null then arrecant.k00_dtoper                                  ";
    $sSql .= "             else arrepaga.k00_dtoper                                                                       ";
    $sSql .= "           end as datapagamento,                                                                            ";
    $sSql .= "           disbanco.dtpago     as dataefetivacao,                                                           ";
    $sSql .= "          (abatimentoarreckey.k128_valorabatido +                                                           ";
    $sSql .= "           abatimentoarreckey.k128_correcao     +                                                           ";
    $sSql .= "           abatimentoarreckey.k128_juros        +                                                           ";
    $sSql .= "           abatimentoarreckey.k128_multa) as valor                                                          ";
    $sSql .= "      from disbanco                                                                                         ";
    $sSql .= "           inner join abatimentorecibo on abatimentorecibo.k127_numprerecibo = disbanco.k00_numpre          ";
    $sSql .= "           inner join db_reciboweb on db_reciboweb.k99_numpre_n = abatimentorecibo.k127_numpreoriginal      ";
    $sSql .= "           inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento           ";
    $sSql .= "           inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial ";
    $sSql .= "           inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey                ";
    $sSql .= "           inner join tabrec on tabrec.k02_codigo = arreckey.k00_receit                                     ";
    $sSql .= "           inner join arretipo on arretipo.k00_tipo = arreckey.k00_tipo                                     ";
    $sSql .= "           left  join arrepaga on arrepaga.k00_numpre = abatimentorecibo.k127_numprerecibo                  ";
    $sSql .= "           left  join arrecant  on arrecant.k00_numpre = arreckey.k00_numpre                                ";
    $sSql .= "                               and arrecant.k00_numpar = arreckey.k00_numpar                                ";
    $sSql .= "                               and arrecant.k00_receit = arreckey.k00_receit                                ";
    $sSql .= "           left  join arrecad   on arrecad.k00_numpre  = arreckey.k00_numpre                                ";
    $sSql .= "                               and arrecad.k00_numpar  = arreckey.k00_numpar                                ";
    $sSql .= "                               and arrecad.k00_receit  = arreckey.k00_receit                                ";
    $sSql .= "     where disbanco.classi is true                                                                          ";
    $sSql .= "       and db_reciboweb.k99_tipo = 1                                                                        ";
    $sSql .= "       and case abatimento.k125_tipoabatimento                                                              ";
    $sSql .= "         when 1 then disbanco.k00_numpre                                                                    ";
    $sSql .= "         when 3 then case (select ar.k127_numprerecibo                                                      ";
    $sSql .= "                             from abatimentorecibo as ar                                                    ";
    $sSql .= "                            where ar.k127_numpreoriginal = abatimentorecibo.k127_numpreoriginal             ";
    $sSql .= "                            limit 1)                                                                        ";
    $sSql .= "                       when abatimentorecibo.k127_numprerecibo then abatimentorecibo.k127_numpreoriginal    ";
    $sSql .= "                       else abatimentorecibo.k127_numprerecibo                                              ";
    $sSql .= "                     end                                                                                    ";
    $sSql .= "       end = {$this->iCodigoArrecadacao}                                                                    ";
    $sSql .= "  group by abatimento,                                                                                      ";
    $sSql .= "           numpre,                                                                                          ";
    $sSql .= "           parcela,                                                                                         ";
    $sSql .= "           total,                                                                                           ";
    $sSql .= "           tipo,                                                                                            ";
    $sSql .= "           tipodebito,                                                                                      ";
    $sSql .= "           tipodebitodescricao,                                                                             ";
    $sSql .= "           receita,                                                                                         ";
    $sSql .= "           receitadescricao,                                                                                ";
    $sSql .= "           datavencimento,                                                                                  ";
    $sSql .= "           datapagamento,                                                                                   ";
    $sSql .= "           dataefetivacao,                                                                                  ";
    $sSql .= "           valor                                                                                            ";

                                             ///////////////
                                             //  union 4  //
                                             ///////////////

    $sSql .= "     union all                                                                                              ";
    $sSql .= "   select distinct                                                                                          ";
    $sSql .= "          arrepaga.k00_numpre as abatimento,                                                                ";
    $sSql .= "          arrepaga.k00_numpre as numpre,                                                                    ";
    $sSql .= "          arrepaga.k00_numpar as parcela,                                                                   ";
    $sSql .= "          arrepaga.k00_numtot as total,                                                                     ";
    $sSql .= "          case when arrepaga.k00_valor < recibo.k00_valor                                                   ";
    $sSql .= "            then 'Parcial'                                                                                  ";
    $sSql .= "            else 'Normal'                                                                                   ";
    $sSql .= "          end as tipo,                                                                                      ";
    $sSql .= "          arretipo.k00_tipo   as tipodebito,                                                                ";
    $sSql .= "          arretipo.k00_descr  as tipodebitodescricao,                                                       ";
    $sSql .= "          tabrec.k02_codigo   as receita,                                                                   ";
    $sSql .= "          tabrec.k02_drecei   as receitadescricao,                                                          ";
    $sSql .= "          recibo.k00_dtvenc   as datavencimento,                                                            ";
    $sSql .= "          arrepaga.k00_dtpaga as datapagamento,                                                             ";
    $sSql .= "          disbanco.dtpago     as dataefetivacao,                                                            ";
    $sSql .= "          arrepaga.k00_valor  as valor                                                                      ";
    $sSql .= "     from disbanco                                                                                          ";
    $sSql .= "          inner join recibo on recibo.k00_numpre = disbanco.k00_numpre                                      ";
    $sSql .= "          inner join arretipo on arretipo.k00_tipo = recibo.k00_tipo                                        ";
    $sSql .= "          inner join arrepaga  on arrepaga.k00_numpre = recibo.k00_numpre                                   ";
    $sSql .= "                              and arrepaga.k00_numpar = recibo.k00_numpar                                   ";
    $sSql .= "                              and arrepaga.k00_receit = recibo.k00_receit                                   ";
    $sSql .= "          inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                      ";
    $sSql .= "          left  join abatimentorecibo on abatimentorecibo.k127_numprerecibo = disbanco.k00_numpre           ";
    $sSql .= "    where disbanco.classi is true                                                                           ";
    $sSql .= "      and abatimentorecibo.k127_numprerecibo is null                                                        ";
    $sSql .= "      and disbanco.k00_numpre = {$this->iCodigoArrecadacao}                                                 ";
    $sSql .= " group by abatimento,                                                                                       ";
    $sSql .= "          numpre,                                                                                           ";
    $sSql .= "          parcela,                                                                                          ";
    $sSql .= "          total,                                                                                            ";
    $sSql .= "          tipo,                                                                                             ";
    $sSql .= "          tipodebito,                                                                                       ";
    $sSql .= "          tipodebitodescricao,                                                                              ";
    $sSql .= "          receita,                                                                                          ";
    $sSql .= "          receitadescricao,                                                                                 ";
    $sSql .= "          datavencimento,                                                                                   ";
    $sSql .= "          datapagamento,                                                                                    ";
    $sSql .= "          dataefetivacao,                                                                                   ";
    $sSql .= "          valor                                                                                             ";

                                             ///////////////
                                             //  union 5  //
                                             ///////////////

    $sSql .= "    union all                                                                                               ";
    $sSql .= "   select distinct                                                                                          ";
    $sSql .= "          0                   as abatimento,                                                                ";
    $sSql .= "          arrepaga.k00_numpre as numpre,                                                                    ";
    $sSql .= "          arrepaga.k00_numpar as parcela,                                                                   ";
    $sSql .= "          arrepaga.k00_numtot as total,                                                                     ";
    $sSql .= "          'Normal'            as tipo,                                                                      ";
    $sSql .= "          arretipo.k00_tipo   as tipodebito,                                                                ";
    $sSql .= "          arretipo.k00_descr  as tipodebitodescricao,                                                       ";
    $sSql .= "          tabrec.k02_codigo   as receita,                                                                   ";
    $sSql .= "          tabrec.k02_drecei   as receitadescricao,                                                          ";
    $sSql .= "          arrepaga.k00_dtvenc as datavencimento,                                                            ";
    $sSql .= "          arrepaga.k00_dtpaga as datapagamento,                                                             ";
    $sSql .= "          cornump.k12_data    as dataefetivacao,                                                            ";
    $sSql .= "          arrepaga.k00_valor  as valor                                                                      ";
    $sSql .= "     from arrepaga                                                                                          ";
    $sSql .= "          inner join cornump on cornump.k12_numpre = arrepaga.k00_numpre                                    ";
    $sSql .= "                            and cornump.k12_numpar = arrepaga.k00_numpar                                    ";
    $sSql .= "          inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                      ";
    $sSql .= "          inner join recibopaga on recibopaga.k00_numnov = cornump.k12_numnov                               ";
    $sSql .= "          left  join arrecant on arrecant.k00_numpre = arrepaga.k00_numpre                                  ";
    $sSql .= "                             and arrecant.k00_numpar = arrepaga.k00_numpar                                  ";
    $sSql .= "          left  join arretipo on arretipo.k00_tipo = arrecant.k00_tipo                                      ";
    $sSql .= "    where cornump.k12_numnov = {$this->iCodigoArrecadacao}                                                  ";
    $sSql .= " group by abatimento,                                                                                       ";
    $sSql .= "          numpre,                                                                                           ";
    $sSql .= "          parcela,                                                                                          ";
    $sSql .= "          total,                                                                                            ";
    $sSql .= "          tipo,                                                                                             ";
    $sSql .= "          tipodebito,                                                                                       ";
    $sSql .= "          tipodebitodescricao,                                                                              ";
    $sSql .= "          receita,                                                                                          ";
    $sSql .= "          receitadescricao,                                                                                 ";
    $sSql .= "          datavencimento,                                                                                   ";
    $sSql .= "          datapagamento,                                                                                    ";
    $sSql .= "          dataefetivacao,                                                                                   ";
    $sSql .= "          valor                                                                                             ";

                                             ///////////////
                                             //  union 6  //
                                             ///////////////

    $sSql .= "    union all                                                                                               ";
    $sSql .= "   select distinct                                                                                          ";
    $sSql .= "          0                   as abatimento,                                                                ";
    $sSql .= "          arrepaga.k00_numpre as numpre,                                                                    ";
    $sSql .= "          arrepaga.k00_numpar as parcela,                                                                   ";
    $sSql .= "          arrepaga.k00_numtot as total,                                                                     ";
    $sSql .= "          'Normal'            as tipo,                                                                      ";
    $sSql .= "          arretipo.k00_tipo   as tipodebito,                                                                ";
    $sSql .= "          arretipo.k00_descr  as tipodebitodescricao,                                                       ";
    $sSql .= "          tabrec.k02_codigo   as receita,                                                                   ";
    $sSql .= "          tabrec.k02_drecei   as receitadescricao,                                                          ";
    $sSql .= "          arrepaga.k00_dtvenc as datavencimento,                                                            ";
    $sSql .= "          arrepaga.k00_dtpaga as datapagamento,                                                             ";
    $sSql .= "          cornump.k12_data    as dataefetivacao,                                                            ";
    $sSql .= "          arrepaga.k00_valor  as valor                                                                      ";
    $sSql .= "     from arrepaga                                                                                          ";
    $sSql .= "          inner join cornump on cornump.k12_numpre = arrepaga.k00_numpre                                    ";
    $sSql .= "                            and cornump.k12_numpar = arrepaga.k00_numpar                                    ";
    $sSql .= "          inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                      ";
    $sSql .= "          inner join recibo on recibo.k00_numpre = cornump.k12_numnov                                       ";
    $sSql .= "          inner join arretipo on arretipo.k00_tipo = recibo.k00_tipo                                        ";
    $sSql .= "    where cornump.k12_numnov = {$this->iCodigoArrecadacao}                                                  ";
    $sSql .= " group by abatimento,                                                                                       ";
    $sSql .= "          numpre,                                                                                           ";
    $sSql .= "          parcela,                                                                                          ";
    $sSql .= "          total,                                                                                            ";
    $sSql .= "          tipo,                                                                                             ";
    $sSql .= "          tipodebito,                                                                                       ";
    $sSql .= "          tipodebitodescricao,                                                                              ";
    $sSql .= "          receita,                                                                                          ";
    $sSql .= "          receitadescricao,                                                                                 ";
    $sSql .= "          datavencimento,                                                                                   ";
    $sSql .= "          datapagamento,                                                                                    ";
    $sSql .= "          dataefetivacao,                                                                                   ";
    $sSql .= "          valor                                                                                             ";

                                             ///////////////
                                             //  union 7  //
                                             ///////////////

    $sSql .= "    union all                                                                                               ";
    $sSql .= "   select distinct                                                                                          ";
    $sSql .= "          0                   as abatimento,                                                                ";
    $sSql .= "          arrepaga.k00_numpre as numpre,                                                                    ";
    $sSql .= "          arrepaga.k00_numpar as parcela,                                                                   ";
    $sSql .= "          arrepaga.k00_numtot as total,                                                                     ";
    $sSql .= "          'Normal'            as tipo,                                                                      ";
    $sSql .= "          arretipo.k00_tipo   as tipodebito,                                                                ";
    $sSql .= "          arretipo.k00_descr  as tipodebitodescricao,                                                       ";
    $sSql .= "          tabrec.k02_codigo   as receita,                                                                   ";
    $sSql .= "          tabrec.k02_drecei   as receitadescricao,                                                          ";
    $sSql .= "          arrepaga.k00_dtvenc as datavencimento,                                                            ";
    $sSql .= "          arrepaga.k00_dtpaga as datapagamento,                                                             ";
    $sSql .= "          cornump.k12_data    as dataefetivacao,                                                            ";
    $sSql .= "          arrepaga.k00_valor  as valor                                                                      ";
    $sSql .= "     from arrepaga                                                                                          ";
    $sSql .= "          inner join cornump on cornump.k12_numpre = arrepaga.k00_numpre                                    ";
    $sSql .= "                            and cornump.k12_numpar = arrepaga.k00_numpar                                    ";
    $sSql .= "          inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                      ";
    $sSql .= "          left  join recibo on recibo.k00_numpre = cornump.k12_numnov                                       ";
    $sSql .= "          left  join recibopaga on recibopaga.k00_numnov = cornump.k12_numnov                               ";
    $sSql .= "          left  join arrecant on arrecant.k00_numpre = cornump.k12_numpre                                   ";
    $sSql .= "                             and arrecant.k00_numpar = cornump.k12_numpar                                   ";
    $sSql .= "          left  join arretipo on arretipo.k00_tipo = arrecant.k00_tipo                                      ";
    $sSql .= "    where recibopaga.k00_numnov is null                                                                     ";
    $sSql .= "      and recibo.k00_numpre is null                                                                         ";
    $sSql .= "      and (cornump.k12_numpre::text || cornump.k12_numpar::text)::text = '{$this->iCodigoArrecadacao}'      ";
    $sSql .= " group by abatimento,                                                                                       ";
    $sSql .= "          numpre,                                                                                           ";
    $sSql .= "          parcela,                                                                                          ";
    $sSql .= "          total,                                                                                            ";
    $sSql .= "          tipo,                                                                                             ";
    $sSql .= "          tipodebito,                                                                                       ";
    $sSql .= "          tipodebitodescricao,                                                                              ";
    $sSql .= "          receita,                                                                                          ";
    $sSql .= "          receitadescricao,                                                                                 ";
    $sSql .= "          datavencimento,                                                                                   ";
    $sSql .= "          datapagamento,                                                                                    ";
    $sSql .= "          dataefetivacao,                                                                                   ";
    $sSql .= "          valor                                                                                             ";

    $rsReciboPago = db_query($sSql);

    if(empty($rsReciboPago)){
      throw new Exception("Erro ao consultar registros do recibo.");
    }

    return db_utils::getCollectionByRecord($rsReciboPago);
  }

  public function getDadosBoleto($iNumpre, $iNumpar, $iReceit){

    /////////////////////////////////////
    // SOMENTE ABORDA CASOS MARCADOS!  //
    /////////////////////////////////////
    // x | recibo baixa normal         //    
    //   | recibo baixa parcial        //    
    //   | recibo baixa credito        //    
    // x | recibo caixa normal         //    
    // x | carne baixa normal          //    
    //   | carne baixa parcial         //    
    //   | carne baixa credito         //    
    // x | carne caixa normal          //    
    // x | recibo avulso baixa normal  //    
    // x | recibo avulso baixa parcial //    
    // x | recibo avulso baixa credito //    
    // x | recibo avulso caixa normal  //    
    /////////////////////////////////////

    $sSql  = " select (select distinct true                                                                               ";
    $sSql .= "           from disbanco                                                                                    ";
    $sSql .= "                inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre                        ";
    $sSql .= "          where disbanco.classi is true                                                                     ";
    $sSql .= "            and recibopaga.k00_numpre = arrepaga.k00_numpre                                                 ";
    $sSql .= "            and recibopaga.k00_numpar = arrepaga.k00_numpar                                                 ";
    $sSql .= "            and recibopaga.k00_receit = arrepaga.k00_receit                                                 ";
    $sSql .= "        ) as recibo_carne_baixa_normal,                                                                     ";
    $sSql .= "        (select distinct true                                                                               ";
    $sSql .= "           from disbanco                                                                                    ";
    $sSql .= "                inner join recibo on recibo.k00_numpre = disbanco.k00_numpre                                ";
    $sSql .= "                left  join abatimentorecibo on abatimentorecibo.k127_numprerecibo = disbanco.k00_numpre     ";
    $sSql .= "          where disbanco.classi is true                                                                     ";
    $sSql .= "            and abatimentorecibo.k127_numprerecibo is null                                                  ";
    $sSql .= "            and recibo.k00_numpre = arrepaga.k00_numpre                                                     ";
    $sSql .= "            and recibo.k00_numpar = arrepaga.k00_numpar                                                     ";
    $sSql .= "            and recibo.k00_receit = arrepaga.k00_receit                                                     ";
    $sSql .= "        ) as recibo_caixa_normal,                                                                           ";
    $sSql .= "        (select distinct true                                                                               ";
    $sSql .= "           from cornump                                                                                     ";
    $sSql .= "                inner join recibo on recibo.k00_numpre = cornump.k12_numnov                                 ";
    $sSql .= "          where cornump.k12_numpre = arrepaga.k00_numpre                                                    ";
    $sSql .= "            and cornump.k12_numpar = arrepaga.k00_numpar                                                    ";
    $sSql .= "            and cornump.k12_receit = arrepaga.k00_receit                                                    ";
    $sSql .= "        ) as carne_caixa_normal,                                                                            ";
    $sSql .= "        (select distinct true                                                                               ";
    $sSql .= "           from abatimentorecibo                                                                            ";
    $sSql .= "                inner join db_reciboweb on db_reciboweb.k99_numpre_n = abatimentorecibo.k127_numpreoriginal ";
    $sSql .= "                inner join disbanco on disbanco.k00_numpre = abatimentorecibo.k127_numprerecibo             ";
    $sSql .= "                inner join arrepaga ar on ar.k00_numpre = abatimentorecibo.k127_numprerecibo                ";
    $sSql .= "          where db_reciboweb.k99_tipo = 1                                                                   ";
    $sSql .= "            and disbanco.classi is true                                                                     ";
    $sSql .= "            and ar.k00_numpre = arrepaga.k00_numpre                                                         ";
    $sSql .= "            and ar.k00_numpar = arrepaga.k00_numpar                                                         ";
    $sSql .= "            and ar.k00_receit = arrepaga.k00_receit                                                         ";
    $sSql .= "        ) as recibo_avulso_baixa_normal,                                                                    ";
    $sSql .= "        (select distinct true                                                                               ";
    $sSql .= "           from cornump                                                                                     ";
    $sSql .= "                inner join recibopaga on recibopaga.k00_numnov = cornump.k12_numnov                         ";
    $sSql .= "          where cornump.k12_numpre = arrepaga.k00_numpre                                                    ";
    $sSql .= "            and cornump.k12_numpar = arrepaga.k00_numpar                                                    ";
    $sSql .= "            and cornump.k12_receit = arrepaga.k00_receit                                                    ";
    $sSql .= "        ) as recibo_avulso_caixa_normal,                                                                    ";
    $sSql .= "        (select distinct true                                                                               ";
    $sSql .= "           from cornump                                                                                     ";
    $sSql .= "                left join recibopaga on recibopaga.k00_numnov = cornump.k12_numnov                          ";
    $sSql .= "                left join recibo on recibo.k00_numpre = cornump.k12_numnov                                  ";
    $sSql .= "          where recibopaga.k00_numnov is null                                                               ";
    $sSql .= "            and recibo.k00_numpre is null                                                                   ";
    $sSql .= "            and cornump.k12_numpre = arrepaga.k00_numpre                                                    ";
    $sSql .= "            and cornump.k12_numpar = arrepaga.k00_numpar                                                    ";
    $sSql .= "            and cornump.k12_receit = arrepaga.k00_receit                                                    ";
    $sSql .= "        ) as carne                                                                                          ";
    $sSql .= "   from arrepaga                                                                                            ";
    $sSql .= "  where arrepaga.k00_numpre = {$iNumpre}                                                                    ";
    $sSql .= "    and arrepaga.k00_numpar = {$iNumpar}                                                                    ";
    $sSql .= "    and arrepaga.k00_receit = {$iReceit}                                                                    ";

    $rsDadosBoletoOrigem = db_query($sSql);

    if(empty($rsDadosBoletoOrigem)){
      throw new Exception("Erro ao consultar registros de origem do debito.");
    }

    $aDadosBoletoOrigem = pg_fetch_object($rsDadosBoletoOrigem);

    if ($aDadosBoletoOrigem->recibo_carne_baixa_normal) {

      $sSql = $this->getSqlDadosBoletoReciboCarneBaixaNormal($iNumpre, $iNumpar, $iReceit);

    } else if ($aDadosBoletoOrigem->recibo_caixa_normal) {

      $sSql = $this->getSqlDadosBoletoReciboCaixaNormal($iNumpre, $iNumpar, $iReceit);

    } else if ($aDadosBoletoOrigem->carne_caixa_normal) {

      $sSql = $this->getSqlDadosBoletoCarneCaixaNormal($iNumpre, $iNumpar, $iReceit);

    } else if ($aDadosBoletoOrigem->recibo_avulso_baixa_normal) {

      $sSql = $this->getSqlDadosBoletoReciboAvulsoBaixaNormal($iNumpre, $iNumpar, $iReceit);
      
    } else if ($aDadosBoletoOrigem->recibo_avulso_caixa_normal) {

      $sSql = $this->getSqlDadosBoletoReciboAvulsoCaixaNormal($iNumpre, $iNumpar, $iReceit);
    } else if ($aDadosBoletoOrigem->carne) {

      $sSql = $this->getSqlDadosBoletoCarne($iNumpre, $iNumpar, $iReceit);
    }

    $sSql .= " order by numpre asc, parcela asc, receita asc";
    $rsDadosBoleto = db_query($sSql);

    if(empty($rsDadosBoleto)){
      throw new Exception("Erro ao consultar registros de origem do debito.");
    }

    $aDadosBoleto = db_utils::getCollectionByRecord($rsDadosBoleto);

    return $aDadosBoleto;
  }

  private function getSqlDadosBoletoReciboCarneBaixaNormal($iNumpre, $iNumpar, $iReceit) {
    
    $sSql  = " select distinct                                                                                           ";
    $sSql .= "        arrepaga.k00_numpre as numpre,                                                                     ";
    $sSql .= "        arrepaga.k00_numpar as parcela,                                                                    ";
    $sSql .= "        arrepaga.k00_numtot as total,                                                                      ";
    $sSql .= "        'Normal'            as tipo,                                                                       ";
    $sSql .= "        arretipo.k00_tipo   as tipodebito,                                                                 ";
    $sSql .= "        arretipo.k00_descr  as tipodebitodescricao,                                                        ";
    $sSql .= "        tabrec.k02_codigo   as receita,                                                                    ";
    $sSql .= "        tabrec.k02_drecei   as receitadescricao,                                                           ";
    $sSql .= "        arrecant.k00_dtvenc as datavencimento,                                                             ";
    $sSql .= "        arrepaga.k00_dtpaga as datapagamento,                                                              ";
    $sSql .= "        disbanco.dtpago     as dataefetivacao,                                                             ";
    $sSql .= "        arrepaga.k00_valor  as valor                                                                       ";
    $sSql .= "   from disbanco                                                                                           ";
    $sSql .= "        inner join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre                               ";
    $sSql .= "        inner join arrepaga on arrepaga.k00_numpre = recibopaga.k00_numpre                                 ";
    $sSql .= "                           and arrepaga.k00_numpar = recibopaga.k00_numpar                                 ";
    $sSql .= "                           and arrepaga.k00_receit = recibopaga.k00_receit                                 ";
    $sSql .= "        inner join arrecant on arrecant.k00_numpre = recibopaga.k00_numpre                                 ";
    $sSql .= "                           and arrecant.k00_numpar = recibopaga.k00_numpar                                 ";
    $sSql .= "        inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                       ";
    $sSql .= "        inner join arretipo on arretipo.k00_tipo = arrecant.k00_tipo                                       ";
    $sSql .= "  where disbanco.k00_numpre = (select distinct recibopaga.k00_numnov                                       ";
    $sSql .= "                                 from arrepaga                                                             ";
    $sSql .= "                                      inner join recibopaga on recibopaga.k00_numpre = arrepaga.k00_numpre ";
    $sSql .= "                                                           and recibopaga.k00_numpar = arrepaga.k00_numpar ";
    $sSql .= "                                                           and recibopaga.k00_receit = arrepaga.k00_receit ";
    $sSql .= "                                      inner join disbanco on disbanco.k00_numpre = recibopaga.k00_numnov   ";
    $sSql .= "                                where disbanco.classi is true                                              ";
    $sSql .= "                                  and arrepaga.k00_numpre = {$iNumpre}                                     ";
    $sSql .= "                                  and arrepaga.k00_numpar = {$iNumpar}                                     ";
    $sSql .= "                                  and arrepaga.k00_receit = {$iReceit})                                    ";

    return $sSql; 
  }

  private function getSqlDadosBoletoReciboCaixaNormal($iNumpre, $iNumpar, $iReceit) {
                                                                               
    $sSql  = " select distinct                                                                                     ";
    $sSql .= "        arrepaga.k00_numpre as numpre,                                                               ";
    $sSql .= "        arrepaga.k00_numpar as parcela,                                                              ";
    $sSql .= "        arrepaga.k00_numtot as total,                                                                ";
    $sSql .= "        'Normal'            as tipo,                                                                 ";
    $sSql .= "        arretipo.k00_tipo   as tipodebito,                                                           ";
    $sSql .= "        arretipo.k00_descr  as tipodebitodescricao,                                                  ";
    $sSql .= "        tabrec.k02_codigo   as receita,                                                              ";
    $sSql .= "        tabrec.k02_drecei   as receitadescricao,                                                     ";
    $sSql .= "        recibo.k00_dtvenc   as datavencimento,                                                       ";
    $sSql .= "        arrepaga.k00_dtpaga as datapagamento,                                                        ";
    $sSql .= "        disbanco.dtpago     as dataefetivacao,                                                       ";
    $sSql .= "        arrepaga.k00_valor  as valor                                                                 ";
    $sSql .= "   from disbanco                                                                                     ";
    $sSql .= "        inner join recibo on recibo.k00_numpre = disbanco.k00_numpre                                 ";
    $sSql .= "        inner join arretipo on arretipo.k00_tipo = recibo.k00_tipo                                   ";
    $sSql .= "        inner join arrepaga  on arrepaga.k00_numpre = recibo.k00_numpre                              ";
    $sSql .= "                            and arrepaga.k00_numpar = recibo.k00_numpar                              ";
    $sSql .= "                            and arrepaga.k00_receit = recibo.k00_receit                              ";
    $sSql .= "        inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                 ";
    $sSql .= "        left  join abatimentorecibo on abatimentorecibo.k127_numprerecibo = disbanco.k00_numpre      ";
    $sSql .= "  where disbanco.classi is true                                                                      ";
    $sSql .= "    and abatimentorecibo.k127_numprerecibo is null                                                   ";
    $sSql .= "    and disbanco.k00_numpre = (select distinct recibo.k00_numpre                                     ";
    $sSql .= "                                 from arrepaga                                                       ";
    $sSql .= "                                      inner join recibo on recibo.k00_numpre = arrepaga.k00_numpre   ";
    $sSql .= "                                                       and recibo.k00_numpar = arrepaga.k00_numpar   ";
    $sSql .= "                                                       and recibo.k00_receit = arrepaga.k00_receit   ";
    $sSql .= "                                      inner join disbanco on disbanco.k00_numpre = recibo.k00_numpre ";
    $sSql .= "                                where disbanco.classi is true                                        ";
    $sSql .= "                                  and arrepaga.k00_numpre = {$iNumpre}                               ";
    $sSql .= "                                  and arrepaga.k00_numpar = {$iNumpar}                               ";
    $sSql .= "                                  and arrepaga.k00_receit = {$iReceit})                              ";

    return $sSql; 
  }

  private function getSqlDadosBoletoCarneCaixaNormal($iNumpre, $iNumpar) {
    
    $sSql  = " select distinct                                                       ";
    $sSql .= "        arrepaga.k00_numpre as numpre,                                 ";
    $sSql .= "        arrepaga.k00_numpar as parcela,                                ";
    $sSql .= "        arrepaga.k00_numtot as total,                                  ";
    $sSql .= "        'Normal'            as tipo,                                   ";
    $sSql .= "        arretipo.k00_tipo   as tipodebito,                             ";
    $sSql .= "        arretipo.k00_descr  as tipodebitodescricao,                    ";
    $sSql .= "        tabrec.k02_codigo   as receita,                                ";
    $sSql .= "        tabrec.k02_drecei   as receitadescricao,                       ";
    $sSql .= "        arrepaga.k00_dtvenc as datavencimento,                         ";
    $sSql .= "        arrepaga.k00_dtpaga as datapagamento,                          ";
    $sSql .= "        cornump.k12_data    as dataefetivacao,                         ";
    $sSql .= "        arrepaga.k00_valor  as valor                                   ";
    $sSql .= "   from arrepaga                                                       ";
    $sSql .= "        inner join cornump on cornump.k12_numpre = arrepaga.k00_numpre ";
    $sSql .= "                          and cornump.k12_numpar = arrepaga.k00_numpar ";
    $sSql .= "        inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit   ";
    $sSql .= "        inner join recibo on recibo.k00_numpre = cornump.k12_numnov    ";
    $sSql .= "        inner join arretipo on arretipo.k00_tipo = recibo.k00_tipo     ";
    $sSql .= "  where arrepaga.k00_numpre = {$iNumpre}                               ";
    $sSql .= "    and arrepaga.k00_numpar = {$iNumpar}                               ";

    return $sSql; 
  }

  private function getSqlDadosBoletoReciboAvulsoBaixaNormal($iNumpre, $iNumpar) {
    
    $sSql  = " select distinct                                                                                    ";
    $sSql .= "        arrepaga.k00_numpre as numpre,                                                              ";
    $sSql .= "        arrepaga.k00_numpar as parcela,                                                             ";
    $sSql .= "        arrepaga.k00_numtot as total,                                                               ";
    $sSql .= "        'Normal'            as tipo,                                                                ";
    $sSql .= "        recibo.k00_tipo   as tipodebito,                                                            ";
    $sSql .= "        arretipo.k00_descr  as tipodebitodescricao,                                                 ";
    $sSql .= "        tabrec.k02_codigo   as receita,                                                             ";
    $sSql .= "        tabrec.k02_drecei   as receitadescricao,                                                    ";
    $sSql .= "        arrepaga.k00_dtvenc as datavencimento,                                                      ";
    $sSql .= "        arrepaga.k00_dtoper as datapagamento,                                                       ";
    $sSql .= "        disbanco.dtpago     as dataefetivacao,                                                      ";
    $sSql .= "        arrepaga.k00_valor  as valor                                                                ";
    $sSql .= "   from arrepaga                                                                                    ";
    $sSql .= "        inner join abatimentorecibo on abatimentorecibo.k127_numprerecibo = arrepaga.k00_numpre     ";
    $sSql .= "        inner join recibo on recibo.k00_numpre = abatimentorecibo.k127_numprerecibo                 ";
    $sSql .= "        inner join db_reciboweb on db_reciboweb.k99_numpre_n = abatimentorecibo.k127_numpreoriginal ";
    $sSql .= "        inner join disbanco on disbanco.k00_numpre = abatimentorecibo.k127_numprerecibo             ";
    $sSql .= "        inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit                                ";
    $sSql .= "        inner join arretipo on arretipo.k00_tipo = recibo.k00_tipo                                  ";
    $sSql .= "  where db_reciboweb.k99_tipo = 1                                                                   ";
    $sSql .= "    and disbanco.classi is true                                                                     ";
    $sSql .= "    and arrepaga.k00_numpre = {$iNumpre}                                                            ";
    $sSql .= "    and arrepaga.k00_numpar = {$iNumpar}                                                            ";

    return $sSql; 
  }

  private function getSqlDadosBoletoReciboAvulsoCaixaNormal($iNumpre, $iNumpar) {

    $sSql  = " select distinct                                                            ";
    $sSql .= "        arrepaga.k00_numpre as numpre,                                      ";
    $sSql .= "        arrepaga.k00_numpar as parcela,                                     ";
    $sSql .= "        arrepaga.k00_numtot as total,                                       ";
    $sSql .= "        'Normal'            as tipo,                                        ";
    $sSql .= "        arretipo.k00_tipo   as tipodebito,                                  ";
    $sSql .= "        arretipo.k00_descr  as tipodebitodescricao,                         ";
    $sSql .= "        tabrec.k02_codigo   as receita,                                     ";
    $sSql .= "        tabrec.k02_drecei   as receitadescricao,                            ";
    $sSql .= "        arrepaga.k00_dtvenc as datavencimento,                              ";
    $sSql .= "        arrepaga.k00_dtpaga as datapagamento,                               ";
    $sSql .= "        cornump.k12_data    as dataefetivacao,                              ";
    $sSql .= "        arrepaga.k00_valor  as valor                                        ";
    $sSql .= "   from arrepaga                                                            ";
    $sSql .= "        inner join cornump on cornump.k12_numpre = arrepaga.k00_numpre      ";
    $sSql .= "                          and cornump.k12_numpar = arrepaga.k00_numpar      ";
    $sSql .= "        inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit        ";
    $sSql .= "        inner join recibopaga on recibopaga.k00_numnov = cornump.k12_numnov ";
    $sSql .= "        left  join arrecant on arrecant.k00_numpre = arrepaga.k00_numpre    ";
    $sSql .= "                           and arrecant.k00_numpar = arrepaga.k00_numpar    ";
    $sSql .= "        left  join arretipo on arretipo.k00_tipo = arrecant.k00_tipo        ";
    $sSql .= "  where arrepaga.k00_numpre = {$iNumpre}                                    ";
    $sSql .= "    and arrepaga.k00_numpar = {$iNumpar}                                    ";

    return $sSql; 
  }

  private function getSqlDadosBoletoCarne($iNumpre, $iNumpar, $iReceit) {

    $sSql  = " select distinct                                                           ";
    $sSql .= "        arrepaga.k00_numpre as numpre,                                     ";
    $sSql .= "        arrepaga.k00_numpar as parcela,                                    ";
    $sSql .= "        arrepaga.k00_numtot as total,                                      ";
    $sSql .= "        'Normal'            as tipo,                                       ";
    $sSql .= "        arretipo.k00_tipo   as tipodebito,                                 ";
    $sSql .= "        arretipo.k00_descr  as tipodebitodescricao,                        ";
    $sSql .= "        tabrec.k02_codigo   as receita,                                    ";
    $sSql .= "        tabrec.k02_drecei   as receitadescricao,                           ";
    $sSql .= "        arrepaga.k00_dtvenc as datavencimento,                             ";
    $sSql .= "        arrepaga.k00_dtpaga as datapagamento,                              ";
    $sSql .= "        cornump.k12_data    as dataefetivacao,                             ";
    $sSql .= "        arrepaga.k00_valor  as valor                                       ";
    $sSql .= "   from cornump                                                            ";
    $sSql .= "        inner join arrepaga on arrepaga.k00_numpre = cornump.k12_numpre    ";
    $sSql .= "                           and arrepaga.k00_numpar = cornump.k12_numpar    ";
    $sSql .= "                           and arrepaga.k00_receit = cornump.k12_receit    ";
    $sSql .= "        inner join tabrec on tabrec.k02_codigo = arrepaga.k00_receit       ";
    $sSql .= "        left join recibopaga on recibopaga.k00_numnov = cornump.k12_numnov ";
    $sSql .= "        left join recibo on recibo.k00_numpre = cornump.k12_numnov         ";
    $sSql .= "        left join arrecant on arrecant.k00_numpre = arrepaga.k00_numpre    ";
    $sSql .= "                           and arrecant.k00_numpar = arrepaga.k00_numpar   ";
    $sSql .= "        left  join arretipo on arretipo.k00_tipo = arrecant.k00_tipo       ";
    $sSql .= "  where recibopaga.k00_numnov is null                                      ";
    $sSql .= "    and recibo.k00_numpre is null                                          ";
    $sSql .= "    and cornump.k12_numnov = (select cornump.k12_numnov                    ";
    $sSql .= "                                from cornump                               ";
    $sSql .= "                               where cornump.k12_numpre = {$iNumpre}       ";
    $sSql .= "                                 and cornump.k12_numpar = {$iNumpar}       ";
    $sSql .= "                                 and cornump.k12_receit = {$iReceit})      ";

    return $sSql; 
  }
}

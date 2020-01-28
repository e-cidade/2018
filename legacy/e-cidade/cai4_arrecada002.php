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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));

$naocalcula = true;
$isReciboNovo = false;
if (isset($HTTP_POST_VARS ["autenticar"])) {
  $naocalcula = false;
}
if (isset($HTTP_POST_VARS ["calcula"])) {
  $naocalcula = true;
}

parse_str($HTTP_SERVER_VARS ["QUERY_STRING"]);

if (isset($HTTP_POST_VARS ["codrec"])) {
  $codrec = $HTTP_POST_VARS ["codrec"];
  unset($valor_variavel);
}

if (! isset($HTTP_POST_VARS ["reduz"])) {

  $sql  = "select k13_reduz as c01_reduz,                                             ";
  $sql .= "       k13_descr as c01_descr                                              ";
  $sql .= "  from cfautent                                                            ";
  $sql .= "       inner join cfautentconta on k16_id     = k11_id                     ";
  $sql .= "       inner join saltes        on k13_conta  = k16_conta                  ";
  $sql .= "       inner join conplanoreduz on c61_reduz  = k13_conta                  ";
  $sql .= "                               and c61_anousu = ".db_getsession("DB_anousu");
  $sql .= "       inner join conplano      on c60_codcon = c61_codcon                 ";
  $sql .= "                               and c60_anousu = c61_anousu                 ";
  $sql .= " where k11_ipterm = '".db_getsession("DB_ip")."'                           ";
  $sql .= "   and k11_instit = ".db_getsession("DB_instit");

  /** [Extensão] Filtro da Despesa */


  $resultconta = db_query($sql);
  if (pg_numrows($resultconta) == 1) {
    $HTTP_POST_VARS ["reduz"] = pg_result($resultconta, 0, "c01_reduz");
    $HTTP_POST_VARS ["descr"] = pg_result($resultconta, 0, "c01_descr");
  }

}


if (isset($HTTP_POST_VARS ["codrec"]) || isset($codrec)) {

  // verifica validade do recibo
  $tamCodigoArrecadacao = strlen(trim($codrec));
  if ($tamCodigoArrecadacao > 15) {

    /* @fixme: corrigir codigoarrecadacao*/
    $banco1 = substr($codrec, 0, 3);
    if ( $banco1 == "816" ||
         $banco1 == "817" ||
         $banco1 == "826" ||
         $banco1 == "827" ||
         $banco1 == "806" ||
         $banco1 == "807" ||
         $banco1 == "041" ||
         $banco1 == "104" ||
         $banco1 == "001") {

      if ($banco1 == "001") {

        /* conforme solicitado pelo evandro no caso de osorio em q temos q diferenciar os carnes/recibos do sistema antigo e do novo,
             no sistema novo o seis primeiros digitos serao '000000' */
        if (substr($codrec, 25, 6) == '000000') { //numpre < 4
          $codigoArrecadacao = substr($codrec, 31, 8);
        } else {
          $codigoArrecadacao = substr($codrec, 29, 8);
        }

      } else if ($banco1 == "041") {
          // canela e capivari
          $sNumBanco   = substr($codrec, 21, 13);
          $sSql        = "select * from arrebanco where k00_numbco = '{$sNumBanco}'";
          $rsArrebanco = db_query($sSql);
          $oArrebanco  = db_utils::fieldsMemory($rsArrebanco,0);
          $codigoArrecadacao = str_pad($oArrebanco->k00_numpre,8,'0',STR_PAD_LEFT);

      } else if ($banco1 == "104") {
        // canela e sapiranga
        $sNumBanco   = substr($codrec, 19, 10);
        $sSql        = "select * from arrebanco where substr(k00_numbco,1,10) = '{$sNumBanco}'";
        $rsArrebanco = db_query($sSql);
        $oArrebanco  = db_utils::fieldsMemory($rsArrebanco,0);
        $codigoArrecadacao = str_pad($oArrebanco->k00_numpre,8,'0',STR_PAD_LEFT);

        /* [Extensão] Autenticação de boletos do convênio SigCB */

      } else {
        $codigoArrecadacao = substr($codrec, 33, 8);
      }

    }

  } else {
    $codigoArrecadacao = substr($codrec, 0, 8);
  }

  $sSqlReciboPaga  = " select *                                        ";
  $sSqlReciboPaga .= "   from recibopaga                               ";
  $sSqlReciboPaga .= "  where k00_numnov = {$codigoArrecadacao} limit 1";
  $rsReciboPaga    = db_query($sSqlReciboPaga);
  if ( pg_num_rows($rsReciboPaga) > 0 ) {
    $codrec  = str_pad($codigoArrecadacao,8,"0",STR_PAD_LEFT).'000';
    $HTTP_POST_VARS ["codrec"] = $codrec;
  }

  $sqlrecval  = "select fc_proximo_dia_util(recibopaga.k00_dtpaga) as k00_dtpaga";
  $sqlrecval .= "  from recibopaga ";
  $sqlrecval .= "       left join arrepaga  on arrepaga.k00_numpre = recibopaga.k00_numpre ";
  $sqlrecval .= "                          and arrepaga.k00_numpar = recibopaga.k00_numpar ";
  $sqlrecval .= " where recibopaga.k00_numnov = {$codigoArrecadacao}";
  $sqlrecval .= "   and arrepaga.k00_numpre is null ";
  $sqlrecval .= " union all ";
  $sqlrecval .= "select fc_proximo_dia_util(recibo.k00_dtvenc) as k00_dtvenc ";
  $sqlrecval .= "  from recibo ";
  $sqlrecval .= "       left join arrepaga  on arrepaga.k00_numpre = recibo.k00_numpre ";
  $sqlrecval .= "                          and arrepaga.k00_numpar = recibo.k00_numpar ";
  $sqlrecval .= " where recibo.k00_numpre = {$codigoArrecadacao} ";
  $sqlrecval .= "   and arrepaga.k00_numpre is null ";
  $sqlrecval .= " limit 1 ";
  $recval = db_query($sqlrecval);

  if (pg_numrows($recval) > 0) {

    if (strtotime(pg_result($recval, 0, 0)) < strtotime(date('Y-m-d', db_getsession("DB_datausu")))) {

      // Verifica se o Recibo é de somente **Uma Parcela** e
      // se é Carne da CGF, Emissao Geral de IPTU ou Emissao Geral de ISSQN
      $sqlReciboUmaParcela  = "select k99_numpre, ";
      $sqlReciboUmaParcela .= "       k99_numpar  ";
      $sqlReciboUmaParcela .= "  from db_reciboweb ";
      $sqlReciboUmaParcela .= " where k99_numpre_n = {$codigoArrecadacao} ";
      $sqlReciboUmaParcela .= "   and k99_tipo     in (2, 5, 6) ";
      $sqlReciboUmaParcela .= "   and (select count(distinct k00_numpar) ";
      $sqlReciboUmaParcela .= "          from recibopaga ";
      $sqlReciboUmaParcela .= "         where k00_numnov = k99_numpre_n) = 1";
      $resReciboUmaParcela  = db_query($sqlReciboUmaParcela);
      if (pg_numrows($resReciboUmaParcela) > 0) {
        db_fieldsmemory($resReciboUmaParcela, 0);
        // Busca NUMPRE/NUMPAR do Arrecad pra processar os cálculos de Juros/Multa/Correcao e
        // permitir efetivar a Arrecadacao da Receita
        $codigoArrecadacao = str_pad($k99_numpre, 8, "0", STR_PAD_LEFT);
        $codrec = $codigoArrecadacao . str_pad($k99_numpar, 3, "0", STR_PAD_LEFT);
        $HTTP_POST_VARS ["codrec"] = $codrec;

      } else {
        echo "<script>";
        echo "  parent.alert(' Recibo Inválido. Verifique o Vencimento! (" . db_formatar(pg_result($recval, 0, 0), 'd') . ")'); ";
        echo "  location.href = 'cai4_arrecada002.php?invalido=true'; ";
        echo "</script>";
        exit();

      }

    }

  }

  // T24879: Valida se eh numpre da recibopaga ZERADO e da emissao geral do issqn
  // entao troca o numpre pelo do Arrecad para processar a arrecadacao de receita
  $sqlReciboEmissaoIss  = "select k99_numpre,  ";
  $sqlReciboEmissaoIss .= "       k99_numpar,  ";
  $sqlReciboEmissaoIss .= "       sum(k00_valor) ";
  $sqlReciboEmissaoIss .= "  from db_reciboweb ";
  $sqlReciboEmissaoIss .= "       inner join recibopaga  on k00_numpre = k99_numpre ";
  $sqlReciboEmissaoIss .= "                             and k00_numpar = k99_numpar ";
  $sqlReciboEmissaoIss .= " where k99_numpre_n = {$codigoArrecadacao} ";
  $sqlReciboEmissaoIss .= "   and k99_tipo     = 6 ";
  $sqlReciboEmissaoIss .= " group by k99_numpre, k99_numpar ";
  $sqlReciboEmissaoIss .= " having cast(sum(k00_valor) as numeric) = cast(0 as numeric) ";
  $resReciboEmissaoIss = db_query($sqlReciboEmissaoIss);
  if (pg_numrows($resReciboEmissaoIss) > 0) {
    db_fieldsmemory($resReciboEmissaoIss, 0);
    $codigoArrecadacao = str_pad($k99_numpre, 8, "0", STR_PAD_LEFT);
    $codrec            = $codigoArrecadacao . str_pad($k99_numpar, 3, "0", STR_PAD_LEFT);
    $HTTP_POST_VARS ["codrec"] = $codrec;
  }

  $sArrebanco = "select k00_numpre as k00_numpre_numbco, k00_numpar as k00_numpar_numbco from caixa.arrebanco where k00_numbco = '" . $codrec . "'";
  $rsArrebanco = db_query($sArrebanco) or die($sArrebanco);
  if (pg_numrows($rsArrebanco) > 0) {
    db_fieldsmemory($rsArrebanco,0);
    $codrec = str_pad( $k00_numpre_numbco , 8, "0", STR_PAD_LEFT) . str_pad( $k00_numpar_numbco , 3, "0", STR_PAD_LEFT);
    $HTTP_POST_VARS ['codrec'] = $codrec;
  }

  if ($naocalcula == true) {

    if (strlen(trim($codrec)) > 15) {

      $banco = substr($codrec, 0, 3);

      /* @note: Busca pelo codigo de barras inteirro para tratar os casos dos novos nosso numero gerados */
      $sSqlReciboCodBar = "select * from recibocodbar where k00_codbar = '{$codrec}'";
      $rsReciboCodBar = db_query($sSqlReciboCodBar);

      if (empty($rsReciboCodBar)) {
        echo "<script>
                parent.alert('Erro ao consultar o código de barras. Verifique. (Banco : $banco)');
                location.href = 'cai4_arrecada002.php?invalido=true';
              </script>";
      }

      if (pg_num_rows($rsReciboCodBar) > 0) {

        $oReciboCodBar  = db_utils::fieldsMemory($rsReciboCodBar,0);
        $sSql        = "select * from arrebanco where k00_numpre = '{$oReciboCodBar->k00_numpre}'";

        $rsArrebanco = db_query($sSql);

        if ( !$rsArrebanco ) {

          echo "<script>";
          echo "  parent.alert('Erro ao buscar dados do recibo'); ";
          echo "  location.href = 'cai4_arrecada002.php?invalido=true'; ";
          echo "</script>";
          exit();
        }

        if ( pg_num_rows($rsArrebanco) > 0 ) {

          $oArrebanco  = db_utils::fieldsMemory($rsArrebanco,0);

          $codrec      = str_pad($oArrebanco->k00_numpre,8,'0',STR_PAD_LEFT) . str_pad( $oArrebanco->k00_numpar,3,'0',STR_PAD_LEFT);

          $iNumpre = $oArrebanco->k00_numpre;
          $iNumpar = $oArrebanco->k00_numpar;
          $isReciboNovo = true;
        }
      }

      if (($banco == "816" ||
           $banco == "817" ||
           $banco == "826" ||
           $banco == "827" ||
           $banco == "806" ||
           $banco == "807" ||
           $banco == "041" ||
           $banco == "104" ||
           $banco == "001") && !$isReciboNovo) {

        if ($banco == "001") {

          if ( strlen(trim($codrec)) == 44 ) { // BDL Banco do Brasil

            if (substr($codrec, 25, 6) <> '000000') {
              $codrec = str_pad(substr($codrec,32,8),8,'0',STR_PAD_LEFT) . str_pad(substr($codrec,40,2),3,'0',STR_PAD_LEFT);
            }else{
              $codrec = str_pad(substr($codrec,31,8),8,'0',STR_PAD_LEFT) . str_pad(substr($codrec,40,2),3,'0',STR_PAD_LEFT);
            }
          } else {

            if (substr($codrec, 25, 6) == '000000') { //numpre < 4
              $codrec = substr($codrec, 31, 8) . substr($codrec, 39, 3);
            } else {
              $codrec = substr($codrec, 29, 8) . "0" . substr($codrec, 37, 2);
            }
          }
        }else if ($banco == "041") {

          // canela e capivari
          $sNumBanco   = substr($codrec, 21, 13);
          $sSql        = "select * from arrebanco where k00_numbco = '{$sNumBanco}'";
          $rsArrebanco = db_query($sSql);
          $oArrebanco  = db_utils::fieldsMemory($rsArrebanco,0);
          $codrec      = str_pad($oArrebanco->k00_numpre,8,'0',STR_PAD_LEFT) . str_pad( $oArrebanco->k00_numpar,3,'0',STR_PAD_LEFT);

        } else if ($banco == "104") {

          // canela e sapiranga
          $sNumBanco   = substr($codrec, 19, 10);
          $sSql        = "select * from arrebanco where substr(k00_numbco,1,10) = '{$sNumBanco}'";
          $rsArrebanco = db_query($sSql);
          $oArrebanco  = db_utils::fieldsMemory($rsArrebanco,0);
          $codrec      = str_pad($oArrebanco->k00_numpre,8,'0',STR_PAD_LEFT) . str_pad( $oArrebanco->k00_numpar,3,'0',STR_PAD_LEFT);

        } else {
          /* @note: corrige numpre/numpar arrecadacao */
          $codrec = substr($codrec, 33, 11);
        }

        $HTTP_POST_VARS ['codrec'] = $codrec;

        if (strlen(trim($codrec)) == 0) {
          echo "<script>
                  parent.alert('Código Inválido. Verifique. (Banco : $banco)');
                  location.href = 'cai4_arrecada002.php?invalido=true';
                </script>";

        }

      } else {
        echo "<script>
                parent.alert('Código Inválido. Verifique. (Banco : $banco)');
                location.href = 'cai4_arrecada002.php?invalido=true';
              </script>";
      }
    }

  /* @note: tratamento caso seja um codigo de barras novo */
   $sCampoNumpre = "substr('{$codrec}',1,8)::integer";
   $sCampoNumpar = "substr('{$codrec}',9,3)::integer";

   if ($isReciboNovo)   {
    $sCampoNumpre = $iNumpre;
    $sCampoNumpar = $iNumpar;
   }

    if (isset($valor_variavel)) {

      $sSqlIssVar  = "update issvar set q05_vlrinf = ".($valor_variavel + 0);
      $sSqlIssVar .= " where q05_numpre = {$sCampoNumpre}";
      $sSqlIssVar .= "   and q05_numpar = {$sCampoNumpar}";
      $result = db_query($sSqlIssVar);
    }

    $sSqlCalcula = "select fc_calcula({$sCampoNumpre},
                                      {$sCampoNumpar},
                                      0,
                                      '".date("Y-m-d", db_getsession("DB_datausu"))."'::date,
                                      '".date("Y-m-d", db_getsession("DB_datausu"))."'::date,
                                      ".db_getsession("DB_anousu").")";

    $result = db_query($sSqlCalcula);
    db_fieldsmemory($result, 0);

    $numero_origem = 0;
    $nome_origem   = "CONTRIBUINTE INDEFINIDO";
    $descr_origem  = "TIPO DE DEBITO INDEFINIDO";
    $tipo_origem   = "ORIGEM INDEFINIDA";

    $tabela_arrecad  = "(select * from arrecad where k00_numpre = ".substr($codrec, 0, 8);
    $tabela_arrecad .= " union ";
    $tabela_arrecad .= " select * from arrecant where k00_numpre = ".substr($codrec, 0, 8)." ) as arrecad ";

    $sql = " select k00_matric         as numero_origem,
                    'MATRIC'           as tipo_origem,
                    arretipo.k00_descr as descr_origem,
                    z01_nome           as nome_origem
               from arrematric
                    inner join $tabela_arrecad on arrecad.k00_numpre = arrematric.k00_numpre
                    inner join arretipo        on arrecad.k00_tipo   = arretipo.k00_tipo
                    inner join iptubase        on k00_matric         = j01_matric
                    inner join cgm             on j01_numcgm         = z01_numcgm
              where arrecad.k00_numpre = " . substr($codrec, 0, 8);
    $result_arrematric = db_query($sql) or die($sql);
    if (pg_numrows($result_arrematric) > 0) {
      db_fieldsmemory($result_arrematric, 0);

    } else {

      $sql = " select k00_inscr          as numero_origem,
                      'INSCRICAO'        as tipo_origem,
                      arretipo.k00_descr as descr_origem,
                      z01_nome           as nome_origem
                 from arreinscr
                      inner join $tabela_arrecad on arrecad.k00_numpre = arreinscr.k00_numpre
                      inner join arretipo        on arrecad.k00_tipo   = arretipo.k00_tipo
                      inner join issbase         on k00_inscr          = q02_inscr
                      inner join cgm             on q02_numcgm         = z01_numcgm
                where arrecad.k00_numpre = " . substr($codrec, 0, 8);
      $result_arreinscr = db_query($sql) or die($sql);
      if (pg_numrows($result_arreinscr) > 0) {
        db_fieldsmemory($result_arreinscr, 0);

      } else {

        $sql = " select k00_numcgm         as numero_origem,
                        'CGM'              as tipo_origem,
                        arretipo.k00_descr as descr_origem,
                        z01_nome           as nome_origem
                   from $tabela_arrecad
                        inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
                        inner join cgm      on k00_numcgm       = z01_numcgm
                  where arrecad.k00_numpre = " . substr($codrec, 0, 8);
        $result_arrecad = db_query($sql) or die($sql);
        if (pg_numrows($result_arrecad) > 0) {
          db_fieldsmemory($result_arrecad, 0);

        } else {

          $sql = " select case when k00_matric is not null then k00_matric else case when k00_inscr is not null then k00_inscr else k00_numcgm end end as numero_origem,
                          case when k00_matric is not null then iptunome else case when k00_inscr is not null then issnome else z01_nome end end as nome_origem,
                          case when k00_matric is not null then 'MATRICULA' else case when k00_inscr is not null then 'INSCRICAO' else 'CGM' end end as tipo_origem,
                          'RECIBO CGF' as descr_origem
                     from ( select k00_numcgm,
                                   cgm.z01_nome,
                                   k00_matric,
                                   cgmiptubase.z01_nome as iptunome,
                                   k00_inscr,
                                   cgmissbase.z01_nome as issnome
                              from recibopaga
                                   left join cgm             on z01_numcgm             = recibopaga.k00_numcgm
                                   left join arrematric      on recibopaga.k00_numpre  = arrematric.k00_numpre
                                   left join iptubase        on k00_matric             = j01_matric
                                   left join cgm cgmiptubase on cgmiptubase.z01_numcgm = j01_numcgm
                                   left join arreinscr       on recibopaga.k00_numpre  = arreinscr.k00_numpre
                                   left join issbase         on q02_inscr              = k00_inscr
                                   left join cgm cgmissbase  on cgmissbase.z01_numcgm  = q02_numcgm
                             where k00_numnov = " . substr($codrec, 0, 8) . " limit 1 ) as x ";
          $result_recibo = db_query($sql) or die($sql);
          if (pg_numrows($result_recibo) > 0) {
            db_fieldsmemory($result_recibo, 0);

          } else {

            $sql = " select case when k00_matric is not null then k00_matric else case when k00_inscr is not null then k00_inscr else k00_numcgm end end as numero_origem,
                            case when k00_matric is not null then iptunome else case when k00_inscr is not null then issnome else z01_nome end end       as nome_origem,
                            case when k00_matric is not null then 'MATRICULA' else case when k00_inscr is not null then 'INSCRICAO' else 'CGM' end end   as tipo_origem,
                            'RECIBO AVULSO' as descr_origem,
                            historicorecibo,
                            pgtoparcial
                      from ( select	k00_numcgm,
                                    cgm.z01_nome,
                                    k00_matric,
                                    cgmiptubase.z01_nome       as iptunome,
                                    k00_inscr,
                                    cgmissbase.z01_nome        as issnome,
                                    recibo.k00_hist            as historicorecibo,
                                    abatimento.k125_sequencial as pgtoparcial
                               from recibo
                                    left join cgm               on cgm.z01_numcgm                     = recibo.k00_numcgm
                                    left join arrematric        on arrematric.k00_numpre              = recibo.k00_numpre
                                    left join iptubase          on iptubase.j01_matric                = arrematric.k00_matric
                                    left join cgm cgmiptubase   on cgmiptubase.z01_numcgm             = iptubase.j01_numcgm
                                    left join arreinscr         on arreinscr.k00_numpre               = recibo.k00_numpre
                                    left join issbase           on arreinscr.k00_inscr                = issbase.q02_inscr
                                    left join cgm cgmissbase    on cgmissbase.z01_numcgm              = issbase.q02_numcgm
                                    left join abatimentorecibo  on abatimentorecibo.k127_numprerecibo = recibo.k00_numpre
                                    left join abatimento        on abatimento.k125_sequencial         = abatimentorecibo.k127_abatimento
                                                               and abatimento.k125_tipoabatimento     = 1
                              where recibo.k00_numpre = " . substr($codrec, 0, 8) . "
                              limit 1
                           ) as x ";
            $result_recibo = db_query($sql) or die($sql);
            if (pg_numrows($result_recibo) > 0) {
              db_fieldsmemory($result_recibo, 0);

              if ( isset($pgtoparcial) && trim($pgtoparcial) != '' ) {
                $sSqlValidaPgtoParcial = " select 1
                                             from abatimentoarreckey
                                                  inner join arreckey on arreckey.k00_sequencial = abatimentoarreckey.k128_arreckey
                                            where abatimentoarreckey.k128_abatimento = {$pgtoparcial}
                                              and not exists ( select 1
                                                                 from arrecad
                                                                where arrecad.k00_numpre = arreckey.k00_numpre
                                                                  and arrecad.k00_numpar = arreckey.k00_numpar
                                                                  and arrecad.k00_receit = arreckey.k00_receit )";
                $rsValidaPgtoParcial   = db_query($sSqlValidaPgtoParcial);
                if ( pg_num_rows($rsValidaPgtoParcial) > 0 ) {
                  echo " <script>
                           parent.alert('Operação Cancelada, débito de origem do Pagamento Parcial não encontrado!');
                           location.href = 'cai4_arrecada002.php?invalido=true';
                         </script>";

                }

              }

            }

          }

        }

      }

    }

    $origem = $descr_origem . " - " . $tipo_origem . ": $numero_origem - $nome_origem";
    if (empty($fc_calcula)) {

      echo "<script>
              parent.alert('Contate CPD.');
              location.href = 'cai4_arrecada002.php?invalido=true';
            </script>";

    } else {

      if (substr($fc_calcula, 0, 1) != '1') {
        if (substr($fc_calcula, 0, 1) == '9') {
          ?>
            <script>
              parent.alert('Código de Arrecadação Inválido.');
              location.href = 'cai4_arrecada002.php?invalido=true';
            </script>
          <?
          exit();
        }

        if (substr($fc_calcula, 0, 1) == '8') {
          ?>
            <script>
              var lista = parent.recibos.document.getElementById("tab");
              var valor = prompt('Valor a recolher:',0);

              tempto = valor.indexOf(".");

              nValorInteiro = valor.split(".");

              if (nValorInteiro[0].length > 8){
                valor = "0";
                alert('Valor inválido. Verifique.');
              }

              if (valor==""||valor=="0") {
                location.href = 'cai4_arrecada002.php?invalido=true';
              } else {

                if (valor.search(',') != '-1') {
                  valor = valor.replace('.','');
                  valor = valor.replace(',','.');
                }

                location.href = 'cai4_arrecada002.php?codrec=<?=$HTTP_POST_VARS ["codrec"]?>&valor_variavel='+valor;
              }
            </script>
          <?
          exit();
        }

        if (substr($fc_calcula, 0, 1) == '7') {
          ?>
            <script>
              var lista = parent.recibos.document.getElementById("tab");

              parent.alert('Parcela com valor Zerado. Contate Suporte.');
              location.href = 'cai4_arrecada002.php?invalido=true';
            </script>
          <?
          exit();
        }

        if (substr($fc_calcula, 0, 1) == '6') {
          ?>
            <script>
              var lista = parent.recibos.document.getElementById("tab");
              parent.alert('Uma das parcelas da unica esta zerado. Contate Suporte.');
              location.href = 'cai4_arrecada002.php?invalido=true';
            </script>
          <?
          exit();
        }

        if (substr($fc_calcula, 0, 1) == '5') {
          ?>
            <script>
              var lista = parent.recibos.document.getElementById("tab");
              parent.alert('Valores Incosistentes.');
              location.href = 'cai4_arrecada002.php?invalido=true';
            </script>
          <?
          exit();
        }

        if (substr($fc_calcula, 0, 1) == '4' || substr($fc_calcula, 0, 1) == '2') {

          $vlrhist = substr($fc_calcula, 1, 13);
          $vlrcor = substr($fc_calcula, 1, 13);
          $vlrjuros = 0;
          $vlrmulta = 0;
          $vlrdesconto = 0;
          $dtvenc = '';
          $vlrimp = $vlrcor + $vlrjuros + $vlrmulta - $vlrdesconto;

          if (substr($fc_calcula, 0, 1) == '4') {

            if (! empty($vlrhist)) {

              if (isset($historicorecibo) && $historicorecibo == 503) {
                ?>
                  <script> var estorno_codrec = 255; </script>
                <?
              } else if (isset($pgtoparcial) && trim($pgtoparcial) != '') {
                ?>
                  <script> var estorno_codrec = 4; </script>
                <?
              } else {
                ?>
                  <script> var estorno_codrec = 1; </script>
                <?
              }

            } else {
                ?>
                  <script> var estorno_codrec = 2; </script>
                <?
            }

          } else {
            ?>
               <script> var estorno_codrec = 3;</script>
            <?
          }

        }
        $fc_calcula = "";

      } else {
        $vlrhist = 0 + ( float ) substr($fc_calcula, 1, 13);
        $vlrcor = 0 + ( float ) substr($fc_calcula, 15, 13);
        $vlrjuros = "0" . trim(substr($fc_calcula, 27, 13));
        $vlrmulta = "0" . trim(substr($fc_calcula, 40, 13));
        $vlrdesconto = "0" . trim(substr($fc_calcula, 53, 13));
        $dtvenc = substr($fc_calcula, 66, 10);
        $vlrimp = ( float ) $vlrcor + ( float ) $vlrjuros + ( float ) $vlrmulta - ( float ) $vlrdesconto;

        /** Extensao : Inicio [autenticacao_taxa_expediente] */
        /** Extensao : Fim [autenticacao_taxa_expediente] */

        ?>
          <script>
            var estorno_codrec = 0;
          </script>
        <?

      }

    }

  } else {
    ?>
     <script>
       var estorno_codrec = 0;
       var lista = parent.recibos.document.getElementById("tab");
       if (lista.rows.length == 1) {
         parent.alert('Sem Código a processar.');
       } else {
         parent.alert('Há Códigos a processar.');
       }
     </script>
    <?
  }

} else {

  if (! isset($invalido)) {
    ?>
      <script>
        var estorno_codrec = 0;
        parent.document.form1.apagar.value = '';
        parent.document.form1.recebido.value = '';
        parent.document.form1.troco.value = '';
      </script>
    <?
  }

}

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_gravacodrec(dados,codrec,estorno_numpre) {
	var tab = (parent.recibos.document.all)?parent.recibos.document.all.tab:parent.recibos.document.getElementById('tab');
  var processa = true;

  if ( codrec.substr(8,3) == '000') {

    for (i=0;i<tab.rows.length;i++) {
      var unica = tab.rows[i].id.substr(3,8);
      if ( codrec.substr(0,8) == unica) {
        parent.alert('Documento Inválido, nao pode arrecadar Única \n juntamente com a Parcela. Verifique!');
        processa = false;
      }

    }

  } else {

    for (i=0;i<tab.rows.length;i++) {

      if ( 'id_'+codrec == tab.rows[i].id) {
        parent.alert('Documento já Digitado. Verifique!');
        processa = false;
        break;
      }

      var unica = tab.rows[i].id.substr(3,8);
      var parce = tab.rows[i].id.substr(11,3);
      if ( codrec.substr(0,8) == unica && parce == '000') {
        parent.alert('Documento já Digitado como Parcela Única. Verifique!');
        processa = false;
      }

    }

  }

  if (processa == true) {
    var NovaLinha = tab.insertRow(tab.rows.length);
    NovaLinha.id = 'id_'+codrec;
    NovaColuna = NovaLinha.insertCell(0);
    NovaColuna.style.fontSize = '12px';
    NovaColuna.align = 'left';

    if (estorno_numpre == true) {
      NovaColuna.style.color = 'red';
      NovaColuna.innerHTML = 'Estorno    ';
    } else {
      NovaColuna.style.color = 'black';
      NovaColuna.innerHTML = 'Arrecadacao';
    }

    for (i=1;i<8;i++) {
      NovaColuna = NovaLinha.insertCell(i);
      NovaColuna.style.fontSize = '12px';

      if (i == 1) {
        NovaColuna.align = 'left';
      } else {
        NovaColuna.align = 'right';
      }

      NovaColuna.innerHTML = dados[i-1];
    }

    NovaColuna = NovaLinha.insertCell(8);
    NovaColuna.align = 'center';

    var totalapagar = new Number(parent.document.form1.apagar.value);
    var totalapagar1 = new Number(dados[6]);

    NovaColuna.style.backgroundcolor = 'red';
    NovaColuna.innerHTML = '<input name="vlr_id_'+codrec+'" id="vlr_id_'+codrec+'" value="'+(estorno_numpre==false?totalapagar1:-totalapagar1)+'" type="hidden"><input value="'+codrec+'" name="cancpg'+codrec+'" class="btnPagamentos" id="id_cancpg'+codrec+'" type="button" onclick="js_removelinha(\'id_'+codrec+'\',\'vlr_id_'+codrec+'\')">' ;

    if (estorno_numpre==false) {
      totalapagar = totalapagar + totalapagar1;
    } else {
      totalapagar = totalapagar - totalapagar1;
    }

    parent.document.form1.apagar.value = totalapagar.toFixed(2);

  }

}


function js_autenticar(lMostra) {

  var lista = parent.recibos.document.getElementById("tab");

  if (lista.rows.length == 1) {
    parent.alert('Não Existem códigos a Autenticar.');

  } else {
	  var system_os = new  Browser();

    if (system_os.system =='Windows') {
      document.form1.action = 'cai4_arrecada003.php?system=windows';
      document.form1.submit();

    } else {
      parent.js_OpenJanelaIframe("CurrentWindow.corpo",'db_autent_iframe','cai4_arrecada003.php?reduz='+document.form1.reduz.value+'&system=linux','Autenticação',lMostra);
    }

  }

}


function js_verificacodrec() {

  if (document.form1.codrec.value == '') {
    parent.alert('Codigo de Arrecadação Vazio.');
    document.form1.codrec.value = '';
    document.form1.codrec.focus();
    return false;

  }

  if (isNaN(document.form1.codrec.value) == true) {
    parent.alert('Codigo Inválido.');
    document.form1.codrec.value = '';
    document.form1.codrec.focus();
    return false;

  }

  return true;
}


function js_atualizaconta(qual) {

  if(qual=='reduz') {
    document.form1.descr.options[document.form1.reduz.selectedIndex].selected = true;
  }

  if(qual=='descr') {
    document.form1.reduz.options[document.form1.descr.selectedIndex].selected = true;
  }

}


function js_baixabanco() {
	document.location.href = 'cai4_arrecada005.php';
  parent.recibos.document.location.href = 'cai4_arrecada006.php';

}


function js_anteriores() {
	parent.db_iframe.jan.location.href = 'cai4_arrecada008.php';
  parent.db_iframe.setLargura(300);
  parent.db_iframe.setAltura(200);
  parent.db_iframe.mostraMsg();
  parent.db_iframe.show();
  parent.db_iframe.focus();

}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>

<body bgcolor=#CCCCCC bgcolor="#AAB7D5" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.codrec.focus()";>
<form name="form1" method="post" onSubmit="return js_verificacodrec()" action="">
<?

$sql  = "select k13_reduz as c01_reduz,                                             ";
$sql .= "       k13_descr as c01_descr,                                             ";
$sql .= "       c60_estrut as c01_estrut,                                           ";
$sql .= "       k13_conta                                                           ";
$sql .= "  from saltes                                                              ";
$sql .= "       inner join conplanoreduz on k13_reduz  = c61_reduz                  ";
$sql .= "                               and c61_anousu = ".db_getsession("DB_anousu");
$sql .= "                               and c61_instit = ".db_getsession('DB_instit');
$sql .= "       inner join conplano      on c60_codcon = c61_codcon                 ";
$sql .= "                               and c60_anousu = c61_anousu                 ";
$sql .= " where k13_limite is null or k13_limite >= '".date("Y-m-d", db_getsession("DB_datausu"))."'";
$sql .= " order by c60_estrut";

/** [Extensão] Filtro da Despesa */


$result_conta = db_query($sql);
if (pg_numrows($result_conta) == 0) {
  echo "<script>parent.alert('Sem Contas Cadastradas.');</script>";
  exit();
}

?>
  <table width="99%">
    <tr>
      <td height="26" align="left" valign="middle"><b>Conta:</b></td>
      <td align="left" valign="middle">
        <select onChange="js_atualizaconta(this.name)" name="reduz" id="reduz" style="width:10%">
        <?
          for($i = 0; $i < pg_numrows($result_conta); $i ++) {
            db_fieldsmemory($result_conta, $i);
            echo "<option value=\"$k13_conta\" " . (isset($HTTP_POST_VARS ["reduz"]) ? ($HTTP_POST_VARS ["reduz"] == $k13_conta ? "selected" : "") : "") . ">$k13_conta</option>";
          }
        ?>
        </select> &nbsp;
        <select onChange="js_atualizaconta(this.name)" name="descr" id="descr"  style="width:85%">
        <?
          for($i = 0; $i < pg_numrows($result_conta); $i ++) {
            db_fieldsmemory($result_conta, $i);
            echo "<option value=\"$c01_descr\" " . (isset($HTTP_POST_VARS ["descr"]) ? ($HTTP_POST_VARS ["descr"] == $c01_descr ? "selected" : "") : "") . ">$c01_descr</option>";
          }
        ?>
        </select>
      </td>
      <td align="center" valign="middle">
       <input name="anteriores" type="button" id="anteriores" onClick="js_anteriores();" value="Anteriores">
      </td>
      <td align="right" valign="middle">
       <input name="banco" type="button" id="banco" onClick="js_baixabanco();" value="Baixa Banco">
      </td>
    </tr>
    <tr>
      <td width="13%" height="26" align="left" valign="middle"><b>Autenticar:</b></td>
      <td width="65%" align="left" valign="middle">
        <input name="codrec"
               type="text"
               id="codrec"
               size="30"
               maxlength="100"
               value="<?=(isset($valor_variavel) && ! isset($calcula) ? $codrec : "")?>"
               onchange="return js_quantdig(this.value);">
      </td>
      <td width="11%" align="center" valign="middle">
        <input name="calcula"
               type="<?=(isset($valor_variavel) && ! isset($calcula) ? "hidden" : "submit")?>"
               id="calcula"
               value="  Calcula "
               onclick="return js_quantdig(0);">
      </td>
      <td width="11%" align="right" valign="middle">
        <input name="autenticar"
               type="button"
               id="autenticar"
               onClick="js_autenticar(false)"
               value="Autenticar">

        <a onclick="js_autenticar(true)">&nbsp</a>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
<?

if (isset($valor_variavel) && ! isset($calcula)) {
 ?>
  <script>
   document.form1.submit();
  </script>
 <?
}

if (isset($HTTP_POST_VARS ["codrec"])) {

  $origem2 = addslashes($origem);

  $oDaoAbatimento = db_utils::getDao('abatimento');
  $sSqlAbatimento = $oDaoAbatimento->sql_queryAbatimentoNumpre(substr($codrec, 0, 8), 3);
  $rsAbatimento   = $oDaoAbatimento->sql_record($sSqlAbatimento);

  $lExisteCredito     = 0;
  if ($oDaoAbatimento->numrows > 0) {
    $lExisteCredito   = 1;
  }

  ?>
    <script>
      if (estorno_codrec == 0) {

        dados = Array('<?=$origem2?>','<?=$vlrhist?>','<?=$vlrcor?>','<?=$vlrjuros?>','<?=$vlrmulta?>','<?=$vlrdesconto?>','<?=$vlrimp?>');
        js_gravacodrec(dados,'<?=$HTTP_POST_VARS ["codrec"]?>',false);

      } else {

        var lExisteCredito = <?=$lExisteCredito?>;
        lQuitar = true;

        if (estorno_codrec == 1 || estorno_codrec == 4) {

          if ( parent.confirm('Débito Quitado. Estornar?') ) {

            if ( estorno_codrec == 4 ) {

              var sMsg ='Você está estornando um recibo avulso gerado automaticamente pelo sistema para controle de pagamento parcial \n'
                       +'Isso vai impactar no valor atual do débito com ligação com o pagamento parcial.\n'
                       +'Para consultar utilize pagamentos efetuados!';
              alert(sMsg);
            }

            dados = new Array('<?=$origem2?>','<?=$vlrhist?>','<?=$vlrcor?>','<?=$vlrjuros?>','<?=$vlrmulta?>','<?=$vlrdesconto?>','<?=$vlrimp?>');

            if (lExisteCredito) {
              if (!confirm('Existe crédito lançado para este numpre.\nEssa rotina vai apenas estornar o recibo de crédito, não estornando o lançamento do mesmo.')){
                lQuitar = false;
              }
            }

            if (lQuitar) {
              js_gravacodrec(dados,'<?=$HTTP_POST_VARS ["codrec"]?>',true);
            }

          }

        } else if (estorno_codrec == 3) {
          parent.alert('Valor do Recibo não Confere. (<?=$vlrhist?>)  Verifique o Recibo.');

        } else if (estorno_codrec == 255) {
          parent.alert('recibo Referente a planilha de receita Nao pode ser estornado.');

        } else {
          parent.alert('Erro ao gerar Valor do Estorno. Verifique Recibo');
        }

      }
  </script>
<?
}
?>

<script>
function js_quantdig(valor){

  if (valor.length == 12) {
    valor = valor.substr(1,12);
    document.form1.codrec.value=valor;
  }

  if (valor==0){
    valor=document.form1.codrec.value;
  }
  tam=valor.length;
  if (tam<11){
    alert("Campo Autenticar Invalido!!");
    document.form1.codrec.value="";
    document.form1.codrec.focus();
    return false;
  }else{
    return true;
  }
}
</script>

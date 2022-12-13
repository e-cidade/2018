<?

$sqlverresult  = " select arrematric.k00_numpre,                                                  ";
$sqlverresult .= "        numpremigra.k00_numpar as numpre_migra,                                 ";
$sqlverresult .= "        arrematric.k00_matric                                                   ";
$sqlverresult .= "   from numpremigra                                                             ";
$sqlverresult .= "        inner join arrematric on arrematric.k00_matric = numpremigra.k00_matric ";
$sqlverresult .= "  where numpremigra.k00_numpre = $convenio                                      ";
$verresult     = db_query($sqlverresult);

if (pg_numrows($verresult) != false) {

  $numpre_migra = pg_result($verresult, 0, 0);
  $numpar       = pg_result($verresult, 0, 1);
  $matric       = pg_result($verresult, 0, 2);
}

$sqlverresult  = " select k00_numpar                         ";
$sqlverresult .= "   from numpremigra                        ";
$sqlverresult .= "  where numpremigra.k00_numpre = $convenio ";
$verresult     = db_query($sqlverresult);

if (pg_result($verresult, 0) == "0") {

  /**
   * UNICA
   */
  $sqlverresult  = "  select arrecad.k00_numpre,                                                     ";
  $sqlverresult .= "         arrecad.k00_numpar,                                                     ";
  $sqlverresult .= "         sum(arrecad.k00_valor) as k00_valor                                     ";
  $sqlverresult .= "    from numpremigra                                                             ";
  $sqlverresult .= "         inner join arrematric on arrematric.k00_matric = numpremigra.k00_matric ";
  $sqlverresult .= "         inner join arrecad    on arrecad.k00_numpre    = arrematric.k00_numpre  ";
  $sqlverresult .= "   where numpremigra.k00_numpre = $convenio                                      ";
  $sqlverresult .= "     and arrecad.k00_tipo       = 5                                              ";
  $sqlverresult .= "     and k00_dtoper >= '2004-01-01'                                              ";
  $sqlverresult .= "group by arrecad.k00_numpre, arrecad.k00_numpar                                  ";
} else {

  $sqlverresult  = "  select arrecad.k00_numpre,                                                     ";
  $sqlverresult .= "         arrecad.k00_numpar,                                                     ";
  $sqlverresult .= "         sum(arrecad.k00_valor) as k00_valor                                     ";
  $sqlverresult .= "    from numpremigra                                                             ";
  $sqlverresult .= "         inner join arrematric on arrematric.k00_matric = numpremigra.k00_matric ";
  $sqlverresult .= "         inner join arrecad    on arrecad.k00_numpre    = arrematric.k00_numpre  ";
  $sqlverresult .= "                              and arrecad.k00_numpar    = numpremigra.k00_numpar ";
  $sqlverresult .= "   where numpremigra.k00_numpre = $convenio                                      ";
  $sqlverresult .= "     and arrecad.k00_tipo = 5                                                    ";
  $sqlverresult .= "     and k00_dtoper >= '2004-01-01'                                              ";
  $sqlverresult .= "group by arrecad.k00_numpre, arrecad.k00_numpar                                  ";
}

$verresult = db_query($sqlverresult);
if (pg_numrows($verresult) != false) {
  $numpre = pg_result($verresult, 0, 0);
}

if (pg_numrows($verresult) > 0) {

  for ($xresult = 0; $xresult < pg_numrows($verresult); $xresult ++) {
    $xtotal += pg_result($verresult, $xresult, 2);
  }
  $xxtotal = 0;

  for ($xresult = 0; $xresult < pg_numrows($verresult); $xresult ++) {

    $xpago      = pg_result($verresult, $xresult, 2);
    $numpre     = pg_result($verresult, $xresult, 0);
    $numpar     = pg_result($verresult, $xresult, 1);
    $vlrpagonew = round($vlrpago * ($xpago / $xtotal), 2);
    $xxtotal   += $vlrpagonew;

    if ($xresult == pg_numrows($verresult) - 1) {

      $diferenca   = $vlrpago - $xxtotal;
      $vlrpagonew += $diferenca;
      $xxtotal    += $diferenca;
    }

    $numpar                 = trim($numpar);

    /**
     * Habilita variavel de sessao para permitir numpre's de outras instituições
     */
    permiteNumpreOutraInstituicao( true );

    $clDisBanco->codret     = $codret;
    $clDisBanco->k15_codbco = $k15_codbco;
    $clDisBanco->k15_codage = $k15_codage;
    $clDisBanco->k00_numbco = $numbco;
    $clDisBanco->dtarq      = $dtarq;
    $clDisBanco->dtpago     = $dtpago;
    $clDisBanco->dtcredito  = $dtcredito;
    $clDisBanco->vlrpago    = "$vlrpagonew";
    $clDisBanco->vlrjuros   = "$vlrjuros";
    $clDisBanco->vlrmulta   = "$vlrmulta";
    $clDisBanco->vlracres   = "$vlracres";
    $clDisBanco->vlrdesco   = "$vlrdesco";
    $clDisBanco->vlrcalc    = "$vlrpago+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco";
    $clDisBanco->cedente    = $cedente;
    $clDisBanco->vlrtot     = "$vlrpagonew+$vlrjuros+$vlrmulta+$vlracres-$vlrdesco";
    $clDisBanco->classi     = "false";
    $clDisBanco->k00_numpre = "".($numpre+0)."";
    $clDisBanco->k00_numpar = "".($numpar+0)."";
    $clDisBanco->convenio   = $convenio;
    $clDisBanco->instit     = $iInstitSessao;
    $clDisBanco->incluir(null);

    if ($clDisBanco->erro_status == "0") {

      $oParametrosMsg           = new stdClass();
      $oParametrosMsg->sMsgErro = $clDisBanco->erro_msg;
      $sMsg                     = _M( MENSAGENS . 'erro_inclusao_disbanco', $oParametrosMsg);
      throw new DBException($sMsg);
    }

    $idRet = $clDisBanco->idret;

    /**
     * Desabilita variavel de sessao para permitir numpre's de outras instituições
     */
    permiteNumpreOutraInstituicao( false );

    echo "<script>js_termometro(".$i.");</script>";
    flush();
    echo "<br/>xtotal: $xtotal - xxtotal: $xxtotal - vlrpago: $vlrpago - vlrpagonew: $vlrpagonew<br/>";
  }

} else {

  $achou_arrecant = 1;
  $sqlverresult   = "  select arrecant.k00_numpre,                                                    ";
  $sqlverresult   = "         arrecant.k00_numpar,                                                    ";
  $sqlverresult   = "         sum(arrecant.k00_valor) as k00_valor                                    ";
  $sqlverresult   = "    from numpremigra                                                             ";
  $sqlverresult   = "         inner join arrematric on arrematric.k00_matric = numpremigra.k00_matric ";
  $sqlverresult   = "         inner join arrecant   on arrecant.k00_numpre   = arrematric.k00_numpre  ";
  $sqlverresult   = "                              and arrecant.k00_numpar   = numpremigra.k00_numpar ";
  $sqlverresult   = "   where numpremigra.k00_numpre = $convenio                                      ";
  $sqlverresult   = "     and arrecant.k00_tipo      = 5                                              ";
  $sqlverresult   = "     and k00_dtoper >= '2004-01-01'                                              ";
  $sqlverresult   = "group by arrecant.k00_numpre, arrecant.k00_numpar                                ";
  $verresult      = db_query($sqlverresult);

  if (pg_numrows($verresult) > 0) {
    echo "<br/>passou arrecant... xxtotal: $xxtotal - convenio: $convenio - numpre_migra: $numpre_migra - numpar: $numpar - matric: $matric<br/>";
  }
}
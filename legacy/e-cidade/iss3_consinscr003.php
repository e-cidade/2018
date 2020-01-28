<?
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

  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("classes/db_issbase_classe.php");
  require_once("classes/db_varfixval_classe.php");
  require_once("classes/db_issnotaavulsa_classe.php");
  require_once("classes/db_isstipoalvara_classe.php");
  require_once("classes/db_numpref_classe.php");
  require_once("libs/db_utils.php");
  require_once("dbforms/verticalTab.widget.php");
  require_once("classes/db_cgm_classe.php");

  $clnumpref       = new cl_numpref;
  $clissbase       = new cl_issbase;
  $clisstipoalvara = new cl_isstipoalvara;
  $clcgm           = new cl_cgm;

  $clcgm->rotulo->label("z01_nome");

  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

  $iCodigoIncricao = $numeroDaInscricao;
  $sDisplayNone    = "";

  /**
   * Retorna se o Alvara pode ser Impresso
   */
  $sSqlTipoAlvara  = " select q98_permiteimpressao                                                                  ";
  $sSqlTipoAlvara .= "  from isstipoalvara                                                                          ";
  $sSqlTipoAlvara .= "       inner join issalvara    on issalvara.q123_isstipoalvara = isstipoalvara.q98_sequencial ";
  $sSqlTipoAlvara .= "       inner join issmovalvara on issmovalvara.q120_issalvara  = issalvara.q123_sequencial    ";
  $sSqlTipoAlvara .= " where q123_inscr = {$iCodigoIncricao} ";
  $rsTipoAlvara    = $clisstipoalvara->sql_record($sSqlTipoAlvara);
  $iLinhasMovimentacaoAlvara = $clisstipoalvara->numrows;
  if ($iLinhasMovimentacaoAlvara > 0) {
    $oTipoAlvara     = db_utils::fieldsMemory($rsTipoAlvara,0);

    if ($oTipoAlvara->q98_permiteimpressao == "f") {
      $sDisplayNone = "display:none;";
    }
  } else {
    $sDisplayNone = "display:none;";
  }

  /**
   * Sql que retorna os dados básicos da inscrição
   */
  $sSqlDadosInscricao  = " select issbase.*,                                                                                       \n";
  $sSqlDadosInscricao .= "        case when (cgm_inscr.z01_nomecomple is null or trim(cgm_inscr.z01_nomecomple) = '')              \n";
  $sSqlDadosInscricao .= "          then cgm_inscr.z01_nome                                                                        \n";
  $sSqlDadosInscricao .= "          else cgm_inscr.z01_nomecomple                                                                  \n";
  $sSqlDadosInscricao .= "        end as z01_nome,                                                                                 \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_ender,                                                                             \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_cgccpf,                                                                            \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_nomefanta,                                                                         \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_telef,                                                                             \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_incest,                                                                            \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_email,                                                                             \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_numero,                                                                            \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_compl,                                                                             \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_bairro,                                                                            \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_munic,                                                                             \n";
  $sSqlDadosInscricao .= "        cgm_inscr.z01_uf,                                                                                \n";
  $sSqlDadosInscricao .= "        cgm_escr.z01_nome as escritorio,                                                                 \n";
  $sSqlDadosInscricao .= "        j14_nome,                                                                                        \n";
  $sSqlDadosInscricao .= "        j13_descr,                                                                                       \n";
  $sSqlDadosInscricao .= "        q02_numero,                                                                                      \n";
  $sSqlDadosInscricao .= "        q02_compl,                                                                                       \n";
  $sSqlDadosInscricao .= "        q05_matric,                                                                                      \n";
  $sSqlDadosInscricao .= "        q98_descricao,                                                                                   \n";
  $sSqlDadosInscricao .= "        q14_proces,                                                                                      \n";
  $sSqlDadosInscricao .= "        p58_dtproc,                                                                                      \n";
  $sSqlDadosInscricao .= "        extract (year from p58_dtproc)::integer as p58_ano,                                              \n";
  $sSqlDadosInscricao .= "        q45_codporte,                                                                                    \n";
  $sSqlDadosInscricao .= "        q40_descr,                                                                                       \n";
  $sSqlDadosInscricao .= "        j88_sigla,                                                                                       \n";
  $sSqlDadosInscricao .= "        case                                                                                             \n";
  $sSqlDadosInscricao .= "          when meicgm.q115_numcgm is not null                                                            \n";
  $sSqlDadosInscricao .= "            then 'MEI'                                                                                   \n";
  $sSqlDadosInscricao .= "            else ''                                                                                      \n";
  $sSqlDadosInscricao .= "        end as tipo                                                                                      \n";
  $sSqlDadosInscricao .= "   from issbase                                                                                          \n";
  $sSqlDadosInscricao .= "        inner      join cgm cgm_inscr      on cgm_inscr.z01_numcgm         = q02_numcgm                  \n";
  $sSqlDadosInscricao .= "        left outer join issruas            on issbase.q02_inscr            = issruas.q02_inscr           \n";
  $sSqlDadosInscricao .= "        left outer join ruas               on ruas.j14_codigo              = issruas.j14_codigo          \n";
  $sSqlDadosInscricao .= "        left outer join ruastipo           on ruas.j14_tipo                = ruastipo.j88_codigo         \n";
  $sSqlDadosInscricao .= "        left outer join issbairro          on issbase.q02_inscr            = q13_inscr                   \n";
  $sSqlDadosInscricao .= "        left outer join bairro             on j13_codi                     = q13_bairro                  \n";
  $sSqlDadosInscricao .= "        left outer join escrito            on issbase.q02_inscr            = q10_inscr                   \n";
  $sSqlDadosInscricao .= "        left outer join cgm cgm_escr       on cgm_escr.z01_numcgm          = q10_numcgm                  \n";
  $sSqlDadosInscricao .= "        left outer join issmatric          on issbase.q02_inscr            = q05_inscr                   \n";
  $sSqlDadosInscricao .= "        left outer join issprocesso        on issbase.q02_inscr            = q14_inscr                   \n";
  $sSqlDadosInscricao .= "              left join protprocesso       on issprocesso.q14_proces       = protprocesso.p58_codproc    \n";
  $sSqlDadosInscricao .= "              left join meicgm             on meicgm.q115_numcgm           = issbase.q02_numcgm          \n";
  $sSqlDadosInscricao .= "              left join issalvara          on issalvara.q123_inscr         = issbase.q02_inscr           \n";
  $sSqlDadosInscricao .= "              left join isstipoalvara      on isstipoalvara.q98_sequencial = issalvara.q123_isstipoalvara\n";
  $sSqlDadosInscricao .= "              left join issqn.issbaseporte on q45_inscr                    = issbase.q02_inscr           \n";
  $sSqlDadosInscricao .= "              left join issqn.issporte     on q40_codporte                 = q45_codporte                \n";
  if ( isset($numeroDaInscricao) ) {
    $sSqlDadosInscricao .= " where issbase.q02_inscr = $numeroDaInscricao ";
  } elseif ( isset($referenciaanterior) ) {
    $sSqlDadosInscricao .= " where issbase.q02_inscmu ilike '$referenciaanterior' ";
  }

  $rsSqlDadosInscricao = db_query($sSqlDadosInscricao);
  $iNumRowsIncricao    = pg_numrows($rsSqlDadosInscricao);

  if ( isset($referenciaanterior) ) {

    $numeroDaInscricao = null;

    if ( $iNumRowsIncricao == 1 ) {
      $numeroDaInscricao = pg_result($rsSqlDadosInscricao,0,"q02_inscr");
    }
    $iCodigoIncricao = $numeroDaInscricao;
  }

  $sSqlNumpref     = $clnumpref->sql_query_file(db_getsession("DB_anousu"),
                                                db_getsession("DB_instit"),
                                                "k03_certissvar,
                                                 k03_regracnd,
                                                 k03_tipocertidao"
                                               );
  $rsSqlNumpref    = $clnumpref->sql_record($sSqlNumpref);
  $iNumRowsNumpref = $clnumpref->numrows;

  if ($iNumRowsNumpref !== 0) {
    $oParmNumpref = db_utils::fieldsMemory($rsSqlNumpref,0);
  }

  /**
   * Define o tipo de certidao
   *   regular
   *   positiva
   *   negativa
   */
  $sWhereIssvar = ($oParmNumpref->k03_certissvar == 't' ? " k00_valor <> 0 " : "");
  $dtBase       = date('Y-m-d', db_getsession('DB_datausu'));
  $sSqlCertid   = "select *                                              ";
  $sSqlCertid  .= "  from fc_tipocertidao($iCodigoIncricao,              ";
  $sSqlCertid  .= "                       'i',                           ";
  $sSqlCertid  .= "                       '{$dtBase}',                   ";
  $sSqlCertid  .= "                       '{$sWhereIssvar}',             ";
  $sSqlCertid  .= "                        {$oParmNumpref->k03_regracnd} ";
  $sSqlCertid  .= "                      )                               ";
  $rsSqlCertid  = $clnumpref->sql_record($sSqlCertid);

  if ($rsSqlCertid !== false) {
  	$sCertidao = db_utils::fieldsMemory($rsSqlCertid,0)->fc_tipocertidao;
  }

  /**
   * Retorna parametro da tabela parissqn
   */
  $sSqlParIssqn  = "select q60_bloqemiscertbaixa from parissqn ";
  $rsSqlParIssqn = $clnumpref->sql_record($sSqlParIssqn);
  if ($rsSqlParIssqn !== false) {
  	$iBloqueioEmissaoCertidaoBaixa  = db_utils::fieldsMemory($rsSqlParIssqn,0)->q60_bloqemiscertbaixa;
  }

  /**
   * Valida se é regular, negativa ou positiva
   */
  $iAvisoCertidao = 1;
  if ($sCertidao != "negativa" && $iBloqueioEmissaoCertidaoBaixa == 2) {
    $iAvisoCertidao = 2;
  } else if ($sCertidao != "negativa" && $iBloqueioEmissaoCertidaoBaixa == 3) {
    $iAvisoCertidao = 3;
  }

  /**
   * Retorna percentual dos socios
   */
  $sSqlSocios     = $clissbase->sqlinscricoes_socios($iCodigoIncricao,0,"q95_perc");
  $rsSqlSocios    = $clissbase->sql_record($sSqlSocios);
  $iNumRowsSocios = $clissbase->numrows;
  $nPercentual    = 0;
  if($iNumRowsSocios > 0){
    $aPercentual = db_utils::getCollectionByRecord($rsSqlSocios);
    foreach ($aPercentual as $oSocios){
      $nPercentual += $oSocios->q95_perc;
    }
  }
?>
<html>
<head>
<title>Dados da Inscri&ccedil;&atilde;o - BCI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script type="text/javascript">
var iBloqueioCertidao = <?=$iAvisoCertidao; ?>

function js_Impressao() {
  window.open('iss3_consinscr003_imprimealvara.php?inscricao=<?=$iCodigoIncricao?>','','location=0,HEIGHT=600,WIDTH=600');
}
function js_Imprime_iss() {
  window.open('iss2_imprimebiciss.php?inscr=<?=$iCodigoIncricao?>','','location=0,HEIGHT=600,WIDTH=600');
}
function js_Imprimebaix() {
	
  if (iBloqueioCertidao == 2){
    alert(_M("tributario.issqn.iss3_consinscr003.certidaoavisodebito"));
  } else if (iBloqueioCertidao == 3) {
	  alert(_M("tributario.issqn.iss3_consinscr003.bloqueiocertidao"));
	  return false;
  }
  window.open('iss2_certibaixa002.php?inscr=<?=$iCodigoIncricao?>','','location=0,HEIGHT=600,WIDTH=600');
}

function js_Imprimetermo() {
    window.open('iss2_imprimetermo001.php?inscr=<?=$iCodigoIncricao?>','','location=0,HEIGHT=600,WIDTH=600');
}
</script>
</head>
<body>

<?
    /**
     * valida se a inscrição passada é valida
     */
    if ($iCodigoIncricao > 0) {
      db_fieldsmemory($rsSqlDadosInscricao,0,1);
      $oDadosInscricao = db_utils::fieldsMemory($rsSqlDadosInscricao,0,true);
?>

<table width="800" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr bgcolor="#CCCCCC">
    <td colspan="4" align="center">
      <font color="#333333">
        <strong>
          &nbsp;DADOS DA INSCRI&Ccedil;&Atilde;O&nbsp;
        </strong>
      </font>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Inscri&ccedil;&atilde;o municipal:&nbsp;</td>
    <td width="356" align="left" nowrap bgcolor="#FFFFFF">
    <font color="#666666">
        <strong>&nbsp;<?=$iCodigoIncricao . " - CGM: " . $oDadosInscricao->q02_numcgm?>&nbsp;</strong>
      </font>
    </td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;CNPJ/CPF: </td>
    <td width="165" nowrap bgcolor="#FFFFFF">
      <font color="#666666"><strong>&nbsp;<?=$oDadosInscricao->z01_cgccpf?></strong></font>
    </td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;<?=db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm','$oDadosInscricao->q02_numcgm')",2) . ":"?>&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->z01_nome?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Inscrição Estadual:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->z01_incest?>
      &nbsp; </strong></font></td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Endereço:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->z01_ender . ", " . $oDadosInscricao->z01_numero . " - " . $oDadosInscricao->z01_compl ?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Telefone:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->z01_telef?>
      &nbsp; </strong></font></td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Bairro/Municipio/UF:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?="$z01_bairro/$z01_munic/$z01_uf"?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Referência anterior:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$oDadosInscricao->q02_inscmu?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Email:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=@$z01_email?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Data inicial:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$oDadosInscricao->q02_dtinic?>
      &nbsp; </strong></font></td>
  </tr>
  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Nome fantasia:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$oDadosInscricao->z01_nomefanta?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Data do cadastro:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$oDadosInscricao->q02_dtcada?>
      &nbsp; </strong></font></td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Registro na junta:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->q02_regjuc?>
      &nbsp; </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Data da junta:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->q02_dtjunta?>
      &nbsp; </strong></font></td>
  </tr>

  <tr>

    <td align="right" nowrap bgcolor="#CCCCCC">&nbsp;Capital social:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF">&nbsp;
      <font color="#666666">
       <strong><?=(trim($oDadosInscricao->q02_capit) == 0 ? $nPercentual : $oDadosInscricao->q02_capit);?> &nbsp; </strong>
      </font>
    </td>
    <td align="right" nowrap bgcolor="#CCCCCC">Data da baixa: </td>
    <td nowrap bgcolor="#FFFFFF">&nbsp;<font color="#666666"><strong>
      <?=$oDadosInscricao->q02_dtbaix?>
      &nbsp; </strong></font></td>
  </tr>

  <tr>
    <td height="15px;" colspan="4">&nbsp;</td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Matr&iacute;cula:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"> <strong><font color="#666666"> &nbsp; <strong>
      <?=$oDadosInscricao->q05_matric?>
      </strong></font></strong></td>
    <td align="right" nowrap bgcolor="#CCCCCC">n&deg; : </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->q02_numero?>
      &nbsp; </strong></font></td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Rua:&nbsp; </td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->j88_sigla . " - " . $oDadosInscricao->j14_nome?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Complemento :&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->q02_compl?>
      &nbsp; </strong></font></td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Bairro:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"><font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->j13_descr?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Processo:</td>
    <td nowrap bgcolor="#FFFFFF">
      <font color="#666666">&nbsp;
        <strong><?="$oDadosInscricao->q14_proces/$oDadosInscricao->p58_ano - $oDadosInscricao->p58_dtproc"?></strong>
      </font>
    </td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Escrit&oacute;rio:&nbsp;</td>
    <td align="left" nowrap bgcolor="#FFFFFF"> <font color="#666666"><strong>&nbsp;
      <?=$oDadosInscricao->escritorio?>
      </strong></font></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Porte:</td>
    <td nowrap bgcolor="#FFFFFF"> <font color="#666666">&nbsp;<strong> <font color="#666666"><strong>
      <?="$oDadosInscricao->q45_codporte - $oDadosInscricao->q40_descr"?>
      </strong></font></strong></font></td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Ultima alteração:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF"> <strong><font color="#666666"> &nbsp; <strong>
      <?=$oDadosInscricao->q02_dtalt?>
      </strong></font></strong></td>
    <td align="right" nowrap bgcolor="#CCCCCC">Tipo:</td>
    <td nowrap bgcolor="#FFFFFF"> <font color="#666666">&nbsp;<strong> <font color="#666666"><strong>
      <?=$oDadosInscricao->tipo?>
      </strong></font></strong></font></td>
  </tr>

  <tr>
    <td align="right" nowrap bgcolor="#CCCCCC">Tipo de Alvará:&nbsp;</td>
    <td nowrap bgcolor="#FFFFFF">
      <strong>
        <font color="#666666"> &nbsp; <?=!empty($iLinhasMovimentacaoAlvara)?$oDadosInscricao->q98_descricao:""?></font>
      </strong>
    </td>
  </tr>

  <tr>
    <td height="15px;" colspan="4"> </td>
  </tr>

  <tr>
    <td colspan="4" align="left"><table width="100%" height="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr valign="bottom">
          <td width="12%">

            <table width="80%" border="0" cellspacing="2" cellpadding="0">
	  <?if ($q02_dtbaix!=""){?>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" >
                  <a href="iss3_consinscr003_detalhes.php?solicitacao=Baixa&inscricao=<?=$iCodigoIncricao?>" target="iframeDetalhes">&nbsp;Dados da Baixa&nbsp;</a></td>
              </tr>
	  <?}?>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" >
                  <a href="iss3_consinscr003_detalhes.php?solicitacao=Atividades&inscricao=<?=$iCodigoIncricao?>" target="iframeDetalhes">&nbsp;Atividades&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=Socios" target="iframeDetalhes">&nbsp;S&oacute;cios&nbsp;</a></td>
              </tr>
	  <?if (1==2){?>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=Calculo" target="iframeDetalhes">&nbsp;C&aacute;lculo&nbsp;</a></td>
              </tr>
	  <?}?>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=TiposDeCalculo" target="iframeDetalhes">&nbsp;Tipos
                  de c&aacute;lculo&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=Quantidades" target="iframeDetalhes">&nbsp;Quantidades&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=Fixado" target="iframeDetalhes">&nbsp;Fixado&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=Observacoes" target="iframeDetalhes">&nbsp;Observa&ccedil;&otilde;es&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=TextoAlvara" target="iframeDetalhes">&nbsp;Texto
                  alvar&aacute;&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=Ocorrencias" target="iframeDetalhes">&nbsp;Ocorrências&nbsp;</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand">
                  <a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=Manual" target="iframeDetalhes">&nbsp;Demonstrativo do calculo</a></td>
              </tr>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="forms/db_frmaidofant.php?inscr=<?=$iCodigoIncricao?>" target="iframeDetalhes">&nbsp;AIDOF&nbsp;</a></td>
              </tr>

              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="iss3_consinscr003_detalhes.php?inscricao=<?=$iCodigoIncricao?>&solicitacao=OptanteSimples" target="iframeDetalhes">&nbsp;Optante Simples&nbsp;</a></td>
              </tr>

              <?
              $clissnotaavulsa = new cl_issnotaavulsa();
              $clissnotaavulsa->sql_record($clissnotaavulsa->sql_query(null,"*","q51_numnota","q51_inscr = $iCodigoIncricao"));
              if ($clissnotaavulsa->numrows > 0){
              ?>
              <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href="iss3_issnotaavulsa002.php?inscr=<?=$iCodigoIncricao?>" target="iframeDetalhes">&nbsp;Notas Avulsas&nbsp;</a></td>
              </tr>
              <?
               }
              ?>
	      <tr>
	        <td title="Imprime BIC" align="center" title="Imprime BIC" nowrap bgcolor="#CCCCCC" style="cursor:hand"><a href='' onClick="js_Imprime_iss();return false;" >Imprime BIC</a></td>
	      </tr>
        <tr>
          <td title="Imprime BIC" align="center" title="Imprime BIC" nowrap bgcolor="#CCCCCC" style="cursor:hand">
            <a href="iss3_consinscr003_movimentacao.php?inscricao=<?=$iCodigoIncricao?>" target="iframeDetalhes">Movimentações</a></td>
        </tr>
      	<?
      	  if ($q02_dtbaix!=""){
      	?>
        <tr>
           <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><input name='imprime'  type='button'  onClick="js_Imprimebaix()" value='Imprime  Certid&atilde;o de Baixa'></td>
        </tr>
    	  <?php
    	    } else {
            /*
             * Verifica se o suario possui permissão para imprimir o alvará
             */
            if ( db_permissaomenu(db_getsession("DB_anousu"), 40, 9740) == "true") {
        ?>
              <tr style=<?=$sDisplayNone?>>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><input name='imprime' type='button' onClick="js_Impressao()" value='Imprime Alvará'></td>
              </tr>
       <?php
            }
          }
       ?>

<?
$sql="select * from varfix inner join varfixval on q33_codigo=q34_codigo and q33_inscr=$iCodigoIncricao";
$varfix=db_query($sql);
$numrows=pg_numrows($varfix);
if ($numrows>0)
{
  ?>
	      <tr>
              <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand"><input name='imprime' type='button' onClick="js_Imprimetermo()" value='Imprime Termo'></td>
	      </tr>
<?}?>
	      <!-- <tr>
                <td align="center" nowrap bgcolor="#CCCCCC" style="cursor:hand" onClick="js_impressao()"><strong>&nbsp;Imprimir</strong></td>
              </tr>-->
            </table></td>
          <td width="88%" align="left"> <iframe align="middle" height="100%" frameborder="0" marginheight="0" marginwidth="0" name="iframeDetalhes" width="100%">
            </iframe> </td>
        </tr>
      </table></td>
  </tr>
</table>

<?
  } else {  // caso nao tenha retornado nenhum registro é mostrado uma tabela informando que a matricula nao foi localizada
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><strong>Pesquisa da inscricao n&deg;
      &nbsp;<?=$iCodigoIncricao?>&nbsp;
      n&atilde;o retornou nenhum registro.</strong></td>
  </tr>
</table>
<?
  } // fim da verificacao
?>
</body>
</html>
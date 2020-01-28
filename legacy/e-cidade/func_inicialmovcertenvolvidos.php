<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);

$oDaoInicialNumpre  = new cl_inicialnumpre;
$oDaoParjuridico    = new cl_parjuridico;

$sSqlParjuridico   = $oDaoParjuridico->sql_query_file(db_getsession('DB_anousu'), db_getsession('DB_instit'));
$rsParjuridico     = $oDaoParjuridico->sql_record($sSqlParjuridico);

if ($oDaoParjuridico->numrows == 0) {

  db_redireciona('db_erros.php?fechar=true&db_erro=Erro na configuração de parâmetros do jurídico.');
  die();
}

$iEnvolvimentoInicialIptu = db_utils::fieldsMemory($rsParjuridico, 0, null)->v19_envolinicialiptu;
$iEnvolvimentoInicialIss  = db_utils::fieldsMemory($rsParjuridico, 0, null)->v19_envolinicialiss;

/**
 * Arreinscr e arrematric para matricula e inscricao
 * Arrenumcgm para cgm
 */

$sSqlInicialNumpre  = "select distinct v59_numpre, k00_numcgm                   ";
$sSqlInicialNumpre .= "  from inicialnumpre                                     ";
$sSqlInicialNumpre .= "       inner join arrenumcgm on v59_numpre = k00_numpre  ";
$sSqlInicialNumpre .= " where v59_inicial = {$inicial}                          ";
$rsInicialNumpre    = $oDaoInicialNumpre->sql_record($sSqlInicialNumpre);

if(!empty($oDaoInicialNumpre->erro_banco)){

  db_redireciona("db_erros.php?fechar=true&db_erro=Erro ao consultar inicial.");
  die();
}

$aDebitosNumpres  = db_utils::getCollectionByRecord( $rsInicialNumpre );
$aSubQuery = array();

foreach ($aDebitosNumpres as $aDebitoNumpre) {

  $iMatricula = null;
  $iInscricao = null;
  $oDaoArrematric = new cl_arrematric;
  $sSqlArrematric = $oDaoArrematric->sql_query_file( $aDebitoNumpre->v59_numpre );
  $rsArrematric   = $oDaoArrematric->sql_record( $sSqlArrematric );
  if( $rsArrematric ){

    $iMatricula  = db_utils::fieldsMemory( $rsArrematric, 0 )->k00_matric;
    $aSubQuery[] = "select riNumcgm, riMatric, riInscr, rvNome, riTipoEnvol
                        from fc_busca_envolvidos ( false, {$iEnvolvimentoInicialIptu}, 'M', {$iMatricula})";
  }

  $oDaoArreinscr = new cl_arreinscr;
  $sSqlArreinscr = $oDaoArreinscr->sql_query_file( $aDebitoNumpre->v59_numpre );
  $rsArreinscr   = $oDaoArreinscr->sql_record( $sSqlArreinscr );
  if( $rsArreinscr ){

    $iInscricao  = db_utils::fieldsMemory( $rsArreinscr, 0 )->k00_inscr;
    $aSubQuery[] = "select riNumcgm, riMatric, riInscr, rvNome, riTipoEnvol
                        from fc_busca_envolvidos ( false, {$iEnvolvimentoInicialIss}, 'I', {$iInscricao})";

  }

  if( $iMatricula == null && $iInscricao == null){

    $aSubQuery[] = "select riNumcgm, riMatric, riInscr, rvNome, riTipoEnvol
                      from fc_busca_envolvidos ( true, 1, 'C', {$aDebitoNumpre->k00_numcgm})";
  }

}

$aSubQuery = array_unique($aSubQuery);
$sSubquery = implode("  \n union \n ", $aSubQuery);

$sSql = " select 1 where 2 = 1 ";

if(!empty($sSubquery)){

  $sSql = "select distinct riNumcgm as j01_numcgm,
                           riMatric as j01_matric,
                           riInscr  as q02_inscr,
                           rvNome   as z01_nome
             from ( $sSubquery ) as subquery ";
}

$funcao_js = '';
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php db_app::load("estilos.css"); ?>
</head>
<body>
  <?php
    db_lovrot($sSql, 15, "()", "", $funcao_js);
  ?>
</body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
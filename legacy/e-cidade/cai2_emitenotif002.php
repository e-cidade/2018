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

ini_set("memory_limit", '-1');
ini_set("display_errors", '0');

require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_libtributario.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/scpdf.php"));

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

define('DB_BIBLIOT',true);

$notifica = '';

global $sCepCxPostal,$sMunicipio, $lValidaOrdemEnderecoEntrega;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($_POST);
db_postmemory($_GET);

$lim1              = 0;
$z01_bairro        = null;
$jtipos            = '';
$nomearquivos      = "";
$lProcessaLoteador = false;
$k50_dtemite       = "//";

$oDaoNotificacao   =  db_utils::getDao("notificacao");
$sSqlNotificacao   = $oDaoNotificacao->sql_query_file($notifica, "k50_dtemite", null, "");
$rsSqlNotificacao  = $oDaoNotificacao->sql_record($sSqlNotificacao);
if ($oDaoNotificacao->numrows > 0) {

  $oNotificacao = db_utils::fieldsMemory($rsSqlNotificacao, 0);
  $k50_dtemite  = db_formatar($oNotificacao->k50_dtemite, 'd');
}

/**
 * Movida validação que ficava dentro da função EnderecoNotif
 */
$lValidaOrdemEnderecoEntrega = false;

$sSqlCfiptu  = " select j18_ordendent ";
$sSqlCfiptu .= "   from cfiptu        ";
$sSqlCfiptu .= "  where j18_anousu  = " . db_getsession("DB_anousu");
$rsCfiptu    = db_query($sSqlCfiptu);
if (pg_numrows($rsCfiptu) == 0) {
  $lValidaOrdemEnderecoEntrega = true;
}

/**
 * Movida lógica que ficava dentro da rotina cabecNotif
 */
$sSqlDadosInstituicao  = "select nomeinst, bairro, cgc, ender, numero as ender_numero ";
$sSqlDadosInstituicao .= "       upper(munic) as munic, uf,                           ";
$sSqlDadosInstituicao .= "       email, url, logo, telef,                             ";
$sSqlDadosInstituicao .= "       to_char(tx_banc,'9.99') as tx_banc, numbanco,        ";
$sSqlDadosInstituicao .= "       cep as cep_munic                                     ";
$sSqlDadosInstituicao .= "  from db_config                                            ";
$sSqlDadosInstituicao .= " where codigo = " . db_getsession("DB_instit");

global $nomeinst;
global $ender;
global $ender_numero;
global $numero;
global $munic;
global $cgc;
global $cep_munic;
global $bairro;
global $uf;
global $db12_extenso;
global $logo;
global $tx_banc;
global $numbanco;

$rsDadosInstituicao    = db_query($sSqlDadosInstituicao);
db_fieldsmemory($rsDadosInstituicao,0);

$iCadTipoMod   = 12;
if (isset($tipoparc) && $tipoparc){
  $iCadTipoMod = 13;
}

$iAnousu                   = db_getsession('DB_anousu');
$mostra_tabela_descontos = true;

$oDaoParNotificacao  = new cl_parnotificacao();
$sSqlParNotificacao  = $oDaoParNotificacao->sql_query_file( $iAnousu, "k102_tipoemissao" );
$rsConsultaParametro = $oDaoParNotificacao->sql_record( $sSqlParNotificacao );

if ( $oDaoParNotificacao->numrows == 0 ) {

  $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.verifique_parametros');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit;
}

$oParam = db_utils::fieldsMemory($rsConsultaParametro, 0);

function recibodesconto($numpre, $numpar, $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros,$dt_venc,$sTipoLista) {

  global $k00_dtvenc, $k40_codigo, $k40_todasmarc, $cadtipoparc;

  $cadtipoparc = 0;

  $sqlvenc = " select k00_dtvenc from arrecad where k00_numpre = $numpre and k00_numpar = $numpar";
  $resultvenc = db_query($sqlvenc) or die($sqlvenc);

  if (pg_numrows($resultvenc) == 0) {
    return 0;
  }

  db_fieldsmemory($resultvenc, 0);

  $sSqlTipoParc  = " select k40_codigo, k40_todasmarc, k40_forma, cadtipoparc          ";
  $sSqlTipoParc .= "   from tipoparc                                                   ";
  $sSqlTipoParc .= "        inner join cadtipoparc    on cadtipoparc     = k40_codigo  ";
  $sSqlTipoParc .= "        inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc ";
  $sSqlTipoParc .= "  where maxparc = 1                                                ";
  $sSqlTipoParc .= "    and '$dt_venc' >= k40_dtini                                    ";
  $sSqlTipoParc .= "    and '$dt_venc' <= k40_dtfim                                    ";
  $sSqlTipoParc .= "    and  k41_arretipo = $tipo $whereloteador                       ";
  $sSqlTipoParc .= "    and '$k00_dtvenc' >= k41_vencini                               ";
  $sSqlTipoParc .= "    and '$k00_dtvenc' <= k41_vencfim                               ";

  $rsTipoParc      = db_query($sSqlTipoParc) or die($sSqlTipoParc);
  $iLinhasTipoParc = pg_numrows($rsTipoParc);

  if ( $iLinhasTipoParc > 0)  {

    if (  $iLinhasTipoParc > 1  ) {

      if ( $sTipoLista == 'C' || $sTipoLista == 'N' ) {

        $sSqlLoteador  = " select *                                                                       ";
        $sSqlLoteador .= "   from arrenumcgm                                                              ";
        $sSqlLoteador .= "        inner join loteam     on loteam.j34_loteam     = arrenumcgm.k00_numcgm  ";
        $sSqlLoteador .= "        inner join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam      ";
        $sSqlLoteador .= "  where k00_numpre = {$numpre}                                                  ";
        $sSqlLoteador .= "    and j120_cgm   = {$z01_numcgm}                                              ";
        $rsLoteador    = db_query($sSqlLoteador);

        if ( pg_num_rows($rsLoteador) > 0 ) {
          $sSqlTipoParc .= " and k40_forma = 3 ";
        } else {
          $sSqlTipoParc .= " and k40_forma = 1 ";
        }

      } else {
        $sSqlTipoParc .= " and k40_forma = 1 ";
      }

      $rsTipoParc = db_query($sSqlTipoParc);
      db_fieldsmemory($rsTipoParc,0);

    } else {
      db_fieldsmemory($rsTipoParc,0);
    }

  } else {

    $sqltipoparc = "select k40_codigo, k40_todasmarc, cadtipoparc
                      from tipoparc
                           inner join cadtipoparc    on cadtipoparc     = k40_codigo
                           inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
                     where maxparc = 1
                       and k41_arretipo = $tipo
                       and '$dt_venc' >= k40_dtini
                       and '$dt_venc' <= k40_dtfim $whereloteador
                       and '$k00_dtvenc' >= k41_vencini
                       and '$k00_dtvenc' <= k41_vencfim ";

    $rsTipoParc = db_query($sqltipoparc) or die($sqltipoparc);

    if (pg_numrows($rsTipoParc) == 1) {
      db_fieldsmemory($rsTipoParc,0);
    } else {
      $k40_todasmarc = false;
    }
  }

  $sqltipoparcdeb    = "select * from cadtipoparcdeb limit 1";
  $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);

  $passar = false;

  if (pg_numrows($resulttipoparcdeb) == 0) {
    $passar = true;
  } else {

    $sqltipoparcdeb = "select k40_codigo,
                              k40_todasmarc
                         from cadtipoparcdeb
                              inner join cadtipoparc on k40_codigo = k41_cadtipoparc
                        where k41_cadtipoparc = $cadtipoparc
                          and k41_arretipo    = $tipo_debito $whereloteador
                          and '$k00_dtvenc' >= k41_vencini
                          and '$k00_dtvenc' <= k41_vencfim ";

    $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
    if (pg_numrows($resulttipoparcdeb) > 0) {
      $passar = true;
    }
  }

  $desconto  = $k40_codigo;
  if (pg_numrows($rsTipoParc) == 0 || ($k40_todasmarc == 't'?$totalregistrospassados <> $totregistros:false) || $passar == false) {
    $desconto = 0;
  }

  return $desconto;
}

$sSqlInstituicao  = "select *, numero as ender_numero       ";
$sSqlInstituicao .= "  from db_config                       ";
$sSqlInstituicao .= "       inner join db_uf on db12_uf = uf ";
$sSqlInstituicao .= " where codigo = ".db_getsession("DB_instit");

db_fieldsmemory(db_query($sSqlInstituicao),0,true);

$sqlparag  = "   select db02_texto                                            ";
$sqlparag .= "    from db_documento                                           ";
$sqlparag .= "         inner join db_docparag on db03_docum    = db04_docum   ";
$sqlparag .= "         inner join db_tipodoc on db08_codigo    = db03_tipodoc ";
$sqlparag .= "         inner join db_paragrafo on db04_idparag = db02_idparag ";
$sqlparag .= "   where db03_tipodoc = 1017                                    ";
$sqlparag .= "     and db03_instit  = ".db_getsession("DB_instit");
$sqlparag .= "order by db04_ordem                                             ";
$resparag  = db_query($sqlparag);

$head1 = 'SECRETARIA DE FINANÇAS';
if ( pg_numrows($resparag) != 0 ) {

  db_fieldsmemory( $resparag, 0 );
  $head1 = $db02_texto;
}

$db_datausu   = $datavenc;
if ($datavenc == "--") {

  $sqlvenc    = "select current_date + '30 days'::interval as db_datausu";
  $resultvenc = db_query($sqlvenc) or die($sqlvenc);
  db_fieldsmemory($resultvenc, 0);
}

$DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
$somenteparc = true;
$somenteiptu = true;

/**
 * Verifica se esta emitindo notificação Parcial ou Geral
 */
if (!isset($notifparc)) {

  if ( $lista == '' ) {

    $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.lista_nao_encontrada');
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
    exit;
  }

  $sSql   = "select * from lista where k60_codigo = " . $lista;
  $result = db_query($sSql);
  db_fieldsmemory($result,0);

  $sqllistatipo  = "select listatipos.*,                                              ";
  $sqllistatipo .= "       arretipo.k03_tipo,                                         ";
  $sqllistatipo .= "       k03_descr                                                  ";
  $sqllistatipo .= "  from listatipos                                                 ";
  $sqllistatipo .= "       inner join arretipo on k00_tipo          = k62_tipodeb     ";
  $sqllistatipo .= "       inner join cadtipo  on arretipo.k03_tipo = cadtipo.k03_tipo";
  $sqllistatipo .= " where k62_lista = " . $lista;

  $resultlistatipo = db_query($sqllistatipo);

  $virgula   = '';
  $tipos     = '';
  $descrtipo = '';

  global $z01_ender, $z01_numero, $z01_compl,  $z01_munic, $z01_uf, $z01_cep, $z01_cxpostal, $z01_destinatario;

  for ($iContador = 0; $iContador < pg_numrows($resultlistatipo); $iContador++ ) {

    db_fieldsmemory($resultlistatipo, $iContador);
    $tipos     .= $virgula.$k62_tipodeb;
    $descrtipo .= $virgula.trim($k03_descr);
    $virgula    = ' , ';
  }

  $sqllistadoc    = "select * from listadoc where k64_codigo = {$lista}";
  $resultlistadoc = db_query($sqllistadoc);

  if ($resultlistadoc == false) {

    $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.erro_procurar_documento');
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
    exit;
  }

  if (pg_numrows($resultlistadoc) == 0) {

    $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.documento_nao_encontrado');
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
    exit;
  }

  /**
   * Define tipo de lista e campos
   *  N - nome
   *  I - inscrição
   *  M - matrícula
   */
  if ($k60_tipo == 'M'){

    $xtipo     = 'Matrícula';
    $xcodigo   = 'k22_matric';
    $xcodigo1  = 'j01_matric';
    $xxcodigo1 = 'k55_matric';
    $xcampos   = ' substr(fc_proprietario_nome,1,7) as z01_numcgm, substr(fc_proprietario_nome,8,40) as z01_nome ';
    $xxmatric  = ' inner join notimatric on k22_matric = k55_matric ';
    $xxcodigo  = 'k55_notifica';
    $xxmatric2 = '';
    $contr     = '';

    if (isset($campo)){

      if ($tipo == 2){
        $contr = 'and k63_notifica in ('.str_replace("-",", ",$campo).') ';
      }elseif ($tipo == 3){
        $contr = 'and k63_notifica not in ('.str_replace("-",", ",$campo).') ';
      }
    }

  }elseif($k60_tipo == 'I'){

    $xtipo     = 'Inscrição';
    $xcodigo   = 'k22_inscr';
    $xcodigo1  = 'q02_inscr';
    $xxcodigo1 = 'k56_inscr';
    $xxmatric  = ' inner join notiinscr on k22_inscr = k56_inscr ';
    $xxmatric2 = ' inner join issbase on q02_inscr = k22_inscr inner join cgm on z01_numcgm = q02_numcgm';
    $xxcodigo  = 'k56_notifica';
    $xcampos   = ' z01_numcgm, z01_nome ';
    $contr     = '';

    if (isset($campo)){

      if ($tipo == 2){
        $contr = 'and k63_notifica in ('.str_replace("-",", ",$campo).') ';
      }elseif ($tipo == 3){
        $contr = 'and k63_notifica not in ('.str_replace("-",", ",$campo).') ';
      }
    }

  }elseif($k60_tipo == 'N' || $k60_tipo == 'C'){

    $xtipo     = 'Numcgm';
    $xcodigo   = 'k22_numcgm';
    $xcodigo1  = 'j01_numcgm';
    $xxcodigo1 = 'k57_numcgm';
    $xxmatric  = ' inner join notinumcgm on k22_numcgm = k57_numcgm ';
    $xxmatric2 = ' inner join cgm        on k22_numcgm = z01_numcgm ';
    $xxcodigo  = 'k57_notifica';
    $xcampos   = ' z01_numcgm, z01_nome ';
    $contr     = '';

    if (isset($campo)){

      if ($tipo == 2){
        $contr = 'and k63_notifica in ('.str_replace("-",", ",$campo).') ';
      }elseif ($tipo == 3){
        $contr = 'and k63_notifica not in ('.str_replace("-",", ",$campo).') ';
      }
    }
  } // Fim if tipo de Lista

  /**
   * Define a ordenação da query
   */
  if ($ordem == 'a') {

    $xxordem  = ' order by z01_nome';
    $xxxordem = ' order by substr(fc_proprietario_nome,8,40)';
  } else if($ordem == 't'){

    $xxordem  = ' order by ' . $xxcodigo;
    $xxxordem = ' order by notifica';
  } else if ($ordem == 'n') {

    $xxordem  = ' order by ' . $xxcodigo1;
    $xxxordem = ' order by ' . $xcodigo1;
  } else if ($ordem == 'e') {

    $xxordem  = ' order by ' . $xxcodigo1;
    $xxxordem = ' order by ender_entrega';
  } else {

    $xxordem  = ' order by z01_munic';
    $xxxordem = ' order by munic_entrega, cep_entrega';
  }

  $limite = '';
  if($fim > 0 && $intervalo == 'n'){
    $limite = 'and ' . $xxcodigo . ' >= ' . $inicio . ' and ' . $xxcodigo . ' <= ' . $fim;
  }

  $sSql  = " select notifica,                                                                 ";
  $sSql .= "        $xcodigo1,                                                                ";
  $sSql .= "        xvalor,                                                                   ";
  $sSql .= "        substr(fc_iptuender,001,40)       as ender_entrega,                       ";
  $sSql .= "        substr(fc_iptuender,159,08)       as cep_entrega,                         ";
  $sSql .= "        substr(fc_iptuender,115,40)       as munic_entrega,                       ";
  $sSql .= "        substr(fc_proprietario_nome,1,7)  as z01_numcgm,                          ";
  $sSql .= "        substr(fc_proprietario_nome,8,40) as z01_nome                             ";
  $sSql .= " from ( select $xxcodigo  as notifica,                                            ";
  $sSql .= "               $xxcodigo1 as $xcodigo1,                                           ";
  $sSql .= "               fc_iptuender($xxcodigo1),                                          ";
  $sSql .= "               fc_proprietario_nome($xxcodigo1),                                  ";
  $sSql .= "               sum(  k22_vlrcor +                                                 ";
  $sSql .= "                     k22_juros  +                                                 ";
  $sSql .= "                     k22_multa  -                                                 ";
  $sSql .= "                     k22_desconto)        as xvalor                               ";
  $sSql .= "          from lista                                                              ";
  $sSql .= "             inner join listanotifica on k63_codigo         = k60_codigo          ";
  $sSql .= "             inner join debitos       on k22_data           = '$k60_datadeb'      ";
  $sSql .= "                                     and k22_numpre         = k63_numpre          ";
  $sSql .= "             inner join arrecad       on k22_data           = '$k60_datadeb'      ";
  $sSql .= "                                     and arrecad.k00_numpre = debitos.k22_numpre  ";
  $sSql .= "                                     and arrecad.k00_numpar = debitos.k22_numpar  ";
  $sSql .= "                                     and arrecad.k00_receit = debitos.k22_receit  ";
  $sSql .= "                                     and arrecad.k00_tipo   = debitos.k22_tipo    ";
  $sSql .= "             $xxmatric                                                            ";
  $sSql .= "                                     and $xxcodigo = k63_notifica                 ";
  $sSql .= "             $xxmatric2  $limite $contr                                           ";
  $sSql .= " where k60_codigo = $lista                                                        ";
  $sSql .= "   and k22_dtvenc <= '$db_datausu'                                                ";
  $sSql .= "       group by $xxcodigo,                                                        ";
  $sSql .= "                $xxcodigo1                                                        ";
  $sSql .= "      ) as x                                                                      ";
  $sSql .= " $xxxordem                                                                        ";

  if ($k60_tipo != 'M' ){

    $sSql  = " select $xxcodigo    as notifica,                                ";
    $sSql .= "        $xxcodigo1   as $xcodigo1,                               ";
    $sSql .= "        $xcampos,                                                ";
    $sSql .= "        sum( k22_vlrcor +                                        ";
    $sSql .= "             k22_juros  +                                        ";
    $sSql .= "             k22_multa  -                                        ";
    $sSql .= "             k22_desconto                                        ";
    $sSql .= "           )         as xvalor                                   ";
    $sSql .= "   from lista                                                    ";
    $sSql .= "        inner join listanotifica on k63_codigo = k60_codigo      ";
    $sSql .= "        inner join debitos       on k22_data   = '$k60_datadeb'  ";
    $sSql .= "                                and k22_numpre = k63_numpre      ";
    $sSql .= "        $xxmatric                                                ";
    $sSql .= "                                and $xxcodigo  = k63_notifica    ";
    $sSql .= "        $xxmatric2                                               ";
    $sSql .= "                                $limite                          ";
    $sSql .= " $contr                                                          ";
    $sSql .= "  where k60_codigo  = $lista                                     ";
    $sSql .= "    and k22_dtvenc <= '$db_datausu'                              ";
    $sSql .= "  group by $xxcodigo,                                            ";
    $sSql .= "        $xxcodigo1,                                              ";
    $sSql .= "        z01_numcgm,                                              ";
    $sSql .= "        z01_nome                                                 ";
    $sSql .= "      $xxordem                                                   ";
  }

  $result = db_query($sSql) or die($sSql);

  if (pg_numrows($result) == 0){

    $oParms         = new stdClass();
    $oParms->sLista = $lista;
    $sMsg           = _M('tributario.notificacoes.cai2_emitenotif002.nenhum_notificacao_gerada', $oParms);
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
    exit;
  }

  if($fim > 0 && $intervalo != 'n'){

    $lim = 0;
    if($inicio > 0){
      $lim1 = $inicio - 1;
    }

    $lim2 = $fim;
    if($fim > pg_numrows($result)){
      $lim2 = pg_numrows($result);
    }

  }else{

    $lim1 = 0;
    $lim2 = pg_numrows($result);
  }

} else {

  /**
   * Emissão de notificação Geral
   */
  $sSqlNotifParc  = " select k43_notifica as notifica,                                         ";
  $sSqlNotifParc .= "        k55_matric   as j01_matric,                                       ";
  $sSqlNotifParc .= "        k56_inscr    as q02_inscr,                                        ";
  $sSqlNotifParc .= "        k57_numcgm   as j01_numcgm,                                       ";
  $sSqlNotifParc .= "        k57_numcgm   as z01_numcgm,                                       ";
  $sSqlNotifParc .= "        case when k55_matric is not null                                  ";
  $sSqlNotifParc .= "             then (select lpad(z01_numcgm,6,0)||' '||z01_nome             ";
  $sSqlNotifParc .= "                     from proprietario_nome                               ";
  $sSqlNotifParc .= "                    where j01_matric = k55_matric limit 1)                ";
  $sSqlNotifParc .= "             else case when k56_inscr is not null                         ";
  $sSqlNotifParc .= "                       then (select lpad(q02_numcgm,6,0)||' '||z01_nome   ";
  $sSqlNotifParc .= "                               from empresa                               ";
  $sSqlNotifParc .= "                              where q02_inscr = k56_inscr limit 1)        ";
  $sSqlNotifParc .= "                       else (select lpad(z01_numcgm,6,0)||' '||z01_nome   ";
  $sSqlNotifParc .= "                               from cgm                                   ";
  $sSqlNotifParc .= "                              where z01_numcgm = k57_numcgm limit 1)      ";
  $sSqlNotifParc .= "             end                                                          ";
  $sSqlNotifParc .= "        end as z01_nome,                                                  ";
  $sSqlNotifParc .= "        sum(k43_vlrcor+k43_vlrjur+k43_vlrmul-k43_vlrdes) as valor         ";
  $sSqlNotifParc .= "   from notidebitosreg                                                    ";
  $sSqlNotifParc .= "        left  join notimatric  on k43_notifica = k55_notifica             ";
  $sSqlNotifParc .= "        left  join notiinscr   on k43_notifica = k56_notifica             ";
  $sSqlNotifParc .= "        left  join notinumcgm  on k43_notifica = k57_notifica             ";
  $sSqlNotifParc .= "  where k43_notifica = {$notifica}                                        ";
  $sSqlNotifParc .= "  group by k43_notifica,                                                  ";
  $sSqlNotifParc .= "           k55_matric,                                                    ";
  $sSqlNotifParc .= "           k56_inscr,                                                     ";
  $sSqlNotifParc .= "           k57_numcgm,                                                    ";
  $sSqlNotifParc .= "           z01_nome                                                       ";

  $result = db_query($sSqlNotifParc) or die($sSqlNotifParc);
  $lim2   = pg_num_rows($result);

  $oNotifParc = db_utils::fieldsMemory($result, 0);

  if (isset($oNotifParc->j01_matric) && trim($oNotifParc->j01_matric) != ""){

    $k60_tipo   = "M";
    $j01_matric = $oNotifParc->j01_matric;
    $xtipo      = 'Matrícula';
    $xcodigo    = 'k22_matric';
    $xcodigo1   = 'j01_matric';
    $xxcodigo1  = 'k55_matric';
    $xxcodigo   = 'k55_notifica';

  }else if (isset($oNotifParc->q02_inscr) && trim($oNotifParc->q02_inscr) != ""){

    $k60_tipo  = "I";
    $xtipo     = 'Inscrição';
    $xcodigo   = 'k22_inscr';
    $xcodigo1  = 'q02_inscr';
    $xxcodigo1 = 'k56_inscr';
    $xxcodigo  = 'k56_notifica';
    $q02_inscr = $oNotifParc->q02_inscr;

  }else{

    $k60_tipo   = "N";
    $j01_numcgm = $oNotifParc->z01_numcgm;
    $xtipo      = 'Numcgm';
    $xcodigo    = 'k22_numcgm';
    $xcodigo1   = 'j01_numcgm';
    $xxcodigo1  = 'k57_numcgm';
    $xxcodigo   = 'k57_notifica';
  }
}

$pdf = new SCPDF("P",'mm',"A4");

$S   = $pdf->lMargin;

$iQtdNotificoesGeradas = 0;
$aListaNotifica        = array();

$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 0);

/**
 * Verifica Tipo de Emissão
 *
 *   Notificação Geral   -  1
 *   Notificação Parcial - 11
 *   Lista               -  2
 *   Aviso Débito        -  3
 */
if ( $tiporel == 1 || $tiporel == 11 ) {

  for($indx=$lim1;$indx < $lim2;$indx++) {

    db_fieldsmemory($result,$indx);

    if ( $oParam->k102_tipoemissao == 3 ) {

      $sSqlConsultaPaga  = " select distinct                                                               ";
      $sSqlConsultaPaga .= "        case                                                                   ";
      $sSqlConsultaPaga .= "          when arrematric.k00_matric is not null then 'M-'||k00_matric         ";
      $sSqlConsultaPaga .= "          else case                                                            ";
      $sSqlConsultaPaga .= "             when arreinscr.k00_inscr is not null then 'I-'||k00_inscr         ";
      $sSqlConsultaPaga .= "             else 'C'||arrenumcgm.k00_numcgm                                   ";
      $sSqlConsultaPaga .= "             end                                                               ";
      $sSqlConsultaPaga .= "        end as origem,                                                         ";
      $sSqlConsultaPaga .= "        z01_nome                                                               ";
      $sSqlConsultaPaga .= "   from arrepaga                                                               ";
      $sSqlConsultaPaga .= "        inner join notidebitos on notidebitos.k53_numpre = arrepaga.k00_numpre ";
      $sSqlConsultaPaga .= "                              and notidebitos.k53_numpar = arrepaga.k00_numpar ";
      $sSqlConsultaPaga .= "        left  join arrematric  on arrematric.k00_numpre  = arrepaga.k00_numpre ";
      $sSqlConsultaPaga .= "        left  join arreinscr   on arreinscr.k00_numpre   = arrepaga.k00_numpre ";
      $sSqlConsultaPaga .= "        left  join arrenumcgm  on arrenumcgm.k00_numpre  = arrepaga.k00_numpre ";
      $sSqlConsultaPaga .= "        inner join cgm         on cgm.z01_numcgm         = arrepaga.k00_numcgm ";
      $sSqlConsultaPaga .= "  where notidebitos.k53_notifica = " . $notifica;

      $rsConsultaPaga    = db_query($sSqlConsultaPaga);
      $iLinhasPaga       = pg_num_rows($rsConsultaPaga);

      if ( $iLinhasPaga > 0 ) {

        for ($iInd=0; $iInd < $iLinhasPaga; $iInd++) {

          $oPaga = db_utils::fieldsMemory($rsConsultaPaga,$iInd);

          $aListaNotifica[$notifica]['Origem'] = $oPaga->origem;
          $aListaNotifica[$notifica]['Nome']   = $oPaga->z01_nome;
        }

        continue;
      }
    }

    if(!isset($k60_datadeb)){
      $k60_datadeb = '';
    }
    $sqljapagou  = " select distinct cadtipo.k03_tipo                                           ";
    $sqljapagou .= "   from notidebitos                                                         ";
    $sqljapagou .= "        inner join debitos   on debitos.k22_numpre = notidebitos.k53_numpre ";
    $sqljapagou .= "                            and debitos.k22_numpar = notidebitos.k53_numpar ";
    $sqljapagou .= "                            and debitos.k22_data   = '$k60_datadeb'         ";
    $sqljapagou .= "        inner join arretipo  on arretipo.k00_tipo  = debitos.k22_tipo       ";
    $sqljapagou .= "        inner join cadtipo   on cadtipo.k03_tipo   = arretipo.k03_tipo      ";
    $sqljapagou .= "        inner join arrecad   on arrecad.k00_numpre = debitos.k22_numpre     ";
    $sqljapagou .= "                            and arrecad.k00_numpar = debitos.k22_numpar     ";
    $sqljapagou .= "                            and arrecad.k00_receit = debitos.k22_receit     ";
    $sqljapagou .= "                            and arrecad.k00_tipo   = debitos.k22_tipo       ";
    $sqljapagou .= "  where notidebitos.k53_notifica = " . $notifica;

    if(isset($notifparc)){

      $sqljapagou  = " select distinct cadtipo.k03_tipo";
      $sqljapagou .= "   from notidebitosreg";
      $sqljapagou .= "        inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre";
      $sqljapagou .= "                           and arrecad.k00_numpar = notidebitosreg.k43_numpar";
      $sqljapagou .= "                           and arrecad.k00_receit = notidebitosreg.k43_receit";
      $sqljapagou .= "        inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo";
      $sqljapagou .= "        inner join cadtipo  on cadtipo.k03_tipo   = arretipo.k03_tipo";
      $sqljapagou .= "  where notidebitosreg.k43_notifica = " . $notifica;
    }

    $resultjapagou = db_query($sqljapagou);

    if ($resultjapagou == false || pg_num_rows($resultjapagou) == 0) {

      $oParms            = new stdClass();
      $oParms->sNotifica = $notifica;
      $sErroMsg          = _M('tributario.notificacoes.cai2_emitenotif002.problema_verificar_notificacao', $oParms);
      continue;
    }

    if ($k60_tipo == 'M') {

      if ($tratamento > 1) {

        if (EnderecoNotif($tratamento, @$j01_matric, $z01_nome) == false && $imprimirmesmoembranco == "n") {
          continue;
        }
      }
    }

    if (pg_numrows($resultjapagou) == 0) {
      continue;
    }

    db_fieldsmemory($resultjapagou, 0);

    if ($k03_tipo != 6 && $k03_tipo != 13 && $k03_tipo != 16) {
      $somenteparc = false;
    }

    if ($k03_tipo != 1) {
      $somenteiptu = false;
    }

    $pdf->AddPage();

    $iQtdNotificoesGeradas++;

    global $variavel;
    $variavel = "";
    if( ($imprimirtimbre == 1) || ($imprimirtimbre == 2) ){
      $pdf = CabecNotif($pdf, 0, $variavel);
    }

    $pdf->SetFont('Arial','',13);

    $numcgm = @$j01_numcgm;
    $matric = @$j01_matric;
    $inscr  = @$q02_inscr;

    if( $matric != '' ){

      $xmatinsc   = " k22_matric = ".$matric." and ";
      $xmatinsc22 = " k22_matric = ".$matric." and ";
      $xmatinsc00 = " k00_matric = ".$matric." and ";
      $matinsc    = "sua matrícula n".chr(176)." ".$matric;
    }else if( $inscr != '' ){

      $xmatinsc   = " k22_inscr = ".$inscr." and ";
      $xmatinsc22 = " k22_inscr = ".$inscr." and ";
      $xmatinsc00 = " k00_inscr = ".$inscr." and ";
      $matinsc    = "sua inscrição n".chr(176)." ".$inscr;
    }else{

      $xmatinsc   = " k22_numcgm = ".$numcgm." and ";
      $xmatinsc22 = " k22_numcgm = ".$numcgm." and ";
      $xmatinsc00 = " k00_numcgm = ".$numcgm." and ";
      $matinsc    = "V.Sa.";
    }

    $matricula = $matric;
    $inscricao = $inscr;
    $jtipos    = '';
    $num2      = 0;
    $k00_descr = '';

    if ( isset($tipos) && trim($tipos)!= '' ){

      $jtipos = ' k22_tipo in ('.$tipos.') and ';
      $sql2   = " select k22_tipo,
                         k00_descr,
                         sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as valor
                    from debitos
                         inner join arretipo on k00_tipo = k22_tipo
                   where $xmatinsc k22_tipo not in ($tipos)
                     and k22_data = '$k60_datadeb'
                     and k22_dtvenc <= '$db_datausu'
                   group by k22_tipo,k00_descr";

      $result2 = db_query($sql2);
      $num2    = pg_numrows($result2);
    }

    $sSqlproced  = " select distinct proced.v03_codigo,   proced.v03_dcomp        ";
    $sSqlproced .= "            from notidebitos                                  ";
    $sSqlproced .= "                 inner join divida on v01_numpre = k53_numpre ";
    $sSqlproced .= "                                  and v01_numpar = k53_numpar ";
    $sSqlproced .= "                 inner join proced on v03_codigo = v01_proced ";
    $sSqlproced .= "           where k53_notifica = " . $notifica;

    $resultaproced = db_query($sSqlproced);
    $virgula       = '';

    global $procedencias;
    $procedencias  = '';
    for($i = 0;$i < pg_numrows($resultaproced);$i++){

      db_fieldsmemory($resultaproced,$i);
      $procedencias .= $virgula.$v03_dcomp;
      $virgula       = ', ';
    }
    $cgm = $z01_numcgm;

    /**
     * Verifica se esta emitindo notificação Parcial ou Geral
     */
    if (!isset($notifparc)){

      $sql1 = "select k22_tipo,
                      k00_descr,
                      sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as valor
                 from debitos
                      inner join arretipo on k00_tipo = k22_tipo
                where $xmatinsc $jtipos k22_data = '$k60_datadeb'
                  and k22_dtvenc <= '$db_datausu'
             group by k22_tipo,k00_descr";

      $result1 = db_query($sql1);
    }

    if ($k60_tipo == 'M'){

      $sqlpropri = "select proprietario.z01_nome,
                           proprietario.codpri,
                           proprietario.nomepri,
                           proprietario.j39_numero,
                           proprietario.j39_compl,
                           proprietario.j34_setor,
                           proprietario.j34_quadra,
                           proprietario.j34_lote,
                           proprietario.j34_zona,
                           proprietario.j34_area,
                           proprietario.j01_tipoimp,
                           proprietario.j13_descr,
                           proprietario.z01_cgmpri,
                           proprietario.j05_codigoproprio,
                           proprietario.j06_setorloc,
                           proprietario.j06_quadraloc,
                           proprietario.j06_lote,
                           proprietario.j05_descr,
                           proprietario.pql_localizacao,
                           substr(proprietario.j01_tipoimp,1,1) as j01_tipoimpres,
                           cgm.*
                      from proprietario
                           inner join cgm on cgm.z01_numcgm = proprietario.z01_cgmpri
                     where j01_matric = $matric";

      $resultpropri = db_query($sqlpropri);
      if (pg_numrows($resultpropri) > 0) {

        db_fieldsmemory($resultpropri,0);
        $nomepri    = ucwords(strtolower($nomepri));
        $j13_descr  = ucwords(strtolower($j13_descr));
        $z01_numcgm = $z01_cgmpri;
      }

      $sqlender    = "select fc_iptuender($matric)";
      $resultender = db_query($sqlender);
      db_fieldsmemory($resultender,0);

      $endereco = split("#", $fc_iptuender);

      if (sizeof($endereco) < 7) {

        $z01_ender    = "";
        $z01_numero   = 0;
        $z01_compl    = "";
        $z01_bairro   = "";
        $z01_munic    = "";
        $z01_uf       = "";
        $z01_cep      = "";
        $z01_cxpostal = "";
      } else {

        $z01_ender    = $endereco[0];
        $z01_numero   = $endereco[1];
        $z01_compl    = $endereco[2];
        $z01_bairro   = $endereco[3];
        $z01_munic    = $endereco[4];
        $z01_uf       = $endereco[5];
        $z01_cep      = $endereco[6];
        $z01_cxpostal = $endereco[7];
      }

    } else if ($k60_tipo == 'I') {

      $imprime = "INSCRICAO: $q02_inscr";

      $sqlempresa = "select *
                       from empresa
                            inner join cgm on z01_numcgm =  q02_numcgm
                      where q02_inscr = $q02_inscr";

      $resultempresa = db_query($sqlempresa);
      if (pg_numrows($resultempresa) > 0) {
        db_fieldsmemory($resultempresa,0);
      }

    } else {

      $sqlender = "select *
                     from cgm
                    where z01_numcgm = $cgm";

      $resultender = db_query($sqlender);
      db_fieldsmemory($resultender,0,true);
    }

    $impostos  = '';
    $virgula   = '';
    $xvalor    = 0;

    $impostos  = 'DÍVIDA ATIVA' ;
    $impostos2 = '';
    $virgula2  = '';
    $xvalor2   = 0;

    /**
     * Verifica se esta emitindo notificação Parcial ou Geral
     */
    if (!isset($notifparc)) {

      $sqldocparagr = "select *
                         from db_docparag
                              inner join listadoc   on db04_docum     = k64_docum
                              inner join db_paragrafo on db02_idparag = db04_idparag
                        where k64_codigo = $lista
                     order by db04_ordem";
    }else{

      $sqldocparagr = "select *
                         from db_docparag
                              inner join notificadoc  on db04_docum   = k100_db_documento
                              inner join db_paragrafo on db02_idparag = db04_idparag
                        where k100_notifica = {$notifica}
                     order by db04_ordem";

      $rsTestaDoc = db_query($sqldocparagr) or die($sqldocparagr);

      if ( pg_num_rows($rsTestaDoc) == 0 ) {

        $sSqlConsultaTipo = "select distinct
                                    arretipo.k00_tipo,
                                    arretipo.k00_descr
                               from notidebitosreg
                                    inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                       and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                                       and arrecad.k00_receit = notidebitosreg.k43_receit
                                    inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                              where k43_notifica = " . $notifica;
        $rsConsultaTipo = db_query($sSqlConsultaTipo) or die($sSqlConsultaTipo);
        $iNroLinhaTipo  = pg_num_rows($rsConsultaTipo);

        if ($iNroLinhaTipo > 1 ) {

          $oParms = new stdClass();
          $oParms->sNotifica = $notifica;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.existe_mais_de_um_tipo_debito', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");

        } else if ($iNroLinhaTipo > 0 ) {

          $oTipo = db_utils::fieldsMemory($rsConsultaTipo,0);
          $sqldocparagr = "select *
                             from db_docparag
                                  inner join db_paragrafo        on db02_idparag      = db04_idparag
                                  inner join notificaarretipodoc on k101_db_documento = db04_docum
                            where k101_tipo = {$oTipo->k00_tipo}
                         order by db04_ordem";

          $rsTestaDoc = db_query($sqldocparagr) or die($sqldocparagr);

          if ( pg_num_rows($rsTestaDoc) == 0 ) {

            $sqldocparagr = " select *
                                from db_docparag
                                     inner join db_paragrafo   on db02_idparag      = db04_idparag
                                     inner join parnotificacao on k102_docnotpadrao = db04_docum
                               where k102_anousu = " . db_getsession("DB_anousu") . "
                                 and k102_instit = " . db_getsession("DB_instit") . "
                            order by db04_ordem";

            $rsTestaDoc = db_query($sqldocparagr) or die($sqldocparagr);

            if ( pg_num_rows($rsTestaDoc) == 0 ) {

              $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.nao_existe_documento_padrao_cadastrado');
              db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
            }
          }
        }
      }
    }

    $resultdocparagr = db_query($sqldocparagr) or die($sqldocparagr);

    /**
     * Validamos a exibiçao da coluna desconto
     */
    $aDocumentoParagrafo = db_utils::getCollectionByRecord($resultdocparagr);

    $lDesconto         = true;
    $lTotalComDesconto = false;
    $lSeedResumido     = false;

    foreach ( $aDocumentoParagrafo as $iChave => $oDocumentoParagrafo) {

      if (strtoupper($oDocumentoParagrafo->db02_descr) == 'NAOEXIBIRDESCONTO') {

        $lDesconto = false;
        continue;
      }

      if (strtoupper($oDocumentoParagrafo->db02_descr) == 'TOTALPORANOCOMDESCONTO') {

        $lTotalComDesconto = true;
        continue;
      }

      if ( strtoupper($oDocumentoParagrafo->db02_descr) == 'SEEDRESUMIDO') {
        $lSeedResumido = true;
      }
    }

    if ($lTotalComDesconto) {
      $lDesconto = true;
    }

    global $db02_inicia;
    global $db02_espaca;

    $sqltexto = "select munic, cgc from db_config where codigo = " . db_getsession("DB_instit");
    $resulttexto = db_query($sqltexto);

    db_fieldsmemory($resulttexto,0,true);
    $pdf->SetFont('Arial','',$fonte);

    if ( $cgc != "92324706000127") {
      $S = $pdf->lMargin;
    }

    /**
     * For dos paragrafos da notificação
     */
    for ($doc = 0; $doc < pg_numrows($resultdocparagr); $doc++) {

      db_fieldsmemory($resultdocparagr,$doc);
      $texto = db_geratexto($db02_texto);

      $sInnerArrecad = "";
      if ( $oParam->k102_tipoemissao == 2 ) {

        $sInnerArrecad = " inner join arrecad  on arrecad.k00_numpre = debitos.k22_numpre
                                              and arrecad.k00_numpar = debitos.k22_numpar
                                              and arrecad.k00_receit = debitos.k22_receit";
      }

       $sqlanos = " select k22_ano,
                           sum(substr(fc_calcula,2,13)::float8)  as k22_vlrhis,
                           sum(substr(fc_calcula,15,13)::float8) as k22_vlrcor,
                           sum(substr(fc_calcula,28,13)::float8) as k22_juros,
                           sum(substr(fc_calcula,41,13)::float8) as k22_multa,
                           sum(substr(fc_calcula,54,13)::float8) as k22_desconto,
                           sum((substr(fc_calcula,15,13)::float8+
                           substr(fc_calcula,28,13)::float8+
                           substr(fc_calcula,41,13)::float8-
                           substr(fc_calcula,54,13)::float8)) as k22_total
                      from ( select fc_arrecexerc(arrecad.k00_numpre, arrecad.k00_numpar ) as k22_ano,
                                    fc_calcula(arrecad.k00_numpre, arrecad.k00_numpar, 0, '{$db_datausu}', '{$db_datausu}',".db_getsession("DB_anousu").")
                               from notidebitos
                                    inner join arrecad  on arrecad.k00_numpre = k53_numpre
                                                       and arrecad.k00_numpar = k53_numpar
                                    inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                              where k53_notifica = $notifica
                           order by 1
                           ) as x
              group by k22_ano
              order by k22_ano";

        $resultanos              = db_query($sqlanos);
        $rsTotaisPorAnoCorrigido = $resultanos;

        if ($resultanos == false) {

          $oParms = new stdClass();
          $oParms->sSqlAnos = $sqlanos;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_anos', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        $totvlrhis1   = 0;
        $totvlrcor1   = 0;
        $totjuros1    = 0;
        $totmulta1    = 0;
        $totdesconto1 = 0;
        $tottotal1    = 0;

        if(pg_num_rows($resultanos)>0){

          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {

            db_fieldsmemory($resultanos,$totano);
            $totvlrhis1   += $k22_vlrhis;
            $totvlrcor1   += $k22_vlrcor;
            $totjuros1    += $k22_juros;
            $totmulta1    += $k22_multa;
            $totdesconto1 += $k22_desconto;
            $tottotal1    += $k22_total;
          }
        }

      if (strtoupper($db02_descr) == "TOTALPORANO" && $somenteparc == false) {

        if (isset($notifparc) ){

          $sqlanos = " select extract (year from k00_dtoper) as k22_ano,
                              sum(k00_valor)  as k22_vlrhis,
                              sum(k43_vlrcor) as k22_vlrcor,
                              sum(k43_vlrjur) as k22_juros,
                              sum(k43_vlrmul) as k22_multa,
                              sum(k43_vlrdes) as k22_desconto,
                              sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                         from notidebitosreg
                              inner join arrecad on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                                and arrecad.k00_receit = notidebitosreg.k43_receit
                        where k43_notifica = {$notifica}
                        group by extract (year from k00_dtoper)
                        order by extract (year from k00_dtoper)";

        } else {

          $sqlanos =  " select case when k22_exerc is null
                                    then extract (year from k22_dtoper)
                                    else k22_exerc
                               end as k22_ano,
                               sum(k22_vlrhis)   as k22_vlrhis,
                               sum(k22_vlrcor)   as k22_vlrcor,
                               sum(k22_juros)    as k22_juros,
                               sum(k22_multa)    as k22_multa,
                               sum(k22_desconto) as k22_desconto,
                               sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                          from notidebitos
                               inner join debitos  on k22_numpre = k53_numpre
                                                  and k22_numpar = k53_numpar
                                                  and k22_data   = '$k60_datadeb'
                               inner join arretipo on k00_tipo   = k22_tipo
                               {$sInnerArrecad}
                         where k53_notifica = $notifica
                         group by case when k22_exerc is null then extract (year from k22_dtoper) else k22_exerc end
                         order by case when k22_exerc is null then extract (year from k22_dtoper) else k22_exerc end ";

        }

        $resultanos = db_query($sqlanos);
        if ($resultanos == false) {

          $oParms = new stdClass();
          $oParms->sSqlAnos = $sqlanos;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_anos', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        if(pg_num_rows($resultanos)>0){

          $pdf->setfillcolor(245);
          $pdf->cell(2,05,"",               0,0,"C",0);
          $pdf->cell(15,05,"ANO",           1,0,"C",1);
          $pdf->cell(25,05,"VLR ORIGINAL",  1,0,"C",1);
          $pdf->cell(30,05,"VLR CORRIGIDO", 1,0,"C",1);
          $pdf->cell(25,05,"JUROS",         1,0,"C",1);
          $pdf->cell(25,05,"MULTA",         1,0,"C",1);

          if ($lDesconto) {
            $pdf->cell(25,05,"DESCONTO",      1,0,"C",1);
          }

          $pdf->cell(30,05,"VLR TOTAL",     1,1,"C",1);
          $pdf->setfillcolor(255,255,255);

          $totvlrhis   = 0;
          $totvlrcor   = 0;
          $totjuros    = 0;
          $totmulta    = 0;
          $tottotal    = 0;
          $totdesconto = 0;

          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {

            db_fieldsmemory($resultanos,$totano);
            $pdf->cell(2,05,"",0,0,"C",0);
            $pdf->cell(15,05,$k22_ano,                             1,0,"C",0);
            $pdf->cell(25,05,trim(db_formatar($k22_vlrhis,'f')),   1,0,"R",0);
            $pdf->cell(30,05,trim(db_formatar($k22_vlrcor,'f')),   1,0,"R",0);
            $pdf->cell(25,05,trim(db_formatar($k22_juros,'f')) ,   1,0,"R",0);
            $pdf->cell(25,05,trim(db_formatar($k22_multa,'f')) ,   1,0,"R",0);

            if ($lDesconto) {
              $pdf->cell(25,05,trim(db_formatar($k22_desconto,'f')), 1,0,"R",0);
            }

            $pdf->cell(30,05,trim(db_formatar($k22_total,'f')) ,   1,1,"R",0);

            $totvlrhis   += $k22_vlrhis;
            $totvlrcor   += $k22_vlrcor;
            $totjuros    += $k22_juros;
            $totmulta    += $k22_multa;
            $totdesconto += $k22_desconto;
            $tottotal    += $k22_total;
          }

          $pdf->setfillcolor(245);
          $pdf->cell(2,05,"",0,0,"C",0);
          $pdf->cell(15,05,"",                                   0,0,"L",0);
          $pdf->cell(25,05,trim(db_formatar($totvlrhis,'f')),    1,0,"R",1);
          $pdf->cell(30,05,trim(db_formatar($totvlrcor,'f')),    1,0,"R",1);
          $pdf->cell(25,05,trim(db_formatar($totjuros,'f')) ,    1,0,"R",1);
          $pdf->cell(25,05,trim(db_formatar($totmulta,'f')) ,    1,0,"R",1);

          if ($lDesconto) {
            $pdf->cell(25,05,trim(db_formatar($totdesconto,'f')) , 1,0,"R",1);
          }

          $pdf->cell(30,05,trim(db_formatar($tottotal,'f')) ,    1,1,"R",1);
          $pdf->setfillcolor(255,255,255);
        }

      } elseif (strtoupper($db02_descr) == "MSGOUTROSDEBITOS" && !isset($notifparc) ) {

        /**
         * processando variavel $listaoutrosdebitos que contem a lista dos outros debitos separados por virgula,
         * alem dos selecionados na lista.
         * Exemplo: DIVIDA ATIVA, INICIAL DO FORO, PARCELAMENTO DE DIVIDA
         * essa linha somente será gerada caso existam outros débitos, senao simplesmente nao será gerada.
         */
        $sql_outros = " select k22_tipo,
                               k00_descr,
                               sum(k22_vlrcor+k22_juros+k22_multa-k22_desconto) as valor
                          from debitos
                               inner join arretipo on k00_tipo = k22_tipo
                               {$sInnerArrecad}
                         where $xmatinsc " . str_replace('in',' not in ',$jtipos) . " k22_data = '$k60_datadeb'
                           and k22_dtvenc < '" .  date("Y-m-d",db_getsession("DB_datausu")) . "'
                         group by k22_tipo, k00_descr";

        $result_outros = db_query($sql_outros) or die($sql_outros);

        $listaoutrosdebitos = "";

        for ($outrosdeb=0; $outrosdeb < pg_numrows($result_outros); $outrosdeb++) {

          db_fieldsmemory($result_outros, $outrosdeb);
          $listaoutrosdebitos .= $k00_descr . ($outrosdeb < pg_numrows($result_outros) - 1 ? ", " : "" );
        }

        if (strlen(trim($listaoutrosdebitos)) > 0) {

          $pdf->SetFont('Arial','B',$fonte);
          $pdf->multicell(0,6,db_geratexto($db02_texto),0,"$db02_alinhamento",0,$db02_inicia);
          $pdf->SetFont('Arial','',$fonte);
        }

      } else if (strtoupper($db02_descr) == "DADOS_DEVEDORES" ) {

        if ($pdf->gety() > ( $pdf->h -10 ) ) {
          $pdf->addPage();
        }

        $pdf->setfont('arial','B',10);
        $pdf->cell(190,7,'DEVEDOR(ES)',0,1,"C",0);
        $pdf->cell(190,0.7,'',"TB",1,"L",0);
        $pdf->Ln(5);
        $pdf->setfont('arial','B',10);
        $pdf->cell(50 ,5,'TIPO'     ,"TB",0,"L",0);
        $pdf->cell(100,5,'NOME'     ,1   ,0,"L",0);
        $pdf->cell(40 ,5,'CPF/CNPJ',"TB" ,1,"L",0);
        $pdf->setfont('arial','',10);

        /**
         * Busca se  é ou não Possuidor
         */
        $sqlPossuidor  = " select j18_textoprom                           ";
        $sqlPossuidor .= "   from cfiptu                                  ";
        $sqlPossuidor .= "  where j18_anousu= ".db_getsession("DB_anousu");

        $resultPossuidor = db_query($sqlPossuidor);
        $linhasPossuidor = pg_num_rows($resultPossuidor);

        $possuidor = "POSSUIDOR";
        if ($linhasPossuidor>0){

          db_fieldsmemory($resultPossuidor,0);
          if(trim($j18_textoprom) != ""){
            $possuidor = $j18_textoprom;
          }
        }

        /**
         * Busca Origem do Numpre pela Notificação
         */
        $sSqlOrigensNumpre  = " select distinct                                                               ";
        $sSqlOrigensNumpre .= "        k53_numpre,                                                            ";
        $sSqlOrigensNumpre .= "       case                                                                    ";
        $sSqlOrigensNumpre .= "         when k00_matric is not null then                                      ";
        $sSqlOrigensNumpre .= "           (select db21_regracgmiptu from db_config where prefeitura is true)  ";
        $sSqlOrigensNumpre .= "         when k00_inscr  is not null then                                      ";
        $sSqlOrigensNumpre .= "           (select db21_regracgmiss from db_config where prefeitura is true)   ";
        $sSqlOrigensNumpre .= "         else 0                                                                ";
        $sSqlOrigensNumpre .= "       end as regra,                                                           ";
        $sSqlOrigensNumpre .= "       case                                                                    ";
        $sSqlOrigensNumpre .= "         when k00_matric is not null then 'M'                                  ";
        $sSqlOrigensNumpre .= "         when k00_inscr  is not null then 'I'                                  ";
        $sSqlOrigensNumpre .= "         else 'C'                                                              ";
        $sSqlOrigensNumpre .= "       end as tipoorigem,                                                      ";
        $sSqlOrigensNumpre .= "       case                                                                    ";
        $sSqlOrigensNumpre .= "         when k00_matric is not null then k00_matric                           ";
        $sSqlOrigensNumpre .= "         when k00_inscr  is not null then k00_inscr                            ";
        $sSqlOrigensNumpre .= "         else k00_numcgm                                                       ";
        $sSqlOrigensNumpre .= "       end as origem                                                           ";
        $sSqlOrigensNumpre .= "  from notidebitos                                                             ";
        $sSqlOrigensNumpre .= "       inner join arrenumcgm on arrenumcgm.k00_numpre = notidebitos.k53_numpre ";
        $sSqlOrigensNumpre .= "       left  join arrematric on arrematric.k00_numpre = notidebitos.k53_numpre ";
        $sSqlOrigensNumpre .= "       left  join arreinscr  on arreinscr.k00_numpre  = notidebitos.k53_numpre ";
        $sSqlOrigensNumpre .= " where k53_notifica = " . $notifica;

        $rsOrigensNumpre    = db_query($sSqlOrigensNumpre);
        $iLinhaOrigemNumpre = pg_num_rows($rsOrigensNumpre);

        for ($ind = 0; $ind < $iLinhaOrigemNumpre; $ind ++ ) {

          $oOrigensNumpre = db_utils::fieldsMemory($rsOrigensNumpre,$ind);

          $sSqlEnvol      = " select * from fc_busca_envolvidos(false, {$oOrigensNumpre->regra},'{$oOrigensNumpre->tipoorigem}',{$oOrigensNumpre->origem})";
          $rsEnvol        = db_query($sSqlEnvol) or die($sSqlEnvol);
          $iLinhasEnvol   = pg_num_rows($rsEnvol);

          $oEnvol = db_utils::getCollectionByRecord($rsEnvol);
        }

          foreach ($oEnvol as $iIndiceEnvol => $oEnvol){

            /**
             * Busca os dados do Numpre
             */
            $sSqlDadosEnvol  = " select z01_numcgm,                      ";
            $sSqlDadosEnvol .= "        z01_nome,                        ";
            $sSqlDadosEnvol .= "        z01_cgccpf,                      ";
            $sSqlDadosEnvol .= "        z01_ender,                       ";
            $sSqlDadosEnvol .= "        z01_numero,                      ";
            $sSqlDadosEnvol .= "        z01_compl,                       ";
            $sSqlDadosEnvol .= "        z01_bairro,                      ";
            $sSqlDadosEnvol .= "        z01_munic,                       ";
            $sSqlDadosEnvol .= "        z01_cep,                         ";
            $sSqlDadosEnvol .= "        z01_uf,                          ";
            $sSqlDadosEnvol .= "        z01_dtfalecimento                ";
            $sSqlDadosEnvol .= "   from cgm                              ";
            $sSqlDadosEnvol .= "  where z01_numcgm = " . $oEnvol->rinumcgm;

            $rsDadosEnvol      = db_query($sSqlDadosEnvol) or die($sSqlDadosEnvol);
            $iLinhasDadosEnvol = pg_num_rows($rsDadosEnvol);

            if ($iLinhasDadosEnvol > 0) {

              $oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol,0);

              $sNome = $oDadosEnvol->z01_nome;
              if ( trim($oDadosEnvol->z01_dtfalecimento) != '' && strlen($oDadosEnvol->z01_cgccpf) == 11 && $oDadosEnvol != '00000000000') {
                $sNome = $sExpressaoFalecimento." ".$oDadosEnvol->z01_nome;
              }

              $sEndereco = "";
              $sEndereco = $oDadosEnvol->z01_ender;

              if(trim($oDadosEnvol->z01_numero) !="0" && trim($oDadosEnvol->z01_numero)!=""){
                $sEndereco .= ",{$oDadosEnvol->z01_numero} ";
              }
              if(trim($oDadosEnvol->z01_compl)  !="0" && trim($oDadosEnvol->z01_compl) !=""){
                $sEndereco .= ",{$oDadosEnvol->z01_compl} ";
              }
              if(trim($oDadosEnvol->z01_bairro) !="0" && trim($oDadosEnvol->z01_bairro)!=""){
                $sEndereco .= ",{$oDadosEnvol->z01_bairro} ";
              }
              if(trim($oDadosEnvol->z01_munic)  !="0" && trim($oDadosEnvol->z01_munic) !=""){
                $sEndereco .= ",{$oDadosEnvol->z01_munic}/{$oDadosEnvol->z01_uf} ";
              }
              if(trim($oDadosEnvol->z01_cep)    !="0" && trim($oDadosEnvol->z01_cep)   !=""){
                $sEndereco .= "- CEP {$oDadosEnvol->z01_cep} .";
              }

              if ($oEnvol->ritipoenvol == "1" || $oEnvol->ritipoenvol == "2") {
                $sTipoProp = "PROPRIETÁRIO";
              }else{
                $sTipoProp = $oOrigensNumpre->tipoorigem == 'I' ? 'EMPRESA' : $possuidor;
              }

              $pdf->cell(50,5,$sTipoProp ,0,0,"L",0);
              $pdf->Cell(100,5,$sNome    ,0,0,"L",0);
              $tam = strlen($oDadosEnvol->z01_cgccpf);

              if($tam == 14){
                $sCgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cnpj");
              }else{
                $sCgcCpf = db_formatar($oDadosEnvol->z01_cgccpf,"cpf");
              }

              $pdf->Cell(40,5,$sCgcCpf,0,1,"L",0);
              $pdf->setfont('arial','',8);
              $pdf->MultiCell(190,5,"$sEndereco","B","L",0);
              $pdf->setfont('arial','',10);
            }

          }

      } else if (strtoupper($db02_descr) == "TOTALPORANOCORRIGIDO" && $somenteparc == false) {

        $totvlrhis1  = 0;
        $totvlrcor1  = 0;
        $totjuros1   = 0;
        $totmulta1   = 0;
        $tottotal1   = 0;
        $totdesconto1= 0;

        $totvlrhis   = 0;
        $totvlrcor   = 0;
        $totjuros    = 0;
        $totmulta    = 0;
        $tottotal    = 0;
        $totdesconto = 0;

        if(pg_num_rows($resultanos)>0){

          $pdf->setfillcolor(245);
          $pdf->cell(2,05,"",0,0,"C",0);
          $pdf->cell(15,05,"ANO",1,0,"C",1);
          $pdf->cell(25,05,"VLR ORIGINAL" ,1,0,"C",1);
          $pdf->cell(30,05,"VLR CORRIGIDO",1,0,"C",1);
          $pdf->cell(25,05,"JUROS"    ,1,0,"C",1);
          $pdf->cell(25,05,"MULTA"    ,1,0,"C",1);

          if ($lDesconto) {
            $pdf->cell(25,05,"DESCONTO"   ,1,0,"C",1);
          }

          $pdf->cell(30,05,"VLR TOTAL"  ,1,1,"C",1);
          $pdf->setfillcolor(255,255,255);

          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {

            db_fieldsmemory($resultanos,$totano);

            $pdf->cell(2,05,"",0,0,"C",0);
            $pdf->cell(15,05,$k22_ano,                           1,0,"C",0);
            $pdf->cell(25,05,trim(db_formatar($k22_vlrhis,'f')) ,1,0,"R",0);
            $pdf->cell(30,05,trim(db_formatar($k22_vlrcor,'f')) ,1,0,"R",0);
            $pdf->cell(25,05,trim(db_formatar($k22_juros,'f'))  ,1,0,"R",0);
            $pdf->cell(25,05,trim(db_formatar($k22_multa,'f'))    ,1,0,"R",0);

            if ($lDesconto) {
              $pdf->cell(25,05,trim(db_formatar($k22_desconto,'f')) ,1,0,"R",0);
            }

            $pdf->cell(30,05,trim(db_formatar($k22_total,'f'))  ,1,1,"R",0);

            $totvlrhis   += $k22_vlrhis;
            $totvlrcor   += $k22_vlrcor;
            $totjuros    += $k22_juros;
            $totmulta    += $k22_multa;
            $totdesconto += $k22_desconto;
            $tottotal    += $k22_total;
          }

          $total_t = $tottotal;

          $pdf->setfillcolor(245);
          $pdf->cell(2,05,"",0,0,"C",0);
          $pdf->cell(15,05,"",                                  0,0,"L",0);
          $pdf->cell(25,05,trim(db_formatar($totvlrhis,'f'))   ,1,0,"R",1);
          $pdf->cell(30,05,trim(db_formatar($totvlrcor,'f'))   ,1,0,"R",1);
          $pdf->cell(25,05,trim(db_formatar($totjuros,'f'))    ,1,0,"R",1);
          $pdf->cell(25,05,trim(db_formatar($totmulta,'f'))    ,1,0,"R",1);

          if ($lDesconto) {
            $pdf->cell(25,05,trim(db_formatar($totdesconto,'f')) ,1,0,"R",1);
          }

          $pdf->cell(30,05,trim(db_formatar($tottotal,'f'))    ,1,1,"R",1);
          $pdf->setfillcolor(255,255,255);
        }

      } else if (strtoupper($db02_descr) == "TOTALPORANOPARCELARECEITA") {

        $pdf->SetFont('Arial','',6);

        $totalhist = 0;
        $totalcorr = 0;
        $totaljuro = 0;
        $totalmult = 0;
        $totaldesc = 0;
        $totalgera = 0;

        $pdf->setfillcolor(245);

        $pdf->cell(6,04,"ANO"   ,1,0,"C",1);

        $pdf->cell(03,04,"P"    ,1,0,"C",1);
        $pdf->cell(03,04,"T"    ,1,0,"C",1);
        $pdf->cell(12,04,"OPER.",1,0,"C",1);
        $pdf->cell(12,04,"VENC.",1,0,"C",1);
        $pdf->cell(16,04,"ORIG.",1,0,"C",1);

        $pdf->cell(07,04,"REC."     ,1,0,"C",1);
        $pdf->cell(50,04,"DESCRIÇÃO",1,0,"C",1);

        $pdf->cell(12,04,"HIST."    ,1,0,"C",1);
        $pdf->cell(12,04,"CORR."    ,1,0,"C",1);
        $pdf->cell(12,04,"JUROS"    ,1,0,"C",1);
        $pdf->cell(12,04,"MULTA"    ,1,0,"C",1);

        if ($lDesconto) {
          $pdf->cell(12,04,"DESC."    ,1,0,"C",1);
        }

        $pdf->cell(12,04,"TOTAL"    ,1,1,"C",1);

        $pdf->setfillcolor(255,255,255);

        $sqlanos = "  select k22_ano,
                             k00_numpre,
                             k00_numpar,
                             k00_receit,
                             k00_numtot,
                             k00_dtoper,
                             k00_dtvenc,
                             k00_matric,
                             k00_inscr,
                             k00_numcgm,
                             k02_drecei,
                             k02_descr,
                             v07_parcel,
                             sum(substr(fc_calcula,2,13)::float8)  as k22_vlrhis,
                             sum(substr(fc_calcula,15,13)::float8) as k22_vlrcor,
                             sum(substr(fc_calcula,28,13)::float8) as k22_juros,
                             sum(substr(fc_calcula,41,13)::float8) as k22_multa,
                             sum(substr(fc_calcula,54,13)::float8) as k22_desconto,
                             sum((substr(fc_calcula,15,13)::float8+
                             substr(fc_calcula,28,13)::float8+
                             substr(fc_calcula,41,13)::float8-
                             substr(fc_calcula,54,13)::float8)) as k22_total
                        from ( select fc_arrecexerc(arrecad.k00_numpre, arrecad.k00_numpar ) as k22_ano,
                                      arrecad.k00_numpre,
                                      arrecad.k00_numpar,
                                      arrecad.k00_receit,
                                      arrecad.k00_numtot,
                                      arrecad.k00_dtoper,
                                      arrecad.k00_dtvenc,
                                      arrecad.k00_numcgm,
                                      arrematric.k00_matric,
                                      arreinscr.k00_inscr,
                                      tabrec.k02_drecei,
                                      tabrec.k02_descr,
                                      termo.v07_parcel,
                                      fc_calcula(arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_receit, '".date("Y-m-d",db_getsession("DB_datausu"))."', '".date("Y-m-d",db_getsession("DB_datausu"))."',".db_getsession("DB_anousu").")
                                 from notidebitos
                                      inner join arrecad  on arrecad.k00_numpre = k53_numpre and arrecad.k00_numpar = k53_numpar
                                      inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                                      inner join tabrec   on arrecad.k00_receit = k02_codigo
                                      left  join termo    on termo.v07_numpre   = arrecad.k00_numpre
                                      left  join arrematric on arrecad.k00_numpre = arrematric.k00_numpre
                                      left  join arreinscr  on arrecad.k00_numpre = arreinscr.k00_numpre
                                where k53_notifica = $notifica
                                order by 1 ) as x
                    group by k22_ano,
                             k00_numpre,
                             k00_numpar,
                             k00_receit,
                             k00_numtot,
                             k00_dtoper,
                             k00_dtvenc,
                             k00_matric,
                             k00_inscr,
                             k00_numcgm,
                             k02_drecei,
                             k02_descr,
                             v07_parcel
                    order by k22_ano,
                             k00_numpre,
                             k00_dtvenc";

        $rsQuery    = db_query($sqlanos);
        $aRegistros = db_utils::getCollectionByRecord($rsQuery);

        foreach ($aRegistros as $oReg) {

          if ($pdf->GetY() > 285) {
            $pdf->addpage();
          }

          $ori = trim($oReg->v07_parcel ? "P - {$oReg->v07_parcel}" :
                     ($oReg->k00_matric ? "M - {$oReg->k00_matric}" :
                     ($oReg->k00_inscr  ? "I - {$oReg->k00_inscr}"  : "C - {$oReg->k00_numcgm}"))
                     );

          $pdf->cell(06,04,$oReg->k22_ano,                             1,0,"C",0);

          $pdf->cell(03,04,trim($oReg->k00_numpar),                    1,0,"C",0); // P
          $pdf->cell(03,04,trim($oReg->k00_numtot),                    1,0,"C",0); // T
          $pdf->cell(12,04,db_formatar(trim($oReg->k00_dtoper),"d"),  1,0,"C",0);  // OPER
          $pdf->cell(12,04,db_formatar(trim($oReg->k00_dtvenc),"d"),  1,0,"C",0);  // VENC
          $pdf->cell(16,04,$ori, 1,0,"L",0); // ORIGEM

          $pdf->cell(07,04,trim($oReg->k00_receit),                    1,0,"C",0);
          $pdf->cell(50,04,trim($oReg->k02_drecei),                     1,0,"L",0);

          $pdf->cell(12,04,trim(db_formatar($oReg->k22_vlrhis,'f')),   1,0,"R",0);
          $pdf->cell(12,04,trim(db_formatar($oReg->k22_vlrcor,'f')),   1,0,"R",0);
          $pdf->cell(12,04,trim(db_formatar($oReg->k22_juros,'f')),    1,0,"R",0);
          $pdf->cell(12,04,trim(db_formatar($oReg->k22_multa,'f')),    1,0,"R",0);

          if ($lDesconto){
            $pdf->cell(12,04,trim(db_formatar($oReg->k22_desconto,'f')), 1,0,"R",0);
          }

          $pdf->cell(12,04,trim(db_formatar($oReg->k22_total,'f')),    1,1,"R",0);

          $totalhist += $oReg->k22_vlrhis;
          $totalcorr += $oReg->k22_vlrcor;
          $totaljuro += $oReg->k22_juros;
          $totalmult += $oReg->k22_multa;
          $totaldesc += $oReg->k22_desconto;
          $totalgera += $oReg->k22_total;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(06,04,"",0,0,"C",0);
        $pdf->cell(03,04,"",0,0,"C",0);
        $pdf->cell(03,04,"",0,0,"C",0);
        $pdf->cell(12,04,"",0,0,"C",0);
        $pdf->cell(12,04,"",0,0,"C",0);
        $pdf->cell(16,04,"",0,0,"C",0);

        $pdf->cell(07,04,"",0,0,"C",0);
        $pdf->cell(50,04,"",0,0,"C",0);

        $pdf->cell(12,04,trim(db_formatar($totalhist,'f')),1,0,"R",1);
        $pdf->cell(12,04,trim(db_formatar($totalcorr,'f')),1,0,"R",1);
        $pdf->cell(12,04,trim(db_formatar($totaljuro,'f')),1,0,"R",1);
        $pdf->cell(12,04,trim(db_formatar($totalmult,'f')),1,0,"R",1);

        if ($lDesconto){
          $pdf->cell(12,04,trim(db_formatar($totaldesc,'f')),1,0,"R",1);
        }

        $pdf->cell(12,04,trim(db_formatar($totalgera,'f')),1,1,"R",1);

        $pdf->setfillcolor(255,255,255);

        $pdf->cell(02,03,"",0,1,"C",1);
        $pdf->SetFont('Arial','',10);

      } elseif (strtoupper($db02_descr) == "NOTIFICACAO ASSINA") {

        if (isset($passou_por_aqui) && $passou_por_aqui==true){
          $ass_notifica = $texto;
        }else{
          $pdf->MultiCell(0,4+$db02_espaca,$texto,"0","$db02_alinhamento",0,$db02_inicia+0);
        }

      } elseif (strtoupper($db02_descr) == "TOTALPORANOCOMDESCONTO") {

        $passou_por_aqui = true;

      } elseif (strtoupper($db02_descr) == "TOTALPORANOCOMDESCONTOSEMTABELA") {

          $mostra_tabela_descontos = false;

      } elseif (strtoupper($db02_descr) == "TOTALPORANOCOMRECIBO") {

        $tottotal = 0;

        if ($somenteparc == false && $somenteiptu == false) {

          if (isset($notifparc) ){

            $sqlanos = " select k00_descr,
                                extract (year from k00_dtoper) as k22_ano,
                                sum(k43_vlrcor) as k22_vlrcor,
                                sum(k43_vlrjur) as k22_juros,
                                sum(k43_vlrmul) as k22_multa,
                                sum(k43_vlrdes) as k22_desconto,
                                sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                           from notidebitosreg
                          inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                             and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                             and arrecad.k00_receit = notidebitosreg.k43_receit
                          inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                          where k43_notifica = {$notifica}
                          group by k00_descr,
                                   extract (year from k00_dtoper)
                          order by extract (year from k00_dtoper)";

          } else {

            $sqlanos = "  select k00_descr,
                                 case when k22_exerc is null
                                      then extract (year from k22_dtoper) else k22_exerc
                                 end as k22_ano,
                                 sum(k22_vlrcor)   as k22_vlrcor,
                                 sum(k22_juros)    as k22_juros,
                                 sum(k22_multa)    as k22_multa,
                                 sum(k22_desconto) as k22_desconto,
                                 sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                            from notidebitos
                                 inner join debitos on k22_numpre = k53_numpre
                                                   and k22_numpar = k53_numpar
                                                   and k22_data = '$k60_datadeb'
                                 inner join arretipo on k00_tipo = k22_tipo
                                 {$sInnerArrecad}
                           where k53_notifica = $notifica
                           group by k00_descr,
                                    case when k22_exerc is null then extract (year from k22_dtoper) else k22_exerc end
                           order by case when k22_exerc is null then extract (year from k22_dtoper) else k22_exerc end";
          }

          $resultanos = db_query($sqlanos) or die($sqlanos);

          if ($resultanos == false) {

            $oParms           = new stdClass();
            $oParms->sSqlAnos = $sqlanos;
            $sMsg             = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_anos', $oParms);
            db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
            exit;
          }

          $totvlrcor = 0;
          $totjuros  = 0;
          $totmulta  = 0;
          $tottotal  = 0;
          $descranos = "";
          $descricao = "";

          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {

            db_fieldsmemory($resultanos,$totano);
            if ($descricao != $k00_descr) {
              $descranos .= $k00_descr.": ";
            }

            $descranos .= $k22_ano . ($totano != pg_numrows($resultanos) -1?",":".");

            $totvlrcor += $k22_vlrcor;

            $totjuros  += (($k22_juros/100)*10);
            $totmulta  += (($k22_multa/100)*10);

            $tottotal  += ($k22_total-(($k22_juros/100)*90)-(($k22_multa/100)*90));
          }

        } elseif ($somenteparc == false && $somenteiptu == true) {

          if (isset($notifparc) ){

            $sqlanos = " select extract (year from k00_dtoper) as k22_ano,
                                k43_numpar as k22_numpar,
                                arretipo.k00_descr,
                                count(*)
                           from notidebitosreg
                                inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                   and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                          where k43_notifica = {$notifica}
                          group by extract (year from k00_dtoper),
                                k43_numpar,
                                arretipo.k00_descr";

          } else {

            $sqlanos = "select case when k22_exerc is null
                                    then extract (year from k22_dtoper)
                                    else k22_exerc
                               end as k22_ano,
                               k22_numpar,
                               arretipo.k00_descr,
                               count(*)
                          from notidebitos
                               inner join debitos  on k22_numpre = k53_numpre
                                                  and k22_numpar = k53_numpar
                                                  and k22_data   = '$k60_datadeb'
                               inner join arretipo on k00_tipo = k22_tipo
                               {$sInnerArrecad}
                         where k53_notifica = $notifica
                         group by case when k22_exerc is null
                                       then extract (year from k22_dtoper)
                                       else k22_exerc end,
                                  k22_numpar,
                                  arretipo.k00_descr";
          }

          $resultanos = db_query($sqlanos) or die($sqlanos);
          if ($resultanos == false) {

            $oParms = new stdClass();
            $oParms->sSqlAnos = $sqlanos;
            $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_anos', $oParms);
            db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
            exit;
          }

          $descranos = "";
          $descricao = "";
          $relanos   = 0;
          $hifen     = "";

          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {

            db_fieldsmemory($resultanos, $totano);

            if ($descricao != $k00_descr) {

              $descranos .= ($descranos == ""?"":" / ") . $k00_descr . ": ";
              $descricao = $k00_descr;
            }

            if ($relanos != $k22_ano) {

              $descranos .= "\n".$k22_ano . "- PARC: ";
              $relanos = $k22_ano;
            }

            $descranos .= $hifen.$k22_numpar;
            $hifen = "-";
          }

        } else {

          if (isset($notifparc) ){

            $sqlanos = " select arretipo.k00_descr,
                                v07_parcel,
                                k43_numpar as k22_numpar,
                                count(*)
                           from notidebitosreg
                                inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                   and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                                inner join termo    on termo.v07_numpre   = arrecad.k00_numpre
                          where k43_notifica = {$notifica}
                          group by arretipo.k00_descr,
                                   v07_parcel,
                                   k43_numpar
                          order by arretipo.k00_descr,
                                   v07_parcel,
                                   k43_numpar ";

          } else {

            $sqlanos = " select arretipo.k00_descr,
                                v07_parcel,
                                k22_numpar,
                                count(*)
                           from notidebitos
                                inner join debitos  on k22_numpre = k53_numpre
                                                   and k22_numpar = k53_numpar
                                                   and k22_data   = '$k60_datadeb'
                                inner join arretipo on k00_tipo         = k22_tipo
                                inner join termo    on termo.v07_numpre = debitos.k22_numpre
                          where k53_notifica = $notifica
                            and exists ( select 1
                                           from arrecad
                                          where k00_numpre = k22_numpre
                                            and k00_numpar = k22_numpar limit 1)
                          group by arretipo.k00_descr,
                                   v07_parcel,
                                   k22_numpar
                          order by arretipo.k00_descr,
                                   v07_parcel,
                                   k22_numpar";
          }

          $resultanos = db_query($sqlanos) or die($sqlanos);
          if ($resultanos == false) {

            $oParms = new stdClass();
            $oParms->sSqlAnos = $sqlanos;
            $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_anos', $oParms);
            db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
            exit;
          }

          $descranos = "";
          $descricao = "";
          $parcel    = 0;
          $hifen     = "";

          for ($totano = 0; $totano < pg_numrows($resultanos); $totano++) {

            db_fieldsmemory($resultanos, $totano);

            if ($descricao != $k00_descr) {

              $descranos .= ($descranos == ""?"":"\n") . $k00_descr . ": ";
              $descricao  = $k00_descr;
            }

            if ($parcel != $v07_parcel) {

              $descranos .= "\n".$v07_parcel." - PARC: ";
              $parcel     = $v07_parcel;
              $hifen      = "";
            }

            $descranos .= $hifen.$k22_numpar;
            $hifen = "-";
          }
        }

        if (isset($notifparc)){

          $sqltipodebito = "  select distinct arretipo.k00_tipo as tipo_debito
                                from notidebitosreg
                                     inner join arrecad  on k00_numpre       = k43_numpre
                                                        and k00_numpar       = k43_numpar
                                     inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
                               where k43_notifica = {$notifica}
                               order by arretipo.k00_tipo";
        } else {

          $sqltipodebito = "  select distinct k22_tipo as tipo_debito
                                from notidebitos
                                     inner join debitos  on k22_numpre = k53_numpre
                                                        and k22_numpar = k53_numpar
                                                        and k22_data   = '$k60_datadeb'
                                     inner join arretipo on k00_tipo   = k22_tipo
                                     {$sInnerArrecad}
                               where k53_notifica = $notifica
                               order by k22_tipo";
        }

        $resulttipodebito = db_query($sqltipodebito) or die($sqltipodebito);

        if ($resulttipodebito == false) {

          $oParms = new stdClass();
          $oParms->sSqlTipoDebito = $sqltipodebito;
          $sMsg   = _M('tributario.notificacoes.cai2_emitenotif002.problema_ferar_select_recibo', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        db_fieldsmemory($resulttipodebito, 0);

        if (isset($notifparc)){

          $sqlrecibo  = "  select k53_numpre,                               ";
          $sqlrecibo .= "         k53_numpar,                               ";
          $sqlrecibo .= "         k22_tipo,                                 ";
          $sqlrecibo .= "         sum(k22_juros)   as k22_juros,            ";
          $sqlrecibo .= "         sum(k22_multa)   as k22_multa,            ";
          $sqlrecibo .= "         fc_calcula                                ";
          $sqlrecibo .= "    from ( select distinct                         ";
          $sqlrecibo .= "                  k43_numpre        as k53_numpre, ";
          $sqlrecibo .= "                  k43_numpar        as k53_numpar, ";
          $sqlrecibo .= "                  arretipo.k00_tipo as k22_tipo,   ";
          $sqlrecibo .= "                  k43_vlrjur        as k22_juros,  ";
          $sqlrecibo .= "                  k43_vlrmul        as k22_multa,  ";
          $sqlrecibo .= "                  fc_calcula(k43_numpre, k43_numpar, 0, '$db_datausu', '$db_datausu',".db_getsession("DB_anousu").")";
          $sqlrecibo .= "           from notidebitosreg                     ";
          $sqlrecibo .= "                inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre ";
          $sqlrecibo .= "                                   and arrecad.k00_numpar = notidebitosreg.k43_numpar ";
          $sqlrecibo .= "                inner join arretipo on arrecad.k00_tipo   = arretipo.k00_tipo ";
          $sqlrecibo .= "         where k43_notifica = {$notifica} ";
          $sqlrecibo .= ") as x group by k53_numpre, k53_numpar, k22_tipo, fc_calcula ";

        } else {

          $sqlrecibo  = "  select k53_numpre,                                                               ";
          $sqlrecibo .= "         k53_numpar,                                                               ";
          $sqlrecibo .= "         k22_tipo,                                                                 ";
          $sqlrecibo .= "         sum(k22_juros) as k22_juros,                                              ";
          $sqlrecibo .= "         sum(k22_multa) as k22_multa,                                              ";
          $sqlrecibo .= "         fc_calcula                                                                ";
          $sqlrecibo .= "    from ( select distinct                                                         ";
          $sqlrecibo .= "                  k53_numpre,                                                      ";
          $sqlrecibo .= "                  k53_numpar,                                                      ";
          $sqlrecibo .= "                  k22_tipo,                                                        ";
          $sqlrecibo .= "                  k22_juros,                                                       ";
          $sqlrecibo .= "                  k22_multa,                                                       ";
          $sqlrecibo .= "                  fc_calcula(k53_numpre, k53_numpar, 0, '$db_datausu', '$db_datausu',".db_getsession("DB_anousu").")";
          $sqlrecibo .= "             from notidebitos                                                      ";
          $sqlrecibo .= "                  inner join debitos  on k22_numpre         = k53_numpre           ";
          $sqlrecibo .= "                                     and k22_numpar         = k53_numpar           ";
          $sqlrecibo .= "                                     and k22_data           = '$k60_datadeb'       ";
          $sqlrecibo .= "                  inner join arretipo on arretipo.k00_tipo  = k22_tipo             ";
          $sqlrecibo .= "            where k53_notifica = {$notifica}                                       ";
          $sqlrecibo .= "              and exists ( select 1                                                ";
          $sqlrecibo .= "                             from arrecad                                          ";
          $sqlrecibo .= "                            where arrecad.k00_numpre = debitos.k22_numpre          ";
          $sqlrecibo .= "                              and arrecad.k00_numpar = debitos.k22_numpar limit 1) ";
          $sqlrecibo .= ") as x group by k53_numpre, k53_numpar, k22_tipo, fc_calcula                       ";
        }

        $resultrecibo = db_query($sqlrecibo) or die($sqlrecibo);
        if ($resultrecibo == false) {

          $oParms = new stdClass();
          $oParms->sSqlTipoDebito = $sqltipodebito;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_ferar_select_recibo', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        if (!isset($passou_por_aqui) || $passou_por_aqui==false){

          /**
           * Busca Desconto
           */
           $lProcessaLoteador = false;
           $loteador          = false;
           $sWhereLoteador    = " and k40_forma <> 3";

            if (isset($z01_numcgm)) {

              $sqlloteador    = "  select *                                                                  ";
              $sqlloteador   .= "    from loteam                                                             ";
              $sqlloteador   .= "         inner join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam ";
              $sqlloteador   .= "   where j120_cgm = {$z01_numcgm}                                           ";
              $resultloteador = db_query($sqlloteador) or die($sqlloteador);

            if (pg_numrows($resultloteador) > 0) {

              $lProcessaLoteador = true;
              $loteador          = true;
              $sWhereLoteador    = " and k40_forma = 3";
            }
          }

          $cadtipoparc = 0;

          $sqltipoparc = " select *
                             from tipoparc
                                  inner join cadtipoparc    on cadtipoparc = k40_codigo
                                  left  join cadtipoparcdeb on cadtipoparcdeb.k41_cadtipoparc = tipoparc.cadtipoparc
                            where maxparc = 1
                              and '".date("Y-m-d",db_getsession("DB_datausu")) . "' >= k40_dtini
                              and '".date("Y-m-d",db_getsession("DB_datausu")) . "' <= k40_dtfim
                              and ( case
                                      when cadtipoparcdeb.k41_cadtipoparc is not null
                                        then ( cadtipoparcdeb.k41_arretipo = $tipo_debito )
                                      else false
                                    end )
                              $sWhereLoteador ";

          $resulttipoparc = db_query($sqltipoparc);
          if (pg_numrows($resulttipoparc) > 0) {
            db_fieldsmemory($resulttipoparc,0);
          } else {
            $k40_todasmarc = false;
          }

          $sqltipoparcdeb    = "select * from cadtipoparcdeb limit 1";
          $resulttipoparcdeb = db_query($sqltipoparcdeb);
          $passar            = false;

          if (pg_numrows($resulttipoparcdeb) == 0) {
            $passar = true;
          } else {

            $sqltipoparcdeb = "select *
                                 from cadtipoparcdeb
                                where k41_cadtipoparc = $cadtipoparc
                                  and k41_arretipo = $tipo_debito ";
            $resulttipoparcdeb = db_query($sqltipoparcdeb);

            if (pg_numrows($resulttipoparcdeb) > 0) {
              $passar = true;
            }
         }

          // TOTALPORANO
          if ( isset($notifparc) )  {

            $sqlanos = "  select extract (year from k00_dtoper)        as k22_ano,
                                 sum(k43_vlrcor)                       as k22_vlrcor,
                                 sum(k43_vlrjur)                       as k22_juros,
                                 sum(k43_vlrmul)                       as k22_multa,
                                 sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                            from notidebitosreg
                                 inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                    and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                 inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                           where k43_notifica = $notifica
                        group by extract (year from k00_dtoper)";

          } else {

            $sqlanos = "  select case when k22_exerc is null
                                      then extract (year from k22_dtoper)
                                      else k22_exerc
                                 end                                 as k22_ano,
                                 sum(k22_vlrcor)                     as k22_vlrcor,
                                 sum(k22_juros)                      as k22_juros,
                                 sum(k22_multa)                      as k22_multa,
                                 sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                            from notidebitos
                                 inner join debitos  on k22_numpre = k53_numpre
                                                    and k22_numpar = k53_numpar
                                                    and k22_data   = '$k60_datadeb'
                                 inner join arretipo on k00_tipo  = k22_tipo
                                 {$sInnerArrecad}
                           where k53_notifica = $notifica
                group by case when k22_exerc is null
                              then extract (year from k22_dtoper)
                              else k22_exerc end";
          }

          $resultcompara1 = db_query($sqlanos) or die($sqlanos);

          if($matric != ''){

            $sqlanostipos  = "   select extract (year from k00_dtoper) as k22_ano,                        ";
            $sqlanostipos .= "          sum(k00_valor)                 as k00_valor                       ";
            $sqlanostipos .= "     from arrematric                                                        ";
            $sqlanostipos .= "          inner join arrecad  on arrematric.k00_numpre = arrecad.k00_numpre ";
            $sqlanostipos .= "          inner join arretipo on arretipo.k00_tipo     = arrecad.k00_tipo   ";
            $sqlanostipos .= "    where k00_matric = $matric                                              ";

            if(!isset($notifparc)){
              $sqlanostipos .= "    and k00_dtvenc < '$k60_datadeb' " . ($tipos == ""?"":" and arrecad.k00_tipo in ($tipos)") ." ";
            } else {
              $sqlanostipos .= "    and k00_dtvenc < '".date("Y-m-d",db_getsession('DB_datausu'))."' ";
            }
            $sqlanostipos .= "    group by extract (year from k00_dtoper)";

          }else if($inscr != ''){

            $sqlanostipos  = "   select extract (year from k00_dtoper) as k22_ano,                       ";
            $sqlanostipos .= "           sum(k00_valor)                as k00_valor                      ";
            $sqlanostipos .= "     from arreinscr                                                        ";
            $sqlanostipos .= "          inner join arrecad  on arreinscr.k00_numpre = arrecad.k00_numpre ";
            $sqlanostipos .= "          inner join arretipo on arretipo.k00_tipo    = arrecad.k00_tipo   ";
            $sqlanostipos .= "    where k00_inscr = $inscr                                               ";

            if(!isset($notifparc)){
              $sqlanostipos .= "    and k00_dtvenc < '$k60_datadeb' " . ($tipos == ""?"":" and arrecad.k00_tipo in ($tipos)") ." ";
            } else {
              $sqlanostipos .= "    and k00_dtvenc < '".date("Y-m-d",db_getsession('DB_datausu'))."' ";
            }
            $sqlanostipos .= "    group by extract (year from k00_dtoper)";

          }else{

            $sqlanostipos  = "   select extract (year from k00_dtoper) as k22_ano,                        ";
            $sqlanostipos .= "          sum(k00_valor)                 as k00_valor                       ";
            $sqlanostipos .= "     from arrenumcgm                                                        ";
            $sqlanostipos .= "          inner join arrecad  on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
            $sqlanostipos .= "          inner join arretipo on arretipo.k00_tipo     = arrecad.k00_tipo   ";
            $sqlanostipos .= "    where arrenumcgm.k00_numcgm = $numcgm                                   ";

            if(!isset($notifparc)){
              $sqlanostipos .= "    and k00_dtvenc < '$k60_datadeb' " . ($tipos == ""?"":" and arrecad.k00_tipo in ($tipos)") ." ";
            } else {
              $sqlanostipos .= "    and k00_dtvenc < '".date("Y-m-d",db_getsession('DB_datausu'))."' ";
            }
            $sqlanostipos .= "    group by extract (year from k00_dtoper)  ";
          }

          $resultcompara2 = db_query($sqlanostipos) or die($sqlanostipos);

          if ( pg_numrows($resulttipoparc) == 0 ||
               $passar == false                 ||
               ($k40_todasmarc == 't' ? pg_numrows($resultcompara1) <> pg_numrows($resultcompara2) : false) ) {
            $desconto = 0;
          } else {

            $desconto   = $k40_codigo;
            $rsDesconto = db_query(" select coalesce(descmul,1), coalesce(descjur,1) from tipoparc where cadtipoparc = $desconto and maxparc = 1");
            if (pg_numrows($rsDesconto) > 0) {
              db_fieldsmemory($rsDesconto,0);
            }
          }
        }

        $fc_numbco        = "";
        $k00_codbco       = 0;
        $k00_codage       = "";
        $tipo_arrecadacao = 0;
        $tipo_cobranca    = 0;

        $dt_venc  = substr($db_datausu,0,10);
        $tot_desc = 0;

        $lProcessaLoteador = false;
        $loteador          = false;
        $sWhereLoteador    = " and k40_forma <> 3";

        if (isset($z01_numcgm)) {

          $sqlloteador  = "  select *                                                                  ";
          $sqlloteador .= "    from loteam                                                             ";
          $sqlloteador .= "         inner join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam ";
          $sqlloteador .= "   where j120_cgm = {$z01_numcgm}                                           ";

          $resultloteador = db_query($sqlloteador) or die($sqlloteador);
          if (pg_numrows($resultloteador) > 0) {

            $lProcessaLoteador = true;
            $loteador          = true;
            $sWhereLoteador    = " and k40_forma = 3";
          }
        }

        try {

          /*
           * Rotinas :
           *   Notificações > Relatórios > Emite Notificações Parciais
           *   Notificações > Relatórios > Emite Notificações
           *   Contrib > Procedimentos > Gera Notificação de Contribuição
           */
          $oRecibo = new recibo(2, null, 27);
        } catch ( Exception $eException ) {
          db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
          exit;
        }

        for ($regrecibo = 0; $regrecibo < pg_numrows($resultrecibo); $regrecibo++) {

          db_fieldsmemory($resultrecibo, $regrecibo);

          if (isset($passou_por_aqui) && $passou_por_aqui==true) {

            $desconto = recibodesconto($k53_numpre, $k53_numpar, $k22_tipo, $k22_tipo, $sWhereLoteador, pg_numrows($resultrecibo), pg_numrows($resultrecibo), $dt_venc, $k60_tipo);
            if ($desconto !=0){

              $result_desc  = db_query("select * from tipoparc where cadtipoparc = $desconto and maxparc = 1");
              $numrows_desc = pg_numrows($result_desc);

              if ($numrows_desc > 0){

                db_fieldsmemory($result_desc,0);

                if (isset($descmul) && isset($descjur)) {

                  $juros     = substr($fc_calcula,28,13);
                  $multa     = substr($fc_calcula,41,13);
                  if ($lProcessaLoteador) {

                    $corrigido = substr($fc_calcula,15,13);
                    $tot_desc += round( ( ( $corrigido / 100.00 ) * $descvlr ),2);
                  }

                  $tot_desc += round(( ( $juros / 100.00 ) * $descjur ),2);
                  $tot_desc += round(( ( $multa / 100.00 ) * $descmul ),2);
                }
              }
            }
          }

          try {

            $oRecibo->addNumpre($k53_numpre,$k53_numpar);
            $oRecibo->setDescontoReciboWeb($k53_numpre,$k53_numpar,$desconto);
          } catch ( Exception $eException ) {

            db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
            exit;
          }

          try {
            $oRegraEmissao = new regraEmissao($k22_tipo,$iCadTipoMod,db_getsession("DB_instit"),date("Y-m-d",db_getsession("DB_datausu")),db_getsession("DB_ip"));
          } catch (Exception $eException){

            db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
            exit;
          }

          if ($oRegraEmissao->isCobranca()){
            $tipo_cobranca++;
          } else {
            $tipo_arrecadacao++;
          }
        }

        if ($tipo_arrecadacao > 0 && $tipo_cobranca > 0) {

          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.tipos_debitos_layout_diferentes');
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        db_inicio_transacao();

        try {

          $oRecibo->setDataRecibo($db_datausu);
          $oRecibo->setDataVencimentoRecibo($db_datausu);
          $oRecibo->emiteRecibo();
          $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

          $k03_numpre = $oRecibo->getNumpreRecibo();
        } catch ( Exception $eException ) {

          db_fim_transacao(true);
          db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
          exit;
        }

        db_fim_transacao();

        $sql = "select r.k00_numcgm,
                       r.k00_receit,
                       case when taborc.k02_codigo is null
                            then tabplan.k02_reduz
                            else taborc.k02_codrec
                       end as codreduz,
                       t.k02_descr,
                       t.k02_drecei,
                       r.k00_dtoper as k00_dtoper,
                       sum(r.k00_valor) as valor
                  from recibopaga r
                       inner join tabrec t on t.k02_codigo       = r.k00_receit
                       inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
                       left  join taborc   on t.k02_codigo       = taborc.k02_codigo
                                          and taborc.k02_anousu  = ".db_getsession("DB_anousu")."
                       left  join tabplan  on t.k02_codigo       = tabplan.k02_codigo
                                          and tabplan.k02_anousu = ".db_getsession("DB_anousu")."
                 where r.k00_numnov = ".$k03_numpre."
              group by r.k00_dtoper,
                       r.k00_receit,
                       t.k02_descr,
                       t.k02_drecei,
                       r.k00_numcgm, codreduz";

        $DadosPagamento = db_query($sql) or die($sql);

        /**
         * Efetua um somatorio do valor
         */
        $datavencimento = pg_result($DadosPagamento,0,"k00_dtoper");
        $total_recibo   = 0;
        for($i = 0;$i < pg_numrows($DadosPagamento);$i++) {
          $total_recibo += pg_result($DadosPagamento,$i,"valor");
        }

        $valordesconto = $tottotal;

        /**
         * Gerar codigo de barras e linha digitável
         */
        $NumBanco     = $numbanco;
        $taxabancaria = $tx_banc;
        $src          = $logo;
        $db_nomeinst  = $nomeinst;
        $db_ender     = $ender;
        $db_munic     = $munic;
        $db_cgc       = $cgc;
        $db_uf        = $uf;
        $db_telef     = $telef;
        $db_email     = $email;

        $total_recibo += $taxabancaria;

        if ( $total_recibo == 0 ){

          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.recibo_valor_zerado');
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
        }

        $valor_parm = $total_recibo;

        /**
         * Seleciona dados de identificacao. Verifica se é inscr ou matric e da o respectivo select
         * variavel vem do cai3_gerfinanc002.php, pelo window open, criada por parse_str
         */
        if ($k60_tipo == 'M'){

          $numero            = $matric;
          $tipoidentificacao = "Matricula: ";
          $sqlIdentificacao  = "select z01_cgmpri as z01_numcgm,
                                       z01_nome,
                                       z01_ender,
                                       z01_numero,
                                       z01_compl,
                                       z01_munic,
                                       z01_uf,
                                       z01_cep,
                                       nomepri,
                                       j39_compl,
                                       j39_numero,
                                       j13_descr,
                                       j34_setor||'.'||j34_quadra||'.'||j34_lote as sql,
                                       z01_cgccpf,
                                       proprietario.j06_setorloc,
                                       proprietario.j06_quadraloc,
                                       proprietario.j06_lote,
                                       proprietario.j05_descr,
                                       proprietario.pql_localizacao
                                  from proprietario
                                 where j01_matric = $numero
                                 limit 1";

          $Identificacao = db_query($sqlIdentificacao) or die($sqlIdentificacao);
          db_fieldsmemory($Identificacao,0);
          $nomepri       = ucwords(strtolower($nomepri));
          $j13_descr     = ucwords(strtolower($j13_descr));
          $ident_tipo_ii = 'Imóvel';

        } elseif ($k60_tipo == 'I') {

          $numero            = $q02_inscr;
          $tipoidentificacao = "Inscricao: ";
          $sqlidentificacao  = "select cgm.z01_numcgm,
                                       cgm.z01_nome,
                                       cgm.z01_ender,
                                       cgm.z01_numero,
                                       cgm.z01_compl,
                                       cgm.z01_munic,
                                       cgm.z01_uf,
                                       cgm.z01_cep,
                                       empresa.z01_ender as nomepri,
                                       empresa.z01_compl as j39_compl,
                                       empresa.z01_numero as j39_numero,
                                       empresa.z01_bairro as j13_descr,
                                       '' as sql,
                                       cgm.z01_cgccpf
                                  from issbase
                                       inner join empresa on issbase.q02_inscr  = empresa.q02_inscr
                                       inner join cgm     on issbase.q02_numcgm = cgm.z01_numcgm
                                 where issbase.q02_inscr = $numero";
          $Identificacao = db_query($sqlidentificacao) or die($sqlidentificacao);
          $ident_tipo_ii = 'Alvará';
          db_fieldsmemory($Identificacao,0);

        } else {

          $numero            = $cgm;
          $tipoidentificacao = "Numcgm: ";
          $sqlIdentificacao  = "select z01_nome,
                                       z01_ender,
                                       z01_numero,
                                       z01_compl,
                                       z01_munic,
                                       z01_uf,
                                       z01_cep,''::bpchar as nomepri,
                                       ''::bpchar         as j39_compl,
                                       ''::bpchar         as j39_numero,
                                       z01_bairro         as j13_descr,
                                       ''                 as sql,
                                       z01_cgccpf,
                                       z01_numcgm
                                  from cgm
                                 where z01_numcgm = $numero ";
          $Identificacao = db_query($sqlIdentificacao) or die($sqlIdentificacao);
          db_fieldsmemory($Identificacao,0);
          $ident_tipo_ii = '';
        }

        $histparcela = "";

        if($k03_tipo == 2){

          $sqlhist = "select distinct q01_anousu, k99_numpar
                        from db_reciboweb
                             inner join isscalc on q01_numpre = k99_numpre
                       where k99_numpre_n = $k03_numpre
                    group by q01_anousu, k99_numpar
                    order by q01_anousu, k99_numpar";

          $resulthist = db_query($sqlhist);

          if(pg_numrows($resulthist)!=false){

            $histparcela .= " - Parcela: ";
            $virgula      = "";
            for($xy=0;$xy<pg_numrows($resulthist);$xy++){

              $histparcela .= $virgula."".pg_result($resulthist,$xy,1);
              $virgula      = ",";
            }
          }
        }

        $k00_descr = "";

        if ($somenteparc == false && $somenteiptu == false) {
          $historico    = $descranos . $histparcela;
        } elseif ($somenteparc == false && $somenteiptu == true) {
          $historico    = $descranos . $histparcela;
        } else {
          $historico    = $descranos . $histparcela;
        }

        $db_vlrbar   = db_formatar(str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
        $db_numpre   = db_numpre($k03_numpre).'000';

        if (isset($notifparc)){

          $sqlvalor = "select k00_tercdigrecnormal, k00_msgrecibo
                         from arretipo
                        where k00_tipo = {$tipo_debito}
                        limit 1";

        } else {

          $sqlvalor = "select k00_tercdigrecnormal, k00_msgrecibo
                         from arretipo
                              inner join listatipos on k00_tipo = k62_tipodeb
                        where k62_lista = $lista
                        limit 1";
        }

        $resultvalor = db_query($sqlvalor) or die($sqlvalor);
        db_fieldsmemory($resultvalor,0);
        if(!isset($k00_tercdigrecnormal) || $k00_tercdigrecnormal == ""){

          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.configure_terceiro_digito');
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
        }

        try {
          $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k03_numpre,0,$total_recibo,$db_vlrbar,$datavencimento,$k00_tercdigrecnormal);

          if ($lConvenioCobrancaValido) {

            if (CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
              CobrancaRegistrada::registrarReciboWebservice($k03_numpre, $oRegraEmissao->getConvenio(), $total_recibo);
            } else {
              CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
            }
          }

        } catch (Exception $eExeption){

          db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
          exit;
        }

        $codigobarras   = $oConvenio->getCodigoBarra();
        $linhadigitavel = $oConvenio->getLinhaDigitavel();
        $datavencimento = db_formatar($datavencimento,"d");

        /**
         * Numpre formatado
         */
        $numpre = db_sqlformatar($k03_numpre,8,'0').'000999';
        $numpre = $numpre . db_CalculaDV($numpre,11);

        if (isset($passou_por_aqui)&&$passou_por_aqui==true && $mostra_tabela_descontos){

          $pdf->cell(0,05,"Valores para pagamento com descontos até: ".$datavencimento,0,1,"L",0);
          $pdf->setfillcolor(245);
          $pdf->cell(2,05,"",0,0,"C",0);
          $pdf->cell(15,05,"",       0,0,"C",0);
          $pdf->cell(25,05,"VLR ORIGINAL"  ,1,0,"C",1);
          $pdf->cell(30,05,"VLR CORRIGIDO",1,0,"C",1);
          $pdf->cell(25,05,"JUROS",          1,0,"C",1);
          $pdf->cell(25,05,"MULTA",          1,0,"C",1);

          if ($lDesconto){
            $pdf->cell(25,05,"DESCONTO",       1,0,"C",1);
          }

          $pdf->cell(30,05,"VLR TOTAL",      1,1,"C",1);
          $pdf->setfillcolor(255,255,255);

          $pdf->setfillcolor(245);
          $pdf->cell(2,05,"",0,0,"C",0);
          $pdf->cell(15,05,"",                               0,0,"L",0);
          $pdf->cell(25,05,trim(db_formatar(@$totvlrhis1,'f')),1,0,"R",0);
          $pdf->cell(30,05,trim(db_formatar(@$totvlrcor1,'f')),1,0,"R",0);

          $pdf->cell(25,05,trim(db_formatar( ( @$totjuros1 ),'f')) ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar( ( @$totmulta1 ),'f')) ,1,0,"R",0);

          if ($lDesconto) {
            $pdf->cell(25,05,trim(db_formatar($tot_desc ,'f')) ,1,0,"R",0);
          }

          @$total_correto = ( $tottotal1-$tot_desc );

          $pdf->cell(30,05,trim(db_formatar(( @$tottotal1-$tot_desc ) ,'f')) ,1,1,"R",0);

          $pdf->setfillcolor(255,255,255);
          $pdf->ln(2);

          $pdf->MultiCell(0,4,@$ass_notifica,"0","$db02_alinhamento",0,0);
        }

        $pdf->prefeitura    = $db_nomeinst;

        /**
         * Quando for convênio BDL de qualquer banco, sistema deve listar no boleto de Recibo (92) e Carnê (100)
         * o nome do cedente que constar como nome no cadastro de convênio.
         * Não deve usar o nome da instituição.
         */
        if ($oConvenio->getiCadTipoConvenio() == "1") {
            $pdf->prefeitura = $oRegraEmissao->getNomeConvenio();
        }

        $pdf->logo          = $src;
        $pdf->tipo_convenio = $oConvenio->getTipoConvenio();
        $pdf->enderpref     = $db_ender;
        $pdf->municpref     = $db_munic;
        $pdf->cgcpref       = $db_cgc;
        $pdf->telefpref     = $db_telef;
        $pdf->emailpref     = @$db_email;
        $pdf->nome          = trim(pg_result($Identificacao,0,"z01_nome"));
        $pdf->cgm           = trim(pg_result($Identificacao,0,"z01_numcgm"));
        $pdf->ender         = trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl"));
        $pdf->munic         = trim(pg_result($Identificacao,0,"z01_munic"));
        $pdf->cep           = trim(pg_result($Identificacao,0,"z01_cep"));
        $pdf->cgccpf        = trim(@pg_result($Identificacao,0,"z01_cgccpf"));
        $pdf->tipoinscr     = $tipoidentificacao;
        $pdf->nrinscr       = $numero;
        $pdf->ip            = db_getsession("DB_ip");
        $pdf->identifica_dados = $ident_tipo_ii;
        $pdf->tipolograd   = 'Logradouro:';
        $pdf->nomepri      = $nomepri;
        $pdf->tipocompl    = 'Número:';
        $pdf->nrpri        = $j39_numero;
        $pdf->complpri     = $j39_compl;
        $pdf->tipobairro   = 'Bairro:';
        $pdf->bairropri    = $j13_descr;
        $pdf->datacalc     = date('d-m-Y',$DB_DATACALC);
        $pdf->taxabanc     = db_formatar($taxabancaria,'f');
        $pdf->recorddadospagto = $DadosPagamento;
        $pdf->linhasdadospagto = pg_numrows($DadosPagamento);
        $pdf->receita      = 'k00_receit';
        $pdf->receitared   = 'codreduz';
        $pdf->dreceita     = 'k02_descr';
        $pdf->ddreceita    = 'k02_drecei';
        $pdf->valor        = 'valor';
        $pdf->historico    = $k00_descr;
        $pdf->historico    = $historico . PHP_EOL . $k00_msgrecibo;
        $pdf->historico    .= $pdf->tipoinscr . ' '. $pdf->nrinscr;
        $pdf->histparcel   = @$histparcela;
        $pdf->dtvenc       = $datavencimento;
        $pdf->numpre       = $numpre;

        $pdf->valtotal     = db_formatar(@$valor_parm,'f');

        $pdf->linhadigitavel  = $linhadigitavel;
        $pdf->codigobarras    = $codigobarras;
        $pdf->texto           = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();
        $pdf->descr3_1        = trim(pg_result($Identificacao,0,"z01_nome")); // contribuinte
        $pdf->descr3_2        = trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl"));// endereco
        $pdf->bairropri       = $j13_descr;    // municipio
        $pdf->munic           = trim(pg_result($Identificacao,0,"z01_munic"));    // bairro
        $pdf->cep             = trim(pg_result($Identificacao,0,"z01_cep"));
        $pdf->cgccpf          = trim(@pg_result($Identificacao,0,"z01_cgccpf"));
        $pdf->titulo5         = "";                 // titulo parcela
        $pdf->descr5          = "";                 // descr parcela
        $pdf->titulo8         = $tipoidentificacao;  // tipo de identificacao;
        $pdf->descr8          = $numero;            //descr matricula ou inscricao
        $pdf->descr4_1        = $historico;
        $pdf->descr4_2        = ""; // historico - linha 1
        $pdf->descr16_1       = "";
        $pdf->descr16_2       = "";
        $pdf->descr16_3       = "";
        $pdf->descr12_1       = "";
        $pdf->descr12_2       = "";
        $pdf->linha_digitavel = $linhadigitavel;
        $pdf->codigo_barras   = $codigobarras;
        $pdf->descr6          = $datavencimento;  // Data de Vencimento
        $pdf->descr7          = db_formatar(@$valor_parm,'f');  // qtd de URM ou valor

        if (strlen(trim($oConvenio->getConvenioCobranca())) == 7) {
           $pdf->descr9 = trim($oConvenio->getConvenioCobranca()) . str_pad($k03_numpre."00",10,0,STR_PAD_LEFT);
        } else {
           $pdf->descr9 = $oConvenio->getNossoNumero();
        }

        if($oRegraEmissao->isCobranca()){

          $pdf->especie= "R$";

          $pdf->agencia_cedente    = $oConvenio->getAgenciaCedente();
          $pdf->carteira           = $oConvenio->getCarteira();
          $pdf->descr11_1          = trim(pg_result($Identificacao,0,"z01_numcgm")) . "-" . trim(pg_result($Identificacao,0,"z01_nome"));
          $pdf->descr11_2          = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
          $pdf->descr11_3          = trim(pg_result($Identificacao,0,"z01_munic"));
          $pdf->tipo_exerc         = $notifica;
          $pdf->data_processamento = date('d/m/Y',db_getsession('DB_datausu'));
          $pdf->dtparapag          = $datavencimento;
          $pdf->descr10            = "1 / 1";
        }

        if ($tipo_arrecadacao > 0){

          if ($pdf->GetY() > 150) {

            $pdf->addpage();
            $pdf->setY(20);
          } else {
            $pdf->setY(155);
          }

          /**
           * Emissão do recibo
           */
          if (strpos($db02_texto, "imprimirdesconto") >= 0 && gettype(strpos($db02_texto, "imprimirdesconto")) != "boolean") {

            $pdf->ln();
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(2,05,"",0,0,"R",0);
            $pdf->cell(175,05,"SEU DÉBITO COM DESCONTO DE R$ " . trim(db_formatar($valordesconto, 'f')) . " FICARÁ EM R$ " . trim(db_formatar($tottotal - $valordesconto, 'f')),1,0,"R",0);
            $pdf->setY($pdf->GetY()+10);
          }

          $linha = $pdf->gety() + 30;
          $linha = $pdf->gety();
          $pdf->ln(5);
          $inirec=16;
          $fimrec=194;
          $pdf->line($inirec,$linha,$fimrec,$linha);
          $xlin = $linha + 20;
          $xcol = 18;
          for ($i = 0;$i < 2;$i++) {

            $pdf->setfillcolor(245);
            $pdf->roundedrect($xcol-2,$xlin-18,178,65,2,'DF','1234');
            $pdf->setfillcolor(255,255,255);
            $pdf->Setfont('Arial','B',11);
            $pdf->text(150,$xlin-13,'RECIBO VÁLIDO ATÉ: ');
            $pdf->text(145,$xlin-8,"VENCIMENTO: " . $pdf->datacalc);

            //Via
            $str_via   = 'Prefeitura';
            if( $i == 0 ){
              $str_via = 'Contribuinte';
            }

            $pdf->Setfont('Arial','B',8);
            $pdf->text(160,$xlin-1,($i+1).'ª Via '.$str_via );
            $pdf->Image('imagens/files/'.$pdf->logo,20,$xlin-17,12);
            $pdf->Setfont('Arial','B',9);
            $pdf->text(40,$xlin-15,$pdf->prefeitura);
            $pdf->Setfont('Arial','',9);
            $pdf->text(40,$xlin-11.5,$pdf->enderpref);
            $pdf->text(40,$xlin-8,$pdf->municpref);
            $pdf->text(40,$xlin-5,$pdf->telefpref);
            $pdf->text(40,$xlin-2,$pdf->emailpref);

            $pdf->Roundedrect($xcol,$xlin+2,$xcol+100,20,2,'DF','1234');
            $pdf->Setfont('Arial','',6);
            $pdf->text($xcol+2,$xlin+4,'Identificação:');
            $pdf->Setfont('Arial','',8);
            $pdf->text($xcol+2,$xlin+7,'Nome :');
            $pdf->text($xcol+17,$xlin+7,$pdf->cgm . " - " . $pdf->nome);
            $pdf->text($xcol+2,$xlin+11,'Endereço :');
            $pdf->text($xcol+17,$xlin+11,$pdf->ender);
            $pdf->text($xcol+2,$xlin+15,'Município :');
            $pdf->text($xcol+17,$xlin+15,trim($pdf->munic));
            $pdf->text($xcol+75,$xlin+15,'CEP :');
            $pdf->text($xcol+82,$xlin+15,$pdf->cep);
            $pdf->text($xcol+2,$xlin+19,'Data :');

            $pdf->text($xcol+17,$xlin+19, date("d-m-Y",db_getsession("DB_datausu")));

            $pdf->text($xcol+40,$xlin+19,'Hora: '.date("H:i:s"));

            $pdf->text($xcol+75,$xlin+19,'CNPJ/CPF:');
            $pdf->text($xcol+90,$xlin+19,db_formatar($pdf->cgccpf,(strlen($pdf->cgccpf)<12?'cpf':'cnpj')));

            $pdf->Setfont('Arial','',6);

            $pdf->Roundedrect($xcol+119,$xlin+2,56,20,2,'DF','1234');

            $pdf->text($xcol+120,$xlin+4,$pdf->identifica_dados);

            $pdf->text($xcol+120,$xlin+7,$pdf->tipoinscr);
            $pdf->text($xcol+137,$xlin+7,$pdf->nrinscr);
            $pdf->text($xcol+120,$xlin+11,$pdf->tipolograd);
            $pdf->text($xcol+137,$xlin+11,$pdf->nomepri);
            $pdf->text($xcol+120,$xlin+15,$pdf->tipocompl);
            $pdf->text($xcol+137,$xlin+15,$pdf->nrpri."      ".$pdf->complpri);
            $pdf->text($xcol+120,$xlin+19,$pdf->tipobairro);
            $pdf->text($xcol+137,$xlin+19,$pdf->bairropri);

            if ($i == 0) {

              $pdf->Roundedrect($xcol,$xlin+25,175,20,2,'DF','1234');
              $pdf->SetY($xlin+26);
              $pdf->SetX($xcol+3);
              $pdf->Setfont('Arial','',5);

              $sHistorico = 'HISTÓRICO :   ' . $pdf->historico;
              $iNumeroLinhas = $pdf->nbLines(110, $sHistorico);

              if ($iNumeroLinhas > 6) {

                $sNovoHistorico = '';
                for ($iCaracter = 0; $iCaracter < strlen($sHistorico); $iCaracter++) {

                  if ($pdf->nbLines(110, $sNovoHistorico . $sHistorico[$iCaracter]) <= 5) {
                    $sNovoHistorico .= $sHistorico[$iCaracter];
                  } else {

                    $sNovoHistorico .= " ...";
                    break;
                  }
                }

                $sHistorico = $sNovoHistorico;
              }

              $pdf->multicell(110,3,$sHistorico,0,"L");
              $pdf->SetX($xcol+3);

              $pdf->Setfont('Arial','',6);
              $pdf->setx(15);

              $pdf->Roundedrect(160,$xlin+30,32,10,2,'DF','1234');
              $pdf->Roundedrect(134,$xlin+30,25,10,2,'DF','1234');
              $pdf->text(164,$xlin+32,'Código de Arrecadação');
              $pdf->text(135,$xlin+32,'Valor a Pagar em R$');
              $pdf->setfont('Arial','',10);
              $pdf->text(162,$xlin+37,$pdf->numpre);
              $pdf->text(135,$xlin+37,$pdf->valtotal);

            } else {

              $pdf->Setfont('Arial','',6);
              $pdf->setx(15);

              $pdf->Roundedrect(161,$xlin+24,32,10,2,'DF','1234');
              $pdf->Roundedrect(135,$xlin+24,25,10,2,'DF','1234');
              $pdf->text(165,$xlin+26,'Código de Arrecadação');
              $pdf->text(138,$xlin+26,'Valor a Pagar em R$');
              $pdf->setfont('Arial','',10);
              $pdf->text(163,$xlin+31,$pdf->numpre);
              $pdf->text(138,$xlin+31,$pdf->valtotal);

              $pdf->SetFont('Arial','B',5);
              $pdf->text(137,$xlin+36,"A   U   T   E   N   T   I   C   A   Ç   Ã   O      M   E   C   Â   N   I   C   A");

              $pdf->setfillcolor(0,0,0);
              $pdf->SetFont('Arial','',4);
              $pdf->TextWithDirection(17.5,$xlin+28,$pdf->texto,'U'); // texto no canhoto do carne
              $pdf->setfont('Arial','',11);
              $pdf->text(19,$xlin+28,$pdf->linhadigitavel);

              $pdf->int25(19,$xlin+31,$pdf->codigobarras,15,0.341);

            }

            $xlin += 67;
          }

          /**
           * Fim emissão do recibo
           */
        } else if ($tipo_cobranca>0) {
  /**
           * Ficha de Compensação
           */
          if ($pdf->gety() > 190 ) {

            $pdf->Setfont('Arial','B',11);
            $pdf->ln(30);
            $pdf->cell(0,05,"ATENÇÃO: Recibo para pagamento na próxima página. ",0,1,"C",0);
            $pdf->addpage();
            $iQtdNotificoesGeradas++;
            $pdf = CabecNotif($pdf,0,$variavel);
            $pdf->Setfont('Arial','',11);
            $pdf->cell(0,05,"NOTIFICAÇÃO: $notifica/$lista ",0,1,"C",0);
          }

          $y  = 193;
          $x  = 5;

          $xx = 15;
          $yy = 0;

          $linha = $pdf->gety() + 30;
          $linha = $pdf->gety();
          $inirec= 16;
          $fimrec= 194;
          $xlin = $linha + 67;
          $xcol = 24;

          $pdf->SetDash(1,1);
          $pdf->Line($x+5,     $y-2, $xx+$x+183, $y-2); //horiz
          $pdf->SetDash();

          $pdf->Line($x+51,$y,$x+51,$y+9);  //vert
          $pdf->Line($x+65,$y,$x+65,$y+9);  //vert

          $pdf->SetLineWidth(0.4);

          $pdf->Line($x+10,     $y+9, $xx+$x+178, $y+9);   // horiz linha inicial superior
          $pdf->Line($x+10,     $y+9, $x+10,      $y+87);  // vert  linha inicial lateral esquerda
          $pdf->Line($xx+$x+178,$y+9, $xx+$x+178, $y+87);  // vart  linha inicial lateral esquerda

          $pdf->SetLineWidth(0.2);

          $pdf->Line($x+10,  $y+17, $xx+$x+178, $y+17); //horiz  2
          $pdf->Line($x+10,  $y+24, $xx+$x+178, $y+24); //horiz  3
          $pdf->Line($x+10,  $y+31, $xx+$x+178, $y+31); //horiz  4
          $pdf->Line($x+10,  $y+38, $xx+$x+178, $y+38); //horiz  5
          $pdf->Line($x+10,  $y+45, $xx+$x+178, $y+45); //horiz  5
          $pdf->Line($x+136, $y+52, $xx+$x+178, $y+52); //horiz  7
          $pdf->Line($x+136, $y+59, $xx+$x+178, $y+59); //horiz  8
          $pdf->Line($x+136, $y+66, $xx+$x+178, $y+66); //horiz  9
          $pdf->Line($x+10,  $y+73, $xx+$x+178, $y+73); //horiz 10

          $pdf->Line($x+136, $y+9,  $x+136, $y+87); //vert 2
          $pdf->Line($x+156, $y+9,  $x+156, $y+17); //vert linha vencimento

          $pdf->Line($x+34,  $y+31, $x+34,  $y+38); //vert
          $pdf->Line($x+73,  $y+31, $x+73,  $y+38); //vert
          $pdf->Line($x+99,  $y+31, $x+99,  $y+38); //vert
          $pdf->Line($x+112, $y+31, $x+112, $y+38); //vert

          // $pdf->Line($x+32,  $y+38, $x+32,  $y+45); //vert
          $pdf->Line($x+32,  $y+38, $x+32,  $y+45); //vert
          $pdf->Line($x+53,  $y+38, $x+53,  $y+45); //vert
          $pdf->Line($x+78,  $y+38, $x+78,  $y+45); //vert
          $pdf->Line($x+108, $y+38, $x+108, $y+45); //vert

          $pdf->SetLineWidth(0.4);
          $pdf->Line($x+10,     $y+87, $xx+$x+178, $y+87); //horiz ultima linha

          //codigo de barras
          $pdf->SetFillColor(0,0,0);

          if (@$pdf->codigo_barras != null) {
            $pdf->int25(20,$y+90,@$pdf->codigo_barras,13,0.3);
          }

          if (empty($pdf->numbanco)) {
            $pdf->numbanco = substr($pdf->linha_digitavel, 0, 3);
          }

          switch ($pdf->numbanco) {

            case '104': // CEF

              try {
                $sCaminhoLogo = cl_db_bancos::exportarLogoPorCodigoBanco($pdf->numbanco);
                $pdf->Image($sCaminhoLogo,$x+10,$y+1,30,7);
              } catch (Exception $e) {}
              $pdf->numbanco = "{$pdf->numbanco}-0";

              break;

            case '001': // BB

              $pdf->Image('imagens/files/bb.jpg',$x+10,$y+1,6,7);
              $pdf->Text($x+18,$y+7,"BANCO DO BRASIL");
              $pdf->numbanco = "{$pdf->numbanco}-9";
              break;

            case '041':

              try {
                $sCaminhoLogo = cl_db_bancos::exportarLogoPorCodigoBanco($pdf->numbanco);
                $pdf->Image($sCaminhoLogo,$x+10,$y+1,30,7);
              } catch (Exception $e) {}
              $pdf->numbanco = "{$pdf->numbanco}-0";
              break;
          }



          // quadrado inferior //

          $pdf->SetFont('Arial','b',12);
          $pdf->Text($x+52,  $y+7,@$pdf->numbanco);      // numero do banco
          $pdf->SetFont('Arial','b',12);
          if (@$pdf->linha_digitavel != null) {
            $pdf->Text($x+69,  $y-7,@$pdf->linha_digitavel);
            $pdf->Text($x+69,  $y+7,@$pdf->linha_digitavel);
          }
          $pdf->SetFont('Arial','b',5);
          $pdf->Text($x+12,   $y+11, "Local de Pagamento");
          $pdf->Text($x+138, $y+11, "Parcela");
          $pdf->Text($x+158, $y+11, "Vencimento");

          $pdf->Text($x+12,   $y+19, "Beneficiário");
          $pdf->text($x+103, $y+19, "CNPJ");
          $pdf->Text($x+138, $y+19, "Agência/Código Beneficiário");

          $pdf->Text($x+12,   $y+26, "Endereço do Beneficiário");
          $pdf->Text($x+138, $y+26, "Nosso Número");

          $pdf->Text($x+12,   $y+33, "Data do Documento");
          $pdf->Text($x+36,  $y+33, "Número do Documento");
          $pdf->Text($x+75,  $y+33, "Espécie Doc.");
          $pdf->Text($x+101, $y+33, "Aceite");
          $pdf->Text($x+114, $y+33, "Data do Processamento");

          $pdf->Text($x+12,  $y+40, "Uso do banco");
          $pdf->Text($x+34,  $y+40, "Carteira");
          $pdf->Text($x+54,  $y+40, "Espécie");
          $pdf->Text($x+80,  $y+40, "Quantidade");
          $pdf->Text($x+110, $y+40, "Valor");
          $pdf->Text($x+138, $y+33, "( = ) Valor do Documento");

          $pdf->Text($x+12,   $y+47,"TEXTO DE RESPONSABILIDADE DO BENEFICIÁRIO");
          $pdf->sety($y+48);
          $pdf->setx($x+11);
          $pdf->SetFont('Arial','',6);
          $pdf->multicell(120,2, $pdf->historico);
          $pdf->SetFont('Arial','b',6);
          $pdf->Text($x+138, $y+40,"( - ) Desconto / Abatimento");
          $pdf->Text($x+138, $y+47,"( - ) Outras Deduções");
          $pdf->Text($x+138, $y+54,"( + ) Mora / Multa");
          $pdf->Text($x+138, $y+61,"( + ) Outros Acréscimos");
          $pdf->Text($x+138, $y+68,"( = ) Valor Cobrado");
          $pdf->Text($x+12,   $y+75,"Pagador");
          $pdf->Text($x+12,   $y+85,"Pagador/Avalista");
          $pdf->SetFont('Arial','b',6);
          $pdf->Text($x+120, $y+90,"AUTENTICAÇÃO MECÂNICA / FICHA DE COMPENSAÇÃO");

          $pdf->especie_doc     = "RC";
          $pdf->aceite          = "N";
          $pdf->localpagamento  = " QUALQUER BANCO ATÉ O VENCIMENTO ";
          $pdf->SetFont('Arial','b',8);

          $pdf->Text($x+12,   $y+15,@$pdf->localpagamento);                    // local de pagamento
          $pdf->SetFont('Arial', '', 6);
          $pdf->Text($x+138, $y+15,@$pdf->descr10);                          // parcela $pdf->parcela);
          $pdf->Text($x+158, $y+15,@$pdf->dtparapag);                        // vencimento $pdf->dtvenc);
          $pdf->Text($x+12,  $y+23,@$pdf->prefeitura);                       // cedente
          $pdf->Text($x+103, $y+23,db_formatar($cgc,"cnpj"));                // cedente
          $pdf->SetFont('Arial', '', 10);
          $pdf->Text($x+138, $y+23,@$pdf->agencia_cedente);                  // agencia do cedente

          $endereco = $pdf->enderpref . ',' . $ender_numero . ' - ' . $pdf->municpref .'/'. $db_uf . ' - CEP: ' . $cep;

          $pdf->SetFont('Arial', '', 6);
          $pdf->Text($x+12, $y+30, $endereco);                    // agencia do cedente
          $pdf->SetFont('Arial', '', 10);
          $pdf->Text($x+12,  $y+37,date('d/m/Y'));                            // data do documento
          $pdf->Text($x+36,  $y+37,@substr($pdf->numpre, 0 , -4));              // numero do documento
          $pdf->Text($x+75,  $y+37,@$pdf->especie_doc);                        // especie do documento
          $pdf->Text($x+101, $y+37,@$pdf->aceite);                             // aceite
          $pdf->Text($x+114, $y+37,@$pdf->data_processamento);                 // data de opercao   data do processamento
          $pdf->SetFont('Arial', '', 6);
          $pdf->Text($x+138, $y+30,str_pad(@$pdf->descr9,17,"0",STR_PAD_LEFT));// nosso numero
          $pdf->Text($x+12, $y-7,str_pad(@$pdf->descr9,17,"0",STR_PAD_LEFT));  // nosso numero
          $pdf->SetFont('Arial', '', 10);
          // $pdf->Text($x+12,  $y+44,@$pdf->tipo_exerc);                        // codigo do cedente //
          $pdf->Text($x+12,  $y+44, '');                        // codigo do cedente //
          $pdf->Text($x+34,  $y+44,@$pdf->carteira);            // carteira
          $pdf->Text($x+54,  $y+44,@$pdf->especie);             // especie
          $pdf->Text($x+80,  $y+44,@$pdf->quantidade);          // quantidade
          $pdf->Text($x+110, $y+44,@$pdf->valorhis);            // valor

          $pdf->Text($x+138, $y+37,@trim($pdf->valtotal));                         // valor do documento

          $pdf->sety($y+42);
          $pdf->SetFont('Arial','',8);
          $pdf->setx(20);
          $pdf->multicell(130,3,@$pdf->descr12_1);
          $pdf->SetFont('Arial','',10);
          $pdf->Text($x+138, $y+47,@$pdf->desconto_abatimento);  // desconto/abatimento
          $pdf->Text($x+138, $y+55,@$pdf->outras_deducoes);      // outras deducoes
          $pdf->Text($x+138, $y+63,@$pdf->mora_multa);           // multa
          $pdf->Text($x+138, $y+71,@$pdf->outros_acrecimos);     // outros acrescimos
          $pdf->Text($x+138, $y+79,@$pdf->valor_cobrado);        // valor cobrado
          $pdf->SetFont('Arial','',8);


          $pdf->SetFont('Arial','',6);
          $pdf->text($x+90,  $y+78, 'CPF/CNPJ:');
          $pdf->text($x+102,  $y+78, db_formatar(@$pdf->cgccpf,(strlen(@$pdf->cgccpf)<12?'cpf':'cnpj')));
          $pdf->Text($x+12,  $y+78,@$pdf->descr11_1);            // $pdf->nome);    // sacado 1
          $pdf->Text($x+12,  $y+80,@$pdf->descr11_2);            // $pdf->ender);    // sacado 2
          $pdf->Text($x+12,  $y+82,trim(@$pdf->munic)." / RS / CEP-".$pdf->cep); // $pdf->munic);    // sacado 3
          $pdf->SetLineWidth(0.2);
        }

      } elseif (strtoupper($db02_descr) == "TOTALPORANO" && $somenteparc == true) {

        if ( isset($notifparc) )  {

          $sqlanos = "select v07_parcel,
                             count(*)          as k22_numpar,
                             sum(k22_vlrcor)   as k22_vlrcor,
                             sum(k22_juros)    as k22_juros,
                             sum(k22_multa)    as k22_multa,
                             sum(k22_desconto) as k22_desconto,
                             sum(k22_total)    as k22_total
                        from ( select v07_parcel,
                                      k43_numpar   as k22_numpar,
                                      sum(k43_vlrcor) as k22_vlrcor,
                                      sum(k43_vlrjur) as k22_juros,
                                      sum(k43_vlrmul) as k22_multa,
                                      sum(k43_vlrdes) as k22_desconto,
                                      sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                                 from notidebitosreg
                                      inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                         and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                                         and arrecad.k00_receit = notidebitosreg.k43_receit
                                      inner join termo    on termo.v07_numpre   = notidebitosreg.k43_numpre
                                where k43_notifica = {$notifica}
                                group by v07_parcel,
                                         k43_numpar ) as x
                       group by v07_parcel";

        } else {

          $sqlanos = "select  v07_parcel,
                              count(*)          as k22_numpar,
                              sum(k22_vlrcor)   as k22_vlrcor,
                              sum(k22_juros)    as k22_juros,
                              sum(k22_multa)    as k22_multa,
                              sum(k22_desconto) as k22_desconto,
                              sum(k22_total)    as k22_total
                         from ( select v07_parcel,
                                       k22_numpar,
                                       sum(k22_vlrcor)   as k22_vlrcor,
                                       sum(k22_juros)    as k22_juros,
                                       sum(k22_multa)    as k22_multa,
                                       sum(k22_desconto) as k22_desconto,
                                       sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                                  from notidebitos
                                       inner join debitos on k22_numpre = k53_numpre
                                                         and k22_numpar = k53_numpar
                                                         and k22_data   = '$k60_datadeb'
                                       {$sInnerArrecad}
                                       inner join arretipo on arretipo.k00_tipo   = k22_tipo
                                       inner join termo    on v07_numpre = k53_numpre
                                 where k53_notifica = $notifica
                                 group by v07_parcel,
                                          k22_numpar) as x
                        group by  v07_parcel";

        }

        $resultanos = db_query($sqlanos) or die($sql);
        if ($resultanos == false) {

          $oParms = new stdClass();
          $oParms->sSqlAnos = $sqlanos;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_anos', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(20,05,"PARCELAS",       1,0,"C",1);
        $pdf->cell(35,05,"PARCELAMENTO",   1,0,"C",1);
        $pdf->cell(30,05,"VLR CORRIGIDO",  1,0,"C",1);
        $pdf->cell(25,05,"JUROS",          1,0,"C",1);
        $pdf->cell(25,05,"MULTA",          1,0,"C",1);

        if ($lDesconto) {
          $pdf->cell(25,05,"DESCONTO",       1,0,"C",1);
        }

        $pdf->cell(25,05,"VLR TOTAL",    1,1,"C",1);
        $pdf->setfillcolor(255,255,255);

        for ($i = 0; $i < pg_num_rows($resultanos);$i++ ){

          db_fieldsmemory($resultanos, $i);
          $pdf->cell(20,05,$k22_numpar                         ,1,0,"C",0);
          $pdf->cell(35,05,$v07_parcel                         ,1,0,"C",0);
          $pdf->cell(30,05,trim(db_formatar($k22_vlrcor,'f'))  ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_juros,'f'))   ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_multa,'f'))   ,1,0,"R",0);

          if ($lDesconto) {
            $pdf->cell(25,05,trim(db_formatar($k22_desconto,'f')),1,0,"R",0);
          }

          $pdf->cell(25,05,trim(db_formatar($k22_total,'f'))   ,1,1,"R",0);
        }

      } elseif (strtoupper($db02_descr) == "TOTALGERALPORANO" && $somenteparc == false) {

        if (isset($notifparc)) {

          $sqlanostipos = " select extract (year from k00_dtoper) as k22_ano,
                                   sum(k43_vlrcor) as k22_vlrcor,
                                   sum(k43_vlrjur) as k22_juros,
                                   sum(k43_vlrmul) as k22_multa,
                                   sum(k43_vlrdes) as k22_desconto,
                                   sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                              from notidebitosreg
                                   inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                      and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                                      and arrecad.k00_receit = notidebitosreg.k43_receit
                                   inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                             where k43_notifica = {$notifica}
                             group by extract (year from k00_dtoper)
                             order by 1";

        } else {

          $sqlanostipos = "  select case when k22_exerc is null
                                         then extract (year from k22_dtoper)
                                         else k22_exerc
                                    end as k22_ano,
                                    sum(k22_vlrcor)   as k22_vlrcor,
                                    sum(k22_juros)    as k22_juros,
                                    sum(k22_multa)    as k22_multa,
                                    sum(k22_desconto) as k22_desconto,
                                    sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                               from debitos
                                    {$sInnerArrecad}
                                    inner join arretipo on k00_tipo = k22_tipo
                                    " . ($tipos == ""?"":" and k22_tipo in ($tipos)") .
                                    " and $xmatinsc22
                                    k22_data = '$k60_datadeb'
                              where k22_dtvenc < '$k60_datadeb'
                              group by case when k22_exerc is null
                                            then extract (year from k22_dtoper)
                                            else k22_exerc end
                              order by 1";
        }

        $resultanostipos = db_query($sqlanostipos);
        if ($resultanostipos == false) {

          $oParms = new stdClass();
          $oParms->sSqlAnosTipos = $sqlanostipos;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_anos_tipos', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        $pdf->cell(10,05,"",               0,0,"C",0);
        $pdf->setfillcolor(245);
        $pdf->cell(15,05,"ANO",            1,0,"C",1);
        $pdf->cell(40,05,"VALOR CORRIGIDO",1,0,"C",1);
        $pdf->cell(25,05,"JUROS",          1,0,"C",1);
        $pdf->cell(25,05,"MULTA",          1,0,"C",1);

        if ($lDesconto){
          $pdf->cell(25,05,"DESCONTO",       1,0,"C",1);
        }

        $pdf->cell(45,05,"VALOR TOTAL",    1,1,"C",1);
        $pdf->setfillcolor(255,255,255);

        $totvlrcor   = 0;
        $totjuros    = 0;
        $totmulta    = 0;
        $totdesconto = 0;
        $tottotal    = 0;

        for ($totano = 0; $totano < pg_numrows($resultanostipos); $totano++) {

          db_fieldsmemory($resultanostipos,$totano);
          $pdf->cell(10,05,"",                          0,0,"C",0);
          $pdf->cell(15,05,$k22_ano,                          1,0,"C",0);
          $pdf->cell(40,05,trim(db_formatar($k22_vlrcor,'f')),1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_juros,'f')) ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_multa,'f')) ,1,0,"R",0);

          if ($lDesconto){
            $pdf->cell(25,05,trim(db_formatar($k22_desconto,'f')) ,1,0,"R",0);
          }

          $pdf->cell(45,05,trim(db_formatar($k22_total,'f')) ,1,1,"R",0);

          $totvlrcor+=$k22_vlrcor;
          $totjuros+=$k22_juros;
          $totmulta+=$k22_multa;
          $totdesconto+=$k22_desconto;
          $tottotal+=$k22_total;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(25,05,"",                               0,0,"L",0);
        $pdf->cell(40,05,trim(db_formatar($totvlrcor,'f')),1,0,"R",1);
        $pdf->cell(25,05,trim(db_formatar($totjuros,'f')) ,1,0,"R",1);
        $pdf->cell(25,05,trim(db_formatar($totmulta,'f')) ,1,0,"R",1);

        if ($lDesconto){
          $pdf->cell(25,05,trim(db_formatar($totdesconto,'f')) ,1,0,"R",1);
        }

        $pdf->cell(45,05,trim(db_formatar($tottotal,'f')) ,1,1,"R",1);
        $pdf->setfillcolor(255,255,255);

      } elseif (strtoupper($db02_descr) == "TOTALGERALPORANO" && $somenteparc == true) {

        if (isset($notifparc) ){

          $sqlparcelas = "select v07_parcel,
                                 count(*)      as k22_numpar,
                                 sum(k22_vlrcor)   as k22_vlrcor,
                                 sum(k22_juros)    as k22_juros,
                                 sum(k22_multa)    as k22_multa,
                                 sum(k22_desconto) as k22_desconto,
                                 sum(k22_total)    as k22_total
                            from ( select v07_parcel,
                                          k43_numpar   as k22_numpar,
                                          sum(k43_vlrcor) as k22_vlrcor,
                                          sum(k43_vlrjur) as k22_juros,
                                          sum(k43_vlrmul) as k22_multa,
                                          sum(k43_vlrdes) as k22_desconto,
                                          sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                                     from notidebitosreg
                                          inner join arrecad    on arrecad.k00_numpre     = notidebitosreg.k43_numpre
                                                               and arrecad.k00_numpar     = notidebitosreg.k43_numpar
                                                               and arrecad.k00_receit     = notidebitosreg.k43_receit
                                          inner join termo      on termo.v07_numpre       = notidebitosreg.k43_numpre
                                    where k43_notifica = {$notifica}
                                    group by v07_parcel,
                                             k43_numpar ) as x
                           group by v07_parcel";

        } else {

          $sqlparcelas = " select v07_parcel,
                                  count(*) as k22_numpar,
                                  sum(k22_vlrcor) as k22_vlrcor,
                                  sum(k22_juros) as k22_juros,
                                  sum(k22_multa) as k22_multa,
                                  sum(k22_desconto) as k22_desconto,
                                  sum(k22_total) as k22_total
                             from ( select v07_parcel,
                                           k22_numpar,
                                           sum(k22_vlrcor) as k22_vlrcor,
                                           sum(k22_juros) as k22_juros,
                                           sum(k22_multa) as k22_multa,
                                           sum(k22_desconto) as k22_desconto,
                                           sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                                      from debitos
                                           inner join termo on v07_numpre  = k22_numpre
                                                           and k22_data    = '$k60_datadeb'
                                           inner join arretipo on k00_tipo = k22_tipo
                                           " . ($tipos == ""?"":" and k22_tipo in ($tipos)") .
                                           " and $xmatinsc22
                                           k22_data = '$k60_datadeb'
                                           {$sInnerArrecad}
                                     where k22_dtvenc < '$k60_datadeb'
                                     group by k22_numpar,
                                              v07_parcel) as x
                            group by v07_parcel";
        }

        $resultparcelas = db_query($sqlparcelas);
        if ($resultparcelas == false) {

          $oParms = new stdClass();
          $oParms->sSqlAnosTipos = $sqlanostipos;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_parcelas', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        if(pg_num_rows($resultparcelas) > 0){

          $pdf->setfillcolor(245);
          $pdf->cell(1,05,"",                0,0,"C",0);
          $pdf->cell(20,05,"PARCELAS",     1,0,"C",1);
          $pdf->cell(35,05,"PARCELAMENTO",   1,0,"C",1);
          $pdf->cell(30,05,"VLR CORRIGIDO",1,0,"C",1);
          $pdf->cell(25,05,"JUROS",          1,0,"C",1);
          $pdf->cell(25,05,"MULTA",          1,0,"C",1);

          if ($lDesconto) {
            $pdf->cell(25,05,"DESCONTO",       1,0,"C",1);
          }

          $pdf->cell(25,05,"VLR TOTAL",     1,1,"C",1);
          $pdf->setfillcolor(255,255,255);

          db_fieldsmemory($resultparcelas,0);

          $pdf->cell(1,05,"",                                    0,0,"C",0);
          $pdf->cell(20,05,$k22_numpar,                        1,0,"C",0);
          $pdf->cell(35,05,$v07_parcel,                        1,0,"C",0);
          $pdf->cell(30,05,trim(db_formatar($k22_vlrcor,'f'))   ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_juros,'f'))    ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_multa,'f'))    ,1,0,"R",0);

          if ($lDesconto) {
            $pdf->cell(25,05,trim(db_formatar($k22_desconto,'f')) ,1,0,"R",0);
          }

          $pdf->cell(25,05,trim(db_formatar($k22_total,'f'))    ,1,1,"R",0);
        }

      } elseif (strtoupper($db02_descr) == "TOTALPORANOEPROCEDENCIA") {

        if (isset($notifparc) ){

          $sqlanostiposdeb = " select extract (year from k00_dtoper) as k22_ano,
                                      v03_descr,
                                      sum(k43_vlrcor) as k22_vlrcor,
                                      sum(k43_vlrjur) as k22_juros,
                                      sum(k43_vlrmul) as k22_multa,
                                      sum(k43_vlrdes) as k22_desconto,
                                      sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                                 from notidebitosreg
                                      inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                         and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                                         and arrecad.k00_receit = notidebitosreg.k43_receit
                                      inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                                      inner join divida.divida on v01_numpre = arrecad.k00_numpre and
                                                                  v01_numpar = arrecad.k00_numpar
                                      inner join divida.proced on v01_proced = v03_codigo
                                where k43_notifica = {$notifica}
                                group by extract (year from k00_dtoper), v03_descr
                                order by extract (year from k00_dtoper)";

        } else {

          $sqlanostiposdeb = "   select case when k22_exerc is null
                                             then extract (year from k22_dtoper)
                                             else k22_exerc
                                        end as k22_ano,
                                        v03_descr,
                                        sum(k22_vlrcor) as k22_vlrcor,
                                        sum(k22_juros) as k22_juros,
                                        sum(k22_multa) as k22_multa,
                                        sum(k22_desconto) as k22_desconto,
                                        sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                                   from notidebitos
                                        inner join debitos  on k22_numpre = k53_numpre
                                                           and k22_numpar = k53_numpar
                                                           and k22_data   = '$k60_datadeb'
                                        {$sInnerArrecad}
                                        inner join arretipo on arretipo.k00_tipo   = k22_tipo
                                        inner join divida.divida on v01_numpre = debitos.k22_numpre and
                                                                    v01_numpar = debitos.k22_numpar
                                        inner join divida.proced on v01_proced = v03_codigo
                                  where k53_notifica = $notifica
                                  group by case when k22_exerc is null
                                                then extract (year from k22_dtoper)
                                                else k22_exerc end,
                                           v03_descr
                                  order by case when k22_exerc is null
                                                then extract (year from k22_dtoper)
                                                else k22_exerc end";
        }

        $resultanostiposdeb = db_query($sqlanostiposdeb);
        if ($resultanostiposdeb == false) {

          $oParms = new stdClass();
          $oParms->sSqlAnosTiposDeb = $sqlanostiposdeb;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_ano_tipo_debito', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(15,05,"ANO",      1,0,"C",1);
        $pdf->cell(40,05,"PROCEDENCIA", 1,0,"C",1);
        $pdf->cell(30,05,"VLR CORRIGIDO",1,0,"C",1);
        $pdf->cell(25,05,"JUROS",          1,0,"C",1);
        $pdf->cell(25,05,"MULTA",          1,0,"C",1);

        if ($lDesconto){
          $pdf->cell(25,05,"DESCONTO",          1,0,"C",1);
        }

        $pdf->cell(25,05,"VLR TOTAL",    1,1,"C",1);
        $pdf->setfillcolor(255,255,255);

        $totvlrcor   = 0;
        $totjuros    = 0;
        $totmulta    = 0;
        $totdesconto = 0;
        $tottotal    = 0;

        for ($totano = 0; $totano < pg_numrows($resultanostiposdeb); $totano++) {

          db_fieldsmemory($resultanostiposdeb,$totano);
          $pdf->cell(15,05,$k22_ano,                          1,0,"C",0);
          $pdf->SetFont('Arial','',7);
          $pdf->cell(40,05,$v03_descr,                  1,0,"C",0);
          $pdf->SetFont('Arial','',10);
          $pdf->cell(30,05,trim(db_formatar($k22_vlrcor,'f')),1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_juros,'f')) ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_multa,'f')) ,1,0,"R",0);

          if ($lDesconto){
            $pdf->cell(25,05,trim(db_formatar($k22_desconto,'f')) ,1,0,"R",0);
          }

          $pdf->cell(25,05,trim(db_formatar($k22_total,'f')) ,1,1,"R",0);

          $totvlrcor   += $k22_vlrcor;
          $totjuros    += $k22_juros;
          $totmulta    += $k22_multa;
          $totdesconto += $k22_desconto;
          $tottotal    += $k22_total;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(55,05,"",                               0,0,"L",0);
        $pdf->cell(30,05,trim(db_formatar($totvlrcor,'f')),1,0,"R",1);
        $pdf->cell(25,05,trim(db_formatar($totjuros,'f')) ,1,0,"R",1);
        $pdf->cell(25,05,trim(db_formatar($totmulta,'f')) ,1,0,"R",1);

        if ($lDesconto) {
          $pdf->cell(25,05,trim(db_formatar($totdesconto,'f')) ,1,0,"R",1);
        }

        $pdf->cell(25,05,trim(db_formatar($tottotal,'f')) ,1,1,"R",1);
        $pdf->setfillcolor(255,255,255);

      } elseif (strtoupper($db02_descr) == "TOTALPORANOETIPO") {

        if (isset($notifparc) ){

          $sqlanostiposdeb = " select extract (year from k00_dtoper) as k22_ano,
                                      k00_descr,
                                      sum(k43_vlrcor) as k22_vlrcor,
                                      sum(k43_vlrjur) as k22_juros,
                                      sum(k43_vlrmul) as k22_multa,
                                      sum(k43_vlrdes) as k22_desconto,
                                      sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                                 from notidebitosreg
                                      inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                                         and arrecad.k00_numpar = notidebitosreg.k43_numpar
                                                         and arrecad.k00_receit = notidebitosreg.k43_receit
                                      inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                                where k43_notifica = {$notifica}
                                group by extract (year from k00_dtoper), k00_descr
                                order by extract (year from k00_dtoper)";

        } else {

          $sqlanostiposdeb = "   select case when k22_exerc is null
                                             then extract (year from k22_dtoper)
                                             else k22_exerc
                                        end as k22_ano,
                                        k00_descr,
                                        sum(k22_vlrcor) as k22_vlrcor,
                                        sum(k22_juros) as k22_juros,
                                        sum(k22_multa) as k22_multa,
                                        sum(k22_desconto) as k22_desconto,
                                        sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                                   from notidebitos
                                        inner join debitos  on k22_numpre = k53_numpre
                                                           and k22_numpar = k53_numpar
                                                           and k22_data   = '$k60_datadeb'
                                        {$sInnerArrecad}
                                        inner join arretipo on arretipo.k00_tipo   = k22_tipo
                                  where k53_notifica = $notifica
                                  group by case when k22_exerc is null
                                                then extract (year from k22_dtoper)
                                                else k22_exerc end,
                                           k00_descr
                                  order by case when k22_exerc is null
                                                then extract (year from k22_dtoper)
                                                else k22_exerc end";
        }

        $resultanostiposdeb = db_query($sqlanostiposdeb);
        if ($resultanostiposdeb == false) {

          $oParms = new stdClass();
          $oParms->sSqlAnosTiposDeb = $sqlanostiposdeb;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_ano_tipo_debito', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(15,05,"ANO",            1,0,"C",1);
        $pdf->cell(40,05,"TIPO DE DEBITO", 1,0,"C",1);
        $pdf->cell(30,05,"VLR CORRIGIDO",  1,0,"C",1);
        $pdf->cell(25,05,"JUROS",          1,0,"C",1);
        $pdf->cell(25,05,"MULTA",          1,0,"C",1);

        if ($lDesconto){
          $pdf->cell(25,05,"DESCONTO",       1,0,"C",1);
        }

        $pdf->cell(25,05,"VLR TOTAL",      1,1,"C",1);
        $pdf->setfillcolor(255,255,255);

        $totvlrcor   = 0;
        $totjuros    = 0;
        $totmulta    = 0;
        $totdesconto = 0;
        $tottotal    = 0;

        for ($totano = 0; $totano < pg_numrows($resultanostiposdeb); $totano++) {

          db_fieldsmemory($resultanostiposdeb,$totano);
          $pdf->cell(15,05,$k22_ano,                          1,0,"C",0);
          $pdf->SetFont('Arial','',7);
          $pdf->cell(40,05,$k00_descr,                  1,0,"C",0);
          $pdf->SetFont('Arial','',10);
          $pdf->cell(30,05,trim(db_formatar($k22_vlrcor,'f')),1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_juros,'f')) ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_multa,'f')) ,1,0,"R",0);

          if ($lDesconto){
            $pdf->cell(25,05,trim(db_formatar($k22_desconto,'f')) ,1,0,"R",0);
          }

          $pdf->cell(25,05,trim(db_formatar($k22_total,'f')) ,1,1,"R",0);

          $totvlrcor   += $k22_vlrcor;
          $totjuros    += $k22_juros;
          $totmulta    += $k22_multa;
          $totdesconto += $k22_desconto;
          $tottotal    += $k22_total;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(55,05,"",                               0,0,"L",0);
        $pdf->cell(30,05,trim(db_formatar($totvlrcor,'f')),1,0,"R",1);
        $pdf->cell(25,05,trim(db_formatar($totjuros,'f')) ,1,0,"R",1);
        $pdf->cell(25,05,trim(db_formatar($totmulta,'f')) ,1,0,"R",1);

        if ($lDesconto){
          $pdf->cell(25,05,trim(db_formatar($totdesconto,'f')) ,1,0,"R",1);
        }

        $pdf->cell(25,05,trim(db_formatar($tottotal,'f')) ,1,1,"R",1);
        $pdf->setfillcolor(255,255,255);

      } elseif (strtoupper($db02_descr) == "TOTALPORANOEHISTORICO") {

        if (isset($notifparc)){

          $sqlanoshistdeb = " select extract (year from k00_dtoper) as k22_ano,
                                     k01_descr,
                                     sum(k43_vlrcor) as k22_vlrcor,
                                     sum(k43_vlrjur) as k22_juros,
                                     sum(k43_vlrmul) as k22_multa,
                                     sum(k43_vlrdes) as k22_desconto,
                                     sum(k43_vlrcor+k43_vlrjur+k43_vlrmul) as k22_total
                                from notidebitosreg
                               inner join arrecad  on arrecad.k00_numpre  = notidebitosreg.k43_numpre
                                                  and arrecad.k00_numpar  = notidebitosreg.k43_numpar
                               inner join arretipo on arretipo.k00_tipo   = arrecad.k00_tipo
                               inner join histcalc on histcalc.k01_codigo = arrecad.k00_hist
                               where k43_notifica = {$notifica}
                               group by extract (year from k00_dtoper), k01_descr
                               order by extract (year from k00_dtoper)";

        } else {

          $sqlanoshistdeb = "  select case when k22_exerc is null
                                           then extract (year from k22_dtoper)
                                           else k22_exerc
                                      end as k22_ano,
                                      k01_descr,
                                      sum(k22_vlrcor) as k22_vlrcor,
                                      sum(k22_juros) as k22_juros,
                                      sum(k22_multa) as k22_multa,
                                      sum(k22_desconto) as k22_desconto,
                                      sum(k22_vlrcor+k22_juros+k22_multa) as k22_total
                                 from notidebitos
                                      inner join debitos  on k22_numpre = k53_numpre
                                                         and k22_numpar = k53_numpar
                                                         and k22_data   = '$k60_datadeb'
                                      {$sInnerArrecad}
                                      inner join arretipo on arretipo.k00_tipo   = k22_tipo
                                      inner join histcalc on k01_codigo = k22_hist
                                where k53_notifica = $notifica
                                group by case when k22_exerc is null
                                              then extract (year from k22_dtoper)
                                              else k22_exerc end,
                                         k01_descr
                                order by case when k22_exerc is null
                                              then extract (year from k22_dtoper)
                                              else k22_exerc end";

        }

        $resultanoshistdeb = db_query($sqlanoshistdeb);
        if ($resultanoshistdeb == false) {

          $oParms = new stdClass();
          $oParms->sSqlAnosHistoricoDebitos = $sqlanoshistdeb;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.problema_gerar_totais_anos_historicos', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(15,05,"ANO",      1,0,"C",1);
        $pdf->cell(40,05,"HISTORICO",      1,0,"C",1);
        $pdf->cell(30,05,"VLR CORRIGIDO",  1,0,"C",1);
        $pdf->cell(25,05,"JUROS",          1,0,"C",1);
        $pdf->cell(25,05,"MULTA",          1,0,"C",1);

        if ($lDesconto){
          $pdf->cell(25,05,"DESCONTO",          1,0,"C",1);
        }

        $pdf->cell(25,05,"VLR TOTAL",      1,1,"C",1);
        $pdf->setfillcolor(255,255,255);

        $totvlrcor   = 0;
        $totjuros    = 0;
        $totmulta    = 0;
        $totdesconto = 0;
        $tottotal    = 0;

        for ($totano = 0; $totano < pg_numrows($resultanoshistdeb); $totano++) {

          db_fieldsmemory($resultanoshistdeb,$totano);
          $pdf->cell(15,05,$k22_ano,                          1,0,"C",0);
          $pdf->cell(40,05,$k01_descr,                  1,0,"C",0);
          $pdf->cell(30,05,trim(db_formatar($k22_vlrcor,'f')),1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_juros,'f')) ,1,0,"R",0);
          $pdf->cell(25,05,trim(db_formatar($k22_multa,'f')) ,1,0,"R",0);

          if ($lDesconto){
            $pdf->cell(25,05,trim(db_formatar($k22_desconto,'f')) ,1,0,"R",0);
          }

          $pdf->cell(25,05,trim(db_formatar($k22_total,'f')) ,1,1,"R",0);

          $totvlrcor   += $k22_vlrcor;
          $totjuros    += $k22_juros;
          $totmulta    += $k22_multa;
          $totdesconto += $k22_desconto;
          $tottotal    += $k22_total;
        }

        $pdf->setfillcolor(245);
        $pdf->cell(55,05,"",                               0,0,"L",0);
        $pdf->cell(30,05,trim(db_formatar($totvlrcor,'f')),1,0,"R",1);
        $pdf->cell(25,05,trim(db_formatar($totjuros,'f')) ,1,0,"R",1);
        $pdf->cell(25,05,trim(db_formatar($totmulta,'f')) ,1,0,"R",1);

        if ($lDesconto) {
          $pdf->cell(25,05,trim(db_formatar($totdesconto,'f')) ,1,0,"R",1);
        }

        $pdf->cell(25,05,trim(db_formatar($tottotal,'f')) ,1,1,"R",1);
        $pdf->setfillcolor(255,255,255);

      } elseif ( strtoupper($db02_descr) == "DATA" ) {

        $sqltexto    = "select munic, cgc from db_config where codigo = " . db_getsession("DB_instit");
        $resulttexto = db_query($sqltexto);
        db_fieldsmemory($resulttexto,0,true);
        $iDiaAtual   = date("d");
        $sMesAtual   = db_mes(date("m"));
        $iAnoAtual   = date("Y");
        $texto       = trim($munic) .', '.$iDiaAtual.' de '.$sMesAtual.' de ' . $iAnoAtual .'.';
        $pdf->MultiCell(0,4+$db02_espaca,$texto,"0","R",0,$db02_inicia+0);
        $pdf->Ln(1);

      } elseif ($db02_descr == "ASSINATURA") {

      	$nAssinaturaAlign = 140;
      	switch($db02_alinhamento) {

      		case "C":
      			$nAssinaturaAlign = 85;
      		break;

      		case "L":
      			$nAssinaturaAlign = 15;
      		break;
      	}

      	$pdf->Image('imagens/files/assinatura_notificacao.jpg',$nAssinaturaAlign,$posicao_assinatura,45);
        $pdf->sety($posicao_assinatura+43);
        $pdf->MultiCell(170,5,$texto,0,"R",0);

      } elseif (strtoupper($db02_descr) == "SEEDPORMATRICULA" && !$lSeedResumido) {

        $pdf->sety(190+35);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,"NOTIFICAÇÃO : ",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(50,5,db_formatar($notifica,'s','0',5,'e'),0,0,"L",0);
        $pdf->setfillcolor(245);
        $pdf->RoundedRect(5,225,145,29,0,'DF','1234');
        $pdf->SetFont('Arial','',12);
        $pdf->ln(0);
        $pdf->cell(40,5,"DESTINATÁRIO : ",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(100,5,$z01_nome,0,1,"L",0);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,"ENDEREÇO: ",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(50,5,trim($nomepri).", ".trim($j39_numero)."  ".trim(@$z39_compl),0,1,"L",0);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,($j13_descr == ""?"":"BAIRRO: "),0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(20,5,$j13_descr,0,1,"L",0);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,"MUNICÍPIO:",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(50,5,trim($munic) ."/".$uf . " - " . substr($cep,0,5)."-".substr($cep,5,3),0,1,"L",0);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,"NOTIFICAÇÃO: ",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(30,5,db_formatar($notifica,'s','0',5,'e'),0,0,"L",0);

        $pdf->SetFont('Arial','',12);
        if ($xcodigo == "k22_numcgm") {

          $pdf->cell(30,5,"CGM:",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
        } elseif ($xcodigo == "k22_matric") {

          $pdf->cell(30,5,"MATRÍCULA:",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
        } elseif ($xcodigo == "k22_inscr") {

          $pdf->cell(30,5,"INSCRIÇÃO:",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
        }

    } elseif (strtoupper($db02_descr) == "SEEDRESUMIDO") {

        $iAltura = 220;

        for ($iRepetirSeed = 0; $iRepetirSeed <= 1 ; $iRepetirSeed++) {

          $pdf->sety($iAltura);
          $pdf->setfillcolor(245);
          $pdf->RoundedRect(5,$iAltura,200,30,0,'DF','1234');
          $pdf->SetFont('Arial','',12);
          $pdf->ln(0);
          $pdf->cell(40,5,"DESTINATÁRIO : ",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(100,5,$z01_nome,0,1,"L",0);
          $pdf->SetFont('Arial','',12);
          $pdf->cell(40,5,"ENDEREÇO: ",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(50,5,trim($z01_ender).", ".trim($z01_numero)."  ".trim($z01_compl),0,1,"L",0);
          $pdf->SetFont('Arial','',12);
          $pdf->cell(40,5,($z01_bairro == ""?"":"BAIRRO: "),0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(20,5,$z01_bairro,0,1,"L",0);
          $pdf->SetFont('Arial','',12);
          $pdf->cell(40,5,"MUNICÍPIO:",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(50,5,trim($z01_munic) ."/".$z01_uf ,0,1,"L",0);
          $pdf->SetFont('Arial','',12);
          $pdf->cell(40,5,"CEP :",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(50,5,substr($z01_cep,0,5)."-".substr($z01_cep,5,3)." / Caixa Postal : ".$z01_cxpostal,0,1,"L",0);
          $pdf->SetFont('Arial','',12);

          $pdf->cell(40,5,"NOTIFICAÇÃO: ",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(30,5,db_formatar($notifica,'s','0',5,'e'),0,0,"L",0);

          $pdf->SetFont('Arial','',12);
          if ($xcodigo == "k22_numcgm") {

            $pdf->cell(30,5,"CGM:",0,0,"L",0);
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
          } elseif ($xcodigo == "k22_matric") {

            $pdf->cell(30,5,"MATRÍCULA:",0,0,"L",0);
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
          } elseif ($xcodigo == "k22_inscr") {

            $pdf->cell(30,5,"INSCRIÇÃO:",0,0,"L",0);
            $pdf->SetFont('Arial','B',12);
            $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
          }
          $iAltura = $iAltura + 33;
        }

      } elseif (strtoupper($db02_descr) == "SEED" && !$lSeedResumido) {

        $pdf->sety(190+35);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,"NOTIFICAÇÃO : ",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(50,5,db_formatar($notifica,'s','0',5,'e'),0,0,"L",0);
        $pdf->setfillcolor(245);
        $pdf->RoundedRect(5,225,145,29,0,'DF','1234');
        $pdf->SetFont('Arial','',12);
        $pdf->ln(0);
        $pdf->cell(40,5,"DESTINATÁRIO : ",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(100,5,$z01_nome,0,1,"L",0);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,"ENDEREÇO: ",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(50,5,trim($z01_ender).", ".trim($z01_numero)."  ".trim($z01_compl),0,1,"L",0);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,($z01_bairro == ""?"":"BAIRRO: "),0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(20,5,$z01_bairro,0,1,"L",0);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,"MUNICÍPIO:",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(50,5,trim($z01_munic) ."/".$z01_uf ,0,1,"L",0);
        $pdf->SetFont('Arial','',12);
        $pdf->cell(40,5,"CEP :",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(50,5,substr($z01_cep,0,5)."-".substr($z01_cep,5,3)." / Caixa Postal : ".$z01_cxpostal,0,1,"L",0);
        $pdf->SetFont('Arial','',12);

        $pdf->cell(40,5,"NOTIFICAÇÃO: ",0,0,"L",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(30,5,db_formatar($notifica,'s','0',5,'e'),0,0,"L",0);

        $pdf->SetFont('Arial','',12);
        if ($xcodigo == "k22_numcgm") {

          $pdf->cell(30,5,"CGM:",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
        } elseif ($xcodigo == "k22_matric") {

          $pdf->cell(30,5,"MATRÍCULA:",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
        } elseif ($xcodigo == "k22_inscr") {

          $pdf->cell(30,5,"INSCRIÇÃO:",0,0,"L",0);
          $pdf->SetFont('Arial','B',12);
          $pdf->cell(20,5,$$xcodigo1,0,1,"L",0);
        }

        $pdf->RoundedRect(150,190+35,55,67,0,'','1234');
        $pdf->SetXY(150,190+35);
        $pdf->SetFont('Arial','',8);
        $pdf->cell(55,5,"CARIMBO",0,0,"C",0);
        $pdf->SetXY(5,220+35);
        $pdf->SetFont('Arial','B',8);
        $pdf->cell(50,5,"Motivos da não entrega",1,0,"C",0);
        $pdf->SetFont('Arial','B',12);
        $pdf->cell(95,5,"Comprovante de Entrega",1,1,"C",0);
        $pdf->SetFont('Arial','',7);
        $pdf->cell(2,3,"",0,1,"L",0);
        $pdf->cell(20,5,"Mudou-se",0,0,"L",0);
        $pdf->RoundedRect(7,264,2,2,0,'DF','1234');
        $pdf->cell(2,5,"",0,0,"L",0);
        $pdf->cell(20,5,"Ausente",0,1,"L",0);
        $pdf->RoundedRect(29,264,2,2,0,'DF','1234');
        $pdf->cell(20,5,"Recusado",0,0,"L",0);
        $pdf->RoundedRect(7,269,2,2,0,'DF','1234');
        $pdf->cell(2,5,"",0,0,"L",0);
        $pdf->cell(20,5,"Não procurado",0,1,"L",0);
        $pdf->RoundedRect(29,269,2,2,0,'DF','1234');
        $pdf->cell(20,5,"Desconhecido",0,0,"L",0);
        $pdf->RoundedRect(7,239+35,2,2,0,'DF','1234');
        $pdf->cell(2,5,"",0,0,"L",0);
        $pdf->cell(20,5,"Falecido",0,1,"L",0);
        $pdf->RoundedRect(29,239+35,2,2,0,'DF','1234');
        $pdf->cell(20,5,"Não existe n" . chr(176),0,0,"L",0);
        $pdf->RoundedRect(7,244+35,2,2,0,'DF','1234');
        $pdf->cell(2,5,"",0,0,"L",0);
        $pdf->cell(20,5,"Outros",0,1,"L",0);
        $pdf->RoundedRect(29,244+35,2,2,0,'DF','1234');
        $pdf->cell(20,5,"Endereço insuficiente",0,0,"L",0);
        $pdf->RoundedRect(7,249+35,2,2,0,'DF','1234');
        $pdf->RoundedRect(5,220+35,50,37,0,'D','1234');
        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(57,225+38);
        $pdf->SetX(57);
        $pdf->cell(35,7,"Assinatura Recebedor: _____________________________________ ",0,1,"L",0);
        $pdf->SetX(57);
        $pdf->cell(35,7,"Nome legível: _____________________________________________",0,1,"L",0);
        $pdf->SetX(57);
        $pdf->cell(50,7,"CI : ____________________ ",0,0,"L",0);
        $pdf->cell(35,7,"Data : ______/______/_______ ",0,1,"L",0);
        $pdf->SetX(57);
        $pdf->cell(55,7,"Assinatura/ECT: ____________________",0,0,"L",0);
        $pdf->cell(15,7,"Matrícula : _____________",0,0,"L",0);
        $pdf->RoundedRect(55,220+35,95,37,0,'D','1234');

      } elseif (strtoupper($db02_descr) == "AR") {

        $iAltAR  = 210;
        $iFontAR = 3;

        $pdf->setfillcolor(255);

        $pdf->Image("imagens/files/logo_correios.jpg",15,$iAltAR,23,5);

        $pdf->SetFont('Arial','B',$iFontAR+14);
        $pdf->Text(40,$iAltAR+5,"AR");
        $pdf->SetFont('Arial','IB',$iFontAR+3);
        $pdf->Text(50,$iAltAR+2,"AVISO DE");
        $pdf->Text(50,$iAltAR+5,"RECEBIMENTO");

        $pdf->RoundedRect(15,$iAltAR+6,178,76,0,'DF','1234');

        $pdf->RoundedRect(15,$iAltAR+6 ,76,55,0,'DF','1234');
        $pdf->RoundedRect(91,$iAltAR+6 ,66,62,0,'DF','1234');
        $pdf->RoundedRect(15,$iAltAR+61,76, 7,0,'DF','1234');

        $pdf->setfillcolor(245);

        $pdf->RoundedRect(157,$iAltAR+6 ,36,51,0,'DF','1234');
        $pdf->RoundedRect(157,$iAltAR+56,36,26,0,'DF','1234');

        $pdf->RoundedRect(15,$iAltAR+68,110,7, 0,'DF','1234');
        $pdf->RoundedRect(15,$iAltAR+75,110,7, 0,'DF','1234');

        $pdf->RoundedRect(119,$iAltAR+68,38,7, 0,'DF','1234');
        $pdf->RoundedRect(119,$iAltAR+75,38,7, 0,'DF','1234');

        $pdf->setfillcolor(255);

        $pdf->SetFont('Arial','B',$iFontAR+3);
        $pdf->Text(17,$iAltAR+9,"DESTINATÁRIO :");

        $pdf->SetY($iAltAR+12);

        $sEnder = trim($z01_ender);

        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," ".trim($z01_nome)                         ,0,1,"L",0);
        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," ".trim($z01_ender)                        ,0,1,"L",0);
        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," ".trim($z01_bairro)                       ,0,1,"L",0);
        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," ".trim($z01_munic)." - ".trim($z01_uf)    ,0,1,"L",0);
        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," ".trim($z01_cep)." - ".trim($z01_cxpostal),0,1,"L",0);

        $pdf->Text(17,$iAltAR+44,"ENDEREÇO PARA DEVOLUÇÃO DO AR :");

        $pdf->SetY($iAltAR+47);
        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," ".trim($nomeinst)                                     ,0,1,"L",0);
        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," CNPJ: ".db_formatar($cgc,"cnpj")                      ,0,1,"L",0);
        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," ".trim($ender).", ".trim($numero)." - ".trim($bairro) ,0,1,"L",0);
        $pdf->Cell(5,3," ",0,0,"L",0);
        $pdf->Cell(75,3," ".trim($cep)." - ".trim($munic)." - ".trim($uf)       ,0,1,"L",0);

        $pdf->Text(93,$iAltAR+10,"TENTATIVAS DE ENTREGA");

        $pdf->Text(96,$iAltAR+16,"1º ______/______/______                 ______:______h");
        $pdf->Text(96,$iAltAR+23,"2º ______/______/______                 ______:______h");
        $pdf->Text(96,$iAltAR+30,"3º ______/______/______                 ______:______h");

        $pdf->Text(93,$iAltAR+36,"ATENÇÃO:");

        $pdf->SetFont('Arial','',$iFontAR+3);
        $pdf->Text(93,$iAltAR+40,"Após 3 (três) tentativas de entrega, devolver o objeto.");
        $pdf->Text(93,$iAltAR+44,"MOTIVO DA DEVOLUÇÃO");

        $pdf->SetXY(93,$iAltAR+48);
        $pdf->Cell(3,3,"1"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Mudou-se"             ,0,0,"L",0);
        $pdf->Cell(3,3,"5"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Recusado"             ,0,1,"L",0);
        $pdf->SetXY(93,$iAltAR+52);
        $pdf->Cell(3,3,"2"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Endereço Insuficiente",0,0,"L",0);
        $pdf->Cell(3,3,"6"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Não Procurado"        ,0,1,"L",0);
        $pdf->SetXY(93,$iAltAR+56);
        $pdf->Cell(3,3,"3"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Não Existe o Número"  ,0,0,"L",0);
        $pdf->Cell(3,3,"7"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Ausente"              ,0,1,"L",0);
        $pdf->SetXY(93,$iAltAR+60);
        $pdf->Cell(3,3,"4"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Desconhecido"         ,0,0,"L",0);
        $pdf->Cell(3,3,"8"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Falecido"             ,0,1,"L",0);
        $pdf->SetXY(93,$iAltAR+64);
        $pdf->Cell(3,3,"9"                     ,1,0,"C",0);
        $pdf->Cell(30,3,"Outros______________________________",0,1,"L",0);

        $pdf->SetFont('Arial','',$iFontAR+3);
        $pdf->Text(30,$iAltAR+36,"Nº DO REGISTRO E CÓDIGO DO CLIENTE ");

        $pdf->SetFont('Arial','',$iFontAR+1);
        $pdf->Text(17,$iAltAR+63,"DECLARAÇÃO DE CONTEÚDO (OPCIONAL)");
        $pdf->Text(17,$iAltAR+70,"ASSINATURA DO RECEBEDOR");
        $pdf->Text(17,$iAltAR+77,"NOME LEGÍVEL DO RECEBEDOR");

        $pdf->Text(121,$iAltAR+70,"DATA DE ENTREGA");
        $pdf->Text(121,$iAltAR+77,"Nº DOCUMENTO DE IDENTIDADE");

        $pdf->Text(172,$iAltAR+9,"CARIMBO");
        $pdf->Text(166,$iAltAR+11,"UNIDADE DE ENTREGA");

        $pdf->Text(160,$iAltAR+59,"RUBRICA E MATRÍCULA DO CARTEIRO");

        $pdf->SetFont('Arial','',$iFontAR+1);
        $pdf->Text(163,$iAltAR+5,"ESPAÇO RESERVADO A MENÇÃO MP");

      } else if (strtoupper($db02_descr) == "VERSO") {

        $pdf->AddPage();
        $iQtdNotificoesGeradas++;
        $opcoesverso = split("\n",$db02_texto);

        $remetente_parte1 = 0;
        $remetente_parte3 = 0;

        for ($xxx=0; $xxx < sizeof($opcoesverso); $xxx++) {

          $dadosopcoes = split("=", $opcoesverso[$xxx]);

          if ($dadosopcoes[0] == "parte1" && ( trim($dadosopcoes[1]) == "REMETENTE" ) ) {
            $remetente_parte1 = 1;
          }

          if ($dadosopcoes[0] == "parte3" && ( trim($dadosopcoes[1]) == "REMETENTE" ) ) {
            $remetente_parte3 = 1;
          }
        }

        for ($xxx=0; $xxx < sizeof($opcoesverso); $xxx++) {

          $dadosopcoes = split("=", $opcoesverso[$xxx]);

          if ($dadosopcoes[0] == "parte2") {

            if (trim($dadosopcoes[1]) == "ENDER_ENTREGA") {

              $S = $pdf->lMargin;
              $S = 10;
              global $variavel;
              $variavel = "";

              if(($imprimirtimbre == 1) || ($imprimirtimbre == 3)){
                $pdf = CabecNotif($pdf, 1, $variavel);
              }else{
                $pdf->ln(135);
              }

              $z01_ender = "";
              $z01_munic = "";

              if ($k60_tipo == 'M'){

                $sqlpropri = "select z01_nome,
                                     codpri,
                                     nomepri,
                                     j39_numero,
                                     j39_compl,
                                     j13_descr as z01_bairro,
                                     j34_setor,
                                     j34_quadra,
                                     j34_lote,
                                     j34_zona,
                                     j34_area,
                                     j01_tipoimp,
                                     proprietario.j06_setorloc,
                                     proprietario.j06_quadraloc,
                                     proprietario.j06_lote,
                                     proprietario.j05_descr,
                                     proprietario.pql_localizacao
                                from proprietario
                               where j01_matric = $matric";

                $resultpropri = db_query($sqlpropri);
                if (pg_numrows($resultpropri) > 0) {

                  db_fieldsmemory($resultpropri,0);
                  $nomepri    = ucwords(strtolower($nomepri));
                  $j13_descr  = ucwords(strtolower($j13_descr));
                }

                if ($tratamento == 0) {

                  $imprimedestinatario = $z01_nome;
                  $sqlender = "select z01_ender,
                                      z01_numero,
                                      z01_compl,
                                      z01_bairro,
                                      z01_munic,
                                      z01_uf,
                                      z01_cep,
                                      z01_cxpostal
                                 from cgm
                                where z01_numcgm = $cgm";
                  $resultender = db_query($sqlender);
                  db_fieldsmemory($resultender,0,true);
                  $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
                  $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;

                } else {

                  EnderecoNotif($tratamento, $matric, $z01_nome);
                  $imprimedestinatario =   $z01_destinatario;
                }

              } elseif ($k60_tipo == 'I') {

                $imprimedestinatario = $z01_nome;
                $sqlempresa = "select z01_ender,
                                      z01_numero,
                                      z01_compl,
                                      z01_bairro,
                                      z01_munic,
                                      z01_uf,
                                      z01_cep,
                                      z01_cxpostal
                                 from issbase
                                      inner join cgm on z01_numcgm = q02_numcgm
                                where q02_inscr = $q02_inscr";
                $resultempresa = db_query($sqlempresa);

                if (pg_numrows($resultempresa) > 0) {
                  db_fieldsmemory($resultempresa,0);
                }
                $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
                $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;

              } elseif ($k60_tipo == 'N' || $k60_tipo =='C') {

                $imprimedestinatario = $z01_nome;
                $sqlender = "select z01_ender,
                                    z01_numero,
                                    z01_compl,
                                    z01_bairro,
                                    z01_munic,
                                    z01_uf,
                                    z01_cep,
                                    z01_cxpostal
                               from cgm
                              where z01_numcgm = $cgm";
                $resultender = db_query($sqlender);
                db_fieldsmemory($resultender,0,true);
                $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
                $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep . "  " .$z01_cxpostal;
              }

              $oLibDocumento = new libdocumento(1702,null);
              if ( $oLibDocumento->lErro ) {

                $oParms = new stdClass();
                $oParms->sErro = $oLibDocumento->sMsgErro;
                $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.sem_documento_cadastrado', $oParms);
                db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
                exit;
              }

              $aParagrafos = $oLibDocumento->getDocParagrafos();
              foreach ($aParagrafos as $oParag) {
                eval($oParag->oParag->db02_texto);
              }

              if (file_exists('imagens/files/correios.jpg')) {
                $pdf->Image('imagens/files/correios.jpg',162,110,38);
              }

            } else {

              $pdf->setfillcolor(255);
              $pdf->Roundedrect(15,98,179,95,2,'DF','1234');

              $S = $pdf->lMargin;
              $S = 10;

              $pdf = CabecNotif($pdf, 1, "");

              $pdf->SetLeftMargin(45);

              $z01_ender = "";
              $z01_munic = "";

              $pdf->Roundedrect(40,135,142,27,2,'DF','1234');
              $pdf->ln(12);

              if ($k60_tipo == 'M') {

                $sqlpropri = "select z01_nome,
                                     codpri,
                                     nomepri,
                                     j39_numero,
                                     j39_compl,
                                     j13_descr as z01_bairro,
                                     j34_setor,
                                     j34_quadra,
                                     j34_lote,
                                     j34_zona,
                                     j34_area,
                                     j01_tipoimp ,
                                     proprietario.j06_setorloc,
                                     proprietario.j06_quadraloc,
                                     proprietario.j06_lote,
                                     proprietario.j05_descr,
                                     proprietario.pql_localizacao
                                from proprietario
                               where j01_matric = $matric";

                $resultpropri = db_query($sqlpropri);
                if (pg_numrows($resultpropri) > 0) {

                  db_fieldsmemory($resultpropri,0);
                  $nomepri    = ucwords(strtolower($nomepri));
                  $z01_bairro = ucwords(strtolower($z01_bairro));
                }

                if ($tratamento == 0) {

                  $pdf->cell(100,5,"DESTINATÁRIO : " . $z01_nome,0,1,"L",0);
                  $sqlender    = "select fc_iptuender($matric)";
                  $resultender = db_query($sqlender);
                  db_fieldsmemory($resultender,0);

                  $endereco = split("#", $fc_iptuender);

                  if (sizeof($endereco) < 7 || ereg_replace("[# 0]","",$fc_iptuender) == "") {

                     $sqlender = "select z01_ender,
                                         z01_numero,
                                         z01_compl,
                                         z01_bairro,
                                         z01_munic,
                                         z01_uf,
                                         z01_cep,
                                         z01_cxpostal
                                    from cgm
                                   where z01_numcgm = $cgm";
                     $resultender = db_query($sqlender);
                     db_fieldsmemory($resultender,0,true);
                     $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
                     $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep . "  ". $z01_cxpostal;

                  } else {

                    $z01_ender  = trim($endereco[0]) . "," . trim($endereco[1]) . " - " . trim($endereco[2]);
                    $z01_bairro = $endereco[3];
                    $z01_munic  = $endereco[4] . "/" . $endereco[5] . " - " .$endereco[6];
                  }

                } else {

                  if ($tratamento == 5 ) {

                    $sSqlEnder = "select z01_cgmpri as z01_numcgm,
                                         z01_nome,
                                         z01_ender,
                                         z01_numero,
                                         z01_compl,
                                         z01_munic,
                                         z01_uf,
                                         z01_cep,
                                         nomepri,
                                         j39_compl,
                                         j39_numero,
                                         j13_descr,
                                         j34_setor||'.'||j34_quadra||'.'||j34_lote as sql,
                                         z01_cgccpf,
                                         proprietario.j06_setorloc,
                                         proprietario.j06_quadraloc,
                                         proprietario.j06_lote,
                                         proprietario.j05_descr,
                                         proprietario.pql_localizacao
                                    from proprietario
                                   where j01_matric = $matric limit 1";

                       $rsEnder = db_query($sSqlEnder);
                       if (pg_numrows($rsEnder) > 0) {

                         db_fieldsmemory($rsEnder,0);
                         $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
                         $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;
                       } else{
                         EnderecoNotif($tratamento, $matric, $z01_nome);
                       }

                  } else if ($tratamento == 7 ) {

                    $sSqlEnder = "select proprietario.nomepri    as z01_ender,
                                         proprietario.j39_numero as z01_numero,
                                         proprietario.j39_compl  as z01_compl,
                                         proprietario.j13_descr  as z01_bairro,
                                         proprietario.j43_munic  as z01_munic,
                                         proprietario.j43_cep    as z01_cep,
                                         proprietario.j06_setorloc,
                                         proprietario.j06_quadraloc,
                                         proprietario.j06_lote,
                                         proprietario.j05_descr,
                                         proprietario.pql_localizacao
                                    from proprietario
                                         left join lote   on j01_idbql = j34_idbql
                                         left join bairro on j34_idbql = j13_codi
                                   where j01_matric = $matric limit 1";

                    $rsEnder = db_query($sSqlEnder) or die($sSqlEnderl);
                    if (pg_numrows($rsEnder) > 0) {

                       db_fieldsmemory($rsEnder,0);
                       $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
                       $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;
                    } else {
                      EnderecoNotif($tratamento, $matric, $z01_nome);
                    }

                  } else {

                     EnderecoNotif($tratamento, $matric, $z01_nome);
                     $pdf->cell(100,5,"DESTINATÁRIO : " . $z01_destinatario,0,1,"L",0);
                  }
                }

              } elseif ($k60_tipo == 'I') {

                $pdf->cell(100,5,"DESTINATÁRIO : " . $z01_nome,0,1,"L",0);
                $sqlempresa = "select z01_ender,
                                      z01_numero,
                                      z01_compl,
                                      z01_bairro,
                                      z01_munic,
                                      z01_uf,
                                      z01_cep,
                                      z01_cxpostal
                                 from issbase
                                      inner join cgm on z01_numcgm = q02_numcgm
                                where q02_inscr = $q02_inscr";
                $resultempresa = db_query($sqlempresa);
                if (pg_numrows($resultempresa) > 0) {
                  db_fieldsmemory($resultempresa,0);
                }
                $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
                $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;

              } elseif ($k60_tipo == 'N' || $k60_tipo='C') {

                $pdf->cell(100,5,"DESTINATÁRIO : " . $z01_nome,0,1,"L",0);
                $sqlender = "select z01_ender,
                                    z01_numero,
                                    z01_compl,
                                    z01_bairro,
                                    z01_munic,
                                    z01_uf,
                                    z01_cep,
                                    z01_cxpostal
                               from cgm
                              where z01_numcgm = $cgm";
                $resultender = db_query($sqlender);
                db_fieldsmemory($resultender,0,true);
                $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
                $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;

              }

              $pdf->cell(100,5,"ENDERECO: " . $z01_ender,0,1,"L",0);
              $pdf->cell(100,5,"BAIRRO: " . $z01_bairro,0,1,"L",0);
              $pdf->cell(100,5,"MUNICIPIO: " . $z01_munic,0,1,"L",0);
              $pdf->ln(5);

              $pdf->SetLeftMargin($S);

              for ($contamsg = 0; $contamsg < sizeof($opcoesverso); $contamsg++) {

                $msgparte2 = split("=", $opcoesverso[$contamsg]);
                if ($msgparte2[0] == "msgparte2") {

                  $parte2 = split("\|", $msgparte2[1]);
                  for ($contaparte2 = 0; $contaparte2 < sizeof($parte2 ); $contaparte2++) {
                    $pdf->cell(190,5,$parte2[$contaparte2],0,1,"C",0);
                  }
                  break;
                }
              }

              if (file_exists('imagens/files/correios.jpg')) {
                $pdf->Image('imagens/files/correios.jpg',162,100,38);
              }

            }//fecha o else

          } elseif ($dadosopcoes[0] == "parte3" || ( $remetente_parte3 == 1 ) ) {

            if (trim($dadosopcoes[1]) == "AR" && $dadosopcoes[0] == "parte3") {

              $iAltAR  = 210;
              $iFontAR = 3;

              $pdf->setfillcolor(255);

              $pdf->Image("imagens/files/logo_correios.jpg",15,$iAltAR,23,5);

              $pdf->SetFont('Arial','B',$iFontAR+14);
              $pdf->Text(40,$iAltAR+5,"AR");
              $pdf->SetFont('Arial','IB',$iFontAR+3);
              $pdf->Text(50,$iAltAR+2,"AVISO DE");
              $pdf->Text(50,$iAltAR+5,"RECEBIMENTO");

              $pdf->RoundedRect(15,$iAltAR+6,178,76,0,'DF','1234');

              $pdf->RoundedRect(15,$iAltAR+6 ,76,55,0,'DF','1234');
              $pdf->RoundedRect(91,$iAltAR+6 ,66,62,0,'DF','1234');
              $pdf->RoundedRect(15,$iAltAR+61,76, 7,0,'DF','1234');

              $pdf->setfillcolor(245);

              $pdf->RoundedRect(157,$iAltAR+6 ,36,51,0,'DF','1234');
              $pdf->RoundedRect(157,$iAltAR+56,36,26,0,'DF','1234');

              $pdf->RoundedRect(15,$iAltAR+68,110,7, 0,'DF','1234');
              $pdf->RoundedRect(15,$iAltAR+75,110,7, 0,'DF','1234');

              $pdf->RoundedRect(119,$iAltAR+68,38,7, 0,'DF','1234');
              $pdf->RoundedRect(119,$iAltAR+75,38,7, 0,'DF','1234');

              $pdf->setfillcolor(255);

              $pdf->SetFont('Arial','B',$iFontAR+3);
              $pdf->Text(17,$iAltAR+9,"DESTINATÁRIO :");

              $pdf->SetY($iAltAR+12);

              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," ".trim($z01_nome)                     , 0,1,"L",0);
              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," ".trim($z01_ender)                    , 0,1,"L",0);
              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," ".trim($z01_bairro)                   , 0,1,"L",0);
              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," ".trim($z01_munic)." - ".trim($z01_uf), 0,1,"L",0);
              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," ".trim($z01_cep)                      , 0,1,"L",0);

              $pdf->Text(17,$iAltAR+44,"ENDEREÇO PARA DEVOLUÇÃO DO AR :");

              $pdf->SetY($iAltAR+47);
              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," ".trim($nomeinst)                                     ,0,1,"L",0);
              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," CNPJ: ".db_formatar($cgc,"cnpj")                      ,0,1,"L",0);
              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," ".trim($ender).", ".trim($numero)." - ".trim($bairro) ,0,1,"L",0);
              $pdf->Cell(5,3," ",0,0,"L",0);
              $pdf->Cell(75,3," ".trim($cep)." - ".trim($munic)." - ".trim($uf)       ,0,1,"L",0);

              $pdf->Text(93,$iAltAR+10,"TENTATIVAS DE ENTREGA");

              $pdf->Text(96,$iAltAR+16,"1º ______/______/______                 ______:______h");
              $pdf->Text(96,$iAltAR+23,"2º ______/______/______                 ______:______h");
              $pdf->Text(96,$iAltAR+30,"3º ______/______/______                 ______:______h");

              $pdf->Text(93,$iAltAR+36,"ATENÇÃO:");

              $pdf->SetFont('Arial','',$iFontAR+3);
              $pdf->Text(93,$iAltAR+40,"Após 3 (três) tentativas de entrega, devolver o objeto.");
              $pdf->Text(93,$iAltAR+44,"MOTIVO DA DEVOLUÇÃO");

              $pdf->SetXY(93,$iAltAR+48);
              $pdf->Cell(3,3,"1"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Mudou-se"             ,0,0,"L",0);
              $pdf->Cell(3,3,"5"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Recusado"             ,0,1,"L",0);
              $pdf->SetXY(93,$iAltAR+52);
              $pdf->Cell(3,3,"2"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Endereço Insuficiente",0,0,"L",0);
              $pdf->Cell(3,3,"6"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Não Procurado"        ,0,1,"L",0);
              $pdf->SetXY(93,$iAltAR+56);
              $pdf->Cell(3,3,"3"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Não Existe o Número"  ,0,0,"L",0);
              $pdf->Cell(3,3,"7"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Ausente"              ,0,1,"L",0);
              $pdf->SetXY(93,$iAltAR+60);
              $pdf->Cell(3,3,"4"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Desconhecido"         ,0,0,"L",0);
              $pdf->Cell(3,3,"8"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Falecido"             ,0,1,"L",0);
              $pdf->SetXY(93,$iAltAR+64);
              $pdf->Cell(3,3,"9"                     ,1,0,"C",0);
              $pdf->Cell(30,3,"Outros______________________________",0,1,"L",0);

              $pdf->SetFont('Arial','',$iFontAR+3);
              $pdf->Text(30,$iAltAR+36,"Nº DO REGISTRO E CÓDIGO DO CLIENTE ");

              $pdf->SetFont('Arial','',$iFontAR+1);
              $pdf->Text(17,$iAltAR+63,"DECLARAÇÃO DE CONTEÚDO (OPCIONAL)");
              $pdf->Text(17,$iAltAR+70,"ASSINATURA DO RECEBEDOR");
              $pdf->Text(17,$iAltAR+77,"NOME LEGÍVEL DO RECEBEDOR");

              $pdf->Text(121,$iAltAR+70,"DATA DE ENTREGA");
              $pdf->Text(121,$iAltAR+77,"Nº DOCUMENTO DE IDENTIDADE");

              $pdf->Text(172,$iAltAR+9,"CARIMBO");
              $pdf->Text(166,$iAltAR+11,"UNIDADE DE ENTREGA");

              $pdf->Text(160,$iAltAR+59,"RUBRICA E MATRÍCULA DO CARTEIRO");

              $pdf->SetFont('Arial','',$iFontAR+1);
              $pdf->Text(163,$iAltAR+5,"ESPAÇO RESERVADO A MENÇÃO MP");

            } else {

              if ( $remetente_parte1 == 1 ) {
                $pdf->sety(8);
              } else {
                $pdf->sety(227);
              }
              $pdf->setfillcolor(245);

              if ( $remetente_parte1 == 1 ) {
                 $diminui=218;
              } else {
                 $diminui=0;
              }

              $pdf->RoundedRect(15,225-$diminui,133,29,0,'DF','1234');

              $pdf->SetLeftMargin($S);
              $pdf->SetFont('Arial','',10);
              $pdf->ln(0);
              $pdf->cell(5,2,"",0,0,"L",0);
              $pdf->cell(30,4,"REMETENTE: ",0,0,"L",0);
              $pdf->SetFont('Arial','B',10);
              $pdf->cell(100,4,$nomeinst,0,1,"L",0);
              $pdf->SetFont('Arial','',10);
              $pdf->cell(5,4,"",0,0,"L",0);
              $pdf->cell(30,4,"ENDEREÇO: ",0,0,"L",0);
              $pdf->SetFont('Arial','B',10);

              if (!isset($ender) || empty($ender)) {
                $pdf->cell(50,4,strtoupper(trim($z01_ender)) . ", " . $z01_numero,0,1,"L",0);
              } else {
                $pdf->cell(50,4,strtoupper(trim(@$ender.", ".$ender_numero)),0,1,"L",0);
              }

              $pdf->SetFont('Arial','',10);
              $pdf->cell(5,4,"",0,0,"L",0);
              $pdf->cell(30,4,($bairro == ""?"":"BAIRRO: "),0,0,"L",0);
              $pdf->SetFont('Arial','B',10);
              $pdf->cell(20,4,$bairro,0,1,"L",0);

              $pdf->SetFont('Arial','',10);
              $pdf->cell(5,4,"",0,0,"L",0);
              $pdf->cell(30,4,"MUNICIPIO:",0,0,"L",0);
              $pdf->SetFont('Arial','B',10);
              $pdf->cell(50,4,trim($munic) ."/".$uf . " - " . substr($cep,0,5)."-".substr($cep,5,3),0,1,"L",0);

              $pdf->SetFont('Arial','',10);
              $pdf->cell(5,4,"",0,0,"L",0);
              $pdf->cell(30,4,"NOTIFICAÇÃO: ",0,0,"L",0);
              $pdf->SetFont('Arial','B',10);
              $pdf->cell(30,4,db_formatar($notifica,'s','0',5,'e'),0,0,"L",0);

              $pdf->SetFont('Arial','',10);
              if ($xcodigo == "k22_numcgm") {

                $pdf->cell(30,4,"CGM:",0,0,"L",0);
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(20,4,$$xcodigo1,0,1,"L",0);
              } elseif ($xcodigo == "k22_matric") {

                $pdf->cell(30,4,"MATRÍCULA:",0,0,"L",0);
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(20,4,$$xcodigo1,0,1,"L",0);
              } elseif ($xcodigo == "k22_inscr") {

                $pdf->cell(30,4,"INSCRIÇÃO:",0,0,"L",0);
                $pdf->SetFont('Arial','B',10);
                $pdf->cell(20,4,$$xcodigo1,0,1,"L",0);
              }

              $pdf->RoundedRect(148,225-$diminui,47,62,0,'','1234');
              $pdf->SetXY(150,225-$diminui);
              $pdf->SetFont('Arial','',8);
              $pdf->cell(55,5,"CARIMBO",0,0,"C",0);

              $pdf->SetXY(15,255-$diminui);
              $pdf->SetFont('Arial','B',8);
              $pdf->cell(50,5,"Motivos da não entrega",1,0,"C",0);
              $pdf->SetFont('Arial','B',12);
              $pdf->cell(83,5,"Para uso do carteiro",1,1,"C",0);

              $pdf->SetFont('Arial','',7);

              $pdf->cell(2,2,"",0,1,"L",0);

              $pdf->cell(10,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Mudou-se",0,0,"L",0);
              $pdf->RoundedRect(17,263-$diminui,2,2,0,'DF','1234');
              $pdf->cell(2,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Ausente",0,1,"L",0);
              $pdf->RoundedRect(39,263-$diminui,2,2,0,'DF','1234');

              $pdf->cell(10,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Recusado",0,0,"L",0);
              $pdf->RoundedRect(17,268-$diminui,2,2,0,'DF','1234');
              $pdf->cell(2,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Não procurado",0,1,"L",0);
              $pdf->RoundedRect(39,268-$diminui,2,2,0,'DF','1234');

              $pdf->cell(10,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Desconhecido",0,0,"L",0);
              $pdf->RoundedRect(17,273-$diminui,2,2,0,'DF','1234');
              $pdf->cell(2,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Falecido",0,1,"L",0);
              $pdf->RoundedRect(39,273-$diminui,2,2,0,'DF','1234');

              $pdf->cell(10,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Não existe n" . chr(176),0,0,"L",0);
              $pdf->RoundedRect(17,278-$diminui,2,2,0,'DF','1234');
              $pdf->cell(2,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Outros",0,1,"L",0);
              $pdf->RoundedRect(39,278-$diminui,2,2,0,'DF','1234');

              $pdf->cell(10,5,"",0,0,"L",0);
              $pdf->cell(20,5,"Endereço insuficiente",0,0,"L",0);
              $pdf->RoundedRect(17,283-$diminui,2,2,0,'DF','1234');

              $pdf->RoundedRect(15,255-$diminui,50,32,0,'D','1234'); // quadro motivos nao entrega

              $pdf->SetFont('Arial','B',8);
              $pdf->SetXY(57,265-$diminui);
              $pdf->SetX(65);
              $pdf->cell(35,7,"Assinatura do entregador: ____________________________ ",0,1,"L",0);

              $pdf->SetX(65);
              $pdf->cell(35,7,"Reintegrado ao serviço postal em: ______/______/_______ ",0,1,"L",0);

              $pdf->RoundedRect(65,255-$diminui,83,32,0,'D','1234');  // quadro para uso carteiro
            }

          }

        }

      }else if (strtoupper($db02_descr) == "ENDER_ENTREGA"){

        $pdf->AddPage();
        $iQtdNotificoesGeradas++;
        global $variavel;
        $variavel = "";

        if(($imprimirtimbre == 1) || ($imprimirtimbre == 3)){
          $pdf = CabecNotif($pdf, 1, $variavel);
        }else{
          $pdf->ln(135);
        }

        $z01_ender = "";
        $z01_munic = "";

        if ($k60_tipo == 'M'){

          $sqlpropri = "select z01_nome,
                               codpri,
                               nomepri,
                               j39_numero,
                               j39_compl,
                               j13_descr as z01_bairro,
                               j34_setor,
                               j34_quadra,
                               j34_lote,
                               j34_zona,
                               j34_area,
                               j01_tipoimp ,
                               proprietario.j06_setorloc,
                               proprietario.j06_quadraloc,
                               proprietario.j06_lote,
                               proprietario.j05_descr,
                               proprietario.pql_localizacao
                          from proprietario
                         where j01_matric = $matric";

          $resultpropri = db_query($sqlpropri);
          if (pg_numrows($resultpropri) > 0) {

            db_fieldsmemory($resultpropri,0);
            $nomepri    = ucwords(strtolower($nomepri));
            $j13_descr  = ucwords(strtolower($j13_descr));
          }

          if ($tratamento == 0) {

            $imprimedestinatario = $z01_nome;
            $sqlender = "select z01_ender,
                                z01_numero,
                                z01_compl,
                                z01_bairro,
                                z01_munic,
                                z01_uf,
                                z01_cep,
                                z01_cxpostal
                           from cgm
                          where z01_numcgm = $cgm";

            $resultender = db_query($sqlender);
            db_fieldsmemory($resultender,0,true);

            if ( $z01_cxpostal == 0 || trim($z01_cxpostal) == '') {
              $sCepCxPostal = $z01_cep;
            } else {
              $sCepCxPostal = $z01_cep. " CAIXA POSTAL : ". $z01_cxpostal;
            }

            $sMunicipio  = trim($z01_munic) . " / " . $z01_uf;
            $z01_ender   = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
            $z01_munic   = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;

          } else {

            EnderecoNotif($tratamento, $matric, $z01_nome);
            $imprimedestinatario =   $z01_destinatario;
          }

        } elseif ($k60_tipo == 'I') {

          $imprimedestinatario = $z01_nome;
          $sqlempresa = "select z01_ender,
                                z01_numero,
                                z01_compl,
                                z01_bairro,
                                z01_munic,
                                z01_uf,
                                z01_cep,
                                z01_cxpostal
                           from issbase
                                inner join cgm on z01_numcgm = q02_numcgm
                          where q02_inscr = $q02_inscr";
          $resultempresa = db_query($sqlempresa);
          if (pg_numrows($resultempresa) > 0) {
            db_fieldsmemory($resultempresa,0);
          }

          if ( $z01_cxpostal == 0 || trim($z01_cxpostal) == '') {
            $sCepCxPostal = $z01_cep;
          } else {
            $sCepCxPostal = $z01_cep. " CAIXA POSTAL : ". $z01_cxpostal;
          }
          $sMunicipio = trim($z01_munic) . " / " . $z01_uf;
          $z01_ender  = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
          $z01_munic  = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;

        } elseif ($k60_tipo == 'N' || $k60_tipo =='C') {

          $imprimedestinatario = $z01_nome;
          $sqlender = "select z01_ender,
                              z01_numero,
                              z01_compl,
                              z01_bairro,
                              z01_munic,
                              z01_uf,
                              z01_cep,
                              z01_cxpostal
                         from cgm
                        where z01_numcgm = $cgm";
          $resultender = db_query($sqlender);
          db_fieldsmemory($resultender,0,true);

          if ( $z01_cxpostal == 0 || trim($z01_cxpostal) == '') {
            $sCepCxPostal = $z01_cep;
          } else {
            $sCepCxPostal = $z01_cep. " CAIXA POSTAL : ". $z01_cxpostal;
          }

          $sMunicipio = trim($z01_munic) . " / " . $z01_uf;
          $z01_ender  = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
          $z01_munic  = trim($z01_munic) . "/" . $z01_uf . " - " . $z01_cep. "  ". $z01_cxpostal;
        }

        $oLibDocumento = new libdocumento(1702,null);
        if ( $oLibDocumento->lErro ) {

          $oParms = new stdClass();
          $oParms->sErro = $oLibDocumento->sMsgErro;
          $sMsg = _M('tributario.notificacoes.cai2_emitenotif002.sem_documento_cadastrado', $oParms);
          db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
          exit;
        }

        $aParagrafos = $oLibDocumento->getDocParagrafos();
        foreach ($aParagrafos as $oParag) {
          eval($oParag->oParag->db02_texto);
        }

        if (file_exists('imagens/files/correios.jpg')) {
          $pdf->Image('imagens/files/correios.jpg',162,110,38);
        }

      } else {

        $imprimir= split("#\n",$texto);
        if (strtoupper($db02_descr) == "PARAGRAFO 1") {

          $pdf->WriteText($texto);
          $pdf->ln();
        } else {
          $pdf->MultiCell(0,4+$db02_espaca,$texto,"0","$db02_alinhamento",0,$db02_inicia+0);
        }

        $posicao_assinatura=$pdf->gety();
      }

     } //Fim "for" principal

    } //Fim do for Notificacoes

  } else if ( $tiporel == 2 ) {

    $iQtdNotificoesGeradas++;
    $pdf->addpage();
    $pdf->setfillcolor(235);
    $pdf->setfont('arial','b',8);
    $pdf->cell(15,05,'Notificação',1,0,"c",1);
    $pdf->cell(15,05,$xtipo,1,0,"c",1);
    $pdf->cell(15,05,'Numcgm',1,0,"c",1);
    $pdf->cell(80,05,'Nome',1,1,"c",1);
    $pdf->setfont('arial','',8);
    $total = 0;

    for($x=$lim1;$x < $lim2;$x++){

      db_fieldsmemory($result,$x);
      if ($pdf->gety() > $pdf->h - 35){

        $pdf->addpage();
        $iQtdNotificoesGeradas++;
        $pdf->setfont('arial','b',8);
        $pdf->cell(15,05,'Notificação',1,0,"c",1);
        $pdf->cell(15,05,$xtipo,1,0,"c",1);
        $pdf->cell(15,05,'Numcgm',1,0,"c",1);
        $pdf->cell(80,05,'Nome',1,1,"c",1);
        $pdf->setfont('arial','',8);
      }
      $pdf->cell(15,05,$notifica,0,0,"R",0);
      $pdf->cell(15,5,$$xcodigo1,0,0,"R",0);
      $pdf->cell(15,5,$z01_numcgm,0,0,"R",0);
      $pdf->cell(80,5,$z01_nome,0,1,"L",0);
      $total += 1;
    }
    $pdf->cell(125,05,'Total de Registros:   '.$total,1,1,"c",1);

  } else if ( $tiporel == 3 ) {

    $sqlparag = "select *
                   from db_documento
                        inner join db_docparag  on db03_docum   = db04_docum
                        inner join db_paragrafo on db04_idparag = db02_idparag
                  where db03_docum = 25
                    and db03_instit = " . db_getsession("DB_instit");

    $resparag = db_query($sqlparag);

    for($x=$lim1;$x < $lim2;$x++){

      $iQtdNotificoesGeradas++;
      db_fieldsmemory($result,$x);
      $pdf->AddPage();
      $pdf->SetFont('Arial','',13);
      $numcgm = @$j01_numcgm;
      $matric = @$j01_matric;
      $inscr  = @$q02_inscr;

      if($matric != ''){

        $xmatinsc = " k22_matric = ".$matric." and ";
        $matinsc  = "sua matrícula n".chr(176)." ".$matric;
      }else if($inscr != ''){

        $xmatinsc = " k22_inscr = ".$inscr." and ";
        $matinsc  = "sua inscrição n".chr(176)." ".$inscr;
      }else{

        $xmatinsc = " k22_numcgm = ".$numcgm." and ";
        $matinsc  = "V.Sa.";
      }
      $matricula = $matric;
      $inscricao = $inscr;
      $cgm       = $z01_numcgm;

      if (isset($notifparc)) {

        $sql10 = "select distinct
                         arretipo.k00_tipo as k22_tipo,
                         k00_descr
                    from notidebitosreg
                         inner join arrecad  on arrecad.k00_numpre = notidebitosreg.k43_numpre
                                            and arrecad.k00_numpar = notidebitosreg.k43_numpar
                         inner join arretipo on arretipo.k00_tipo  = arrecad.k00_tipo
                   where k43_notifica = " . $notifica;

      } else {

        $sql10 = "select distinct
                         k22_tipo,
                         k00_descr
                    from debitos
                         inner join arretipo on k00_tipo = k22_tipo
                   where $xmatinsc $jtipos k22_data = '$k60_datadeb' ";
      }

      $result10 = db_query($sql10);
      $xxtipos  = '';
      $virgula  = '';
      for ($i = 0;$i < pg_numrows($result10);$i++) {

        db_fieldsmemory($result10,$i);
        $xxtipos .= $virgula.$k00_descr;
        $virgula = ', ';
      }

      if (isset($notifparc)) {
        $dDataDebito  = date("Y-m-d",db_getsession("DB_datausu"));
      }else{
        $dDataDebito = strtotime($k60_datadeb);
      }

      $pdf->multicell(0,4,trim($munic).",".date('d',strtotime($dDataDebito))." de ".db_mes(date('m',strtotime($dDataDebito)))." de ".date('Y',strtotime($dDataDebito)).".",0,"R",0,0);
      $pdf->ln(10);

      for($ip = 0;$ip < pg_numrows($resparag);$ip++) {

        db_fieldsmemory($resparag,$ip);
        if($db02_alinha != 0){
          $pdf->setx($pdf->lMargin + $db02_alinha);
        }

        $pdf->multicell(0,6,db_geratexto($db02_texto),0,"$db02_alinhamento",0,$db02_inicia);

        if($db02_espaca > 1){
          $pdf->ln($db02_espaca);
        }
      }

      $pdf->setx(100);
      $posicaoy = $pdf->gety();
      $pdf->Image('imagens/assinatura/shimi.jpg',115,$posicaoy+10,45);
      $pdf->MultiCell(90,6,"\n\n\n"."Jorge Alfredo Schmitt"."\n"."Coordenador de Unidade",0,"C",0,15);

      if ($k60_tipo == 'M') {

        $sql3    = "select j43_ender  as z01_ender,
                           j43_numimo as z01_numero,
                           j43_comple as z01_compl,
                           j43_munic  as z01_munic,
                           j43_uf     as z01_uf,
                           j43_cep    as z01_cep,
                           j43_cxpost as z01_cxpostal
                      from iptuender
                     where j43_matric = " . $matric;
        $result3 = db_query($sql3);
        if (pg_numrows($result3) > 0) {

          db_fieldsmemory($result3,0);
          $sql3    = "select z01_nome from cgm where z01_numcgm = " . $cgm;
          $result3 = db_query($sql3);
          db_fieldsmemory($result3,0);
        } else {

          $sql3    = "select * from cgm where z01_numcgm = " . $cgm;
          $result3 = db_query($sql3);
        }

      } else {

        $sql3    = "select * from cgm where z01_numcgm = " . $cgm;
        $result3 = db_query($sql3);
      }

      if (pg_numrows($result3) > 0){

        db_fieldsmemory($result3,0);
        $pdf->text(10,248,"Contribuinte: ");
        $pdf->SetFont('Arial','',10);
        $pdf->text(10,254,strtoupper($xtipo).' - '.$$xcodigo1);
        $pdf->text(10,259,$z01_nome);
        if ($z01_cxpostal==""){
          $pdf->text(10,264,$z01_ender.", ".$z01_numero." ".$z01_compl);
        } else {
          $pdf->text(10,264,$z01_cxpostal);
        }
        $pdf->text(10,269,trim($z01_munic)." - ".$z01_uf);
        $pdf->text(10,274,substr($z01_cep,0,5) . "-" . substr($z01_cep,5,3));
      }
    }
  }

  if ($iQtdNotificoesGeradas > 0) {

    if ( $oParam->k102_tipoemissao == 3 && !empty($aListaNotifica) ) {

      $arquivoNotif  = "tmp/notificacoes_".date('His').".pdf";
      $nomearquivos .= "tmp/notificacoes_".date('His').".pdf#Dowload Lista de Notificações |";

      $pdf->Output($arquivoNotif,false,true);

      $head1 = "Relatório Notificações não Emitidas ";
      $head2 = "Lista nº {$lista}           ";

      $pdf1 = new PDF();
      $pdf1->Open();
      $pdf1->AliasNbPages();
      $pdf1->setfillcolor(235);
      $pdf1->AddPage();
      $iAlt = 5;

      $pdf1->SetFont("Arial","B",7);
      $pdf1->Cell(35 ,$iAlt,"Notificações"    ,1,0,"C",1);
      $pdf1->Cell(35 ,$iAlt,"Origem"          ,1,0,"C",1);
      $pdf1->Cell(120,$iAlt,"Nome do Contribuinte",1,1,"C",1);
      $pdf1->SetFont("Arial","",7);

      foreach ( $aListaNotifica as $iNotifica => $aDadosNotif ){

        $pdf1->Cell(35 ,$iAlt,$iNotifica         ,0,0,"C",0);
        $pdf1->Cell(35 ,$iAlt,$aDadosNotif['Origem'] ,0,0,"C",0);
        $pdf1->Cell(120,$iAlt,$aDadosNotif['Nome']   ,0,1,"L",0);
      }

      $arquivoNotifDif = "tmp/notificacoesdif_".date('His').".pdf";
      $nomearquivos   .= "tmp/notificacoesdif_".date('His').".pdf#Dowload Lista das Notificações não Emitidas |";

      $pdf1->Output($arquivoNotifDif,false,true);

      echo "<script>";
      echo "  listagem = '$nomearquivos';";
      echo "  parent.js_montarlista(listagem,'form1');";
      echo "</script>";

    } else {
      $pdf->Output();
    }

  }else{
    db_redireciona('db_erros.php?fechar=true&db_erro='.@$sErroMsg.'');
  }

  function CabecNotif($pdf, $endereco, $central) {

    global $nomeinst;
    global $ender;
    global $ender_numero;
    global $munic;
    global $cgc;
    global $bairro;
    global $uf;
    global $db12_extenso;
    global $logo;

    $S      = $pdf->lMargin;
    $pdf->SetLeftMargin(10);
    $Letra  = 'Times';
    $posini = 20;

    if ($endereco == 1) {

      $pdf->Image("imagens/files/".$logo,$posini,100,24);
      $pdf->Ln(90);
    } else {
      $pdf->Image("imagens/files/".$logo,$posini,8,24);
    }

    $pdf->Ln(5);
    $pdf->SetFont($Letra,'',10);
    $pdf->MultiCell(0,4,$db12_extenso,0,"C",0);
    $pdf->SetFont($Letra,'B',13);
    $pdf->MultiCell(0,6,$nomeinst,0,"C",0);
    $pdf->SetFont($Letra,'B',12);
    $pdf->MultiCell(0,4,@$GLOBALS["head1"],0,"C",0);
    if ($endereco == 1) {
      $pdf->MultiCell(0,5,$central,0,"C",0);
    }
    $pdf->SetLeftMargin(15);
    $pdf->SetRightMargin(15);
    $pdf->Ln(3);

    return $pdf;
  }

  function EnderecoNotif($tratamento, $matric, $z01_nome ) {

    global $z01_ender,$z01_bairro, $z01_numero, $z01_compl, $z01_munic, $z01_uf,
           $z01_cep, $z01_cxpostal, $z01_destinatario, $sCepCxPostal,
           $sMunicipio, $lValidaOrdemEnderecoEntrega;

    if($lValidaOrdemEnderecoEntrega){
      return false;
    }

    $sqlendereco    = "select substr(fc_iptuender,001,40) as z01_ender,          ";
    $sqlendereco   .= "       substr(fc_iptuender,042,10) as z01_numero,         ";
    $sqlendereco   .= "       substr(fc_iptuender,053,20) as z01_compl,          ";
    $sqlendereco   .= "       substr(fc_iptuender,074,40) as z01_bairro,         ";
    $sqlendereco   .= "       substr(fc_iptuender,115,40) as z01_munic,          ";
    $sqlendereco   .= "       substr(fc_iptuender,156,02) as z01_uf,             ";
    $sqlendereco   .= "       substr(fc_iptuender,159,08) as z01_cep,            ";
    $sqlendereco   .= "       substr(fc_iptuender,168,20) as z01_cxpostal,       ";
    $sqlendereco   .= "       substr(fc_iptuender,189,40) as z01_destinatario    ";
    $sqlendereco   .= "  from ( select fc_iptuender($matric, $tratamento) ) as x ";

    $resultendereco = db_query($sqlendereco) or die($sqlendereco);

    db_fieldsmemory($resultendereco, 0);

    if (pg_numrows($resultendereco) > 0 && pg_result($resultendereco,0,0) != "") {

      db_fieldsmemory($resultendereco, 0);

      $sCepCxPostal = $z01_cep. " CAIXA POSTAL : ". $z01_cxpostal;
      if ( $z01_cxpostal == 0 || trim($z01_cxpostal) == '') {
        $sCepCxPostal = $z01_cep;
      }

      $sMunicipio  = trim($z01_munic) . " / " . $z01_uf;
      $z01_ender   = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
      $z01_munic   = trim($z01_munic) . " / " . $z01_uf . " - " . $sCepCxPostal;

      if ($z01_destinatario == "") {
        $z01_destinatario = $z01_nome;
      }

    } else {

      $sqlpropri = "select z01_ender,
                           z01_munic,
                           z01_bairro,
                           z01_cep,
                           z01_uf,
                           z01_numero,
                           z01_compl,
                           z01_cxpostal,
                           z01_nome as z01_destinatario
                      from ( select z01_cgmpri
                               from proprietario
                              where j01_matric = $matric) as x
                     inner join cgm on z01_numcgm = x.z01_cgmpri";

      $resultpropri = db_query($sqlpropri);
      if (pg_numrows($resultpropri) > 0) {

        db_fieldsmemory($resultpropri,0);

        $sCepCxPostal = $z01_cep. " CAIXA POSTAL : ". $z01_cxpostal;
        if ( $z01_cxpostal == 0 || trim($z01_cxpostal) == '') {
          $sCepCxPostal = $z01_cep;
        }

        $sMunicipio  = trim($z01_munic) . " / " . $z01_uf;
        $z01_ender = $z01_ender . ", " . $z01_numero . " - " . $z01_compl;
        $z01_munic = trim($z01_munic) . "/" . $z01_uf . " - " . $sCepCxPostal;

      } else {

        $sCepCxPostal     = '';
        $sMunicipio       = '';
        $z01_ender        = "";
        $z01_numero       = "";
        $z01_compl        = "";
        $z01_bairro       = "";
        $z01_munic        = "";
        $z01_uf           = "";
        $z01_cep          = "";
        $z01_cxpostal     = "";
        $z01_destinatario = "";
        return false;
      }

    }

    return true;
  }

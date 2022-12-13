<?php
/**
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

require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libtributario.php"));
require_once(modification("dbforms/db_funcoes.php"));

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$tipotxt = $arq;

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

set_time_limit(0);

$erro           = false;
$descricao_erro = false;
$unicas         = array();
$ordena         = "" ;

if ($selunica != "") {

  $vt = split("U",$selunica);
  foreach ($vt as $i => $v){

    $check = split("=",$v);
    if (isset($check) && $check != "") {
      array_push($unicas, $check[0]."-".$check[1]."-".$check[2])."#";
    }
  }
}
$sQuebraLinha = "\r\n";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <table width="100%" height="18">
    <tr>
      <td width="100%" align="center">&nbsp;</td>
    </tr>
    <tr>
    <td width="100%"  align="center">
      <?php db_criatermometro('termometro','Concluido...','blue',1); ?>
    </td>
    </tr>
  </table>
</body>
</html>
<?php
$aNumpres =  array("L"=>"","S"=>"");
try{
  $oRegraEmissao = new regraEmissao($k00_tipo,9,db_getsession('DB_instit'),date("Y-m-d", db_getsession("DB_datausu")),db_getsession('DB_ip'));
} catch (Exception $eExeption){

  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

$iCodConvenio         = $oRegraEmissao->getConvenio();
$iCodConvenioCobranca = $oRegraEmissao->getCodConvenioCobranca();
$lCobranca            = $oRegraEmissao->isCobranca();

/**
 * Variavel que controla a exibição de vistorias
 * no arquivo de layout e dados
 */
$lGerarVistorias      = true;

if ( $oRegraEmissao->getCadTipoConvenio() == 2 ) {
  $iTamNossoNumero = 13;
} else {
  $iTamNossoNumero = 10;
}

if (!empty($k00_tipo) ) {

  $resulttipo = db_query("select k00_descr, k00_codbco, k00_codage, k00_txban, k00_rectx,
                                 k00_hist1, k00_hist2, k00_hist3, k00_hist4, k00_hist5,
                                 k00_hist6, k00_hist7, k00_hist8, k00_txban as tx_banc
                           from arretipo
                          where k00_tipo = $k00_tipo ");
  db_fieldsmemory($resulttipo, 0);
}

/**
 * Verificar maior numpar do arrecad para as vistorias
 */
$sqlMaxParc  = " select max(maiornumpar) as maiornumparvist,                                                         ";
$sqlMaxParc .= "        max(maiornumparsani) as maiornumparsani                                                      ";
$sqlMaxParc .= "   from (                                                                                            ";
$sqlMaxParc .= "          select max(k00_numpar) as maiornumpar,                                                     ";
$sqlMaxParc .= "                 0 as maiornumparsani                                                                ";
$sqlMaxParc .= "           from vistorias                                                                            ";
$sqlMaxParc .= "                 inner join vistinscr      on vistinscr.y71_codvist = vistorias.y70_codvist          ";
$sqlMaxParc .= "                 inner join vistorianumpre on vistorianumpre.y69_codvist = vistorias.y70_codvist     ";
$sqlMaxParc .= "                 inner join arrecad        on vistorianumpre.y69_numpre  = arrecad.k00_numpre        ";
$sqlMaxParc .= "                 inner join arreinstit     on arreinstit.k00_numpre      = arrecad.k00_numpre        ";
$sqlMaxParc .= "                                          and arreinstit.k00_instit = ".db_getsession('DB_instit');
$sqlMaxParc .= "          where extract(year from y70_data) = ".db_getsession('DB_anousu');
$sqlMaxParc .= "            and y70_ativo is true                                                                    ";
$sqlMaxParc .= "            and y70_parcial is false                                                                 ";
$sqlMaxParc .= "        union all                                                                                    ";
$sqlMaxParc .= "          select 0 as maiornumpar,                                                                   ";
$sqlMaxParc .= "                 max(k00_numpar) as maiornumparsani                                                  ";
$sqlMaxParc .= "           from vistorias                                                                            ";
$sqlMaxParc .= "                inner join vistsanitario  on vistsanitario.y74_codvist  = vistorias.y70_codvist      ";
$sqlMaxParc .= "                inner join sanitarioinscr on sanitarioinscr.y18_codsani = vistsanitario.y74_codsani  ";
$sqlMaxParc .= "                inner join sanitario      on sanitario.y80_codsani      = sanitarioinscr.y18_codsani ";
$sqlMaxParc .= "                inner join vistorianumpre on vistorianumpre.y69_codvist = vistorias.y70_codvist      ";
$sqlMaxParc .= "                inner join arrecad        on vistorianumpre.y69_numpre  = arrecad.k00_numpre         ";
$sqlMaxParc .= "                inner join arreinstit     on arreinstit.k00_numpre      = arrecad.k00_numpre         ";
$sqlMaxParc .= "                                         and arreinstit.k00_instit = ".db_getsession('DB_instit');
$sqlMaxParc .= "          where extract(year from y70_data) = ".db_getsession('DB_anousu');
$sqlMaxParc .= "            and y70_ativo is true                                                                    ";
$sqlMaxParc .= "            and y70_parcial is false ) x                                                             ";
$rsMaxParc   = db_query($sqlMaxParc) or die($sqlMaxParc);
db_fieldsmemory($rsMaxParc,0);

$tipodebitoarrecad = $k00_tipo;

if($k03_tipo == 2){
  $tipo = "fixo";
}else if($k03_tipo == 3){
  $tipo = "variavel";
}else if($k03_tipo == 19){
  $tipo = "vistoriageral";
}else if($k03_tipo == 5){
  $tipo = "vistoriasemissqn";
}

$clissbase   = new cl_issbase;
$cldb_config = new cl_db_config;
$clisscalc   = new cl_isscalc;
$clarrecad   = new cl_arrecad;

$arqnomes    = '';
$passavist   = true;
$passaiss    = true;
$passasani   = true;

$limit = '';
if ($quantidade != ''){
  $limit = " limit $quantidade ";
}

if($ord == "escritorio"){
  $ordena = " escritorio, z01_nome ";
}else if( $ord == 'nome'){
  $ordena = " z01_nome, q01_inscr ";
} else {
  $ordena = " q01_inscr " ;
}

if(isset($tx_banc) && $tx_banc != "") {
  $taxa_bancaria = $tx_banc;
}else{
  $taxa_bancaria = 0;
}

/**
 * Vistorias sem ISSQN
 */
if ($k03_tipo == 5) {

  $sql = $clissbase->sql_query_file(null,
                                    "q02_inscr as q01_inscr",
                                    "q02_inscr $limit ",
                                    " q02_dtbaix is null");
  $result = $clissbase->sql_record($sql);

/**
 * VISTORIAS
 */
} else if($k03_tipo == 19) {

  $join                  = "left";
  $ordenaescrito         = "";
  $whereescrito          = "";
  $sWhereEscritorioAtivo = "";

  if (isset($emis) && $emis == "comescr") {

    if (isset($cgmescrito) && $cgmescrito != "") {

      $whereescrito          = " and q10_numcgm in ($cgmescrito) ";
      $sWhereEscritorioAtivo = " q10_dtfim is null or q10_dtfim >= '".date('Y-m-d',db_getsession('DB_datausu'))."'";
    } else {
      $whereescrito = "";
    }

    $join = "inner";

  } else if (isset($emis) && $emis == "semescr") {

    $join = " left ";
    $whereescrito = " and q10_numcgm is null";

  } else if (isset($emis) && $emis == "geral") {

    $whereescrito = "";
    $join = " left ";
  }

  $sql  = " select * from (                                                                                         ";
  $sql .= "   select distinct on (q01_inscr) * from (                                                               ";
  $sql .= "     select 1 as tipo_vist,                                                                              ";
  $sql .= "            y69_numpre as q01_numpre,                                                                    ";
  $sql .= "            ( select sum(k00_valor)                                                                      ";
  $sql .= "                from arrecad                                                                             ";
  $sql .= "               where k00_numpre = y69_numpre                                                             ";
  $sql .= "            ) as q01_valor,                                                                              ";
  $sql .= "            q10_numcgm,                                                                                  ";
  $sql .= "            cgm_escritorio.z01_nome as escritorio,                                                       ";
  $sql .= "            q02_inscr as q01_inscr,                                                                      ";
  $sql .= "            cgm.z01_nome,                                                                                ";
  $sql .= "            y77_descricao,                                                                               ";
  $sql .= "            substr(y70_data,1,4) as anousu,                                                              ";
  $sql .= "            arrecad.k00_tipo                                                                             ";
  $sql .= "       from issbase                                                                                      ";
  $sql .= "            inner join vistinscr             on vistinscr.y71_inscr        = issbase.q02_inscr           ";
  $sql .= "            inner join vistorias             on vistorias.y70_codvist      = vistinscr.y71_codvist       ";
  $sql .= "            inner join tipovistorias         on vistorias.y70_tipovist     = tipovistorias.y77_codtipo   ";
  $sql .= "            inner join vistorianumpre        on vistorianumpre.y69_codvist = vistorias.y70_codvist       ";
  $sql .= "            inner join arrecad               on vistorianumpre.y69_numpre  = arrecad.k00_numpre          ";
  $sql .= "            inner join arreinscr             on vistorianumpre.y69_numpre  = arreinscr.k00_numpre        ";
  $sql .= "            $join join escrito               on escrito.q10_inscr          = issbase.q02_inscr           ";
  $sql .= "            $join join cgm as cgm_escritorio on escrito.q10_numcgm         = cgm_escritorio.z01_numcgm   ";
  $sql .= "            inner join cgm                   on cgm.z01_numcgm             = issbase.q02_numcgm          ";
  $sql .= "      where issbase.q02_dtbaix is null                                                                   ";

  if ( !empty($sWhereEscritorioAtivo) ) {
    $sql .= " and $sWhereEscritorioAtivo";
  }

  $sql .= "     union all                                                                                           ";
  $sql .= "                                                                                                         ";
  $sql .= "     select 2 as tipo_vist,                                                                              ";
  $sql .= "            y69_numpre as q01_numpre,                                                                    ";
  $sql .= "            (select sum(k00_valor)                                                                       ";
  $sql .= "        from arrecad                                                                                     ";
  $sql .= "      where k00_numpre = y69_numpre) as q01_valor,                                                       ";
  $sql .= "            q10_numcgm,                                                                                  ";
  $sql .= "            cgm_escritorio.z01_nome as escritorio,                                                       ";
  $sql .= "            q02_inscr as q01_inscr,                                                                      ";
  $sql .= "            cgm.z01_nome,                                                                                ";
  $sql .= "            y77_descricao,                                                                               ";
  $sql .= "            substr(y70_data,1,4) as anousu,                                                              ";
  $sql .= "            arrecad.k00_tipo                                                                             ";
  $sql .= "       from issbase                                                                                      ";
  $sql .= "            inner join sanitarioinscr        on sanitarioinscr.y18_inscr    = issbase.q02_inscr          ";
  $sql .= "            inner join vistsanitario         on vistsanitario.y74_codsani   = sanitarioinscr.y18_codsani ";
  $sql .= "            inner join vistorias             on vistorias.y70_codvist       = vistsanitario.y74_codvist  ";
  $sql .= "            inner join tipovistorias         on vistorias.y70_tipovist      = tipovistorias.y77_codtipo  ";
  $sql .= "            inner join vistorianumpre        on vistorianumpre.y69_codvist  = vistorias.y70_codvist      ";
  $sql .= "            inner join arrecad               on vistorianumpre.y69_numpre   = arrecad.k00_numpre         ";
  $sql .= "            inner join arreinscr             on vistorianumpre.y69_numpre   = arreinscr.k00_numpre       ";
  $sql .= "            $join join escrito               on escrito.q10_inscr           = issbase.q02_inscr          ";
  $sql .= "            $join join cgm as cgm_escritorio on escrito.q10_numcgm          = cgm_escritorio.z01_numcgm  ";
  $sql .= "            inner join cgm                   on cgm.z01_numcgm              = issbase.q02_numcgm         ";
  $sql .= "      where issbase.q02_dtbaix is null                               ";

  if ( !empty($sWhereEscritorioAtivo) ) {
    $sql .= " and $sWhereEscritorioAtivo";
  }

  $sql .= "      ) as xx                                         ";
  $sql .= "      where 1 = 1 $whereescrito ";
  $sql .= "        and anousu = '".db_getsession('DB_anousu')."' ";

  if (!empty($k00_tipo)) {
    $sql .= " and k00_tipo = {$k00_tipo}";
  }

  $sql .= "      $limit                                          ";
  $sql .= "    ) as x                                            ";
  $sql .= " order by $ordena                                     ";

  $result = db_query($sql) or die($sql);

/**
 * ISSQN VARIAVEL / FIXO
 */
} else {

  $lGerarVistorias = false;

  $iAnousu         = db_getsession("DB_anousu");

  $sWhereCaracteristicaNfe  = " and not exists ( select 1                                 ";
  $sWhereCaracteristicaNfe .= "                    from issbasecaracteristica             ";
  $sWhereCaracteristicaNfe .= "                   where q138_caracteristica in( 10, 11 )  ";
  $sWhereCaracteristicaNfe .= "                     and q138_inscr          = q01_inscr ) ";

  /**
   * Validamos se a inscrição possui a caracteristica de emissor de NFE (10)
   * apenas se for ISSQN Variável, para fixo deve gerar normalmente
   */
  if( $k03_tipo == 2 ){
    $sWhereCaracteristicaNfe = '';
  }

  $dDataSistema = date("Y-m-d",db_getsession("DB_datausu"));

  $sWhere  = "     q01_anousu = {$iAnousu}                                                                      ";
  $sWhere .= " and k00_tipo   = {$k00_tipo}                                                                     ";
  $sWhere .= " and not exists ( select 1                                                                        ";
  $sWhere .= "                    from isscadsimples                                                            ";
  $sWhere .= "                         left join isscadsimplesbaixa on q38_sequencial = q39_isscadsimples       ";
  $sWhere .= "                   where q38_inscr = q01_inscr                                                    ";
  $sWhere .= "                     and ( q39_dtbaixa is null or q39_dtbaixa > '{$dDataSistema}' ) ";
  $sWhere .= "                )                                                                                 ";

  $sWhere .= $sWhereCaracteristicaNfe;

  $sql    = $clisscalc->sql_query_arrecad( null, null, null, null, null,
                                           "distinct q01_inscr, q01_valor, q01_numpre",
                                           "q01_inscr $limit ", $sWhere );

  $result = $clisscalc->sql_record($sql);
}

/**
 * For principal da geração do arquivo
 */
if ( $result == false || pg_num_rows($result) == 0 ) {

  $erro = true;
  $descricao_erro =  "Não existe cálculo efetuado.";
} else {

  $quantos = 0;
  $mensagemdebitosanosanteriores = "";

  $iTotalLinhas20   = 0;
  $nTotalParcelas20 = 0;

  /**
   * Executa o laço duas vezes
   *  1 - Geração dos dados
   *  2 - Geração do layout
   */
  for ($vez = 0; $vez <= 1; $vez++) {

    if ($vez == 0) {
      $gerar = "dados";
    }
    if ($vez == 1) {
      $gerar = "layout";
    }

    $anousu         = db_getsession("DB_anousu");
    $nomedoarquivo  = "tmp/" . $gerar . "_" . $tipo . "_tipdeb_" . str_pad($tipodebitoarrecad,4,"0",STR_PAD_LEFT) . "_issqn" . $anousu . "_" . date("Y-m-d_His",db_getsession("DB_datausu")) . ".txt";
    $arqnomes      .= $nomedoarquivo."# Download do Arquivo - ".$nomedoarquivo."|";

    $clabre_arquivo =  new cl_abre_arquivo($nomedoarquivo);

    if ($clabre_arquivo->arquivo != false) {

      $quantidade = pg_numrows($result);

      global $contador;
      $contador = 0;

      /**
       * For pelo resource de inscrições
       */

      for ($i=0; $i<$quantidade; $i++) {

        db_fieldsmemory($result,$i);

        db_atutermometro($i,$quantidade,'termometro');

        $resultiss = $clissbase->empresa_record($clissbase->empresa_query($q01_inscr));

        if ($resultiss == false or pg_numrows($resultiss) == 0) {
          continue;
        }

        db_fieldsmemory($resultiss, 0);

        $passar = true;

        if ($k03_tipo == 5) {

          $sqlprocura    = "select * from isscalc where q01_anousu = " . db_getsession("DB_anousu") . " and q01_inscr = $q01_inscr";
          $resultprocura = db_query($sqlprocura) or die($sqlprocura);
          if (pg_num_rows($resultprocura) > 0) {
            continue;
          }

          $q01_valor = 0;
          $q01_numpre = 0;

        } else {

          // verifica issqn
          $resultarr = $clarrecad->sql_record($clarrecad->sql_query_instit("","arrecad.*","arrecad.k00_numpre,k00_numpar"," arrecad.k00_numpre = $q01_numpre and arreinstit.k00_instit = ".db_getsession('DB_instit') ));
          if ($clarrecad->numrows == 0) {
            $passar = false;
          }
        }

        $aliquota   = 0;
        $sqlprocura = "select q01_valor as aliquota from isscalc where q01_anousu = " . db_getsession("DB_anousu") . " and q01_inscr = $q01_inscr";
        $resultprocura = db_query($sqlprocura) or die($sqlprocura);
        if (pg_num_rows($resultprocura) > 0) {
          db_fieldsmemory($resultprocura,0);
        }

        $sqvalorMax         = "select max(k00_numpar) as parcelamaxima, sum(k00_valor) as total_debito from arrecad where k00_numpre = $q01_numpre";
        $rsValorMax         = db_query($sqvalorMax) or die($sqvalorMax);
        $intNumrowsValorMax = pg_numrows($rsValorMax);
        $parcelamaxima      = 0;
        if($intNumrowsValorMax > 0){
          db_fieldsmemory($rsValorMax,0);
        }

        /*       VERIFICA SE EXISTE VISTORIAS DE LOCALIZACAO PARA ESSA INSCRICAO       */
        //------------------------------------------| TAM |    LABEL NO LAYOUT       |
        $sqlVistorias  = " select y70_codvist,    "; //  10    CODIGO DA VISTORIA
        $sqlVistorias .= "        y70_data,       "; //  10    DATA DA VISTORIA
        $sqlVistorias .= "        y70_hora,       "; //   5    HORA DA VISTORIA
        $sqlVistorias .= "        y70_contato,    "; //  50    CONTATO DA VISTORIA
        $sqlVistorias .= "        y70_tipovist,   "; //  10    CODIGO DO TIPO DE VISTORIA
        $sqlVistorias .= "        y77_descricao,  "; //  50    DESCRICAO DO TIPO DE VISTORIA
        $sqlVistorias .= "        y70_id_usuario, "; //  10    CODIGO USUARIO QUE DIGITOU A VISTORIA
        $sqlVistorias .= "        nome,           "; //  50    NOME DO USUARIO QUE DIGITOU A VISTORIA
        $sqlVistorias .= "        y70_coddepto,   "; //  10    CODIGO DO DEPARTAMENTO QUE DIGITOU A VISTORIA
        $sqlVistorias .= "        descrdepto,     "; //  50    NOME DO DEPARTAMENTO QUE DIGITOU A VISTORIA
        $sqlVistorias .= "        y70_numbloco,   "; //  20    NUMERO DO BLOCO DA VISTORIA
        $sqlVistorias .= "        y70_parcial,    "; //
        $sqlVistorias .= "        ( select y69_numpre ";
        $sqlVistorias .= "            from sanitarioinscr ";
        $sqlVistorias .= "                 inner join vistsanitario  on y18_codsani = y74_codsani ";
        $sqlVistorias .= "                 inner join vistorianumpre on vistorianumpre.y69_codvist = vistsanitario.y74_codvist ";
        $sqlVistorias .= "                 inner join vistorias      on vistorianumpre.y69_codvist = vistorias.y70_codvist  ";
        $sqlVistorias .= "           where extract(year from y70_data) = ".db_getsession('DB_anousu');
        $sqlVistorias .= "             and y70_ativo is true             ";
        $sqlVistorias .= "             and y70_parcial is false          ";
        $sqlVistorias .= "             and y18_inscr  = $q01_inscr       ";
        $sqlVistorias .= "             and y70_instit = ".db_getsession('DB_instit')." limit 1 ) as numpre_sanitario, ";
        $sqlVistorias .= "        y69_numpre      "; //   8    NUMPRE DA VISTORIA
        $sqlVistorias .= "  from vistorias                                                                        ";
        $sqlVistorias .= "        inner join tipovistorias  on tipovistorias.y77_codtipo = vistorias.y70_tipovist ";
        $sqlVistorias .= "        inner join db_depart      on vistorias.y70_coddepto = db_depart.coddepto        ";
        $sqlVistorias .= "        inner join db_usuarios    on db_usuarios.id_usuario = vistorias.y70_id_usuario  ";
        $sqlVistorias .= "        inner join vistinscr      on vistinscr.y71_codvist = vistorias.y70_codvist      ";
        $sqlVistorias .= "        inner join vistorianumpre on vistorianumpre.y69_codvist = vistorias.y70_codvist ";
        $sqlVistorias .= "        inner join arreinstit     on arreinstit.k00_numpre = vistorianumpre.y69_numpre  ";
        $sqlVistorias .= "                                 and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";
        $sqlVistorias .= " where extract(year from y70_data) = ".db_getsession('DB_anousu');
        $sqlVistorias .= "   and y70_ativo is true             ";
        $sqlVistorias .= "   and y70_parcial is false          ";
        $sqlVistorias .= "   and y71_inscr  = $q01_inscr       ";
        $sqlVistorias .= "   and y70_instit = ".db_getsession('DB_instit');
        $sqlVistorias .= "   and ( select count(*) from arrecad where arrecad.k00_numpre = vistorianumpre.y69_numpre ) > 0 ";

        $rsVistorias         = db_query($sqlVistorias) or die($sqlVistorias);
        $intNumrowsVistorias = pg_numrows($rsVistorias);

        if ($k03_tipo == 5 and $intNumrowsVistorias == 0) {
          continue;
        }

        if ($intNumrowsVistorias > 0) {
          db_fieldsmemory($rsVistorias,0);
        }

        $tipo_geracao = "";

        if ($passar == true) {
          $quantos ++;

          if ( (int) $quantidade_registros_real > 0 and $gerar == "dados" ) {

            if ( $quantos > $quantidade_registros_real ) {

              db_atutermometro($quantidade,$quantidade,'termometro');
              flush();
              break;
            }
          }

          if ($tipo == "fixo" and pg_result($resultarr,0,'k00_tipo')==$tipodebitoarrecad) {

            if ($gerar == "dados") {

              $tipo_geracao = "ISSQN FIXO";
              if ($tipotxt == "txt") {
                fputs($clabre_arquivo->arquivo,str_pad($tipo_geracao,30));
              }
            }
          } else if ($tipo == "variavel" and pg_result($resultarr,0,'k00_tipo')==$tipodebitoarrecad) {

            if ($gerar == "dados") {

              $tipo_geracao = "ISSQN VARIAVEL" ;
              if ($tipotxt == "txt") {
                fputs($clabre_arquivo->arquivo,str_pad($tipo_geracao,30));
              }
            }
          } else if ($tipo == "vistoriageral") {

            if ($gerar == "dados") {

              $tipo_geracao = "VISTORIAS";
              if ($tipotxt == "txt") {
                fputs($clabre_arquivo->arquivo,str_pad($tipo_geracao,30));
              }
            }
          } else if ($tipo == "vistoriasemissqn") {

            if ($gerar == "dados") {

              $tipo_geracao = "VISTORIAS SEM ISSQN";
              if ($tipotxt == "txt") {
                fputs($clabre_arquivo->arquivo,str_pad($tipo_geracao,30));
              }
            }
          } else {
            continue;
          }

          if ($gerar == "layout") {
            fputs($clabre_arquivo->arquivo, db_contador("TIPO","TIPO",$contador,30));
          }

          if ($gerar == "dados") {

            if ($tipotxt == "txt") {

              fputs($clabre_arquivo->arquivo,str_pad($quantos,10));
              fputs($clabre_arquivo->arquivo,str_pad(db_getsession("DB_anousu"),10));
              fputs($clabre_arquivo->arquivo,str_pad($q02_inscr,10));
              fputs($clabre_arquivo->arquivo,db_formatar($q01_valor,'f',' ',18));

              fputs($clabre_arquivo->arquivo,str_pad($q02_numcgm,10));
              fputs($clabre_arquivo->arquivo,str_pad($z01_nome,40));
              fputs($clabre_arquivo->arquivo,substr(str_pad($z01_nomefanta,50), 0, 50));
              fputs($clabre_arquivo->arquivo,str_pad($z01_cgccpf,20));
              fputs($clabre_arquivo->arquivo,str_pad($z01_incest,20));

              fputs($clabre_arquivo->arquivo,str_pad(db_formatar($q02_dtcada,'d'),10));
              fputs($clabre_arquivo->arquivo,str_pad(db_formatar($q02_dtinic,'d'),10));
              fputs($clabre_arquivo->arquivo,str_pad(db_formatar($q02_dtbaix,'d'),10));

              fputs($clabre_arquivo->arquivo,str_pad($j14_tipo,20));
              fputs($clabre_arquivo->arquivo,str_pad($z01_ender,40));
              fputs($clabre_arquivo->arquivo,str_pad($z01_numero,10));
              fputs($clabre_arquivo->arquivo,substr(str_pad($z01_compl,20),0,20));
              fputs($clabre_arquivo->arquivo,str_pad($z01_cxpostal,20));
              fputs($clabre_arquivo->arquivo,str_pad($z01_bairro,50));
              fputs($clabre_arquivo->arquivo,str_pad($z01_munic,20));
              fputs($clabre_arquivo->arquivo,str_pad($z01_uf,2));
              fputs($clabre_arquivo->arquivo,str_pad($z01_cep,8));
              fputs($clabre_arquivo->arquivo,str_pad($z01_telef,20));
              fputs($clabre_arquivo->arquivo,str_pad($q01_numpre, 8, "0", STR_PAD_LEFT));

            } elseif ($tipotxt == "bsjtxt") {

              if ($quantos == 1) {

                $sCedente = "2141"; // ###falta###

                $linha00 = "";
                $linha00 .= "BSJR00";
                $linha00 .= str_replace("/","",date('d/m/y',db_getsession('DB_datausu')));
                $linha00 .= str_replace(":","",db_hora(0,"H:i:s"));
                $linha00 .= $sCedente;
                $linha00 .= "    ";
                $linha00 .= "N";
                $linha00 .= "ISS ".substr($anousu,2,2);
                $linha00 .= str_repeat(" ",255);

                fputs($clabre_arquivo->arquivo, db_contador_bsj($linha00,"",$contador,288));

              }

              $quantunica_linha10 = 0;
              if(isset($unicas) && sizeof($unicas) > 0 && $unicas[0] != "--") {

                for ($unica=0; $unica < sizeof($unicas); $unica++) {

                  $vencunica = substr($unicas[$unica],0,10);
                  $operunica = substr($unicas[$unica],11,10);
                  $percunica = substr($unicas[$unica],22,strlen($unicas[$unica])-22);

                  /* PROCESSA AS UNICAS */
                  $sqlFindUnica  = " select *,                                                                                            ";
                  $sqlFindUnica .= "        substr(fc_calcula,2,13)::float8   as uvlrhis,                                                 ";
                  $sqlFindUnica .= "        substr(fc_calcula,15,13)::float8  as uvlrcor,                                                 ";
                  $sqlFindUnica .= "         substr(fc_calcula,28,13)::float8 as uvlrjuros,                                               ";
                  $sqlFindUnica .= "        substr(fc_calcula,41,13)::float8  as uvlrmulta,                                               ";
                  $sqlFindUnica .= "        substr(fc_calcula,54,13)::float8  as uvlrdesconto,                                            ";
                  $sqlFindUnica .= "        (substr(fc_calcula,15,13)::float8+                                                            ";
                  $sqlFindUnica .= "        substr(fc_calcula,28,13)::float8+                                                             ";
                  $sqlFindUnica .= "        substr(fc_calcula,41,13)::float8-                                                             ";
                  $sqlFindUnica .= "        substr(fc_calcula,54,13)::float8) as utotal                                                   ";
                  $sqlFindUnica .= "   from (select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,    ";
                  $sqlFindUnica .= "                fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").") ";
                  $sqlFindUnica .= "           from recibounica r                                                                         ";
                  $sqlFindUnica .= "         where r.k00_numpre = $q01_numpre                                                             ";
                  $sqlFindUnica .= "           and r.k00_dtvenc = '$vencunica'                                                            ";
                  $sqlFindUnica .= "           and r.k00_dtoper = '$operunica'                                                            ";
                  $sqlFindUnica .= "           and k00_percdes  = $percunica ) as unica                                                   ";
                  $resultfin1 = db_query($sqlFindUnica);

                  if ($resultfin1!=false) {

                    if (pg_numrows($resultfin1) > 0) {
                      $quantunica_linha10=1;
                    }
                  }
                }
              }

              $linha10 = "BSJR10";
              $linha10 .= substr( str_pad(addslashes($z01_nome), 40, " ",STR_PAD_RIGHT), 0,40);

              if (trim($z01_cxpostal) != "" and $z01_cxpostal > 0) {
                $linha10 .= str_pad("CAIXA POSTAL: $z01_cxpostal",40," ",STR_PAD_RIGHT);
              } else {

                if ( strlen(trim($z01_ender)) >= 40 ) {
                  $z01_ender = substr($z01_ender,0,34);
                }
                $linha10 .= substr( str_pad(substr( addslashes($z01_ender) . (strlen(trim($z01_numero)) > 0?", ":"") . trim($z01_numero) . (strlen(trim($z01_compl)) > 0?"/":"") . trim($z01_compl) . "-" . $z01_bairro ,0,40),40," ",STR_PAD_RIGHT) ,0,40);
              }
              $linha10 .= substr( str_pad(addslashes($z01_munic), 20," ",STR_PAD_RIGHT), 0,40);
              $linha10 .= str_pad(substr($z01_cep,0,5), 5);
              $linha10 .= str_pad($z01_uf, 2," ",STR_PAD_RIGHT);
              $linha10 .= str_repeat(" ", 17);
              if ( strlen(trim($q02_escrit)) > 0 and false ) {
                $linha10 .= str_pad(substr("ESCRITORIO CONTABIL: $q02_escrit",0,80), 80," ",STR_PAD_RIGHT);
              } else {
                $linha10 .= str_repeat(" ", 80);
              }
              $linha10 .= str_pad($anousu, 4, "0", STR_PAD_LEFT) . " ";

              // testar se for variavel as parcelas em aberto tem que fechar com parcela maxima, senao dá erro no banco (ocorreu erro em 2012) - variavel
              $linha10 .= str_pad($parcelamaxima + $quantunica_linha10,2,"0",STR_PAD_LEFT);
              $linha10 .= str_repeat("0", 15);
              $linha10 .= str_repeat("0", 5);
              $linha10 .= str_repeat("0", 2);
              $linha10 .= ($mensagemdebitosanosanteriores == ""?"N":"S");
              $linha10 .= ($quantunica_linha10 == 0?"N":"S");
              $linha10 .= str_pad(substr($z01_cep,0,8), 8, "0", STR_PAD_LEFT);
              $linha10 .= str_pad($parcelamaxima,2,"0",STR_PAD_LEFT);
              $linha10 .= str_repeat(" ", 37);
              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha10,"",$contador,288));

              // parte 1
              $linha31 = "BSJR30";

              $imp_linha31  = " ";
              $imp_linha31 .= "$tipo_geracao - COMPETENCIA: $anousu";
              $linha31     .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = "";
              $linha31    .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $linha31    .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $linha31    .= str_repeat(" ",24);

              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha31,"",$contador,288));

              // parte 2
              $linha31 = "BSJR30";

              $imp_linha31  = " ";
              $imp_linha31 .= "INSCRICAO: $q01_inscr - $z01_nome";
              $linha31     .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $linha31    .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $linha31    .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $linha31    .= str_repeat(" ",24);

              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha31,"",$contador,288));

              // parte 3
              $linha31 = "BSJR30";

              $imp_linha31  = " ";
              $imp_linha31 .= "ATIVIDADE: $q03_descr";
              $linha31     .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $linha31    .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $linha31    .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $linha31    .= str_repeat(" ",24);

              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha31,"",$contador,288));

              // parte 4
              $linha31 = "BSJR30";

              $imp_linha31 = " ";
              if ($k03_tipo == 3) {
                $imp_linha31 .= trim(strtoupper($k00_descr)) . " - Despesas extras: " . trim(db_formatar($taxa_bancaria,'f',' ',18));
              } else {
                $imp_linha31 .= trim(strtoupper($k00_descr)) . ": " . trim(db_formatar($total_debito,'f',' ',18)) . " - Despesas extras: " . trim(db_formatar($taxa_bancaria,'f',' ',18));
              }
              $linha31    .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $imp_linha31 = " ";
              $linha31    .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              if ( strlen(trim($q02_escrit)) > 0 ) {
                $imp_linha31 .= substr("ESCRITORIO CONTABIL: $q02_escrit",0,80);
              }
              $linha31 .= str_pad($imp_linha31,86," ",STR_PAD_RIGHT);

              $linha31 .= str_repeat(" ",24);

              fputs($clabre_arquivo->arquivo, db_contador_bsj($linha31,"",$contador,288));
            }

          } else {

            fputs($clabre_arquivo->arquivo, db_contador("CONTADOR","CONTADOR",$contador,10));
            fputs($clabre_arquivo->arquivo, db_contador("EXERCICIO","EXERCICIO",$contador,10));
            fputs($clabre_arquivo->arquivo, db_contador("INSCRICAO","INSCRICAO",$contador,10));
            fputs($clabre_arquivo->arquivo, db_contador("VALORCALCULADO","VALOR",$contador,18));

            fputs($clabre_arquivo->arquivo, db_contador("CGM","CGM",$contador,10));
            fputs($clabre_arquivo->arquivo, db_contador("NOME","NOME",$contador,40));
            fputs($clabre_arquivo->arquivo, db_contador("NOMEFANTASIA","NOME FANTASIA",$contador,50));
            fputs($clabre_arquivo->arquivo, db_contador("CNPJCPF","CNPJ/CPF",$contador,20));
            fputs($clabre_arquivo->arquivo, db_contador("INSCRICAOESTADUAL","INSCRICAO ESTADUAL",$contador,20));

            fputs($clabre_arquivo->arquivo, db_contador("DATACADASTRO","DATA DO CADASTRO",$contador,10));
            fputs($clabre_arquivo->arquivo, db_contador("DATAINICIO","DATA DE INICIO",$contador,10));
            fputs($clabre_arquivo->arquivo, db_contador("DATABAIXA","DATA DA BAIXA",$contador,10));

            fputs($clabre_arquivo->arquivo, db_contador("TIPOLOGRADOURO","TIPO DO LOGRADOURO",$contador,20));
            fputs($clabre_arquivo->arquivo, db_contador("ENDERECO","ENDERECO",$contador,40));
            fputs($clabre_arquivo->arquivo, db_contador("NUMERO","NUMERO",$contador,10));
            fputs($clabre_arquivo->arquivo, db_contador("COMPLEMENTO","COMPLEMENTO",$contador,20));
            fputs($clabre_arquivo->arquivo, db_contador("CAIXAPOSTAL","CAIXA POSTAL",$contador,20));
            fputs($clabre_arquivo->arquivo, db_contador("BAIRRO","BAIRRO",$contador,50));
            fputs($clabre_arquivo->arquivo, db_contador("MUNICIPIO","MUNICIPIO",$contador,20));
            fputs($clabre_arquivo->arquivo, db_contador("UF","UF",$contador,2));
            fputs($clabre_arquivo->arquivo, db_contador("CEP","CEP",$contador,8));
            fputs($clabre_arquivo->arquivo, db_contador("TELEFONE","TELEFONE",$contador,20));
            fputs($clabre_arquivo->arquivo, db_contador("NUMPRE","NUMPRE",$contador,8));
          }

          $linha20 = "BSJR20";
          if(isset($unicas) && sizeof($unicas) > 0 && $unicas[0] != "--") {

            $aNumpres = array("L"=>"","S"=>"");

            if (!empty($q01_numpre) && $numpre_sanitario == $q01_numpre){
              $aNumpres["L"] = $y69_numpre; //indice L para localizacao
            } else {
              $aNumpres["L"] = $q01_numpre; //indice L para localizacao
            }

            if (!empty($numpre_sanitario)){
              $aNumpres["S"] = $numpre_sanitario;  //indice S para sanitario
            }

            foreach ($aNumpres as $sChave => $numpre_vistoria ) {

            for ($unica=0; $unica < sizeof($unicas); $unica++) {

              $vencunica = substr($unicas[$unica],0,10);
              $operunica = substr($unicas[$unica],11,10);
              $percunica = substr($unicas[$unica],22,strlen($unicas[0])-22);
              /* PROCESSA AS UNICAS */

              $resultfin1 = false;

              if (empty($numpre_vistoria)) {
                $numpre_vistoria = 0;
              }

              $sqlFindUnica  = " select *,                                                                                               ";
              $sqlFindUnica .= "        substr(fc_calcula,2,13)::float8 as uvlrhis,                                                      ";
              $sqlFindUnica .= "        substr(fc_calcula,15,13)::float8 as uvlrcor,                                                     ";
              $sqlFindUnica .= "        substr(fc_calcula,28,13)::float8 as uvlrjuros,                                                   ";
              $sqlFindUnica .= "        substr(fc_calcula,41,13)::float8 as uvlrmulta,                                                   ";
              $sqlFindUnica .= "        substr(fc_calcula,54,13)::float8 as uvlrdesconto,                                                ";
              $sqlFindUnica .= "        (substr(fc_calcula,15,13)::float8+                                                               ";
              $sqlFindUnica .= "        substr(fc_calcula,28,13)::float8+                                                                ";
              $sqlFindUnica .= "        substr(fc_calcula,41,13)::float8-                                                                ";
              $sqlFindUnica .= "        substr(fc_calcula,54,13)::float8) as utotal                                                      ";
              $sqlFindUnica .= "   from (select r.k00_numpre,r.k00_dtvenc as dtvencunic, r.k00_dtoper as dtoperunic,r.k00_percdes,       ";
              $sqlFindUnica .= "                fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")    ";
              $sqlFindUnica .= "           from recibounica r                                                                            ";
              $sqlFindUnica .= "          where r.k00_numpre = {$numpre_vistoria}                                                        ";
              $sqlFindUnica .= "            and r.k00_dtvenc = '$vencunica'                                                              ";
              $sqlFindUnica .= "            and r.k00_dtoper = '$operunica'                                                              ";
              $sqlFindUnica .= "            and k00_percdes  = $percunica                                                                ";
              $sqlFindUnica .= "            and exists (select 1 from arrecad where arrecad.k00_numpre = r.k00_numpre limit 1)) as unica ";
              $resultfin1 = db_query($sqlFindUnica);

              if ($gerar == "dados") {

                 if ($tipotxt == "txt") {
                   fputs($clabre_arquivo->arquivo,"#INICIODASUNICAS#");
                 }
              } else {
                fputs($clabre_arquivo->arquivo, db_contador("EXPRESSAO","EXPRESSAO",$contador,17));
              }

              if ($resultfin1!=false) {

                /**
                 * Gera fincanceiro
                 */
                if (pg_numrows($resultfin1) > 0) {

                  for ($unicontParc1=0; $unicontParc1<pg_numrows($resultfin1); $unicontParc1++) {

                    db_fieldsmemory($resultfin1,$unicontParc1);

                  if ($gerar == "dados") { // if orginal

                    if ($k00_tipo != "3") {

                      $utotal += $taxa_bancaria;
                      db_inicio_transacao();

                      try {

                        $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

                        $oRecibo = new recibo(2,null,6);
                        $oRecibo->addNumpre($k00_numpre,0);
                        $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
                        $oRecibo->setDataRecibo($dtvencunic);
                        $oRecibo->setDataVencimentoRecibo($dtvencunic);
                        $oRecibo->emiteRecibo($lConvenioCobrancaValido);
                        $novo_numpre = $oRecibo->getNumpreRecibo();

                        if ($lConvenioCobrancaValido) {
                          CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
                        }

                      } catch ( Exception $eException ) {

                        db_fim_transacao(true);
                        db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
                        exit;
                      }

                      db_fim_transacao();
                    }

                    if ( $k00_tipo == "3" ) {

                      $DadosPgtoUnica = debitos_numpre_carne($k00_numpre,0,db_getsession('DB_datausu'),db_getsession('DB_anousu'));
                      $k00_numnov     = $k00_numpre;
                    } else {

                      $sWhere         = "arrecad.k00_tipo = $k00_tipo ";
                      $DadosPgtoUnica = debitos_numpre_carne_recibopaga($k00_numpre,0,db_getsession('DB_datausu'),db_getsession('DB_anousu'),db_getsession('DB_instit'),$sWhere);
                    }

                    $oDadosPgtoUnica = db_utils::fieldsMemory($DadosPgtoUnica, 0);
                    $vlrbar          = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

                    $sqlTercdig = "select arretipo.k00_tipo,
                                          case
                                            when k03_tipo = 3 then '7'  else '6'
                                          end as tercdigito
                                     from arrecad
                                          inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo
                                    where arrecad.k00_numpre = $k00_numpre limit 1 ";

                    $rsTercDigito = db_query($sqlTercdig);
                    db_fieldsmemory($rsTercDigito,0);

                     try {

                      $oConvenio       = new convenio($iCodConvenio,$oDadosPgtoUnica->k00_numnov,0,$utotal,$vlrbar,$dtvencunic,$tercdigito);
                      $codigo_barras   = $oConvenio->getCodigoBarra();
                      $linha_digitavel = $oConvenio->getLinhaDigitavel();
                    } catch (Exception $eExeption){

                      db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
                      exit;
                    }

                    // Convênio SICOB
                    if ( $oRegraEmissao->getCadTipoConvenio() == 5 ) {

                      $aNossoNumero    = explode("-",$oConvenio->getNossoNumero());
                      $sNossoNumero    = str_pad($aNossoNumero[0],$iTamNossoNumero,' ',STR_PAD_LEFT);
                      $sDigNossoNumero = str_pad($aNossoNumero[1],1,' ',STR_PAD_LEFT);
                    } else {

                      $sNossoNumero    = str_pad($oConvenio->getNossoNumero(),$iTamNossoNumero,' ',STR_PAD_LEFT);
                      $sDigNossoNumero = ' ';
                    }

//                  if ($gerar == "dados") { // if orginal

                      if ($tipotxt == "txt") {

                        fputs($clabre_arquivo->arquivo,db_formatar($dtvencunic,'d'));
                        fputs($clabre_arquivo->arquivo,str_pad($k00_percdes,6));
                        fputs($clabre_arquivo->arquivo,str_pad(db_formatar($uvlrhis,'f'),15));
                        fputs($clabre_arquivo->arquivo,str_pad(db_formatar($uvlrdesconto,'f'),15));
                        fputs($clabre_arquivo->arquivo,str_pad(db_formatar($utotal,'f'),15));
                        fputs($clabre_arquivo->arquivo,db_numpre($oDadosPgtoUnica->k00_numnov)."000");
                        fputs($clabre_arquivo->arquivo,$sNossoNumero,$iTamNossoNumero);
                        fputs($clabre_arquivo->arquivo,$sDigNossoNumero,1);

                        unset($uvlrhis, $uvlrdesconto, $utotal);
                      } elseif ($tipotxt == "bsjtxt") {

                          $linha20 .= str_pad(db_numpre($oDadosPgtoUnica->k00_numnov)."000",25," ",STR_PAD_RIGHT);
                          $linha20 .= str_pad($oConvenio->getNossoNumero(),13," ",STR_PAD_LEFT);
                          $linha20 .= "00";
                          $linha20 .= substr($dtvencunic,8,2) . substr($dtvencunic,5,2) . substr($dtvencunic,2,2);

                          $k00_valor_imprimir = $utotal + $taxa_bancaria;

                          $linha20 .= str_replace(".","",db_formatar($k00_valor_imprimir,'p','0',16,"e"));
                          $linha20 .= str_repeat("0",11);
                          $linha20 .= str_replace(".","",db_formatar($uvlrdesconto,'p','0',12,"e"));

                          if ($k03_tipo == 2 or $k03_tipo == 3) {
                            $linha20 .= "03";
                          } elseif ($k03_tipo == 19 or $k03_tipo == 5) {
                            $linha20 .= "04";
                          }

                          $linha20 .= str_replace(".","",db_formatar($utotal,'p','0',16,"e"));

                          $linha20 .= "18";
                          $linha20 .= str_replace(".","",db_formatar($taxa_bancaria,'p','0',16,"e"));

                          $linha20 .= str_repeat("0",166);

                          fputs($clabre_arquivo->arquivo, db_contador_bsj($linha20,"",$contador,288));
                          $iTotalLinhas20++;
                          $nTotalParcelas20 += $k00_valor_imprimir;

                          // linha 1
                          // parte 1
                          $linha50 = "BSJR50";

                          $imp_linha50  = "";
                          $imp_linha50 .= "INSCRICAO: $q01_inscr";
                          $linha50     .= substr(str_pad($imp_linha50,55," ",STR_PAD_RIGHT),0,55);

                          // parte 2
                          $imp_linha50  = "";
                          $imp_linha50 .= "ATIVIDADE: $q03_descr";
                          $linha50     .= substr(str_pad($imp_linha50,55," ",STR_PAD_RIGHT),0,55);

                          // parte 3
                          $imp_linha50 = "";
                          $competencia = $anousu;
                          if ($k03_tipo == 3) {

                            $competencia  = str_pad($k00_numpar,2,"0",STR_PAD_LEFT) . "/" . $anousu;
                            $imp_linha50 .= "ALIQ: $aliquota" . "%" . " - COMPET: $competencia";
                          } elseif ($k03_tipo == 2) {

                            $sqltipcalc    = "select q81_descr from ativtipo inner join tipcalc on q81_codigo = q80_tipcal where q80_ativ = $q07_ativ and q81_cadcalc = 2";
                            $resulttipcalc = db_query($sqltipcalc) or die($sqltipcalc);
                            if (pg_numrows($resulttipcalc) > 0) {
                              $q81_descr = pg_result($resulttipcalc,0,0);
                            } else {
                              $q81_descr = "";
                            }

                            $imp_linha50 .= "ALIQ: $q81_descr - COMPET: $competencia";
                          } else {
                            $imp_linha50 .= "COMPET: $competencia";
                          }

                          $linha50     .= substr(str_pad($imp_linha50,55," ",STR_PAD_RIGHT),0,55);

                          // parte 4
                          $imp_linha50 = "";
                          $linha50 .= str_pad($imp_linha50,55," ",STR_PAD_RIGHT);

                          // parte 5
                          $linha50 .= str_repeat(" ",62);
                          fputs($arquivo, db_contador_bsj($linha50,"",$contador,288));

                          // linha 2
                          // parte 1
                          $sqlmsg      = "select k00_msgparc from arretipo where k00_tipo = $tipodebitoarrecad";
                          $result_mesg = db_query($sqlmsg) or die($sqlmsg);
                          $msg         = substr( str_pad( pg_result($result_mesg,0,0),281," ",STR_PAD_RIGHT) ,0,281);

                          $imp_linha50  = "BSJR50";
                          $imp_linha50 .= $msg;

                          $imp_linha50 .= " ";

                          $linha50 = substr(str_pad($imp_linha50,288," ",STR_PAD_RIGHT),0,288);
                          fputs($arquivo, db_contador_bsj($linha50,"",$contador,288));

                          break;
                      }

                    } else {

                      fputs($clabre_arquivo->arquivo, db_contador("VENCIMENTOUNICA".$percunica,          "VENCIMENTO UNICA $percunica"              ,$contador,10));
                      fputs($clabre_arquivo->arquivo, db_contador("PERCENTUALDESCONTOUNICA".$percunica,  "PERCENTUAL DE DESCONTO UNICA $percunica"  ,$contador,6));
                      fputs($clabre_arquivo->arquivo, db_contador("VALORHISTORICOUNICA".$percunica,      "VALOR HISTORICO UNICA $percunica"         ,$contador,15));
                      fputs($clabre_arquivo->arquivo, db_contador("VALORDESCONTOUNICA".$percunica,       "VALOR DO DESCONTO UNICA $percunica"       ,$contador,15));
                      fputs($clabre_arquivo->arquivo, db_contador("TOTALLIQUIDOUNICA".$percunica,        "TOTAL LIQUIDO UNICA $percunica"           ,$contador,15));
                      fputs($clabre_arquivo->arquivo, db_contador("CODIGODEARRECADACAOUNICA".$percunica, "CODIGO DE ARRECADACAO UNICA $percunica"   ,$contador,11));
                      fputs($clabre_arquivo->arquivo, db_contador("NOSSO_NUMERO_PARC{$percunica}",       "NOSSO NUMERO UNICA {$percunica}"          ,$contador,$iTamNossoNumero));
                      fputs($clabre_arquivo->arquivo, db_contador("DG_NOSSO_NUMERO_PARC{$percunica}",    "DIGITO DO NOSSO NUMERO UNICA {$percunica}",$contador,1));
                    }

                    if ($lCobranca) {

                      if ( $k00_numpre == '' || $dtvencunic == '' || $vlrbar == '' ){
                        $fc_febraban = str_repeat('0',101);
                      } else {
                        $fc_febraban = $linha_digitavel.",".$codigo_barras;
                      }

                      $maxcols = 101;

                    } else {
                       $fc_febraban = $codigo_barras.",".$linha_digitavel;
                       $maxcols      = strlen($fc_febraban);
                    }


                   if ($gerar == "dados") {

                     if ($tipotxt == "txt") {

                       $fc_febraban = str_pad($fc_febraban, 101, ' ', STR_PAD_RIGHT);
                       fputs($clabre_arquivo->arquivo,$fc_febraban);
                     }
                   } else {
                     fputs($clabre_arquivo->arquivo, db_contador("CODIGOFEBRABANUNICA".$percunica,"CODIGO FEBRABAN UNICA $percunica",$contador,101));
                   }
                }

              } else { // Caso nao tenha encontrado registro na recibounica

                if ($gerar == "dados") {

                  if ($tipotxt == "txt") {
                    fputs($clabre_arquivo->arquivo,str_repeat(' ',(174 + $iTamNossoNumero)));
                  }
                } else {

                  $sDescricaoTipoVistoria = "LOCALIZACAO";
                  if ($sChave == "S") {
                    $sDescricaoTipoVistoria = "SANITARIO";
                  }

                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_VENCIMENTOUNICA".$percunica,          "$sDescricaoTipoVistoria VENCIMENTO UNICA $percunica"              ,$contador,10));
                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_PERCENTUALDESCONTOUNICA".$percunica,  "$sDescricaoTipoVistoria PERCENTUAL DE DESCONTO UNICA $percunica"  ,$contador,6));
                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_VALORHISTORICOUNICA".$percunica,      "$sDescricaoTipoVistoria VALOR HISTORICO UNICA $percunica"         ,$contador,15));
                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_VALORDESCONTOUNICA".$percunica,       "$sDescricaoTipoVistoria VALOR DO DESCONTO UNICA $percunica"       ,$contador,15));
                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_TOTALLIQUIDOUNICA".$percunica,        "$sDescricaoTipoVistoria TOTAL LIQUIDO UNICA $percunica"           ,$contador,15));
                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_CODIGODEARRECADACAOUNICA".$percunica, "$sDescricaoTipoVistoria CODIGO DE ARRECADACAO UNICA $percunica"   ,$contador,11));
                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_NOSSO_NUMERO_PARC{$percunica}",       "$sDescricaoTipoVistoria NOSSO NUMERO UNICA {$percunica}"          ,$contador,$iTamNossoNumero));
                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_DG_NOSSO_NUMERO_PARC{$percunica}",    "$sDescricaoTipoVistoria DIGITO DO NOSSO NUMERO UNICA {$percunica}",$contador,1));
                  fputs($clabre_arquivo->arquivo, db_contador("{$sDescricaoTipoVistoria}_CODIGOFEBRABANUNICA".$percunica,      "$sDescricaoTipoVistoria CODIGO FEBRABAN UNICA $percunica"         ,$contador,101));
                }
              }

            }// Fim da geração financeira das unicas

          }
}

          if ($gerar == "dados") {

            if ($tipotxt == "txt") {
              fputs($clabre_arquivo->arquivo,"#FIMDASUNICAS#");
            }
          } else {
            fputs($clabre_arquivo->arquivo, db_contador("EXPRESSAO","EXPRESSAO",$contador,14));
          }

        } // Fim das unicas

        if($passaiss) {

          /**
           * Busca o maximo de parcelas do tipo de debito selecionado
           */
          if ($tipo == "fixo") {
            $cadcalc = 2;
          } else if ($tipo == "variavel") {
            $cadcalc = 3;
          }

          if ($tipo == "vistoriasemissqn" || $tipo == "vistoriageral"){
            $maiornumpar = 0;
          } else {

            $sqlMaxParcelas  = " select max(k00_numpar) as maiornumpar                ";
            $sqlMaxParcelas .= "   from arrecad                                       ";
            $sqlMaxParcelas .= "        inner join isscalc on q01_numpre = k00_numpre ";
            $sqlMaxParcelas .= "                          and q01_anousu = ".db_getsession('DB_anousu');
            $sqlMaxParcelas .= "  where q01_cadcal = $cadcalc                         ";
            $sqlMaxParcelas .= "    and k00_tipo = (select k00_tipo                   ";
            $sqlMaxParcelas .= "                      from arrecad                    ";
            $sqlMaxParcelas .= "                     where k00_numpre = $q01_numpre   ";
            $sqlMaxParcelas .= "                     limit 1)                         ";

            $rsMaxParcelas  = db_query($sqlMaxParcelas);
            db_fieldsmemory($rsMaxParcelas,0);
          }

          $passaiss = false;
        }

        /**
         * Passa a referencia da variavel $contador e o atributo $clabre_arquivo->arquivo
         * para q dentro da funcao nao seja perdido seu valor
         * financeiro do issqn
         */
        if($gerar == "dados"){

          for($imax = 1; $imax <= $maiornumpar; $imax++){
            geraArrecad($clabre_arquivo->arquivo,$q01_numpre,$imax,$contador,$gerar,$tipodebitoarrecad,$oRegraEmissao->getCadTipoConvenio(),$tipotxt,$taxa_bancaria,$q01_inscr,$z01_nome,$tipodebitoarrecad,$iTotalLinhas20,$nTotalParcelas20,$contador,$anousu,$q03_descr,$aliquota,$q07_ativ,$oRegraEmissao->getConvenio());
          }
        }else{

          for($imax = 1; $imax <= $maiornumpar; $imax++){
            geraArrecad($clabre_arquivo->arquivo,$q01_numpre,$imax,$contador,$gerar,$tipodebitoarrecad,$oRegraEmissao->getCadTipoConvenio(),$tipotxt,$taxa_bancaria,$q01_inscr,$z01_nome,$tipodebitoarrecad,$iTotalLinhas20,$nTotalParcelas20,$contador,$anousu,$q03_descr,$aliquota,$q07_ativ,$oRegraEmissao->getConvenio());

          }
        }

        if ($gerar == "dados") {

          if ($tipotxt == "txt") {
            fputs($clabre_arquivo->arquivo,str_pad(substr($q03_descr, 0, 40), 40));
          }
        } else {
          fputs($clabre_arquivo->arquivo, db_contador("ATIVIDADEPRINCIPAL","DESCRICAO DA ATIVIDADE PRINCIPAL",$contador,40));
        }

        /**
         * VISTORIAS LOCALIZACAO
         */
        if ($gerar == "dados") {

          if ($tipotxt == "txt" && $lGerarVistorias) {
            fputs($clabre_arquivo->arquivo,"#INICIOVISTORIALOCALIZACAO#");
          }
        } else {

          if($lGerarVistorias){
            fputs($clabre_arquivo->arquivo, db_contador("EXPRESSAO","EXPRESSAO",$contador,27));
          }
        }


        if ($gerar == "dados" ) {

          if ( ( $tipotxt != "bsjtxt" || ($tipotxt == "bsjtxt" && $tipo == "vistoriageral") ) && $lGerarVistorias ) {

            if($intNumrowsVistorias > 0){

              for($iVistorias=0;$iVistorias<$intNumrowsVistorias;$iVistorias++){

                db_fieldsmemory($rsVistorias,$iVistorias);

                if ($tipotxt == "txt") {

                  fputs($clabre_arquivo->arquivo,str_pad($y70_codvist              ,10));                   // codigo da vistoria
                  fputs($clabre_arquivo->arquivo,str_pad(db_formatar($y70_data,'d'),10));                   // data da vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($y70_hora                 ,5));                    // hora da vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($y70_contato              ,50));                   // contato da vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($y70_tipovist             ,10));                   // codigo do tipo de vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($y77_descricao            ,50));                   // descricao do tipo de vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($y70_id_usuario           ,10));                   // codigo do usuario que digitou a vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($nome                     ,50));                   // nome do usuario que digitou a vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($y70_coddepto             ,10));                   // codigo do departamento que digitou a vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($descrdepto               ,50));                   // nome do departamento que digitou a vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($y70_numbloco             ,20));                   // numero do bloco da vistoria
                  fputs($clabre_arquivo->arquivo,str_pad($y69_numpre               ,8, "0", STR_PAD_LEFT)); //  numpre da vistoria
                }

                for($imaxparcelas=1;$imaxparcelas <= $maiornumparvist; $imaxparcelas++){

                  geraArrecad($clabre_arquivo->arquivo,$y69_numpre,$imaxparcelas,$contador,$gerar,"VISTORIA".$y77_descricao,$oRegraEmissao->getCadTipoConvenio(),$tipotxt,$taxa_bancaria,$q01_inscr,$z01_nome,$tipodebitoarrecad,$iTotalLinhas20,$nTotalParcelas20,$contador,$anousu,$q03_descr,$aliquota,$q07_ativ,$oRegraEmissao->getConvenio());

                }
              }

            }else{

              // 437 283
              //fputs($clabre_arquivo->arquivo,str_repeat(' ',437));
              // 154 se refere aos dados do arrecad gerados pela funcao geraArrecad
              if (  $maiornumparvist > 0 ) {

                if ($tipotxt == "txt") {
                  fputs($clabre_arquivo->arquivo,str_repeat(' ',448));
                }
              } else {

                if ($tipotxt == "txt") {
                  fputs($clabre_arquivo->arquivo,str_repeat(' ',283));
                }
              }

            }
          }

        }else{

          if( $lGerarVistorias ){

            /**
             * Gera layout
             */
            $y69_numpre    = 0;
            $y77_descricao = "";

            fputs($clabre_arquivo->arquivo, db_contador("CODIGOVISTORIA",        "CODIGO DA VISTORIA",        $contador                    ,10));
            fputs($clabre_arquivo->arquivo, db_contador("DATAVISTORIA",          "DATA DA VISTORIA",          $contador                    ,10));
            fputs($clabre_arquivo->arquivo, db_contador("HORAVISTORIA",          "HORA DA VISTORIA",          $contador                    ,5));
            fputs($clabre_arquivo->arquivo, db_contador("CONTATOVISTORIA",       "CONTATO DA VISTORIA",       $contador                    ,50));
            fputs($clabre_arquivo->arquivo, db_contador("CODIGOTIPOVISTORIA",    "CODIGO DO TIPO DE VISTORIA",$contador                    ,10));
            fputs($clabre_arquivo->arquivo, db_contador("DESCRICAOTIPOVISTORIA", "DESCRICAO DO TIPO DE VISTORIA",$contador                 ,50));
            fputs($clabre_arquivo->arquivo, db_contador("CODIGOUSUARIOVISTORIA", "CODIGO DO USUARIO QUE DIGITOU A VISTORIA",$contador      ,10));
            fputs($clabre_arquivo->arquivo, db_contador("NOMEUSUARIOVISTORIA",   "NOME DO USUARIO QUE DIGITOU A VISTORIA",$contador        ,50));
            fputs($clabre_arquivo->arquivo, db_contador("CODIGODEPARTVISTORIA",  "CODIGO DO DEPARTAMENTO QUE DIGITOU A VISTORIA",$contador ,10));
            fputs($clabre_arquivo->arquivo, db_contador("NOMEDEPARTVISTORIA",    "NOME DO DEPARTAMENTO QUE DIGITOU A VISTORIA",$contador   ,50));
            fputs($clabre_arquivo->arquivo, db_contador("NUMEROBLOCO",           "NUMERO DO BLOCO DA VISTORIA",$contador                   ,20));
            fputs($clabre_arquivo->arquivo, db_contador("NUMPREVISTORIA",        "NUMPRE DA VISTORIA",$contador                            ,8));

            for($imaxparcelas=1;$imaxparcelas <= $maiornumparvist; $imaxparcelas++){

              geraArrecad($clabre_arquivo->arquivo,$y69_numpre,$imaxparcelas,$contador,$gerar,"VISTORIA".$y77_descricao,$oRegraEmissao->getCadTipoConvenio(),$tipotxt,$taxa_bancaria,$q01_inscr,$z01_nome,$tipodebitoarrecad,$iTotalLinhas20,$nTotalParcelas20,$contador,$anousu,$q03_descr,$aliquota,$q07_ativ,$oRegraEmissao->getConvenio());

            }
          }
        }

        if ($gerar == "dados") {

          if ($tipotxt == "txt" && $lGerarVistorias) {
            fputs($clabre_arquivo->arquivo,"#INICIOVISTORIASANITARIO#");
          }
        } else {

          if($lGerarVistorias){
            fputs($clabre_arquivo->arquivo, db_contador("EXPRESSAO","EXPRESSAO",$contador,25));
          }
        }

        /**
         * VISTORIAS DE SANITARIO
         */
          /* VERIFICA SE EXISTE VISTORIAS DE SANITARIO PARA ESSA INSCRICAO */
                                                       //  TAM | LABEL NO LAYOUT
                                                       //----------------------------
          $sqlSanitario  = " select y70_codvist,    ";   //  10    CODIGO DA VISTORIA
          $sqlSanitario .= "        y70_data,       ";   //  10    DATA DA VISTORIA
          $sqlSanitario .= "        y70_hora,       ";   //   5    HORA DA VISTORIA
          $sqlSanitario .= "        y70_contato,    ";   //  50    CONTATO DA VISTORIA
          $sqlSanitario .= "        y70_tipovist,   ";   //  10    CODIGO DO TIPO DE VISTORIA
          $sqlSanitario .= "        y77_descricao,  ";   //  50    DESCRICAO DO TIPO DE VISTORIA
          $sqlSanitario .= "        y70_id_usuario, ";   //  10    CODIGO USUARIO QUE DIGITOU A VISTORIA
          $sqlSanitario .= "        nome,           ";   //  50    NOME DO USUARIO QUE DIGITOU A VISTORIA
          $sqlSanitario .= "        y70_coddepto,   ";   //  10    CODIGO DO DEPARTAMENTO QUE DIGITOU A VISTORIA
          $sqlSanitario .= "        descrdepto,     ";   //  50    NOME DO DEPARTAMENTO QUE DIGITOU A VISTORIA
          $sqlSanitario .= "        y70_numbloco,   ";   //  20    NUMERO DO BLOCO DA VISTORIA
          $sqlSanitario .= "        y70_parcial,    ";   //
          $sqlSanitario .= "        y69_numpre      ";   //   8    NUMPRE DA VISTORIA
          $sqlSanitario .= "  from vistorias        ";
          $sqlSanitario .= "        inner join tipovistorias  on tipovistorias.y77_codtipo  = vistorias.y70_tipovist     ";
          $sqlSanitario .= "        inner join db_depart      on vistorias.y70_coddepto     = db_depart.coddepto         ";
          $sqlSanitario .= "        inner join db_usuarios    on db_usuarios.id_usuario     = vistorias.y70_id_usuario   ";
          $sqlSanitario .= "        inner join vistsanitario  on vistsanitario.y74_codvist  = vistorias.y70_codvist      ";
          $sqlSanitario .= "        inner join sanitarioinscr on sanitarioinscr.y18_codsani = vistsanitario.y74_codsani  ";
          $sqlSanitario .= "        inner join sanitario      on sanitario.y80_codsani      = sanitarioinscr.y18_codsani ";
          $sqlSanitario .= "        inner join vistorianumpre on vistorianumpre.y69_codvist = vistorias.y70_codvist      ";
          $sqlSanitario .= " where extract(year from y70_data) = ".db_getsession('DB_anousu');
          $sqlSanitario .= "   and y70_ativo is true       ";
          $sqlSanitario .= "   and y70_parcial is false    ";
          $sqlSanitario .= "   and y18_inscr  = $q01_inscr ";
          $sqlSanitario .= "   and (select count(*) from arrecad where arrecad.k00_numpre = vistorianumpre.y69_numpre) > 0";

          $rsSanitario = db_query($sqlSanitario);
          $intNumrowsSanitario = pg_numrows($rsSanitario);

          if ( $lGerarVistorias ){

            if ($gerar == "dados") {

              if($intNumrowsSanitario > 0){

                for($iSanitario=0;$iSanitario<$intNumrowsSanitario;$iSanitario++){

                  db_fieldsmemory($rsSanitario,$iSanitario);

                  if ($tipotxt == "txt") {

                    fputs($clabre_arquivo->arquivo,str_pad($y70_codvist,10));                   //  "CODIGO DA VISTORIA",
                    fputs($clabre_arquivo->arquivo,str_pad(db_formatar($y70_data,'d'),10));     //  "DATA DA VISTORIA",
                    fputs($clabre_arquivo->arquivo,str_pad($y70_hora,5));                       //  "HORA DA VISTORIA",
                    fputs($clabre_arquivo->arquivo,str_pad($y70_contato,50));                   //  "CONTATO DA VISTORIA",
                    fputs($clabre_arquivo->arquivo,str_pad($y70_tipovist,10));                  //  "CODIGO DO TIPO DE VISTORIA"
                    fputs($clabre_arquivo->arquivo,str_pad($y77_descricao,50));                 //  "DESCRICAO DO TIPO DE VISTORIA"
                    fputs($clabre_arquivo->arquivo,str_pad($y70_id_usuario,10));                //  "CODIGO DO USUARIO QUE DIGITOU A VISTORIA"
                    fputs($clabre_arquivo->arquivo,str_pad($nome,50));                          //  "NOME DO USUARIO QUE DIGITOU A VISTORIA"
                    fputs($clabre_arquivo->arquivo,str_pad($y70_coddepto,10));                  //  "CODIGO DO DEPARTAMENTO QUE DIGITOU A VISTORIA"
                    fputs($clabre_arquivo->arquivo,str_pad($descrdepto,50));                    //  "NOME DO DEPARTAMENTO QUE DIGITOU A VISTORIA"
                    fputs($clabre_arquivo->arquivo,str_pad($y70_numbloco,20));                  //  "NUMERO DO BLOCO DA VISTORIA"
                    fputs($clabre_arquivo->arquivo,str_pad($y69_numpre, 8, "0", STR_PAD_LEFT)); //  "NUMPRE DA VISTORIA"
                  }

                  for($imaxparcelassani=1;$imaxparcelassani <= $maiornumparsani; $imaxparcelassani++){

                    geraArrecad($clabre_arquivo->arquivo,$y69_numpre,$imaxparcelassani,$contador,$gerar,"VISTORIA".$y77_descricao,$oRegraEmissao->getCadTipoConvenio(),$tipotxt,$taxa_bancaria,$q01_inscr,$z01_nome,$tipodebitoarrecad,$iTotalLinhas20,$nTotalParcelas20,$contador,$anousu,$q03_descr,$aliquota,$q07_ativ,$oRegraEmissao->getConvenio());

                  }
                }
              }else{

                if ( $maiornumparsani > 0 ) {

                  if ($tipotxt == "txt") {
                    fputs($clabre_arquivo->arquivo,str_repeat(' ',448));
                  }
                } else {

                  if ($tipotxt == "txt") {
                    fputs($clabre_arquivo->arquivo,str_repeat(' ',283));
                  }
                }

              }
            }else{

              /**
               * Geração layout
               */
              fputs($clabre_arquivo->arquivo, db_contador("CODIGOVISTORIASANITARIO",        "CODIGO DA VISTORIA SANITARIO",        $contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("DATAVISTORIASANITARIO",          "DATA DA VISTORIA SANITARIO",          $contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("HORAVISTORIASANITARIO",          "HORA DA VISTORIA SANITARIO",          $contador,5));
              fputs($clabre_arquivo->arquivo, db_contador("CONTATOVISTORIASANITARIO",       "CONTATO DA VISTORIA SANITARIO",       $contador,50));
              fputs($clabre_arquivo->arquivo, db_contador("CODIGOTIPOVISTORIASANITARIO",    "CODIGO DO TIPO DE VISTORIA SANITARIO",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("DESCRICAOTIPOVISTORIASANITARIO", "DESCRICAO DO TIPO DE VISTORIA SANITARIO",$contador,50));
              fputs($clabre_arquivo->arquivo, db_contador("CODIGOUSUARIOVISTORIASANITARIO", "CODIGO DO USUARIO QUE DIGITOU A VISTORIA SANITARIO",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("NOMEUSUARIOVISTORIASANITARIO",   "NOME DO USUARIO QUE DIGITOU A VISTORIA SANITARIO",$contador,50));
              fputs($clabre_arquivo->arquivo, db_contador("CODIGODEPARTVISTORIASANITARIO",  "CODIGO DO DEPARTAMENTO QUE DIGITOU A VISTORIA SANITARIO",$contador,10));
              fputs($clabre_arquivo->arquivo, db_contador("NOMEDEPARTVISTORIASANITARIO",    "NOME DO DEPARTAMENTO QUE DIGITOU A VISTORIA SANITARIO",$contador,50));
              fputs($clabre_arquivo->arquivo, db_contador("NUMEROBLOCOVISTORIASANITARIO",   "NUMERO DO BLOCO DA VISTORIA SANITARIO",$contador,20));
              fputs($clabre_arquivo->arquivo, db_contador("NUMPREVISTORIASANITARIO",        "NUMPRE DA VISTORIA SANITARIO",$contador,8));

              for($imaxparcelassani=1;$imaxparcelassani <= $maiornumparsani; $imaxparcelassani++){

                geraArrecad($clabre_arquivo->arquivo,$y69_numpre,$imaxparcelassani,$contador,$gerar,"VISTORIASANITARIO".$y77_descricao,$oRegraEmissao->getCadTipoConvenio(),$tipotxt,$taxa_bancaria,$q01_inscr,$z01_nome,$tipodebitoarrecad,$iTotalLinhas20,$nTotalParcelas20,$contador,$anousu,$q03_descr,$aliquota,$q07_ativ,$oRegraEmissao->getConvenio());

              }

            }
          }

          if ( $gerar == "dados" ) {

            if ($tipotxt == "txt") {

              fputs($clabre_arquivo->arquivo, $sAgencia       ,5);
              fputs($clabre_arquivo->arquivo, $sDigAgencia    ,1);
              fputs($clabre_arquivo->arquivo, $sOperacao      ,3);
              fputs($clabre_arquivo->arquivo, $sCedente       ,$iTamCedente);
              fputs($clabre_arquivo->arquivo, $sDigCedente    ,1);
              fputs($clabre_arquivo->arquivo, $sCarteira      ,$iTamCarteira);
              fputs($clabre_arquivo->arquivo, $sConvenio      ,$iTamConvenio);
              fputs($clabre_arquivo->arquivo, date('d/m/Y',db_getsession('DB_datausu')),10);
              fputs($clabre_arquivo->arquivo, str_pad($oRegraEmissao->getNomeConvenio(),50," ",STR_PAD_RIGHT),50);
            }

          } else {

            fputs($clabre_arquivo->arquivo, db_contador("AGENCIA"            ,"AGENCIA DO CONVENIO"       ,$contador,5));
            fputs($clabre_arquivo->arquivo, db_contador("DG_AGENCIA"         ,"DIGITO DA AGENCIA"         ,$contador,1));
            fputs($clabre_arquivo->arquivo, db_contador("OPERACAO"           ,"OPERACAO DO CONVENIO"      ,$contador,3));
            fputs($clabre_arquivo->arquivo, db_contador("CEDENTE"            ,"CEDENTE DO CONVENIO"       ,$contador,$iTamCedente));
            fputs($clabre_arquivo->arquivo, db_contador("DG_CEDENTE"         ,"DIGITO DO CEDENTE"         ,$contador,1));
            fputs($clabre_arquivo->arquivo, db_contador("CARTEIRA"           ,"CARTEIRA DO CONVENIO"      ,$contador,$iTamCarteira));
            fputs($clabre_arquivo->arquivo, db_contador("CONVENIO"           ,"CONVENIO"                  ,$contador,$iTamConvenio));
            fputs($clabre_arquivo->arquivo, db_contador("DATA_PROCESSAMENTO" ,"DATA DO PROCESSAMENTO"     ,$contador,10));
            fputs($clabre_arquivo->arquivo, db_contador("DESCRICAO_CONVENIO" ,"DESCRICAO DO CONVENIO"     ,$contador,50));

          }

          if ($tipotxt == "txt") {
            fputs($clabre_arquivo->arquivo,"{$sQuebraLinha}");
          }

        }

        if ($gerar == "layout" && $quantos >= 1) {
          break;
        }

        unset($numpre_sanitario, $q01_inscr, $aNumpres);
      } // Fim do for das inscrições

      db_atutermometro(99, 100, 'termometro');
      flush();

      if ($tipotxt == "bsjtxt") {

        $linha90 = "";
        $linha90 .= "BSJR90";
        $linha90 .= str_pad($iTotalLinhas20,7,"0",STR_PAD_LEFT);
        $linha90 .= str_pad(str_replace(".","",   db_formatar($nTotalParcelas20,'p','0',15) ),15,"0",STR_PAD_LEFT);
        $linha90 .= str_pad(0,7,"0",STR_PAD_LEFT);
        $linha90 .= str_pad(0,15,"0",STR_PAD_LEFT);
        $linha90 .= str_repeat(" ",238);

        fputs($clabre_arquivo->arquivo, db_contador_bsj($linha90,"",$contador,288));
      }

      fclose($clabre_arquivo->arquivo);
    } else {

      $erro = true;
      $descricao_erro = 'Erro ao gerar arquivo.';
    }

  } // Fim do for de layout / dados

} // Fim do if principal da geração

echo "<script>";

if ( $erro ) {

  echo "alert('erro: {$descricao_erro}');";
  echo "parent.db_iframe_carne.hide(); ";

} else {

  echo "  listagem = '$arqnomes';";
  echo "  parent.js_montarlista(listagem,'form1');";
}

echo "</script>";

function db_contador($apelido, $expressao, $contador, $valor) {

  $sQuebraLinha = "\r\n";
  global $contador;
  $contadorant = $contador + 1;
  $contador+=$valor;
  return str_pad($apelido,50) . " - " . str_pad($expressao,90) . " - " . str_pad($valor,4,"0",STR_PAD_LEFT) . " - " . str_pad($contadorant,4,"0",STR_PAD_LEFT) . " - " . str_pad($contador,4,"0",STR_PAD_LEFT) . "{$sQuebraLinha}";
}

function db_contador_bsj($apelido, $expressao, $contador, $valor) {

  global $contador, $contadorgeral;
  $sQuebraLinha = "\r\n";
  $contadorant  = $contador + 1;
  $contador    += $valor;

  return str_pad($apelido,30) . "{$sQuebraLinha}";
}

function geraArrecad(&$arquivo,$numpre,$numpar,&$dbcont,$tipogerar,$complementoidentificador,$iTipoConvenio,$tipotxt,$taxa_bancaria,$q01_inscr,$z01_nome,$tipodebitoarrecad,$iTotalLinhas20,$nTotalParcelas20,$contador,$anousu,$q03_descr,$aliquota,$q07_ativ,$oRegraEmissaoConvenio) {

  global $segmento;
  global $formvencfebraban;
  global $k00_dtvenc;
  global $k00_valor;
  global $k00_codbco;
  global $k00_codage;

  global $k00_numpar;
  global $k00_numpre;
  global $k00_tipo;
  global $k03_tipo;
  global $fc_febraban;
  global $tercdigito;
  global $erro;
  global $codigobarras;
  global $linhadigitavel;
  global $maxcols;
  global $iCodConvenio;
  global $lCobranca;
  global $iCodConvenioCobranca;

  global $sAgencia;
  global $sDigAgencia;
  global $sOperacao;
  global $sCedente;
  global $iTamCedente;
  global $sDigCedente;
  global $sCarteira;
  global $iTamCarteira;
  global $sConvenio;
  global $iTamConvenio;
  global $iTamNossoNumero;
  global $iTotalLinhas20;
  global $nTotalParcelas20;
  global $q81_descr;


  if (!class_exists("db_utils")) {
    require_once(modification("libs/db_utils.php"));
  }


  // pega os dados da instituicao

  db_sel_instit();

  flush();

  if ( $iTipoConvenio == 2 ) {
    $iTamNossoNumero = 13;
  } else {
    $iTamNossoNumero = 10;
  }

  if ( $tipogerar == "dados" ) {

    $sqlGeraArrecad  = " select a.k00_numpre,                                            ";
    $sqlGeraArrecad .= "        k00_numpar,                                              ";
    $sqlGeraArrecad .= "        k00_numtot,                                             ";
    $sqlGeraArrecad .= "        k00_numdig,                                                  ";
    $sqlGeraArrecad .= "        k00_dtvenc,                                                ";
    $sqlGeraArrecad .= "        k00_codbco,                                                ";
    $sqlGeraArrecad .= "        k00_codage,                                                ";
    $sqlGeraArrecad .= "        sum(k00_valor)::float8 as k00_valor                          ";
    $sqlGeraArrecad .= "   from arreinscr m                                              ";
    $sqlGeraArrecad .= "        inner join arrecad a on m.k00_numpre      = a.k00_numpre ";
    $sqlGeraArrecad .= "        inner join arretipo  on arretipo.k00_tipo = a.k00_tipo   ";
    $sqlGeraArrecad .= "  where m.k00_numpre = $numpre                                   ";
    $sqlGeraArrecad .= "    and a.k00_numpar = $numpar                                   ";
    $sqlGeraArrecad .= "  group by k00_codbco,                                           ";
    $sqlGeraArrecad .= "           k00_codage,                                           ";
    $sqlGeraArrecad .= "           a.k00_numpre,                                         ";
    $sqlGeraArrecad .= "           k00_numpar,                                           ";
    $sqlGeraArrecad .= "           k00_numtot,                                           ";
    $sqlGeraArrecad .= "           k00_numdig,                                           ";
    $sqlGeraArrecad .= "           k00_dtvenc                                            ";

    $resultfin = db_query($sqlGeraArrecad) or die($sqlGeraArrecad);

    if( $resultfin ){

      if (pg_numrows($resultfin) > 0) {

        if ($tipotxt == "txt") {
          fputs($arquivo,str_pad($numpar,3,"0",STR_PAD_LEFT));
        }
        db_fieldsmemory($resultfin, 0);

        // Gerando recibopaga
        $iNumpre           = $k00_numpre;
        $iNumpar           = $k00_numpar;
        $dVencimentoRecibo = $k00_dtvenc;
        $iCodBco           = $k00_codbco;
        $iCodAge           = $k00_codage;

        db_inicio_transacao();

        try {

          $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissaoConvenio);

          $oRecibo = new recibo(2,null,6);
          $oRecibo->addNumpre($iNumpre,$iNumpar);
          $oRecibo->setNumBco($iCodConvenioCobranca);
          $oRecibo->setDataVencimentoRecibo($dVencimentoRecibo);
          $oRecibo->emiteRecibo($lConvenioCobrancaValido);
          $novo_numpre = $oRecibo->getNumpreRecibo();

          if ($lConvenioCobrancaValido) {
            CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissaoConvenio);
          }

        } catch ( Exception $eException ) {
          db_fim_transacao(true);
          db_redireciona("db_erros.php?fechar=true&db_erro={$eException->getMessage()}");
          exit;
        }

        db_fim_transacao();

        $numpre  = db_numpre($novo_numpre).str_pad($k00_numpar,3,"0",STR_PAD_LEFT);
        $numpref = db_numpre($novo_numpre).str_pad(0,3,"0",STR_PAD_LEFT);

        $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

        $sqlTercdig  = " select arretipo.k00_tipo,                                         ";
        $sqlTercdig .= "        case                                                       ";
        $sqlTercdig .= "          when k03_tipo = 3                                        ";
        $sqlTercdig .= "            then '7'                                               ";
        $sqlTercdig .= "          else '6'                                                 ";
        $sqlTercdig .= "       end as tercdigito                                           ";
        $sqlTercdig .= "  from arrecad                                                     ";
        $sqlTercdig .= "       inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
        $sqlTercdig .= " where arrecad.k00_numpre = $k00_numpre limit 1                    ";

         $rsTercDigito = db_query($sqlTercdig);
        db_fieldsmemory($rsTercDigito,0);
         $k00_numpre = $novo_numpre;

        try {
          $oConvenio = new convenio($iCodConvenio,$k00_numpre,0,$k00_valor,$vlrbar,$k00_dtvenc,$tercdigito);
        } catch (Exception $eExeption){
          db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
          exit;
        }

        $codigo_barras   = $oConvenio->getCodigoBarra();
        $linha_digitavel = $oConvenio->getLinhaDigitavel();

        if( $lCobranca ){

          if ( $k00_numpre == '' || $k00_dtvenc == '' || $vlrbar == '' ){
            $fc_febraban = str_repeat('0',101);
          } else {
             $fc_febraban = $linha_digitavel.",".$codigo_barras;
          }

          $maxcols = 101;

        } else {
          $fc_febraban = $codigo_barras.",".$linha_digitavel;
          $maxcols      = strlen($fc_febraban);
        }

        if ($tipotxt == "txt") {
          fputs($arquivo,db_formatar($k00_dtvenc  ,'d'));
          fputs($arquivo,db_formatar($k00_valor,'f',' ',18));
          fputs($arquivo,$numpref);
          fputs($arquivo,$fc_febraban);
        }

        // Convênio SICOB
        if ( $iTipoConvenio == 5 ) {
          $aNossoNumero    = explode("-",$oConvenio->getNossoNumero());
          $sNossoNumero    = $aNossoNumero[0];
          $sDigNossoNumero = $aNossoNumero[1];
        } else {
          $sNossoNumero    = $oConvenio->getNossoNumero();
          $sDigNossoNumero = ' ';
        }

        if ($tipotxt == "txt") {
          fputs($arquivo,str_pad($sNossoNumero,$iTamNossoNumero," ",STR_PAD_LEFT));
          fputs($arquivo,str_pad($sDigNossoNumero,1            ," ",STR_PAD_LEFT));
          fputs($arquivo,"#FIMDOPARCELADO#");
        }

        // Convênio SICOB
        if ( $iTipoConvenio == 5 ) {

          $iTamCarteira = 2;
          $iTamCedente  = 8;
          $iTamConvenio = 4;

          if ( $tipogerar == "dados" ) {
            $aCarteira   = explode("-",$oConvenio->getCarteira());
            $iConvenio   = ($oConvenio->getConvenioCobranca()==0?' ':$oConvenio->getConvenioCobranca());
            $sAgencia    = str_pad($oConvenio->getCodAgencia()              ,5,"0",STR_PAD_LEFT);
            $sDigAgencia = str_pad($oConvenio->getDigitoAgencia()           ,1," ",STR_PAD_LEFT);
            $sOperacao   = str_pad($oConvenio->getOperacao()                ,3,"0",STR_PAD_LEFT);
            $sCedente    = str_pad($oConvenio->getCedente()      ,$iTamCedente,"0",STR_PAD_LEFT);
            $sDigCedente = str_pad($oConvenio->getDigitoCedente()           ,1," ",STR_PAD_LEFT);
            $sCarteira   = str_pad($aCarteira[0]                ,$iTamCarteira," ",STR_PAD_LEFT);
            $sConvenio   = str_pad($iConvenio                   ,$iTamConvenio," ",STR_PAD_LEFT);
          }

        // Convênio BSJ,BDL
        } else if ( in_array($iTipoConvenio,array(1,2)) ) {

          if ($tipotxt == "txt") { // ###falta###

            $iTamCarteira = 6;

            $sSqlCedente  = "select ar13_cedente                                        ";
            $sSqlCedente .= "  from conveniocobranca                                    ";
            $sSqlCedente .= " where ar13_cadconvenio = {$iTipoConvenio} ";

            $rsCendete    = db_query($sSqlCedente) or die($sSqlCedente);

            if ( pg_num_rows($rsCendete) > 0 ) {
              $iTamCedente = strlen(pg_result($rsCendete,'ar13_cedente',0));
            } else {
              $iTamCedente = 7;
            }

            if ( $iTipoConvenio == 1 ) {
              $iTamConvenio = 7;
            } else {
              $iTamConvenio = 4;
            }

            if ( $tipogerar == "dados" ) {
              $iConvenio   = ($oConvenio->getConvenioCobranca()==0?' ':$oConvenio->getConvenioCobranca());
              $aCarteira   = explode("-",$oConvenio->getCarteira());
              $aAgencia    = explode("-",$oConvenio->getCodAgencia());
              $sAgencia    = str_pad($aAgencia[0]                                 ,5,"0",STR_PAD_LEFT);
              $sDigAgencia = str_pad($aAgencia[1]                                 ,1," ",STR_PAD_LEFT);
              $sOperacao   = str_pad($oConvenio->getOperacao()                    ,3,"0",STR_PAD_LEFT);
              $sCedente    = str_pad($oConvenio->getCedente()          ,$iTamCedente," ",STR_PAD_LEFT);
              $sDigCedente = str_pad($oConvenio->getDigitoCedente()               ,1," ",STR_PAD_LEFT);
              $sCarteira   = str_pad($aCarteira[0]                    ,$iTamCarteira," ",STR_PAD_LEFT);
              $sConvenio   = str_pad($iConvenio                       ,$iTamConvenio," ",STR_PAD_LEFT);
            }

          }

        // Demais Convênios ARRECADAÇÃO, CAIXA PADRÃO etc.
        } else {

          $iTamCarteira    = 6;
          $iTamCedente     = 6;
          $iTamConvenio    = 4;

          if ( $tipogerar == "dados" ) {
            $aAgencia    = explode("-",$oConvenio->getCodAgencia());
            $sAgencia    = str_pad($aAgencia[0],5     ,"0",STR_PAD_LEFT);
            $sDigAgencia = str_pad($aAgencia[1],1     ," ",STR_PAD_LEFT);
            $sOperacao   = str_pad("",3               ," ",STR_PAD_LEFT);
            $sCedente    = str_pad("",$iTamCedente    ," ",STR_PAD_LEFT);
            $sDigCedente = str_pad("",1               ," ",STR_PAD_LEFT);
            $sCarteira   = str_pad("",$iTamCarteira   ," ",STR_PAD_LEFT);
            $sConvenio   = str_pad($oConvenio->getConvenioArrecadacao(),$iTamConvenio," ",STR_PAD_LEFT);
          }
        }

        if ($tipotxt == "bsjtxt") {

          $linha21 = "BSJR20";
          $linha21 .= str_pad($k00_numpre . $k00_numpar,25," ",STR_PAD_RIGHT);
          $linha21 .= str_pad($oConvenio->getNossoNumero(),13," ",STR_PAD_LEFT);
          $linha21 .= str_pad($k00_numpar,2,"0", STR_PAD_LEFT);
          $linha21 .= substr($k00_dtvenc,8,2) . substr($k00_dtvenc,5,2) . substr($k00_dtvenc,2,2);

          $k00_valor_imprimir = $k00_valor;
          if ($k03_tipo != 3) {
            $k00_valor_imprimir += $taxa_bancaria;
          }

          $linha21 .= str_replace(".","",db_formatar($k00_valor_imprimir,'p','0',16,"e"));
          $linha21 .= str_repeat("0",11);
          $linha21 .= str_repeat("0",11);

          if ($k03_tipo == 2 or $k03_tipo == 3) {
            $linha21 .= "03";
          } elseif ($k03_tipo == 19 or $k03_tipo == 5) {
            $linha21 .= "04";
          }

          $linha21 .= str_replace(".","",db_formatar($k00_valor,'p','0',16,"e"));

          $linha21 .= "18";
          $linha21 .= str_replace(".","",db_formatar($taxa_bancaria,'p','0',16,"e"));

          $linha21 .= str_repeat("0",165);

          fputs($arquivo, db_contador_bsj($linha21,"",$contador,288));
          $iTotalLinhas20++;
          $nTotalParcelas20 += $k00_valor_imprimir;


          // linha 1
          // parte 1
          $linha50 = "BSJR50";

          $imp_linha50 = "";
          $imp_linha50 .= "INSCRICAO: $q01_inscr";
          $linha50     .= substr(str_pad($imp_linha50,55," ",STR_PAD_RIGHT),0,55);

          // parte 2
          $imp_linha50 = "";
          $imp_linha50 .= "ATIVIDADE: $q03_descr";
          $linha50     .= substr(str_pad($imp_linha50,55," ",STR_PAD_RIGHT),0,55);

          // parte 3
          $imp_linha50 = "";
          $competencia = $anousu;
          if ($k03_tipo == 3) {
            $competencia = str_pad($k00_numpar,2,"0",STR_PAD_LEFT) . "/" . $anousu;
            $imp_linha50 .= "ALIQ: $aliquota" . "%" . " - COMPET: $competencia";
          } elseif ($k03_tipo == 2) {
            $sqltipcalc = "select q81_descr from ativtipo inner join tipcalc on q81_codigo = q80_tipcal where q80_ativ = $q07_ativ and q81_cadcalc = 2";
            $resulttipcalc = db_query($sqltipcalc) or die($sqltipcalc);
            if (pg_numrows($resulttipcalc) > 0) {
              $q81_descr = pg_result($resulttipcalc,0,0);
            } else {
              $q81_descr = "";
            }
            $imp_linha50 .= "ALIQ: $q81_descr - COMPET: $competencia";
          } else {
            $imp_linha50 .= "COMPET: $competencia";
          }
          $linha50     .= substr(str_pad($imp_linha50,55," ",STR_PAD_RIGHT),0,55);

          // parte 4
          $imp_linha50 = "";
          $linha50 .= str_pad($imp_linha50,55," ",STR_PAD_RIGHT);

          // parte 5
          $linha50 .= str_repeat(" ",62);
          fputs($arquivo, db_contador_bsj($linha50,"",$contador,288));

          // linha 2
          // parte 1
          $sqlmsg = "select k00_msgparc from arretipo where k00_tipo = $tipodebitoarrecad";
          $result_mesg = db_query($sqlmsg) or die($sqlmsg);
          $msg = substr( str_pad( pg_result($result_mesg,0,0),281," ",STR_PAD_RIGHT) ,0,281);

          $imp_linha50 = "BSJR50";
          $imp_linha50 .= $msg;

          $imp_linha50 .= " ";

          $linha50 = substr(str_pad($imp_linha50,288," ",STR_PAD_RIGHT),0,288);
          fputs($arquivo, db_contador_bsj($linha50,"",$contador,288));
        }

      } else {
        if ($tipotxt == "txt") {
          fputs($arquivo,str_repeat(" ",( 155 + $iTamNossoNumero )));
        }
      }
    }
  } else {

    fputs($arquivo, db_contador("PARCELA$numpar",                                                 "PARCELA $numpar",                                                    $dbcont,3));
    fputs($arquivo, db_contador("VENCIMENTO".$complementoidentificador.$numpar,                   "VENCIMENTO DA PARCELA".$complementoidentificador. $numpar,           $dbcont,10));
    fputs($arquivo, db_contador("VALOR".$complementoidentificador.$numpar,                        "VALOR DA PARCELA".$complementoidentificador.$numpar,                 $dbcont,18));
    fputs($arquivo, db_contador("CODIGODEARRECADACAO".$complementoidentificador.$numpar,          "CODIGO DE ARRECADACAO DA PARCELA ".$complementoidentificador.$numpar,$dbcont,11));
    fputs($arquivo, db_contador("LINHADIGITAVELCODIGODEBARRAS".$complementoidentificador.$numpar, "LINHA DIGITAVEL E CODIGO DE BARRAS DA PARCELA ".$complementoidentificador.$numpar,$dbcont,$maxcols));
    fputs($arquivo, db_contador("NOSSO_NUMERO_PARC{$numpar}",                                     "NOSSO NUMERO PARCELA {$numpar}",                                     $dbcont,$iTamNossoNumero));
    fputs($arquivo, db_contador("DG_NOSSO_NUMERO_PARC{$numpar}",                                  "DIGITO DO NOSSO NUMERO PARCELA {$numpar}",                           $dbcont,1));
    fputs($arquivo, db_contador("EXPRESSAO",                                                      "EXPRESSAO",                                                          $dbcont,16));

  }
}

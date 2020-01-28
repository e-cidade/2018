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

require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_libtributario.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/recibo.model.php"));

db_app::import("exceptions.*");

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

if(isset($_GET['sessao'])){

  $aDados = $_SESSION[$_GET['sessao']];

  require_once(modification("ext/php/adodb-time.inc.php"));

  if(isset($_SESSION["DB_datausu"])){
    $DB_DATACALC = adodb_mktime(0,0,0,date("m",db_getsession("DB_datausu")),date("d",db_getsession("DB_datausu")),date("Y",db_getsession("DB_datausu")));
  }
} else {
  $aDados = $_POST;
}

$oPost = db_utils::postMemory($aDados);

db_postmemory($aDados);
db_postmemory($_GET);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$vlrjuros  = 0;
$vlrmulta  = 0;
$nDesconto = 0;
$nValorTot = 0;
$obs       = "";
$total     = 0;
$ninfla    = 0;
$qinfla    = 0;

$sMatriculaEndereco = "";
$sMatriculaBairro   = "";

$cldb_config = new cl_db_config;
$clmodcarne  = new cl_modcarne;
$cldb_bancos = new cl_db_bancos;

if (isset($oPost->H_DATAUSU)) {
  $sDataVenc = date("Y-m-d",$oPost->H_DATAUSU);
}

$histinf = "";
$result  = db_query("select codmodelo, k03_tipo, to_char(k00_txban,'99.99') as tx_banc from arretipo where k00_tipo = $tipo_debito and k00_instit = ".db_getsession("DB_instit"));

db_fieldsmemory($result, 0);
pg_free_result($result);

$aParcelasSemInflatores = array();
$aNumpresProcessados    = array();
$aNumpresUnicas         = array();
$whereDatasUnicas       = "";

if ($txtNumpreUnicaSelecionados != ""){
  $datasUnicas = "'".implode("','", explode(',',$txtNumpreUnicaSelecionados) )."'";
}else{
  $datasUnicas = "";
}

if (isset($DadosUnicas) && trim($DadosUnicas) != "" ){

  $aUnicas = explode(",",$DadosUnicas);
  foreach ($aUnicas as $iInd => $sUnica){

    $aUnica = explode("_",$sUnica);
    $aDadosUnicas[$aUnica[0]][] = $aUnica[1];
  }
}

if ($numpre_unica != '') {
  $aNumpresUnicas = explode(',',$numpre_unica);
}

$sqlpref  = "select db21_codcli,                                          ";
$sqlpref .= "       db12_extenso,                                         ";
$sqlpref .= "       db12_uf,nomeinst as prefeitura,                       ";
$sqlpref .= "       munic                                                 ";
$sqlpref .= "  from db_config                                             ";
$sqlpref .= "       inner join cgm   on cgm.z01_numcgm = db_config.numcgm ";
$sqlpref .= "       inner join db_uf on db12_uf        = uf               ";
$sqlpref .= " where db_config.codigo = ".db_getsession("DB_instit");

$resul = $cldb_config->sql_record($sqlpref);

if($cldb_config->numrows > 0){
  db_fieldsmemory($resul, 0); // pega o dados da prefa
}else{

  db_redireciona('db_erros.php?fechar=true&db_erro=Contate Suporte. A configuração do sistema não esta completa.');
  exit;
}

if (!isset($pdf2)) {
  $pdf2 = new stdClass();
}

$pdf2->uf_config  = $db12_uf;
$munic2           = $munic;
$nomeinst2        = $prefeitura;
$taxabancaria     = $tx_banc;
$msgvencida       = "";
$bql              = "";
$obsdiver         = "";

if ((int) $codmodelo > 0) {
  $impmodelo = (int) $codmodelo;
} else {
  $impmodelo = 1;
}

if (isset($oPost->iParcIni, $oPost->iParcFim)) {

  $iParcelaIni = $oPost->iParcIni;
  $iParcelaFim = $oPost->iParcFim;
} else {

  $iParcelaIni = null;
  $iParcelaFim = null;
}

try{

  $iParcelaInicial = 1;
  $iParcelaFinal   = 1;
  $oRegraEmissao   = new regraEmissao($tipo_debito,1,db_getsession('DB_instit'),date("Y-m-d", db_getsession("DB_datausu")),db_getsession('DB_ip'),true,false,$iParcelaIni,$iParcelaIni);

} catch (Exception $eExeption){

  db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
  exit;
}

////////////////////////////////////////////////////////////////////////////////
////////  C O M E Ç O   D A  G E R A Ç Ã O  D O S   C A R N E S   //////////////
////////////////////////////////////////////////////////////////////////////////

/********************* R O T I N A   P A R A   B U S C A R   O   M O D E L O   D E   C A R N E *****************************************************/
$rstipo = db_query("select * from arretipo where k00_tipo = $tipo_debito");
db_fieldsmemory($rstipo, 0);

$result = db_query("select * from db_config where codigo = ".db_getsession('DB_instit'));
db_fieldsmemory($result, 0);

/***************************************************************************************************************************************************/

// FUNCAO Q RETORNA O PDF ESTANCIADO JA COM O MODELO CERTO TESTANDO AS RESTRIÇÕES
$pdf1                        = $oRegraEmissao->getObj();
$pdf1->sMensagemCaixa        = '';
$pdf1->sMensagemContribuinte = '';

if ($oRegraEmissao->getCadTipoConvenio() == 6 ) {

  $pdf1->sCedenteBoleto    = $oRegraEmissao->getNomeConvenio();
  $pdf1->sTituloInstrucoes = 'TEXTO DE RESPONSABILIDADE DO CEDENTE';
}

$pdf1->uf_config  = $db12_uf;
$pdf1->prefeitura = $nomeinst;

/**
 * Quando for convênio BDL de qualquer banco, sistema deve listar no boleto de Recibo (92) e Carnê (100)
 * o nome do cedente que constar como nome no cadastro de convênio.
 * Não deve usar o nome da instituição.
 */
if ($oRegraEmissao->getCadTipoConvenio() == 1) {
    $pdf1->prefeitura = $oRegraEmissao->getNomeConvenio();
}

$sqlparag  = " select db02_texto                                             ";
$sqlparag .= "   from db_documento                                           ";
$sqlparag .= "        inner join db_docparag  on db03_docum   = db04_docum   ";
$sqlparag .= "        inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
$sqlparag .= "        inner join db_paragrafo on db04_idparag = db02_idparag ";
$sqlparag .= " where db03_tipodoc = 1017                                     ";
$sqlparag .= "   and db03_instit = ".db_getsession("DB_instit")." ";
$sqlparag .= " order by db04_ordem ";
$resparag = db_query($sqlparag);

if (pg_numrows($resparag) == 0) {
  $pdf1->secretaria = 'SECRETARIA DE FINANÇAS';
} else {

  db_fieldsmemory($resparag, 0);
  $pdf1->secretaria = $db02_texto;
}

$pdf1->k03_tipo      = $k03_tipo;
$pdf1->tipodebito    = $k00_descr;
$pdf1->pretipodebito = $k00_descr;
$pdf1->logo          = $logo;

db_query("BEGIN");

db_postmemory($aDados);

$vt      = $aDados;
$tam     = sizeof($vt);
$numpres = "";
reset($vt);

for ($i = 0; $i < $tam; $i ++) {

  if (db_indexOf(key($vt), "CHECK") > 0 && db_indexOf(key($vt), "CHECKU") == 0) {

    $registros = split("N",$vt[key($vt)]);
    for ($reg=0; $reg < sizeof($registros); $reg++) {
      if ($registros[$reg] == "") {
        continue;
      }
      $registro = split("R", $registros[$reg]);
      if (gettype(strpos($numpres, "N".$registro[0])) == "boolean") {
        $numpres     .= "N".$registro[0];
        $aVarNumpre   = explode("P",$registro[0]);
        $aTodosNumpres[$aVarNumpre[0]][] = "P".$aVarNumpre[1];
      }
    }
  }
  next($vt);
}

if (isset($oPost->marcarvencidas) && isset($oPost->marcartodas)) {

  if ($oPost->marcarvencidas == 'true' && $oPost->marcartodas == 'false') {

    $aNumpres   = split("N",$numpres);
    $numpres    = "";
    $sNumPreAnt = "";
    $sAuxiliar  = "";
    for ($iInd = 0; $iInd < count($aNumpres); $iInd++) {

      if ($aNumpres[$iInd] == "") {
        continue;
      }

      $iNumpre = split("P",$aNumpres[$iInd]);
      $iNumpar = split("P", strstr($aNumpres[$iInd],"P"));
      $iNumpar = $iNumpar[1];
      $iNumpre = $iNumpre[0];

      $sSqlArrecad  = "  select *                             ";
      $sSqlArrecad .= "    from arrecad                       ";
      $sSqlArrecad .= "   where k00_numpre   = {$iNumpre}     ";
      $sSqlArrecad .= "     and k00_numpar   = {$iNumpar}     ";
      $sSqlArrecad .= "     and k00_dtvenc   > '{$sDataVenc}' ";
      $rsSqlArrecad = db_query($sSqlArrecad);
      $iNumRows     = pg_num_rows($rsSqlArrecad);

      if ($iNumRows == 0) {

        if ($_POST["tipo_debito"] == 3 || $_POST["tipo_debito"] == 5) {

          if (empty($sNumPreAnt) || $sNumPreAnt != $iNumpre) {

            $sNumPreAnt = $iNumpre;
            $sAuxiliar  = "N";
          }

          $numpres .= "{$sAuxiliar}N".$iNumpre."P".$iNumpar;
          $sAuxiliar = "";
        } else {
          $numpres .= 'N'.$iNumpre."P".$iNumpar;
        }
      }

    }
  }

}

$sounica = $numpres;
$numpres = split("N", $numpres);

$unica = 2;

if (sizeof($numpres) < 2) {

  $numpres[0] = 0;
  foreach ( $aNumpresUnicas as $iInd => $sNumpresUnica ){
    $numpres[] = $sNumpresUnica.'P000';
  }
  $unica = 1;

} else {

  if (isset ($aDados["numpre_unica"])) {

    if ($numpre_unica != '') {

      $numpres      = array();
      $numpres[0]   = "";
      $aTodasUnicas = explode(",",$numpre_unica);

      foreach ( $aTodosNumpres as $sNumpre => $aParc){

        if( in_array($sNumpre,$aTodasUnicas)){
          $numpres[] = $sNumpre."P000";
        }

        foreach ($aParc as $iInd => $sParc){
          $numpres[] = $sNumpre.$sParc;
        }

      }

      foreach ( $aTodasUnicas as $iInd => $sNumpre ){
        $numpres[] = $sNumpre."P000";
      }

      $numpres = array_unique($numpres);
      $unica   = 1;
    }

  }

}

if (isset ($geracarne) && $geracarne == 'banco') {
  $pagabanco = 't';
} else {
  $pagabanco = 't';
}

/******************************************************   F O R   Q   M O N T A   O S   C A R N E S  ******************************************************/
$aParcelasSemInflatores = array();

if ($unica != "1"){

  for ($volta = 0; $volta < sizeof($numpres); $volta ++) {

    if ($numpres[$volta] == "" || $numpres[$volta] == "0") {
      continue;
    }

    $aNumpre = split('P',$numpres[$volta]);

    $k00_numpre = $aNumpre[0];
    $k00_numpar = $aNumpre[1];

    $sDataUsuCarnes = $H_DATAUSU;
    if (isset($k00_formemissao)) {

      if ($k00_formemissao == 1) {

        $sSqlArrecad  = " select k00_dtvenc                   ";
        $sSqlArrecad .= "   from arrecad                      ";
        $sSqlArrecad .= "  where k00_numpre   = {$k00_numpre} ";
        $sSqlArrecad .= "    and k00_numpar   = {$k00_numpar} ";
        $rsSqlArrecad = db_query($sSqlArrecad);
        $iNumRows     = pg_num_rows($rsSqlArrecad);
        if ($iNumRows == 0) {

          $oArrecad       = db_utils::fieldsMemory($rsSqlArrecad, 0);
          $sDataUsuCarnes = $oArrecad->k00_dtvenc;
        }
      }
    }

    if(!$oRegraEmissao->isCobranca()){

      $rsDadosPagamento = debitos_numpre_carne($k00_numpre, $k00_numpar, $sDataUsuCarnes, $H_ANOUSU,db_getsession('DB_instit'),$DB_DATACALC,$forcarvencimento);
      db_fieldsmemory($rsDadosPagamento, 0);
      if ( isset($total) && $total < 0 ) {
        array_push($aParcelasSemInflatores,$k00_numpar);
      }
    }
  }
}

if (count($aParcelasSemInflatores) > 0 ) {

  $sS = ( count($aParcelasSemInflatores) > 1 ? 's' : '' );
  db_redireciona("db_erros.php?fechar=true&db_erro=Valor negativo na{$sS} parcela{$sS} ".implode(",",$aParcelasSemInflatores)." verifique.");
  exit;
}

/**
 * Validamos através de que parametro a CGF esta sendo acessada
 *
 *  ver_matric - matricula
 *  ver_inscr  - inscrição
 *  ver_numcgm - cgm (matric e inscr sao nulos ou 0)
 */
if( !empty($aDados) ){

  $pdf1->sReciboSacadoContribuinteTitulo = 'Contribuinte';
  $pdf1->iReciboSacadoContribuinteCodigo = $aDados['ver_numcgm'];

  if( !empty( $aDados['ver_matric'] ) ){

    $pdf1->sReciboSacadoContribuinteTitulo = 'Matrícula';
    $pdf1->iReciboSacadoContribuinteCodigo = $aDados['ver_matric'];
  }

  if( !empty( $aDados['ver_inscr'] ) ){

    $pdf1->sReciboSacadoContribuinteTitulo = 'Inscrição';
    $pdf1->iReciboSacadoContribuinteCodigo = $aDados['ver_inscr'];
  }
}

for ($volta = 1; $volta < sizeof($numpres); $volta ++) {

  if ($numpres[$volta] == "") {
    continue;
  }

  $k00_numpre = substr($numpres[$volta], 0, strpos($numpres[$volta], 'P'));

  $resulttipo = db_query("select k00_descr, k00_codbco, k00_codage, k00_txban, k00_rectx,
                                 k00_hist1, k00_hist2,  k00_hist3,  k00_hist4, k00_hist5,
                                 k00_hist6, k00_hist7,  k00_hist8
                            from arretipo
                           where k00_tipo = $tipo_debito");
  db_fieldsmemory($resulttipo, 0);

  //////////////////////  PARCELAMENTO DE DIVERSO ///////////////////////////////////
  $pdf1->parcel = "";

  if ($k03_tipo == 16) {

    $sql28  = " select b.*                                                               ";
    $sql28 .= "   from diversos a                                                        ";
    $sql28 .= "        left join  termodiver       on  dv10_parcel    = dv05_coddiver    ";
    $sql28 .= "        left outer join procdiver b on a.dv05_procdiver= b.dv09_procdiver ";
    $sql28 .= " where dv05_numpre = $k00_numpre limit 1                                  ";

    $result28 = db_query($sql28);
    if (pg_numrows($result28) > 0) {

      db_fieldsmemory($result28, 0);
      $pdf1->tipodebito    = 'PARCELAMENTO DE '.$dv09_descr;
      $pdf1->pretipodebito = "PARCELAMENTO DE  $dv09_descr  N- $v10_parcel";
      $pdf1->parcel        = $dv10_parcel;
    }
  }

  //////////////  DIVERSO /////////////////////
  if (($tipo_debito == 28) || ($tipo_debito == 25)) {

    ///////// PARCELAMENTO///////////////
    if ($tipo_debito == 28) {

      $sql25  = " select *                                                        ";
      $sql25 .= "   from termo                                                    ";
      $sql25 .= "        inner join termodiver on v07_parcel     = dv10_parcel    ";
      $sql25 .= "        inner join diversos   on dv10_coddiver  = dv05_coddiver  ";
      $sql25 .= "        inner join procdiver  on dv05_procdiver = dv09_procdiver ";
      $sql25 .= " where v07_numpre = $k00_numpre";

      ///////////// DIVERSO ///////////////
    } else {

      $sql25  = "  select *                                                                ";
      $sql25 .= "    from diversos a                                                       ";
      $sql25 .= "         left outer join procdiver b on a.dv05_procdiver=b.dv09_procdiver ";
      $sql25 .= "   where dv05_numpre = $k00_numpre                                        ";
      $sql25 .= "order by a.dv05_coddiver desc limit 1                                     ";
    }

    $result25 = db_query($sql25) or die($sql25);

    if (pg_numrows($result25) > 0) {

      db_fieldsmemory($result25, 0);
      $obs                 = substr($obs, 0, 20);
      $pdf1->tipodebito    = $dv09_descr;
      $pdf1->pretipodebito = $dv09_descr;
      $pdf1->pretipodebito = "PARCELAMENTO DE DIVERSO N- " . empty($dv10_parcel) ? '' : $dv10_parcel;
      if ($tipo_debito == 28) {
        $pdf1->parcel = $v07_parcel;
      }
    } else {

      $obs    = "";
      $rstermo = db_query(" select v07_parcel from termo where v07_numpre = $k00_numpre ");
      if( pg_numrows($rstermo) > 0 ){

        db_fieldsmemory($rstermo,0);
        $codparcel    = " N- $v07_parcel ";
        $pdf1->parcel = $v07_parcel;
      }else{
        $codparcel = "";
      }

      $pdf1->tipodebito    = "";
      $pdf1->pretipodebito = $k00_descr.$codparcel;
      $k00_hist1           = "";
      $pdf1->secretaria    = "";
      $dv05_procdiver      = 0;
    }

    if ($dv05_procdiver == 1284) {

      $pdf1->secretaria = 'FUNDO MUNICIPAL DE HABITAÇÃO';
      $k00_hist1        = 'Convênio SEHAB nº 72/99 - Programa Especial do Funco de Desenvolvimento Social. Aprovação do Conselho Estadual de Habitação em 08/09/1999';
    } else if ($dv05_procdiver == 221) {

      $pdf1->secretaria = 'FUNDO MUNICIPAL DE HABITAÇÃO';
      $k00_hist1        = 'Lei Municipal nº 3049/2002, de 04/12/2002. Aprovação do Conselho Estadual de Habitação em dez/2002';
    }
  }

  $proprietario = '';
  $xender       = '';
  $xbairro      = '';

  $rstermo = db_query(" select v07_parcel from termo where v07_numpre = $k00_numpre ");
  if( pg_numrows($rstermo) > 0 ){

    db_fieldsmemory($rstermo,0);
    $codparcel = " N- $v07_parcel ";
    $pdf1->pretipodebito =$k00_descr.$codparcel;
    $pdf1->parcel = $v07_parcel;
  }else{
    $codparcel = "";
  }

  /***********************************************************************************************************************/

  $sqlorigem  = " select arrecad.k00_numpre,                       ";
  $sqlorigem .= "        arrenumcgm.k00_numcgm as z01_numcgm,      ";
  $sqlorigem .= "        case                                      ";
  $sqlorigem .= "           when arrematric.k00_matric is not null ";
  $sqlorigem .= "             then arrematric.k00_matric           ";
  $sqlorigem .= "           when arreinscr.k00_inscr is not null   ";
  $sqlorigem .= "             then arreinscr.k00_inscr             ";
  $sqlorigem .= "           else                                   ";
  $sqlorigem .= "             arrenumcgm.k00_numcgm                ";
  $sqlorigem .= "        end as origem,                            ";
  $sqlorigem .= "        case                                      ";
  $sqlorigem .= "          when arrematric.k00_matric is not null  ";
  $sqlorigem .= "            then 'Matrícula'                      ";
  $sqlorigem .= "          when arreinscr.k00_inscr is not null    ";
  $sqlorigem .= "            then 'Inscrição'                      ";
  $sqlorigem .= "        else                                      ";
  $sqlorigem .= "         'CGM'                                    ";
  $sqlorigem .= "        end as descr                              ";
  $sqlorigem .= "   from arrecad                                   ";
  $sqlorigem .= "        inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
  $sqlorigem .= "        left join arrematric  on arrematric.k00_numpre = arrecad.k00_numpre ";
  $sqlorigem .= "        left join arreinscr   on arreinscr.k00_numpre  = arrecad.k00_numpre ";
  $sqlorigem .= " where arrecad.k00_numpre = $k00_numpre";
  $rsOrigem   = db_query($sqlorigem) or die($sqlorigem);

  if (pg_numrows($rsOrigem) > 0) {
    db_fieldsmemory($rsOrigem, 0);
  } else {
    db_msgbox("Nao encontrou registros do numpre: $k00_numpre!");
  }

  if (!empty ($descr) && $descr == 'Matrícula') {

    $sSqlIdentificacao  = "select *                    ";
    $sSqlIdentificacao .= "  from proprietario         ";
    $sSqlIdentificacao .= " where j01_matric = $origem ";
    $sSqlIdentificacao .= " limit 1                    ";
    $Identificacao = db_query($sSqlIdentificacao);

    if(pg_numrows($Identificacao)==0) {
      db_redireciona('db_erros.php?fechar=true&db_erro=Problemas no Cadastro da Matricula ' . $origem);
    }

    db_fieldsmemory($Identificacao, 0);

    $proprietario       = $z01_nome;
    $pdf1->bairropri    = $j13_descr;
    $pdf1->prebairropri = $z01_bairro;
    $pdf1->nomepriimo   = $nomepri;

    $pdf1->pqllocal     = "PQL: {$pql_localizacao}";

    $sql = "select sum(j22_valor) as vlredi
              from iptucale
                   inner join iptucalc on j23_anousu = j22_anousu
                                      and j22_matric = j23_matric
                   inner join iptunump on j20_anousu = j23_anousu
                                      and j20_matric = j23_matric
             where j20_numpre = $k00_numpre
               and j22_matric = $j01_matric";
    $sqlres = db_query($sql);
    if (pg_numrows($sqlres) > 0) {
      db_fieldsmemory($sqlres, 0);
    } else {
      $vlredi = 0;
    }

    $sql = "select j23_vlrter,
                   j23_aliq
              from iptucalc
                   inner join iptunump on j20_anousu = j23_anousu
                                      and j20_matric = j23_matric
             where j20_numpre = $k00_numpre
               and j23_matric = $j01_matric";
    $sqlres = db_query($sql);

    if (pg_numrows($sqlres) > 0) {

      db_fieldsmemory($sqlres, 0);
      $pdf1->iptj23_aliq = $j23_aliq;
    } else {

      $j23_vlrter = 0;
      $j23_aliq   = 0;
    }

    $j23_vlrter += $vlredi;
    $pdf1->iptj23_vlrter = db_formatar($j23_vlrter, 'f');

    if($oRegraEmissao->isCobranca()){
      $xender = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
    }else{
      $xender = $nomepri.', '.$j39_numero.'  '.$j39_compl;
    }

    $pdf1->iptbql          = $j34_setor.'-'.$j34_quadra.'-'.$j34_lote." ".($pql_localizacao!=""?"PQL: $pql_localizacao":"");
    $pdf1->pql_localizacao = $pql_localizacao;
    $bql                   = '  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote." ".($pql_localizacao!=""?"PQL: $pql_localizacao":"");

    if (isset ($impmodelo) && $impmodelo == 30) {

      if ($k00_tipo != 6) {
        $iNumeroOrigem = $j01_matric;
      } else {
        $iNumeroOrigem = "";
        $iNumeroOrigem = $j01_matric;
      }

    } else {

      if ($k00_tipo != 6) {
        $iNumeroOrigem = $j01_matric.'  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
      } else {

        $iNumeroOrigem = "";
        $iNumeroOrigem = $j01_matric.'  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
      }
    }
  } else if (!empty ($descr) && $descr == 'Inscrição') {

    $Identificacao = db_query("select * from empresa where q02_inscr = $origem");

    if(pg_numrows($Identificacao)==0) {
      db_redireciona('db_erros.php?fechar=true&db_erro=Problemas no Cadastro da Inscrição ' . $origem);
    }

    db_fieldsmemory($Identificacao, 0);
    if ($k00_tipo != 6) {

      $iNumeroOrigem = $q02_inscr;
      $z01_numcgm    = $q02_numcgm;
    } else {

      $iNumeroOrigem = "";
      $iNumeroOrigem = $q02_inscr;
      $z01_numcgm    = $q02_numcgm;
    }

  } else {

    $Identificacao = db_query("select cgm.*, ''::bpchar as nomepri, ''::bpchar as j39_co
                                 from cgm
                                where z01_numcgm = $origem");

    if(pg_numrows($Identificacao)==0) {
      db_redireciona('db_erros.php?fechar=true&db_erro=Problemas no Cadastro do CGM ' . $origem);
    }

    db_fieldsmemory($Identificacao, 0);
    $iNumeroOrigem = $origem;
  }
  /************************************************************************************************************************************/

  // PARCELAMENTO DE DIVIDA  OU  PARCELAMENTO DE CONTR. E MELHORIA OU PARCELAMENTO DE DIVERSO PARCELAMENTO DE INICIAL
  if (($k03_tipo == 6) || ($k03_tipo == 17) || ($k03_tipo == 16) || ($k03_tipo == 13)) {

    $sqltipodeb = "select termo.*,
                          z01_nome,
                          z01_ender,
                          z01_numero,
                          z01_compl,
                          z01_bairro,
                          coalesce(k00_matric,0) as matric,
                          coalesce(k00_inscr,0) as inscr
                     from termo
                          left outer join arrematric on v07_numpre = arrematric.k00_numpre
                          left outer join arreinscr  on v07_numpre = arreinscr.k00_numpre
                          inner join cgm             on v07_numcgm = z01_numcgm
                    where v07_numpre = $k00_numpre ";

    $sqltipodeb = " select z.*,
                          z01_nome,
                          z01_ender,
                          z01_numero,
                          z01_compl,
                          z01_bairro from (  select x.*,
                          case when x.matric <> 0 then
                            case when j41_numcgm is not null then
                              promitente.j41_numcgm
                            else iptubase.j01_numcgm
                            end
                          else
                          case when x.inscr <> 0 then issbase.q02_numcgm
                          else arrecad.k00_numcgm
                          end
                          end as z01_numcgm
                        from ( select termo.*,
                                      coalesce(k00_matric,0) as matric,
                                      coalesce(k00_inscr,0) as inscr
                                 from termo
                                      left outer join arrematric  on v07_numpre = arrematric.k00_numpre
                                      left outer join arreinscr   on v07_numpre = arreinscr.k00_numpre
                          where v07_numpre = $k00_numpre
                        ) as x
                        left join iptubase           on j01_matric = x.matric
                        left outer join promitente   on j01_matric = j41_matric and promitente.j41_tipopro is true
                        left join issbase            on q02_inscr  = x.inscr
                        inner join arrecad           on v07_numpre = k00_numpre
                      ) as z
                      inner join cgm on z.z01_numcgm = cgm.z01_numcgm ";

    $resulttipodeb = db_query($sqltipodeb);
    if (pg_numrows($resulttipodeb) == 0) {

      db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento sem termo cadastrado.');
      exit;
    } else {

      db_fieldsmemory($resulttipodeb, 0);
      $pdf1->parcel = $v07_parcel;
    }
  }

  $exercicio = '';
  //  PARCELAMENTO DE DIVIDA ATIVA
  if ($k03_tipo == 6) {

    $sqldivida = "select distinct v01_exerc
                    from termodiv
                         inner join divida on v01_coddiv = coddiv
                   where parcel = $v07_parcel";
    $resultdivida = db_query($sqldivida);
    $traco        = '';
    $exercicio    = ' - Exerc : ';
    for ($k = 0; $k < pg_numrows($resultdivida); $k ++) {

      $exercicio .= $traco.substr(pg_result($resultdivida, $k, "v01_exerc"), 2, 2);
      $traco = '-';
    }
  }

  //SE FOR UNICA
  /******************************************************************************************************************************/
  if (in_array($k00_numpre,$aNumpresUnicas) && !in_array($k00_numpre,$aNumpresProcessados)) {
    $unica = 1;
  } else {
    $unica = 0;
  }


  /*************************************************** U N I C A ******************************************************/
  if ($unica == 1 && $datasUnicas != "" ) {

    if ($datasUnicas != "") {

      $sSqlWhereData = " where ";
      $sOperador     = "";

      foreach ($aDadosUnicas as $sUnicaNumpre => $aUnicaVenc ){

        if ( $sUnicaNumpre == $k00_numpre ) {

          $sVencimentosUnica = implode("','",$aUnicaVenc);
          $sSqlWhereData    .= "  {$sOperador} ( r.k00_numpre = {$k00_numpre}   ";
          $sSqlWhereData    .= "  and r.k00_dtvenc in ('{$sVencimentosUnica}')) ";
          $sOperador         = " or ";
        }
      }
    }

    $sql  = " select *,                                                ";
    $sql .= "        substr(fc_calcula,2,13)::float8 as uvlrhis,       ";
    $sql .= "        substr(fc_calcula,15,13)::float8 as uvlrcor,      ";
    $sql .= "        substr(fc_calcula,28,13)::float8 as uvlrjuros,    ";
    $sql .= "        substr(fc_calcula,41,13)::float8 as uvlrmulta,    ";
    $sql .= "        substr(fc_calcula,54,13)::float8 as uvlrdesconto, ";
    $sql .= "        (substr(fc_calcula,15,13)::float8 + substr(fc_calcula,28,13)::float8 + substr(fc_calcula,41,13)::float8 - substr(fc_calcula,54,13)::float8) as utotal, ";
    $sql .= "         substr(fc_calcula,77,17)::float8 as qinfla,      ";
    $sql .= "         substr(fc_calcula,94,4)::varchar(5) as ninfla    ";
    $sql .= "   from ( select r.k00_numpre,                            ";
    $sql .= "                 r.k00_dtvenc as dtvencunic,              ";
    $sql .= "                 r.k00_dtvenc as dtvencunicuni,           ";
    $sql .= "                 r.k00_dtoper as dtoperunic,              ";
    $sql .= "                 r.k00_percdes,                           ";
    $sql .= "                 fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").") ";
    $sql .= "            from recibounica r                            ";
    $sql .= "                 {$sSqlWhereData}                         ";
    $sql .= "             and r.k00_dtvenc >= '".date('Y-m-d', db_getsession("DB_datausu"))."'::date ) as unica ";

    $aNumpresProcessados[] = $k00_numpre;

    $sql .= "          order by dtvencunic ";

    $linha     = 220;

    $resultfin = db_query($sql) or die($sql);

    if ($resultfin != false) {

      for ($unicont = 0; $unicont < pg_numrows($resultfin); $unicont ++) {

        $oMensagem                   = DBTributario::getMensagensParcela($k00_numpre, null, null );
        $pdf1->sMensagemContribuinte = $oMensagem->sMensagemContribuinte;
        $pdf1->sMensagemCaixa        = $oMensagem->sMensagemCaixa;

        $pdf1->arraycodhist       = array();
        $pdf1->arrayreduzreceitas = array();
        $pdf1->arraycodreceitas   = array();
        $pdf1->arraydescrreceitas = array();
        $pdf1->arrayvalreceitas   = array();
        $pdf1->arraycodtipo       = array();
        $pdf1->descr12_1          = "";
        $pdf1->tipo_exerc         = "";

        db_fieldsmemory($resultfin, $unicont);
        $vlrhis       = db_formatar($uvlrhis, 'f');
        $vlrdesconto  = db_formatar($uvlrdesconto, 'f');
        $utotal      += $taxabancaria;
        $vlrtotal     = db_formatar($utotal, 'f');
        $vlrbar       = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

        $sqlvalor     = "select k00_impval, k00_tercdigcarneunica from arretipo where k00_tipo = $tipo_debito";
        db_fieldsmemory(db_query($sqlvalor), 0);

        if (!isset ($k00_tercdigcarneunica) || $k00_tercdigcarneunica == "") {
          db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
        }

        $iTercDig = $k00_tercdigcarneunica;

        if ($k00_impval == 't') {

          $k00_valor = $utotal;
          $vlrbar    = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
          $ninfla    = '';

          if ($utotal == 0) {

            $iTercDig = 7;
            $vlrbar   = "00000000000";
          }

        } else {

          $k00_valor = $qinfla;
          $iTercDig  = 7;
          $vlrbar    = "00000000000";
        }

        if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {

          if (substr($dtvencunic, 0, 4) > db_getsession('DB_anousu')) {
            continue;
          }
        }

        try {

          db_inicio_transacao();

          $oRecibo = new recibo(2, null, 1);
          $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());
          $oRecibo->setDataVencimentoRecibo($dtvencunic);
          $oRecibo->addNumpre($k00_numpre, 0);

          /**
           * Alteração para retirar a logica da regra de desconto pos-pagamento para
           * parcelamento
           */
          $oRecibo->setDescontoReciboWeb($k00_numpre, $k00_numpar, retornaRegraDescontoParcelamento($k00_numpre));

          $oRecibo->emiteRecibo();
          $iCodigoUnicaRecibo = $oRecibo->getNumpreRecibo();
          $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());

          if ($lConvenioCobrancaValido && !CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
             CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
          }

          db_fim_transacao(false);

        } catch ( Exception $eException ) {
          db_fim_transacao(true);
          db_redireciona("db_erros.php?fechar=true&db_erro=[2] - {$eException->getMessage()}");
          exit;
        }

        if($oRegraEmissao->isCobranca()){

          if (substr($dtvencunic, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0 && ( $ninfla_ant != "" && $ninfla_ant != "REAL")) {

            $k00_valor = 0;
            $especie   = $ninfla;
            $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
          }else{

            $especie   = 'R$';
            $histinf   = "";
          }

          if($dtvencunic < date('Ymd',db_getsession('DB_datausu')) && $k00_valor > 0){

            $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$dtvencunic;
            $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
          }else{
            $msgvencida = "";
          }
        }

        /**
         *Convênio sendo instanciado na Unica
         */

        try {
          $oConvenio = new convenio($oRegraEmissao->getConvenio(),$iCodigoUnicaRecibo,0,$k00_valor,$vlrbar,$dtvencunic,$iTercDig);
        } catch (Exception $eExeption){

          db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
          exit;
        }

        /**
         * Faz comunicacão com webservice para validação dos dados do recibo
         */
        try {
          if ($lConvenioCobrancaValido && CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
            CobrancaRegistrada::registrarReciboWebservice($iCodigoUnicaRecibo, $oRegraEmissao->getConvenio(), $k00_valor);
          }
        } catch(Exception $oErro) {
          db_redireciona("db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
          exit;
        }

        $codigo_barras   = $oConvenio->getCodigoBarra();
        $linha_digitavel = $oConvenio->getLinhaDigitavel();

        if($oRegraEmissao->isCobranca()) {

          $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
          $pdf1->carteira        = $oConvenio->getCarteira();
          $pdf1->nosso_numero = $oConvenio->getNossoNumero();
        }

        global $pdf;

        if($dtoperunic > date("Y-m-d")){
          $pdf1->data_processamento = date("d/m/Y");
        }else{
          $pdf1->data_processamento = db_formatar($dtoperunic,'d');
        }

        $pdf1->titulo1 = $descr;
        $pdf1->descr1  = $iNumeroOrigem;
        $pdf1->descr2  = db_numpre($iCodigoUnicaRecibo, 0).'000'; //.db_formatar($k00_numpar,'s',"0",3,"e");

        if (isset ($obs)) {

          $pdf1->titulo13 = 'Observação';
          $pdf1->descr13  = $obs;
        }

        /////////////// ISSQN FIXO //////////////////////////////
        if ($k03_tipo == 2) {

          $pdf1->titulo4  = 'Atividade';
          $pdf1->descr4_1 = '- '.$q07_ativ.'-'.$q03_descr;
          $pdf1->titulo13 = 'Atividade';
          $pdf1->descr13  = $q07_ativ;

          ////////////// PARCELAMANTO DE DIVIDA E DE INICIAL ////////////
        } else if (($k03_tipo == 6) || ($k03_tipo == 13)) {

          $pdf1->titulo4  = 'Parcelamento';
          $pdf1->descr4_1 = '- '.$v07_parcel.$exercicio;
          $pdf1->titulo13 = 'Parcelamento';
          $pdf1->descr13  = $v07_parcel;
        }

        $pdf1->descr5 = 'UNICA';

        $pdf1->descr6      = db_formatar($dtvencunic,"d");
        $pdf1->predescr6   = $dtvencunic;
        $pdf1->predatacalc = $dtvencunic;
        $pdf1->titulo8     = $descr;
        $pdf1->pretitulo8  = $descr;
        $pdf1->descr8      = $iNumeroOrigem;
        $pdf1->predescr8   = $iNumeroOrigem;
        $pdf1->descr9      = db_numpre($iCodigoUnicaRecibo, 0).'000';
        $pdf1->predescr9   = db_numpre($iCodigoUnicaRecibo, 0).'000';
        $pdf1->descr10     = 'UNICA';

        $pdf1->tipo_exerc      = "$k00_tipo / ".substr($dtvencunic,0,4);

        if (!empty ($aDados["ver_matric"])) {

          $sqlEnder = "select z01_nome,                                         ";
          $sqlEnder.= "       z01_ender,                                        ";
          $sqlEnder.= "       z01_numero,                                       ";
          $sqlEnder.= "       z01_compl,                                        ";
          $sqlEnder.= "       z01_munic as j43_munic,                           ";
          $sqlEnder.= "       z01_uf as j43_uf,                                 ";
          $sqlEnder.= "       z01_cep as j43_cep,                               ";
          $sqlEnder.= "       nomepri as j43_ender,                             ";
          $sqlEnder.= "       j39_compl as j43_compl,                           ";
          $sqlEnder.= "       j39_numero,                                       ";
          $sqlEnder.= "       j13_descr as j43_bairro,                          ";
          $sqlEnder.= "       case                                              ";
          $sqlEnder.= "         when j13_descr is not null and j13_descr != ''  ";
          $sqlEnder.= "         then j13_descr                                  ";
          $sqlEnder.= "         else ''                                         ";
          $sqlEnder.= "       end as j13_descr,                                 ";
          $sqlEnder.= "       j34_setor||'.'||j34_quadra||'.'||j34_lote as sql, ";
          $sqlEnder.= "       pql_localizacao,                                  ";
          $sqlEnder.= "       z01_cgccpf,                                       ";
          $sqlEnder.= "      z01_bairro,                                        ";
          $sqlEnder.= "       z01_numcgm                                        ";
          $sqlEnder.= "  from proprietario                                      ";
          $sqlEnder.= " where j01_matric = {$j01_matric}                        ";
          $sqlEnder.= " limit 1                                                 ";

          $rsresultender   = db_query($sqlEnder);
          $intNumrowsEnder = pg_numrows($rsresultender);
          if($intNumrowsEnder > 0){
            db_fieldsmemory($rsresultender,0);
          }

          $pdf1->pretipocompl  = 'Número:';
          $pdf1->tipobairro    = 'Bairro:';
          $pdf1->bairropri     = $j13_descr;
          $pdf1->nomepriimo    = $j43_ender;
          $pdf1->tipocompl     = empty($j43_compl) ? '' : $j43_compl;
          $pdf1->tipocompl     = empty($j43_compl) ? '' : $j43_compl;
          $pdf1->tipocompl     = 'Número:';

          $pdf1->descr11_1     = $z01_cgmpri." - ".$proprietario;
          $pdf1->descr11_2     = $xender;
          $pdf1->descr11_3     = $xbairro;
          $pdf1->bairrocontri  =  $z01_bairro;

          $pdf1->munic         = $j43_munic;
          $pdf1->premunic      = $j43_munic;
          $pdf1->uf            = $z01_uf;
          $pdf1->ufcgm         = $z01_uf;
          $pdf1->descr3_1      = $z01_cgmpri." - ".$proprietario;
          $pdf1->descr3_2      = $xender;
          $pdf1->predescr3_1   = $z01_cgmpri." - ".$proprietario;
          $pdf1->predescr3_2   = $z01_ender." ".$z01_numero." ".$z01_compl;
          $pdf1->descr3_3      = $xbairro;
          $pdf1->descr17       = $bql;

          $pdf1->tipoinscr     = 'Matricula';
          $pdf1->nrinscr       = $j01_matric.' - SQL:'.$sql;
          $pdf1->tipolograd    = 'Rua ';
          $pdf1->pretipolograd = 'Rua ';
          $pdf1->cep           = $z01_cep;
          $pdf1->precep        = $z01_cep;
          $pdf1->nomepri       = $z01_ender;
          $pdf1->prenomepri    = $j43_ender;
          $pdf1->nrpri         = $j39_numero;
          $pdf1->prenrpri      = $j39_numero;
          $pdf1->complpri      = empty($j43_compl) ? '' : $j43_compl;
          $pdf1->precomplpri   = empty($j43_compl) ? '' : $j43_compl;
          $pdf1->precgccpf     = $z01_cgccpf;
          $pdf1->cgccpf        = $z01_cgccpf;

        } else {

          $pdf1->pretipocompl  = 'Número:';
          $pdf1->tipocompl     = 'Número:';
          $pdf1->tipobairro    = 'Bairro:';
          $pdf1->bairropri     = $z01_bairro;

          /**
           * Seta Inscricao para o campo z01_cgmpri, pois a unica pode ser para matricula ou inscricao
           */
          if(empty($z01_cgmpri)){
            $z01_cgmpri = $origem;
          }
          $pdf1->descr11_1     = $z01_cgmpri." - ".$z01_nome;
          $pdf1->descr11_2     = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
          $pdf1->descr11_3     = $xbairro;
          $pdf1->bairrocontri  = $z01_bairro;
          $pdf1->munic         = $z01_munic;
          $pdf1->premunic      = $z01_munic;
          $pdf1->uf            = $z01_uf;
          $pdf1->ufcgm         = $z01_uf;
          $pdf1->descr3_1      = $z01_cgmpri." - ".$z01_nome;
          $pdf1->descr3_2      = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
          $pdf1->predescr3_1   = $z01_cgmpri." - ".$z01_nome;
          $pdf1->predescr3_2   = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
          $pdf1->descr3_3      = $z01_bairro;
          $pdf1->tipoinscr     = 'Cgm';
          $pdf1->nrinscr       =  $z01_cgmpri;
          $pdf1->tipolograd    = 'Rua ';
          $pdf1->pretipolograd = 'Rua ';
          $pdf1->cep           = $z01_cep;
          $pdf1->precep        = $z01_cep;
          $pdf1->nomepri       = $z01_ender;
          $pdf1->nomepriimo    = $z01_ender;
          $pdf1->prenomepri    = $z01_ender;
          $pdf1->nrpri         = $z01_numero;
          $pdf1->prenrpri      = $z01_numero;
          $pdf1->complpri      = $z01_compl;
          $pdf1->precomplpri   = $z01_compl;
          $pdf1->precgccpf     = $z01_cgccpf;
          $pdf1->cgccpf        = $z01_cgccpf;

        }

        /************  PEGA AS RECEITAS COM OS VALORES *****************/

        $sqlReceitas  = " select substr(fc_calcula,15,13)::float8 as valor_corrigido,            ";
        $sqlReceitas .= "         substr(fc_calcula,28,13)::float8 as valor_juros,               ";
        $sqlReceitas .= "         substr(fc_calcula,41,13)::float8 as valor_multa,               ";
        $sqlReceitas .= "         (substr(fc_calcula,54,13)::float8 * -1)as valor_desconto,      ";
        $sqlReceitas .= "         (substr(fc_calcula,15,13)::float8+                             ";
        $sqlReceitas .= "        substr(fc_calcula,28,13)::float8+                               ";
        $sqlReceitas .= "        substr(fc_calcula,41,13)::float8-                               ";
        $sqlReceitas .= "        substr(fc_calcula,54,13)::float8) as valreceita,                ";
        $sqlReceitas .= "        codreceita,                                                     ";
        $sqlReceitas .= "        k00_hist,                                                       ";
        $sqlReceitas .= "        codtipo,                                                        ";
        $sqlReceitas .= "        descrreceita,                                                   ";
        $sqlReceitas .= "        reduzreceita                                                    ";
        $sqlReceitas .= "    from (                                                              ";
        $sqlReceitas .= " select k00_receit as codreceita,                                       ";
        $sqlReceitas .= "        k02_descr  as descrreceita,                                     ";
        $sqlReceitas .= "        case when taborc.k02_codigo is not null then k02_codrec         ";
        $sqlReceitas .= "             when tabplan.k02_codigo is not null then k02_reduz         ";
        $sqlReceitas .= "        end  as reduzreceita,                                           ";
        $sqlReceitas .= "        k00_valor  as val,                                              ";
        $sqlReceitas .= "        k00_tipo as codtipo,                                            ";
        $sqlReceitas .= "        k00_hist,                                                       ";
        $sqlReceitas .= "        fc_calcula(recibounica.k00_numpre,0,a.k00_receit,recibounica.k00_dtvenc,recibounica.k00_dtvenc,".db_getsession('DB_anousu').")";
        $sqlReceitas .= "   from arrecad a                                                       ";
        $sqlReceitas .= "        inner join recibounica on recibounica.k00_numpre = a.k00_numpre ";
        $sqlReceitas .= "        inner join tabrec  on tabrec.k02_codigo = a.k00_receit          ";
        $sqlReceitas .= "        left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo   ";
        $sqlReceitas .= "                          and taborc.k02_anousu   = ".db_getsession('DB_anousu');
        $sqlReceitas .= "        left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo  ";
        $sqlReceitas .= "                          and tabplan.k02_anousu  = ".db_getsession('DB_anousu');
        $sqlReceitas .= " where a.k00_numpre = $k00_numpre                                       ";
        $sqlReceitas .= "   and k00_numpar = 1                                                   ";
        $sqlReceitas .= "   and recibounica.k00_dtvenc = '".$dtvencunicuni."' ) as c             ";

        $rsReceitas = db_query($sqlReceitas);

        $intnumrows = pg_num_rows($rsReceitas);

        $vlrjuros   = 0;
        $vlrmulta   = 0;
        $nDesconto  = 0;
        for ($x = 0; $x < $intnumrows; $x ++) {

          db_fieldsmemory($rsReceitas, $x);

          $pdf1->arraycodreceitas[$x]   = $codreceita;
          $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
          $pdf1->arraydescrreceitas[$x] = $descrreceita;
          $pdf1->arrayvalreceitas[$x]   = $valor_corrigido;
          $pdf1->arraycodtipo[$x]       = $codtipo;
          $pdf1->arraycodhist[$x]       = $k00_hist;

          $vlrjuros  += $valor_juros;
          $vlrmulta  += $valor_multa;
          $nDesconto += $valor_desconto;
        }

        if(isset($vlrjuros) && $vlrjuros != "" && $vlrjuros !=0){

          $pdf1->arraycodhist[]       = "";
          $pdf1->arraycodtipo[]       = "t";
          $pdf1->arraycodreceitas[]   = "";
          $pdf1->arrayreduzreceitas[] = "";
          $pdf1->arraydescrreceitas[] = "Juros : ";
          $pdf1->arrayvalreceitas[]   = $vlrjuros;
        }

        if(isset($vlrmulta) && $vlrmulta != "" && $vlrmulta != 0){

          $pdf1->arraycodhist[]       = "";
          $pdf1->arraycodtipo[]       = "t";
          $pdf1->arraycodreceitas[]   = "";
          $pdf1->arrayreduzreceitas[] = "";
          $pdf1->arraydescrreceitas[] = "Multa : ";
          $pdf1->arrayvalreceitas[]   = $vlrmulta;
        }

        if(isset($nDesconto) && $nDesconto != "" && $nDesconto != 0){

          $pdf1->arraycodhist[]       = 918;
          $pdf1->arraycodtipo[]       = "t";
          $pdf1->arraycodreceitas[]   = "";
          $pdf1->arrayreduzreceitas[] = "";
          $pdf1->arraydescrreceitas[] = "Desconto : ";
          $pdf1->arrayvalreceitas[]   = $nDesconto;
        }

        $pdf1->especie   = 'R$';

        /***********************************************************************************************/
        if ($oRegraEmissao->isCobranca()) {

          $pdf1->descr12_1 .= $pdf1->tipodebito."\n".
                              $pdf1->titulo1." - ".$pdf1->descr1." / ".
                              $pdf1->titulo4." ".$pdf1->descr4_1." Parcela Única \n";
                              (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
                                (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
                              (isset($pdf1->predescr12_1)?$pdf1->predescr12_1 .= $pdf1->pretipodebito."\n":"").
                                $pdf1->titulo1." - ".$pdf1->descr1." / ".
                                $pdf1->titulo4." ".$pdf1->descr4_1." Parcela Única \n";
                              (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
                                (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";

        }

        //BUSCA A MSG DE PAGAMENTO E AS INSTRUÇÕES DA TABELA NUMPREF
        $rsmsgcarne = db_query("select k03_msgcarne, k03_msgbanco from numpref where k03_anousu = ".db_getsession("DB_anousu"));
        if (pg_numrows($rsmsgcarne) > 0) {
          db_fieldsmemory($rsmsgcarne, 0);
        }

        $sqlMsgCarne        = " select k00_msguni2 from arretipo where k00_tipo = $k00_tipo ";
        $rsMsgCarneUnica    = db_query($sqlMsgCarne);
        $intNumrowsMsgCarne = pg_numrows($rsMsgCarneUnica);

        if($intNumrowsMsgCarne > 0 ){
          db_fieldsmemory($rsMsgCarneUnica, 0);
        }
        if (isset ($k00_msguni2) && $k00_msguni2 != "") {
          $pdf1->predescr12_1 = $k00_msguni2; //msg unica, via contribuinte
        }

        $pdf1->descr14   = db_formatar($dtvencunic,'d');
        $pdf1->dtparapag = db_formatar($dtvencunic,'d');

        if ($iTercDig == '7') {

          //////////////////// ISSQN VARIAVEL /////////////////////
          if ($k03_tipo == 3) {

            $sqlaliq      = "select q05_aliq,                ";
            $sqlaliq     .= "       q05_ano                  ";
            $sqlaliq     .= "  from issvar                   ";
            $sqlaliq     .= " where q05_numpre = $k00_numpre ";
            $sqlaliq     .= "   and q05_numpar = $k00_numpar ";

            $rsIssvarano  = db_query($sqlaliq);
            $intNumrows   = pg_numrows($rsIssvarano);

            if ($intNumrows == 0) {

              db_redireciona('db_erros.php?fechar=true&db_erro=Ano não encontrado na tabela issvar. Contate o suporte');
              exit;
            }

            db_fieldsmemory($rsIssvarano, 0);
            $pdf1->descr4_1 = $k00_numpar.'a PARCELA   -   Alíquota '.$q05_aliq.'%     EXERCÍCIO : '.$q05_ano;
          }
          $pdf1->titulo7  = 'Valor Pago';
          $pdf1->titulo15 = 'Valor Pago';
          $pdf1->titulo13 = 'Valor da Receita Tributável';

          $pdf1->descr7    = db_formatar($k00_valor, 'f');
          $pdf1->descr15   = db_formatar($k00_valor, 'f');
          $pdf1->valtotal  = db_formatar($k00_valor, 'f');
          $pdf1->predescr7 = db_formatar($k00_valor, 'f');

        } else {

          $pdf1->descr15   = db_formatar($k00_valor, 'f');
          $pdf1->valtotal  = db_formatar($k00_valor, 'f');
          $pdf1->descr7    = db_formatar($k00_valor, 'f');
          $pdf1->predescr7 = db_formatar($k00_valor, 'f');
        }

        $pdf1->descr12_2           = '- PARCELA ÚNICA COM '.$k00_percdes.'% DE DESCONTO';
        $pdf1->prehistoricoparcela = ' PARCELA ÚNICA COM '.$k00_percdes.'% DE DESCONTO';
        $pdf1->linha_digitavel     = $linha_digitavel;
        $pdf1->codigo_barras       = $codigo_barras;

        $sqlmsg    = "select k00_tipo, k00_msguni, k00_msguni2 from arretipo where k00_tipo = ".$k00_tipo;
        $resultmsg = db_query($sqlmsg);
        $linhasmsg = pg_num_rows($resultmsg);
        db_fieldsmemory($resultmsg, 0);

        $desconto  = $k00_percdes;
        $texto     = db_geratexto($k00_msguni);
        $texto2    = db_geratexto($k00_msguni2);
        $pdf1->premsgunica = $texto;

        if ($texto2 != '' ) {
          $pdf1->descr12_1 .= $texto2;
        }

        if ($texto != '' ) {
          $pdf1->descr12_2 .= $texto;
        }

        if ($texto2 != '' ) {

          $pdf1->descr16_1 = substr($texto2, 0, 55);
          $pdf1->descr16_2 = substr($texto2, 55, 55);
          $pdf1->descr16_3 = substr($texto2, 110, 55);
        }

        db_sel_instit();

        $pdf1->enderpref  = $ender;
        $pdf1->numeropref = $numero;
        $pdf1->municpref  = $munic;
        $pdf1->telefpref  = $email;
        $pdf1->cgcpref    = $cgc;
        $pdf1->emailpref  = $telef;

        // ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
        //verifica se é ficha e busca o codigo do banco
        if($oRegraEmissao->isCobranca()){

          $rsConsultaBanco = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
          $oBanco          = db_utils::fieldsMemory($rsConsultaBanco,0);
          $pdf1->numbanco  = $oBanco->db90_codban."-".$oBanco->db90_digban;
          $pdf1->banco     = $oBanco->db90_abrev;

          try{
            $pdf1->imagemlogo = $oConvenio->getImagemBanco();
          } catch (Exception $eExeption){
            db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
          }
        }
        //####################################################

        if(isset($vlrdesconto) && $vlrdesconto != ""){
          $pdf1->iptuvlrdesconto = trim($vlrdesconto);
        }else{
          $pdf1->iptuvlrdesconto = "R$ 0,00";
        }

        $pdf1->iptsubtitulo       = "";
        $pdf1->ipttotal           = trim($pdf1->descr7);
        $pdf1->iptuvlrcor         = trim(db_formatar(($k00_valor+$uvlrdesconto), 'f'));
        $pdf1->iptj43_cep         = $pdf1->cep;
        $pdf1->iptz01_cidade      = $pdf1->munic;
        $pdf1->iptz01_bairro      = $z01_bairro;

        if($z01_compl != ""){
          $pdf1->descr3_2 = $pdf1->descr3_2;
        }
        $pdf1->iptendermatric     = $pdf1->descr3_2;
        $pdf1->iptj01_matric      = $pdf1->descr1;
        $pdf1->iptcodigo_barras   = $pdf1->codigo_barras;
        $pdf1->iptlinha_digitavel = $pdf1->linha_digitavel;
        $pdf1->iptprefeitura      = $pdf1->prefeitura;
        $pdf1->iptj23_anousu      = trim(substr($k00_descr,4));
        $pdf1->iptdataemis        = $pdf1->data_processamento;
        $pdf1->iptdtvencunic      = $pdf1->descr6;
        $pdf1->iptz01_nome        = $pdf1->descr3_1;
        $pdf1->iptz01_cgccpf      = $pdf1->cgccpf;
        $pdf1->iptdtvencunic      = $pdf1->dtparapag;
        $pdf1->iptprefeitura      = $pdf1->prefeitura;
        $pdf1->iptj01_matric      = $pdf1->descr1;
        $pdf1->iptnomepri         = strtoupper($z01_ender);
        $pdf1->iptcodpri          = $z01_numero == "" ? "" : ', '.$z01_numero.' / '.$z01_compl;
        $pdf1->iptproprietario    = $pdf1->descr11_1;
        $pdf1->iptz01_ender       = $pdf1->iptnomepri.$pdf1->iptcodpri;

        $pdf1->imprime();
      }
    }

    $unica                       = 2;
    $oMensagem                   = DBTributario::getMensagensParcela($k00_numpre, null, null );
    $pdf1->sMensagemContribuinte = $oMensagem->sMensagemContribuinte;
    $pdf1->sMensagemCaixa        = $oMensagem->sMensagemCaixa;
    $pdf1->descr12_1             = '';
    $pdf1->premsgunica           = '';

    $pdf1->arraycodhist          = array();
    $pdf1->arrayreduzreceitas    = array();
    $pdf1->arraycodreceitas      = array();
    $pdf1->arraydescrreceitas    = array();
    $pdf1->arrayvalreceitas      = array();
    $pdf1->arraycodtipo          = array();

    continue;
  }

  if ($sounica == '') {

    $pdf1->objpdf->Output();
    exit;
  }

  /******************************************************** FIM PARCELA UNICA ************************************************************************/

  $valores    = split("P", $numpres[$volta]);
  $k00_numpre = $valores[0];
  $k00_numpar = split("R", $valores[1]);
  $k00_numpar = $k00_numpar[0];
  $k03_anousu = $H_ANOUSU;

  global $k03_numpre;
  $k03_numpre = 0;
  $sDataUsuCarnes = $H_DATAUSU;
  if (isset($k00_formemissao)) {

    if ($k00_formemissao == 1) {

      $sSqlArrecad  = " select k00_dtvenc                   ";
      $sSqlArrecad .= "   from arrecad                      ";
      $sSqlArrecad .= "  where k00_numpre   = {$k00_numpre} ";
      $sSqlArrecad .= "    and k00_numpar   = {$k00_numpar} ";
      $rsSqlArrecad = db_query($sSqlArrecad);
      $iNumRows     = pg_num_rows($rsSqlArrecad);

      if ($iNumRows == 0) {

        $oArrecad       = db_utils::fieldsMemory($rsSqlArrecad, 0);
        $sDataUsuCarnes = $oArrecad->k00_dtvenc;
      }
    }
  }

  try {

    db_inicio_transacao();

    $sSqlArrecadVencimento  = " select k00_dtvenc                   ";
    $sSqlArrecadVencimento .= "   from arrecad                      ";
    $sSqlArrecadVencimento .= "  where k00_numpre   = {$k00_numpre} ";
    $sSqlArrecadVencimento .= "    and k00_numpar   = {$k00_numpar} ";
    $rsSqlArrecadVencimento = db_query($sSqlArrecadVencimento);
    $iNumRows     = pg_num_rows($rsSqlArrecadVencimento);

    if ($rsSqlArrecadVencimento && $iNumRows > 0) {
      $oArrecadVencimento = db_utils::fieldsMemory($rsSqlArrecadVencimento, 0);
    }

    $oDataVencimentoOriginal = new DBDate($oArrecadVencimento->k00_dtvenc);
    $sVencimento             = $oDataVencimentoOriginal->getDate();
    $oDataAtual              = new DBDate(date('Y-m-d',$DB_DATACALC));

    if ($oDataVencimentoOriginal->getTimeStamp() < $oDataAtual->getTimeStamp() ) {
      $sVencimento = $oDataAtual->getDate();
    }
    $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio());


    $iCodigoRecibo = null;

    /**
     * Quando o convenio for um convenio de cobrança registrada válido, adiciona o recibo gerado na fila para geração do arquivo de cobrança registrada
     */
    if ($lConvenioCobrancaValido) {

      $exerciciosCarne = (!empty($k00_exercicioscarne) ? $k00_exercicioscarne : 0);

      if(date('Y', strtotime($sVencimento)) > (db_getsession('DB_anousu') + $exerciciosCarne)){
        throw new Exception("Não é possível emitir carnês com ano de vencimento superior ao ano atual quando utilizado convênio de cobrança registrada.");
      }

      $oRecibo = new recibo(2, null, 1);
      $oRecibo->setNumBco($oRegraEmissao->getCodConvenioCobranca());

      /**
       * Se a data de pagamento da CGF for superior a data de vencimento original do debito
       * que esta sendo gerado carne banco de cobrança registrada
       * data de vencimento do recibo recebe valor setado na CGF
       */
      if($db_datausu > $sVencimento) {
        $sVencimento = $db_datausu;
      }

      $oRecibo->setDataVencimentoRecibo($sVencimento);
      $oRecibo->setEmiteCarneBanco(true);
      $oRecibo->addNumpre($k00_numpre, $k00_numpar);

      /**
       * Alteração para retirar a logica da regra de desconto pos-pagamento para
       * parcelamento
       */
      $oRecibo->setDescontoReciboWeb($k00_numpre, $k00_numpar, retornaRegraDescontoParcelamento($k00_numpre));
      $oRecibo->emiteRecibo($lConvenioCobrancaValido);
      $iCodigoRecibo = $oRecibo->getNumpreRecibo();
      $k03_numpre    = $iCodigoRecibo;

      $oDaoReciboPaga = new cl_recibopaga();
      $sSqlReciboPaga = $oDaoReciboPaga->sql_query_file(null," sum(k00_valor) as total ", null," k00_numnov = {$iCodigoRecibo} ");
      $rsReciboPaga   = db_query($sSqlReciboPaga);

      if(!$rsReciboPaga || pg_num_rows($rsReciboPaga) == 0) {
        throw new DBException("Erro ao emitir o Recibo.");
      }

      $total = db_utils::fieldsMemory($rsReciboPaga,0)->total;

      /**
      *  Validar se não for cobrança registrada
      */
      if ($lConvenioCobrancaValido && !CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
          CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->getConvenio());
      }
      $sDataUsuCarnes = strtotime($sVencimento);
    }

    db_fim_transacao(false);

  } catch ( Exception $eException ) {
    db_fim_transacao(true);
    db_redireciona("db_erros.php?fechar=true&db_erro=[2] - {$eException->getMessage()}");
    exit;
  }

  if(!$oRegraEmissao->isCobranca()){

    $DadosPagamento = debitos_numpre_carne($k00_numpre, $k00_numpar, $sDataUsuCarnes, $H_ANOUSU,db_getsession('DB_instit'),$DB_DATACALC,$forcarvencimento);
    db_fieldsmemory($DadosPagamento, 0);
  }

  if ( $total < 0 ) {
    db_redireciona("db_erros.php?fechar=true&db_erro=Valor negativo na{$sS} parcela{$sS} ".implode(",",$aParcelasSemInflatores)." verifique.");
  }

  $nValorTot  = $total;
  $total     += $taxabancaria;
  $ninfla_ant = $ninfla;

  if($k03_numpre == 0 ){
    $recibopaga = false;
  }else{
    $recibopaga = true;
  }


  if ($recibopaga == false) {

    $sql1 = "select k00_dtvenc as datavencimento,
                    k00_dtvenc,
                    k00_numtot,
                    k00_dtoper
               from arrecad
              where k00_numpre = $k00_numpre
                and k00_numpar = $k00_numpar
              limit 1";
  } else {

    $sql1 = "select k00_dtvenc as datavencimento,
                    k00_dtpaga as k00_dtvenc,
                    k00_numtot,
                    k00_dtoper
               from recibopaga
              where k00_numnov = $k03_numpre
              limit 1";
  }

  db_fieldsmemory(db_query($sql1), 0);
  $k00_dtvenc = db_formatar($k00_dtvenc, 'd');

  if($k00_dtoper > date("Y-m-d")){
    $pdf1->data_processamento = date("d/m/Y");
  }else{
    $pdf1->data_processamento = db_formatar($k00_dtoper,'d'); // agora é a data de operação
  }

  $sqlvalor = "select k00_impval,k00_tercdigcarnenormal from arretipo where k00_tipo = $tipo_debito";

  db_fieldsmemory(db_query($sqlvalor), 0);

  if (!isset ($k00_tercdigcarnenormal) || $k00_tercdigcarnenormal == "") {
    db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
  }

  $iTercDig = $k00_tercdigcarnenormal;

  $ss = $ninfla;

  if ($k00_impval == 't') {

    if($k03_tipo == 3){

      $rsAnoissvar  = db_query("select q05_ano from issvar where q05_numpre = $k00_numpre");
      $intAnoissvar = pg_numrows($rsAnoissvar);
      db_fieldsmemory($rsAnoissvar,0);

      if($intAnoissvar > 0 && $q05_ano <= date("Y", $H_DATAUSU)){

        $k00_valor = $total;
        $vlrbar    = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
        $ninfla    = '';

      }

      if($total == 0){
        $iTercDig = 7;
      }

    } else {

      $ninfla_ant = $ninfla;

      if ($total > 0){

        $k00_valor = $total;
        $vlrbar    = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
        $ninfla    = '';
      } else {

        $vlrbar    = db_formatar(str_replace('.', '', str_pad(number_format(0, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
        $k00_valor = $qinfla;
      }

      $k00_valor  = $total;

  		/**
  		 * Número de exercicios para correção configurado para cada tipo de débito
  		 */
  		$iExerciciosCarne = $k00_exercicioscarne;

      if (empty($k00_exercicioscarne)) {
        $iExerciciosCarne = 0;
      }

      if ( ($total == 0) || (substr($k00_dtvenc, 6, 4) > date("Y", $H_DATAUSU) + $iExerciciosCarne ) ) {

            if ($ninfla_ant == "REAL") {

              $iTercDig = 6;
              $vlrbar   = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
            } else {

              $iTercDig = 7;
              $vlrbar   = "00000000000";
              if ($total != 0) {

                $k00_valor = $qinfla;
                $ninfla    = $ss;
              }
            }
          }
    } // Else do Issqn

  } else {

    $k00_valor = $qinfla;
    $iTercDig  = 7;
    $vlrbar    = "00000000000";
  }

  $dtvenc         = substr($k00_dtvenc, 6, 4).substr($k00_dtvenc, 3, 2).substr($k00_dtvenc, 0, 2);
  $datavencimento = $dtvenc;
  $dtVencimento   = $dtvenc;
  $tmpdt          = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);

  $dDataOperacao  = str_replace('/', '', $oPost->k00_dtoper);
  $dDataOperacao  = substr($dDataOperacao, 4, 7).substr($dDataOperacao, 2, 2).substr($dDataOperacao, 0, 2);

  if ($tmpdt > $datavencimento && $k00_valor > 0) {
    $dtVencimento = $tmpdt;
  }

  $db_dtvenc = str_replace("-", "", $datavencimento);

  if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {

    if (substr($db_dtvenc, 0, 4) > db_getsession('DB_anousu')) {
      continue;
    }
  }

  $ninfla_ant = $ninfla;

  if($oRegraEmissao->isCobranca()){

    if (substr($db_dtvenc, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0 && ( $ninfla_ant != "" && $ninfla_ant != "REAL")) {

      $k00_valor = 0;
      $especie   = $ninfla;
      $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
    }else{

      $especie   = 'R$';
      $histinf   = "";
    }

    if($dtvenc < date('Ymd',db_getsession('DB_datausu')) && $k00_valor > 0){

      $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;
      $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
    }else{
      $msgvencida = "";
    }

    if(isset($qinfla) && $qinfla != '' && $k00_valor == 0){
      $k00_valor = $qinfla;
    }
  }

  if($recibopaga == false){

    $iNumpre  = $iCodigoRecibo;
    $iNumpar  = 0;

    if ( empty($iCodigoRecibo) ) {

      $iNumpre  = $k00_numpre;
      $iNumpar  = $k00_numpar;
    }
  }else{

    $iNumpre  = $k03_numpre;
    $iNumpar  = 0;
  }

  if ($tipo_debito == 3 && $nValorTot == 0) {
    $nValorTot = $nValorTot;
  } else{
    $nValorTot += $taxabancaria;
  }

  try {
    $oConvenio = new convenio($oRegraEmissao->getConvenio(),$iNumpre,$iNumpar,$nValorTot,$vlrbar,$sVencimento,$iTercDig);
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
    exit;
  }

  /**
  *   Cobrança registrada
  *
  */
  try {
    if ($lConvenioCobrancaValido && CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
       CobrancaRegistrada::registrarReciboWebservice($iNumpre, $oRegraEmissao->getConvenio(), $nValorTot);
    }
  } catch (Exception $oErro) {
    db_redireciona("db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
    exit;
  }

  $codigo_barras   = $oConvenio->getCodigoBarra();
  $linha_digitavel = $oConvenio->getLinhaDigitavel();

  $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
  $pdf1->carteira        = $oConvenio->getCarteira();

  if($oRegraEmissao->isCobranca()){
    $pdf1->nosso_numero = $oConvenio->getNossoNumero();
  }

  if($recibopaga==false){

    $numpre = db_sqlformatar($k00_numpre, 8, '0').'000999';
    $numpre = $numpre.db_CalculaDV($numpre, 11);
  }else{

    $numpre = db_sqlformatar($k03_numpre, 8, '0').'000999';
    $numpre = $numpre.db_CalculaDV($numpre, 11);
  }

  global $pdf;

  $pdf1->descr12_2 = '';
  $pdf1->titulo1   = $descr;
  $pdf1->descr1    = $iNumeroOrigem;

  $sNumparzinho = '000';

  if (empty($iCodigoRecibo)) {
    $iCodigoRecibo = $k00_numpre;
    $sNumparzinho  = str_pad($k00_numpar, 3, '0', STR_PAD_LEFT);
  }

  if($recibopaga ==false){
    $pdf1->descr2 = db_numpre($iCodigoRecibo, 0).$sNumparzinho;
  }else{
    $pdf1->descr2 = db_numpre($k03_numpre, 0).db_formatar(0, 's', "0", 3, "e");
  }

  $pdf1->tipo_exerc      = "$k00_tipo / ".substr($k00_dtoper,0,4);

  /************  BUSCA RECEITAS COM OS VALORES *****************/
  $recibopaga =false;

  if($recibopaga == false) {

    $sqlReceitas  = " select substr(fc_calcula,15,13)::float8 as valor_corrigido,           ";
    $sqlReceitas .= "        substr(fc_calcula,28,13)::float8 as valor_juros,               ";
    $sqlReceitas .= "        substr(fc_calcula,41,13)::float8 as valor_multa,               ";
    $sqlReceitas .= "        (substr(fc_calcula,54,13)::float8 * -1)as valor_desconto,      ";
    $sqlReceitas .= "        (substr(fc_calcula,15,13)::float8+                             ";
    $sqlReceitas .= "        substr(fc_calcula,28,13)::float8+                              ";
    $sqlReceitas .= "        substr(fc_calcula,41,13)::float8-                              ";
    $sqlReceitas .= "        substr(fc_calcula,54,13)::float8) as valreceita,               ";
    $sqlReceitas .= "        codreceita,                                                    ";
    $sqlReceitas .= "        k00_hist,                                                      ";
    $sqlReceitas .= "        valor_historico,                                               ";
    $sqlReceitas .= "        codtipo,                                                       ";
    $sqlReceitas .= "        descrreceita,                                                  ";
    $sqlReceitas .= "        reduzreceita                                                   ";
    $sqlReceitas .= "   from (                                                              ";
    $sqlReceitas .= " select k00_receit as codreceita,                                      ";
    $sqlReceitas .= "        k02_descr  as descrreceita,                                    ";
    $sqlReceitas .= "        case when taborc.k02_codigo is not null then k02_codrec        ";
    $sqlReceitas .= "             when tabplan.k02_codigo is not null then k02_reduz        ";
    $sqlReceitas .= "        end  as reduzreceita,                                          ";
    $sqlReceitas .= "        k00_valor  as val,                                             ";
    $sqlReceitas .= "        k00_tipo as codtipo,                                           ";
    $sqlReceitas .= "        k00_hist,                                                      ";
    $sqlReceitas .= "        case when a.k00_tipo = 3 then issvar.q05_vlrinf                ";
    $sqlReceitas .= "             else a.k00_valor                                          ";
    $sqlReceitas .= "        end as valor_historico,                                        ";
    $sqlReceitas .= "        fc_calcula(a.k00_numpre,                                       ";
    $sqlReceitas .= "                   a.k00_numpar,                                       ";
    $sqlReceitas .= "                   a.k00_receit,                                       ";
    $sqlReceitas .= "                   ( case when k00_dtvenc < '".date("Y-m-d",db_getsession("DB_datausu"))."'";
    $sqlReceitas .= "                          then '".date('Y-m-d',$H_DATAUSU)."'          ";
    $sqlReceitas .= "                          else k00_dtvenc end ),                       ";
    $sqlReceitas .= "                   ( case when k00_dtvenc < '".date("Y-m-d",db_getsession("DB_datausu"))."'";
    $sqlReceitas .= "                          then '".date('Y-m-d',$H_DATAUSU)."'          ";
    $sqlReceitas .= "                          else k00_dtvenc end ),                       ";
    $sqlReceitas .=                     $H_ANOUSU.")                                        ";
    $sqlReceitas .= "   from arrecad a                                                      ";
    $sqlReceitas .= "        inner join tabrec  on tabrec.k02_codigo = a.k00_receit         ";
    $sqlReceitas .= "        left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo  ";
    $sqlReceitas .= "                          and taborc.k02_anousu   = ".db_getsession('DB_anousu');
    $sqlReceitas .= "        left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo ";
    $sqlReceitas .= "                          and tabplan.k02_anousu  = ".db_getsession('DB_anousu');
    $sqlReceitas .= "        left join issvar   on a.k00_numpre        = q05_numpre         ";
    $sqlReceitas .= "                          and a.k00_numpar        = q05_numpar         ";
    $sqlReceitas .= " where a.k00_numpre = $k00_numpre                                      ";
    $sqlReceitas .= "   and a.k00_numpar = $k00_numpar ) as c                               ";

  }else{

    $sqlReceitas = " select k00_tipo as codtipo from arrecad where k00_numpre = $k00_numpre ";
    $rsReceitas  = db_query($sqlReceitas);
    if(pg_numrows($rsReceitas) == 0){

      db_msgbox("Não encontrado arrecad ($k00_numpre).");
      exit;
    }

    db_fieldsmemory($rsReceitas, 0);

    $sqlReceitas  = " select valor_corrigido,                                                           ";
    $sqlReceitas .= "        valor_historico,                                                           ";
    $sqlReceitas .= "        valor_juros,                                                               ";
    $sqlReceitas .= "        valor_multa,                                                               ";
    $sqlReceitas .= "        valor_desconto,                                                            ";
    $sqlReceitas .= "        ( coalesce(valor_corrigido,0) +                                            ";
    $sqlReceitas .= "          coalesce(valor_juros,0)     +                                            ";
    $sqlReceitas .= "          coalesce(valor_multa,0)     -                                            ";
    $sqlReceitas .= "          coalesce(valor_desconto,0)                                               ";
    $sqlReceitas .= "        ) as valreceita,                                                           ";
    $sqlReceitas .= "        codreceita,                                                                ";
    $sqlReceitas .= "        k00_hist,                                                                  ";
    $sqlReceitas .= "        codtipo,                                                                   ";
    $sqlReceitas .= "        descrreceita,                                                              ";
    $sqlReceitas .= "        reduzreceita                                                               ";
    $sqlReceitas .= "    from ( select recibopaga.k00_valor as valor_corrigido,                         ";
    $sqlReceitas .= "                  a.k00_valor          as valor_historico,                         ";
    $sqlReceitas .= "                  (select k00_valor                                                ";
    $sqlReceitas .= "                     from recibopaga juros                                         ";
    $sqlReceitas .= "                    where juros.k00_numnov = {$k03_numpre}                         ";
    $sqlReceitas .= "                      and juros.k00_numpar = recibopaga.k00_numpar                 ";
    $sqlReceitas .= "                      and juros.k00_receit = tabrec.k02_recjur                     ";
    $sqlReceitas .= "                  limit 1) as valor_juros,                                         ";
    $sqlReceitas .= "                  (select k00_valor                                                ";
    $sqlReceitas .= "                     from recibopaga multa                                         ";
    $sqlReceitas .= "                    where multa.k00_numnov = {$k03_numpre}                         ";
    $sqlReceitas .= "                      and multa.k00_numpar = recibopaga.k00_numpar                 ";
    $sqlReceitas .= "                      and multa.k00_receit = tabrec.k02_recmul                     ";
    $sqlReceitas .= "                  limit 1) as valor_multa,                                         ";
    $sqlReceitas .= "                  (select k00_valor                                                ";
    $sqlReceitas .= "                     from recibopaga desconto                                      ";
    $sqlReceitas .= "                    where desconto.k00_numnov = {$k03_numpre}                      ";
    $sqlReceitas .= "                      and desconto.k00_numpar = recibopaga.k00_numpar              ";
    $sqlReceitas .= "                      and desconto.k00_hist   = 918                                ";
    $sqlReceitas .= "                  limit 1) as valor_desconto,                                      ";
    $sqlReceitas .= "                  recibopaga.k00_receit as codreceita,                             ";
    $sqlReceitas .= "                  k02_descr             as descrreceita,                           ";
    $sqlReceitas .= "                  case                                                             ";
    $sqlReceitas .= "                    when taborc.k02_codigo is not null then k02_codrec             ";
    $sqlReceitas .= "                    when tabplan.k02_codigo is not null then k02_reduz             ";
    $sqlReceitas .= "                  end                   as reduzreceita,                           ";
    $sqlReceitas .= "                  recibopaga.k00_valor  as val,                                    ";
    $sqlReceitas .= "                  k00_tipo              as codtipo,                                ";
    $sqlReceitas .= "                  recibopaga.k00_hist                                              ";
    $sqlReceitas .= "             from recibopaga                                                       ";
    $sqlReceitas .= "                  inner join arrecad a on a.k00_numpre     = recibopaga.k00_numpre ";
    $sqlReceitas .= "                                      and a.k00_numpar     = recibopaga.k00_numpar ";
    $sqlReceitas .= "                                      and a.k00_receit     = recibopaga.k00_receit ";
    $sqlReceitas .= "                  inner join tabrec    on tabrec.k02_codigo  = a.k00_receit        ";
    $sqlReceitas .= "                  left  join taborc    on tabrec.k02_codigo  = taborc.k02_codigo   ";
    $sqlReceitas .= "                                      and taborc.k02_anousu  = ".db_getsession('DB_anousu');
    $sqlReceitas .= "                  left  join tabplan   on tabrec.k02_codigo  = tabplan.k02_codigo  ";
    $sqlReceitas .= "                                      and tabplan.k02_anousu = ".db_getsession('DB_anousu');
    $sqlReceitas .= "  where recibopaga.k00_numnov = $k03_numpre  ) as c                                ";
  }

  $rsReceitas = db_query($sqlReceitas);
  $intnumrows = pg_num_rows($rsReceitas);

  $vlrjuros     = 0;
  $vlrmulta     = 0;
  $vlrhistorico = 0;
  $nDesconto    = 0;

  unset($pdf1->arraycodhist);
  unset($pdf1->arraycodtipo);
  unset($pdf1->arraycodreceitas);
  unset($pdf1->arrayreduzreceitas);
  unset($pdf1->arraydescrreceitas);
  unset($pdf1->arrayvalreceitas);

  $nTotalDebito = 0;
  for ($x = 0; $x < $intnumrows; $x ++) {

    db_fieldsmemory($rsReceitas, $x);

    $pdf1->arraycodreceitas[$x]   = $codreceita;
    $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
    $pdf1->arraydescrreceitas[$x] = $descrreceita;

    if ($k00_hist != 918) {

      $nTotalDebito += $valor_corrigido;
      $pdf1->arrayvalreceitas[$x] = $valor_corrigido;
    } else {
      $pdf1->arrayvalreceitas[$x] = $valor_historico;
    }

    $pdf1->arraycodtipo[$x]       = $codtipo;
    $pdf1->arraycodhist[$x]       = $k00_hist;
    $vlrhistorico  += $valor_historico;

    if ($k00_hist != 918) {

      $vlrjuros      += $valor_juros;
      $vlrmulta      += $valor_multa;
    }

    $nDesconto     += $valor_desconto;
  }

  $pdf1->valororigem = db_formatar($vlrhistorico,"f");

  if(isset($vlrjuros) && $vlrjuros != "" && $vlrjuros !=0){

    $pdf1->arraycodhist[]       = "";
    $pdf1->arraycodtipo[]       = "t";
    $pdf1->arraycodreceitas[]   = "";
    $pdf1->arrayreduzreceitas[] = "";
    $pdf1->arraydescrreceitas[] = "Juros : ";
    $pdf1->arrayvalreceitas[]   = $vlrjuros;
  }
  if(isset($vlrmulta) && $vlrmulta != "" && $vlrmulta != 0){

    $pdf1->arraycodhist[]       = "";
    $pdf1->arraycodtipo[]       = "t";
    $pdf1->arraycodreceitas[]   = "";
    $pdf1->arrayreduzreceitas[] = "";
    $pdf1->arraydescrreceitas[] = "Multa : ";
    $pdf1->arrayvalreceitas[]   = $vlrmulta;
  }
  if(isset($nDesconto) && $nDesconto != "" && $nDesconto != 0){

    $pdf1->arraycodhist[]       = 918;
    $pdf1->arraycodtipo[]       = "t";
    $pdf1->arraycodreceitas[]   = "";
    $pdf1->arrayreduzreceitas[] = "";
    $pdf1->arraydescrreceitas[] = "Desconto : ";
    $pdf1->arrayvalreceitas[]   = $nDesconto;
  }
  /***********************************************************************************************/

  if(!empty ($aDados["ver_matric"])) {

    $sqlEnder  = " select z01_nome,                                               ";
    $sqlEnder .= "        z01_ender,                                              ";
    $sqlEnder .= "        z01_numero,                                             ";
    $sqlEnder .= "        z01_compl,                                              ";
    $sqlEnder .= "        z01_munic as j43_munic,                                 ";
    $sqlEnder .= "        z01_uf as j43_uf,                                       ";
    $sqlEnder .= "        z01_cep as j43_cep,                                     ";
    $sqlEnder .= "        nomepri as j43_ender,                                   ";
    $sqlEnder .= "        j39_compl as j43_compl,                                 ";
    $sqlEnder .= "        j39_numero,                                             ";
    $sqlEnder .= "        j13_descr as j43_bairro,                                ";
    $sqlEnder .= "        case when j13_descr is not null and j13_descr != '' then";
    $sqlEnder .= "           j13_descr                                            ";
    $sqlEnder .= "            else ''                                             ";
    $sqlEnder .= "        end as j13_descr,                                       ";
    $sqlEnder .= "        j34_setor||'.'||j34_quadra||'.'||j34_lote as sql,       ";
    $sqlEnder .= "        z01_cgccpf,                                             ";
    $sqlEnder .= "        z01_bairro,                                             ";
    $sqlEnder .= "        z01_numcgm                                              ";
    $sqlEnder .= "   from proprietario                                            ";
    $sqlEnder .= "  where j01_matric = $j01_matric limit 1                        ";

    $rsresultender   = db_query($sqlEnder);
    $intNumrowsEnder = pg_numrows($rsresultender);

    if($intNumrowsEnder > 0){
      db_fieldsmemory($rsresultender,0);
    }

    if (!empty($j43_ender)) {

      $sEnderecoMatricula = $j43_ender;
      if (!empty($j39_numero)) {
        $sEnderecoMatricula .= $j39_numero;
      }
      if (!empty($j43_compl)) {
        $sEnderecoMatricula .= "  - {$j43_compl}";
      }
    }

    if (!empty($j43_bairro)) {
      $sMatriculaBairro = $j43_bairro;
    }
    $sEnderecoMatricula  = "{$j43_ender}, {$j39_numero}";
    $pdf1->pretipocompl  = 'Número:';
    $pdf1->tipocompl     = 'Número:';
    $pdf1->tipobairro    = 'Bairro:';
    $pdf1->bairropri     = $j13_descr;
    $pdf1->descr11_1     = $z01_cgmpri." - ".$proprietario;
    $pdf1->descr11_2     = $xender;
    $pdf1->descr11_3     = $xbairro;
    if (!empty($sMatriculaBairro)) {
      $pdf1->descr11_3     = $sMatriculaBairro;
    }

    $pdf1->bairrocontri  = $z01_bairro;
    $pdf1->munic         = $j43_munic;
    $pdf1->premunic      = $j43_munic;
    $pdf1->uf            = $z01_uf;
    $pdf1->ufcgm         = $z01_uf;
    $pdf1->descr3_1      = $z01_cgmpri." - ".$proprietario;
    $pdf1->descr3_2      = $xender;
    $pdf1->predescr3_1   = $z01_cgmpri." - ".$proprietario;
    $pdf1->predescr3_2   = $z01_ender." ".$z01_numero;
    $pdf1->descr3_3      = $xbairro;
    $pdf1->descr17       = $bql;
    $pdf1->tipoinscr     = 'Matricula';
    $pdf1->nrinscr       = $j01_matric.' - SQL:'.$sql;
    $pdf1->tipolograd    = 'Rua ';
    $pdf1->pretipolograd = 'Rua ';
    $pdf1->cep           = $z01_cep;
    $pdf1->precep        = $z01_cep;
    $pdf1->nomepri       = $z01_ender;
    $pdf1->prenomepri    = $j43_ender;
    $pdf1->nrpri         = $j39_numero;
    $pdf1->prenrpri      = $j39_numero;
    $pdf1->complpri      = empty($j43_compl) ? '' : $j43_compl;
    $pdf1->precomplpri   = empty($j43_compl) ? '' : $j43_compl;
    $pdf1->precgccpf     = $z01_cgccpf;
    $pdf1->cgccpf        = $z01_cgccpf;

  }else if(!empty($aDados["ver_inscr"])) {

    $iNumInscr = $origem;
    if( !empty($aDados["ver_inscr"])){
      $iNumInscr = $aDados["ver_inscr"];
    }

    $sSqlInscr  = "  select cgm.z01_numcgm,                                          ";
    $sSqlInscr .= "         cgm.z01_nome,                                            ";
    $sSqlInscr .= "         cgm.z01_ender,                                           ";
    $sSqlInscr .= "         cgm.z01_numero,                                          ";
    $sSqlInscr .= "         cgm.z01_compl,                                           ";
    $sSqlInscr .= "         cgm.z01_bairro,                                          ";
    $sSqlInscr .= "         cgm.z01_munic,                                           ";
    $sSqlInscr .= "         cgm.z01_uf,                                              ";
    $sSqlInscr .= "         cgm.z01_cep,                                             ";
    $sSqlInscr .= "         empresa.z01_ender as nomepri,                            ";
    $sSqlInscr .= "         empresa.z01_compl as j39_compl,                          ";
    $sSqlInscr .= "         empresa.z01_numero as j39_numero,                        ";
    $sSqlInscr .= "         empresa.z01_bairro as j13_descr,                         ";
    $sSqlInscr .= "         '' as sql,                                               ";
    $sSqlInscr .= "         cgm.z01_cgccpf                                           ";
    $sSqlInscr .= "    from issbase                                                  ";
    $sSqlInscr .= "     inner join empresa on issbase.q02_inscr  = empresa.q02_inscr ";
    $sSqlInscr .= "     inner join cgm     on issbase.q02_numcgm = cgm.z01_numcgm    ";
    $sSqlInscr .= "   where issbase.q02_inscr = $iNumInscr                           ";

    $rsInscr = db_query($sSqlInscr) or die($sSqlInscr);
    db_fieldsmemory($rsInscr,0);

    $pdf1->pretipocompl  = 'Número:';
    $pdf1->tipocompl     = 'Número:';
    $pdf1->tipobairro    = 'Bairro:';
    $pdf1->bairropri     = $j13_descr;
    $pdf1->descr11_1     = $z01_numcgm." - ".$z01_nome;
    $pdf1->descr11_2     = strtoupper($nomepri). ($j39_numero == "" ? "" : ', '.$j39_numero.'  '.$j39_compl);
    $pdf1->descr11_3     = $z01_bairro;
    $pdf1->bairrocontri  = $z01_bairro;
    $pdf1->munic         = $z01_munic;
    $pdf1->premunic      = $z01_munic;
    $pdf1->uf            = $z01_uf;
    $pdf1->ufcgm         = $z01_uf;
    $pdf1->descr3_1      = $z01_numcgm." - ".$z01_nome;
    $pdf1->descr3_2      = strtoupper($nomepri). ($j39_numero == "" ? "" : ', '.$j39_numero.'  '.$j39_compl);
    $pdf1->predescr3_1   = $z01_numcgm." - ".$z01_nome;
    $pdf1->predescr3_2   = $z01_ender." ".$z01_numero;
    $pdf1->descr3_3      = $z01_bairro;
    $pdf1->tipoinscr     = 'Inscrição';
    $pdf1->nrinscr       = $iNumInscr;
    $pdf1->tipolograd    = 'Rua ';
    $pdf1->pretipolograd = 'Rua ';
    $pdf1->cep           = $z01_cep;
    $pdf1->precep        = $z01_cep;
    $pdf1->nomepriimo    = $nomepri;
    $pdf1->nomepri       = $nomepri;
    $pdf1->prenomepri    = $nomepri;;
    $pdf1->nrpri         = $j39_numero;
    $pdf1->prenrpri      = $j39_numero;
    $pdf1->complpri      = $j39_compl;
    $pdf1->precomplpri   = $j39_compl;
    $pdf1->precgccpf     = $z01_cgccpf;
    $pdf1->cgccpf        = $z01_cgccpf;


  } else if(!empty($aDados["ver_numcgm"])) {

    $iNumcgm = $origem;
    if( !empty($aDados["ver_numcgm"])){
      $iNumcgm = $aDados["ver_numcgm"];
    }

    $sSqlNumCgm  = " select z01_numcgm,           ";
    $sSqlNumCgm .= "        z01_nome,             ";
    $sSqlNumCgm .= "        z01_ender,            ";
    $sSqlNumCgm .= "        z01_numero,           ";
    $sSqlNumCgm .= "        z01_compl,            ";
    $sSqlNumCgm .= "        z01_bairro,           ";
    $sSqlNumCgm .= "        z01_munic,            ";
    $sSqlNumCgm .= "        z01_uf,               ";
    $sSqlNumCgm .= "        z01_cep,              ";
    $sSqlNumCgm .= "        z01_cgccpf            ";
    $sSqlNumCgm .= "   from cgm                   ";
    $sSqlNumCgm .= "  where z01_numcgm = $iNumcgm ";

    $rsNumCgm = db_query($sSqlNumCgm) or die($sSqlNumCgm);
    db_fieldsmemory($rsNumCgm,0);

    $pdf1->pretipocompl  = 'Número:';
    $pdf1->tipocompl     = 'Número:';
    $pdf1->tipobairro    = 'Bairro:';
    $pdf1->bairropri     = $z01_bairro;
    $pdf1->descr11_1     = $z01_numcgm." - ".$z01_nome;
    $pdf1->descr11_2     = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
    $pdf1->descr11_3     = $z01_bairro;
    $pdf1->bairrocontri  = $z01_bairro;
    $pdf1->munic         = $z01_munic;
    $pdf1->premunic      = $z01_munic;
    $pdf1->uf            = $z01_uf;
    $pdf1->ufcgm         = $z01_uf;
    $pdf1->descr3_1      = $z01_numcgm." - ".$z01_nome;
    $pdf1->descr3_2      = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
    $pdf1->predescr3_1   = $z01_numcgm." - ".$z01_nome;
    $pdf1->predescr3_2   = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
    $pdf1->descr3_3      = $z01_bairro;
    $pdf1->tipoinscr     = 'Cgm';
    $pdf1->nrinscr       =  $z01_numcgm;
    $pdf1->tipolograd    = 'Rua ';
    $pdf1->pretipolograd = 'Rua ';
    $pdf1->cep           = $z01_cep;
    $pdf1->precep        = $z01_cep;
    $pdf1->nomepri       = $z01_ender;
    $pdf1->nomepriimo    = $z01_ender;
    $pdf1->prenomepri    = $z01_ender;
    $pdf1->nrpri         = $z01_numero;
    $pdf1->prenrpri      = $z01_numero;
    $pdf1->complpri      = $z01_compl;
    $pdf1->precomplpri   = $z01_compl;
    $pdf1->precgccpf     = $z01_cgccpf;
    $pdf1->cgccpf        = $z01_cgccpf;

  } else {

    $pdf1->descr11_1     = $z01_numcgm." - ".$z01_nome;
    $pdf1->descr11_2     = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
    $pdf1->descr11_3     = $z01_bairro;
    $pdf1->descr3_1      = $z01_numcgm." - ".$z01_nome;
    $pdf1->descr3_2      = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
    $pdf1->predescr3_1   = $z01_numcgm." - ".$z01_nome;
    $pdf1->predescr3_2   = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
    $pdf1->descr3_3      = $z01_bairro;
    $pdf1->bairrocontri  = $z01_bairro;
    $pdf1->prebairropri  = $z01_bairro;
    $pdf1->bairropri     = $z01_bairro;
    $pdf1->cep           = $z01_cep;
    $pdf1->precep        = $z01_cep;
    $pdf1->precgccpf     = $z01_cgccpf;
    $pdf1->uf            = $z01_uf;
    $pdf1->tipoinscr     = 'Cgm';
    $pdf1->nrinscr       = $z01_numcgm;
    $pdf1->munic         = $z01_munic;
    $pdf1->premunic      = $z01_munic;
    $pdf1->tipolograd    = 'Rua ';
    $pdf1->pretipolograd = 'Rua ';
    $pdf1->nomepri       = $z01_ender;
    $pdf1->prenomepri    = $z01_ender;
  }

  if ($k00_hist1 == '' || $k00_hist2 == '') {

    $pdf1->descr4_1            = $k00_numpar.'a PARCELA';
    $pdf1->historicoparcela    = $k00_numpar.'a PARCELA';
    $pdf1->prehistoricoparcela = $k00_numpar.'a PARCELA';

    if ($k03_tipo == 16) {

      $sqldiversos = "select distinct dv05_obs
                        from termo
                             inner join termodiver on dv10_parcel   = v07_parcel
                             inner join diversos   on dv05_coddiver = dv10_coddiver
                       where v07_numpre = $k00_numpre";
      $resultdiversos = db_query($sqldiversos);

      if (pg_numrows($resultdiversos) > 0) {

        db_fieldsmemory($resultdiversos, 0, true);
        $pdf1->descr4_2    = substr($dv05_obs, 0, 100);
        $pdf1->predescr4_2 = substr($dv05_obs, 0, 100);
        $obsdiver          = substr($dv05_obs, 0, 100);
      }

    } else if ($k03_tipo == 7) {

      $sqldiversos    = "select distinct dv05_obs from diversos where dv05_numpre = $k00_numpre";
      $resultdiversos = db_query($sqldiversos);

      if (pg_numrows($resultdiversos) > 0) {

        db_fieldsmemory($resultdiversos, 0, true);
        $pdf1->descr4_2    = substr($dv05_obs, 0, 100);
        $pdf1->predescr4_2 = substr($dv05_obs, 0, 100);
        $obsdiver          = substr($dv05_obs, 0, 100);
      }
    }
  }else{

    if (isset ($k00_hist1) && $k00_hist1 != "" && $k00_hist1 != ".") {
      $pdf1->descr4_1 = $k00_hist1;
    }

    if (isset ($k00_hist2) && $k00_hist2 != "" && $k00_hist2 != ".") {

      $pdf1->descr4_2 = $k00_hist2;
      $pdf1->predescr4_2 = $k00_hist2;
    }
  }

  /**************  SE FOR CARNE DE VISTORIAS PEGA OS DADOS DA VISTORIA  **************/
  $sqlvistorias = " select y77_descricao, extract (year from y70_data) as ano_vistoria
                      from vistorianumpre
                           inner join vistorias     on vistorias.y70_codvist  = vistorianumpre.y69_codvist
                           inner join tipovistorias on vistorias.y70_tipovist = tipovistorias.y77_codtipo
                     where y69_numpre = $k00_numpre";
  $rsvistorias  = db_query($sqlvistorias);
  /***********************************************************************************/

  if (pg_numrows($rsvistorias) > 0) {

    db_fieldsmemory($rsvistorias, 0);
    $pdf1->tipodebito    = $y77_descricao." - $ano_vistoria";
    $pdf1->pretipodebito = $y77_descricao." - $ano_vistoria";
  }

  if (isset ($obs)) {

    $pdf1->titulo13 = 'Observação';
    $pdf1->descr13  = $obs;
  }

  if ($k03_tipo == 2) {

    $pdf1->titulo4  = 'Atividade';
    $pdf1->descr4_1 = '- '.$q07_ativ.'-'.$q03_descr;
    $pdf1->titulo13 = 'Atividade';
    $pdf1->descr13  = $q07_ativ;
  } else if (($k03_tipo == 6) || ($k03_tipo == 13)) {

    $pdf1->titulo4  = 'Parcelamento';
    $pdf1->descr4_1 = '- '.$v07_parcel.$exercicio;
    $pdf1->titulo13 = 'Parcelamento';
    $pdf1->descr13  = $v07_parcel;
  }

  $oMensagem = DBTributario::getMensagensParcela($k00_numpre, $k00_numpar, $oPost->k00_dtoper);

  $pdf1->sMensagemContribuinte = $oMensagem->sMensagemContribuinte;
  $pdf1->sMensagemCaixa        = $oMensagem->sMensagemCaixa;

  $pdf1->descr5 = $k00_numpar.' / '.$k00_numtot;
  $tmpdta       = split("/",$k00_dtvenc);
  $tmpdtvenc    = $tmpdta[2]."-".$tmpdta[1]."-".$tmpdta[0];

  if($db_datausu > $tmpdtvenc && $k00_valor > 0){

    $pdf1->dtparapag    = db_formatar($db_datausu,'d');
    $pdf1->datacalc     = db_formatar($db_datausu,'d');
    $pdf1->predatacalc  = db_formatar($db_datausu,'d');
    $pdf1->confirmdtpag = 't';
  }else{

    $pdf1->dtparapag    = $k00_dtvenc;
    $pdf1->datacalc     = $k00_dtvenc;
    $pdf1->predatacalc  = $k00_dtvenc;
    $pdf1->confirmdtpag = 't';
  }

  $pdf1->descr6     = $k00_dtvenc;
  $pdf1->predescr6  = $k00_dtvenc;
  $pdf1->titulo8    = $descr;
  $pdf1->pretitulo8 = $descr;
  $pdf1->descr8     = $iNumeroOrigem;
  $pdf1->predescr8  = $iNumeroOrigem;

  if($recibopaga == false){

    $pdf1->descr9     = db_numpre($iCodigoRecibo, 0). str_pad($sNumparzinho, 3, '0', STR_PAD_LEFT);
    $pdf1->predescr9  = db_numpre($iCodigoRecibo, 0). str_pad($sNumparzinho, 3, '0', STR_PAD_LEFT);
  }else{

    $pdf1->descr9     = db_numpre($k03_numpre, 0).db_formatar(0, 's', "0", 3, "e");
    $pdf1->predescr9  = db_numpre($k03_numpre, 0).db_formatar(0, 's', "0", 3, "e");
  }

  $pdf1->descr10 = $k00_numpar.' / '.$k00_numtot;
  $pdf1->descr14 = $k00_dtvenc;

  if ($total == 0) {

    //////////// ISSQN VARIAVEL ///////////
    if ($k03_tipo == 3) {

      $sqlaliq     = "select q05_aliq, q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
      $rsIssvarano = db_query($sqlaliq);
      $intNumrows  = pg_numrows($rsIssvarano);
      if ($intNumrows == 0) {

        db_redireciona('db_erros.php?fechar=true&db_erro=Ano não encontrado na tabela issvar. Contate o suporte');
        exit;
      }

      db_fieldsmemory($rsIssvarano, 0);
      $pdf1->descr4_1 = $k00_numpar.'a PARCELA   -   Alíquota '.$q05_aliq.'%     EXERCÍCIO : '.$q05_ano;
    }

    $pdf1->titulo7    = 'Valor Pago';
    $pdf1->titulo15   = 'Valor Pago';
    $pdf1->titulo13   = 'Valor da Receita Tributável';
    $pdf1->descr15    = '';
    $pdf1->valtotal   = '';
    $pdf1->descr7     = '';
    $pdf1->predescr7  = '';

  } else {

    $pdf1->mora_multa = db_formatar($vlrmulta+$vlrjuros,"f");
    $pdf1->descr15    = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
    $pdf1->valtotal   = db_formatar($k00_valor, 'f'); //$k00_valor;
    $pdf1->descr7     = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
    $pdf1->predescr7  = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
  }

  if($oRegraEmissao->isCobranca()){

      $pdf1->descr12_1 .= $pdf1->tipodebito . "\n"  .
                          $pdf1->titulo1    . " - " .
                          $pdf1->descr1     . " / " .
                          $pdf1->titulo4    . " "   .
                          $pdf1->descr4_1   . " Parcela - " .
                          $k00_numpar."/".$k00_numtot . "\n" .
                          (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
                        (isset($pdf1->predescr12_1)?$pdf1->predescr12_1 .= $pdf1->pretipodebito:"")."\n".
                          $pdf1->titulo1    . " - " .
                          $pdf1->descr1     . " / " .
                          $pdf1->titulo4    . " "   .
                          $pdf1->descr4_1   . " Parcela - ".
                          $k00_numpar."/".$k00_numtot."\n".
                          (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";


  }

  $sSqlCarne  = "select k03_msgcarne,                           ";
  $sSqlCarne .= "       k03_msgbanco                            ";
  $sSqlCarne .= "  from numpref                                 ";
  $sSqlCarne .= " where k03_anousu = ".db_getsession("DB_anousu");
  $sSqlCarne .= "   and k03_instit = ".db_getsession("DB_instit");

  $rsmsgcarne = db_query($sSqlCarne);

  if (pg_numrows($rsmsgcarne) > 0) {
    db_fieldsmemory($rsmsgcarne, 0);
  }

  if ($pagabanco == 't') {

    if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < $dDataOperacao)) {

      if (isset ($k00_msgparcvenc2) && $k00_msgparcvenc2 != "") {

        $pdf1->descr12_1    .= $k00_msgparcvenc." ".$histinf." ".$msgvencida;
        $pdf1->predescr12_1 .= $k00_msgparcvenc." ".$histinf;
      }
    } else {

      if (isset ($k00_msgparc) && $k00_msgparc != "") {
        $pdf1->descr12_1 .= $k00_msgparc." ".$histinf;
      } elseif (isset ($k03_msgbanco) && $k03_msgbanco != "") {

        $pdf1->descr12_1    .= $k03_msgbanco." Não aceitar após vencimento.";
        $pdf1->predescr12_1 .= $k03_msgbanco." Não aceitar após vencimento.";
      }
    }

  } else {

    if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < $dDataOperacao)) {

      $pdf1->descr12_1 .= $k00_msgparcvenc2." ".$histinf;
    } elseif (isset ($k00_msgparc2) && $k00_msgparc2 != "") {

      $pdf1->descr12_1 .= $k00_msgparc2." ".$histinf;
    } elseif (isset ($k03_msgbanco) && $k03_msgbanco != "") {
      $pdf1->descr12_1 .= $k03_msgbanco." Após o vencimento cobrar juros de 1%a.m e multa de 2% ";
    } else {
      $pdf1->descr12_1 .= '- O PAGAMENTO DEVERÁ SER EFETUADO SOMENTE NA PREFEITURA.'." ".$histinf." ".$msgvencida;
    }
  }

  $sqlparag = "select db02_texto
                 from db_documento
                      inner join db_docparag  on db03_docum   = db04_docum
                      inner join db_paragrafo on db04_idparag = db02_idparag
                where db03_docum = 27
                  and db02_descr ilike '%MENSAGEM CARNE%'
                  and db03_instit = ".db_getsession("DB_instit");

  $resparag = db_query($sqlparag);

  if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < $dDataOperacao) && $k00_valor > 0) {

    $part1 = '';
    $part2 = '';
    $part3 = '';

    if (isset ($k00_msgparcvenc) && $k00_msgparcvenc != "") {

      if (strlen($k00_msgparcvenc) > 50) {
        $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strrpos(substr($k00_msgparcvenc, 0, 50), ' '));
      } else {
        $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strlen($k00_msgparcvenc));
      }

      if (strlen($k00_msgparcvenc) > 100) {
        $part2 = substr(substr($k00_msgparcvenc, strlen($part1), 50), 0, strrpos(substr($k00_msgparcvenc, strlen($part1), strlen($k00_msgparcvenc)), ' '));
      } else {
        $part2 = substr(substr($k00_msgparcvenc, strlen($part1) + 1, 50), 0, strlen($k00_msgparcvenc));
      }

      if (strlen($k00_msgparcvenc) > 150) {
        $part3 = substr(substr($k00_msgparcvenc, strlen($part2), 50), 0, strlen($k00_msgparcvenc));
      }

      $pdf1->descr16_1    = $part1;
      $pdf1->descr16_2    = $part2;
      $pdf1->descr16_3    = $part3;
      $pdf1->predescr16_1 = $part1;
      $pdf1->predescr16_2 = $part2;
      $pdf1->predescr16_3 = $part3;
    }

  } elseif (isset ($k00_msgparc) && $k00_msgparc != "") {

    $pdf1->descr16_1    = substr($k00_msgparc, 0, 50);
    $pdf1->descr16_2    = substr($k00_msgparc, 50, 50);
    $pdf1->descr16_3    = substr($k00_msgparc, 100, 50);
    $pdf1->predescr16_1 = substr($k00_msgparc, 0, 50);
    $pdf1->predescr16_2 = substr($k00_msgparc, 50, 50);
    $pdf1->predescr16_3 = substr($k00_msgparc, 100, 50);
  } else {

    if (isset ($k03_msgcarne) && $k03_msgcarne != "") {

      $pdf1->descr16_1    = substr($k03_msgcarne, 0, 50);
      $pdf1->descr16_2    = substr($k03_msgcarne, 50, 50);
      $pdf1->descr16_3    = substr($k03_msgcarne, 100, 50);
      $pdf1->predescr16_1 = substr($k03_msgcarne, 0, 50);
      $pdf1->predescr16_2 = substr($k03_msgcarne, 50, 50);
      $pdf1->predescr16_3 = substr($k03_msgcarne, 100, 50);
    } else {

      if (pg_numrows($resparag) == 0) {
        $db02_texto = "";
      } else {
        db_fieldsmemory($resparag, 0);
      }

      $pdf1->descr16_1    = "  ";
      $pdf1->descr16_1    = substr($db02_texto, 0, 55);
      $pdf1->descr16_2    = substr($db02_texto, 55, 55);
      $pdf1->descr16_3    = substr($db02_texto, 110, 55);
      $pdf1->predescr16_1 = substr($db02_texto, 0, 55);
      $pdf1->predescr16_2 = substr($db02_texto, 55, 55);
      $pdf1->predescr16_3 = substr($db02_texto, 110, 55);
    }

  }
  $pdf1->texto    = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();
  $imprimircodbar = true;

  $sqltermo       = "select k40_forma
                       from termo
                            inner join cadtipoparc on k40_codigo = v07_desconto
                      where v07_numpre = $k00_numpre";
  $resulttermo = db_query($sqltermo) or die($sqltermo);

  if (pg_numrows($resulttermo) > 0) {

    db_fieldsmemory($resulttermo, 0);
    if ($k40_forma == 2 and $k00_numpar == $k00_numtot) {
      $imprimircodbar=false;
    }
  }

  if ($imprimircodbar == true) {

    $pdf1->linha_digitavel = $linha_digitavel;
    $pdf1->codigo_barras   = $codigo_barras;
  } else {

    $pdf1->linha_digitavel = null;
    $pdf1->codigo_barras   = null;
  }

  db_sel_instit();

  $pdf1->enderpref  = $ender;
  $pdf1->numeropref = $numero;
  $pdf1->municpref  = $munic;
  $pdf1->telefpref  = $email;
  $pdf1->cgcpref    = $cgc;
  $pdf1->emailpref  = $telef;

  $pdf1->especie = empty($especie) ? '' : $especie;

  // VERIFICA SE É UM PARCELAMENTO COM DESCONTO, SE FOR MOSTRAR O DESCONTO NO CARNE.
  $sqlVerParcel = "select k00_numpre, k00_numpar, k00_receit, k00_dtvenc, k00_tipo, v07_totpar, k40_aplicacao,
                          (select sum(k00_valor)
                             from arrecad a
                            where a.k00_numpre = arrecad.k00_numpre
                              and a.k00_numpar = arrecad.k00_numpar ) as k00_valor
                    from termo
                         inner join arrecad     on v07_numpre = k00_numpre
                         inner join cadtipoparc on k40_codigo = v07_desconto
                   where k00_numpre = $k00_numpre
                     and k00_numpar = $k00_numpar ";

  $resultVerParcel = db_query($sqlVerParcel);
  $linhasVerParcel = pg_num_rows($resultVerParcel);

  if($linhasVerParcel>0 ) {

    db_fieldsmemory($resultVerParcel,0);

    //Calcula o desconto.
    $datahoje = date("Y-m-d", $DB_DATACALC);

    try {

      $sSqlVencimento = "select fc_proximo_dia_util('{$k00_dtvenc}'::date) as vencimento;";
      $rsVencimento   = db_query($sSqlVencimento);

      if ( !$rsVencimento ) {
        throw new DBException("Erro ao tentar buscar o próximo dia útil do vencimento.");
      }

      $k00_dtvenc = db_utils::fieldsMemory($rsVencimento, 0)->vencimento;

    } catch (Exception $eExeption) {
      db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
    }

    if(db_strtotime($k00_dtvenc) >= db_strtotime($db_datausu)){

      $sqlDesconto    = "select fc_recibodesconto($k00_numpre,$k00_numpar,$v07_totpar,$k00_receit,$k00_tipo,'$k00_dtvenc','$k00_dtvenc') as percento";
      $resultDesconto = db_query($sqlDesconto);
      $linhasDesconto = pg_num_rows($resultDesconto);

      $pdf1->descr4_2 = "";

      if($linhasDesconto > 0){

        db_fieldsmemory($resultDesconto,0);

        if($percento != 0 ){

          $desc          = 100 - $percento;
          $valorDesconto = round ( ( $nTotalDebito - ( $nTotalDebito * ($desc/100) ) ) ,2);

          $pdf1->descr4_2  = "Valor da parcela                        R$".db_formatar($nTotalDebito,"f")."\n";
          $pdf1->descr4_2 .= "Juro (mora + fincanciamento) R$".db_formatar($vlrjuros,"f")."\n";
          $pdf1->descr4_2 .= "Desconto até o vencimento     R$".db_formatar($valorDesconto,"f");

          $pdf1->descontototal = $valorDesconto;

        }else{
          $pdf1->descr4_2="";
        }
      }

    } else {

      $pdf1->descr4_2  = "Valor da parcela                        R$".db_formatar($nTotalDebito,"f")."\n";
      $pdf1->descr4_2 .= "Juro (mora + fincanciamento) R$".db_formatar($vlrjuros,"f")."\n";
      $pdf1->descr4_2 .= "Multa                                          R$".db_formatar($vlrmulta,"f")."\n";
    }
  }

  // ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
  if($oRegraEmissao->isCobranca()){

    $rsConsultaBanco = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
    $oBanco          = db_utils::fieldsMemory($rsConsultaBanco,0);
    $pdf1->numbanco  = $oBanco->db90_codban."-".$oBanco->db90_digban;
    $pdf1->banco     = $oBanco->db90_abrev;

    try{
      $pdf1->imagemlogo = $oConvenio->getImagemBanco();
    } catch (Exception $eExeption){
      db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
    }
  }

  if(isset($vlrdesconto) && $vlrdesconto != ""){

    $pdf1->iptuvlrdesconto = trim(substr(trim($vlrdesconto),2));
    if($pdf1->iptuvlrdesconto == "" || $pdf1->iptuvlrdesconto == 0){
      $pdf1->iptuvlrdesconto = "0,00";
    }
  }else{
    $pdf1->iptuvlrdesconto = "R$ 0,00";
  }

  $pdf1->iptsubtitulo  = "";
  $pdf1->ipttotal      = trim(substr(trim($pdf1->descr7),2));
  $pdf1->iptuvlrcor    = trim(substr(trim($pdf1->descr7),2));
  $pdf1->iptj43_cep    = $pdf1->cep;
  $pdf1->iptz01_cidade = $pdf1->munic;
  $pdf1->iptz01_bairro = $z01_bairro;

  if($z01_compl != "" && $descr != "Matrícula") {
    $pdf1->descr3_2 = $pdf1->descr3_2." / ".$z01_compl;
  }
  $pdf1->iptz01_ender       = $pdf1->descr3_2;
  if(isset($j01_matric) && trim($j01_matric)!= "") {
    $pdf1->iptj01_matric      = $j01_matric;//$pdf1->descr1;
  }else{
    $pdf1->iptj01_matric      = $pdf1->descr1;
  }

  /**
   * Variável $sEnderecoMatricula criada na linha 39
   * Se for uma busca por matricula ela tera o valor de  $j43_ender .  $j39_numero
   */
  if (!empty($sEnderecoMatricula)) {
    $pdf1->descr3_2         = $sEnderecoMatricula;
  }

  $pdf1->iptcodigo_barras   = $pdf1->codigo_barras;
  $pdf1->iptlinha_digitavel = $pdf1->linha_digitavel;
  $pdf1->iptprefeitura      = $pdf1->prefeitura;
  $pdf1->iptj23_anousu      = trim(substr($k00_descr,4));
  $pdf1->iptdataemis        = $pdf1->data_processamento;
  $pdf1->iptdtvencunic      = $pdf1->descr6;
  $pdf1->iptz01_nome        = $pdf1->descr3_1;
  $pdf1->iptz01_cgccpf      = $pdf1->cgccpf;
  $pdf1->iptdtvencunic      = $pdf1->dtparapag;
  $pdf1->iptprefeitura      = $pdf1->prefeitura;
  $pdf1->iptj01_matric      = $pdf1->descr1;
  $pdf1->iptnomepri         = strtoupper($z01_ender);
  $pdf1->iptcodpri          = $z01_numero == "" ? "" : ', '.$z01_numero.' / '.$z01_compl;
  $pdf1->iptproprietario    = $pdf1->descr11_1;

  $pdf1->imprime();
  $pdf1->descr12_1          = '';
  $pdf1->predescr12_1       = '';
}

db_query("COMMIT");
// @todo - retirar o comentario do output
$pdf1->objpdf->Output();

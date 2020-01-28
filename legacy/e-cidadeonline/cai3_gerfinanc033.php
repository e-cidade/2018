<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
include_once("libs/db_sql.php");
include_once("libs/db_utils.php");
require_once("std/DBDate.php");
require_once("libs/db_libtributario.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_db_bancos_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_modcarne_classe.php");
require_once("model/regraEmissao.model.php");
require_once("model/convenio.model.php");
require_once("classes/db_recibopagaboleto_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cldb_config = new cl_db_config;
$cldb_bancos = new cl_db_bancos;
$clmodcarne  = new cl_modcarne;

$histinf   = "";

$txtNumpreUnicaSelecionados = "";
$forcarvencimento = "";
$DB_DATACALC="";

$H_DATAUSU=db_getsession("DB_datausu");
$db_datausu=date("Y-m-d",$H_DATAUSU);

$result = db_query("select codmodelo,k03_tipo from arretipo where k00_tipo = $tipo_debito");
db_fieldsmemory($result, 0);
pg_free_result($result);

 $aParcelasSemInflatores = array();

$whereDatasUnicas = "";
if ($txtNumpreUnicaSelecionados != ""){
  $datasUnicas = "'".implode("','", explode(',',$txtNumpreUnicaSelecionados) )."'";
}else{
  $datasUnicas = "";
}

if (isset($numpre_unica) && $numpre_unica != "") {
  $sSqlUnicas = "select distinct k00_dtvenc from recibounica where k00_numpre = {$numpre_unica} order by k00_dtvenc ";
  $rsUnicas = db_query($sSqlUnicas);
  if(pg_num_rows($rsUnicas) > 0){
    $datasUnicas = "";
    $sVir = "";
    for ($iI = 0 ; $iI < pg_num_rows($rsUnicas); $iI++){
      db_fieldsmemory($rsUnicas,$iI);
      $datasUnicas .= "$sVir'$k00_dtvenc'";
      $sVir = ',';
    }
  }
}


$sqlpref = "select db12_extenso,db12_uf,nomeinst as prefeitura, munic, to_char(tx_banc,'99.99') as tx_banc from db_config inner join cgm on cgm.z01_numcgm = db_config.numcgm inner join db_uf on db12_uf=uf where db_config.codigo = ".db_getsession("DB_instit");
$resul = $cldb_config->sql_record($sqlpref);

if($cldb_config->numrows>0){
  db_fieldsmemory($resul, 0); // pega o dados da prefa
}else{
  db_redireciona('db_erros.php?fechar=true&db_erro=Contate Suporte. A configuração do sistema não esta completa.');
  exit;
}
$pdf2 = new stdclass();
$pdf2->uf_config     = $db12_uf;
$munic2     = $munic;
$nomeinst2  = $prefeitura;
$taxabancaria = $tx_banc;
$msgvencida = "";
$bql        = "";
$obsdiver   = "";

if ((int) $codmodelo > 0) {
  $impmodelo = (int) $codmodelo;
} else {
  $impmodelo = 1;
}
//=============================================================================================================================

if ($k03_tipo == 1 && $impmodelo <> 1 && $impmodelo <> 30) {

  if (!isset ($matric)) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Para Emissão de Carnê do IPTU Consulte Dados Pela Matrícula.');
    exit;
  }
  $cliptubase = new cl_iptubase;
  $rstipo = db_query("select * from arretipo where k00_tipo = $tipo_debito");
  db_fieldsmemory($rstipo, 0);
  try{
    $oRegraEmissao = new regraEmissao($tipo_debito,22,db_getsession('DB_instit'),date("Y-m-d", db_getsession("DB_datausu")),db_getsession('DB_ip'));
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
    exit;
  }

  $pdf2 = $oRegraEmissao->getObj();

  $resultpro = $cliptubase->proprietario_record($cliptubase->proprietario_query($matric));
  db_fieldsmemory($resultpro, 0);

  $pdf2->uf_config     = $db12_uf;
  $pdf2->iptj01_matric = $j01_matric;
  $pdf2->iptz01_cidade = $munic2;
  $pdf2->iptprefeitura = $nomeinst2;
  $pdf2->iptz01_ender  = $z01_ender;
  $pdf2->iptbql        = $j34_setor."/".$j34_quadra."/".$j34_lote;
  $pdf2->iptnomepri    = $nomepri;
  $pdf2->iptcodpri     = $j39_numero;
  $pdf2->iptproprietario = $proprietario;
  $pdf2->iptz01_nome   = $z01_nome;
  $pdf2->iptz01_numcgm = $z01_numcgm;
  $pdf2->iptz01_cgccpf = $z01_cgccpf;
  $pdf2->iptz01_bairro = $z01_bairro;
  $pdf2->iptbairroimo  = $j13_descr;
  $pdf2->iptz01_cidade = $z01_cidade;
  $pdf2->iptz01_munic  = $z01_munic;
  $pdf2->iptz01_cep    = $z01_cep;
  $pdf2->iptj43_cep    = $j43_cep;
  $pdf2->iptdataemis   = date("d/m/Y", db_getsession("DB_datausu"));
  $pdf2->msgunica      = $k00_msguni;

  $sql = "select * from arrematric
  inner join arrecad on arrecad.k00_numpre = arrematric.k00_numpre
  where k00_matric = $j01_matric
  and k00_dtvenc < '".date("Y-m-d", db_getsession("DB_datausu"))."' limit 1";
  $rsResulant = db_query($sql);
  $numlin = pg_numrows($rsResulant);
  if ($numlin > 0) {
    $pdf2->iptdebant = "Há Débitos Anteriores, favor procurar Setor de Dívida Ativa";
  }

  unset ($resultpro);

  $vt = $HTTP_POST_VARS;
  $tam = sizeof($vt);
  reset($vt);
  $numpres = "";
  for ($i = 0; $i < $tam; $i ++) {
    if (db_indexOf(key($vt), "CHECK") > 0)
    $numpres .= "N".$vt[key($vt)];
    next($vt);
  }

  $numpres = split("N", $numpres);

  $unica = "t";
  if (sizeof($numpres) < 2) {
    $numpres = array ("0" => "0", "1" => $numpre_unica.'P000');
    $unica = true;
  } else {
    if (isset ($HTTP_POST_VARS["numpre_unica"])) {
      $unica = true;
    }
  }

  //  db_query("BEGIN");
  for ($volta = 1; $volta < sizeof($numpres); $volta ++) {
    $codigos = split("P", $numpres[$volta]);
  }

  $resultunica = db_query("select j23_anousu from iptucalc inner join iptunump on j20_anousu = j23_anousu and j20_matric = j23_matric where j20_numpre = $codigos[0]");

  db_fieldsmemory($resultunica, 0);
  $pdf2->iptj23_anousu = $j23_anousu;

  if ($datasUnicas != "") {
    $whereDatasUnicas = " and k00_dtvenc in ( $datasUnicas ) ";
  }

  $resultunica = db_query("select * from recibounica where k00_numpre = $codigos[0] $whereDatasUnicas ");
  if (pg_numrows($resultunica) > 0) {
    db_fieldsmemory($resultunica, 0);
    $vencunica = db_formatar($k00_dtvenc, "d");
  }

  if ($unica == 't') {

    $sql = "select *,
                   substr(fc_calcula,2,13)::float8 as uvlrhis,
                   substr(fc_calcula,15,13)::float8 as uvlrcor,
                   substr(fc_calcula,28,13)::float8 as uvlrjuros,
                   substr(fc_calcula,41,13)::float8 as uvlrmulta,
                   substr(fc_calcula,54,13)::float8 as uvlrdesconto,
                   (substr(fc_calcula,15,13)::float8+
                   substr(fc_calcula,28,13)::float8+
                   substr(fc_calcula,41,13)::float8-
                   substr(fc_calcula,54,13)::float8) as utotal
              from ( select r.k00_numpre,
                            r.k00_dtvenc as dtvencunic,
                            r.k00_dtoper as dtoperunic,
                            r.k00_percdes,
                            fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").")
                       from recibounica r
                     where r.k00_numpre = ".$codigos[0];

    if ($datasUnicas != "") {
      $sql .= "      and r.k00_dtvenc in ( $datasUnicas ) ";
    }

    $sql .= "         and r.k00_dtvenc >= '".date('Y-m-d', db_getsession("DB_datausu"))."'::date ) as unica order by dtvencunic ";
    $linha = 220;
    $resultfin = db_query($sql) or die($sql);

    if ($resultfin != false) {
      db_fieldsmemory($resultfin, 0);
      $k00_valor = $utotal;
      $pdf2->iptk00_percdes  = $k00_percdes;
      $uvlrcor               = db_formatar($uvlrcor, 'f');
      $pdf2->iptuvlrcor      = $uvlrcor;
      $vlrhis                = db_formatar($uvlrhis, 'f');
      $vlrdesconto           = db_formatar($uvlrdesconto, 'f');
      $pdf2->iptuvlrdesconto = $vlrdesconto;
      $utotal                += $taxabancaria;
      $vlrtotal              = db_formatar($utotal, 'f');
      $vlrbar                = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
      $pdf2->ipttotal        = $vlrtotal;


      $numpre = db_numpre($k00_numpre).'000'; //db_formatar(0,'s',3,'e');

      $dtvenc = str_replace("-", "", $dtvencunic);

      $datavencimento = $dtvencunic;
      $tmpdt      = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);
      if ($tmpdt > $datavencimento) {
        $datavencimento = $tmpdt;
      }

      if ( isset($emiscarneiframe) && $emiscarneiframe == 'n' ) {
        if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu')) {
          continue;
        }
      }

      if($oRegraEmissao->isCobranca()){

        if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0 && ( $ninfla_ant != "" && $ninfla_ant != "REAL") ) {
          $k00_valor = 0;
          $especie   = $ninfla;
          $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
        }else{
          $especie   = 'R$';
          $histinf   = "";
        }


        if($datavencimento < date('Ymd',db_getsession('DB_datausu'))){
          $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;
          $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
        }else{
          $msgvencida = "";
        }

      }else{
        $dtvencunic = db_formatar($dtvencunic, 'd');
      }

      try {
        $oConvenio = new convenio($oRegraEmissao->getConvenio(),$k00_numpre,0,$k00_valor,$vlrbar,$datavencimento,6);
      } catch (Exception $eExeption){
      db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
      exit;
    }

      $pdf2->iptcodigo_barras   = $oConvenio->getCodigoBarra();
      $pdf2->iptlinha_digitavel = $oConvenio->getLinhaDigitavel();
      $pdf2->iptdtvencunic      = db_formatar($datavencimento, 'd');

      pg_free_result($resultfin);

    }

    $sql = "select sum(j22_valor) as vlredi
    from iptucale
    where j22_anousu = $j23_anousu
    and j22_matric = $j01_matric";
    $sqlres = db_query($sql);
    if (pg_numrows($sqlres) > 0) {
      db_fieldsmemory($sqlres, 0);
    } else {
      $vlredi = 0;
    }
    $sql = "select j23_vlrter, j23_aliq
    from iptucalc
    where j23_anousu = $j23_anousu
    and j23_matric = $j01_matric";
    $sqlres = db_query($sql);
    if (pg_numrows($sqlres) > 0) {
      db_fieldsmemory($sqlres, 0);
      $pdf2->iptj23_aliq = $j23_aliq;
    } else {
      $j23_vlrter = 0;
      $j23_aliq = 0;
    }
    $j23_vlrter += $vlredi;
    $pdf2->iptj23_vlrter = db_formatar($j23_vlrter, 'f');
  }

// ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
//verifica se é ficha e busca o codigo do banco
  if($oRegraEmissao->isCobranca()){

  $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
  $oBanco     = db_utils::fieldsMemory($rsConsultaBanco,0);
  $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
  $pdf1->banco      = $oBanco->db90_abrev;

  try {
    $pdf1->imagemlogo = $oConvenio->getImagemBanco();
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
  }

  }
//#############################################################
  $pdf2->imprime();
  $pdf2->objpdf->Output();
  exit;

} else {

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


  try{
    $oRegraEmissao = new regraEmissao($tipo_debito,22,db_getsession('DB_instit'),date("Y-m-d", db_getsession("DB_datausu")),db_getsession('DB_ip'));
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
    exit;
  }

  $pdf1 = $oRegraEmissao->getObj();

  $pdf1->uf_config  = $db12_uf;
  $pdf1->prefeitura = $nomeinst;

  $sqlparag  = " select db02_texto ";
  $sqlparag .= "   from db_documento ";
  $sqlparag .= "        inner join db_docparag  on db03_docum   = db04_docum ";
  $sqlparag .= "        inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
  $sqlparag .= "        inner join db_paragrafo on db04_idparag = db02_idparag ";
  $sqlparag .= " where db03_tipodoc = 1017 ";
  $sqlparag .= "   and db03_instit = ".db_getsession("DB_instit")." ";
  $sqlparag .= " order by db04_ordem ";
  $resparag = db_query($sqlparag);

  if (pg_numrows($resparag) == 0) {
    $pdf1->secretaria = 'SECRETARIA DE FINANÇAS';
  } else {
    db_fieldsmemory($resparag, 0);
    $pdf1->secretaria = $db02_texto;
  }

  $pdf1->tipodebito = $k00_descr;
  $pdf1->pretipodebito = $k00_descr;
  $pdf1->logo = $logo;

  db_query("BEGIN");
  db_postmemory($HTTP_POST_VARS);
  $vt = $HTTP_POST_VARS;
  $tam = sizeof($vt);
  reset($vt);
  $numpres = "";

  //print_r($vt);exit;
  for ($i = 0; $i < $tam; $i ++) {
    if (db_indexOf(key($vt), "CHECK") > 0) {
      $registros = split("N",$vt[key($vt)]);
      for ($reg=0; $reg < sizeof($registros); $reg++) {
        if ($registros[$reg] == "") {
          continue;
        }
        $registro = split("R", $registros[$reg]);
        if (gettype(strpos($numpres, "N".$registro[0])) == "boolean") {
          $numpres .= "N".$registro[0];
        }
      }
    }
    next($vt);
  }
  $sounica = $numpres;
  $numpres = split("N", $numpres);

  $unica = 2;
  if (sizeof($numpres) < 2) {
    $numpres = array ("0" => "0", "1" => $numpre_unica.'P000');
    $unica = 1;
  } else {
    if (isset ($HTTP_POST_VARS["numpre_unica"])) {
      if ($numpre_unica != '') {
        $unica = 1;
      }
    }
  }
  //sizeof($numpres);
  if (isset ($geracarne) && $geracarne == 'banco') {
    $pagabanco = 't';
  } else {
    $pagabanco = 't';
  }

  /******************************************************   F O R   Q   M O N T A   O S   C A R N E S  ******************************************************/
  $aParcelasSemInflatores = array();

  //print_r($numpres);

  if ($unica != "1"){

  for ($volta = 0; $volta < sizeof($numpres); $volta ++) {

    if ( $numpres[$volta] == "" || $numpres[$volta] == "0" ) {
      continue;
    }

    $aNumpre = split('P',$numpres[$volta]);

    $k00_numpre = $aNumpre[0];
    $k00_numpar = $aNumpre[1];


    $rsDadosPagamento = debitos_numpre_carne($k00_numpre, $k00_numpar, $H_DATAUSU, $H_ANOUSU,db_getsession('DB_instit'),$DB_DATACALC,$forcarvencimento);

    //db_criatabela($rsDadosPagamento);

    db_fieldsmemory($rsDadosPagamento, 0);

    if ( isset($total) && $total < 0 ) {
      array_push($aParcelasSemInflatores,$k00_numpar);
    }




  }
//  exit;

  }

  if (count($aParcelasSemInflatores) > 0 ) {
    $sS = ( count($aParcelasSemInflatores)>1?'s':'' );
    db_redireciona("db_erros.php?fechar=true&db_erro=Valor negativo na{$sS} parcela{$sS} ".implode(",",$aParcelasSemInflatores)." verifique.");
    exit;
  }


   for ($volta = 1; $volta < sizeof($numpres); $volta ++) {
    if ($numpres[$volta] == "") {
      continue;
    }
    $k00_numpre = substr($numpres[$volta], 0, strpos($numpres[$volta], 'P'));

    $resulttipo = db_query("select k00_descr,k00_codbco,k00_codage,k00_txban,k00_rectx,
    k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,
    k00_hist6,k00_hist7,k00_hist8
    from arretipo
    where k00_tipo = $tipo_debito ");
    db_fieldsmemory($resulttipo, 0);


    //////////////////////  PARCELAMENTO DE DIVERSO ///////////////////////////////////
    if ($k03_tipo == 16) {
      $sql28  = " select b.* ";
      $sql28 .= "   from diversos a ";
      $sql28 .= "        left join  termodiver on  dv10_parcel = dv05_coddiver ";
      $sql28 .= "        left outer join procdiver b on a.dv05_procdiver=b.dv09_procdiver ";
      $sql28 .= " where dv05_numpre = $k00_numpre limit 1";
      $result28 = db_query($sql28);
      if (pg_numrows($result28) > 0) {
        db_fieldsmemory($result28, 0);
        $pdf1->tipodebito = 'PARCELAMENTO DE '.$dv09_descr;
        $pdf1->pretipodebito = "PARCELAMENTO DE  $dv09_descr  N- $v10_parcel";
      }
    }

    //////////////  DIVERSO /////////////////////
    if (($tipo_debito == 28) || ($tipo_debito == 25)) {
      ///////// PARCELAMENTO///////////////
      if ($tipo_debito == 28) {
        $sql25  = " select * ";
        $sql25 .= "   from termo ";
        $sql25 .= "        inner join termodiver on v07_parcel = dv10_parcel ";
        $sql25 .= "        inner join diversos on dv10_coddiver = dv05_coddiver ";
        $sql25 .= "        inner join procdiver on dv05_procdiver=dv09_procdiver ";
        $sql25 .= " where v07_numpre = $k00_numpre";

        ///////////// DIVERSO ///////////////
      } else {
        $sql25 = "select * ";
        $sql25 .= "        from diversos a ";
        $sql25 .= "          left outer join procdiver b on a.dv05_procdiver=b.dv09_procdiver ";
        $sql25 .= " where dv05_numpre = $k00_numpre ";
        $sql25 .= "        order by a.dv05_coddiver desc limit 1";

      }
      //echo $sql25;exit;
      $result25 = db_query($sql25) or die($sql25);

      if (pg_numrows($result25) > 0) {
        db_fieldsmemory($result25, 0);
        @$obs = substr($obs, 0, 20);
        $pdf1->tipodebito = $dv09_descr;
        $pdf1->pretipodebito = $dv09_descr;
        $pdf1->pretipodebito = "PARCELAMENTO DE DIVERSO N- " . @$dv10_parcel;
      } else {
        @$obs = "";
        $rstermo = db_query(" select v07_parcel from termo where v07_numpre = $k00_numpre ");
        if( pg_numrows($rstermo) > 0 ){
          $codparcel = " N- $v07_parcel ";
        }else{
          $codparcel = "";
        }
        $pdf1->tipodebito = "";
        $pdf1->pretipodebito =$k00_descr.$codparcel;
        $k00_hist1 = "";
        $pdf1->secretaria = "";
        $dv05_procdiver = 0;
      }

      if ($dv05_procdiver == 1284) {
        $pdf1->secretaria = 'FUNDO MUNICIPAL DE HABITAÇÃO';
        $k00_hist1 = 'Convênio SEHAB nº 72/99 - Programa Especial do Funco de Desenvolvimento Social. Aprovação do Conselho Estadual de Habitação em 08/09/1999';
      } else if ($dv05_procdiver == 221) {
        $pdf1->secretaria = 'FUNDO MUNICIPAL DE HABITAÇÃO';
        $k00_hist1 = 'Lei Municipal nº 3049/2002, de 04/12/2002. Aprovação do Conselho Estadual de Habitação em dez/2002';
      }
    }

    $proprietario = '';
    $xender = '';
    $xbairro = '';

    $rstermo = db_query(" select v07_parcel from termo where v07_numpre = $k00_numpre ");
    if( pg_numrows($rstermo) > 0 ){
      db_fieldsmemory($rstermo,0);
      $codparcel = " N- $v07_parcel ";
      $pdf1->pretipodebito =$k00_descr.$codparcel;
    }else{
      $codparcel = "";
    }

    /***********************************************************************************************************************/

    $sqlorigem  = " select arrecad.k00_numpre, ";
    $sqlorigem .= "        arrenumcgm.k00_numcgm as z01_numcgm, ";
    $sqlorigem .= "        case ";
    $sqlorigem .= "           when arrematric.k00_matric is not null ";
    $sqlorigem .= "             then arrematric.k00_matric ";
    $sqlorigem .= "           when arreinscr.k00_inscr is not null ";
    $sqlorigem .= "             then arreinscr.k00_inscr ";
    $sqlorigem .= "           else ";
    $sqlorigem .= "             arrenumcgm.k00_numcgm ";
    $sqlorigem .= "        end as origem, ";
    $sqlorigem .= "        case ";
    $sqlorigem .= "          when arrematric.k00_matric is not null ";
    $sqlorigem .= "            then 'Matrícula' ";
    $sqlorigem .= "          when arreinscr.k00_inscr is not null ";
    $sqlorigem .= "            then 'Inscrição' ";
    $sqlorigem .= "        else ";
    $sqlorigem .= "         'CGM' ";
    $sqlorigem .= "        end as descr ";
    $sqlorigem .= "   from arrecad ";
    $sqlorigem .= "        inner join arrenumcgm on arrenumcgm.k00_numpre = arrecad.k00_numpre ";
    $sqlorigem .= "        left join arrematric on arrematric.k00_numpre = arrecad.k00_numpre ";
    $sqlorigem .= "        left join arreinscr  on arreinscr.k00_numpre  = arrecad.k00_numpre ";
    $sqlorigem .= " where arrecad.k00_numpre = $k00_numpre";
    $rsOrigem   = db_query($sqlorigem) or die($sqlorigem);
    if (pg_numrows($rsOrigem) > 0) {
      db_fieldsmemory($rsOrigem, 0);
    } else {
      db_msgbox("Nao encontrou registros do numpre: $k00_numpre!");
    }

    if (!empty ($descr) && $descr == 'Matrícula') {
      $Identificacao = db_query("select * from proprietario where j01_matric = $origem limit 1");

      if(pg_numrows($Identificacao)==0) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Problemas no Cadastro da Matricula ' . $origem);
      }

      db_fieldsmemory($Identificacao, 0);
      //db_criatabela($Identificacao);
      $proprietario = $z01_nome;
      //die($nomepri);
      $pdf1->bairropri  = $j13_descr;
      $pdf1->prebairropri  = $z01_bairro;
      $pdf1->nomepriimo = $nomepri;


      $pdf1->pqllocal  = "PQL: {$j05_codigoproprio} - {$j05_descr} / {$j06_quadraloc} / {$j06_lote}";
      // trocado porque bage pediu

      if($oRegraEmissao->isCobranca()){
        $xender = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
      }else{
        $xender = $nomepri.', '.$j39_numero.'  '.$j39_compl;
      }
      $bql = '  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
      if (isset ($impmodelo) && $impmodelo == 30) {
        if ($k00_tipo != 6) {
          $iNumeroOrigem = $j01_matric;
        } else {
          //$descr = "";
          $iNumeroOrigem = "";
          $iNumeroOrigem = $j01_matric;
        }
      } else {
        if ($k00_tipo != 6) {
          $iNumeroOrigem = $j01_matric.'  SQL:'.$j34_setor.'-'.$j34_quadra.'-'.$j34_lote;
        } else {
          //         $descr = "";
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
        $z01_numcgm = $q02_numcgm;
      } else {
        //    $descr = "";
        $iNumeroOrigem = "";
        $iNumeroOrigem = $q02_inscr;
        $z01_numcgm = $q02_numcgm;
      }

    } else {
      //  db_msgbox("entrou");
      $Identificacao = db_query("select cgm.*,
      ''::bpchar as nomepri,
      ''::bpchar as j39_co
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
      $sqltipodeb = " select  termo.*,z01_nome,z01_ender,z01_numero,z01_compl,z01_bairro,
      coalesce(k00_matric,0) as matric,
      coalesce(k00_inscr,0) as inscr
      from termo
      left outer join arrematric  on v07_numpre = arrematric.k00_numpre
      left outer join arreinscr  on v07_numpre = arreinscr.k00_numpre
      inner join cgm       on v07_numcgm = z01_numcgm
      where v07_numpre = $k00_numpre ";
      $sqltipodeb = " select z.*, z01_nome,z01_ender,z01_numero,z01_compl,z01_bairro from (
      select   x.*,
      case when x.matric <> 0 then case when j41_numcgm is not null then promitente.j41_numcgm else iptubase.j01_numcgm end else case when x.inscr <> 0 then issbase.q02_numcgm else arrecad.k00_numcgm end end as z01_numcgm
      from (
      select  termo.*,
      coalesce(k00_matric,0) as matric,
      coalesce(k00_inscr,0) as inscr
      from termo
      left outer join arrematric  on v07_numpre = arrematric.k00_numpre
      left outer join arreinscr  on v07_numpre = arreinscr.k00_numpre
      where v07_numpre = $k00_numpre) as x
      left join iptubase    on j01_matric = x.matric
      left outer join promitente      on j01_matric = j41_matric and promitente.j41_tipopro is true
      left join issbase    on q02_inscr  = x.inscr
      inner join arrecad on v07_numpre = k00_numpre) as z
      inner join cgm on z.z01_numcgm = cgm.z01_numcgm ";
      $resulttipodeb = db_query($sqltipodeb);
      if (pg_numrows($resulttipodeb) == 0) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Parcelamento sem termo cadastrado.');
        exit;
      } else {
        db_fieldsmemory($resulttipodeb, 0);
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
      $traco = '';
      $exercicio = ' - Exerc : ';
      for ($k = 0; $k < pg_numrows($resultdivida); $k ++) {
        $exercicio .= $traco.substr(pg_result($resultdivida, $k, "v01_exerc"), 2, 2);
        $traco = '-';
      }
    }

/// S E   F O R   U N I C A...
/******************************************************************************************************************************/
    if ($unica == 1 && $datasUnicas != "" ) {

      $sql  = " select *, ";
      $sql .= "        substr(fc_calcula,2,13)::float8 as uvlrhis, ";
      $sql .= "        substr(fc_calcula,15,13)::float8 as uvlrcor, ";
      $sql .= "        substr(fc_calcula,28,13)::float8 as uvlrjuros, ";
      $sql .= "        substr(fc_calcula,41,13)::float8 as uvlrmulta, ";
      $sql .= "        substr(fc_calcula,54,13)::float8 as uvlrdesconto, ";
      $sql .= "        (substr(fc_calcula,15,13)::float8 + substr(fc_calcula,28,13)::float8 + substr(fc_calcula,41,13)::float8 - substr(fc_calcula,54,13)::float8) as utotal, ";
      $sql .= "         substr(fc_calcula,77,17)::float8 as qinfla, ";
      $sql .= "         substr(fc_calcula,94,4)::varchar(5) as ninfla ";
      $sql .= "   from ( select r.k00_numpre, ";
      $sql .= "                 r.k00_dtvenc as dtvencunic, ";
      $sql .= "                 r.k00_dtvenc as dtvencunicuni, ";
      $sql .= "                 r.k00_dtoper as dtoperunic, ";
      $sql .= "                 r.k00_percdes, ";
      $sql .= "                 fc_calcula(r.k00_numpre,0,0,r.k00_dtvenc,r.k00_dtvenc,".db_getsession("DB_anousu").") ";
      $sql .= "            from recibounica r ";
      $sql .= "           where r.k00_numpre = ".$k00_numpre;

      if ($datasUnicas != "") {
        $sql .= "           and r.k00_dtvenc in ( $datasUnicas ) ";
      }

      $sql .= "             and r.k00_dtvenc >= '".date('Y-m-d', db_getsession("DB_datausu"))."'::date ) as unica ";
      $sql .= "          order by dtvencunic ";


      $linha   = 220;
      $resultfin = db_query($sql) or die($sql);

      if ($resultfin != false) {
        for ($unicont = 0; $unicont < pg_numrows($resultfin); $unicont ++) {

		// André TI - Prefeitura de Maricá
		  $oMensagem                   = DBTributario::getMensagensParcela($k00_numpre, null, null );
          $pdf1->sMensagemContribuinte = $oMensagem->sMensagemContribuinte;
          $pdf1->sMensagemCaixa        = $oMensagem->sMensagemCaixa;
		// André TI - Prefeitura de Maricá

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
          $utotal    += $taxabancaria;
          $vlrtotal     = db_formatar($utotal, 'f');
          $vlrbar       = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');

          $sqlvalor = "select k00_impval, k00_tercdigcarneunica from arretipo where k00_tipo = $tipo_debito";
          db_fieldsmemory(db_query($sqlvalor), 0);

          // alterei para buscar o terceiro digito pelo tipo de debito da tabela arretipo
          if (!isset ($k00_tercdigcarneunica) || $k00_tercdigcarneunica == "") {
            db_redireciona('db_erros.php?fechar=true&db_erro=Configure o terceiro digito do codigo de barras no cadastro do tipo de debito para este tipo de debito.');
          }

          $iTercDig = $k00_tercdigcarneunica;

          if ($k00_impval == 't') {
            $k00_valor = $utotal;
            $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
            $ninfla = '';
            if ($utotal == 0) {
              $iTercDig = 7;
              $vlrbar   = "00000000000";
            }
          } else {
            $k00_valor = $qinfla;
            $iTercDig  = 7;
            $vlrbar    = "00000000000";
          }


          $numpre = db_numpre($k00_numpre).'000'; //db_formatar(0,'s',3,'e');

          $datavencimento = $dtvencunic;

          if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {
            if (substr($dtvencunic, 0, 4) > db_getsession('DB_anousu')) {
              continue;
            }
          }

          if($oRegraEmissao->isCobranca()){

            if (substr($dtvencunic, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0 && ( @$ninfla_ant != "" && @$ninfla_ant != "REAL")) {
              $k00_valor = 0;
              $especie   = $ninfla;
              $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
            }else{
              $especie   = 'R$';
              $histinf   = "";
            }

            if($dtvencunic < date('Ymd',db_getsession('DB_datausu'))){
              $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;
              $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
            }else{
              $msgvencida = "";
            }

          } else {
            $dtvencunic = str_replace("/","",$dtvencunic);
          }

          try {

            $result = db_query("select nextval('numpref_k03_numpre_seq') as numpre_recibo") or die("Erro ao gerar recibo.");
            $iNumpreRecibo = db_utils::fieldsMemory($result, 0)->numpre_recibo;

            $sSqlTipoDebito = " select k00_codbco,
                                       k00_codage
                                  from arretipo
                                 where k00_tipo = {$tipo_debito} ";
            $rsTipoDebito = db_query($sSqlTipoDebito);

            if (pg_num_rows($rsTipoDebito) == 0){
              echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo.";
              exit;
            }

            $oTipoDebito = db_utils::fieldsMemory($rsTipoDebito, 0);

            $sSqlReciboWeb = "insert into db_reciboweb values({$k00_numpre},0,{$iNumpreRecibo},{$oTipoDebito->k00_codbco},'{$oTipoDebito->k00_codage}','{$oRegraEmissao->getCodConvenioCobranca()}')";
            db_query($sSqlReciboWeb) or die("Erro ao gerar Recibo.");

            $sSqlRecibo = "select fc_recibo({$iNumpreRecibo}, '{$dtvencunic}'::date, '{$dtvencunic}'::date, " . db_getsession("DB_anousu") . ")";
            $Recibo = db_query($sSqlRecibo) or die("Erro ao gerar Recibo.");

            $oDaoReciboPagaBoleto = new cl_recibopagaboleto();

            $oDaoReciboPagaBoleto->k138_numnov  = $iNumpreRecibo;
            $oDaoReciboPagaBoleto->k138_data    = date("Y-m-d",db_getsession("DB_datausu"));
            $oDaoReciboPagaBoleto->k138_hora    = date("H:i:s",db_getsession("DB_datausu"));
            $oDaoReciboPagaBoleto->k138_usuario = db_getsession("DB_id_usuario");
            $oDaoReciboPagaBoleto->incluir("");

            if ( (int)$oDaoReciboPagaBoleto->erro_status == 0 ) {
            	throw new Exception("Gravar dados recibopagaboleto: \n" . $oDaoReciboPagaBoleto->erro_msg );
            }

            $oConvenio = new convenio($oRegraEmissao->getConvenio(),$iNumpreRecibo,0,$k00_valor,$vlrbar,$dtvencunic,$iTercDig);

            if (CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->getConvenio())) {

              if (CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->getConvenio())) {
                CobrancaRegistrada::registrarReciboWebservice($iNumpreRecibo, $oRegraEmissao->getConvenio(), $k00_valor, true);
              } else {
                CobrancaRegistrada::adicionarRecibo($iNumpreRecibo, $oRegraEmissao->getConvenio());
              }
            }
          } catch (Exception $eExeption){
            db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
            exit;
          }

          if( $oRegraEmissao->isCobranca() ) {

            $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
            $pdf1->carteira        = $oConvenio->getCarteira();

            if(strlen($oConvenio->getConvenioCobranca()) == 7) {
              $pdf1->nosso_numero = trim($oConvenio->getConvenioCobranca()) . str_pad($iNumpreRecibo,8,"0",STR_PAD_LEFT) . "00";
            } else {
              $pdf1->nosso_numero = $oConvenio->getNossoNumero();
            }

          }

          global $pdf;

          $pdf1->data_processamento = db_formatar($dtoperunic, 'd');
          $pdf1->titulo1 = $descr;
          $pdf1->descr1 = $iNumeroOrigem;
          $pdf1->descr2 = db_numpre($iNumpreRecibo, 0).'000'; //.db_formatar($k00_numpar,'s',"0",3,"e");
          if (isset ($obs)) {
            $pdf1->titulo13 = 'Observação';
            $pdf1->descr13 = $obs;
          }
          /////////////// ISSQN FIXO //////////////////////////////
          if ($k03_tipo == 2) {
            $pdf1->titulo4 = 'Atividade';
            $pdf1->descr4_1 = '- '.$q07_ativ.'-'.$q03_descr;
            $pdf1->titulo13 = 'Atividade';
            $pdf1->descr13 = $q07_ativ;
            ////////////// PARCELAMANTO DE DIVIDA E DE INICIAL ////////////
          } else if (($k03_tipo == 6) || ($k03_tipo == 13)) {
            $pdf1->titulo4 = 'Parcelamento';
            $pdf1->descr4_1 = '- '.$v07_parcel.$exercicio;
            $pdf1->titulo13 = 'Parcelamento';
            $pdf1->descr13 = $v07_parcel;
          }

          $pdf1->descr5 = 'UNICA';
          $pdf1->descr6 = db_formatar($dtvencunic,'d');
          $pdf1->predescr6   = db_formatar($dtvencunic,'d');
          $pdf1->predatacalc = db_formatar($dtvencunic,'d');
          $pdf1->titulo8 = $descr;
          $pdf1->pretitulo8 = $descr;
          $pdf1->descr8 = $iNumeroOrigem;
          $pdf1->predescr8 = $iNumeroOrigem;
          $pdf1->descr9 = db_numpre($iNumpreRecibo, 0).'000';
          $pdf1->predescr9 = db_numpre($iNumpreRecibo, 0).'000';
          $pdf1->descr10 = 'UNICA';

          $pdf1->tipo_exerc      = "$k00_tipo / ".substr($dtvencunic,0,4);
          if (!empty ($HTTP_POST_VARS["ver_matric"])) {
             $sqlEnder = "select z01_nome,z01_ender, z01_numero, z01_compl, z01_munic as j43_munic, z01_uf as j43_uf, z01_cep as j43_cep, nomepri as j43_ender, j39_compl as j43_compl,
                                 j39_numero, j13_descr as j43_bairro, case when j13_descr is not null and j13_descr != '' then j13_descr else ''  end as j13_descr, j34_setor||'.'||j34_quadra||'.'||j34_lote as sql,
                                 z01_cgccpf, z01_bairro, z01_numcgm from proprietario where j01_matric = $j01_matric limit 1";

            $rsresultender = db_query($sqlEnder);
            $intNumrowsEnder = pg_numrows($rsresultender);
            if($intNumrowsEnder > 0){
              db_fieldsmemory($rsresultender,0);
            }


            $pdf1->pretipocompl  = 'Número:';
            $pdf1->tipobairro    = 'Bairro:';
            $pdf1->bairropri     = $j13_descr;
            $pdf1->nomepriimo = $j43_ender;
            $pdf1->tipocompl  = $j43_compl;
            $pdf1->tipocompl  = $j43_compl;
            $pdf1->tipocompl     = 'Número:';

            $pdf1->descr11_1    = $z01_cgmpri." - ".$proprietario;
            $pdf1->descr11_2    = $xender;
            $pdf1->descr11_3    = $xbairro;
            $pdf1->bairrocontri =  $z01_bairro;

            $pdf1->munic      = $j43_munic;
            $pdf1->premunic   = $j43_munic;
            $pdf1->uf         = $z01_uf;
            $pdf1->descr3_1   = $z01_cgmpri." - ".$proprietario;
            $pdf1->descr3_2   = $xender;
            $pdf1->predescr3_1= $z01_cgmpri." - ".$proprietario;
            $pdf1->predescr3_2= $z01_ender." ".$z01_numero." ".$z01_compl;
            $pdf1->descr3_3   = $xbairro;
            $pdf1->descr17    = $bql; //variavel q guarda o setor/quadra/lote


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
            $pdf1->complpri      = $j43_compl;
            $pdf1->precomplpri   = $j43_compl;
            $pdf1->precgccpf     = $z01_cgccpf;
            $pdf1->cgccpf        = $z01_cgccpf;

          } else {

            $pdf1->pretipocompl  = 'Número:';
            $pdf1->tipocompl     = 'Número:';
            $pdf1->tipobairro    = 'Bairro:';
            $pdf1->bairropri     = $z01_bairro;
            $pdf1->descr11_1     = $z01_numcgm." - ".$z01_nome;
            $pdf1->descr11_2     = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
            $pdf1->descr11_3     = $xbairro;
            $pdf1->bairrocontri  = $z01_bairro;
            $pdf1->munic         = $z01_munic;
            $pdf1->premunic      = $z01_munic;
            $pdf1->uf            = $z01_uf;
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

          }

      /************  P E G A   A S   R E C E I T A S   C O M   O S   V A L O R E S  *****************/

      $sqlReceitas  = " select substr(fc_calcula,15,13)::float8 as valor_corrigido,
                               substr(fc_calcula,28,13)::float8 as valor_juros,
                               substr(fc_calcula,41,13)::float8 as valor_multa,
                               ( substr(fc_calcula,54,13)::float8 * -1)as valor_desconto,
                               (substr(fc_calcula,15,13)::float8+ ";
      $sqlReceitas .= "        substr(fc_calcula,28,13)::float8+ ";
      $sqlReceitas .= "        substr(fc_calcula,41,13)::float8- ";
      $sqlReceitas .= "        substr(fc_calcula,54,13)::float8) as valreceita, ";
      $sqlReceitas .= "        codreceita, ";
      $sqlReceitas .= "        k00_hist, ";
      $sqlReceitas .= "        codtipo,";
      $sqlReceitas .= "        descrreceita, ";
      $sqlReceitas .= "        reduzreceita ";
      $sqlReceitas .= "    from (";
      $sqlReceitas .= " select k00_receit as codreceita, ";
      $sqlReceitas .= "        k02_descr  as descrreceita, ";
      $sqlReceitas .= "        case when taborc.k02_codigo is not null then k02_codrec ";
      $sqlReceitas .= "             when tabplan.k02_codigo is not null then k02_reduz ";
      $sqlReceitas .= "        end  as reduzreceita, ";
      $sqlReceitas .= "        k00_valor  as val, ";
      $sqlReceitas .= "        k00_tipo as codtipo,";
      $sqlReceitas .= "        k00_hist, ";
      $sqlReceitas .= "        fc_calcula(recibounica.k00_numpre,0,a.k00_receit,recibounica.k00_dtvenc,recibounica.k00_dtvenc,".db_getsession('DB_anousu').")";
      $sqlReceitas .= "   from arrecad a";
      $sqlReceitas .= "        inner join recibounica on recibounica.k00_numpre = a.k00_numpre";
      $sqlReceitas .= "        inner join tabrec  on tabrec.k02_codigo = a.k00_receit ";
      $sqlReceitas .= "        left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo ";
      $sqlReceitas .= "                          and taborc.k02_anousu   = ".db_getsession('DB_anousu')."";
      $sqlReceitas .= "        left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo ";
      $sqlReceitas .= "                          and tabplan.k02_anousu  = ".db_getsession('DB_anousu')."";
      $sqlReceitas .= " where a.k00_numpre = $k00_numpre ";
      $sqlReceitas .= "   and k00_numpar = 1 ";
      $sqlReceitas .= "   and recibounica.k00_dtvenc = '".$dtvencunicuni."' ) as c";

      $rsReceitas = db_query($sqlReceitas);
      $intnumrows = pg_num_rows($rsReceitas);
      $vlrjuros = 0;
      $vlrmulta = 0;
      $nDesconto = 0;
      for ($x = 0; $x < $intnumrows; $x ++) {
        db_fieldsmemory($rsReceitas, $x);
        $pdf1->arraycodreceitas[$x]   = $codreceita;
        $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
        $pdf1->arraydescrreceitas[$x] = $descrreceita;
        $pdf1->arrayvalreceitas[$x]   = $valor_corrigido; //   (float)substr($fc_calcula,14,13); //$valreceita;
        //$pdf1->arrayvalreceitas[$x]   = (substr($fc_calcula,12,14)+substr($fc_calcula,12,14)+substr($fc_calcula,12,14)-substr($fc_calcula,12,14))$valreceita; //   (float)substr($fc_calcula,14,13); //$valreceita;
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

          if($oRegraEmissao->isCobranca()){
            $pdf1->descr12_1 .= $pdf1->tipodebito ."\n ".
            										$pdf1->titulo1    ." - ".
            										$pdf1->descr1     ." / ".
                                $pdf1->titulo4    . " " .
                                $pdf1->descr4_1   ." Parcela Única \n";
            (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
            (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
            (isset($pdf1->predescr12_1)?$pdf1->predescr12_1 .= $pdf1->pretipodebito."\n":"").
            $pdf1->titulo1." - ".$pdf1->descr1." / ".
            $pdf1->titulo4." ".$pdf1->descr4_1." Parcela Única \n";
            (isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
            (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
          }

          ///////// PEGA A MSG DE PAGAMENTO E AS INSTRUÇÕES DA TABELA NUMPREF
          $rsmsgcarne = db_query("select k03_msgcarne, k03_msgbanco from numpref where k03_anousu = ".db_getsession("DB_anousu"));
          if (pg_numrows($rsmsgcarne) > 0) {
            db_fieldsmemory($rsmsgcarne, 0);
          }


          $sqlMsgCarne = " select k00_msguni2 from arretipo where k00_tipo = $k00_tipo ";
          $rsMsgCarneUnica = db_query($sqlMsgCarne);
          $intNumrowsMsgCarne = pg_numrows($rsMsgCarneUnica);

          if($intNumrowsMsgCarne > 0 ){
            db_fieldsmemory($rsMsgCarneUnica, 0);
          }

          if (isset ($k00_msguni2) && $k00_msguni2 != "") {
            $pdf1->predescr12_1 = $k00_msguni2; //msg unica, via contribuinte
          }

          $pdf1->descr14   = db_formatar($dtvencunic,'d');//.db_formatar($dtvencunic,'d');
          $pdf1->dtparapag = db_formatar($dtvencunic,'d');//.db_formatar($dtvencunic,'d');

          if ($iTercDig == '7') {
            //////////////////// ISSQN VARIAVEL /////////////////////
            if ($k03_tipo == 3) {
              $sqlaliq = "select q05_aliq,q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
              $rsIssvarano = db_query($sqlaliq);
              $intNumrows = pg_numrows($rsIssvarano);
              if ($intNumrows == 0) {
                db_redireciona('db_erros.php?fechar=true&db_erro=Ano não encontrado na tabela issvar. Contate o suporte');
                exit;
              }
              db_fieldsmemory($rsIssvarano, 0);
              $pdf1->descr4_1 = $k00_numpar.'a PARCELA   -   Alíquota '.$q05_aliq.'%     EXERCÍCIO : '.$q05_ano;
              //$pdf1->descr4_1   = $k00_numpar.'a PARCELA   -   Alíquota '.pg_result(db_query($sqlaliq),"q05_aliq").'%     EXERCÍCIO : '.pg_result(db_query($sqlaliq),"q05_ano");
            }
            $pdf1->titulo7 = 'Valor Pago';
            $pdf1->titulo15 = 'Valor Pago';
            $pdf1->titulo13 = 'Valor da Receita Tributável';


//*******************************************************************
           //alterado para passar os valores para o carnê (Anderson) ...

            $pdf1->descr7    = db_formatar($k00_valor, 'f');
            $pdf1->descr15   = db_formatar($k00_valor, 'f');
            $pdf1->valtotal  = db_formatar($k00_valor, 'f');
            $pdf1->predescr7 = db_formatar($k00_valor, 'f');
//*******************************************************************


          } else {
            $pdf1->descr15   = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor);
            $pdf1->valtotal  = db_formatar($k00_valor, 'f');
            $pdf1->descr7    = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor);
            $pdf1->predescr7 = db_formatar($k00_valor, 'f'); //($ninfla==''?'R$'.db_formatar($k00_valor,'f'):$ninfla.''.$k00_valor);
          }

          $pdf1->descr12_2 = '- PARCELA ÚNICA COM '.$k00_percdes.'% DE DESCONTO';
          $pdf1->prehistoricoparcela = ' PARCELA ÚNICA COM '.$k00_percdes.'% DE DESCONTO';

          $pdf1->linha_digitavel = $oConvenio->getLinhaDigitavel();
          $pdf1->codigo_barras = $oConvenio->getCodigoBarra();
          //debug($pdf1);exit;

          $sqlmsg = "select k00_tipo,k00_msguni,k00_msguni2 from arretipo where k00_tipo=".$k00_tipo;
          $resultmsg = db_query($sqlmsg);
          $linhasmsg = pg_num_rows($resultmsg);
          db_fieldsmemory($resultmsg, 0);
          $desconto = $k00_percdes;
          $texto  = db_geratexto($k00_msguni);
          $texto2 = db_geratexto($k00_msguni2);

          $pdf1->premsgunica =$texto;

          if ($texto != '' ) {
            $pdf1->descr12_1 .= $texto2;
          }

          if ($texto2 != '' ) {
            $pdf1->descr16_1 = substr($texto2, 0, 55);
            $pdf1->descr16_2 = substr($texto2, 55, 55);
            $pdf1->descr16_3 = substr($texto2, 110, 55);
          }

          //db_sel_instit();
          $pdf1->enderpref  = $ender;
          $pdf1->cgcpref    = $cgc;
          $pdf1->numeropref = $numero;
          $pdf1->municpref  = $munic;
          $pdf1->telefpref  = $email;
          $pdf1->emailpref  = $telef;


// ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
//verifica se é ficha e busca o codigo do banco
   if($oRegraEmissao->isCobranca()){

  $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
  $oBanco     = db_utils::fieldsMemory($rsConsultaBanco,0);
  $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
  $pdf1->banco      = $oBanco->db90_abrev;

  try {
    $pdf1->imagemlogo = $oConvenio->getImagemBanco();
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
  }

  }
//#############################################################
          $pdf1->imprime();
        }
      }
      $unica = 2;
      $pdf1->descr12_1 = '';
      $pdf1->premsgunica = '';


      if ($sounica == '') {

        $pdf1->objpdf->Output();
        db_query("COMMIT");
        exit;
      }
    $pdf1->arraycodhist       = array();
    $pdf1->arrayreduzreceitas = array();
    $pdf1->arraycodreceitas   = array();
    $pdf1->arraydescrreceitas = array();
    $pdf1->arrayvalreceitas   = array();
    $pdf1->arraycodtipo       = array();

    }
/******************************************************** FIM PARCELA UNICA ************************************************************************/

    if ($k00_codbco == "" || $k00_codage == "") {
      $errobco = "Código do banco e ou agência zerado ou nulo!";
      db_redireciona("db_erros.php?fechar=true&db_erro=Verifique cadastro do tipo de débito - $tipo_debito <br> $errobco");
      //.$tipo_debito."\n $errobco");
      exit;
    }

    $valores = split("P", $numpres[$volta]);
    $k00_numpre = $valores[0];
    $k00_numpar = split("R", $valores[1]);
    $k00_numpar = $k00_numpar[0];
    $k03_anousu = $H_ANOUSU;

    global $k03_numpre;
    $k03_numpre = 0;

    $DadosPagamento = debitos_numpre_carne($k00_numpre, $k00_numpar, $H_DATAUSU, $H_ANOUSU,db_getsession('DB_instit'),$DB_DATACALC,$forcarvencimento);
    db_fieldsmemory($DadosPagamento, 0);

//    db_criatabela($DadosPagamento);exit;
    if ( $total < 0 ) {
      db_redireciona("db_erros.php?fechar=true&db_erro=Valor negativo na{$sS} parcela{$sS} ".implode(",",$aParcelasSemInflatores)." verifique.");
      //array_push($aParcelasSemInflatores,$k00_numpar);
    }

    $total += $taxabancaria;
    $ninfla_ant = $ninfla;

    //    db_msgbox(date("Y-m-d",$H_DATAUSU)."<<<>>>".date("Y-m-d",db_getsession('DB_datausu')));

    if($k03_numpre == 0 ){
      $recibopaga = false;
    }else{
      $recibopaga = true;
    }

    if($recibopaga == false){
       $sql1 = "select k00_dtvenc as datavencimento,
       k00_dtvenc,
       k00_numtot,
       k00_dtoper
       from arrecad
       where k00_numpre = $k00_numpre
       and k00_numpar = $k00_numpar
       limit 1";
    }else{
       $sql1 = "select k00_dtvenc as datavencimento,
       k00_dtvenc,
       k00_numtot,
       k00_dtoper
       from recibopaga
       where k00_numnov = $k03_numpre
       limit 1";

    }

    db_fieldsmemory(db_query($sql1), 0);
    $k00_dtvenc = db_formatar($k00_dtvenc, 'd');
    $pdf1->data_processamento = db_formatar($k00_dtoper,'d'); // agora é a data de operação

    // alterei para buscar o terceiro digito pelo tipo de debito da tabela arretipo
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
          $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
          $ninfla = '';
        }

        if($total == 0){
          $iTercDig = 7;
    }else{
        $iTercDig = $k00_tercdigcarnenormal;
    }

      }else{

        $ninfla_ant = $ninfla;

        if ($total > 0){
          $k00_valor = $total;
          $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
          $ninfla = '';
        }else{
          $vlrbar = db_formatar(str_replace('.', '', str_pad(number_format(0, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
          $k00_valor = $qinfla;
        }

           $k00_valor = $total;


        if (($total == 0) || (substr($k00_dtvenc, 6, 4) > date("Y", $H_DATAUSU) +1 )) {
          if ($ninfla_ant == "REAL") {
            $iTercDig = 6;
            $vlrbar   = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
          } else {
            $iTercDig = 7;
            $vlrbar = "00000000000";
            if ($total != 0) {
              $k00_valor = $qinfla;
              $ninfla = $ss;
            }
          }
        }
      }
    } else {
      $k00_valor = $qinfla;
      $iTercDig  = 7;
      $vlrbar    = "00000000000";
    }

    if($recibopaga == false){
      $numpre = db_numpre($k00_numpre).db_formatar($k00_numpar, 's', "0", 3, "e");
    }else{
      $numpre = db_numpre($k03_numpre).db_formatar(0, 's', "0", 3, "e");
    }

    $dtvenc = substr($k00_dtvenc, 6, 4).substr($k00_dtvenc, 3, 2).substr($k00_dtvenc, 0, 2);
    $datavencimento = $dtvenc;
    $tmpdt = substr($db_datausu,0,4).substr($db_datausu,5,2).substr($db_datausu,8,2);

    if ($tmpdt > $datavencimento) {
       $datavencimento = $tmpdt;
    }

    if (isset ($emiscarneiframe) && $emiscarneiframe == 'n') {
      if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu')) {
        continue;
      }
    }

      $ninfla_ant = $ninfla;

      if($oRegraEmissao->isCobranca()){

        if (substr($datavencimento, 0, 4) > db_getsession('DB_anousu') && $k00_valor > 0 && ( $ninfla_ant != "" && $ninfla_ant != "REAL")) {
          $k00_valor = 0;
          $especie   = $ninfla;
          $histinf   = "\n Atenção : entre em contato com o municipio para saber o valor da $ninfla.";
        }else{
          $especie   = 'R$';
          $histinf   = "";
        }

        if($dtvenc < date('Ymd',db_getsession('DB_datausu'))){
          $msgvencida = "\n Parcela vencida, valor calculado com juros e multa até a data atual. Vencimento original ".$k00_dtvenc;
          $k00_dtvenc = date('d/m/Y',$H_DATAUSU);
        }else{
          $msgvencida = "";
        }
      }

        if($recibopaga == false){

          $iNumpre  = $k00_numpre;
          $iNumpar  = $k00_numpar;

        } else {

          $iNumpre  = $k03_numpre;
          $iNumpar  = 0;

        }

      try {

        $db_dtvenc = str_replace("/","-",$k00_dtvenc);
        $db_dtvenc = implode("-", array_reverse(explode('-', $db_dtvenc)));
        $oConvenio = new convenio($oRegraEmissao->getConvenio(),$iNumpre,$iNumpar,$k00_valor,$vlrbar,$db_dtvenc,$iTercDig);

      } catch (Exception $eExeption){
        db_redireciona("db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}");
        exit;
      }

      $pdf1->tipo_convenio = $oConvenio->getTipoConvenio();

      $codigo_barras   = $oConvenio->getCodigoBarra();
      $linha_digitavel = $oConvenio->getLinhaDigitavel();

      $pdf1->agencia_cedente = $oConvenio->getAgenciaCedente();
      $pdf1->carteira       = $oConvenio->getCarteira();

      if($oRegraEmissao->isCobranca()){

        if(strlen(trim($oConvenio->getConvenioCobranca())) == 7) {
          $pdf1->nosso_numero = trim($oConvenio->getConvenioCobranca()) . str_pad($k00_numpre,8,"0",STR_PAD_LEFT) . str_pad($k00_numpar,2,"0",STR_PAD_LEFT);
        } else {
          $pdf1->nosso_numero = $oConvenio->getNossoNumero();
        }

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
      $pdf1->titulo1 = $descr;
      $pdf1->descr1 = $iNumeroOrigem;


      if($recibopaga ==false){
        $pdf1->descr2 = db_numpre($k00_numpre, 0).db_formatar($k00_numpar, 's', "0", 3, "e");

      }else{
        $pdf1->descr2 = db_numpre($k03_numpre, 0).db_formatar(0, 's', "0", 3, "e");
      }


      $pdf1->tipo_exerc      = "$k00_tipo / ".substr($k00_dtoper,0,4);

      /************  P E G A   A S   R E C E I T A S   C O M   O S   V A L O R E S  *****************/
      if($recibopaga==false){

        $sqlReceitas  = " select k00_receit as codreceita, ";
        $sqlReceitas .= "        k02_descr  as descrreceita, ";
        $sqlReceitas .= "        case when taborc.k02_codigo is not null then k02_codrec ";
        $sqlReceitas .= "             when tabplan.k02_codigo is not null then k02_reduz ";
        $sqlReceitas .= "        end  as reduzreceita, ";
        $sqlReceitas .= "        k00_valor  as valreceita, ";
      $sqlReceitas .= "       (select (select k02_codigo from tabrec where k02_recjur = k00_receit or k02_recmul = k00_receit limit 1) is not null ) as codtipo,";
      //$sqlReceitas .= "        fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,'".date('Y-m-d',$H_DATAUSU)."','".date('Y-m-d',$H_DATAUSU)."',".$H_ANOUSU.")";
        $sqlReceitas .= "        fc_calcula(a.k00_numpre,a.k00_numpar,a.k00_receit,a.k00_dtvenc,a.k00_dtvenc,".db_getsession('DB_anousu').") ";
        $sqlReceitas .= "   from arrecad a";
        $sqlReceitas .= "        inner join tabrec  on tabrec.k02_codigo = a.k00_receit ";
        $sqlReceitas .= "        left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo ";
        $sqlReceitas .= "                          and taborc.k02_anousu   = ".db_getsession('DB_anousu')."";
        $sqlReceitas .= "        left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo ";
        $sqlReceitas .= "                          and tabplan.k02_anousu  = ".db_getsession('DB_anousu')."";
        $sqlReceitas .= " where k00_numpre = $k00_numpre ";
        $sqlReceitas .= "   and k00_numpar = $k00_numpar ";

      }else{

        $sqlReceitas  = " select k00_tipo as codtipo
                          from arrecad where k00_numpre = $k00_numpre";
        $rsReceitas = db_query($sqlReceitas);
        if(pg_numrows($rsReceitas)==0){
          db_msgbox("Não encontrado arrecad ($k00_numpre).");
          exit;
        }
        db_fieldsmemory($rsReceitas,0);

        $sqlReceitas  = " select k00_receit as codreceita, ";
        $sqlReceitas .= "        k02_descr  as descrreceita, ";
        $sqlReceitas .= "        case when taborc.k02_codigo is not null then k02_codrec ";
        $sqlReceitas .= "             when tabplan.k02_codigo is not null then k02_reduz ";
        $sqlReceitas .= "        end  as reduzreceita, ";
        $sqlReceitas .= "        k00_valor  as valreceita, ";
        $sqlReceitas .= "        k00_valor as fc_calcula";
        $sqlReceitas .= "   from recibopaga a";
        $sqlReceitas .= "        inner join tabrec  on tabrec.k02_codigo = a.k00_receit ";
        $sqlReceitas .= "        left  join taborc  on tabrec.k02_codigo   = taborc.k02_codigo ";
        $sqlReceitas .= "                          and taborc.k02_anousu   = ".db_getsession('DB_anousu')."";
        $sqlReceitas .= "        left  join tabplan on tabrec.k02_codigo   = tabplan.k02_codigo ";
        $sqlReceitas .= "                          and tabplan.k02_anousu  = ".db_getsession('DB_anousu')."";
        $sqlReceitas .= " where k00_numnov = $k03_numpre ";

      }

      $rsReceitas = db_query($sqlReceitas);
      $intnumrows = pg_num_rows($rsReceitas);
      for ($x = 0; $x < $intnumrows; $x ++) {
        db_fieldsmemory($rsReceitas, $x);
        $pdf1->arraycodreceitas[$x]   = $codreceita;
        $pdf1->arrayreduzreceitas[$x] = $reduzreceita;
        $pdf1->arraydescrreceitas[$x] = $descrreceita;

        if($recibopaga==false){
          $pdf1->arrayvalreceitas[$x]   = (float)substr($fc_calcula,14,13);
        }else{
          $pdf1->arrayvalreceitas[$x]   = $fc_calcula; //$valreceita;
        }

        $pdf1->arraycodtipo[$x]       = $codtipo;
      }

      if(isset($vlrjuros) && $vlrjuros != "" && $vlrjuros !=0){
        //      $x++;
        $pdf1->arraycodreceitas[$x]   = "";
        $pdf1->arrayreduzreceitas[$x] = "";
        $pdf1->arraydescrreceitas[$x] = "Juros : ";
        $pdf1->arrayvalreceitas[$x]   = "$vlrjuros";
        $pdf1->arraycodtipo[$x]       = $codtipo;
      }else{
        $pdf1->arraycodreceitas[$x]   = "";
        $pdf1->arrayreduzreceitas[$x] = "";
        $pdf1->arraydescrreceitas[$x] = "Juros : ";
        $pdf1->arrayvalreceitas[$x]   = "";
        $pdf1->arraycodtipo[$x]       = $codtipo;
      }
      if(isset($vlrmulta) && $vlrmulta != "" && $vlrmulta != 0){
        $x++;
        $pdf1->arraycodreceitas[$x]   = "";
        $pdf1->arrayreduzreceitas[$x] = "";
        $pdf1->arraydescrreceitas[$x] = "Multa : ";
        $pdf1->arrayvalreceitas[$x]   = "$vlrmulta";
        $pdf1->arraycodtipo[$x]       = $codtipo;
      }else{
        $x++;
        $pdf1->arraycodreceitas[$x]   = "";
        $pdf1->arrayreduzreceitas[$x] = "";
        $pdf1->arraydescrreceitas[$x] = "Multa : ";
        $pdf1->arrayvalreceitas[$x]   = "";
        $pdf1->arraycodtipo[$x]       = $codtipo;
      }

      /*
      echo "Pos: $x<br>";
      echo "Multa: $vlrmulta<br>";
      echo "Juros: $vlrjuros<br>";
      print_r($pdf1->arrayvalreceitas);
      echo "<br>";
      */

      /***********************************************************************************************/

      if(!empty ($HTTP_POST_VARS["ver_matric"])) {

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

        $rsresultender = db_query($sqlEnder);
        $intNumrowsEnder = pg_numrows($rsresultender);
        if($intNumrowsEnder > 0){
          db_fieldsmemory($rsresultender,0);
        }
        $pdf1->pretipocompl  = 'Número:';
        $pdf1->tipocompl     = 'Número:';
        $pdf1->tipobairro    = 'Bairro:';
        $pdf1->bairropri     = $j13_descr;
        $pdf1->descr11_1     = $z01_cgmpri." - ".$proprietario;
        $pdf1->descr11_2     = $xender;
        $pdf1->descr11_3     = $xbairro;
        $pdf1->bairrocontri  = $z01_bairro;
        $pdf1->munic         = $j43_munic;
        $pdf1->premunic      = $j43_munic;
        $pdf1->uf            = $z01_uf;
        $pdf1->descr3_1      = $z01_cgmpri." - ".$proprietario;
        $pdf1->descr3_2      = $xender;
        $pdf1->predescr3_1   = $z01_cgmpri." - ".$proprietario;
        $pdf1->predescr3_2   = $z01_ender." ".$z01_numero;
        $pdf1->descr3_3      = $xbairro;
        $pdf1->descr17       = $bql; //variavel q guarda o setor/quadra/lote
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
        $pdf1->complpri      = $j43_compl;
        $pdf1->precomplpri   = $j43_compl;
        $pdf1->precgccpf     = $z01_cgccpf;
        $pdf1->cgccpf        = $z01_cgccpf;

      }else if(!empty($HTTP_POST_VARS["ver_inscr"])){

        $iNumInscr = @$HTTP_POST_VARS["ver_inscr"];

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
        $pdf1->descr11_2     = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
        $pdf1->descr11_3     = $z01_bairro;
        $pdf1->bairrocontri  = $z01_bairro;
        $pdf1->munic         = $z01_munic;
        $pdf1->premunic      = $z01_munic;
        $pdf1->uf            = $z01_uf;
        $pdf1->descr3_1      = $z01_numcgm." - ".$z01_nome;
        $pdf1->descr3_2      = strtoupper($z01_ender). ($z01_numero == "" ? "" : ', '.$z01_numero.'  '.$z01_compl);
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


    } else if(!empty($HTTP_POST_VARS["ver_numcgm"])) {
        $iNumcgm = @$HTTP_POST_VARS["ver_numcgm"];

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
      $sqlvistorias = " select y77_descricao
                          from vistorianumpre
                               inner join vistorias     on vistorias.y70_codvist  = vistorianumpre.y69_codvist
                               inner join tipovistorias on vistorias.y70_tipovist = tipovistorias.y77_codtipo
                         where y69_numpre = $k00_numpre";
      $rsvistorias = db_query($sqlvistorias);
      /***********************************************************************************/

      if (pg_numrows($rsvistorias) > 0) {

        db_fieldsmemory($rsvistorias, 0);
        $pdf1->tipodebito    = $y77_descricao." - ".db_getsession('DB_anousu');
        $pdf1->pretipodebito = $y77_descricao." - ".db_getsession('DB_anousu');

      }

      if (isset ($obs)) {

        $pdf1->titulo13 = 'Observação';
        $pdf1->descr13 = $obs;

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

	  // André TI - Prefeitura de Maricá
	    $oMensagem = DBTributario::getMensagensParcela($k00_numpre, $k00_numpar, $k00_dtoper);

  		$pdf1->sMensagemContribuinte = $oMensagem->sMensagemContribuinte;
  		$pdf1->sMensagemCaixa        = $oMensagem->sMensagemCaixa;
      // André TI - Prefeitura de Maricá

      $pdf1->descr5 = $k00_numpar.' / '.$k00_numtot;
      $tmpdta       = split("/",$k00_dtvenc);
      $tmpdtvenc    = $tmpdta[2]."-".$tmpdta[1]."-".$tmpdta[0];

      if($db_datausu > $tmpdtvenc){
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

      if($recibopaga ==false){
        $pdf1->descr9     = db_numpre($k00_numpre, 0).db_formatar($k00_numpar, 's', "0", 3, "e");
        $pdf1->predescr9  = db_numpre($k00_numpre, 0).db_formatar($k00_numpar, 's', "0", 3, "e");
      }else{
        $pdf1->descr9     = db_numpre($k03_numpre, 0).db_formatar(0, 's', "0", 3, "e");
        $pdf1->predescr9  = db_numpre($k03_numpre, 0).db_formatar(0, 's', "0", 3, "e");
      }

      $pdf1->descr10 = $k00_numpar.' / '.$k00_numtot;
      $pdf1->descr14 = $k00_dtvenc;


      if ($total == 0) {

        //////////// ISSQN VARIAVEL ///////////

        if ($k03_tipo == 3) {
          $sqlaliq = "select q05_aliq,q05_ano from issvar where q05_numpre = $k00_numpre and q05_numpar = $k00_numpar";
          $rsIssvarano = db_query($sqlaliq);
          $intNumrows = pg_numrows($rsIssvarano);
          if ($intNumrows == 0) {
            db_redireciona('db_erros.php?fechar=true&db_erro=Ano não encontrado na tabela issvar. Contate o suporte');
            exit;
          }
          db_fieldsmemory($rsIssvarano, 0);
          $pdf1->descr4_1 = $k00_numpar.'a PARCELA   -   Alíquota '.$q05_aliq.'%     EXERCÍCIO : '.$q05_ano;
        }
        $pdf1->titulo7   = 'Valor Pago';
        $pdf1->titulo15  = 'Valor Pago';
        $pdf1->titulo13  = 'Valor da Receita Tributável';
        $pdf1->descr15   = '';
        $pdf1->valtotal  = '';
        $pdf1->descr7    = '';
        $pdf1->predescr7 = '';
      } else {

        $pdf1->descr15   = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
        $pdf1->valtotal  = db_formatar($k00_valor, 'f'); //$k00_valor;
        $pdf1->descr7    = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);
        $pdf1->predescr7 = ($ninfla == '' ? 'R$  '.db_formatar($k00_valor, 'f') : $ninfla.'  '.$k00_valor);

      }

    if($oRegraEmissao->isCobranca()){
        $pdf1->descr12_1 .= $pdf1->tipodebito."\n".
        $pdf1->titulo1." - ".$pdf1->descr1." / ".
        $pdf1->titulo4." ".$pdf1->descr4_1." Parcela - ".
        $k00_numpar."/".$k00_numtot."\n".
        //(isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
        (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
        (isset($pdf1->predescr12_1)?$pdf1->predescr12_1 .= $pdf1->pretipodebito:"")."\n".
        $pdf1->titulo1." - ".$pdf1->descr1." / ".
        $pdf1->titulo4." ".$pdf1->descr4_1." Parcela - ".
        $k00_numpar."/".$k00_numtot."\n".
        //(isset($bql)&&$bql!=""?" - ".$bql."\n":"\n").
        (isset($obsdiver)&&$obsdiver!=""?$obsdiver:"")."\n";
      }

      $rsmsgcarne = db_query("select k03_msgcarne, k03_msgbanco from numpref where k03_anousu = ".db_getsession("DB_anousu"));
      if (pg_numrows($rsmsgcarne) > 0) {
        db_fieldsmemory($rsmsgcarne, 0);
      }

      if ($pagabanco == 't') {
        if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
          if (isset ($k00_msgparcvenc2) && $k00_msgparcvenc2 != "") {
            $pdf1->descr12_1    .= $k00_msgparcvenc2." ".$histinf." ".$msgvencida;
            $pdf1->predescr12_1 .= $k00_msgparcvenc2." ".$histinf." ".$msgvencida;
          }
        } else {
          if (isset ($k00_msgparc2) && $k00_msgparc2 != "") {
            $pdf1->descr12_1 .= $k00_msgparc2." ".$histinf." ".$msgvencida;
            (isset($pdf1->predescr12_1)?$pdf1->predescr12_1 .= $k00_msgparc2." ".$histinf." ".$msgvencida:"");
          } elseif (isset ($k03_msgbanco) && $k03_msgbanco != "") {
            $pdf1->descr12_1    .= $k03_msgbanco." Não aceitar após vencimento.";
            $pdf1->predescr12_1 .= $k03_msgbanco." Não aceitar após vencimento.";
          }
        }
      } else {
        if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
          $pdf1->descr12_1 .= $k00_msgparcvenc2." ".$histinf." ".$msgvencida;
        } elseif (isset ($k00_msgparc2) && $k00_msgparc2 != "") {
          $pdf1->descr12_1 .= $k00_msgparc2." ".$histinf." ".$msgvencida;
        } elseif (isset ($k03_msgbanco) && $k03_msgbanco != "") {
          $pdf1->descr12_1 .= $k03_msgbanco." Após o vencimento cobrar juros de 1%a.m e multa de 2% ";
        } else {
          $pdf1->descr12_1 .= '- O PAGAMENTO DEVERÁ SER EFETUADO SOMENTE NA PREFEITURA.'." ".$histinf." ".$msgvencida;
        }
      }

      $sqlparag = "select db02_texto
                     from db_documento
                          inner join db_docparag on db03_docum = db04_docum
                          inner join db_paragrafo on db04_idparag = db02_idparag
                    where db03_docum = 27
                      and db02_descr ilike '%MENSAGEM CARNE%'
                      and db03_instit = ".db_getsession("DB_instit");

      $resparag = db_query($sqlparag);

      if (isset ($datavencimento) && (str_replace('-', '', $datavencimento) < date("Ymd", db_getsession("DB_datausu")))) {
        if (isset ($k00_msgparcvenc) && $k00_msgparcvenc != "") {
          if (strlen($k00_msgparcvenc) > 50) {
            $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strrpos(substr($k00_msgparcvenc, 0, 50), ' '));
          } else {
            $part1 = substr(substr($k00_msgparcvenc, 0, 50), 0, strlen($k00_msgparcvenc));
          }
          if (strlen($k00_msgparcvencvenc) > 100) {
            $part2 = substr(substr($k00_msgparcvenc, strlen($part1), 50), 0, strrpos(substr($k00_msgparcvenc, strlen($part1), strlen($k00_msgparcvenc)), ' '));
          } else {
            $part2 = substr(substr($k00_msgparcvenc, strlen($part1) + 1, 50), 0, strlen($k00_msgparcvenc));
          }
          if (strlen($k00_msgparcvenc) > 105) {
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
      $pdf1->texto = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();
      $imprimircodbar=true;
      $sqltermo = "select k40_forma
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
      $pdf1->cgcpref    = $cgc;
      $pdf1->numeropref = $numero;
      $pdf1->municpref  = $munic;
      $pdf1->telefpref  = $email;
      $pdf1->emailpref  = $telef;


      @$pdf1->especie = @$especie;

      // VERIFICA SE É UM PARCELAMENTO COM DESCONTO, SE FOR MOSTRAR O DESCONTO NO CARNE.

      $sqlVerParcel = "select k00_numpre,k00_numpar,k00_receit,k00_valor,k00_dtvenc,k00_tipo,v07_totpar,k40_aplicacao
                       from termo
                       inner join arrecad     on v07_numpre = k00_numpre
                       inner join cadtipoparc on k40_codigo = v07_desconto
                       where k00_numpre = $k00_numpre and k00_numpar = $k00_numpar  ";
      //echo "<br> $sqlVerParcel <br>";
      $resultVerParcel = db_query($sqlVerParcel);
      $linhasVerParcel = pg_num_rows($resultVerParcel);
      if($linhasVerParcel>0 ) {
        db_fieldsmemory($resultVerParcel,0);
        //calcula o desconto.
        $datahoje = date("Y-m-d");
        if($k00_dtvenc > $datahoje){
          $sqlDesconto = "select  fc_recibodesconto($k00_numpre,$k00_numpar,$v07_totpar,$k00_receit,$k00_tipo,'$k00_dtvenc','$k00_dtvenc') as percento";
          //echo "<br>$sqlDesconto<br>";
          $resultDesconto = db_query($sqlDesconto);
          $linhasDesconto = pg_num_rows($resultDesconto);
          $pdf1->descr4_2 ="";

          if($linhasDesconto >0){
            db_fieldsmemory($resultDesconto,0);
            if($percento != 0 ){
              $desc          = 100 - $percento;
              $valortotal    = round(($total * 100) / $desc,2);
              $valorDesconto = round($valortotal - $total,2);
    /*
              echo " <br> numpre  = $k00_numpre
                     <br> numpar  = $k00_numpar
                     <br> receita = $k00_receit
                     <br> total   = $v07_totpar
                     <br> tipo    = $k00_tipo
                     <br> dtvenc  = $k00_dtvenc
                     <br> valor   = $k00_valor  ";
              echo " <br> valortotal = $valortotal
                     <br> desconto = $valorDesconto
                     <br> valor com desconto = $total <br><br>";
             */
               $pdf1->descr4_2 = "Valor da parcela                    R$".db_formatar($valortotal,"f")." \nDesconto até o vencimento R$".db_formatar($valorDesconto,"f")."";

            }else{
              $pdf1->descr4_2="";
            }
          }
        }
      }

// ###################### BUSCA OS DADOS PARA IMPRIMIR O LOGO DO BANCO #########################
//verifica se é ficha e busca o codigo do banco
  if($oRegraEmissao->isCobranca()){

  $rsConsultaBanco  = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($oConvenio->getCodBanco()));
  $oBanco     = db_utils::fieldsMemory($rsConsultaBanco,0);
  $pdf1->numbanco   = $oBanco->db90_codban."-".$oBanco->db90_digban;
  $pdf1->banco      = $oBanco->db90_abrev;

  try {
    $pdf1->imagemlogo = $oConvenio->getImagemBanco();
  } catch (Exception $eExeption){
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eExeption->getMessage());
  }

  }
//#############################################################
      $pdf1->imprime();
      $pdf1->descr12_1 = '';
      $pdf1->predescr12_1 = '';
    }
    db_query("COMMIT");
    $pdf1->objpdf->Output();
  }
  // 4 - carne ficha de compensacao
  // 33 - pre-impresso bagea
  // 1 ou 2 normal
?>

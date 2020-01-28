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

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("classes/db_tabativ_classe.php");
require_once("classes/db_issprocesso_classe.php");
require_once("classes/db_isstipoalvara_classe.php");
require_once("classes/db_ativprinc_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("libs/db_utils.php");
require_once("libs/db_libtributario.php");
require_once("libs/db_libsys.php");
require_once("dbagata/classes/core/AgataAPI.class");
require_once("model/documentoTemplate.model.php");
require_once("std/db_stdClass.php");

$clisstipoalvara = new cl_isstipoalvara;

db_postmemory($HTTP_SERVER_VARS);

/**
 * Get tem as variáveis
 * inscricao  (código)
 */
$oGet  = db_utils::postMemory($_GET);

/* VERIFICA SE É DOCUMENTO ALVARÁ */
/* SE FOR, IMPRIME UTILIZANDO O ARQUIVO AGT */

$sSql = 'SELECT * from parissqn';
$rsParissqn = db_query($sSql);
db_fieldsmemory($rsParissqn, 0);

if($q60_modalvara == 9) {

	ini_set("error_reporting","E_ALL & ~NOTICE");

	$sDescrDoc        = date("YmdHis").db_getsession("DB_id_usuario");
	$sNomeRelatorio   = "tmp/geraAlvara{$sDescrDoc}.pdf";
	$sCaminhoSalvoSxw = "tmp/alvara_{$sDescrDoc}_{$oGet->inscricao}.sxw";

	$sAgt = "issqn/alvara.agt";

	/**
   * Retorna se o Alvara pode ser Impresso
   */
  $sSqlTipoAlvara  = " select q98_documento ";
  $sSqlTipoAlvara .= "  from isstipoalvara ";
  $sSqlTipoAlvara .= "       inner join issalvara on issalvara.q123_isstipoalvara = q98_sequencial ";
  $sSqlTipoAlvara .= " where q123_inscr = {$oGet->inscricao} ";
  $rsTipoAlvara    = $clisstipoalvara->sql_record($sSqlTipoAlvara);

  $oTipoAlvara     = db_utils::fieldsMemory($rsTipoAlvara,0);

	$aParam          = array();
	$aParam['$inscr'] = $oGet->inscricao;

	db_stdClass::oo2pdf(6, $oTipoAlvara->q98_documento, $sAgt, $aParam, $sCaminhoSalvoSxw, $sNomeRelatorio);

	exit;
}

$cltabativ       = new cl_tabativ();
$clativprinc     = new cl_ativprinc();
$cldb_config     = new cl_db_config();
$clissprocesso   = new cl_issprocesso();
$pdf = new scpdf();
$pdf->Open();
//$pdf1 = new db_impcarne($pdf);
$rsResultpar = db_query("select * from parissqn");
//db_criatabela($rsResult);exit;
if (pg_numrows($rsResultpar) > 0) {
  db_fieldsmemory($rsResultpar, 0);
}

$impdatas = $q60_impdatas;
$impcodativ = $q60_impcodativ;
$impobsativ = $q60_impobsativ;
$impobslanc = $q60_impobsissqn;

$ano = db_getsession("DB_anousu");

//========== ALVARA PRE-IMPRESSO ==================//

if (isset($q60_modalvara) && $q60_modalvara == "3") {

  $tamanho = array (

                        290,
                        95
  );
  $spdf1 = new scpdf("P", "mm", $tamanho);
  $spdf1->Open();
  $pdf2 = new db_impcarne($spdf1, '26');
  $pdf2->objpdf->AddPage();
  $pdf2->objpdf->SetTextColor(0, 0, 0);
  //                             die($cltabativ->sql_queryinf($oGet->inscricao,"","*",""," q88_inscr is not null and tabativ.q07_inscr = $oGet->inscricao "));
  //	die($cltabativ->sql_queryinf($oGet->inscricao,"","*",""," q88_inscr is not null and tabativ.q07_inscr = $oGet->inscricao "));
  $rsResult = $cltabativ->sql_record($cltabativ->sql_queryinf($oGet->inscricao, "", "*", "", " q88_inscr is not null and tabativ.q07_inscr = $oGet->inscricao "));
  $numrows = $cltabativ->numrows;
  if ($numrows != 0) {
    db_fieldsmemory($rsResult, 0);
  } else {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
    exit();
  }
  $pdf2->nrinscr = $q02_inscr;
  $pdf2->dtiniativ = $q07_datain;
  $pdf2->dtinic = $q02_dtinic;
  $pdf2->ativ = $q07_ativ;
  $pdf2->nome = $z01_nome;
  $pdf2->fantasia = $z01_nomefanta;
  $pdf2->ender = $j14_nome;
  $pdf2->cnpjcpf = $z01_numcgm;
  $pdf2->descrativ = $q03_descr;
  $pdf2->numero = $q02_numero;
  $pdf2->compl = $q02_compl;
  $pdf2->bairropri = $j13_descr;
  $pdf2->imprime();
  $pdf2->objpdf->Output();
  exit();
  //====================================================================================================================
} else {
  if (isset($q60_modalvara) && $q60_modalvara == "1") {
    //echo "modelo 23";
    $pdf1 = new db_impcarne($pdf, '23'); // alvara tamanha A5
  } else if (isset($q60_modalvara) && $q60_modalvara == "2") {
    //echo "modelo 24";
    $pdf1 = new db_impcarne($pdf, '24'); // alvara tamanho A4
  } else if (isset($q60_modalvara) && $q60_modalvara == "4") {
    //echo "modelo 35";
    $pdf1 = new db_impcarne($pdf, '35'); // alvara tamanho A4 com fontes menores
  } else if (isset($q60_modalvara) && $q60_modalvara == "5") {
    //echo "modelo 50";
    $pdf1 = new db_impcarne($pdf, '50'); // alvara tamanho pre-impresso A4
  } else if (isset($q60_modalvara) && $q60_modalvara == "6") {
    //echo "modelo 59";
    $pdf1 = new db_impcarne($pdf, '59'); // alvara tamanho pre-impresso A4
  } else if (isset($q60_modalvara) && $q60_modalvara == "7") {
    //echo "modelo 63";
    $pdf1 = new db_impcarne($pdf, '63'); // alvara tamanho A4 frente/verso
  } else if (isset($q60_modalvara) && $q60_modalvara == "8") {
    //echo "modelo 64";
    $pdf1 = new db_impcarne($pdf, '64'); // alvara tamanho A4 processo/area
  } else {
    db_redireciona('db_erros.php?fechar=true&db_erro=Modelo de alvara não definido !');
    exit();
  }

  $pdf1->objpdf->AddPage();
  $pdf1->objpdf->SetTextColor(0, 0, 0);
  // die($cldb_config->sql_query(db_getsession("DB_instit"),"nomeinst as prefeitura, munic"));
  $resul = $cldb_config->sql_record($cldb_config->sql_query(db_getsession("DB_instit"), "nomeinst as prefeitura, munic"));
  db_fieldsmemory($resul, 0); //pega o dados da prefa
  $munic = strtoupper($munic);
  //global $db02_texto;
  //	                             die($cltabativ->sql_queryinf("$oGet->inscricao","","*","","(q07_databx < '".date("Y-m-d", db_getsession("DB_datausu"))."' or  q07_databx is null) and tabativ.q07_inscr = $oGet->inscricao "));
  $result = $cltabativ->sql_record($cltabativ->sql_queryinf($oGet->inscricao, "", "*", "", "q88_inscr is not null and (q07_databx < '" . date("Y-m-d", db_getsession("DB_datausu")) . "' or  q07_databx is null) and tabativ.q07_inscr = $oGet->inscricao "));
  $numrows = $cltabativ->numrows;
  if ($numrows > 0) {
    db_fieldsmemory($result, 0);
  } else {
    db_redireciona('db_erros.php?fechar=true&db_erro=Atividades não cadastradas!');
  }
  //================================= PARAGRAFOS ALVARA PROVISORIO =====================================================
  if ($q07_perman == 'f') {
    $pdf1->tipoalvara = 'LICENÇA PROVISÓRIA DE ATIVIDADE';
    $pdf1->permanente = 'f';
    $sqlparag = "select *
      from db_documento
      inner join db_docparag on db03_docum = db04_docum
      inner join db_tipodoc on db08_codigo  = db03_tipodoc
      inner join db_paragrafo on db04_idparag = db02_idparag
      where db03_tipodoc = 1011 and db03_instit = " . db_getsession("DB_instit") . "
      and not db02_descr ilike 'assinatura%'
      order by db04_ordem ";
    $resparag = db_query($sqlparag);
    //	   die($sqlparag);
    //db_criatabela($resparag);exit;
    if (pg_numrows($resparag) == 0) {
      db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento 26 com os paragrafos do alvara!');
      exit();
    }
    $numrows = pg_numrows($resparag);
    //	$pdf1->inicia = $db02_inicia;
    for($i = 0; $i < $numrows; $i ++) {
      db_fieldsmemory($resparag, $i);
      if ($db04_ordem == '1') {
        $pdf1->texto = $db02_texto;
      }
      // == assinatura1
      if ($db04_ordem == '2') {
        $pdf1->obs = $db02_texto;
      }
      if ($db04_ordem == '3') {
        $pdf1->assalvara = $db02_texto;
      }
    }
  } else {
    //============================== PARAGRAFOS ALVARA PERMANENTE ======================================================


    //db_msgbox("permanente");


    $sql_tipoalvara = "select db_paragrafo.*
      from db_documento
      inner join db_docparag on db03_docum = db04_docum
      inner join db_paragrafo on db04_idparag = db02_idparag
      where db03_tipodoc = 1034 and
      db03_instit  = " . db_getsession('DB_instit') . "
      order by db02_descr
      ";
    $res_tipoalvara = db_query($sql_tipoalvara);
    if (pg_numrows($res_tipoalvara) > 0) {
      db_fieldsmemory($res_tipoalvara, 0);
      $pdf1->tipoalvara = $db02_texto;
    } else {
      $pdf1->tipoalvara = 'ALVARÁ DE LICENÇA PARA LOCALIZAÇÃO OU EXERCÍCIO DA ATIVIDADE';
    }

    $pdf1->permanente = 't';
    $sqlparag = "select *
      from db_documento
      inner join db_docparag on db03_docum = db04_docum
      inner join db_tipodoc on db08_codigo  = db03_tipodoc
      inner join db_paragrafo on db04_idparag = db02_idparag
      where db03_tipodoc = 1010 and db03_instit = " . db_getsession("DB_instit") . "
      and not db02_descr ilike 'assinatura%'
      order by db04_ordem ";
    $resparag = db_query($sqlparag);
    //die($sqlparag);
    //db_criatabela($resparag);exit;
    if (pg_numrows($resparag) == 0) {
      db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento do alvara!');
      exit();
    }
    $numrows = pg_numrows($resparag);
    //		$pdf1->inicia = $db02_inicia;
    for($i = 0; $i < $numrows; $i ++) {
      db_fieldsmemory($resparag, $i);
      if ($db04_ordem == '1') {
        $pdf1->texto = $db02_texto;
      }
      if ($db04_ordem == '2') {
        $pdf1->obs = $db02_texto;
      }
      if ($db04_ordem == '3') {
        $pdf1->assalvara = $db02_texto;
      }
    }
  }

  // PEGA A ATIVIDADE PRINCIPAL
  //die($cltabativ->sql_queryinf($oGet->inscricao,"","*",""," q88_inscr is not null and q11_inscr is null and tabativ.q07_inscr = $oGet->inscricao "));
  $result = $cltabativ->sql_record($cltabativ->sql_queryinf($oGet->inscricao, "", "*", "", " q88_inscr is not null and q11_inscr is null and tabativ.q07_inscr = $oGet->inscricao "));
  //die($cltabativ->sql_queryinf($oGet->inscricao,"","*",""," q88_inscr is not null and q11_inscr is null and tabativ.q07_inscr = $oGet->inscricao "));
  $numrows = $cltabativ->numrows;
  if ($numrows != 0) {
    db_fieldsmemory($result, 0);
  } else {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
    exit();
  }

  $sSql = " select q14_proces,
                       q30_area,
                       p58_dtproc, extract (year from p58_dtproc)::integer as p58_ano
                  from issbase
                       left join issprocesso       on issbase.q02_inscr      = issprocesso.q14_inscr
                       left join protprocesso      on issprocesso.q14_proces = protprocesso.p58_codproc
                       left join issquant          on issquant.q30_inscr     = issbase.q02_inscr
                                                  and issquant.q30_anousu    = {$ano}
                 where issbase.q02_inscr = {$oGet->inscricao}";
  //die($sSql);
  $result = db_query($sSql);
  if (pg_numrows($result) > 0) {
    db_fieldsmemory($result, 0);
  }

  db_sel_instit();

  $pdf1->prefeitura = $nomeinst;
  $pdf1->municpref = $munic;
  $pdf1->ativ = $q07_ativ;
  $pdf1->nrinscr = $q02_inscr;
  $pdf1->nome = $z01_nome;
  $pdf1->nomecompl = $z01_nomecomple;
  $pdf1->processo    = @$q14_proces;
  $pdf1->areaterreno = @$q30_area;
  $pdf1->cgm = $z01_numcgm;
  $pdf1->fantasia = $z01_nomefanta;
  $pdf1->obsativ = $q03_atmemo;
  $pdf1->ender = $j14_nome;
  $pdf1->bairropri = $j13_descr;
  $pdf1->compl = $q02_compl;
  $pdf1->numero = $q02_numero;
  $pdf1->descrativ = $q03_descr;
  $pdf1->datainc = $q02_dtinic;
  $pdf1->cnpjcpf = $z01_cgccpf;
  $pdf1->dtiniativ = $q07_datain;
  $pdf1->dtfimativ = $q07_datafi;
  $pdf1->lancobs = $q02_memo;
  $pdf1->dtinic = $q02_dtinic;
  $pdf1->icms = $z01_incest;
  $pdf1->rg = $z01_ident;
  $pdf1->datacad = $q02_dtcada;
  $pdf1->q07horaini = $q07_horaini;
  $pdf1->q07horafim = $q07_horafim;
  $pdf1->q02memo = $q02_memo;

  $pdf1->impdatas = $impdatas;
  $pdf1->impcodativ = $impcodativ;
  $pdf1->impobsativ = $impobsativ;
  $pdf1->impobslanc = $impobslanc;

  // PEGA AS ATIVIDADES SECUNDARIAS
  //die($cltabativ->sql_queryinf($q02_inscr,"","*",""," q88_inscr is null "));
  $arr = array ();
  $arr02 = array ();
  $descr = "";

  //   die($cltabativ->sql_queryinf($oGet->inscricao, "", "*", "", " q88_inscr is null and q11_inscr is null and (q07_databx < '".date("Y-m-d", db_getsession("DB_datausu"))."' or  q07_databx is null) and tabativ.q07_inscr = $oGet->inscricao "));
  $result = $cltabativ->sql_record($cltabativ->sql_queryinf($oGet->inscricao, "", "*", "", " q88_inscr is null and q11_inscr is null and (q07_databx < '" . date("Y-m-d", db_getsession("DB_datausu")) . "' or  q07_databx is null) and tabativ.q07_inscr = $oGet->inscricao "));
  $numrows = $cltabativ->numrows;
  //	die($numrows);
  if ($numrows != 0) {
    for($i = 0; $i < $numrows; $i ++) {
      db_fieldsmemory($result, $i);
      if ($descr != $q03_descr) {
        $arr [$i] ["codativ"] = $q07_ativ;
        $arr [$i] ["descr"] = $q03_descr;
        $arr [$i] ["datain"] = $q07_datain;
        $arr [$i] ["datafi"] = $q07_datafi;
        $arr [$i] ["atv_perman"] = $q07_perman;
        $q03_atmemo = str_replace("\n", "", $q03_atmemo);
        $q03_atmemo = str_replace("\r", "", $q03_atmemo);
        $arr02 [$q07_ativ] = $q03_atmemo;
      }
    }
  }
  $pdf1->q03_atmemo = $arr02;
  $pdf1->outrasativs = $arr;

  if (isset($q02_memo)) {
    //    $q02_memo = str_replace("\n", "", $q02_memo);
    //    $q02_memo = str_replace("\r", "", $q02_memo);
    $pdf1->q02_memo = substr($q02_memo, 0, 3500);
    $pdf1->q02_memo .= " ...";
  }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sSqlCodCnae = " select ativprinc.q88_inscr as princ, q71_estrutural ";
$sSqlCodCnae .= "   from tabativ ";
$sSqlCodCnae .= "        left join ativprinc     on ativprinc.q88_inscr          = tabativ.q07_inscr            ";
$sSqlCodCnae .= "                               and ativprinc.q88_seq            = tabativ.q07_seq              ";
$sSqlCodCnae .= "        left join atividcnae    on atividcnae.q74_ativid        = tabativ.q07_ativ             ";
$sSqlCodCnae .= "        left join cnaeanalitica on cnaeanalitica.q72_sequencial = atividcnae.q74_cnaeanalitica ";
$sSqlCodCnae .= "        left join cnae          on cnae.q71_sequencial          = cnaeanalitica.q72_cnae       ";
$sSqlCodCnae .= "  where q07_inscr = {$oGet->inscricao} ";
$rsCodCnae = db_query($sSqlCodCnae);
$iTotalLinhas = pg_num_rows($rsCodCnae);
for($i = 0; $i < $iTotalLinhas; $i ++) {

  $oCodCnae = db_utils::fieldsMemory($rsCodCnae, $i);

  if (isset($oCodCnae->princ) && $oCodCnae->princ != "") {
    $pdf1->iAtivPrincCnae = substr($oCodCnae->q71_estrutural, 1, strlen($oCodCnae->q71_estrutural));
  } else {
    $pdf1->aCodigosCnae [] = substr($oCodCnae->q71_estrutural, 1, strlen($oCodCnae->q71_estrutural));
  }

}

$pdf1->imprime();
$pdf1->objpdf->Output();
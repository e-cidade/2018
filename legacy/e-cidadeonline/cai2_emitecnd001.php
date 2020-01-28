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

require_once("libs/db_utils.php");
require_once("std/DBLargeObject.php");
require_once("libs/db_sql.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_protparam_classe.php");
require_once("classes/db_certidao_classe.php");
require_once("classes/db_parjuridico_classe.php");
require_once("classes/db_certidaocgm_classe.php");
require_once("classes/db_certidaoinscr_classe.php");
require_once("classes/db_certidaomatric_classe.php");
require_once("classes/db_numpref_classe.php");
require_once("classes/db_db_docparag_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_db_certidaoweb_classe.php");
require_once("classes/db_cgm_classe.php");

$ip = getenv("REMOTE_ADDR");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$textarea = "";
$codproc  = "";
$sWhere   = "";

if (isset ($cadrecibo) && $cadrecibo == 't') {
	require_once ('fpdf151/scpdf.php');
} else {
	require_once ('fpdf151/pdf1.php');
}
$clcertidaoweb    = new cl_db_certidaoweb;
$clcertidao       = new cl_certidao;
$clcertidaocgm    = new cl_certidaocgm;
$clcertidaoinscr  = new cl_certidaoinscr;
$clcertidaomatric = new cl_certidaomatric;
$clnumpref        = new cl_numpref;
$cldb_docparag    = new cl_db_docparag;
$cldb_usuarios    = new cl_db_usuarios;

$dadosbaixaempresa     = "";
$dadosalvaraprovisorio = "";
$dadosbaixamatricula   = "";
db_query("select fc_putsession('DB_anousu','".db_getsession("DB_anousu")."')");

$mes1 = date("m");
$ano  = date("Y");
$dia  = date("d");
$hora = date("H");
$min  = date("i");
$sec  = date("s");
$ip   = getenv("REMOTE_ADDR");

if ( $tipo == 1 ) {
	$sTipoCertidao = "p"; //Certidão positiva
} else if ( $tipo == 2 ) {
	$sTipoCertidao = "n"; //Certidão negativa
} else {
	$sTipoCertidao = "r"; //Certidão regular
}

$w13_instit   = db_getsession('DB_instit');
$sqlconfig    = "select * from configdbpref where w13_instit = $w13_instit ";
$resultconfig = db_query($sqlconfig);
$linhaconfig  = pg_num_rows($resultconfig);
if ( $linhaconfig > 0 ){
  db_fieldsmemory($resultconfig, 0);
}

/**
 * Novos parametros para reemisão da certidão
 */
$iInstit      		 = db_getsession('DB_instit');
$iAnoUsu      		 = db_getsession('DB_anousu');

$lReemitirCertidao = false;

/**
 * Usa como parametro a ultima certidao emitida com o tipo selecionado
 */
if ( isset ($titulo) && $titulo == 'MATRICULA' ) {
	$sWhere = " and p47_matric = {$origem} ";
}elseif( isset ($titulo) && $titulo == 'INSCRICAO' ) {
	$sWhere = " and p48_inscr  = {$origem} ";
}else {
	$sWhere = " and p49_numcgm = {$origem} ";
}

$sSql         = $clcertidao->sql_query_certidao( '', 'p50_web, p50_data, p50_sequencial, p50_arquivo as oid', 'p50_data DESC, p50_sequencial DESC LIMIT 1', "p50_instit = {$iInstit} and p50_tipo = '{$sTipoCertidao}' {$sWhere}" );
$rsResultados = $clcertidao->sql_record( $sSql );
if ( !empty($rsResultados) ){

  db_fieldsmemory($rsResultados,0);
  $dDataEmissao = $p50_data;
  $iOidCertidao = $oid;
}

/**
 * Valida se deve efetuar a reemissao da certidao ou emitir nova
 * $dDataEmissao + $dDiasValidade - $iPrazoLimiteReemissao
 */
$sSql         = $clnumpref->sql_query_file ( $iAnoUsu, $w13_instit, "k03_diasreemissaocertidao, k03_diasvalidadecertidao", 'k03_anousu LIMIT 1' );
$rsResultados = $clnumpref->sql_record( $sSql );

if ( pg_num_rows($rsResultados) > 0 ) {

  db_fieldsmemory($rsResultados,0);

  if( isset($dDataEmissao) ){
  	$sSql    			= "select '$dDataEmissao'::date + '$k03_diasvalidadecertidao days'::interval - '$k03_diasreemissaocertidao days'::interval as datavalidacao";
  	$rsVencimento = db_query( $sSql );
  	db_fieldsmemory($rsVencimento, 0);
  	$dDataValidacao = $datavalidacao;

  	$sSql         = "select '$dDataValidacao'::date - '$ano-$mes1-$dia'::date as datavalida";
  	$rsValida     = db_query($sSql);
  	db_fieldsmemory($rsValida, 0);

  	if ( $datavalida > 0 ) {
  		$lReemitirCertidao = true;
  	}
  }
}

/**
 * Só deve permitir a reemisão caso a data seja valida
 * @todo retirar ! da $lReemitirCertidao
 */
if ( $lReemitirCertidao && isset ($iOidCertidao) && $iOidCertidao != 0 && $p50_web == 't') {

	db_inicio_transacao();
	$sArquivo 				= "tmp/certidao_" . $p50_sequencial. '.pdf';
	$lReemitiuArquivo = DBLargeObject::leitura($iOidCertidao, $sArquivo);
	db_fim_transacao();

	if ( $lReemitiuArquivo ) {

	  db_redireciona( $sArquivo );
	  exit;
	}
}

/**
 * Processo de emissão de uma nova certidão
 */
try{

  $sqlvenc = "select '$ano-$mes1-$dia'::date + '$k03_diasvalidadecertidao days'::interval as datavenc";
  $resultvenc = db_query($sqlvenc) or die($sqlvenc);
  db_fieldsmemory($resultvenc, 0);

  $venc = $datavenc;
  $dia2 = substr($venc, 8, 2);
  $mes2 = substr($venc, 5, 2);
  $ano2 = substr($venc, 0, 4);

  $venc      = $ano2 . "-" . $mes2 . "-" . $dia2;
  $sequencia = db_query("select nextval('db_certidaoweb_codcert_seq')") or die("erro ao gerar sequencia");
  $seq2      = pg_result($sequencia, 0, 0);
  $tamanho   = strlen($seq2);
  $seq       = "";
  for ($i = 0; $i < (7 - $tamanho); $i++) {
  	$seq .= "0";
  }
  $seq .= $seq2;
  $sql  = db_query("select cgc, db21_regracgmiptu from db_config where codigo = $w13_instit limit 1");
  for ($i = 0; $i < (pg_numfields($sql)); $i++) {
  	@db_fieldsmemory($sql, 0);
  }

  $nros = $seq . $cgc . $ano . $mes1 . $dia . $hora . $min . $sec;
  $t1   = strrev($nros);

  //**************************************************************************************************************************//

  if (isset ($textarea) && $textarea != "") {
  	$historico = $textarea;
  } else {
  	$textarea = @ $historico;
  }
  if ($codproc != "") {

  	if (strpos($codproc, "/") > 0) {

  		$codproc   = split("\/", $codproc);
  		$exercicio = $codproc[1];
  		$codproc   = $codproc[0];
  	} else {

  		$codproc   = $codproc;
  		$exercicio = db_getsession("DB_anousu");
  	}
  } else {

  	$codproc = "";
  	$exercicio = 0;
  }

  if ($codproc && $codproc != "") {
  	$proc = ",conforme processo N".chr(176)." $codproc, ";
  }
  $sqlerro = false;

  db_inicio_transacao();

  if (isset ($titulo) && $titulo == 'CGM') {

    $numcgm  = $origem;
    $iNumcgm = $origem;
  }

  if (isset ($titulo) && $titulo == 'MATRICULA') {

    $matric     = $origem;
    $iMatricula = $origem;
  }

  if (isset ($titulo) && $titulo == 'INSCRICAO') {

    $inscr      = $origem;
    $iInscricao = $origem;
  }

  //**************************************************************************************************************************//
  if (isset ($textarea) && $textarea != "") {
  	$historico = $textarea;
  } else {
  	$textarea = @ $historico;
  }
  $codtipodoc = 0;
  $sql = "select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where prefeitura = true and codigo = $w13_instit ";
  $result = db_query($sql);
  if(pg_num_rows($result) > 0){
  	db_fieldsmemory($result, 0);
  }

  if($w13_tipocertidao == '3') {
    $w13_tipocertidao = $indconj;
  }

  if ($tipo == 1) {
  	// certidao positiva
  	$tipocer = "CERTIDÃO POSITIVA DE DÉBITO";
  	if (isset ($matric)) {

  	  $codtipodoc = $w13_tipocertidao == '1' ?  1028 : 2028 ;
  		//$codtipodoc = 1028;
  		$codtipo = 26;

  		$sql = "select * from proprietario where j01_matric = $matric";
  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

      if (isset ($j01_baixa) && $j01_baixa != "") {

        $situinscr           = "Situação da matrícula: MATRÍCULA BAIXADA ";
        $dadosbaixamatricula = "Matricula Baixada em: ".db_formatar($j01_baixa,'d');
      } else {
        $situinscr           = "Situação da matrícula: MATRÍCULA ATIVA ";
      }

  		db_sel_instit(null, "db21_usasisagua");
  		if($db21_usasisagua == 't') {
  		  $sSqlEndImovel = "select x01_matric      as j01_matric,
                               j14_nome,
                               x01_numero      as j39_numero,
                               x11_complemento as j39_compl,
                               j13_descr,
                               x01_quadra      as j34_quadra
                          from aguabase
                         inner join ruas       on j14_codigo = x01_codrua
                         inner join bairro     on j13_codi   = x01_codbairro
                          left join aguaconstr on x11_matric = x01_matric
                         where x01_matric = $matric";
  		  $rSqlEndImovel = db_query($sSqlEndImovel);
  		  db_fieldsmemory($rSqlEndImovel, 0);
  		}

  	} else	if (isset ($numcgm)) {

  		$codtipodoc = $w13_tipocertidao == '1' ?  1030 : 2030 ;
  		//$codtipodoc = 1030;
  		$codtipo = 27;
  		$sql = "select trim(z01_nome) as z01_nome,* from cgm where z01_numcgm = $numcgm";
  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

  	} else	if (isset ($inscr)) {

  		$codtipodoc = $w13_tipocertidao == '1' ?  1029 : 2029 ;

  		$codtipo = 28;
  		$sql = "select * from empresa where q02_inscr = $inscr";
  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

      if (isset ($q02_dtbaix) && $q02_dtbaix != "") {
        $situinscr         = "Situação do alvará: ALVARÁ BAIXADO ";
        $dadosbaixaempresa = "Alvará Baixado em: ".db_formatar($q02_dtbaix,'d');
      } else {
        $situinscr         = "Situação do alvará: ALVARÁ ATIVO ";
      }

      $sql2 = " select q07_inscr,
                       q07_perman,
                       min(q07_datain) as q07_datain,
                       max(q07_datafi) as q07_datafi
                  from tabativ
                 where q07_inscr = {$inscr}
                   and q07_perman = false
              group by q07_inscr, q07_perman ";
      $result2 = db_query($sql2);

      if (pg_num_rows($result2) > 0) {
        db_fieldsmemory($result2, 0);
        $dadosalvaraprovisorio = "Alvará Provisório Válido entre: (".db_formatar($q07_datain,'d')." e ".db_formatar($q07_datafi,'d').")";
      }
  	}

  } else	if ($tipo == 2) {
  	// certidao negativa

    $tipocer = "CERTIDÃO NEGATIVA";
  	if (isset ($matric)) {

  		$codtipodoc = $w13_tipocertidao == '1' ?  1022 : 2022 ;
  		//$codtipodoc = 1022;
  		$codtipo = 29;
  		$sql = "select * from proprietario where j01_matric = $matric";

  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

  	  if (isset ($j01_baixa) && $j01_baixa != "") {
        $situinscr           = "Situação da matrícula: MATRÍCULA BAIXADA ";
        $dadosbaixamatricula = "Matricula Baixada em: ".db_formatar($j01_baixa,'d');
      } else {
        $situinscr           = "Situação da matrícula: MATRÍCULA ATIVA ";
      }

      db_sel_instit(null, "db21_usasisagua");
      if($db21_usasisagua == 't') {
        $sSqlEndImovel = "select x01_matric      as j01_matric,
                               j14_nome,
                               x01_numero      as j39_numero,
                               x11_complemento as j39_compl,
                               j13_descr,
                               x01_quadra      as j34_quadra
                          from aguabase
                         inner join ruas       on j14_codigo = x01_codrua
                         inner join bairro     on j13_codi   = x01_codbairro
                          left join aguaconstr on x11_matric = x01_matric
                         where x01_matric = $matric";
        $rSqlEndImovel = db_query($sSqlEndImovel);
        db_fieldsmemory($rSqlEndImovel, 0);
      }

      /**
       * Busca Dados do Cgm validando pela regra
       */
      $iRegraPromitente = $db21_regracgmiptu;
      if( is_numeric( $iRegraPromitente ) ){

        $sSqlBuscaRG = "select *
                          from cgm
                         where z01_numcgm in (select riNumcgm
                                                from fc_busca_envolvidos( true, {$iRegraPromitente}, 'M', {$matric} ) )";
        $rsBuscaRG  = db_query($sSqlBuscaRG);
        db_fieldsmemory($rsBuscaRG, 0);
      }

  	} else	if (isset ($numcgm)) {

  		$codtipodoc = $w13_tipocertidao == '1' ?  1024 : 2024 ;
  		$codtipo = 30;
  		$sql = "select trim(z01_nome) as z01_nome,* from cgm where z01_numcgm = $numcgm";
  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

  	} else if (isset ($inscr)) {

  		$codtipodoc = $w13_tipocertidao == '1' ?  1023 : 2023 ;
  		$codtipo = 31;
  		$sql = "select * from empresa where q02_inscr = $inscr";
  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

  	    if (isset ($q02_dtbaix) && $q02_dtbaix != "") {
        $situinscr         = "Situação do alvará: ALVARÁ BAIXADO ";
        $dadosbaixaempresa = "Alvará Baixado em: ".db_formatar($q02_dtbaix,'d');
      } else {
        $situinscr         = "Situação do alvará: ALVARÁ ATIVO ";
      }

      $sql2 = " select q07_inscr,
                       q07_perman,
                       min(q07_datain) as q07_datain,
                       max(q07_datafi) as q07_datafi
                  from tabativ
                 where q07_inscr = {$inscr}
                   and q07_perman = false
              group by q07_inscr, q07_perman ";
      $result2 = db_query($sql2);

      if (pg_num_rows($result2) > 0) {
        db_fieldsmemory($result2, 0);
        $dadosalvaraprovisorio = "Alvará Provisório Válido entre: (".db_formatar($q07_datain,'d')." e ".db_formatar($q07_datafi,'d').")";
      }

      if(!isset($z01_numcgm)){
        $z01_numcgm = $q02_numcgm;
      }
  	}
  } else {
  	// certidao regular
  	$tipocer = "CERTIDÃO POSITIVA COM EFEITO DE NEGATIVA";
  	if (isset ($matric)) {

  		$codtipo = 32;
  		$codtipodoc = $w13_tipocertidao == '1' ?  1025 : 2025 ;
  		$sql = "select * from proprietario where j01_matric = $matric";
  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

  	  if (isset ($j01_baixa) && $j01_baixa != "") {
        $situinscr           = "Situação da matrícula: MATRÍCULA BAIXADA ";
        $dadosbaixamatricula = "Matricula Baixada em: ".db_formatar($j01_baixa,'d');
      } else {
        $situinscr           = "Situação da matrícula: MATRÍCULA ATIVA ";
      }

      db_sel_instit(null, "db21_usasisagua");
      if($db21_usasisagua == 't') {
        $sSqlEndImovel = "select x01_matric      as j01_matric,
                               j14_nome,
                               x01_numero      as j39_numero,
                               x11_complemento as j39_compl,
                               j13_descr,
                               x01_quadra      as j34_quadra
                          from aguabase
                         inner join ruas       on j14_codigo = x01_codrua
                         inner join bairro     on j13_codi   = x01_codbairro
                          left join aguaconstr on x11_matric = x01_matric
                         where x01_matric = $matric";
        $rSqlEndImovel = db_query($sSqlEndImovel);
        db_fieldsmemory($rSqlEndImovel, 0);
      }

  	} else	if (isset ($numcgm)) {

  		$codtipodoc = $w13_tipocertidao == '1' ?  1027 : 2027 ;
  		$codtipo = 33;
  		$sql = "select trim(z01_nome) as z01_nome,* from cgm where z01_numcgm = $numcgm";
  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

  	} else	if (isset ($inscr)) {

  		$codtipo = 34;
  		$codtipodoc = $w13_tipocertidao == '1' ?  1026 : 2026 ;
  		$sql = "select * from empresa where q02_inscr = $inscr";
  		$result = db_query($sql);
  		db_fieldsmemory($result, 0);

      if (isset ($q02_dtbaix) && $q02_dtbaix != "") {
        $situinscr         = "Situação do alvará: ALVARÁ BAIXADO ";
        $dadosbaixaempresa = "Alvará Baixado em: ".db_formatar($q02_dtbaix,'d');
      } else {
        $situinscr         = "Situação do alvará: ALVARÁ ATIVO ";
      }

      $sql2 = " select q07_inscr,
                       q07_perman,
                       min(q07_datain) as q07_datain,
                       max(q07_datafi) as q07_datafi
                  from tabativ
                 where q07_inscr = {$inscr}
                   and q07_perman = false
             group by  q07_inscr, q07_perman ";

      $result2 = db_query($sql2);

      if (pg_num_rows($result2) > 0) {
        db_fieldsmemory($result2, 0);
        $dadosalvaraprovisorio = "Alvará Provisório Válido entre: (".db_formatar($q07_datain,'d')." e ".db_formatar($q07_datafi,'d').")";
      }

      if(!isset($z01_numcgm)){
        $z01_numcgm = $q02_numcgm;
      }
  	}
  }

  /**
   * GERA PDF
   */
  $cl_cgm = new cl_cgm;

  if (isset($z01_numcgm) || @$z01_numcgm != null || @$z01_numcgm != '' ) {

    $sSqlIdent = $cl_cgm->sql_query("","*","","z01_numcgm = $z01_numcgm");
  } else {

    $sSqlIdent = $cl_cgm->sql_query("","*","","z01_cgccpf = '$z01_cgccpf'");
  }
  //die($sSqlIdent);
  $rsIdent   = $cl_cgm->sql_record($sSqlIdent);
  if ($cl_cgm->numrows > 0) {
   db_fieldsmemory($rsIdent, 0);
  }
  $z01_cgmpri = $z01_numcgm;

  $sqlDbconfig = "select * from db_config where codigo = ".db_getsession('DB_instit');
  $rsDbconfig = db_query($sqlDbconfig);
  db_fieldsmemory($rsDbconfig, 0);

  /**
   *  Criada este select apenas para corrigir o nome na identificação do contribuinte
   *  de acordo com a configuração encontrada no campo (db21_regracgmiptu) na tabela db_config
   *
   *  Isso faz com que não aparece o nome do proprietário e sim o nome do promitente.
   */
  if ( $db21_regracgmiptu == 2 && isset($matric)) {

    $sSqlNomePromitente = "select * from proprietario where j01_matric = $matric";
    $rsNomePromitente   = db_query($sSqlNomePromitente);
    db_fieldsmemory($rsNomePromitente, 0);
  }

  if (isset ($cadrecibo) && $cadrecibo == 't') {
  	$pdf = new scpdf(); // abre a classe
  } else {
  	$pdf = new PDF1(); // abre a classe
  }

  $sqlparag  = "select db02_texto                                                          ";
  $sqlparag .= "   from db_documento                                                       ";
  $sqlparag .= "        inner join db_docparag on db03_docum = db04_docum                  ";
  $sqlparag .= "	      inner join db_tipodoc on db08_codigo  = db03_tipodoc               ";
  $sqlparag .= "        inner join db_paragrafo on db04_idparag = db02_idparag             ";
  $sqlparag .= "  where db03_tipodoc = 1017                                                ";
  $sqlparag .= "    and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";

  $resparag = db_query($sqlparag);

  if ( pg_numrows($resparag) != 0 ) {

    db_fieldsmemory( $resparag, 0 );
    $head1 = $db02_texto;
  }

  $pdf->Open(); // abre o relatorio
  $pdf->AliasNbPages(); // gera alias para as paginas
  $pdf->AddPage(); // adiciona uma pagina
  $pdf->SetAutoPageBreak('on', 0);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFillColor(255);
  if (isset ($cadrecibo) && $cadrecibo == 't') {
  	$pdf->settopmargin(1);
  	$pdf->SetFont('Arial', 'B', 12);
  	$pdf->Image('imagens/files/Brasao.png', 20, 10, 15);
  	$pdf->sety(15);
  	$pdf->setfont('Arial', 'B', 18);
  	$pdf->Multicell(0, 8, $nomeinst, 0, "C", 0); // prefeitura
  }
  $y = $pdf->gety();
  $pdf->sety($y);
  $result  = $cldb_docparag->sql_record($cldb_docparag->sql_query("","","db_docparag.*,db02_texto,db02_descr,db02_espaca,db02_alinha,db02_inicia","db04_ordem"," db03_tipodoc = $codtipodoc "));
  $numrows = $cldb_docparag->numrows;

  if ($numrows==0){
  	throw new Exception("Documento não configurado.");
  }
  $logofundo = substr($logo,0,strpos($logo,"."));
  /*   F U N D O   D O   D O C U M E N T O  */
  if (file_exists('imagens/files/Brasaocnd.jpg')){
  	$pdf->Image('imagens/files/Brasaocnd.jpg',60,80,100);
  }
  $nome="";
  $result_usu=$cldb_usuarios->sql_record($cldb_usuarios->sql_query(db_getsession("DB_id_usuario"),"nome"));
  if ($cldb_usuarios->numrows>0){
  	db_fieldsmemory($result_usu,0);
  }
    $data = date("Y-m-d",db_getsession("DB_datausu"));
    $data = split('-',$data);
    $dia  = $data[2];
    $mes  = $data[1];
    $ano  = $data[0];
    $mes  = db_mes($mes);
    $data = " $dia de $mes de $ano ";

    $nome="";

  $numer = "";
  if ( $w13_tipocodigocertidao != 0 ) {

  	$numer = $codimpresso != 0 ? " Nº $codimpresso " : "";
  }

  $pdf->SetFont('Arial','b',13);
  $pdf->cell(0,5,$tipocer.$numer,0,1,"C",0);
  $pdf->ln();
  for ($i=0; $i < $numrows; $i++) {

     db_fieldsmemory($result,$i);
     if ($db02_descr=='CODIGO PHP') {
     	   eval($db02_texto);
     } else {

  	   $pdf->SetFont('Arial','',12);
  	   $pdf->SetX($db02_alinha);
  	   $texto=db_geratexto($db02_texto);
  	   $pdf->SetFont('Arial','',12);
  	   $pdf->cell(15,6,"",0,0,"R",0);
  	   $pdf->MultiCell("0",4+$db02_espaca,$texto,"0","J",0,$db02_inicia+0);
  	   $pdf->cell(0,6,"",0,1,"R",0);
     }
  }

  $pdf->SetX(@ $x +80);
  $y = $pdf->GetY();
  $x = $pdf->GetX();
  $pdf->SetXY($x +80, $y +10);

  //****************************************   	FIM PDF   ******************************************************//

  /************************************   R O D A P E (recibo)   D A   C N D  *******************************************************/
  if (isset ($cadrecibo) && $cadrecibo == 't') {
  	$y = $pdf->w - 20;
  } else {
  	$y = $pdf->GetY() - 20;
  }

  if (isset ($cadrecibo) && $cadrecibo == 't') {
  	$dtimp = date("Y-m-d", db_getsession('DB_datausu'));
  	$y = $pdf->w - 28;
  	$x = $pdf->GetX();
  	$pdf->SetXY($x, $y +3);
  	$pdf->RoundedRect(5, $y +36, 80, 28, '', '1234');
  	$pdf->Ln(17);
  	$TamLetra = 7;
  	$alt = 4;
  	$b = 0;
  	$rsRecibo   = db_query("select * from recibo inner join tabrec on k00_receit = k02_codigo where k00_numpre = $k03_numpre");
  	$intNumrows = pg_numrows($rsRecibo);

  	if ($intNumrows == 0) {
      throw new Exception("Recibo não cadastrado");
  	}

  	$valortotal = 0;
  	for ($ii = 0; $ii < $intNumrows; $ii ++) {
  		db_fieldsmemory($rsRecibo, $ii);
  		if ($ii == 0) {
  			$taxa1 = $k02_drecei;
  			$valor1 = $k00_valor;
  		}
  		if ($ii == 1) {
  			$taxa2 = $k02_drecei;
  			$valor2 = $k00_valor;
  		}
  		if ($ii == 2) {
  			$taxa3 = $k02_drecei;
  			$valor3 = $k00_valor;
  		}
  		$valortotal += $k00_valor;
  	}

  	//*******************************************************************************************************************//

  	$y = $pdf->GetY();
  	$x = $pdf->GetX();
  	$pdf->SetXY($x, $y +18);
  	$pdf->SetFont('Arial', 'B', $TamLetra -2);
  	$pdf->cell(20, 3, "$titulo", $b, 0, "L", 0); //cgm matricula ou inscricao
  	$pdf->cell(20, 3, "Dt impr.", $b, 0, "L", 0);
  	$pdf->cell(20, 3, "Dt Venc", $b, 0, "L", 0);
  	$pdf->cell(20, 3, "", $b, 1, "L", 0);

  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->SetFont('Arial', '', $TamLetra);

  	$pdf->SetFont('Arial', '', $TamLetra);
  	$pdf->cell(20, $alt, "$origem", $b, 0, "L", 0); //cgm matricula ou inscricao
  	$pdf->cell(20, $alt, db_formatar($dtimp, "d"), $b, 0, "L", 0);
  	$pdf->cell(20, $alt, db_formatar($k00_dtvenc, "d"), $b, 0, "L", 0);

  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->cell(20, $alt, "Valor", $b, 0, "C", 0);
  	$pdf->SetFont('Arial', 'B', $TamLetra +1);
  	$pdf->cell(110, $alt, "DOCUMENTO VÁLIDO SOMENTE APOS AUTENTICAÇÃO MECANICA ", $b, 1, "C", 0);

  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->SetFont('Arial', '', $TamLetra -1);

  	if (isset ($taxa1) && $taxa1 != "") {
  		$pdf->cell(60, $alt, "$taxa1", "B", 0, "L", 0);
  		$pdf->cell(20, $alt, "$valor1", $b, 0, "C", 0);
  		$pdf->SetFont('Arial', 'B', $TamLetra +1);
  		$pdf->cell(110, $alt, "OU COMPROVANTE DE QUITAÇÃO", $b, 1, "C", 0);
  	} else {
  		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
  		$pdf->cell(20, $alt, "", $b, 0, "C", 0);
  		$pdf->cell(110, $alt, "", $b, 1, "C", 0);
  	}

  	$pdf->SetFont('Arial', '', $TamLetra -1);

  	if (isset ($taxa2) && $taxa2 != "") {
  		$pdf->cell(60, $alt, "$taxa2", "B", 0, "L", 0);
  		$pdf->cell(20, $alt, "$valor2", $b, 0, "C", 0);
  	} else {
  		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
  		$pdf->cell(20, $alt, "", $b, 0, "C", 0);
  	}

  	$pdf->SetFont('Arial', 'B', $TamLetra +1);
  	$pdf->cell(110, $alt, " A U T E N T I C A Ç Ã O   M E C Â N I C A ", $b, 1, "C", 0);

  	$pdf->SetFont('Arial', '', $TamLetra -1);
  	if (isset ($taxa3) && $taxa3 != "") {
  		$pdf->cell(60, $alt, "$taxa3", "B", 0, "L", 0);
  		$pdf->cell(20, $alt, "$valor3", $b, 1, "C", 0);
  	} else {
  		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
  		$pdf->cell(20, $alt, "", $b, 1, "C", 0);
  	}

  	$pdf->SetFont('Arial', 'B', $TamLetra -1);
  	$pdf->cell(60, $alt, "Valor Total : ", $b, 0, "R", 0);
  	$pdf->cell(20, $alt, "$valortotal", $b, 1, "C", 0);

  	$y = $pdf->GetY();
  	$x = $pdf->GetX();
  	$pdf->SetXY($x, $y +10);

  	/******************************************************************************************************************************************/

  	$pdf->RoundedRect(5, $y +9, 200, 41, 0, '', '1234');

  	$pdf->SetFont('Arial', 'B', $TamLetra -2);
  	$pdf->cell(110, 3, "", $b, 0, "L", 0);
  	$pdf->cell(20, 3, "$titulo", $b, 0, "L", 0); //cgm matricula ou inscricao
  	$pdf->cell(20, 3, "Dt impr.", $b, 0, "L", 0);
  	$pdf->cell(20, 3, "Dt Venc", $b, 0, "L", 0);
  	$pdf->cell(20, 3, "", $b, 1, "L", 0);

  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->cell(40, $alt, "CONTRIBUINTE: ", $b, 0, "L", 0);
  	$pdf->SetFont('Arial', '', $TamLetra);
  	$pdf->cell(70, $alt, @ $z01_nome, $b, 0, "L", 0);

  	$pdf->SetFont('Arial', '', $TamLetra);
  	$pdf->cell(20, $alt, "$origem", $b, 0, "L", 0); //cgm matricula ou inscricao
  	$pdf->cell(20, $alt, db_formatar($dtimp, "d"), $b, 0, "L", 0);
  	$pdf->cell(20, $alt, db_formatar($k00_dtvenc, "d"), $b, 0, "L", 0);

  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->cell(20, $alt, "Valor", $b, 1, "C", 0);

  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->cell(40, $alt, "ENDEREÇO: ", $b, 0, "L", 0);
  	$pdf->SetFont('Arial', '', $TamLetra);
  	$pdf->cell(70, $alt, trim(@ $z01_ender).", ".trim(@ $z01_numero)."  ".trim(@ $z01_compl), $b, 0, "L", 0);

  	$pdf->SetFont('Arial', '', $TamLetra -1);
  	if (isset ($taxa1) && $taxa1 != "") {
  		$pdf->cell(60, $alt, "$taxa1", "B", 0, "L", 0);
  		$pdf->cell(20, $alt, "$valor1", $b, 1, "C", 0);
  	} else {
  		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
  		$pdf->cell(20, $alt, "", $b, 1, "C", 0);
  	}

  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->cell(40, $alt, "MUNICIPIO: ", $b, 0, "L", 0);
  	$pdf->SetFont('Arial', '', $TamLetra);
  	$pdf->cell(70, $alt, @ $z01_munic."/".@ $z01_uf." - ".substr(@ $z01_cep, 0, 5)."-".substr(@ $z01_cep, $alt, 3), $b, 0, "L", 0);

  	$pdf->SetFont('Arial', '', $TamLetra -1);
  	if (isset ($taxa2) && $taxa2 != "") {
  		$pdf->cell(60, $alt, "$taxa2", "B", 0, "L", 0);
  		$pdf->cell(20, $alt, "$valor2", $b, 1, "C", 0);
  	} else {
  		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
  		$pdf->cell(20, $alt, "", $b, 1, "C", 0);
  	}

  	$pdf->cell(40, $alt, "", $b, 0, "L", 0);
  	$pdf->cell(70, $alt, "", $b, 0, "L", 0);

  	$pdf->SetFont('Arial', '', $TamLetra -1);
  	if (isset ($taxa3) && $taxa3 != "") {
  		$pdf->cell(60, $alt, "$taxa3", "B", 0, "L", 0);
  		$pdf->cell(20, $alt, "$valor3", $b, 1, "C", 0);
  	} else {
  		$pdf->cell(60, $alt, "", $b, 0, "L", 0);
  		$pdf->cell(20, $alt, "", $b, 1, "C", 0);
  	}

  	$pdf->cell(40, $alt, "", $b, 0, "L", 0);
  	$pdf->cell(70, $alt, "", $b, 0, "L", 0);
  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->cell(60, $alt, "Valor Total : ", $b, 0, "R", 0);
  	$pdf->cell(20, $alt, "$valortotal", $b, 1, "C", 0);

  	$pdf->SetFont('Arial', '', $TamLetra +1);
  	$pdf->cell(110, $alt, "$linhadigitavel", $b, 0, "C", 0);
  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->cell(80, $alt, "", 0, 1, "C", 0);

  	$pdf->cell(40, $alt, "", $b, 0, "L", 0);
  	$pdf->cell(70, $alt, "", $b, 0, "L", 0);
  	$pdf->SetFont('Arial', 'B', $TamLetra);
  	$pdf->cell(80, $alt, " A U T E N T I C A Ç Ã O   M E C Â N I C A  ", 0, 1, "C", 0);

  	$y = $pdf->GetY();
  	$x = $pdf->GetX();
  	$pdf->SetXY($x, $y);

  	$pdf->SetFillColor(000);
  	$pdf->int25($x, $y -4, $codigobarras, 13, 0.341);
  }

  $pdf->Sety(252);
  $y = $pdf->GetY();
  $pdf->MultiCell(90, 5, '', 0, "C", 0);
  $pdf->SetFillColor(000);
  $pdf->MultiCell(180, 3, "Código de Autenticidade da Certidão", 0, "R", 0);
  $pdf->MultiCell(180, 10, $t1, 0, "R", 0);
  $pdf->int25(95, 270, $t1, 15, 0.341);

  $nomearq = $pdf->GeraArquivoTemp();
  $arqpdf =  $nomearq;
  $pdf->Output($arqpdf, false, true);


  db_inicio_transacao();
  $localrecebeanexo = $arqpdf;
  // Para ler conteudo do arquivo pdf gerado
  if ( trim($localrecebeanexo) != "") {

  	$arquivograva = fopen($localrecebeanexo, "rb");
  	$dados = fread($arquivograva, filesize($localrecebeanexo));
  	fclose($arquivograva);
  	$oidgrava = pg_lo_create();

  	if (isset ($titulo) && $titulo == 'CGM') {
  		$sqlcgm="select z01_nome from cgm where z01_numcgm = $origem";
  	}elseif(isset ($titulo) && $titulo == 'INSCRICAO') {
  		$sqlcgm="select z01_nome from cgm inner join issbase on q02_numcgm= z01_numcgm where q02_inscr = $origem";
  	}elseif(isset ($titulo) && $titulo == 'MATRICULA') {
  		$sqlcgm="select z01_nome from cgm inner join iptubase on j01_numcgm = z01_numcgm where j01_matric = $origem";
  	}
  	$resultcgm = db_query($sqlcgm);
  	$linhascgm = pg_num_rows($resultcgm);
  	if ($linhascgm > 0){
  		db_fieldsmemory($resultcgm, 0);
  	}

    if ($tipo == 1) {
      $clcertidao->p50_tipo = "p";
    } else if ($tipo == 2) {
      $clcertidao->p50_tipo = "n";
    } else {
      $clcertidao->p50_tipo = "r";
    }
    $id_usu = @$_SESSION["id"];
    if( $id_usu == "" ){
      $id_usu = "1";
    }

    $clcertidao->p50_idusuario   = $id_usu;
    $clcertidao->p50_data        = date("Y-m-d", db_getsession('DB_datausu'));
    $clcertidao->p50_hora        = db_hora();
    $clcertidao->p50_ip          = $ip;
    if (isset ($historico) && $historico != "") {
      $clcertidao->p50_hist      = $historico. ($codproc != '' ? ", processo N".chr(176).": ".$codproc : '');
    } else {
      $clcertidao->p50_hist      = " ". ($codproc != '' ? "Processo N".chr(176).": ".$codproc : '');
    }

    $clcertidao->p50_web         = 'true';
    $clcertidao->p50_codproc     = $codproc;
    $clcertidao->p50_exerc       = $exercicio;
    $clcertidao->p50_codimpresso = '';
    $clcertidao->p50_instit      = db_getsession("DB_instit");
    $clcertidao->p50_arquivo     = $oidgrava;

    /**
     * Adicionamos campo para armazenar o dias de validade da certidão de acordo com
     * o parametro que estava setado quando emitida
     */
    $sSql         = $clnumpref->sql_query_file ( $iAnoUsu, $iInstit, "k03_diasvalidadecertidao" );
    $rsResultados = $clnumpref->sql_record( $sSql );
    if ( pg_num_rows($rsResultados) > 0 ){

      db_fieldsmemory($rsResultados,0);

      if( isset($k03_diasvalidadecertidao) ){
        $clcertidao->p50_diasvalidade = $k03_diasvalidadecertidao;
      }
    }

    $clcertidao->incluir(null);

    if ($clcertidao->erro_status == '0') {

      $erro_msg = $clcertidao->erro_msg."--- Inclusão Certidão";
      throw new Exception($erro_msg);
    }

    if (isset ($iNumcgm)) {

      $clcertidaocgm->p49_sequencial = $clcertidao->p50_sequencial;
      $clcertidaocgm->p49_numcgm     = $iNumcgm;
      $clcertidaocgm->incluir();

      if ($clcertidaocgm->erro_status == '0') {

        $erro_msg = $clcertidaocgm->erro_msg."--- Inclusão Certidão CGM";
        throw new Exception($erro_msg);
      }
    }

    if (isset ($iMatriula)) {

      $clcertidaomatric->p47_sequencial = $clcertidao->p50_sequencial;
      $clcertidaomatric->p47_matric     = $iMatriula;
      $clcertidaomatric->incluir();

      if ($clcertidaomatric->erro_status == '0') {

        $erro_msg = $clcertidaomatric->erro_msg."--- Inclusão Certidão Matricula";
        throw new Exception($erro_msg);
      }
    }

    if (isset ($iInscricao)) {

      $clcertidaoinscr->p48_sequencial = $clcertidao->p50_sequencial;
      $clcertidaoinscr->p48_inscr      = $iInscricao;
      $clcertidaoinscr->incluir();

      if ($clcertidaoinscr->erro_status == '0') {

        $erro_msg = $clcertidaoinscr->erro_msg."--- Inclusão Certidão Inscrição";
        throw new Exception($erro_msg);
      }
    }

    /*
     * Verificamos qual o parâmetro configurado para a numeração da certidão
     */
    if ($w13_tipocodigocertidao != 0) {

      $iTipoCodigo        = $w13_tipocodigocertidao;
      $sTipoCertidao      = $clcertidao->p50_tipo;

      $rsCodigoECidade = $clnumpref->sql_record($clnumpref->sql_query(db_getsession("DB_anousu"),db_getsession('DB_instit'),"k03_tipocodcert"));
      db_fieldsmemory($rsCodigoECidade,0);
      $iCodigoECidade  = $k03_tipocodcert;

      if ($iCodigoECidade == 5 && $iTipoCodigo == 3) {
        $codimpresso = 0;
      } else {

        $sSqlNumeroCertidao = "select fc_numerocertidao($iInstit,$iTipoCodigo,'{$sTipoCertidao}', true) ";
        $codimpresso        = pg_result(db_query("$sSqlNumeroCertidao"),0);
      }

      $clcertidaoalt = new cl_certidao;
      $clcertidaoalt->p50_sequencial = $clcertidao->p50_sequencial;
      $clcertidaoalt->p50_codimpresso = $codimpresso;
      $clcertidaoalt->alterar($clcertidao->p50_sequencial);

      if ($clcertidaoalt->erro_status == '0') {

        $erro_msg = $clcertidaoalt->erro_msg."--- Inclusão do código do processo de impressão";
        throw new Exception($erro_msg);
      }

      // linha incluida para atualizar a classe clcertidao com o codigo a ser impresso pois abaixo o programa trata somente a clcertidao
      $clcertidao->p50_codimpresso = $clcertidaoalt->p50_codimpresso;
    }

  	$clcertidaoweb->codcert      = $clcertidao->p50_sequencial;
  	$clcertidaoweb->tipocer      = $tipo;
  	$clcertidaoweb->cerdtemite   = $ano . "-" . $mes1 . "-" . $dia;
  	$clcertidaoweb->cerhora      = $hora . ":" . $min . ":" . $sec;
  	$clcertidaoweb->cerdtvenc    = $venc;
  	$clcertidaoweb->cerip        = $ip;
  	$clcertidaoweb->ceracesso    = $t1;
  	$clcertidaoweb->cercertidao  = $oidgrava;
  	$clcertidaoweb->cernomecontr = addslashes($z01_nome);
  	$clcertidaoweb->cerhtml      = "x";
  	$clcertidaoweb->cerweb       = 'true';
  	$clcertidaoweb->incluir();

  	$erro_msg = $clcertidaoweb->erro_msg;

  	if ($clcertidaoweb->erro_status == 0) {
      throw new Exception("Erro ao gerar certidao. ERRO: " . $erro_msg);
  	}

  	$objeto = pg_lo_open($conn, $oidgrava, "w");
  	if ($objeto != false) {

  		$erro = pg_lo_write($objeto, $dados);
  		pg_lo_close($objeto);
  	} else {
  		throw new Exception("Erro ao salvar dados do arquivo da Certidão na base de dados");
    }
  }

  db_fim_transacao(false);

  if (file_exists($arqpdf)) {

      header('Content-Description: File Transfer');
      header('Content-Type: application/pdf');
      header('Content-Disposition: attachment; filename='.$arqpdf);
      header('Content-Length: ' . filesize($arqpdf));
      readfile($arqpdf);
  }

} catch ( Exception $oErro ) {

  if ( db_utils::inTransaction() ) {
    db_fim_transacao(true);
  }

  db_redireciona("db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
  exit;
}

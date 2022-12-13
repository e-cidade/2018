<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("fpdf151/pdf1.php");
require_once("classes/db_liccomissaocgm_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_pcfornecertifrenovacao_classe.php");
require_once("classes/db_pcfornecertif_classe.php");
require_once("classes/db_pctipodoccertif_classe.php");
require_once("classes/db_pcfornecertifdoc_classe.php");
require_once("classes/db_pcfornesubgrupo_classe.php");
require_once("classes/db_pctipocertifcom_classe.php");
require_once("classes/db_pctipocertif_classe.php");
require_once("libs/db_libdocumento.php");

$clpcfornecertif    = new cl_pcfornecertif;
$clpctipodoccertif  = new cl_pctipodoccertif;
$clpcfornecertifdoc = new cl_pcfornecertifdoc;
$clpcfornesubgrupo  = new cl_pcfornesubgrupo;
$cldb_config        = new cl_db_config;
$clliccomissaocgm   = new cl_liccomissaocgm;
$clpctipocertifcom  = new cl_pctipocertifcom;
$clpcparam          = new cl_pcparam;
$clpctipocertif     = new cl_pctipocertif;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$sWhere       = "";
$sAnd         = "";
$iInstituicao = db_getsession("DB_instit");

if (isset($forne) && $forne != "") {

  $sWhere .= " {$sAnd} pc74_pcforne = {$forne} ";
  $sAnd    = " and ";
}

if (isset($codigo) && $codigo != "") {

  $sWhere .= " {$sAnd} pc74_codigo = {$codigo} ";
  $sAnd    = " and ";
} else if (isset($dtInicio) && $dtInicio != "" && isset($dtFim) && $dtFim != "") {

  $sWhere .= " {$sAnd} pc74_data between '{$dtInicio}' and '{$dtFim}' ";
  $sAnd    = " and ";
}

$sSqlPcForneCertif = $clpcfornecertif->sql_query(null, "*", 'pc74_codigo', $sWhere);
$result            = $clpcfornecertif->sql_record($sSqlPcForneCertif);
if ($clpcfornecertif->numrows == 0) {

  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');
  exit;
}

$sSqlPcParam = $clpcparam->sql_query_file($iInstituicao, "pc30_comobs");
$res_param   = $clpcparam->sql_record($sSqlPcParam);
if ($clpcparam->numrows > 0) {
  db_fieldsmemory($res_param, 0);
}

$pdf = new PDF1();
$pdf->Open();
$pdf->AliasNbPages();
$alt = 5;

$retorno_numrows = $clpcfornecertif->numrows;
for ($z = 0; $z < $retorno_numrows; $z++) {

  db_fieldsmemory($result, $z);

  $sSqlPcTipoCertif  = $clpctipocertif->sql_query_file($pc70_codigo, "*", null, "");
  $rsSqlPcTipoCertif = $clpctipocertif->sql_record($sSqlPcTipoCertif);
  if ($clpctipocertif->numrows == 0) {

    db_redireciona('db_erros.php?fechar=true&db_erro=Não existe um tipo de documento cadastrado.');
    exit;
  }

  $oPcTipoCertif = db_utils::fieldsMemory($rsSqlPcTipoCertif, 0);

  $oLibDocumento = new libdocumento($oPcTipoCertif->pc70_tipodoc);
  $oLibDocumento->numeroregistrocadastral = $pc74_codigo;

  $aParagrafos  = $oLibDocumento->getDocParagrafos();

  $sTitulo      = "CERTIFICADO DE REGISTRO CADASTRAL N° {$pc74_codigo}";
  $sDescrcaoCGM = "Fornecedor: ";
  foreach ($aParagrafos as $oParagrafo) {

    if (!$oParagrafo instanceof stdClass) {
      continue;
    }

    if (trim($oParagrafo->oParag->db02_descr) == 'TITULO') {
      $sTitulo = nl2br($oLibDocumento->replaceText($oParagrafo->oParag->db02_texto));
    }

    if (trim($oParagrafo->oParag->db02_descr) == 'TIPO_CGM') {
    	$sDescrcaoCGM = nl2br($oLibDocumento->replaceText($oParagrafo->oParag->db02_texto));
    }
  }

  $pdf->AddPage("P");
  $pdf->SetAutoPageBreak(true, 30);
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', 'b', 8);
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFillColor(220);
  $pdf->SetXY('10', '40');

  $pdf->SetFont('Arial', 'b', 14);
  $pdf->cell(0, 1, $sTitulo, 0, 1, "C", 0);
  $pdf->cell(0, 5, "", 0, 1, "R", 0);

  if (isset($pc70_obs) && $pc70_obs != "") {

    $pdf->setfont('arial', 'b', 8);
    $pdf->multicell(0,$alt,trim($pc70_obs),0, "J", 0);
    $pdf->ln();
  }

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(35, $alt, $sDescrcaoCGM, 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(145, $alt, $pc60_numcgm."-", 1, 0, "L", 0);

  $pdf->setx(57);
  $pdf->setfont('arial', 'b', 10);
  $pdf->cell(50, $alt, $z01_nome, 0, 1, "L", 0);

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(35, $alt, 'Endereço', 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(60, $alt,$z01_ender.", ".$z01_numero."/".$z01_compl, 1, 0, "L", 0);
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Bairro :', 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(55, $alt, $z01_bairro, 1, 1, "L", 0);

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(35, $alt, 'Cidade :', 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(60, $alt, $z01_munic, 1, 0, "L", 0);
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Estado :', 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(55, $alt, $z01_uf, 1, 1, "L", 0);

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(35, $alt, 'Cep :', 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(60, $alt, $z01_cep, 1, 0, "L", 0);
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'CNPJ :', 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(55, $alt,$z01_cgccpf, 1, 1, "L", 0);

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(35, $alt, 'Telefone Comercial :', 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(60, $alt, $z01_telcon, 1, 0, "L", 0);
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'E-mail Comercial:', 1, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(55, $alt,$z01_emailc, 1, 1, "L", 0);

  $pdf->ln();
  if (isset($oSocial) && $oSocial == 0) {

    if ($pc60_obs != "") {

      $pdf->setfont('arial', 'b', 10);
      $pdf->cell(0, 8, 'OBJETO SOCIAL DA EMPRESA: ', 0, 0, "L", 0);
      $pdf->cell(0, 8, "", 0, 1, "R", 0);
      $pdf->setfont('arial', 'b', 8);
      $pdf->multicell(180,$alt,@$pc60_obs,1, "L", 0);
      $pdf->ln();
    }
  }

  $sWhere              = "pc72_pctipocertif = {$pc74_pctipocertif}";
  $sSqlPcTipoDocCertif = $clpctipodoccertif->sql_query(null, "*", "pcdoccertif.pc71_codigo", $sWhere);
  $result_doc          = $clpctipodoccertif->sql_record($sSqlPcTipoDocCertif);

  if ($pc30_comobs == "t") {

    $tam = 52;
    $br  = 0;
  } else {

    $br  = 1;
    $tam = 112;
  }

  $pdf->SetAutoPageBreak(false);

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell($tam+10, $alt, "DESCRIÇÃO DOS DOCUMENTOS", 1, 0, "C", 0);
  $pdf->cell(20,  $alt, "EMISSÂO", 1, 0, "C", 0);
  $pdf->cell(20,  $alt, "VALIDADE", 1, 0, "C", 0);
  $pdf->cell(18,  $alt, "AP *", 1, $br, "C", 0);

  if ($pc30_comobs == "t") {
    $pdf->cell(60, $alt, "OBSERVAÇÃO", 1, 1, "C", 0);
  }

  $pdf->setfont('arial', '', 7);
  for ($w = 0; $w < $clpctipodoccertif->numrows; $w++) {

    $pdf->SetWidths(array($tam+10, 20, 20, 18, 60));
    $pdf->SetAligns(array('L', 'C', 'C', 'C', 'L'));

    db_fieldsmemory($result_doc, $w);

    $sDescricao = $pc72_pcdoccertif."-".$pc71_descr;

    $sWhere     = "pc75_pcfornecertif = {$pc74_codigo} and pc75_pcdoccertif = {$pc72_pcdoccertif}";
    $sSql       = $clpcfornecertifdoc->sql_query_file(null, "*", "", $sWhere);
    $result_df  = $clpcfornecertifdoc->sql_record($sSql);
    if ($clpcfornecertifdoc->numrows > 0) {

      db_fieldsmemory($result_df, 0);

      if ($pc75_validade == "") {
        $sValidade = "";
      } else {
        $sValidade = db_formatar($pc75_validade, 'd');
      }
    } else {
      $sValidade = "Não apresentado";
    }

    $sEmissao = isset($pc75_dataemissao) && $pc75_dataemissao != "" ? db_formatar($pc75_dataemissao, 'd') : "";

    if (isset($pc75_apresentado)) {
      $sApresentado = ($pc75_apresentado == 1 ? "SIM" : ($pc75_apresentado == 2 ? "NÃO" : ""));
    } else {
      $sApresentado = "NÂO";
    }

    if ($pc30_comobs == "t") {

      $sObservacao = (strlen($pc75_obs) < 255 ? $pc75_obs : substr($pc75_obs, 0, 252).'...');
      $aDados      = array($sDescricao, $sEmissao, $sValidade, $sApresentado, $sObservacao);
    } else {
      $aDados      = array($sDescricao, $sEmissao, $sValidade, $sApresentado);
    }

    $pdf->Row($aDados, $alt, true, 5, 0, true);
    mudaPagina($pdf, $pdf->PageNo());
  }

  $pdf->setfont('arial', 'b', 6);
  $pdf->cell(180, $alt, "* Informação de que o documento foi apresentado.", 0, 1, "L", 0);

  $pdf->setfont('arial', '', 7);
  $pdf->SetAutoPageBreak(true, 30);

  if (isset($gForn) && $gForn == 0) {

  	$sWhere     = "pc76_pcforne = {$pc60_numcgm}";
    $result_sub = $clpcfornesubgrupo->sql_record($clpcfornesubgrupo->sql_query(null, "*", null, $sWhere));
    if ($clpcfornesubgrupo->numrows > 0) {

        $pdf->cell(1, 5,"", 0, 1, "L", 0);
        $pdf->setfont('arial', 'b', 8);
        $pdf->cell(80, $alt,"GRUPOS DE FORNECIMENTO", 1, 1, "C", 0);
        $pdf->setfont('arial', '', 7);

        for ($w = 0; $w < $clpcfornesubgrupo->numrows; $w++) {

          db_fieldsmemory($result_sub,$w);

          if ($pdf->gety() > $pdf->h - 30) {

            $pdf->AddPage("P");
            $pdf->SetXY('10', '40');
            $pdf->SetFont('Arial', 'b', 14);
            $pdf->cell(0, 1, $sTitulo, 0, 1, "C", 0);
            $pdf->cell(0, 5, "", 0, 1, "R", 0);

            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(35, $alt, $sDescrcaoCGM, 1, 0, "R", 0);
            $pdf->setfont('arial', '', 7);
            $pdf->cell(145, $alt, $pc60_numcgm."-", 1, 0, "L", 0);

            $pdf->setx(53);
            $pdf->setfont('arial', 'b', 10);
            $pdf->cell(50, $alt, $z01_nome, 0, 1, "L", 0);

            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(35, $alt, 'Endereço', 1, 0, "R", 0);
            $pdf->setfont('arial', '', 7);
            $pdf->cell(60, $alt,$z01_ender.", ".$z01_numero."/".$z01_compl, 1, 0, "L", 0);
            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(30, $alt, 'Bairro :', 1, 0, "R", 0);
            $pdf->setfont('arial', '', 7);
            $pdf->cell(55, $alt, $z01_bairro, 1, 1, "L", 0);

            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(35, $alt, 'Cidade :', 1, 0, "R", 0);
            $pdf->setfont('arial', '', 7);
            $pdf->cell(60, $alt, $z01_munic, 1, 0, "L", 0);
            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(30, $alt, 'Estado :', 1, 0, "R", 0);
            $pdf->setfont('arial', '', 7);
            $pdf->cell(55, $alt, $z01_uf, 1, 1, "L", 0);

            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(35, $alt, 'Cep :', 1, 0, "R", 0);
            $pdf->setfont('arial', '', 7);
            $pdf->cell(60, $alt, $z01_cep, 1, 0, "L", 0);
            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(30, $alt, 'CNPJ :', 1, 0, "R", 0);
            $pdf->setfont('arial', '', 7);
            $pdf->cell(55, $alt,$z01_cgccpf, 1, 1, "L", 0);

            $pdf->cell(1, 5,"", 0, 1, "L", 0);
            $pdf->setfont('arial', 'b', 8);
            $pdf->cell(80, $alt,"GRUPOS DE FORNECIMENTO", 1, 1, "C", 0);
            $pdf->setfont('arial', '', 7);
          }

          $pdf->cell(80, $alt,$pc76_pcsubgrupo."-".$pc04_descrsubgrupo, 1, 1, "L", 0);
        }
    }
  }

  $pdf->SetFont('Arial','b',12);
  $pdf->ln();
  if (isset($pc70_parag2) && $pc70_parag2 != "") {

    $pdf->setfont('arial', 'b', 8);
    $pdf->multicell(0,$alt,@$pc70_parag2,0, "J", 0);
  }

  $numrows_comissao = 0;

  $sWhere           = "pc77_pctipocertif = {$pc74_pctipocertif}";
  $sSqlCertifCom    = $clpctipocertifcom->sql_query(null, "*", null, $sWhere);
  $result_certifcom = $clpctipocertifcom->sql_record($sSqlCertifCom);
  if ($clpctipocertifcom->numrows > 0) {

    db_fieldsmemory($result_certifcom, 0);

    $sWhere           = "l31_liccomissao = {$pc77_liccomissao}";
    $sSqlComissao     = $clliccomissaocgm->sql_query(null, "z01_nome, l31_tipo", null, $sWhere);
    $result_comissao  = $clliccomissaocgm->sql_record($sSqlComissao);
    $numrows_comissao = $clliccomissaocgm->numrows;
    if ($clliccomissaocgm->numrows > 0) {
      db_fieldsmemory($result_comissao, 0);
    }
  }

  $result_munic = $cldb_config->sql_record($cldb_config->sql_query_file());
  if($cldb_config->numrows > 0) {
    db_fieldsmemory($result_munic, 0);
  }

  $pdf->setfont('arial', 'b', 8);
  if (strtoupper($munic) == "BAGE") {

    $pdf->setfont('arial', 'b', 6);
    $pdf->ln(10);
  }

  $dia = date ('d',db_getsession("DB_datausu"));
  $mes = date ('m',db_getsession("DB_datausu"));
  $ano = date ('Y',db_getsession("DB_datausu"));
  $mes = db_mes($mes);

  if (strtoupper($munic) != "BAGE") {
    $pdf->cell(60, 4, "DATA DA INCLUSÃO DO REGISTRO: " . db_formatar($pc74_data, "d"),0,1,"L",0);
  }

  $pdf->cell(60,4,"DATA DE VALIDADE DO CERTIFICADO: " . db_formatar($pc74_validade,"d"),0,1,"L",0);


  $oCertifRenovacao    = new cl_pcfornecertifrenovacao();
  $sSqlCertifRenovacao = $oCertifRenovacao->sql_query(null,"*", "", " pc35_fornecertffilho={$pc74_codigo} ");
  $rsCertifRenovacao   = $oCertifRenovacao->sql_record($sSqlCertifRenovacao);

  if ($oCertifRenovacao->numrows > 0) {

    $oCertifResult = db_utils::fieldsMemory($rsCertifRenovacao, 0);
    $pdf->cell(60,4,"ATUALIZADO A PARTIR DO CERTIFICADO: {$oCertifResult->pc35_pcfornecertiforiginal} EM " . db_formatar($oCertifResult->pc35_datarenovacao,"d"),0,1,"L",0);
  }


  $pdf->ln(10);

  $pdf->cell(175,4,"$munic, $dia de $mes de $ano. ",0,1,"R",0);
  $pdf->ln(20);

  $sqlparag      = "  select db02_descr,                                            ";
  $sqlparag     .= "         db02_texto                                             ";
  $sqlparag     .= "    from db_documento                                           ";
  $sqlparag     .= "        inner join db_docparag  on db03_docum   = db04_docum    ";
  $sqlparag     .= "        inner join db_tipodoc   on db08_codigo  = db03_tipodoc  ";
  $sqlparag     .= "        inner join db_paragrafo on db04_idparag = db02_idparag  ";
  $sqlparag     .= "  where db03_tipodoc = {$oPcTipoCertif->pc70_tipodoc}           ";
  $sqlparag     .= "    and db03_instit = {$iInstituicao}                           ";
  $sqlparag     .= "  order by db04_ordem                                           ";
  $resparag      = db_query($sqlparag);
  $iNumRowsParag = pg_numrows($resparag);

  if ($iNumRowsParag > 0) {
    db_fieldsmemory($resparag, 0);
  }

  $assinatura_diretor = "";
  if ($iNumRowsParag == 0) {
    $assinatura_diretor = "ASSINATURA";
  } else if ($db02_descr == "CODIGO PHP") {
    eval($db02_texto);
  } else if (trim($assinatura_diretor) == "" && trim($db02_texto) == "") {

    $pdf->cell(200, 4, $assinatura_diretor, 0, 1, "C", 0);
    $pdf->cell(200, 4, "DIRETOR(A) DEPARTAMENTO DE COMPRAS", 0, 1, "C", 0);
    $pdf->ln(12);
  } else {

    for ($i = 0; $i < $iNumRowsParag; $i++) {

    	db_fieldsmemory($resparag, $i);

      $assinatura_diretor = $db02_texto;
      $pdf->MultiCell(200, 4, $assinatura_diretor, 0, 'C', 0, 0);
      $pdf->ln(12);
    }
  }

  $conta = 0;
  if ($numrows_comissao != 0) {

    for ($x = 0; $x < $clliccomissaocgm->numrows; $x++) {

      db_fieldsmemory($result_comissao, $x);

      $pdf->cell(90,4,$z01_nome,0,0,"C",0);
      if ($conta++ == 1 or $x == pg_numrows($result_comissao) - 1) {

        $pdf->ln();
        for ($a = 0; $a < $clliccomissaocgm->numrows; $a++) {

          db_fieldsmemory($result_comissao, $a);

          if ($l31_tipo == "P") {
            $pdf->cell(90,4,"PRESIDENTE",0,0,"C",0);
          }

          if ($l31_tipo == "M") {
            $pdf->cell(90,4,"MEMBRO",0,0,"C",0);
          }
        }

        $pdf->ln(12);
        $conta = 0;
      }
    }
  }
}

$pdf->Output();

function mudaPagina($pdf, $iContaPagina) {

    $iPosicaoAtual    = $pdf->gety();
    $iPosicaoAtual   += (2*5);
    $iPosicaoAtual    = (int)$iPosicaoAtual;

    $iTamanhoPagina   = $pdf->h;
    $iTamanhoPagina  -= 58;
    $iTamanhoPagina   = (int)$iTamanhoPagina;

    $iTamanhoMPagina  = $pdf->h;
    $iTamanhoMPagina -= 30;
    $iTamanhoMPagina  = (int)$iTamanhoMPagina;

    if ((($iPosicaoAtual > $iTamanhoPagina) && $iContaPagina == 1 )
    ||  (($iPosicaoAtual > $iTamanhoMPagina) && $iContaPagina != 1)) {

      $pdf->addpage("P");
    }
  }
?>
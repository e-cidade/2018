<?php
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

require_once modification("fpdf151/pdf.php");
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_sql.php");
require_once modification("libs/db_libdocumento.php");

define('NAO_AGRUPAR', 1);
define('AGRUPAR_ORGAO', 2);
define('AGRUPAR_ORGAO_UNIDADE', 3);
define('AGRUPAR_DEPARTAMENTO', 4);
define('AGRUPAR_DEPARTAMENTO_DIVISAO', 5);

$oGet = db_utils::postMemory($_GET);

$lNovaPagina = true;
$lNovoCabecalho = false;
$lClassificacao = ($oGet->classificacao == 1);


$sTituloAgrupador = null;

$iDepartamentoAnterior = 0;
$iOrgaoAnterior        = 0;
$iUnidadeAnterior      = 0;
$iDivisaoAnterior      = 0;

$iTipoAgrupamento = NAO_AGRUPAR;

/**
 * Agrupamento
 */
if ( !empty($oGet->cboAgrupar) ) {
  $iTipoAgrupamento = $oGet->cboAgrupar;
}

$cldepartdiv     = new cl_departdiv;
$cldbdepart      = new cl_db_depart;
$cldbbens        = new cl_bens;
$cldbbensbaix    = new cl_bensbaix;
$cldbclabens     = new cl_clabens;
$cldbbensmater   = new cl_bensmater;
$cldbbensimoveis = new cl_bensimoveis;
$clhistbem       = new cl_histbem;

$clrotulo = new rotulocampo;
$clrotulo->label("t52_bem");
$clrotulo->label("t52_dtaqu");
$clrotulo->label("t52_descr");
$clrotulo->label("t52_codcla");
$clrotulo->label("t64_descr");
$clrotulo->label("t64_class");
$clrotulo->label("t52_ident");
$clrotulo->label("descrdepto");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$and              = "and";
$where            = " t55_codbem is null ";
$assinatura       = "";
$where_instit     = " db_depart.instit = ".db_getsession("DB_instit");
$where_t52_instit = " t52_instit = ".db_getsession("DB_instit");
$where           .= $and.$where_t52_instit;
$sAnoUsu          = db_getsession("DB_anousu");


/**
 * Escolheu departamento no formulario
 */
if ( !empty($depto) ) {

  $res_depto = $cldbdepart->sql_record($cldbdepart->sql_query_div(null,"nomeresponsavel", null,"coddepto = $depto and $where_instit"));

  if ($cldbdepart->numrows > 0){

    db_fieldsmemory($res_depto,0);
    $assinatura = $nomeresponsavel;
  }

  $where     .= " $and coddepto = $depto";
  $flag_todos = 0;

} else {
  $flag_todos = 1;
}

/**
 * Escolheu divisao no formulario
 */
if ( $div === '0' || !empty($div) ) {

  if (isset($depto) && $depto  != ""){
    $where_instit .= " and coddepto = $depto";
  }

  $res_deptodiv = $cldepartdiv->sql_record($cldepartdiv->sql_query_div(null, "distinct z01_nome", null,"t30_codigo = $div and $where_instit"));

  if ($cldepartdiv->numrows > 0){

    db_fieldsmemory($res_deptodiv,0);
    $assinatura = $z01_nome;
  }

  if ($div === '0') {
      $where .= " $and t33_divisao is null";
  } else {
      $where .= " $and t33_divisao = $div";
  }
}

/**
 * Filtro de bens
 */
if ( !empty($filtro_bens) ) {

  if ($filtro_bens == "I"){
    $where .= " $and t52_bem between $t52_bem_ini and $t52_bem_fim ";
  }

  if ($filtro_bens == "S"){
    $where .= " $and t52_bem in ($listabens) ";
  }
}

if (isset($unidades) && $unidades != "" && isset($orgaos) && $orgaos != ""){

  $where .= " $and o41_unidade in ".$unidades." and o41_anousu = ".$sAnoUsu." and o41_orgao in ".$orgaos;

} else if (isset($unidades) && $unidades != ""){

  $where .= " $and o41_unidade in ".$unidades." and o41_anousu = ".$sAnoUsu;

} else if (isset($orgaos) && $orgaos != "") {

  $where .= " $and o40_orgao in ".$orgaos." and o40_anousu = ".$sAnoUsu;
}

if (isset($departamentos) && $departamentos != "") {

  $where .= " $and coddepto in ".$departamentos;
}

if (isset($divisoes) && $divisoes != "") {

  $where .= " $and t30_codigo in ".$divisoes;
}

if (isset($dtini) && $dtini != "" && isset($dtfim) && $dtfim != "") {

  $where .= " $and t52_dtaqu between '".$dtini."' and '".$dtfim."'";
} else if (isset($dtini) && $dtini != "" ) {
  $where .= " $and t52_dtaqu >= '".$dtini."'";
} else if (isset($dtfim) && $dtfim != "") {
  $where .= " $and t52_dtaqu <= '".$dtfim."'";
}

$sCampos  = "t52_bem, t52_descr, t52_codcla, t52_obs, t64_descr, t52_ident, t52_depart, descrdepto, t52_dtaqu, ";
$sCampos .= "t54_codbem, t53_codbem, t64_class,t30_codigo,t30_descr ";

$lValor = false;

if (isset($cboValor) && trim($cboValor) != "" && $cboValor == 1) {

  $lValor = true;
  $sCampos .= ", t52_valaqu ";
}

/**
 * Ordenação
 */
switch ( $iTipoAgrupamento ) {

  case AGRUPAR_ORGAO :
  case AGRUPAR_ORGAO_UNIDADE :
    $sOrder = "o40_orgao, o41_unidade, t52_bem";
  break;

  case AGRUPAR_DEPARTAMENTO :
  case AGRUPAR_DEPARTAMENTO_DIVISAO :
    $sOrder = "t52_depart, t30_codigo, t52_bem";
  break;

  default :
    $sOrder = "t52_depart, t52_bem";
  break;
}

if (isset($orgaos) && isset($unidades)) {
  $sSql = $cldbbens->sql_query_termo("",$sCampos,$sOrder,$where);
} else {
  $sSql = $cldbbens->sql_querybensdepto("","distinct ".$sCampos,$sOrder,$where);
}

$result = db_query($sSql);
$linhas = $cldbbens->numrows;
$xxnum  = pg_numrows($result);

if ($xxnum == 0) {

  $sMsg = _M('patrimonial.patrimonio.pat2_bensdepto002.nao_existem_registros');
  db_redireciona('db_erros.php?fechar=true&db_erro=' . urlDecode($sMsg));
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false);
$total      = 0;
$nSomaTotal = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$alt       = 4;
$depto_ant = "";

// Texto do documento de TERMO DE RESPONSABILIDADE DO PATRIMONIO
try {
  $oLibDocumento = new libdocumento(10);
  $aParagrafos   = $oLibDocumento->getDocParagrafos();

} catch (Exception $e) {
  $aParagrafos = array();
}

$aParagrafos = (empty($aParagrafos) ? array() : $aParagrafos);

for ($x = 0; $x < pg_numrows($result); $x++) {

  db_fieldsmemory($result, $x);

  /**
   * Adicionado um controle de quebra para exibir o rodapé corretamente
   */
  if ($opcao_obs == "S" && !empty($aParagrafos) && $posicao == "B") {

    $iAltura = 5;

    if (trim($t52_obs) != "") {
      $aLinhasObservacao = explode("\n", $t52_obs);

      foreach ( $aLinhasObservacao as $sObservacao ) {

        $iAltura += ($pdf->NbLines(277, $sObservacao) * 5);
      }
    }

    if ($pdf->getY() + $iAltura > $pdf->h - 35) {
      $lNovaPagina = true;
    }
  }

  $head1 = "TERMO DE RESPONSABILIDADE";

  if ($flag_todos == 1) {

    if ($depto_ant != $t52_depart) {
      if ($depto_ant==""){
        $depto_ant = $t52_depart;
      } else {

        $lNovaPagina = true;
        $depto_ant = $t52_depart;
      }
    }
  }

  $sQueryOrgao   = " select db01_orgao,   ";
  $sQueryOrgao  .= "        db01_unidade, ";
  $sQueryOrgao  .= "        db01_anousu,  ";
  $sQueryOrgao  .= "        o40_orgao,    ";
  $sQueryOrgao  .= "        o40_descr,    ";
  $sQueryOrgao  .= "        o41_orgao,    ";
  $sQueryOrgao  .= "        o41_unidade,  ";
  $sQueryOrgao  .= "        o41_descr     ";
  $sQueryOrgao  .= "   from db_departorg  ";
  $sQueryOrgao  .= "        inner join orcorgao   on db01_orgao   = o40_orgao    ";
  $sQueryOrgao  .= "                          and o40_anousu      = db01_anousu  ";
  $sQueryOrgao  .= "        inner join orcunidade on db01_unidade = o41_unidade  ";
  $sQueryOrgao  .= "                          and o41_anousu      = db01_anousu  ";
  $sQueryOrgao  .= "  where db01_coddepto = $t52_depart  ";
  $sQueryOrgao  .= "    and db01_anousu   = $sAnoUsu and o41_orgao = o40_orgao   ";

  $resQueryOrgao = db_query($sQueryOrgao);
  if(pg_num_rows($resQueryOrgao) > 0) {

    db_fieldsmemory($resQueryOrgao, 0);
    $head3 = "ORGÃO  : $db01_orgao - $o40_descr";
    $head4 = "UNIDADE : $db01_unidade - $o41_descr";

  } else {

    $head3 = "ORGÃO : ";
    $head4 = "UNIDADE : ";
  }

  $head5 = "DEPARTAMENTO : ".$t52_depart." - ".$descrdepto;

  /**
   * Escolheu uma divisao no formulario
   */
  if (isset($div) && $div != "") {

    if ( !empty($t30_codigo) ) {
      $head6 = "DIVISÃO : " . $t30_descr;
    }
  }

  $sDepartamento = !empty($descrdepto) ? "$t52_depart - $descrdepto" : null;
  $sDivisao      = !empty($t30_descr)  ? "$t30_codigo - $t30_descr"  : null;
  $sUnidade      = !empty($o41_descr)  ? "$o41_unidade - $o41_descr" : null;
  $sOrgao        = !empty($o40_descr)  ? "$o40_orgao - $o40_descr"   : null;

  /**
   * - Define titulo do agrupador
   * - Verifica se deve imprimir novo cabecalho
   */
  switch ( $iTipoAgrupamento ) {

    case AGRUPAR_ORGAO :
    case AGRUPAR_ORGAO_UNIDADE :

      $sTituloAgrupador = $sOrgao;

      if ( $iOrgaoAnterior != $o40_orgao ) {
        $lNovoCabecalho = true;
      }

      if ( $iTipoAgrupamento == AGRUPAR_ORGAO_UNIDADE ) {

        if ( !empty($sUnidade) ) {
          $sTituloAgrupador .= ' / ' . $sUnidade;
        }

        if ( $iUnidadeAnterior != $o41_unidade ) {
          $lNovoCabecalho = true;
        }
      }

    break;

    case AGRUPAR_DEPARTAMENTO :
    case AGRUPAR_DEPARTAMENTO_DIVISAO :

      $sTituloAgrupador = $sDepartamento;

      if ( $iDepartamentoAnterior != $t52_depart ) {
        $lNovoCabecalho = true;
      }

      if ( $iTipoAgrupamento == AGRUPAR_DEPARTAMENTO_DIVISAO ) {

        if ( !empty($sDivisao) ) {
          $sTituloAgrupador .= ' / ' . $sDivisao;
        }

        if ( $iDivisaoAnterior != $t30_codigo ) {
          $lNovoCabecalho = true;
        }
      }

    break;

  }

  $iDepartamentoAnterior = $t52_depart;
  $iOrgaoAnterior        = isset($o40_orgao) ? $o40_orgao : '';
  $iUnidadeAnterior      = isset($o41_unidade) ? $o41_unidade : '';
  $iDivisaoAnterior      = $t30_codigo;

  $res_situacaobem = $clhistbem->sql_record($clhistbem->sql_query(null,"t70_descr", "t56_histbem desc", "t52_bem = $t52_bem"));
  $numrows = $clhistbem->numrows;

  if ($numrows > 0) {
    db_fieldsmemory($res_situacaobem, 0);
  }

  if ( $pdf->gety() > $pdf->h - 35 || $lNovaPagina ) {

    $pdf->setfont('arial','b',8);
    if ($flag_todos == 1 && $x > 0) {
      if ($posicao == "B") {

        foreach ($aParagrafos as $oParagrafo) {

          if ($oParagrafo->oParag->db02_tipo == libparagrafo::TIPO_CODIGO_PHP) {
            eval($oParagrafo->oParag->db02_texto);
          } else {
            $pdf->multicell(277, 5, $oParagrafo->oParag->db02_texto, 0, "L");
          }
        }
      }
    }

    $pdf->addpage('L');
    if ($x == 0) {
      if ($posicao == "A") {
        foreach ($aParagrafos as $oParagrafo) {

          if ($oParagrafo->oParag->db02_tipo == libparagrafo::TIPO_CODIGO_PHP) {
            eval($oParagrafo->oParag->db02_texto);
          } else {
            $pdf->multicell(257,5,$oParagrafo->oParag->db02_texto,0,"L");
          }
        }
        $pdf->ln(3);
      }
    }

    if ($flag_todos == 1 && $x > 0) {

      if ($posicao == "A") {

        foreach ($aParagrafos as $oParagrafo) {

          if ($oParagrafo->oParag->db02_tipo == libparagrafo::TIPO_CODIGO_PHP) {
            eval($oParagrafo->oParag->db02_texto);
          } else {
            $pdf->multicell(257,5,$oParagrafo->oParag->db02_texto,0,"L");
          }
        }
        $pdf->ln(3);
      }
    }

    $pdf->setfont('arial','b',8);

    if ( !empty($sTituloAgrupador) ) {
      $pdf->cell(0, $alt, $sTituloAgrupador ,1 , 1, "L", 1);
    }

    $pdf->cell(12,$alt,"Código"         ,1 , 0, "C", 1);
    $pdf->cell(25,$alt,$RLt52_ident     ,1 , 0, "C", 1);

    $iCelula = 80 + ($lClassificacao ? 0 : 100) + ($lValor ? 0 : 15);

    $pdf->cell($iCelula,$alt,$RLt52_descr     ,1 , 0, "C", 1);

    if ($lClassificacao) {
      $pdf->cell(20,$alt,$RLt64_class     ,1 , 0, "C", 1);
      $pdf->cell(80,$alt,$RLt64_descr   , 1, 0, "L", 1);
    }

    $pdf->cell(20, $alt, $RLt52_dtaqu     , 1, 0, "C", 1);
    $pdf->cell(25, $alt, "Situação do Bem", 1, 0, "C", 1);

    if ($lValor) {
      $pdf->cell(15,$alt,"Valor"   , 1, 1, "C", 1);
    } else {
      $pdf->ln();
    }

    if ($opcao_obs == "S") {
      $pdf->cell(277,$alt,"Características Adicionais do Bem",1,1,"L",1);
    }

    if ($t53_codbem !="") {
      $tipo = "Material";
    } elseif ($t54_codbem != "") {
      $tipo = "Imóvel";
    } else {
      $tipo = "Material";
    }

    $lNovaPagina = false;
    $lNovoCabecalho = false;
  }

  if ( $lNovoCabecalho ) {

    $pdf->setfont('arial','b',8);

    if ( !empty($sTituloAgrupador) ) {
      $pdf->cell(0, $alt, $sTituloAgrupador ,1 , 1, "L", 1);
    }

    $pdf->cell(12,$alt,"Código"         ,1 , 0, "C", 1);
    $pdf->cell(25,$alt,$RLt52_ident     ,1 , 0, "C", 1);

    $iCelula = 80 + ($lClassificacao ? 0 : 100) + ($lValor ? 0 : 15);

    $pdf->cell($iCelula,$alt,$RLt52_descr     ,1 , 0, "C", 1);

    if ($lClassificacao) {
      $pdf->cell(20,$alt,$RLt64_class     ,1 , 0, "C", 1);
      $pdf->cell(80,$alt,$RLt64_descr   , 1, 0, "L", 1);
    }

    $pdf->cell(20, $alt, $RLt52_dtaqu     , 1, 0, "C", 1);
    $pdf->cell(25, $alt, "Situação do Bem", 1, 0, "C", 1);

    if ($lValor) {
      $pdf->cell(15,$alt,"Valor"   , 1, 1, "C", 1);
    } else {
      $pdf->ln();
    }

    if ($opcao_obs == "S") {
      $pdf->cell(277,$alt,"Características Adicionais do Bem",1,1,"L",1);
    }

    $lNovoCabecalho = false;
  }

  $pdf->setfont('arial','',7);
  $pdf->cell(12, $alt, $t52_bem,   0, 0, "C", 0);
  $pdf->cell(25, $alt, $t52_ident, 0, 0, "C", 0);

  $iCelula = 80 + ($lClassificacao ? 0 : 100) + ($lValor ? 0 : 15);

  $pdf->cell($iCelula, $alt, $t52_descr, 0, 0, "L", 0);

  if ($lClassificacao) {
    $pdf->cell(20, $alt, $t64_class, 0, 0, "C", 0);
    $pdf->cell(80,$alt,substr($t64_descr,0,48),0,0,"L",0);
  }

  $pdf->cell(20,$alt,db_formatar($t52_dtaqu ,'d'),0,0,"C",0);
  $pdf->cell(25,$alt,substr(@$t70_descr,0,48),0,0,"C",0);

  if ($lValor) {
    $pdf->cell(15,$alt,db_formatar($t52_valaqu,"f") , 0, 1, "R", 0);
  } else {
    $pdf->ln();
  }

  if ($opcao_obs == "S") {

    if ($x % 2 == 0) {
      $p = 1;
    } else {
      $p = 0;
    }

    if (trim($t52_obs)!="") {

      $aLinhasObservacao = explode("\n", $t52_obs);

      foreach ( $aLinhasObservacao as $sObservacao ) {

        $iAlturaLinhaObservacoes = $pdf->NbLines(277, $sObservacao) * 5;

        if ( $pdf->gety() + $iAlturaLinhaObservacoes > $pdf->h - 20 ) {
          $pdf->addpage('L');
        }

        $pdf->multicell(277, 5, $sObservacao, 0, "L", $p);
      }
    }
  }

  if ($lValor) {
    $nSomaTotal += $t52_valaqu;
  }
  $total ++;
}

if ($pdf->gety() + 5 > $pdf->h - 35) {

  if ($posicao == "B") {

    foreach ($aParagrafos as $oParagrafo) {

      if ($oParagrafo->oParag->db02_tipo == libparagrafo::TIPO_CODIGO_PHP) {
        eval($oParagrafo->oParag->db02_texto);
      } else {
        $pdf->multicell(277,5,$oParagrafo->oParag->db02_texto,0,"L");
      }
    }
  }

  $pdf->addpage('L');
}

$pdf->setfont('arial','b',8);
if ($lValor) {

  $pdf->cell(232, $alt, "Total de Registros  :  $total", 'T',0,"R",0);
  $pdf->cell(20,  $alt, "Valor Total: ",              'T',0,"R",0);
  $pdf->cell(25,  $alt, db_formatar($nSomaTotal,"f"),    'T',1,"R",0);
} else {
  $pdf->cell(277,$alt, "Total de Registros  :  $total", 'T',1,"R",0);
}

if ($posicao == "B") {

  foreach ($aParagrafos as $oParagrafo) {

    if ($oParagrafo->oParag->db02_tipo == libparagrafo::TIPO_CODIGO_PHP) {
      eval($oParagrafo->oParag->db02_texto);
    } else {
      $pdf->multicell(277,5,$oParagrafo->oParag->db02_texto,0,"L");
    }
  }
}

$pdf->ln(10);

// Assinatura no rodapé
if (!isset($ass)) {

  $sSqlParagrafo  = "select db02_texto ";
  $sSqlParagrafo .= "  from db_documento ";
  $sSqlParagrafo .= "       inner join db_docparag on db03_docum = db04_docum ";
  $sSqlParagrafo .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
  $sSqlParagrafo .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
  $sSqlParagrafo .= " where db03_tipodoc = 1401 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";

  $oResourceParagrafo = @db_query($sSqlParagrafo);

  if (@pg_numrows($oResourceParagrafo) > 0) {

    db_fieldsmemory($oResourceParagrafo, 0);
    eval($db02_texto);

  } else {

    $sSqlParagrafoPadrao  = "select db61_texto ";
    $sSqlParagrafoPadrao .= "  from db_documentopadrao ";
    $sSqlParagrafoPadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
    $sSqlParagrafoPadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
    $sSqlParagrafoPadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
    $sSqlParagrafoPadrao .= " where db60_tipodoc = 1401 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";

    $oResourceParagrafoPadrao = @db_query($sSqlParagrafoPadrao);

    if (@pg_numrows($oResourceParagrafoPadrao) > 0) {

      db_fieldsmemory($oResourceParagrafoPadrao, 0);
      eval($db61_texto);

    }

  }

} else {

  $ass = utf8_decode(db_stdClass::db_stripTagsJson($ass));
  $ass = str_replace('\n', "\n", $ass);
  $pdf->MultiCell(270,$alt, $ass,'',"R");
}

Header('Content-disposition: inline; filename=termo_responsabilidade_' . time() . '.pdf');
$pdf->output();

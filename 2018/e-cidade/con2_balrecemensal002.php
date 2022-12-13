<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica
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

require_once ("fpdf151/pdf.php");
require_once ("fpdf151/assinatura.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_liborcamento.php");
require_once ("libs/db_libcontabilidade.php");


$clAssinatura = new cl_assinatura;
$oGet         = db_utils::postMemory($_GET);
$iInstituicao = split("-",$oGet->db_selinstit);
$sInstituicao = implode(", ", $iInstituicao);

/**
 * Variáveis de Configuração do relatório
 * Tm = Tamanho
 */
$iAlt               = 4;
$iTmFonte           = 6;
$iTmValor           = 16.5; // Tamanho da Coluna de Valores
$iTmDescr           = 45;   // Tamanho da Coluna de Descrição
$iTamanhoEstrutural = 28;   // Tamanho da Coluna de Estrutura
$iTmReduz           = 10;   // Tamanho da Coluna de Rec
$iFundo             =  0;

$iTamanhoSubstrDescricao = 32;
/**
 * Datas para pesquisa dos dados de Receita
 */
$iAnoUsu   = db_getsession("DB_anousu");
$dtIni     = $iAnoUsu."-01-01";
$dtFin     = $iAnoUsu."-12-31";
/**
 * Se for setada uma data limite para retorno dos dados, utilizar ela.
 */
if (isset($oGet->dtLimit) && !empty($oGet->dtLimit)) {
  $dtFin = implode("-", array_reverse(explode("/", $oGet->dtLimit)));
}

/**
 * Variáveis que conterão o somátório da Coluna
 */
$nTotalSaldoJan    = 0;
$nTotalSaldoFev    = 0;
$nTotalSaldoMar    = 0;
$nTotalSaldoAbr    = 0;
$nTotalSaldoMai    = 0;
$nTotalSaldoJun    = 0;
$nTotalSaldoJul    = 0;
$nTotalSaldoAgo    = 0;
$nTotalSaldoSet    = 0;
$nTotalSaldoOut    = 0;
$nTotalSaldoNov    = 0;
$nTotalSaldoDez    = 0;
$nSomatorioPeriodo = 0;
$nSomatorioGeral   = 0;


/**
 * Array dos Meses
 */
$aMeses = array("janeiro", "fevereiro", "marco", "abril", "maio", "junho", "julho", "agosto", "setembro",
                "outubro", "novembro", "dezembro");
/**
 * Seta os meses para o período selecionado
 */
$aMesesPeriodo = array();
if (isset($oGet->periodo)) {

  switch ($oGet->periodo) {
    case "2":

      $aMesesPeriodo           = array("janeiro", "fevereiro", "marco", "abril", "maio", "junho");
      $iTmValor                = 23;
      $iTmDescr                = 55;
      $iTamanhoSubstrDescricao = 38;
      break;
    case "3":

      $aMesesPeriodo           = array("julho", "agosto", "setembro", "outubro", "novembro", "dezembro");
      $iTmValor                = 23;
      $iTmDescr                = 55;
      $iTamanhoSubstrDescricao = 38;
      break;
    default:

      $aMesesPeriodo = array("janeiro", "fevereiro", "marco", "abril", "maio", "junho", "julho", "agosto", "setembro",
                             "outubro", "novembro", "dezembro");
      break;
  }
}

/**
 * Monta Sql para buscar as Instituições
 */
$sSqlInstit   = "select codigo,nomeinstabrev from db_config where codigo in ({$sInstituicao}) ";

$rsInstit     = db_query($sSqlInstit);
$oInstit      = db_utils::getCollectionByRecord($rsInstit);

$sNomeInstit  = "";
$sVirgula     = "";
/**
 * Monta uma String com as instituições recebidas
 */
foreach ($oInstit as $key => $value) {

  $sNomeInstit .=  $sVirgula.$value->nomeinstabrev;
  $sVirgula     = ", ";
}

/**
 * Monta Cabeçalho
 */
$head1 = "RELATÓRIO DE RECEITA MENSAL";
$head4 = "EXERCÍCIO: ".db_getsession("DB_anousu")." - CONFERÊNCIA" ;
$head5 = "INSTITUIÇÕES : ".$sNomeInstit;

if (isset($oGet->dtLimit) && !empty($oGet->dtLimit) ) {
  $head6 = "Posição até ".$oGet->dtLimit;
} else {
  $head6 = "Posição Atual";
}

/**
 * if data fim for passada busca por ela
 */

$clreceita_saldo_mes            = new cl_receita_saldo_mes;
$clreceita_saldo_mes->anousu    = $iAnoUsu;
$clreceita_saldo_mes->dtini     = $dtIni;
$clreceita_saldo_mes->dtfim     = $dtFin;
$clreceita_saldo_mes->usa_datas = 'sim';
$clreceita_saldo_mes->instit    = $sInstituicao;
$clreceita_saldo_mes->sql_record();
$rsReceitaSaldoMes              = $clreceita_saldo_mes->result;
$oReceitaSaldoMes               = db_utils::getCollectionByRecord($rsReceitaSaldoMes);


/**
 * Percorre a Colection somando os valores da linha criando os totalizadores para o Período
 */
foreach ($oReceitaSaldoMes as $oReceita) {

  $oReceita->nTotalPeriodo = 0;
  $oReceita->nTotalGeral   = 0;

  foreach ($aMesesPeriodo as $sMes) {

    $oReceita->nTotalPeriodo += $oReceita->{$sMes};
  }

  if (isset($oGet->periodo) && $oGet->periodo == 2) {

    $oReceita->nTotalGeral += $oReceita->nTotalPeriodo;

  } else {

    foreach ($aMeses as $sMes) {

      $oReceita->nTotalGeral += $oReceita->{$sMes};
    }
  }

  if (db_conplano_grupo($iAnoUsu, $oReceita->o57_fonte, 9004)) {

    $nTotalSaldoJan += $oReceita->janeiro ;
    $nTotalSaldoFev += $oReceita->fevereiro ;
    $nTotalSaldoMar += $oReceita->marco ;
    $nTotalSaldoAbr += $oReceita->abril ;
    $nTotalSaldoMai += $oReceita->maio ;
    $nTotalSaldoJun += $oReceita->junho ;
    $nTotalSaldoJul += $oReceita->julho;
    $nTotalSaldoAgo += $oReceita->agosto ;
    $nTotalSaldoSet += $oReceita->setembro ;
    $nTotalSaldoOut += $oReceita->outubro ;
    $nTotalSaldoNov += $oReceita->novembro;
    $nTotalSaldoDez += $oReceita->dezembro;
    $nSomatorioGeral  = ($nTotalSaldoJan+$nTotalSaldoFev+$nTotalSaldoMar+$nTotalSaldoAbr+$nTotalSaldoMai+$nTotalSaldoJun);
    $nSomatorioGeral += ($nTotalSaldoJul+$nTotalSaldoAgo+$nTotalSaldoSet+$nTotalSaldoOut+$nTotalSaldoNov+$nTotalSaldoDez);

  }
}
// Variável de controle para primeira Página
$lPrimeiraPagina = true;

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfont('arial', 'b', 8);
$oPdf->setleftmargin(10);

/**
 * Itera sobre a Collection da Receita imprimindo seu conteúdo
 */
foreach ($oReceitaSaldoMes as $oReceita) {

  /**
   * Imprime Cabeçalho
   */
  if ($oPdf->gety() > $oPdf->h-30 || $lPrimeiraPagina) {

    $lPrimeiraPagina = false;
    $oPdf->addpage("L");
    $oPdf->setfont('arial','B',$iTmFonte);
    $oPdf->cell($iTamanhoEstrutural,$iAlt,"RECEITA"    , "B", 0, "L", 0);
    $oPdf->cell($iTmDescr ,$iAlt,"DESCRIÇÃO"  , "B", 0, "L", 0);
    $oPdf->cell($iTmFonte ,$iAlt,"REC"        , "B", 0, "L", 0);

    /**
     * Imprime cabeçalho conforme o Período selecionado
     */
    foreach ($aMesesPeriodo as $sPeriodo) {
      $oPdf->cell($iTmValor ,$iAlt, strtoupper($sPeriodo), "B", 0, "R", 0);
    }
    if (isset($oGet->periodo) && ($oGet->periodo == 2 || $oGet->periodo == 3)) {

      $oPdf->cell(25 ,$iAlt, "TOTAL PERÍODO", "B", 0, "R", 0);
      $oPdf->cell(25 ,$iAlt, "TOTAL GERAL",   "B", 0, "R", 0);
    }
    $oPdf->ln();
  }

  /**
   * Variável de controle para setar negrito quando forconta Sintética
   */
  $sBold   = "";
  /**
   * Variável para identação do fonte
   */
  $sEspaco = "    ";
  if ($oReceita->o70_codrec == 0) {

    $sBold                = 'B';
    $sEspaco              = "";
    $oReceita->o70_codrec = '';
  }


  $oPdf->setfont('arial', $sBold, $iTmFonte);
  $oPdf->cell($iTamanhoEstrutural, $iAlt, $sEspaco.db_formatar($oReceita->o57_fonte,'receita'), 0, 0, "L");

  $oPdf->cell($iTmDescr,  $iAlt, substr($sEspaco.$oReceita->o57_descr, 0, $iTamanhoSubstrDescricao), 0, 0, "L");
  $oPdf->cell($iTmFonte,  $iAlt, $oReceita->o70_codrec, 0, 0, "C");

  /**
   * Verifica os Períodos antes de Imprimir
   */
  foreach ($aMesesPeriodo as $sPeriodo) {
    $oPdf->cell($iTmValor, $iAlt, db_formatar($oReceita->$sPeriodo,'f'), 0, 0, "R");
  }
  if (isset($oGet->periodo) && ($oGet->periodo == 2 || $oGet->periodo == 3)) {

     $oPdf->cell(25, $iAlt, db_formatar($oReceita->nTotalPeriodo,'f'), 0, 0, "R");
     $oPdf->cell(25, $iAlt, db_formatar($oReceita->nTotalGeral,'f')  , 0, 0, "R");
  }
  $oPdf->ln();

}
/**
 * Imprime Somatório da das Colunas
 */
$oPdf->setfont('arial', 'B', $iTmFonte);
$oPdf->cell($iTamanhoEstrutural, $iAlt,'',        "TB", 0, "L", 0);
$oPdf->cell($iTmDescr+$iTmFonte, $iAlt,'TOTAIS ', "TB", 0, "R", 0);

if (isset($oGet->periodo) && ($oGet->periodo == 1 || $oGet->periodo == 2)) {

  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoJan,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoFev,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoMar,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoAbr,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoMai,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoJun,'f'), "TB", 0, "R", 0);

  $nSomatorioPeriodo += $nTotalSaldoJan+$nTotalSaldoFev+$nTotalSaldoMar+$nTotalSaldoAbr+$nTotalSaldoMai+$nTotalSaldoJun;
}
if (isset($oGet->periodo) && ($oGet->periodo == 1 || $oGet->periodo == 3)) {

  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoJul,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoAgo,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoSet,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoOut,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoNov,'f'), "TB", 0, "R", 0);
  $oPdf->cell($iTmValor, $iAlt,db_formatar($nTotalSaldoDez,'f'), "TB", 0, "R", 0);

  $nSomatorioPeriodo += $nTotalSaldoJul+$nTotalSaldoAgo+$nTotalSaldoSet+$nTotalSaldoOut+$nTotalSaldoNov+$nTotalSaldoDez;
}

if (isset($oGet->periodo) && ($oGet->periodo == 2 || $oGet->periodo == 3)) {

  $oPdf->cell(25, $iAlt,db_formatar($nSomatorioPeriodo,'f'), "TB", 0, "R", 0);
  $oPdf->cell(25, $iAlt,db_formatar($nSomatorioGeral,'f'),   "TB", 0, "R", 0);
}
/**
 * Setando campos de Assinatura
 */
$sTesoureiro     =  "______________________________"."\n"."Tesoureiro";
$sSecFazenda     =  "______________________________"."\n"."Secretaria da Fazenda";
$sContador       =  "______________________________"."\n"."Contador";
$sPrefeito       =  "______________________________"."\n"."Prefeito";
$sAssPrefeito    = $clAssinatura->assinatura(1000,$sPrefeito);
$sAssSecFazenda  = $clAssinatura->assinatura(1002,$sSecFazenda);
$sAssTesoureiro  = $clAssinatura->assinatura(1004,$sTesoureiro);
$sAssContador    = $clAssinatura->assinatura(1005,$sContador);

$oPdf->setfont('arial','B',8);
if ($oPdf->gety() > ( $oPdf->h - 50 )) {
  $oPdf->addpage("L");
}

$nLargura = ($oPdf->w ) / 2;
$oPdf->ln(10);
$iPosicaoY = $oPdf->gety();
$oPdf->multicell($nLargura, 4, $sAssPrefeito, 0, "C", 0, 0);
$oPdf->setxy($nLargura, $iPosicaoY);
$oPdf->multicell($nLargura, 4, $sAssContador, 0, "C", 0, 0);

$oPdf->Output();
?>

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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_coremp_classe.php"));
require_once(modification("classes/db_pagordemnota_classe.php"));

$iAnoUsoSessao      = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");
$oGet               = db_utils::postMemory($_GET);

$dtDataInicialBanco = implode("-", array_reverse(explode("/", $oGet->dtDataInicial)));
$dtDataFinalBanco   = implode("-", array_reverse(explode("/", $oGet->dtDataFinal)));
$oGet->lQuebraConta == "t" ? $oGet->lQuebraConta = true : $oGet->lQuebraConta = false;

$aOrderBy    = array();
$aWhere      = array();
$aWhereConta = array();

if ($oGet->sTipoOrdem == "empenho") {
	if ($oGet->lQuebraConta) {
		$aOrderBy[] = "tipo, k13_conta, k12_data, e60_codemp ";
	} else {
		$aOrderBy[] = "tipo, k12_data, e60_codemp ";
	}
} else {
	if ($oGet->lQuebraConta) {
		$aOrderBy[] = "tipo, k13_conta, k12_data, k12_autent ";
	} else {
		$aOrderBy[] = "tipo, k12_data, k12_autent";
	}
}

$sWhereContaPagadora = "";
if (!empty($oGet->iContaPagadora)) {
  $sWhereContaPagadora = "k13_conta = $oGet->iContaPagadora";
  $aWhere[]            = $sWhereContaPagadora;
}


/*
 * Validar Datas
 */
if ( !empty($oGet->dtDataInicial) && !empty($oGet->dtDataFinal) ) {
  
  $aWhere[] = "coremp.k12_data between '{$dtDataInicialBanco}' and '{$dtDataFinalBanco}'";
  $head5    = "Ordem de {$oGet->dtDataInicial} até {$oGet->dtDataFinal}";
} else if (!empty($oGet->dtDataInicial)) {
  
  $aWhere[] = "coremp.k12_data >= '{$dtDataInicialBanco}'";
  $head5    = "Ordem a partir de: {$oGet->dtDataInicial}";
} else if (!empty($oGet->dtDataFinal)) {
  
  $aWhere[] = "coremp.k12_data <= '{$dtDataFinalBanco}'";
  $head5    = "Ordem até: {$oGet->dtDataInicial}";
} else {
  
  $sSqlBuscaDataEmp   = "  select max(coremp.k12_data) as maior,                                    ";
  $sSqlBuscaDataEmp  .= "         min(coremp.k12_data) as menor                                     ";
  $sSqlBuscaDataEmp  .= "    from coremp                                                            ";
  $sSqlBuscaDataEmp  .= "         inner join corrente   on corrente.k12_id     = coremp.k12_id      ";
  $sSqlBuscaDataEmp  .= "                              and corrente.k12_data   = coremp.k12_data    ";
  $sSqlBuscaDataEmp  .= "                              and corrente.k12_autent = coremp.k12_autent  ";
  $sSqlBuscaDataEmp  .= "                              and k12_instit = {$iInstituicaoSessao}       ";
  $sSqlBuscaDataEmp  .= "         inner join saltes     on saltes.k13_conta = corrente.k12_conta    ";
  $sSqlBuscaDataEmp  .= "   where {$sWhereContaPagadora}                                            ";
  $rsBuscaData        = db_query($sSqlBuscaDataEmp);
  $oDadosData         = db_utils::fieldsMemory($rsBuscaData, 0);
  $dtDataInicialBanco = $oDadosData->maior;
  $dtDataFinalBanco   = $oDadosData->menor;
  $head5 = "Ordem de {$oGet->dtDataInicial} até {$oGet->dtDataFinal}";
}

if (!empty($oGet->sCredoresSelecionados)) {
	$aWhere[] = "e60_numcgm in ({$oGet->sCredoresSelecionados})";
}


$sWhereEmpenho = "";
if ($oGet->iListaEmpenho == 0) {
  $sWhereEmpenho = '1 = 1';
} else {
  
  if ($oGet->iListaEmpenho == 1) {
    $sWhereEmpenho = "tipo = 'Emp'";
  } else {
    $sWhereEmpenho = "tipo = 'RP'";
  }
}

if (!empty($oGet->iTipoBaixa) && $oGet->iTipoBaixa == 2) {
  $aWhere[] = "k106_sequencial not in (2, 5)";
} elseif (!empty($oGet->iTipoBaixa) && $oGet->iTipoBaixa == 3) {
  $aWhere[] = "k106_sequencial in (2, 5)";
}

$sImplodeWhere     = implode(" and ", $aWhere);                                                                    
$sImplodeOrderBy   = implode(", ", $aOrderBy);                                                                     
$sSqlBuscaEmpenhos  = "  select *                                                                                  ";
$sSqlBuscaEmpenhos .= "   from ( select coremp.k12_empen,                                                          ";
$sSqlBuscaEmpenhos .= "                e60_numemp,                                                                 ";
$sSqlBuscaEmpenhos .= "                e60_codemp,                                                                 ";
$sSqlBuscaEmpenhos .= "                case when e49_numcgm is null                                                ";
$sSqlBuscaEmpenhos .= "                  then e60_numcgm                                                           ";
$sSqlBuscaEmpenhos .= "                    else e49_numcgm                                                         ";
$sSqlBuscaEmpenhos .= "                  end as e60_numcgm,                                                        ";
$sSqlBuscaEmpenhos .= "                k12_codord as e50_codord,                                                   ";
$sSqlBuscaEmpenhos .= "                case when e49_numcgm is null                                                ";
$sSqlBuscaEmpenhos .= "                  then cgm.z01_nome                                                         ";
$sSqlBuscaEmpenhos .= "                    else cgmordem.z01_nome                                                  ";
$sSqlBuscaEmpenhos .= "                  end as z01_nome,                                                          ";
$sSqlBuscaEmpenhos .= "                k12_valor,                                                                  ";
$sSqlBuscaEmpenhos .= "                k12_cheque,                                                                 ";
$sSqlBuscaEmpenhos .= "                e60_anousu,                                                                 ";
$sSqlBuscaEmpenhos .= "                coremp.k12_autent,                                                          ";
$sSqlBuscaEmpenhos .= "                coremp.k12_data,                                                            ";
$sSqlBuscaEmpenhos .= "                k13_conta,                                                                  ";
$sSqlBuscaEmpenhos .= "                k13_descr,                                                                  ";
$sSqlBuscaEmpenhos .= "                case when e60_anousu < {$iAnoUsoSessao} then 'RP' else 'Emp' end as tipo,   ";
$sSqlBuscaEmpenhos .= "                k106_sequencial                                                             ";
$sSqlBuscaEmpenhos .= "           from coremp                                                                      ";
$sSqlBuscaEmpenhos .= "                inner join empempenho        on e60_numemp          = k12_empen             ";
$sSqlBuscaEmpenhos .= "                                            and e60_instit          = {$iInstituicaoSessao} ";
$sSqlBuscaEmpenhos .= "                inner join orcdotacao        on e60_coddot = o58_coddot and e60_anousu = o58_anousu ";
$sSqlBuscaEmpenhos .= "                inner join pagordem          on e50_codord          = k12_codord            ";
$sSqlBuscaEmpenhos .= "                left  join pagordemconta     on e50_codord          = e49_codord            ";
$sSqlBuscaEmpenhos .= "                inner join corrente          on corrente.k12_id     = coremp.k12_id         ";
$sSqlBuscaEmpenhos .= "                                            and corrente.k12_data   = coremp.k12_data       ";
$sSqlBuscaEmpenhos .= "                                            and corrente.k12_autent = coremp.k12_autent     ";
$sSqlBuscaEmpenhos .= "                inner join cgm               on cgm.z01_numcgm      = e60_numcgm            ";
$sSqlBuscaEmpenhos .= "                left  join cgm cgmordem      on cgmordem.z01_numcgm = e49_numcgm            ";
$sSqlBuscaEmpenhos .= "                inner join saltes            on saltes.k13_conta    = corrente.k12_conta    ";
$sSqlBuscaEmpenhos .= "                left  join corgrupocorrente  on k105_id             = corrente.k12_id       ";
$sSqlBuscaEmpenhos .= "                                            and k105_data           = corrente.k12_data     ";
$sSqlBuscaEmpenhos .= "                                            and k105_autent         = corrente.k12_autent   ";
$sSqlBuscaEmpenhos .= "                left  join corgrupotipo      on k106_sequencial     = k105_corgrupotipo     ";
$sSqlBuscaEmpenhos .= "          where {$sImplodeWhere}                                                            ";
$sSqlBuscaEmpenhos .= "          order by {$sImplodeOrderBy}) as xxx                                               ";
$sSqlBuscaEmpenhos .= " where {$sWhereEmpenho}                                                                     ";
$rsExecutaBuscaEmpenho = db_query($sSqlBuscaEmpenhos);
$iLinhasRetornadasBuscaEmpenho = pg_num_rows($rsExecutaBuscaEmpenho);
if ($iLinhasRetornadasBuscaEmpenho == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Não existem empenhos para o filtro selecionado.");
}

$total = 0;

$aDadosImprimir = array();
for ($iRowBusca = 0; $iRowBusca < $iLinhasRetornadasBuscaEmpenho; $iRowBusca++) {
  
  $oDadoEmpenho = db_utils::fieldsMemory($rsExecutaBuscaEmpenho, $iRowBusca);

  if ( $oDadoEmpenho->tipo == "Emp" ) {
    $total += $oDadoEmpenho->k12_valor;
  }

  if ( $oDadoEmpenho->e50_codord > 0 ) {

    $oDaoPagOrdemNota   = db_utils::getDao('pagordemnota');
    $sSqlBuscaOrdemNota = $oDaoPagOrdemNota->sql_query($oDadoEmpenho->e50_codord,null,'e69_numero');
    $rsBuscaOrdemNota   = $oDaoPagOrdemNota->sql_record($sSqlBuscaOrdemNota);
    $iTotalOrdemNota    = $oDaoPagOrdemNota->numrows;
    $aNotasEncontradas  = array();
  	if( $oDaoPagOrdemNota->numrows > 0 ) {
  	  for ($iRowOrdem = 0; $iRowOrdem < $iTotalOrdemNota; $iRowOrdem++ ) {
    		$iNumeroOrdem        = db_utils::fieldsMemory($rsBuscaOrdemNota, $iRowOrdem)->e69_numero;
    		$aNotasEncontradas[] = $iNumeroOrdem;
  	  }
    } else {
      $aNotasEncontradas[] = "0";
    }
  }
  $oDadoEmpenho->aNotas = $aNotasEncontradas;
  $aDadosImprimir[] = $oDadoEmpenho;
  unset($oDadoEmpenho);

}

$iAltura              = 4;
$lTroca               = true;
$nValorSoma           = 0;
$nValorTotalBanco     = 0;
$nValorTotalRelatorio = 0;
$iTotalRegistros      = 0;
$iTotalRegistroGeral  = 0;

$head1 = "MOVIMENTAÇÃO EMPENHOS PAGOS";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$sTipoEmpenho = $aDadosImprimir[0]->tipo;
$iCodigoBanco = $aDadosImprimir[0]->k13_conta;
$iQuantBanco  = 0;

foreach ($aDadosImprimir as $iIndice => $oDadoEmpenho) {
  
  if ( $oPdf->gety() > $oPdf->h - 30 || $lTroca) {
    imprimeCabecalho($oPdf, $iAltura);
    $lTroca = false;
  }
  
  if ( $iCodigoBanco != $oDadoEmpenho->k13_conta && $oGet->lQuebraConta ) {
  	$oPdf->setfont('arial','B',9);
  	$oPdf->cell(280, $iAltura, "TOTAL DO BANCO ($iQuantBanco registros):  R$ ".db_formatar($nValorTotalBanco,"f"), "T", 1, "L", 1);
  	$oPdf->ln(5);
  	$nValorTotalBanco = 0;
  	$iCodigoBanco     = $oDadoEmpenho->k13_conta;
    $iQuantBanco      = 0;
  }

  if ( $sTipoEmpenho != $oDadoEmpenho->tipo ) {
  	$oPdf->setfont('arial','B',9);
    $sTxtAlteraPagina = "TOTAL DE REGISTROS [1] :  {$iTotalRegistros}   VALOR TOTAL  :R$ ".db_formatar($nValorSoma,"f");
  	$oPdf->cell(280, $iAltura, $sTxtAlteraPagina, "T", 1, "L", 0);
  	$oPdf->ln(3);
  	$iTotalRegistros = 0;
  	$nValorSoma      = 0;
  	$sTipoEmpenho    = $oDadoEmpenho->tipo;
  }
  if ( $iCodigoBanco != $oDadoEmpenho->k13_conta && $oGet->lQuebraConta ) {
    
  	$oPdf->setfont('arial','B',9);
  	$oPdf->cell(280, $iAltura, "TOTAL DO BANCO  :  R$ ".db_formatar($nValorTotalBanco,"f"), "T", 1, "L", 1);
  	$oPdf->ln(5);
  	$nValorTotalBanco = 0;
  	$nValorSoma       = 0;
  	$iCodigoBanco     = $oDadoEmpenho->k13_conta;
  	$sTipoEmpenho     = $oDadoEmpenho->tipo;
  }
 
  $notas    = "";
  $sepnotas = "";
  $oPdf->setfont('arial','',7);
  $oPdf->cell(20,$iAltura,db_formatar($oDadoEmpenho->k12_data, 'd'),0,0,"C",0);
  $oPdf->cell(15,$iAltura,$oDadoEmpenho->k12_autent,0,0,"C",0);
  $oPdf->cell(15,$iAltura,$oDadoEmpenho->k13_conta,0,0,"C",0);
  $oPdf->cell(40,$iAltura,substr($oDadoEmpenho->k13_descr,0,25),0,0,"L",0);
  $oPdf->cell(18,$iAltura,$oDadoEmpenho->k12_empen,0,0,"C",0);
  $oPdf->cell(15,$iAltura,trim($oDadoEmpenho->e60_codemp).'/'.$oDadoEmpenho->e60_anousu,0,0,"C",0);
  $oPdf->cell(15,$iAltura,$oDadoEmpenho->e50_codord,0,0,"C",0);

  if (count($oDadoEmpenho->aNotas) > 0) {
    $sNotasEncontradas = implode(" - ", $oDadoEmpenho->aNotas);
  }

  $iYold = $oPdf->getY();
  $iXold = $oPdf->getx();
  $oPdf->multicell(28,3,$sNotasEncontradas,0,"L",0);
  $iYlinha = $oPdf->gety();
  $oPdf->setxy($iXold+28,$iYold);
  $oPdf->cell(60, $iAltura, $oDadoEmpenho->z01_nome,   0, 0, "L", 0);
  $oPdf->cell(20, $iAltura, db_formatar($oDadoEmpenho->k12_valor, 'f'),  0, 0, "R", 0);
  $oPdf->cell(20, $iAltura, $oDadoEmpenho->k12_cheque, 0, 0, "R", 0);
  $oPdf->cell(15, $iAltura, $oDadoEmpenho->tipo,       0, 1, "C", 0);
  $oPdf->sety($iYlinha+1);
  
  $nValorSoma           += $oDadoEmpenho->k12_valor+0;
  $nValorTotalBanco     += $oDadoEmpenho->k12_valor+0;
  $nValorTotalRelatorio += $oDadoEmpenho->k12_valor+0;
  $iTotalRegistros++;
  $iTotalRegistroGeral++;
  $iQuantBanco++;
  $sTipoEmpenho = $oDadoEmpenho->tipo;

}

$oPdf->setfont('arial','B',9);
if($oGet->lQuebraConta){
  $oPdf->cell(280,$iAltura, "TOTAL DO BANCO ($iQuantBanco registros):  R$ ".db_formatar($nValorTotalBanco,"f"), "T", 1, "L", 1);
	$oPdf->ln(5);
}

$oPdf->cell(280,$iAltura,'TOTAL DE REGISTROS [2] :  '.$iTotalRegistros."   VALOR TOTAL  :R$ ".db_formatar($nValorSoma,"f"),"T",1,"L",0);
$oPdf->ln(10);

$oPdf->setfont('arial','B',12);
$oPdf->cell(280,$iAltura*2,'TOTAL DE GERAL      :  '.$iTotalRegistroGeral."   VALOR GERAL  :R$ ".db_formatar($nValorTotalRelatorio,"f"),"T",0,"L",0);
$oPdf->Output();

function imprimeCabecalho($oPdf, $iAltura) {
  
  $oPdf->addpage("L");
  $oPdf->SetFont('arial','b',8);
  $oPdf->cell(20, $iAltura, "Data Autent",     1, 0, "C", 1);
  $oPdf->cell(15, $iAltura, "Cod.Aut.",        1, 0, "C", 1);
  $oPdf->cell(15, $iAltura, "Cod.Con.",        1, 0, "C", 1);
  $oPdf->cell(40, $iAltura, "Descrição Conta", 1, 0, "C", 1);
  $oPdf->cell(18, $iAltura, "Empenho",         1, 0, "C", 1);
  $oPdf->cell(15, $iAltura, "Cód. Emp",        1, 0, "C", 1);
  $oPdf->cell(15, $iAltura, "Ordem",           1, 0, "C", 1);
  $oPdf->cell(28, $iAltura, "Notas Fiscais",   1, 0, "C", 1);
  $oPdf->cell(60, $iAltura, "Credor",          1, 0, "C", 1);
  $oPdf->cell(20, $iAltura, "Vlr. Autent",     1, 0, "C", 1);
  $oPdf->cell(20, $iAltura, "N° Cheque",       1, 0, "C", 1);
  $oPdf->cell(15, $iAltura, "Tipo",            1, 1, "C", 1);
  $iYlinha = $oPdf->getY();
}
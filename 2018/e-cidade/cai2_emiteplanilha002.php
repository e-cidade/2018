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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("fpdf151/PDFDocument.php");
require_once modification("libs/db_sql.php");

$oGet = db_utils::postMemory($_GET);


$clPlaCaixa     = new cl_placaixa;
$clPlaCaixaRec  = new cl_placaixarec;
$clTabPlan      = new cl_tabplan;
$clTabOrc       = new cl_taborc;
$oDaoRhPlanilha = new cl_rhempenhofolharubricaplanilha();

$clPlaCaixaRec->rotulo->label();

$oPdf = new PDFDocument();
$oPdf->addHeaderDescription("PLANILHA DE RECEITAS");

$sWherePlaCaixa  = "     k81_seqpla is not null ";
$sWherePlaCaixa .= " and k80_instit = ".db_getsession("DB_instit");

if (isset($oGet->codpla) && trim($oGet->codpla) != '' ) {
	$sWherePlaCaixa .= " and k80_codpla = {$oGet->codpla} ";
} else if ( isset($oGet->sListaPla) && trim($oGet->sListaPla) != ''  ) {
  $sWherePlaCaixa .= " and k80_codpla in ({$oGet->sListaPla}) ";
} else if ( isset($oGet->iPlaIni) || isset($oGet->iPlaFin) ) {

	if ( isset($oGet->iPlaIni) && trim($oGet->iPlaIni) != '' ) {
		$sWherePlaCaixa .= " and k80_codpla >= {$oGet->iPlaIni} ";
	}

  if ( isset($oGet->iPlaFin) && trim($oGet->iPlaFin) != '' ) {
    $sWherePlaCaixa .= " and k80_codpla <= {$oGet->iPlaFin} ";
  }

}
if ( isset($oGet->k144_numeroprocesso) && !empty($oGet->k144_numeroprocesso)) {

  $sWherePlaCaixa .= " and k144_numeroprocesso = '{$oGet->k144_numeroprocesso}' ";
}

$sSqlPlanilha    = $clPlaCaixa->sql_query_rec(null,"k80_codpla, k144_numeroprocesso",null,$sWherePlaCaixa);

$rsPlaCaixa      = $clPlaCaixa->sql_record($sSqlPlanilha);
$iLinhasPlaCaixa = $clPlaCaixa->numrows;

if ( $iLinhasPlaCaixa == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Planilha de caixa não cadastrada com este código ou de outra instituição.');
  exit;
}

if ( $iLinhasPlaCaixa >= 1 &&  isset($oGet->k144_numeroprocesso) && !empty($oGet->k144_numeroprocesso)) {

  $sProcesso = db_utils::fieldsMemory($rsPlaCaixa, 0)->k144_numeroprocesso;
  $oPdf->addHeaderDescription("PROCESSO ADMINISTRATIVO: {$sProcesso}");
}

$oPdf->Open();
$oPdf->AliasNbPages();
$iAlt  = 4;

for ( $iInd=0; $iInd < $iLinhasPlaCaixa; $iInd++ ) {

	$oPlaCaixa = db_utils::fieldsMemory($rsPlaCaixa,$iInd);

	$total = 0;

	$sSqlPlanilhaFolha = $oDaoRhPlanilha->sql_query( null, "*", null, "k80_codpla = {$oPlaCaixa->k80_codpla}" );
	$rsPlanilhaFolha   = $oDaoRhPlanilha->sql_record( "select exists({$sSqlPlanilhaFolha}) as folha" );

	$lPlanilhaFolha = db_utils::fieldsMemory($rsPlanilhaFolha, 0)->folha == 't';

	$sWherePlaCaixaRec = "k81_codpla = {$oPlaCaixa->k80_codpla}";

	$sSqlPlacaixaRec = $clPlaCaixaRec->sql_query(null,"*","k81_seqpla",$sWherePlaCaixaRec);
	$rsRec           = $clPlaCaixaRec->sql_record( $sSqlPlacaixaRec );
	$iLinhasRec      = $clPlaCaixaRec->numrows;

	for($iIndRec = 0; $iIndRec < $iLinhasRec;$iIndRec++){

		$oRec = db_utils::fieldsMemory($rsRec,$iIndRec);

	  if ($oPdf->gety() > $oPdf->h - 30 || $iIndRec==0 ) {

      $oPdf->AddPage();
      $oPdf->setfont('arial','b',14);
      $oPdf->cell(32,$iAlt,"Planilha Caixa:"         ,0,0,"L",0);
      $oPdf->cell(20,$iAlt,$oRec->k81_codpla               ,0,1,"R",0);
      $oPdf->ln();
      $oPdf->cell(20,$iAlt,"Data:"                   ,0,0,"L",0);
      $oPdf->cell(20,$iAlt,db_formatar($oRec->k80_data,'d'),0,1,"R",0);
      $oPdf->ln();

      if ($lPlanilhaFolha) {

      	$oPdf->cell(22, $iAlt, "Origem:", 0, 0, "L", 0);
	      $oPdf->cell($oPdf->getAvailWidth(), $iAlt, "Folha de Pagamento", 0, 1, "L", 0);
    		$oPdf->ln();
      }

      $oPdf->setfont('arial','b',8);
      $oPdf->cell(10,$iAlt,"Seq"       ,"B",0,"R",0);
      $oPdf->cell(15,$iAlt,"Codigo"    ,"B",0,"R",0);
      $oPdf->cell(15,$iAlt,"Conta"     ,"B",0,"R",0);
      $oPdf->cell(60,$iAlt,"Descrição" ,"B",0,"L",0);
      $oPdf->cell(15,$iAlt,"Receita"   ,"B",0,"R",0);
      $oPdf->cell(15,$iAlt,"Reduz"     ,"B",0,"R",0);
      $oPdf->cell(60,$iAlt,"Descrição" ,"B",0,"L",0);
      $oPdf->cell($oPdf->getAvailWidth(),$iAlt,"Valor"     ,"B",1,"R",0);
      $oPdf->setfont('arial','',6);
	  }

	   $oPdf->cell(10,$iAlt,$iIndRec+1  ,0,0,"R",0);
	   $oPdf->cell(15,$iAlt,$oRec->k81_seqpla ,0,0,"R",0);
	   $oPdf->cell(15,$iAlt,$oRec->k81_conta  ,0,0,"R",0);
	   $oPdf->cell(60,$iAlt,$oRec->k13_descr  ,0,0,"L",0);
	   $oPdf->cell(15,$iAlt,$oRec->k81_receita,0,0,"R",0);

	   // procuramos o reduzido para receitas-extra
	   $reduz="";

	   if ($oRec->k02_tipo=='O'){
	     $rr= $clTabOrc->sql_record($clTabOrc->sql_query(db_getsession("DB_anousu"),$oRec->k81_receita));
	     if ($clTabOrc->numrows > 0 ){
	         db_fieldsmemory($rr,0);
	         // aqui tenho o70_codrec
	         $reduz = $k02_codrec;
	     } else {
	         $reduz = 0;
	     }
	   } else {
	     $rr= $clTabPlan->sql_record($clTabPlan->sql_query($oRec->k81_receita,db_getsession("DB_anousu")));
	     if ($clTabPlan->numrows > 0 ){
	         db_fieldsmemory($rr,0);
	         // aqui tenho o reduzido
	         $reduz = $k02_reduz;
	     } else {
	         $reduz = 0;
	     }
	   }
	   $oPdf->cell(15,$iAlt,"(".$reduz.")",0,0,"R",0);
	   if ($reduz==0){
	      $oPdf->cell(45,$iAlt,"* REVISAR CADASTRO ",0,0,"L",0);
	   } else {
	      $oPdf->cell(45,$iAlt,substr($oRec->k02_drecei,0,20),0,0,"L",0);
	   }
	   $oPdf->cell($oPdf->getAvailWidth(), $iAlt,db_formatar($oRec->k81_valor,'f'),0,1,"R",0);

	   /**
	    * Imprime o nome / razao social
	    */
	   $oPdf->setfont('arial','B',6);
	   $oPdf->cell(25, $iAlt,"Nome / Razão Social: ",0,0,"L",0);
	   $oPdf->setfont('arial','',6);
	   $oPdf->cell(100,$iAlt,$oRec->z01_nome,0,0,"L",0);
	   $oPdf->setfont('arial','B',6);
	   $oPdf->cell(8, $iAlt,"CP:",0,0,"L",0);
	   $oPdf->setfont('arial','',6);
	   $oPdf->cell(57,$iAlt,"{$oRec->c58_estrutural} - {$oRec->c58_descr}", 0, 1, "L",0);

	   if($oRec->k81_obs!="") {

	     $oPdf->cell(25,$iAlt,"",0,0,"L",0);
	     $oPdf->multicell(165,$iAlt,$oRec->k81_obs,0,1,"L",0);
	   }
	   $total += $oRec->k81_valor;

	}

	$oPdf->setfont('arial','b',8);
	$oPdf->ln();
	$oPdf->cell(90,$iAlt,'TOTAL DE REGISTROS  :  '.$iLinhasRec,1,0,"L",0);
	$oPdf->cell(105,$iAlt,'Valor Total :  '.db_formatar($total,'f'),1,0,"R",0);

}

$oPdf->showPDF("Planilha_caixa_" . time());
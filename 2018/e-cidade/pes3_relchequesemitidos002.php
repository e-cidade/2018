<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_libpessoal.php");
include("libs/db_utils.php");
include("classes/db_rhemissaocheque_classe.php");
include("classes/db_rhemissaochequeitem_classe.php");
include("classes/db_selecao_classe.php");

$oGet = db_utils::postMemory($_GET);

$clselecao  = new cl_selecao();
$clgerasql  = new cl_gera_sql_folha();


  if ( $oGet->tipoGera == "p" ) {
    
    $sSigla = "r52";
    
    switch ($oGet->tipofol) {
      case "r48":
        $iTipoGera   = "8";
      break;
      case "r14":
        $iTipoGera   = "7";
      break;
      case "r35":
        $iTipoGera   = "10";
      break;
      case "r52":
        $iTipoGera   = "6";
      break;
      case "r20":
        $iTipoGera   = "9";
      break;                    
    }   
    
  } else {

  	$sSigla = $oGet->tipofol;
  	
    switch ($oGet->tipofol) {
      case "r48":
        $iTipoGera = "2";   
      break;
      case "r14":
        $iTipoGera = "1";    
      break;
      case "r35":
        $iTipoGera = "4";    
      break;
      case "r22":
        $iTipoGera = "5";    
      break;
      case "r20":
        $iTipoGera = "3";    
      break;                    
    }
    
  }

  
  switch ($oGet->tipo) {
    case "l":
      $sTipoResumo = "lotac" ;
      $sCampoWhere = "r70_estrut";
      $clgerasql->usar_lot = true;
      $lString     = true;
    break;
    case "o":
      $sTipoResumo = "orgao";
      $sCampoWhere = "o40_orgao";
      $clgerasql->usar_org = true;
      $lString     = false;
    break;
    case "m":
      $sTipoResumo = "regis" ;
      $sCampoWhere = "rh01_regist";
      $lString     = false;
    break;
    case "t":
      $sTipoResumo = "local" ;
      $sCampoWhere = "rh55_estrut";
      $clgerasql->usar_tra = true;
      $lString     = true;
    break;           
  }
  
  $sWhere = "1=1";
  
  if ( isset($oGet->filtro) ) {
    
    if ( $oGet->filtro == "s" && isset($oGet->{"f".$sTipoResumo}) && trim($oGet->{"f".$sTipoResumo}) != "" ) {
      $sWhere .= " and {$sCampoWhere} in ('".str_replace(',',"','",$oGet->{"f".$sTipoResumo})."') ";    	
    } else if ( $oGet->filtro == "i" && isset($oGet->{$sTipoResumo."i"}) && trim($oGet->{$sTipoResumo."i"} ) != "" ) {
      if ( $lString ) {
        $sWhere .= " and {$sCampoWhere} >= '".$oGet->{$sTipoResumo."i"}."'";
      } else {
        $sWhere .= " and {$sCampoWhere} >= ".$oGet->{$sTipoResumo."i"};
      }    	
    }
  
    if ( $oGet->filtro == "i" && isset($oGet->{$sTipoResumo."f"}) && trim($oGet->{$sTipoResumo."f"} ) != "" ) {
      if ( $lString ) {
        $sWhere .= " and {$sCampoWhere} <= '".$oGet->{$sTipoResumo."f"}."'";
      } else {
        $sWhere .= " and {$sCampoWhere} <= ".$oGet->{$sTipoResumo."f"};
      }    	
    }
    
  }
  
  if( isset($oGet->selecao) && trim($oGet->selecao) != ""){
    $rsSelecao = $clselecao->sql_record($clselecao->sql_query_file($oGet->selecao,db_getsession("DB_instit"),"r44_where"));
    if($clselecao->numrows > 0){
      $oSelecao = db_utils::fieldsMemory($rsSelecao,0);
      $sWhere  .= " and ".$oSelecao->r44_where;
    }
  }
  
  
  $clgerasql->usar_pes = true;

  $sCamposDados  = "rh02_regist, ";
  $sCamposDados .= "rh02_anousu, ";
  $sCamposDados .= "rh02_mesusu  ";
  
  $sSqlDados     = $clgerasql->gerador_sql($sSigla,$oGet->anofolha,$oGet->mesfolha,null,null,$sCamposDados,"",$sWhere);
  
  $sCampos  = " r18_regist,    ";
  $sCampos .= " z01_nome,      ";
  $sCampos .= " r18_numcheque, ";
  $sCampos .= " r15_dtgeracao, ";
  $sCampos .= " r18_valor      ";
  
  $sSqlConsultaReg  = " select {$sCampos}                                                                       "; 
  $sSqlConsultaReg .= "   from ({$sSqlDados}) as x                                                              ";
  $sSqlConsultaReg .= "        inner join rhemissaochequeitem on rhemissaochequeitem.r18_anousu = x.rh02_anousu ";
  $sSqlConsultaReg .= "                                      and rhemissaochequeitem.r18_mesusu = x.rh02_mesusu ";
  $sSqlConsultaReg .= "                                      and rhemissaochequeitem.r18_regist = x.rh02_regist ";
  $sSqlConsultaReg .= "        inner join rhemissaocheque     on rhemissaocheque.r15_sequencial = rhemissaochequeitem.r18_emissaocheque ";
  $sSqlConsultaReg .= "        inner join cgm                 on cgm.z01_numcgm                 = rhemissaochequeitem.r18_numcgm ";
  $sSqlConsultaReg .= "  where r18_anousu = {$oGet->anofolha}                                                   ";  
  $sSqlConsultaReg .= "    and r18_mesusu = {$oGet->mesfolha}                                                   ";
  $sSqlConsultaReg .= "    and r18_tipo   = {$iTipoGera}                                                        ";

  $rsConsultaReg = db_query($sSqlConsultaReg);
  $iLinhasReg    = pg_num_rows($rsConsultaReg);
  
  
  if ( $iLinhasReg ==  0 ) {
  	db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado');
  }

  
$head2 = "RELATÓRIO DE CHEQUES EMITIDOS";
  
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AddPage();
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);

$iAlt        = 4;
$lTroca      = 1;
$nValorTotal = 0;

imprimeCabecalho($pdf,$iAlt);

for ( $iInd=0; $iInd < $iLinhasReg; $iInd++ ) {
	
  if( $pdf->gety() > $pdf->h - 20 ){
     $pdf->AddPage();
     $troca = 1;
  }

	$oRegistros = db_utils::fieldsMemory($rsConsultaReg,$iInd);
	
	if ( $lTroca == 1 ) { 
	  $lTroca = 0;
	} else {
	  $lTroca = 1;
	}
	
  $pdf->cell(30,$iAlt,$oRegistros->r18_regist                    ,0,0,"C",$lTroca);
  $pdf->cell(70,$iAlt,$oRegistros->z01_nome                      ,0,0,"L",$lTroca);
  $pdf->cell(30,$iAlt,$oRegistros->r18_numcheque                 ,0,0,"C",$lTroca);
  $pdf->cell(30,$iAlt,db_formatar($oRegistros->r15_dtgeracao,"d"),0,0,"C",$lTroca);
  $pdf->cell(30,$iAlt,db_formatar($oRegistros->r18_valor,"f")    ,0,1,"R",$lTroca);	
  
  $nValorTotal += $oRegistros->r18_valor;
  
}
  $pdf->ln(5);
  $pdf->setfont('arial','B',7);
  $pdf->cell(160,$iAlt,"TOTAL REGISTROS: ".$iLinhasReg             ,0,0,"L",0);
  $pdf->cell(30,$iAlt,"VALOR TOTAL:".db_formatar($nValorTotal,"f"),0,1,"R",0);


$pdf->Output();

function imprimeCabecalho($pdf,$iAlt){
	
	$pdf->setfont('arial','B',7);
  $pdf->cell(30,$iAlt,"Matrícula"   ,1,0,"C",1);
  $pdf->cell(70,$iAlt,"Nome"        ,1,0,"C",1);
  $pdf->cell(30,$iAlt,"Nº Cheque"   ,1,0,"C",1);
  $pdf->cell(30,$iAlt,"Data Emissão",1,0,"C",1);
  $pdf->cell(30,$iAlt,"Valor"       ,1,1,"C",1);
  $pdf->setfont('arial','',7);      
  
}

?>
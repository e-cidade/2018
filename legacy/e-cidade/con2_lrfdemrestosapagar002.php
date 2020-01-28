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

if (!isset($arqinclude)){
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("fpdf151/assinatura.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empresto_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_orcparamrelnota_classe.php");

$clempresto   	   = new cl_empresto();
$classinatura 	   = new cl_assinatura(); 
$cldb_config  	   = new cl_db_config();
$clorcparamrelnota = new cl_orcparamrelnota;

$oGet            = db_utils::postMemory($_GET);
// instituições selecionadas na tela
$aListaSelInstit = explode("-",$oGet->db_selinstit);

$iAnoUsu	       = db_getsession('DB_anousu');
$db_selinstit    = $oGet->db_selinstit;

}

// Variáveis dispolibilizadasd para outros relatório
$nTotalEmpNaoLiq = 0;
$nTotSufAntInscr = 0;


if (!isset($lGeraPDF)){
  $lGeraPDF = true;
}

 


// Todas instituições
$aListaInstit     = array();
$rsListaInstit    = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
$iNroLinhasInstit = pg_num_rows($rsListaInstit);

for ($i=0; $i < $iNroLinhasInstit; $i++) {
  $oListaInstit   = db_utils::fieldsMemory($rsListaInstit,$i);	
  $aListaInstit[] = $oListaInstit->codigo; 	
}

$sListaInstit = implode(",",$aListaInstit);  

$sDataIni 	  = "$iAnoUsu-01-01"; 
$sDataFin	  = "$iAnoUsu-12-31";

$sWhereInstit      = "e60_instit in ({$sListaInstit})";

$sSubSqlPeriodoAnt = $clempresto->sql_rp($iAnoUsu, $sWhereInstit, $sDataIni, $sDataFin);

$sSqlPeriodoAnt  = " select * from (														   ";
$sSqlPeriodoAnt .= "                select e60_instit,										   ";
$sSqlPeriodoAnt .= "                       e60_numemp,										   ";
$sSqlPeriodoAnt .= "                       o58_orgao,										   ";
$sSqlPeriodoAnt .= "                       o40_descr,										   ";
$sSqlPeriodoAnt .= "                       o58_codigo,										   ";
$sSqlPeriodoAnt .= "                       o15_descr,										   ";
$sSqlPeriodoAnt .= "                       db21_tipoinstit,								 	   ";
$sSqlPeriodoAnt .= "                       sum(case when e60_anousu < ({$iAnoUsu} - 1) then    ";
$sSqlPeriodoAnt .= "                                e91_vlrliq-e91_vlrpag					   ";      
$sSqlPeriodoAnt .= "                            else 0 end ) as  inscricao_ant,			 	   ";
$sSqlPeriodoAnt .= "                       sum(case when e60_anousu = ({$iAnoUsu} - 1) then    ";
$sSqlPeriodoAnt .= "                                e91_vlrliq-e91_vlrpag					   ";
$sSqlPeriodoAnt .= "                           else 0 end ) as  valor_processado,			   ";
$sSqlPeriodoAnt .= "                       sum(case when e60_anousu < ({$iAnoUsu} - 1) then    ";
$sSqlPeriodoAnt .= "                                e91_vlremp-e91_vlranu-e91_vlrliq		   ";
$sSqlPeriodoAnt .= "                           else 0 end ) as  valor_nao_processado_ant,	   ";
$sSqlPeriodoAnt .= "                       sum(case when e60_anousu = ({$iAnoUsu} - 1) then    ";
$sSqlPeriodoAnt .= "                                e91_vlremp-e91_vlranu-e91_vlrliq		   ";
$sSqlPeriodoAnt .= "                           else 0 end ) as  valor_nao_processado,		   ";
$sSqlPeriodoAnt .= "                       sum(coalesce(vlrliq,0)) as vlrliq,		     	   ";
$sSqlPeriodoAnt .= "                       sum(coalesce(vlranuliq,0)) as canc_proc,		 	   ";
$sSqlPeriodoAnt .= "                       sum(coalesce(vlranunliq,0)) as canc_nproc,		   ";
$sSqlPeriodoAnt .= "                       sum(coalesce(vlrpag,0)) as vlrpag,				   ";
$sSqlPeriodoAnt .= "                       sum(coalesce(vlrpagliq,0)) as vlrpagliq			   ";
$sSqlPeriodoAnt .= "                  from ({$sSubSqlPeriodoAnt}) as x					 	   ";
$sSqlPeriodoAnt .= "                       inner join db_config on codigo = e60_instit		   ";
$sSqlPeriodoAnt .= "                 where substr(o56_elemento,4,2) != '91'					   ";
$sSqlPeriodoAnt .= "                 group by e60_instit,									   ";
$sSqlPeriodoAnt .= "                          e60_numemp,								   	   ";
$sSqlPeriodoAnt .= "                          db21_tipoinstit,								   ";
$sSqlPeriodoAnt .= "                          o58_orgao,									   ";
$sSqlPeriodoAnt .= "                          o40_descr,								 	   ";
$sSqlPeriodoAnt .= "                          o58_codigo,									   ";
$sSqlPeriodoAnt .= "                          o15_descr									 	   ";
$sSqlPeriodoAnt .= "               ) as foo												 	   "; 
$sSqlPeriodoAnt .= "        order by o58_orgao,o58_codigo,db21_tipoinstit		  			   ";

//die($sSqlPeriodoAnt);
//db_criatabela($rsPeriodoAnt);exit;

$rsPeriodoAnt 		 = pg_query($sSqlPeriodoAnt);
$iNroLinhaPeriodoAnt = pg_num_rows($rsPeriodoAnt);

$aPagProc	 = 0;
$aPagNaoProc = 0;

for ($iInd = 0; $iInd < $iNroLinhaPeriodoAnt; $iInd++){
  
   $oPeriodoAnt  = db_utils::fieldsMemory($rsPeriodoAnt, $iInd);
   
   if ( $oPeriodoAnt->db21_tipoinstit == 1 || $oPeriodoAnt->db21_tipoinstit == 2 ) {
     $lAdminDireta = true; 
   } else {
   	 $lAdminDireta = false;
   }
   
   $sNomeOrgao	 = $oPeriodoAnt->o58_orgao."-".$oPeriodoAnt->o40_descr;
   $sNomeRecurso = $oPeriodoAnt->o58_codigo."-".$oPeriodoAnt->o15_descr;
      
   if (!in_array($oPeriodoAnt->e60_instit,$aListaSelInstit)){
     if (!isset($aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior'])){
   	   $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['Processado']      = 0;
       $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['NaoProcessado']   = 0;
     }  	
     if (!isset($aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual'])){   
       $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['Processado']         = 0;
       $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['NaoProcessado']      = 0;
       $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['EmpCancNaoInscr']    = 0;     
     }
   	 if (!isset($aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior'])) {
   	   $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['Processado']     = 0;
       $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['NaoProcessado']  = 0;
   	 }
     if (!isset($aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual'])){
  	  $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['Processado']      	  = 0;
      $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['NaoProcessado']   	  = 0;
      $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['EmpCancNaoInscr'] 	  = 0;
  	 }         
     continue;
   }
   

   $sSqlEmpresto  = " select e91_vlrliq-e91_vlrpag as vlrpag 		   "; 
   $sSqlEmpresto .= "    from empresto   							   ";
   $sSqlEmpresto .= "   where e91_anousu = ".($iAnoUsu+1)."			   "; 
   $sSqlEmpresto .= "     and e91_numemp  = {$oPeriodoAnt->e60_numemp} ";
   
   $rsEmpresto = pg_query($sSqlEmpresto);
   
   if(pg_num_rows($rsEmpresto) > 0){
   	 $oEmpresto = db_utils::fieldsMemory($rsEmpresto,0);
   	 $nVlrpag   = $oEmpresto->vlrpag;
   }else{
     $nVlrpag   = 0;
   }
   
   $naPagarProcessado    = $nVlrpag;
   $naPagarNaoProcessado = ($oPeriodoAnt->valor_nao_processado+$oPeriodoAnt->valor_nao_processado_ant)-$oPeriodoAnt->canc_nproc - $oPeriodoAnt->vlrliq;
    
   
   if (isset($aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior'])){
     $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['Processado']      += $naPagarProcessado;
     $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['NaoProcessado']   += $naPagarNaoProcessado;
   } else {
     $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['Processado']      = $naPagarProcessado;
     $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['NaoProcessado']   = $naPagarNaoProcessado;   	
   }

   if (isset($aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior'])){
     $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['Processado']      += $naPagarProcessado;
     $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['NaoProcessado']   += $naPagarNaoProcessado;
   } else {
     $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['Processado']      = $naPagarProcessado;
     $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['NaoProcessado']   = $naPagarNaoProcessado;   	
   }   

   if (!isset($aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual'])){   
   	 $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['Processado']      = 0;
     $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['NaoProcessado']   = 0;
     $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['EmpCancNaoInscr'] = 0;     
   }
   
   if (!isset($aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual'])){   
     $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['Processado']      = 0;
     $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['NaoProcessado']   = 0;
     $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['EmpCancNaoInscr'] = 0;     
   }   
   
}




$sWhereDotacao		 = " w.o58_instit in ({$sListaInstit}) ";
$sSubSqlDotacaoSaldo = db_dotacaosaldo(8,2,3,false,$sWhereDotacao,$iAnoUsu,$sDataIni,$sDataFin,8,0,true);

//die($sSubSqlDotacaoSaldo);

$sSqlDotacaoSaldo  = " select o58_instit, 															  ";
$sSqlDotacaoSaldo .= "        db21_tipoinstit,  													  ";
$sSqlDotacaoSaldo .= "        case when x.o58_orgao = -1 then 0 else x.o58_orgao end as o58_orgao,    ";
$sSqlDotacaoSaldo .= "        o40_descr, 															  ";
$sSqlDotacaoSaldo .= "        case when x.o58_coddot = -1 then 0 else x.o58_coddot end as o58_coddot, ";
$sSqlDotacaoSaldo .= "        case when x.o58_codigo = -1 then 0 else x.o58_codigo end as o58_codigo, ";
$sSqlDotacaoSaldo .= "        o15_descr, 															  ";
$sSqlDotacaoSaldo .= "        sum(empenhado_acumulado) as empenhado_acumulado, 						  ";
$sSqlDotacaoSaldo .= "        sum(anulado_acumulado)   as anulado_acumulado, 						  ";
$sSqlDotacaoSaldo .= "        sum(liquidado_acumulado) as liquidado_acumulado, 						  ";
$sSqlDotacaoSaldo .= "        sum(pago_acumulado)      as pago_acumulado 							  ";
$sSqlDotacaoSaldo .= "   from ( {$sSubSqlDotacaoSaldo} ) as x										  ";
$sSqlDotacaoSaldo .= "       inner join orcdotacao ocd on ocd.o58_coddot  = x.o58_coddot 			  ";
$sSqlDotacaoSaldo .= "                                and ocd.o58_anousu  = {$iAnoUsu} 				  ";
$sSqlDotacaoSaldo .= "       inner join db_config      on codigo 		  = ocd.o58_instit	 		  ";
$sSqlDotacaoSaldo .= "       left  join orcorgao o     on o40_anousu  	  = {$iAnoUsu}			  	  ";
$sSqlDotacaoSaldo .= "                                and o.o40_orgao 	  = x.o58_orgao			  	  ";
$sSqlDotacaoSaldo .= "       left  join orctiporec otr on o15_codigo  	  = x.o58_codigo		 	  ";
$sSqlDotacaoSaldo .= " group by o58_instit,															  ";
$sSqlDotacaoSaldo .= "          db21_tipoinstit,  													  ";
$sSqlDotacaoSaldo .= "          x.o58_orgao, 														  ";
$sSqlDotacaoSaldo .= "          o40_descr, 															  ";
$sSqlDotacaoSaldo .= "          x.o58_codigo,														  ";
$sSqlDotacaoSaldo .= "          o15_descr, 															  ";
$sSqlDotacaoSaldo .= "          x.o58_coddot 														  ";
$sSqlDotacaoSaldo .= " order by o58_instit, 														  ";
$sSqlDotacaoSaldo .= "          x.o58_orgao, 														  ";
$sSqlDotacaoSaldo .= "          db21_tipoinstit,  													  ";
$sSqlDotacaoSaldo .= "          x.o58_codigo 														  ";

$rsDotacaoSaldo 		= pg_query($sSqlDotacaoSaldo);
$iNroLinhasDotacaoSaldo = pg_num_rows($rsDotacaoSaldo);

//die($sSqlDotacaoSaldo);


for ($iInd=0; $iInd < $iNroLinhasDotacaoSaldo; $iInd++) {
	
  $oDotacaoSaldo = db_utils::fieldsMemory($rsDotacaoSaldo,$iInd);	
	
  $sNomeOrgao   = $oDotacaoSaldo->o58_orgao."-".$oDotacaoSaldo->o40_descr;
  $sNomeRecurso = $oDotacaoSaldo->o58_codigo."-".$oDotacaoSaldo->o15_descr;
   
  if ( $oDotacaoSaldo->db21_tipoinstit == 1 || $oDotacaoSaldo->db21_tipoinstit == 2 ) {
    $lAdminDireta = true; 
  } else {
    $lAdminDireta = false;
  }

  if (!in_array($oDotacaoSaldo->o58_instit,$aListaSelInstit)){
  	if (!isset($aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual'])){
  	  $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['Processado']      	  = 0;
      $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['NaoProcessado']   	  = 0;
      $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['EmpCancNaoInscr'] 	  = 0;
  	}
    if (!isset($aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior'])){
  	  $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['Processado']      = 0;
      $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['NaoProcessado']   = 0;
  	}  	
  	if (!isset($aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual'])){
  	  $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['Processado']          	  = 0;
      $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['NaoProcessado']      	  = 0;
      $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['EmpCancNaoInscr']  	  = 0;    
  	}
   	if (!isset($aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior'])){
  	  $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['Processado']          = 0;
      $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['NaoProcessado']       = 0;
  	}
	continue;
  }  
  
  
  $naPagarNaoProcessado = $oDotacaoSaldo->empenhado_acumulado - $oDotacaoSaldo->anulado_acumulado - $oDotacaoSaldo->liquidado_acumulado;
  $naPagarProcessado    = $oDotacaoSaldo->liquidado_acumulado - $oDotacaoSaldo->pago_acumulado;
  $sSqlEmpCanc  = " select sum(e94_valor) as e94_valor 						";
  $sSqlEmpCanc .= "   from empanulado 									    "; 
  $sSqlEmpCanc .= "    	   inner join empempenho on e94_numemp = e60_numemp "; 
  $sSqlEmpCanc .= "   	   inner join orcdotacao on o58_anousu = e60_anousu ";
  $sSqlEmpCanc .= "    						    and o58_instit = e60_instit ";
  $sSqlEmpCanc .= "  where e60_anousu = {$iAnoUsu}  						";
  $sSqlEmpCanc .= "    and e60_instit in ({$oDotacaoSaldo->o58_instit})     "; 
  $sSqlEmpCanc .= "    and o58_codigo = {$oDotacaoSaldo->o58_codigo} 	    ";
  $sSqlEmpCanc .= "    and o58_orgao  = {$oDotacaoSaldo->o58_orgao} 	    ";
  $sSqlEmpCanc .= "    and e94_empanuladotipo = 1						    ";

  
  $rsEmpCanc = pg_query($sSqlEmpCanc);
  $oEmpCanc  = db_utils::fieldsMemory($rsEmpCanc,0);
  $nEmpCancNaoProcessado = $oEmpCanc->e94_valor;

  
  if (isset($aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual'])){
    $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['Processado']      += $naPagarProcessado;
    $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['NaoProcessado']   += $naPagarNaoProcessado;
    $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['EmpCancNaoInscr'] += $nEmpCancNaoProcessado;
  } else {
    $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['Processado']      = $naPagarProcessado;
    $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['NaoProcessado']   = $naPagarNaoProcessado;
    $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAtual']['EmpCancNaoInscr'] = $nEmpCancNaoProcessado;       	
  }  

  if (isset($aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual'])){
    $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['Processado']      += $naPagarProcessado;
    $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['NaoProcessado']   += $naPagarNaoProcessado;
    $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['EmpCancNaoInscr'] += $nEmpCancNaoProcessado;
  } else {
    $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['Processado']      = $naPagarProcessado;
    $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['NaoProcessado']   = $naPagarNaoProcessado;   	
    $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAtual']['EmpCancNaoInscr'] = $nEmpCancNaoProcessado;
  }     
  

  if (!isset($aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior'])){
    $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['Processado']    = 0;
    $aRecurso[$lAdminDireta][$sNomeRecurso]['ExercAnterior']['NaoProcessado'] = 0;
  }
  
  if (!isset($aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior'])){
    $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['Processado']     = 0;
    $aOrgao[$lAdminDireta][$sNomeOrgao]['ExercAnterior']['NaoProcessado']  = 0;
  }    

  $nTotalEmpNaoLiq += $naPagarNaoProcessado;
  
}


// Busca valor da relatório ANEXO V -Demostrativo da Disponibilidade de Caixa


$lGerarPDF = false;
include("con2_lrfdispcaixa002_2008.php");

// Antigamente
//$nTotSufAntInscr = $nSuficiencia; - $nTotalRPinscritos;

// T26286
$nTotSufAntInscr = $nSuficiencia;// - $nTotalRPinscritos;



if ( $lGeraPDF ) {

	
$rsConfig = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'),"codigo,munic"));
$oConfig  = db_utils::fieldsMemory($rsConfig,0);

$head2  = "MUNICÍPIO DE {$oConfig->munic}";
$head3  = "RELATÓRIO DE GESTÃO FISCAL";
$head4  = "DEMONSTRATIVO DOS RESTOS A PAGAR";
$head5  = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  
	
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->addpage();

$iAlt  = 4;

$pdf->setfont('arial','',5);

$pdf->cell(170,$iAlt,'RREO - ANEXO VI (LRF, art.55, inciso III, alínea "b")',"0",0,"L",0);
$pdf->cell(20 ,$iAlt,"R$ 1,00"												,"0",1,"R",0);

getHeader("ORGÃO",$pdf,$iAlt);

$nValProcAtu    = 0; 
$nValNaoProcAtu = 0;
$nValEmpCanc    = 0;

$nTotProcAnt    = 0;
$nTotProcAtu    = 0;
$nTotNaoProcAnt = 0; 
$nTotNaoProcAtu = 0;
$nTotEmpCanc    = 0;


foreach ( $aOrgao as $lAdminDireta => $aOrgao2){
  
  getSubHeader($lAdminDireta,$pdf,$iAlt);
  
  foreach ( $aOrgao2 as $sDescrOrgao => $aOrgao3){
  	
  	if(($pdf->gety() > $pdf->h-30)) {
  	  
  	  $pdf->cell(190,$iAlt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0); 
	  $pdf->AddPage();
	  $pdf->cell(190,$iAlt,'Continuação '.($pdf->pageNo()-1)."/{nb}","B",1,"R",0); 
  	  
  	  getHeader("ORGÃO",$pdf,$iAlt);
  	  
  	}	
  	
    foreach ( $aOrgao3 as $sTipoExerc => $aOrgaoVal) {
    	  	
  	  if ( $sTipoExerc == "ExercAnterior") {
	    $nValProcAnt    = $aOrgaoVal['Processado']; 
	    $nValNaoProcAnt = $aOrgaoVal['NaoProcessado'];
  	  } else {
  	  	$nValProcAtu    = $aOrgaoVal['Processado']; 
	    $nValNaoProcAtu = $aOrgaoVal['NaoProcessado'];
	    $nValEmpCanc    = $aOrgaoVal['EmpCancNaoInscr'];
  	  }
  	  
    }
    
    $pdf->Cell(60,$iAlt,"  ".$sDescrOrgao				  ,"R",0,"L",0);
    $pdf->Cell(25,$iAlt,db_formatar($nValProcAnt,"f")	  ,"R",0,"R",0);
    $pdf->Cell(25,$iAlt,db_formatar($nValProcAtu,"f")	  ,"R",0,"R",0);
    $pdf->Cell(25,$iAlt,db_formatar($nValNaoProcAnt,"f")  ,"R",0,"R",0);
    $pdf->Cell(25,$iAlt,db_formatar($nValNaoProcAtu,"f")  ,"R",0,"R",0);
    $pdf->Cell(30,$iAlt,db_formatar($nValEmpCanc,"f")     ,"0",1,"R",0);

    $nTotProcAnt    += $nValProcAnt;
    $nTotProcAtu    += $nValProcAtu;
    $nTotNaoProcAnt += $nValNaoProcAnt; 
    $nTotNaoProcAtu += $nValNaoProcAtu;
    $nTotEmpCanc    += $nValEmpCanc;
    
  }
  
}

$pdf->Cell(60,$iAlt,"TOTAL "					      ,"TBR",0,"L",0);
$pdf->Cell(25,$iAlt,db_formatar($nTotProcAnt,"f")	  ,  "1",0,"R",0);
$pdf->Cell(25,$iAlt,db_formatar($nTotProcAtu,"f")	  ,  "1",0,"R",0);
$pdf->Cell(25,$iAlt,db_formatar($nTotNaoProcAnt,"f")  ,  "1",0,"R",0);
$pdf->Cell(25,$iAlt,db_formatar($nTotNaoProcAtu,"f")  ,  "1",0,"R",0);
$pdf->Cell(30,$iAlt,db_formatar($nTotEmpCanc,"f")     , "TB",1,"R",0);



$pdf->Ln();

$pdf->Cell(160,$iAlt,"SUFICIÊNCIA ANTES DA INSCRIÇÃO EM RESTOS A PAGAR NÃO PROCESSADOS" ,"TR",0,"L",0);
$pdf->Cell( 30,$iAlt,db_formatar($nTotSufAntInscr,"f")  							    , "T",1,"R",0);
$pdf->Cell(160,$iAlt,"( Apurado no Anexo V - Demostrativo da Disponibilidade de Caixa )","BR",0,"L",0);
$pdf->Cell( 30,$iAlt,""																    , "B",1,"R",0);

$pdf->Ln();


getHeader("RECURSO",$pdf,$iAlt);

$nValProcAtu    = 0; 
$nValNaoProcAtu = 0;
$nValEmpCanc    = 0;

$nTotProcAnt    = 0;
$nTotProcAtu    = 0;
$nTotNaoProcAnt = 0; 
$nTotNaoProcAtu = 0;
$nTotEmpCanc    = 0;


foreach ( $aRecurso as $lAdminDireta => $aRecurso2){
	
  getSubHeader($lAdminDireta,$pdf,$iAlt);	
	
  foreach ( $aRecurso2 as $sDescrRecurso => $aRecurso3){
  	
  	if(($pdf->gety() > $pdf->h-30)) {

  	  $pdf->cell(190,$iAlt,'Continua na Página '.($pdf->pageNo()+1)."/{nb}","TB",1,"R",0); 
	  $pdf->AddPage();
	  $pdf->cell(190,$iAlt,'Continuação '.($pdf->pageNo()-1)."/{nb}","B",1,"R",0);
	    		
  	  getHeader("RECURSO",$pdf,$iAlt);
  	  
  	}
  	
    foreach ( $aRecurso3 as $sTipoExerc => $aRecursoVal) {
    	  	
  	  if ( $sTipoExerc == "ExercAnterior") {
	    $nValProcAnt    = $aRecursoVal['Processado']; 
	    $nValNaoProcAnt = $aRecursoVal['NaoProcessado'];
  	  } else {
  	  	$nValProcAtu    = $aRecursoVal['Processado']; 
	    $nValNaoProcAtu = $aRecursoVal['NaoProcessado'];
	    $nValEmpCanc    = $aRecursoVal['EmpCancNaoInscr'];
  	  }
  	  
    }
    
    $pdf->Cell(60,$iAlt,"  ".$sDescrRecurso				  ,"R",0,"L",0);
    $pdf->Cell(25,$iAlt,db_formatar($nValProcAnt,"f")	  ,"R",0,"R",0);
    $pdf->Cell(25,$iAlt,db_formatar($nValProcAtu,"f")	  ,"R",0,"R",0);
    $pdf->Cell(25,$iAlt,db_formatar($nValNaoProcAnt,"f")  ,"R",0,"R",0);
    $pdf->Cell(25,$iAlt,db_formatar($nValNaoProcAtu,"f")  ,"R",0,"R",0);
    $pdf->Cell(30,$iAlt,db_formatar($nValEmpCanc,"f")     ,"0",1,"R",0);

    $nTotProcAnt    += $nValProcAnt;
    $nTotProcAtu    += $nValProcAtu;
    $nTotNaoProcAnt += $nValNaoProcAnt; 
    $nTotNaoProcAtu += $nValNaoProcAtu;
    $nTotEmpCanc    += $nValEmpCanc;
    
  }
  
}

$pdf->Cell(60,$iAlt,"TOTAL "					      ,"TBR",0,"L",0);
$pdf->Cell(25,$iAlt,db_formatar($nTotProcAnt,"f")	  ,  "1",0,"R",0);
$pdf->Cell(25,$iAlt,db_formatar($nTotProcAtu,"f")	  ,  "1",0,"R",0);
$pdf->Cell(25,$iAlt,db_formatar($nTotNaoProcAnt,"f")  ,  "1",0,"R",0);
$pdf->Cell(25,$iAlt,db_formatar($nTotNaoProcAtu,"f")  ,  "1",0,"R",0);
$pdf->Cell(30,$iAlt,db_formatar($nTotEmpCanc,"f")     , "TB",1,"R",0);

// Verifica se foi incluido Notas explicativas em algum dos períodos: 6º Bimestre, 3º Quadrimestre, 2º Semestre 
  
$sWhereNota  = " orcparamrelnota.o42_codparrel = 25 			 "; 
$sWhereNota .= " and o42_anousu = {$iAnoUsu} 					 ";
$sWhereNota .= " and o42_instit = ".db_getsession('DB_instit')." ";
$sWhereNota .= " and o42_periodo in ('6B','3Q','2S')			 ";
  
$rsNotas 	   = $clorcparamrelnota->sql_record($clorcparamrelnota->sql_query_file(null,null,null,null,"o42_periodo",null,$sWhereNota));
$iNroLinhaNota = $clorcparamrelnota->numrows;

if ($iNroLinhaNota > 0) {
  $oNota = db_utils::fieldsMemory($rsNotas,0);
  notasExplicativas(&$pdf,58,$oNota->o42_periodo,190);
} else {
  notasExplicativas(&$pdf,58,'1B',190);	 
}

$pdf->Ln($iAlt*2);

assinaturas(&$pdf,&$classinatura,'GF');

$pdf->Output();

}


function getHeader($sDescricao,$pdf,$iAlt){
	
  $pdf->Cell( 60,$iAlt,""						     ,"TR",0,"L",0);
  $pdf->Cell(100,$iAlt,"RESTOS A PAGAR INSCRITOS"    , "1",0,"C",0);
  $pdf->Cell( 30,$iAlt,"EMPENHOS"				     , "T",1,"C",0);
	
  $pdf->Cell(60,$iAlt,""							 , "R",0,"C",0);
  $pdf->Cell(50,$iAlt,"Liquidados e Não Pagos"	 	 , "R",0,"C",0);
  $pdf->Cell(50,$iAlt,"Empenhados e Não Liquidados"  , "R",0,"C",0);
  $pdf->Cell(30,$iAlt,"CANCELADOS E"				 , "0",1,"C",0);
	
  $pdf->Cell(60,$iAlt,$sDescricao		  			 , "R",0,"C",0);
  $pdf->Cell(50,$iAlt,"(Processados)"	   			 ,"BR",0,"C",0);
  $pdf->Cell(50,$iAlt,"(Não Processados)"			 ,"BR",0,"C",0);
  $pdf->Cell(30,$iAlt,"NÂO INSCRITOS"	   			 , "0",1,"C",0);
	
  $pdf->Cell(60,$iAlt,""			   				 , "R",0,"C",0);
  $pdf->Cell(25,$iAlt,"De Exercícios"				 ,"TR",0,"C",0);
  $pdf->Cell(25,$iAlt,"Do Exercício" 				 ,"TR",0,"C",0);
  $pdf->Cell(25,$iAlt,"De Exercício" 				 ,"TR",0,"C",0);
  $pdf->Cell(25,$iAlt,"Do Exercícios"				 ,"TR",0,"C",0);
  $pdf->Cell(30,$iAlt,"POR INSUFICIÊNCIA"			 , "0",1,"C",0);
	
  $pdf->Cell(60,$iAlt,""							 ,"BR",0,"C",0);
  $pdf->Cell(25,$iAlt,"Anteriores"				 	 ,"BR",0,"C",0);
  $pdf->Cell(25,$iAlt,""							 ,"BR",0,"C",0);
  $pdf->Cell(25,$iAlt,"Anteriores"					 ,"BR",0,"C",0);
  $pdf->Cell(25,$iAlt,""							 ,"BR",0,"C",0);	
  $pdf->Cell(30,$iAlt,"FINANCEIRA"				 	 , "B",1,"C",0);
	
}

function getSubHeader($lAdminDireta,$pdf,$iAlt){
	
  if ($lAdminDireta){
    $pdf->Cell(60,$iAlt*2,"ADMINISTRAÇÃO DIRETA"  ,"R",0,"L",0);
  } else {
  	$pdf->Cell(60,$iAlt*2,"ADMINISTRAÇÃO INDIRETA","R",0,"L",0);
  }
  
  $pdf->Cell(25,$iAlt*2,"","R",0,"R",0);
  $pdf->Cell(25,$iAlt*2,"","R",0,"R",0);
  $pdf->Cell(25,$iAlt*2,"","R",0,"R",0);
  $pdf->Cell(25,$iAlt*2,"","R",0,"R",0);
  $pdf->Cell(30,$iAlt*2,"","0",1,"R",0);	
	
}
?>
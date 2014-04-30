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

require_once("libs/db_liborcamento.php");
require_once("libs/db_utils.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");

db_postmemory($_POST);

$oJson          = new services_json();
$aListaTipos    = $oJson->decode(str_replace("\\", "", $aListaTipo));
$aListaDeptos   = $oJson->decode(str_replace("\\", "", $aListaDepartamentos));
$sDepartamentos = "";
if (count($aListaDeptos) > 0) {
  foreach ($aListaDeptos as $iDepto) {
    $sDepartamentos .= "{$iDepto},";
  }
  $sDepartamentos = substr($sDepartamentos, 0, -1);
}
$iNroLista      = count($aListaTipos);
$sWhereTipo     = "";
$sCamposTipo    = "";

$aDataFinal     = explode('/',$data_fin);
$sDataFinal     = implode('-',array_reverse($aDataFinal));

foreach ($aListaTipos as $iInd => $sTipo) {
	
	if ( $iInd == 0 ) {
		$sAndOr = '';
	} else {
	  
		if ( $iNroLista > 1 ) {
		  $sAndOr = 'or';
		} else {
		  $sAndOr = 'and';
		}
	}
	
  switch ($sTipo) {
    
  	case "chkSol":
  		$sWhereTipo  .= " {$sAndOr} o82_codres is not null ";
  		if ($sDepartamentos != "") {
  		  $sWhereTipo .= " and pc13_depto in ({$sDepartamentos}) ";
  		}
  	  break;
  	  
    case "chkAut":
      $sWhereTipo  .= " {$sAndOr} o83_codres is not null ";
      if ($sDepartamentos != "") {
        $sWhereTipo .= " and e54_depto in ({$sDepartamentos}) ";
      }
      break;
      
    case "chkSupl":
      $sWhereTipo  .= " {$sAndOr} o81_codres is not null ";
      break;
      
    case "chkProc":
      $sWhereTipo  .= " {$sAndOr} o84_codres is not null ";
      break;
                    	
    case "chkMan":
      $sWhereTipo  .= " {$sAndOr} (     o81_codres is null   ";
      $sWhereTipo  .= "             and o82_codres is null   ";
      $sWhereTipo  .= "             and o83_codres is null   ";
      $sWhereTipo  .= "             and o84_codres is null  )";
      break;
  }
	
}

$sWhereTipo = " and ({$sWhereTipo})";


$clselorcdotacao = new cl_selorcdotacao();
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
$instits= $clselorcdotacao->getInstit();

if (trim(@$instits) == ""){
   $instits = db_getsession("DB_instit");
}

$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in ($instits)");
$descr_inst = '';
$xvirg = '';
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  $descr_inst .= $xvirg.$nomeinst ; 
  $xvirg = ', ';
}

if ( $nivel{0} == "1" ) {
	$sDescricaoNivel = "Orgão";
} else if ($nivel{0} == "2" ) {
	$sDescricaoNivel = "Orgão/Unidade";
} else if ($nivel{0} == "3" ) {
  $sDescricaoNivel = "Função";  
} else if ($nivel{0} == "4" ) {
  $sDescricaoNivel = "Subfunção";  
} else if ($nivel{0} == "5" ) {
  $sDescricaoNivel = "Programa";  
} else if ($nivel{0} == "6" ) {
  $sDescricaoNivel = "Projeto/Atividade";  
} else if ($nivel{0} == "7" ) {
  $sDescricaoNivel = "Elemento";  
} else if ($nivel{0} == "8" ) {
  $sDescricaoNivel = "Recurso";  
} else if ($nivel{0} == "9" ) {
  $sDescricaoNivel = "Desdobramento";  
}


$sCampos  = "pc11_numero, solicitem.pc11_codigo, pcmater.pc01_descrmater, w.o58_orgao, o40_descr,";
$sCampos .= "pc13_depto,";
$sCampos .= "w.o58_orgao,o40_descr,w.o58_unidade,o41_descr,";    
$sCampos .= "w.o58_funcao,o52_descr,";    
$sCampos .= "w.o58_subfuncao,o53_descr,";    
$sCampos .= "w.o58_programa,o54_descr,";     
$sCampos .= "w.o58_projativ,o55_descr,";     
$sCampos .= "e.o56_elemento,e.o56_descr,";     
$sCampos .= "w.o58_codigo,o15_descr";
$sCampos .= "";

if ( $nivel{0} == 9 ) {
	
	$sCamposDesd  = " case                                                        "; 
	$sCamposDesd .=	"   when dsol.o56_elemento is not null then dsol.o56_elemento "; 
	$sCamposDesd .=	"	  when daut.o56_elemento is not null then daut.o56_elemento ";
	$sCamposDesd .=	"   else ''                                                   ";
	$sCamposDesd .=	" end as coddesdobramento,                                    ";
	$sCamposDesd .=	" case                                                        ";
	$sCamposDesd .=	"	  when dsol.o56_descr is not null then dsol.o56_descr       "; 
	$sCamposDesd .=	"	  when daut.o56_descr is not null then daut.o56_descr       ";
	$sCamposDesd .=	"   else ''                                                   ";
	$sCamposDesd .=	" end as descrdesdobramento                                   ";

} else {
	$sCamposDesd  = "'' as coddesdobramento, ";
	$sCamposDesd .= "'' as descrdesdobramento";
}

$sql = "select distinct 
               case when o81_codres is not null then 'RESERVAS PROVENIENTES DE CRÉDITOS ADICIONAIS (SUPLEMENTAÇÕES)'				       
                    when o82_codres is not null then 'RESERVAS PROVENIENTES DE SOLICITAÇÃO DE COMPRA'
                    when o83_codres is not null then 'RESERVAS PROVENIENTES DE AUTORIZAÇÕES DE EMPENHO'
                    when o84_codres is not null then 'RESERVAS PROVENIENTES DE PROCESSAMENTOS' 
                    else 'RESERVAS PROVENIENTES DE INCLUSÃO MANUAL'
               end as tiporeserva,
               {$sCampos},
               {$sCamposDesd},      
               orcreserva.*,
				       o83_autori,
				       pc13_codigo,
				       o81_codsup,
				       o46_codlei,
				       o38_descr          from orcreserva
					     inner join orcdotacao   w        on w.o58_anousu    = o80_anousu
					                                     and w.o58_coddot    = o80_coddot 
					     inner join orcorgao     o        on o.o40_anousu    = w.o58_anousu 
					                                     and o.o40_orgao     = w.o58_orgao 
					     inner join orcunidade   u        on u.o41_anousu    = w.o58_anousu 
					                                     and u.o41_orgao     = w.o58_orgao 
					                                     and u.o41_unidade   = w.o58_unidade
					     inner join orcfuncao    f        on f.o52_funcao    = w.o58_funcao 
					     inner join orcsubfuncao s        on s.o53_subfuncao = w.o58_subfuncao 
					     inner join orcprograma  p        on p.o54_anousu    = w.o58_anousu 
					                                     and p.o54_programa  = w.o58_programa 
					     inner join orcprojativ  a        on a.o55_anousu    = w.o58_anousu 
					                                     and a.o55_projativ  = w.o58_projativ 
					     inner join orcelemento  e        on e.o56_codele    = w.o58_codele  
					                                     and e.o56_anousu    = w.o58_anousu
					     inner join orctiporec   r        on r.o15_codigo    = w.o58_codigo
					     left  join orcreservaaut         on o83_codres      = o80_codres
					     left  join empautoriza           on e54_autori      = o83_autori
					     left  join empautitem            on e55_autori      = e54_autori  
					     left  join orcelemento daut      on daut.o56_codele = e55_codele
					                                     and daut.o56_anousu = e54_anousu
					     left  join orcreservager         on o84_codres      = o80_codres
					     left  join orcreservasol         on o82_codres      = o80_codres
					     left  join pcdotac               on pc13_codigo     = o82_solicitem
  			                                       and pc13_coddot     = o80_coddot
					     left  join orcreservasup         on o80_codres      = o81_codres
					     left  join orcsuplem             on o46_codsup      = o81_codsup
					     left  join orcprojeto            on o39_codproj     = o46_codlei
					     left  join orctipoproj           on o39_tipoproj    = o38_tipoproj 
               left  join solicitem             on pc13_codigo     = pc11_codigo
               left  join solicita              on pc10_numero     = pc11_numero
               left  join solicitempcmater      on pc16_solicitem  = pc11_codigo
               left  join pcmater               on pc01_codmater   = pc16_codmater  
               left  join solicitemele          on pc11_codigo     = pc18_solicitem
               left  join orcelemento dsol      on dsol.o56_codele = pc18_codele
 	  		 where o80_anousu = ".db_getsession("DB_anousu")." 
 	  		   and ".$clselorcdotacao->getDados()." 
 	  		   and w.o58_instit in (".$instits.")
 	  		   and o80_dtlanc <= '{$sDataFinal}'
 	  		   {$sWhereTipo}";
if ($pc10_numero_inicial != "") {
  $sql .= " and pc11_numero between {$pc10_numero_inicial} and {$pc10_numero_final} ";  
}
if ($o83_autori_inicial != "") {
  $sql .= " and o83_autori between {$o83_autori_inicial} and {$o83_autori_final} ";
} 	  		   
$sql .= " order by tiporeserva,{$sCampos} ";
			   
$rsDadosReserva = db_query($sql);
$iNroReserva    = pg_num_rows($rsDadosReserva);

if( $iNroReserva == 0 ){
  db_redireciona("db_erros.php?fechar&db_erro=Não existem reserva de saldo para listar.");
}

for ( $iInd=0; $iInd < $iNroReserva; $iInd++ ) {
	
	$oReserva = db_utils::fieldsMemory($rsDadosReserva,$iInd);
	
  if(trim($oReserva->pc13_codigo) != ''){
    
  	$sDescricaoReserva  = 'Solicitação de Compra No. '.$oReserva->pc11_numero.(trim($oReserva->o80_descr)!=''?' - '.$oReserva->o80_descr:'');
    $sDescricaoReserva .= ' Item: '.$oReserva->pc11_codigo.' - '.ucfirst(strtolower(substr($oReserva->pc01_descrmater,0,53)));
    
  } elseif(trim($oReserva->o81_codsup) != ''){
  	
    $sDescricaoReserva = 'Projeto de Suplementação N.o '.$oReserva->o46_codlei.' ('.$oReserva->o38_descr.')';
    
  } elseif(trim($oReserva->o83_autori) != ''){
  	
    $sDescricaoReserva = 'Reserva Gerada pela Autorização N.o '.$oReserva->o83_autori;
    
  } else{
  	
    $sDescricaoReserva = (trim($oReserva->o80_descr)==''?'Reserva Gerada Manualmente':$oReserva->o80_descr);
    
  }
    	
	$aReserva['Descr']  = $sDescricaoReserva;
	$aReserva['DtLanc'] = $oReserva->o80_dtlanc;
	$aReserva['Valor']  = $oReserva->o80_valor;

	$sDescrOrgao         = db_formatar($oReserva->o58_orgao,'orgao')          .' - '.$oReserva->o40_descr;
	$sDescrUnidade       = db_formatar($oReserva->o58_unidade,'orgao')        .' - '.$oReserva->o41_descr;
	$sDescrFuncao        = db_formatar($oReserva->o58_funcao,'funcao')        .' - '.$oReserva->o52_descr;
	$sDescrSubFuncao     = db_formatar($oReserva->o58_subfuncao,'subfuncao')  .' - '.$oReserva->o53_descr;
	$sDescrPrograma      = db_formatar($oReserva->o58_programa,'programa')    .' - '.$oReserva->o54_descr;
	$sDescrProjAtiv      = db_formatar($oReserva->o58_projativ,'projativ')    .' - '.$oReserva->o55_descr;
	$sDescrElemento      = db_formatar($oReserva->o56_elemento,'elemento')    .' - '.$oReserva->o56_descr;
	$sDescrRecurso       = db_formatar($oReserva->o58_codigo,'recurso')       .' - '.$oReserva->o15_descr;
	$sDescrDesdobramento = db_formatar($oReserva->coddesdobramento,'elemento').' - '.$oReserva->descrdesdobramento;
	
  $aNivel[$oReserva->tiporeserva][$sDescrOrgao]
															   [$sDescrUnidade]
															   [$sDescrFuncao]
															   [$sDescrSubFuncao]  
															   [$sDescrPrograma]
															   [$sDescrProjAtiv]  
															   [$sDescrElemento]
															   [$sDescrRecurso]
															   ['aReservas'][$oReserva->o80_codres] = $aReserva;
  
  $aNivelUnico[1][$oReserva->tiporeserva][$sDescrOrgao]['aReservas'][$oReserva->o80_codres]         = $aReserva;  
  $aNivelUnico[2][$oReserva->tiporeserva][$sDescrUnidade]['aReservas'][$oReserva->o80_codres]       = $aReserva;
  $aNivelUnico[3][$oReserva->tiporeserva][$sDescrFuncao]['aReservas'][$oReserva->o80_codres]        = $aReserva;
  $aNivelUnico[4][$oReserva->tiporeserva][$sDescrSubFuncao]['aReservas'][$oReserva->o80_codres]     = $aReserva;
  $aNivelUnico[5][$oReserva->tiporeserva][$sDescrPrograma]['aReservas'][$oReserva->o80_codres]      = $aReserva;
  $aNivelUnico[6][$oReserva->tiporeserva][$sDescrProjAtiv]['aReservas'][$oReserva->o80_codres]      = $aReserva;
  $aNivelUnico[7][$oReserva->tiporeserva][$sDescrElemento]['aReservas'][$oReserva->o80_codres]      = $aReserva;
  $aNivelUnico[8][$oReserva->tiporeserva][$sDescrRecurso]['aReservas'][$oReserva->o80_codres]       = $aReserva;
  $aNivelUnico[9][$oReserva->tiporeserva][$sDescrDesdobramento]['aReservas'][$oReserva->o80_codres] = $aReserva;
  
}
  
$head1 = "RELATORIO DE RESERVA DE SALDO";
$head2 = "EXERCÍCIO : ".db_getsession("DB_anousu");
$head3 = "INSTITUIÇÕES : ".$descr_inst;
$head4 = "POSIÇÃO ATÉ : ".$data_fin;
$head5 = "NIVEL : ".($nivel{1}=="A"?"Até ":"").$sDescricaoNivel;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->addPage(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$alt = 4;

if ( $nivel{1} == 'A') {
  
	
	foreach ( $aNivel as $sTipoReserva => $aDados) {
	  
	  $pdf->Ln(3);
	  $pdf->cell(0,$alt,$sTipoReserva,0,1,"L",0);
	  $nValorTotalReserva = 0;
	  $nValorTotalNivel   = 0;
	    
	  foreach ($aDados as $sOrgao => $aDadosOrgao) {
	  	if ( $nivel{0} >= 1 ) {
        $pdf->cell(0,$alt,$sOrgao,0,1,"L",0);
	  	}
	    foreach ($aDadosOrgao as $sUnidade => $aDadosUnidade) {
	    	if ( $nivel{0} >= 2 ) {
          $pdf->cell(0,$alt,$sUnidade,0,1,"L",0);
	    	}
	      foreach ($aDadosUnidade as $sFuncao => $aDadosFuncao) {
	      	if ( $nivel{0} >= 3 ) {
            $pdf->cell(0,$alt,$sFuncao,0,1,"L",0);
	      	}  
	        foreach ($aDadosFuncao as $sSubFuncao => $aDadosSubFuncao) {
	        	if ( $nivel{0} >= 4 ) {
              $pdf->cell(0,$alt,$sSubFuncao,0,1,"L",0);
	        	}  
	          foreach ($aDadosSubFuncao as $sPrograma => $aDadosPrograma) {
	          	if ( $nivel{0} >= 5 ) {
	          	  $pdf->cell(0,$alt,$sPrograma,0,1,"L",0);
	          	}  
	            foreach ($aDadosPrograma as $sProjAtiv => $aDadosProjAtiv) {
	            	if ( $nivel{0} >= 6 ) {
	            	  $pdf->cell(0,$alt,$sProjAtiv,0,1,"L",0);
	            	}  
	              foreach ($aDadosProjAtiv as $sElemento => $aDadosElemento) {
	              	if ( $nivel{0} >= 7 ) {
	              	  $pdf->cell(0,$alt,$sElemento,0,1,"L",0);
	              	}  
	                foreach ($aDadosElemento as $sRecurso => $aDadosRecursos) {
         	          if ( $nivel{0} >= 8 ) {
	                	  $pdf->cell(0,$alt,$sRecurso,0,1,"L",0);
         	          }
	                  foreach ($aDadosRecursos['aReservas'] as $iCodReserva => $aReserva) {
	                                    
	                    $sDescrReserva = $aReserva['Descr'];
	                    $dtDataLanc    = $aReserva['DtLanc'];
	                    $nValor        = $aReserva['Valor'];
	                    
	                    if ( $forma_impressao == 'a' ) {
	                      $pdf->setfont('arial','',8);
	                      $pdf->cell(20,$alt,$iCodReserva                ,'TBR',0,'C');
	                      $pdf->cell(20,$alt,db_formatar($dtDataLanc,'d'),'TBR',0,'C');
	                      $pdf->cell(20,$alt,db_formatar($nValor,'f')    ,'TBR',0,"R");
	                      $pdf->multicell(130,$alt,$sDescrReserva        ,'TB','L');
	                      $pdf->setfont('arial','b',8);
	                    }
	                    $nValorTotalNivel   += $nValor;
	                    $nValorTotalReserva += $nValor;
	                     
	                  }
	                  imprimeTotal($pdf,$alt,$nValorTotalNivel,"TOTAL RECURSO",$nivel{0},8);
	                }
	                imprimeTotal($pdf,$alt,$nValorTotalNivel,"TOTAL ELEMENTO",$nivel{0},7);
	              }
	              imprimeTotal($pdf,$alt,$nValorTotalNivel,"TOTAL ATIVIDADE",$nivel{0},6);
	            }
	            imprimeTotal($pdf,$alt,$nValorTotalNivel,"TOTAL PROGRAMA",$nivel{0},5);
	          }
	          imprimeTotal($pdf,$alt,$nValorTotalNivel,"TOTAL SUBFUNÇÃO",$nivel{0},4);
	        }
	        imprimeTotal($pdf,$alt,$nValorTotalNivel,"TOTAL FUNÇÃO",$nivel{0},3);
	      }
	      imprimeTotal($pdf,$alt,$nValorTotalNivel,"TOTAL UNIDADE",$nivel{0},2);
	    }
	    imprimeTotal($pdf,$alt,$nValorTotalNivel,"TOTAL ORGÃO",$nivel{0},1);
	  } 
	  imprimeTotal($pdf,$alt,$nValorTotalReserva,"TOTAL TIPO RESERVA",0,0);
	}	
	
} else {
	
	foreach ($aNivelUnico[$nivel{0}] as $sTipoReserva => $aDadosNivel ) {
        
		$pdf->setfont('arial','b',8);
		$pdf->Ln(3);
		$pdf->cell(0,$alt,$sTipoReserva,0,1,"L",0);
    $nValorTotalReserva = 0;
		
		foreach ($aDadosNivel as $sDescrNivel => $aReservas ) {
		        
			$pdf->setfont('arial','b',8);	
			$pdf->Ln(3);
			$pdf->cell(0,$alt,$sDescrNivel,0,1,"L",0);
	    $nValorTotalNivel   = 0;
			
			foreach ($aReservas['aReservas'] as $iCodReserva => $aReserva ) {

				$sDescrReserva = $aReserva['Descr'];
				$dtDataLanc    = $aReserva['DtLanc'];
				$nValor        = $aReserva['Valor'];
				
				if ( $forma_impressao == 'a' ) {
					$pdf->setfont('arial','',8);
					$pdf->cell(20,$alt,$iCodReserva                ,'TBR',0,'C');
					$pdf->cell(20,$alt,db_formatar($dtDataLanc,'d'),'TBR',0,'C');
					$pdf->cell(20,$alt,db_formatar($nValor,'f')    ,'TBR',0,"R");
					$pdf->multicell(130,$alt,$sDescrReserva        ,'TB','L');
	        $pdf->setfont('arial','b',8);
				}  

				$nValorTotalNivel   += $nValor;
				$nValorTotalReserva += $nValor;				
				
			}
			$pdf->cell(40,$alt,'TOTAL '.strtoupper($sDescricaoNivel),'TBR',0,'L');
	    $pdf->cell(20,$alt,db_formatar($nValorTotalNivel,'f')   ,'TBR',1,"R");
	    $pdf->ln(3);
		}
	  $pdf->cell(40,$alt,'TOTAL RESERVA'                     ,'TBR',0,'L');
    $pdf->cell(20,$alt,db_formatar($nValorTotalReserva,'f'),'TBR',1,"R");
    $pdf->ln(3);
	}
	
}

$pdf->Output();

function imprimeTotal($pdf,$iAlt,$nValorTotal,$sDescr,$iNivel,$iNivelTotal){
	
	if ( $iNivel == $iNivelTotal) {	
	  $pdf->cell(40,$iAlt,$sDescr                      ,'TBR',0,'L');
	  $pdf->cell(20,$iAlt,db_formatar($nValorTotal,'f'),'TBR',1,"R");
	  $pdf->ln(3);
	  global $nValorTotalNivel;
	  $nValorTotalNivel = 0;
	}
	
}

?>
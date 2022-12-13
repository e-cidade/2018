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
include("libs/db_utils.php");
set_time_limit(0);

//db_postmemory($HTTP_SERVER_VARS,2);exit;

$oGet = db_utils::postMemory($_GET);
$oGet->setor   = str_replace(",","','",$oGet->setor);
$oGet->bairro  = str_replace(",","','",$oGet->bairro);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$iMatric	  = "";
$alt  		  = 4;
$fonte		  = 8;
$andWhere	  = "";
$ValorTotal	= 0;
$ValorTotalIsen	= 0;
$Totalizador		= 0;
$SubValorTotal	= 0;
$SubValorTotalIsen  = 0;
$SubTotalizador     = 0;
$Chave					= null;
$int = 0;
$nomearquivos = "";
$arqi = 0;

switch($oGet->selagrupa){
	case "m":
		$NomeTotal  = "DA MATRÍCULA";
			if($oGet->selordem == "m"){	
				$orderby	  = " order by j21_matric, j21_anousu, iptucalh.j17_codhis "; 
	    }else if($oGet->selordem == "n"){
				$orderby	  = " order by z01_nome, j21_matric, j21_anousu, iptucalh.j17_codhis "; 
			}else if($oGet->selordem == "b"){
				$orderby	  = " order by j34_bairro, j21_matric, j21_anousu, iptucalh.j17_codhis "; 
			}else if($oGet->selordem == "s"){
				$orderby	  = " order by j34_setor, j21_matric, j21_anousu, iptucalh.j17_codhis "; 
			}
		$NomeGrupo		  = "MATRÍCULA";
	break;
	case "b":
		$NomeTotal  = "DO BAIRRO";
		$orderby	  = " order by j34_bairro, j21_anousu,iptucalh.j17_codhis "; 
		$NomeGrupo  = "BAIRRO";
	break;
	case "s":
		$NomeTotal 	= "DO SETOR";
		$orderby	  = " order by j34_setor, j21_anousu,iptucalh.j17_codhis "; 
		$NomeGrupo	= "SETOR";
	break;
}

$head2 = "RELATÓRIO DE VALORES POR HISTÓRICO DE CÁLCULO";
$head3 = "TIPO : ".($oGet->seltipo == "a"?"ANALÍTICO":"SINTÉTICO");
$head4 = "EXERCÍCIO DE ".$oGet->anoexei." À ".$oGet->anoexef;
$head5 = "AGRUPADO POR ".$NomeGrupo;

if($oGet->setor){
	$andWhere .= "and j34_setor in ('".$oGet->setor."')";	
}
if($oGet->bairro){
	$andWhere .= "and j34_bairro in ('".$oGet->bairro."')";	
}

$sSqlCalv  = " select  j21_anousu,                                                                       ";
$sSqlCalv .= "         j21_matric,                                                                       ";
$sSqlCalv .= "         z01_numcgm,                                                                       ";
$sSqlCalv .= "         z01_nome,	                                                                       ";
$sSqlCalv .= "         j34_setor,	                                                                       ";
$sSqlCalv .= "         j34_bairro,	                                                                     ";
$sSqlCalv .= "         j13_descr,	                                                                       ";
$sSqlCalv .= "         k02_codigo,                                                                       ";
$sSqlCalv .= "         j17_codhis,                                                                       ";
$sSqlCalv .= "         j21_receit,                                                                       ";
$sSqlCalv .= "         j17_descr,	                                                                       ";
$sSqlCalv .= "         j21_valor,	                                                                       ";
$sSqlCalv .= "         case when iptucalhconf.j89_codhis is not null                                     ";
$sSqlCalv .= "            then (select sum(x.j21_valor)							                                     ";
$sSqlCalv .= "                    from iptucalv x										                                     ";
$sSqlCalv .= "                   where x.j21_anousu = iptucalv.j21_anousu			                           ";
$sSqlCalv .= "                     and x.j21_matric = iptucalv.j21_matric			                           ";
$sSqlCalv .= "                     and x.j21_receit = iptucalv.j21_receit			                           ";
$sSqlCalv .= "                     and x.j21_codhis = iptucalhconf.j89_codhis)                           ";
$sSqlCalv .= "            else 0					                                                               ";
$sSqlCalv .= "         end as j21_valorisen                                                              ";
$sSqlCalv .= "    from iptucalv						                                                               ";
$sSqlCalv .= "         inner join iptucalh           on iptucalh.j17_codhis        = j21_codhis					 ";
$sSqlCalv .= "         left  join iptucalhconf conf  on conf.j89_codhis            = iptucalh.j17_codhis ";
$sSqlCalv .= "         left  join iptucalhconf       on iptucalhconf.j89_codhispai = j21_codhis					 ";
$sSqlCalv .= "         inner join tabrec             on tabrec.k02_codigo          = j21_receit					 ";
$sSqlCalv .= "         inner join iptubase           on iptubase.j01_matric        = j21_matric					 ";
$sSqlCalv .= "         inner join lote               on lote.j34_idbql             = j01_idbql					 ";
$sSqlCalv .= "         inner join bairro             on bairro.j13_codi            = j34_bairro					 ";
$sSqlCalv .= "         inner join cgm                on cgm.z01_numcgm             = j01_numcgm					 ";
$sSqlCalv .= "         left  join iptucadtaxaexe     on iptucadtaxaexe.j08_tabrec  = j21_receit					 ";
$sSqlCalv .= "                                      and iptucadtaxaexe.j08_anousu  = j21_anousu					 ";
$sSqlCalv .= "   where 1=1																																							 ";
$sSqlCalv .= "     and j21_anousu between ".$oGet->anoexei." and ".$oGet->anoexef."										   ";
$sSqlCalv .= "     and conf.j89_codhis is null																													 ";
$sSqlCalv .=       $andWhere;
$sSqlCalv .=			 $orderby ;

	$pdf->addpage();
	
	$rsCalv = pg_query($sSqlCalv) or die ($sSqlCalv); 
	
	for($i=0;	$i <= pg_num_rows($rsCalv); $i++){
	
		$oCalv  = db_utils::fieldsMemory($rsCalv,$i);

		//---------------------------------- INÍCIO DOS AGRUPAMENTOS --------------------------------------//
			
			if($oGet->selagrupa == "s"){
				 $campoPrinc = $oCalv->j34_setor; 
			}else if($oGet->selagrupa == "b"){
				 $campoPrinc = $oCalv->j13_descr; 
			}else if($oGet->selagrupa == "m"){
				 $campoPrinc = $oCalv->j21_matric; 
		  }	
			
				if($int == 2000){	                                                                                    ////                          	
					$arqi++;                                                                                            	//	                        													
					$arq            = "tmp/RelHistCalc_parte_".$arqi.".pdf";                                        	    //
					$nomearquivos  .= "tmp/RelHistCalc_parte_".$arqi.".pdf# Download Relatório_Parte_$arqi.pdf|";         //
					$pdf->Output($arq,false,true);                                                                        //
					unset($pdf);                                                                                          //
					                                                                                                      //
					$pdf = new PDF();                                                                                     //
					$pdf->Open();                                                                                         // VERIFICA TAMANHO DOC E QUEBRA ARQUIVO
					$pdf->AliasNbPages();                                                                                 // 
					$pdf->setfillcolor(235);                                                                              //
					$pdf->addpage();                                                                                      //
					$int = 0;                                                                                             //
					$i --;                                                                                                //
					continue;                                                                                             //
				}                                                                                                       //
                                                                                                              ////
			                                                                                                         
		  if($oGet->seltipo == "a"){	                                                                              
			                                                                                                        
				if($campoPrinc != $Chave && $i !=0){														////                          	
					if($oCalv->j21_matric != $iMatric && $oGet->selagrupa=="m"){		//	                        													
						$pdf->setfont('arial','b',$fonte);                          	//
						$pdf->ln();                                                   //
						$pdf->setx(32);                                               //
						$pdf->cell(15,$alt,'Matrícula',0,0,"C",1);                    //
						$pdf->cell(65,$alt,'Nome'     ,0,0,"C",1);                    //
						$pdf->cell(50,$alt,'Bairro'		,0,0,"C",1);                    //
						$pdf->cell(20,$alt,'Setor'		,0,1,"C",1);                    //
						$pdf->ln(2);                                                  // IMPRIME CABEÇALHO DA MATRÍCULA
							                                                            //
						$pdf->setfont('arial','',$fonte);                             //
						$pdf->setx(32);                                               //
						$pdf->cell(15,$alt,$Mat				,0,0,"C",0);                    //
						$pdf->cell(65,$alt,$Nome			,0,0,"L",0);                    //
						$pdf->cell(50,$alt,$Historico ,0,0,"L",0);                    //
						$pdf->cell(20,$alt,$Setor     ,0,1,"C",0);                    //
						$iMatric = $oCalv->j21_matric;                                //
			    } 				                                                      // 
				                                                                ////
				$pdf->ln();                                                     
				$pdf->setfont('arial','b',$fonte);                              
				$pdf->setx(32);                                                 
				$pdf->cell(20,$alt,'Exercício',0,0,"C",1);                      
				$pdf->cell(40,$alt,'Histórico',0,0,"C",1);                      
				$pdf->cell(30,$alt,'Valor'		,0,0,"C",1);                      
				$pdf->cell(30,$alt,'Isenção'	,0,0,"C",1);                      
				$pdf->cell(30,$alt,'Total'		,0,1,"C",1);                      
				$pdf->setfont('arial','',$fonte);                               
                                                                        
				foreach( $aAgrupaHist as $campPri  => $valor1 ){								
					 foreach( $valor1		 as $exe  => $valor2){										
							                                                          
							$SubValorTotal			= 0;                                  
							$SubValorTotalIsen  = 0;                                  
							$SubTotalizador     = 0;                                  
							                                                          
								$pdf->ln();																																										 
								$pdf->setx(32);																																								 
								$pdf->cell(20,$alt,$exe ,0,0,"C",0);																													 
								
								foreach( $valor2  as $hist => $valor3){		                                                       
									$pdf->setx(32);                                                                                
									$pdf->cell(20,$alt,"",0,0,"C",0);                                                              
									$pdf->cell(40,$alt,$hist																										 ,0,0,"L",0);          
									$pdf->cell(30,$alt,db_formatar($valor3['valor'],"f")												 ,0,0,"R",0);					 
									$pdf->cell(30,$alt,db_formatar($valor3['valorisen'],"f")										 ,0,0,"R",0);					 
									$pdf->cell(30,$alt,db_formatar(($valor3['valor'] + $valor3['valorisen']),"f"),0,1,"R",0);      
									$SubValorTotal			+=  $valor3['valor'];										                                                                          
									$SubValorTotalIsen  +=  $valor3['valorisen'];
									$SubTotalizador     +=  ($valor3['valor'] + $valor3['valorisen']);
								}                                                                                                
						
								$pdf->setx(92);                                                                                
								$pdf->cell(30,$alt,db_formatar($SubValorTotal,"f")		 ,0,0,"R",0);					 
								$pdf->cell(30,$alt,db_formatar($SubValorTotalIsen,"f") ,0,0,"R",0);					 
								$pdf->cell(30,$alt,db_formatar($SubTotalizador ,"f") 	 ,0,1,"R",0);      
						}                                                                                                   
				} 	                                                                                                
																																																						
				if($campoPrinc != $Chave && $i != 0){																															////                       
					                                                                                                	//	
					$pdf->ln();                                                                                     	//                       
					$pdf->setx(52);                                                                                   //                       
					$pdf->cell(60,$alt,'TOTAL GERAL '.$NomeTotal.' '.$Chave.' : ' ,0,1,"L",0);                        //
					$pdf->ln();                                                                                       //                       
																																																					  //                    		 
					foreach( $aAgrupaTotal as $campPri  => $valor1 ){																								  //                    			 
						foreach( $valor1  as $hist => $valor2){		                                                      //
							 $pdf->setx(32);                                                                              //
							 $pdf->cell(20,$alt,""																											  ,0,0,"C",0);    //
							 $pdf->cell(40,$alt,$hist																										  ,0,0,"L",0);    // IMPRIME TOTAL GERAL POR ( MATRÍCULA, SETOR OU BAIRRO )
							 $pdf->cell(30,$alt,db_formatar($valor2['valor'],"f")												  ,0,0,"R",0);    //
							 $pdf->cell(30,$alt,db_formatar($valor2['valorisen'],"f")							 		 	  ,0,0,"R",0);    //
							 $pdf->cell(30,$alt,db_formatar(($valor2['valor'] + $valor2['valorisen']),"f"),0,1,"R",0);    //
						}                                                                                               //
					} 	                                                                                              //                       
					                                                                                                  //
					$pdf->ln();                                                                                       //
					$pdf->setx(92);                                                                                   //                       
					$pdf->cell(30,$alt,db_formatar($ValorTotal,"f")			,0,0,"R",0);                                  //                     
					$pdf->cell(30,$alt,db_formatar($ValorTotalIsen,"f")	,0,0,"R",0);                                  //                     
					$pdf->cell(30,$alt,db_formatar($Totalizador,"f")		,0,1,"R",0);                                  //                     
					$pdf->ln();                                                                                     ////                       
																																																					                     	 
					$SubValorTotal			= 0;                                                                         
					$SubValorTotalIsen  = 0;                                                                         
					$SubTotalizador     = 0;                                                                         
					$ValorTotal			= 0;                                                                             
					$ValorTotalIsen = 0;                                                                             
					$Totalizador    = 0;                                                                             
					unset($aAgrupaSubTotal);                                                                         
					unset($aAgrupaTotal);                                                                            
					unset($aAgrupaHist);                                                                             
					$int ++;	                                                                                       
				                                                                                                   
				}                                                                                                 
			}
		}		
			if ($i == pg_num_rows($rsCalv)){
				break;	
			}
			$Chave = $campoPrinc;
			
			if(isset($aAgrupaHist [$campoPrinc][$oCalv->j21_anousu][$oCalv->j17_descr])){																											////
				$aAgrupaHist [$campoPrinc][$oCalv->j21_anousu][$oCalv->j17_descr]['valor']			 += $oCalv->j21_valor;                          	//
				$aAgrupaHist [$campoPrinc][$oCalv->j21_anousu][$oCalv->j17_descr]['valorisen']	 += $oCalv->j21_valorisen;                      	//
				$aAgrupaHist [$campoPrinc][$oCalv->j21_anousu][$oCalv->j17_descr]['totalizador'] += ($oCalv->j21_valor + $oCalv->j21_valorisen);	//
			}else{                                                                                                                            	// ARRAY COM AS INFOMAÇÕES POR EXERCÍCIO
				$aAgrupaHist [$campoPrinc][$oCalv->j21_anousu][$oCalv->j17_descr]['valor']			  = $oCalv->j21_valor;                          	//
				$aAgrupaHist [$campoPrinc][$oCalv->j21_anousu][$oCalv->j17_descr]['valorisen']	  = $oCalv->j21_valorisen;                      	//
				$aAgrupaHist [$campoPrinc][$oCalv->j21_anousu][$oCalv->j17_descr]['totalizador']  = ($oCalv->j21_valor + $oCalv->j21_valorisen);	//
			}                                                                                                                                 ////
							
			if(isset($aAgrupaTotal[$campoPrinc][$oCalv->j17_descr])){																											////
				$aAgrupaTotal[$campoPrinc][$oCalv->j17_descr]['valor']			 += $oCalv->j21_valor;                          	//
				$aAgrupaTotal[$campoPrinc][$oCalv->j17_descr]['valorisen']	 += $oCalv->j21_valorisen;                      	//
				$aAgrupaTotal[$campoPrinc][$oCalv->j17_descr]['totalizador'] += ($oCalv->j21_valor + $oCalv->j21_valorisen);	//
			}else{                                                                                                        	// ARRAY COM AS INFORMAÇÔES DO TOTAL POR ( MATRÍCULA, SETOR OU BAIRRO ) 
				$aAgrupaTotal[$campoPrinc][$oCalv->j17_descr]['valor']			  = $oCalv->j21_valor;                          	//
				$aAgrupaTotal[$campoPrinc][$oCalv->j17_descr]['valorisen']	  = $oCalv->j21_valorisen;                      	//
				$aAgrupaTotal[$campoPrinc][$oCalv->j17_descr]['totalizador']  = ($oCalv->j21_valor + $oCalv->j21_valorisen);	//
			}                                                                                                             ////
				
			if(isset($aTotalFinal[$oCalv->j17_descr])){																											              ////
				$aTotalFinal[$oCalv->j17_descr]['valor']			 += $oCalv->j21_valor;												 	           		  //
				$aTotalFinal[$oCalv->j17_descr]['valorisen']	 += $oCalv->j21_valorisen;											           		  //
				$aTotalFinal[$oCalv->j17_descr]['totalizador'] += ($oCalv->j21_valor + $oCalv->j21_valorisen);           		  //
			}else{																																													           		  // ARRAY COM AS INFORMAÇÕES DO TOTAL GERAL
				$aTotalFinal[$oCalv->j17_descr]['valor']			  = $oCalv->j21_valor;													           		  //
				$aTotalFinal[$oCalv->j17_descr]['valorisen']	  = $oCalv->j21_valorisen;											           		  //
				$aTotalFinal[$oCalv->j17_descr]['totalizador']  = ($oCalv->j21_valor + $oCalv->j21_valorisen);           		  //
			}																																																              ////
				
				$Mat			       = $oCalv->j21_matric;
				$Nome			       = $oCalv->z01_nome	 ;
				$Historico       = $oCalv->j13_descr ;
			  $Setor           = $oCalv->j34_setor ;
				$ValorTotal			+= $oCalv->j21_valor ;
				$ValorTotalIsen += $oCalv->j21_valorisen;
				$Totalizador    += ($oCalv->j21_valor + $oCalv->j21_valorisen);
		
	} //----------------------------------- FIM DO FOR ------------------------------------------//
	
	$ValorTotal     = 0;																																															////
	$ValorTotalIsen = 0;																																																//	
	$Totalizador    =	0;																																																//
	if($oGet->seltipo == "a"){                                                                                          //
		$pdf->addpage();                                                                                                  //
	}                                                                                                                   //
	$pdf->ln();                                                                                                         //
	$pdf->setx(42);                                                                                                     //
	$pdf->cell(60,$alt,'TOTAL GERAL POR '.$NomeGrupo.' : ' ,0,1,"L",0);                                                 //
	$pdf->ln(2);                                                                                                        //
	$pdf->setfont('arial','b',$fonte);                                                                                  //
	$pdf->setx(42);                                                                                                     //
	$pdf->cell(40,$alt,'Histórico',0,0,"C",1);                                                                          //
	$pdf->cell(30,$alt,'Valor'		,0,0,"C",1);                                                                          //
	$pdf->cell(30,$alt,'Isenção'	,0,0,"C",1);                                                                          //
	$pdf->cell(30,$alt,'Total'		,0,1,"C",1);                                                                          //
	$pdf->setfont('arial','',$fonte);                                                                                   //
		                                                                                                                  //
	foreach( $aTotalFinal as $descr => $descrValor ){																											              // IMPRIME TOTALIZADOR GERAL
			 $pdf->setx(42);                                                                                                //
			 $pdf->cell(40,$alt,$descr																													  ,0,0,"L",0);              //
			 $pdf->cell(30,$alt,db_formatar($descrValor['valor'],"f")															,0,0,"R",0);              //
			 $pdf->cell(30,$alt,db_formatar($descrValor['valorisen'],"f")													,0,0,"R",0);              //
			 $pdf->cell(30,$alt,db_formatar(($descrValor['valor'] + $descrValor['valorisen']),"f"),0,1,"R",0);              //
	                                                                                                                    //
	$ValorTotal     += $descrValor['valor'];                                                                            //
	$ValorTotalIsen += $descrValor['valorisen'];                                                                        //
	$Totalizador    += ($descrValor['valor']+ $descrValor['valorisen']);                                                //
                                                                                                                      //
	}                                                                                                                   //
	                                                                                                                    //
	$pdf->setfont('arial','b',$fonte);                                                                                  //
	$pdf->setx(82);                                                                                                     //
	$pdf->cell(30,$alt,db_formatar($ValorTotal,"f")			,0,0,"R",0);                                                    //
	$pdf->cell(30,$alt,db_formatar($ValorTotalIsen,"f")	,0,0,"R",0);                                                    //
	$pdf->cell(30,$alt,db_formatar($Totalizador,"f")		,0,1,"R",0);                                                    //
	$pdf->ln();                                                                                                       ////


$arqi++;
$arq           = "tmp/RelHistCalc_parte_".$arqi.".pdf";
$nomearquivos .= "tmp/RelHistCalc_parte_".$arqi.".pdf# Download Relatório_Parte_".$arqi.".pdf";
$pdf->Output($arq,false,true);

echo "<script>";
echo "  listagem = '$nomearquivos';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";

?>
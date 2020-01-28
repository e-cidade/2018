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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");


$oGet = db_utils::postMemory($_GET);
if ( $oGet->imprime_origem == 's') {
	$lImprimeOrigem = true;
} else {
	$lImprimeOrigem = false;
}

$iInstit    = db_getsession('DB_instit');
$sWhereBco  = '';
$sWhereData = '';


if ( $oGet->banco != 0 ) {
	
	$sWhereBco = ' and a.k15_codbco = '.$oGet->banco;
	
  if ( $oGet->conta != 0 ) {
    $sWhereBco .= " and d.k00_conta = {$oGet->conta} ";
  }
	
  $sSqlNomeBanco  = " select z01_nome                                     ";
  $sSqlNomeBanco .= "   from cadban                                       ";
  $sSqlNomeBanco .= "   inner join cgm on k15_numcgm = z01_numcgm         ";
  $sSqlNomeBanco .= "                 and k15_codbco = {$oGet->banco}     ";

  if ( $oGet->conta != 0 ) {
  	$sSqlNomeBanco .= "               and k15_conta  = {$oGet->conta}     ";
  }
  
  $sSqlNomeBanco .= "                 and k15_instit = {$iInstit} limit 1 ";
   
  $sNomeBco = pg_result(pg_exec($sSqlNomeBanco),0,"z01_nome");
  
} else {
	
	$sNomeBco = 'TODOS OS BANCOS';
	
}


if($oGet->tipocampo == 'arq'){
  $sCampoData = 'd.dtarquivo';
  $sWhereData = " and d.instit = {$iInstit}";
}else{
	$sCampoData = 'a.dtpago';
  $sWhereData = " and a.instit = {$iInstit}";
}
if($oGet->datai != ""){
  $sWhereData .= " and {$sCampoData} >= '{$oGet->datai}' ";	
}
if($oGet->dataf != ""){
  $sWhereData .= " and {$sCampoData} <= '{$oGet->dataf}' ";	
}

$sql  = " select codret,                                        ";
$sql .= "        (select k00_numcgm                             ";
$sql .= "           from recibopaga                             ";
$sql .= "          where recibopaga.k00_numnov = xxx.k00_numpre ";     
$sql .= "                limit 1)  ";
$sql .= "         as z01_numcgm,   ";
$sql .= "         k00_dtpaga,      ";
$sql .= "         k00_numpre,      ";
$sql .= "         k00_numpar,      ";
$sql .= "         numpre_origem,   ";
$sql .= "         numpar_origem,   ";
$sql .= "         vlrcalc_origem,  ";
$sql .= "         vlrpago,         ";
$sql .= "         ( select sum(k00_valor)                           ";
$sql .= "             from recibopaga                               "; 
$sql .= "            where k00_numnov = xxx.k00_numpre) as vlrcalc, ";
$sql .= "        (select z01_nome                                   ";
$sql .= "            from cgm                                       ";
$sql .= "                   join recibopaga on recibopaga.k00_numcgm = z01_numcgm ";
$sql .= "            where recibopaga.k00_numnov = xxx.k00_numpre limit 1)        ";
$sql .= "        as z01_nome,      ";
$sql .= "        k00_conta,        ";
$sql .= "        tipo              ";
$sql .= " from(                    ";

$sql .= " select d.codret,                             ";
$sql .= "        $sCampoData          as k00_dtpaga,    ";
$sql .= "        a.k00_numpre        as k00_numpre,    ";
$sql .= "        a.k00_numpar        as k00_numpar,    ";
$sql .= "        p.k00_numpre        as numpre_origem, ";
$sql .= "        p.k00_numpar        as numpar_origem, ";
$sql .= "        a.vlrpago           as vlrpago,       ";
$sql .= "        sum(p.k00_valor)    as vlrcalc_origem,";
$sql .= "        d.k00_conta,                          ";
$sql .= "        'RECIBO CGF'::text as tipo            ";
$sql .= "   from disbanco a                            ";
$sql .= "        inner join disarq d      on d.codret     = a.codret     ";  
$sql .= "        inner join recibopaga p  on p.k00_numnov = a.k00_numpre ";
$sql .= "    where 1=1 $sWhereData $sWhereBco ";
$sql .= "    and a.classi                     ";
$sql .= "    group by d.codret,               ";
$sql .= "             a.dtarq,                ";
$sql .= "             a.k00_numpre,           ";
$sql .= "             a.k00_numpar,           ";
$sql .= "             p.k00_numpre,           ";
$sql .= "             p.k00_numpar,           ";
$sql .= "             a.vlrpago,              ";
$sql .= "             $sCampoData,            ";
$sql .= "             d.k00_conta             ";
$sql .= ") as xxx";

$sql .= " union all                                  ";

$sql .= " select distinct                            ";
$sql .= "        d.codret,                           ";
$sql .= "        b.k00_numcgm     as z01_numcgm,     ";
$sql .= "        $sCampoData       as k00_dtpaga,    ";
$sql .= "        a.k00_numpre     as k00_numpre,     ";
$sql .= "        a.k00_numpar     as k00_numpar,     ";
$sql .= "        null::integer    as numpre_origem,  ";
$sql .= "        null::integer    as numpar_origem,  ";
$sql .= "        0                as vlrcalc_origem, ";
$sql .= "        a.vlrpago        as vlrpago,        ";
$sql .= "        a.vlrcalc        as vlrcalc,        ";
$sql .= "        c.z01_nome,                         "; 
$sql .= "        d.k00_conta,                        ";
$sql .= "        'PARCELA'::text  as tipo            ";
$sql .= "   from disbanco a                          ";
$sql .= "        inner join disarq d   on d.codret     = a.codret     ";  
$sql .= "        inner join arrecant b on b.k00_numpre = a.k00_numpre ";
$sql .= "                             and b.k00_numpar = a.k00_numpar ";
$sql .= "        inner join cgm c      on c.z01_numcgm = b.k00_numcgm ";
$sql .= " where 1=1 $sWhereData $sWhereBco ";
$sql .= "   and a.classi = 't' ";

$sql .= " union all ";

$sql .= " select codret, 
                 k00_numcgm, 
                 k00_dtpaga, 
                 k00_numpre, 
                 k00_numpar, 
                 null::integer as numpre_origem,
                 null::integer as numpar_origem,
                 0             as vlrcalc_origem,
                 vlrpago, 
                 vlrcalc, 
                 z01_nome, 
                 k00_conta, 
                 tipo 
            from (";
$sql .= " select d.codret, ";
$sql .= "        (select b.k00_numcgm from arreold b where b.k00_numpre = a.k00_numpre and b.k00_numpar = a.k00_numpar limit 1) as k00_numcgm,"; 
$sql .= "        (select e.k00_numpre from arrecant e where e.k00_numpre = a.k00_numpre and e.k00_numpar = a.k00_numpar limit 1) as k00_numpre_arrecant,"; 
$sql .= "        $sCampoData      as k00_dtpaga, ";
$sql .= "        a.k00_numpre     as k00_numpre, ";
$sql .= "        a.k00_numpar     as k00_numpar, ";
$sql .= "        a.vlrpago        as vlrpago,    ";
$sql .= "        a.vlrcalc        as vlrcalc,    ";
$sql .= "        (select c.z01_nome from arreold b inner join cgm c on c.z01_numcgm = b.k00_numcgm where b.k00_numpre = a.k00_numpre  and b.k00_numpar = a.k00_numpar limit 1) as z01_nome,"; 
$sql .= "        d.k00_conta, ";
$sql .= "        'PARCELAOLD'::text  as tipo ";
$sql .= "   from disbanco a ";
$sql .= "        inner join disarq d   on d.codret = a.codret ";  
$sql .= " where 1=1 $sWhereData $sWhereBco ";
$sql .= "   and a.classi = 't' ";
$sql .= " ) as bx_arreold where bx_arreold.k00_numcgm is not null and k00_numpre_arrecant is null";



$sql .= " union all ";

if ($parcunica == 'sim'){
	
	$sql .= " select distinct ";
	$sql .= "        d.codret, ";
  $sql .= "        b.k00_numcgm     as z01_numcgm, ";
	$sql .= "        $sCampoData      as k00_dtpaga, ";
	$sql .= "        a.k00_numpre     as k00_numpre, ";
	$sql .= "        a.k00_numpar     as k00_numpar, ";
	$sql .= "        null::integer    as numpre_origem,  ";
	$sql .= "        null::integer    as numpar_origem,  ";
	$sql .= "        0                as vlrcalc_origem, ";
	$sql .= "        a.vlrpago        as vlrpago,    ";
	$sql .= "        a.vlrcalc        as vlrcalc,    ";
	$sql .= "        c.z01_nome,                     "; 
	$sql .= "        d.k00_conta,                    ";
	$sql .= "        'PARCELA UNICA'::text  as tipo  ";
	$sql .= "   from disbanco a ";
  $sql .= "        left  join recibo p1     on p1.k00_numpre = a.k00_numpre ";
  $sql .= "        left  join recibopaga p2 on p2.k00_numnov = a.k00_numpre ";
	$sql .= "        inner join disarq d      on d.codret      = a.codret ";  
	$sql .= "        inner join arrepaga b    on b.k00_numpre  = a.k00_numpre ";
	$sql .= "        inner join cgm c         on c.z01_numcgm  = b.k00_numcgm ";
	$sql .= " where p1.k00_numpre is null and p2.k00_numpre is null $sWhereData $sWhereBco ";
	$sql .= "   and a.classi = 't' ";
	$sql .= "   and a.k00_numpar = 0 "; // Identifica se eh UNICA numpar=0

	$sql .= " union all ";
}

$sql .= " select d.codret, ";
$sql .= "        p.k00_numcgm, ";
$sql .= "        $sCampoData as k00_dtpaga, ";
$sql .= "        a.k00_numpre, ";
$sql .= "        a.k00_numpar, ";
$sql .= "        null::integer    as numpre_origem,";
$sql .= "        null::integer    as numpar_origem,";
$sql .= "        0                as vlrcalc_origem, ";
$sql .= "        a.vlrpago        as vlrpago,";
$sql .= "        sum(p.k00_valor) as vlrcalc, ";
$sql .= "        c.z01_nome, "; 
$sql .= "        d.k00_conta, ";
$sql .= "        'RECIBO AVULSO'::text as tipo ";
$sql .= "   from disbanco a ";
$sql .= "        inner join disarq d on d.codret     = a.codret ";  
$sql .= "        inner join recibo p on a.k00_numpre = p.k00_numpre ";
$sql .= "        inner join cgm c    on c.z01_numcgm = p.k00_numcgm ";
$sql .= " where 1=1 $sWhereData $sWhereBco ";
$sql .= "   and a.classi = 't' ";
$sql .= " group by d.codret, ";
$sql .= "          p.k00_numcgm, ";
$sql .= "          a.k00_numpre, ";
$sql .= "          a.k00_numpar, ";
$sql .= "          numpre_origem,";
$sql .= "          numpar_origem,";
$sql .= "          a.vlrpago, ";
$sql .= "          c.z01_nome, ";
$sql .= "          $sCampoData, ";
$sql .= "          d.k00_conta ";

$sql = "select abs(round((vlrcalc - vlrpago),2)) as diferenca,* 
          from ($sql) as x 
         order by codret, ";
         
if ($ordem == "d") {
	$sql        .= "diferenca desc";
	$sCampoOrdem = "diferenca";
} elseif ($ordem == "a") {
	$sql        .= "z01_nome";
	$sCampoOrdem = "z01_nome";
} elseif ($ordem == "n") {
	$sql        .= "k00_numpre, k00_numpar";
	$sCampoOrdem = "k00_numpre"; 
}

$rsDadosBaixaBco = pg_query($sql) or die($sql);
$iLinhasBaixaBco = pg_num_rows($rsDadosBaixaBco); 

if ( $iLinhasBaixaBco == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem valores com diferença no período e banco escolhido.');
} 

$aDadosBaixaBco = array();

for ( $iInd=0; $iInd < $iLinhasBaixaBco; $iInd++ ) {
	
	$oBaixaBco = db_utils::fieldsMemory($rsDadosBaixaBco,$iInd);

	$oValoresBaixaBco = new stdClass();
	$oValoresBaixaBco->sNome      = $oBaixaBco->z01_nome;
	$oValoresBaixaBco->nValorCalc = $oBaixaBco->vlrcalc; 
	$oValoresBaixaBco->nValorPago = $oBaixaBco->vlrpago;
  $oValoresBaixaBco->nDiferenca = $oBaixaBco->diferenca;
	$oValoresBaixaBco->dtArq      = $oBaixaBco->k00_dtpaga;
	$oValoresBaixaBco->iConta     = $oBaixaBco->k00_conta;
	$oValoresBaixaBco->sTipo      = $oBaixaBco->tipo;

  if ( trim($oGet->difapartir) != '' ) {
    if( round($oValoresBaixaBco->nDiferenca,2) < round((float)$oGet->difapartir,2) ) {
      continue;  
    }    
  }
        	
  if( $oGet->imprimirsemdif == "nao" ) {
    if( round($oValoresBaixaBco->nDiferenca,2) == 0 ) {
      continue;
    }
  }  
  
	$oNumpreOrigem = new stdClass();
	$oNumpreOrigem->iNumpre    = $oBaixaBco->numpre_origem; 
	$oNumpreOrigem->iNumpar    = $oBaixaBco->numpar_origem;
	$oNumpreOrigem->nValorCalc = $oBaixaBco->vlrcalc_origem;
  

  $aDadosBaixaBco[$oBaixaBco->codret][$oBaixaBco->$sCampoOrdem][$oBaixaBco->z01_numcgm][$oBaixaBco->k00_numpre][$oBaixaBco->k00_numpar]['oDados']    = $oValoresBaixaBco; 

  if ( trim($oBaixaBco->numpre_origem) != '' ) {
	  $aDadosBaixaBco[$oBaixaBco->codret][$oBaixaBco->$sCampoOrdem][$oBaixaBco->z01_numcgm][$oBaixaBco->k00_numpre][$oBaixaBco->k00_numpar]['aOrigem'][] = $oNumpreOrigem;
  }
  
	
}

$head3 = "RELATÓRIO DE CONFERÊNCIA DOS ";
$head4 = "VALORES BAIXADOS POR BANCO";
$head5 = $sNomeBco;

if($oGet->tipocampo == 'arq'){
	$labeldata = '(Data do arquivo)';
	$labelRel = 'DT.ARQ';
}else{
	$labeldata = '(Data do pagamento)';
	$labelRel = 'PGTO';

}

if ( $lImprimeOrigem ) {
	$head6 = "Lista Numpres de Origem";
}

$head7 = 'Período'.$labeldata.' : '.db_formatar($oGet->datai,'d').' a '.db_formatar($oGet->dataf,'d');

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->AddPage();
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFont('Arial','B',6);
$oPdf->SetFillColor(210);

$iAlt        = 4;
$iPreenche   = 1;
$lImprimeCab = true;

if ( $quebrarpagina == "sim" ) {
  $lQuebra = true;
} else {
	$lQuebra = false;
}

$lPrimeiro = true;

foreach ( $aDadosBaixaBco as $iCodRet => $aDadosOrdem ) {
	
	$nTotalPago     = 0;
	$nTotalCalc     = 0;
	$nTotalDif      = 0;	
	$iContRegistros = 0;
	
	foreach ( $aDadosOrdem   as $sOrdem => $aDadosNumCGM ) {	
		foreach ( $aDadosNumCGM  as $iNumCGM => $aDadosNumpre ) {
			foreach ( $aDadosNumpre  as $iNumpre => $aDadosNumpar ) {
				foreach ( $aDadosNumpar  as $iNumpar => $aDadosGerais ) {
					
					$iContRegistros++;
					
					if ( $oPdf->gety() > ($oPdf->h - 30) || $lImprimeCab ) {
						
						if ( $lQuebra || ($oPdf->gety() > ($oPdf->h - 30)) ) {
							if ( !$lPrimeiro ) {
						    $oPdf->AddPage();
							}
						}
						
				    $oPdf->SetFont('Arial','B',6);
				    $oPdf->SetFillColor(210);
						$oPdf->Cell(12,$iAlt,"NUMCGM"    ,1,0,"L",1);
						$oPdf->Cell(45,$iAlt,"NOME"      ,1,0,"L",1);
						$oPdf->Cell(12,$iAlt,"NUMPRE"    ,1,0,"C",1);
						$oPdf->Cell(12,$iAlt,"PARC."     ,1,0,"C",1);
						$oPdf->Cell(15,$iAlt,"VLR PAGO"  ,1,0,"C",1);
						$oPdf->Cell(15,$iAlt,"VLR CALC"  ,1,0,"C",1);
						$oPdf->Cell(15,$iAlt,"DIFER."    ,1,0,"C",1);
						$oPdf->Cell(15,$iAlt,"$labelRel" ,1,0,"C",1);
						$oPdf->Cell(20,$iAlt,"CONTA"     ,1,0,"C",1);
						$oPdf->Cell(20,$iAlt,"TIPO"      ,1,0,"C",1);
						$oPdf->Cell(15,$iAlt,"CODRET"    ,1,1,"C",1);
				    $oPdf->SetFont('Arial','',6);
				    $oPdf->Ln(($iAlt/2));
				    $lImprimeCab = false;
 				    $lPrimeiro   = false;
	
				  }				

				  $sSqlSuperSimples  = "  select *                                                            "; 
          $sSqlSuperSimples .= "    from issvar                                                       "; 
          $sSqlSuperSimples .= "         inner join issarqsimplesregissvar on q68_issvar = q05_codigo ";
          $sSqlSuperSimples .= "   where q05_numpre = {$iNumpre}                                      ";
          $sSqlSuperSimples .= "     and q05_numpar = {$iNumpar}                                      ";
                                    
          $rsSuperSimples    = pg_query($sSqlSuperSimples) or die($sSqlSuperSimples);

				  if ( pg_numrows($rsSuperSimples) > 0 ) {
				    $aDadosGerais['oDados']->nValorCalc = $aDadosGerais['oDados']->nValorPago;
				  }				  
				  
				  $nDiferenca = round($aDadosGerais['oDados']->nValorCalc,2) - round($aDadosGerais['oDados']->nValorPago,2);
		  		if( $oGet->imprimirsemdif == "nao" ) {
            if( round($nDiferenca,2) == 0 ) {
               continue;
            }
          }
				  
				  if ( $iPreenche == 0 ) {
				  	$iPreenche = 1;
				    $iCorFundo = 236;
				  } else {
				  	$iPreenche = 0;
				    $iCorFundo = 245;
				  }
				  				  
          
          $oPdf->SetFillColor($iCorFundo);				  
		      $oPdf->Cell(12,$iAlt,$iNumCGM                                            ,0,0,"C",1);
		      $oPdf->Cell(45,$iAlt,$aDadosGerais['oDados']->sNome                      ,0,0,"L",1);
		      $oPdf->Cell(12,$iAlt,$iNumpre                                            ,0,0,"C",1);
		      $oPdf->Cell(12,$iAlt,"$iNumpar"                                          ,0,0,"C",1);
		      $oPdf->Cell(15,$iAlt,db_formatar($aDadosGerais['oDados']->nValorPago,'f'),0,0,"R",1);
		      $oPdf->Cell(15,$iAlt,db_formatar($aDadosGerais['oDados']->nValorCalc,'f'),0,0,"R",1);
		      $oPdf->Cell(15,$iAlt,db_formatar($nDiferenca,'f')                        ,0,0,"R",1);
		      $oPdf->Cell(15,$iAlt,db_formatar($aDadosGerais['oDados']->dtArq,'d')     ,0,0,"C",1);
		      $oPdf->Cell(20,$iAlt,$aDadosGerais['oDados']->iConta                     ,0,0,"C",1);
		      $oPdf->Cell(20,$iAlt,$aDadosGerais['oDados']->sTipo                      ,0,0,"L",1);
		      $oPdf->Cell(15,$iAlt,$iCodRet                                            ,0,1,"C",1);			  

          $lImprimeSubCab = true;
		      
          
          if ( isset($aDadosGerais['aOrigem']) && $lImprimeOrigem ) {
			      foreach ( $aDadosGerais['aOrigem'] as $iInd => $oNumpreOrigem ) {
			      	
			      	if ( $oPdf->gety() > ($oPdf->h - 30) || $lImprimeSubCab ) {
			      		$oPdf->SetFont('Arial','B',6);
			      		$oPdf->SetFillColor(210);
			      		$oPdf->Cell(136,$iAlt,''             ,'T',0,"C",0);
			          $oPdf->Cell(20 ,$iAlt,'NUMPRE ORIGEM',1,0,"C",1);
			          $oPdf->Cell(20 ,$iAlt,'NUMPAR ORIGEM',1,0,"C",1);
			          $oPdf->Cell(20 ,$iAlt,'VLR CALC'     ,1,1,"C",1);
			          $oPdf->SetFont('Arial','',6);
			          $lImprimeSubCab = false;
			      	}
	
	            $oPdf->Cell(136,$iAlt,''                                         ,0,0,"C",0);
	            $oPdf->Cell(20 ,$iAlt,$oNumpreOrigem->iNumpre                    ,0,0,"C",0);
	            $oPdf->Cell(20 ,$iAlt,$oNumpreOrigem->iNumpar                    ,0,0,"C",0);
	            $oPdf->Cell(20 ,$iAlt,db_formatar($oNumpreOrigem->nValorCalc,'f'),0,1,"R",0);
			      	
			      }
          }
		      
		      $nTotalPago += $aDadosGerais['oDados']->nValorPago;
		      $nTotalCalc += $aDadosGerais['oDados']->nValorCalc;
		      $nTotalDif  += $nDiferenca;
		      
				}
			}
		}
	}
	
  if( round($nDiferenca,2) <> 0 || $oGet->imprimirsemdif == "sim" ) {
      $oPdf->Ln(($iAlt/2));
	    $oPdf->SetFont('Arial','B',6);
	    $oPdf->Cell(81,$iAlt,"TOTAL  : ".$iContRegistros." REGISTROS",0,0,"L",0);
	    $oPdf->Cell(15,$iAlt,db_formatar($nTotalPago,'f')            ,0,0,"R",0);
	    $oPdf->Cell(15,$iAlt,db_formatar($nTotalCalc,'f')            ,0,0,"R",0);
	    $oPdf->Cell(15,$iAlt,db_formatar($nTotalDif,'f')             ,0,0,"R",0);
	    $oPdf->Ln($iAlt*2);
	    $oPdf->SetFont('Arial','',6);
      $oPdf->Ln(($iAlt/2));
      
      $lImprimeCab = true;
  }      
	
}

$oPdf->Output();

?>
<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

if ($oGet->sPonto == 's') {
	$sTipo = 'SALÁRIO';
	$sSigla = 'r14';
} elseif ($oGet->sPonto == 'c') {
	$sTipo = 'COMPLEMENTAR';
	$sSigla = 'r48';
} elseif ($oGet->sPonto == 'd') {
	$sTipo = '13o. SALÁRIO';
	$sSigla = 'r35';
} elseif ($oGet->sPonto == 'r') {
	$sTipo = 'RECISÃO';
	$sSigla = 'r20';
} elseif ($oGet->sPonto == 'a') {
	$sTipo = 'ADIANTAMENTO';
	$sSigla = 'r22';
}

$head2 = "RESUMO DOS EMPENHOS";
$head4 = "PERIODO : ".$oGet->iMes." / ".$oGet->iAno;
$head6 = "TIPO    : $sTipo";

$sSql  = "	select rubric,                                                                                                                                                              \n";
$sSql .= "       tipo,                                                                                                                                                                  \n";
$sSql .= "       descricao,                                                                                                                                                             \n";
$sSql .= "       recurso,                                                                                                                                                               \n";
$sSql .= "       descr_recurso,                                                                                                                                                         \n";
$sSql .= "       round(sum(case                                                                                                                                                         \n";
$sSql .= "          when pd = 1 then valor                                                                                                                                              \n";
$sSql .= "          when pd = 2 then 0                                                                                                                                                  \n";
$sSql .= "       end),2) as provento,                                                                                                                                                   \n";
$sSql .= "       round(sum(case                                                                                                                                                         \n";
$sSql .= "          when pd = 2 then valor                                                                                                                                              \n";
$sSql .= "          when pd = 1 then 0                                                                                                                                                  \n";
$sSql .= "       end),2) as desconto                                                                                                                                                    \n";
$sSql .= "  from ( select rh73_rubric as rubric,                                                                                                                                        \n";
$sSql .= "                case                                                                                                                                                          \n";
$sSql .= "                  when rh78_sequencial is null then 'e'                                                                                                                       \n";
$sSql .= "                  else case                                                                                                                                                   \n";
$sSql .= "                         when e21_retencaotiporecgrupo = 3 then 'p'                                                                                                           \n";
$sSql .= "                         when e21_retencaotiporecgrupo = 4 then 'd'                                                                                                           \n";
$sSql .= "                         when e21_retencaotiporecgrupo = 2 then 'r'                                                                                                           \n";
$sSql .= "                         else ''                                                                                                                                              \n";
$sSql .= "                       end                                                                                                                                                    \n";
$sSql .= "                end as tipo,                                                                                                                                                  \n";
$sSql .= "                rh27_descr   as descricao,                                                                                                                                    \n";
$sSql .= "                rh72_recurso as recurso,                                                                                                                                      \n";
$sSql .= "                o15_descr    as descr_recurso,                                                                                                                                \n";
$sSql .= "                rh73_pd      as pd,                                                                                                                                           \n";
$sSql .= "                rh73_valor   as valor                                                                                                                                         \n";
$sSql .= "           from rhempenhofolha                                                                                                                                                \n";
$sSql .= "                inner join rhempenhofolharhemprubrica    on rh81_rhempenhofolha        = rhempenhofolha.rh72_sequencial                                                       \n";
$sSql .= "                inner join rhempenhofolharubrica         on rh73_sequencial            = rhempenhofolharhemprubrica.rh81_rhempenhofolharubrica                                \n";
$sSql .= "                inner join rhrubricas                    on rh27_rubric                = rhempenhofolharubrica.rh73_rubric                                                    \n";
$sSql .= "                                                        and rh27_instit                = rhempenhofolharubrica.rh73_instit                                                    \n";
$sSql .= "                left  join rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial                                                \n";
$sSql .= "                left  join retencaotiporec               on e21_sequencial             = rhempenhofolharubricaretencao.rh78_retencaotiporec                                   \n";
$sSql .= "                left  join orctiporec                    on o15_codigo                 = rhempenhofolha.rh72_recurso                                                          \n";
$sSql .= "          where rh72_anousu   = {$oGet->iAno}                                                                                                                                 \n";
$sSql .= "            and rh72_mesusu   = {$oGet->iMes}                                                                                                                                 \n";
$sSql .= "            and rh72_siglaarq = '$sSigla'                                                                                                                                     \n";
$sSql .= "            and rh27_pd <> 3                                                                                                                                                  \n";

if ($oGet->sPonto == 'c') {
	$sSql .= "          and rh72_seqcompl <> '0' 																																																																				  \n";
	if ($oGet->iSemestre != '') {
		$sSql .= "        and rh72_seqcompl = {$oGet->iSemestre}                                                                                                                            \n";
	}
}

$sSql .= "       union all                                                                                                                                                              \n";
$sSql .= "                                                                                                                                                                              \n";
$sSql .= "         select rh73_rubric as rubric,                                                                                                                                        \n";
$sSql .= "                case                                                                                                                                                          \n";
$sSql .= "                  when rh78_sequencial is null then 'Slip'                                                                                                                    \n";
$sSql .= "                  else case                                                                                                                                                   \n";
$sSql .= "                         when e21_retencaotiporecgrupo = 3 then 'p'                                                                                                           \n";
$sSql .= "                         when e21_retencaotiporecgrupo = 4 then 'd'                                                                                                           \n";
$sSql .= "                         when e21_retencaotiporecgrupo = 2 then 'r'                                                                                                           \n";
$sSql .= "                         else ''                                                                                                                                              \n";
$sSql .= "                       end                                                                                                                                                    \n";
$sSql .= "                end as tipo,                                                                                                                                                  \n";
$sSql .= "                rh27_descr   as descricao,                                                                                                                                    \n";
$sSql .= "                rh79_recurso as recurso,                                                                                                                                      \n";
$sSql .= "                o15_descr    as descr_recurso,                                                                                                                                \n";
$sSql .= "                rh73_pd      as pd,                                                                                                                                           \n";
$sSql .= "                rh73_valor   as valor                                                                                                                                         \n";
$sSql .= "           from rhslipfolha                                                                                                                                                   \n";
$sSql .= "                inner join rhslipfolharhemprubrica       on rhslipfolharhemprubrica.rh80_rhslipfolha                 = rhslipfolha.rh79_sequencial                            \n";
$sSql .= "                inner join rhempenhofolharubrica         on rhempenhofolharubrica.rh73_sequencial                    = rhslipfolharhemprubrica.rh80_rhempenhofolharubrica     \n";
$sSql .= "                inner join rhrubricas                    on rhrubricas.rh27_rubric                                   = rhempenhofolharubrica.rh73_rubric                      \n";
$sSql .= "                                                        and rhrubricas.rh27_instit                                   = rhempenhofolharubrica.rh73_instit                      \n";
$sSql .= "                left  join rhempenhofolharubricaretencao on rhempenhofolharubricaretencao.rh78_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial                  \n";
$sSql .= "                left  join retencaotiporec               on retencaotiporec.e21_sequencial                           = rhempenhofolharubricaretencao.rh78_retencaotiporec     \n";
$sSql .= "                left  join orctiporec                    on orctiporec.o15_codigo                                    = rhslipfolha.rh79_recurso                               \n";

$sSql .= "          where rh79_anousu   = {$oGet->iAno}                                                                                                                                 \n";
$sSql .= "            and rh79_mesusu   = {$oGet->iMes}                                                                                                                                 \n";
$sSql .= "            and rh79_siglaarq = '$sSigla'                                                                                                                                     \n";
$sSql .= "            and rh27_pd <> 3                                                                                                                                                  \n";

if ($oGet->sPonto == 'c') {
	$sSql .= "          and rh79_seqcompl <> '0' 																																																																				  \n";
	if ($oGet->iSemestre != '') {
		$sSql .= "        and rh79_seqcompl = {$oGet->iSemestre}                                                                                                                            \n";
	}
}

if ($oGet->sPonto == 's') {

	$sSql .= "                                                                                   \n";
	$sSql .= " union all                                                                         \n";
	$sSql .= "                                                                                   \n";
	$sSql .= " select rh27_rubric,                                                               \n";
	$sSql .= "        '',                                                                        \n";
	$sSql .= "        rh27_descr,                                                                \n";
	$sSql .= "        rh25_recurso,                                                              \n";
	$sSql .= "        o15_descr,                                                                 \n";
	$sSql .= "        r14_pd,                                                                    \n";
	$sSql .= "        r14_valor                                                                  \n";
	$sSql .= "   from gerfsal                                                                    \n";
	$sSql .= "        inner join rhrubricas                    on r14_rubric     = rh27_rubric   \n";
	$sSql .= "                                                and r14_instit     = rh27_instit   \n";
	$sSql .= "        left  join rhrubretencao                 on rh27_rubric    = rh75_rubric   \n";
	$sSql .= "                                                and rh27_instit    = rh75_instit   \n";
	$sSql .= "        left  join rhrubelemento                 on rh27_rubric    = rh23_rubric   \n";
	$sSql .= "                                                and rh27_instit    = rh23_instit   \n";
	$sSql .= "        left  join rhlotavinc                    on r14_lotac::int = rh25_codigo   \n";
	$sSql .= "                                                and r14_anousu     = rh25_anousu   \n";
	$sSql .= "        left  join orctiporec                    on o15_codigo     = rh25_recurso  \n";
	$sSql .= "  where rh27_pd <> 3                                                               \n";
	$sSql .= "    and rh27_instit = ".db_getsession('DB_instit')."                               \n";
	$sSql .= "    and rh75_rubric is null                                                        \n";
	$sSql .= "    and rh23_rubric is null                                                        \n";
	$sSql .= "    and r14_anousu = {$oGet->iAno}                                                 \n";
	$sSql .= "    and r14_mesusu = {$oGet->iMes}                                                 \n";

}
 
$sSql .= "           ) as x         \n";
$sSql .= "  group by rubric,        \n";
$sSql .= "           tipo,          \n";
$sSql .= "           descricao,     \n";
$sSql .= "           recurso,       \n";
$sSql .= "           descr_recurso  \n";
$sSql .= "  order by recurso,rubric \n";

$rsResultado = db_query($sSql);

if (pg_num_rows($rsResultado) == 0) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$oGet->iMes.' / '.$oGet->iAno);
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$oPdf->setfont('arial','b',8);
$iTroca           = 1;
$iAltura          = 4;
$nEmpenhos        = 0;
$nPagamentos      = 0;
$nRetencoes       = 0;
$nDevolucoes      = 0;
$nOutros          = 0;
$nProv            = 0;
$nDesconto        = 0;
$nTotalEmpenhos   = 0;
$nTotalPagamentos = 0;
$nTotalRetencoes  = 0;
$nTotalDevolucoes = 0;
$nTotalOutros     = 0;
$iContsec         = '';
$oPdf->setfillcolor(235);

for ($iCont = 0; $iCont < pg_num_rows($rsResultado); $iCont++) {

	db_fieldsmemory($rsResultado,$iCont);
	 
	if ($iContsec != $recurso) {

		$iTroca = 1;
		$iContsec = $recurso;
		if ($iCont != 0 ) {
			
			$oPdf->setfont('arial','b',8);
			$oPdf->ln(3);
			$oPdf->cell(85,6,' ',"T",0,"L",0);
			$oPdf->cell(30,6,db_formatar($nProv,'f')    ,"T",0,"R",0);
			$oPdf->cell(30,6,db_formatar($nDesconto,'f'),"T",1,"R",0);
			$oPdf->cell(115,4,'TOTAL DE EMPENHOS  :    '  ,0,0,"R",0);
			$oPdf->cell(30,4,db_formatar($nEmpenhos,'f')  ,0,1,"R",0);
			$oPdf->cell(115,4,'TOTAL DE PAG.EXTRA :    '  ,0,0,"R",0);
			$oPdf->cell(30,4,db_formatar($nPagamentos,'f'),0,1,"R",0);
			$oPdf->cell(115,4,'TOTAL DE RETENCOES :    '  ,0,0,"R",0);
			$oPdf->cell(30,4,db_formatar($nRetencoes,'f') ,0,1,"R",0);
			$oPdf->cell(115,4,'TOTAL DE DEVOLUCOES:    '  ,0,0,"R",0);
			$oPdf->cell(30,4,db_formatar($nDevolucoes,'f'),0,1,"R",0);
			$oPdf->cell(115,4,'TOTAL DE OUTROS :    '     ,0,0,"R",0);
			$oPdf->cell(30,4,db_formatar($nOutros,'f')    ,0,1,"R",0);
			$oPdf->cell(115,4,'LÍQUIDO :    '             ,0,0,"R",0);
			$oPdf->cell(30,4,db_formatar( ( $nEmpenhos + $nPagamentos - $nRetencoes + $nOutros + $nDevolucoes ) ,'f'),0,1,"R",0);			
			$nEmpenhos   = 0;
			$nPagamentos = 0;
			$nRetencoes  = 0;
			$nDevolucoes = 0;
			$nOutros     = 0;
			$nProv       = 0;
			$nDesconto   = 0;
			
		}
		
	}
	
	if ($oPdf->gety() > $oPdf->h - 30 || $iTroca != 0) {
		
		$oPdf->addpage();
		$oPdf->setfont('arial','b',8);
		$oPdf->cell(15,$iAltura,'RUBRICA'  ,1,0,"C",1);
		$oPdf->cell(70,$iAltura,'DESCRICAO',1,0,"C",1);
		$oPdf->cell(30,$iAltura,'PROVENTO' ,1,0,"C",1);
		$oPdf->cell(30,$iAltura,'DESCONTO' ,1,1,"C",1);
		$oPdf->ln(3);
		$oPdf->cell(0,$iAltura,$recurso.' - '.$descr_recurso,0,1,"L",0);
		$iTroca = 0;
		$iPre = 1;
	}
	
	if ($iPre == 1) {
		$iPre = 0;
	} else {
		$iPre = 1;
	}
	
	$oPdf->setfont('arial','',7);
	$oPdf->cell(15,$iAltura,$tipo.'-'.$rubric         ,0,0,"C",$iPre);
	$oPdf->cell(70,$iAltura                ,$descricao,0,0,"L",$iPre);
	$oPdf->cell(30,$iAltura,db_formatar($provento,'f'),0,0,"R",$iPre);
	$oPdf->cell(30,$iAltura,db_formatar($desconto,'f'),0,1,"R",$iPre);
	
	if ($tipo == 'e') {
		
		if ($provento > 0) {
			$nEmpenhos      += $provento;
			$nTotalEmpenhos += $provento;
		} else {
			$nEmpenhos      -= $desconto;
			$nTotalEmpenhos -= $desconto;
		}
		
	} elseif ($tipo == 'r') {
		
		if ($provento > 0) {
			$nRetencoes      -= $provento;
			$nTotalRetencoes -= $provento;
		} else {
			$nRetencoes      += $desconto;
			$nTotalRetencoes += $desconto;
		}
		
	} elseif ($tipo == 'p') {
		
		if ($provento > 0) {
			$nPagamentos      += $provento;
			$nTotalPagamentos += $provento;
		} else {
			$nPagamentos      -= $desconto;
			$nTotalPagamentos -= $desconto;
		}
		
	} elseif ($tipo == '') {
		
		if ($provento > 0) {
			$nOutros      += $provento;
			$nTotalOutros += $provento;
		} else {
			$nOutros      -= $desconto;
			$nTotalOutros -= $desconto;
		}
		
	} elseif ($tipo == 'd') {
		
		if ($provento > 0) {
			$nDevolucoes      += $provento;
			$nTotalDevolucoes += $provento;
		} else {
			$nDevolucoes      -= $desconto;
			$nTotalDevolucoes -= $desconto;
		}
		
	}
	
	$nProv     += $provento;
	$nDesconto += $desconto;
}

$oPdf->setfont('arial','b',8);
$oPdf->ln(3);
$oPdf->cell(85 ,6,' '                         ,"T",0,"L",0);
$oPdf->cell(30 ,6,db_formatar($nProv,'f')     ,"T",0,"R",0);
$oPdf->cell(30 ,6,db_formatar($nDesconto,'f') ,"T",1,"R",0);
$oPdf->cell(115,4,'TOTAL DE EMPENHOS  :    '    ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nEmpenhos,'f')   ,0,1,"R",0);
$oPdf->cell(115,4,'TOTAL DE PAG.EXTRA :    '    ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nPagamentos,'f') ,0,1,"R",0);
$oPdf->cell(115,4,'TOTAL DE RETENCOES :    '    ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nRetencoes,'f')  ,0,1,"R",0);
$oPdf->cell(115,4,'TOTAL DE DEVOLUCOES:    '    ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nDevolucoes,'f') ,0,1,"R",0);
$oPdf->cell(115,4,'TOTAL DE OUTROS:    '        ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nOutros,'f')     ,0,1,"R",0);
$oPdf->cell(115,4,'LÍQUIDO :    '               ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar( ( $nEmpenhos + $nPagamentos - $nRetencoes + $nOutros + $nDevolucoes ) ,'f'),0,1,"R",0);

$oPdf->setfont('arial','b',8);
$oPdf->ln(3);
$oPdf->cell(145,6,' '                             ,"T",1,"L",0);
$oPdf->cell(115,4,'TOTAL GERAL DE EMPENHOS  :    '  ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nTotalEmpenhos,'f')  ,0,1,"R",0);
$oPdf->cell(115,4,'TOTAL GERAL DE PAG.EXTRA :    '  ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nTotalPagamentos,'f'),0,1,"R",0);
$oPdf->cell(115,4,'TOTAL GERAL DE RETENCOES :    '  ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nTotalRetencoes,'f') ,0,1,"R",0);
$oPdf->cell(115,4,'TOTAL GERAL DE DEVOLUCOES:    '  ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nTotalDevolucoes,'f'),0,1,"R",0);
$oPdf->cell(115,4,'TOTAL GERAL DE OUTROS :   '      ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar($nTotalOutros,'f')    ,0,1,"R",0);
$oPdf->cell(115,4,'LÍQUIDO :    '                   ,0,0,"R",0);
$oPdf->cell(30 ,4,db_formatar( ( $nTotalEmpenhos + $nTotalPagamentos - $nTotalRetencoes + $nTotalOutros + $nTotalDevolucoes ) ,'f'),0,1,"R",0);

$oPdf->Output();
?>
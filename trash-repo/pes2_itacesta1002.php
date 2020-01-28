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
require_once("libs/db_stdlib.php");

$oGet = db_utils::postMemory($_GET);

$clrotulo = new rotulocampo;
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
$clrotulo->label('rh27_elemen');
$clrotulo->label('rh27_pd');

$head1 = "RELAÇÃO DE CONFERÊNCIA";
$head2 = "CESTA BÁSICA ";
$head3 = "PERÍODO : ".$oGet->iMes." / ".$oGet->iAno;

/**
 * Define o tipo de resumo e a header.
 */
if ($oGet->iTipoResumo == 2) {
	
	$head4 = "LOTAÇÃO(ÕES) : ".$aLotacoes;
	$aLotacoes = "'".str_replace(",","','",$oGet->aLotacoes)."'";
	
} else if ($oGet->iTipoResumo == 3) {
	
	$head4 = "ÓRGÃO(S) : ".$aOrgaos;
	$aOrgaos = "'".str_replace(",","','",$oGet->aOrgaos)."'";
	
}

$iInstituicao = db_getsession("DB_instit");

$iAno         = $oGet->iAno;

$iMes         = $oGet->iMes;


$sSql  = "select gerfsal.r14_regist,                                                                      ";
$sSql .= "       z01_nome,                                                                                ";
$sSql .= "       round(sum(gerfsal.r14_valor),2) as bruto,                                                ";
$sSql .= "       round(x.r14_valor,2) as cesta,                                                           ";
$sSql .= "       (case when sum(gerfsal.r14_valor) > 1062.56 and (x.r14_valor>0 and x.r14_valor <> 28.23) ";
$sSql .= "             then 'RECEBENDO A MAIS'                                                            ";
$sSql .= "             else(case when sum(gerfsal.r14_valor) < 1062.56 and x.r14_valor is null            ";
$sSql .= "                       then 'RECEBENDO A MENOS'                                                 ";
$sSql .= "                  end)                                                                          ";
$sSql .= "             end) as situacao                                                                   ";
$sSql .= "  from rhpessoalmov                                                                             ";
$sSql .= "	     inner join rhpessoal   on rh01_regist       = rh02_regist                                ";
$sSql .= "       inner join cgm         on rh01_numcgm       = z01_numcgm                                 ";
$sSql .= "       inner join gerfsal     on r14_anousu        = rh02_anousu                                ";
$sSql .= "			                       and r14_mesusu        = rh02_mesusu                                ";
$sSql .= "                             and r14_regist        = rh02_regist                                ";
$sSql .= "				  								   and r14_instit        = rh02_instit                                ";
$sSql .= "       inner join rhrubricas  on r14_rubric        = rh27_rubric                                ";
$sSql .= "			                       and rh27_instit       = r14_instit                                 ";
$sSql .= "        left join rhlota      on rhlota.r70_codigo = rhpessoalmov.rh02_lota                     ";
$sSql .= "  										       and rhlota.r70_instit = rhpessoalmov.rh02_instit                   ";
$sSql .= "        left join rhlotaexe   on rh26_codigo       = r70_codigo                                 ";
$sSql .= "                             and rh26_anousu       = {$iAno}                                    ";
$sSql .= "        left join orcorgao    on o40_orgao         = rh26_orgao                                 ";
$sSql .= "        left join (select r14_regist,                                                		        ";
$sSql .= "                          r14_valor                                                  		        ";
$sSql .= "                     from gerfsal                                                    		        ";
$sSql .= "                    where r14_anousu = {$iAno}                                      		        ";
$sSql .= "                      and r14_mesusu = {$iMes}                                      		        ";
$sSql .= "										  and r14_instit = {$iInstituicao}                                	        ";
$sSql .= "                      and r14_rubric = '0064') as x                                             "; 
$sSql .= "                             on gerfsal.r14_regist = x.r14_regist                               ";
$sSql .= "        left join rhregime   on rh02_codreg       = rh30_codreg                                 ";
$sSql .= "        where rh02_anousu = {$iAno}                                                             ";
$sSql .= "           and rh02_mesusu = {$iMes}                                                            ";
$sSql .= "				   and rh02_instit = {$iInstituicao}                                                    ";
$sSql .= "           and r14_pd      = 1                                                                  ";
$sSql .= "           and r14_pd     <> 3                                                                  ";
$sSql .= "           and r14_rubric  = rh27_rubric                                                        ";
$sSql .= "           and rh27_tipo   = '1'                                                                ";

if ($oGet->aLotacoes != '') {
	$sSql .= "				 and rhlota.r70_codigo in ({$aLotacoes})  	      																		";	                   
}
if ($oGet->aOrgaos   != '') {
	$sSql .= "				 and orcorgao.o40_orgao in ({$aOrgaos}) 	  										      								";
}

/**
 * Define o vínculo e a header
 */
if ($oGet->iVinculo == 2) {
	
	$sSql .= "and rh30_vinculo = 'A'                                                                        ";
	$head5 = 'VÍNCULO : ATIVOS';
	
} else if ($oGet->iVinculo == 3) {
	
	$sSql .= "and rh30_vinculo = 'I'                                                                        ";
	$head5 = 'VÍNCULO : INATIVOS';
	
} else if ($oGet->iVinculo == 4) {
	
	$sSql .= "and rh30_vinculo = 'P'                                                                        ";
	$head5 = 'VÍNCULO : PENSIONISTAS';
	
} else if ($oGet->iVinculo == 5) {
	
	$sSql .= "and rh30_vinculo in ('I','P')                                                                 ";
	$head5 = 'VÍNCULO : INATIVOS/PENSIONISTAS';
	
}

$sSql .= "         group by z01_nome, gerfsal.r14_regist, x.r14_valor                                     ";

/**
 * Define a ordem e a header
 */
if ($oGet->iTipoOrdem == 1) {
	
	$sSql .= "         order by z01_nome asc                                                                ";
	$head6 = 'ORDEM : ALFABÉTICA';
	
} else if ($oGet->iTipoOrdem == 2) {
	
	$sSql .= "         order by r14_regist asc                                                              ";
	$head6 = 'ORDEM : NUMÉRICA';
	
}

$rsResult = db_query($sSql);

if (pg_num_rows($rsResult) == 0) {
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$iMes.' / '.$iAno);
}

$oPdf = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);

$iTroca             = 1;
$iAltura            = 4;
$iTotalFuncionarios = 0;

for ($iContador = 0; $iContador < pg_numrows($rsResult); $iContador++) {
	
   $oCestaBasica = db_utils::fieldsMemory($rsResult, $iContador);
   
   if ($oPdf->gety() > $oPdf->h - 30 || $iTroca != 0 ){
   	
      $oPdf->addpage();
      $oPdf->setfont('arial','b',8);
      
      $oPdf->cell(15,$iAltura, 'CÓDIGO'             , 1, 0, "C", 1);
      $oPdf->cell(70,$iAltura, 'NOME DO FUNCIONÁRIO', 1, 0, "C", 1);
      $oPdf->cell(25,$iAltura, 'BRUTO'              , 1, 0, "C", 1);
      $oPdf->cell(25,$iAltura, 'CESTA'              , 1, 0, "C", 1);
      $oPdf->cell(50,$iAltura, ''                   , 1, 1, "C", 1);
      $iTroca         = 0;
      $iPreenchimento = 1;
      $oPdf->ln(4);
      
   }
   
   if ($iPreenchimento == 1) {
   	
     $iPreenchimento = 0;
     
   } else {
   	
     $iPreenchimento = 1;
     
   }
   
   $oPdf->setfont('arial','',7);
   $oPdf->cell(15, $iAltura, $oCestaBasica->r14_regist, 0, 0, "C", $iPreenchimento);
   $oPdf->cell(70, $iAltura, $oCestaBasica->z01_nome  , 0, 0, "L", $iPreenchimento);
   $oPdf->cell(25, $iAltura, $oCestaBasica->bruto     , 0, 0, "R", $iPreenchimento);
   $oPdf->cell(25, $iAltura, $oCestaBasica->cesta     , 0, 0, "R", $iPreenchimento);
   $oPdf->cell(50, $iAltura, $oCestaBasica->situacao  , 0, 1, "L", $iPreenchimento);
   $iTotalFuncionarios += 1;
   
}

$oPdf->setfont('arial','b',8);
$oPdf->cell(185,$iAltura,'TOTAL GERAL  :  '.$iTotalFuncionarios.'   FUNCIONÁRIOS',"T", 0, "C", 0);

$oPdf->Output();
   
?>
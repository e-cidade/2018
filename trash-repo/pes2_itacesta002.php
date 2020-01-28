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
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head1 = "RELAÇÃO DE ASSINATURAS";
$head2 = "CESTA BÁSICA - 1% BÁSICO";
$head3 = "PERÍODO : ".$oGet->iMes." / ".$oGet->iAno;

/**
 * Define o tipo de resumo e a header.
 */
if ($oGet->iTipoResumo == 2) {
	
	$head4 = "LOTAÇÕES : ".$aLotacoes;
	$aLotacoes = "'".str_replace(",","','",$oGet->aLotacoes)."'";
	
} else if ($oGet->iTipoResumo == 3) {
	
	$head4 = "ÓRGÃO(S) : ".$aOrgaos;
	$aOrgaos = "'".str_replace(",","','",$oGet->aOrgaos)."'";
	
}

$iInstituicao = db_getsession("DB_instit");

$iAno         = $oGet->iAno;

$iMes         = $oGet->iMes;


$sSql  = " select distinct(rh01_regist),                                               \n";
$sSql .= "        z01_nome                                                             \n";
$sSql .= "  from rhpessoalmov                                                          \n";
$sSql .= "	inner join rhpessoal   on rh01_regist       = rh02_regist                  \n";
$sSql .= "  inner join cgm         on rh01_numcgm       = z01_numcgm                   \n";
$sSql .= "  inner join gerfsal     on r14_anousu        = rh02_anousu                  \n";
$sSql .= "	                      and r14_mesusu        = rh02_mesusu                  \n";
$sSql .= "                        and r14_regist        = rh02_regist                  \n";
$sSql .= "	  								    and r14_instit        = rh02_instit                  \n";
$sSql .= "  inner join rhrubricas  on r14_rubric        = rh27_rubric                  \n";
$sSql .= "	                      and rh27_instit       = r14_instit                   \n";
$sSql .= "  left join rhlota       on rhlota.r70_codigo = rhpessoalmov.rh02_lota       \n";
$sSql .= "  							        and rhlota.r70_instit = rhpessoalmov.rh02_instit     \n";
$sSql .= "  left join rhlotaexe    on rh26_codigo       = r70_codigo                   \n";
$sSql .= "                        and rh26_anousu       = {$iAno}                      \n";
$sSql .= "  left join orcorgao     on o40_orgao         = rh26_orgao                   \n";
$sSql .= "  left join rhregime     on rh02_codreg       = rh30_codreg                  \n";
$sSql .= " where r14_anousu = {$iAno}                                                  \n";
$sSql .= "   and r14_mesusu = {$iMes}                                                  \n";
$sSql .= "	 and r14_instit = {$iInstituicao}                                          \n";
$sSql .= "   and r14_rubric = '0064'                                                   \n";
if ($oGet->aLotacoes != '') {
	$sSql .= "					 and rhlota.r70_codigo in ({$aLotacoes})	      								 ";
}
if ($oGet->aOrgaos   != '') {
	$sSql .= "					 and orcorgao.o40_orgao in ({$aOrgaos})	  										   ";
}                                                                                      
                                                                                       
/**                                                                                    
 * Define o vínculo e a header                                                         
 */                                                                                    
if ($oGet->iVinculo == 2) {                                                            
	                                                                                     
	$sSql .= "and rh30_vinculo = 'A'                                                     ";
	$head5 = 'VÍNCULO : ATIVOS';                                                         
	                                                                                     
} else if ($oGet->iVinculo == 3) {                                                     
	                                                                                     
	$sSql .= "and rh30_vinculo = 'I'                                                     ";
	$head5 = 'VÍNCULO : INATIVOS';                                                       
	                                                                                     
} else if ($oGet->iVinculo == 4) {                                                     
	                                                                                     
	$sSql .= "and rh30_vinculo = 'P'                                                     ";
	$head5 = 'VÍNCULO : PENSIONISTAS';                                                   
	                                                                                     
} else if ($oGet->iVinculo == 5) {                                                     
	                                                                                     
	$sSql .= "and rh30_vinculo in ('I','P')                                              ";
	$head5 = 'VÍNCULO : INATIVOS/PENSIONISTAS';
	
}

/**
 * Define a ordem e a header
 */
if ($oGet->iTipoOrdem == 1) {
	
	$sSql .= "         order by z01_nome asc                                              ";
	$head6 = 'ORDEM : ALFABÉTICA';
	
} else if ($oGet->iTipoOrdem == 2) {
	
	$sSql .= "         order by rh01_regist asc                                            ";
	$head6 = 'ORDEM : NUMÉRICA';
	
}

$rsResult = db_query($sSql);

if (pg_numrows($rsResult) == 0) {
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$iMes.' / '.$iAno);

}

$oPdf = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);

$iTroca             = 1;
$iAltura            = 8;
$iTotalFuncionarios = 0;

for ($iContador = 0; $iContador < pg_numrows($rsResult); $iContador++) {
	
   $oCestaBasica = db_utils::fieldsMemory($rsResult, $iContador);
   
   if ($oPdf->gety() > $oPdf->h - 30 || $iTroca != 0 ) {
   	
      $oPdf->addpage();
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(15, $iAltura, 'CÓDIGO'             , 1, 0, "C", 1);
      $oPdf->cell(70, $iAltura, 'NOME DO FUNCIONÁRIO', 1, 0, "C", 1);
      $oPdf->cell(70, $iAltura, 'ASSINATURA'         , 1, 1, "C", 1);
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
   $oPdf->cell(15, $iAltura, $oCestaBasica->rh01_regist, 0, 0, "C", $iPreenchimento);
   $oPdf->cell(70, $iAltura, $oCestaBasica->z01_nome   , 0, 0, "L", $iPreenchimento);
   $oPdf->cell(70, $iAltura, '.....................................................................................', 0, 1, "C", $iPreenchimento);
   $iTotalFuncionarios += 1;
   
}

$oPdf->setfont('arial', 'b', 8);
$oPdf->cell(155, $iAltura, 'TOTAL GERAL  :  '.$iTotalFuncionarios.'   FUNCIONÁRIOS', "T", 0, "C", 0);

$oPdf->Output();
   
?>
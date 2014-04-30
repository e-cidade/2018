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

$oPost = db_utils::postMemory($_POST);
$oGet  =  db_utils::postMemory($_GET);

$ano     = db_getsession('DB_anousu');
$mes     = db_mesfolha();
$iInstit = db_getsession('DB_instit');

$sDescricao    = '';
$sComplementar = '';

if (isset($oGet->ano)) {
  $ano = $oGet->ano;
}

if (isset($oGet->mes)) {
	$mes = $oGet->mes;
}

switch ($oGet->ponto) {
	
	case 'r14':
		
	  $sigla      = 'r14';
	  $sDescricao = 'Salário';
	  break;
	  
  case 'r48':
    
    $sigla      = 'r48';
    $sDescricao = 'Complementar';
    break;
    
  case 'r35':
    
    $sigla      = 'r35';
    $sDescricao = '13o. Salário';
    break;
    
  case 'r20':
    
    $sigla      = 'r20';
    $sDescricao = 'Rescisão';
    break;
    
  case 'r22':
    
    $sigla      = 'r22';
    $sDescricao = 'Adiantamento';
    break;
}

if (!empty($sWhere)) {
	$sWhere = "where {$sWhere}";
}

$head2 = "RESUMO DOS EMPENHOS";
$head4 = "PERIODO : {$mes} / {$ano}";
$head6 = "TIPO    : {$sDescricao}";                                                                                                                                         
                                                                                                                                                                            
$sql  = "select cod_regime as rubric,                                                                                                                                        \n";
$sql .= "       descr_regime as descricao,                                                                                                                                   \n";
$sql .= "       recurso,                                                                                                                                                     \n";
$sql .= "       descr_recurso,                                                                                                                                               \n";
$sql .= "       round(sum(case when pd = 1 then valor when pd = 2 then 0 end),2) as provento,                                                                                \n";
$sql .= "       round(sum(case when pd = 2 then valor when pd = 1 then 0 end),2) as desconto                                                                                 \n";
$sql .= "  from ( select rh73_rubric as rubric,                                                                                                                              \n";
$sql .= "                case                                                                                                                                                \n";
$sql .= "                  when rh78_sequencial is null then 'e'                                                                                                             \n";
$sql .= "                  else case                                                                                                                                         \n";
$sql .= "                    when e21_retencaotiporecgrupo = 3 then 'p'                                                                                                      \n";
$sql .= "                    when e21_retencaotiporecgrupo = 4 then 'd'                                                                                                      \n";
$sql .= "                    when e21_retencaotiporecgrupo = 2 then 'r'                                                                                                      \n";
$sql .= "                    else ''                                                                                                                                         \n";
$sql .= "                  end                                                                                                                                               \n";
$sql .= "                end as tipo,                                                                                                                                        \n";
$sql .= "                rh27_descr   as descricao,                                                                                                                          \n";
$sql .= "                rh72_recurso as recurso,                                                                                                                            \n";
$sql .= "                o15_descr    as descr_recurso,                                                                                                                      \n";
$sql .= "                rh30_codreg  as cod_regime,                                                                                                                         \n";
$sql .= "                rh30_descr   as descr_regime,                                                                                                                       \n";
$sql .= "                rh73_pd      as pd,                                                                                                                                 \n";
$sql .= "                rh73_valor   as valor                                                                                                                               \n";
$sql .= "           from rhempenhofolha                                                                                                                                      \n";
$sql .= "          inner join rhempenhofolharhemprubrica    on rh81_rhempenhofolha        = rhempenhofolha.rh72_sequencial                                                   \n";
$sql .= "          inner join rhempenhofolharubrica         on rh73_sequencial            = rhempenhofolharhemprubrica.rh81_rhempenhofolharubrica                            \n";
$sql .= "          inner join rhrubricas                    on rh27_rubric                = rhempenhofolharubrica.rh73_rubric                                                \n";
$sql .= "                                                  and rh27_instit                = rhempenhofolharubrica.rh73_instit                                                \n";
$sql .= "          left  join rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial                                            \n";
$sql .= "          left  join retencaotiporec               on e21_sequencial             = rhempenhofolharubricaretencao.rh78_retencaotiporec                               \n";
$sql .= "          left  join orctiporec                    on o15_codigo                 = rhempenhofolha.rh72_recurso                                                      \n";
$sql .= "          inner join rhpessoalmov                  on rh02_seqpes                = rh73_seqpes                                                                      \n";
$sql .= "          inner join rhregime                      on rh02_codreg                = rh30_codreg                                                                      \n";
$sql .= "                                                  and rh02_instit                = rh30_instit                                                                      \n";
$sql .= "          where rh72_anousu   = {$ano}                                                                                                                              \n";
$sql .= "            and rh72_mesusu   = {$mes}                                                                                                                              \n";
$sql .= "            and rh72_siglaarq = '{$sigla}'                                                                                                                          \n";
$sql .= "            and rh27_pd       <> 3                                                                                                                                  \n";
                                                                                                                                                                            
if (isset($oGet->complementar)) {                                                                                                                                           
  if ($oGet->complementar != '') {                                                                                                                                          
    $sql .= "        and rh72_seqcompl = {$oGet->complementar}                                                                                                               \n";   
  }                                                                                                                                                                         
  $sql .= "          and rh72_seqcompl <> 0                                                                                                                                  \n";    
}                                                                                                                                                                           
             
$sql .= "          union all                                                                                                                                                 \n";   
$sql .= "         select rh73_rubric as rubric,                                                                                                                              \n";
$sql .= "                case                                                                                                                                                \n";
$sql .= "                  when rh78_sequencial is null then 'Slip'                                                                                                          \n";
$sql .= "                  else case                                                                                                                                         \n";
$sql .= "                    when e21_retencaotiporecgrupo = 3 then 'p'                                                                                                      \n";
$sql .= "                    when e21_retencaotiporecgrupo = 4 then 'd'                                                                                                      \n";
$sql .= "                    when e21_retencaotiporecgrupo = 2 then 'r'                                                                                                      \n";
$sql .= "                    else ''                                                                                                                                         \n";
$sql .= "                  end                                                                                                                                               \n";
$sql .= "                end as tipo,                                                                                                                                        \n";
$sql .= "                rh27_descr   as descricao,                                                                                                                          \n";
$sql .= "                rh79_recurso as recurso,                                                                                                                            \n";
$sql .= "                o15_descr    as descr_recurso,                                                                                                                      \n";
$sql .= "                rh30_codreg  as cod_regime,                                                                                                                         \n";
$sql .= "                rh30_descr   as descr_regime,                                                                                                                       \n";
$sql .= "                rh73_pd      as pd,                                                                                                                                 \n";
$sql .= "                rh73_valor   as valor                                                                                                                               \n";
$sql .= "           from rhslipfolha                                                                                                                                         \n";
$sql .= "          inner join rhslipfolharhemprubrica       on rhslipfolharhemprubrica.rh80_rhslipfolha                 = rhslipfolha.rh79_sequencial                        \n";
$sql .= "          inner join rhempenhofolharubrica         on rhempenhofolharubrica.rh73_sequencial                    = rhslipfolharhemprubrica.rh80_rhempenhofolharubrica \n";
$sql .= "          inner join rhrubricas                    on rhrubricas.rh27_rubric                                   = rhempenhofolharubrica.rh73_rubric                  \n";
$sql .= "                                                  and rhrubricas.rh27_instit                                   = rhempenhofolharubrica.rh73_instit                  \n";
$sql .= "          left  join rhempenhofolharubricaretencao on rhempenhofolharubricaretencao.rh78_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial              \n";
$sql .= "          left  join retencaotiporec               on retencaotiporec.e21_sequencial                           = rhempenhofolharubricaretencao.rh78_retencaotiporec \n";
$sql .= "          left  join orctiporec                    on orctiporec.o15_codigo                                    = rhslipfolha.rh79_recurso                           \n";
$sql .= "          inner join rhpessoalmov                  on rh02_seqpes                                              = rh73_seqpes                                        \n";
$sql .= "          inner join rhregime                      on rh02_codreg                                              = rh30_codreg                                        \n";
$sql .= "                                                  and rh02_instit                                              = rh30_instit                                        \n";
$sql .= "         where rh79_anousu   = {$ano}                                                                                                                               \n";   
$sql .= "           and rh79_mesusu   = {$mes}                                                                                                                               \n";   
$sql .= "           and rh79_siglaarq = '{$sigla}'                                                                                                                           \n";   
$sql .= "           and rh27_pd      <> 3                                                                                                                                    \n";   

if (isset($oGet->complementar) ) {
  if ($oGet->complementar != '') {
    $sql .= "       and rh79_seqcompl = {$oGet->complementar}                                                                                                                \n";           
  }                                                                                                                                                                        
  $sql .= "         and rh79_seqcompl <> 0                                                                                                                                   \n";
}
             
$sql .= "         union all                                                                                                                                                  \n";                                                                                                                                             
$sql .= "        select rh27_rubric,                                                                                                                                         \n"; 
$sql .= "               '',                                                                                                                                                  \n";
$sql .= "               rh27_descr,                                                                                                                                          \n";
$sql .= "               rh25_recurso,                                                                                                                                        \n";
$sql .= "               o15_descr,                                                                                                                                           \n";
$sql .= "               rh30_codreg  as cod_regime,                                                                                                                          \n";
$sql .= "               rh30_descr   as descr_regime,                                                                                                                        \n";
$sql .= "               r14_pd,                                                                                                                                              \n";
$sql .= "               r14_valor                                                                                                                                            \n";   
$sql .= "          from gerfsal                                                                                                                                              \n";
$sql .= "         inner join rhrubricas                    on r14_rubric     = rh27_rubric                                                                                   \n";
$sql .= "                                                 and r14_instit     = rh27_instit                                                                                   \n";
$sql .= "         left  join rhrubretencao                 on rh27_rubric    = rh75_rubric                                                                                   \n";
$sql .= "                                                 and rh27_instit    = rh75_instit                                                                                   \n";
$sql .= "         left  join rhrubelemento                 on rh27_rubric    = rh23_rubric                                                                                   \n";
$sql .= "                                                 and rh27_instit    = rh23_instit                                                                                   \n";
$sql .= "         left  join rhlotavinc                    on r14_lotac::int = rh25_codigo                                                                                   \n";
$sql .= "                                                 and r14_anousu     = rh25_anousu                                                                                   \n";
$sql .= "         left  join orctiporec                    on o15_codigo     = rh25_recurso                                                                                  \n";
$sql .= "         inner join rhpessoalmov                  on rh02_anousu    = r14_anousu                                                                                    \n";
$sql .= "                                                 and rh02_mesusu    = r14_mesusu                                                                                    \n";
$sql .= "                                                 and rh02_regist    = r14_regist                                                                                    \n";
$sql .= "                                                 and rh02_instit    = r14_instit                                                                                    \n";
$sql .= "         inner join rhregime                      on rh02_codreg    = rh30_codreg                                                                                   \n";
$sql .= "                                                 and rh02_instit    = rh30_instit                                                                                   \n";
$sql .= "         where rh27_pd <> 3                                                                                                                                         \n";
$sql .= "           and rh27_instit = {$iInstit}                                                                                                                             \n";
$sql .= "           and rh75_rubric is null                                                                                                                                  \n";
$sql .= "           and rh23_rubric is null                                                                                                                                  \n";
$sql .= "           and r14_anousu = {$ano}                                                                                                                                  \n";
$sql .= "           and r14_mesusu = {$mes} ) as x                                                                                                                           \n";
$sql .= "   group by cod_regime,                                                                                                                                             \n";
$sql .= "            descr_regime,                                                                                                                                           \n";
$sql .= "            recurso,                                                                                                                                                \n";
$sql .= "            descr_recurso                                                                                                                                           \n";
$sql .= "   order by recurso,rubric                                                                                                                                          \n";

$rsSql = db_query($sql);

if(pg_numrows($rsSql) == 0){
  
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
  
}

$oPdf = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$total = 0;
$oPdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$alt = 4;
$empenhos    = 0;
$pagametos   = 0;
$retencoes   = 0;
$devolucoes  = 0;
$outros      = 0;
$prov        = 0;
$desc        = 0;
$liq         = 0;
$t_empenhos  = 0;
$t_pagametos = 0;
$t_retencoes = 0;
$t_devolucoes= 0;
$t_outros    = 0;
$t_prov      = 0;
$t_desc      = 0;
$t_liqu      = 0;
$xsec = '';
$oPdf->setfillcolor(235);
for($x = 0; $x < pg_numrows($result);$x++){
  
   db_fieldsmemory($result,$x);
   
   if($xsec != $recurso){
     $troca = 1;
     $xsec = $recurso;
     
     if($x != 0 ){
       $oPdf->setfont('arial','b',8);
       $oPdf->ln(3);
       $oPdf->cell(85,6,' ',"T",0,"L",0);
       $oPdf->cell(30,6,db_formatar($prov,'f'),"T",0,"R",0);
       $oPdf->cell(30,6,db_formatar($desc,'f'),"T",0,"R",0);
       $oPdf->cell(30,6,db_formatar($prov - $desc,'f'),"T",1,"R",0);
     
       $total = 0;
       $empenhos   = 0;
       $pagamentos = 0;
       $retencoes  = 0;
       $devolucoes = 0;
       $outros     = 0;
       $prov       = 0;
       $desc       = 0;
       $liqu       = 0;
     }
     
   }
   
   if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){
     
      $oPdf->addpage();
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
      $oPdf->cell(70,$alt,'DESCRICAO',1,0,"C",1);
      $oPdf->cell(30,$alt,'PROVENTO',1,0,"C",1);
      $oPdf->cell(30,$alt,'DESCONTO',1,0,"C",1);
      $oPdf->cell(30,$alt,'LIQUIDO',1,1,"C",1);
      $oPdf->ln(3);
      $oPdf->cell(0,$alt,$recurso.' - '.$descr_recurso,0,1,"L",0);
      $troca = 0;
      $pre = 1;
      
   }
   
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
     
   $oPdf->setfont('arial','',7);
   $oPdf->cell(15,$alt,$rubric,0,0,"C",$pre);
   $oPdf->cell(70,$alt,$descricao,0,0,"L",$pre);
   $oPdf->cell(30,$alt,db_formatar($provento,'f'),0,0,"R",$pre);
   $oPdf->cell(30,$alt,db_formatar($desconto,'f'),0,0,"R",$pre);
   $oPdf->cell(30,$alt,db_formatar($provento - $desconto,'f'),0,1,"R",$pre);
 
   $prov       += $provento;
   $desc       += $desconto;
   $t_prov     += $provento;
   $t_desc     += $desconto;
}


$oPdf->setfont('arial','b',8);
$oPdf->ln(3);
$oPdf->cell(85,6,' ',"T",0,"L",0);
$oPdf->cell(30,6,db_formatar($prov,'f'),"T",0,"R",0);
$oPdf->cell(30,6,db_formatar($desc,'f'),"T",0,"R",0);
$oPdf->cell(30,6,db_formatar($prov - $desc,'f'),"T",1,"R",0);

$oPdf->ln(3);
$oPdf->cell(85,6,'TOTAL GERAL ',"T",0,"L",0);
$oPdf->cell(30,6,db_formatar($t_prov,'f'),"T",0,"R",0);
$oPdf->cell(30,6,db_formatar($t_desc,'f'),"T",0,"R",0);
$oPdf->cell(30,6,db_formatar($t_prov - $t_desc,'f'),"T",1,"R",0);

$oPdf->Output();
?>
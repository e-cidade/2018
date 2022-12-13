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

include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
include("libs/db_sql.php");
include("classes/db_basesr_classe.php");
$clbasesr = new cl_basesr;

$sql_in = $clbasesr->sql_query_file($ano,$mes,"B995",null,db_getsession("DB_instit"),"r09_rubric");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sql_inst = "select * from db_config where codigo = ".db_getsession("DB_instit");
$result_inst = pg_exec($sql_inst);

db_fieldsmemory($result_inst,0);

$recurso_descr = '';

//// nome das previdencias
$sql_nome = "SELECT distinct cast(r33_codtab as integer)-2 as r33_codtab,
                    r33_nome, 
                    r33_ppatro
               FROM inssirf 
              WHERE r33_anousu = $ano 
                and r33_mesusu = $mes
	            and r33_codtab in ($previdencia)
	            and r33_codtab > 2
	            and r33_instit = ".db_getsession("DB_instit");
$res_nome = pg_query($sql_nome);
$virg_nome = '';
$descr_nome = '';

for($inome=0;$inome<pg_numrows($res_nome);$inome++){
 db_fieldsmemory($res_nome,$inome);
 $descr_nome .= $virg_nome.$r33_nome;
 $virg_nome   = ', '; 
}


$xbases    = " ('R992') ";
$xdeducao  = " (".$sql_in.") ";
$xrubricas = " ('R901','R902','R903','R904','R905','R906','R907','R908','R909','R910','R911','R912') ";
$prev      = " and rh02_tbprev in ($previdencia) ";
$devolucao = " ('')";


$soma     = 0;
$base     = 0;
$ded      = 0;
$dev      = 0;
$desco    = 0;
$patronal = 0;

for($inome=0;$inome<pg_numrows($res_nome);$inome++){
    db_fieldsmemory($res_nome,$inome);

    if($recurso == 'g'){

      if($tipo == 's'){
  
        $sql = "
        select count(r01_regist) as soma1 ,
               sum(base)       as base1,
               sum(ded)        as ded1,
               sum(dev)        as dev1,
               sum(desco)      as desco1,
               patro           as patronal1
        from 
        (
        select rh02_anousu,
               rh02_mesusu,
               rh01_regist as r01_regist,
               z01_nome,
               rh02_lota,
               rh03_padrao,
               sum(case when r14_rubric in ".$xrubricas." then r14_valor else 0 end) as desco,
               sum(case when r14_rubric in ".$xdeducao." then r14_valor else 0 end) as ded ,
               sum(case when r14_rubric in ".$devolucao." then r14_valor else 0 end) as dev ,
               sum(case when r14_rubric in ".$xbases."    then r14_valor else 0 end) as base
        from gerfsal 
             inner join rhpessoalmov on rh02_anousu = r14_anousu 
                                    and rh02_mesusu = r14_mesusu 
                                    and rh02_regist = r14_regist
                                    and rh02_instit = r14_instit
             inner join rhpessoal    on rh01_regist = r14_regist 
             left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
             inner join cgm on rh01_numcgm = z01_numcgm  
        where r14_anousu = $ano 
          and r14_mesusu = $mes
          and r14_instit = ".db_getsession('DB_instit')."
          and rh02_tbprev = $r33_codtab
          and ( r14_rubric in ".$xrubricas." 
             or r14_rubric in ".$xdeducao." 
             or r14_rubric in ".$devolucao." 
             or r14_rubric in ".$xbases.")
          group by 
               rh02_anousu,
               rh02_mesusu,
               rh01_regist,
               z01_nome,
               rh02_lota,
               rh03_padrao
      
        union all
      
        select rh02_anousu,
               rh02_mesusu,
               rh01_regist,
               z01_nome,
               rh02_lota,
               rh03_padrao,
               sum(case when r48_rubric in ".$xrubricas." then r48_valor else 0 end) as desco,
               sum(case when r48_rubric in ".$xdeducao."  then r48_valor else 0 end) as ded ,
               sum(case when r48_rubric in ".$devolucao." then r48_valor else 0 end) as dev ,
               sum(case when r48_rubric in ".$xbases."    then r48_valor else 0 end) as base
        from gerfcom
             inner join rhpessoalmov on rh02_anousu = r48_anousu 
                                    and rh02_mesusu = r48_mesusu 
                                    and rh02_regist = r48_regist
                                    and rh02_instit = r48_instit
             inner join rhpessoal    on rh01_regist = r48_regist 
             left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
             inner join cgm on rh01_numcgm = z01_numcgm  
        where r48_anousu = $ano
          and r48_mesusu = $mes
          and r48_instit = ".db_getsession('DB_instit')."
          and rh02_tbprev = $r33_codtab
          and ( r48_rubric in ".$xrubricas." 
             or r48_rubric in ".$xdeducao." 
             or r48_rubric in ".$devolucao." 
             or r48_rubric in ".$xbases." )
          group by 
               rh02_anousu,
               rh02_mesusu,
               rh01_regist,
               z01_nome,
               rh02_lota,
               rh03_padrao
                         
        union all
      
        select rh02_anousu,
               rh02_mesusu,
               rh01_regist,
               z01_nome,
               rh02_lota,
               rh03_padrao,
               sum(case when r20_rubric in ".$xrubricas." then r20_valor else 0 end) as desco,
               sum(case when r20_rubric in ".$xdeducao."  then r20_valor else 0 end) as ded ,
               sum(case when r20_rubric in ".$devolucao." then r20_valor else 0 end) as dev ,
               sum(case when r20_rubric in ".$xbases."    then r20_valor else 0 end) as base
        from gerfres
             inner join rhpessoalmov on rh02_anousu = r20_anousu 
                                    and rh02_mesusu = r20_mesusu 
                                    and rh02_regist = r20_regist
                                    and rh02_instit = r20_instit
             inner join rhpessoal    on rh01_regist = r20_regist 
             left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
             inner join cgm on rh01_numcgm = z01_numcgm  
        where r20_anousu = $ano
          and r20_mesusu = $mes
          and r20_instit = ".db_getsession('DB_instit')."
          and rh02_tbprev = $r33_codtab
          and ( r20_rubric in ".$xrubricas." 
             or r20_rubric in ".$xdeducao." 
             or r20_rubric in ".$devolucao." 
             or r20_rubric in ".$xbases.")
          group by 
               rh02_anousu,
               rh02_mesusu,
               rh01_regist,
               z01_nome,
               rh02_lota,
               rh03_padrao
        ) as xx 
        left  join
        (
        select rh72_anousu, rh72_mesusu, rh72_tabprev, 
               sum(rh73_valor) as patro 
        from rhempenhofolha 
             inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha        = rh72_sequencial 
             inner join rhempenhofolharubrica      on rh81_rhempenhofolharubrica = rh73_sequencial
       where rh72_siglaarq in('r20', 'r14', 'r48')
       group by rh72_anousu, rh72_mesusu, rh72_tabprev
        
        ) as xyy on xyy.rh72_anousu  = rh02_anousu 
                and xyy.rh72_mesusu  = rh02_mesusu
                and xyy.rh72_tabprev = $r33_codtab
        group by patro
               ";
      }else{
        $sql = "
        select count(soma) as soma1,
               round(sum(base),2)       as base1,
               round(sum(ded),2)        as ded1,
               round(sum(dev),2)        as dev1,
               round(sum(desco),2)      as desco1,
               round(sum(base)/100*$r33_ppatro,2) as patronal1
        from 
        (
        select rh01_regist as soma ,
               sum(base)       as base,
               sum(ded)        as ded,
               sum(dev)        as dev,
               sum(desco)      as desco
        from 
        (
        select rh01_regist,
               z01_nome,
               rh02_lota,
               rh03_padrao,
               case when r35_rubric in ".$xrubricas." then r35_valor else 0 end as desco,
               case when r35_rubric in ".$xdeducao."  then r35_valor else 0 end as ded ,
               case when r35_rubric in ".$devolucao." then r35_valor else 0 end as dev ,
               case when r35_rubric in ".$xbases."    then r35_valor else 0 end as base
        from gerfs13 
             inner join rhpessoalmov on rh02_anousu = r35_anousu 
                                    and rh02_mesusu = r35_mesusu 
                                    and rh02_regist = r35_regist
                                    and rh02_instit = r35_instit
             inner join rhpessoal    on rh01_regist = r35_regist 
             left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
             inner join cgm on rh01_numcgm = z01_numcgm  
        where r35_anousu = $ano 
          and r35_mesusu = $mes
          and r35_instit = ".db_getsession('DB_instit')."
          and rh02_tbprev = $r33_codtab
          and ( r35_rubric in ".$xrubricas." 
             or r35_rubric in ".$xdeducao." 
             or r35_rubric in ".$devolucao." 
             or r35_rubric in ".$xbases.")
      
        ) as xx group by rh01_regist
        ) as xxx
                         
               ";
      }
    } else {
      if($tipo == 's') {
  
        $sql = "
        select recurso,
               o15_descr as recurso_descr,
               count(soma) as soma1,
               round(sum(base),2)       as base1,
               round(sum(ded),2)        as ded1,
               round(sum(dev),2)        as dev1,
               round(sum(desco),2)      as desco_calculo,
              round(coalesce(desconto,0)+sum(desc_aut),2)        as desco1, 
               sum(desc_aut) as desc_aut,
               round(coalesce(patro,0) + sum(base_aut),2) as patronal1
        from 
        (
        select r01_regist as soma ,
               recurso,
               round(sum(base),2) as base,
               round(sum(base_aut)/100*$r33_ppatro,2) as base_aut,
               sum(ded)         as ded,
               sum(dev)         as dev,
               sum(desco)       as desco,
               round(sum(desc_aut),2) as desc_aut   
        from 
        (
        select 
               rh01_regist as r01_regist,
               rh25_recurso as recurso,
               z01_nome,
               rh02_lota,
               rh03_padrao,
               sum(case when r14_rubric in ".$xrubricas." then r14_valor else 0 end) as desco,
               sum(case when r14_rubric in ".$xdeducao." then r14_valor else 0 end) as ded ,
               sum(case when r14_rubric in ".$devolucao." then r14_valor else 0 end) as dev ,
               sum(case when r14_rubric in ".$xbases."    then r14_valor else 0 end) as base,
               0 as base_aut,
               0 as desc_aut
        from gerfsal 
             inner join rhpessoalmov on rh02_anousu = r14_anousu 
                                    and rh02_mesusu = r14_mesusu 
                                    and rh02_regist = r14_regist
                                    and rh02_instit = r14_instit
             inner join rhpessoal    on rh01_regist = r14_regist 
             left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
             left join rhlotavinc on rh25_codigo = rh02_lota
                                    and rh25_anousu = r14_anousu
             inner join cgm on rh01_numcgm = z01_numcgm  
        where r14_anousu = $ano 
          and r14_mesusu = $mes
          and r14_instit = ".db_getsession('DB_instit')."
          and rh02_tbprev = $r33_codtab
          and ( r14_rubric in ".$xrubricas." 
             or r14_rubric in ".$xdeducao." 
             or r14_rubric in ".$devolucao." 
             or r14_rubric in ".$xbases.")
          group by 
               rh01_regist,
               rh25_recurso,
               z01_nome,
               rh02_lota,
               rh03_padrao
      
        union all
      
        select rh01_regist,
               rh25_recurso,
               z01_nome,
               rh02_lota,
               rh03_padrao,
               sum(case when r48_rubric in ".$xrubricas." then r48_valor else 0 end) as desco,
               sum(case when r48_rubric in ".$xdeducao."  then r48_valor else 0 end) as ded ,
               sum(case when r48_rubric in ".$devolucao." then r48_valor else 0 end) as dev ,
               sum(case when r48_rubric in ".$xbases."    then r48_valor else 0 end) as base,
               0 as base_aut,
               0 as desc_aut
        from gerfcom
             inner join rhpessoalmov on rh02_anousu = r48_anousu 
                                    and rh02_mesusu = r48_mesusu 
                                    and rh02_regist = r48_regist
                                    and rh02_instit = r48_instit
             inner join rhpessoal    on rh01_regist = r48_regist 
             left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
             inner join cgm on rh01_numcgm = z01_numcgm  
             left join rhlotavinc    on rh25_codigo = rh02_lota
                                    and rh25_anousu = r48_anousu
        where r48_anousu = $ano
          and r48_mesusu = $mes
          and r48_instit = ".db_getsession('DB_instit')."
          and rh02_tbprev = $r33_codtab
          and ( r48_rubric in ".$xrubricas." 
             or r48_rubric in ".$xdeducao." 
             or r48_rubric in ".$devolucao." 
             or r48_rubric in ".$xbases." )
          group by 
               rh01_regist,
               rh25_recurso,
               z01_nome,
               rh02_lota,
               rh03_padrao
                         
        union all
      
        select rh01_regist,
               rh25_recurso,
               z01_nome,
               rh02_lota,
               rh03_padrao,
               sum(case when r20_rubric in ".$xrubricas." then r20_valor else 0 end) as desco,
               sum(case when r20_rubric in ".$xdeducao."  then r20_valor else 0 end) as ded ,
               sum(case when r20_rubric in ".$devolucao." then r20_valor else 0 end) as dev ,
               sum(case when r20_rubric in ".$xbases."    then r20_valor else 0 end) as base,
               0 as base_aut,
               0 as desc_aut
        from gerfres
             inner join rhpessoalmov on rh02_anousu = r20_anousu 
                                    and rh02_mesusu = r20_mesusu 
                                    and rh02_regist = r20_regist
                                    and rh02_instit = r20_instit
             inner join rhpessoal    on rh01_regist = r20_regist 
             left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
             inner join cgm on rh01_numcgm = z01_numcgm  
             left join rhlotavinc    on rh25_codigo = rh02_lota
                                    and rh25_anousu = r20_anousu
        where r20_anousu = $ano
          and r20_mesusu = $mes
          and r20_instit = ".db_getsession('DB_instit')."
          and rh02_tbprev = $r33_codtab
          and ( r20_rubric in ".$xrubricas." 
             or r20_rubric in ".$xdeducao." 
             or r20_rubric in ".$devolucao." 
             or r20_rubric in ".$xbases.")
          group by 
               rh01_regist,
               rh25_recurso,
               z01_nome,
               rh02_lota,
               rh03_padrao
        union all
        select 0,
               recurso,
               '',
               0,
               '',
               desconto,
               0,
               0,
               base,
               base,
               desconto
        from 
        w_rhrpa
        where anousu = $ano
          and mesusu = $mes
          and trim(pis) <> ''
        ) as xx group by r01_regist, recurso
        ) as xxx left join orctiporec on recurso = o15_codigo
        left join 
        (select rh72_recurso, round(sum(desconto),2) as desconto , round(sum(patro),2) as patro from 
        (
        select rh72_recurso, 
               0 as desconto, 
               sum(rh73_valor) as patro 
        from rhempenhofolha 
             inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha        = rh72_sequencial 
             inner join rhempenhofolharubrica      on rh81_rhempenhofolharubrica = rh73_sequencial 
        where rh72_anousu = $ano
          and rh72_mesusu = $mes
          and rh72_tabprev = $r33_codtab
          and (rh72_siglaarq in('r20', 'r14', 'r48'))
        group by rh72_recurso

        union all

        select recurso, 
               round(sum(rh73_valor),2) , 
               0 
        from (SELECT case when rh72_recurso is null then rh79_recurso else rh72_recurso end as recurso, 
                     rh73_valor,
                     0
                     from rhempenhofolharubrica 
                          inner join rhrubricas on rh73_rubric = rh27_rubric 
                                               and rh73_instit = rh27_instit 
                          left join rhempenhofolharhemprubrica on rh81_rhempenhofolharubrica = rh73_sequencial 
                          left join rhempenhofolha    on rh81_rhempenhofolha = rh72_sequencial 
                          left join rhslipfolharhemprubrica on rh80_rhempenhofolharubrica = rh73_sequencial 
                          left join rhslipfolha on rh80_rhslipfolha = rh79_sequencial 
                     where (rh73_tiporubrica = 2 and (rh72_tipoempenho = 1 or rh79_tipoempenho = 1 )) 
                       and ((rh72_anousu = $ano and rh72_mesusu = $mes) 
                             or 
                            (rh79_anousu = $ano and rh79_mesusu = $mes) 
                           ) 
                       and (rh72_siglaarq in('r20', 'r14', 'r48') or rh79_siglaarq in('r20', 'r14', 'r48'))    
                       and rh73_rubric in ".$xrubricas."
             ) as xyx group by recurso ) as xyxyxy group by rh72_recurso


        ) as xyy on rh72_recurso = recurso
        group by recurso, o15_descr, patro, desconto
                         
               ";
        } else {
          
        $sql = "
        select recurso,
               o15_descr as recurso_descr,
               count(soma) as soma1,
               round(sum(base),2)       as base1,
               round(sum(ded),2)        as ded1,
               round(sum(dev),2)        as dev1,
               round(sum(desco),2)      as desco_calculo,
              round(coalesce(desconto,0)+sum(desc_aut),2)        as desco1, 
               sum(desc_aut) as desc_aut,
               round(coalesce(patro,0) + sum(base_aut),2) as patronal1
        from 
        (
        select r01_regist as soma ,
               recurso,
               round(sum(base),2) as base,
               round(sum(base_aut)/100*$r33_ppatro,2) as base_aut,
               sum(ded)         as ded,
               sum(dev)         as dev,
               sum(desco)       as desco,
               round(sum(desc_aut),2) as desc_aut   
        from 
        (
        select 
               rh01_regist as r01_regist,
               rh25_recurso as recurso,
               z01_nome,
               rh02_lota,
               rh03_padrao,
               sum(case when r35_rubric in ".$xrubricas." then r35_valor else 0 end) as desco,
               sum(case when r35_rubric in ".$xdeducao." then r35_valor else 0 end) as ded ,
               sum(case when r35_rubric in ".$devolucao." then r35_valor else 0 end) as dev ,
               sum(case when r35_rubric in ".$xbases."    then r35_valor else 0 end) as base,
               0 as base_aut,
               0 as desc_aut
        from gerfs13 
             inner join rhpessoalmov on rh02_anousu = r35_anousu 
                                    and rh02_mesusu = r35_mesusu 
                                    and rh02_regist = r35_regist
                                    and rh02_instit = r35_instit
             inner join rhpessoal    on rh01_regist = r35_regist 
             left join rhpespadrao   on rh03_seqpes    = rhpessoalmov.rh02_seqpes
             left join rhlotavinc on rh25_codigo = rh02_lota
                                    and rh25_anousu = r35_anousu
             inner join cgm on rh01_numcgm = z01_numcgm  
        where r35_anousu = $ano 
          and r35_mesusu = $mes
          and r35_instit = ".db_getsession('DB_instit')."
          and rh02_tbprev = $r33_codtab
          and ( r35_rubric in ".$xrubricas." 
             or r35_rubric in ".$xdeducao." 
             or r35_rubric in ".$devolucao." 
             or r35_rubric in ".$xbases.")
          group by 
               rh01_regist,
               rh25_recurso,
               z01_nome,
               rh02_lota,
               rh03_padrao
      
        ) as xx group by r01_regist, recurso
        ) as xxx left join orctiporec on recurso = o15_codigo
        left join 
        (select rh72_recurso, round(sum(desconto),2) as desconto , round(sum(patro),2) as patro from 
        (
        select rh72_recurso, 
               0 as desconto, 
               sum(rh73_valor) as patro 
        from rhempenhofolha 
             inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha        = rh72_sequencial 
             inner join rhempenhofolharubrica      on rh81_rhempenhofolharubrica = rh73_sequencial 
        where rh72_anousu = $ano
          and rh72_mesusu = $mes
          and rh72_tabprev = $r33_codtab
          and rh72_siglaarq = 'r35'
        group by rh72_recurso

        union all

        select recurso, 
               round(sum(rh73_valor),2) , 
               0 
        from (SELECT case when rh72_recurso is null then rh79_recurso else rh72_recurso end as recurso, 
                     rh73_valor,
                     0
                     from rhempenhofolharubrica 
                          inner join rhrubricas on rh73_rubric = rh27_rubric 
                                               and rh73_instit = rh27_instit 
                          left join rhempenhofolharhemprubrica on rh81_rhempenhofolharubrica = rh73_sequencial 
                          left join rhempenhofolha    on rh81_rhempenhofolha = rh72_sequencial 
                          left join rhslipfolharhemprubrica on rh80_rhempenhofolharubrica = rh73_sequencial 
                          left join rhslipfolha on rh80_rhslipfolha = rh79_sequencial 
                     where (rh73_tiporubrica = 2 and (rh72_tipoempenho = 1 or rh79_tipoempenho = 1 )) 
                       and ((rh72_anousu = $ano and rh72_mesusu = $mes) 
                             or 
                            (rh79_anousu = $ano and rh79_mesusu = $mes) 
                           ) 
                       and rh73_rubric in ".$xrubricas."
                       and (rh72_siglaarq = 'r35' or rh79_siglaarq = 'r35')
             ) as xyx group by recurso ) as xyxyxy group by rh72_recurso


        ) as xyy on rh72_recurso = recurso
        group by recurso, o15_descr, patro, desconto
                         
                         
               ";
      }
    }
    $result = pg_exec($sql);
    //echo $sql;
    //db_criatabela($result);exit;
    $xxnum = pg_numrows($result);
    if ($xxnum == 0){
      db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

    }
}
//echo $sql ; exit;

global $pdf;
$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,25);

//echo "logo --> $logo";exit;
$pdf1->logo             = 'logoprevidencia.jpg';
$pdf1->prefeitura       = $nomeinst;
$pdf1->enderpref        = $ender.', '.$numero;
$pdf1->cgcpref          = $cgc;
$pdf1->cep              = $cep;
$pdf1->ufpref           = $uf;
$pdf1->cgcpref          = $cgc;
$pdf1->municpref        = $munic;
$pdf1->telefpref        = $telef;
$pdf1->emailpref        = $email;
$pdf1->ano              = $ano;
$pdf1->mes              = $mes;
$total1                 = 0;
$total2                 = 0;
$total3                 = 0;
$total4                 = 0;
$total5                 = 0;
for($iguia=0;$iguia<pg_numrows($result);$iguia++){
//$pdf1->modelo     	= 25;
  db_fieldsmemory($result,$iguia);
  $soma     = $soma1;
  $base     = $base1;
  $ded      = $ded1;
  $dev      = $dev1;
  $desco    = $desco1;
  $patronal = $patronal1;

  $pdf1->func             = $soma;
  $pdf1->base             = $base;
  $pdf1->deducao          = $ded;
  $pdf1->desconto         = $desco - $dev;
  $pdf1->patronal         = $patronal;
  $pdf1->cod_pagto        = $cod_pagto;
  $total1                 += $soma;
  $total2                 += $base;
  $total3                 += $ded;
  $total4                 += $pdf1->desconto;
  $total5                 += $pdf1->patronal;
  $pdf1->terceiros        = 0;
  $pdf1->atu_monetaria    = 0;
  $pdf1->juros            = 0;
  $pdf1->previdencia      = strtoupper($tipo == 's'?$descr_nome.'-'.$recurso_descr:$descr_nome.'-'.$recurso_descr.' S/ 13º');
  $pdf1->imprime();
}  
$pdf1->objpdf->output();
?>
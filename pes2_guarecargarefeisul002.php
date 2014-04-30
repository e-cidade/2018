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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//$ano = 2006;
//$mes = 11;


$head2 = "PERODO : ".$mes." / ".$ano;
$head3 = "RECARGA REFEISUL";

$lotacao_filtro = "select r02_codigo from padroes where r02_anousu = $ano and r02_instit = 1 and r02_mesusu = $mes and r02_descr ilike '*%'";

$sql = "
      select s.r14_regist ,
             z01_nome,
             z01_cgccpf,
             rh03_padrao,
             case when (trim(rh03_padrao) in ( $lotacao_filtro)
                    or rh30_regime = 2) and g.r14_regist is null 
                  then $maior
                  else case when (trim(rh03_padrao) in ( $lotacao_filtro )
                              or rh30_regime = 2) and g.r14_regist is not null
                            then ($maior + 10) - $menor
                            else 0
                       end
             end as valor1,
             case when trim(rh03_padrao) not in ( $lotacao_filtro )
                        and rh30_regime <> 2 and g.r14_regist is null 
                  then $menor
                  else case when trim(rh03_padrao) not in ( $lotacao_filtro )
                             and rh30_regime <> 2 and g.r14_regist is not null
                       then 0
                       else 0
                  end
             end as valor2
      from gerfsal s
           inner join rhpessoal    on rh01_regist  = s.r14_regist
           inner join rhpessoalmov on rh02_anousu  = s.r14_anousu
                                  and rh02_mesusu  = s.r14_mesusu
                                  and rh02_regist  = s.r14_regist
                                  and rh02_instit  = s.r14_instit
           inner join rhregime     on rh30_codreg  = rh02_codreg
                                  and rh30_instit  = rh02_instit
           left  join rhpespadrao  on rh02_seqpes  = rh03_seqpes
           inner join cgm          on rh01_numcgm  = z01_numcgm
           left  join (select r14_regist, 
                              sum(r14_valor) 
                       from gerfsal
                       where r14_anousu = $ano
                         and r14_mesusu = $mes
                         and r14_rubric in ('1228','1229')
                         and r14_instit = ".db_getsession('DB_instit')."
                       group by r14_regist
                      ) as g on g.r14_regist = s.r14_regist
      where s.r14_anousu = $ano
        and s.r14_mesusu = $mes
        and s.r14_instit = ".db_getsession("DB_instit")."
        and s.r14_rubric = '1249'
order by z01_nome
";
//echo $sql;exit;
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=No nenhum registro encontrado no perodo de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$tot_val = 0;
$alt = 4;
$xsec = 0;
$pre = 1;
$pdf->setfillcolor(235);
if($totais == 't'){
  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        $pdf->cell(75,$alt,'NOME',1,0,"C",1);
        $pdf->cell(35,$alt,'CPF',1,0,"C",1);
        $pdf->cell(15,$alt,'PADRAO',1,0,"C",1);
        $pdf->cell(25,$alt,'VALOR',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }  
     $pdf->setfont('arial','',7);
     $pdf->cell(75,$alt,$z01_nome,0,0,"L",$pre);
     $pdf->cell(35,$alt,$z01_cgccpf,0,0,"C",$pre);
     $pdf->cell(15,$alt,$rh03_padrao,0,0,"C",$pre);
     $pdf->cell(25,$alt,db_formatar($valor1+$valor2,'f'),0,1,"R",$pre);
     $total++;
     $tot_val += $valor1+$valor2;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(125,$alt,'TOTAL   '.$total.'   FUNCIONRIOS',"T",0,"L",0);
  $pdf->cell(25,$alt,db_formatar($tot_val,'f'),"T",1,"R",0);
}else{
  $pdf->addpage();
}

$sql1 = "
select case when rh26_orgao = 9
            then 'SAUDE'
            else
              case when rh26_orgao = 7
                then 'EDUCACAO'
                else 'DEMAIS'
              end
       end as sec,
       sum(valor1+valor2) as valor,
       count(valor1) as func
from
(
      select s.r14_regist ,
             z01_nome,
             rh02_lota,
             rh03_padrao,
             case when (trim(rh03_padrao) in ( $lotacao_filtro )
                    or rh30_regime = 2) and g.r14_regist is null 
                  then $maior
                  else case when (trim(rh03_padrao) in ( $lotacao_filtro )
                              or rh30_regime = 2) and g.r14_regist is not null
                            then ($maior + 10) - $menor
                            else 0
                       end
             end as valor1,
             case when trim(rh03_padrao) not in ( $lotacao_filtro )
                        and rh30_regime <> 2 and g.r14_regist is null 
                  then $menor
                  else case when trim(rh03_padrao) not in ( $lotacao_filtro )
                             and rh30_regime <> 2 and g.r14_regist is not null
                       then 0
                       else 0
                  end
             end as valor2
      from gerfsal s
           inner join rhpessoal    on rh01_regist = s.r14_regist
           inner join rhpessoalmov on rh02_anousu = s.r14_anousu
                                  and rh02_mesusu = s.r14_mesusu
                                  and rh02_regist = s.r14_regist
                                  and rh02_instit = s.r14_instit
           left  join rhpespadrao  on rh02_seqpes  = rh03_seqpes
           inner join rhregime     on rh30_codreg  = rh02_codreg
                                  and rh30_instit  = rh02_instit
           inner join cgm          on rh01_numcgm = z01_numcgm
           left  join (select r14_regist, 
                              sum(r14_valor) 
                       from gerfsal
                       where r14_anousu = $ano
                         and r14_mesusu = $mes
                         and r14_rubric in ('1228','1229')
                         and r14_instit = ".db_getsession('DB_instit')."
                       group by r14_regist
                      ) as g on g.r14_regist = s.r14_regist
      where s.r14_anousu = $ano
        and s.r14_mesusu = $mes
        and s.r14_instit = ".db_getsession("DB_instit")."
        and s.r14_rubric = '1249'
        ) as x
left join rhlotaexe     on rh26_codigo = rh02_lota
                       and rh26_anousu = $ano
group by sec
"; 

//echo $sql1;exit;

$pdf->ln(4);
$result1 = pg_exec($sql1);
$pdf->cell(35,10,'TOTALIZAO',0,1,"L",0);
for($xx = 0; $xx < pg_numrows($result1);$xx++){
   db_fieldsmemory($result1,$xx);
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->cell(35,$alt,$sec,0,0,"L",$pre);
   $pdf->cell(25,$alt,$func,0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($valor,'f'),0,1,"R",$pre);
   
}

$sql2 = "
select r70_estrut, r70_descr,
       sum(valor1+valor2) as valor,
       count(valor1) as func
from
(
      select s.r14_regist ,
             z01_nome,
             rh02_lota,
             rh02_instit,
             rh03_padrao,
             case when (trim(rh03_padrao) in ( $lotacao_filtro )
                    or rh30_regime = 2) and g.r14_regist is null 
                  then $maior
                  else case when (trim(rh03_padrao) in ( $lotacao_filtro )
                              or rh30_regime = 2) and g.r14_regist is not null
                            then ($maior + 10) - $menor
                            else 0
                       end
             end as valor1,
             case when trim(rh03_padrao) not in ( $lotacao_filtro )
                        and rh30_regime <> 2 and g.r14_regist is null 
                  then $menor
                  else case when trim(rh03_padrao) not in ( $lotacao_filtro )
                             and rh30_regime <> 2 and g.r14_regist is not null
                       then 0
                       else 0
                  end
             end as valor2
      from gerfsal s
           inner join rhpessoal    on rh01_regist = s.r14_regist
           inner join rhpessoalmov on rh02_anousu = s.r14_anousu
                                  and rh02_mesusu = s.r14_mesusu
                                  and rh02_regist = s.r14_regist
                                  and rh02_instit = s.r14_instit
           left  join rhpespadrao  on rh02_seqpes  = rh03_seqpes
           inner join rhregime     on rh30_codreg  = rh02_codreg
                                  and rh30_instit  = rh02_instit
           inner join cgm          on rh01_numcgm = z01_numcgm
           left  join (select r14_regist, 
                              sum(r14_valor) 
                       from gerfsal
                       where r14_anousu = $ano
                         and r14_mesusu = $mes
                         and r14_rubric in ('1228','1229')
                         and r14_instit = ".db_getsession('DB_instit')."
                       group by r14_regist
                      ) as g on g.r14_regist = s.r14_regist
      where s.r14_anousu = $ano
        and s.r14_mesusu = $mes
        and s.r14_instit = ".db_getsession("DB_instit")."
        and s.r14_rubric = '1249'
        ) as x
inner join rhlota       on r70_codigo  = rh02_lota
                       and r70_instit  = rh02_instit
left join rhlotaexe     on rh26_codigo = rh02_lota
                       and rh26_anousu = $ano
where rh26_orgao = 7
group by r70_estrut, r70_descr
order by r70_estrut, r70_descr
"; 
//echo $sql2;exit;
$total_func_edu = 0;
$total_val_edu  = 0;
$result2 = pg_exec($sql2);
$pdf->ln(4);
$pdf->cell(35,10,'TOTAL DA EDUCACAO',0,1,"L",0);
$pdf->setfont('arial','',8);
for($xx = 0; $xx < pg_numrows($result2);$xx++){
   db_fieldsmemory($result2,$xx);
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->cell(25,$alt,$r70_estrut,0,0,"L",$pre);
   $pdf->cell(80,$alt,$r70_descr,0,0,"L",$pre);
   $pdf->cell(25,$alt,$func,0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($valor,'f'),0,1,"R",$pre);
   $total_func_edu += $func;
   $total_val_edu  += $valor;
}
$pdf->setfont('arial','b',8);
$pdf->cell(105,$alt,'TOTAL','T',0,"C",$pre);
$pdf->cell(25,$alt,$total_func_edu,'T',0,"R",$pre);
$pdf->cell(25,$alt,db_formatar($total_val_edu,'f'),'T',1,"R",$pre);


$pdf->Output();
?>
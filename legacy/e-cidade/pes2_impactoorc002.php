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
include("classes/db_selecao_classe.php");
$clselecao = new cl_selecao;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "RELAÇÃO DE CARGOS / PADRÃO";
$head4 = "MÊS  : ".$ano." / ".db_mes($mes,1) ;

$where = "";
if(isset($selec) && $selec != ''){
  $where = " and rh02_codreg in (".$selec.") ";
}
$head6 = "";
$head5 = "";
if(trim($selecao) != ""){
  $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($selecao," r44_descr, r44_where ",db_getsession("DB_instit")));
  if($clselecao->numrows > 0){
    db_fieldsmemory($result_selecao, 0);
    $where .= " and ".$r44_where;
    $head5 = "SELEÇÃO: ".$selecao." - ".$r44_descr;
  }
}

 if(isset($cai) && trim($cai) != "" && isset($caf) && trim($caf) != ""){
    // Se for por intervalos e vier lotação inicial e final
    $where .= " and rh37_funcao between '".$cai."' and '".$caf."' ";
    $head6.= " CARGO DE ".$cai." A ".$caf;
  }else if(isset($cai) && trim($cai) != ""){
    // Se for por intervalos e vier somente lotação inicial
    $where .= " and rh37_funcao >= '".$cai."' ";
    $head6.= " CARGO SUPERIORES A ".$cai;
  }else if(isset($caf) && trim($caf) != ""){
    // Se for por intervalos e vier somente lotação final
    $where .= " and rh37_funcao <= '".$caf."' ";
    $head6.= " CARGO INFERIORES A ".$caf;
  }else if(isset($fca) && trim($fca) != ""){
    // Se for por selecionados
    $where .= " and rh37_funcao in ('".str_replace(",","','",$fca)."') ";
    $head6.= " CARGO SELECIONADAS";
  }
if($padrao != ""){
   $where .= " and r02_codigo = '$padrao'";
    $head7 = " PADRÃO : $padrao";
}  
$sql = "
select rh01_regist,
       z01_nome, 
       rh03_padrao,
       rh37_descr,
       r02_valor,
       r02_codigo,
       case when r02_descr is null then 'Sem Padrão' else r02_descr end as r02_descr,
       rh37_funcao
       
from rhpessoalmov 
     inner join rhpessoal     on rh01_regist = rh02_regist 
     inner join cgm            on z01_numcgm = rh01_numcgm 
     left join rhpesrescisao   on rh05_seqpes = rh02_seqpes 
     inner join rhlota         on r70_codigo = rh02_lota
		                          and r70_instit = rh02_instit
     inner join rhfuncao       on rh37_funcao = rh02_funcao
		                          and rh37_instit = rh02_instit
     inner join rhregime       on rh30_codreg = rh02_codreg
		                          and rh30_instit = rh02_instit
     left  join rhpespadrao    on rh02_seqpes = rh03_seqpes
     left  join padroes        on  padroes.r02_anousu = rhpespadrao.rh03_anousu  
                               and padroes.r02_mesusu = rhpespadrao.rh03_mesusu  
                               and padroes.r02_instit = rhpessoalmov.rh02_instit
                               and padroes.r02_regime = rhregime.rh30_regime
                               and padroes.r02_codigo = rhpespadrao.rh03_padrao  
where rh02_anousu = $ano
  and rh02_mesusu = $mes
  and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_recis is null 
  $where
order by rh37_funcao,r02_codigo,z01_nome;
       ";
//echo $sql ; exit;
//die ($sql);
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cálculos para o período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
if($tiposa == 'f'){
   $pdf->setleftmargin(50);
}else{
   $pdf->setleftmargin(40);
}
$pdf->setfont('arial','b',8);
$troca      = 1;
$alt        = 4;
$total_fun  = 0;
$xfuncao    = 0;
$xpadrao    = 0;
$imp_cab = true;
$total_sec = 0;
$total_pad = 0;
$pre = 1;
$tot_registros = pg_numrows($result);
$x = 0;
while($x < $tot_registros){
   db_fieldsmemory($result,$x);
   if($pdf->gety() > $pdf->h - 35 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      if($tiposa == 'f'){
         $pdf->cell(20,$alt,'PADRÃO',1,0,"C",1);
         $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      }else{  
         $pdf->cell(40,$alt,'VALOR DO PADRÃO',1,1,"C",1);
      }  
      $troca = 0;
   }
   $pdf->setfont('arial','b',8);
   $pdf->cell(0,$alt,"CARGO :  ".$rh37_funcao." - ". $rh37_descr,0,1,"L",0);
   $xfuncao = $rh37_funcao;
   $iContFuncCargo = 0;
   while($xfuncao == $rh37_funcao && $x < $tot_registros){
      $pdf->setfont('arial','b',8);

      if($tiposa == 't'){
         $pdf->cell(0,$alt,"                             PADRÃO :  $r02_codigo - $r02_descr",0,1,"L",0);
      }
      $xpadrao = $r02_codigo;
      
      while($xpadrao == $r02_codigo && $xfuncao == $rh37_funcao && $x < $tot_registros){
         if ($pdf->gety() > $pdf->h - 35 || $troca != 0 ){
            $pdf->addpage();
            $pdf->setfont('arial','b',8);
            $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
            $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
            if($tiposa == 'f'){
               $pdf->cell(20,$alt,'PADRÃO',1,0,"C",1);
               $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
            }else{  
               $pdf->cell(40,$alt,'VALOR DO PADRÃO',1,1,"C",1);
            }
            $troca = 0;
         }
         if($pre == 1){
           $pre = 0;
         }else{
           $pre = 1;
         }
         if($tiposa == 't'){
            $pdf->setfont('arial','',7);
            $pdf->cell(15,$alt,$rh01_regist,0,0,"C",$pre);
            $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
            $pdf->cell(40,$alt,db_formatar($r02_valor,'f'),0,1,"R",$pre);
         }   
         $total_fun += $r02_valor;
         $total_pad += $r02_valor;
         $total_sec += $r02_valor;
         $x++;
         if($x < $tot_registros){
           db_fieldsmemory($result,$x);
         }
         $iContFuncCargo += 1;
      }
      if($xpadrao != 0){
        $pdf->setfont('arial','b',8);
        if($tiposa == 'f'){
           $pdf->cell(95,$alt,$xpadrao,0,0,"R",0);
           $pdf->cell(20,$alt,db_formatar($total_pad,'f'),0,1,"R",0);
        }else{  
           $pdf->cell(115,$alt,"TOTAL DO PADRÃO         :".db_formatar($total_pad,'f'),0,1,"R",0);
        }   
        $total_pad = 0;
      }
   }
   if($xfuncao != 0){
     $pdf->setfont('arial','b',8);
     $pdf->cell(115,$alt,"TOTAL DO CARGO          :".db_formatar($total_sec,'f'),0,1,"R",0);
     if ($tiposa == "t") {
       $pdf->cell(115,$alt,"TOTAL DE FUNCIONÁRIOS NO GARGO :               ".$iContFuncCargo,0,1,"R",0);
     }
     $total_sec = 0;
   }
}
$pdf->setfont('arial','b',8);
$pdf->cell(115,$alt,"TOTAL GERAL             :".db_formatar($total_fun,'f'),0,1,"R",0);
if ($tiposa == "t") {
  $pdf->cell(115,$alt,"TOTAL GERAL DE FUNCIONÁRIOS :                ".$tot_registros,0,0,"R",0);
}
$pdf->Output();
   
?>
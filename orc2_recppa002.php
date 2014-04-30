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
include("libs/db_sql.php");
include("classes/db_orcppalei_classe.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);


$clorcppalei = new cl_orcppalei;
$clrotulo = new rotulocampo;
$clrotulo->label('r37_funcao');
$clrotulo->label('r37_descr');
$clrotulo->label('r37_vagas');
$clrotulo->label('r37_cbo');
$clrotulo->label('r37_lei');
$clrotulo->label('r37_class');

$anosql = Array();

$ano1 = $ano;
$ano = substr(str_replace('-',',',$ano1),1);
$ano_arr = 	split(',',$ano);
$anoini =$ano_arr[0];
$anofim = $ano_arr[count($ano_arr) - 1];

if(sizeof($ano_arr) > 3 ){
  $head3 = "RELATÓRIO DE RECEITAS PLURI ANUAL";
}else{
  $head3 = "RELATÓRIO DE RECEITAS DA LDO";
}
$index  = 0;
if(!isset($lei)){
  db_redireciona('db_erros.php?fechar=true&db_erro=Lei não informada.');
}

$result_periodo = $clorcppalei->sql_record($clorcppalei->sql_query_file($lei,"o21_anoini,o21_anofim"));
if($clorcppalei->numrows==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Lei $lei não encontrada.");
}

db_fieldsmemory($result_periodo,0);

for($i=$o21_anoini;$i<=$o21_anofim;$i++){  
  $anosql[$index] = $i;
  $index++;
}
$where_tiporec = "";
$where_tiporec = " where o27_codleippa=$lei ";
if(isset($codigos) && trim($codigos)!=""){
  if($parametro=="S"){    
    $where_tiporec .= " and o15_codigo in ($codigos) ";
  }else{
    $where_tiporec .= " and o15_codigo not in ($codigos) ";
  }
}

if($tipo == 'R')
{
  $quebra = "Recurso";
  $sql = "
	select 
	       o70_codigo as recurso,
	       o15_descr as descr_recu,
	       sum(case when o27_exercicio = ".$anosql[0]." then o27_valor else 0 end) as a1, 
	       sum(case when o27_exercicio = ".$anosql[1]." then o27_valor else 0 end) as a2, 
	       sum(case when o27_exercicio = ".$anosql[2]." then o27_valor else 0 end) as a3, 
	       sum(case when o27_exercicio = ".$anosql[3]." then o27_valor else 0 end) as a4 
	from 
	(select o70_codigo,            
	            o15_descr ,
		        o27_exercicio,
		case when fc_conplano_grupo(o27_exercicio,substr(o57_fonte,1,2)||'%',9000) is true then 
	            (ABS(o27_valor)) *-1 
	      else o27_valor 
	    end as o27_valor
	from orcpparec 
	     left join orcfontes on o57_codfon = o27_codfon and o57_anousu = ".db_getsession("DB_anousu")."
	     left join orcreceita on o70_codfon = o27_codfon and o70_anousu = ".db_getsession("DB_anousu")."  
	     inner join orctiporec on o15_codigo = o70_codigo
	$where_tiporec ) as x 
	group by
		 o70_codigo,      
		 o15_descr
	order by o70_codigo

       ";
	$result = pg_exec($sql);

} else {
  if($tiporel == 's'){
    $quebra = "Receita Sintético";
    $sql = "
    select o57_fonte as estrut_mae,
              o57_descr as descr_rece,
              o70_codigo as recurso,
              o15_descr as descr_recu,
              sum(case when o27_exercicio = ".$anosql[0]." then o27_valor else 0 end) as a1, 
              sum(case when o27_exercicio = ".$anosql[1]." then o27_valor else 0 end) as a2, 
              sum(case when o27_exercicio = ".$anosql[2]." then o27_valor else 0 end) as a3, 
              sum(case when o27_exercicio = ".$anosql[3]." then o27_valor else 0 end) as a4
    FROM
    (SELECT o57_fonte,
            o57_descr,
            o70_codigo,
            o15_descr,
	        o27_exercicio,
            case when fc_conplano_grupo(o27_exercicio,substr(o57_fonte,1,2)||'%',9000) is true 
            then ABS(o27_valor) * (-1) 
            else o27_valor end as o27_valor
    from orcpparec 
         left join orcfontes on o57_codfon = o27_codfon and o70_anousu = ".db_getsession("DB_anousu")."
         left join orcreceita on o70_codfon = o27_codfon and o70_anousu = ".db_getsession("DB_anousu")." 
         inner join orctiporec on o15_codigo = o70_codigo
    $where_tiporec     ) as x
    group by o57_fonte,
            o57_descr,
	 		o70_codigo,
	 		o15_descr
    order by o57_fonte

    ";
//    echo $sql;exit;
    $result = pg_exec($sql);
  }else{
    $quebra = "Receita Analítico";
    /* db_receitappa ()
     * ------------------------------------------------------
     *  onde esta a função ?
     */
    $result = db_receitappa($anosql[0],$where_tiporec,false);
        
    
  }
}
//db_criatabela($result);exit;
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.@$o21_anoini.' / '.@$o21_anofim);
}

$head5 = "Quebra por $quebra";
$head6 = "Período $ano";

$pdf = new PDF(); 
$pdf->imprime_rodape = false;

$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$total  = 0;
$troca  = 1;
$alt    = 4;
$valor1 = 0;
$valor2 = 0;
$valor3 = 0;
$valor4 = 0;
$estrut = '';


if($tipo == 'R'){
///// quebrar por recurso

  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
    
     // quando for escolhido 2 ou mais exercicios
     $ct = true;
     for ($y=0;$y < count($ano_arr);$y++) {
   	    if ($anosql[0] ==  $ano_arr[$y]){
                if ($a1 > 0) $ct=false;
	    }  
            if ($anosql[1] ==  $ano_arr[$y]){
                if ($a2 > 0) $ct=false;
	    }  
            if ($anosql[2] ==  $ano_arr[$y]){
                if ($a3 > 0) $ct=false;
	    }  
            if ($anosql[3] ==  $ano_arr[$y]){
                if ($a4 > 0) $ct=false;
	    }  
	    if ($ct==true) continue(2);
     } 

     // if  ($a1==0 && $a2==0 && $a3==0 && $a4==0) continue;
     
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->addpage('L');
        $pdf->setfont('arial','b',8);
        $pdf->cell(30,$alt,'RECURSO',1,0,"C",1);
        $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
        for ($y=0;$y < count($ano_arr);$y++) {
            if (count($ano_arr) == $y +1){
         	   $lfin = 1;
            }else{
               $lfin = 0;	
            }	   
            if ($anosql[0] ==  $ano_arr[$y]){
        	$pdf->cell(22,$alt,$anosql[0],1,$lfin,"R",1);
            }
            if ($anosql[1] ==  $ano_arr[$y]){
        	$pdf->cell(22,$alt,$anosql[1],1,$lfin,"R",1);
            }	
            if ($anosql[2] ==  $ano_arr[$y]){
        	$pdf->cell(22,$alt,$anosql[2],1,$lfin,"R",1);
            }
            if ($anosql[3] ==  $ano_arr[$y]){
         	$pdf->cell(22,$alt,$anosql[3],1,$lfin,"R",1);
            }
        }
        $cor = 1;
        $troca = 0;
     }

     if($cor == 1)
       $cor = 0;
     else
       $cor = 1;

     $pdf->setfont('arial','',7);
     $pdf->cell(30,$alt,$recurso,0,0,"L",$cor);
     $pdf->cell(80,$alt,$descr_recu,0,0,"L",$cor);
     for ($y=0;$y < count($ano_arr);$y++) {
                if (count($ano_arr) == $y +1){
        	    $lfin = 1;
                }else{
                    $lfin = 0;	
                }	   
        	if ($anosql[0] ==  $ano_arr[$y]){
        		$pdf->cell(22,$alt,db_formatar($a1,'f'),0,$lfin,"R",$cor);
        	}
        	if ($anosql[1] ==  $ano_arr[$y]){
        		$pdf->cell(22,$alt,db_formatar($a2,'f'),0,$lfin,"R",$cor);
        	}	
        	if ($anosql[2] ==  $ano_arr[$y]){
        		$pdf->cell(22,$alt,db_formatar($a3,'f'),0,$lfin,"R",$cor);
        	}
        	if ($anosql[3]==  $ano_arr[$y]){
        		$pdf->cell(22,$alt,db_formatar($a4,'f'),0,$lfin,"R",$cor);
        	}
     }
     $total ++;
     $valor1 += $a1;
     $valor2 += $a2;
     $valor3 += $a3;
     $valor4 += $a4;
  }
  if($cor == 1)
    $cor = 0;
  else
    $cor = 1;
  $pdf->setfont('arial','b',8);
  $pdf->cell(110,$alt,'TOTAL DE REGISTROS  : '.$total,1,0,"C",0);
  
    for ($y=0;$y < count($ano_arr);$y++) {
         if (count($ano_arr) == $y+1){
       	   $lfin = 1;
         }else{
            $lfin = 0;	
         }	   
      
         if ($anosql[0] ==  $ano_arr[$y]){
       	 	$pdf->cell(22,$alt,db_formatar($valor1,'f'),1,$lfin,"R",$cor);
         }
      	if ($anosql[1] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($valor2,'f'),1,$lfin,"R",$cor);
       	}	
       	if ($anosql[2] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($valor3,'f'),1,$lfin,"R",$cor);
       	}
       	if ($anosql[3] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($valor4,'f'),1,$lfin,"R",$cor);
       	}
     }
  
}else{
////// quebrar por receita

  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);

     // se algum exercicio tiver valor maior que zero então imprime a linha 
     $ct = true;
     for ($y=0;$y < count($ano_arr);$y++) {
   	    if ($anosql[0] ==  $ano_arr[$y]){
                if ($a1 > 0) $ct=false;
	    }  
            if ($anosql[1] ==  $ano_arr[$y]){
                if ($a2 > 0) $ct=false;
	    }  
            if ($anosql[2] ==  $ano_arr[$y]){
                if ($a3 > 0) $ct=false;
	    }  
            if ($anosql[3] ==  $ano_arr[$y]){
                if ($a4 > 0) $ct=false;
	    }  
	    if ($ct==true) continue(2);
     } 

     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->addpage('L');
        $pdf->setfont('arial','b',8);
        $pdf->cell(30,$alt,'ESTRUTURAL',1,0,"C",1);
        $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
        $pdf->cell(80,$alt,'RECURSO',1,0,"C",1);
        for ($y=0;$y < count($ano_arr);$y++) {
        	if (count($ano_arr) == $y +1){
        	   $lfin = 1;
            }else{
               $lfin = 0;	
            }	   
        	if ($anosql[0] ==  $ano_arr[$y]){
        		$pdf->cell(22,$alt,$anosql[0],1,$lfin,"R",1);
        	}
        	if ($anosql[1] ==  $ano_arr[$y]){
        		$pdf->cell(22,$alt,$anosql[1],1,$lfin,"R",1);
        	}	
        	if ($anosql[2] ==  $ano_arr[$y]){
        		$pdf->cell(22,$alt,$anosql[2],1,$lfin,"R",1);
        	}
        	if ($anosql[3] ==  $ano_arr[$y]){
        		$pdf->cell(22,$alt,$anosql[3],1,$lfin,"R",1);
        	}
        }
        $cor = 1;
        $troca = 0;
     }
     if($cor == 1)
       $cor = 0;
     else
       $cor = 1;
     $pdf->setfont('arial','',7);
     $pdf->cell(30,$alt,$estrut_mae,0,0,"L",$cor);
     $pdf->cell(80,$alt,$descr_rece,0,0,"L",$cor);
     if($estrut != ''){
       $pdf->cell(80,$alt,$recurso.' - '.$descr_recu,0,0,"L",$cor);
     }else{
       $pdf->cell(80,$alt,'',0,0,"L",$cor);
     }
     //print ($ano1.' - '.$ano2.' - '.$ano3.' - '.$ano4);exit;
     for ($y=0;$y < count($ano_arr);$y++) {
         if (count($ano_arr) == $y+1){
       	   $lfin = 1;
         }else{
            $lfin = 0;	
         }	   
      
         if ($anosql[0] ==  $ano_arr[$y]){
       	 	$pdf->cell(22,$alt,db_formatar($a1,'f'),0,$lfin,"R",$cor);
         }
      	if ($anosql[1] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($a2,'f'),0,$lfin,"R",$cor);
       	}	
       	if ($anosql[2] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($a3,'f'),0,$lfin,"R",$cor);
       	}
       	if ($anosql[3] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($a4,'f'),0,$lfin,"R",$cor);
       	}
     } 	
     $total ++;
     $valor1 += $a1;
     $valor2 += $a2;
     $valor3 += $a3;
     $valor4 += $a4;
  } // end LOOP

  
  if($cor == 1)
    $cor = 0;
  else
    $cor = 1;
  if($tiporel == 's'){     
    $pdf->setfont('arial','b',8);
    $pdf->cell(190,$alt,'TOTAL DE REGISTROS  : '.$total,1,0,"C",0);
    for ($y=0;$y < count($ano_arr);$y++) {
         if (count($ano_arr) == $y+1){
       	   $lfin = 1;
         }else{
            $lfin = 0;	
         }	   
      
         if ($anosql[0] ==  $ano_arr[$y]){
       	 	$pdf->cell(22,$alt,db_formatar($valor1,'f'),1,$lfin,"R",$cor);
         }
      	if ($anosql[1] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($valor2,'f'),1,$lfin,"R",$cor);
       	}	
       	if ($anosql[2] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($valor3,'f'),1,$lfin,"R",$cor);
       	}
       	if ($anosql[3] ==  $ano_arr[$y]){
       		$pdf->cell(22,$alt,db_formatar($valor4,'f'),1,$lfin,"R",$cor);
       	}
     }
  }
}

$pdf->Output();
   
?>
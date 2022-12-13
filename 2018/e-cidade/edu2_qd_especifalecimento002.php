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
?
	include("fpdf151/pdfwebseller.php");
	include("classes/db_calendario_classe.php");
	include("classes/db_periodocalendario_classe.php");
	$clcalendario = new cl_calendario;
	$clperiodocalendario = new cl_periodocalendario;
	$escola = db_getsession("DB_coddepto");

	$sql = "select * from aluno
		     inner join matricula on ed47_i_codigo=ed60_i_aluno
				 inner join turma on ed60_i_turma=ed57_i_codigo
			where ed57_i_escola=$escola";

	$result = pg_query($sql);
	$linhas = pg_num_rows($result);

	//db_criatabela($result);
	//exit;
	if($linhas==0){?>
	 <table width='100%'>
	  <tr>
	   <td align='center'>
	    <font color='#FF0000' face='arial'>
	     <b>Nenhum registro encontrado.<br>
	     <input type='button' value='Fechar' onclick='window.close()'></b>
	    </font>
	   </td>
	  </tr>
	 </table>
	 <?
	 exit;
	}

	$pdf = new PDF();
	$pdf->Open();
	$pdf->AliasNbPages();
	$head1 = "Quadro de Especificações por Falecimento";
	$head2 = "Ano: ".$calendario;
	$head3 = "Mês: ".$mes;

$sql = "select distinct ed11_i_codigo,ed11_c_abrev,ed11_i_ensino,ed10_c_abrev from serie
	         inner join turma on ed11_i_codigo=ed57_i_serie
	         inner join calendario on ed57_i_calendario=ed52_i_codigo
			 inner join ensino on ed10_i_codigo = ed11_i_ensino
         where ed57_i_escola=$escola and ed52_i_ano=$calendario order by ed11_i_ensino";

$result1 = pg_query($sql);
$linhas1= pg_num_rows($result1);

  $pdf->Addpage("L");

  $pdf->ln(5);
  $troca = 1;
  $cor = "0";
  $pdf->setfillcolor(223);
  $primeiro = "";

  $pdf->setfont('arial','',8);

  db_fieldsmemory($result1,0);

  $pdf->cell(120,6,"Quadro de Especificações por Falecimento",1,1,"C",$cor);

  $xzero=20;
  $yzero=46;
  $pdf->setxy(10,46);
  $pdf->cell(10,12,"",1,0,"C",$cor);
  $pdf->setxy(20,52);

  $first=$ed10_c_abrev;
  $cont=0;
  for($x=0;$x<$linhas1;$x++){
 	db_fieldsmemory($result1,$x);
	if($first!=$ed10_c_abrev){
		$pdf->setxy($xzero,$yzero);
		$pdf->cell(14*$cont,6,$first,1,0,"C",$cor);
		$xzero=$xzero+($cont*14);
		$first=$ed10_c_abrev;
		$cont=0;
		$pdf->setxy($xulti,$yulti);
	}
	$cont=$cont+1;
	if($x==$linhas1-1){
		$pdf->cell(14,6,$ed11_c_abrev,1,1,"C",$cor);
	}else{
	    $pdf->cell(14,6,$ed11_c_abrev,1,0,"C",$cor);
	    $xulti = $pdf->getx();
		$yulti = $pdf->gety();
	}
  }
  $pdf->setxy($xzero,$yzero);
  $pdf->cell(14*$cont,6,$first,1,0,"C",$cor);
  $pdf->setxy(10,58);


  $pdf->cell(10,6,"",1,0,"C",$cor);
  $bit=1;
  $z=$linhas1*2;
  for($x=0;$x<$z;$x++){
 	if($x==$z-1){
		if($bit==1){
	      $pdf->cell(7,6,"M",1,0,"C",$cor);
		  $pdf->cell(10,6,"Total",1,1,"C",$cor);
	    }else{
	      $pdf->cell(7,6,"F",1,0,"C",$cor);
		  $pdf->cell(10,6,"Total",1,1,"C",$cor);
	  	}
	}else{
	  if($bit==1){
	    $pdf->cell(7,6,"M",1,0,"C",$cor);
	    $bit=0;
	  }else{
	    $pdf->cell(7,6,"F",1,0,"C",$cor);
	    $bit=1;
	  }
    }
  }

  for($x=0;$x<($linhas1*2);$x++){
     $vet[$x]=0;
  }

 for($idade=6;$idade<20;$idade++){

	if($idade==6){
		$part_sql2=" < 7 ";
	    $pdf->cell(10,6,"-7",1,0,"C",$cor);
	}else{
	    if($idade==19){
		   $part_sql2=" > 18 ";
	       $pdf->cell(10,6,"+18",1,0,"C",$cor);
		}else{
		   $part_sql2=" = ".$idade." ";
	       $pdf->cell(10,6,$idade,1,0,"C",$cor);
		}
	}

	$tlinha=0;
	$vcont=0;
	for($c1=0;$c1<$linhas1;$c1++){
		db_fieldsmemory($result1,$c1);

		$sql2 = "Select
         (select count(*) from matricula
           inner join aluno on ed60_i_aluno=ed47_i_codigo
           inner join turma on ed60_i_turma=ed57_i_codigo
           inner join calendario on ed57_i_calendario=ed52_i_codigo
           left join transfescolafora on ed104_i_aluno=ed47_i_codigo
           left join transfescolarede on ed60_i_codigo=ed103_i_matricula
         where  ed47_v_sexo='M'
           and $calendario-extract(year from ed47_d_nasc) ".$part_sql2."
           and (extract(month from ed104_d_data) = $mes
           or extract(month from ed103_d_data) = $mes)
           and ed52_i_ano = $calendario
           and ed57_i_escola = $escola
           and ed57_i_serie = ed11_i_codigo
           and ed60_c_situacao = 'FALECIDO') as masculino,

        (select count(*) from matricula
          inner join aluno on ed60_i_aluno=ed47_i_codigo
          inner join turma on ed60_i_turma=ed57_i_codigo
          inner join calendario on ed57_i_calendario=ed52_i_codigo
          left join transfescolafora on ed104_i_aluno=ed47_i_codigo
          left join transfescolarede on ed60_i_codigo=ed103_i_matricula
        where  ed47_v_sexo='F'
          and $calendario-extract(year from ed47_d_nasc) ".$part_sql2."
          and (extract(month from ed104_d_data) = $mes
          or extract(month from ed103_d_data) = $mes)
          and ed52_i_ano = $calendario
          and ed57_i_escola = $escola
          and ed57_i_serie = ed11_i_codigo
          and ed60_c_situacao = 'FALECIDO')  as feminino
        from serie
        where ed11_i_codigo=".$ed11_i_codigo;

		$result2 = pg_query($sql2);
		$linhas2= pg_num_rows($result2);
		db_fieldsmemory($result2,0);

		$tlinha=$tlinha+($masculino+$feminino);

		$vet[$vcont]=$vet[$vcont]+$masculino;
		$vcont=$vcont+1;

		$vet[$vcont]=$vet[$vcont]+$feminino;
		$vcont=$vcont+1;

		if($c1!=$linhas1-1){
			$pdf->cell(7,6,$masculino==0?'':$masculino,1,0,"C",$cor);
        	$pdf->cell(7,6,$feminino==0?'':$feminino,1,0,"C",$cor);
		}else{
			$pdf->cell(7,6,$masculino==0?'':$masculino,1,0,"C",$cor);
			$pdf->cell(7,6,$feminino==0?'':$feminino,1,0,"C",$cor);
			$pdf->cell(10,6,"$tlinha",1,1,"C",$cor);
        }
	}

 	$pdf->setfont('arial','',8);

  }
  $pdf->cell(10,6,"Total",1,0,"C",$cor);
  $total=0;
  for($x=0;$x<($linhas1*2);$x++){
     $pdf->cell(7,6,"$vet[$x]",1,0,"C",$cor);
     $total=$total+$vet[$x];
  }
  $pdf->cell(10,6,"$total",1,0,"C",$cor);


  $pdf->Output();


?>
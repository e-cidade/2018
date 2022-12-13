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
$head1 = "Expansão de Matricula por Idade/Sexo";
$head2 = "Ano: ".$calendario;
$head3 = "Mês: ".$mes;

  $pdf->Addpage("L");

  $pdf->ln(5);
  $cor = "0";
  $pdf->setfillcolor(223);
  $primeiro = "";

  //Definindo ultimo dia do mes
  if($mes==1||$mes==3||$mes==5||$mes==7||$mes==8||$mes==10||$mes==12){
       $dialimite = 31;
  }elseif($mes==4||$mes==6||$mes==9||$mes==11){
       $dialimite = 30;
  }else{
       $dialimite = 28;
  }

  $pdf->setfont('arial','',12);

  $pdf->cell(125,6,"Expansão de Matricula por Idade/Sexo",1,1,"C",$cor);

  $poszeroy = $pdf->Gety();
  $poszerox = $pdf->Getx();
  $pdf->cell(25,6,"Sexo","LRT",2,"R",$cor);
  $pdf->cell(25,6,"Idade","LRB",0,"L",$cor);
  $pdf->line(10,$poszeroy,35,$poszeroy+12);
  $pdf->setXY(35,$poszeroy);
  $posy = $pdf->Gety();
  $posx = $pdf->Getx();
  $pdf->cell(40,6,"Masculino",1,0,"C",$cor);
  $pdf->cell(40,6,"Feminino",1,0,"C",$cor);
  $pdf->cell(20,12,"Total",1,0,"C",$cor);
  $pdf->SetXY($posx,$posy+6);
  $pdf->cell(20,6,"Novos",1,0,"C",$cor);
  $pdf->cell(20,6,"Rep.",1,0,"C",$cor);
  $pdf->cell(20,6,"Novos",1,0,"C",$cor);
  $pdf->cell(20,6,"Rep.",1,0,"C",$cor);

  $pdf->SetXY($poszerox,$poszeroy+12);

  for($x=0;$x<5;$x++){
     $vet[$x]=0;
  }

	$sql2 = "select ed47_i_codigo,ed47_v_sexo from matricula
                    inner join aluno on ed60_i_aluno=ed47_i_codigo
                    inner join turma on ed60_i_turma=ed57_i_codigo
                    inner join calendario on ed57_i_calendario=ed52_i_codigo
             where ed60_c_situacao = 'MATRICULADO'
                   and ed60_d_datamatricula > $calendario-$mes-$dialimite
                   and ed52_i_ano = $calendario
                   and ed60_c_concluida='N'
                   and ed57_i_escola = $escola";
	$result2 = pg_query($sql2);
    $linhas2= pg_num_rows($result2);
    //die("Quantidade de alunos na escola: ".$linhas2);
  for($idade=6;$idade<20;$idade++){

	if($idade==6){
		$part_sql2=" < 7 ";
	    $pdf->cell(25,6,"-7",1,0,"C",$cor);
	}else{
	    if($idade==19){
		   $part_sql2=" > 18 ";
	       $pdf->cell(25,6,"+18",1,0,"C",$cor);
		}else{
		   $part_sql2=" = ".$idade." ";
	       $pdf->cell(25,6,$idade,1,0,"C",$cor);
		}
	}

	$masc_novo=0;
	$masc_rep=0;
    $fem_novo=0;
	$fem_rep=0;
	$tlinha=0;
	for($y=0;$y<$linhas2;$y++){
		db_fieldsmemory($result2,$y);
	    $sql3 = "select ed60_c_rfanterior from matricula
                      innee join aluno on ed60_i_aluno=ed47_i_codigo
                      inner join turma on ed60_i_turma=ed57_i_codigo
                 where ed47_i_codigo= $ed47_i_codigo
                      and $calendario-extract(year from ed47_d_nasc)".$part_sql2;

	   	  $result3 = pg_query($sql3);
          $linhas3 = pg_num_rows($result3);
		  if($linhas3>0){
		     $tlinha=$tlinha+1;
			 db_fieldsmemory($result3,0);
		     if($ed47_v_sexo=='M'){
	            if($ed60_c_rfanterior=='A'){
	      	       $masc_novo=$masc_novo+1;
				   $vet[0]=$vet[0]+1;
	            }else{
	               $masc_rep=$masc_rep+1;
	               $vet[1]=$vet[1]+1;
			    }
		    }else{
	    	    if($ed60_c_rfanterior=='A'){
	      	       $fem_novo=$fem_novo+1;
				   $vet[2]=$vet[2]+1;
	            }else{
	               $fem_rep=$fem_rep+1;
				   $vet[3]=$vet[3]+1;
	            }
		  }
	    }

	}


	$pdf->cell(20,6,$masc_novo==0?'':$masc_novo,1,0,"C",$cor);
    $pdf->cell(20,6,$masc_rep==0?'':$masc_rep,1,0,"C",$cor);
    $pdf->cell(20,6,$fem_novo==0?'':$fem_novo,1,0,"C",$cor);
    $pdf->cell(20,6,$fem_rep==0?'':$fem_rep,1,0,"C",$cor);


	$pdf->cell(20,6,"$tlinha",1,1,"C",$cor);
    $vet[4]=$vet[4]+$tlinha;
  }

  $pdf->cell(25,6,"Total",1,0,"C",$cor);
  for($x=0;$x<5;$x++){
     $pdf->cell(20,6,"$vet[$x]",1,0,"C",$cor);
  }

  $pdf->Output();
?>
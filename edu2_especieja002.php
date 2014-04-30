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
  $head1 = "Expanção por Etapa/idade";
  $head2 = "Ano: ".$calendario;
  $head3 = "Mês: ".$mes;

  $pdf->Addpage();

  //$pdf->ln(5);
  $troca = 1;
  $cor1 = "0";
  $cor2 = "1";
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

  $pdf->setfont('arial','',7);

  ////////////////////////////////////////////////////////////////////////////////// Tabela 01
  
  $pdf->cell(170,4,"Movimentação escolar EJA",1,1,"C",$cor);
  $alt_ini = $pdf->getY();
  $pdf->cell(20,4,"     ","LRT",2,"R",$cor);
  $pdf->cell(20,4,"Totalidade","LRB",0,"L",$cor);
  //$pdf->line(10,$alt_ini,30,$alt_ini+8);
  $pdf->setXY(30,$alt_ini);
  
  $posy=$pdf->getx();
  $pdf->cell(30,4,"Matricula Geral",1,0,"C",$cor);
  $pdf->cell(30,4,"Alunos Evadidos",1,0,"C",$cor);
  $pdf->cell(30,4,"Alunos Tranferidos",1,0,"C",$cor);
  $pdf->cell(30,4,"Alunos Novos",1,0,"C",$cor);
  $pdf->cell(30,4,"Matricula Final",1,1,"C",$cor);
  $pdf->setXY($posy,$alt_ini+4);
  $pdf->cell(10,4,"M",1,0,"C",$cor);
  $pdf->cell(10,4,"F",1,0,"C",$cor);
  $pdf->cell(10,4,"T",1,0,"C",$cor);
  $pdf->cell(10,4,"M",1,0,"C",$cor);
  $pdf->cell(10,4,"F",1,0,"C",$cor);
  $pdf->cell(10,4,"T",1,0,"C",$cor);
  $pdf->cell(10,4,"M",1,0,"C",$cor);
  $pdf->cell(10,4,"F",1,0,"C",$cor);
  $pdf->cell(10,4,"T",1,0,"C",$cor);
  $pdf->cell(10,4,"M",1,0,"C",$cor);
  $pdf->cell(10,4,"F",1,0,"C",$cor);
  $pdf->cell(10,4,"T",1,0,"C",$cor);
  $pdf->cell(10,4,"M",1,0,"C",$cor);
  $pdf->cell(10,4,"F",1,0,"C",$cor);
  $pdf->cell(10,4,"T",1,1,"C",$cor);


  for($x=0;$x<16;$x++){
     $vet[$x]=0;
  }

  $sql1 = "select ed11_i_codigo,ed11_c_descr,ed11_c_abrev from serie
                      inner join ensino on ed11_i_ensino=ed10_i_codigo
           where      ed10_i_tipoensino = 2
           order by   ed11_i_ensino";

  $result1 = pg_query($sql1);
  $linhas1= pg_num_rows($result1);  

  for($x=0;$x<$linhas1;$x++){
      db_fieldsmemory($result1,$x);
 
	  $pdf->cell(20,4,"$ed11_c_abrev",1,0,"C",$cor);
	  $vcont=1;
	  for($i=0;$i<4;$i++){
		 $sqlpart = "";
		 if($i==1){			
			$sqlpart = "and (ed60_c_situacao = 'EVADIDO' OR ed60_c_situacao = 'CANCELADO')";
		 }else{
		 	if($i==2){
		 	   $sqlpart = "and (ed60_c_situacao = 'TRANSFERIDO REDE' OR ed60_c_situacao = 'TRANSFERIDO FORA')";
		    }else{
		       if($i==3){
		       	  $sqlpart = "and (ed60_d_datamatricula between '$calendario-$mes-01' AND '$calendario-$mes-$dialimite')";
		       }
		    }
		 }
		 
		 
		 $sql2 = "select ed47_v_sexo,(count(*)) as totalsexo from matricula
                     inner join aluno on ed60_i_aluno=ed47_i_codigo
                     inner join turma on ed60_i_turma=ed57_i_codigo
					 inner join serie on ed57_i_serie=ed11_i_codigo 
					 inner join ensino on ed11_i_ensino=ed10_i_codigo
                     inner join calendario on ed57_i_calendario=ed52_i_codigo
                  where ed60_d_datamatricula> $calendario-$mes-$dialimite 
				      ".$sqlpart."
                     and ed57_i_serie=$ed11_i_codigo
                     and ed52_i_ano=$calendario
                     and ed57_i_escola=$escola
					 and ed10_i_tipoensino = 2
				  group by ed47_v_sexo";
		 $result2 = pg_query($sql2);
         $linhas2= pg_num_rows($result2);
		 
		 $masculino=0;
		 $feminino=0;	
		 if($linhas2==0){
		   $linhas2=-1;
		 }
         for($y=0;$y<$linhas2;$y++){
         	db_fieldsmemory($result2,$y);
			if($ed47_v_sexo=="M"){
         		$masculino=$totalsexo;
         	}else{
         		$feminino=$totalsexo;
         	}
         }
		 $vet[$vcont]=$vet[$vcont]+$masculino;
		 $vcont=$vcont+1;
		 $vet[$vcont]=$vet[$vcont]+$feminino;
		 $vcont=$vcont+1;
		 $vet[$vcont]=$vet[$vcont]+$masculino+$feminino;
		 $vcont=$vcont+1;
		 
		 $pdf->cell(10,4,$masculino,1,0,"C",$cor);
		 $pdf->cell(10,4,$feminino,1,0,"C",$cor);
		 $pdf->cell(10,4,($masculino+$feminino),1,0,"C",$cor);
		 
		 if($i==0){
		 	$tm=$masculino;
			$tf=$feminino;
			$tt=$masculino+$feminino;
		 }else{
		 	$tm=$tm-$masculino;
			$tf=$tf-$feminino;
			$tt=$tt-($masculino+$feminino);
		 }

  	  }
      $pdf->cell(10,4,$tm,1,0,"C",$cor);
	  $pdf->cell(10,4,$tf,1,0,"C",$cor);
	  $pdf->cell(10,4,($tt),1,1,"C",$cor);

  }

  $pdf->cell(20,4,"Total",1,0,"C",$cor);
  for($x=1;$x<16;$x++){
     $pdf->cell(10,4,"$vet[$x]",1,0,"C",$cor);
  }
  
  $pdf->cell(1,4," ",0,1,"C",$cor);
  $pdf->cell(1,4," ",0,1,"C",$cor);
  ////////////////////////////////////////////////////////////////////////////////// Tabela 02
  
  $pdf->cell(170,4,"Expansão de Matrícula por Etapa/idade",1,1,"C",$cor);
  $alt_ini = $pdf->getY();
  $pdf->cell(20,2,"Idade","LRT",2,"R",$cor);
  $pdf->cell(20,2,"Totalidade","LRB",0,"L",$cor);
  $pdf->line(10,$alt_ini,30,$alt_ini+4);
  $pdf->setXY(30,$alt_ini);
  
  for($x=15;$x<22;$x++){
		$pdf->cell(15,4,$x,1,0,"C",$cor);
  }
  $pdf->cell(15,4,"22 a 35",1,0,"C",$cor);
  $pdf->cell(15,4,"35 a 50",1,0,"C",$cor);
  $pdf->cell(15,4,"mais de 50",1,1,"C",$cor);

  for($x=0;$x<13;$x++){
     $vet[$x]=0;
  }

  $sql1 = "select ed11_i_codigo,ed11_c_descr,ed11_c_abrev from serie
                      inner join ensino on ed11_i_ensino=ed10_i_codigo
           where      ed10_i_tipoensino = 2
           order by   ed11_i_ensino";

  $result1 = pg_query($sql1);
  $linhas1= pg_num_rows($result1);  

  for($x=0;$x<$linhas1;$x++){
      db_fieldsmemory($result1,$x);
 
	  $pdf->cell(20,4,"$ed11_c_abrev",1,0,"C",$cor);
	  $vcont=1;
	  for($idade=15;$idade<25;$idade++){
         if($idade==22){  
		   $sqlpart = " > 22 and 2008-extract(year from ed47_d_nasc) < 35";
		 }else
		   if($idade==23){
		   	  $sqlpart = " > 35 and 2008-extract(year from ed47_d_nasc) < 50";
		   }else{
		   	  if($idade==24){
		   	     $sqlpart = " > 50 ";
			  }else{
			  	 $sqlpart=" = $idade";
			  }	 
		   }
		   
		 $sql2 = "select (count(*)) as quantidade from matricula
                     inner join aluno on ed60_i_aluno=ed47_i_codigo
                     inner join turma on ed60_i_turma=ed57_i_codigo
					 inner join serie on ed57_i_serie=ed11_i_codigo 
					 inner join ensino on ed11_i_ensino=ed10_i_codigo
                     inner join calendario on ed57_i_calendario=ed52_i_codigo
                  where 2008-extract(year from ed47_d_nasc)".$sqlpart."
                     and ed60_c_situacao='MATRICULADO'
                     and ed60_d_datamatricula> $calendario-$mes-$dialimite
                     and ed57_i_serie=$ed11_i_codigo
                     and ed52_i_ano=$calendario
                     and ed57_i_escola=$escola
					 and ed10_i_tipoensino = 2";
		 $result2 = pg_query($sql2);
         $linhas2= pg_num_rows($result2);
		 db_fieldsmemory($result2,0);

		 $vet[$vcont]=$vet[$vcont]+$quantidade;
		 $vcont=$vcont+1;
		 if($idade==24){
		 	$pdf->cell(15,4,$quantidade==0?'':$quantidade,1,1,"C",$cor);
		 }else{
		 	$pdf->cell(15,4,$quantidade==0?'':$quantidade,1,0,"C",$cor);
		 }
  	  }

  }

  $pdf->cell(20,4,"Total",1,0,"C",$cor);
  $total=0;
  for($x=1;$x<11;$x++){
     $pdf->cell(15,4,"$vet[$x]",1,0,"C",$cor);
     $total=$total+$vet[$x];
  }

  $pdf->cell(1,4," ",0,1,"C",$cor);
  $pdf->cell(1,4," ",0,1,"C",$cor);
  ////////////////////////////////////////////////////////////////////////////////// Tabela 03
/*
  $pdf->cell(140,4,"Número de Matrículas por Totalidade e Sexo",1,1,"C",$cor);
  $alt_ini = $pdf->getY();
  $pdf->cell(20,4,"     ","LRT",2,"R",$cor);
  $pdf->cell(20,4,"Totalidade","LRB",0,"L",$cor);
  //$pdf->line(10,$alt_ini,30,$alt_ini+8);
  $pdf->setXY(30,$alt_ini);
  
  $posy=$pdf->getx();
  $pdf->cell(60,4,"Masculino",1,0,"C",$cor);
  $pdf->cell(60,4,"Feminino",1,1,"C",$cor);
  $pdf->setXY($posy,$alt_ini+4);
  $pdf->cell(20,4,"Avanço",1,0,"C",$cor);
  $pdf->cell(20,4,"Aprov de Estudos",1,0,"C",$cor);
  $pdf->cell(20,4,"Permanência",1,0,"C",$cor);
  $pdf->cell(20,4,"Avanço",1,0,"C",$cor);
  $pdf->cell(20,4,"Aprov de Estudos",1,0,"C",$cor);
  $pdf->cell(20,4,"Permanência",1,1,"C",$cor);
  
  for($x=0;$x<16;$x++){
     $vet[$x]=0;
  }

  $sql1 = "select ed11_i_codigo,ed11_c_descr,ed11_c_abrev from serie
                      inner join ensino on ed11_i_ensino=ed10_i_codigo
           where      ed10_i_tipoensino = 2
           order by   ed11_i_ensino";

  $result1 = pg_query($sql1);
  $linhas1= pg_num_rows($result1);  

  for($x=0;$x<$linhas1;$x++){
      db_fieldsmemory($result1,$x);
 
	  $pdf->cell(20,4,"$ed11_c_abrev",1,0,"C",$cor);
	  $vcont=1;
	  for($i=0;$i<2;$i++){
		 $sqlpart = "";
		 if($i==0){			
			$sqlpart = "AND (ed60_c_situacao = 'EVADIDO' OR ed60_c_situacao = 'CANCELADO')";
		 }else{
		 	if($i==1){
		 	   $sqlpart = "and (ed60_c_situacao = 'TRANSFERIDO REDE' OR ed60_c_situacao = 'TRANSFERIDO FORA')";
		    }
		 }
		 
		 
		 $sql2 = "select (count(ed47_v_sexo='M')) as masculino,(count(ed47_v_sexo='F')) as feminino from matricula
                     inner join aluno on ed60_i_aluno=ed47_i_codigo
                     inner join turma on ed60_i_turma=ed57_i_codigo
					 inner join serie on ed57_i_serie=ed11_i_codigo 
					 inner join ensino on ed11_i_ensino=ed10_i_codigo
                     inner join calendario on ed57_i_calendario=ed52_i_codigo
                  where ed60_c_situacao='MATRICULADO'  ".$sqlpart."
                     and ed60_d_datamatricula> $calendario-$mes-$dialimite
                     and ed57_i_serie=$ed11_i_codigo
                     and ed52_i_ano=$calendario
                     and ed57_i_escola=$escola
					 and ed10_i_tipoensino = 2";
		 $result2 = pg_query($sql2);
         $linhas2= pg_num_rows($result2);
		 db_fieldsmemory($result2,0);

		 $vet[$vcont]=$vet[$vcont]+$masculino;
		 $vcont=$vcont+1;
		 $vet[$vcont]=$vet[$vcont]+$feminino;
		 $vcont=$vcont+1;
		 $vet[$vcont]=$vet[$vcont]+$masculino+$feminino;
		 $vcont=$vcont+1;
		 
		 $pdf->cell(20,4,$masculino,1,0,"C",$cor);
		 $pdf->cell(20,4,$feminino,1,0,"C",$cor);
		 if($i==1){  
		   $pdf->cell(20,4,($masculino+$feminino),1,1,"C",$cor);
         }else{
           $pdf->cell(20,4,($masculino+$feminino),1,0,"C",$cor);
         } 

  	  }
  }

  $pdf->cell(20,4,"Total",1,0,"C",$cor);
  $total=0;
  for($x=1;$x<7;$x++){
     $pdf->cell(20,4,"$vet[$x]",1,0,"C",$cor);
     $total=$total+$vet[$x];
  }

  $pdf->cell(1,4," ",0,1,"C",$cor);
  $pdf->cell(1,4," ",0,1,"C",$cor); */
  ////////////////////////////////////////////////////////////////////////////////// Tabela 04

 $pdf->cell(180,4,"Transferencia dee Escola EJA",1,1,"C",$cor);

  $pdf->cell(20,4,"Totalidade",1,0,"C",$cor);
  

  $sql1 = "select ed11_i_codigo,ed11_c_descr,ed11_c_abrev from serie
                      inner join ensino on ed11_i_ensino=ed10_i_codigo
           where      ed10_i_tipoensino = 2
           order by   ed11_i_ensino";

  $result1 = pg_query($sql1);
  $linhas1= pg_num_rows($result1);

  $quebra=0;
  for($x=0;$x<$linhas1;$x++){
    db_fieldsmemory($result1,$x);
	if($x==$linhas1-1){$quebra=1;}
	$pdf->cell(20,4,"$ed11_c_abrev",1,$quebra,"C",$cor);
  }
  
  $pdf->cell(20,4,"Idade/Sexo",1,0,"C",$cor);
  
  $quebra=0;
  for($x=0;$x<$linhas1;$x++){
	$pdf->cell(10,4,"M",1,0,"C",$cor);
	if($x==$linhas1-1){$quebra=1;}  
    $pdf->cell(10,4,"F",1,$quebra,"C",$cor);
  
  }

  for($x=0;$x<($linhas1*2)+1;$x++){
     $vet[$x]=0;
  }

  $sql1 = "select ed11_i_codigo,ed11_c_descr,ed11_c_abrev from serie
                      inner join ensino on ed11_i_ensino=ed10_i_codigo
           where      ed10_i_tipoensino = 2
           order by   ed11_i_ensino";

  $result1 = pg_query($sql1);
  $linhas1= pg_num_rows($result1);  

  for($idade=15;$idade<25;$idade++){
      
	  if($idade==22){ 
	     $pdf->cell(20,4,"22 a 35",1,0,"C",$cor);
         $sqlpart = " > 22 and 2008-extract(year from ed47_d_nasc) < 35";
	  }else{    
		if($idade==23){  
		   $pdf->cell(20,4,"35 a 50",1,0,"C",$cor);
		   $sqlpart = "> 35 and 2008-extract(year from ed47_d_nasc) < 50";
        }else{
          if($idade==24){	  
	         $pdf->cell(20,4,"mais de 50",1,0,"C",$cor);
			 $sqlpart = " > 50 ";
	      }else{    
			   $pdf->cell(20,4,"$idade",1,0,"C",$cor);
			   $sqlpart = " = $idade";
	      }
		}
	  }	  
	  $vcont=1;
	  for($i=0;$i<$linhas1;$i++){
		 db_fieldsmemory($result1,$i);
		 
		 $sql2 = "select ed47_v_sexo,(count(*)) as totalsexo from matricula
                     inner join aluno on ed60_i_aluno=ed47_i_codigo
                     inner join turma on ed60_i_turma=ed57_i_codigo
					 inner join serie on ed57_i_serie=ed11_i_codigo 
					 inner join ensino on ed11_i_ensino=ed10_i_codigo
                     inner join calendario on ed57_i_calendario=ed52_i_codigo
                  where 2008-extract(year from ed47_d_nasc)".$sqlpart."
                     and (ed60_c_situacao = 'TRANSFERIDO REDE' OR ed60_c_situacao = 'TRANSFERIDO FORA')
					 and ed60_d_datamatricula> $calendario-$mes-$dialimite
					 and ed57_i_serie=$ed11_i_codigo
                     and ed52_i_ano=$calendario
                     and ed57_i_escola=$escola
					 and ed10_i_tipoensino = 2
				  group by ed47_v_sexo";
		 $result2 = pg_query($sql2);
         $linhas2= pg_num_rows($result2);
         $masculino=0;
		 $feminino=0;
		 if($linhas2==0){
		   $linhas2=-1;	
		 }
		 for($x=0;$x<$linhas2;$x++){
         	db_fieldsmemory($result2,$y);
			if($ed47_v_sexo=="M"){
         		$masculino=$totalsexo;
         	}else{
         		$feminino=$totalsexo;
         	}
         }
		 $vet[$vcont]=$vet[$vcont]+$masculino;
		 $vcont=$vcont+1;
		 $vet[$vcont]=$vet[$vcont]+$feminino;
		 $vcont=$vcont+1;		 
		 $pdf->cell(10,4,$masculino,1,0,"C",$cor);
		 $quebra=0;
		 if($i==$linhas1-1){$quebra=1;}
		 $pdf->cell(10,4,$feminino,1,$quebra,"C",$cor);
		 
  	  }

  }

  $pdf->cell(20,4,"Total",1,0,"C",$cor);
  //die("Numero de linhas: ".$linhas1);
  for($x=1;$x<($linhas1*2)+1;$x++){
     $pdf->cell(10,4,"$vet[$x]",1,0,"C",$cor);
  }

  $pdf->Output();

?>
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

  $pdf->Addpage("L");

  $pdf->ln(5);
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

  $pdf->setfont('arial','',8);

  $pdf->cell(224,6,"Expansão de Matrícula por Etapa/idade",1,1,"C",$cor);
  $alt_ini = $pdf->getY();
  $pdf->cell(14,3,"Idade","LRT",2,"R",$cor);
  $pdf->cell(14,3,"Etapa","LRB",0,"L",$cor);
  $pdf->line(10,$alt_ini,24,$alt_ini+6);
  $pdf->setXY(24,$alt_ini);
  $pdf->cell(14,6,"-7",1,0,"C",$cor);
  for($x=7;$x<19;$x++){
		$pdf->cell(14,6,$x,1,0,"C",$cor);
  }
  $pdf->cell(14,6,"+18",1,0,"C",$cor);
  $pdf->cell(14,6,"Total",1,1,"C",$cor);


  $sql1 = "select distinct ed11_i_codigo,ed11_c_abrev,ed11_i_ensino,ed10_c_descr from serie
	         inner join turma on ed11_i_codigo=ed57_i_serie
	         inner join calendario on ed57_i_calendario=ed52_i_codigo
			 inner join ensino on ed10_i_codigo = ed11_i_ensino
         where ed57_i_escola=$escola and ed52_i_ano=$calendario order by ed11_i_ensino";

  $result1 = pg_query($sql1);
  $linhas1= pg_num_rows($result1);

  for($x=0;$x<15;$x++){
     $vet[$x]=0;
  }

  $first="";
  for($x=0;$x<$linhas1;$x++){

	  db_fieldsmemory($result1,$x);

	  if($first!=$ed11_i_ensino){
         $pdf->cell(224,4,$ed10_c_descr,1,1,"L",0);
         $first = $ed11_i_ensino;
      }

	  $pdf->cell(14,6,"$ed11_c_abrev",1,0,"C",$cor);

	  $tlinha=0;
	  $vcont=1;
	  for($idade=6;$idade<20;$idade++){

		 $sql2 = "select (count(*)) as quantidade from matricula
                     inner join aluno on ed60_i_aluno=ed47_i_codigo
                     inner join turma on ed60_i_turma=ed57_i_codigo
                     inner join calendario on ed57_i_calendario=ed52_i_codigo
                  where 2008-extract(year from ed47_d_nasc) = $idade
                     and ed60_c_situacao='MATRICULADO'
                     and ed60_d_datamatricula> $calendario-$mes-$dialimite
                     and ed57_i_serie=$ed11_i_codigo
                     and ed52_i_ano=$calendario
                     and ed57_i_escola=$escola";
		 $result2 = pg_query($sql2);
         $linhas2= pg_num_rows($result2);
		 db_fieldsmemory($result2,0);

		 $tlinha=$tlinha+$quantidade;
		 $vet[$vcont]=$vet[$vcont]+$quantidade;
		 $vcont=$vcont+1;
		 if($idade==19){
		 	$pdf->cell(14,6,$quantidade==0?'':$quantidade,1,0,"C",$cor);
			$pdf->cell(14,6,"$tlinha",1,1,"C",$cor);
		 }else{
		 	$pdf->cell(14,6,$quantidade==0?'':$quantidade,1,0,"C",$cor);
		 }
  	  }

  }

  $pdf->cell(14,6,"Total",1,0,"C",$cor);
  $total=0;
  for($x=1;$x<15;$x++){
     $pdf->cell(14,6,"$vet[$x]",1,0,"C",$cor);
     $total=$total+$vet[$x];
  }
  $pdf->cell(14,6,"$total",1,0,"C",$cor);

  $pdf->Output();

?>
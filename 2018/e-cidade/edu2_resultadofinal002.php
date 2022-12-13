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

require("libs/db_stdlibwebseller.php");
include("fpdf151/pdf.php");
include("classes/db_turma_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_regenciaperiodo_classe.php");
$resultedu= eduparametros(db_getsession("DB_coddepto"));
$clturma = new cl_turma;
$clmatricula = new cl_matricula;
$clregencia = new cl_regencia;
$clregenciaperiodo = new cl_regenciaperiodo;
$sql = $clturma->sql_query("","*","ed57_c_descr"," ed57_i_codigo in ($turmas)");
$result = $clturma->sql_record($sql);
//db_criatabela($result);
//exit;
if($clturma->numrows==0){?>
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
for($x=0;$x<$clturma->numrows;$x++){
 db_fieldsmemory($result,$x);
 $sql1= $clregenciaperiodo->sql_query("","sum(ed78_i_aulasdadas) as aulas",""," ed78_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo)");
 $result1 = $clregenciaperiodo->sql_record($sql1);
 db_fieldsmemory($result1,0);
 $pdf->setfillcolor(223);
 $dia = substr($ed52_d_resultfinal,8,2);
 $mes = db_mes(substr($ed52_d_resultfinal,5,2));
 $ano = substr($ed52_d_resultfinal,0,4);
 $head1 = "ATA DE RESULTADOS FINAIS";
 $head2 = "Aos $dia dias do mês de $mes de $ano conclui-se a apuração final do rendimento escolar, nos termos da lei 9394 de 20 de dezembro de 1996.";
 $head3 = "Tipo de Ensino: $ed10_c_descr";
 $head4 = "Curso: $ed29_c_descr";
 $head5 = "Série: $ed11_c_descr     Ano: $ed52_i_ano     C.H. Total: $aulas";
 $head6 = "Turma: $ed57_c_descr     Dias Letivos: $ed52_i_diasletivos     Turno: $ed15_c_nome";
 $head7 = "Ato de Autorização: Resolução Elaboraçao Hist. Esc. n° 115";
 $pdf->addpage('P');
 $pdf->ln(5);
 /*
 $pdf->setfont('arial','BI',9);
 $pdf->Text(140,9,$ed18_c_nome);
 $pdf->SetFont('Arial','I',8);
 $pdf->Text(140,14,$ed18_c_coordenadoria);
 $pdf->Text(140,18,"Endereço: ".$j14_nome." ".$ed18_i_numero);
 $pdf->Text(140,22,"Bairro: ".$j13_descr);
 $pdf->Text(140,26,"Cidade: ".$cp05_localidades." - ".$cp06_sigla);
 $pdf->Text(140,30,"E-mail: ".$ed18_c_email);
 */
 //inicio cabeçalho
 $pdf->setfont('arial','b',7);
 $inicio = $pdf->getY();
 $pdf->cell(5,4,"","LRT",0,"C",0);
 $pdf->cell(55,4,"Disciplinas","LRT",0,"R",0);
 $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr","ed59_i_codigo"," ed59_i_turma = $ed57_i_codigo");
 $result2 = $clregencia->sql_record($sql2);
 $cont = 0;
 $reg_pagina = 0;
 $sep = "";
 for($y=0;$y<$clregencia->numrows;$y++){
  db_fieldsmemory($result2,$y);
  if($y<8){
   $pdf->cell(15,4,$ed232_c_abrev,"LRT",0,"C",0);
   $cont++;
   $reg_pagina .= $sep.$ed59_i_codigo;
   $sep = ",";
  }
 }
 for($y=$cont;$y<8;$y++){
  $pdf->cell(15,4,"","LRT",0,"C",0);
 }
 $pdf->cell(10,8,"RF",1,1,"C",0);
 $pdf-> setY($inicio+4);
 $pdf->cell(5,4,"","LRB",0,"C",0);
 $pdf->cell(55,4,"Carga Horária","LRB",0,"R",0);
 $cont1= 0;
 for($y=0;$y<$clregencia->numrows;$y++){
  db_fieldsmemory($result2,$y);
  $sql3= $clregenciaperiodo->sql_query("","sum(ed78_i_aulasdadas) as aulas",""," ed78_i_regencia = $ed59_i_codigo");
  $result3 = $clregenciaperiodo->sql_record($sql3);
  db_fieldsmemory($result3,0);
  if($y<8){
   $pdf->cell(15,4,$aulas==""?"0":$aulas,"LRB",0,"C",0);
   $cont1++;
  }
 }
 for($y=$cont1;$y<8;$y++){
  $pdf->cell(15,4,"","LRB",0,"C",0);
 }
 $pdf->cell(10,4,"",0,1,"C",0);
 $pdf->cell(5,4,"N°",1,0,"C",0);
 $pdf->cell(55,4,"Nome do Aluno",1,0,"C",0);
 $cont2 = 0;
 for($y=0;$y<$clregencia->numrows;$y++){
  if($y<8){
   $pdf->cell(15,4,"Aprov",1,0,"C",0);
   $cont2++;
  }
 }
 for($y=$cont2;$y<8;$y++){
  $pdf->cell(15,4,"",1,0,"C",0);
 }
 $pdf->cell(10,4,"",1,1,"C",0);
 //fim cabeçalho
 $sql4= $clmatricula->sql_query("","ed60_i_codigo,ed60_c_situacao,ed60_i_aluno,ed60_i_numaluno,ed47_v_nome","ed47_v_nome"," ed60_i_turma = $ed57_i_codigo");
 $result4 = $clmatricula->sql_record($sql4);
 $cor1 = 0;
 $cor2 = 1;
 $cor = "";
 $cont4 = 0;
 $limite = 41;
 $cont_geral = 0;
 for($z=0;$z<$clmatricula->numrows;$z++){
  db_fieldsmemory($result4,$z);
  if($cor==$cor1){
   $cor = $cor2;
  }else{
   $cor = $cor1;
  }
  $pdf->cell(5,4,$ed60_i_numaluno,"LR",0,"C",$cor);
  $pdf->cell(55,4,$ed47_v_nome,"LR",0,"L",$cor);
  $sql5 = "SELECT ed74_c_valoraprov,ed81_c_todoperiodo,ed37_c_tipo
           FROM diariofinal
            inner join diario on ed95_i_codigo = ed74_i_diario
            left join amparo on ed81_i_diario = ed95_i_codigo
            inner join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov
            inner join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed95_i_regencia in ($reg_pagina)
           ORDER BY ed95_i_regencia
         ";
  $result5 = pg_query($sql5);
  $linhas5 = pg_num_rows($result5);
  $cont3 = 0;
  if($linhas5>0){
   for($v=0;$v<$linhas5;$v++){
    db_fieldsmemory($result5,$v);
    if(trim($ed60_c_situacao)!="MATRICULADO"){
     //$aproveitamento = trim($ed60_c_situacao);
    }else{
     if(trim($ed81_c_todoperiodo)=="S"){
      $aproveitamento = "AMPARADO";
     }else{
      if(trim($ed37_c_tipo)=="NOTA"){
       $aproveitamento = number_format($ed74_c_valoraprov,2,".",".");
      }elseif(trim($ed37_c_tipo)=="PARECER"){
       $aproveitamento = "Parecer";
      }else{
       $aproveitamento = $ed74_c_valoraprov;
      }
     }
    }
    $pdf->cell(15,4,$aproveitamento,"LR",0,"C",$cor);
    $cont3++;
   }
  }else{
   $pdf->cell(15,4,substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5),"LR",0,"C",$cor);
   $cont3++;
  }
  for($y=$cont3;$y<8;$y++){
   $pdf->cell(15,4,"","LR",0,"C",$cor);
  }
  $sql6 = "SELECT ed95_i_codigo
           FROM diario
            inner join aluno on ed47_i_codigo = ed95_i_aluno
            inner join diariofinal on ed74_i_diario = ed95_i_codigo
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo)
           AND ed74_c_resultadofinal = 'R'
          ";
  $result6 = pg_query($sql6);
  $linhas6 = pg_num_rows($result6);
  //db_criatabela($result4);
  if($linhas6==0){
   $rf = "APR";
  }else{
   $rf = "REP";
  }
  $pdf->cell(10,4,$rf,"LR",1,"C",$cor);
  $pdf->line(10,52,200,52);
  if($cont4==$limite && ($cont_geral+1)<$clmatricula->numrows){
   //inicio rodape
   $pdf->cell(100,4,"Convenções",1,0,"C",0);
   $pdf->cell(90,4,"Observações",1,1,"C",0);
   $alt_conv = $pdf->getY();
   $cont5 = 0;
   $borda = "L";
   $quebra = "0";
   $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr","ed59_i_codigo"," ed59_i_codigo in ($reg_pagina)");
   $result2 = $clregencia->sql_record($sql2);
   for($y=0;$y<$clregencia->numrows;$y++){
    db_fieldsmemory($result2,$y);
    $pdf->cell(50,4,$ed232_c_abrev." - ".$ed232_c_descr,$borda,$quebra,"L",0);
    if(($y%2)==0){
     $borda = "R";
     $quebra = "1";
    }else{
     $borda = "L";
     $quebra = "0";
    }
    $cont5++;
   }
   if($quebra=="1"){
    $pdf->cell(50,4,"","R",1,"L",0);
    $cont5++;
   }
   for($y=($cont5/2);$y<12;$y++){
    $pdf->cell(100,4,"","LR",1,"L",0);
   }
   $pdf->setY($alt_conv);
   $pdf->setX(110);
   $pdf->cell(90,20,"","LBR",2,"L",0);
   $pdf->cell(90,4,"E, para constar, foi lavrada esta ata.","LR",2,"C",0);
   $pdf->cell(90,4,"Alegrete, ".date("d",db_getsession("DB_datausu"))." de ".db_mes(date("m",db_getsession("DB_datausu")))." de ".date("Y",db_getsession("DB_datausu")),"LR",2,"C",0);
   $pdf->cell(90,8,"","LR",2,"L",0);
   $pdf->cell(45,4,"_______________________","L",0,"C",0);
   $pdf->cell(45,4,"_______________________","R",1,"C",0);
   $pdf->setX(110);
   $pdf->cell(45,4,"SECRETÁRIO(A)","L",0,"C",0);
   $pdf->cell(45,4,"DIRETOR(A)","R",1,"C",0);
   $pdf->setX(110);
   $pdf->cell(90,4,"","LR",1,"C",0);
   $pdf->cell(190,2,"",1,1,"C",0);
   //fim rodape
   $pdf->addpage('P');
   $pdf->ln(5);
   //inicio cabeçalho
   $pdf->setfont('arial','b',7);
   $inicio = $pdf->getY();
   $pdf->cell(5,4,"","LRT",0,"C",0);
   $pdf->cell(55,4,"Disciplinas","LRT",0,"R",0);
   $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr","ed59_i_codigo"," ed59_i_codigo in ($reg_pagina)");
   $result2 = $clregencia->sql_record($sql2);
   $cont = 0;
   $reg_pagina = 0;
   $sep = "";
   for($y=0;$y<$clregencia->numrows;$y++){
    db_fieldsmemory($result2,$y);
    if($y<8){
     $pdf->cell(15,4,$ed232_c_abrev,"LRT",0,"C",0);
     $cont++;
     $reg_pagina .= $sep.$ed59_i_codigo;
     $sep = ",";
    }
   }
   for($y=$cont;$y<8;$y++){
    $pdf->cell(15,4,"","LRT",0,"C",0);
   }
   $pdf->cell(10,8,"RF",1,1,"C",0);
   $pdf-> setY($inicio+4);
   $pdf->cell(5,4,"","LRB",0,"C",0);
   $pdf->cell(55,4,"Carga Horária","LRB",0,"R",0);
   $cont1= 0;
   for($y=0;$y<$clregencia->numrows;$y++){
    db_fieldsmemory($result2,$y);
    $sql3= $clregenciaperiodo->sql_query("","sum(ed78_i_aulasdadas) as aulas",""," ed78_i_regencia = $ed59_i_codigo");
    $result3 = $clregenciaperiodo->sql_record($sql3);
    db_fieldsmemory($result3,0);
    if($y<8){
     $pdf->cell(15,4,$aulas==""?"0":$aulas,"LRB",0,"C",0);
     $cont1++;
    }
   }
   for($y=$cont1;$y<8;$y++){
    $pdf->cell(15,4,"","LRB",0,"C",0);
   }
   $pdf->cell(10,4,"",0,1,"C",0);
   $pdf->cell(5,4,"N°",1,0,"C",0);
   $pdf->cell(55,4,"Nome do Aluno",1,0,"C",0);
   $cont2 = 0;
   for($y=0;$y<$clregencia->numrows;$y++){
    if($y<8){
     $pdf->cell(15,4,"Aprov",1,0,"C",0);
     $cont2++;
    }
   }
   for($y=$cont2;$y<8;$y++){
    $pdf->cell(15,4,"",1,0,"C",0);
   }
   $pdf->cell(10,4,"",1,1,"C",0);
   //fim cabeçalho
   $cont4 = -1;
  }
  $cont4++;
  $cont_geral++;
 }
 for($z=$cont4;$z<$limite+1;$z++){
  $pdf->cell(5,4,"","LR",0,"C",0);
  $pdf->cell(55,4,"","LR",0,"L",0);
  for($t=0;$t<8;$t++){
   $pdf->cell(15,4,"","LR",0,"C",0);
  }
  $pdf->cell(10,4,"","LR",1,"C",0);
 }
 //inicio rodape
 $pdf->cell(100,4,"Convenções",1,0,"C",0);
 $pdf->cell(90,4,"Observações",1,1,"C",0);
 $alt_conv = $pdf->getY();
 $cont5 = 0;
 $borda = "L";
 $quebra = "0";
 $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr","ed59_i_codigo"," ed59_i_codigo in ($reg_pagina)");
 $result2 = $clregencia->sql_record($sql2);
 for($y=0;$y<$clregencia->numrows;$y++){
  db_fieldsmemory($result2,$y);
  $pdf->cell(50,4,$ed232_c_abrev." - ".$ed232_c_descr,$borda,$quebra,"L",0);
  if(($y%2)==0){
   $borda = "R";
   $quebra = "1";
  }else{
   $borda = "L";
   $quebra = "0";
  }
  $cont5++;
 }
 if($quebra=="1"){
  $pdf->cell(50,4,"","R",1,"L",0);
  $cont5++;
 }
 for($y=($cont5/2);$y<12;$y++){
  $pdf->cell(100,4,"","LR",1,"L",0);
 }
 $pdf->setY($alt_conv);
 $pdf->setX(110);
 $pdf->cell(90,20,"","LBR",2,"L",0);
 $pdf->cell(90,4,"E, para constar, foi lavrada esta ata.","LR",2,"C",0);
 $pdf->cell(90,4,"Alegrete, ".date("d",db_getsession("DB_datausu"))." de ".db_mes(date("m",db_getsession("DB_datausu")))." de ".date("Y",db_getsession("DB_datausu")),"LR",2,"C",0);
 $pdf->cell(90,8,"","LR",2,"L",0);
 $pdf->cell(45,4,"_______________________","L",0,"C",0);
 $pdf->cell(45,4,"_______________________","R",1,"C",0);
 $pdf->setX(110);
 $pdf->cell(45,4,"SECRETÁRIO(A)","L",0,"C",0);
 $pdf->cell(45,4,"DIRETOR(A)","R",1,"C",0);
 $pdf->setX(110);
 $pdf->cell(90,4,"","LR",1,"C",0);
 $pdf->cell(190,2,"",1,1,"C",0);
 //fim rodape
 $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr","ed59_i_codigo"," ed59_i_turma = $ed57_i_codigo AND ed59_i_codigo not in ($reg_pagina)");
 $result2 = $clregencia->sql_record($sql2);
 
 /////////////////////////////////////////////////////////////////////////////////////////
 
 if($clregencia->numrows>0){
  $pdf->addpage('P');
  $pdf->ln(5);
  $pdf->setfont('arial','b',7);
  $inicio = $pdf->getY();
  $pdf->cell(5,4,"","LRT",0,"C",0);
  $pdf->cell(55,4,"Disciplinas","LRT",0,"R",0);
  $cont = 0;
  $reg_pagina = 0;
  $sep = "";
  for($y=0;$y<$clregencia->numrows;$y++){
   db_fieldsmemory($result2,$y);
   if($y<8){
    $pdf->cell(15,4,$ed232_c_abrev,"LRT",0,"C",0);
    $cont++;
    $reg_pagina .= $sep.$ed59_i_codigo;
    $sep = ",";
   }
  }
  for($y=$cont;$y<8;$y++){
   $pdf->cell(15,4,"","LRT",0,"C",0);
  }
  $pdf->cell(10,8,"RF",1,1,"C",0);
  $pdf-> setY($inicio+4);
  $pdf->cell(5,4,"","LRB",0,"C",0);
  $pdf->cell(55,4,"Carga Horária","LRB",0,"R",0);
  $cont1= 0;
  for($y=0;$y<$clregencia->numrows;$y++){
   db_fieldsmemory($result2,$y);
   $sql3= $clregenciaperiodo->sql_query("","sum(ed78_i_aulasdadas) as aulas",""," ed78_i_regencia = $ed59_i_codigo");
   $result3 = $clregenciaperiodo->sql_record($sql3);
   db_fieldsmemory($result3,0);
   if($y<8){
    $pdf->cell(15,4,$aulas==""?"0":$aulas,"LRB",0,"C",0);
    $cont1++;
   }
  }
  for($y=$cont1;$y<8;$y++){
   $pdf->cell(15,4,"","LRB",0,"C",0);
  }
  $pdf->cell(10,4,"",0,1,"C",0);
  $pdf->cell(5,4,"N°",1,0,"C",0);
  $pdf->cell(55,4,"Nome do Aluno",1,0,"C",0);
  $cont2 = 0;
  for($y=0;$y<$clregencia->numrows;$y++){
   if($y<8){
    $pdf->cell(15,4,"Aprov",1,0,"C",0);
    $cont2++;
   }
  }
  for($y=$cont2;$y<8;$y++){
   $pdf->cell(15,4,"",1,0,"C",0);
  }
  $pdf->cell(10,4,"",1,1,"C",0);
  //fim cabeçalho
  $sql4= $clmatricula->sql_query("","ed60_i_codigo,ed60_c_situacao,ed60_i_aluno,ed60_i_numaluno,ed47_v_nome","ed47_v_nome"," ed60_i_turma = $ed57_i_codigo");
  $result4 = $clmatricula->sql_record($sql4);
  $cor1 = 0;
  $cor2 = 1;
  $cor = "";
  $cont4 = 0;
  $limite = 41;
  for($z=0;$z<$clmatricula->numrows;$z++){
   db_fieldsmemory($result4,$z);
   if($cor==$cor1){
    $cor = $cor2;
   }else{
    $cor = $cor1;
   }
   $pdf->cell(5,4,$ed60_i_numaluno,"LR",0,"C",$cor);
   $pdf->cell(55,4,$ed47_v_nome,"LR",0,"L",$cor);
   $sql5 = "SELECT ed74_c_valoraprov,ed81_c_todoperiodo,ed37_c_tipo
            FROM diariofinal
             inner join diario on ed95_i_codigo = ed74_i_diario
             left join amparo on ed81_i_diario = ed95_i_codigo
             inner join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov
             inner join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed95_i_regencia in ($reg_pagina)
            ORDER BY ed95_i_regencia
          ";
   $result5 = pg_query($sql5);
   $linhas5 = pg_num_rows($result5);
   $cont3 = 0;
   if($linhas5>0){
    for($v=0;$v<$linhas5;$v++){
     db_fieldsmemory($result5,$v);
     if(trim($ed60_c_situacao)!="MATRICULADO"){
      //$aproveitamento = trim($ed60_c_situacao);
     }else{
      if(trim($ed81_c_todoperiodo)=="S"){
       $aproveitamento = "AMPARADO";
      }else{
       if(trim($ed37_c_tipo)=="NOTA"){
        $aproveitamento = number_format($ed74_c_valoraprov,2,".",".");
       }elseif(trim($ed37_c_tipo)=="PARECER"){
        $aproveitamento = "Parecer";
       }else{
        $aproveitamento = $ed74_c_valoraprov;
       }
      }
     }
     $pdf->cell(15,4,$aproveitamento,"LR",0,"C",$cor);
     $cont3++;
    }
   }else{
    $pdf->cell(15,4,substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5),"LR",0,"C",$cor);
    $cont3++;
   }
   for($y=$cont3;$y<8;$y++){
    $pdf->cell(15,4,"","LR",0,"C",$cor);
   }
   $sql6 = "SELECT ed95_i_codigo
            FROM diario
             inner join aluno on ed47_i_codigo = ed95_i_aluno
             inner join diariofinal on ed74_i_diario = ed95_i_codigo
            WHERE ed95_i_aluno = $ed60_i_aluno
            AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo)
            AND ed74_c_resultadofinal = 'R'
           ";
   $result6 = pg_query($sql6);
   $linhas6 = pg_num_rows($result6);
   //db_criatabela($result4);
   if($linhas6==0){
    $rf = "APR";
   }else{
    $rf = "REP";
   }
   $pdf->cell(10,4,$rf,"LR",1,"C",$cor);
   $pdf->line(10,52,200,52);
   if($cont4==$limite){
    //inicio rodape
    $pdf->cell(100,4,"Convenções",1,0,"C",0);
    $pdf->cell(90,4,"Observações",1,1,"C",0);
    $alt_conv = $pdf->getY();
    $cont5 = 0;
    $borda = "L";
    $quebra = "0";
    $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr","ed59_i_codigo"," ed59_i_codigo in ($reg_pagina)");
    $result2 = $clregencia->sql_record($sql2);
    for($y=0;$y<$clregencia->numrows;$y++){
     db_fieldsmemory($result2,$y);
     $pdf->cell(50,4,$ed232_c_abrev." - ".$ed232_c_descr,$borda,$quebra,"L",0);
     if(($y%2)==0){
      $borda = "R";
      $quebra = "1";
     }else{
      $borda = "L";
      $quebra = "0";
     }
     $cont5++;
    }
    if($quebra=="1"){
     $pdf->cell(50,4,"","R",1,"L",0);
     $cont5++;
    }
    for($y=($cont5/2);$y<12;$y++){
     $pdf->cell(100,4,"","LR",1,"L",0);
    }
    $pdf->setY($alt_conv);
    $pdf->setX(110);
    $pdf->cell(90,20,"","LBR",2,"L",0);
    $pdf->cell(90,4,"E, para constar, foi lavrada esta ata.","LR",2,"C",0);
    $pdf->cell(90,4,"Alegrete, ".date("d",db_getsession("DB_datausu"))." de ".db_mes(date("m",db_getsession("DB_datausu")))." de ".date("Y",db_getsession("DB_datausu")),"LR",2,"C",0);
    $pdf->cell(90,8,"","LR",2,"L",0);
    $pdf->cell(45,4,"_______________________","L",0,"C",0);
    $pdf->cell(45,4,"_______________________","R",1,"C",0);
    $pdf->setX(110);
    $pdf->cell(45,4,"SECRETÁRIO(A)","L",0,"C",0);
    $pdf->cell(45,4,"DIRETOR(A)","R",1,"C",0);
    $pdf->setX(110);
    $pdf->cell(90,4,"","LR",1,"C",0);
    $pdf->cell(190,2,"",1,1,"C",0);
    //fim rodape
    $pdf->addpage('P');
    $pdf->ln(5);
    //inicio cabeçalho
    $pdf->setfont('arial','b',7);
    $inicio = $pdf->getY();
    $pdf->cell(5,4,"","LRT",0,"C",0);
    $pdf->cell(55,4,"Disciplinas","LRT",0,"R",0);
    $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr","ed59_i_codigo"," ed59_i_codigo in ($reg_pagina)");
    $result2 = $clregencia->sql_record($sql2);
    $cont = 0;
    $reg_pagina = 0;
    $sep = "";
    for($y=0;$y<$clregencia->numrows;$y++){
     db_fieldsmemory($result2,$y);
     if($y<8){
      $pdf->cell(15,4,$ed232_c_abrev,"LRT",0,"C",0);
      $cont++;
      $reg_pagina .= $sep.$ed59_i_codigo;
      $sep = ",";
     }
    }
    for($y=$cont;$y<8;$y++){
     $pdf->cell(15,4,"","LRT",0,"C",0);
    }
    $pdf->cell(10,8,"RF",1,1,"C",0);
    $pdf-> setY($inicio+4);
    $pdf->cell(5,4,"","LRB",0,"C",0);
    $pdf->cell(55,4,"Carga Horária","LRB",0,"R",0);
    $cont1= 0;
    for($y=0;$y<$clregencia->numrows;$y++){
     db_fieldsmemory($result2,$y);
     $sql3= $clregenciaperiodo->sql_query("","sum(ed78_i_aulasdadas) as aulas",""," ed78_i_regencia = $ed59_i_codigo");
     $result3 = $clregenciaperiodo->sql_record($sql3);
     db_fieldsmemory($result3,0);
     if($y<8){
      $pdf->cell(15,4,$aulas==""?"0":$aulas,"LRB",0,"C",0);
      $cont1++;
     }
    }
    for($y=$cont1;$y<8;$y++){
     $pdf->cell(15,4,"","LRB",0,"C",0);
    }
    $pdf->cell(10,4,"",0,1,"C",0);
    $pdf->cell(5,4,"N°",1,0,"C",0);
    $pdf->cell(55,4,"Nome do Aluno",1,0,"C",0);
    $cont2 = 0;
    for($y=0;$y<$clregencia->numrows;$y++){
     if($y<8){
      $pdf->cell(15,4,"Aprov",1,0,"C",0);
      $cont2++;
     }
    }
    for($y=$cont2;$y<8;$y++){
     $pdf->cell(15,4,"",1,0,"C",0);
    }
    $pdf->cell(10,4,"",1,1,"C",0);
    //fim cabeçalho
    $cont4 = -1;
   }
   $cont4++;
  }
  for($z=$cont4;$z<$limite+1;$z++){
   $pdf->cell(5,4,"","LR",0,"C",0);
   $pdf->cell(55,4,"","LR",0,"L",0);
   for($t=0;$t<8;$t++){
    $pdf->cell(15,4,"","LR",0,"C",0);
   }
   $pdf->cell(10,4,"","LR",1,"C",0);
  }
  //inicio rodape
  $pdf->cell(100,4,"Convenções",1,0,"C",0);
  $pdf->cell(90,4,"Observações",1,1,"C",0);
  $alt_conv = $pdf->getY();
  $cont5 = 0;
  $borda = "L";
  $quebra = "0";
  $sql2= $clregencia->sql_query("","ed59_i_codigo,ed232_c_abrev,ed232_c_descr","ed59_i_codigo"," ed59_i_codigo in ($reg_pagina)");
  $result2 = $clregencia->sql_record($sql2);
  for($y=0;$y<$clregencia->numrows;$y++){
   db_fieldsmemory($result2,$y);
   $pdf->cell(50,4,$ed232_c_abrev." - ".$ed232_c_descr,$borda,$quebra,"L",0);
   if(($y%2)==0){
    $borda = "R";
    $quebra = "1";
   }else{
    $borda = "L";
    $quebra = "0";
   }
   $cont5++;
  }
  if($quebra=="1"){
   $pdf->cell(50,4,"","R",1,"L",0);
   $cont5++;
  }
  for($y=($cont5/2);$y<12;$y++){
   $pdf->cell(100,4,"","LR",1,"L",0);
  }
  $pdf->setY($alt_conv);
  $pdf->setX(110);
  $pdf->cell(90,20,"","LBR",2,"L",0);
  $pdf->cell(90,4,"E, para constar, foi lavrada esta ata.","LR",2,"C",0);
  $pdf->cell(90,4,"Alegrete, ".date("d",db_getsession("DB_datausu"))." de ".db_mes(date("m",db_getsession("DB_datausu")))." de ".date("Y",db_getsession("DB_datausu")),"LR",2,"C",0);
  $pdf->cell(90,8,"","LR",2,"L",0);
  $pdf->cell(45,4,"_______________________","L",0,"C",0);
  $pdf->cell(45,4,"_______________________","R",1,"C",0);
  $pdf->setX(110);
  $pdf->cell(45,4,"SECRETÁRIO(A)","L",0,"C",0);
  $pdf->cell(45,4,"DIRETOR(A)","R",1,"C",0);
  $pdf->setX(110);
  $pdf->cell(90,4,"","LR",1,"C",0);
  $pdf->cell(190,2,"",1,1,"C",0);
  //fim rodape
 }
}
$pdf->Output();
?>
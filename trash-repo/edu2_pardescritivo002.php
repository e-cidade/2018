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
include("classes/db_matricula_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_diarioresultado_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_procresultado_classe.php");
include("classes/db_procavaliacao_classe.php");
include("classes/db_regenciaperiodo_classe.php");
$cldiarioresultado = new cl_diarioresultado;
$cldiarioavaliacao = new cl_diarioavaliacao;
$clprocresultado = new cl_procresultado;
$clprocavaliacao = new cl_procavaliacao;
$clregenciaperiodo = new cl_regenciaperiodo;
$clmatricula = new cl_matricula;
$clregencia = new cl_regencia;
$clescola = new cl_escola;
$escola = db_getsession("DB_coddepto");
$sql = $clmatricula->sql_query("","*","ed47_v_nome"," ed60_i_aluno in ($alunos) AND ed60_i_turma = $turma");
$result = $clmatricula->sql_record($sql);
$periodo = explode("|",$periodo);
//db_criatabela($result);
//exit;
if($clmatricula->numrows==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhuma matrícula para a turma selecionada<br>
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
for($x=0;$x<$clmatricula->numrows;$x++){
 db_fieldsmemory($result,$x);
 if($periodo[0]=="A"){
  $tp_per = "A";
  $sql1 = $clprocavaliacao->sql_query("","ed09_c_descr as per_descr",""," ed41_i_codigo = $periodo[1]");
  $result1 = $clprocavaliacao->sql_record($sql1);
  db_fieldsmemory($result1,0);
  $campos = "ed72_i_codigo as codaval,ed72_t_parecer as parecer,ed72_c_amparo as amparoum,ed93_i_codigo as codparecer,ed81_c_todoperiodo as amparo,ed92_c_descr,ed91_c_descr,ed06_c_descr as justificativa,ed92_i_sequencial";
  $tabela = "diarioavaliacao";
  $ligacao = "left join pareceraval on ed93_i_diarioavaliacao = ed72_i_codigo
              left join parecer on ed92_i_codigo = ed93_i_parecer
              left join parecerlegenda on ed91_i_codigo = ed93_i_parecerlegenda
             ";
  $where = "ed72_i_procavaliacao";
  $join = "ed72_i_diario";
 }else{
  $tp_per = "R";
  $sql1 = $clprocresultado->sql_query("","ed42_c_descr as per_descr",""," ed43_i_codigo = $periodo[1]");
  $result1 = $clprocresultado->sql_record($sql1);
  db_fieldsmemory($result1,0);
  $campos = "ed73_i_codigo as codaval,ed73_t_parecer as parecer,ed63_i_codigo as codparecer,ed92_c_descr,ed91_c_descr,ed92_i_sequencial";
  $tabela = "diarioresultado";
  $ligacao = "left join parecerresult on ed63_i_diarioresultado = ed73_i_codigo
              left join parecer on ed92_i_codigo = ed63_i_parecer
              left join parecerlegenda on ed91_i_codigo = ed63_i_parecerlegenda
             ";
  $where = "ed73_i_procresultado";
  $join = "ed73_i_diario";
 }
 $pdf->setfillcolor(223);
 $head1 = "BOLETIM POR PARECER DESCRITIVO";
 $head2 = "Aluno: $ed47_v_nome";
 $head3 = "Curso: $ed29_i_codigo - $ed29_c_descr";
 $head4 = "Calendário: $ed52_c_descr";
 $head5 = "Período: $per_descr";
 $head6 = "Série: $ed11_c_descr";
 $head7 = "Turma: $ed57_c_descr";
 $head8 = "Matrícula: $ed60_i_codigo";
 if($punico=="yes"){
  $order = "ed232_c_descr LIMIT 1";
 }else{
  $order = "ed232_c_descr";
 }
 $result2 = $clregencia->sql_record($clregencia->sql_query("","*",$order," ed59_i_codigo in ($disciplinas) "));
 $linhas2 = $clregencia->numrows;
 for($y=0;$y<$linhas2;$y++){
  db_fieldsmemory($result2,$y);
  $pdf->addpage('P');
  $pdf->ln(5);
  $pdf->setfont('arial','b',7);
  if($punico=="yes"){
   $titulo = "";
  }else{
   $titulo = "Disciplina: $ed232_c_descr";
  }
  $pdf->cell(190,4,$titulo,1,1,"L",1);
  $sql3 = "SELECT $campos
           FROM $tabela
            inner join diario on ed95_i_codigo = $join
            left join amparo on ed81_i_diario = ed95_i_codigo
            left join justificativa on ed06_i_codigo = ed81_i_justificativa
            left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp
            $ligacao
           WHERE ed95_i_aluno = $ed60_i_aluno
           AND ed95_i_regencia = $ed59_i_codigo
           AND $where = $periodo[1]
           ORDER BY ed92_i_sequencial
         ";
  $result3 = pg_query($sql3);
  $linhas3 = pg_num_rows($result3);
  if($linhas3>0){
   if($tp_per=="A"){
    $amparo = pg_result($result3,0,'amparo');
    $amparoum = pg_result($result3,0,'amparoum');
   }else{
    $amparo = "N";
    $amparoum = "N";
   }
   if(($amparo=="N" || $amparo=="") && ($amparoum=="N" || $amparoum=="")){
    if($periodo[0]=="A"){
     if($ed89_i_codigo!=""){
      $sql_d = $clregencia->sql_query("","ed59_i_codigo as discglob",""," ed57_i_codigo = $ed57_i_codigo AND ed12_i_codigo = $ed89_i_disciplina");
      $result_d = $clregencia->sql_record($sql_d);
      db_fieldsmemory($result_d,0);
      $sql4 = $clregenciaperiodo->sql_query("","ed78_i_aulasdadas",""," ed78_i_regencia = $discglob AND ed78_i_procavaliacao = $periodo[1]");
      $result4 = $clregenciaperiodo->sql_record($sql4);
      db_fieldsmemory($result4,0);
     }else{
      $sql4 = $clregenciaperiodo->sql_query_file("","ed78_i_aulasdadas",""," ed78_i_regencia = $ed59_i_codigo AND ed78_i_procavaliacao = $periodo[1]");
      $result4 = $clregenciaperiodo->sql_record($sql4);
      db_fieldsmemory($result4,0);
     }
     $pdf->cell(190,4,"","LR",1,"L",0);
     $pdf->cell(10,4,"","L",0,"L",0);
     $pdf->cell(170,4,"Aulas Dadas: $ed78_i_aulasdadas",0,0,"L",0);
     $pdf->cell(10,4,"","R",1,"L",0);
     if($ed89_i_codigo!=""){
      $sql5 = $cldiarioavaliacao->sql_query("","ed72_i_numfaltas as fper",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $discglob AND ed72_i_procavaliacao = $periodo[1]");
      $result5 = $cldiarioavaliacao->sql_record($sql5);
      db_fieldsmemory($result5,0);
     }else{
      $sql5 = $cldiarioavaliacao->sql_query("","ed72_i_numfaltas as fper",""," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed72_i_procavaliacao = $periodo[1]");
      $result5 = $cldiarioavaliacao->sql_record($sql5);
      db_fieldsmemory($result5,0);
     }
     $fper = $fper==""?"0":$fper;
     $faltas = "N° de Faltas: $fper";
    }else{
     $faltas = "";
    }
    $pdf->cell(10,4,"","L",0,"L",0);
    $pdf->cell(170,4,$faltas,0,0,"L",0);
    $pdf->cell(10,4,"","R",1,"L",0);
    $pdf->cell(190,4,"","LR",1,"L",0);
    $pdf->cell(5,4,"","LR",0,"L",0);
    $pdf->cell(20,4,"Sequencial","LTB",0,"L",0);
    $pdf->cell(135,4,"Parecer","LTB",0,"L",0);
    $pdf->cell(25,4,"Aproveitamento","RTB",0,"L",0);
    $pdf->cell(5,4,"","LR",1,"L",0);
    $linha = $pdf->getY();
    if(pg_result($result3,0,'codparecer')!=""){
     $cor1 = 0;
     $cor2 = 1;
     $cor = "";
     for($z=0;$z<$linhas3;$z++){
      db_fieldsmemory($result3,$z);
      if($cor==$cor1){
       $cor = $cor2;
      }else{
       $cor = $cor1;
      }
      $pdf->setfont('arial','',7);
      $pdf->cell(5,4,"","LR",0,"L",0);
      $pdf->cell(20,4,$ed92_i_sequencial,"LR",0,"L",$cor);
      $pdf->cell(135,4,$ed92_c_descr,"LR",0,"L",$cor);
      $pdf->cell(25,4,$ed91_c_descr,"LR",0,"L",$cor);
      $pdf->cell(5,4,"","LR",1,"L",0);
     }
     $pdf->line(15,$pdf->getY(),195,$pdf->getY());
     $pdf->line(15,$linha,195,$linha);
     $pdf->cell(10,4,"","L",0,"L",0);
     $pdf->cell(170,4,"",0,0,"L",0);
     $pdf->cell(10,4,"","R",1,"L",0);
    }else{
     $pdf->cell(5,4,"","LR",0,"L",0);
     $pdf->cell(180,4,"Nenhum parecer padronizado cadastrado para este aluno.","LR",0,"L",0);
     $pdf->cell(5,4,"","LR",1,"L",0);
     $pdf->line(15,$pdf->getY(),195,$pdf->getY());
     $pdf->cell(10,4,"","L",0,"L",0);
     $pdf->cell(170,4,"",0,0,"L",0);
     $pdf->cell(10,4,"","R",1,"L",0);
    }
    $pdf->setfont('arial','b',7);
    $pdf->cell(5,4,"","L",0,"L",0);
    $pdf->cell(180,4,"Parecer Descritivo:",0,0,"L",0);
    $pdf->cell(5,4,"","R",1,"L",0);
    $pdf->cell(190,2,"","LR",1,"L",0);
    $pdf->setfont('arial','',7);
    $pdf->multicell(190,3,"       ".pg_result($result3,0,'parecer'),"RL","J",0,0);
    $pdf->cell(190,4,"","LR",1,"L",0);
    $completar = 260-$pdf->getY();
    $pdf->cell(190,$completar,"","LR",1,"L",0);
   }else{
    $pdf->cell(190,4,"","LR",1,"L",0);
    $pdf->cell(10,4,"","L",0,"L",0);
    $pdf->cell(170,4,"Aluno possui amparo para esta discilpina neste período",0,0,"L",0);
    $pdf->cell(10,4,"","R",1,"L",0);
    $pdf->cell(10,4,"","L",0,"L",0);
    $pdf->cell(170,4,"Justificativa Legal: ".pg_result($result3,0,'justificativa'),0,0,"L",0);
    $pdf->cell(10,4,"","R",1,"L",0);
    $completar = 260-$pdf->getY();
    $pdf->cell(190,$completar,"","LR",1,"L",0);
   }
  }else{
   $pdf->cell(190,4,"","LR",1,"L",0);
   $completar = 260-$pdf->getY();
   $pdf->cell(190,$completar,"","LR",1,"L",0);
  }
  $pdf->line(10,$pdf->getY(),200,$pdf->getY());
  $pdf->cell(190,4,"",0,1,"C",0);
  $pdf->cell(190,4,"__________________________________________________",0,1,"C",0);
  $pdf->cell(190,4,"Assinatura do Regente",0,1,"C",0);
 }
}
$pdf->Output();
?>
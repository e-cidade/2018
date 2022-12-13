<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
$resultedu         = eduparametros(db_getsession("DB_coddepto"));
$clturma           = new cl_turma;
$clmatricula       = new cl_matricula;
$clregencia        = new cl_regencia;
$clregenciaperiodo = new cl_regenciaperiodo;
$clregenteconselho = new cl_regenteconselho;
$claprovconselho   = new cl_aprovconselho;

$sCampos  = "distinct                                   \n";
$sCampos .= "ed52_i_ano, ed57_c_descr, ed29_i_codigo,   \n";
$sCampos .= "ed29_c_descr, ed52_c_descr, ed11_c_descr,  \n";
$sCampos .= "ed15_c_nome, ed57_i_codigo, ed223_i_serie, \n";
$sCampos .= "ed52_d_resultfinal                         \n";

$result            = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                         $sCampos,
                                                                         "ed57_c_descr",
                                                                         " ed220_i_codigo in ($turmas)"
                                                                        )
                                         );
if ($clturma->numrows == 0) { ?>

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
$pdf->SetAutoPageBreak(false);
$linhas = $clturma->numrows;
for ($x = 0; $x < $linhas; $x++) {

  db_fieldsmemory($result,$x);
  $obs_cons    = "";
  $sCampos     = "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome,ed253_i_data,ed232_c_descr as disc_conselho,ed253_t_obs,ed47_v_nome,ed59_i_ordenacao";
  $sWhere      = "ed59_i_turma = $ed57_i_codigo AND ed59_i_serie = $ed223_i_serie";
  $result_cons = $claprovconselho->sql_record($claprovconselho->sql_query("",
                                                                          $sCampos,
                                                                          "ed59_i_ordenacao",
                                                                          $sWhere
                                                                         )
                                             );
  $sWhere      = " ed78_i_regencia in (select ed59_i_codigo from regencia where ";
  $sWhere     .= " ed59_i_turma = $ed57_i_codigo AND ed59_i_serie = $ed223_i_serie)";
  $result1     = $clregenciaperiodo->sql_record($clregenciaperiodo->sql_query("",
                                                                              "sum(ed78_i_aulasdadas) as aulas",
                                                                              "",
                                                                              $sWhere
                                                                             )
                                               );
  db_fieldsmemory($result1,0);
  $result5 = $clregenteconselho->sql_record($clregenteconselho->sql_query("",
                                                                          "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as regente",
                                                                          "",
                                                                          " ed235_i_turma = $ed57_i_codigo"
                                                                         )
                                           );
  if ($clregenteconselho->numrows > 0) {
    db_fieldsmemory($result5,0);
  } else {
    $regente = "";
  }
  $pdf->setfillcolor(223);
  $dia   = substr($ed52_d_resultfinal,8,2);
  $mes   = db_mes(substr($ed52_d_resultfinal,5,2));
  $ano   = substr($ed52_d_resultfinal,0,4);
  $head1 = "QUADRO DE RESULTADOS FINAIS";
  $head2 = "Curso: $ed29_c_descr";
  $head3 = "Calendário: $ed52_c_descr";
  $head4 = "Etapa: $ed11_c_descr";
  $head5 = "Ano: $ed52_i_ano";
  $head6 = "C.H. Total: $aulas";
  $head7 = "Turma: $ed57_c_descr";
  $head8 = "Regente: $regente";
  $pdf->addpage('L');
  $pdf->setfont('arial','b',7);
  $inicio = $pdf->getY();
  $pdf->cell(5,4,"","LRT",0,"C",0);
  $pdf->cell(65,4,"Disciplinas","LRT",0,"R",0);
  $sWhere     = " ed59_i_turma = $ed57_i_codigo AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
  $sql2       = $clregencia->sql_query("",
                                       "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                       "ed59_i_ordenacao,ed59_i_codigo",
                                       $sWhere
                                      );
  $result2    = $clregencia->sql_record($sql2);
  $cont       = 0;
  $reg_pagina = 0;
  $sep        = "";
  for ($y = 0; $y < $clregencia->numrows; $y++) {

    db_fieldsmemory($result2,$y);
    if ($y < 9) {

      $pdf->cell(22,4,$ed232_c_abrev,"LRT",0,"C",0);
      $cont++;
      $reg_pagina .= $sep.$ed59_i_codigo;
      $sep         = ",";

    }
  }
  for ($y = $cont; $y < 9; $y++) {
    $pdf->cell(22,4,"","LRT",0,"C",0);
 }
 $pdf->cell(10,4,"",1,1,"C",0);
 $pdf->cell(5,4,"N°",1,0,"C",0);
 $pdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
 $cont2 = 0;
 for ($y = 0; $y < $clregencia->numrows; $y++) {

   if ($y < 9) {

     $pdf->cell(12,4,"Aprov",1,0,"C",0);
     $pdf->cell(10,4,"% Freq",1,0,"C",0);
     $cont2++;

  }
 }
 for ($y = $cont2; $y < 9; $y++) {

   $pdf->cell(12,4,"",1,0,"C",0);
   $pdf->cell(10,4,"",1,0,"C",0);

 }
 $pdf->cell(10,4,"RF",1,1,"C",0);
 $sCampos = "ed60_i_codigo, ed60_c_parecer, ed60_c_situacao, ed60_i_aluno, ed60_i_numaluno, ed47_v_nome";
 $sql4    = $clmatricula->sql_query("",
                                    $sCampos,
                                    "ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa",
                                    " ed60_i_turma = $ed57_i_codigo AND ed221_i_serie = $ed223_i_serie"
                                   );
 $result4 = $clmatricula->sql_record($sql4);
 $cor1    = 0;
 $cor2    = 0;
 $cor     = "";
 $cont4   = 0;
 if ($claprovconselho->numrows == 0) {
   $limite = 35;
 } else {
   $limite = 33;
 }
 $cont_geral = 0;
 for ($z = 0; $z < $clmatricula->numrows; $z++) {

   db_fieldsmemory($result4,$z);

   if ($trocaTurma == 1 && $ed60_c_situacao == 'TROCA DE TURMA') {
     continue;
   }
   if ($cor == $cor1) {
     $cor = $cor2;
   } else {
     $cor = $cor1;
   }
   $pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",$cor);
   $pdf->cell(65,4,$ed47_v_nome,1,0,"L",$cor);
   $sql5    = "SELECT ed74_c_valoraprov,ed74_i_percfreq,ed81_c_todoperiodo,ed37_c_tipo,ed59_c_freqglob,ed89_i_disciplina,";
   $sql5   .= "       ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev, ed74_c_resultadofreq, ed11_i_ensino, ed95_i_codigo, ed95_i_regencia ";
   $sql5   .= "       FROM diariofinal ";
   $sql5   .= "       inner join diario on ed95_i_codigo = ed74_i_diario ";
   $sql5   .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
   $sql5   .= "       inner join serie on ed11_i_codigo = ed59_i_serie ";
   $sql5   .= "       inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
   $sql5   .= "       inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
   $sql5   .= "       inner join turma on turma.ed57_i_codigo = regencia.ed59_i_turma ";
   $sql5   .= "       inner join base  on  base.ed31_i_codigo = turma.ed57_i_base ";
   $sql5   .= "        left join basediscglob  on  basediscglob.ed89_i_codigo = base.ed31_i_codigo ";
   $sql5   .= "        left join amparo on ed81_i_diario = ed95_i_codigo ";
   $sql5   .= "        left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
   $sql5   .= "        left join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov ";
   $sql5   .= "        left join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao ";
   $sql5   .= "       WHERE ed95_i_aluno = $ed60_i_aluno ";
   $sql5   .= "       AND ed95_i_regencia in ($reg_pagina) ";
   $sql5   .= "   AND ed59_c_condicao    = 'OB' ";
   $sql5   .= "       ORDER BY ed59_i_ordenacao,ed59_i_codigo ";
   $result5 = db_query($sql5);
   $linhas5 = pg_num_rows($result5);
   $cont3   = 0;
   if ($linhas5 > 0) {

     for ($v = 0; $v < $linhas5; $v++) {

       db_fieldsmemory($result5,$v);

       //Total de aulas dadas em todos os bimestres
       $rsTotalAulasDadas = db_query("select sum(ed78_i_aulasdadas) as iTotalAulasDadas from regenciaperiodo where ed78_i_regencia = {$ed95_i_regencia}");
       db_fieldsmemory($rsTotalAulasDadas, 0, false, false);

       //Total de faltas em todos os bimestres
       $rsTotalFaltas = db_query("select sum(ed72_i_numfaltas) as iTotalFaltas from diarioavaliacao where ed72_i_diario = {$ed95_i_codigo}");
       db_fieldsmemory($rsTotalFaltas, 0, false, false);

       //Média de frequência em todos bimestres
       $frequencia = $itotalaulasdadas > 0 ? (($itotalaulasdadas - $itotalfaltas) * 100) / $itotalaulasdadas : "";
       $ed74_i_percfreq = $frequencia;

       if ($ed60_c_parecer == "S") {
         $ed37_c_tipo = "PARECER";
       }
       if (trim($ed60_c_situacao) != "MATRICULADO") {

         $aproveitamento = substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5);
         $frequencia = "";

       } else {

         if (trim($ed81_c_todoperiodo) == "S") {

           if ($ed81_i_justificativa != "") {
             $aproveitamento = "AMP.";
           } else {
             $aproveitamento = $ed250_c_abrev;
           }
           $frequencia = "";

         } else {

           if (trim($ed59_c_freqglob) == "F") {

             $aproveitamento = "-";
             if ($resultedu == 'S') {
               $frequencia = ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano);
             } else {
               $frequencia = ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano);
             }
           } else if (trim($ed59_c_freqglob) == "A") {

             if (trim($ed37_c_tipo) == "NOTA") {

               if ($resultedu == 'S') {
                 $aproveitamento = ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano);
               } else {
                 $aproveitamento = ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano);
             }

             } else if (trim($ed37_c_tipo) == "PARECER") {
               $aproveitamento = "Parecer";
             } else {
               $aproveitamento = $ed74_c_valoraprov;
             }

             /*
             $sql_f    = "SELECT ed74_i_percfreq ";
             $sql_f   .= "   FROM diariofinal ";
             $sql_f   .= "    inner join diario on ed95_i_codigo = ed74_i_diario ";
             $sql_f   .= "    inner join regencia on ed59_i_codigo = ed95_i_regencia ";
             $sql_f   .= "    inner join turma on ed57_i_codigo = ed59_i_turma ";
             $sql_f   .= "   WHERE ed57_i_codigo = $ed57_i_codigo ";
             $sql_f   .= "   AND ed59_c_freqglob = 'F' ";
             $sql_f   .= "   AND ed95_i_aluno = $ed60_i_aluno ";
             $sql_f   .= "   AND ed95_i_regencia = $ed59_i_codigo ";
             $result_f = db_query($sql_f);
             $linhas_f = pg_num_rows($result_f);
             if ($resultedu == 'S') {
               $frequencia = number_format((float) pg_result($result_f,0,'ed74_i_percfreq'),2,".",".");
             } else {
               $frequencia = number_format((float) pg_result($result_f,0,'ed74_i_percfreq'),0);
             }
             */

           } else {

             if (trim($ed37_c_tipo) == "NOTA") {

               if ($resultedu == 'S') {
                 $aproveitamento = ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano);
               } else {
                 $aproveitamento = ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano);
               }

             } else if (trim($ed37_c_tipo) == "PARECER") {
               $aproveitamento = "Parecer";
             } else {
               $aproveitamento = $ed74_c_valoraprov;
             }

             $frequencia = '';
             if (isset($ed74_i_percfreq) && !empty($ed74_i_percfreq)) {

               if ($resultedu == 'S') {
                 $frequencia = ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano);
               } else {
                 $frequencia = ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano);
               }
             }
           }
         }
       }
       $pdf->setfont('arial','',9);
       $pdf->cell(12,4,$aproveitamento,1,0,"C",$cor);
       $pdf->cell(10,4,$frequencia,1,0,"C",$cor);
       $pdf->setfont('arial','b',7);
       $cont3++;
     }
   } else {

     $pdf->setfont('arial','b',7);
     $pdf->cell(12,4,substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5),1,0,"C",$cor);
     $pdf->cell(10,4,"",1,0,"C",$cor);
     $pdf->setfont('arial','',9);
     $cont3++;

   }
   for ($y = $cont3; $y < 9; $y++) {

     $pdf->cell(12,4,"",1,0,"C",$cor);
     $pdf->cell(10,4,"",1,0,"C",$cor);

   }
   $sql6    = " SELECT ed95_i_codigo ";
   $sql6   .= "       FROM diario ";
   $sql6   .= "        inner join aluno on ed47_i_codigo = ed95_i_aluno ";
   $sql6   .= "        inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
   $sql6   .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
   $sql6   .= "       WHERE ed95_i_aluno = $ed60_i_aluno ";
   $sql6   .= "       AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo ";
   $sql6   .= "                                                                    AND ed59_i_serie = $ed223_i_serie) ";
   $sql6   .= "       AND ed59_c_condicao = 'OB' ";
   $sql6   .= "       AND ed74_c_resultadofinal != 'A' ";
   $result6 = db_query($sql6);
   $linhas6 = pg_num_rows($result6);
   if (trim($ed60_c_situacao) != "MATRICULADO" || $linhas5 == 0) {
     $rf = "";
   } else {

    $oTurma               = TurmaRepository::getTurmaByCodigo( $ed57_i_codigo );
    $iAnoCalendario       = $oTurma->getCalendario()->getAnoExecucao();
    $aDadosTermoAprovado  = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, 'A', $iAnoCalendario);
    $aDadosTermoReprovado = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, 'R', $iAnoCalendario);

     if ($linhas6 == 0) {

       $rf = "APR";
       if (isset($aDadosTermoAprovado[0])) {
         $rf = $aDadosTermoAprovado[0]->sAbreviatura;
       }
     } else {

       $rf = "REP";
       if (isset($aDadosTermoReprovado[0])) {
         $rf = $aDadosTermoReprovado[0]->sAbreviatura;
       }
     }
     if ($ed74_c_valoraprov == "") {

       if (trim($ed59_c_freqglob) == "F") {

         $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, $ed74_c_resultadofreq, $iAnoCalendario);
         if (isset($aDadosTermo[0])) {
           $rf = $aDadosTermo[0]->sAbreviatura;
         }
       } else {
         $rf = "";
       }
     }
   }
   $pdf->cell(10,4,$rf,1,1,"C",$cor);
   $pdf->line(10,43,288,43);
   if ($cont4 == $limite && ($cont_geral+1) < $clmatricula->numrows) {

     $pdf->setfont('arial','b',6);
     $alt_conv = $pdf->getY();
     $cont5    = 0;
     $quebra   = "0";
     $sWhere   = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
     $sql2     = $clregencia->sql_query("",
                                        "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                        "ed59_i_ordenacao",
                                        $sWhere
                                       );
     $result2  = $clregencia->sql_record($sql2);
     for ($y = 0; $y < $clregencia->numrows; $y++) {

       db_fieldsmemory($result2,$y);
       $pdf->cell(30,4,$ed232_c_abrev." - ".substr($ed232_c_descr,0,15),0,0,"L",0);

     }
     $pdf->setY($alt_conv);
     $pdf->setX(10);
     $pdf->cell(278,4,"",1,1,"L",0);
     if ($claprovconselho->numrows > 0) {

       $pdf->cell(278,4,"Observações",1,1,"C",0);
       $sepobs = "";
       for ($g = 0; $g < $claprovconselho->numrows; $g++) {

         db_fieldsmemory($result_cons,$g);
         $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs - Responsável: $z01_nome";
         $sepobs = "\n";

       }
       $pdf->multicell(278,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
     }
     $pdf->addpage('L');
     $pdf->setfont('arial','b',7);
     $inicio = $pdf->getY();
     $pdf->cell(5,4,"","LRT",0,"C",0);
     $pdf->cell(65,4,"Disciplinas","LRT",0,"R",0);
     $sWhere     = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
     $sql2       = $clregencia->sql_query("",
                                          "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                          "ed59_i_ordenacao",
                                          $sWhere
                                         );
     $result2    = $clregencia->sql_record($sql2);
     $cont       = 0;
     $reg_pagina = 0;
     $sep        = "";
     for ($y = 0; $y < $clregencia->numrows; $y++) {

       db_fieldsmemory($result2,$y);
       if ($y < 9) {

         $pdf->cell(22,4,$ed232_c_abrev,"LRT",0,"C",0);
         $cont++;
         $reg_pagina .= $sep.$ed59_i_codigo;
         $sep         = ",";

       }
     }
     for ($y = $cont; $y < 9; $y++) {
       $pdf->cell(22,4,"","LRT",0,"C",0);
     }
     $pdf->cell(10,4,"RF",1,1,"C",0);
     $pdf->cell(5,4,"N°",1,0,"C",0);
     $pdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
     $cont2 = 0;
     for ($y = 0; $y < $clregencia->numrows; $y++) {

       if ($y < 9) {

         $pdf->cell(12,4,"Aprov",1,0,"C",0);
         $pdf->cell(10,4,"% Freq",1,0,"C",0);
         $cont2++;

       }
     }
     for ($y = $cont2; $y < 9; $y++) {

       $pdf->cell(12,4,"",1,0,"C",0);
       $pdf->cell(10,4,"",1,0,"C",0);

     }
     $pdf->cell(10,4,"",1,1,"C",0);
     $cont4 = -1;
   }
   $cont4++;
   $cont_geral++;
 }
 for ($z = $cont4; $z < $limite; $z++) {

   $pdf->cell(5,4,"",1,0,"C",0);
   $pdf->cell(65,4,"",1,0,"L",0);
   for ($t = 0; $t < 9; $t++) {

     $pdf->cell(12,4,"",1,0,"C",0);
     $pdf->cell(10,4,"",1,0,"C",0);

   }
   $pdf->cell(10,4,"",1,1,"C",0);
 }
 $alt_conv = $pdf->getY();
 $cont5   = 0;
 $quebra  = "0";
 $sWhere  = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
 $sql2    = $clregencia->sql_query("",
                                   "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                   "ed59_i_ordenacao",
                                   $sWhere
                                  );
 $result2 = $clregencia->sql_record($sql2);
 for ($y = 0; $y < $clregencia->numrows; $y++) {

   db_fieldsmemory($result2,$y);
   $pdf->cell(30.9,4,$ed232_c_abrev." - ".substr($ed232_c_descr,0,15),0,0,"L",0);

 }
 $pdf->setY($alt_conv);
 $pdf->setX(10);
 $pdf->cell(278,4,"",1,1,"L",0);
 if ($claprovconselho->numrows > 0) {

   $pdf->cell(278,4,"Observações",1,1,"C",0);
   $sepobs = "";
   for ($g = 0; $g < $claprovconselho->numrows; $g++) {

     db_fieldsmemory($result_cons,$g);
     $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs - Responsável: $z01_nome";
     $sepobs = "\n";

   }
   $pdf->multicell(278,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
 }
 $sWhere  = " ed59_i_turma = $ed57_i_codigo AND ed59_i_codigo not in ($reg_pagina) AND ed59_c_condicao = 'OB' ";
 $sWhere .= " AND ed59_i_serie = $ed223_i_serie";
 $sql2    = $clregencia->sql_query("",
                                   "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                   "ed59_i_ordenacao",
                                   $sWhere
                                  );
 $result2 = $clregencia->sql_record($sql2);
 if ($clregencia->numrows > 0) {

   $pdf->addpage('L');
   $pdf->setfont('arial','b',7);
   $inicio = $pdf->getY();
   $pdf->cell(5,4,"","LRT",0,"C",0);
   $pdf->cell(65,4,"Disciplinas","LRT",0,"R",0);
   $cont = 0;
   $reg_pagina = 0;
   $sep = "";

   for ($y = 0; $y < $clregencia->numrows; $y++) {

     db_fieldsmemory($result2,$y);
     if ($y < 9) {

       $pdf->cell(22,4,$ed232_c_abrev,"LRT",0,"C",0);
       $cont++;
       $reg_pagina .= $sep.$ed59_i_codigo;
       $sep = ",";

     }
   }
   for ($y = $cont; $y < 9; $y++) {
     $pdf->cell(22,4,"","LRT",0,"C",0);
   }
   $pdf->cell(10,4,"RF",1,1,"C",0);
   $pdf->cell(5,4,"N°",1,0,"C",0);
   $pdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
   $cont2 = 0;
   for ($y = 0; $y < $clregencia->numrows; $y++) {

     if ($y < 9) {

       $pdf->cell(12,4,"Aprov",1,0,"C",0);
       $pdf->cell(10,4,"% Freq",1,0,"C",0);
       $cont2++;

     }
   }
   for ($y = $cont2; $y < 9; $y++) {

     $pdf->cell(12,4,"",1,0,"C",0);
     $pdf->cell(10,4,"",1,0,"C",0);

   }
   $pdf->cell(10,4,"",1,1,"C",0);
   $sCampos = "ed60_i_codigo,ed60_c_parecer,ed60_c_situacao,ed60_i_aluno,ed60_i_numaluno,ed47_v_nome";
   $sql4    = $clmatricula->sql_query("",
                                      $sCampos,
                                      "ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa",
                                      " ed60_i_turma = $ed57_i_codigo AND ed221_i_serie = $ed223_i_serie"
                                     );
   $result4 = $clmatricula->sql_record($sql4);
   $cor1    = 0;
   $cor2    = 0;
   $cor     = "";
   $cont4   = 0;
   if ($claprovconselho->numrows == 0) {
     $limite = 35;
   } else {
     $limite = 33;
   }
   $cont_geral = 0;
   for ($z = 0; $z < $clmatricula->numrows; $z++) {

     db_fieldsmemory($result4,$z);

     if ($trocaTurma == 1 && $ed60_c_situacao == 'TROCA DE TURMA') {
       continue;
     }
     if ($cor == $cor1) {
       $cor = $cor2;
     } else {
       $cor = $cor1;
     }
     $pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",$cor);
     $pdf->cell(65,4,$ed47_v_nome,1,0,"L",$cor);
     $sql5    = " SELECT ed74_c_valoraprov,ed74_i_percfreq,ed81_c_todoperiodo,ed37_c_tipo,ed59_c_freqglob, ";
     $sql5   .= "             ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev ";
     $sql5   .= "      FROM diariofinal ";
     $sql5   .= "       inner join diario on ed95_i_codigo = ed74_i_diario ";
     $sql5   .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
     $sql5   .= "       inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
     $sql5   .= "       inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
     $sql5   .= "       left join amparo on ed81_i_diario = ed95_i_codigo ";
     $sql5   .= "       left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
     $sql5   .= "       left join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov ";
     $sql5   .= "       left join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao ";
     $sql5   .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
     $sql5   .= "      AND ed95_i_regencia in ($reg_pagina) ";
     $sql5   .= "      AND ed59_c_condicao = 'OB' ORDER BY ed59_i_ordenacao ";
     $result5 = db_query($sql5);
     $linhas5 = pg_num_rows($result5);
     $cont3   = 0;
     if ($linhas5 > 0) {

       for ($v = 0; $v < $linhas5; $v++) {

         db_fieldsmemory($result5,$v);
         if ($ed60_c_parecer == "S") {
           $ed37_c_tipo = "PARECER";
         }
         if (trim($ed60_c_situacao) != "MATRICULADO") {

           $aproveitamento = substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5);
           $frequencia     = "";

         } else {

           if (trim($ed81_c_todoperiodo) == "S") {

             if ($ed81_i_justificativa != "") {
               $aproveitamento = "AMP.";
             } else {
               $aproveitamento = $ed250_c_abrev;
             }
             $frequencia = "";

           } else {

             if (trim($ed59_c_freqglob) == "F") {

               $aproveitamento = "-";
               if ($resultedu == 'S') {
                 $frequencia = ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano);
               } else {
                 $frequencia = ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano);
               }

             } else if (trim($ed59_c_freqglob) == "A") {

               if (trim($ed37_c_tipo) == "NOTA") {

                 if ($rsultedu == 'S') {
                   $aproveitamento = ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano);
                 } else {
                   $aproveitamento = ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano);
                 }

               } else if (trim($ed37_c_tipo) == "PARECER") {
                 $aproveitamento = "Parecer";
               } else {
                 $aproveitamento = $ed74_c_valoraprov;
               }

               $sql_f    = " SELECT ed74_i_percfreq ";
               $sql_f   .= "  FROM diariofinal ";
               $sql_f   .= "   inner join diario on ed95_i_codigo = ed74_i_diario ";
               $sql_f   .= "   inner join regencia on ed59_i_codigo = ed95_i_regencia ";
               $sql_f   .= "   inner join turma on ed57_i_codigo = ed59_i_turma ";
               $sql_f   .= "  WHERE ed57_i_codigo = $ed57_i_codigo ";
               $sql_f   .= "  AND ed59_c_freqglob = 'F' ";
               $sql_f   .= "  AND ed95_i_aluno = $ed60_i_aluno ";
               $sql_f   .= "  AND ed95_i_regencia = $ed59_i_codigo ";
               $result_f = db_query($sql_f);
               $linhas_f = pg_num_rows($result_f);
               if ($resultedu == 'S') {
                 $frequencia = ArredondamentoFrequencia::arredondar(pg_result($result_f,0,'ed74_i_percfreq'), $ed52_i_ano);
               } else {
                 $frequencia = ArredondamentoFrequencia::arredondar(pg_result($result_f,0,'ed74_i_percfreq'), $ed52_i_ano);
               }

             } else {

               if (trim($ed37_c_tipo) == "NOTA") {

                 if ($resultedu == 'S') {
                   $aproveitamento = ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano);
                 } else {
                   $aproveitamento = ArredondamentoNota::formatar($ed74_c_valoraprov, $ed52_i_ano);
                 }

               } else if (trim($ed37_c_tipo) == "PARECER") {
                 $aproveitamento = "Parecer";
               } else {
                 $aproveitamento = $ed74_c_valoraprov;
               }
               if ($resultedu == 'S') {
                 $frequencia = ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano);
               } else {
                 $frequencia = ArredondamentoFrequencia::arredondar($ed74_i_percfreq, $ed52_i_ano);
               }
             }
          }
         }
         $pdf->setfont('arial','',9);
         $pdf->cell(12,4,$aproveitamento,1,0,"C",$cor);
         $pdf->cell(10,4,$frequencia,1,0,"C",$cor);
         $pdf->setfont('arial','b',7);
         $cont3++;

       }
     } else {

       $pdf->setfont('arial','b',7);
       $pdf->cell(12,4,substr(trim(Situacao($ed60_c_situacao,$ed60_i_codigo)),0,5),1,0,"C",$cor);
       $pdf->cell(10,4,"",1,0,"C",$cor);
       $pdf->setfont('arial','',9);
       $cont3++;

     }
     for ($y = $cont3; $y < 9; $y++) {

       $pdf->cell(12,4,"",1,0,"C",$cor);
       $pdf->cell(10,4,"",1,0,"C",$cor);

     }
     $sql6    = " SELECT ed95_i_codigo ";
     $sql6   .= "      FROM diario ";
     $sql6   .= "       inner join aluno on ed47_i_codigo = ed95_i_aluno ";
     $sql6   .= "       inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
     $sql6   .= "       inner join regencia on ed59_i_codigo = ed95_i_regencia ";
     $sql6   .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
     $sql6   .= "      AND ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo ";
     $sql6   .= "                                                                      AND ed59_i_serie = $ed223_i_serie)";
     $sql6   .= "      AND ed59_c_condicao = 'OB' ";
     $sql6   .= "      AND ed74_c_resultadofinal != 'A' ";
     $result6 = db_query($sql6);
     $linhas6 = pg_num_rows($result6);

     $oTurma               = TurmaRepository::getTurmaByCodigo( $ed57_i_codigo );
     $iAnoCalendario       = $oTurma->getCalendario()->getAnoExecucao();

     $aDadosTermoAprovado  = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, 'A', $iAnoCalendario);
     $aDadosTermoReprovado = DBEducacaoTermo::getTermoEncerramento($ed11_i_ensino, 'R', $iAnoCalendario);
     if (trim($ed60_c_situacao) != "MATRICULADO") {
       $rf = "";
     } else {

       if ($linhas6 == 0) {

         $rf = "APR";
         if (isset($aDadosTermoAprovado[0])) {
           $rf = $aDadosTermoAprovado[0]->sAbreviatura;
         }
       } else {

         $rf = "REP";
         if (isset($aDadosTermoReprovado[0])) {
           $rf = $aDadosTermoReprovado[0]->sAbreviatura;
         }
       }

       if (@$ed74_c_valoraprov == "") {
         $rf = "";
       }
     }
     $pdf->cell(10,4,$rf,1,1,"C",$cor);
     $pdf->line(10,43,288,43);
     if ($cont4 == $limite && ($cont_geral+1) < $clmatricula->numrows) {

       $alt_conv = $pdf->getY();
       $cont5  = 0;
       $quebra = "0";
       $sWhere  = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
       $sql2    = $clregencia->sql_query("",
                                         "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                         "ed59_i_ordenacao",
                                         $sWhere
                                        );
       $result2 = $clregencia->sql_record($sql2);
       for ($y = 0; $y < $clregencia->numrows; $y++) {

         db_fieldsmemory($result2,$y);
         $pdf->cell(30,4,$ed232_c_abrev." - ".substr($ed232_c_descr,0,15),0,0,"L",0);

       }
       $pdf->setY($alt_conv);
       $pdf->setX(10);
       $pdf->cell(278,4,"",1,1,"L",0);
       if ($claprovconselho->numrows > 0) {

         $pdf->cell(278,4,"Observações",1,1,"C",0);
         $sepobs = "";
         for ($g = 0; $g < $claprovconselho->numrows; $g++) {

           db_fieldsmemory($result_cons,$g);
           $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs - Responsável: $z01_nome";
           $sepobs    = "\n";
           $pdf->multicell(278,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);

         }
       }
       $pdf->addpage('L');
       $pdf->setfont('arial','b',7);
       $inicio = $pdf->getY();
       $pdf->cell(5,4,"","LRT",0,"C",0);
       $pdf->cell(65,4,"Disciplinas","LRT",0,"R",0);
       $sWhere     = " ed59_i_codigo in ($reg_pagina)  AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
       $sql2       = $clregencia->sql_query("",
                                            "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                            "ed59_i_ordenacao",
                                            $sWhere
                                           );
       $result2    = $clregencia->sql_record($sql2);
       $cont       = 0;
       $reg_pagina = 0;
       $sep        = "";
       for ($y = 0; $y < $clregencia->numrows; $y++) {

         db_fieldsmemory($result2,$y);
         if ($y < 9) {

           $pdf->cell(22,4,$ed232_c_abrev,"LRT",0,"C",0);
           $cont++;
           $reg_pagina .= $sep.$ed59_i_codigo;
           $sep         = ",";

         }
       }
       for ($y = $cont; $y < 9; $y++) {
         $pdf->cell(22,4,"","LRT",0,"C",0);
       }
       $pdf->cell(10,4,"RF",1,1,"C",0);
       $pdf->cell(5,4,"N°",1,0,"C",0);
       $pdf->cell(65,4,"Nome do Aluno",1,0,"C",0);
       $cont2 = 0;
       for ($y = 0; $y < $clregencia->numrows; $y++) {

         if ($y < 9) {

           $pdf->cell(12,4,"Aprov",1,0,"C",0);
           $pdf->cell(10,4,"% Freq",1,0,"C",0);
           $cont2++;

         }
       }
       for ($y = $cont2; $y < 9; $y++) {

         $pdf->cell(12,4,"",1,0,"C",0);
         $pdf->cell(10,4,"",1,0,"C",0);

       }
       $pdf->cell(10,4,"",1,1,"C",0);
       $cont4 = -1;
     }
     $cont4++;
   }
   for ($z = $cont4; $z < $limite; $z++) {

     $pdf->cell(5,4,"",1,0,"C",0);
     $pdf->cell(65,4,"",1,0,"L",0);
     for ($t = 0; $t < 9; $t++) {

       $pdf->cell(12,4,"",1,0,"C",0);
       $pdf->cell(10,4,"",1,0,"C",0);

     }
     $pdf->cell(10,4,"",1,1,"C",0);
   }
   $alt_conv = $pdf->getY();
   $cont5    = 0;
   $quebra   = "0";
   $sWhere   = " ed59_i_codigo in ($reg_pagina) AND ed59_c_condicao = 'OB' AND ed59_i_serie = $ed223_i_serie";
   $sql2     = $clregencia->sql_query("",
                                      "ed59_i_codigo,ed232_c_abrev,ed232_c_descr,ed59_i_ordenacao",
                                      "ed59_i_ordenacao",
                                      $sWhere
                                     );
   $result2  = $clregencia->sql_record($sql2);

   for ($y = 0; $y < $clregencia->numrows; $y++) {

     db_fieldsmemory($result2,$y);
     $pdf->cell(30,4,$ed232_c_abrev." - ".substr($ed232_c_descr,0,15),0,0,"L",0);

   }
   $pdf->setY($alt_conv);
   $pdf->setX(10);
   $pdf->cell(278,4,"",1,1,"L",0);
   if ($claprovconselho->numrows>0) {

     $pdf->cell(278,4,"Observações",1,1,"C",0);
     $sepobs = "";

     for ($g = 0; $g < $claprovconselho->numrows; $g++) {

       db_fieldsmemory($result_cons,$g);
       $obs_cons .= $sepobs."-Aluno(a) $ed47_v_nome foi aprovado pelo Conselho de Classe. Justificativa: $ed253_t_obs - Responsável: $z01_nome";
       $sepobs = "\n";

     }
     $pdf->multicell(278,4,($obs_cons!=""?$obs_cons."\n":""),1,"J",0,0);
   }
 }
}
$pdf->Output();
?>
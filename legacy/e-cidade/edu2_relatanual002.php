<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

include("fpdf151/pdfwebseller.php");
include("classes/db_calendario_classe.php");
$clcalendario   = new cl_calendario;
$data_censo_dia = substr($data_censo,0,2);
$data_censo_mes = substr($data_censo,3,2);
$data_censo_ano = substr($data_censo,6,4);
$data_censo     = $data_censo_ano."-".$data_censo_mes."-".$data_censo_dia;
$sql0           = " SELECT ed52_d_fim,ed52_i_diasletivos ";
$sql0          .= "   FROM calendario ";
$sql0          .= "    inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
$sql0          .= "   WHERE ed52_i_ano = $ano_censo ";
$sql0          .= "   AND ed38_i_escola = $iEscola ";
$sql0          .= "   ORDER BY ed52_i_codigo ";
$sql0          .= "   LIMIT 1 ";
$result0        = db_query($sql0);
$linhas0        = pg_num_rows($result0);

if ($linhas0 > 0) {

  $datarel     = pg_result($result0,0,0);
  $diasletivos = pg_result($result0,0,1);

} else {

  $datarel     = date("Y-m-d");
  $diasletivos = 200;

}

$sql    = " SELECT count(ed57_i_codigo) as qtdturmas, ";
$sql   .= "        ed11_c_descr, ";
$sql   .= "        ed11_i_codigo, ";
$sql   .= "        ed11_i_ensino, ";
$sql   .= "        ed10_c_descr, ";
$sql   .= "        ed10_c_abrev, ";
$sql   .= "        ed31_i_codigo, ";
$sql   .= "        ed31_c_descr ";
$sql   .= " FROM turma ";
$sql   .= "  inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
$sql   .= "  inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
$sql   .= "  inner join serie on ed11_i_codigo = ed223_i_serie ";
$sql   .= "  inner join base on ed31_i_codigo = ed57_i_base ";
$sql   .= "  inner join ensino on ed10_i_codigo = ed11_i_ensino ";
$sql   .= "  inner join calendario on ed52_i_codigo=ed57_i_calendario ";
$sql   .= " WHERE ed57_i_escola = $iEscola ";
$sql   .= " AND ed52_i_ano = $ano_censo ";
$sql   .= " AND exists(select * from matricula where ed60_i_turma = ed57_i_codigo) ";
$sql   .= " GROUP BY ed11_c_descr,ed11_i_codigo,ed11_i_sequencia,ed11_i_ensino,ed10_c_descr, ";
$sql   .= " ed10_c_abrev,ed31_i_codigo,ed31_c_descr ";
$sql   .= " ORDER BY ed11_i_ensino,ed11_i_sequencia ";
$result = db_query($sql);
$linhas = pg_num_rows($result);
if ($linhas == 0) {?>

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
$head1 = "RELATÓRIO ANUAL";
$head2 = "Ano: ".$ano_censo;
$head3 = "Data de Encerramento Ano Letivo: ".db_formatar($datarel,'d');
$head4 = "N° de Dias Letivos: $diasletivos";
$head5 = "Data do censo: ".$data_censo_dia."/".$data_censo_mes."/".$data_censo_ano;
$pdf->ln(5);
$troca                = 1;
$soma_turma           = 0;
$soma_antecenso       = 0;
$soma_poscenso        = 0;
$soma_geral           = 0;
$soma_transf          = 0;
$soma_canc            = 0;
$soma_final           = 0;
$soma_evadidos        = 0;
$soma_aprovados       = 0;
$soma_reprovados      = 0;
$soma_parc_turma      = 0;
$soma_parc_antecenso  = 0;
$soma_parc_poscenso   = 0;
$soma_parc_geral      = 0;
$soma_parc_transf     = 0;
$soma_parc_canc       = 0;
$soma_parc_final      = 0;
$soma_parc_evadidos   = 0;
$soma_parc_aprovados  = 0;
$soma_parc_reprovados = 0;
$pri_ensino           = "";
$pri_base             = "";
$series_base          = "";
$sep_series           = "";
$pdf->setfont('arial','b',8);
for ($c = 0; $c < $linhas; $c++) {

  db_fieldsmemory($result,$c);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

    $pdf->addpage('L');
    $pdf->setfillcolor(235);
    $pdf->cell(20,12,"Etapa",1,0,"C",1);
    $pdf->cell(15,12,"Turmas",1,0,"C",1);
    $pdf->cell(2,12,"",1,0,"C",1);
    $posy = $pdf->Gety();
    $pdf->cell(10,4,"Matr.","LRT",2,"C",1);
    $pdf->cell(10,4,"Inicial","LR",2,"C",1);
    $pdf->cell(10,4,"Censo","LRB",2,"C",1);
    $pdf->SetXY(57,$posy);
    $pdf->cell(10,4,"Entra","LRT",2,"C",1);
    $pdf->cell(10,4,"Após","LR",2,"C",1);
    $pdf->cell(10,4,"Censo","LRB",2,"C",1);
    $pdf->SetXY(67,$posy);
    $pdf->cell(10,4,"","LRT",2,"C",1);
    $pdf->cell(10,4,"Matr.","LR",2,"C",1);
    $pdf->cell(10,4,"Geral","LRB",2,"C",1);
    $pdf->SetXY(77,$posy);
    $pdf->cell(10,4,"Transf.","LRT",2,"C",1);
    $pdf->cell(10,4,"Após","LR",2,"C",1);
    $pdf->cell(10,4,"Censo","LRB",2,"C",1);
    $pdf->SetXY(87,$posy);
    $pdf->cell(10,4,"Canc.","LRT",2,"C",1);
    $pdf->cell(10,4,"Após","LR",2,"C",1);
    $pdf->cell(10,4,"Censo","LRB",2,"C",1);
    $pdf->SetXY(97,$posy);
    $pdf->cell(10,4,"","LRT",2,"C",1);
    $pdf->cell(10,4,"Matr.","LR",2,"C",1);
    $pdf->cell(10,4,"Final","LRB",2,"C",1);
    $pdf->SetXY(107,$posy);
    $pdf->cell(2,12,"",1,0,"C",1);
    $pdf->SetXY(109,$posy);
    $pdf->cell(20,8,"Aprovados",1,2,"C",1);
    $pdf->cell(10,4,"N°",1,0,"C",1);
    $pdf->cell(10,4,"%",1,0,"C",1);
    $pdf->SetXY(129,$posy);
    $pdf->cell(40,4,"Reprovados",1,2,"C",1);
    $pdf->cell(20,4,"Rendimento",1,0,"C",1);
    $pdf->cell(20,4,"Infreq./Evad.",1,0,"C",1);
    $pdf->SetXY(129,$posy+8);
    $pdf->cell(10,4,"N°",1,0,"C",1);
    $pdf->cell(10,4,"%",1,0,"C",1);
    $pdf->cell(10,4,"N°",1,0,"C",1);
    $pdf->cell(10,4,"%",1,0,"C",1);
    $pdf->SetXY(171,$posy+8);
    $sql_disc    = " SELECT DISTINCT ed232_i_codigo,ed232_c_abrev,ed232_c_descr ";
    $sql_disc   .= " FROM caddisciplina ";
    $sql_disc   .= "  inner join disciplina on ed12_i_caddisciplina=ed232_i_codigo ";
    $sql_disc   .= "  inner join regencia on ed59_i_disciplina=ed12_i_codigo ";
    $sql_disc   .= "  inner join diario on ed59_i_codigo=ed95_i_regencia ";
    $sql_disc   .= "  inner join calendario on ed52_i_codigo=ed95_i_calendario ";
    $sql_disc   .= "  inner join diariofinal on ed74_i_diario=ed95_i_codigo ";
    $sql_disc   .= " WHERE ed52_i_ano = $ano_censo ";
    $sql_disc   .= " AND ed95_i_escola = $iEscola ";
    $sql_disc   .= " AND ed59_c_condicao = 'OB' ";
    $sql_disc   .= " ORDER BY ed232_c_descr ";
    $result_disc = db_query($sql_disc);
    $linhas_disc = pg_num_rows($result_disc);

    for ($w = 0; $w < $linhas_disc; $w++) {

      db_fieldsmemory($result_disc,$w);
      $pdf->cell(8,4,$ed232_c_abrev,1,0,"C",1);

    }

    if ($linhas_disc < 2) {
      $titulo_rendimento = "";
    } else if ($linhas_disc < 5) {
      $titulo_rendimento = "Rep. Rend.";
    } else {
      $titulo_rendimento = "Reprovados por Rendimento";
    }

    $pdf->cell(1,4,"",1,1,"C",1);
    $pdf->SetXY(169,$posy);
    $pdf->cell(2,12,"",1,0,"C",1);
    $pdf->SetXY(171,$posy);
    $pdf->cell($linhas_disc*8+1,8,$titulo_rendimento,1,1,"C",1);
    $pdf->SetXY(10,$posy+12);
    $troca = 0;

  }

  if ($pri_base != $ed31_i_codigo && $c > 0) {

    $pdf->cell($linhas_disc*8+162,1,"",1,1,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,4,"Sub-total",1,0,"C",0);
    $pdf->cell(15,4,$soma_parc_turma,1,0,"C",0);
    $pdf->cell(2,4,"",1,0,"C",1);
    $pdf->cell(10,4,$soma_parc_antecenso,1,0,"C",0);
    $pdf->cell(10,4,$soma_parc_poscenso,1,0,"C",0);
    $pdf->cell(10,4,$soma_parc_geral,1,0,"C",0);
    $pdf->cell(10,4,$soma_parc_transf,1,0,"C",0);
    $pdf->cell(10,4,$soma_parc_canc,1,0,"C",0);
    $pdf->cell(10,4,$soma_parc_final,1,0,"C",0);
    $pdf->cell(2,4,"",1,0,"C",1);
    $perc_parc_somaaprov  = ($soma_parc_aprovados/$soma_parc_final)*100;
    $perc_parc_somareprov = ($soma_parc_reprovados/$soma_parc_final)*100;
    $perc_parc_somaevad   = ($soma_parc_evadidos/$soma_parc_final)*100;
    $pdf->cell(10,4,$soma_parc_aprovados,1,0,"C",0);
    $pdf->cell(10,4,number_format($perc_parc_somaaprov,2,".","."),1,0,"R",0);
    $pdf->cell(10,4,$soma_parc_reprovados,1,0,"C",0);
    $pdf->cell(10,4,number_format($perc_parc_somareprov,2,".","."),1,0,"R",0);
    $pdf->cell(10,4,$soma_parc_evadidos,1,0,"C",0);
    $pdf->cell(10,4,number_format($perc_parc_somaevad,2,".","."),1,0,"R",0);
    $pdf->cell(2,4,"",1,0,"C",1);

    for ($w = 0; $w < $linhas_disc; $w++) {

      db_fieldsmemory($result_disc,$w);
      $sql5    = " SELECT count(*) as reprov_disc ";
      $sql5   .= " FROM diario ";
      $sql5   .= "  inner join calendario on ed52_i_codigo = ed95_i_calendario ";
      $sql5   .= "  inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
      $sql5   .= "  inner join regencia on ed59_i_codigo = ed95_i_regencia ";
      $sql5   .= "  inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
      $sql5   .= "  inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
      $sql5   .= " WHERE ed95_i_escola = $iEscola ";
      $sql5   .= " AND ed52_i_ano = $ano_censo ";
      $sql5   .= " AND ed232_i_codigo = $ed232_i_codigo ";
      $sql5   .= " AND ed95_i_serie in ($series_base) ";
      $sql5   .= " AND ed74_c_resultadofinal='R' ";
      $sql5   .= " AND ed74_c_resultadoaprov='R' ";
      $result5 = db_query($sql5);
      db_fieldsmemory($result5,0);
      $pdf->cell(8,4,$reprov_disc==""||$reprov_disc==0?"":$reprov_disc,1,0,"C",0);

    }

    $pdf->cell(1,4,"",1,1,"C",0);
    $soma_parc_turma      = 0;
    $soma_parc_antecenso  = 0;
    $soma_parc_poscenso   = 0;
    $soma_parc_geral      = 0;
    $soma_parc_transf     = 0;
    $soma_parc_canc       = 0;
    $soma_parc_final      = 0;
    $soma_parc_evadidos   = 0;
    $soma_parc_aprovados  = 0;
    $soma_parc_reprovados = 0;
    $series_base          = "";
    $sep_series           = "";
    $pdf->setfont('arial','',8);

  }

  $series_base .= $sep_series.$ed11_i_codigo;
  $sep_series   = ",";
  $pdf->setfont('arial','b',8);
  if ($pri_ensino != $ed11_i_ensino) {

    $pdf->cell($linhas_disc*8+162,4,$ed10_c_descr." - ".$ed10_c_abrev,1,1,"L",1);
    $pri_ensino = $ed11_i_ensino;

  }

  $pdf->setfont('arial','',8);
  if ($pri_base != $ed31_i_codigo) {

    $pdf->cell($linhas_disc*8+162,4,$ed31_c_descr,1,1,"L",0);
    $pri_base = $ed31_i_codigo;

  }

  $pdf->setfont('arial','',8);
  $pdf->cell(20,4,$ed11_c_descr,1,0,"L",0);
  $pdf->cell(15,4,$qtdturmas,1,0,"C",0);
  $pdf->cell(2,4,"",1,0,"C",1);
  //Matricula Total
  $sql1 = " SELECT (select count(*)  ";
  $sql1 .= "           from matricula ";
  $sql1 .= "	         inner join turma on ed57_i_codigo = ed60_i_turma ";
  $sql1 .= "	         inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sql1 .= " 	         inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sql1 .= "	        where ed221_i_serie = ed11_i_codigo ";
  $sql1 .= "	        and ed57_i_escola = $iEscola ";
  $sql1 .= "	        and ed57_i_base = $ed31_i_codigo ";
  $sql1 .= "	        and ed52_i_ano = $ano_censo ";
  $sql1 .= "	        and ed221_c_origem = 'S' ";
  $sql1 .= "          and ed60_c_situacao != 'TROCA DE MODALIDADE' ";
  $sql1 .= "          and ed60_c_situacao <> 'TROCA DE TURMA' ";
  $sql1 .= "          and ed60_d_datamatricula <= '$data_censo' ";
  $sql1 .= "        )- ";
  $sql1 .= "        (select count(*)  ";
  $sql1 .= "          from matricula ";
  $sql1 .= "	         inner join turma on ed57_i_codigo = ed60_i_turma ";
  $sql1 .= "	         inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sql1 .= " 	         inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sql1 .= "	        where ed221_i_serie = ed11_i_codigo ";
  $sql1 .= "	        and ed57_i_escola = $iEscola ";
  $sql1 .= "	        and ed57_i_base = $ed31_i_codigo ";
  $sql1 .= "	        and ed52_i_ano = $ano_censo ";
  $sql1 .= "	        and ed221_c_origem = 'S' ";
  $sql1 .= "          and (ed60_c_situacao = 'TRANSFERIDO REDE'  ";
  $sql1 .= "               OR ed60_c_situacao = 'TRANSFERIDO FORA' ";
  $sql1 .= "               OR ed60_c_situacao = 'CANCELADO'  ";
  $sql1 .= "               OR ed60_c_situacao = 'FALECIDO'  ";
  $sql1 .= "               OR ed60_c_situacao = 'AVANÇADO'  ";
  $sql1 .= "               OR ed60_c_situacao = 'CLASSIFICADO') ";
  $sql1 .= "          and ed60_c_situacao <> 'TROCA DE TURMA' ";
  $sql1 .= "          and ed60_d_datamatricula <= '$data_censo' ";
  $sql1 .= "          and ed60_d_datasaida <= '$data_censo' ";
  $sql1 .= "        )as antecenso, ";
  $sql1 .= "      (select count(*)  ";
  $sql1 .= "        from matricula ";
  $sql1 .= "	         inner join turma on ed57_i_codigo = ed60_i_turma ";
  $sql1 .= "	         inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sql1 .= "	         inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sql1 .= "        where ed221_i_serie = ed11_i_codigo ";
  $sql1 .= "	        and ed57_i_escola = $iEscola ";
  $sql1 .= "	        and ed57_i_base = $ed31_i_codigo ";
  $sql1 .= "          and ed52_i_ano = $ano_censo ";
  $sql1 .= "	        and ed221_c_origem = 'S' ";
  $sql1 .= "          and ed60_c_situacao != 'TROCA DE MODALIDADE' ";
  $sql1 .= "          and ed60_c_situacao <> 'TROCA DE TURMA' ";
  $sql1 .= "          and ed60_d_datamatricula > '$data_censo' ";
  $sql1 .= "        ) as poscenso, ";
  $sql1 .= "	      (select count(*)  ";
  $sql1 .= "	        from matricula ";
  $sql1 .= "	         inner join turma on ed57_i_codigo = ed60_i_turma ";
  $sql1 .= "	         inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sql1 .= " 	         inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sql1 .= "          where ed221_i_serie = ed11_i_codigo ";
  $sql1 .= "	        and ed57_i_escola = $iEscola ";
  $sql1 .= " 	        and ed57_i_base = $ed31_i_codigo ";
  $sql1 .= "          and ed52_i_ano = $ano_censo ";
  $sql1 .= " 	        and ed221_c_origem = 'S' ";
  $sql1 .= "          and (ed60_c_situacao = 'TRANSFERIDO FORA'  ";
  $sql1 .= "	             OR ed60_c_situacao = 'TRANSFERIDO REDE' ";
  $sql1 .= "	             OR ed60_c_situacao = 'FALECIDO'  ";
  $sql1 .= "               OR ed60_c_situacao = 'AVANÇADO'  ";
  $sql1 .= "	             OR ed60_c_situacao = 'CLASSIFICADO') ";
  $sql1 .= "          and ed60_d_datasaida > '$data_censo' ";
  $sql1 .= "        ) as transfposcenso, ";
  $sql1 .= "        (select count(*)  ";
  $sql1 .= "	        from matricula ";
  $sql1 .= "	         inner join turma on ed57_i_codigo = ed60_i_turma ";
  $sql1 .= "	         inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sql1 .= " 	         inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sql1 .= "          where ed221_i_serie = ed11_i_codigo ";
  $sql1 .= "	        and ed57_i_escola = $iEscola ";
  $sql1 .= " 	        and ed57_i_base = $ed31_i_codigo ";
  $sql1 .= "	        and ed52_i_ano = $ano_censo ";
  $sql1 .= "	        and ed221_c_origem = 'S' ";
  $sql1 .= "          and ed60_d_datasaida > '$data_censo' ";
  $sql1 .= "	        and ed60_c_situacao in('CANCELADO', 'MATRICULA TRANCADA',  'MATRICULA INDEFERIDA') ";
  $sql1 .= "	      ) as cancposcenso ";
  $sql1 .= "  FROM serie ";
  $sql1 .= "   WHERE ed11_i_codigo = $ed11_i_codigo ";
  $result1 = db_query($sql1);
  db_fieldsmemory($result1,0);
  $matr_final = ($antecenso+$poscenso)-$transfposcenso-$cancposcenso;
  $pdf->cell(10,4,$antecenso,1,0,"C",0);
  $pdf->cell(10,4,$poscenso == "" || $poscenso == 0?"":$poscenso,1,0,"C",0);
  $pdf->cell(10,4,$antecenso+$poscenso,1,0,"C",0);
  $pdf->cell(10,4,$transfposcenso == "" || $transfposcenso == 0?"":$transfposcenso,1,0,"C",0);
  $pdf->cell(10,4,$cancposcenso == "" || $cancposcenso == 0?"":$cancposcenso,1,0,"C",0);
  $pdf->cell(10,4,$matr_final,1,0,"C",0);
  $pdf->cell(2,4,"",1,0,"C",1);
  $soma_turma          += $qtdturmas;
  $soma_antecenso      += $antecenso;
  $soma_poscenso       += $poscenso;
  $soma_geral          += $antecenso+$poscenso;
  $soma_transf         += $transfposcenso;
  $soma_canc           += $cancposcenso;
  $soma_final          += $matr_final;
  $soma_parc_turma     += $qtdturmas;
  $soma_parc_antecenso += $antecenso;
  $soma_parc_poscenso  += $poscenso;
  $soma_parc_geral     += $antecenso+$poscenso;
  $soma_parc_transf    += $transfposcenso;
  $soma_parc_canc      += $cancposcenso;
  $soma_parc_final     += $matr_final;
  //seleciona todos alunos da serie na escola
  $sql3       = " SELECT fc_edurfatual(ed60_i_codigo) as rendimentofinal ";
  $sql3      .= " FROM matricula ";
  $sql3      .= "  inner join turma on ed57_i_codigo = ed60_i_turma ";
  $sql3      .= "  inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sql3      .= "  inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sql3      .= " WHERE ed221_i_serie = $ed11_i_codigo ";
  $sql3      .= " AND ed52_i_ano = $ano_censo ";
  $sql3      .= " AND ed57_i_escola = $iEscola ";
  $sql3      .= " AND ed57_i_base = $ed31_i_codigo ";
  $sql3      .= " AND ed221_c_origem = 'S' ";
  $sql3      .= " AND ed60_c_situacao = 'MATRICULADO' ";
  $sql3      .= " GROUP BY ed60_i_codigo ";
  $result3    = db_query($sql3);
  $linhas3    = pg_num_rows($result3);
  $aprovados  = 0;
  $reprovados = 0;
  for ($x = 0; $x < $linhas3; $x++) {

    db_fieldsmemory($result3,$x);
    if ($rendimentofinal == "R") {
      $reprovados++;
    } else if ($rendimentofinal == "A") {
      $aprovados++;
    }
  }
  $sql41    = " SELECT count(*) as evadidos ";
  $sql41   .= "  FROM matricula ";
  $sql41   .= "   inner join turma on ed57_i_codigo = ed60_i_turma ";
  $sql41   .= "   inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sql41   .= "   inner join calendario on ed52_i_codigo = ed57_i_calendario ";
  $sql41   .= "  WHERE ed221_i_serie = $ed11_i_codigo ";
  $sql41   .= "  AND ed57_i_escola = $iEscola ";
  $sql41   .= "  AND ed57_i_base = $ed31_i_codigo ";
  $sql41   .= "  AND ed52_i_ano = $ano_censo ";
  $sql41   .= "  AND ed221_c_origem = 'S' ";
  $sql41   .= "  AND ed60_c_situacao = 'EVADIDO' ";
  $result41 = db_query($sql41);
  db_fieldsmemory($result41,0);
  $soma_evadidos        += $evadidos;
  $soma_aprovados       += $aprovados;
  $soma_reprovados      += $reprovados;
  $soma_parc_evadidos   += $evadidos;
  $soma_parc_aprovados  += $aprovados;
  $soma_parc_reprovados += $reprovados;
  
  $perc_aprov  = 0;
  $perc_reprov = 0;
  $perc_evad   = 0;
  
  if ( $matr_final > 0 ) {
    
    $perc_aprov  = ($aprovados/$matr_final)*100;
    $perc_reprov = ($reprovados/$matr_final)*100;
    $perc_evad   = ($evadidos/$matr_final)*100;
  }
  
  $pdf->cell(10,4,$aprovados,1,0,"C",0);
  $pdf->cell(10,4,number_format($perc_aprov,2,".","."),1,0,"R",0);
  $pdf->cell(10,4,$reprovados,1,0,"C",0);
  $pdf->cell(10,4,number_format($perc_reprov,2,".","."),1,0,"R",0);
  $pdf->cell(10,4,$evadidos == 0?"":$evadidos,1,0,"C",0);
  $pdf->cell(10,4,number_format($perc_evad,2,".","."),1,0,"R",0);
  $pdf->cell(2,4,"",1,0,"C",1);
  for ($w = 0; $w < $linhas_disc; $w++) {

    db_fieldsmemory($result_disc,$w);
    $sql5    = " SELECT count(*) as reprov_disc ";
    $sql5   .= "  FROM	diario ";
    $sql5   .= "   inner join calendario on ed52_i_codigo = ed95_i_calendario ";
    $sql5   .= "    inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
    $sql5   .= "   inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sql5   .= "   inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
    $sql5   .= "   inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
    $sql5   .= "  WHERE ed95_i_serie = $ed11_i_codigo ";
    $sql5   .= "  AND ed95_i_escola = $iEscola ";
    $sql5   .= "   AND ed52_i_ano = $ano_censo ";
    $sql5   .= "   AND ed232_i_codigo = $ed232_i_codigo ";
    $sql5   .= "  AND ed74_c_resultadofinal='R' ";
    $sql5   .= "  AND ed74_c_resultadoaprov='R' ";
    $result5 = db_query($sql5);
    db_fieldsmemory($result5,0);
    $pdf->cell(8,4,$reprov_disc==""||$reprov_disc==0?"":$reprov_disc,1,0,"C",0);

  }

  $pdf->cell(1,4,"",1,1,"C",0);
}

$pdf->cell($linhas_disc*8+161,1,"",1,1,"L",0);
$pdf->setfont('arial','b',8);
$pdf->cell(20,4,"Sub-total",1,0,"C",0);
$pdf->cell(15,4,$soma_parc_turma,1,0,"C",0);
$pdf->cell(2,4,"",1,0,"C",1);
$pdf->cell(10,4,$soma_parc_antecenso,1,0,"C",0);
$pdf->cell(10,4,$soma_parc_poscenso,1,0,"C",0);
$pdf->cell(10,4,$soma_parc_geral,1,0,"C",0);
$pdf->cell(10,4,$soma_parc_transf,1,0,"C",0);
$pdf->cell(10,4,$soma_parc_canc,1,0,"C",0);
$pdf->cell(10,4,$soma_parc_final,1,0,"C",0);
$pdf->cell(2,4,"",1,0,"C",1);
$perc_parc_somaaprov  = ($soma_parc_aprovados/$soma_parc_final)*100;
$perc_parc_somareprov = ($soma_parc_reprovados/$soma_parc_final)*100;
$perc_parc_somaevad   = ($soma_parc_evadidos/$soma_parc_final)*100;
$pdf->cell(10,4,$soma_parc_aprovados,1,0,"C",0);
$pdf->cell(10,4,number_format($perc_parc_somaaprov,2,".","."),1,0,"R",0);
$pdf->cell(10,4,$soma_parc_reprovados,1,0,"C",0);
$pdf->cell(10,4,number_format($perc_parc_somareprov,2,".","."),1,0,"R",0);
$pdf->cell(10,4,$soma_parc_evadidos,1,0,"C",0);
$pdf->cell(10,4,number_format($perc_parc_somaevad,2,".","."),1,0,"R",0);
$pdf->cell(2,4,"",1,0,"C",1);
for ($w = 0; $w < $linhas_disc; $w++) {

  db_fieldsmemory($result_disc,$w);
  $sql5    = " SELECT count(*) as reprov_disc ";
  $sql5   .= "  FROM diario ";
  $sql5   .= "   inner join calendario on ed52_i_codigo = ed95_i_calendario ";
  $sql5   .= "   inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
  $sql5   .= "   inner join regencia on ed59_i_codigo = ed95_i_regencia ";
  $sql5   .= "   inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
  $sql5   .= "   inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
  $sql5   .= "  WHERE ed95_i_escola = $iEscola ";
  $sql5   .= "  AND ed52_i_ano = $ano_censo ";
  $sql5   .= "  AND ed232_i_codigo = $ed232_i_codigo ";
  $sql5   .= "  AND ed95_i_serie in ($series_base) ";
  $sql5   .= "  AND ed74_c_resultadofinal='R' ";
  $sql5   .= "  AND ed74_c_resultadoaprov='R' ";
  $result5 = db_query($sql5);
  db_fieldsmemory($result5,0);
  $pdf->cell(8,4,$reprov_disc==""||$reprov_disc==0?"":$reprov_disc,1,0,"C",0);

}

$pdf->cell(1,4,"",1,1,"C",0);
$pdf->cell($linhas_disc*8+161,2,"",0,1,"L",0);
$pdf->setfont('arial','b',9);
$pdf->cell(20,4,"TOTAL",1,0,"C",0);
$pdf->cell(15,4,$soma_turma,1,0,"C",0);
$pdf->cell(2,4,"",1,0,"C",1);
$pdf->cell(10,4,$soma_antecenso,1,0,"C",0);
$pdf->cell(10,4,$soma_poscenso,1,0,"C",0);
$pdf->cell(10,4,$soma_geral,1,0,"C",0);
$pdf->cell(10,4,$soma_transf,1,0,"C",0);
$pdf->cell(10,4,$soma_canc,1,0,"C",0);
$pdf->cell(10,4,$soma_final,1,0,"C",0);
$pdf->cell(2,4,"",1,0,"C",1);
$perc_somaaprov = ($soma_aprovados/$soma_final)*100;
$perc_somareprov = ($soma_reprovados/$soma_final)*100;
$perc_somaevad = ($soma_evadidos/$soma_final)*100;
$pdf->cell(10,4,$soma_aprovados,1,0,"C",0);
$pdf->cell(10,4,number_format($perc_somaaprov,2,".","."),1,0,"R",0);
$pdf->cell(10,4,$soma_reprovados,1,0,"C",0);
$pdf->cell(10,4,number_format($perc_somareprov,2,".","."),1,0,"R",0);
$pdf->cell(10,4,$soma_evadidos,1,0,"C",0);
$pdf->cell(10,4,number_format($perc_somaevad,2,".","."),1,0,"R",0);
$pdf->cell(2,4,"",1,0,"C",1);
for ($w = 0; $w < $linhas_disc; $w++) {

  db_fieldsmemory($result_disc,$w);
  $sql5    = " SELECT count(*) as reprov_disc ";
  $sql5   .= "  FROM	diario ";
  $sql5   .= "   inner join calendario on ed52_i_codigo = ed95_i_calendario ";
  $sql5   .= "    inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
  $sql5   .= "   inner join regencia on ed59_i_codigo = ed95_i_regencia ";
  $sql5   .= "   inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
  $sql5   .= "   inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ";
  $sql5   .= "  WHERE ed95_i_escola = $iEscola ";
  $sql5   .= "  AND ed52_i_ano = $ano_censo ";
  $sql5   .= "   AND ed232_i_codigo = $ed232_i_codigo ";
  $sql5   .= "  AND ed74_c_resultadofinal='R' ";
  $sql5   .= "  AND ed74_c_resultadoaprov='R' ";
  $result5 = db_query($sql5);
  db_fieldsmemory($result5,0);
  $pdf->cell(8,4,$reprov_disc == "" || $reprov_disc == 0?"":$reprov_disc,1,0,"C",0);

}
$pdf->cell(1,4,"",1,1,"C",0);
$pdf->Output();
?>
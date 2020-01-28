<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//ini_set('display_errors', 'Off');
include("fpdf151/pdfwebseller.php");
require("libs/db_utils.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_periodoescola_classe.php");
include("classes/db_diasemana_classe.php");
include("classes/db_turmaachorario_classe.php");

// Função de comparação para ordenar pelo período, dia da semana e turma
function cmpPeriodo($a1, $a2) {

  if ($a1->sHoraInicio == $a2->sHoraInicio) {

    if ($a1->iDiaSemana == $a2->iDiaSemana) {
      return $a1->sNomeTurma > $a2->sNomeTurma ? 1 : -1;
    }

    return $a1->iDiaSemana > $a2->iDiaSemana ? 1 : -1;

  }

  return $a1->sHoraInicio > $a2->sHoraInicio ? 1 : -1;

}

$clregenciahorario     = new cl_regenciahorario;
$clperiodoescola       = new cl_periodoescola;
$cldiasemana           = new cl_diasemana;
$clturmaachorario      = new cl_turmaachorario;
$oDaoRecHumanoHoraDisp = new cl_rechumanohoradisp();

$escola              = db_getsession("DB_coddepto");
if ($escolahorario != "") {
  $condicao = "AND ed17_i_escola = $escolahorario";
} else {
  $condicao = "";
}

$sCampos  = "DISTINCT case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else ";
$sCampos .= "cgmcgm.z01_nome end as z01_nome,ed18_c_nome,ed18_i_codigo,ed17_i_turno as turno";

$result0  = $clregenciahorario->sql_record($clregenciahorario->sql_query("",
                                                                         $sCampos,
                                                                         "",
                                                                         "ed58_i_rechumano = $rechumano and ed58_ativo is true $condicao"
                                                                        )
                                          );
$escolas = "";
$sep     = "";
for ($x = 0; $x < $clregenciahorario->numrows; $x++) {
	
  db_fieldsmemory($result0,$x);
  if ($ed18_i_codigo != $ed18_i_codigo) {
    $escolas .= $sep.$ed18_i_codigo." - ".$ed18_c_nome."\n";
  }else{
  	$escolas = $ed18_i_codigo." - ".$ed18_c_nome."\n";
  }
}

$head1 = "RELATÓRIO DOS HORÁRIOS DO PROFESSOR";
$head2 = "Professor: ".@$z01_nome;
$head3 = "Ano: ".$anohorario;
$head4 = "Escola(s):\n".$escolas;
$pdf   = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(223);
$pdf->addpage('P');

$sCampos    = "min(ed17_h_inicio) as menorhorario,max(ed17_h_fim) as maiorhorario";
$result_per = $clregenciahorario->sql_record($clregenciahorario->sql_query("",
                                                                         $sCampos,
                                                                         "",
                                                                         "ed58_i_rechumano = $rechumano and ed58_ativo is true  $condicao"
                                                                        )
                                          );                                    
db_fieldsmemory($result_per,0);
$hora1         = (int)substr($menorhorario,0,2);
$hora2         = (int)substr($maiorhorario,0,2)+1;
$horainicial   = $hora1*100;
$horafinal     = $hora2*100;
$tempo_ini     = mktime($hora1,0,0,date("m"),date("d"),date("Y"));
$tempo_fim     = mktime($hora2,0,0,date("m"),date("d"),date("Y"));
$difer_minutos = ($tempo_fim-$tempo_ini)/60;
$alt_tab_hora  = $difer_minutos/5;
$qtd_hora      = $difer_minutos/60;
$larg_tabela   = 195;
$larg_coluna1  = 10;
$tabela1_top   = $pdf->getY();
$tabela1_left  = floor($pdf->getX());

////////Grade dias da semana
$result   = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                "ed32_i_codigo,ed32_c_abrev,ed32_c_descr",
                                                                "ed32_i_codigo",
                                                                "ed04_i_escola = $escola AND ed04_c_letivo = 'S'"
                                                               )
                                    );
$larg_dia = floor(($larg_tabela-$larg_coluna1)/$cldiasemana->numrows);
$pdf->cell($larg_coluna1,5,"",0,0,"C",0);
$pdf->setY($tabela1_top);
$pdf->setX($tabela1_left+$larg_coluna1);
$pdf->setfont('arial','b',7);

$iNumDias = $cldiasemana->numrows;
for ($x = 0; $x < $cldiasemana->numrows; $x++) {
	
  db_fieldsmemory($result,$x);
  $pdf->cell($larg_dia,5,$ed32_c_descr,1,0,"C",1);
  
}

////////Grade de fundo
$pdf->setY($tabela1_top+7);
for ($x = 0; $x < $qtd_hora; $x++) {
	
  $pdf->setX($tabela1_left+$larg_coluna1);
  $pdf->cell($larg_dia*$cldiasemana->numrows,$alt_tab_hora/$qtd_hora,"",1,1,"C",0);
  
}
$fim_tabelafundo = $pdf->getY();

////////Linhas verticais da grade de fundo
$left_ini = $tabela1_left+$larg_coluna1+$larg_dia;
for ($x = 0; $x < $cldiasemana->numrows-1; $x++) {
	
  $pdf->line($left_ini,$tabela1_top+7,$left_ini,$fim_tabelafundo);
  $left_ini += $larg_dia;
  
}

////////Grade dos horários
$pdf->setX($tabela1_left);
$top_ini = $tabela1_top+5;
$tt      = 0;
for ($t = $horainicial; $t <= $horafinal; $t += 1) {
	
  $pdf->setY($top_ini);
  $hora = strlen($t)==3?"0".$t:$t;
  $hora = substr($hora,0,2).":".substr($hora,2,2);
  if($hora <= $horafinal){
  if ($t != 2400) {
  	
    if (($t%100) == 0) {
      $pdf->cell($larg_coluna1,5,$hora,0,0,"C",0);
    }
  }
 
  $tt += 1;
  if ($tt == 60) {
  	
    $t += 40;
    $tt = 0;
    
  }
   }
  $top_ini += 0.2;
}

$pdf->setfont('arial','',6);

////////Horário do Docente
$var_left = $tabela1_left+$larg_coluna1;

$aSimult    = array();
$iIndSimult = -1;

$sWhereRecHumanoHoraDisp = "ed75_i_rechumano = {$rechumano} AND ed33_ativo is true";
if( isset( $escolahorario ) && !empty( $escolahorario ) ) {
  $sWhereRecHumanoHoraDisp .= " AND ed75_i_escola = {$escolahorario}";
}
$sSqlRecHumanoHoraDisp   = $oDaoRecHumanoHoraDisp->sql_query( null, "ed75_i_codigo", null, $sWhereRecHumanoHoraDisp );
$rsRecHumanoHoraDisp     = db_query( $sSqlRecHumanoHoraDisp );
$lRecHumanoHoraDisp      = pg_num_rows( $rsRecHumanoHoraDisp ) > 0;

if( $lRecHumanoHoraDisp ) {

  for ($x = 0; $x < $cldiasemana->numrows; $x++) {

    $ini_top = $tabela1_top+7;
    db_fieldsmemory($result,$x);
    $sCampos = "ed232_c_abrev,ed15_c_nome,ed58_i_codigo,ed17_h_inicio,ed17_h_fim,ed17_i_escola,ed57_c_descr,ed232_c_descr,ed08_c_descr, ed58_i_diasemana";
    $sOrder  = "ed58_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc,ed17_i_turno,escola.ed18_c_nome";
    $sWhere  = " ed58_i_rechumano = $rechumano AND ed58_i_diasemana = $ed32_i_codigo";
    $sWhere .= " and ed52_i_ano = $anohorario and ed58_ativo is true  $condicao";

    $result1 = $clregenciahorario->sql_record($clregenciahorario->sql_query("",
                                                                            $sCampos,
                                                                            $sOrder,
                                                                            $sWhere
                                              )
    );

    $sCampos  = "ed268_c_descr,ed20_i_codigo,ed270_i_codigo,ed08_c_descr,turno.ed15_c_nome";
    $sCampos .= ",ed17_h_inicio,ed17_h_fim,ed17_i_escola,ed18_i_codigo,ed18_c_nome,ed08_c_descr";
    $sOrder   = "ed270_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
    $sWhere   =  " ed270_i_rechumano = $rechumano AND ed270_i_diasemana = $ed32_i_codigo AND ed52_i_ano = $anohorario ";
    $result3  = $clturmaachorario->sql_record($clturmaachorario->sql_query("",
                                                                           $sCampos,
                                                                           $sOrder,
                                                                           $sWhere
                                              )
    );
    $tt         = 0;
    for ($t = $horainicial; $t <= $horafinal; $t += 1) {

      $hora = strlen($t)==3?"0".$t:$t;
      $hora = substr($hora,0,2).":".substr($hora,2,2);
      if ($clregenciahorario->numrows > 0) {

        for ($y = 0; $y < $clregenciahorario->numrows; $y++) {

          db_fieldsmemory($result1,$y);
          if (trim($hora) == trim($ed17_h_inicio)) {

            $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
            $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
            $difermin  = ($tempo_fim-$tempo_ini)/60;
            $difer     = ceil($difermin/2);
            $conta     = $y;
            $proximo   = true;
            $array     = array();

            while ($proximo == true) {

              $conta++;
              if ($clregenciahorario->numrows > $conta) {

                $oDados = db_utils::fieldsmemory($result1,$conta);
                if ($ed17_h_inicio == $oDados->ed17_h_inicio) {

                  $array[] = $conta;
                  $proximo = true;
                  $y++;

                }else{
                  $proximo=false;
                }
              }else{
                $proximo=false;
              }
            }
            if (count($array) >0) {

              $lista = "";
              $sep   = "";
              for ($e = 0; $e < count($array); $e++) {
                $lista = $array[$e];
                $sep   =",";
              }
            }


            if (count($array) > 0) {

              $iIndSimult++;
              $aSimult[$iIndSimult]->iCodigo         = $ed58_i_codigo;
              $aSimult[$iIndSimult]->sHoraInicio     = $ed17_h_inicio;
              $aSimult[$iIndSimult]->sHoraFim        = $ed17_h_fim;
              $aSimult[$iIndSimult]->iCodigoEscola   = $ed17_i_escola;
              $aSimult[$iIndSimult]->sNomePeriodo    = $ed08_c_descr;
              $aSimult[$iIndSimult]->sNomeTurno      = $ed15_c_nome;
              $aSimult[$iIndSimult]->sNomeTurma      = $ed57_c_descr;
              $aSimult[$iIndSimult]->sNomeDisciplina = $ed232_c_descr;
              $aSimult[$iIndSimult]->iDiaSemana      = $ed58_i_diasemana;

              for ($a = 0; $a < count($array); $a++) {

                $iIndSimult++;
                $oDados                                = db_utils::fieldsmemory($result1, $array[$a]);
                $aSimult[$iIndSimult]->iCodigo         = $oDados->ed58_i_codigo;
                $aSimult[$iIndSimult]->sHoraInicio     = $oDados->ed17_h_inicio;
                $aSimult[$iIndSimult]->sHoraFim        = $oDados->ed17_h_fim;
                $aSimult[$iIndSimult]->iCodigoEscola   = $oDados->ed17_i_escola;
                $aSimult[$iIndSimult]->sNomePeriodo    = $oDados->ed08_c_descr;
                $aSimult[$iIndSimult]->sNomeTurno      = $oDados->ed15_c_nome;
                $aSimult[$iIndSimult]->sNomeTurma      = $oDados->ed57_c_descr;
                $aSimult[$iIndSimult]->sNomeDisciplina = $oDados->ed232_c_descr;
                $aSimult[$iIndSimult]->iDiaSemana      = $oDados->ed58_i_diasemana;

              }
              $tempo_ini = mktime(substr($aSimult[$iIndSimult]->sHoraInicio,0,2),substr($aSimult[$iIndSimult]->sHoraInicio,3,2),0,1,1,1999);
              $tempo_fim = mktime(substr($aSimult[$iIndSimult]->sHoraFim,0,2),substr($aSimult[$iIndSimult]->sHoraFim,3,2),0,1,1,1999);
              $difermin  = ($tempo_fim-$tempo_ini)/60;
              $difer     = ceil($difermin/5);
              $alt_multi = $difer/4;
              $pdf->setXY($var_left,$ini_top);
              $texto  = "Atendimento Simultâneo \n";
              $texto .= "Escola: ".$ed17_i_escola."\n";
              $texto .= "Período: ".$aSimult[$iIndSimult]->sNomePeriodo." Período / ".$aSimult[$iIndSimult]->sNomeTurno."\n";
              $texto .= "Horário: ".$aSimult[$iIndSimult]->sHoraInicio ." / ". $aSimult[$iIndSimult]->sHoraFim."\n";
              $pdf->multicell($larg_dia,$alt_multi,$texto,1,"J",1,0);

            } else {

              $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
              $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
              $difermin  = ($tempo_fim-$tempo_ini)/60;
              $difer     = ceil($difermin/5);
              $alt_multi = $difer/4;
              $pdf->setXY($var_left,$ini_top);
              $texto  = "Escola: ".$ed17_i_escola."\n".substr(trim($ed232_c_descr),0,10)."\nTurma: ";
              $texto .= substr(trim($ed57_c_descr),0,10)."\n".substr(trim($ed15_c_nome),0,6)."-$ed17_h_inicio / $ed17_h_fim";
              $pdf->multicell($larg_dia,$alt_multi,$texto,1,"J",1,0);

            }
          }
        }
      }

      if ($clturmaachorario->numrows > 0) {

        for ($w = 0; $w < $clturmaachorario->numrows; $w++) {

          db_fieldsmemory($result3,$w);
          if (trim($hora) == trim($ed17_h_inicio)) {

            $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
            $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
            $difermin  = ($tempo_fim-$tempo_ini)/60;
            $difer     = ceil($difermin/5);
            $alt_multi = $difer/4;
            $pdf->setXY($var_left,$ini_top);
            $texto  = "Escola: ".$ed17_i_escola."\n".substr(trim($ed268_c_descr),0,10)."\n".substr(trim($ed15_c_nome),0,6);
            $texto .= "-$ed17_h_inicio / $ed17_h_fim";
            $pdf->multicell($larg_dia,$alt_multi,$texto,1,"J",1,0);

          }
        }
      }

      $tt += 1;
      if ($tt == 60) {

        $t += 40;
        $tt = 0;

      }

      $ini_top += 0.2;
    }
    $var_left += $larg_dia;
  }
}

if (count($aSimult) > 0) { // Tem atendimento simultâneo

  ////////Grade de fundo (atendimento simultâneo)
  $tabelafundotop = $pdf->getY();
  //echo "Y: $tabelafundotop<br><br>";
  $tabela1_leftbaixo = 20;
  $larg_dia1 = 20;
  $pdf->setFont('arial', 'B', 7);
  $pdf->setXY(20,$fim_tabelafundo + 5);
  $pdf->cell($larg_dia * $iNumDias,5,"Atendimento Simultâneo",1,1,"C",0);
  $pdf->setX(20);
  for ($x = 0; $x < $cldiasemana->numrows; $x++) {
	
    db_fieldsmemory($result,$x);
    $pdf->cell($larg_dia,5,$ed32_c_descr,1,0,"C",1);
  
  }
  $nYFimDiaSemana = $pdf->getY();

  usort($aSimult, 'cmpPeriodo');

  $pdf->setfillcolor(239);
  $lNovoPer = false;
  $lNovoDia = false;
  $sHoraIni = '';
  $iDiaSem  = -1;
  $nYmaior  = $nYFimDiaSemana + 5;
  for ($a = 0; $a < count($aSimult); $a++) {

    if ($sHoraIni != $aSimult[$a]->sHoraInicio) {

      if ($nYmaior > $pdf->h - 45) {

        if ($a == 0) {

          $pdf->addPage();
          $nYmaior        = $pdf->getY();
          $nYFimDiaSemana = $nYmaior;

        } else {

          // Última linha horizontal
          $pdf->line(20, $pdf->getY(), ($larg_dia * $iNumDias) + 20, $pdf->getY());
          // Linhas verticais da grade de fundo
          $left_ini = 20;
          for ($x = 0; $x < $cldiasemana->numrows + 1; $x++) {
          
            $pdf->line($left_ini,$nYFimDiaSemana,$left_ini,$nYmaior);
            $left_ini += $larg_dia;
          
          }
  
          $pdf->addPage();
          $nYmaior        = $pdf->getY();
          $nYFimDiaSemana = $nYmaior;
  
        }

      }
      $pdf->setFont('arial', 'B', 7);
      $pdf->setXY(20, $nYmaior);
      $sTextoPeriodo = $aSimult[$a]->sNomePeriodo.' Período / '.$aSimult[$a]->sNomeTurno;
      $pdf->cell($larg_dia * $iNumDias, 3, '', 1, 1, 'C', true, 0);
      $nYini    = $pdf->getY();
      $sHoraIni = $aSimult[$a]->sHoraInicio;
      $lNovoPer = true;
      $pdf->setFont('arial', '', 7);
  
    }

    if ($iDiaSem != $aSimult[$a]->iDiaSemana) {
  
      $pdf->setY($nYini);
      $iDiaSem  = $aSimult[$a]->iDiaSemana;
      $lNovoDia = true;
  
    }
  
    if (!$lNovoPer && !$lNovoDia)  {
      $sQuebra = "\n";
    } else {
      $sQuebra = '';
    }
  
    $nX    = ($larg_dia * ($aSimult[$a]->iDiaSemana - 2)) + 20;
    $nYTmp = $pdf->getY();
    if ($lNovoDia || $lNovoPer) {

      $pdf->setXY($nX, $nYTmp - 2.5);
      $pdf->setFont('arial', 'B', 7);
      $pdf->cell($larg_dia - 1, $alt_multi, $sTextoPeriodo, 0, 0, 'C', false, 0);
      $pdf->setFont('arial', '', 7);
      
    }
    $pdf->setXY($nX, $nYTmp);
    $texto  = $sQuebra."Turma: ". substr(trim($aSimult[$a]->sNomeTurma),0,8)."\n";
    $texto .= "Disciplina: ".substr(trim($aSimult[$a]->sNomeDisciplina),0,10);
    $pdf->multicell($larg_dia - 2, $alt_multi, $texto, 0, 1, 'J', false, 0);
    
    $nYmaior  = $nYmaior > $pdf->getY() ? $nYmaior : $pdf->getY();
    $lNovoPer = false;
    $lNovoDia = false;

  }

  // Última linha horizontal
  $pdf->line(20, $pdf->getY(), ($larg_dia * $iNumDias) + 20, $pdf->getY());
  

  ////////Linhas verticais da grade de fundo
  $left_ini = 20;
  for ($x = 0; $x < $cldiasemana->numrows + 1; $x++) {
	
    $pdf->line($left_ini,$nYFimDiaSemana,$left_ini,$nYmaior);
    $left_ini += $larg_dia;
  
  }

}
$pdf->Output();
?>
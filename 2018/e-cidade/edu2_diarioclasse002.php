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

require_once ("fpdf151/pdfwebseller.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");

$clmatricula         = new cl_matricula;
$clregencia          = new cl_regencia;
$clescola            = new cl_escola;
$clprocavaliacao     = new cl_procavaliacao;
$clregenciahorario   = new cl_regenciahorario;
$clregenciaperiodo   = new cl_regenciaperiodo;
$clperiodocalendario = new cl_periodocalendario;
$escola              = db_getsession("DB_coddepto");
$discglob            = false;
$sSqlRegencia        = $clregencia->sql_query("","*","ed59_i_ordenacao"," ed59_i_codigo in ($disciplinas)");
$sResultRegencia     = $clregencia->sql_record($sSqlRegencia);


if ($clregencia->numrows == 0) {

  echo "<table width='100%'>";
  echo " <tr>";
  echo "  <td align='center'>";
  echo "   <font color='#FF0000' face='arial'>";
  echo "    <b>Nenhuma matrícula para a turma selecionada<br>";
  echo "    <input type='button' value='Fechar' onclick='window.close()'></b>";
  echo "   </font>";
  echo "  </td>";
  echo " </tr>";
  echo "</table>";
  exit;

}

$oGet         = db_utils::postMemory($_GET);

function Abreviar($nome,$max) {

  if (strlen(trim($nome)) > $max) {

    $strinv   = strrev(trim($nome));
    $ultnome  = substr($strinv,0,strpos($strinv," "));
    $ultnome  = strrev($ultnome);
    $nome     = strrev($strinv);
    $prinome  = substr($nome,0,strpos($nome," "));
    $nomes    = strtok($nome, " ");
    $iniciais = "";

    while($nomes):
      if (($nomes == 'E') || ($nomes == 'DE') || ($nomes == 'DOS') ||
         ($nomes == 'DAS') || ($nomes == 'DA') || ($nomes == 'DO')) {

        $iniciais .= " ".$nomes;
        $nomes = strtok(" ");

      } else if (($nomes == $ultnome) || ($nomes == $prinome)) {

        $nome  = "";
        $nomes = strtok(" ");

      } else {

        $iniciais .= " ".$nomes[0].".";
        $nomes     = strtok(" ");

      }
    endwhile;
    $nome  =  $prinome;
    $nome .= $iniciais;
    $nome .= " ".$ultnome;
 }
 return trim($nome);
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$iLinhasRegencia = $clregencia->numrows;

for ($x = 0; $x < $iLinhasRegencia; $x++) {

  db_fieldsmemory($sResultRegencia,$x);

  $sCamposProc     = "ed09_i_codigo, ed09_c_descr, ed09_c_abrev, ed41_i_procresultvinc, ed41_i_procavalvinc ";
  $sSqlProcAval    = $clprocavaliacao->sql_query( "", $sCamposProc, "", " ed41_i_codigo = {$periodo}" );
  $sResultProcAval = $clprocavaliacao->sql_record($sSqlProcAval);
  db_fieldsmemory($sResultProcAval,0);
  $oDadosPeriodo = db_utils::fieldsMemory($sResultProcAval, 0);
  $oRecuperacao  = '';

  if ($oDadosPeriodo->ed41_i_procresultvinc != "0") {
    $oRecuperacao = ResultadoAvaliacaoRepository::getResultadoAvaliacaoByCodigo($oDadosPeriodo->ed41_i_procresultvinc);
  }

  if ($oDadosPeriodo->ed41_i_procavalvinc != "0") {
    $oRecuperacao = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo($oDadosPeriodo->ed41_i_procavalvinc);
  }
  
  if (!empty($oRecuperacao)) {

    $avaliacao = "true";
    $falta     = "true";
  }
  $sCampos           = "ed52_i_codigo,ed52_c_aulasabado,ed53_d_inicio,ed53_d_fim";
  $sWhere            = " ed53_i_calendario = $ed57_i_calendario AND ed53_i_periodoavaliacao = $ed09_i_codigo";
  $sSqlPeriodoCal    = $clperiodocalendario->sql_query("",$sCampos,"",$sWhere);
  $sResultPeriodoCal = $clperiodocalendario->sql_record($sSqlPeriodoCal);

  if ( pg_num_rows( $sResultPeriodoCal ) == 0) {

    $sMensagemErro = "Período de avaliação e período do calendário selecionados, não equivalentes.";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagemErro}");
  }

  db_fieldsmemory($sResultPeriodoCal,0);

  $dataperiodo = " $ed09_c_descr - ___/___/_______ à ___/___/_______";
  if ($oGet->iModeloRelatorio != 4) {
 	  $dataperiodo = $ed09_c_descr." - ".@db_formatar($ed53_d_inicio,'d')." à ".@db_formatar($ed53_d_fim,'d');
  }
  $sCamposRegenciaHorario = "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as regente";
  $sWhereRegHorario       = " ed58_i_regencia = $ed59_i_codigo and ed58_ativo is true  ";
  $sSqlRegenciaHorario    = $clregenciahorario->sql_query("",$sCamposRegenciaHorario,"",$sWhereRegHorario);
  $sResutlRegenciaHorario = $clregenciahorario->sql_record($sSqlRegenciaHorario);

  if ($clregenciahorario->numrows > 0) {
    db_fieldsmemory($sResutlRegenciaHorario,0);
  } else {
    $regente = "";
  }

  $sCamposRegPeriodo = " ed78_i_aulasdadas as aulas";
  $sWhereRegPeriodo  = " ed78_i_regencia = $ed59_i_codigo AND ed78_i_procavaliacao = $periodo";
  $sSqlRegPeriodo    = $clregenciaperiodo->sql_query("",$sCamposRegPeriodo,"",$sWhereRegPeriodo);
  $sResultRegPeriodo = $clregenciaperiodo->sql_record($sSqlRegPeriodo);

  if ($clregenciaperiodo->numrows > 0) {
    db_fieldsmemory($sResultRegPeriodo,0);
  } else {
    $aulas = "";
  }
  if ($informadiasletivos == "S") {
    $colunas = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,1);
  } else {
    $colunas = $qtdecolunas;
  }
  if ($avaliacao == "true" && $falta == "true") {
    $larguraindiv = round(175/$colunas,1);
  } else if ($avaliacao == "false" && $falta == "true") {
    $larguraindiv = round(205/$colunas,1);
  } else if ($avaliacao == "false" && $falta == "false") {
    $larguraindiv = round(219/$colunas,1);
  } else if ($avaliacao == "true" && $falta == "false")  {
    $larguraindiv = round(180/$colunas,1);
  }
  $larguracolunas = $colunas*$larguraindiv;
  
  $pdf->setfillcolor(235);
  $head1 = "DIÁRIO DE CLASSE";
  $head2 = "Curso: $ed29_i_codigo - $ed29_c_descr";
  $head3 = "Calendário: $ed52_c_descr";
  $head4 = "Etapa: $ed11_c_descr";
  $head5 = "Período: $ed09_c_descr";
  $head7 = "Disciplina: $ed232_c_descr";
  $head8 = "Regente: $regente";
  $head6 = "Turma: $ed57_c_descr";
  $head9 = "Aulas Dadas: $aulas";
  $pdf->addpage('L');
  $pdf->setfont('arial','b',8);
  if ($avaliacao == "true" && $falta == "false") {
 	  $larg = 35;
  } else if ($avaliacao == "false" && $falta == "true") {
 	  $larg = 10;
  } else if ($avaliacao == "false" && $falta == "false") {
   	$larg = 0;
  } else {
  	$larg = 45;
  }
  
  $pdf->cell(60+$larguracolunas+$larg,4,@$dataperiodo,0,1,"C",1);
  
  $pdf->cell(50,4,"",1,0,"C",0);
  $pdf->cell(10,4,"Mês >",1,0,"R",0);
  if ($informadiasletivos == "S") {

    $array_meses = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,3);
    $pdf->setfont('arial','b',7);

    for ($r = 0; $r < count($array_meses); $r++) {

      $qtd_diasmes = explode(",",$array_meses[$r]);
      $iquebra     = 0;
      if ($r == (count($array_meses)-1) && ($avaliacao != "true" && $falta != "true")) {
        $iquebra = 1;
      }
      $pdf->cell($larguraindiv*$qtd_diasmes[1],4,$qtd_diasmes[0],1,$iquebra,"C",0);

    }

  } else {

    if ($avaliacao != "true" && $falta != "true") {
   		$quebras = 1;
   	} else {
   		$quebras = 0;
   	}
    $pdf->cell($larguracolunas,4,"",1,$quebras,"R",0);
  }

  $pdf->setfont('arial','b',8);
  $sLabel = "Avaliações";
  if (!empty($oRecuperacao)) {
    $sLabel = "";
  }
  if ($avaliacao == "true" && $falta == "false") {
    $pdf->cell(35,4, $sLabel,1,1,"C",0);
  }
  if ($falta == "true" && $avaliacao == "false") {
    $pdf->cell(10,4,"",1,1,"C",0);
  }
  if ($avaliacao == "true" && $falta == "true") {

    $pdf->cell(35,4,$sLabel,1,0,"C",0);
    $pdf->cell(10,4,"",1,1,"R",0);
  }
  
  $pdf->setfont('arial','',8);
  $pdf->cell(5,4,"N°",1,0,"C",0);
  $pdf->cell(45,4,"Nome do Aluno",1,0,"C",0);
  $pdf->setfont('arial','b',8);
  $pdf->cell(10,4,"Dia >",1,0,"R",0);
  
  if ($informadiasletivos == "S") {

    $n_dias = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,2);
    $pdf->setfont('arial','b',6);

    for ($r = 0; $r < count($n_dias); $r++) {

      $iQuebra = 0;
      if ($r == (count($n_dias)-1) && ($avaliacao != "true" && $falta != "true")) {
      	$iQuebra = 1;
      }

      $umdia = explode("-",$n_dias[$r]);
      $pdf->cell($larguraindiv,4,$umdia[0],1,$iQuebra,"C",0);
    }
  } else {

    
    for ($r = 0; $r < $colunas; $r++) {

      $iQuebra = 0;
      if ($r == ($colunas-1) && ($avaliacao != "true" && $falta != "true")) {
      	$iQuebra = 1;
      }
      $pdf->cell($larguraindiv,4,"",1,$iQuebra,"C",0);
    }
  }
  $pdf->setfont('arial','b',8);
  
  if ($avaliacao == "true" && $falta == "false") {

    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(5,4,"",1,1,"C",0);
  }
  
  if ($avaliacao == "true" && $falta == "true") {

   if (!empty($oRecuperacao)) {

      $pdf->cell(10,4, $oRecuperacao->getDescricaoAbreviada(), 1,0,"C",0);
      $pdf->cell(10,4, $ed09_c_abrev,1,0,"C",0);
      $pdf->cell(5,4,"N°",1,0,"C",0);
      $pdf->cell(10,4,"MF",1,0,"C",0);
      $pdf->cell(10,4,"Ft",1,1,"C",0);

    } else {

      $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"",1,0,"C",0);
      $pdf->cell(5,4,"N°",1,0,"C",0);
      $pdf->cell(5,4,"Ft",1,1,"C",0);
    }

  }

  if ($falta == "true" && $avaliacao == "false") {

    $pdf->cell(5,4,"N°",1,0,"C",0);
    $pdf->cell(5,4,"Ft",1,1,"C",0);

  }
  $condicao = "";
  if ($active == "SIM") {
    $condicao .= "and ed60_c_situacao IN ('MATRICULADO', 'TROCA DE TURMA')";
  }

  if ($trocaTurma == 1) {
    $condicao .= " and ed60_c_situacao != 'TROCA DE TURMA'";
  }
  $sCamposMat          = " ed60_i_numaluno, ed60_i_aluno, ed60_i_codigo, ed47_v_nome, ed60_c_situacao, ed60_matricula";
  $sOrderMat           = " ed60_i_numaluno, to_ascii(ed47_v_nome), ed60_c_ativa";
  $sWhereMat           = " ed60_i_turma = $ed57_i_codigo AND ed221_i_serie = $ed59_i_serie $condicao";
  $sSqlMat             = $clmatricula->sql_query("",$sCamposMat,$sOrderMat,$sWhereMat);
  $sResultMat          = $clmatricula->sql_record($sSqlMat);
  $limite              = 33;
  $cont                = 0;
  $cont_geral          = 0;
  $cor1                = 0;
  $cor2                = 1;
  $cor                 = "";
  for ($y = 0; $y < $clmatricula->numrows; $y++) {

    db_fieldsmemory($sResultMat,$y);
    
    /**
     * Verifica se foi selecionado apenas um turno referente e adiciona apenas os alunos que precisam aparecer
     * no relatório
     */
    if ($iTurno != 0 ) {
    
      $oMatricula      = MatriculaRepository::getMatriculaByCodigo( $ed60_i_codigo );
      $aTurnos         = $oMatricula->getTurnosVinculados();
      $lTurnoVinculado = false;
    
      foreach ( $aTurnos as $oTurnoReferente ) {
    
        if ( $iTurno == $oTurnoReferente->ed336_turnoreferente ) {
          $lTurnoVinculado = true;
        }
      }
    
      if ( !$lTurnoVinculado ) {
        continue;
      }
    
    }

    $cont++;
    $cont_geral++;
    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }
    $lPossuiMinino = true;
    $oNotaAbaixo   = null;
    if (!empty($oRecuperacao)) {

      $sTabela  = " inner join diarioresultado on ed95_i_codigo = ed73_i_diario";
      $sCampos  = " ed73_i_valornota as nota, ed73_c_aprovmin as aproveitamento_minimo";
      $sWhere   = " ed73_i_procresultado = ";
      if ($oRecuperacao instanceof AvaliacaoPeriodica) {

        $sTabela  = " inner join diarioavaliacao on ed95_i_codigo = ed72_i_diario";
        $sCampos  = " ed72_i_valornota as nota, ed72_c_aprovmin as aproveitamento_minimo";
        $sWhere   = " ed73_i_procavaliacao = ";
      }
      $sSqlNotaAbaixo  = "select {$sCampos} ";
      $sSqlNotaAbaixo .= "  from diario";
      $sSqlNotaAbaixo .= "       {$sTabela} where {$sWhere} {$oRecuperacao->getCodigo()}";
      $sSqlNotaAbaixo .= "   and ed95_i_aluno    = {$ed60_i_aluno}";
      $sSqlNotaAbaixo .= "   and ed95_i_regencia = {$ed59_i_codigo}";
      $rsNotaAbaixo    = db_query($sSqlNotaAbaixo);
      if (pg_num_rows($rsNotaAbaixo) > 0) {

        $oNotaAbaixo   = db_utils::fieldsMemory($rsNotaAbaixo, 0);
        if ($oNotaAbaixo->aproveitamento_minimo == 'N' && $oNotaAbaixo->nota != "") {
          $lPossuiMinino = false;
        }
      }
    }

    if (!empty($oRecuperacao) && $lPossuiMinino) {

      $cont--;
      $cont_geral--;
      continue;
    }
    $sSqlDiarioAval    = " SELECT ed72_c_amparo as amparo,ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev ";
    $sSqlDiarioAval   .= "      FROM diarioavaliacao ";
    $sSqlDiarioAval   .= "           inner join diario on ed95_i_codigo = ed72_i_diario ";
    $sSqlDiarioAval   .= "           left join amparo on ed81_i_diario = ed95_i_codigo ";
    $sSqlDiarioAval   .= "           left join convencaoamp on ed250_i_codigo = ed81_i_convencaoamp ";
    $sSqlDiarioAval   .= "      WHERE ed95_i_aluno = $ed60_i_aluno ";
    $sSqlDiarioAval   .= "            AND ed95_i_regencia = $ed59_i_codigo ";
    $sSqlDiarioAval   .= "            AND ed72_i_procavaliacao = $periodo ";
    $sResultDiarioAval = db_query($sSqlDiarioAval);
    $iLinhasDiarioAval = pg_num_rows($sResultDiarioAval);
    if ($iLinhasDiarioAval > 0) {
      db_fieldsmemory($sResultDiarioAval,0);
    } else {
      $amparo = "";
    }
    $pdf->setfont('arial','',6);
    $pdf->cell(5,4,$ed60_i_numaluno,1,0,"C",0);
    
    if (strlen(trim($ed47_v_nome)) > 43) {
      $pdf->setfont('arial','',5);
    }

    $pdf->cell(55, 4, substr($ed47_v_nome, 0, 47),1,0,"L",0);

    if ($amparo == "S") {

      $iQuebra = 0;
      
      if ($avaliacao != "true" && $falta != "true") {
        $iQuebra = 1;
      }
      
      $pdf->setfont('arial','b',11);
      if ($ed81_i_justificativa != "") {
        $pdf->cell($larguraindiv*$colunas,4,"AMPARADO",1,$iQuebra,"C",0);
      } else {
        $pdf->cell($larguraindiv*$colunas,4,"$ed250_c_abrev",1,0,"C",0);
      }
      $pdf->setfont('arial','b',8);

    } else {

      if (trim($ed60_c_situacao) != "MATRICULADO") {

        $pdf->setfont('arial','b',11);
        $sSituacao = trim(Situacao($ed60_c_situacao,$ed60_i_codigo));

        if ( trim($ed60_c_situacao) == "TRANSFERIDO FORA" || trim($ed60_c_situacao) == "TRANSFERIDO REDE") {
          $sSituacao = "TRANSFERIDO";
        }

        $pdf->cell($larguraindiv*$colunas,4,$sSituacao,1,0,"C",0);
        $pdf->setfont('arial','b',8);
      } else {

        $pdf->setfont('arial','b',8);
        $at = $pdf->getY();
        $lg = $pdf->getX();
        for ($r = 0; $r < $colunas; $r++) {

          $pdf->setfont('arial','b',12);
          $pdf->cell($larguraindiv,4,"",1,0,"C",0);          
          
          if ($oGet->lPontos == "true") { 
          	$pdf->Text($lg+($larguraindiv*30/100),$at+2,".");
          }
          
          if ($r == ($colunas-1) && ($avaliacao != "true" && $falta != "true")) {
            $pdf->cell(1,4,"",0,1,"C",0);
          }
          $lg = $pdf->getX();
        }
        $pdf->setfont('arial','b',8);
      }
    }

    if (trim($ed60_c_situacao) == "MATRICULADO") {

      if (empty($oRecuperacao)) {
        
        for ($r = 0; $r < 7; $r++) {

     	    if ($r == 6 && ($avaliacao == "true" && $falta != "true")) {
            $pdf->cell(5,4,"",1,1,"C",0);
     	    } else if ($avaliacao == "true" && $falta == "false") {
     		    $pdf->cell(5,4,"",1,0,"C",0);
     	    } else if ($avaliacao == "true" && $falta == "true") {
     	      $pdf->cell(5,4,"",1,0,"C",0);
     	    }
        }
      } else {

        $pdf->cell(10, 4, ArredondamentoNota::formatar($oNotaAbaixo->nota, $ed52_i_ano), 1, 0, "C", 0);
        $pdf->cell(10, 4, "", 1, 0, "C", 0);
      }

      if ($falta == "true") {

        $pdf->cell(5, 4, $ed60_i_numaluno, 1, 0, "C", 0);
        if ( empty( $oRecuperacao ) ) {
          $pdf->cell(5,4,"",1,1,"C",0);
        } else {
          
          $pdf->cell(10,4,"",1,0,"C",0);
          $pdf->cell(10,4,"",1,1,"C",0);
        }
      }
    } else {

      $sSituacao = trim(Situacao($ed60_c_situacao,$ed60_i_codigo));
    	$sBorda         = 1;
    	$iTamanhoCelula = 45;
    	
      if ( trim($ed60_c_situacao) == "TRANSFERIDO FORA" || trim($ed60_c_situacao) == "TRANSFERIDO REDE") {
        $sSituacao = "TRANSFERIDO";
      }
    	if ($oGet->iModeloRelatorio == 4) {
    		
    		$sSituacao      = "";
    		$iTamanhoCelula = 10;
    	}
    	
      $pdf->setfont('arial','b',11);
      $pdf->cell($iTamanhoCelula, 4, $sSituacao, $sBorda, 1, "C", 0);
      $pdf->setfont('arial','b',8);
    }
    
    if ($cont == $limite && $cont_geral < $clmatricula->numrows) {

      $pdf->setfont('arial','b',8);
      $pdf->setfont('arial','b',8);
      if ($avaliacao == "true" && $falta == "false") {
   	    $larg = 35;
      } else if ($avaliacao == "false" && $falta == "true") {
     	  $larg = 10;
      } else if ($avaliacao == "false" && $falta == "false") {
     	  $larg = 0;
      } else {
     	  $larg = 45;
      }
      $pdf->cell(($larguracolunas+60+$larg)/2,5,"Entregue em _____/_____/_____ POR_______________________",1,0,"L",0);
      $pdf->cell(($larguracolunas+60+$larg)/2,5,"Revisado em _____/_____/_____ POR_______________________",1,1,"L",0);
      $pdf->cell(($larguracolunas+60+$larg)/2,5,"Processado em _____/_____/_____ POR_____________________",1,0,"L",0);
      $pdf->cell(($larguracolunas+60+$larg)/2,5,"Assinatura do professor:_________________________________",1,1,"L",0);
      $pdf->addpage("L");
      if ($avaliacao == "true" && $falta == "false") {
 	      $larg = 35;
      } else if ($avaliacao == "false" && $falta == "true") {
 	      $larg = 10;
      } else if ($avaliacao == "false" && $falta == "false") {
      	$larg = 0;
      } else {
  	    $larg = 45;
      }
      $pdf->cell(60+$larguracolunas+$larg,4,@$dataperiodo,0,1,"C",1);
      $pdf->cell(50,4,"",1,0,"C",0);
      $pdf->cell(10,4,"Mês >",1,0,"R",0);
      if ($informadiasletivos == "S") {

        $array_meses = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,3);
        $pdf->setfont('arial','b',7);

        for ($r = 0; $r < count($array_meses); $r++) {

          $qtd_diasmes = explode(",",$array_meses[$r]);
          $iquebra     = 0;
          if ($r == (count($array_meses)-1) && ($avaliacao != "true" && $falta != "true")) {
            $iquebra = 1;
          }
          
          $pdf->cell($larguraindiv*$qtd_diasmes[1],4,$qtd_diasmes[0],1,$iquebra,"C",0);
        }
      } else {

        if ($avaliacao != "true" && $falta != "true") {
   		    $quebras = 1;
   	    } else {
   		    $quebras = 0;
   	    }
        $pdf->cell($larguracolunas,4,"",1,$quebras,"R",0);
      }
      $pdf->setfont('arial','b',8);
      if ($avaliacao == "true" && $falta == "false") {
        $pdf->cell(35,4,"Avaliações",1,1,"R",0);
      }
      if ($falta == "true" && $avaliacao == "false") {
        $pdf->cell(10,4,"",1,1,"R",0);
      }
      if ($avaliacao == "true" && $falta == "true") {

        $pdf->cell(35,4,"Avaliações",1,0,"R",0);
        $pdf->cell(10,4,"",1,1,"R",0);

      }
      $pdf->setfont('arial','',8);
      $pdf->cell(5,4,"N°",1,0,"C",0);
      $pdf->cell(45,4,"Nome do Aluno",1,0,"C",0);
      $pdf->setfont('arial','b',8);
      $pdf->cell(10,4,"Dia >",1,0,"R",0);

      if ($informadiasletivos == "S") {

        $n_dias = DiasLetivos($ed53_d_inicio,$ed53_d_fim,$ed52_c_aulasabado,$ed52_i_codigo,2);
        $pdf->setfont('arial','b',6);
        for ($r = 0; $r < count($n_dias); $r++) {

          $iQuebra = 0;
          if ($r == (count($n_dias)-1) && ($avaliacao != "true" && $falta != "true")) {
      	    $iQuebra = 1;
          }
          $umdia = explode("-",$n_dias[$r]);
          $pdf->cell($larguraindiv,4,$umdia[0],$iQuebra,0,"C",0);
        }
      } else {

        for ($r = 0; $r < $colunas; $r++) {
      
          $iQuebra = 0;
          if ($r == ($colunas-1) && ($avaliacao != "true" && $falta != "true")) {
          	$iQuebra = 1;
          }
          $pdf->cell($larguraindiv,4,"",1,$iQuebra,"C",0);
        }
      }
      
      if ($avaliacao == "true" && $falta == "false") {
    
        $pdf->cell(5,4,"B",1,0,"C",0);
        $pdf->cell(5,4,"",1,0,"C",0);
        $pdf->cell(5,4,"",1,0,"C",0);
        $pdf->cell(5,4,"",1,0,"C",0);
        $pdf->cell(5,4,"",1,0,"C",0);
        $pdf->cell(5,4,"",1,0,"C",0);
        $pdf->cell(5,4,"",1,1,"C",0);
      }
      
      if ($avaliacao == "true" && $falta == "true") {
    
        if (!empty($oRecuperacao)) {
    
          $pdf->cell(10, 4,"",1,0,"C",0);
          $pdf->cell(10, 4,"",1,0,"C",0);
          $pdf->cell(5,  4,"N°",1,0,"C",0);
          $pdf->cell(10, 4,"",1,0,"C",0);
          $pdf->cell(10, 4,"Ft",1,1,"C",0);
        } else {
    
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"",1,0,"C",0);
          $pdf->cell(5,4,"N°",1,0,"C",0);
          $pdf->cell(5,4,"Ft",1,1,"C",0);
        }
      }

      if ($falta == "true" && $avaliacao == "false") {
    
        $pdf->cell(5,4,"N°",1,0,"C",0);
        $pdf->cell(5,4,"Ft",1,1,"C",0);
      }
      $cont = 0;
    }
  }
  
  $termino = $pdf->getY();
  
  for ($t = $cont; $t < $limite; $t++) {

    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }

    $pdf->cell(5,4,"",1,0,"C",0);
    $pdf->cell(55,4,"",1,0,"C",0);
    $pdf->setfont('arial','b',8);
    $at = $pdf->getY();
    $lg = $pdf->getX();

    for ($r = 0; $r < $colunas; $r++) {

      $pdf->setfont('arial','b',12);
      $pdf->cell($larguraindiv,4,"",1,0,"C",0);
      
      if ($oGet->lPontos == "true") {
        $pdf->Text($lg+($larguraindiv*30/100),$at+2,".");
      }
      if ($r == ($colunas-1) && ($avaliacao != "true" && $falta != "true")) {
        $pdf->cell(1,4,"",0,1,"C",0);
      }
      $lg = $pdf->getX();
    }

    $pdf->setfont('arial','b',8);
    $iMaximoQuadrosAvaliacao  = 7;
    $iTamanhoQuadrosAvaliacao = 5;
    
    if (!empty($oRecuperacao)) {

      $iMaximoQuadrosAvaliacao  = 2;
      $iTamanhoQuadrosAvaliacao = 10;
    }
    for ($r = 0; $r < $iMaximoQuadrosAvaliacao; $r++) {

   	  if ($r == 6 && ($avaliacao == "true" && $falta != "true")) {
        $pdf->cell(5,4,"",1,1,"C",0);
   	  } else if ($avaliacao == "true" && $falta == "false") {
   		  $pdf->cell(5,4,"",1,0,"C",0);
   	  } else if ($avaliacao == "true" && $falta == "true") {
   	    $pdf->cell($iTamanhoQuadrosAvaliacao, 4, "", 1, 0, "C", 0);
   	  }
    }

    if ($falta == "true") {

      $pdf->cell(5,4,"",1,0,"C",0);
      if ( empty( $oRecuperacao ) ) {
        $pdf->cell(5,4,"",1,1,"C",0);
      } else {
        
        $pdf->cell(10,4,"",1,0,"C",0);
        $pdf->cell(10,4,"",1,1,"C",0);
      }
    }
  }
  $pdf->setfont('arial','b',8);
  if ($avaliacao == "true" && $falta == "false") {
 	  $larg = 35;
  } else if ($avaliacao == "false" && $falta == "true") {
   	$larg = 10;
  } else if ($avaliacao == "false" && $falta == "false") {
   	$larg = 0;
  } else {
   	$larg = 45;
  }
  $pdf->cell(($larguracolunas+60+$larg)/2,5,"Entregue em _____/_____/_____ POR_______________________",1,0,"L",0);
  $pdf->cell(($larguracolunas+60+$larg)/2,5,"Revisado em _____/_____/_____ POR_______________________",1,1,"L",0);
  $pdf->cell(($larguracolunas+60+$larg)/2,5,"Processado em _____/_____/_____ POR_____________________",1,0,"L",0);
  $pdf->cell(($larguracolunas+60+$larg)/2,5,"Assinatura do professor:_________________________________",1,1,"L",0);
}
$pdf->Output();
?>
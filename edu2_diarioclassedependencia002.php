<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


  require_once("libs/db_utils.php");
  require_once("libs/db_stdlibwebseller.php");
  require_once("fpdf151/pdfwebseller.php");
  
  /* Transformo o array do get em um objeto */
  $oGet = db_utils::postMemory($_GET);
  
  /* requiro e instancio as classes utilizadas */
  $oDaoTurma                = db_utils::getdao('turma');
  $oDaoMatricula            = db_utils::getdao('matricula');
  $oDaoMatriculaDependencia = db_utils::getdao('matriculadependencia');
  $oDaoParametros           = db_utils::getdao('edu_parametros');
  $oDaoRegencia             = db_utils::getdao('regencia');
  $oDaoRegenciaHora         = db_utils::getdao('regenciahorario');
  $oDaoRegenciaPeriodo      = db_utils::getdao('regenciaperiodo');
  $oDaoProcResult           = db_utils::getdao('procresultado');
  $oDaoAvalCompoeres        = db_utils::getdao('avalcompoeres');
  $oDaoProcAvaliacao        = db_utils::getdao('procavaliacao');
  $oDaoPerCalendario        = db_utils::getdao('periodocalendario');
  $oDaoDiarioAvaliacao      = db_utils::getdao('diarioavaliacao');
  $oDaoAbonoFalta           = db_utils::getdao('abonofalta');
  
  $lResultEdu     = eduparametros($oGet->iEscola);
  $lNotaBranco    = VerParametroNota($oGet->iEscola);
  $lDiscGlob      = false;
  
  /* Busco o ano do calendário */
  $sCamposTurma   = " ed52_i_ano AS anocalendario ";
  $sWhereTurma    = " ed220_i_codigo = ".$oGet->iTurma;
  $sSqlTurma      = $oDaoTurma->sql_query_turmaserie("", $sCamposTurma, "", $sWhereTurma);
  $rsTurma        = $oDaoTurma->sql_record($sSqlTurma);
  $iAnoCalendario = db_utils::fieldsmemory($rsTurma, 0)->anocalendario;
  
  /* Busco a Data Base dos Parâmetros */
  $sCamposParam   = " ed233_c_database ";
  $sWhereParam    = " ed233_i_escola = ".$oGet->iEscola;
  $sSqlParametros = $oDaoParametros->sql_query("", $sCamposParam, "", $sWhereParam);
  $rsParametros   = $oDaoParametros->sql_record($sSqlParametros);
  $sDataBase      = db_utils::fieldsmemory($rsParametros, 0)->ed233_c_database;
  
  if ($oDaoParametros->numrows > 0) {
  	
  	if (!strstr($sDataBase, "/")) {
  		
  	  echo('<table style="width:90%;">');
  	  echo('  <tr> ');
  	  echo('    <td style="text-align:center; color:#FF0000; font-family:Arial;"> ');
  	  echo('      <strong> ');
  	  echo('        Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros) <br /> ');
  	  echo('        deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) <br /><br /> ');
  	  echo('        Valor atual do parâmetro: '.($sDataBase == '' ? 'Não Informado' : $sDataBase).'. <br /><br /> ');
  	  echo('        <input type="button" onClick="window.close();" value="Fechar" /> ');
  	  echo('      </strong> ');
  	  echo('    </td> ');
  	  echo('  </tr> ');
  	  echo('</table>');
  	  
  	  exit;
  		
  	} 
  	
  	$aDataBase = explode("/", $sDataBase);
  	
  	$iDiaDB    = $aDataBase[0];
  	$iMesDB    = $aDataBase[1];
  	
  	if (!checkdate($iMesDB, $iDiaDB, $iAnoCalendario)) {
  	  
      echo('<table style="width:90%;">');
  	  echo('  <tr> ');
  	  echo('    <td style="text-align:center; color:#FF0000; font-family:Arial;"> ');
  	  echo('      <strong> ');
  	  echo('        Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros) <br /> ');
  	  echo('        deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e deve ser uma data válida. <br /><br /> ');
  	  echo('        Valor atual do parâmetro: '.($sDataBase == '' ? 'Não Informado' : $sDataBase).'. <br /> ');
  	  echo('        Data Base para Cálculo Idade: '.$iDiaDB.'/'.$iMesDB.'/'.$iAnoCalendario.' (Data Inválida). <br /><br />');
  	  echo('        <input type="button" onClick="window.close();" value="Fechar" /> ');
  	  echo('      </strong> ');
  	  echo('    </td> ');
  	  echo('  </tr> ');
  	  echo('</table>');
  	  
  	  exit;
  		
  	}
  	
  	$dDataBaseCalc  = $iAnoCalendario."-".(str_pad($iMesDB, 2, '0', STR_PAD_LEFT))."-";
  	$dDataBaseCalc .= str_pad($iDiaDB, 2, '0', STR_PAD_LEFT);
  	
  } else {
  	
  	$dDataBaseCalc = $iAnoCalendario."-12-31";
  	
  }
  
  /* Busco as Regências as Disciplinas Escolhidas */
  $sCamposRegencia = " * ";
  $sOrderRegencia  = " ed59_i_ordenacao ";
  $sWhereRegencia  = " ed59_i_turma = ".$oGet->iTurma;
  $sWhereRegencia .= " AND ed59_i_disciplina IN (".$oGet->iDisciplinas.") ";
  $sSqlRegencia    = $oDaoRegencia->sql_query("", $sCamposRegencia, $sOrderRegencia, $sWhereRegencia);
  $rsRegencia      = $oDaoRegencia->sql_record($sSqlRegencia);
  $iLinhasRegencia = $oDaoRegencia->numrows;
  
  if ($iLinhasRegencia == 0) {
  	
  	echo('<table style="width:90%;">');
  	echo('  <tr> ');
  	echo('    <td style="text-align:center; color:#FF0000; font-family:Arial;"> ');
  	echo('      <strong> ');
  	echo('        Nenhuma matrícula para a(s) turma(s) selecionada(s)! ');
  	echo('      </strong> <br /><br /> ');
  	echo('      <input type="button" onClick="window.close();" value="Fechar" /> ');
  	echo('    </td> ');
  	echo('  </tr> ');
  	echo('</table>');
  	
  }
  
  /* Verifico os Procedimentos de Avaliação */
  $sCamposProcResult  = " ed43_i_codigo,ed37_c_tipo as tipores,ed43_c_arredmedia as arredmedia, ";
  $sCamposProcResult .= " ed43_c_minimoaprov as minimoaprovres, ed43_c_obtencao as obtencao ";
  $sWhereProcResult   = " ed43_c_geraresultado = 'S' ";
  $sWhereProcResult  .= " AND ed43_i_procedimento = ".db_utils::fieldsmemory($rsRegencia, 0)->ed220_i_procedimento;
  $sSqlProcResult     = $oDaoProcResult->sql_query("", $sCamposProcResult, "", $sWhereProcResult);
  $rsProcResult       = $oDaoProcResult->sql_record($sSqlProcResult);
  $iLinhasProcResult  = $oDaoProcResult->numrows;
  
  if ($iLinhasProcResult == 0) {
  	
  	echo('<table style="width:90%;">');
  	echo('  <tr> ');
  	echo('    <td style="text-align:center; color:#FF0000; font-family:Arial;"> ');
  	echo('      <strong> ');
  	echo('        Nenhum resultado do procedimento de avaliação desta turma tem a opção de gerar resultado final!! ');
  	echo('      </strong> <br /><br /> ');
  	echo('      <input type="button" onClick="window.close();" value="Fechar" /> ');
  	echo('    </td> ');
  	echo('  </tr> ');
  	echo('</table>');
  	
  } else {
  	$oDadosProcResult = db_utils::fieldsmemory($rsProcResult, 0);
  }
  
  $oPdf = new PDF();
  $oPdf->Open();
  $oPdf->AliasNbPages();

  $iContadorGeral = 0;
  
  for ($iCont = 0; $iCont < $iLinhasRegencia; $iCont++) {
  	
  	$oDadosRegencia = db_utils::fieldsmemory($rsRegencia, $iCont);
  	
  	/* iQtdAval */
  	$sCamposQtdAval = " count(*) as qtdaval ";
  	$sWhereQtdAval  = " ed43_c_geraresultado = 'S' AND ed43_i_procedimento = ".$oDadosRegencia->ed220_i_procedimento;
  	$sSqlQtdAval    = $oDaoAvalCompoeres->sql_query("", $sCamposQtdAval, "", $sWhereQtdAval);
  	$rsQtdAval      = $oDaoAvalCompoeres->sql_record($sSqlQtdAval);
  	$iQtdAval       = db_utils::fieldsmemory($rsQtdAval, 0)->qtdaval;
  	
  	/* iPriAval */
  	$sCamposPriAval = " min(ed41_i_sequencia) as priaval ";
  	$sWherePriAval  = " ed09_c_somach = 'S' AND ed41_i_procedimento = ".$oDadosRegencia->ed220_i_procedimento;
  	$sSqlPriAval    = $oDaoProcAvaliacao->sql_query("", $sCamposPriAval, "", $sWherePriAval);
  	$rsPriAval      = $oDaoProcAvaliacao->sql_record($sSqlPriAval);
  	$iPriAval       = db_utils::fieldsmemory($rsPriAval, 0)->priaval;
  	
  	/* iUltAval */
  	$sCamposUltAval = " max(ed41_i_sequencia) as ultaval ";
  	$sWhereUltAval  = " ed09_c_somach = 'S' AND ed41_i_procedimento = ".$oDadosRegencia->ed220_i_procedimento;
  	$sSqlUltAval    = $oDaoProcAvaliacao->sql_query("", $sCamposUltAval, "", $sWhereUltAval);
  	$rsUltAval      = $oDaoProcAvaliacao->sql_record($sSqlUltAval);
  	$iUltAval       = db_utils::fieldsmemory($rsUltAval, 0)->ultaval;
  	
  	/* Procedimento de Avaliação */
  	$sCamposProcAval = " ed37_c_tipo,ed09_i_codigo,ed09_c_descr,ed41_i_sequencia ";
  	$sWhereProcAval  = " ed41_i_codigo = ".$oGet->iPeriodo;
  	$sSqlProcAval    = $oDaoProcAvaliacao->sql_query("", $sCamposProcAval, "", $sWhereProcAval);
  	$rsProcAval      = $oDaoProcAvaliacao->sql_record($sSqlProcAval);
  	$oDadosProcAval  = db_utils::fieldsmemory($rsProcAval, 0);
  	
  	/* Períodos do Calendário */
  	$sCamposPeriodos     = " ed52_i_codigo,ed52_c_aulasabado,ed53_d_inicio,ed53_d_fim ";
  	$sWherePeriodos      = " ed53_i_calendario = ".$oDadosRegencia->ed57_i_calendario;
  	$sWherePeriodos     .= " AND ed53_i_periodoavaliacao = ".$oDadosProcAval->ed09_i_codigo;
  	$sSqlPeriodos        = $oDaoPerCalendario->sql_query("", $sCamposPeriodos, "", $sWherePeriodos);
  	$rsPerCalendario     = $oDaoPerCalendario->sql_record($sSqlPeriodos);
  	$oDadosPerCalendario = db_utils::fieldsmemory($rsPerCalendario, 0);
  	
  	$sDataPeriodo  = $oDadosProcAval->ed09_c_descr." - ".db_formatar($oDadosPerCalendario->ed53_d_inicio, "d");
  	$sDataPeriodo .= " à ".db_formatar($oDadosPerCalendario->ed53_d_fim, "d");
  	
  	/* Busco o Regente */
  	$sCamposRegHorario  = " case when ed20_i_tiposervidor = 1 then ";
  	$sCamposRegHorario .= "         cgmrh.z01_nome ";
  	$sCamposRegHorario .= "      else ";
  	$sCamposRegHorario .= "         cgmcgm.z01_nome ";
  	$sCamposRegHorario .= " end as regente ";
  	$sWhereRegHorario   = " ed58_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	$sSqlRegHorario     = $oDaoRegenciaHora->sql_query("", $sCamposRegHorario, "", $sWhereRegHorario);
  	$rsRegHorario       = $oDaoRegenciaHora->sql_record($sSqlRegHorario);
  	
  	if ($oDaoRegenciaHora->numrows > 0) {
  	  $sRegente = db_utils::fieldsmemory($rsRegHorario, 0)->regente;
  	} else {
  	  $sRegente = "";	
  	}
  	
  	/* Busco as Aulas Dadas */
  	$sCamposRegPeriodo = " ed78_i_aulasdadas as aulas ";
  	$sWhereRegPeriodo  = " ed78_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	$sWhereRegPeriodo .= " AND ed78_i_procavaliacao = ".$oGet->iPeriodo;
  	$sSqlRegPeriodo    = $oDaoRegenciaPeriodo->sql_query("", $sCamposRegPeriodo, "", $sWhereRegPeriodo);
  	$rsRegPeriodo      = $oDaoRegenciaPeriodo->sql_record($sSqlRegPeriodo);
  	
  	if ($oDaoRegenciaPeriodo->numrows > 0) {
  	  $iAulasDadas = db_utils::fieldsmemory($rsRegPeriodo, 0)->aulas;
  	} else {
  	  $iAulasDadas = "";
  	}
  	
  	/* Verifica se é para informar os dias letivos */
  	if ($oGet->sDiasLetivos == "S") {
  	  $iColunas = DiasLetivos($oDadosPerCalendario->ed53_d_inicio, $oDadosPerCalendario->ed53_d_fim,
  	                          $oDadosPerCalendario->ed52_c_aulasabado, $oDadosPerCalendario->ed52_i_codigo, 1);
  	} else {
  	  $iColunas = $oGet->iColunas;
  	}
  	
  	/* Configuração das Colunas */
  	$iLargIndividual = round(190 / $iColunas, 1);
  	$iLargColunas    = $iColunas * $iLargIndividual;
  	
  	$oPdf->SetFillColor(225);
  	
  	$head1 = "DIÁRIO DE CLASSE - DEPENDÊNCIA";
  	$head2 = "Curso: ".$oDadosRegencia->ed29_i_codigo." - ".$oDadosRegencia->ed29_c_descr;
  	$head3 = "Calendário: ".$oDadosRegencia->ed52_c_descr;
  	$head4 = "Etapa: ".$oDadosRegencia->ed11_c_descr;
  	$head5 = "Período: ".$oDadosProcAval->ed09_c_descr;
  	$head6 = "Turma: ".$oDadosRegencia->ed57_c_descr;
  	$head7 = "Disciplina: ".$oDadosRegencia->ed232_c_descr;
  	$head8 = "Regente: ".$sRegente;
  	$head9 = "Aulas Dadas: ".$iAulasDadas;
  	
  	/* ######## PAGINAS PRESENÇAS ######## */
  	$oPdf->AddPage("L");
  	$oPdf->Cell(70, 4, "", 1, 0, "C", 0);
  	$oPdf->Cell(10, 4, "Mês >", 1, 0, "R", 0);
  	
  	if ($oGet->sDiasLetivos == "S") {
  		
  	  $aMeses = DiasLetivos($oDadosPerCalendario->ed53_d_inicio, $oDadosPerCalendario->ed53_d_fim,
  	                        $oDadosPerCalendario->ed52_c_aulasabado, $oDadosPerCalendario->ed52_i_codigo, 3);
  	  $oPdf->SetFont('Arial', 'b', 7);
  	  
  	  for ($iContMeses = 0; $iContMeses < count($aMeses); $iContMeses++) {
  	  	
  	  	$aDiaMes = explode(",", $aMeses[$iContMeses]);
  	  	$oPdf->Cell($iLargIndividual * $aDiaMes[1], 4, $aDiaMes[0], 1, 0, "C", 0);
  	  	
  	  }
  		
  	} else {
  	  $oPdf->Cell($iLargColunas, 4, "", 1, 0, "R", 0);
  	}
  	
  	$oPdf->SetFont('Arial', '', 8);
  	$oPdf->Cell(1, 4, "", 1, 1, "R", 0);
  	$oPdf->Cell(5, 4, "Nº", 1, 0, "C", 0);
  	$oPdf->Cell(65, 4, "Nome do Aluno", 1, 0, "C", 0);
  	$oPdf->SetFont('Arial', 'b', 8);
  	$oPdf->Cell(10, 4, "Dia >", 1, 0, "R", 0);
  	
  	if ($oGet->sDiasLetivos == "S") {
  		
  	  $aDias = DiasLetivos($oDadosPerCalendario->ed53_d_inicio, $oDadosPerCalendario->ed53_d_fim,
  	                       $oDadosPerCalendario->ed52_c_aulasabado, $oDadosPerCalendario->ed52_i_codigo, 2);
  	  $oPdf->SetFont('Arial', 'b', 6);

      for ($iContDias = 0; $iContDias < count($aDias); $iContDias++) {

        $iDia = explode("-", $aDias[$iContDias]);
        $oPdf->Cell($iLargIndividual, 4, $iDia[0], 1, 0, "C", 0);

      }
  		
  	} else {
  	  
  	  for ($iContDias = 0; $iContDias < $oGet->iColunas; $iContDias++) {
  	  	$oPdf->Cell($iLargIndividual, 4, "", 1, 0, "C", 0);
  	  }	
  		
  	}

    $oPdf->Cell(1, 4, "", 1, 1, "C", 0);

    if ($oGet->sAtivo == "S") {
      $sCondicao = " AND ed60_c_situacao = 'MATRICULADO' ";
    } else {
      $sCondicao = "";
    }
    
    /* 
     * Realizo a busca dos alunos matriculados na turma
     * somente aqueles que foram matriculados pela dependencia.
     */
    $sSqlMatricula  = " SELECT ";
    $sSqlMatricula .= "     ed60_i_aluno,ed60_i_codigo,ed60_i_numaluno,ed60_c_parecer,ed47_v_nome,ed47_d_nasc, ";
    $sSqlMatricula .= "     fc_idade(ed47_d_nasc,'$dDataBaseCalc'::date) as idadealuno,ed60_c_situacao ";
    $sSqlMatricula .= "   FROM matriculadependencia ";
    $sSqlMatricula .= "     INNER JOIN matricula      ON ed60_i_codigo     = ed297_matricula ";
    $sSqlMatricula .= "     INNER JOIN matriculaserie ON ed221_i_matricula = ed60_i_codigo ";
    $sSqlMatricula .= "     INNER JOIN turma          ON ed57_i_codigo     = ed297_turma ";
    $sSqlMatricula .= "     INNER JOIN aluno          ON ed47_i_codigo     = ed60_i_aluno ";
    $sSqlMatricula .= "   WHERE ";
    $sSqlMatricula .= "     ed297_turma = ".$oDadosRegencia->ed57_i_codigo;
    $sSqlMatricula .= "     AND ed221_i_serie = ".$oDadosRegencia->ed59_i_serie;
    $sSqlMatricula .= "     ".$sCondicao;
    $sSqlMatricula .= "   ORDER BY ";
    $sSqlMatricula .= "     ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa ";
    $rsMatricula    = $oDaoMatriculaDependencia->sql_record($sSqlMatricula);
    $iLinhasMatDep  = $oDaoMatriculaDependencia->numrows;

    $iLimit    = 35;
    $iContador = 0;
    $lCor      = true;

    for ($iContMat = 0; $iContMat < $iLinhasMatDep; $iContMat++) {
      
      $oDadosMatricula = db_utils::fieldsmemory($rsMatricula, $iContMat);
      $iContador++;
      $iContadorGeral++;

      $lCor = $lCor == true ? false : true;

      /* Sql do Diário de Avaliação */
      $sSqlDiarioAval  = " SELECT ed72_c_amparo as amparo,ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev ";
      $sSqlDiarioAval .= "     FROM diarioavaliacao ";
      $sSqlDiarioAval .= "          INNER JOIN diario       ON ed95_i_codigo  = ed72_i_diario ";
      $sSqlDiarioAval .= "          LEFT  JOIN amparo       ON ed81_i_diario  = ed95_i_codigo ";
      $sSqlDiarioAval .= "          LEFT  JOIN convencaoamp ON ed250_i_codigo = ed81_i_convencaoamp ";
      $sSqlDiarioAval .= "     WHERE ";
      $sSqlDiarioAval .= "          ed95_i_aluno = ".$oDadosMatricula->ed60_i_aluno;
      $sSqlDiarioAval .= "          AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
      $sSqlDiarioAval .= "          AND ed72_i_procavaliacao = ".$oGet->iPeriodo;
      $rsDiarioAval    = $oDaoDiarioAvaliacao->sql_record($sSqlDiarioAval);

      if ($oDaoDiarioAvaliacao->numrows > 0) {
        $oDadosDiarioAval = db_utils::fieldsmemory($rsDiarioAval, 0);
        $amparo = $oDadosDiarioAval->amparo;
      } else {
        $amparo = "";
      }

      $oPdf->SetFont('Arial', '', 8);
      $oPdf->Cell(5, 4, $oDadosMatricula->ed60_i_numaluno, 1, 0, "C", $lCor);
      $oPdf->Cell(70, 4, $oDadosMatricula->ed47_v_nome, 1, 0, "L", $lCor);
      $oPdf->Cell(5, 4, $oDadosMatricula->idadealuno, 1, 0, "C", $lCor);

      if ($amparo == "S") {

        $oPdf->SetFont('Arial', 'b', 11);

        if ($oDadosDiarioAvaliacao->ed81_i_justificativa == "S") {
          $oPdf->Cell($iLargIndividual * $oGet->iColunas, 4, "AMPARADO", 1, 0, "C", 0);
        } else {
          $oPdf->Cell($iLargIndividual * $oGet->iColunas, 4, $oDadosDiarioAval->ed250_c_abrev, 1, 0, "C", 0);
        }

        $oPdf->SetFont('Arial', 'b', 8);

      } else {

        if (trim($oDadosMatricula->ed60_c_situacao) != "MATRICULADO") {

          $oPdf->SetFont('Arial', 'b', 11);
          $oPdf->Cell($iLargIndividual * $iColunas, 4, trim($oDadosMatricula->ed60_c_situacao), 1, 0, "C", 0);
          $oPdf->SetFont('Arial', 'b', 8);

        } else {
          
          $oPdf->SetFont('Arial', 'b', 8);
          $at = $oPdf->GetY();
          $lg = $oPdf->GetX();

          for ($iContX = 0; $iContX < $iColunas; $iContX++) {
            
            $oPdf->SetFont('Arial', 'b', 12);
            $oPdf->Cell($iLargIndividual, 4, "", 1, 0, "C", 0);
            $oPdf->Text($lg + ($iLargIndividual * 30 / 100), $at+2, ".");
            $lg = $oPdf->GetX();

          }

          $oPdf->SetFont('Arial', 'b', 8);

        }

      }

      $oPdf->Cell(1, 4, "", 1, 1, "C", 0);
      
      if ($iContador == $iLimit && $iLimit < $iLinhasMatDep) {
      	
      	$oPdf->SetFont('Arial', 'b', 8);
      	$oPdf->Cell(($iLargColunas + 81), 5, "Assinatura do professooor:_________________________________", 1, 1, "L", 0);
      	$oPdf->Line(10, 43, ($iLargColunas + 91), 43);
      	
      	$oPdf->AddPage("L");
      	$oPdf->SetFont('Arial', 'b', 7);
      	$oPdf->Cell(70, 4, "Mês >", 1, 0, "R", 0);
      	
      	if ($oGet->sDiasLetivos == "S") {
      	  
      	  $aMeses = DiasLetivos($oDadosPerCalendario->ed53_d_inicio, $oDadosPerCalendario->ed53_d_fim,
  	                            $oDadosPerCalendario->ed52_c_aulasabado, $oDadosPerCalendario->ed52_i_codigo, 3);
  	      $oPdf->SetFont('Arial', 'b', 6);

          for ($iContMeses = 0; $iContMeses < count($aMeses); $iContMeses++) {

            $iMes = explode("-", $aMeses[$iContMeses]);
            $oPdf->Cell($iLargIndividual * $iMes[1], 4, $iMes[0], 1, 0, "C", 0);

          }
      		
      	} else {
      	  $oPdf->Cell($iLargColunas, 4, "", 1, 0, "R", 0);
      	}
      	
        $oPdf->SetFont('Arial', '', 8);
  	    $oPdf->Cell(1, 4, "", 1, 1, "R", 0);
  	    $oPdf->Cell(5, 4, "Nº", 1, 0, "C", 0);
  	    $oPdf->Cell(65, 4, "Nome do Aluno", 1, 0, "C", 0);
  	    $oPdf->SetFont('Arial', 'b', 8);
  	    $oPdf->Cell(10, 4, "Dia >", 1, 0, "R", 0);
  	
  	    if ($oGet->sDiasLetivos == "S") {
  		
  	      $aDias = DiasLetivos($oDadosPerCalendario->ed53_d_inicio, $oDadosPerCalendario->ed53_d_fim,
  	                           $oDadosPerCalendario->ed52_c_aulasabado, $oDadosPerCalendario->ed52_i_codigo, 2);
  	      $oPdf->SetFont('Arial', 'b', 6);

          for ($iContDias = 0; $iContDias < count($aDias); $iContDias++) {

            $iDia = explode("-", $aDias[$iContDias]);
            $oPdf->Cell($iLargIndividual, 4, $iDia[0], 1, 0, "C", 0);

          }
  		
  	    } else {
  	  
  	      for ($iContDias = 0; $iContDias < $oGet->iColunas; $iContDias++) {
  	  	    $oPdf->Cell($iLargIndividual, 4, "", 1, 0, "C", 0);
  	      }	
  		
  	    }
  	    
  	    $oPdf->Cell(1, 4, "", 1, 1, "C", 0);
  	    $iContador = 0;
      	
      }

    }
    
    $iTermino = $oPdf->GetY();
    
    for ($iContTer = $iContador; $iContTer < $iLimit; $iContTer++) {
      
      $lCor = $lCor == true ? false : true;
      
      $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
      $oPdf->Cell(70, 4, "", 1, 0, "L", $lCor);
      $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
      
      $iAt = $oPdf->GetY();
      $iLg = $oPdf->GetX();
      
      for ($iContCol = 0; $iContCol < $iColunas; $iContCol++) {
      	
      	$oPdf->SetFont('Arial', 'b', 12);
      	$oPdf->Cell($iLargIndividual, 4, "", 1, 0, "C", $lCor);
      	$oPdf->Text($iLg + ($iLargIndividual * 30 / 100), $iAt + 2, ".");
      	$iLg = $oPdf->GetX();
      	
      }
      
      $oPdf->Cell(1, 4, "", 1, 1, "C", $lCor);
    	
    }
    
    $oPdf->SetFont('Arial', 'b', 8);
    $oPdf->Cell($iLargColunas + 81, 5, "Assinatura do professor:_________________________________", 1, 1, "L", 0);
    $oPdf->Line(10, 43, $iLargColunas + 91, 43);
  
    /* ######## PAGINA 2 - AVALIACOES ######## */
    $oPdf->AddPage("L");
    $oPdf->SetFont('Arial', 'b', 7);
    $iLargMp = 0;
  
    if ($lNotaBranco == "S" 
        && ($oDadosProcResult->obtencao == "ME"
            || $oDadosProcResult->obtencao == "MP"
            || $oDadosProcResult->obtencao == "SO")) {
  	
      if (($iPriAval + 2) <= $oDadosProcAval->ed41_i_sequencia) {
  	    $iLargMp += 10;
  	  }
  	
  	  if ($oDadosProcAval->ed41_i_sequencia == $iUltAval) {
  	    $iLargMp += 10;
  	  }
  	
    }
  
    $iQuadros             = 25;
    $sWhereProcAvaliacao  = " ed41_i_procedimento = ".$oDadosRegencia->ed220_i_procedimento;
    $sWhereProcAvaliacao .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
    $sSqlProcAvaliacao    = $oDaoProcAvaliacao->sql_query("", 
                                                          "ed09_c_abrev", 
                                                          "ed41_i_sequencia ASC", 
                                                          $sWhereProcAvaliacao
                                                         );
    $rsProcAvaliacao      = $oDaoProcAvaliacao->sql_record($sSqlProcAvaliacao);
    $iQuadros            -= $oDaoProcAvaliacao->numrows + 2;
  
    if ($iLargMp == 20) {
  	  $iQuadros = $iQuadros;	
    } elseif ($iLargMp == 10) {
  	  $iQuadros += 2;
    } else {
  	  $iQuadros += 4;
    }
  
    $oPdf->SetFont('Arial', '', 8);
    $oPdf->Cell(5, 4, "Nº", 1, 0, "C", 0);
    $oPdf->Cell(40, 4, "Nome do Aluno", 1, 0, "L", 0);
    $oPdf->SetFont('Arial', 'b', 8);
  
    if ($oGet->lSexo == "true") {
  	  $oPdf->Cell(5, 4, "S", 1, 0, "C", 0);
    }
  
    if ($oGet->lNascimento == "true") {
  	  $oPdf->Cell(20, 4, "Nascimento", 1, 0, "C", 0);
    }
  
    if ($oGet->lIdade == "true") {
  	
  	  $oPdf->SetFont('Arial', '', 8);
  	  $oPdf->Cell(5, 4, "I", 1, 0, "C", 0);
  	
    }
  
    if ($oGet->lResultAnt == "true") {
  	 
  	  $oPdf->SetFont('Arial', 'b', 8);
  	  $oPdf->Cell(5, 4, "RA", 1, 0, "C", 0);
  	
    }
  
    for ($iContPA = 0; $iContPA < $oDaoProcAvaliacao->numrows; $iContPA++) {
  	
  	  $oDadosProcAvaliacao = db_utils::fieldsmemory($rsProcAvaliacao, $iContPA);
  	  $oPdf->Cell(10, 4, $oDadosProcAvaliacao->ed09_c_abrev, 1, 0, "C", 0);
  	
    }
  
    if ($lNotaBranco == "S" 
        && ($oDadosProcResult->obtencao == "ME"
            || $oDadosProcResult->obtencao == "MP"
            || $oDadosProcResult->obtencao == "SO")) {

      if (($iPriAval + 2) <= $oDadosProcAval->ed41_i_sequencia) {
        $oPdf->Cell(10, 4, "NP", 1, 0, "C", 0);
      }     

      if ($oDadosProcAval->ed41_i_sequencia == $iUltAval) {
        $oPdf->Cell(10, 4, "P", 1, 0, "C", 0);
      }
          	
    }
  
    $oPdf->Cell(10, 4, substr($oDadosProcAval->ed37_c_tipo, 0, 5), 1, 0, "C", 0);
    $oPdf->Cell(5, 4, "F", 1, 0, "C", 0);
  
    if ($oGet->lAbono == "true") {
  	  $oPdf->Cell(5, 4, "FA", 1, 0, "C", 0);
    }
  
    if ($oGet->lTotalFaltas == "true") {
  	  $oPdf->Cell(5, 4, "TF", 1, 0, "C", 0);
    }
  
    if ($oGet->lCodigo == "true") {
  	  $oPdf->Cell(12, 4, "Código", 1, 0, "C", 0);
    }
  
    if ($oGet->lParecer == "true") {
  	  $oPdf->Cell(18, 4, "Parecer", 1, 0, "C", 0);
    }
  
    if ($oGet->lSexo == "false") {
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
    }
  
    if ($oGet->lIdade == "false") {
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
    }
  
    if ($oGet->lAbono == "false") {
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
    }
  
    if ($oGet->lCodigo == "false") {
  	
  	  $oPdf->Cell(6, 4, "", 1, 0, "C", 0);
  	  $oPdf->Cell(6, 4, "", 1, 0, "C", 0);
  	
    }
  
    if ($oGet->lNascimento == "false") {
  	
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	
    }
  
    if ($oGet->lResultAnt == "false") {
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
    }
  
    if ($oGet->lTotalFaltas == "false") {
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
    }
  
    if ($oGet->lParecer == "false") {
  	
  	  $oPdf->Cell(6, 4, "", 1, 0, "C", 0);
  	  $oPdf->Cell(6, 4, "", 1, 0, "C", 0);
  	  $oPdf->Cell(6, 4, "", 1, 0, "C", 0);
  	
    }
  
    for ($iContQuadros = 0; $iContQuadros < $iQuadros; $iContQuadros++) {
  	  $oPdf->Cell(5, 4, "", 1, $iContQuadros == ($iQuadros - 1) ? 1 : 0, "C", 0);
    }
  
    $oPdf->SetFont('Arial', 'b', 8);
  
    if ($oGet->sAtivo == "S") {
  	  $sCondicao = " AND ed60_c_situacao = 'MATRICULADO' ";
    } else {
  	  $sCondicao = "";
    }
  
    $sSqlMatricula  = " SELECT ";
    $sSqlMatricula .= "     ed60_i_codigo,ed60_c_rfanterior,ed60_i_aluno,ed60_i_numaluno,ed60_c_parecer,ed47_v_nome, ";
    $sSqlMatricula .= "     ed47_v_sexo,ed47_d_nasc,fc_idade(ed47_d_nasc,'$dDataBaseCalc'::date) as idadealuno,ed60_c_situacao ";
    $sSqlMatricula .= "   FROM matriculadependencia ";
    $sSqlMatricula .= "     INNER JOIN matricula      ON ed60_i_codigo     = ed297_matricula ";
    $sSqlMatricula .= "     INNER JOIN matriculaserie ON ed221_i_matricula = ed60_i_codigo ";
    $sSqlMatricula .= "     INNER JOIN turma          ON ed57_i_codigo     = ed297_turma ";
    $sSqlMatricula .= "     INNER JOIN aluno          ON ed47_i_codigo     = ed60_i_aluno ";
    $sSqlMatricula .= "   WHERE ";
    $sSqlMatricula .= "     ed297_turma = ".$oDadosRegencia->ed57_i_codigo;
    $sSqlMatricula .= "     AND ed221_i_serie = ".$oDadosRegencia->ed59_i_serie;
    $sSqlMatricula .= "     ".$sCondicao;
    $sSqlMatricula .= "   ORDER BY ";
    $sSqlMatricula .= "     ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa ";
    $rsMatricula    = $oDaoMatriculaDependencia->sql_record($sSqlMatricula);
    $iLinhasMatDep2 = $oDaoMatriculaDependencia->numrows;
  
    $iLimit         = 35;
    $iContador      = 0;
    $iContadorGeral = 0;
    $lCor           = true;
  
    for ($iCont2 = 0; $iCont2 < $iLinhasMatDep2; $iCont2++) {
  	
  	  $oDadosMatricula2 = db_utils::fieldsmemory($rsMatricula, $iCont2);
  	  $iContador++;
  	  $iContadorGeral++;
  	
  	  $lCor = $lCor == true ? false : true;
  	
  	  if (trim($oDadosProcAval->ed37_c_tipo) == "NOTA") {
  	  
  	    $sCampoAval  = " ed72_i_valornota IS NULL ";
  	    $sCampoAval2 = " ed72_i_valornota IS NOT NULL ";	
  		
  	  } elseif (trim($oDadosProcAval->ed37_c_tipo) == "NIVEL") {
  	  
  	    $sCampoAval  = " ed72_c_valorconceito = '' ";
  	    $sCampoAval2 = " ed72_c_valorconceito != '' ";
  		
  	  } elseif (trim($oDadosProcAval->ed37_c_tipo) == "PARECER") {
  		
  	    $sCampoAval  = " ed72_t_parecer = '' ";
  	    $sCampoAval2 = " ed72_t_parecer != '' ";
  		
  	  }
  	
  	  $sWhereDiarioAval     = " ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	  $sWhereDiarioAval    .= " AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	  $sWhereDiarioAval    .= " AND ".$sCampoAval." AND ed72_c_amparo = 'N' AND ed09_c_somach = 'S' ";
  	  $sWhereDiarioAval    .= " AND ed37_c_tipo = '".$oDadosProcResult->tipores."' ";
  	  $sSqlDiarioAvaliacao  = $oDaoDiarioAvaliacao->sql_query("", "ed72_i_codigo", "ed41_i_sequencia", $sWhereDiarioAval);
  	  $rsDiarioAvaliacao    = $oDaoDiarioAvaliacao->sql_record($sSqlDiarioAvaliacao);
  	  $iLinhasDiarioAval1   = $oDaoDiarioAvaliacao->numrows;
  	
  	  $sWhereDiarioAval2    = " ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	  $sWhereDiarioAval2   .= " AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	  $sWhereDiarioAval2   .= " AND ".$sCampoAval2." AND ed72_c_amparo = 'N' AND ed09_c_somach = 'S' ";
  	  $sWhereDiarioAval2   .= " AND ed37_c_tipo = '".$oDadosProcResult->tipores."' ";
  	  $sWhereDiarioAval2   .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	  $sSqlDiarioAvaliacao2 = $oDaoDiarioAvaliacao->sql_query("", "ed72_i_codigo", "ed41_i_sequencia", $sWhereDiarioAval2);
  	  $rsDiarioAvaliacao2   = $oDaoDiarioAvaliacao->sql_record($sSqlDiarioAvaliacao2);
  	  $iLinhasDiarioAval2   = $oDaoDiarioAvaliacao->numrows;
  	
  	  $iLinhasDiarioAval2   = $iLinhasDiarioAval2 == 0 ? 1 : $iLinhasDiarioAval2;
  	
  	  $oPdf->SetFont('Arial', '', 8);
  	  $oPdf->Cell(5, 4, $oDadosMatricula2->ed60_i_numaluno, 1, 0, "C", $lCor);
  	  $oPdf->Cell(40, 4, substr($oDadosMatricula2->ed47_v_nome, 0, 23), 1, 0, "L", $lCor);
  	
  	  if ($oGet->lSexo == "true") {
  	    $oPdf->Cell(5, 4, $oDadosMatricula2->ed47_v_sexo, 1, 0, "C", $lCor);
  	  }
  	
  	  if ($oGet->lNascimento == "true") {
  	    $oPdf->Cell(20, 4, db_formatar($oDadosMatricula2->ed47_d_nasc, "d"), 1, 0, "C", $lCor);
  	  }
  	
  	  if ($oGet->lIdade == "true") {
  	    $oPdf->Cell(5, 4, $oDadosMatricula2->idadealuno, 1, 0, "C", $lCor);
  	  }
  	
  	  $aInfoAnterior = explode("|", RFanterior($oDadosMatricula2->ed60_i_codigo));
  	  if ($oGet->lResultAnt == "true") {
  	    $oPdf->Cell(5, 4, substr($aInfoAnterior[1], 0, 1), 1, 0, "C", $lCor);
  	  }
  	
  	  if (trim($oDadosMatricula2->ed60_c_situacao) == "MATRICULADO") {
  	  
  	    $sCamposDiario  = " ed37_c_minimoaprov as minperiodo,ed72_i_procavaliacao,ed72_c_valorconceito, ";
  	    $sCamposDiario .= " ed72_i_valornota,ed72_c_amparo,ed37_c_tipo,ed72_i_escola,ed72_c_tipo, ";
  	    $sCamposDiario .= " ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev ";
  	    $sWhereDiario   = " ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	    $sWhereDiario  .= " AND ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	    $sWhereDiario  .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	    $sSqlDiario     = $oDaoDiarioAvaliacao->sql_query("", $sCamposDiario, "ed41_i_sequencia ASC", $sWhereDiario);
  	    $rsDiario       = $oDaoDiarioAvaliacao->sql_record($sSqlDiario);
  	    $iLinhasDiario  = $oDaoDiarioAvaliacao->numrows;
  	  
  	    if ($iLinhasDiario > 0) {
  	  	
  	  	  for ($iContDiario = 0; $iContDiario < $iLinhasDiario; $iContDiario++) {
  	  		
  	  	    $oDadosDiario = db_utils::fieldsmemory($rsDiario, $iContDiario);
  	  	  
  	  	    if ($oDadosMatricula2->ed60_c_parecer == "S") {
  	  	  	  $oDadosDiario->ed37_c_tipo = "PARECER";
  	  	    }
  	  	  
  	  	    if ($oDadosDiario->ed72_i_escola != $oGet->iEscola 
  	  	         || $oDadosDiario->ed72_c_tipo == "F") {
  	  	  	  $sNE = "*";
  	  	    } else {
  	  	  	  $sNE = "";
  	  	    }
  	  	  
  	  	    if ($oDadosDiario->ed72_c_amparo == "S") {
  	  	  	
  	  	  	  if ($oDadosDiario->ed81_i_justificativa != "") {
  	  	  	    $oPdf->Cell(10, 4, "AMP", 1, 0, "C", $lCor);
  	  	  	  } else {
  	  	  	    $oPdf->Cell(10, 4, $oDadosDiario->ed250_c_abrev, 1, 0, "C", $lCor);
  	  	  	  }
  	  	  	
  	  	    } else {
  	  	  	
  	  	  	  if ($oDadosDiario->ed37_c_tipo == "NOTA" && $oDadosDiario->ed72_i_valornota != "") {
  	  	  	  
  	  	  	    if ($lResultEdu == "S") {
  	  	  	  	  $fAprov = number_format($oDadosDiario->ed72_i_valornota, 2, ".", ".");
  	  	  	    }	else {
  	  	  	  	  $fAprov = floor($oDadosDiario->ed72_i_valornota);
  	  	  	    }
  	  	  		
  	  	  	  } elseif ($oDadosDiario->ed37_c_tipo == "NOTA" && $oDadosDiario->ed72_i_valornota == "") {
  	  	  	    $fAprov = $oDadosDiario->ed72_i_valornota;
  	  	  	  } elseif ($oDadosDiario->ed37_c_tipo == "NIVEL") {
  	  	  	    $fAprov = $oDadosDiario->ed72_c_valorconceito;
  	  	  	  } else {
  	  	  	    $fAprov = "";
  	  	  	  }
  	  	  	
  	  	  	  if (trim($oDadosDiario->ed37_c_tipo) == "NOTA" && $fAprov < $oDadosDiario->minperiodo) {
  	  	  	  
  	  	  	    $oPdf->SetFont('Arial', 'b', 10);
  	  	  	    $oPdf->Cell(10, 4, $sNE.$fAprov, 1, 0, "C", $lCor);	
  	  	  	    $oPdf->SetFont('Arial', '', 10);
  	  	  		
  	  	  	  } else {
  	  	  	  
  	  	  	    $oPdf->SetFont('Arial', 'b', 10);
  	  	  	    $oPdf->Cell(10, 4, $sNE.$fAprov, 1, 0, "C", $lCor);
  	  	  		
  	  	  	  }
  	  	  	
  	  	    }
  	  		
  	  	  }
  	  	
  	    } else {
  	  	
  	  	  for ($i = 0; $i < $oDaoProcAvaliacao->numrows; $i++) {	
  	  	    $oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);	  		
  	  	  }
  	  	
  	    }
  	  
  	    $sSqlAmparo    = " SELECT ed72_c_amparo as amparo ";
  	    $sSqlAmparo   .= "     FROM diarioavaliacao ";
  	    $sSqlAmparo   .= "          INNER JOIN diario ON ed95_i_codigo = ed72_i_diario ";
  	    $sSqlAmparo   .= "     WHERE ";
  	    $sSqlAmparo   .= "          ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	    $sSqlAmparo   .= "          AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	    $sSqlAmparo   .= "          AND ed72_i_procavaliacao = ".$oGet->iPeriodo;
  	    $rsAmparo      = $oDaoDiarioAvaliacao->sql_record($sSqlAmparo);
  	    $iLinhasAmparo = $oDaoDiarioAvaliacao->numrows;
  	  
  	    if ($iLinhasAmparo > 0) {
  	  	  $sAmparo = db_utils::fieldsmemory($rsAmparo, 0);
  	    } else {
  	  	  $sAmparo = "";
  	    }
  	  
  	    $sSqlResFinal    = " SELECT ed74_c_resultadofinal as verificarf ";
  	    $sSqlResFinal   .= "     FROM diariofinal ";
  	    $sSqlResFinal   .= "          INNER JOIN diario ON ed95_i_codigo = ed74_i_diario ";
  	    $sSqlResFinal   .= "     WHERE ";
  	    $sSqlResFinal   .= "          ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	    $sSqlResFinal   .= "          AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	    $rsResFinal      = db_query($sSqlResFinal);
  	    $iLinhasResFinal = pg_num_rows($rsResFinal);
  	  
  	    if ($iLinhasResFinal > 0) {
  	  	  $sVerificaResFinal = db_utils::fieldsmemory($rsResFinal, 0)->verificarf;
  	    } else {
  	  	  $sVerificaResFinal = "";
  	    }
  	  
  	    $sResFinal      = "";
  	    $sNotaProjetada = "";
  	  
  	    if ($lNotaBranco == "S" && $iLinhasDiario > 0
  	        && ($oDadosProcResult->obtencao == "ME"
  	            || $oDadosProcResult->obtencao == "MP"
  	            || $oDadosProcResult->obtencao == "SO")) {
  	  	
  	      if (trim($oDadosDiario->ed37_c_tipo) == "NOTA") {
  	    	
  	        if ($oDadosProcResult->obtencao == "ME") {
  	      	
  	      	  $sCamposDiarioAvaliacao  = " sum(ed72_i_valornota)/count(ed72_i_valornota) as aprvto, ";
  	      	  $sCamposDiarioAvaliacao .= " (".$oDadosProcResult->minimoaprovres."*(count(ed72_i_valornota)+1)) - ";
  	      	  $sCamposDiarioAvaliacao .= " sum(ed72_i_valornota) as projetada";
  	      	  $sWhereDiarioAvaliacao   = " ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	      	  $sWhereDiarioAvaliacao  .= " AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	      	  $sWhereDiarioAvaliacao  .= " AND ed72_c_amparo = 'N' AND ed72_i_valornota IS NOT NULL ";
  	      	  $sWhereDiarioAvaliacao  .= " AND ed09_c_somach = 'S' ";
  	      	  $sWhereDiarioAvaliacao  .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	      	  $sWhereDiarioAvaliacao  .= " AND ed37_c_tipo = '".$oDadosProcResult->tipores."' ";
  	      	  $sSqlDiarioAvaliacao     = $oDaoDiarioAvaliacao->sql_query("",
  	      	                                                             $sCamposDiarioAvaliacao,
  	      	                                                             "",
  	      	                                                             $sWhereDiarioAvaliacao
  	      	                                                            );
  	      	  $rsDiarioAvaliacao       = $oDaoDiarioAvaliacao->sql_record($sSqlDiarioAvaliacao);
  	      	  $oDadosDiarioAvaliacao   = db_utils::fieldsmemory($rsDiarioAvaliacao, 0);
  	      	
  	      	  $iProjetada = $oDadosDiarioAvaliacao->projetada < 0 ? 0 : $oDadosDiarioAvaliacao->projetada;
  	      	
  	      	  if ($oDadosProcResult->arredmedia == "S") {
  	      		
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosDiarioAvaliacao->aprvto), 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosDiarioAvaliacao->aprvto), 0);
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 0);
  	      	  	
  	      	    }
  	      		
  	      	  } else {
  	      	  
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosDiarioAvaliacao->aprvto, 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format($iProjetada, 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosDiarioAvaliacao->aprvto, 0);
  	      	  	  $sNotaProjetada = number_format($iProjetada, 0);
  	      	  	
  	      	    }	
  	      		
  	      	  }
  	      	
  	        } elseif ($oDadosProcResult->obtencao == "MP") {
  	      	
  	      	  $sWhereAvalComp  = " ed44_i_procresultado = ".$oDadosProcResult->ed43_i_codigo;
  	      	  $sWhereAvalComp .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	      	  $sSqlAvalComp    = $oDaoAvalCompoeres->sql_query("",
  	      	                                                   "sum(ed44_i_peso) as somapeso",
  	      	                                                   "",
  	      	                                                   $sWhereAvalComp
  	      	                                                  );
  	      	  $rsAvalComp      = $oDaoAvalCompoeres->sql_record($sSqlAvalComp);
  	      	  $iSomaPeso       = db_utils::fieldsmemory($rsAvalComp, 0);
  	      	
  	      	  $iSomaPeso       = $iSomaPeso == "" ? 0 : $iSomaPeso;
  	      	
  	      	  $sSqlAvaliacao    = " SELECT sum(ed72_i_valornota*ed44_i_peso)/sum(ed44_i_peso) as aprvto, ";
  	      	  $sSqlAvaliacao   .= "        (( ".number_format($oDadosProcResult->minimoaprovres,0)." * ($iQtdAval + ";
  	      	  $sSqlAvaliacao   .= "            ((sum(ed44_i_peso)*".$iLinhasDiarioAval2.")/count(*)))) -  ";
  	      	  $sSqlAvaliacao   .= "            (sum(ed72_i_valornota*ed44_i_peso)/(count(*)/".$iLinhasDiarioAval2;
  	      	  $sSqlAvaliacao   .= "              )))/$iQtdAval as projetada ";
  	      	  $sSqlAvaliacao   .= "      FROM diarioavaliacao ";
  	      	  $sSqlAvaliacao   .= "           INNER JOIN diario ON ed95_i_codigo = ed72_i_diario ";
  	      	  $sSqlAvaliacao   .= "           INNER JOIN procavaliacao ON ed41_i_codigo = ed72_i_procavaliacao ";
  	      	  $sSqlAvaliacao   .= "           INNER JOIN formaavaliacao ON ed41_i_codigo = ed72_i_procavaliacao ";
  	      	  $sSqlAvaliacao   .= "           INNER JOIN periodoavaliacao ON ed09_i_codigo = ed41_i_periodoavaliacao ";
  	      	  $sSqlAvaliacao   .= "           INNER JOIN avalcompoeres ON ed44_i_procavaliacao = ed72_i_procavaliacao ";
  	      	  $sSqlAvaliacao   .= "      WHERE ";
  	      	  $sSqlAvaliacao   .= "           ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	      	  $sSqlAvaliacao   .= "           AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	      	  $sSqlAvaliacao   .= "           AND ed72_c_amparo = 'N' AND ed72_i_valornota IS NOT NULL ";
  	      	  $sSqlAvaliacao   .= "           AND ed09_c_somach = 'S' ";
  	      	  $sSqlAvaliacao   .= "           AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	      	  $rsAvaliacao      = db_query($sSqlAvaliacao);
  	          $oDadosAvaliacao  = db_utils::fieldsmemory($rsAvaliacao, 0);
  	        
  	          $iProjetada       = $oDadosAvaliacao->projetada < 0 ? 0 : $oDadosAvaliacao->projetada;
  	        
  	          if ($oDadosProcResult->arredmedia == "S") {
  	      		
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosAvaliacao->aprvto), 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosAvaliacao->aprvto), 0);
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 0);
  	      	  	
  	      	    }
  	      		
  	      	  } else {
  	      	  
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosAvaliacao->aprvto, 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format($iProjetada, 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosAvaliacao->aprvto, 0);
  	      	  	  $sNotaProjetada = number_format($iProjetada, 0);
  	      	  	
  	      	    }	
  	      		
  	      	  }
  	      	
  	        } elseif ($oDadosProcResult->obtencao == "SO") {
  	      	
  	      	  $sCamposSO       = " sum(ed72_i_valornota) as aprvto, ";
  	      	  $sCamposSO      .= " ".$oDadosProcResult->minimoaprovres." - sum(ed72_i_valornota) as projetada ";
  	      	  $sWhereSO        = " ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	      	  $sWhereSO       .= " AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	      	  $sWhereSO       .= " AND ed72_c_amparo = 'N' AND ed72_i_valornota IS NOT NULL AND ed09_c_somach = 'S' ";
  	      	  $sWhereSO       .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	      	  $sSqlAvaliacao   = $oDaoDiarioAvaliacao->sql_query("", $sCamposSO, "", $sWhereSO);
  	      	  $rsAvaliacao     = $oDaoDiarioAvaliacao->sql_record($sSqlAvaliacao);
  	      	  $oDadosAvaliacao = db_utils::fieldsmemory($rsAvaliacao, 0);
  	      	
  	      	  $iProjetada       = $oDadosAvaliacao->projetada < 0 ? 0 : $oDadosAvaliacao->projetada;
  	      	
  	          if ($oDadosProcResult->arredmedia == "S") {
  	      		
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosAvaliacao->aprvto), 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosAvaliacao->aprvto), 0);
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 0);
  	      	  	
  	      	    }
  	      		
  	      	  } else {
  	      	  
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosAvaliacao->aprvto, 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format($iProjetada, 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosAvaliacao->aprvto, 0);
  	      	  	  $sNotaProjetada = number_format($iProjetada, 0);
  	      	  	
  	      	    }	
  	      		
  	      	  }
  	      	
  	        } elseif ($oDadosProcResult->obtencao == "MN") {
  	      	
  	      	  $sCamposMN       = " max(ed72_i_valornota) as aprvto ";
  	      	  $sWhereMN        = " ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	      	  $sWhereMN       .= " AND ed95_i_regencia ".$oDadosRegencia->ed59_i_codigo;
  	      	  $sWhereMN       .= " AND ed72_c_amparo = 'N' AND ed72_i_valornota IS NOT NULL ";
  	      	  $sWhereMN       .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	      	  $sSqlAvaliacao   = $oDaoDiarioAvaliacao->sql_query("", $sCamposMN, "", $sWhereMN);
  	      	  $rsAvaliacao     = $oDaoDiarioAvaliacao->sql_record($sSqlAvaliacao);
  	      	  $oDadosAvaliacao = db_utils::fieldsmemory($rsAvaliacao, 0);
  	      	
  	      	  $iProjetada      = $oDadosAvaliacao->aprvto >= $oDadosProcResult->minimoaprovres ? 0 :
  	      	                         ($oDadosProcResult->minimoaprovres - $oDadosAvaliacao->aprvto);
  	      	                         
  	          if ($oDadosProcResult->arredmedia == "S") {
  	      		
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosAvaliacao->aprvto), 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosAvaliacao->aprvto), 0);
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 0);
  	      	  	
  	      	    }
  	      		
  	      	  } else {
  	      	  
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosAvaliacao->aprvto, 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format($iProjetada, 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosAvaliacao->aprvto, 0);
  	      	  	  $sNotaProjetada = number_format($iProjetada, 0);
  	      	  	
  	      	    }	
  	      		
  	      	  }
  	      	
  	        } elseif ($oDadosProcResult->obtencao == "UN") {
  	      	
  	      	  $sCamposUN       = " ed72_c_amparo as ultamparo,ed72_i_valornota as aprvto ";
  	      	  $sWhereUN        = " ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	      	  $sWhereUN       .= " AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	      	  $sOrderUN        = " ed41_i_sequencia DESC LIMIT 1 ";
  	      	  $sSqlAvaliacao   = $oDaoDiarioAvaliacao->sql_query("", $sCamposUN, $sOrderUN, $sWhereUN);
  	      	  $rsAvaliacao     = $oDaoDiarioAvaliacao->sql_record($sSqlAvaliacao);
  	      	  $oDadosAvaliacao = db_utils::fieldsmemory($rsAvaliacao, 0);
  	      	
  	      	  $iProjetada      = $oDadosAvaliacao->aprvto >= $oDadosProcResult->minimoaprovres ? 0 :
  	      	                         ($oDadosProcResult->minimoaprovres - $oDadosAvaliacao->aprvto);
  	      	                         
  	          if ($oDadosProcResult->arredmedia == "S") {
  	      		
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosAvaliacao->aprvto), 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format(round($oDadosAvaliacao->aprvto), 0);
  	      	  	  $sNotaProjetada = number_format(round($iProjetada), 0);
  	      	  	
  	      	    }
  	      		
  	      	  } else {
  	      	  
  	      	    if ($lResultEdu == "S") {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosAvaliacao->aprvto, 2, ".", ".");
  	      	  	  $sNotaProjetada = number_format($iProjetada, 2, ".", ".");
  	      	  	
  	      	    } else {
  	      	  	
  	      	  	  $sResFinal      = number_format($oDadosAvaliacao->aprvto, 0);
  	      	  	  $sNotaProjetada = number_format($iProjetada, 0);
  	      	  	
  	      	    }	
  	      		
  	      	  }
  	      	
  	        }
  	      
  	        $sResFinal      = trim($oDadosMatricula2->ed60_c_situacao) != "MATRICULADO" 
  	                          || $oDadosAvaliacao->aprvto == "" ? "" : $sResFinal;
  	                        
  	        $sNotaProjetada = trim($oDadosMatricula2->ed60_c_situacao) != "MATRICULADO" 
  	                          || $iProjetada == "" ? "" : $sNotaProjetada;
  	                        
  	        if ($iLinhasDiarioAval2 == 0) {
  	      	
  	      	  $sResFinal      = "";
  	      	  $sNotaProjetada = "";
  	      	
  	        }
  	      
  	        if (($iPriAval + 2) <= $oDadosProcAval->ed41_i_sequencia) {
  	      	
  	      	  if (trim($oDadosDiario->ed37_c_tipo) == "NOTA" 
  	      	      && $sResFinal < @$oDadosProcResult->minimoaprovres) {
  	      		
  	      	    $oPdf->SetFont('Arial', 'b', 10);
  	      	    $oPdf->Cell(10, 4, $sResFinal, 1, 0, "C", $lCor);
  	      		
  	      	  } else {
  	      	    $oPdf->Cell(10, 4, $sResFinal, 1, 0, "C", $lCor);
  	      	  }
  	      	
  	        }
  	      
  	        if ($oDadosProcAval->ed41_i_sequencia == $iUltAval) {
  	      	  $oPdf->Cell(10, 4, $sNotaProjetada, 1, 0, "C", $lCor);
  	        }
  	    	
  	    } else {
  	      
  	      if (($iPriAval + 2) <= $oDadosProcAval->ed41_i_sequencia) {
  	      	$oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
  	      }	
  	      
  	      if ($oDadosProcAval->ed41_i_sequencia == $iUltAval) {
  	      	$oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
  	      }
  	    	
  	    }      	
  	          	
  	  } elseif ($lNotaBranco == "S" 
  	            && $iLinhasDiarioAval1 > 0
  	            && $iLinhasDiario > 0
  	            && ($oDadosProcResult->obtencao == "ME"
  	                || $oDadosProcResult->obtencao == "MP"
  	                || $oDadosProcResult->obtencao == "SO")) {
  	  	
  	  	if (($iPriAval + 2) <= $oDadosProcAval->ed41_i_sequencia) {
  	  	  $oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
  	  	}
  	  	
  	  	if ($oDadosProcAval->ed41_i_sequencia == $iUltAval) {
  	  	  $oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
  	  	}
  	  	
  	  } elseif ($lNotaBranco == "S" && $iLinhasDiarioAval1 == 0
  	            && ($oDadosProcResult->obtencao == "ME"
  	                || $oDadosProcResult->obtencao == "MP"
  	                || $oDadosProcResult->obtencao == "SO")) {
  	  	
  	    if (($iPriAval + 2) <= $oDadosProcAval->ed41_i_sequencia) {
  	  	  $oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
  	  	}
  	  	
  	  	if ($oDadosProcAval->ed41_i_sequencia == $iUltAval) {
  	  	  $oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
  	  	}
  	  	
  	  }
  	  
  	   $sCamposAval  = " ed72_c_valorconceito,ed72_i_valornota,ed37_c_tipo,ed72_i_escola,ed72_c_tipo,";
  	   $sCamposAval .= " ed81_i_justificativa,ed81_i_convencaoamp,ed250_c_abrev ";
  	   $sWhereAval   = " ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	   $sWhereAval  .= " AND ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	   $sWhereAval  .= " AND ed41_i_sequencia = ".$oDadosProcAval->ed41_i_sequencia;
  	   $sSqlAval     = $oDaoDiarioAvaliacao->sql_query("", $sCamposAval, "ed41_i_sequencia", $sWhereAval);
  	   $rsSqlAval    = $oDaoDiarioAvaliacao->sql_record($sSqlAval);
  	   $iLinhasAval  = $oDaoDiarioAvaliacao->numrows;
  	  
  	   if ($iLinhasAval > 0) {
  	  	 $oDadosDiarioAval2 = db_utils::fieldsmemory($rsSqlAval, 0);
  	   }
  	  
  	   if ($amparo == "S") {
  	  	
  	  	 $oPdf->SetFont('Arial', '', 8);
  	  	
  	  	  if ($oDadosDiarioAval2->ed81_i_justificativa != "") {
  	  	    $oPdf->Cell(10, 4, "AMP", 1, 0, "C", $lCor);
  	  	  } else {
  	  	    $oPdf->Cell(10, 4, $oDadosDiarioAval2->ed250_c_abrev, 1, 0, "C", $lCor);
  	  	  }
  	  	
  	  	  $oPdf->SetFont('Arial', '', 10);
  	  	
  	    } else {
  	  	
  	  	  if ($iLinhasAval > 0) {
  	  	  
  	  	    $oDadosDiarioAval2 = db_utils::fieldsmemory($rsSqlAval, 0);
  	  	  
  	  	    if ($oDadosMatricula2->ed60_c_parecer == "S") {
  	  	  	  $oDadosDiario->ed37_c_tipo = "PARECER";
  	  	    }
  	  	  
  	  	    if ($oDadosDiarioAval2->ed72_i_escola != $oGet->iEscola
  	  	         || $oDadosDiarioAval2->ed72_c_tipo == "F") {
  	  	  	   $sNE = "*";
  	  	    } else {
  	  	  	  $sNE = "";
  	  	    }
  	  	  
  	  	    if ($oDadosDiarioAval2->ed37_c_tipo == "NOTA"
  	  	        && $oDadosDiarioAval2->ed72_i_valornota != "") {
  	  	  	
  	  	      if ($lResultEdu == "S") {
  	  	        $sAprovAtual = number_format($oDadosDiarioAval2->ed72_i_valornota, 2, ".", ".");
  	  	      } else {
  	  	        $sAprovAtual = floor($oDadosDiarioAval2->ed72_i_valornota);
  	  	      } 	
  	  	      	
  	  	    } elseif ($oDadosDiario->ed37_c_tipo == "NOTA"
  	  	              && $oDadosDiarioAval2->ed72_i_valornota == "") {
  	  	      $sAprovAtual = $oDadosDiarioAval2->ed72_i_valornota;
  	  	    } else {
  	  	  	  $sAprovAtual = "";
  	  	    }
  	  	  
  	  	    if (trim($oDadosDiarioAval2->ed37_c_tipo) == "NOTA"
  	  	        && $sAprovAtual < @$oDadosProcResult->minimoaprovres) {
  	  	  	
  	  	      $oPdf->SetFont('Arial', 'b', 10);
  	  	      $oPdf->Cell(10, 4, $sNE.$sAprovAtual, 1, 0, "C", $lCor);
  	  	      $oPdf->SetFont('Arial', '', 10);  	
  	  	      	
  	  	    } else {
  	  	  	  $oPdf->Cell(10, 4, $sNE.$sAprovAtual, 1, 0, "C", $lCor);
  	  	    }
  	  		
  	  	  } else {
  	  	    $oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
  	  	  }
  	  	
  	    }
  	  
  	    $sCamposFaltas = " sum(ed72_i_numfaltas) as faltas ";
  	    $sWhereFaltas  = " ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	    $sWhereFaltas .= " AND ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	    $sWhereFaltas .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	    $sSqlFaltas    = $oDaoDiarioAvaliacao->sql_query("", $sCamposFaltas, "", $sWhereFaltas);
  	    $rsFaltas      = $oDaoDiarioAvaliacao->sql_record($sSqlFaltas);
  	    $iLinhasFaltas = $oDaoDiarioAvaliacao->numrows;
  	  
  	    if ($iLinhasFaltas > 0) {
  	  	  $iFaltas = db_utils::fieldsmemory($rsFaltas, 0)->faltas;
  	    } else {
  	  	  $iFaltas = 0;
  	    }
  	  
  	    $sCamposAbono  = " sum(ed80_i_numfaltas) as faltasabonadas ";
  	    $sWhereAbono   = " ed95_i_aluno = ".$oDadosMatricula2->ed60_i_aluno;
  	    $sWhereAbono  .= " AND ed95_i_regencia = ".$oDadosRegencia->ed59_i_codigo;
  	    $sWhereAbono  .= " AND ed41_i_sequencia < ".$oDadosProcAval->ed41_i_sequencia;
  	    $sSqlAbono     = $oDaoAbonoFalta->sql_query("", $sCamposAbono, "", $sWhereAbono);
  	    $rsAbonoFalta  = $oDaoAbonoFalta->sql_record($sSqlAbono);
  	    $iLInhasAbono  = $oDaoAbonoFalta->numrows;
  	  
  	    if ($iLInhasAbono > 0) {
  	  	  $iFaltasAbonadas = db_utils::fieldsmemory($rsAbonoFalta, 0)->faltasabonadas;
  	    } else {
  	  	  $iFaltasAbonadas = 0;
  	    }
  	  
  	    $oPdf->Cell(5, 4, $iFaltas, 1, 0, "C", $lCor);
  	  
  	    if ($oGet->lAbono == "true") {
  	  	  $oPdf->Cell(5, 4, $iFaltasAbonadas, 1, 0, "C", $lCor);
  	    }
  	  
  	    if ($oGet->lTotalFaltas == "true") {
  	  	  $oPdf->Cell(5, 4, $iFaltas + $iFaltasAbonadas, 1, 0, "C", $lCor);
  	    }
  		
  	  } else {
  		
  	    $iLarguraCell = $oDaoProcAvaliacao->numrows * 10 + 25 + $iLargMp;
  	    $oPdf->Cell($iLarguraCell, 4, trim(substr(Situacao($oDadosMatricula2->ed60_c_situacao, 
  	                                       $oDadosMatricula2->ed60_i_codigo), 0, 11)), 1, 0, "C", $lCor);
  	  
  	  }
  	
  	  $oPdf->SetFont('Arial', '', 8);
  	
  	  if ($oGet->lCodigo == "true") {
  	    $oPdf->Cell(12, 4, $oDadosMatricula2->ed60_i_codigo, 1, 0, "C", $lCor);
  	  }
  	
  	  if ($oDadosMatricula2->ed60_c_situacao != "MATRICULADO") {
  	    $oPdf->Cell(18 + ($iQuadros * 5), 4, trim($oDadosMatricula2->ed60_c_situacao), 1, 1, "C", $lCor);
  	  } else {
  	  
  	    if ($amparo == "S") {
  	  	
  	  	  if ($oDadosDiarioAval2->ed81_i_justificativa != "") {
  	  	    $oPdf->Cell(18 + ($iQuadros * 5), 4, "AMPARO", 1, 1, "C", $lCor);
  	  	  } else {
  	  	    $oPdf->Cell(18 + ($iQuadros * 5), 4, $oDadosDiarioAval2->ed250_c_abrev, 1, 1, "C", $lCor);
  	  	  }
  	  	
  	    } else {
  	  	
  	  	  if ($oGet->lParecer == "true") {
  	  	  
  	  	    $oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);	
  	  	    $oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	    $oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  		
  	  	  }
  	  	
  	  	  if ($oGet->lSexo == "false") {
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	  }
  	  	
  	  	  if ($oGet->lIdade == "false") {
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	  }
  	  	
  	      if ($oGet->lAbono == "false") {
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	  }
  	  	
  	  	  if ($oGet->lCodigo == "false") {
  	  	  
  	  	    $oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	    $oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  		
  	  	  }
  	  	
  	  	  if ($oGet->lNascimento == "false") {
  	  		
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);	
  	  		
  	  	  }
  	  	
  	  	  if ($oGet->lResultAnt == "false") {
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	  }
  	  	
  	  	  if ($oGet->lTotalFaltas == "false") {
  	  	    $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	  }
  	  	
  	      if ($oGet->lParecer == "false") {
  	  	  
  	  	    $oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);	
  	  	    $oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	    $oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  		
  	  	  }
  	  	
  	  	  for ($iCont3 = 0; $iCont3 < $iQuadros; $iCont3++) {
  	  	    $oPdf->Cell(5, 4, "", 1, $iCont3 == ($iQuadros -1) ? 1 : 0, "C", $lCor);
  	  	  }
  	  	
  	    }	
  		
  	  }
  	
  	  if ($iContador == $iLimit && $iContadorGeral < $oDaoMatricula2->numrows) {
  	  
  	    $oPdf->SetFont('Arial', 'b', 7);
  	  
  	    if ($oGet->lSexo == "true") {
  	  	  $sSubSexo = " S - Sexo |";
  	    } else {
  	  	  $sSubSexo = "";
  	    }
  	  
  	    if ($oGet->lIdade == "true") {
  	  	  $sSubIdade = "I - Idade |";
  	    } else {
  	      $sSubIdade = "";
  	    }
  	  
  	    if ($oGet->lTotalFaltas == "true") {
  	  	  $sSubTF = "TF - Total de Faltas |";
  	    } else {
  	  	  $sSubTF = "";
  	    }
  	  
  	    if ($oGet->lResultAnt == "true") {
  	  	  $sSubRA = "RA - (A-Aprovado R-Reprovado T-Tranferido C-Cancelado E-Evadido F-Falecido) |";
  	    } else {
  	  	  $sSubRA = "";
  	    }
  	  
  	    if ($oGet->lAbono == "true") {
  	  	  $sSubAbono = "FA - Faltas Abonadas |";
  	    } else {
  	  	  $sSubAbono = "";
  	    }
  	  
  	    $sMsg  = $sSubRA." F - Faltas | ".$sSubIdade." ".$sSubSexo;
  	    $sMsg .= $sSubAbono." ".$sSubTF." AMP - Amparado | NP - Nota Parcial ";
  	    $sMsg .= " | P - Nota Projetada | * - Nota Externa";
  	  
  	    $iLarguraCell = 135 + ($oDaoProcAvaliacao->numrows * 10) + ($iQuadros * 5) + $iLargMp;
  	    $oPdf->Cell($iLarguraCell, 5, $sMsg, 1, 1, "L", 0);
  	  
  	    $sMsgAssinatura = "Assinatura do professor:_________________________________";
  	    $oPdf->Cell($iLarguraCell, 5, $sMsgAssinatura, 1, 1, "L", 0);
  	  
  	    $oPdf->AddPage("L");
  	    $oPdf->SetFont('Arial', '', 8);
  	    $oPdf->Cell(5, 4, "Nº", 1, 0, "C", 0);
  	    $oPdf->Cell(40, 4, "Nome do Aluno", 1, 0, "C", 0);
  	    $oPdf->SetFont('Arial', 'b', 8);
  	  
  	    if ($oGet->lSexo == "true") {
  	  	  $oPdf->Cell(5, 4, "S", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lNascimento == "true") {
  	  	  $oPdf->Cell(20, 4, "Nascimento", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lIdade == "true") {
  	  	  $oPdf->Cell(5, 4, "I", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lResultAnt == "true") {
  	  	  $oPdf->Cell(5, 4, "RA", 1, 0, "C", 0);
  	    }
  	  
  	    for ($iProcAval = 0; $iProcAval < $oDaoProcAvaliacao->numrows; $iProcAval++) {
  	  	
  	  	  $oDadosProcAvaliacao = db_utils::fieldsmemory($rsProcAvaliacao, $iContPA);
  	      $oPdf->Cell(10, 4, $oDadosProcAvaliacao->ed09_c_abrev, 1, 0, "C", 0);
  	  	
  	    }
  	  
  	    if ($lNotaBranco == "S"
  	        && ($oDadosProcResult->obtencao == "ME"
  	            || $oDadosProcResult->obtencao == "MP"
  	            || $oDadosProcResult->obtencao == "SO")) {
  	  	
  	      if (($iPriAval + 2) <= $oDadosProcAval->ed41_i_sequencia) {
  	        $oPdf->Cell(10, 4, "NP", 1, 0, "C", 0);
  	      }        	
  	    
  	      if ($oDadosProcAval->ed41_i_sequencia == $iUltAval) {
  	        $oPdf->Cell(10, 4, "P", 1, 0, "C", 0);
  	      }
  	          	
  	    }
  	  
  	    $oPdf->Cell(10, 4, substr($oDadosDiario->ed37_c_tipo, 0, 5), 1, 0, "C", 0);
  	    $oPdf->Cell(5, 4, "F", 1, 0, "C", 0);
  	  
  	    if ($oGet->lAbono == "true") {
  	  	  $oPdf->Cell(5, 4, "FA", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lTotalFaltas == "true") {
  	  	  $oPdf->Cell(5, 4, "TF", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lCodigo == "true") {
  	  	  $oPdf->Cell(12, 4, "Código", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lParecer == "true") {
  	  	  $oPdf->Cell(18, 4, "Parecer", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lSexo == "false") {
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lIdade == "false") {
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lAbono == "false") {
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lCodigo == "false") {
  	  	
  	  	  $oPdf->Cell(6, 4, "", 1, 0, "C", 0);
  	  	  $oPdf->Cell(6, 4, "", 1, 0, "C", 0);
  	  	
  	    }
  	  
  	    if ($oGet->lNascimento == "false") {
  	  	
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	  	
  	    }
  	  
  	    if ($oGet->lResultAnt == "false") {
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lTotalFaltas == "false") {
  	  	  $oPdf->Cell(5, 4, "", 1, 0, "C", 0);
  	    }
  	  
  	    if ($oGet->lParecer == "false") {
  	  	  $oPdf->Cell(18, 4, "", 1, 0, "C", 0);
  	    }
  	  
  	    for ($iQuadros2 = 0; $iQuadros2 < $iQuadros; $iQuadros2++) {
  	  	  $oPdf->Cell(5, 4, "", 1, $iQuadros2 == ($iQuadros - 1) ? 1 : 0, "C", 0);
  	    }
  	  
  	    $iContador = 0;
  		
  	  }
  	
    }
  
    $iTermino = $oPdf->GetY();
    
    for ($iP = 0; $iP < ($iLimit - 1); $iP++) {
    	
      $lCor = $lCor == true ? false : true;

      $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
      $oPdf->Cell(40, 4, "", 1, 0, "C", $lCor);
      
      if ($oGet->lSexo == "true") {
      	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
      }
      
      if ($oGet->lNascimento == "true") {
      	$oPdf->Cell(20, 4, "", 1, 0, "C", $lCor);
      }
      
      if ($oGet->lIdade == "true") {
      	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
      }
      
      if ($oGet->lResultAnt == "true") {
      	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
      }
      
      for ($iContP = 0; $iContP < $oDaoProcAvaliacao->numrows; $iContP++) {
      	$oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
      }
      
      if ($lNotaBranco == "S"
  	      && ($oDadosProcResult->obtencao == "ME"
  	          || $oDadosProcResult->obtencao == "MP"
  	          || $oDadosProcResult->obtencao == "SO")) {
  	  	
  	    if (($iPriAval + 2) <= $oDadosProcAval->ed41_i_sequencia) {
  	      $oPdf->Cell(10, 4, "NP", 1, 0, "C", 0);
  	    }        	
  	    
  	    if ($oDadosProcAval->ed41_i_sequencia == $iUltAval) {
  	      $oPdf->Cell(10, 4, "P", 1, 0, "C", 0);
  	    }
  	          	
  	  }
  	  
  	  $oPdf->Cell(10, 4, "", 1, 0, "C", $lCor);
  	  $oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  
  	  if ($oGet->lAbono == "true") {
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  }
  	  
  	  if ($oGet->lTotalFaltas == "true") {
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  }
  	  
  	  if ($oGet->lCodigo == "true") {
  	  	$oPdf->Cell(12, 4, "", 1, 0, "C", $lCor);
  	  }
  	  
  	  if ($oGet->lParecer == "true") {
  	  	
  	  	$oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	$oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	$oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	
  	  }
  	  
  	  if ($oGet->lSexo == "false") {
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  }
  	  
  	  if ($oGet->lIdade == "false") {
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  }
  	  
  	  if ($oGet->lAbono == "false") {
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  }
  	  
  	  if ($oGet->lCodigo == "false") {
  	  	
  	  	$oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	$oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	
  	  }
  	  
  	  if ($oGet->lNascimento == "false") {
  	  	
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  	
  	  }
  	  
  	  if ($oGet->lResultAnt == "false") {
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  }
  	  
  	  if ($oGet->lTotalFaltas == "false") {
  	  	$oPdf->Cell(5, 4, "", 1, 0, "C", $lCor);
  	  }
  	  
  	  if ($oGet->lParecer == "false") {
  	  	
  	  	$oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	$oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	$oPdf->Cell(6, 4, "", 1, 0, "C", $lCor);
  	  	
  	  }
  	  
  	  for ($iContQ = 0; $iContQ < $iQuadros; $iContQ++) {
  	  	$oPdf->Cell(5, 4, "", 1, $iContQ == ($iQuadros -1) ? 1 : 0, "C", $lCor);
  	  }
    	
    }
    
    $oPdf->SetFont('Arial', 'b', 7);
    
    if ($oGet->lSexo == "true") {
  	  $sSubSexo = " S - Sexo | ";
  	} else {
  	  $sSubSexo = "";
  	}
  	  
  	if ($oGet->lIdade == "true") {
  	  $sSubIdade = "I - Idade | ";
  	} else {
  	  $sSubIdade = "";
  	}
  	  
  	if ($oGet->lTotalFaltas == "true") {
  	  $sSubTF = "TF - Total Faltas | ";
  	} else {
  	  $sSubTF = "";
  	}
  	  
  	if ($oGet->lResultAnt == "true") {
  	  $sSubRA = "RA - (A-Aprovado R-Reprovado T-Tranferido C-Cancelado E-Evadido F-Falecido) | ";
  	} else {
  	  $sSubRA = "";
  	}
  	  
  	if ($oGet->lAbono == "true") {
  	  $sSubAbono = "FA - Faltas Abonadas | ";
  	} else {
  	  $sSubAbono = "";
  	}
  	  
  	$sMsg  = $sSubRA." F - Faltas | ".$sSubIdade." ".$sSubSexo;
  	$sMsg .= $sSubAbono." ".$sSubTF." AMP - Amparo | NP - Nota Parcial ";
  	$sMsg .= " | P - Nota Projetada | * - Nota Externa";
  	  
  	$iLarguraCell = 135 + ($oDaoProcAvaliacao->numrows * 10) + ($iQuadros * 5) + $iLargMp;
  	$oPdf->Cell($iLarguraCell, 5, $sMsg, 1, 1, "L", 0);
  	  
  	$sMsgAssinatura = "Assinatura do professor:_________________________________";
  	$oPdf->Cell($iLarguraCell, 5, $sMsgAssinatura, 1, 1, "L", 0);
  
  }
  
  $oPdf->Output();

?>
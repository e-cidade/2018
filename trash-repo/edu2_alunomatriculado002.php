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

require_once("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
$oDaoSerie      = db_utils::getdao("serie");
$oDaoCalendario = db_utils::getdao("calendario");
$oDaoMatricula  = db_utils::getdao("matricula");
$head3          = "";
$head4          = "";

if ($iSerieEscolhida != 0) {
	
  $sWhere    = " AND ed11_i_codigo = $iSerieEscolhida";
  $sSqlSerie = $oDaoSerie->sql_query("",  "ed11_c_descr",  "",  " ed11_i_codigo = $iSerieEscolhida");
  $rsSerie   = $oDaoSerie->sql_record($sSqlSerie);  
  $head3     = " Etapa: ".trim(db_utils::fieldsmemory($rsSerie,  0)->ed11_c_descr);
  
} else {
	
  $sWhere = "";
  $head3  = "Etapa: TODAS";
  
}

if ($iFiltro == 1) {
  $head4 = " Filtro: TURMAS E PERCENTUAIS";
} else if ($iFiltro == 2) {
  $head4 = " Filtro: TURMAS";
}

$sCamposMat    = " count(ed60_i_codigo) as qtdmatr, ed10_c_descr, ed11_i_codigo, ed11_c_descr, ed57_c_descr,  ";
$sCamposMat   .= " ed57_i_codigo, ed57_i_numvagas as vagas, ed15_c_nome ";
$sOrder        = " ed10_c_abrev, ed11_i_sequencia, ed57_c_descr";
$sWhereMat     = " ed57_i_calendario = $iCalendario AND ed57_i_escola = $iEscola AND ed221_c_origem = 'S'".$sWhere;
$sGroupBy      = " group by ed10_c_descr, ed11_i_codigo, ed11_c_descr, ed10_c_abrev, ed11_i_sequencia, ed57_c_descr, ";
$sGroupBy     .= " ed57_i_codigo,ed57_i_numvagas, ed57_i_nummatr, ed15_c_nome"; 
$sSqlMatricula = $oDaoMatricula->sql_query_alunomatriculado("",  $sCamposMat,  $sOrder,  $sWhereMat.$sGroupBy);
$rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);  
$iLinhasMat    = $oDaoMatricula->numrows;

if ($iLinhasMat == 0) {
	
  echo " <table width='100%'> ";
  echo "  <tr> ";
  echo "   <td align='center'>";
  echo "    <font color='#FF0000' face='arial'> ";
  echo "     <b>Nenhum registro encontrado.<br>";
  echo "     <input type='button' value='Fechar' onclick='window.close()'></b> ";
  echo "    </font> ";
  echo "   </td> ";
  echo "  </tr> ";
  echo " </table> ";
  exit;
  
}

$sSqlCalendario = $oDaoCalendario->sql_query("",  "ed52_c_descr",  "",  " ed52_i_codigo = $iCalendario");
$rsCalendario   = $oDaoCalendario->sql_record($sSqlCalendario);
$oPdf = new Pdf();
$oPdf->Open();
$oPdf->AliasNbPages();
$head1 = "RELATÓRIO DE ALUNOS MATRICULADOS";
$head2 = "Calendário: ".db_utils::fieldsmemory($rsCalendario,  0)->ed52_c_descr;
$oPdf->setfillcolor(223);
$oPdf->addpage('P');
$oPdf->ln(5);
/**
 * Variáveis que fazem a soma de todos os alunos conforme a situação da matricula
 * iSomaMatriculado, iSomaEvadido, iSomaCancelado, iSomaTransferido, iSomaProgredido, iSomaFalecido
 * 
 */
$iEnsino          = "";
$iSerie           = "";
$iTurma           = "";
$iSomaTotal       = 0;
$iSomaMatriculado = 0;
$iSomaEvadido     = 0;
$iSomaCancelado   = 0;
$iSomaTransferido = 0;
$iSomaProgredido  = 0;
$iSomaFalecido    = 0;
$iSomaDisponiveis = 0;
$iSomaVagas       = 0;
/**
 * Verifica a soma total por serie e pela situacao no aluno 
 * 
 */
$iSomaSerieTotal       = 0;
$iSomaSerieMatriculado = 0;
$iSomaSerieEvadido     = 0;
$iSomaSerieCancelado   = 0;
$iSomaSerieTransferido = 0;
$iSomaSerieProgredido  = 0;
$iSomaSerieFalecido    = 0;
$iSomaSerieDisponiveis = 0;
$iSomaSerieVagas       = 0;
/**
 * Soma as matriculas por ensino e pela situacao do aluno 
 * 
 */
$iSomaEnsinoTotal       = 0;
$iSomaEnsinoMatriculado = 0;
$iSomaEnsinoEvadido     = 0;
$iSomaEnsinoCancelado   = 0;
$iSomaEnsinoTransferido = 0;
$iSomaEnsinoProgredido  = 0;
$iSomaEnsinoFalecido    = 0;
$iSomaEnsinoDisponiveis = 0;
$iSomaEnsinoVagas       = 0;
$iCorEtapa              = "225"; //cor1
$iCor                   = "240"; //cor2
$iCorTotalEnsino        = "215"; //cor3
$iCorEnsino             = "180"; //cor4

for ($iCont = 0; $iCont < $iLinhasMat; $iCont++) {
	
  $oDadosMatricula = db_utils::fieldsmemory($rsMatricula, $iCont);
  
  if ($iSerie != $oDadosMatricula->ed11_c_descr) {
  	
    if ($iCont != 0) {
    	
      $oPdf->setfillcolor($iCorEtapa);
      $oPdf->setfont('arial', 'b', 8);
      $oPdf->cell(60, 4, "Total da Etapa $iSerie", 1, 0, "R", 1);
      $oPdf->cell(15, 4, "$iSomaSerieTotal", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "$iSomaSerieEvadido", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "$iSomaSerieCancelado", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "$iSomaSerieTransferido", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "$iSomaSerieProgredido", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "$iSomaSerieFalecido", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "$iSomaSerieMatriculado", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "$iSomaSerieVagas", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "$iSomaSerieDisponiveis", 1, 1, "C", 1);
      
      if ($iFiltro == 1) {
      	
        $oPdf->setfillcolor($iCorEtapa);
        $oPdf->setfont('arial', 'b', 8);
        $oPdf->cell(60, 4, "Percentuais:", 1, 0, "R", 1);
        $oPdf->cell(15, 4, "", 1, 0, "C", 1);
        $oPdf->cell(15, 4, number_format(($iSomaSerieEvadido/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
        $oPdf->cell(15, 4, number_format(($iSomaSerieCancelado/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
        $oPdf->cell(15, 4, number_format(($iSomaSerieTransferido/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
        $oPdf->cell(15, 4, number_format(($iSomaSerieProgredido/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
        $oPdf->cell(15, 4, number_format(($iSomaSerieFalecido/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
        $oPdf->cell(15, 4, number_format(($iSomaSerieMatriculado/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "", 1, 0, "C", 1);
        $oPdf->cell(15, 4, number_format(($iSomaSerieDisponiveis/$iSomaSerieVagas)*100, 2, ", ", ".")."%",
                    1, 1, "C", 1
                   );
        
      }//fecha o if $lFiltro
      
      $iSomaSerieTotal       = 0;
      $iSomaSerieMatriculado = 0;
      $iSomaSerieEvadido     = 0;
      $iSomaSerieCancelado   = 0;
      $iSomaSerieTransferido = 0;
      $iSomaSerieProgredido  = 0;
      $iSomaSerieFalecido    = 0;
      $iSomaSerieDisponiveis = 0;
      $iSomaSerieVagas       = 0;
      
      
    }//fecha o if $iCont != 0
    
    if ($iEnsino != $oDadosMatricula->ed10_c_descr) {
    	
      if ($iCont != 0) {
      	
        $oPdf->setfillcolor($iCorTotalEnsino);
        $oPdf->setfont('arial', 'b', 8);
        $oPdf->cell(60, 4, "Total ".substr($iEnsino, 0, 28), 1, 0, "R", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoTotal", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoEvadido", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoCancelado", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoTransferido", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoProgredido", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoFalecido", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoMatriculado", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoVagas", 1, 0, "C", 1);
        $oPdf->cell(15, 4, "$iSomaEnsinoDisponiveis", 1, 1, "C", 1);
        
        if ($iFiltro == 1) {
        	
          $oPdf->setfillcolor($iCorTotalEnsino);
          $oPdf->setfont('arial', 'b', 8);
          $oPdf->cell(60, 4, "Percentuais:", 1, 0, "R", 1);
          $oPdf->cell(15, 4, "", 1, 0, "C", 1);
          $oPdf->cell(15, 4, number_format(($iSomaEnsinoEvadido/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 
                      1, 0, "C", 1
                     );
          $oPdf->cell(15, 4, number_format(($iSomaEnsinoCancelado/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 
                      1, 0, "C", 1
                     );
          $oPdf->cell(15, 4, number_format(($iSomaEnsinoTransferido/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 
                      1, 0, "C", 1
                     );
          $oPdf->cell(15, 4, number_format(($iSomaEnsinoProgredido/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 
                      1, 0, "C", 1
                     );
          $oPdf->cell(15, 4, number_format(($iSomaEnsinoFalecido/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 
                      1, 0, "C", 1
                     );
          $oPdf->cell(15, 4, number_format(($iSomaEnsinoMatriculado/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 
                      1, 0, "C", 1
                     );
          $oPdf->cell(15, 4, "", 1, 0, "C", 1);
          $oPdf->cell(15, 4, number_format(($iSomaEnsinoDisponiveis/$iSomaEnsinoVagas)*100, 2, ", ", ".")."%",
                      1, 1, "C", 1
                     );
          
        }//fecha o if $lFiltro
        
        $iSomaEnsinoTotal       = 0;
        $iSomaEnsinoMatriculado = 0;
        $iSomaEnsinoEvadido     = 0;
        $iSomaEnsinoCancelado   = 0;
        $iSomaEnsinoTransferido = 0;
        $iSomaEnsinoTransferido = 0;
        $iSomaEnsinoFalecido    = 0;
        $iSomaEnsinoDisponiveis = 0;
        $iSomaEnsinoVagas       = 0;
        
      }//fecha o if $iCont != 0
      
      $oPdf->setfillcolor($iCorEnsino);
      $oPdf->setfont('arial', 'b', 9);
      $oPdf->cell(195, 4, $oDadosMatricula->ed10_c_descr, 1, 1, "L", 1);
      $iEnsino = $oDadosMatricula->ed10_c_descr;
      
    }//fecha o if $ensino != $ed10_c_descr
    
    $oPdf->setfillcolor(0);
    $oPdf->cell(195, 0.5, "", 1, 1, "C", 1);
    $oPdf->setfillcolor($iCorEtapa);
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(60, 4, "Etapa: $oDadosMatricula->ed11_c_descr", 1, 0, "L", 1);
    $oPdf->cell(15, 4, "Matr.Inic.", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "EVAD.", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "CANC.", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "TRANS.", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "PROGR.", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "ÓBITO", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "Matr.Efet.", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "Vagas", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "Vag.Disp.", 1, 1, "C", 1);
    $oPdf->setfillcolor(0);
    $oPdf->cell(195, 0.5, "", 1, 1, "C", 1);
    $iSerie = $oDadosMatricula->ed11_c_descr;
    
  }//fecha o if ($iSerie != $ed11_c_descr)
  
  if ($iFiltro == 1 || $iFiltro == 2) {
  	
    $oPdf->setfillcolor($iCor);
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(40, 4, "Turma: $oDadosMatricula->ed57_c_descr", "BTL", 0, "L", 0);
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(20, 4, "Turno: $oDadosMatricula->ed15_c_nome", "BTR", 0, "R", 0);
    $oPdf->cell(15, 4, $oDadosMatricula->qtdmatr, 1, 0, "C", 0);
    
  }
  
  $sCamposMatr    = "count(ed60_i_codigo) as qtdsituacao, ed60_c_situacao";
  $sWhereMatr     = " ed60_i_turma = $oDadosMatricula->ed57_i_codigo AND ed221_i_serie = $oDadosMatricula->ed11_i_codigo AND ed221_c_origem = 'S'";
  $sGroupByMatr   = " Group By ed60_c_situacao"; 
  $sSqlMatr       = $oDaoMatricula->sql_query_matriculaserie("", $sCamposMatr ,  "",  $sWhereMatr.$sGroupByMatr);
  $rsMatr         = $oDaoMatricula->sql_record($sSqlMatr);  
  $iLinhasMatr    = $oDaoMatricula->numrows;
    
  $sSituacao              = array("EVADIDO", "CANCELADO", "TRANSFERIDO", "PROGREDIDO", "FALECIDO", "MATRICULADO");
  $iSomaMatriculaTurma    = 0;
  $iSomaEvadidoTurma      = 0;
  $iSomaCanceladoTurma    = 0;
  $iSomaTrasnsferidoTurma = 0;
  $iSomaProgredidoTurma   = 0;
  $iSomaFalecidoTurma     = 0;
  
  for ($iContSituacao = 0; $iContSituacao < 6; $iContSituacao++) {
  	
    for ($iContMat = 0; $iContMat < $iLinhasMatr; $iContMat++) {
    	
      $oDadosMatr = db_utils::fieldsmemory($rsMatr, $iContMat);
      
      if (trim($oDadosMatr->ed60_c_situacao) == "TRANSFERIDO REDE" || trim($oDadosMatr->ed60_c_situacao) == "TRANSFERIDO FORA" 
          || trim($oDadosMatr->ed60_c_situacao) == "TROCA DE MODALIDADE") {
          	
        $oDadosMatr->ed60_c_situacao = "TRANSFERIDO";
                
      } elseif (trim($oDadosMatr->ed60_c_situacao) == "AVANÇADO" || trim($oDadosMatr->ed60_c_situacao) == "CLASSIFICADO") {
        $oDadosMatr->ed60_c_situacao = "PROGREDIDO";
      }
      
      if (trim($oDadosMatr->ed60_c_situacao) == $sSituacao[$iContSituacao]) {
      
        if ($oDadosMatr->ed60_c_situacao == "MATRICULADO") $iSomaMatriculaTurma    += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "MATRICULADO") $iSomaMatriculado       += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "MATRICULADO") $iSomaSerieMatriculado  += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "MATRICULADO") $iSomaEnsinoMatriculado += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "EVADIDO")     $iSomaEvadidoTurma      += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "EVADIDO")     $iSomaEvadido           += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "EVADIDO")     $iSomaSerieEvadido      += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "EVADIDO")     $iSomaEnsinoEvadido     += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "CANCELADO")   $iSomaCanceladoTurma    += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "CANCELADO")   $iSomaCancelado         += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "CANCELADO")   $iSomaSerieCancelado    += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "CANCELADO")   $iSomaEnsinoCancelado   += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "TRANSFERIDO") $iSomaTrasnsferidoTurma += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "TRANSFERIDO") $iSomaTransferido       += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "TRANSFERIDO") $iSomaSerieTransferido  += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "TRANSFERIDO") $iSomaEnsinoTransferido += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "PROGREDIDO")  $iSomaProgredidoTurma   += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "PROGREDIDO")  $iSomaProgredido        += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "PROGREDIDO")  $iSomaSerieProgredido   += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "PROGREDIDO")  $iSomaEnsinoProgredido  += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "FALECIDO")    $iSomaFalecidoTurma     += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "FALECIDO")    $iSomaFalecido          += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "FALECIDO")    $iSomaSerieFalecido     += $oDadosMatr->qtdsituacao;
        if ($oDadosMatr->ed60_c_situacao == "FALECIDO")    $iSomaEnsinoFalecido    += $oDadosMatr->qtdsituacao;
        
      }//fecha o if trim($ed60_c_situacao) == $sSituacao[$iContSituacao]
      
    }//fecha o for $iContMat = 0; $iContMat < $iLinhasMatr; $iContMat++
    
  }//fecha o for situacao
  
  for ($iContSit = 0; $iContSit < 6; $iContSit++) {
  	
    if ($sSituacao[$iContSit] == "EVADIDO") {
      $oPdf->cell(15, 4, "$iSomaEvadidoTurma", 1, 0, "C", 0);
    }
     
    if ($sSituacao[$iContSit] == "CANCELADO") {
      $oPdf->cell(15, 4, "$iSomaCanceladoTurma", 1, 0, "C", 0);
    }
    
    if ($sSituacao[$iContSit] == "TRANSFERIDO") {
      $oPdf->cell(15, 4, "$iSomaTrasnsferidoTurma", 1, 0, "C", 0);
    }
    
    if ($sSituacao[$iContSit] == "PROGREDIDO") {
      $oPdf->cell(15, 4, "$iSomaProgredidoTurma", 1, 0, "C", 0);
    }
    
    if ($sSituacao[$iContSit] == "FALECIDO") {
      $oPdf->cell(15, 4, "$iSomaFalecidoTurma", 1, 0, "C", 0);
    }
    
    if ($sSituacao[$iContSit] == "MATRICULADO") {
      $oPdf->cell(15, 4, "$iSomaMatriculaTurma", 1, 0, "C", 0);
    }
    
  }//fecha o for situ
  
  $iDisponiveis = $oDadosMatricula->vagas-$iSomaMatriculaTurma;
  
  if ($iFiltro == 1 || $iFiltro == 2) {
  	
    $oPdf->cell(15, 4, "$oDadosMatricula->vagas", 1, 0, "C", 0);
    $oPdf->cell(15, 4, "$iDisponiveis", 1, 1, "C", 0);
    
  }
  
  $iSomaTotal             += $oDadosMatricula->qtdmatr;
  $iSomaDisponiveis       += $iDisponiveis;
  $iSomaVagas             += $oDadosMatricula->vagas;
  $iSomaSerieTotal        += $oDadosMatricula->qtdmatr;
  $iSomaSerieDisponiveis  += $iDisponiveis;
  $iSomaSerieVagas        += $oDadosMatricula->vagas;
  $iSomaEnsinoTotal       += $oDadosMatricula->qtdmatr;
  $iSomaEnsinoDisponiveis += $iDisponiveis;
  $iSomaEnsinoVagas       += $oDadosMatricula->vagas;
  
  if ($iCont+1 == $iLinhasMat) {
  	
    $oPdf->setfillcolor($iCorEtapa);
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(60, 4, "Total da etapa $iSerie", 1, 0, "R", 1);
    $oPdf->cell(15, 4, "$iSomaSerieTotal", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaSerieEvadido", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaSerieCancelado", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaSerieTransferido", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaSerieProgredido", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaSerieFalecido", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaSerieMatriculado", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaSerieVagas", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaSerieDisponiveis", 1, 1, "C", 1);
    
    if ($iFiltro == 1) {
    	
      $oPdf->setfillcolor($iCorEtapa);
      $oPdf->setfont('arial', 'b', 8);
      $oPdf->cell(60, 4, "Percentuais:", 1, 0, "R", 1);
      $oPdf->cell(15, 4, "", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaSerieEvadido/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaSerieCancelado/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaSerieTransferido/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaSerieProgredido/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaSerieFalecido/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaSerieMatriculado/$iSomaSerieTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaSerieDisponiveis/$iSomaSerieVagas)*100, 2, ", ", ".")."%", 1, 1, "C", 1);
      
    }
    
    $oPdf->setfillcolor($iCorTotalEnsino);
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(60, 4, "Total ".substr($iEnsino, 0, 28), 1, 0, "R", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoTotal", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoEvadido", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoCancelado", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoTransferido", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoProgredido", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoFalecido", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoMatriculado", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoVagas", 1, 0, "C", 1);
    $oPdf->cell(15, 4, "$iSomaEnsinoDisponiveis", 1, 1, "C", 1);
    
    if ($iFiltro == 1) {
    	
      $oPdf->setfillcolor($iCorTotalEnsino);
      $oPdf->setfont('arial', 'b', 8);
      $oPdf->cell(60, 4, "Percentuais:", 1, 0, "R", 1);
      $oPdf->cell(15, 4, "", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaEnsinoEvadido/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaEnsinoCancelado/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaEnsinoTransferido/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaEnsinoProgredido/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaEnsinoFalecido/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaEnsinoMatriculado/$iSomaEnsinoTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
      $oPdf->cell(15, 4, "", 1, 0, "C", 1);
      $oPdf->cell(15, 4, number_format(($iSomaEnsinoDisponiveis/$iSomaEnsinoVagas)*100, 2, ", ", ".")."%",
                  1, 1, "C", 1
                 );
      
    }//fecha o if lFiltro
    
  }//fecha o if $iCont+1 == $iLinhasMat
  
}//fecha o for $iCont = 0; $iCont < $iLinhasMat; $iCont++

$oPdf->setfillcolor($iCorEnsino);
$oPdf->setfont('arial', 'b', 9);
$oPdf->cell(195, 4, "TOTAL GERAL", 1, 1, "L", 1);

$oPdf->cell(60, 4, "", 1, 0, "R", 1);
$oPdf->cell(15, 4, "Matr.Inic.", 1, 0, "C", 1);
$oPdf->cell(15, 4, "EVAD.", 1, 0, "C", 1);
$oPdf->cell(15, 4, "CANC.", 1, 0, "C", 1);
$oPdf->cell(15, 4, "TRANS.", 1, 0, "C", 1);
$oPdf->cell(15, 4, "PROGR.", 1, 0, "C", 1);
$oPdf->cell(15, 4, "ÓBITO", 1, 0, "C", 1);
$oPdf->cell(15, 4, "Matr.Efet.", 1, 0, "C", 1);
$oPdf->cell(15, 4, "Vagas.", 1, 0, "C", 1);
$oPdf->cell(15, 4, "Vag.Disp.", 1, 1, "C", 1);

$oPdf->setfillcolor($iCorEtapa);
$oPdf->cell(60, 4, "Somas:", 1, 0, "R", 1);
$oPdf->cell(15, 4, "$iSomaTotal", 1, 0, "C", 1);
$oPdf->cell(15, 4, "$iSomaEvadido", 1, 0, "C", 1);
$oPdf->cell(15, 4, "$iSomaCancelado", 1, 0, "C", 1);
$oPdf->cell(15, 4, "$iSomaTransferido", 1, 0, "C", 1);
$oPdf->cell(15, 4, "$iSomaProgredido", 1, 0, "C", 1);
$oPdf->cell(15, 4, "$iSomaFalecido", 1, 0, "C", 1);
$oPdf->cell(15, 4, "$iSomaMatriculado", 1, 0, "C", 1);
$oPdf->cell(15, 4, "$iSomaVagas", 1, 0, "C", 1);
$oPdf->cell(15, 4, "$iSomaDisponiveis", 1, 1, "C", 1);

$oPdf->cell(60, 4, "Percentuais:", 1, 0, "R", 1);
$oPdf->cell(15, 4, "", 1, 0, "C", 1);
$oPdf->cell(15, 4, number_format(($iSomaEvadido/$iSomaTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
$oPdf->cell(15, 4, number_format(($iSomaCancelado/$iSomaTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
$oPdf->cell(15, 4, number_format(($iSomaTransferido/$iSomaTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
$oPdf->cell(15, 4, number_format(($iSomaProgredido/$iSomaTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
$oPdf->cell(15, 4, number_format(($iSomaFalecido/$iSomaTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
$oPdf->cell(15, 4, number_format(($iSomaMatriculado/$iSomaTotal)*100, 2, ", ", ".")."%", 1, 0, "C", 1);
$oPdf->cell(15, 4, "", 1, 0, "C", 1);
$oPdf->cell(15, 4, number_format(($iSomaDisponiveis/$iSomaVagas)*100, 2, ", ", ".")."%", 1, 1, "C", 1);
$oPdf->Output();
?>
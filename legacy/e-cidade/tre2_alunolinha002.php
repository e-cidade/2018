<?php
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
require_once("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlib.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$sWhere  = " tre06_sequencial = {$oGet->iLinha}";

if ( !empty($oGet->iItinerario) ) {
  $sWhere .= " and tre09_tipo = {$oGet->iItinerario}";
}

$oDaoLinhaTransporte = new cl_linhatransporte();
$sSqlLinhaTransporte = $oDaoLinhaTransporte->sql_query_linha_horario_veiculo( $sWhere );
$rsLinhaTransporte   = db_query( $sSqlLinhaTransporte );


if ( !$rsLinhaTransporte ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foi possível buscar as linhas de transporte.');
}

$iLinhas = pg_num_rows($rsLinhaTransporte);

$aDadosAnalitico = array();
$aDadosSintetico = array();

$oPdf = new PDF('P');
$oPdf->Open();
$oPdf->SetAutoPageBreak(false, 20);
$oPdf->AliasNbPages();
$oPdf->SetFillColor(225);

switch ($oGet->iItinerario) {
	case 1 :
	  $head3  = "Itinerário: Ida";
	  break;
	case 2 :
	  $head3  = "Itinerário: Volta";
	  break;
	default:
	  $head3  = "Itinerário: TODOS";
	  break;
}

$head1  = "Relatório de Alunos por Linha";
$head2  = "Linha: {$oGet->sLinha}";

/**
 * $oGet->iTipo  -> 1 Analítico
 *       ....... -> 2 Sintético
 */
if ( $oGet->iTipo == 1 ) {
  
  $head4  = "Tipo: Analítico";
  
  for ( $i = 0; $i < $iLinhas; $i++ ) {
     
    $oDadosLinha = db_utils::fieldsMemory( $rsLinhaTransporte, $i );

    
    $oVeiculo            = new stdClass();
    $oVeiculo->sPlaca    = $oDadosLinha->placa;
    $oVeiculo->sNome     = $oDadosLinha->nome_veiculo;
    $oVeiculo->iVagas    = $oDadosLinha->vagas;
    $oVeiculo->iOcupadas = $oDadosLinha->vagas_ocupadas;
    $oVeiculo->aAlunos = array();
    
    /**
     * Query para buscar os alunos que estão vinculados a um veículo
     */
    $sWhere           = " tre12_linhatransportehorarioveiculo = {$oDadosLinha->vinculo_veiculo_horario}";
    $oDaoVinculoAluno = new cl_linhatransportepontoparadaaluno();
    $sSqlVinculoAluno = $oDaoVinculoAluno->sql_query(null, "aluno.ed47_i_codigo, aluno.ed47_v_nome", "aluno.ed47_v_nome", $sWhere);
    $rsVinculoAluno   = db_query($sSqlVinculoAluno);
    if ( $rsVinculoAluno && pg_num_rows( $rsVinculoAluno ) > 0 ) {
      $oVeiculo->aAlunos = db_utils::getCollectionByRecord($rsVinculoAluno);
    }
    
    /**
     * Verifica se ainda não foi criado o itinerário
     */
    if (    !isset( $aDadosAnalitico[$oDadosLinha->itinerario] )
    	   && !array_key_exists( $oDadosLinha->itinerario, $aDadosAnalitico ) ) {
      
      $oItinerario               = new stdClass();
      $oItinerario->iItinerario  = $oDadosLinha->itinerario;
      $oItinerario->sItinerario  = $oDadosLinha->itinerario == 1 ? "Ida" : "Volta";
      $oItinerario->aHora        = array();
      $aDadosAnalitico[$oDadosLinha->itinerario] = $oItinerario;
    }
    
    /**
     * Verifica se existe o array de horario e caso não haja, é criado e adicionado dados
     */
    if ( isset($aDadosAnalitico[$oDadosLinha->itinerario]->aHora[$oDadosLinha->codigo_horario])
        && array_key_exists($oDadosLinha->codigo_horario, $aDadosAnalitico[$oDadosLinha->itinerario]->aHora) ) {

      $aDadosAnalitico[$oDadosLinha->itinerario]->aHora[$oDadosLinha->codigo_horario]->iHoraVagas                                      += $oVeiculo->iVagas;
      $aDadosAnalitico[$oDadosLinha->itinerario]->aHora[$oDadosLinha->codigo_horario]->iHoraOcupadas                                   += $oVeiculo->iOcupadas;
      $aDadosAnalitico[$oDadosLinha->itinerario]->aHora[$oDadosLinha->codigo_horario]->aVeiculos[$oDadosLinha->vinculo_veiculo_horario] = $oVeiculo;
      
    } else {
      $oHora                = new stdClass();
      $oHora->sHoraSaida    = $oDadosLinha->hora_saida;
      $oHora->sHoraEntrada  = $oDadosLinha->hora_chegada;
      $oHora->iHoraVagas    = $oVeiculo->iVagas;
      $oHora->iHoraOcupadas = $oVeiculo->iOcupadas;
      $oHora->aVeiculos     = array();
      
      $oHora->aVeiculos[$oDadosLinha->vinculo_veiculo_horario] = $oVeiculo;
      $aDadosAnalitico[$oDadosLinha->itinerario]->aHora[$oDadosLinha->codigo_horario] = $oHora;
    }
  }
  
  montaRelatorioAnalitico($oPdf, $aDadosAnalitico);
  
} else {
	
  $head4  = "Tipo: Sintético";
  
  for ( $i = 0; $i < $iLinhas; $i++ ) {
  	
    $oDadosLinha = db_utils::fieldsMemory( $rsLinhaTransporte, $i );
    
    if ( array_key_exists($oDadosLinha->codigo_horario, $aDadosSintetico) ) {

      $aDadosSintetico[$oDadosLinha->itinerario][$oDadosLinha->codigo_horario]->vagas          += $oDadosLinha->vagas;
      $aDadosSintetico[$oDadosLinha->itinerario][$oDadosLinha->codigo_horario]->vagas_ocupadas += $oDadosLinha->vagas_ocupadas;
    } else {
      $aDadosSintetico[$oDadosLinha->itinerario][$oDadosLinha->codigo_horario] = $oDadosLinha;
    }
  }
 
  montaRelatorioSintetico($oPdf, $aDadosSintetico);
}

function montaCabecalhoSintetico(FPDF $oPdf, $sItinerario) {
  
  $oPdf->SetFont("arial", "B", 8);
  
  $oPdf->cell(190, 4, "Itinerário - {$sItinerario}",  0, 1);
  $oPdf->cell(115, 4, "Horários",    "TBR", 0, 'C', 1);
  $oPdf->cell( 25, 4, "Vagas",           1, 0, 'C', 1);
  $oPdf->cell( 25, 4, "Passageiros",     1, 0, 'C', 1);
  $oPdf->cell( 25, 4, "Disponíveis", "TBL", 1, 'C', 1);
}

function montaRelatorioSintetico(FPDF $oPdf, $aDadosSintetico) {
    
  $iTotalVagas         = 0;
  $iTotalVagasOcupadas = 0;
  
  $lPrimeiraPagina   = true;
  $lImprimeCabecalho = true;
  
  foreach ($aDadosSintetico as $iItinerario => $aHorarios) {

    $iTotalItinerarioVagas         = 0;
    $iTotalItinerarioVagasOcupadas = 0;
  	
    $sItinerario = $iItinerario == 1 ? "Ida" : "Volta";
    
    if ( $lPrimeiraPagina || $oPdf->getY() > $oPdf->h - 40 ) {
      
      $oPdf->addPage();
      $lPrimeiraPagina = false;
    }
    
    if ( $lImprimeCabecalho ) {
      
      montaCabecalhoSintetico($oPdf, $sItinerario);
      $lImprimeCabecalho= false;
    }
                 
    $oPdf->SetFont("arial", "", 8);
    foreach ($aHorarios as $oHorario) {
      
      $iDisponiveis = $oHorario->vagas - $oHorario->vagas_ocupadas;
      $sHorario     = "{$oHorario->hora_saida} às {$oHorario->hora_chegada}";
      
      $oPdf->cell(115, 4, $sHorario,                     "TBR", 0, 'C');
      $oPdf->cell( 25, 4, "{$oHorario->vagas}",              1, 0, 'C');
      $oPdf->cell( 25, 4, "{$oHorario->vagas_ocupadas}",     1, 0, 'C');
      $oPdf->cell( 25, 4, "{$iDisponiveis}",             "TBL", 1, 'C');

      $iTotalItinerarioVagas            += $oHorario->vagas;
      $iTotalItinerarioVagasOcupadas    += $oHorario->vagas_ocupadas;
      
      if ( $oPdf->getY() > $oPdf->h - 30) {
        
        $oPdf->addPage();
        montaCabecalhoSintetico($oPdf, $sItinerario);
      }
    }
    
    if ( $oPdf->getY() > $oPdf->h - 20) {
      
      $oPdf->addPage();
      montaCabecalhoSintetico($oPdf, $sItinerario);
    }
    
    $iTotalItinerarioDisponiveis = $iTotalItinerarioVagas - $iTotalItinerarioVagasOcupadas;
    $oPdf->SetFont("arial", "B", 8);
    $oPdf->cell(115, 4, "Total {$sItinerario}",         "TBR", 0, 'R', 1);
    $oPdf->cell( 25, 4, $iTotalItinerarioVagas,             1, 0, 'C', 1);
    $oPdf->cell( 25, 4, $iTotalItinerarioVagasOcupadas,     1, 0, 'C', 1);
    $oPdf->cell( 25, 4, $iTotalItinerarioDisponiveis,   "TBL", 1, 'C', 1);

    $iTotalVagas         += $iTotalItinerarioVagas;
    $iTotalVagasOcupadas += $iTotalItinerarioVagasOcupadas;
    $lImprimeCabecalho    = true;
    $oPdf->Ln();
  }
  
  $iTotalDisponiveis = $iTotalVagas - $iTotalVagasOcupadas;
  $oPdf->SetFont("arial", "B", 8);
  $oPdf->SetFillColor(200);
  $oPdf->cell(115, 4, "Total Geral",        "TBR", 0, 'R', 1);
  $oPdf->cell( 25, 4, $iTotalVagas,             1, 0, 'C', 1);
  $oPdf->cell( 25, 4, $iTotalVagasOcupadas,     1, 0, 'C', 1);
  $oPdf->cell( 25, 4, $iTotalDisponiveis,   "TBL", 1, 'C', 1);
}

function montaCabecalhoAnalitico(FPDF $oPdf, $sItinerario) {

  $oPdf->SetFont("arial", "B", 8);

  $oPdf->cell(190, 4, "Itinerário - {$sItinerario}",  0, 1);
  $oPdf->cell(115, 4, "Horários",    "TBR", 0, 'C', 1);
  $oPdf->cell( 25, 4, "Vagas",           1, 0, 'C', 1);
  $oPdf->cell( 25, 4, "Passageiros",     1, 0, 'C', 1);
  $oPdf->cell( 25, 4, "Disponíveis", "TBL", 1, 'C', 1);
}

function montaRelatorioAnalitico($oPdf, $aDadosAnalitico) {
  
  $lPrimeiraPagina     = true;
  $lImprimeCabecalho   = true;
  $iTotalVagas         = 0;
  $iTotalVagasOcupadas = 0;

  foreach ( $aDadosAnalitico as $oItinerario ) {
    
    $iTotalItinerarioVagas         = 0;
    $iTotalItinerarioVagasOcupadas = 0;
    $sHoraSaidaAnterior            = "";
    $sHoraEntradaAnterior          = "";
    
    if ( $lPrimeiraPagina || $oPdf->getY() > $oPdf->h - 40 ) {

      $oPdf->addPage();
      $lPrimeiraPagina = false;
    }
    
    $oPdf->SetFont("arial", "", 8);
    $oPdf->cell(190, 4, "Itinerário - {$oItinerario->sItinerario}",  0, 1);
    
    foreach ( $oItinerario->aHora as $oHora ) {

      foreach ( $oHora->aVeiculos as $oVeiculo ) {

        if ( ($oHora->sHoraSaida != $sHoraSaidaAnterior) || ($oHora->sHoraEntrada != $sHoraEntradaAnterior) ) {
          
          $sHoraSaidaAnterior   = $oHora->sHoraSaida;
          $sHoraEntradaAnterior = $oHora->sHoraEntrada;
          
          $oPdf->SetFont("arial", "B", 8);
          $oPdf->cell(115, 4, "Horários",    "TBR", 0, 'C', 1);
          $oPdf->cell( 25, 4, "Vagas",           1, 0, 'C', 1);
          $oPdf->cell( 25, 4, "Passageiros",     1, 0, 'C', 1);
          $oPdf->cell( 25, 4, "Disponíveis", "TBL", 1, 'C', 1);
          
          $sHora           = "{$oHora->sHoraSaida} às {$oHora->sHoraEntrada}";
          $iHoraDisponivel = $oHora->iHoraVagas - $oHora->iHoraOcupadas;
          
          $oPdf->SetFont("arial", "", 8);
          $oPdf->cell(115, 4, $sHora,                 "TBR", 0, 'C', 0);
          $oPdf->cell( 25, 4, "{$oHora->iHoraVagas}",      1, 0, 'C', 0);
          $oPdf->cell( 25, 4, "{$oHora->iHoraOcupadas}",   1, 0, 'C', 0);
          $oPdf->cell( 25, 4, "{$iHoraDisponivel}", "TBL", 1, 'C', 0);
        }
        $iTotalItinerarioVagas         += $oVeiculo->iVagas;
        $iTotalItinerarioVagasOcupadas += $oVeiculo->iOcupadas;
        
        $oPdf->SetFont("arial", "B", 8);
        $sNomeVeiculo = "Veiculo: {$oVeiculo->sPlaca} - {$oVeiculo->sNome}";
        
        $oPdf->cell(190, 4, $sNomeVeiculo, "TB", 1);
        
        if ( count( $oVeiculo->aAlunos ) ) {
        
          $oPdf->cell(190, 4, "ALUNOS", "TB", 1);
          $oPdf->SetFont("arial", "", 8);
          
          foreach ( $oVeiculo->aAlunos as $oAluno ) {

            if ( $oPdf->getY() > $oPdf->h - 20 ) {
              $oPdf->addPage();
            }

            $sNomeAluno = "{$oAluno->ed47_i_codigo} - {$oAluno->ed47_v_nome}";
            $oPdf->cell(190, 4, $sNomeAluno, "TB", 1);
          }
        }
      }
    }

    $iTotalItinerarioDisponiveis = $iTotalItinerarioVagas - $iTotalItinerarioVagasOcupadas;
    
    $oPdf->SetFont("arial", "B", 8);
    $oPdf->cell(115, 4, "Total {$oItinerario->sItinerario}", "TBR", 0, 'R', 1);
    $oPdf->cell( 25, 4, $iTotalItinerarioVagas,                  1, 0, 'C', 1);
    $oPdf->cell( 25, 4, $iTotalItinerarioVagasOcupadas,          1, 0, 'C', 1);
    $oPdf->cell( 25, 4, $iTotalItinerarioDisponiveis,        "TBL", 1, 'C', 1);
    
    $iTotalVagas         += $iTotalItinerarioVagas;
    $iTotalVagasOcupadas += $iTotalItinerarioVagasOcupadas;
    $oPdf->Ln();
    $oPdf->Ln();
  }
  
  $iTotalDisponiveis = $iTotalVagas - $iTotalVagasOcupadas;
  $oPdf->SetFont("arial", "B", 8);
  $oPdf->SetFillColor(200);
  $oPdf->cell(115, 4, "Total Geral",        "TBR", 0, 'R', 1);
  $oPdf->cell( 25, 4, $iTotalVagas,             1, 0, 'C', 1);
  $oPdf->cell( 25, 4, $iTotalVagasOcupadas,     1, 0, 'C', 1);
  $oPdf->cell( 25, 4, $iTotalDisponiveis,   "TBL", 1, 'C', 1);
}

$oPdf->output();
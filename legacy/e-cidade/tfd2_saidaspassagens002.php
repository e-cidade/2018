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
define( 'MENSAGENS_TFD2_SAIDAPASSAGENS', 'saude.tfd.tfd2_saidaspassagens002.' );

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("fpdf151/pdf.php"));

$oGet = db_utils::postMemory( $_GET );

try {

  $aDadosRelatorio = buscaDados( $oGet );

  if( count( $aDadosRelatorio ) == 0 ) {
    throw new BusinessException( _M( MENSAGENS_TFD2_SAIDAPASSAGENS . 'nenhum_pedido_encontrado' ) );
  }

  /**
   * Objeto com atributos padrões para o relatório
   */
  $oConfiguracoes                 = new stdClass();
  $oConfiguracoes->iAltura        = 4;
  $oConfiguracoes->iLarguraMaxima = 192;
  $oConfiguracoes->iColunaCGS     = 15;
  $oConfiguracoes->iColunaNome    = 110;
  $oConfiguracoes->iColunaCNS     = 20;
  $oConfiguracoes->iColunaSaida   = 27;
  $oConfiguracoes->iColunaValor   = 20;
  $oConfiguracoes->sPrestadora    = '';
  $oConfiguracoes->sDestino       = '';

  $oPdf  = new PDF();
  $head1 = "VIAGENS - TRANSPORTE COLETIVO";
  $head3 = "Período: {$oGet->dataInicial} até {$oGet->dataFinal}";

  if( !empty( $oGet->iDestino ) ) {
    $head4 = "Destino: {$oGet->sDestino}";
  }

  $oPdf->Open();
  $oPdf->AddPage();
  $oPdf->SetFillColor( 225 );
  $oPdf->SetAutoPageBreak( false, 10 );

  foreach( $aDadosRelatorio as $oDadosRelatorio ) {

    $oConfiguracoes->sPrestadora = $oDadosRelatorio->sPrestadora;
    $oConfiguracoes->sDestino    = $oDadosRelatorio->sDestino;

    imprimePrestadoraDestino( $oPdf, $oConfiguracoes );
    imprimeCabecalho( $oPdf, $oConfiguracoes );

    foreach( $oDadosRelatorio->aPacientes as $oDadosPaciente ) {

      imprimeDadosPacienteAcompanhantes( $oPdf, $oConfiguracoes, $oDadosPaciente );

      foreach( $oDadosPaciente->aAcompanhantes as $oDadosAcompanhante ) {
        imprimeDadosPacienteAcompanhantes( $oPdf, $oConfiguracoes, $oDadosAcompanhante, true );
      }
    }

    $oPdf->Ln();
  }

  $oPdf->Output();
} catch ( Exception $oErro ) {
  db_redireciona( "db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
}

/**
 * Imprime a linha com a informação da Prestadora e o Destino da mesma
 *
 * @param PDF $oPdf
 * @param     $oConfiguracoes
 */
function imprimePrestadoraDestino( PDF $oPdf, $oConfiguracoes ) {

  $oPdf->SetFont( 'arial', 'b', 7 );
  $oPdf->Cell( 15, $oConfiguracoes->iAltura, "Prestadora:", 0, 0, 'L' );

  $oPdf->SetFont( 'arial', '', 6 );
  $oPdf->Cell( 85, $oConfiguracoes->iAltura, $oConfiguracoes->sPrestadora, 0, 0, 'L' );

  $oPdf->SetFont( 'arial', 'b', 7 );
  $oPdf->Cell( 11, $oConfiguracoes->iAltura, "Destino:", 0, 0, 'L' );

  $oPdf->SetFont( 'arial', '', 6 );
  $oPdf->Cell( 81, $oConfiguracoes->iAltura, $oConfiguracoes->sDestino, 0, 1, 'L' );
}

/**
 * Imprime o cabeçalho das informações dos pacientes/acompanhantes
 *
 * @param PDF $oPdf
 * @param     $oConfiguracoes
 */
function imprimeCabecalho( PDF $oPdf, $oConfiguracoes ) {

  $oPdf->SetFont( 'arial', 'b', 7 );

  $oPdf->Cell( $oConfiguracoes->iColunaCGS,   $oConfiguracoes->iAltura, "CGS",               1, 0, 'C', 1 );
  $oPdf->Cell( $oConfiguracoes->iColunaNome,  $oConfiguracoes->iAltura, "NOME",              1, 0, 'C', 1 );
  $oPdf->Cell( $oConfiguracoes->iColunaCNS,   $oConfiguracoes->iAltura, "CNS",               1, 0, 'C', 1 );
  $oPdf->Cell( $oConfiguracoes->iColunaSaida, $oConfiguracoes->iAltura, "DATA / HORA SAÍDA", 1, 0, 'C', 1 );
  $oPdf->Cell( $oConfiguracoes->iColunaValor, $oConfiguracoes->iAltura, "VALOR",             1, 1, 'C', 1 );
}

/**
 * Imprime os dados dos pacientes/acompanhantes
 *
 * @param PDF        $oPdf
 * @param            $oConfiguracoes
 * @param            $oDadosPaciente
 * @param bool|false $lAcompanhante
 */
function imprimeDadosPacienteAcompanhantes( PDF $oPdf, $oConfiguracoes, $oDadosPaciente, $lAcompanhante = false ) {

  if( $oPdf->GetY() > $oPdf->h - 15 ) {

    $oPdf->AddPage();

    imprimePrestadoraDestino( $oPdf, $oConfiguracoes );
    imprimeCabecalho( $oPdf, $oConfiguracoes );
  }

  $oPdf->SetFont( 'arial', '', 6 );

  $sNome          = $lAcompanhante ? "+ AC: {$oDadosPaciente->sNome}" : $oDadosPaciente->sNome;
  $sDataHoraSaida = "{$oDadosPaciente->sDataSaida} - {$oDadosPaciente->sHoraSaida}";
  $sValorUnitario = "R$ {$oDadosPaciente->sValorUnitario}";

  $oPdf->Cell( $oConfiguracoes->iColunaCGS,   $oConfiguracoes->iAltura, $oDadosPaciente->iCGS, 1, 0, 'R' );
  $oPdf->Cell( $oConfiguracoes->iColunaNome,  $oConfiguracoes->iAltura, $sNome,                1, 0, 'L' );
  $oPdf->Cell( $oConfiguracoes->iColunaCNS,   $oConfiguracoes->iAltura, $oDadosPaciente->iCNS, 1, 0, 'R' );
  $oPdf->Cell( $oConfiguracoes->iColunaSaida, $oConfiguracoes->iAltura, $sDataHoraSaida,       1, 0, 'C' );
  $oPdf->Cell( $oConfiguracoes->iColunaValor, $oConfiguracoes->iAltura, $sValorUnitario,       1, 1, 'R' );
}

/**
 * Responsável por buscar os pedidos com saída, e organizar os dados para impressão no relatório
 *
 * @param $oGet
 * @return array
 * @throws DBException
 * @throws ParameterException
 */
function buscaDados( $oGet ) {

  $aDadosRelatorio                    = array();
  $oConfiguracoesPedidos              = new stdClass();
  $oConfiguracoesPedidos->oDataInicio = new DBDate( $oGet->dataInicial );
  $oConfiguracoesPedidos->oDataFim    = new DBDate( $oGet->dataFinal );
  $oConfiguracoesPedidos->iDestino    = $oGet->iDestino;

  $aPedidosTFD = PedidoTFDRepository::buscaPedidos( $oConfiguracoesPedidos );

  /**
   * Percorre os pedidos com saída, existentes para o período selecionado
   */
  foreach( $aPedidosTFD as $oPedidoTFD ) {

    /**
     * Preenche os dados do paciente do pedido
     */
    $oDadosPaciente                 = new stdClass();
    $oDadosPaciente->iCGS           = $oPedidoTFD->getPaciente()->getCodigo();
    $oDadosPaciente->sNome          = $oPedidoTFD->getPaciente()->getNome();
    $oDadosPaciente->iCNS           = $oPedidoTFD->getPaciente()->getCartaoSusAtivo();
    $oDadosPaciente->sDataSaida     = '';
    $oDadosPaciente->sHoraSaida     = '';
    $oDadosPaciente->sValorUnitario = '';

    $aDadosSaida = $oPedidoTFD->getDadosSaidaTransporteColetivo();

    if( is_null( $aDadosSaida ) ) {
      continue;
    }

    if(!isset($aDadosSaida[$oPedidoTFD->getPaciente()->getCodigo()])) {
      continue;
    }

    $oDadosSaidaPaciente = $aDadosSaida[ $oPedidoTFD->getPaciente()->getCodigo() ];
    $sValorUnitario      = db_formatar( $oDadosSaidaPaciente->sValorUnitario, 'f', '0', 2, 'e' );

    $oDadosPaciente->sDataSaida     = $oDadosSaidaPaciente->oDataSaida->getDate(DBDate::DATA_PTBR);
    $oDadosPaciente->sHoraSaida     = $oDadosSaidaPaciente->sHoraSaida;
    $oDadosPaciente->sValorUnitario = $sValorUnitario;
    $oDadosPaciente->aAcompanhantes = array();

    /**
     * Percorre os acompanhantes, preenchendo os dados da mesma forma que o paciente
     */
    foreach( $oPedidoTFD->getAcompanhantes() as $oCGSAcompanhante ) {

      if(!isset($aDadosSaida[$oCGSAcompanhante->getCodigo()])) {
        continue;
      }

      $oDadosAcompanhante        = new stdClass();
      $oDadosAcompanhante->iCGS  = $oCGSAcompanhante->getCodigo();
      $oDadosAcompanhante->sNome = $oCGSAcompanhante->getNome();
      $oDadosAcompanhante->iCNS  = $oCGSAcompanhante->getCartaoSusAtivo();

      $oDadosSaidaAcompanhante = $aDadosSaida[ $oCGSAcompanhante->getCodigo() ];
      $sValorUnitario          = db_formatar( $oDadosSaidaAcompanhante->sValorUnitario, 'f', '0', 2, 'e' );

      $oDadosAcompanhante->sDataSaida = '';

      if($oDadosSaidaAcompanhante->oDataSaida instanceof DBDate) {
        $oDadosAcompanhante->sDataSaida = $oDadosSaidaAcompanhante->oDataSaida->getDate(DBDate::DATA_PTBR);
      }

      $oDadosAcompanhante->sHoraSaida     = $oDadosSaidaAcompanhante->sHoraSaida;
      $oDadosAcompanhante->sValorUnitario = $sValorUnitario;
      $oDadosPaciente->aAcompanhantes[]   = $oDadosAcompanhante;
    }

    /**
     * Guarda a prestadora do pedido, agrupando os pacientes e seus acompanhantes por prestadora/destino
     */
    $oPrestadora = $oPedidoTFD->getPrestadora();

    if( !array_key_exists($oPrestadora->getCodigo(), $aDadosRelatorio) ) {

      $oDadosPrestadora               = new stdClass();
      $oDadosPrestadora->sPrestadora  = $oPrestadora->getCgm()->getNome();
      $oDadosPrestadora->sDestino     = $oPrestadora->getDestino()->sDescricao;
      $oDadosPrestadora->aPacientes[] = $oDadosPaciente;

      $aDadosRelatorio[ $oPrestadora->getCodigo() ] = $oDadosPrestadora;
      $aControlePrestadora[]                        = $oPrestadora->getCodigo();
    } else {
      $aDadosRelatorio[ $oPrestadora->getCodigo() ]->aPacientes[] = $oDadosPaciente;
    }
  }

  return $aDadosRelatorio;
}
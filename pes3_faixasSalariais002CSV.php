<?php
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
 

$fArquivo = fopen($sArquivo, "w");

$aHeaderCSV      = array();
$aHeaderCSV[]    = $sAgrupador;
$lEscritaArquivo = fputcsv($fArquivo, $aHeaderCSV, ";");

if ( !$lEscritaArquivo ) {

	throw new Exception('Erro ao Escrever arquivo CSV.');
	exit;
}

foreach ( $aDadosRelatorio as $oDadosRelatorio ) {

  $aDadosCSV   = array();
	$aDadosCSV[] = $oDadosRelatorio->iCodigoAgrupador . ' - ' . $oDadosRelatorio->sDescricaoAgrupador;

	$aDadosTotalGeral   = array();
	$aDadosTotalGeral[] = "Total {$oDadosRelatorio->sDescricaoAgrupador}";
	$aDadosTotalGeral[] = "";
	$aDadosTotalGeral[] = number_format($oDadosRelatorio->nTotal, 2, ',', '');
	$aDadosTotalGeral[] = "100%";
	$aDadosTotalGeral[] = $oDadosRelatorio->iTotalFuncionarios;

	$lEscritaArquivo = fputcsv($fArquivo, $aDadosCSV, ";");

  for ( $iFaixa = 0; $iFaixa < $iQuantidadeFaixas; $iFaixa++ ) {
  
  	$iCodigoFaixa = $iFaixa + 1;
  
  	$aHeaderFaixaCSV   = array();
  	$aHeaderFaixaCSV[] = 'Faixa';
  	$aHeaderFaixaCSV[] = 'Valor';
  	$aHeaderFaixaCSV[] = 'Percentual' ;
  	$aHeaderFaixaCSV[] = 'Funcionarios';
  	
  	$lEscritaArquivo = fputcsv($fArquivo, $aHeaderFaixaCSV, ";");
  	
  	$aDadosRodapeCSV   = array();
  	$aDadosRodapeCSV[] = 'Faixa ' . $iCodigoFaixa;
  	$aDadosRodapeCSV[] = $aFaixas[$iFaixa]['sDescricaoFaixa'];
  	$aDadosRodapeCSV[] = $oDadosRelatorio->aFaixas[$iFaixa]['nValorFaixa'];
  	$aDadosRodapeCSV[] = $oDadosRelatorio->aFaixas[$iFaixa]['nPercentual'];
  	$aDadosRodapeCSV[] = $oDadosRelatorio->aFaixas[$iFaixa]['iTotalFuncionarios'];
  	
  	$lEscritaArquivo = fputcsv($fArquivo, $aDadosRodapeCSV, ";");
  	
  	/**
  	 * Verificamos se opcao de listar servidores esta como 'Sim'
  	 */
  	if ($iOpcaoServidor == 2) {
  	
  	  /**
  	   * Imprimimos os servidores por faixa
  	   */
  	  if ($oDadosRelatorio->aFaixas[$iFaixa]['iTotalFuncionarios'] > 0) {
  	
  	    $aHeaderServidoresCSV   = array();
  	    $aHeaderServidoresCSV[] = 'Matrícula';
  	    $aHeaderServidoresCSV[] = 'Nome do Servidor';
  	    $aHeaderServidoresCSV[] = 'Valor';
  	    
  	    $lEscritaArquivo       = fputcsv($fArquivo, $aHeaderServidoresCSV, ";");
  	
  	    foreach($oDadosRelatorio->aFaixas[$iFaixa]['aServidores'] as $oDadosServidor) {
  	
  	      $oServidor          = new Servidor($oDadosServidor->iMatricula);
  	      $aDadosServidores   = array();
  	      $aDadosServidores[] = $oServidor->getMatricula();
  	      $aDadosServidores[] = $oServidor->getCgm()->getNome();
  	      $aDadosServidores[] = db_formatar($oDadosServidor->nValor, 'f');
  	      unset($oServidor);
  	      $lEscritaArquivo = fputcsv($fArquivo, $aDadosServidores, ";");
  	    }
  	  }
  	}
  }
  
  $lEscritaArquivo = fputcsv($fArquivo, $aDadosTotalGeral, ";");
}

fclose($fArquivo);
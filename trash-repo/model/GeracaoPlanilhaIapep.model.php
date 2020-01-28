<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ('libs/db_utils.php');
require_once ('libs/db_stdlib.php');
require_once ('libs/exceptions/DBException.php');

class GeracaoPlanilhaIapep {
  
  const MENSAGENS = 'recursoshumanos.pessoal.pes4_geracaoplanilhaiapep.';
  
  function __construct() {

  	$this->iInstit = db_getsession("DB_instit");
  }
  
  /**
   * Monta a string CSV inteira pronta para ser salva no arquivo
   * @param array RhPessoalMov[]  - Array de objetos extraidos da query
   * @param string $sDataAdmissao - Periodo da data de admiss�o
   * @param string $sCompetencia  - Competencia da folha
   * @return string - Conteudo do arquivo CSV
   */
  private function montaEstruturaCSV( $aDados, $sDataAdmissao, $sCompetencia, $lIsSalario13 = false ) {
  
  	$sLinha         = '';
  	$sSeparador     = ';';
  	$sQuebraDeLinha = "\n";
  
  	$sAdmissao      = 'ap�s 20/12/2012.';
  	if( $sDataAdmissao == '2012-12-20' ){
  		$sAdmissao    = 'at� 20/12/2012.';
  	}
  
  	$sCabecalho     = '"Ano / M�s Compet�ncia - ' . $sCompetencia . '"';
  	$sCabecalho    .= $sQuebraDeLinha;
  	
  	$sLabelBaseCalculo     = '"Base de C�lculo para a Previd�ncia"';
  	$sLabelContribServidor = '"Contribui��o Previdenci�ria do Servidor"';
  	$sLabelContribPatronal = '"Contribui��o Patronal do Servidor"';
  
  	if ( $lIsSalario13 ) {
  	
  	  $sLabelBaseCalculo     = '"Base de C�lculo da Contribui��o Previd�nci�ria 13� Sal�rio"';
  		$sLabelContribServidor = '"Contribui��o Previdenci�ria do Servidor 13� Sal�rio"';
  		$sLabelContribPatronal = '"Contribui��o Previd�nci�ria Patronal 13� Sal�rio"';
  	}
  	
  	$sCabecalho    .= '"Matr�cula"';
  	$sCabecalho    .= $sSeparador . '"Nome Completo"';
  	$sCabecalho    .= $sSeparador . '"CPF"';
  	$sCabecalho    .= $sSeparador . '"Data de Admiss�o"';
  	$sCabecalho    .= $sSeparador . '"Remunera��o Bruta"';
  	$sCabecalho    .= $sSeparador . $sLabelBaseCalculo;
  	$sCabecalho    .= $sSeparador . $sLabelContribServidor;
  	$sCabecalho    .= $sSeparador . $sLabelContribPatronal;
  	$sCabecalho    .= $sQuebraDeLinha;
  
  	foreach ( $aDados as $aDado ){
  
  		$sLinha .= $aDado->rh02_regist;
  		$sLinha .= $sSeparador . '"'. $aDado->z01_nome .'"';
  		$sLinha .= $sSeparador . db_formatar( $aDado->z01_cgccpf, 'cpf' );
  		$sLinha .= $sSeparador . db_formatar( $aDado->rh01_admiss, 'd' );
  		$sLinha .= $sSeparador . trim ( db_formatar( $aDado->remuneracao_bruta, 									 'f' ) );
  		$sLinha .= $sSeparador . trim ( db_formatar( $aDado->contribuicao_previdenciaria, 			   'f' ) );
  		$sLinha .= $sSeparador . trim ( db_formatar( $aDado->contribuicao_previdenciaria_servidor, 'f' ) );
  		$sLinha .= $sSeparador . trim ( db_formatar( $aDado->contribuicao_patronal_servidor,			 'f' ) );
  		$sLinha .= $sQuebraDeLinha;
  	}
  
  	return $sCabecalho . $sLinha;
  }

  /**
   * M�todo publico para abstrair gera��o do arquivo CSV
   * @param object $oParametros - Parametros definidos pelo usu�rio
   * @param string $sCompetencia - Competencia da folha
   * @param string $sTipoPlanilha - Tipo de Planiha (salario ou 13 salario)
   * @param boolean $lIsPlanilha13 - Indicador de planilha de 13 salario
   * @throws DBException
   * @throws BusinessException
   * @return string - Nome do arquivo
   */
  public function geraPlanilha ( $oParametros, $sCompetencia, $sTipoPlanilha, $lIsPlanilha13 ){
  
  	$oDaoRhPessoalMov   = new cl_rhpessoalmov();

    $aTipoVinculos = explode(",", $oParametros->sTipoVinculo);
    $aArquivos     = array();

    foreach ($aTipoVinculos as $sTipoVinculo) {

      $sSqlPlanilhas = $oDaoRhPessoalMov->sql_geracaoPlanilhasIAPEP( $oParametros->sDataAdmissao,
                                                                     $oParametros->iAno,
                                                                     $oParametros->iMes,
                                                                     $this->iInstit,
                                                                     $sTipoVinculo,
                                                                     $lIsPlanilha13
                                                                  );
  
      $rsPlanilhas   = db_query( $sSqlPlanilhas );
  
      if ( !$rsPlanilhas ) {
        throw new DBException( _M( self::MENSAGENS . "erro_busca_dados_planilha" ) );
      }
  
      if ( pg_num_rows( $rsPlanilhas ) == 0) {
        continue;
      }
  
      $aDadosPlanilha = db_utils::getCollectionByRecord( $rsPlanilhas, false, false, false );
    
      $sConteudoCSV   = $this->montaEstruturaCSV ( $aDadosPlanilha, $oParametros->sDataAdmissao, $sCompetencia, $lIsPlanilha13 );
  
      if( empty ( $sConteudoCSV ) ){
        throw new BusinessException( _M( self::MENSAGENS . "erro_gerar_csv" ) );
      }
  
      $aArquivos[] = $this->criaArquivoCSV ( $sConteudoCSV, $sTipoPlanilha, $oParametros->iAno, $oParametros->iMes, $sTipoVinculo );
  
    }

    return $aArquivos;
  }
  
  /**
   * M�todo privado para criar o arquivo CSV local no disco
   * @param string $sConteudoCSV - String com conte�do CSV do arquivo
   * @param string $sTipoPlanilha - Tipo de Planiha (salario ou 13 salario)
   * @param integer $iAno - Ano compet�ncia
   * @param integer $iMes - M�s compet�ncia
   * @throws BusinessException
   * @return string - Nome do arquivo
   */
  private function criaArquivoCSV( $sConteudoCSV, $sTipoPlanilha, $iAno, $iMes, $sTipoVinculo ){
  
  	$sNomeArquivo   = "planilha_{$sTipoPlanilha}_{$sTipoVinculo}_{$iAno}-{$iMes}.csv";
  
  	$fArquivo = fopen( "tmp/$sNomeArquivo", "w+" );
  	fwrite( $fArquivo, $sConteudoCSV );
  	fclose( $fArquivo );
  
  	if ( !file_exists ( "tmp/$sNomeArquivo" ) ) {
  		throw new BusinessException( _M( self::MENSAGENS . "erro_criar_csv" ) );
  	}
  
  	return $sNomeArquivo;
  }

  /**
   * M�todo publico para abstrair gera��o do arquivo PDF
   * @param object $oParametros - Parametros definidos pelo usu�rio
   * @param string $sCompetencia - Competencia da folha
   * @throws BusinessException
   * @return string - Nome do arquivo
   */
  public function geraPDFTotalizador ( $oParametros, $sCompetencia ){
  
  	$aConteudoPDF   = $this->getDadosTotalizador ( $oParametros );
  
  	if( empty ( $aConteudoPDF ) ){
  		throw new BusinessException( _M( self::MENSAGENS . "erro_gerar_pdf" ) );
  	}
  
  	if ( !$this->montaEstruturaPDF ( $aConteudoPDF, $sCompetencia, $oParametros->sDataAdmissao, true ) ){
  		throw new BusinessException( _M( self::MENSAGENS . "erro_criar_pdf" ) );
  	}
  
  	return 'totalizadores.pdf';
  }
  
  /**
   * Busca dados para gerar o arquivo de totalizadores das planilhas
   * @param object $oParametros - Parametros definidos pelo usu�rio
   * @throws DBException
   * @throws BusinessException
   * @return array RhPessoalMov[]  - Array de objetos extraidos da query
   */
  private function getDadosTotalizador ( $oParametros ){
  
  	$oDaoRhPessoalMov   			  = new cl_rhpessoalmov();

    $aDadosRetorno = array(
      'A' => array(),
      'I' => array(),
      'P' => array()
    );

    $aTipoVinculos = explode(",", $oParametros->sTipoVinculo);

    foreach ($aTipoVinculos as $sTipoVinculo) {
      
    	$sSqlTotalizadoresPlanilhas = $oDaoRhPessoalMov->sql_geracaoPDFIAPEP ( $oParametros->sDataAdmissao, $oParametros->iAno,	$oParametros->iMes, $this->iInstit, $sTipoVinculo );

    	$rsTotalizadoresPlanilhas   = db_query( $sSqlTotalizadoresPlanilhas );

    	if ( !$rsTotalizadoresPlanilhas ) {
    		throw new DBException( _M( self::MENSAGENS . "erro_busca_dados_total_planilha" ) );
    	}
    
    	if ( pg_num_rows( $rsTotalizadoresPlanilhas ) == 0 ) {
        continue;
    	}
    
    	$aDadosTotalizadoresPlanilha = db_utils::getCollectionByRecord( $rsTotalizadoresPlanilhas, false, false, false );

      /**
       * Sempre retornar� um registro
       */
      $aDadosRetorno[$sTipoVinculo] = $aDadosTotalizadoresPlanilha[0];

    }

    if (empty($aDadosRetorno)) {
      throw new BusinessException( _M( self::MENSAGENS . "nenhum_registro_total_planilha_encontrado" ) );
    }

  	return $aDadosRetorno;
  }
  
  /**
   * Monta e grava PDF com totalizadores
   * @param array RhPessoalMov[]    - Array de objetos extraidos da query
   * @param string $sCompetencia    - Competencia da folha
   * @param string $sDataAdmissao   - Periodo da data de admiss�o
   * @param boolean $lTotalizadores - Informa se � pdf de totalizadores ou n�o
   * @return boolean - True se conseguiu gerar o arquivo
   */
  private function montaEstruturaPDF ( $aDados, $sCompetencia, $sDataAdmissao, $lTotalizadores = false ){

    $iTotalizadorFolhaBruta                              = 0;
    $iTotalizadorContribuicaoPrevidenciariaServidor      = 0;
    $iTotalizadorContribuicaoPatronalServidor            = 0;
    $iTotalizadorContribuicaoPrevicendiaPatronalServidor = 0;
  
  	foreach ( $aDados as $sTipoVinculo => $aDado ){

  		${"iQuantidadeServidores".$sTipoVinculo}                       = isset($aDado->quantidadeservidores) ? $aDado->quantidadeservidores : 0;
  
      ${"iTotalFolhaBruta".$sTipoVinculo}                            = isset($aDado->totalfolhabruta) ?  $aDado->totalfolhabruta : 0;
      ${"iTotalContribuicaoPrevidenciaria".$sTipoVinculo}            = isset($aDado->totalcontribuicaoprevidenciaria) ? $aDado->totalcontribuicaoprevidenciaria : 0;
      ${"iTotalContribuicaoPrevidenciariaServidor".$sTipoVinculo}    = isset($aDado->totalcontribuicaoprevidenciariaservidor) ? $aDado->totalcontribuicaoprevidenciariaservidor   : 0;
      ${"iTotalContribuicaoPatronalServidor".$sTipoVinculo}          = isset($aDado->totalcontribuicaopatronalservidor) ? $aDado->totalcontribuicaopatronalservidor         : 0;
 
      ${"iTotalFolhaBruta13".$sTipoVinculo}                          = isset($aDado->totalfolhabruta13) ? $aDado->totalfolhabruta13                         : 0;
      ${"iTotalContribuicaoPrevidenciaria13".$sTipoVinculo}          = isset($aDado->totalcontribuicaoprevidenciaria13) ? $aDado->totalcontribuicaoprevidenciaria13         : 0;
      ${"iTotalContribuicaoPrevidenciariaServidor13".$sTipoVinculo}  = isset($aDado->totalcontribuicaoprevidenciariaservidor13) ? $aDado->totalcontribuicaoprevidenciariaservidor13 : 0;
      ${"iTotalContribuicaoPatronalServidor13".$sTipoVinculo}        = isset($aDado->totalcontribuicaopatronalservidor13) ? $aDado->totalcontribuicaopatronalservidor13       : 0;
                                                                                                                                            
      $iTotalizadorFolhaBruta                                       += isset($aDado->totalizadorfolhabruta) ? $aDado->totalizadorfolhabruta : 0                             ;
      $iTotalizadorContribuicaoPrevidenciariaServidor               += isset($aDado->totalizadorcontribuicaoprevidenciariaservidor) ? $aDado->totalizadorcontribuicaoprevidenciariaservidor : 0     ;
      $iTotalizadorContribuicaoPatronalServidor                     += isset($aDado->totalizadorcontribuicaopatronalservidor) ? $aDado->totalizadorcontribuicaopatronalservidor : 0           ;
      $iTotalizadorContribuicaoPrevicendiaPatronalServidor          += isset($aDado->totalizadorcontribuicaoprevicendiapatronalservidor) ? $aDado->totalizadorcontribuicaoprevicendiapatronalservidor : 0;
  	}
  	
  	if ( $iQuantidadeServidoresA == 0 && 
         $iQuantidadeServidoresI == 0 && 
         $iQuantidadeServidoresP == 0   ) {
      
  		throw new BusinessException( _M( self::MENSAGENS . "nenhum_registro_total_planilha_encontrado" ) );
  	}
  	
    $sCabecalho     = "PLANO FINANCEIRO";
  	$sAdmissao      = 'ap�s 20/12/2012.';
  	if( $sDataAdmissao == '2012-12-20' ){
  		$sAdmissao    = 'at� 20/12/2012.';
  	}

    if ($lTotalizadores && $sDataAdmissao != '2012-12-20') {
      $sCabecalho = "PLANO PREVIDENCI�RIO";
    }
  
  	$oPdf = new PDF();
  	$oPdf->Open();
  	$oPdf->AliasNbPages();
  	$oPdf->setfillcolor(235);
  	$iAltura 		   = 4;
  	$iTamanhoFonte = 9;
  
  	$oPdf->addpage("P");
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  
  	//DADOS A SEREM POPULADOS
  	$dDataHoje        = date('d/m/Y');
    	  
  	/**
  	 * Cabe�alho Geral
  	 */
  	$oPdf->cell(192,$iAltura, "APURA��O MENSAL PREVIDENCI�RIA - AMP" ,1,0,"C",0);
  	$oPdf->Ln();
  	$oPdf->cell(192,$iAltura, $sCabecalho ,1,0,"C",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(60,$iAltura, "ENTE/PODER:" ,1,0,"C",0);
  	$oPdf->cell(132,$iAltura, "(EXECUTIVO, TJ, ALEPI, MPE, TJ)" ,1,0,"C",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(60,$iAltura, "ANO/M�S DE COMPET�NCIA:" ,1,0,"C",0);
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  	$oPdf->cell(44,$iAltura, $sCompetencia ,1,0,"C",0);
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(44,$iAltura, 'DATA ATUAL' ,1,0,"C",0);
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  	$oPdf->cell(44,$iAltura, $dDataHoje ,1,0,"C",0);
  	$oPdf->Ln();
  
  	/**
  	 * ATIVOS
  	*/
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(192,$iAltura, "FOLHA DE PAGAMENTO DOS ATIVOS ADMITIDOS {$sAdmissao}" ,1,0,"C",0);
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "N". CHR(176) ." DE SERVIDORES:" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, $iQuantidadeServidoresA ,1,0,"C",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(162, $iAltura, "" ,1,0,"C",0);
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(30,  $iAltura, "R$" ,1,0,"C",0);
  	$oPdf->Ln();
  
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  
  	/**
  	 *  TOTAL DA FOLHA SALARIO
  	*/
  	$oPdf->cell(10, $iAltura, "A" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalFolhaBrutaA, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "B" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "BASE DE C�LCULO DA CONTRIBUI��O PREVIDENCI�RIA" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaA, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "C" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA DO SERVIDOR" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaServidorA, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "D" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA PATRONAL" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPatronalServidorA, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	/**
  	 *  TOTAL DA FOLHA SALARIO 13�
  	*/
  	$oPdf->cell(10, $iAltura, "E" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA 13" . CHR(176) . ' SAL�RIO' ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalFolhaBruta13A, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "F" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "BASE DE C�LCULO DA CONTRIBUI��O PREVIDENCI�RIA DO 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciaria13A, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "G" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA DO SERVIDOR 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaServidor13A, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "H" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA PATRONAL 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPatronalServidor13A, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(192, $iAltura, "" ,0,0,"C",0);
  	$oPdf->Ln();
  
  	/**
  	 * INATIVOS
  	*/
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(192,$iAltura, "FOLHA DE PAGAMENTO DOS INATIVOS ADMITIDOS {$sAdmissao}" ,1,0,"C",0);
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "N". CHR(176) ." DE SERVIDORES:" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, $iQuantidadeServidoresI,1,0,"C",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(162, $iAltura, "" ,1,0,"C",0);
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(30,  $iAltura, "R$" ,1,0,"C",0);
  	$oPdf->Ln();
  
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  
  	/**
  	 *  TOTAL DA FOLHA SALARIO
  	*/
  	$oPdf->cell(10, $iAltura, "I" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalFolhaBrutaI, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "J" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "BASE DE C�LCULO DA CONTRIBUI��O PREVIDENCI�RIA" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaI, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "K" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA DO SERVIDOR" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaServidorI, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "L" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA PATRONAL" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPatronalServidorI, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	/**
  	 *  TOTAL DA FOLHA SALARIO 13�
  	*/
  	$oPdf->cell(10, $iAltura, "M" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA 13" . CHR(176) . ' SAL�RIO' ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalFolhaBruta13I, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "N" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "BASE DE C�LCULO DA CONTRIBUI��O PREVIDENCI�RIA DO 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciaria13I, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "O" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA DO SERVIDOR 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaServidor13I, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "P" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA PATRONAL 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPatronalServidor13I, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(192, $iAltura, "" ,0,0,"C",0);
  	$oPdf->Ln();
  
  	/**
  	 * PENSIONISTAS
  	*/
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(192,$iAltura, "FOLHA DE PAGAMENTO DOS PENSIONISTAS ATIVOS ADMITIDOS {$sAdmissao}" ,1,0,"C",0);
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "N". CHR(176) ." DE SERVIDORES:" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, $iQuantidadeServidoresP ,1,0,"C",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(162, $iAltura, "" ,1,0,"C",0);
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(30,  $iAltura, "R$" ,1,0,"C",0);
  	$oPdf->Ln();
  
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  
  	/**
  	 *  TOTAL DA FOLHA SALARIO
  	*/
  	$oPdf->cell(10, $iAltura, "Q" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalFolhaBrutaP, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "R" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "BASE DE C�LCULO DA CONTRIBUI��O PREVIDENCI�RIA" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaP, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "S" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA DO SERVIDOR" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaServidorP, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "T" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA PATRONAL" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPatronalServidorP, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	/**
  	 *  TOTAL DA FOLHA SALARIO 13�
  	*/
  	$oPdf->cell(10, $iAltura, "U" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA 13" . CHR(176) . ' SAL�RIO' ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalFolhaBruta13P, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "V" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "BASE DE C�LCULO DA CONTRIBUI��O PREVIDENCI�RIA DO 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciaria13P, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "W" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA DO SERVIDOR 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPrevidenciariaServidor13P, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "X" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "CONTRIBUI��O PREVIDENCI�RIA PATRONAL 13" . CHR(176) ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalContribuicaoPatronalServidor13P, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(192, $iAltura, "" ,0,0,"C",0);
  	$oPdf->Ln();
  
  	/**
  	 *  TOTALIZADORES ATIVOS
  	*/
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(162,$iAltura, "TOTALIZADORES" ,1,0,"C",0);
  	$oPdf->cell(30,$iAltura, "R$" ,1,0,"C",0);
  	$oPdf->setfont('arial','',$iTamanhoFonte);
  	$oPdf->Ln();
  
  	$oPdf->setfont('arial','b',$iTamanhoFonte);
  	$oPdf->cell(10, $iAltura, "AA" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA (A+E+I+M+Q+U)" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalizadorFolhaBruta,"f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "AB" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA CONTRIBUI��O PREVIDENCI�RIA DO SERVIDOR (C+G+K+O+S+W)" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalizadorContribuicaoPrevidenciariaServidor,"f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "AC" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA CONTRIBUI��O PREVIDENCI�RIA PATRONAL (D+H+L+P+T+X)" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalizadorContribuicaoPatronalServidor,"f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
    $iTotalizadorContribuicaoPrevicendiaPatronalServidor = $iTotalizadorContribuicaoPrevidenciariaServidor + $iTotalizadorContribuicaoPatronalServidor;

  	$oPdf->cell(10, $iAltura, "AD" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DE CONTRIBUI��ES PREVIDENCI�RIAS (AB+AC)" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalizadorContribuicaoPrevicendiaPatronalServidor,"f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	/**
  	 *  TOTALIZADORES RESTANTES
  	*/
  	$oPdf->cell(10, $iAltura, "AE" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA DOS INATIVOS (I)" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalFolhaBrutaI, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "AF" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA FOLHA BRUTA DO PENSIONISTAS (Q)" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, trim(db_formatar($iTotalFolhaBrutaP, "f")) ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "AG" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL FOLHA 90 - PENSIONISTAS DO PODER NA FOLHA DO EXECUTIVO" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, '-' ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "AH" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA CONTRIBUI��O PREVIDENCI�RIA DO SERVIDOR FOLHA 90 PENSION" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, '-' ,1,0,"R",0);
  	$oPdf->Ln();
  
  	$oPdf->cell(10, $iAltura, "AI" ,1,0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL DA CONTRIBUI��O PREVIDENCI�RIA PATRONAL FOLHA 90 PENSION" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, '-' ,1,0,"R",0);
  	$oPdf->Ln();

    $iTotalDeficit = $iTotalizadorContribuicaoPrevicendiaPatronalServidor - $iTotalFolhaBrutaI - $iTotalFolhaBrutaP;
  
    $oPdf->cell(10, $iAltura, "AJ" ,'LRT',0,"C",0);
    $oPdf->cell(152,$iAltura, "D�FICIT/SUPER�VIT (AD+AH+AI-AE-AF-AG)" ,1,0,"C",0);
    $oPdf->cell(30, $iAltura, trim(db_formatar($iTotalDeficit, "f")) ,1,0,"R",0);
    $oPdf->Ln();


  	$oPdf->cell(10, $iAltura, "" ,'LRB',0,"C",0);
  	$oPdf->cell(152,$iAltura, "TOTAL SOLICITADO PELO PODER PARA PAGAMENTO FOLHA INATIVOS/PENSION" ,1,0,"C",0);
  	$oPdf->cell(30, $iAltura, '-' ,1,0,"R",0);
  	$oPdf->Ln();
  
  	/**
  	 * ASSINATURA RESPONS�VEL
  	*/
  	$oPdf->setfont('arial','b', 5);
  	$oPdf->cell(192,$iAltura+5, '' ,1,0,"C",0);
  
  	$oPdf->text( 80 , $oPdf->GetY()+8,"ASSINATURA DO RESPONS�VEL PELO PODER/�RG�O");
  
  	$oPdf->Close();
  
  	$oPdf->Output( 'tmp/totalizadores.pdf', false, true);
  
  	if( file_exists ('tmp/totalizadores.pdf') ){
  		return true;
  	}
  
  	return false;
  }
  
}
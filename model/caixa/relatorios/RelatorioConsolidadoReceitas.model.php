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


class RelatorioConsolidadoReceitas extends PDF {
  
  /**
   * Modelos Dos Relatório Selecionados
   */
  private $aModelosInternos = array();
  
  /**
   * 
   * @var array
   */
  private $oDadosRelatorios = array();
  
  private $lQuebraPagina;
  
  private $sNomeArquivo;
  
  /**
   * Totalizadores
   * @var number
   */
  public $nTotalizadorValorHistorico = 0;
  public $nTotalizadorValorPago      = 0;
  public $nTotalizadorValorPagar     = 0;
  public $nTotalizadorDesconto       = 0;
  public $nTotalizadorValorCorrigido = 0;
  public $nTotalizadorMulta          = 0;
  public $nTotalizadorJuros          = 0;
  public $nTotalizadorTotal          = 0;
  
  /**
   * Construtor do Relatório 
   */
  public function __construct() {
    
    global $head1, $head2, $head3, $head4, $head5;
    
    $oDaoConsolidacaoDebitos = db_utils::getDao('consolidacaodebitos');
    
    $sCampos                 = 'k161_sequencial         ,';
    $sCampos                .= 'k161_datageracao        ,';
    $sCampos                .= 'k161_usuario            ,';
    $sCampos                .= 'k161_filtrosselecionados';
    
    $sSqlConsolidacaoDebitos = $oDaoConsolidacaoDebitos->sql_query_file(null, $sCampos, 'k161_datageracao desc');
    
    $rsConsolidacaoDebitos   = $oDaoConsolidacaoDebitos->sql_record($sSqlConsolidacaoDebitos);

    $oCabecalho              = db_utils::fieldsMemory($rsConsolidacaoDebitos, 0);
    
    $aFiltros                = explode('|', $oCabecalho->k161_filtrosselecionados);
    
    $head2 = 'RELATÓRIO CONSOLIDADO DE MOVIMENTAÇÕES';
    
    if ($aFiltros[0] != '')
      $head3 = 'Período Inicial: ' . $aFiltros[0];
    if ($aFiltros[1] != '')
      $head4 = 'Período Final:   ' . $aFiltros[1];
    if ($aFiltros[2] != '')
      $head5 = 'Data Débitos:    ' . implode('/', array_reverse(explode('-', $aFiltros[2])));
    
    parent::__construct('L');
    
    $this->Open();
    $this->AliasNbPages();
    
    $this->AddPage();
    $this->SetFillColor(235);
    $this->Setfont('Arial', 'b', 9);
    
  }
  
  /**
   * 
   * @param integer $iCodigoModelo
   * @return boolean
   */
  public function adicionarModelo( $iCodigoModelo ) {
    
    $this->aModelosInternos[] = $iCodigoModelo;
    return true;
  }

  
  private function getDados() {
    
    $oDaoConsolidacaoDebitosRegistros = db_utils::getDao('consolidacaodebitosregistros');
    
    $this->aDadosRelatorios           = array();
    
    foreach ($this->aModelosInternos as $iCodigoModelo) {
    
      if ($iCodigoModelo == 1) {
        $sCodigoModelo = '1';
      } else if ($iCodigoModelo == 2) {
        $sCodigoModelo = '2';
      } else if ($iCodigoModelo == 3) {
        $sCodigoModelo = '3';
      } else if ($iCodigoModelo == 4) {
        $sCodigoModelo = '4, 5';
      } else if ($iCodigoModelo == 5) {
        $sCodigoModelo = '6';
      } else if ($iCodigoModelo == 6) {
        $sCodigoModelo = '7';
      } else if ($iCodigoModelo == 7) {
        $sCodigoModelo = '8, 9';
      }

      $aModelos = explode(",", $sCodigoModelo);
      
      foreach ($aModelos as $iCodigoModelo) {
        
        $iCodigoModelo = trim($iCodigoModelo);
        
        $sSqlConsolidacaoDebitosRegistros       = $oDaoConsolidacaoDebitosRegistros->sql_query(null,
                                                                                               "*",
                                                                                               "k162_receitatesouraria",
                                                                                               "k162_tiporelatorio = $iCodigoModelo");
        
        $rsConsolidacaoDebitosRegistros         = $oDaoConsolidacaoDebitosRegistros->sql_record($sSqlConsolidacaoDebitosRegistros);
        
        $this->aDadosRelatorios[$iCodigoModelo] = db_utils::getCollectionByRecord($rsConsolidacaoDebitosRegistros);
        
      }
      
      
    
    }
    
    return true;
  }
  
  private function escreverCabecalho( $iTipoRelatorio, $sDescricao, $lDiferente = false) {
    
    $this->Ln(5);
    
    $iQuantidadeColunas = $lDiferente ? 3 : 5 ;
    $iLargura           = 280;
    $iLarguraValores    = 160 / $iQuantidadeColunas; 
    
    $this->Setfont('Arial', 'b', 10);                     
    $this->Cell( $iLargura, 5,  $sDescricao, true, true, 'C', true);
    
    $this->Setfont('Arial', 'b', 6);
    
    $this->Cell( 20, 5, 'Receita Orçamento' , true, false, 'C', true); 
    $this->Cell( 20, 5, 'Receita Tesouraria', true, false, 'C', true); 
    $this->Cell( 80, 5, 'Descrição'         , true, false, 'C', true); 
    
    if ( $lDiferente ) {

    	if ($iTipoRelatorio == 6) { //relatório de pagamentos
    		
    		$this->Cell( 160, 5, 'Valor Pago', true, true, 'C', true);
    		
    	} else {
    	     
	      $this->Cell( $iLarguraValores, 5, 'Valor a Pagar'     , true, false, 'C', true);
	      $this->Cell( $iLarguraValores, 5, 'Valor Pago'        , true, false, 'C', true);
	      $this->Cell( $iLarguraValores, 5, 'Desconto Concedido', true, true , 'C', true);
      
    	}
    } else {
      $this->Cell( $iLarguraValores, 5, 'Vlr Histórico'     , true, false, 'C', true); 
      $this->Cell( $iLarguraValores, 5, 'Vlr Corrigido'     , true, false, 'C', true); 
      $this->Cell( $iLarguraValores, 5, 'Multa'             , true, false, 'C', true); 
      $this->Cell( $iLarguraValores, 5, 'Juros'             , true, false, 'C', true); 
      $this->Cell( $iLarguraValores, 5, 'Total'             , true, true , 'C', true); 
    }
     
  }
  
  private function escreverRegistros( $iTipoRelatorio, $oDados, $lDiferente ) {
    
    
    $iQuantidadeColunas = $lDiferente ? 3 : 5 ;
    $iLargura           = 280;
    $iLarguraValores    = 160 / $iQuantidadeColunas;

    $this->Setfont('Arial', 'b', 6);

    $this->Cell( 20, 5, $oDados->k162_receitaorcamento , true, false, 'C');
    $this->Cell( 20, 5, $oDados->k162_receitatesouraria, true, false, 'C');
    $this->Cell( 80, 5, $oDados->k162_descricao        , true, false, 'L');
    
    if ( $lDiferente ) {
    	
    	if ($iTipoRelatorio == 6) {
    		
    		$this->Cell( 160, 5, db_formatar($oDados->k162_valorhistorico, "f"), true, true , 'R');
    	  $this->nTotalizadorValorHistorico += $oDados->k162_valorhistorico;
    		
    	} else {
      
	      $this->Cell( $iLarguraValores, 5, db_formatar($oDados->k162_valorpagar       , "f"), true, false, 'R');
	      $this->Cell( $iLarguraValores, 5, db_formatar($oDados->k162_valorpago        , "f"), true, false, 'R');
	      $this->Cell( $iLarguraValores, 5, db_formatar($oDados->k162_descontoconcedido, "f"), true, true , 'R');
	      
    	}
    	
    	//$this->nTotalizadorValorHistorico += $oDados->k162_valorhistorico;
    	$this->nTotalizadorValorPagar     += $oDados->k162_valorpagar;
    	$this->nTotalizadorValorPago      += $oDados->k162_valorpago;
    	$this->nTotalizadorDesconto       += $oDados->k162_descontoconcedido;
      
    } else {
      
      $this->Cell( $iLarguraValores, 5, db_formatar($oDados->k162_valorhistorico   , "f"), true, false, 'R');
      $this->Cell( $iLarguraValores, 5, db_formatar($oDados->k162_valorcorrigido   , "f"), true, false, 'R');
      $this->Cell( $iLarguraValores, 5, db_formatar($oDados->k162_multa            , "f"), true, false, 'R');
      $this->Cell( $iLarguraValores, 5, db_formatar($oDados->k162_juros            , "f"), true, false, 'R');
      $this->Cell( $iLarguraValores, 5, db_formatar($oDados->k162_total            , "f"), true, true , 'R');
      
      $this->nTotalizadorValorHistorico += $oDados->k162_valorhistorico;
      $this->nTotalizadorValorCorrigido += $oDados->k162_valorcorrigido;
      $this->nTotalizadorMulta          += $oDados->k162_multa;
      $this->nTotalizadorJuros          += $oDados->k162_juros;
      $this->nTotalizadorTotal          += $oDados->k162_total;
    }
    
  }
  
  public function escreverTotalizadores($iTipoRelatorio, $lDiferente) {
  	
  	$iQuantidadeColunas = $lDiferente ? 3 : 5 ;
  	$iLargura           = 280;
  	$iLarguraValores    = 160 / $iQuantidadeColunas;
  	
  	$this->Setfont('Arial', 'b', 6);
  	
  	$this->Cell(120, 5, "Total(is)" , true, false, 'R');
  	
  	if ( $lDiferente ) {
  	
  		if ($iTipoRelatorio == 6) {
  	
  			$this->Cell( 160, 5, db_formatar($this->nTotalizadorValorHistorico, "f"), true, true , 'R');
  	
  		} else {
  	
  			$this->Cell( $iLarguraValores, 5, db_formatar($this->nTotalizadorValorPagar, "f"), true, false, 'R');
  			$this->Cell( $iLarguraValores, 5, db_formatar($this->nTotalizadorValorPago , "f"), true, false, 'R');
  			$this->Cell( $iLarguraValores, 5, db_formatar($this->nTotalizadorDesconto  , "f"), true, true , 'R');
  			 
  		}
  	
  	} else {
  	
  		$this->Cell( $iLarguraValores, 5, db_formatar($this->nTotalizadorValorHistorico, "f"), true, false, 'R');
  		$this->Cell( $iLarguraValores, 5, db_formatar($this->nTotalizadorValorCorrigido, "f"), true, false, 'R');
  		$this->Cell( $iLarguraValores, 5, db_formatar($this->nTotalizadorMulta         , "f"), true, false, 'R');
  		$this->Cell( $iLarguraValores, 5, db_formatar($this->nTotalizadorJuros         , "f"), true, false, 'R');
  		$this->Cell( $iLarguraValores, 5, db_formatar($this->nTotalizadorTotal         , "f"), true, true , 'R');
  	
  	}
  }

  
  public function processar() {

    $lBuscouDados        = $this->getDados();

    $lDiferente          = false;
    
    $aDescricaoRelatorio = array(
                                  1 => 'Descontos Concedidos por Regras',
                                  2 => 'Débitos Cancelados',
                                  3 => 'Prescrição de Dívida',
                                  4 => 'Inscrição de Dívida - Curto Prazo',
                                  5 => 'Inscrição de Dívida - Longo Prazo',
                                  6 => 'Pagamentos Geral',
                                  7 => 'Descontos Concedidos (Cota Única)',
                                  8 => 'Resumo Geral de Dívida - Curto Prazo',
                                  9 => 'Resumo Geral de Dívida - Longo Prazo'
                                );
    
    
    if ($lBuscouDados) {
      
      $iTiposSelecionados = count($this->aDadosRelatorios);
      
      $iImpressos         = 0;
      
      foreach ($this->aDadosRelatorios as $iTipoRelatorio => $aDadosRelatorio) {
        
        $lDiferente = false;
        
        if (in_array($iTipoRelatorio, array(1, 6, 7))) {
          $lDiferente = true;
        } 
        
        $this->escreverCabecalho( $iTipoRelatorio, $aDescricaoRelatorio[$iTipoRelatorio], $lDiferente);
        
        foreach ($aDadosRelatorio as $oDadosRelatorio) {
          
          $this->escreverRegistros($iTipoRelatorio, $oDadosRelatorio, $lDiferente);  
          
        }
        
        $this->escreverTotalizadores($iTipoRelatorio, $lDiferente);
        
        $iImpressos++;
        
        if ($this->getQuebraPagina() and ($iTiposSelecionados > $iImpressos)) {
          $this->AddPage();
        }

      }

    }

    if ( !$lBuscouDados ) {
      throw new BusinessException("Erro ao Bucar os Dados do Exception");
    } 

    $this->setNomeArquivo("tmp/relatorioconsolidado" . date('YmdHis') . ".pdf");
    
    $this->Output($this->sNomeArquivo, false, true);
    
    return true;
    
  }
  
  public function getNomeArquivo() {
    return $this->sNomeArquivo;
  }
  
  public function setNomeArquivo($sNomeArquivo) {
    $this->sNomeArquivo = $sNomeArquivo;
  }
  
  public function setQuebraPagina($lQuebraPagina) {
    $this->lQuebraPagina = $lQuebraPagina;
  }
  
  public function getQuebraPagina() {
    return $this->lQuebraPagina;
  }
  
  
}
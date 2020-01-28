<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

 /**
  * programação financeira
  * @package Caixa
  */
  class ProgramacaoFinanceira {
  	
   /**
    * Código sequencial;
    *
    * @var integer
    */ 
  	protected $iCodigo;
  	
   /**
    * Código id_usuario;
    *
    * @var integer
    */
  	protected $iIdUsuario;
  	
   /**
    * Dia do Pagamento
    *
    * @var integer
    */
    protected $iDiaPagamento;
    
   /**
    * Parcelas
    *
    * @var aParcelas collection
    */
    protected $aParcelas = array();
    
   /**
    * Valor Total
    *
    * @var numeric
    */
    protected $nValorTotal;
    
   /**
    * Periodicidade;
    *
    * @var integer
    */
    protected $iPeriodicidade;
    
   /**
    * Mes Inicial;
    *
    * @var integer
    */
    protected $iMesInicial;
    
   /**
    * Construtor da classe
    * 
    * @param integer $iCodigo
    */
    public function __construct($iCodigo=null) {
    	
    	if (!empty($iCodigo)) {

        $oDaoProgramacaoFinanceira = db_utils::getDao("programacaofinanceira");
        $sSqlProgramacaoFinanceira = $oDaoProgramacaoFinanceira->sql_query($iCodigo, "programacaofinanceira.*");
        $rsProgramacaoFinanceira   = $oDaoProgramacaoFinanceira->sql_record($sSqlProgramacaoFinanceira);
        if ($oDaoProgramacaoFinanceira->numrows > 0) {
        	
        	$oProgramacaoFinanceira = db_utils::fieldsMemory($rsProgramacaoFinanceira, 0);
       	  $this->setCodigo($iCodigo);
        	$this->setIdUsuario($oProgramacaoFinanceira->k117_id_usuario);
        	$this->setDiaPagamento($oProgramacaoFinanceira->k117_diapagamento);
          $this->setValorTotal($oProgramacaoFinanceira->k117_valortotal);
          $this->setPeriodicidade($oProgramacaoFinanceira->k117_periodicidade);

          $oDaoProgrFinanceiraParcela = db_utils::getDao("programacaofinanceiraparcela");
          $sWhere                     = "k118_programacaofinanceira = {$this->getCodigo()}";
          $sSqlProgrFinanceiraParcela = $oDaoProgrFinanceiraParcela->sql_query(null, "programacaofinanceiraparcela.*",
                                                                               'k118_parcela', $sWhere);
          $rsProgrFinanceiraParcela   = $oDaoProgrFinanceiraParcela->sql_record($sSqlProgrFinanceiraParcela);
          
          for ($i = 0; $i < $oDaoProgrFinanceiraParcela->numrows; $i++) {
                      	
            $oParcela                      = new stdClass();
            $oProgramacaoFinanceiraParcela = db_utils::fieldsMemory($rsProgrFinanceiraParcela, $i);
            $oParcela->parcela             = $oProgramacaoFinanceiraParcela->k118_parcela;
            $oParcela->datapagamento       = $oProgramacaoFinanceiraParcela->k118_datapagamento;
            $oParcela->valor               = $oProgramacaoFinanceiraParcela->k118_valor;            
            $this->aParcelas[]             = $oParcela;
            
            if ($i == 0) {
              $dtPagamento = $oProgramacaoFinanceiraParcela->k118_datapagamento;
            	$iMesInicial = date("m", mktime(0, 0, 0, substr($dtPagamento, 5, 2), 
            	                                         substr($dtPagamento, 8, 2), 
            	                                         substr($dtPagamento, 0, 4)));
              $this->setMesInicial($iMesInicial);
            }
          }
        }
    	}
    }
  
	 /**
	  * Retorna o Código Sequencial
	  * 
	  * @return integer
	  */
	  public function getCodigo() {
	
	    return $this->iCodigo;
	  }
  
	 /**
	  * Seta valor código sequencial
	  * 
	  * @param integer $iCodigo
	  */
	  private function setCodigo($iCodigo) {
	
	    $this->iCodigo = $iCodigo;
	  }
  
	 /**
	  * Retorna o Dia de Pagamento
	  * 
	  * @return integer
	  */
	  public function getDiaPagamento() {
	
	    return $this->iDiaPagamento;
	  }
	  
	 /**
	  * Seta Dia de Pagamento
	  * 
	  * @param integer $iDiaPagamento
	  */
	  public function setDiaPagamento($iDiaPagamento) {
	
	    $this->iDiaPagamento = $iDiaPagamento;
	  }
  
	 /**
	  * Retorna Id do Usuário
	  * 
	  * @return integer
	  */
	  public function getIdUsuario() {
	
	    return $this->iIdUsuario;
	  }
	  
	 /**
	  * Seta Id do Usuário
	  * 
	  * @param integer $iIdUsuario
	  */
	  public function setIdUsuario($iIdUsuario) {
	
	    $this->iIdUsuario = $iIdUsuario;
	  }
  
	 /**
	  * Retorna Periodicidade
	  * 
	  * @return integer
	  */
	  public function getPeriodicidade() {
	
	    return $this->iPeriodicidade;
	  }
	  
	 /**
	  * Seta Periodicidade
	  * 
	  * @param integer $iPeriodicidade
	  */
	  public function setPeriodicidade($iPeriodicidade) {
	
	    $this->iPeriodicidade = $iPeriodicidade;
	  }
  
	 /**
	  * Retorna Valor Total
	  * 
	  * @return numeric
	  */
	  public function getValorTotal() {
	
	    return $this->nValorTotal;
	  }
  
	 /**
	  * Seta Valor Total
	  * 
	  * @param numeric $nValorTotal
	  */
	  public function setValorTotal($nValorTotal) {
	  
	    $this->nValorTotal = $nValorTotal;
	  }
    
	 /**
	   * Retorna o Mes Inicial
	   * @return integer
	   */
	  public function getMesInicial() {
	  
	    return $this->iMesInicial;
	  }
	  
	 /**
	   * Seta o Mes Inicial
	   * @param integer $iMesInicial
	   */
	  private function setMesInicial($iMesInicial) {
	
	    $this->iMesInicial = $iMesInicial;
	  }
  
   /**
    * Processar
    */
    public function processar($iNumeroParcelas, $iMesInicial) {
    	
      $iMesAtual      = date('m',db_getsession('DB_datausu'));
      $iAnoAtual      = db_getsession('DB_anousu');
      $iPeriodicidade = $this->getPeriodicidade();
      $this->setMesInicial($iMesInicial);
    	if ($iMesInicial < $iMesAtual) {
    		throw new Exception("Erro mês inicial {$iMesInicial} menor que o mês {$iMesAtual} atual.");
    	}
    	
    /*
     * Acerta datas de pagamento das parcelas por periodicidade
     */
	    for ($iInd = 0; $iInd < $iNumeroParcelas; $iInd++) {
	    	
	      switch ($iPeriodicidade) {
	            
	        case 1:
	 	
	          if ($iInd == 0) {
	          	$iMes = $iMesInicial;
	          } else {
		          $iMes = ($iMesInicial+1);
	          }
	
	          if ($iMes > 12) {
	                  
	            $iMes      = 1;
	            $iAnoAtual = ($iAnoAtual+1);
	          }
	          break;
	         
	        case 2:
	  
	          if ($iInd == 0) {
	            $iMes = $iMesInicial;
	          } else {
	            $iMes = $iMesInicial+2;
	          }
	
	          if ($iMes > 12) {
	            	
	          	$iMes      = ($iMes-12);
	            $iAnoAtual = ($iAnoAtual+1);
	          }
	          break;
	          
	        case 3:
	  
	          if ($iInd == 0) {
	            $iMes = $iMesInicial;
	          } else {
	            $iMes = ($iMesInicial+3);
	          }
	
	          if ($iMes > 12) {
	                  
	            $iMes      = ($iMes-12);
	            $iAnoAtual = ($iAnoAtual+1);
	          }
	          break;
	          
	        case 4:
	               
	          if ($iInd == 0) {
	            $iMes = $iMesInicial;
	          } else {
	            $iMes = ($iMesInicial+4);
	          }
	
	          if ($iMes > 12) {
	                  
	            $iMes      = ($iMes-12);
	            $iAnoAtual = ($iAnoAtual+1);
	          }
	          break;
	          
	        case 5:
	              
	          if ($iInd == 0) {
	            $iMes = $iMesInicial;
	          } else {
	            $iMes = ($iMesInicial+6);
	          }
	
	          if ($iMes > 12) {
	                  
	            $iMes      = ($iMes-12);
	            $iAnoAtual = ($iAnoAtual+1);
	          }
	          break;
	          
	        case 6:
	
	          $iMes = $iMesInicial;
	          if ($iInd == 0) {
	            $iAnoAtual = $iAnoAtual;
	          } else {
	            $iAnoAtual = ($iAnoAtual+1);
	          }
	          break;
	      }
	      
        $iMesInicial    = str_pad(($iMes),2,"0",STR_PAD_LEFT);
        $sDataPagamento = $iAnoAtual."-".$iMesInicial."-".$this->getDiaPagamento();
            
        $oParcela                      = new stdClass();
        $oParcela->parcela             = $iInd+1;
        $oParcela->datapagamento       = $sDataPagamento;      
        $this->aParcelas[]             = $oParcela;
	    }
	    
      $this->acertaValorParcelas();
      return $this;
    }

   /**
    * Incluir Parcela
    * 
    * @param integer $iParcela
    * @param integer $dtData
    * @param numeric $nValor
    */
    public function incluirParcela($iParcela, $dtData, $nValor) {
    	
      if (empty($dtData)) {
        throw new Exception("Erro data de pagamento não informado! \\n\\n Inclusão abortada.");
      }
      
    	if ($nValor > $this->getValorTotal()) {
    		throw new Exception("Erro valor informado é maior que o saldo disponível! \\n\\n Inclusão abortada.");
    	}
    	
      if ($nValor < 0) {
        throw new Exception("Erro valor da parcela ficará negativa! \\n\\n Inclusão abortada.");
      }
    	
    	if (empty($iParcela)) {
    		throw new Exception("Erro parcela não informado! \\n\\n Inclusão abortada.");
    	}
    	    	
      if (empty($nValor)) {
        throw new Exception("Erro valor da parcela não informado! \\n\\n Inclusão abortada.");
      }
      
      $oDaoProgramacaoFinanceiraParcela = db_utils::getDao("programacaofinanceiraparcela");
      $aParcelas = $this->getParcelas();
      foreach ($aParcelas as $oParcela) {
        
       /**
        * Verifica se já existe parcela
        */
        if ($iParcela == $oParcela->parcela) {
          throw new Exception("Erro parcela {$oParcela->parcela} já existe! \\n\\n Inclusão abortada.");
        }
        
        $dtPagamento = $oParcela->datapagamento;
        $this->acertaValorParcelas($iParcela, $nValor);
      }
      
     /**
      * Verifica se a data e menor que a data da ultima parcela
      */
      if ( strtotime($dtData) <= strtotime($dtPagamento)) {
        throw new Exception("Erro data do pagamento menor que última parcela \\nou data já informada! \\n\\n Inclusão abortada.");
      }
      
      $oParcela                = new stdClass();
      $oParcela->parcela       = $iParcela;
      $oParcela->datapagamento = $dtData;
      $oParcela->valor         = $nValor;
      $this->aParcelas[]       = $oParcela;
      
      $this->acertaValorParcelas($iParcela, $nValor);
      return $this;
    }
    
   /**
    * Alterar Parcela
    * 
    * @param integer $iParcela
    * @param integer $dtData
    * @param numeric $nValor
    */
    public function alterarParcela($iParcela, $dtData, $nValor) {
      
      if ($nValor > $this->getValorTotal()) {
        throw new Exception("Erro valor informado é maior que o saldo disponível! \\n\\n Alteração abortada.");
      }
      
      if ($nValor < 0) {
        throw new Exception("Erro valor da parcela ficará negativa! \\n\\n Alteração abortada.");
      }
    	
      if (empty($iParcela)) {
        throw new Exception("Erro parcela não informado! \\n\\n Alteração abortada.");
      }
      
      if (empty($dtData)) {
        throw new Exception("Erro data de pagamento não informado! \\n\\n Alteração abortada.");
      }
      
      if (empty($nValor)) {
        throw new Exception("Erro valor da parcela não informado! \\n\\n Alteração abortada.");
      }      

      $aParcelas = $this->getParcelas();      
      foreach ($aParcelas as $iPos => $oParcela) {
        
        if ($iParcela == $oParcela->parcela) {

        	$this->acertaValorParcelas($iParcela, $nValor);

          $oParcela->parcela       = $iParcela;
          $oParcela->valor         = round($nValor, 2);
          $oParcela->datapagamento = $dtData;

          array_splice($aParcelas, $iPos, 1, $oParcela);
          break;
        }
      }
      
      $this->acertaValorParcelas($iParcela, $nValor);
      return $this;
    }
    
   /**
    * Excluir Parcela 
    * 
    * @param integer $iParcela
    */
    public function excluirParcela($iParcela) {
      
      if (empty($iParcela)) {
        throw new Exception("Erro parcela não informado.");
      }
      
      foreach ($this->getParcelas() as $iPos => $oParcela) {

        if ($iParcela == $oParcela->parcela) {
        	
          array_splice($this->aParcelas, $iPos, 1);
          $this->acertaValorParcelas();
          break;
        }         
      }

      return $this;
    }
    
   /**
    * Salvar Programação Financeira
    */
    public function save() {
    	
   		$oDaoProgramacaoFinanceira        = db_utils::getDao("programacaofinanceira");
    	$oDaoProgramacaoFinanceiraParcela = db_utils::getDao("programacaofinanceiraparcela");
    	
   		$oDaoProgramacaoFinanceira->k117_id_usuario    = $this->getIdUsuario();
   		$oDaoProgramacaoFinanceira->k117_periodicidade = $this->getPeriodicidade();
   		$oDaoProgramacaoFinanceira->k117_valortotal    = $this->getValorTotal();
   		$oDaoProgramacaoFinanceira->k117_diapagamento  = $this->getDiaPagamento();
   		
   		$iCodigo = $this->getCodigo();
    	if (empty($iCodigo)) {
    		
    	 /**
    	  * Inclui novo registro programação financeira
    	  */
    		$oDaoProgramacaoFinanceira->k117_data        = date('Y-m-d', db_getsession('DB_datausu'));
    		$oDaoProgramacaoFinanceira->incluir(null);
	    	if ($oDaoProgramacaoFinanceira->erro_status == 0) {
	        throw new Exception("Erro ao incluir programação financeira. \\n{$oDaoProgramacaoFinanceira->erro_msg}");
	      }
	      
	      $this->setCodigo($oDaoProgramacaoFinanceira->k117_sequencial);
    	} else {
    		
    	 /**
    	  * Altera registro programação financeira
     	  */
    		$oDaoProgramacaoFinanceira->k117_sequencial  = $this->getCodigo();
    	  $oDaoProgramacaoFinanceira->alterar($oDaoProgramacaoFinanceira->k117_sequencial);
        if ($oDaoProgramacaoFinanceira->erro_status == 0) {
          throw new Exception("Erro ao alterar programação financeira. \\n{$oDaoProgramacaoFinanceira->erro_msg}");
        } 
    	}
    	
     /**
      * Exclui registro programação financeira parcela
      */
    	$oDaoProgramacaoFinanceiraParcela->excluir(null, "k118_programacaofinanceira = {$this->getCodigo()}");
      if ($oDaoProgramacaoFinanceiraParcela->erro_status == 0) {
      	
      	$sMsg = "Erro ao excluir parcela programação financeira. \\n{$oDaoProgramacaoFinanceiraParcela->erro_msg}";
        throw new Exception($sMsg);
      }
    	
      $aParcelas = $this->getParcelas();
      foreach ($aParcelas as $oParcela) {
      	
       /**
        * Inclui novo registro na programação financeira parcela
        */
      	$oDaoProgramacaoFinanceiraParcela->k118_programacaofinanceira = $this->getCodigo();
      	$oDaoProgramacaoFinanceiraParcela->k118_parcela               = $oParcela->parcela;
      	$oDaoProgramacaoFinanceiraParcela->k118_datapagamento         = $oParcela->datapagamento;
      	$oDaoProgramacaoFinanceiraParcela->k118_valor                 = $oParcela->valor;
      	$oDaoProgramacaoFinanceiraParcela->incluir(null);
        if ($oDaoProgramacaoFinanceiraParcela->erro_status == 0) {
        	
        	$sMsg = "Erro ao incluir parcela programação financeira. \\n{$oDaoProgramacaoFinanceiraParcela->erro_msg}";
          throw new Exception($sMsg);
        }
      }
    }
    
   /**
    * Mostrar Parcelas Programação Financeira Parcela 
    */
    public function getParcelas() {
    	return $this->aParcelas;
    }
    
   /**
    * Acerta valor das parcelas
    */
    private function acertaValorParcelas($iParcela=null, $nValor=null) {
    	
	    $nTotalDiferenca = 0;
	    $nValorDiferenca = 0;
	    $nDiferenca      = 0;
	    $nValorParcela   = 0;
	    
      $iTotalParcelas  = count($this->getParcelas());
	    $nValorTotal     = round($this->getValorTotal(), 2);  	

	    if ($iTotalParcelas > 0) {

	      if (!empty($iParcela) && !empty($nValor)) {
	      	
		      if (($iTotalParcelas - 1) > 0) {
	          $iTotalParcelas = ($iTotalParcelas - 1);
	        } else {
	          $iTotalParcelas = 1;
	        }
	      	
	      	$nValor          = round($nValor, 2);
	      	$nValorDiferenca = round(($nValorTotal - $nValor), 2);
	        $nValorParcela   = round(($nValorDiferenca / $iTotalParcelas), 2);
	        
	        $aParcelas       = $this->getParcelas();
          foreach ($aParcelas as $iPos => $oParcela) {
          
            if ($iParcela != $oParcela->parcela) {
              
              if ($nValorParcela <= 0) {
                throw new Exception("Erro valor da parcela não pode ser zero!");
              }
              
              $oParcela->valor = round($nValorParcela, 2);
              array_splice($aParcelas, $iPos, 1, $oParcela);
            }
          }
          
          $nTotalDiferencaDivisao       = round(($nValorDiferenca / $iTotalParcelas), 2);
          $nTotalDiferencaMultiplicacao = round(($nTotalDiferencaDivisao * $iTotalParcelas), 2);
          $nTotalDiferenca              = round(($nTotalDiferencaMultiplicacao + $nValor), 2);
	      } else {
          
	      	$nValor          = round($nValor, 2);
	      	$nValorDiferenca = round(($nValorTotal - $nValor), 2);
	        $nValorParcela   = round(($nValorDiferenca / $iTotalParcelas), 2);

	        $aParcelas       = $this->getParcelas();
          foreach ($aParcelas as $iPos => $oParcela) {
            
          	if ($nValorParcela <= 0) {
          		throw new Exception("Erro valor da parcela não pode ser zero!");
          	}
          	
            $oParcela->valor = $nValorParcela;
            array_splice($aParcelas, $iPos, 1, $oParcela);
          }
          
          $nTotalDiferenca = ($nValorParcela * $iTotalParcelas);
	      }
	      
        if ($nTotalDiferenca < $nValorTotal) {          
          $nDiferenca = ($this->aParcelas[0]->valor + ($nValorTotal - $nTotalDiferenca));
        } else {
          $nDiferenca = ($this->aParcelas[0]->valor + ($nValorTotal - $nTotalDiferenca));
        }
          
        $this->aParcelas[0]->valor = $nDiferenca;
	    }
    }
  }
?>
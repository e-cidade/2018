<?php
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
 * Enter description here...
 * @package contratos
 */
class AcordoComissao {
	
	/**
	 * Código da comissão. (acordocomissao.ac08_sequencial)
	 *
	 * @var Integer
	 */
	protected $iCodigo;
	
	/**
	 * Descrição  da comissão. (acordocomissao.ac08_descricao)
	 *
	 * @var String
	 */
	protected $sDescricao;
	
	 /**
   * Observação  da comissão. (acordocomissao.ac08_observacao)
   *
   * @var String
   */
  protected $sObservacao;
	
	/**
	 * Data inicial da vigência da comissão. (acordocomissao.ac08_datainicial)
	 *
	 * @var String
	 */	
	protected $sDataInicial;
	
	/**
	 * Data final da vigência da comissão. (acordocomissao.ac08_datafinal)
	 *
	 * @var String
	 */
	protected $sDataFinal;
	
	/**
	 * Menbros da comissão. (acordocomissaomembros)
	 *
	 * @var Array of AcordoComissaoMembros
	 */
	protected $aMembros = array();
	
	/**
   * Método construtor da classe AcordoCOmissao
   * 
   * @param Integer $iCodigo 
   */
  function __construct($iCodigo = null) {
  	
  	//SE EXISTIR O CODIGO, CARREGA OS DADOS DO BANCO
  	if ($iCodigo) {
  		
  		$this->iCodigo = $iCodigo;
  		
  		//BUSCA OS DADOS DO ACORDO
  		$oDaoComissao = db_utils::getDao('acordocomissao');
  		$sSql         = $oDaoComissao->sql_query(null, "*", "", " ac08_sequencial={$iCodigo}");
  		$rsComissao   = $oDaoComissao->sql_record($sSql);
  		
  		if ($oDaoComissao->numrows == 0) {
  			
  			throw new Exception('Não foi encontrado comissão com o código informado.');
  		} else {
  		
	  		$oComissao = db_utils::fieldsMemory($rsComissao, 0);
	  		

	  		$this->setDescricao($oComissao->ac08_descricao);
	  		$this->setObservacao($oComissao->ac08_observacao);
	  		$this->setDataInicial(db_formatar($oComissao->ac08_datainicial, "d"));
	  		$this->setDataFinal(db_formatar($oComissao->ac08_datafim, "d"));
	  		
	  		//BUSCA OS MEMBROS DO ACORDO
	  		$oDaoComissaoMembro = db_utils::getDao('acordocomissaomembro');
	  		$sSqlMembro         = $oDaoComissaoMembro->sql_query(null, "*", "ac07_tipomembro, z01_nome",
	  		                                                     " ac07_acordocomissao={$iCodigo}");
	  		$rsCM               = $oDaoComissaoMembro->sql_record($sSqlMembro);
	  		
	  		if($oDaoComissaoMembro->numrows > 0) {
	  			
	  		  $aMembros           = db_utils::getColectionByRecord($rsCM);
		  		
		  		foreach($aMembros AS $oMembroC) {
		  		
		  			$oMembro = new AcordoComissaoMembro($oMembroC->ac07_sequencial);
		  			$this->addMembro($oMembro);
  		    }	
  		  }
  		}  	
  	}
  }
  
  /**
   * Adiciona membro a comissão.
   *
   * @param AcordoComissaoMembro $membro
   * @return Boolean
   */
  function addMembro(AcordoComissaoMembro $membro) {
  	
  	$this->aMembros[$membro->getCodigoCgm()] = $membro;
  	
  	/*
  	$this->aMembros[] = $membro;
  	
  	if (!$this->getMembroByCgm($membro->getCodigoCgm())) {
  		
  		$this->aMembros[$membro->getCodigoCgm()] = $membro;
  		return true;
  	} else {
  		
  		throw new Exception("O membro {$membro->getCodigoCgm()} ja está cadastrado nesta Comissão.");  		
  	}
    */
  }
  
  /**
   * Remove membro da comissão.
   *
   * @param AcordoComissaoMembro $membro
   * return AcordoComissao;
   */
  function removerMembro(AcordoComissaoMembro $membro) {
  	
  	if ( isset($this->aMembros[$membro->getCodigoCgm()]) ) {
  		 
  		unset($this->aMembros[$membro->getCodigoCgm()]);
  		return $this;
  		
  	} else {
  	   return false;  	 
  	}
  }
  
  /**
   * Retorna o membro pelo id, em caso de não haver membro, retorna NULL.
   *
   * @param Integer $id
   * @return AcordoComissaoMembro
   */
  function getMembroByCgm($cgm) {
  	
  	if (isset($this->aMembros[$cgm])) {
  		
  		return $this->aMembros[$cgm];
  	} else {
  		
  		return false;
  	}
  	
  }
  
  function membroExists($cgm) {
  	
	  if (isset($this->aMembros[$cgm])) {
	      
	      return true;
	    } else {
	      
	      return false;
	  }
  }
  
  function getMembros() {
  	
  	return $this->aMembros;
  }
  
  /**
   * Metodo de persistencia dos dados
   * @return AcordoComissao
   */
  function save() {
  	
  	$iDataInicial = explode('/',$this->getDataInicial());
  	$iDataFinal   = explode('/',$this->getDataFinal());
  	$iDataInicial = db_strtotime($iDataInicial[2].'-'.$iDataInicial[1].'-'.$iDataInicial[0]); 
  	$iDataFinal   = db_strtotime($iDataFinal[2].'-'.$iDataFinal[1].'-'.$iDataFinal[0]);
  	
  	if ($iDataInicial > $iDataFinal) {
  		throw new Exception("Erro ao tentar salvar Comissão.\nA Data Final deve ser maior ou igual a Data Inicial");
  	}
  	
  	$oDaoComissao = db_utils::getDao('acordocomissao');
  	$oDaoComissaoMembro = db_utils::getDao('acordocomissaomembro');
  	
  	$oDaoComissao->ac08_descricao   = $this->getDescricao(); 
    $oDaoComissao->ac08_observacao  = $this->getObservacao(); 
    $oDaoComissao->ac08_datainicial = $this->getDataInicial();
    $oDaoComissao->ac08_datafim     = $this->getDataFinal();

    if ($this->iCodigo) {
  		
  		$oDaoComissao->alterar($this->getCodigo());
      if($oDaoComissao->erro_status==0){
        $sqlerro=true;
        return false;
      }
  		
  	} else {
  		
	    $oDaoComissao->incluir(null);  	  
	    if($oDaoComissao->erro_status==0){
        $sqlerro=true;
        return false;
      }	    
      
  		$this->iCodigo = $oDaoComissao->ac08_sequencial;
  	}
  	
  	if ($oDaoComissao->erro_status == 0) {
  		throw new Exception("Erro ao tentar salvar Comissão.\n{$oDaoComissao->erro_msg}");
  	}
  	
  	//EXCLUI OS MEMBROS JA CADASTRADOS NO ACORDO
  	$oDaoComissaoMembro->excluir(NULL, "ac07_acordocomissao={$this->getCodigo()}");

  	//CADASTRA OS MEMBROS DO ACORDO
  	foreach ($this->aMembros as $oMembro) {
  		$oMembro->save();
  	}
  	
  	return $this;
  }  
 
  /**
   * @return Integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * @return String
   */
  public function getDataFinal() {
    
    return $this->sDataFinal;
  }
  
  /**
   * @return String
   */
  public function getDataInicial() {
    return $this->sDataInicial;
  }
  
  /**
   * @return String
   */
  public function getDescricao() {

    return $this->sDescricao;
  }

  /**
   * @return String
   */
  public function getObservacao() {

    return $this->sObservacao;
  }
     
  /**
   * @param String $sDataFinal
   * @return AcordoComissao
   */
  public function setDataFinal($sDataFinal) {

  	$this->sDataFinal = $sDataFinal;
  	return $this;
  }
  
  /**
   * @param String $sDataInicial
   */
  public function setDataInicial($sDataInicial) {
    
  	$this->sDataInicial = $sDataInicial;
  	return $this;
  }
  
  /**
   * @param String $sDescricao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
    return $this;
  }
  
  /**
   * @param String $sObservacao
   */
  public function setObservacao($sObservacao) {

    $this->sObservacao = $sObservacao;
    return $this;
  }
}

?>
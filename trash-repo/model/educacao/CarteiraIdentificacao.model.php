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

/**
 * Classe modelo para carteira de identificacao
 * @package   Educacao
 * @author    Robson Inacio - robson@dbseller.com.br 
 * @version   $Revision: 1.4 $ 
 */
class CarteiraIdentificacao {
	
  private $iSequencialCarteira = null;
  private $iLote               = null;
  private $oSituacao           = null;
  private $oAluno              = null;
  
  /**
   * Metodo construtor, caso seja passado parametro $oAluno, faz o load dos dados 
   *
   * @param Aluno $oAluno
   */
  public function __construct(Aluno $oAluno) {
  	
  	if ($oAluno != null){
  		
  		$this->oAluno = $oAluno;
  		
  		$oDaoCarteiraIdentificacao = db_utils::getDao("loteimpressaocartaoidentificacaoaluno");  		
  		$sWhere = "ed306_aluno = {$oAluno->getCodigoAluno()}";
  		$sSqlCarteiraIdentificacao = $oDaoCarteiraIdentificacao->sql_query(null, 
  		                                                                   "*", 
  		                                                                   "ed306_sequencial desc limit 1", 
  		                                                                   $sWhere);
  		$rsCarteiraIdentificacao   = $oDaoCarteiraIdentificacao->sql_record($sSqlCarteiraIdentificacao);
  		
  		if ($rsCarteiraIdentificacao && $oDaoCarteiraIdentificacao->numrows > 0) {
  			
  			$oCarteiraIdentificacao    = db_utils::fieldsMemory($rsCarteiraIdentificacao,0);
  			/**
  			 * Aluno sem foto, nao pode gerar cartao de identificacao
  			 */
  			if ( empty($oCarteiraIdentificacao->ed47_o_oid) ) {
  				return false;
  			}
  			
			  $this->iSequencialCarteira = $oCarteiraIdentificacao->ed306_sequencial;
			  $this->oSituacao           = new CarteiraIdentificacaoSituacao($oCarteiraIdentificacao->ed306_cartaoidentificacaosituacao);
			  return true;  			
  		} 
  				
  	}
  	
  	$this->setSituacao(new CarteiraIdentificacaoSituacao(1));
  	return true; 	
  }
  
  
  public function getAluno() {
    return $this->oAluno;
  } 
  
  /**
   * Retorna propriedade iSequencialCarteira
   *
   * @return integer
   */
  public function getSequencialCarteira() {
  	
  	return $this->iSequencialCarteira;
  } 
  
  /**
   * * Retorna propriedade oSituacao
   *
   * @return CarteiraIdentificacaoSituacao
   */
  public function getSituacao() {
    
    return $this->oSituacao;
  }

  public function setSituacao(CarteiraIdentificacaoSituacao $oCarteiraIdentificacaoSituacao ) {
    
    $this->oSituacao = $oCarteiraIdentificacaoSituacao;
  }
  
  public function getCodigoLote(){
  	
  	return $this->iLote;
  }
  /**
   * Persistem os dados da carteira do aluno no banco de dados
   */
  public function salvar($iLoteImpressao = null) {
  	
   	$oDaoCarteiraIdentificacao = db_utils::getDao("loteimpressaocartaoidentificacaoaluno");
    $oDaoCarteiraIdentificacao->ed306_cartaoidentificacaosituacao      = $this->getSituacao()->getCodigoSituacao();
    $oDaoCarteiraIdentificacao->ed306_aluno                            = $this->oAluno->getCodigoAluno();
   	
    if (($iLoteImpressao != null && $iLoteImpressao != $this->getCodigoLote() ) ||
        ! $this->getSequencialCarteira() ) {
    	
	    $oDaoCarteiraIdentificacao->ed306_loteimpressaocartaoidentificacao = $iLoteImpressao;
	    $oDaoCarteiraIdentificacao->incluir(null);
	  	if ($oDaoCarteiraIdentificacao->erro_status == 0) {
	  		throw new Exception("Erro ao salvar os dados da carteira do aluno : {$oDaoCarteiraIdentificacao->erro_msg}");  		
	  	}
    } else {
    	
    	$iSequencialCarteira = $this->getSequencialCarteira();
    	$oDaoCarteiraIdentificacao->ed306_sequencial                       = $iSequencialCarteira;
      $oDaoCarteiraIdentificacao->ed306_loteimpressaocartaoidentificacao = $this->getCodigoLote();
      $oDaoCarteiraIdentificacao->alterar($iSequencialCarteira);
      if ($oDaoCarteiraIdentificacao->erro_status == 0) {
        throw new Exception("Erro ao alterar situacao da carteira do aluno : {$oDaoCarteiraIdentificacao->erro_msg}");     
      }
    }
  }
}

?>
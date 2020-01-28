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


require_once(modification('model/configuracao/DBEstruturaValor.model.php'));

class Organograma extends DBEstruturaValor {
	
	
  protected $iCodigoOrganograma;
  
  protected $sAssociado;
  
  protected $sFilhos;
	
	protected $iCodigoDepartamento;
	
	protected $sTipoEstrutural = 'Organograma';
	
	protected $sDescricaoDepartamento;
	
	function __construct($iCodigoOrganograma){
		
		if (! empty($iCodigoOrganograma) ){
			
			$oDaoOrganograma       = db_utils::getDao("db_organograma");
			$sSqlDadosOrganograma  = $oDaoOrganograma->sql_query_conta($iCodigoOrganograma,"*,
			                                                                   (select count(*) 
			                                                                         from 
			                                                                         db_estruturavalor f 
			                                                                    where f.db121_estruturavalorpai = db_estruturavalor.db121_sequencial)
			                                                                    as filhos");
			$rsDadosOrganograma    = $oDaoOrganograma->sql_record($sSqlDadosOrganograma);
			if ($oDaoOrganograma->numrows > 0){
				
				$oDadosOrganograma            = db_utils::fieldsMemory($rsDadosOrganograma, 0);
				$this->iCodigoOrganograma     = $iCodigoOrganograma;
        $this->iCodigoDepartamento    = $oDadosOrganograma->coddepto;        
        $this->sDescricaoDepartamento = $oDadosOrganograma->descrdepto;        
        $this->sDescricaoOrganograma  = $oDadosOrganograma->db122_descricao;
        $this->sAssociado             = $oDadosOrganograma->db122_associado;
        $this->sFilhos                = $oDadosOrganograma->filhos;
				parent::__construct($oDadosOrganograma ->db122_estruturavalor);
				unset($oDadosGrupo);
			}
		}
		$this->tipo = __CLASS__;
	}

	/**
	 * persiste os dados do Organograma
	 *
	 * @return Organograma
	 */
	function salvar(){
		parent::salvar();
		$oDaoOrganograma                        = db_utils::getDao("db_organograma");
		$oDaoOrganograma->db122_estruturavalor  = $this->iCodigo;
    $oDaoOrganograma->db122_depart          = $this->iCodigoDepartamento; 
    $oDaoOrganograma->db122_descricao       = $this->sDescricao;
    $oDaoOrganograma->db122_associado       = $this->sAssociado;
		if (empty($this->iCodigoOrganograma)) {

			$oDaoOrganograma->incluir(null);
			$this->iCodigoOrganograma  = @$oDaoOrganograma->db122_sequencial;

		} else {

			$oDaoOrganograma->db122_sequencial= $this->getCodigoOrganograma();
			$oDaoOrganograma->alterar($this->getCodigo());
		}
		if ($oDaoOrganograma->erro_status == 0) {
			throw new Exception($oDaoOrganograma->erro_msg);
		}
		return $this;
	}

	function __destruct(){

	}
	
	static public function getCodigoByEstrutura($iCodigoEstrutura){
		
		$iCodigoOrganograma = null;
		$oDaoOrganograma    = db_utils::getDao("db_organograma");
		$sSqlCodigo         = $oDaoOrganograma->sql_query_file(null, 'db122_sequencial', null, "db122_estruturavalor={$iCodigoEstrutura}");
		
		$rsCodigo = $oDaoOrganograma->sql_record($sSqlCodigo);
		if ($oDaoOrganograma->numrows > 0){
			$iCodigoOrganograma = db_utils::fieldsMemory($rsCodigo, 0)->db122_sequencial;
		}
		return $iCodigoOrganograma;
	}
	
	/**
	 * Retorna o codigo do Organograma
	 *
	 * @return integer
	 */
  public function getCodigoOrganograma(){
    return $this->iCodigoOrganograma;
  }
  
  /**
   * Define Código do Organograma
   *
   * @param integer $iCodigoOrganograma
   */
  public function setCodigoOrganograma($iCodigoOrganograma){
  	
    $this->iCodigoOrganograma = $iCodigoOrganograma;
    return $this;
  }
  
  /**
   * Define o código do departamento.
   *
   * @param integer $iCodigoDepartamento
   */
  public function setCodigoDepartamento($iCodigoDepartamento){
  	
    $this->iCodigoDepartamento = $iCodigoDepartamento;
    return $this;
  }
  
  /**
   * Retorna o Código do departamento.
   *
   * @return integer
   */
  public function getCodigoDepartamento(){
    return $this->iCodigoDepartamento;
  }
  
	/**
	 * Retorna Código Estrutural
	 *
	 * @return integer
	 */
	public function getCodigoEstrutural(){
		return $this->iCodigo;
	}
	
	public function getDescricaoDepartamento(){
    return $this->sDescricaoDepartamento;
  }  
  
  public function getDescricaoOrganograma(){
    return $this->sDescricaoOrganograma;
  } 
  public function getFilhos(){
    return $this->sFilhos;
  } 
  
  public function getAssociado(){
    return $this->sAssociado;
  }
  public function setAssociado($sAssociado){
    $this->sAssociado = $sAssociado;
    return $this;
  }

  public function setDescricaoDepartamento($sDescricaoDepartamento){
    $this->sDescricaoDepartamento = $sDescricaoDepartamento;
    return $this;
  }

}
?>
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

/**
 * Model para Tipos de assentamentos
 *
 * @package pessoal
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class TipoAssentamento {

	/**
	 * Sequencial do tipo de assentamento
	 * 
	 * @var Integer
	 */
	private $iSequencial;

  /**
   * Código do tipo de assentamento
   *
   * @var String
   */
  private $sCodigo;

  /**
   * Descricao do tipo de assentamento
   *
   * @var String
   */
  private $sDescricao;

  /**
   * Tipo do assentamentos
   *
   * @var String
   */
  private $sTipo;

  private $aAssentamentos;

  /**
   * Natureza
   * @var integer
   */
  private $natureza;

  public function __construct($iSequencial) {

		if ( empty($iSequencial) && $iSequencial != 0) {
			return;
		}

    $oDaoTipoasse        = new cl_tipoasse;
    $rsTipoAssentamento  = db_query($oDaoTipoasse->sql_query($iSequencial, "h12_assent, h12_descr, h12_tipo,h12_natureza"));

    if (!$rsTipoAssentamento) {
      throw new BDException($oDaoTipoasse->erro_msg);
    }

    if (pg_num_rows($rsTipoAssentamento) == 0) {
      throw new Exception("Nenhum Assentamento encontrado para o código informado ({$iSequencial}).");
    }

    $oTipoAssentamento   = db_utils::fieldsMemory($rsTipoAssentamento, 0);

		$this->setSequencial($iSequencial);
    $this->setCodigo($oTipoAssentamento->h12_assent);
    $this->setDescricao($oTipoAssentamento->h12_descr);
    $this->setTipo($oTipoAssentamento->h12_tipo);
    $this->setNatureza($oTipoAssentamento->h12_natureza);
  }

  /**
   * @param $natureza
   */
  public function setNatureza($natureza) {
    $this->natureza = $natureza;
  }

  /**
   * @return int
   */
  public function getNatureza() {
    return $this->natureza;
  }

	/**
	 * Retorna o código do tipo de assentamento
	 * @return Integer
	 */
	public function getSequencial() {
		return $this->iSequencial;
	}

	/**
	 * Define o código do tipo de assentamento
	 * @param Integer $iCodigo
	 */
	public function setSequencial($iSequencial) {
		$this->iSequencial = $iSequencial;
	}

  /**
   * Retorna o código do tipo de assentamento
   * @return String
   */
  public function getCodigo() {
    return $this->sCodigo;
  }

  /**
   * Define o código do tipo de assentamento
   * @param String $iCodigo
   */
  public function setCodigo($sCodigo) {
    $this->sCodigo = (!empty($sCodigo)) ? $sCodigo :  '';
  }

  /**
   * Retorna a descricao do tipo de assentamento
   * @return String
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define a descricao do tipo de assentamento
   * @param String $iCodigo
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = (!empty($sDescricao)) ? $sDescricao :  '';
  }

  /**
   * Retorna o tipo do assentamento
   * @return String
   */
  public function getTipo() {
    return $this->sTipo;
  }

  /**
   * Define o tipo do assentamento
   * A - Afastamento
   * S - Assentamento
   * @param String $sTipo
   */
  public function setTipo($sTipo) {
    $this->sTipo = $sTipo;
  } 

	/**
	 * Persist na base o tipo de assentamento
	 * @return mixed true | String mensagem de erro
	 */
	public function persist() {
		return;
	}

	/**
	 * Transforma o objeto em um formato JSON
	 * @return JSON
	 */
  public function toJSON() {

    $aRetorno["codigo"]            = $this->getSequencial();

    return json_encode((object)$aRetorno);
  }

  /**
   * Retorna os dados financeiros do tipo de assentamento
   * 
   * @return false|String|StdClass   Retorna a linha da tabela cl_tipoassefinanceiro que 
   *                                 vincula um tipo de assentamento a uma rubrica e uma formula
   */
  private function getTipoAssentamentoFinanceiro() {

  	if(empty($this->iSequencial)) {
      return false;
    }

    $oDaoTipoassefinanceiro    = new cl_tipoassefinanceiro;
    $sWhereTipoassefinanceiro  = "     rh165_tipoasse = {$this->iSequencial}";
    $sWhereTipoassefinanceiro .= " and rh165_instit   = ". db_getsession('DB_instit');
    $sSqlTipoassefinanceiro    = $oDaoTipoassefinanceiro->sql_query(null, "*", null, $sWhereTipoassefinanceiro);
    
    try{

      $rsTipoassefinanceiro = db_query($sSqlTipoassefinanceiro);

      if(!$rsTipoassefinanceiro) {
        throw new DBException("Ocorreu um erro ao buscar o tipo de assentamento financeiro.");
      }

      if(pg_num_rows($rsTipoassefinanceiro) == 0) {
        return false;
      }

      return db_utils::fieldsMemory($rsTipoassefinanceiro, 0);

    } catch (Exception $oErro) {
      return $oErro->getMessage();
    }
  }

  /**
   * Retorna a rubrica configurada para o tipo de assentamento
   * 
   * @return false|Rubrica       Retorna false se não encontrar rubrica configurada a Rubrica
   */
  public function getRubricaTipoAssentamentoFinanceiro() {

  	$oStdTipoAssentamentoFinanceiro = $this->getTipoAssentamentoFinanceiro();

    if($oStdTipoAssentamentoFinanceiro instanceof stdClass) {
      return RubricaRepository::getInstanciaByCodigo($oStdTipoAssentamentoFinanceiro->rh165_rubric);
    }

    return false;
  }

  /**
   * Retorna a variável configuarada para o tipo de assentamento
   * 
   * @return false|String       Retorna false se não encontrar varíavel configurada ou a string da variável
   */
  public function getVariavelTipoAssentamentoFinanceiro() {

  	$oStdTipoAssentamentoFinanceiro = $this->getTipoAssentamentoFinanceiro();

    if($oStdTipoAssentamentoFinanceiro instanceof stdClass) {
      return  $oStdTipoAssentamentoFinanceiro->db148_nome;
    }

    return false;
  }

  /**
   * Retorna o tipo de lancamento configurado para o tipo de assentamento
   * 
   * @return false|Integer
   */
  public function getTipoLancamentoTipoAssentamentoFinanceiro() {

  	$oStdTipoAssentamentoFinanceiro = $this->getTipoAssentamentoFinanceiro();

    if(!empty($oStdTipoAssentamentoFinanceiro) ) {
      return  $oStdTipoAssentamentoFinanceiro->rh165_tipolancamento;
    }

  	return false;
  }

  /**
   * @return Assentamento[]
   * @throws DBException
   */
  public function getAssentamentos() {

    if(empty($aAssentamentos)) {

      $oDaoAssenta          = new cl_assenta;
      $sWhereAssentamentos  = " h16_assent = {$this->iSequencial}";
      $sSqlAssentamentos    = $oDaoAssenta->sql_query(null, "h16_codigo", null, $sWhereAssentamentos);
      
      try{

        $rsAssentamentos = db_query($sSqlAssentamentos);

        if(!$rsAssentamentos) {
          throw new DBException("Ocorreu um erro ao buscar os assentamentos para este tipo.");
        }

        $iQtdeAssentamentos = pg_num_rows($rsAssentamentos);
        
        if($iQtdeAssentamentos == 0) {
          return array();
        }

        for ($iIndAssentamentos=0; $iIndAssentamentos < $iQtdeAssentamentos; $iIndAssentamentos++) { 

          $oAssentamento = AssentamentoFactory::getByCodigo(db_utils::fieldsMemory($rsAssentamentos, $iIndAssentamentos)->h16_codigo);
          $this->aAssentamentos[] = $oAssentamento;
        }

      } catch (Exception $oErro) {
        throw new DBException($oErro->getMessage());
      }      
    }

    return $this->aAssentamentos;
  }

  public function getAtributosDinamicos() {

    $aAtributosDinamicos = array();

    $sSqlBuscaAtributos  = 'select db109_sequencial, 
  	        	                     db109_descricao,
  	        	                     db109_nome
                              from tipoassedb_cadattdinamico
 	                                 inner join db_cadattdinamico on h79_db_cadattdinamico = db118_sequencial
 	                                 inner join db_cadattdinamicoatributos on db109_db_cadattdinamico = db118_sequencial
 	                           where h79_tipoasse = '. $this->getSequencial();

    $sIndice = "AtributosDinamicosTipoAssentamento:{$this->getSequencial()}";
    if ( DBRegistry::get($sIndice) ) {
      return DBRegistry::get($sIndice);
    }

    $rsBuscaAtributos = db_query($sSqlBuscaAtributos);

    if(!$rsBuscaAtributos) {
      throw new DBException("Ocorreu um erro ao buscar os atributos dinâmicos para o assentamento.");
    }

    if(pg_num_rows($rsBuscaAtributos) > 0) {

      for ($iIndAtributos = 0; $iIndAtributos < pg_num_rows($rsBuscaAtributos); $iIndAtributos++) {

        $oAtributoDinamico                    = new stdClass;
        $oAtributoDinamico->descricaoAtributo = db_utils::fieldsMemory($rsBuscaAtributos, $iIndAtributos)->db109_descricao;
        $oAtributoDinamico->nomeAtributo      = db_utils::fieldsMemory($rsBuscaAtributos, $iIndAtributos)->db109_nome;
        $oAtributoDinamico->codigoAtributo    = db_utils::fieldsMemory($rsBuscaAtributos, $iIndAtributos)->db109_sequencial;

        $iCodigoAtributo = db_utils::fieldsMemory($rsBuscaAtributos, $iIndAtributos)->db109_sequencial;
        $aAtributosDinamicos[$iCodigoAtributo] = $oAtributoDinamico;
      }
    }

    DBRegistry::add($sIndice, $aAtributosDinamicos);
    return $aAtributosDinamicos;
  }

  public function getAtributoDinamicoPorNome($sNome) {

    $aAtributos = $this->getAtributosDinamicos();
    foreach ($aAtributos as $oAtributo) {
      if ($oAtributo->nomeAtributo == $sNome) {
        return $oAtributo;
      }
    }
  }
}

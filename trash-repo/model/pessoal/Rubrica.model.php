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

/**
 * Classe para manipua��o de rubricas
 *
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @author   Jeferson Rodrigo Prudente Belmiro jeferson.belmiro@dbseller.com.br
 * @package  Pessoal
 * @revision $Author: dbalberto $
 * @version  $Revision: 1.9 $
 */

class Rubrica {
	
  /**
   * C�digo da Rubrica 
   * @var string 
   */
  private $sCodigo;
  
  /**
   * Descri��o da Rubrica
   * @var string
   */
  private $sDescricao;
  
  /**
   * Tipo de Rubrica
   * @var integer
   */
  private $iTipo;
  
  /**
   * Quantidade ou valor da rubrica
   * @var number
   */
  private $nQuantidadeValor;
  
  /**
   * Condi��o da F�rmula 2 
   * @var string
   */
  private $sCondicaoFormula2;
  
  /**
   * Condi��o da F�rmula 3
   * @var string
   */
  private $sCondicaoFormula3;
  
  /**
   * F�rmula para C�lculo do C�digo 1
   * @var integer
   */
  private $sFormulaCalculo;
  
  /**
   * F�rmula para C�lculo do C�digo 2
   * @var integer
   */
  private $sFormulaCalculo2;

  /**
   * F�rmula para C�lculo do C�digo 3
   * @var integer
   */
  private $sFormulaCalculo3;
  
  /**
   * F�rmula para a quantidade. 
   * @var string
   */
  private $sFormulaQuantidade;
  
  /**
   * Indica o tipo de m�dia para f�rias. 
   * @var integer
   */
  private $iMediaFerias;
  
  /**
   * Indica o tipo de m�dia para 13� Sal�rio. 
   * @var integer
   */
  private $iMedia13oSalario;
  
  /**
   * Indica se o c�digo entra para rescis�o.   
   * @var boolean
   */
  private $lEntraParaRescisao;
  
  /**
   * Tipo de inicializacao - 1-Fixa - 2-Vari�vel. 
   * @var integer
   */
  private $iTipoInicializacao;
  
  /**
   * Indica se o c�digo ser� informado com data limite. 
   * @var boolean
   */
  private $lUtilizarDataLimite;
  
  /**
   * Calcula n�mero de presta��es que faltam. 
   * @var boolean
   */
  private $lCalcularPrestacoes;
  
  /**
   * Proporcionaliza o c�digo nos afastamentos. 
   * @var boolean
   */
  private $lProporcionalizarAfastamento;
  
  /**
	 * Grava qtde proporcional nos afastamentos.
	 * Nao pode ser proporcional caso na formula exista uma base.
   * @var boolean
   */  
  private $lProporcionalizarMedias;
  
  /**
   * Calcula ou n�o proporcional em caso de inativo.
   * @var boolean
   */  
  private $lCalcularProporcaoInativos;

  /**
   * Observa��o que aparecer� quando o c�digo for digitado no ponto.
   * @var string
   */
  private $sObservacao;
  
  /**
   * codigo da instituicao 
   * @var integer
   */
  private $iInstituicao;
  
  /**
   * Verifica se o registro est� ativo ou inativo. 
   * @var boolean
   */
  private $lAtivo;
  
  const TIPO_PROVENTO = 1;
  const TIPO_DESCONTO = 2;
  

  private $sTipoEmpenho;

  
  /**
   * Valida se a rubrica e automatica na complementar
   * @var boolean
   */
  private $lAutmomaticaComplentar = false;
  
  /**
   * Valor padr�o da rubrica para inclus�o no ponto
   * @var number
   */
  private $nValorPadrao = 0;
  
  /**
   * Quantidade padr�o da rubrica para inclus�o no ponto
   * @var number
   */
  private $nQuantidadePadrao = 0;

  /**
   * Construtor 
   */
  public function __construct($sCodigo = null, $iInstituicao = null) {
    
    
    if ( !empty($sCodigo) ) {
      
      $this->setCodigo($sCodigo);
      
      $this->setInstituicao  (db_getsession('DB_instit'));
      
      if (!empty($iInstituicao)) {
      	$this->setInstituicao  ($iInstituicao);
      }
      
      $oDaoRubricas = db_utils::getDao('rhrubricas');
      
      $sSqlRubricas = $oDaoRubricas->sql_query_file($this->getCodigo(), $this->getInstituicao());    
      $rsRubricas   = $oDaoRubricas->sql_record($sSqlRubricas);
      
      if ( !$rsRubricas || $oDaoRubricas->numrows == 0 ) {
        throw new DBException("Nenhuma rubrica encontrada para c�digo {$this->getCodigo()}");
      }
      
      $oRubricas = db_utils::fieldsMemory($rsRubricas, 0);
      
      $this->setCodigo                     ((string) $oRubricas->rh27_rubric);
      $this->setDescricao          				 ($oRubricas->rh27_descr );
      $this->setTipo                			 ($oRubricas->rh27_pd    );
      $this->setQuantidadeValor            ($oRubricas->rh27_quant );
      $this->setCondicaoFormula2           ($oRubricas->rh27_cond2 );
      $this->setCondicaoFormula3           ($oRubricas->rh27_cond3 );
      $this->setFormulaCalculo             ($oRubricas->rh27_form  );
      $this->setFormulaCalculo2            ($oRubricas->rh27_form2 );
      $this->setFormulaCalculo3            ($oRubricas->rh27_form3 );
      $this->setFormulaQuantidade          ($oRubricas->rh27_formq );
      $this->setMediaFerias                ($oRubricas->rh27_calc1 );
      $this->setMedia13oSalario            ($oRubricas->rh27_calc2 );
      $this->setEntraParaRescisao          ($oRubricas->rh27_calc3 );
      $this->setTipoInicializacao          ($oRubricas->rh27_tipo  );
      $this->setUtilizarDataLimite         ($oRubricas->rh27_limdat);
      $this->setCalcularPrestacoes         ($oRubricas->rh27_presta);
      $this->setProporcionalizarAfastamento($oRubricas->rh27_calcp );
      $this->setProporcionalizarMedias     ($oRubricas->rh27_propq );
      $this->setCalcularProporcaoInativos  ($oRubricas->rh27_propi );
      $this->setObservacao                 ($oRubricas->rh27_obs   );
      $this->setInstituicao                ($oRubricas->rh27_instit);
      $this->setAtivo                      ($oRubricas->rh27_ativo ); 
      $this->lAutmomaticaComplentar = $oRubricas->rh27_complementarautomatica == 't';
      $this->setValorPadrao                ($oRubricas->rh27_valorpadrao);
      $this->setQuantidadePadrao           ($oRubricas->rh27_quantidadepadrao);
    }
    
  } 
  
  /**
   * retorna o tipo de rubrica
   * @return string
   */
  public function getCodigo() {
    return $this->sCodigo;
  }

  /**
   * Define o tipo de rubrica
   * @param string $sCodigo
   */
  public function setCodigo($sCodigo) {
    $this->sCodigo = $sCodigo;
  }

  /**
   * retorna descricao da rubrica
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Define a descricao da rubrica
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna o tipo de rubrica
   * @return integer
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * Define o tipo de rubrica
   * @param integer $iTipo
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }

  /**
   * Retorna quantidade ou valor da rubrica
   * @return float
   */
  public function getQuantidadeValor() {
    return $this->nQuantidadeValor;
  }

  /**
   * Define quantidade ou valor da rubrica
   * @param float $nQuantidadeValor
   */
  public function setQuantidadeValor($nQuantidadeValor) {
    $this->nQuantidadeValor = $nQuantidadeValor;
  }

  /**
   * Retorna condicao da forma numero 2
   * @return string
   */
  public function getCondicaoFormula2() {
    return $this->sCondicaoFormula2;
  }

  /**
   * Define condicao da formula numero 2
   * @param string $sCondicaoFormula2
   */
  public function setCondicaoFormula2($sCondicaoFormula2) {
    $this->sCondicaoFormula2 = $sCondicaoFormula2;
  }

  /**
   * Retorna condicao formula numero 3
   * @return string
   */
  public function getCondicaoFormula3() {
    return $this->sCondicaoFormula3;
  }

  /**
   * Define condicao formula 3
   * @param string $sCondicaoFormula3
   */
  public function setCondicaoFormula3($sCondicaoFormula3) {
    $this->sCondicaoFormula3 = $sCondicaoFormula3;
  }

  /**
   * Retorna formula do calculo numero 1
   * @return string
   */
  public function getFormulaCalculo() {
    return $this->sFormulaCalculo;
  }

  /**
   * Define formula do calculo numero 1
   * @param string $sFormulaCalculo
   */
  public function setFormulaCalculo($sFormulaCalculo) {
    $this->sFormulaCalculo = $sFormulaCalculo;
  }

  /**
   * Retorna formula calculo numero 2
   * @return string
   */
  public function getFormulaCalculo2() {
    return $this->sFormulaCalculo2;
  }

  /**
   * Define formula do calculo numero 2
   * @param string $sFormulaCalculo2
   */
  public function setFormulaCalculo2($sFormulaCalculo2) {
    $this->sFormulaCalculo2 = $sFormulaCalculo2;
  }

  /**
   * Retorna formula do calculo numero 3
   * @return string
   */
  public function getFormulaCalculo3() {
    return $this->sFormulaCalculo3;
  }

  /**
   * Define formula do calculo numero 3
   * @param string $sFormulaCalculo3
   */
  public function setFormulaCalculo3($sFormulaCalculo3) {
    $this->sFormulaCalculo3 = $sFormulaCalculo3;
  }

  /**
   * Retorna formula da quantidade 
   * @return string
   */
  public function getFormulaQuantidade() {
    return $this->sFormulaQuantidade;
  }

  /**
   * Define formula da quantidade 
   * @param string $sFormulaQuantidade 
   */
  public function setFormulaQuantidade($sFormulaQuantidade) {
    $this->sFormulaQuantidade = $sFormulaQuantidade;
  }

	/**
	 * Retorna media de ferias
	 * @return integer
	 */	 
  public function getMediaFerias() {
    return $this->iMediaFerias;
  }

	/**
	 * Define media de ferias
	 * @param integer $iMediaFerias
	 */	 
  public function setMediaFerias($iMediaFerias) {
    $this->iMediaFerias = $iMediaFerias;
  }

	/**
	 * Retorna media de salario	  
	 * @return integer
	 */	 
  public function getMedia13oSalario() {
    return $this->iMedia13oSalario;
  }

	/**
	 * Define media de salario
	 * @param integer $iMedia13oSalario
	 */	 
  public function setMedia13oSalario($iMedia13oSalario) {
    $this->iMedia13oSalario = $iMedia13oSalario;
	}

	/**
	 * Define se o c�digo entra para rescis�o 
	 * 
	 * @param mixed $lEntraParaRescisao 
	 * @access public
	 * @return void
	 */
	public function setEntraParaRescisao($lEntraParaRescisao) {
		$this->lEntraParaRescisao = $lEntraParaRescisao;
	}

	/**
   * Retorna se o c�digo entra para rescis�o.   
	 * @param boolean $lEntraParaRescisao 
	 * @return boolean
	 */	 
  public function getEntraParaRescisao() {
    return $this->lEntraParaRescisao;
  }

	/**
	 * Retorna o tipo de inicializacao
	 * @return integer 
	 */	 
  public function getTipoInicializacao() {
    return $this->iTipoInicializacao;
  }

	/**
	 * Define o tipo de inicializacao
	 * @param integer $iTipoInicializacao
	 */	 
  public function setTipoInicializacao($iTipoInicializacao) {
    $this->iTipoInicializacao = $iTipoInicializacao;
  }

	/**
   * Indica se ser� utilizado data limite 
	 * @param boolean $lUtilizarDataLimite
	 * @return boolean
	 */	 
  public function utilizarDataLimite() {
    return $this->lUtilizarDataLimite;
  }
  
  
  public function setUtilizarDataLimite($lUtilizarDataLimite) {
    $this->lUtilizarDataLimite = $lUtilizarDataLimite;
  }

	/**
   * Informa se ira calcular n�mero de presta��es que faltam. 
	 * @return boolean
	 */	 
  public function calcularPrestacoes() {
    return $this->lCalcularPrestacoes;
  } 
	
	/**
	 * Define se ira calcular o n�mero de presta��es que faltam 
	 * @param boolean $lCalcularPrestacoes 
	 * @access public
	 * @return void
	 */
	public function setCalcularPrestacoes($lCalcularPrestacoes) {
    $this->lCalcularPrestacoes = $lCalcularPrestacoes;
  }

  /**
   * Informa se ira proporcionalizar afastamento 
   * 
   * @access public
   * @return void
   */
  public function proporcionalizarAfastamento() {
    return $this->lProporcionalizarAfastamento;
  }

  /**
   * Define se ira proporcionalizar afastamento
   * @param boolean $lProporcionalizarAfastamento
   */
  public function setProporcionalizarAfastamento($lProporcionalizarAfastamento) {
    $this->lProporcionalizarAfastamento = $lProporcionalizarAfastamento;
  }

  /**
   * Informa se ira proporcionalizar medias
   * @return boolean
   */
  public function proporcionalizarMedias() {
    return $this->lProporcionalizarMedias;
  }

  /**
   * Define se ira proporcionalizarMedias medias
   * @param boolean $lProporcionalizarMedias
   */
  public function setProporcionalizarMedias($lProporcionalizarMedias) {
    $this->lProporcionalizarMedias = $lProporcionalizarMedias;
  }

  /**
   * Informa se ira calcular proporcao para inativos
   */
  public function calcularProporcaoInativos() {
    return $this->lCalcularProporcaoInativos;
  }

 /**
  * Define se ira calcular proporcao para inativos
  * @param boolean $lCalcularProporcaoInativos
  */
  public function setCalcularProporcaoInativos($lCalcularProporcaoInativos) {
    $this->lCalcularProporcaoInativos = $lCalcularProporcaoInativos;
  }

  /**
   * Retorna observacao da rubrica
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define observa��o da rubrica
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna instituicao
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }

  /**
   * Define instituicao
   * @param integer $iInstituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
  }

  /**
   * Retorna se o registro esta ativo
   * @return boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }

  /**
   * Define se o registro est� ativo
   * @param boolean $lAtivo
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }
  
  /**
   * Retorna o valor padr�o da rubrica
   * @return number
   */
  public function getValorPadrao() {
  	return $this->nValorPadrao;
  }
  
  /**
   * Define o valor padr�o da rubrica
   * @param number $nValorPadrao
   */
  public function setValorPadrao($nValorPadrao) {
  	$this->nValorPadrao = $nValorPadrao;
  }
  
  /**
   * Retorna a quantidade padr�o da rubrica
   * @return number
   */
  public function getQuantidadePadrao() {
  	return $this->nQuantidadePadrao;
  }
  
  /**
   * Define a quantidade padr�o da rubrica
   * @param number $nQuantidadePadrao
   */
  public function setQuantidadePadrao($nQuantidadePadrao) {
  	$this->nQuantidadePadrao = $nQuantidadePadrao;
  }
  
  /**
   * Retorna o Tipo de Empenho para a Rubrica
   * 
   * @access public
   * @return void
   */
  public function getTipoEmpenho() {

    if ( !is_null($this->sTipoEmpenho) ) {
      return $this->sTipoEmpenho;
    }

    $oDaoRHRubElemento = db_utils::getDao("rhrubelemento");
    $sSqlTipoEmpenho   = $oDaoRHRubElemento->sql_query_file($this->getCodigo(), $this->getInstituicao(), 'rh23_codele');
    $rsTipoEmpenho     = db_query($sSqlTipoEmpenho);

    if ( !$rsTipoEmpenho ) {
      throw new DBException("Erro ao Validar dados da Rubrica");
    } 

    if ( pg_num_rows($rsTipoEmpenho) > 0 ) {

      $this->sTipoEmpenho = "e";
      return $this->sTipoEmpenho; //Apenas Elemento
    }

    $sSqlRubricaRetencao = " select e21_retencaotiporecgrupo                                            ";
    $sSqlRubricaRetencao.= "   from rhrubretencao                                                       ";
    $sSqlRubricaRetencao.= "        inner join retencaotiporec on e21_sequencial = rh75_retencaotiporec ";
    $sSqlRubricaRetencao.= "  where rh75_rubric = '{$this->getCodigo()}'                         ";
    $sSqlRubricaRetencao.= "    and rh75_instit = " . $this->getInstituicao();
    $rsRubricasRetencao  = db_query($sSqlRubricaRetencao);
    if ( !$rsRubricasRetencao ) {
      throw new DBException("Erro ao Validar dados de Reten��o da Rubrica");
    }

    if ( pg_num_rows($rsRubricasRetencao) == 0 ) {

      $this->sTipoEmpenho = "";
      return ''; // n�o � elemento nem reten��o da Folha
    }

    $iTipoGrupoRentencaoReceita = db_utils::fieldsMemory( $rsRubricasRetencao, 0 )->e21_retencaotiporecgrupo;

    switch ( $iTipoGrupoRentencaoReceita ) {

      case 1: //| Fornecedor
      default:      // N�o retencao da Folha de Pagamento 
        $this->sTipoEmpenho = "";
      break;
      case 2: //| Folha de Pagamento
        $this->sTipoEmpenho = "r";
      break;
      case 3: //| Pagamento-Extra
        $this->sTipoEmpenho = "p";
      break;
      case 4: //| Devolu��o
        $this->sTipoEmpenho = "d";
      break;
    }
    return $this->sTipoEmpenho;
  }
   
  public function isAutomaticaComplementar() {
    return $this->lAutmomaticaComplentar;
  }
}
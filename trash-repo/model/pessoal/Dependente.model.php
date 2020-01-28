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

require_once 'std/DBDate.php';

/**
 * Model Dependente 
 * 
 * @package pessoal
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br> 
 */
class Dependente {

  const CONJUGE                                        = 'C';
  const FILHO                                          = 'F';
  const PAI                                            = 'P';
  const MAE                                            = 'M';
  const AVO                                            = 'A';
  const OUTROS                                         = 'O';
                                                     
  const CALCULO                                        = 'C';
  const SEMPRE                                         = 'S';
  const NAO_DEPENDENTE                                 = 'N';

  const IRF_NAO_DEPENDENTE                             = 0;
  const IRF_CONJUGE_COMPANHEIRO                        = 1;
  const IRF_FILHOS_ATE_21                              = 2;
  const IRF_FILHO_ENTEADO_ATE_24_ENSINO_SUPERIOR       = 3;
  const IRF_IRMAO_NETO_BISNETO_ATE_21                  = 4;
  const IRF_IRMAO_NETO_BISNETO_21_A_24_ENSINO_SUPERIOR = 5;
  const IRF_PAIS_AVOS_BISAVOS                          = 6;
  const IRF_MENOR_POBRE_ATE_21_GUARDA_JUDICIAL         = 7;
  const IRF_ABSOLUTAMENTE_INCAPAZ                      = 8;

  /**
   * Nome do dependente
   * 
   * @var string
   e @access private
   */
  private $sNome;

  /**
   * Data de nascimento do dependente
   * 
   * @var DBDate
   * @access private
   */
  private $oDataNascimento;

  /**
   * Grau de parentesco
   * C-Conjuge, F-Filho(a), P-Pai, M-Mãe, A-Avó(ô) e O-Outros
   *
   * @var string 
   * @access private
   */
  private $sGrauParentesco;

  /**
   * Salário família
   * C - Cálculo
   * S - Sempre Dependente
   * N - Não Dependente
   * 
   * @var string
   * @access private
   */
  private $sSalarioFamilia;

  /**
   * Tipo de depentente 
   * IRF: 
   * 0 - Não Dependente 
   * 1 - Cônjuge
   * 2 - Filhos até 21 anos
   * 3 - Irmãos, netos até 21 anos
   * 4 - Pais e avós
   * 5 - Absolutamente incapaz
   * 6 - Filhos maiores de 21 anos em curso universitário,
   * 7 - Irmãos, netos maiores de 21 anos em curso  universitário
   * 
   * @var integer
   * @access private
   */
  private $iTipo;

  /**
   * Especial - condição de dependente especial
   * C - Cálculo
   * S - Sempre Dependente
   * N - Não Dependente
   * 
   * @var string
   * @access private
   */
  private $sCondicaoEspecial;

  /**
   * Construtor 
   *
   * @param int $iDependente
   * @access public
   * @return void
   */
  public function __construct($iDependente = 0) {

    if ( empty($iDependente) ) {
      return;
    } 

    $oDaoRhdepend   = db_utils::getDao('rhdepend');
    $sSqlDependente = $oDaoRhdepend->sql_query_file($iDependente);
    $rsDependente   = $oDaoRhdepend->sql_record($sSqlDependente);

    /**
     * Erro no banco 
     */
    if ( $oDaoRhdepend->erro_status == "0" ) {
      throw new DBException($oDaoRhdepend->erro_msg); 
    }

    $oDependente = db_utils::fieldsMemory($rsDependente, 0);

    $this->setCodigo($oDependente->rh31_codigo);
    $this->setNome($oDependente->rh31_nome);
    
    if ( !empty($oDependente->rh31_dtnasc) ) {
	    $this->setDataNascimento(new DBDate($oDependente->rh31_dtnasc));    	
    }
    
    $this->setGrauParentesco($oDependente->rh31_gparen);
    $this->setTipo($oDependente->rh31_irf);
    $this->setSalarioFamilia($oDependente->rh31_depend);
    $this->setCondicaoEspecial($oDependente->rh31_especi);
  }

  /**
   * Define o codigo do dependente
   *
   * @param integer $iCodigo
   * @access public
   * @return void
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retornar condicao especial do dependente
   *
   * @access public
   * @return void
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o nome do depedente 
   *
   * @param string $sNome
   * @access public
   * @return void
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Retorna o nome do dependente
   *
   * @access public
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Defincao a data de nascimento do servidor
   *
   * @param DBDate $oDataNascimento
   * @access public
   * @return void
   */
  public function setDataNascimento(DBDate $oDataNascimento) {
    $this->oDataNascimento = $oDataNascimento;
  }

  /**
   * Retorna data de nascimento do depedente 
   *
   * @access public
   * @return DBDate
   */
  public function getDataNascimento() {
    return $this->oDataNascimento;
  }

  /**
   * Define o grau de parentesco do dependente com o servidor
   *
   * @param string $sGrauParentesco
   * @access public
   * @return void
   */
  public function setGrauParentesco($sGrauParentesco) {
    $this->sGrauParentesco = strtoupper($sGrauParentesco);
  }

  /**
   * Retorna o grau de parentesco do dependente com o servidor
   *
   * @access public
   * @return string
   */
  public function getGrauParentesco() {
    return $this->sGrauParentesco;
  }

  /**
   * Define se é dependente de salario familia
   *
   * @param string $sSalarioFamilia
   * @access public
   * @return void
   */
  public function setSalarioFamilia($sSalarioFamilia) {
    $this->sSalarioFamilia = strtoupper($sSalarioFamilia);
  }

  /**
   * Refine se é dependente de salario familia
   *
   * @access public
   * @return string
   */
  public function getSalarioFamilia() {
    return $this->sSalarioFamilia;
  }

  /**
   * Define o tipo de dependente
   *
   * @param integer $iTipo
   * @access public
   * @return void
   */
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }

  /**
   * Retorna o tipo de dependente
   *
   * @access public
   * @return integer
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * Define se dependente tem condição especial
   *
   * @param string $sCondicaoEspecial
   * @access public
   * @return void
   */
  public function setCondicaoEspecial($sCondicaoEspecial) {
    $this->sCondicaoEspecial = $sCondicaoEspecial;
  }

  /**
   * Define se dependente tem condição especial
   *
   * @access public
   * @return string
   */
  public function getCondicaoEspecial() {
    return $this->sCondicaoEspecial;
  }

}
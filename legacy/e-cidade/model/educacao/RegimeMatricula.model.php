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
 * Model para controle dos regime de matriculas
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package educacao
 */
final class RegimeMatricula {

  /**
   * Codigo do Regime
   * @var integer
   */
  private $iCodigoRegime;

  /**
   * Nome do regimne
   * @var string
   */
  private $sNome;

  /**
   * Abreviatura da descricao do regime
   * @var unknown
   */
  private $sAbreviatura;

  /**
   * o Regime é divido
   * @var integer
   */
  private $lPossuiDivisao = false;

  /**
   * Método construtor
   * @param string $iRegimeMatricula
   */
  public function __construct($iRegimeMatricula = null) {

    if (!empty($iRegimeMatricula)) {

      $oDaoRegime      = db_utils::getDao("regimemat");
      $sSqlDadosRegime = $oDaoRegime->sql_query_file($iRegimeMatricula);
      $rsDadosRegime   = $oDaoRegime->sql_record($sSqlDadosRegime);
      if ($oDaoRegime->numrows > 0) {

        $oDadosRegime = db_utils::fieldsMemory($rsDadosRegime, 0);
        $this->setCodigo($oDadosRegime->ed218_i_codigo);
        $this->setNome(trim($oDadosRegime->ed218_c_nome));
        $this->setAbreviatura(trim($oDadosRegime->ed218_c_abrev));
        $this->lPossuiDivisao = $oDadosRegime->ed218_c_divisao == 'S' ? true : false;
        unset($oDadosRegime);
      }
    }
  }


  /**
   * Retorna o Código do regime
   * Codigo do regime
   * @return integer Codigo do regime
   */
  public function getCodigo() {
    return $this->iCodigoRegime;
  }

  /**
   * Define o Codigo do Regime
   * @param integer $iCodigoRegime Codigo do regime
   */
  private function setCodigo($iCodigoRegime) {
    $this->iCodigoRegime = $iCodigoRegime;
  }

  /**
   * Retorna o nome do Regime
   * Nome Completo do regime
   * @return string Nome do Regime
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Define o Nome do regime
   * @param string $sNomeRegime Nome do regime
   */
  public function setNome($sNomeRegime) {
    $this->sNome = $sNomeRegime;
  }
  /**
   * Retorna o nome abreviado do regime
   * Nome Abreviado do regime
   * @return string Abreviatura do regime
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }

  /**
   * Abreviatura do Regime
   * String com no maximo 10 caracteres para ser utilizado como abreviatura
   * @throws ParameterException
   * @param string $sAbreviatura Texto com a abreviatura
   */
  public function setAbreviatura($sAbreviatura) {

    if (mb_strlen(trim($sAbreviatura)) > 10) {
      throw new ParameterException('Abreviatura não pode ser maior que 10 caracteres.');
    }
    $this->sAbreviatura = $sAbreviatura;
  }

  /**
   * Informa se o regime possui divisoes
   * Retorna true caso exista alguma divisao no regime, false caso nao existir
   * @return boolean
   */
  public function temDivisao () {
    return $this->lPossuiDivisao;
  }
}
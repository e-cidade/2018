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

define("URL_MENSAGEM_AJUDACUSTO", "saude.tfd.AjudaCusto.");
/**
 * Procedimentos que definem uma Ajuda de Custo para o TFD 
 * 
 * @author Andrio Costa    <andrio.costa@dbseller.com.br>
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package tfd
 * @version $Revision: 1.2 $
 */
class AjudaCusto {

  /**
   * Código sequencial
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Procedimento 
   * @var ProcedimentoSaude
   */
  private $oProcedimento;
  
  /**
   * Descrição para ajuda de custo
   * @var string
   */
  private $sDescricao;  
  
  /**
   * Define se a ajuda é sempre faturada com os pedidos de TFD 
   * @var unknown
   */
  private $lFaturaAutomatico = false;

  /**
   * Define se ajuda de custo é somente para acompanhantes
   * @var boolean
   */
  private $lAcompanhate = false; 

  /**
   * Retorna a instancia de uma ajuda de custo
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {
  	
    if (!empty($iCodigo)) {
    	
      $oDaoAjuda = new cl_tfd_ajudacusto();
      $sSqlAjuda = $oDaoAjuda->sql_query_file($iCodigo);
      $rsAjuda   = $oDaoAjuda->sql_record($sSqlAjuda);
      
      if ($oDaoAjuda->numrows == 0) {
        throw new BusinessException(_M(URL_MENSAGEM_AJUDACUSTO."ajuda_custo_nao_encontrado"));
      }

      $oDados = db_utils::fieldsMemory($rsAjuda, 0);

      $this->iCodigo           = $oDados->tf12_i_codigo; 
      $this->oProcedimento     = new ProcedimentoSaude($oDados->tf12_i_procedimento); 
      $this->sDescricao        = $oDados->tf12_descricao;
      $this->lFaturaAutomatico = $oDados->tf12_faturabpa == 't';
      $this->lAcompanhate      = $oDados->tf12_acompanhente == 't';
    }
  }

  /**
   * Getter codigo
   * @return integer
   */
  public function getCodigo () {
    return $this->iCodigo; 
  }
  
  /**
   * Setter descrição informada para ajuda de custo
   * @param string $sDescricao
   */
  public function setDescricao ($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Getter descrição informada para ajuda de custo
   * @return string $sDescricao
   */
  public function getDescricao () {
    return $this->sDescricao; 
  }
  
  /**
   * Setter procedimento da saude
   * @param ProcedimentoSaude $oProcedimento
   */
  public function setProcedimento ($oProcedimento) {
    $this->oProcedimento = $oProcedimento;
  }
  
  /**
   * Getter procedimento da saude
   * @return ProcedimentoSaude $oProcedimento
   */
  public function getProcedimento () {
    return $this->oProcedimento; 
  }

  /**
   * Define se a ajuda de custo é sempre faturada no BPA do TFD
   * @param boolean $lFaturaAutomatico
   */
  public function setFaturaAutomatico ($lFaturaAutomatico) {
    $this->lFaturaAutomatico = $lFaturaAutomatico;
  }
  
  /**
   * Verifica se a ajuda de custo é sempre faturada no BPA do TFD
   * @return boolean $lFaturaAutomatico
   */
  public function isFaturaAutomatico () {
    return $this->lFaturaAutomatico;
  }

  /**
   * Define se é uma ajuda de custo somente para acompanhantes 
   * @param boolean $lAcompanhante
   */
  public function setAcompanhante($lAcompanhante) {
  	
    $this->lAcompanhate = $lAcompanhante;
  }
  
  /**
   * Verifica se o procedimento é somente para acompanhantes
   * @return boolean
   */
  public function isSomenteAcompanhante() {
  	
    return $this->lAcompanhate;
  }
}
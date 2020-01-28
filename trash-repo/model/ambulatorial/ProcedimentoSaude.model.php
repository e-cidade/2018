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

define("URL_MENSAGEM_PROCEDIMENTOSAUDE", "saude.ambulatorial.ProcedimentoSaude.");
/**
 * Procedimentos utilizados na saude
 * @author Andrio Costa    <andrio.costa@dbseller.com.br>
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package ambulatorial
 * @version $Revision: 1.1 $
 */
final class ProcedimentoSaude {
  
  /**
   * Codigo sequencial
   * @var integer
   */
  protected $iCodigo;
  
  /**
   * Código estrutural do procedimento
   * @var string
   */
  protected $sEstrutural;
  
  /**
   * Financimento do procedimento
   * @var FinanciamentoSaude
   */
  protected $oFinanciamento;

  /**
   * Descricao do procedimento
   * @var string
   */
  protected $sDescricao;

  /**
   * Retorna a instancia de um procedimento saude
   * @param string $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {

      $oDaoProcedimento = new cl_sau_procedimento();
      $sSqlProcedimento = $oDaoProcedimento->sql_query_file($iCodigo);
      $rsProcedimento   = $oDaoProcedimento->sql_record($sSqlProcedimento);
      
      if ($oDaoProcedimento->numrows == 0) {
      	throw new BusinessException(_M(URL_MENSAGEM_PROCEDIMENTOSAUDE."procedimento_nao_encontrado"));
      }
      $oDados = db_utils::fieldsMemory($rsProcedimento, 0);
      
      $this->iCodigo        = $oDados->sd63_i_codigo;
      $this->sEstrutural    = $oDados->sd63_c_procedimento;
      $this->sDescricao     = $oDados->sd63_c_nome;
      $this->oFinanciamento = FinanciamentoSaudeRepository::getFinanciamentoSaudeByCodigo($oDados->sd63_i_financiamento);
      
    }
    
  }

  /**
   * Getter código sequencial
   */
  public function getCodigo() {
    
    return $this->iCodigo;
  }

  /**
   * Setter codigo do Estrutural do governo
   * @param String $sEstrutural
   */
  public function setEstrutural ($sEstrutural) {
    $this->sEstrutural = $sEstrutural;
  }
  
  /**
   * Getter codigo do Estrutural do governo
   * @return String
   */
  public function getEstrutural () {
    return $this->sEstrutural; 
  }
  

  /**
   * Setter descrição do procedimento
   * @param string $sDescricao
   */
  public function setDescricao ($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Getter descrição do procedimento
   * @return string $sDescricao
   */
  public function getDescricao () {
    return $this->sDescricao; 
  }


  /**
   * Setter financiamento da saude
   * @param FinanciametoSaude $oFinanciamentoSaude
   */
  public function setFinanciamentoSaude (FinanciametoSaude $oFinanciamentoSaude) {
    $this->oFinanciamento = $oFinanciamentoSaude;
  }
  
  /**
   * Getter financiamento da saude
   * @return FinanciametoSaude $oFinanciamentoSaude
   */
  public function getFinanciamentoSaude () {
    return $this->oFinanciamento; 
  }
  

}
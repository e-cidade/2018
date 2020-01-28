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
 * Financiamentos da Saude
 *
 * @package ambulatorial
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.2 $
 *
 */
class FinanciamentoSaude {
	
  /**
   * Código interdo do financiamento
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Cógido do financiamento importado do governo
   * @var string
   */
  private $sFinanciamento;
  
  /**
   * Descrição do financiamento
   * @var string
   */
  private $sDescricao;
  
  /**
   * Competência do financiamento
   * @var DBCompetencia
   */
  private $oCompetencia;
  
  
  public function __construct($iCodigo) {
  	
    if ($iCodigo !== "") {
    	
      $oDaoFinanciamento = new cl_sau_financiamento();
      $sSqlFinanciamento = $oDaoFinanciamento->sql_query_file($iCodigo);
      $rsFinanciamento   = $oDaoFinanciamento->sql_record($sSqlFinanciamento);
      
      if ($oDaoFinanciamento->numrows == 0) {
        throw new ParameterException(_M("saude.ambulatorial.FinanciamentoSaude.financiamento_nao_encontrado"));
      }
      $oDadosFinanciamento  = db_utils::fieldsMemory($rsFinanciamento, 0);
      $this->iCodigo        = $oDadosFinanciamento->sd65_i_codigo;
      $this->sFinanciamento = $oDadosFinanciamento->sd65_c_financiamento;
      $this->sDescricao     = $oDadosFinanciamento->sd65_c_nome;
      
      /**
       * Caso a competência for 0 (zero), substituimos os valores da competencia por um valor qualquer 
       */
      $iMes = $oDadosFinanciamento->sd65_i_mescomp;
      $iAno = $oDadosFinanciamento->sd65_i_anocomp;
      if ($iCodigo !== "") {
        $iMes = 1;
        $iAno = 1970;
      } 
      $this->oCompetencia = new DBCompetencia($iAno, $iMes);
      
    }
  }
  
  /**
   * Retorna o codigo interno do financiamento
   * @return integer
   */
  public function getCodigo() {
  	
    return $this->iCodigo;
  }
  
  /**
   * Retorna o código do financiamento importado do governo
   * @return string
   */
  public function getFinanciamento() {
  	
    return $this->sFinanciamento;
  }

  /**
   * Retorna a descrição do financiamento
   * @return string
   */
  public function getDescricao() {
  	
    $this->sDescricao;
  }
  
  /**
   * Retonra a competência do financiamento
   * @return DBCompetencia
   */
  public function getCompetencia() {
  	
    $this->oCompetencia;
  }
}
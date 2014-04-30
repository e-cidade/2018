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
 * UPS (Unidade Pronto Socorro)
 *
 * @package ambulatorial
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.1 $
 *
 */
class UnidadeProntoSocorro {
	
  /**
   * Departamento vinculádo
   * @var DBDepartamento
   */
  private $oDepartamento;
  
  /**
   * Código do CNES (Cadastro Nacional de Estabelecimentos de Saúde)
   * @var string 
   */
  private $sCNES;

  /**
   * Código do alvará
   * @var string
   */
  private $sAlvara;

  /**
   * Código do tipo de unidade
   * @var integer
   */
  private $iTipoUnidade;
  
  /**
   * Descrição do tipo de unidade 
   * @var string
   */
  private $sTipoUnidade;
  
  /**
   * Construtor
   * @param integer $iUnidade
   * @throws ParameterException
   */
  public function __construct($iUnidade) {
  	
    if (!empty($iUnidade)) {
      
      $sCampos = "sd02_i_codigo, sd02_v_cnes, sd02_v_num_alvara, sd42_i_tp_unid_id, sd42_v_descricao ";
    	
      $oDaoUnidade = new cl_unidades();
      $sSqlUnidade = $oDaoUnidade->sql_query_model($iUnidade, $sCampos);
      $rsUnidade   = $oDaoUnidade->sql_record($sSqlUnidade);
      
      if ($oDaoUnidade->numrows == 0) {
        throw new ParameterException(_M("saude.ambulatorial.UnidadeProntoSocorro.ups_nao_encontrada"));
      }
      
      $oDadosUnidade = db_utils::fieldsMemory($rsUnidade, 0);
      $this->oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo($oDadosUnidade->sd02_i_codigo);
      $this->sCNES         = $oDadosUnidade->sd02_v_cnes;
      $this->sAlvara       = $oDadosUnidade->sd02_v_num_alvara;
      $this->iTipoUnidade  = $oDadosUnidade->sd42_i_tp_unid_id;
      $this->sTipoUnidade  = $oDadosUnidade->sd42_v_descricao;
      
    }
  } 
  
  /**
   * Retorna o departamento vinculádo
   * @return DBDepartamento
   */
  public function getDepartamento() {
  	
    return $this->oDepartamento;
  }
  
  /**
   * Retorna o código do CNES (Cadastro Nacional de Estabelecimentos de Saúde)
   * @return string
   */
  public function getCNES() {
  	
    return $this->sCNES;
  }
  
  /**
   * Retorna o alvara do estabelecimento
   * @return string
   */
  public function getAlvara() {
  	
    return $this->sAlvara;
  }
  
  /**
   * Retorna a destrição do tipo de unidade
   * @return string
   */
  public function getDescricaoTipoUnidade() {
  	
    return $this->sTipoUnidade;
  }
  
  /**
   * Retorna o códico do tipo de unidade 
   * @return integer
   */
  public function getCodigoTipoUnidade() {
  	
    return $this->iTipoUnidade;
  }
  
  
}
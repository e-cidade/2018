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
 * Arquivo de definição da classe dos dos cartorios utilizados pelo censo escolar,
 * e cadastro dos alunos do e-cidade
 * @since 05/08/2013
 */
/**
 * Classe para definicao dos cartórios Nacionais, de acordo com o censo escolar
 * @author iuri@dbseller.com.br
 * @package Educacao
 * @subpackage censo
 * @version $Revision: 1.1 $
 */
class CensoCartorio {
  
  /**
   * Codigo do cartorio
   * @var integer
   */
  protected $iCodigo;

  /**
   * Nome do cartorio
   * @var string
   */
  protected $sNome;
  
  /**
   * Serventia do cartorio
   * @var string
   */
  protected $sServentia;
  
  /**
   * Municipio do cartório
   * @var CensoMunicipio
   */
  protected $oMunicipio;
  
  
  /**
   * Instância o cartório passado como parâmetro
   * @param integer $iCodigoCartorio Código do cartório
   */
  public function __construct($iCodigoCartorio) {
    
    if (!empty($iCodigoCartorio)) {
      
      if (!DBNumber::isInteger($iCodigoCartorio)) {
        throw new ParameterException('Parâmetro $iCodigo deve ser um inteiro');
      }
      
      $oDaoCensoCartorio = new cl_censocartorio();
      $sSqlCartorio      = $oDaoCensoCartorio->sql_query_file($iCodigoCartorio);
      $rsCartorio        = $oDaoCensoCartorio->sql_record($sSqlCartorio);
      if (!$rsCartorio || $oDaoCensoCartorio->numrows == 0) {
        
        throw new BusinessException(_M('educacao.escola.CensoCartorio.cartorio_nao_encontrado',
                                      (Object)array("codigo_cartorio" => $iCodigoCartorio)
                                      )
                                    );
      }
      $oDadosCartorio   = db_utils::fieldsMemory($rsCartorio, 0);
      $this->oMunicipio = CensoMunicipioRepository::getMunicipioByCodigo($oDadosCartorio->ed291_i_censomunic);
      $this->iCodigo    = $iCodigoCartorio;
      $this->sNome      = $oDadosCartorio->ed291_c_nome;
    }
  }
  
  

  /**
   * Retorna o codigo do cartorio
   * @return integer codigo do cartorio
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Nome do cartorio
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Retorna o municipio do cartorio
   * @return CensoMunicipio
   */
  public function getMunicipio() {
    return $this->oMunicipio;
  }
}
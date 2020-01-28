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
 * Classe para definiзгo e controle dos departamentos.
 * 
 * @package configuraзгo
 * @author Luiz Marcelo Schmitt
 * @version 1.0
 */
class DBDepartamento {

	/**
	 * Cуdigo do departamento.
	 *
	 * @var integer_type $iCodigoDepartamento
	 */
  protected $iCodigoDepartamento;
  
  /**
   * Nome do departamento.
   * 
   * @var string_type $sNomeDepartamento
   */
  protected $sNomeDepartamento;
  
  /**
   * Instituiзгo
   * @var Instituicao
   */
  protected $oInstituicao;
  
  /**
   * Mйtodo construtor da classe.
   *
   * @param integer_type $iCodigoDepartamento
   */
  public function __construct($iCodigoDepartamento) {
    
    if (!empty($iCodigoDepartamento)) {
      
      $oDaoDepartamento      = db_utils::getDao("db_depart");
      $sSqlDadosDepartamento = $oDaoDepartamento->sql_query_file($iCodigoDepartamento);
      $rsDadosDepartamento   = $oDaoDepartamento->sql_record($sSqlDadosDepartamento);
      if ($oDaoDepartamento->numrows > 0) {
        
        $oDadosDepartamento        = db_utils::fieldsMemory($rsDadosDepartamento, 0);
        $this->iCodigoDepartamento = $oDadosDepartamento->coddepto;
        $this->sNomeDepartamento   = $oDadosDepartamento->descrdepto;
        $this->oInstituicao        = InstituicaoRepository::getInstituicaoByCodigo($oDadosDepartamento->instit);
        unset($oDadosDepartamento);
      }
    }
  }
  
  /**
   * Retorna o nome do departamento.
   * @return $this->sNomeDepartamento
   */
  public function getNomeDepartamento() {
    return $this->sNomeDepartamento;
  }
  
  /**
   * Retorna o codigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigoDepartamento;
  }

  /**
   * Retorna a instituiзгo do departamento
   * @return Instituicao
   */
  public function getInstituicao() {
    
    return $this->oInstituicao;
    
  }
}
?>
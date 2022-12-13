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
 * Classe para controle dos municpios do censo
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @version $Revision: 1.1 $
 * @package Educacao
 * @subpackage censo
 */
class CensoMunicipio {

  /**
   * Codigo do municipio
   * @var integer
   */
  protected $iCodigoMunicipio;
  
  /**
   * UF do Municipio
   * @var CensoUF
   */
  protected $oCensoUf;
  
  /**
   * Nome do Municipio
   * @var string
   */
  protected $sNome;
  
  /**
   * Instancia um Municipio
   * @param integer $iCodigoMunicipio codigo do municipio do censo
   */
  public function __construct($iCodigoMunicipio) {
    
    if (!empty($iCodigoMunicipio)) {
      if (!DBNumber::isInteger($iCodigoMunicipio)) {
        throw new ParameterException('Parametro $iCodigoMunicipio deve ser umn inteiro');
      }
      
      $oDaoCensoMunic     = new cl_censomunic();
      $sSqlDadosMunicipio = $oDaoCensoMunic->sql_query_file($iCodigoMunicipio);
      $rsDadosMunicipio   = $oDaoCensoMunic->sql_record($sSqlDadosMunicipio);
      if ($rsDadosMunicipio && $oDaoCensoMunic->numrows == 1) {
        
        $oDadosMunicipio        = db_utils::fieldsMemory($rsDadosMunicipio, 0);
        $this->iCodigoMunicipio = $iCodigoMunicipio;
        $this->sNome            = $oDadosMunicipio->ed261_c_nome;
        $this->oCensoUf         = CensoUFRepository::getEstadoPorCodigo($oDadosMunicipio->ed261_i_censouf);
      }
    }
  }
  
  /**
   * Retorna o codigo do municipio
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigoMunicipio;
  }

  /**
   * Retorna a UF do municipio
   * @return CensoUF
   */
  public function getUF() {
    return $this->oCensoUf;
  }

  /**
   * Retorna o nome do município
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

}
?>
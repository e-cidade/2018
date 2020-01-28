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
 * Forma de retorno das solicitacoes do cidadao
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 * @package social
 * @version $Revision: 1.1 $
 */
class FormaRetorno {
  
  /**
   * Codigo da forma de retorno
   * @var integer
   */
  protected $iCodigoRetorno;
  
  /**
   * descricao da forma de Retorno
   * @var string
   */
  protected $sDescricao;
  
  /**
   * Método Construtor
   * @param integer $iCodigo Código da Forma de Retorno
   */
  function __construct($iCodigo) {

    if (!empty($iCodigo)) {
      
      $oDaoTipoRetorno = db_utils::getDao("tiporetorno");
      $sSqlRetorno     = $oDaoTipoRetorno->sql_query_file($iCodigo);
      $rsTipoRetorno   = $oDaoTipoRetorno->sql_record($sSqlRetorno);
      if ($oDaoTipoRetorno->numrows > 0) {
        
        $oDadosRetorno        = db_utils::fieldsMemory($rsTipoRetorno, 0);
        $this->iCodigoRetorno = $oDadosRetorno->ov22_sequencial;
        $this->sDescricao     = $oDadosRetorno->ov22_descricao;
        unset($oDadosRetorno);
      }
    }
  }
  /**
   * Retorna o codigo da forma de Retorno
   * @return integer
   */
  public function getCodigoRetorno() {
    return $this->iCodigoRetorno;
  }
  
  /**
   * Retorna a descricao da forma de retorno
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
}

?>
<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */
 

 /*
   *     E-cidade Software Publico para Gestao Municipal
   *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
 * classe singleton, para retornar os parametros do protocolo
 */
final class ParametroProtocolo {
  
  const  TRAMITE_PERMITE_ESCOLHER_DEPARTAMENTO           = 1;            
  const  TRAMITE_NAO_PERMITE_ESCOLHER_DEPARAMENTO        = 2;        
  const  TRAMITE_PERMITE_ESCOLHER_DEPARTAMENTO_COM_AVISO = 3; 
    
  /*
   * codigo do parametro p90_traminic Tramite/transferencia
   */
  private $iTipoControleTramite;
  private static $oInstance;
  
  /*
   * construtor da classe recebe a instiruicao para verificar os parametros
   */
  private function __construct( $iInstituicao ) {
    
    if ( !isset($iInstituicao) ) {
      throw new ParameterException("Par�metro institui��o n�o pode ser nulo.");
    }
    $oDaoProtParam = db_utils::getDao("protparam");
    $sSqlProtParam = $oDaoProtParam->sql_query_file (null, "*", null, " p90_instit = {$iInstituicao} ");
    $rsProtParam   = $oDaoProtParam->sql_record($sSqlProtParam);
    if ($oDaoProtParam->numrows == 0) {
      throw new BusinessException("Par�metros do protocolo n�o configurado.");
    }
    $oDadosProtParam = db_utils::fieldsMemory($rsProtParam, 0);
    $this->iTipoControleTramite = $oDadosProtParam->p90_traminic;
  }
  
  private function __clone(){  
  }
  
  /**
   * metodo para verificar parametro do tipo de tramite
   * @param unknown $iInstituicao
   * @return integer iTipoControleTramite
   */
  public static function getFormaDeControleDoDepartamentoNoTramite( $iInstituicao ) {
    
    return self::getInstance($iInstituicao)->iTipoControleTramite;
  }
  
  /**
   * metodo get instance que ira construir ela mesma, para que retorne somente uma instancia da classe
   * @param unknown $iInstituicao
   * @return ParametroProtocolo
   */
  private function getInstance( $iInstituicao ) {
    
    if ( empty(self::$oInstance) ) {
      self::$oInstance = new ParametroProtocolo( $iInstituicao );
    }
    return self::$oInstance;
  }
}
?>
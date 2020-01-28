<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
      throw new ParameterException("Parâmetro instituição não pode ser nulo.");
    }
    $oDaoProtParam = db_utils::getDao("protparam");
    $sSqlProtParam = $oDaoProtParam->sql_query_file (null, "*", null, " p90_instit = {$iInstituicao} ");
    $rsProtParam   = $oDaoProtParam->sql_record($sSqlProtParam);
    if ($oDaoProtParam->numrows == 0) {
      throw new BusinessException("Parâmetros do protocolo não configurado.");
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
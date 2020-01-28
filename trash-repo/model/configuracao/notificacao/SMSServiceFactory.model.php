<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Factory para construcoes de  instacias de operadoras para envio de sms
 * @author Iuri Guntchnigg <iuri@dbseller.com.br>
 *         Robson Inacio   <robson@dbseller.com.br> 
 * @version $Revision: 1.1 $ 
 * @package ecidade
 * @subpackage notificacao
 */
class SMSServiceFactory {
  
  /**
   * Retorna a classe responsavel pelo envio de Mensgame
   * @return ISMSService 
   */
  static function getOperadora($sServiceName) {
     
    switch ($sServiceName) {
      
      case 'convect':
         return new SMSServiceConvect();
        break;
        
        default:
          throw new ParameterException("Operadora {$sServiceName} não Homologada para envio de SMS");
         break; 
    }
    
  }
}

?>
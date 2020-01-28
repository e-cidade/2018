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
 * Factory que retorna a classe de exporta��o do censo
 * @package    Educacao
 * @subpackage Censo
 * @author     fabio.esteves@dbseller.com.br
 *
 */
class ExportacaoCensoFactory {
  
  /**
   * Retorna a instancia da classe de acordo com o ano
   * @param integer $iEscola -> 2012 Censo 2012
   * @return ExportacaoCenso2012
   */
  static function getInstanceByAno ($iAnoCenso) {
    
    switch ($iAnoCenso) {
      
      case '2012':
        
        require_once('ExportacaoCenso2012.model.php');
        return new ExportacaoCenso2012(db_getsession("DB_coddepto"), $iAnoCenso);
        break;
        
      case '2013':
      
        require_once('ExportacaoCenso2013.model.php');
        return new ExportacaoCenso2013(db_getsession("DB_coddepto"), $iAnoCenso);
        break;
      
    }
  }
}
?>
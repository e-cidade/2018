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
* Interface para o sistema de contas
* @author dbseller
* @name ISistemaConta
* @package contabilidade
* @subpackage planoconta
*/

interface ISistemaConta {
  /**
   * M�todo respons�vel pelo comportamento do sistema de conta
   * ao ser vinculado a um plano de conta
   * @param ContaPlano $oContaPlano
   */
  public function integrarDados(ContaPlano $oContaPlano);
  
  /**
  * M�todo respons�vel pela exclus�o do sistema de conta
  * ao ser desvinculado de um plano de conta
  * @param ContaPlano $oContaPlano
  */
  public function excluirDadosIntegrados(ContaPlano $oContaPlano);
  
  /**
   * 
   * Retorna o c�digo do tipo do sistema de conta
   */
  public function getCodigoSistemaConta();
  
  
  /**
   * Retorna o s
   */
  public function getDescricao();
}
?>
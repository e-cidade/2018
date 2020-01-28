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
 * Interface para as classes de exce��o no Processamento de Inconsist�ncias
 * Utilizado para processar dados aonde o procedimento padrao n�o atende
 * @author F�bio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 * @subpackage inconsistencia
 * @version $Revision: 1.2 $
 */
interface IExcecaoProcessamentoDependencias {

  /**
   * metodo para realizar o processamento das execessoes
   * @param integer $iChaveCorreta codigo da chave correta
   * @param integer $iChaveIncorreta codigo da chave que dever� ser excluida/alterada
   * @return boolean
   */
	public function processar($iChaveCorreta, $iChaveIncorreta);

	/**
	 * Retorna a ultima mensagem de erro ocorrida no processamento
	 * @return string Mensagem de erro
	 */
	public function getMensagemErro();
}
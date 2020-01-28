<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Model para geraзгo de arquivos do pit documentos tipo 50
 * @author Iuri Guntchnigg
 * @version $Revision: 1.2 $
 * @package empenho
 */
require_once ('model/arquivoPit.model.php');

final class arquivoPit50 extends arquivoPit {
  
   /**
    * Tipo do documento fiscal
    *
    * @var integer
    */
   protected $iTipoDocumento = 50;
   
   /**
    * Cуdigo do header do  Layout no Sistema
    *
    * @var integer
    */
   protected $iCodigoLayoutHeader = 259;
   
   /**
    * Cуdigo do layout
    *
    * @var integer
    */
   protected $iCodigoLayout = 74;
   
   
   /**
    * Codigo da linha dos registros 
    *
    * @var integer
    */
   protected $iCodigoRegistros = 260;
   
   /**
    * Codigo do arquivo
    *
    * @var integer
    */
   protected $idArquivo  = null;
  /**
   * 
   *@param integer $idArquivo = Cуdigo do arquivo gerado 
   */
  function __construct($idArquivo = null) {

    parent::__construct($idArquivo);
  
  }
  
  /**
   * retorna o codigo do layout do arquivo
   *
   * @return integer codigo do layout
   */
  function getCodigoLayout() {
    return $this->iCodigoLayout;    
  }
  /**
   * 
   */
  function __destruct() {

  }
}

?>
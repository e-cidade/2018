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
 * Model para retornar os dados da empresa para sistemas webservice
 * @author Everton Catto Heckler <everton.heckler@dbseller.com.br>
 * 
 */
class DadosEmpresaWebService extends Empresa {
  
  /**
   * Foto principal
   * @param string
   */
  public function getFotoPrincipal() {

    $iFoto = $this->oCgmEmpresa->getFotoPrincipal();
    
    db_query("begin");
    DBLargeObject::leitura($iFoto, '/tmp/foto_cgm_'.$this->oCgmEmpresa->getCodigo());
    db_query("commit");
   
    return base64_encode(file_get_contents('/tmp/foto_cgm_'.$this->oCgmEmpresa->getCodigo()));
  }
}
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

require_once ('ArquivoSiprevBase.model.php');
require_once ('ArquivoSiprevOrgao.model.php');

class ArquivoSiprevCarreira extends  ArquivoSiprevBase {
  
  protected $sNomeArquivo = "carreiras";
  //protected $sCnpj        = "00711026041";
  
  /*
   * Essa classe não possui um metodo getDados Proprio,
   * para tanto, percorremos o retorno do metodo getDados da classe ArquivoSiprevOrgao
   */
  public function getDados() {
  	
  	$oDadosOrgao                   = new ArquivoSiprevOrgao();
  	$aDadosOrgao                   = $oDadosOrgao->getDados(); 
  	$aDadosCarreira                = array();
  	foreach ($aDadosOrgao as $oIndiceDados => $oValorDados) {
  		
  		$oLinha                      = new stdClass();
      $oLinha->dadosCarreira       = new stdClass();
      $oLinha->orgao               = new stdClass();
      // Dados Carreira
      $oLinha->dadosCarreira->nome = "Servidor Público";
      // Orgao Vinculo
      $oLinha->dadosCarreira->orgao->nome  = $oValorDados->dadosOrgao->nome;
      $oLinha->dadosCarreira->orgao->poder = $oValorDados->dadosOrgao->poder;
       
      $aDadosCarreira[]            = $oLinha;
  	}
  	return $aDadosCarreira;
  	
  }
  
  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão 
   * repassadas para o arquivo que será gerado
   */
  public  function getElementos(){

  	$aDados         = array();
    $aDadosCarreira = array("nome"         => "dadosCarreira",             
                            "propriedades" => Array( "nome",
                                                     Array( "nome"         => "orgao",
                                                            "propriedades" => Array("nome",
                                                                                    "poder"
                                                                                    ) 
                                                           ) 
                                                    )                          
                            );
    $aDados[]       = $aDadosCarreira;    
    return $aDados;    
    	
  }
  
  
  
}  
?>
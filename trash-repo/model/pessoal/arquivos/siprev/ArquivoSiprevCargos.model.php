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

class ArquivoSiprevCargos extends  ArquivoSiprevBase {
  
  protected $sNomeArquivo = "cargos";
  
  
  public function getDados() {
  	
  	
	  $sSqlDados  = " SELECT rh37_funcao,                                     ";
	  $sSqlDados .= "        rh37_descr,                                      "; 
	  $sSqlDados .= "        cgc,                                             ";
	  $sSqlDados .= "        nomeinst,                                        ";
	  $sSqlDados .= "        db21_tipopoder as poder                          "; 
	  $sSqlDados .= " from rhfuncao                                           ";
	  $sSqlDados .= "      inner join db_config on rh37_instit = codigo       "; 
	  $sSqlDados .= " order by rh37_funcao ;                                  "; 	
  
    $rsDados      = db_query($sSqlDados); 
    $aListaDados  = db_utils::getColectionByRecord($rsDados);
    $aDados       = array();  
    foreach ($aListaDados as $oIndiceDados => $oValorDados) {
    	
      $oLinha                                = new stdClass();
      $oLinha->dadosCargo                    = new stdClass();
      $oLinha->orgao                         = new stdClass();
      $oLinha->carreira                      = new stdClass();     
       
      // Dados carreira Vinculo
      $oLinha->dadosCargo->carreira->nome         = "Servidor Público";   
     
     // Dados Cargo
      $oLinha->dadosCargo->nome              = $oValorDados->rh37_descr;
      $oLinha->dadosCargo->cargoAcumulacao   = 1;
      $oLinha->dadosCargo->contagemEspecial  = 1;          
      $oLinha->dadosCargo->tecnicoCientifico = 0; 
      $oLinha->dadosCargo->dedicacaoExclusiva= 0; 
      $oLinha->dadosCargo->aposentadoriaEspecial = 0; 
      
    	// Dados Vinculo
      $oLinha->dadosCargo->carreira->orgao->nome            = $oValorDados->nomeinst;
      $oLinha->dadosCargo->carreira->orgao->nome;
      /*
       * valida poder
       */
      if ($oValorDados->poder == 0) {
      	$sPoder = 3;
      } else {
      	$sPoder = $oValorDados->poder;
      }
      $oLinha->dadosCargo->carreira->orgao->poder          = $sPoder;
      
      $aDados[] = $oLinha;
    }
    return $aDados;
    
  }
  
  /*
   * Esse método é responsável por definir quais os elementos e suas propriedades que serão
   * repassadas para o arquivo que será gerado.
   */    
  public  function getElementos(){


  	$aDados      = array();
    $aDadosCargo = array("nome"         => "dadosCargo",             
                         "propriedades" => Array( "nome",
                                                  "cargoAcumulacao",
                                                  "contagemEspecial",
                                                  "tecnicoCientifico",
                                                  "dedicacaoExclusiva",
                                                  "aposentadoriaEspecial",
                                                  Array( "nome"         => "carreira",
                                                         "propriedades" => Array( "nome",
                                                                                  array("nome"=> "orgao",
                                                                                                 "propriedades"=> array("nome",
                                                                                                                       "poder"
                                                                                                                       )
                                                                                        )
                                                                                 )
                                                        )
                                                 )                          
                            );
                         
    $aDados[]   = $aDadosCargo;   
    
    
    
    return $aDados;    
    
  }    
    
}
?>
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

class ArquivoSiprevOrgao extends  ArquivoSiprevBase {
  
  protected $sNomeArquivo = "orgaos";
  
  public function getDados() {
  	
	  $sSqlDados  = "	  SELECT codigo,                 "; 
	  $sSqlDados .= "          nomeinst,               ";
	  $sSqlDados .= "          cgc,                    ";
	  $sSqlDados .= "          nomeinstabrev,          ";
	  $sSqlDados .= "          db21_esfera,            ";
	  $sSqlDados .= "          db21_tipopoder,         ";
	  $sSqlDados .= "          db21_tipoinstit         ";
	  $sSqlDados .= "   from db_config;                ";
	  
    $rsDados      = db_query($sSqlDados); 
    $aListaDados  = db_utils::getColectionByRecord($rsDados);
    $aDados       = array();  
    foreach ($aListaDados as $oIndiceDados => $oValorDados) {

      $oLinha                                = new stdClass();
      $oLinha->dadosOrgao                    = new stdClass();
      $oLinha->dadosPoder                    = new stdClass();    
      	
      // Dados Orgao
          //valida abreviatura no maximo 10 caracteres
      if (strlen($oValorDados->nomeinstabrev) >10 ) {
      	$sAbreviatura = substr($oValorDados->nomeinstabrev, 0, -1);

      } else {
      	$sAbreviatura = $oValorDados->nomeinstabrev;
      }
          // Valida Tipo de esfera. No sistema temos 0 , porem no manual SIPREV nгo existe 0 entгo atriuimos 3 = Municipal
      if ($oValorDados->db21_esfera == 0) {
      	$sEsfera = 3;
      } else {
      	$sEsfera = $oValorDados->db21_esfera;
      }
          // Valida naturezaJuridica
      if ($oValorDados->db21_tipoinstit > 6) {
      	 $iNaturezaJuridica = 99;   
      } else {
      	 $iNaturezaJuridica = $oValorDados->db21_tipoinstit;
      }           
          
      $oLinha->dadosOrgao->nome              = $oValorDados->nomeinst;
      $oLinha->dadosOrgao->razaoSocial       = $oValorDados->nomeinst;
      $oLinha->dadosOrgao->sigla             = $sAbreviatura;
      $oLinha->dadosOrgao->cnpj              = $oValorDados->cgc;
      $oLinha->dadosOrgao->esfera            = $sEsfera;
      $oLinha->dadosOrgao->naturezaJuridica  = $iNaturezaJuridica;
      
      
      // Dados Poder
          // Verifica se o  codigo e o codigo de seзгo da instituiзгo sгo o mesmo, e atribui а variavel                               
//  echo "\n representante-{$this->cRepresentante}  tipo ato -{$this->iTipoAto}  ano -{$this->iAnoAto}  
//         numero-{$this->iNumeroAto}  data-{$this->dDataAto}  unidade-{$this->iUnidadeGestora} \n";      
      if ( $oValorDados->codigo == $this->iUnidadeGestora ) {

      	$iGestora = 1;                           
        /*
         * Declaracao do objeto, que ira gerar tag tipoAto
         */
        $DataPublicacao = substr($this->dDataAto,6,4).'-'.substr($this->dDataAto,3,2).'-'.substr($this->dDataAto,0,2);
        $oLinha->dadosOrgao->unidadeGestora                               = new stdClass();
        $oLinha->dadosOrgao->unidadeGestora->atoLegal                     = new stdClass(); 
        $oLinha->dadosOrgao->unidadeGestora->atoLegal->tipoAto            = $this->iTipoAto;
        $oLinha->dadosOrgao->unidadeGestora->atoLegal->numero             = $this->iNumeroAto;
        $oLinha->dadosOrgao->unidadeGestora->atoLegal->ano                = $this->iAnoAto;
        $oLinha->dadosOrgao->unidadeGestora->atoLegal->dataInicioVigencia = $DataPublicacao;
        $oLinha->dadosOrgao->unidadeGestora->atoLegal->dataPublicacao     = $DataPublicacao;        
        $oLinha->dadosOrgao->unidadeGestora->representanteLegal           = new stdClass(); 
        $oLinha->dadosOrgao->unidadeGestora->representanteLegal->nome     = $this->cRepresentante;

      } else {                                    
      	$iGestora = 0;                           
      }                                           
      if ($oValorDados->db21_tipopoder == 0) {
      	$sPoder = 3;
      } else {
      	$sPoder = $oValorDados->db21_tipopoder;
      }
      $oLinha->dadosOrgao->poder             = $sPoder;
      $oLinha->dadosOrgao->gestora           = $iGestora;                
      
      $aDados[] = $oLinha;
    }
    return $aDados;
    
  }
  
  /*
   * Esse mйtodo й responsбvel por definir quais os elementos e suas propriedades que serгo
   * repassadas para o arquivo que serб gerado.
   */
  public  function getElementos(){

    $aDadosTipoAto = array( "nome"         => "atoLegal",
                            "propriedades" => array( 
                                                      "tipoAto", 
                                                      "numero", 
                                                      "ano", 
                                                      "dataInicioVigencia", 
                                                      "dataPublicacao" 
                                                   )

                            );
    $aDadosRepresentanteLegal = array( "nome"         => "representanteLegal",
                                       "propriedades" => array( "nome" )
                                     );
    $aDados        = array();
    $aDadosOrgao = array("nome"         => "dadosOrgao",             
                         "propriedades" => Array( "nome",
                                                  "razaoSocial",
                                                  "sigla",
                                                  "cnpj",
                                                  "esfera",
                                                  "poder",
                                                  "gestora",
                                                  "naturezaJuridica",
                                                   array("nome"         => "unidadeGestora",
                                                         "propriedades" =>  array($aDadosTipoAto,
                                                                                  $aDadosRepresentanteLegal
                                                                                 ) 
                                                                                   
                                                        )  
                                                )                                         
                        );
    $aDados[]     = $aDadosOrgao;   
                         
    return $aDados;    
  	
  }

}

?>